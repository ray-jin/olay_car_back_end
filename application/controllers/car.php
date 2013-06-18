<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Car extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->load->model('cars');
                $this->load->model('makes');
                $this->load->model('models');
                $this->load->model('saved_searches');
                $this->load->helper('url');
                $this->load->helper('email');
                $this->load->model('manage_m'); // a little different
	}
	
	function index()
	{
		echo "Invalid Access!";
	}
        
        
        /*
         * @uid : user id
         * @sid : session id
         * @ufuname : reg
         * @c_car : price
         * @a_me : userfile[5]: array         
         */
	function add_listing() {
             
           $config['upload_path'] = UPLOAD_PATH; 
           
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size']	= $this->config->item('max_img_size'); //2MB
                        
            $this->load->library('upload', $config);
            //check whether valid session
            $uid=$_POST['uid'];
            $sid=$_POST['sid'];
            
            if (!is_numeric ($uid) || !$this->tank_auth->is_valid_session($uid,$sid)){
                $result['cid'] = $this->config->item('fail');
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_session');
                echo json_encode($result);
                return;
            }
            $auser= $this->users->get_user_by_id($uid, true);
        
            $tbl_name = "cars";
            $new_idx = $this->manage_m->get_next_insert_idx($tbl_name);
            $fnames=$this->upload->do_multi_upload_car($auser->username,$new_idx);
            
            if (is_array($fnames))
            {
                $registration=$_POST['registration'];
                $postcode=$_POST['postcode'];
                $price=$_POST['price'];                 
 
                $result['cid'] = $this->cars->create_car($uid,$registration,$price,$fnames,$auser->username,$new_idx,$postcode);                

                if ($result['cid']!=-1){
                    $result['status'] = $this->config->item('success');
                }
                else{
                    $data['show_errors'] = "Error occured";
                }
            }
            else{
                 $error = array('error' => $this->upload->display_errors("",""));                    
                 $result['status'] = $this->config->item('fail');
                 $result['error'] = $error['error'];
            }
           
         
            echo json_encode($result);
	}
        
         /*
         * @uid : user id
         * @sid : session id
         * @cid : car id
         * @make : manufactuer
         * model : model
         * year : year
          * @f_type : fuel_type
          * @trans : transmission
          * @m_age : mileage
          * @desc : description         
         */
	function update_car2() {
            
            if (!isset($_REQUEST['uid']) || !isset($_REQUEST['sid']) || !isset($_REQUEST['cid']) )
            {
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            } 
            
            $uid=$_REQUEST['uid'];
            $sid=$_REQUEST['sid'];
            $cid=$_REQUEST['cid'];
                        
            if (!is_numeric ($uid) || !$this->tank_auth->is_valid_session($uid,$sid)){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_session');
                echo json_encode($result);
                return;
            }
            
            $this->load->library('upload');
            $this->upload->set_allowed_types('*');
            $this->upload->set_upload_path(UPLOAD_PATH);
            $this->upload->set_max_filesize($this->config->item('max_img_size'));
			
            $auser= $this->users->get_user_by_id($uid);
            $fnames=array("","","","","","","");
            $img_del=array();

            if ( $this->upload->do_car_file_upload($auser->username,$cid,"file_url_1"))
            {
                 $fnames[1]=$this->upload->file_name;
                 $img_del[1]=true;
            }  
            if ( $this->upload->do_car_file_upload($auser->username,$cid,"file_url_2"))
            {
                 $fnames[2]=$this->upload->file_name;
                 $img_del[2]=true;
            }
            if ( $this->upload->do_car_file_upload($auser->username,$cid,"file_url_3"))
            {
                 $fnames[3]=$this->upload->file_name;

            }
            if ( $this->upload->do_car_file_upload($auser->username,$cid,"file_url_4"))
            {
                 $fnames[4]=$this->upload->file_name;

            }
            if ( $this->upload->do_car_file_upload($auser->username,$cid,"file_url_5"))
            {
                 $fnames[5]=$this->upload->file_name;

            }
            if (is_array($fnames)){                               
                if ($this->cars->update_car2($cid,$_REQUEST['make']
                        ,$_REQUEST['model'],$_REQUEST['year'],$_REQUEST['fuel_type'],$_REQUEST['transmission']
                        ,$_REQUEST['mileage'],$_REQUEST['desc'], $fnames,$auser->username,"1",$_REQUEST['postcode']
                        ,$_REQUEST['registration'],$_REQUEST['price'])){
                    //redirect("admin/car");                                                
                    $result['status'] = $this->config->item('success');
                }
                else{
                    $result['status'] = $this->config->item('fail');
                    $result['error'] = "Updating error";
                }
            }
            else{
                 $result['status'] = $this->config->item('fail');
                 $result['error'] = "File uploading error";
            }

           
            echo json_encode($result);
	}
	/*
         * @uid : user id
         * @sid : session id
         * @cid : car id  
         */
         function remove_car() {    	
            if (!isset($_REQUEST['uid']) || !isset($_REQUEST['sid']) || !isset($_REQUEST['cid']) )
            {                
                $result['status'] = $this->config->item('fail');               
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            } 
            
            $uid=$_REQUEST['uid'];
            $sid=$_REQUEST['sid'];
            $cid=$_REQUEST['cid'];
            
            if (!is_numeric ($uid) || !$this->tank_auth->is_valid_session($uid,$sid)){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_session');
                echo json_encode($result);
                return;
            }
            
            $car=$this->cars->get_car_by_id($cid);
            if ( ($car==null) || $car->uid!=$uid)  {
                $result['status'] = $this->config->item('fail');
                $result['error'] = "Car doesn't exist or not correct owner";
                echo json_encode($result);
                return;
            }
            $this->cars->delete_car($cid);
            $result['status'] = $this->config->item('success');              

            echo json_encode($result);
				
        }
        
        /*
         * 
         */
         public function check_car_session($is_echo=true) {    	
            if (!isset($_REQUEST['uid']) || !isset($_REQUEST['sid'])  )
            {
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                if ($is_echo==true) echo json_encode($result);
                return false;
            } 
            
            $uid=$_REQUEST['uid'];
            $sid=$_REQUEST['sid'];
            
            if (!is_numeric ($uid) || !$this->tank_auth->is_valid_session($uid,$sid)){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_session');
                if ($is_echo==true) echo json_encode($result);
                return false;                
            }
            return true;            
				
        }
        
        /*
         * @uid : user id
         * @sid : session id
         * @cid : car id  
         */
         function add_watch_car() {    	
            
             if (!$this->check_car_session())
                return;
            
             if( !isset($_REQUEST['cid'])){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
                
            $uid=$_REQUEST['uid'];
            $cid=$_REQUEST['cid'];
            
            $car=$this->cars->get_car_by_id($cid);
            if ( $car==null )   {
                $result['status'] = $this->config->item('fail');
                $result['error'] = "Car doesn't exist";
                echo json_encode($result);
                return;
            }
            
            $watch_car_id=$this->cars->add_watch_car($cid,$uid);
            if ($watch_car_id==-1){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('failed');
            }
            else{
                $result['status'] = $this->config->item('success');
                $result['wc_id'] = "$watch_car_id";
            }
            
            echo json_encode($result);				
        }
        
         /*
         * @uid : user id
         * @sid : session id         
         */
         function list_watch_car() {    	
            
             if (!$this->check_car_session())
                return;
            
            $uid=$_REQUEST['uid'];
     
            $result['status'] = $this->config->item('success');
            $result['cars']= $this->cars->list_watch_car($uid);
            
            echo json_encode($result);	
            
        }
        
         /*
         * @uid : user id
         * @sid : session id
         * @cid : car id  
         */
         function remove_watch_car() {    	
            
             if (!$this->check_car_session())
                return;
            
             if( !isset($_REQUEST['cid'])){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
                
            $uid=$_REQUEST['uid'];
            $cid=$_REQUEST['cid'];
            $this->cars->remove_watch_car($cid,$uid);
            
            $result['status'] = $this->config->item('success');
            echo json_encode($result);				
        }
        
         /*
         * @number : page number
         * @offset : offset number
         * @showall : 1 -> by any user, 0 -> by this user only, default is 1
         */
         function list_latest_cars() {    	
            
           
             if( !isset($_REQUEST['number']) || !isset($_REQUEST['offset']) ){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
                       
            $result['status'] = $this->config->item('success');
            $number=$_REQUEST['number'];
            $offset=$_REQUEST['offset'];
            $showall=1; //by default show all cars for all users 
            $uid="1"; //it's meaning less parameter
            
            $result['cars']= $this->cars->list_latest_cars($uid,$number,$offset,$showall);
            
            echo json_encode($result);	            
        }
        
         /*
         *
         * @number : number of results         
         * @offset : starting index
         */
         function list_popular_cars() {    	
            
            if( !isset($_REQUEST['number']) || !isset($_REQUEST['offset']) ){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
           
            $number=$_REQUEST['number'];
            $offset=$_REQUEST['offset'];            
            
            $result['status'] = $this->config->item('success');
            $result['cars']= $this->cars->list_popular_cars($number, $offset);
            
            echo json_encode($result);	            
        }
        
          /*         
           * @uid : user id
         * @sid : session id
         * @number : car id         
         * increase number of visitors for this specific car.
         */
         function get_car_detail() {    	
            if (!$this->check_car_session())
                return;
            if( !isset($_REQUEST['cid'])){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            $cid=$_REQUEST['cid']; $uid=$_REQUEST['uid'];
            
            $acar= $this->cars->get_car_by_id($cid);
            
            if ($acar==null){
                $result['status'] = $this->config->item('fail');
                $result['error'] = "Car does not exist.";
                echo json_encode($result);
                return;
            }
            
            if ($acar->uid!=$uid)
                $this->cars->inc_num_visitors($cid,$acar->num_visitors+1);
            //incrase the number of visitors of the car
            
            $result['price']=$acar->price;
            $result['make']=$acar->make;
            $result['model']=$acar->model;
            $result['mileage']=$acar->mileage;
            $result['year']=$acar->year;
            $result['transmission']=$acar->transmission;
            if ($acar->file_url_1!="")
                $result['file_url_1']=HOST.UPLOAD_PATH.$acar->file_url_1;
            if ($acar->file_url_2!="")
                $result['file_url_2']=HOST.UPLOAD_PATH.$acar->file_url_2;
            if ($acar->file_url_3!="")
                $result['file_url_3']=HOST.UPLOAD_PATH.$acar->file_url_3;
            if ($acar->file_url_4!="")
                $result['file_url_4']=HOST.UPLOAD_PATH.$acar->file_url_4;
            if ($acar->file_url_5!="")
                $result['file_url_5']=HOST.UPLOAD_PATH.$acar->file_url_5;
                
            echo json_encode($result);
        }
        
         /*
         
          * @make : car make
          * @model : model
          * @price : price
          * @p_code : post code of current location
          * @radius : radius within the p_code
         * @number : number of results
         * @showall : 1 -> by any user, 0 -> by this user only, default is 1
         */
         function a_search() {    	// advanced search
            
             $is_admin=false;
             if ($this->check_car_session(false)==true &&  $_REQUEST['uid']==ADMIN_USER_ID){                 
                    $is_admin=true;
             }
                
            if( !isset($_REQUEST['number'])){
                $result['status'] =$this->config->item('fail');          
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            
            $number=$_REQUEST['number'];
            $offset = (isset($_REQUEST['offset'])) ? $_REQUEST['offset'] : "";
            $make = (isset($_REQUEST['make'])) ? $_REQUEST['make'] : "";
            $model = (isset($_REQUEST['model'])) ? $_REQUEST['model'] : "";
            $s_price = (isset($_REQUEST['s_price'])) ? $_REQUEST['s_price'] : "";
            $e_price= (isset($_REQUEST['e_price'])) ? $_REQUEST['e_price'] : "";
            $p_code= (isset($_REQUEST['p_code'])) ? $_REQUEST['p_code'] : "";
            $radius= (isset($_REQUEST['radius'])) ? $_REQUEST['radius'] : 9999999999;
            //$loc=$_REQUEST['loc'];            
            $pcode_obj=null;
            if ($p_code!=""){
                $pcode_obj=$this->cars->get_post_code_obj($p_code);         
                if ($pcode_obj==null){
                    $result['status'] =$this->config->item('fail');          
                    $result['error'] = "Postcode is not correct";
                    echo json_encode($result);
                    return;
                }
                    
            }
            
            $result['status'] = $this->config->item('success');
            $result['cars']= $this->cars->a_search_list($make,$model,$s_price,$e_price,$pcode_obj,$radius,$number,$offset,$is_admin);
            
            echo json_encode($result);	
            
        }
        
         /*
         * @uid : user id
         * @sid : session id
         * @cid : car id  
          * @comment : comment text
         */
         function add_comment_car() {    	
            
             if (!$this->check_car_session())
                return;
            
             if( !isset($_REQUEST['cid']) || !isset($_REQUEST['comment'])){
                $result['status'] = $this->config->item('fail');              
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
                
            $uid=$_REQUEST['uid'];
            $cid=$_REQUEST['cid'];
            $comment=$_REQUEST['comment'];
            
            $car=$this->cars->get_car_by_id($cid);            
            if ( $car==null )   {
                $result['status'] = $this->config->item('fail');
                $result['error'] = "Car doesn't exist";
                echo json_encode($result);
                return;
            }
            
            $comm_id=$this->cars->add_comment_car($cid,$uid,$comment);
            
            $result['status'] = $this->config->item('success');
            $result['comm_id'] = $comm_id;
            
            echo json_encode($result);				
        }
        
         /*
         * @uid : user id
         * @sid : session id  
         * @cid : car id      
         */
         function list_comment_car() {    	
            
             if (!$this->check_car_session())
                return;
             
             if( !isset($_REQUEST['cid']) || $_REQUEST['cid']==""){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            $uid=$_REQUEST['uid']; $cid=$_REQUEST['cid'];
            
            
            if( !isset($_REQUEST['number']) || $_REQUEST['number']==""){
                $result['number'] = "-1";
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            if( !isset($_REQUEST['offset']) || $_REQUEST['offset']==""){
                $result['offset'] = "-1";
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            $number=$_REQUEST['number'];
            $offset=$_REQUEST['offset'];
            
            $result['status'] = $this->config->item('success');
            $result['comments']= $this->cars->list_comment_car($cid, $number, $offset);
            
            
            echo json_encode($result);            
        }
         
        /*
         * @uid : user id
         * @sid : session id
         * @comm_id : comment id  
         */
         function remove_comment_car() {    	
            
             if (!$this->check_car_session())
                return;
            
             if( !isset($_REQUEST['comm_id'])){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
                
            $uid=$_REQUEST['uid'];
            $comm_id=$_REQUEST['comm_id'];             
            
            $comment=$this->cars->get_comment_by_id($comm_id);
            if ( ($comment==null) || $comment->uid!=$uid)  {
                $result['status'] = $this->config->item('fail');
                $result['error'] = "Comment doesn't exist or not correct owner";
                echo json_encode($result);
                return;
            }
            
            $this->cars->remove_comment_car_by_id($comm_id);         
            
            $result['status'] = $this->config->item('success');
            echo json_encode($result);				
        }
        
         /*
         * @uid : user id, as sender user id
         * @sid : session id
         * @r_id : receiver id  
          * @msg : message text
          * It will also send an email to receiver's mail address
         */
         function send_message_car() {    	
            
             if (!$this->check_car_session())
                return;
            
             if( !isset($_REQUEST['r_id']) || !isset($_REQUEST['msg'])){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
          
            
            $uid=$_REQUEST['uid']; $r_id=$_REQUEST['r_id'];
            
            $receiver_user= $this->users->get_user_by_id($r_id,true);
            $sender_user= $this->users->get_user_by_id($uid,true);
            
            if ($receiver_user==null || $sender_user==null){
                $result['status'] = $this->config->item('fail');
                $result['error'] = "Receiver or Sender does not exist";
                echo json_encode($result);
                return;
            }
                            
            $inbox_id=$this->cars->add_message_car($_REQUEST['uid'],$_REQUEST['r_id'],$_REQUEST['msg'],"inbox");
            $outbox_id=$this->cars->add_message_car($_REQUEST['uid'],$_REQUEST['r_id'],$_REQUEST['msg'],"outbox");
            
            $result['status'] = $this->config->item('success');             
            $result['outbox_id'] = $outbox_id;
            
            //********************send mail function*******************
          
           /*  $headers = 'From: '.$sender_user->email. "\r\n" .
            'Reply-To: iwantacar@rayjin.mailgun.org' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
            
            $okmail=send_email($receiver_user->email, "Message from(".$sender_user->email.")", $_REQUEST['msg'],$headers);
                        
            $result["mailsent"]=$okmail;*/
            
            
            
             $ch = curl_init();
               
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, 'api:key-0iizmqtrsytx5o59xvq9pksy5jvdvey7');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v2/rayjin.mailgun.org/messages');
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('from' => 'iwantacar@rayjin.mailgun.org',//$sender_user->email,
                                                        'to' => $receiver_user->email,
                                                        'Reply-To' => 'iwantacar@rayjin.mailgun.org',
                                                        'sender' => $receiver_user->email,
                                                        'subject' => "Message from(".$sender_user->email.")",
                                                        'text' => $_REQUEST['msg']));
             

            $okmail= curl_exec($ch);
            if( ! $okmail) 
            { 
               // trigger_error(curl_error($ch)); 
                $result["mailsent"]=false;
            } 
            else{
                $result["mailsent"]=true;
            }
            curl_close($ch);
          
            echo json_encode($result);				
        }
        
        /*
         * subject contains receiver email
         */
        function receive_message_car_from_mailgun_mime(){
           
           /* $subject="Message from(ray_jin@gmail.com)";
            $start=  strripos($subject, "(");
            $end=  strripos($subject, ")");
            $mail=  substr($subject, $start+1, $end-$start-1);
            echo $mail;*/
           
         //  $sender_mail="abc";//$_REQUEST['sender'];
          // $subject="subject";//$_REQUEST['subject'];
           
           //$start=  strripos($subject, "(");
           //$end=  strripos($subject, ")");
           //$receiver_mail="aaa";//  substr($subject, $start+1, $end-$start-1);
           
         //  $message="message";//Reply by ".$sender_mail."\r\n".$_REQUEST['stripped-text'];
           
           //$sender=$this->users->get_user_by_email($sender_mail);
           //$receiver=$this->users->get_user_by_email($receiver_mail);
           
           //$msg_id=$this->cars->add_message_car($sender->id,$receiver->id,$message);
           //$msg_id=$this->cars->add_message_car(4,5,$message);
           //echo $msg_id; /// Newly created message
           //return 6;
               $headers = 'From: nice.ray.jin@hotmail.com'. "\r\n" .
            'X-Mailer: PHP/' . phpversion();
    

        $okmail=mail("nice.ray.jin@gmail.com", $_REQUEST['sender'], $_REQUEST['stripped-text'], $headers);
        //echo $okmail;
        }
        
        /*
         * @uid : user id
         * @sid : session id  
         * @s_id : sender id //-1: for all senders
         * @r_id : receiver id // -1: for all receivers
         * @number : number of messages
         * @offset : offset of messages
         */
         function list_message_car() {    	
            
             if (!$this->check_car_session())
                return;
              $result['status'] = $this->config->item('fail');
             if( !isset($_REQUEST['s_id']) || $_REQUEST['s_id']=="" || 
                     !isset($_REQUEST['r_id']) || $_REQUEST['r_id']=="" || 
                     !isset($_REQUEST['type'])    ){
               
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
                                    
            if ( $_REQUEST['type']!="inbox" && $_REQUEST['type']!="outbox"){
            
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
                    
            $uid=$_REQUEST['uid']; $r_id=$_REQUEST['r_id']; $s_id=$_REQUEST['s_id']; $type=$_REQUEST['type']; 
            
            if ($type=="inbox" && $uid!=$r_id){
                $result['error'] = "Receiver should be current user";
                echo json_encode($result);
                return;
            }
            
            if ($type=="outbox" && $uid!=$s_id){
                $result['error'] = "Sender should be current user";
                echo json_encode($result);
                return;
            }
                            
            if( !isset($_REQUEST['number']) || $_REQUEST['number']==""){                
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            if( !isset($_REQUEST['offset']) || $_REQUEST['offset']==""){                
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            $result['status'] =$this->config->item('success');
            $number=$_REQUEST['number'];
            $offset=$_REQUEST['offset'];
            
            $result['msgs']= $this->cars->list_message_car($s_id, $r_id, $number, $offset, $type);
            
            echo json_encode($result);            
        }
        
        /*
         * @uid : user id
         * @sid : session id
         * @msg_id : message id  
         * @type : inbox, outbox
         */
         function remove_message_car() {    	
            
             if (!$this->check_car_session())
                return;
            
             if( !isset($_REQUEST['msg_id']) || !isset($_REQUEST['type']) ||  $_REQUEST['type']=="" ){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            if ( $_REQUEST['type']!="inbox" && $_REQUEST['type']!="outbox"){
            
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
                
            $msg_id=$_REQUEST['msg_id']; $uid=$_REQUEST['uid']; $type=$_REQUEST['type'];            
            $msg=$this->cars->get_message_by_id($msg_id,$type);
            
            if ( $msg==null){
                $result['status'] = $this->config->item('fail');
                $result['error'] = "Message doesn't exist.";
                echo json_encode($result);
                return;
            }
            
            if ( $type=="inbox" && $uid!=$msg->receiver_id ){
                $result['status'] = $this->config->item('fail');
                $result['error'] = "Receiver should be current user.";
                echo json_encode($result);
                return;
            }
            
            if ( $type=="outbox" && $uid!=$msg->sender_id ){
                $result['status'] = $this->config->item('fail');
                $result['error'] = "Receiver should be current user.";
                echo json_encode($result);
                return;
            }
            
            $this->cars->remove_message_car_by_id($msg_id,$type);
            
            $result['status'] = $this->config->item('success');
            echo json_encode($result);
        }
        
        /*
         * @uid : user id, as sender user id
         * @sid : session id
         * @r_id : receiver id  
         * @price : price offered by @uid
         * @msg : message text :(optional)
         */
         function add_offer_car() {    	
            
             if (!$this->check_car_session())
                return;
            
             if( !isset($_REQUEST['cid']) || !isset($_REQUEST['price'])){                
                $result['status'] = $this->config->item('success');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            if (!isset($_REQUEST['msg'])) 
                $msg="";            
            else
                $msg=$_REQUEST['msg'];
            $uid=$_REQUEST['uid']; $cid=$_REQUEST['cid'];
            
            $car=$this->cars->get_car_by_id($cid);
            if ( $car==null )   {
                $result['status'] = $this->config->item('fail');
                $result['error'] = "Car doesn't exist";
                echo json_encode($result);
                return;
            }
            
            $offer_id=$this->cars->add_offer_car($uid,$cid,$_REQUEST['price'],$msg);
            
            $result['status'] = $this->config->item('success');
            $result['offer_id'] = $offer_id;
            echo json_encode($result);				
        }
        
         /*
         * @uid : user id
         * @sid : session id  
         * @cid : car id          
         * @number : number of messages
         * @offset : offset of messages
         */
         function list_offer_car() {    	
            
             if (!$this->check_car_session())
                return;
             $result['status'] = $this->config->item('fail');
             
             if( !isset($_REQUEST['cid']) || $_REQUEST['cid']=="" || !isset($_REQUEST['o_id']) || $_REQUEST['o_id']==""){                
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            $uid=$_REQUEST['uid']; $cid=$_REQUEST['cid']; $o_id=$_REQUEST['o_id']; 
            // $cid==-1 -> search by user $uid -> Not -1
            // $cid!=-1 -> search by car and s_id=uid
            
            if ($cid==-1 && $o_id!=$uid ){            
                $result['error'] = "Offering user should be current user.";
                echo json_encode($result);
                return;
            }                        
            
            if( !isset($_REQUEST['number']) || $_REQUEST['number']==""){
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            if( !isset($_REQUEST['offset']) || $_REQUEST['offset']==""){                
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            $number=$_REQUEST['number'];
            $offset=$_REQUEST['offset'];
            
            $result['status'] = $this->config->item('success');
            $result['offers']= $this->cars->list_offer_car($o_id, $cid, $number, $offset);
            
            echo json_encode($result);            
        }
        
         /*
         * @uid : user id
         * @sid : session id
         * @offer_id : offer id  
         */
         function remove_offer_car() {    	
            
             if (!$this->check_car_session())
                return;
            
             if( !isset($_REQUEST['offer_id'])){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
                
            $offer_id=$_REQUEST['offer_id']; $uid=$_REQUEST['uid'];
                        
            $offer=$this->cars->get_offer_by_id($offer_id);
            if ( ($offer==null) || $offer->uid!=$uid)  {
                $result['status'] = $this->config->item('fail');
                $result['error'] = "Offer doesn't exist or not correct owner";
                echo json_encode($result);
                return;
            }
            
            $this->cars->remove_offer_car_by_id($offer_id);
            
            $result['status'] =$this->config->item('success');
            echo json_encode($result);
        }
        
         /*
         * @uid : user id
         * @sid : session id
         * @pcodeA : pcodeA : should be 3 letters or 4 letters
         * @pcodeB : pcodeB
         * return the distance
         */
         function calc_postcode_distance() {    	
            
             if (!$this->check_car_session())
                return;
            
             if( !isset($_REQUEST['pcodeA']) || !isset($_REQUEST['pcodeB'])){
                $result['status'] =$this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            $result['status'] =$this->config->item('success');
            $uid=$_REQUEST['uid'];
            $pcodeA=$_REQUEST['pcodeA']; $pcodeB=$_REQUEST['pcodeB'];
            $distance=$this->cars->calc_postcode_distance($pcodeA,$pcodeB);
            
            $result['uid'] = $uid;
            $result['distance'] = $distance;
            
            echo json_encode($result);
            return $distance;
        }
        
        
        
        
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */


//Watch list completed