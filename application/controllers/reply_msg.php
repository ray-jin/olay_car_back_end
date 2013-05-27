<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reply_msg extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
                $this->load->model('cars');
		$this->load->helper('url');
		$this->load->library('tank_auth');
	}
     
	function index()
	{
		$sender_mail=$_REQUEST['sender'];
                $subject=$_REQUEST['subject'];

                $start=  strripos($subject, "(");
                $end=  strripos($subject, ")");
                $receiver_mail=substr($subject, $start+1, $end-$start-1);

                $message="Reply by ".$sender_mail."\r\n".$_REQUEST['stripped-text'];

                $sender=$this->users->get_user_by_email($sender_mail);
                $receiver=$this->users->get_user_by_email($receiver_mail);

                
                $msg_id=$this->cars->add_message_car($sender->id,$receiver->id,$message);
                //echo $msg_id; /// Newly created message
                //return 6;
                    $headers = "From: ".$sender_mail. "\r\n" .
                'X-Mailer: PHP/' . phpversion();

                    
                $okmail=mail($receiver_mail, $subject, $message, $headers);
                    //echo $okmail;
	}
}


        
