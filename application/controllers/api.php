<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once 'car.php';

class Api extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->load->model('saved_searches');
                $this->load->model('manage_m');
                $this->load->model('manage_m/users');
                $this->load->helper('url');
                $this->load->model('cars');
                $this->load->model('models');
                $this->load->model('makes');
                $this->load->model('saved_searches');
	}
	
	function index()
	{
		echo "Invalid Access!";
	}
        
	 /**
	 * Login user on the site 
         * @uname : username
	 * @umail : mail address
         * @upass : password
         * @d_token : device token
         * on sucess uid>0 on fail uid=-1
         * 
	 * @return json string (uid='user id',sid="session id",status="0,1,2,3") 
         * 0: success ,1:register_invalid_params, 2: duplicate user_id or email adddress
	 */
	function register_user() {		
            
            if (!isset($_GET['uname']) || !isset($_GET['umail']) || !isset($_GET['upass']) || !isset($_GET['d_token']))
            {
                $result['uid'] = "-1";
                $result['sid']="";
                $result['error'] = $this->config->item('invalid_params');
                $result['status']=$this->config->item('fail');
                echo json_encode($result);
                return;
            } else {
                
                $fname=""; //default face book user name
                
                
                if (!is_null($data = $this->tank_auth->create_user(
                        $_GET['uname'],
                        $_GET['umail'],
                        $_GET['upass'],
                        $_GET['d_token'],
                        $fname))) {                    
                            $result['uid'] = $this->tank_auth->get_user_id();
                            $result['sid'] = $this->tank_auth->get_session_id();
                            $result['status'] =  $this->config->item('success'); //succeed                    
                            
                    } else {
                            
                            $result['status'] = $this->config->item('fail'); //fail
                            $errors = $this->tank_auth->get_error_message();
                            $result['error']=$errors['message'];  
                            //$result['status'] = $this->config->item('register_duplicate_umail'); // duplicate facebook id or mail or username
                    }
            }
            echo json_encode($result);
	}
	
        /**
	 * Login user on the site 
	 * @umail : userid or mail address
         * @upass : password
         * @d_token : device token
         * on sucess uid>0 on fail uid=-1
         * 
	 * @return json string (uid='user id',sid="session id",status="0,1,2,3") 
         * 0: success ,1:not activated, 2: banned, 3:incorrect password;
	 */
	function login_user() { 
           
            $result = array('uid'=>'','sid'=>'');
            
            if (!isset($_GET['umail']) || !isset($_GET['upass']) || !isset($_GET['d_token'])) {
                    $result['uid'] = "-1";
                    $result['sid']="";
                    $result['status']=$this->config->item('fail'); 
                    $result['error'] = $this->config->item('invalid_params');
                    echo json_encode($result);
                    return;
            } 
            
            $umail=$_GET['umail'];                      
            $upass = $_GET['upass'];
            $d_token=$_GET['d_token'];
            
            if ($this->tank_auth->login(
                    $umail,
                    $upass,
                    $d_token,
                    true,
                    true,
                    true)) {
                    
                   
                    $rand= random_string('unique');
                    
                    $this->tank_auth->update_session_info($this->tank_auth->get_user_id(), $rand, $d_token); //update users table with fresh session id
                    $result['uid'] = $this->tank_auth->get_user_id();
                    $result['sid'] = $rand;
                    $result['status'] = $this->config->item('login_success'); //success
                                        
            } else 
                {
                    $result['uid'] = "-1";
                    $result['sid'] = "";
                    $result['status']=$this->config->item('fail'); 
                    $errors = $this->tank_auth->get_error_message();
                    $result['error']="Incorrect Username or Password";  
            }
            echo json_encode($result);
	}
        
          /**
	 * Login user on the site 
	 * @umail : userid or mail address
         * @fname : facebook id
         * @uname : username         
         * @d_token : device token
         * on sucess uid>0 on fail uid=-1
         * 
	 * @return json string (uid='user id',sid="session id",status="0,1,2,3") 
         * 0: success ,1:not activated, 2: banned, 3:incorrect facebook id or mail address;;
	 */
	function login_user_f() { //facebook login
           
            $result = array();
            
            if (!isset($_GET['fname']) || !isset($_GET['d_token'])) {                   
                    $result['status']=$this->config->item('fail'); 
                    $result['error'] = $this->config->item('invalid_params');
                    
                    echo json_encode($result);
                    return;
            } 
            
            
            $fname=$_GET['fname'];
            $d_token=$_GET['d_token'];
            
            $umail=isset($_GET['umail']) ? $_GET['umail']: '';
            $uname=isset($_GET['uname']) ? $_GET['uname']: '';
            $upass=isset($_GET['upass']) ? $_GET['upass']: '';

            if ($this->tank_auth->login_f($umail,$fname,$uname,$upass,$d_token)) {
                
                    
                   $rand= random_string('unique');
                    $this->tank_auth->update_session_info($this->tank_auth->get_user_id(),  $rand, $d_token); //update users table with fresh session id
                    $result['uid'] = $this->tank_auth->get_user_id();
                    $result['sid'] = $rand;
                    $result['status'] = $this->config->item('login_success'); //success
                                        
            }
            else { // 
                                    
                    $result['status'] = $this->config->item('fail'); 
                    $errors = $this->tank_auth->get_error_message();                         
                    $result['error']=$errors['message'];// "Incorrect Username or Password";
              
            }
            echo json_encode($result);
	}
	
	function logout_user() {
		$result = array('method'=>'logout_user');
		if (!isset($_GET['session'])) {
			$result['status'] = 0;
			$result['message'] = "Invalid values";
		} else {
			$this->tank_auth->logout();
			$this->manage_m->logout($_GET['session']);
			$result['status'] = 1;
		}
		echo json_encode($result);
	}
	
	
	
        
        /*
         * @uid : user id
         * @sid : session id
         * @ufuname : user full name
         * @c_car : current car
         * @a_me : about me 
         * @region :regin
         */
	function update_user() {
            
            $config['upload_path'] = UPLOAD_PATH;
            
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size']	= $this->config->item('max_img_size'); //2MB
                        
            $this->load->library('upload', $config);
            //check whether valid session
            $uid=$_POST['uid'];
            $sid=$_POST['sid'];
            
            if (!is_numeric ($uid) || !$this->tank_auth->is_valid_session($uid,$sid)){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_session');
                $result['uid'] = "-1";
                echo json_encode($result);
                return;
            }
            $auser= $this->users->get_user_by_id($uid, true);
        //    if($auser)
          //      $config['upload_path'].="\\".$auser->username;
            
            if ($_FILES['userfile']['size']>0){
                 if ( !$this->upload->do_user_profile_upload($auser->username))
                {
                    $error = array('error' => $this->upload->display_errors("",""));                    
                    $result['status'] = $this->config->item('fail');                    
                    $result['error'] = $error['error'];
                    echo json_encode($result);
                    return;
                }
                $img_loc=$auser->username."/profile/".$this->upload->file_name;
            }
            else{
                $img_loc="";
            }
           
           
                                   
            $ufuname=$_POST['ufuname'];
            $c_car=$_POST['c_car'];
            $a_me=$_POST['a_me'];
            $loc=$_POST['loc'];
            //$img_loc=$this->upload->file_name;


            if ($this->users->update_user_profile($uid,$ufuname,$c_car,$a_me,$loc,$img_loc,$auser->username)){
                $result['status'] = $this->config->item('success');
            }
            else{
                $result['status'] = $this->config->item('fail');
                $result['error'] = "Error occured";
            }
//                  $this->users->del                                    
            
            echo json_encode($result);
	}
        
        /*
         * @uid : user id
         * @sid : session id
         * @return ufuname, current car, about me, location (postal code), image url
         */
	function get_user_profile() {
                       
            //check valid params
            
            if (!isset($_REQUEST['uid']) || !isset($_REQUEST['sid']))
            {
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            } 
            
            //check whether valid session
            
            $uid=$_REQUEST['uid'];
            $sid=$_REQUEST['sid'];
            
            if (!is_numeric ($uid) || !$this->tank_auth->is_valid_session($uid,$sid)){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_session');
                echo json_encode($result);
                return;
            }
            
            $aprofile= $this->users->get_profile_by_userid($uid);
            
        //    if($auser)
          //      $config['upload_path'].="\\".$auser->username;
            if (!$aprofile){
                $result['status'] = $this->config->item('fail');                    
                $result['error'] = $this->config->item('not_exist');
            }
            else
            {
                $result['status'] = $this->config->item('success');
                $result['uf_id'] = $aprofile->id;
                $result['ufuname'] = $aprofile->ufuname;
                $result['c_car'] = $aprofile->current_car;
                $result['loc'] = $aprofile->loc;
                $result['img_loc'] = base_url()."/".UPLOAD_PATH."/".$aprofile->image_loc;
                //$result['url'] = base_url()."//". $this->config->item('upload_path')."//".$file_loc_array[0]["img_loc"];
                $result['a_me'] = $aprofile->about_me;
                
            }                              
            echo json_encode($result);
	}
	
	function user_password() {
		$result = array('method'=>'user_password');
		if ( !is_null($this->tank_auth->forgot_password($_GET['mail']))) {
			
			$password = $this->manage_m->generate_password();
			$hashed_password = $this->tank_auth->secure_password($password);
			
			$this->manage_m->change_password($hashed_password, $_GET['mail']);
			
			$config = array();
			$config['mailtype'] = "html"; 
			$config['charset'] = "utf-8"; 
			$config['protocol'] = "smtp"; 
			$config['smtp_host'] = "ssl://smtp.googlemail.com"; 
			$config['smtp_port'] = 465; 
			$config['smtp_user'] = "qinghan910@gmail.com"; 
			$config['smtp_pass'] = "onguard8"; 
			$config['smtp_timeout'] = 10;
			
			$this->load->library('email', $config); 
			$this->email->set_newline("\r\n"); 
			$this->email->clear(); 
			$this->email->from("qinghan910@gmail.com", "Manager"); 
			$this->email->to($_GET['mail']); 
			$this->email->subject("Change Password - Green Code!"); 
			$this->email->message($password);
			
			$this->email->send();
			
			$result['status'] = 1;
			$result['return']['password'] = "sent the new password with your email";
		} else {
			$result['status'] = 0;
			$result['message'] = "Not exist the user";
		}
		
		echo json_encode($result);
	}
        
	 /*
         * @uid : Test
         * @sid : session id
          * Mailgun Test
         * @return ufuname, current car, about me, location (postal code), image url
         */
        function mailgun_test(){
             $ch = curl_init();
               
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, 'api:key-0iizmqtrsytx5o59xvq9pksy5jvdvey7');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v2/rayjin.mailgun.org/messages');
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('from' => 'nice.ray.jin@gmail.com',
                                                        'to' => 'gold.brain.pitt@gmail.com',
                                                        'Reply-To' => 'iwantacar@rayjin.mailgun.org',
                                                        'sender' => 'gold.brain.pitt@gmail.com',
                                                        'subject' => 'Hello',
                                                        'text' => 'Testing some Mailgun !'));

            $result = curl_exec($ch);
            if( ! $result ) 
                { 
                    trigger_error(curl_error($ch)); 
                } 
            curl_close($ch);
            echo $result;
            
           // return $result;
        }
        
           /*
         * 
         */
         function list_postcodes() {    	
            
            $result['status'] = $this->config->item('success');
            $result['postcodes']= $this->cars->list_postcodes();
                        
            echo json_encode($result);            
        }
        
        /*
         * @uid : user id
         * @sid : session id
         * return the distance
         */
         function list_saved_searches() {    	
             $car=  new Car();
             
             if (!$car->check_car_session())
                return;
                        
            $result['status'] =$this->config->item('success');
            $uid=$_REQUEST['uid'];
            
            $list= $this->saved_searches->list_saved_searches(0,1000,$uid);
            $result['saved_strings']=$list['rows'];
            echo json_encode($result);
        }
        
        /*
         * @uid : user id
         * @sid : session id
         * @query : query string
         * return the distance
         */
         function add_saved_searches() {    	
             $car=  new Car();
             
             if (!$car->check_car_session())
                return;
            
             if( !isset($_REQUEST['query']) || !isset($_REQUEST['query'])){
                $result['status'] = $this->config->item('fail');              
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }
             
            $query=$_REQUEST['query'];                        
            $uid=$_REQUEST['uid'];
            
            $new_id= $this->saved_searches->add_saved_searches($uid,$query);
            
            $result['status'] = $this->config->item('success');
            $result['new_id'] = $new_id;
            
            echo json_encode($result);
        }
        
        /*
         * Remove saved search id
         * 
         */
         function remove_saved_search() {    	
            
               $car=  new Car();
             if (!$car->check_car_session())
                return;
            
             if( !isset($_REQUEST['saved_search_id']) || $_REQUEST['saved_search_id']==""){
                $result['status'] = $this->config->item('fail');
                $result['error'] = $this->config->item('invalid_params');
                echo json_encode($result);
                return;
            }                
            $uid=$_REQUEST['uid'];
            $id=$_REQUEST['saved_search_id'];                         
            
            $ele = $this->saved_searches->get_specific_data($id);
            if ( ($ele==null) || $ele['uid']!=$uid)  {
                $result['status'] = $this->config->item('fail');
                $result['error'] = "Saved Search item doesn't exist or not correct owner";
                echo json_encode($result);
                return;
            }
            
            $this->saved_searches->remove_saved_searches($id);                               
            $result['status'] = $this->config->item('success');
            echo json_encode($result);				
        }
        
         /*
         * list all makes
         * 
         */
         function list_makes() {           
            $result['status'] =$this->config->item('success');            
            $list= $this->makes->list_makes(0, 1000, "");
            $result['makes']=$list['rows'];
            echo json_encode($result);            
        }
        
        /*
         * list all makes
         * @make_id : make id : -1 (for all)
         */
         function list_models() {           
            
             if( !isset($_REQUEST['make_id']) || $_REQUEST['make_id']=="" ){               
                $result['error'] = $this->config->item('invalid_params');
                $result['status'] = $this->config->item('fail');
                echo json_encode($result);
                return;
            }
            $make_id=$_REQUEST['make_id'];
            $result['status'] =$this->config->item('success');            
            $list= $this->models->list_all_models_by_maker_id($make_id);
            $result['makes']=$list['rows'];
            echo json_encode($result);            
        }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */