<?php

function write_log($str, $status=false) {

	echo date("Y-m-d H:i:s")." => $str \n";
	error_log(date("Y-m-d H:i:s")." => ".$str." \n", 3, LOG_PATH);
	if($status) {
		email_log($str);
	}
}

function email_log($str) {
	$to      = LOG_EMAIL_TO;
	$subject = 'Caught exception in batch script :';
	$message = $str;
	$headers = 'From: shiv.sah@icreon.com' . "\r\n" .
				'Reply-To: shiv.sah@icreon.com' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();

	mail($to, $subject, $message, $headers);
}

?>