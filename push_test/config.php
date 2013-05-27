<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set("display_errors", "On");

define('__PATH_TO_UPLOAD','upload/');
define('__PATH_TO_API','api/');
define('__PATH_TO_COMMON','common/');
define('SITE_URL',"http://".$_SERVER['SERVER_NAME']."/iphone/");
define('SITE_NAME',"Songfeud!");
define('DOC_ROOT',$_SERVER['DOCUMENT_ROOT']."/iphone/");

define('HTTP_SERVER', "http://".$_SERVER["SERVER_NAME"]);
define('DIR_WS_HTTP_CATALOG', 'iphone/');
define('DIR_WS_HTTP_ADMIN', 'admin/');
define('HTTP_ADMIN_SERVER', HTTP_SERVER."/".DIR_WS_HTTP_CATALOG.DIR_WS_HTTP_ADMIN);	
define('DIR_WS_HTTP_UPLOAD', 'upload/');
define('HTTP_UPLOAD_SERVER', HTTP_ADMIN_SERVER.DIR_WS_HTTP_UPLOAD);
define('DIR_WS_HTTP_UPLOAD_CAT', 'category/');
define('HTTP_UPLOAD_SERVER_CAT', HTTP_ADMIN_SERVER.DIR_WS_HTTP_UPLOAD.DIR_WS_HTTP_UPLOAD_CAT);

define ("DB_HOST","localhost");
define ("DB_USER","root");
define ("DB_PASS","root");
define ("DB_NAME","olay");
define ("FROM","hitesh@techintegrity.in");

#pushnotification Settings
define ("CERTIFICATE_FILE_NAME", "push_dev.pem");
define ("CERTIFICATE_PASSWORD", "");
define ("PUSH_MODE", "develop");
/* Production
define ("CERTIFICATE_FILE_NAME", "songfeud_product.pem");
define ("CERTIFICATE_PASSWORD", "");
define ("PUSH_MODE", "live");
*/
mysql_connect (DB_HOST,DB_USER,DB_PASS) or die ("Couldn't connect with database!");
mysql_select_db(DB_NAME) or die ("Database not found!");

require_once __PATH_TO_COMMON. 'functions.php';
class Config
{
	public function verify_iphone_sign($sign="",$salt="")
	{
		if($sign!='' and $salt!='')
		{
			$key="likesongfeud";	
			$md5_salt=md5($key.$salt);
			if($sign==$md5_salt)
			{
				return true;
			}
		}
		return false;
	}	
	public function dbconn()
	{
		mysql_connect(DB_HOST,DB_USER,DB_PASS) or die ("Couldn't connect with database!");
		mysql_select_db(DB_NAME) or die ("Database not found!");
	}
	public function dbclose()
	{
		mysql_close();
	}
	
	
	# Send Pushnotification
	public function sendPushNotification($deviceToken, $certFile, $certPass, $push_method, $alert, $badge, $sound, $custom_key, $custom_value) 
	 { 
	
	     $deviceToken = str_replace(" ", "", $deviceToken); 
	     $deviceToken = pack('H*', $deviceToken); 
	     $tmp = array(); 
	     if($alert) 
	     { 
	  		$tmp['alert'] = $alert; 
	     } 
	     if($badge) 
	     { 
	  		$tmp['badge'] = $badge; 
	     } 
	     if($sound) 
	     { 
	  		$tmp['sound'] = $sound; 
	     } 
	     $body['aps'] = $tmp; 
	     $body[$custom_key] = $custom_value; 
	     $ctx = stream_context_create(); 
	     stream_context_set_option($ctx, 'ssl', 'local_cert', $certFile); 
	     stream_context_set_option($ctx, 'ssl', 'passphrase', $certPass); 
	     
	     if ( $push_method == 'develop' )
	      	$ssl_gateway_url = 'ssl://gateway.sandbox.push.apple.com: 2195';
	     else if ( $push_method == 'live' )
	      	$ssl_gateway_url = 'ssl://gateway.push.apple.com: 2195';
	      
	     if(isset($certFile) && isset($ssl_gateway_url)) 
	     {
	      	$fp = stream_socket_client($ssl_gateway_url, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx); 
	     }
	     if(!$fp) 
	     { 
	   		//print "Connection failed $err $errstr\n"; 
	   		return FALSE; 
	     } 
	     $payload = json_encode($body); 
	     $msg = chr(0).chr(0).chr(32).$deviceToken.chr(0).chr(strlen($payload)).$payload; 
	     fwrite($fp, $msg); 
	     fclose($fp);      
	
	     
	     return TRUE;
	 }
}