<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	
	$config['main_category'] = array(
		'admin\user'	=>	'Users',	
                'admin\user_profile'	=>	'User Profile',
                'admin\car'	=>	'Cars',
                'admin\inbox'	=>	'Inbox Messages',
                'admin\outbox' =>     'Outbox Messages',
                'admin\offer'	=>	'Offers',
                'admin\comment'	=>	'Comments',
                'admin\watch'	=>	'Watches',
                'admin\make'	=>	'Makes',
                'admin\model'	=>	'Models',
                'admin\saved_search' =>	'Saved Searches',
	);
        
        $config['max_count_per_page'] = 10;	
        $config['duplicate_mail'] = "Email address already exist";
        $config['login_success']="0";
        $config['login_not_activated']="1";
        $config['login_banned']="2";
	$config['login_incorrect_password']="3";
        $config['register_success']="0";
        $config['invalid_params']="Invalid parameters";
        $config['incorrect_fbname_umail']="Incorrect facebook id or mail id";
        $config['register_duplicate_umail']="Duplicate items";
        $config['fail']="-1";
        $config['success']="0";
        $config['login_incorrect_fname_umail']="3";
        $config['max_img_size']=2048; //2MB;
        $config['invalid_session']="Invalid session"; 
        $config['failed']="Failed";
        $config['unknown_error']="unknow error";
        $config['not_exist']="does not exist";
        
        define ('HOST',"http://192.168.180.90/cars/");
        define('UPLOAD_PATH', 'uploads/');
        define('ADMIN_USER_ID',1);
        define('FAIL',-1);
        define('SUCCESS',0);
        