<?php

error_reporting (E_ERROR |E_PARSE  );

ini_set("display_errors", 'on');

$message 		= trim($_GET['message']);

$aDeviceToken   = "194C5273BEAF9965208A84403B7422419F22986BAFA302D3828F5D03BC3392C3";

if (!empty($message)) {


		//Send notification
		$certFileName = "push_dev.pem";
		$certPassword = "123";
		$push_method = "develop";//Set it "live" when live on iTunes
		$messageID = "1";
		
		$trim="test";
			
		$sendResult = send($aDeviceToken, $certFileName, $certPassword, $push_method, $message, 1, "default", "custom", $messageID);
		
		if ($sendResult) {
			$error_flag = $sendResult;
			$str = "SUCCESS";
		}
		else {
			$error_flag = $sendResult;
			$str = "ERROR";

		}
}

echo $error_flag . "  :  " . $str . " <> " . $message;

function send($deviceToken, $certFile, $certPass, $push_method, $alert, $badge, $sound, $custom_key, $custom_value) 
 { 

     $deviceToken = str_replace(" ", "", $deviceToken); 
     echo $deviceToken = pack('H*', $deviceToken); 
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
		print_r($err);
		print_r($errstr);
     }
     if(!$fp) 
     { 
   		echo  "Connection failed ".$err. $errstr."\n"; 
   		return FALSE; 
     } 
     $payload = json_encode($body); 
     $msg = chr(0).chr(0).chr(32).$deviceToken.chr(0).chr(strlen($payload)).$payload; 
     fwrite($fp, $msg); 
     fclose($fp);      

     
     return TRUE;
 }
?>


