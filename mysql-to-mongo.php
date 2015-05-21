<?php
//ini_set("display_errors", 1);
//error_reporting(E_ALL);

$mysqlConnection 	= mysql_connect('172.16.2.35','isnadmin','isnghyt65rt');
$mysqlDBConnection 	= mysql_select_db('isndev',$mysqlConnection);
$mdbConnection 		= new MongoClient('mongodb://root:root@localhost:27017');

echo "\n\n ************* Script starts... ********************************* \n\n";

//$user_sql = "SELECT * FROM users WHERE status IS NOT NULL LIMIT 5";
$user_sql = "SELECT * FROM users";
$user_query = mysql_query($user_sql) or die("TABLE users ERROR <font color='red'>".mysql_error()."</font><br>");

while($user_info = mysql_fetch_assoc($user_query)) {
	$user_details = array(
		"id" 					=> (int)$user_info['id'],
		"parent_id" 			=> (int)$user_info['parent_id'],
		"username" 				=> $user_info['username'],
		"password" 				=> $user_info['password'],
		"status" 				=> (int)$user_info['status'],
		"suspend_for_days" 		=> (int)$user_info['suspend_for_days'],
		"parent_status" 		=> (int)$user_info['parent_status'],
		"login_attempts" 		=> (int)$user_info['login_attempts'],
		"login_attempt_time"	=> new MongoDate($user_info['login_attempt_time']),
		"activation_key" 		=> $user_info['activation_key'],
		"parent_activation_key" => $user_info['parent_activation_key'],
		"resetsenton" 			=> $user_info['resetsenton'],
		"resetsentontime" 		=> new MongoDate(strtotime($user_info['resetsentontime'])),
		"created_on" 			=> new MongoDate(strtotime($user_info['created_on'])),
		"updated_on" 			=> new MongoDate(strtotime($user_info['updated_on'])),
		"openid" 				=> $user_info['openid']
	);
	$output = $mdbConnection->ISN_RND->users->insert($user_details);
	echo "USER -> id : ".$user_info['id']." inserted in ISN_RND -> users collection.\n";
}

echo "\n\n ************* Script completed ********************************* \n\n";


?>