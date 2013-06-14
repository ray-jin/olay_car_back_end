<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Car extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->load->model('cars');
                $this->load->helper('url');
                $this->load->helper('email');
                //$this->load->model('manage_m/users');
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
        //    if($auser)
          //      $config['upload_path'].="\\".$auser->username;
            $fnames=$this->upload->do_multi_upload_car($auser->username);
            if ( !$fnames) //error;
            {
              
                $error = array('error' => $this->upload->display_errors("",""));                    
                $result['status'] = $this->config->item('fail');
                $result['cid'] = $this->config->item('fail');
                $result['error'] = $error['error'];
                    
            }
            else //success
            {
                                   
                  $registration=$_POST['registration'];
                  $price=$_POST['price'];                 
                 
                  //$this->users->update_user_profile($uid,$ufuname,$c_car,$a_me,$region,$img_loc);
                  //Insert info in to database and image locations into car_imgs tables
                //  $fnames=$auser->username."//".$fnames;
                  $result['cid'] = $this->cars->create_car($uid,$registration,$price,$fnames,$auser->username);
                  $result['status'] = $this->config->item('success');
                  
                  
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

            if (!is_null($data = $this->cars->update_car2(
                    $cid,
                    $_REQUEST['make'],
                    $_REQUEST['model'],
                    $_REQUEST['year'],
                    $_REQUEST['f_type'],
                    $_REQUEST['trans'],
                    $_REQUEST['m_age'],
                    $_REQUEST['desc']))) {                    
                        $result['status'] = $this->config->item('success');

                } 
                else {
                    $result['status'] = $this->config->item('fail');                        
                    $result['error'] = $this->config->item('unknown_error');
                       
                }
            echo json_encode($result);
	}
	/*
         * @uid : user id
         * @sid : session id
         * @cid : car id  
         */
         function remove_car() {    	
            if (!isset($_GET['uid']) || !isset($_GET['sid']) || !isset($_GET['cid']) )
            {                
                $result['status'] = $this->config->item('fail');               
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            } 
            
            $uid=$_GET['uid'];
            $sid=$_GET['sid'];
            $cid=$_GET['cid'];
            
            if (!is_numeric ($uid) || !$this->tank_auth->is_valid_session($uid,$sid)){
                $result['cid'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_session');
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
         private function check_car_session() {    	
            if (!isset($_GET['uid']) || !isset($_GET['sid'])  )
            {
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            } 
            
            $uid=$_GET['uid'];
            $sid=$_GET['sid'];            
            
            if (!is_numeric ($uid) || !$this->tank_auth->is_valid_session($uid,$sid)){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_session');
                echo json_encode($result);
                return;
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
            
             if( !isset($_GET['cid'])){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
                
            $uid=$_GET['uid'];
            $watch_car_id=$this->cars->add_watch_car($_GET['cid'],$uid);
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
            
            $uid=$_GET['uid'];
     
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
            
             if( !isset($_GET['cid'])){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
                
            $uid=$_GET['uid'];
            $cid=$_GET['cid'];
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
            
           
             if( !isset($_GET['number']) || !isset($_GET['offset']) ){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
                            
            $number=$_GET['number'];
            $offset=$_GET['offset'];
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
            
            if( !isset($_GET['number']) || !isset($_GET['offset']) ){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
           
            $number=$_GET['number'];
            $offset=$_GET['offset'];            
            
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
           
            if( !isset($_GET['cid'])){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            $cid=$_GET['cid'];
            
            $acar= $this->cars->get_car_by_id($cid);
            //incrase the number of visitors of the car
            if (!$acar ||  !$this->cars->inc_num_visitors($cid,$acar->num_visitors+1)){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('not_exist');
            }
            else{
                $result['price']=$acar->price;
                $result['make']=$acar->make;
                $result['model']=$acar->model;
                $result['mileage']=$acar->mileage;
                $result['year']=$acar->year;
                $result['transmission']=$acar->transmission;
                //get Image or video url
                
                //$result['url']=$acar->
                $file_loc_array=$this->cars->list_car_img_loc($acar->id);
                
                if (sizeof($file_loc_array)>0) {
                    $result['url'] = base_url()."//". $this->config->item('upload_path')."//".$file_loc_array[0]["img_loc"];
                    
                }
                else
                {
                    $result['url']="";
                }
  
            }
    
            echo json_encode($result);
        }
        
         /*
         * @uid : user id
         * @sid : session id
          * @make : car make
          * @model : model
          * @price : price
          * @p_code : post code of current location
          * @radius : radius within the p_code
         * @number : number of results
         * @showall : 1 -> by any user, 0 -> by this user only, default is 1
         */
         function a_search() {    	// advanced search
            
             if (!$this->check_car_session())
                return;
             
            if( !isset($_GET['number'])){
                $result['status'] =$this->config->item('fail');          
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            $uid=$_GET['uid'];
            $number=$_GET['number'];
            $offset = (isset($_GET['offset'])) ? $_GET['offset'] : "";
            $make = (isset($_GET['make'])) ? $_GET['make'] : "";
            $model = (isset($_GET['model'])) ? $_GET['model'] : "";
            $s_price = (isset($_GET['s_price'])) ? $_GET['s_price'] : "";
            $e_price= (isset($_GET['e_price'])) ? $_GET['e_price'] : "";
            $p_code= (isset($_GET['p_code'])) ? $_GET['p_code'] : "";
            $radius= (isset($_GET['radius'])) ? $_GET['radius'] : 9999999999;
            //$loc=$_GET['loc'];            
            $result['status'] = $this->config->item('success');
            $result['cars']= $this->cars->a_search_list($make,$model,$s_price,$e_price,$p_code,$radius,$number,$offset);
            
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
                $result['status'] = $this->config->item('success');              
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
                
            $uid=$_REQUEST['uid'];
            $cid=$_REQUEST['cid'];
            $comment=$_REQUEST['comment'];
            
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
             
             if( !isset($_GET['cid']) || $_GET['cid']==""){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            $uid=$_GET['uid']; $cid=$_GET['cid'];
            
            
            if( !isset($_GET['number']) || $_GET['number']==""){
                $result['number'] = "-1";
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            if( !isset($_GET['offset']) || $_GET['offset']==""){
                $result['offset'] = "-1";
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            $number=$_GET['number'];
            $offset=$_GET['offset'];
            
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
            
             if( !isset($_GET['comm_id'])){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
                
            $comm_id=$_GET['comm_id']; $uid=$_GET['uid'];
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
            $msg_id=$this->cars->add_message_car($_REQUEST['uid'],$_REQUEST['r_id'],$_REQUEST['msg']);
            
            $result['status'] = $this->config->item('success'); 
            $result['msg_id'] = $msg_id;
            
            //********************send mail function*******************
          
           /*  $headers = 'From: '.$sender_user->email. "\r\n" .
            'Reply-To: iwantacar@rayjin.mailgun.org' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
            
            $okmail=send_email($receiver_user->email, "Message from(".$sender_user->email.")", $_REQUEST['msg'],$headers);
                        
            $result["mailsent"]=$okmail;*/
            
            $receiver_user= $this->users->get_user_by_id($r_id,true);
            $sender_user= $this->users->get_user_by_id($uid,true);
            
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
             
             if( !isset($_GET['s_id']) || $_GET['s_id']=="" || !isset($_GET['r_id']) || $_GET['r_id']==""){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            $uid=$_GET['uid']; $s_id=$_GET['s_id']; $r_id=$_GET['r_id'];
            $result['status'] =$this->config->item('success');
            
            if( !isset($_GET['number']) || $_GET['number']==""){                
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            if( !isset($_GET['offset']) || $_GET['offset']==""){                
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            $number=$_GET['number'];
            $offset=$_GET['offset'];
            
            $result['msgs']= $this->cars->list_message_car($s_id, $r_id, $number, $offset);
            
            echo json_encode($result);            
        }
        
        /*
         * @uid : user id
         * @sid : session id
         * @msg_id : message id  
         */
         function remove_message_car() {    	
            
             if (!$this->check_car_session())
                return;
            
             if( !isset($_GET['msg_id'])){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
                
            $msg_id=$_GET['msg_id']; $uid=$_GET['uid'];
            $this->cars->remove_message_car_by_id($msg_id);         
            
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
            $offer_id=$this->cars->add_offer_car($_REQUEST['uid'],$_REQUEST['cid'],$_REQUEST['price'],$msg);
            
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
             
             if( !isset($_GET['cid']) || $_GET['cid']==""){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            $uid=$_GET['uid']; $cid=$_GET['cid']; 
                        
            if( !isset($_GET['number']) || $_GET['number']==""){
                
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            if( !isset($_GET['offset']) || $_GET['offset']==""){
                
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            
            $number=$_GET['number'];
            $offset=$_GET['offset'];
            
            $result['status'] = $this->config->item('success');
            $result['offers']= $this->cars->list_offer_car($cid, $number, $offset);
            
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
            
             if( !isset($_GET['offer_id'])){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
                
            $offer_id=$_GET['offer_id']; $uid=$_GET['uid'];
            $this->cars->remove_offer_car_by_id($offer_id);
            
            $result['status'] =$this->config->item('success');;
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
            
             if( !isset($_GET['pcodeA']) || !isset($_GET['pcodeB'])){
                $result['uid'] = "-1";
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
            $uid=$_GET['uid'];
            $pcodeA=$_GET['pcodeA']; $pcodeB=$_GET['pcodeB'];
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