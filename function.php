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

function export_rows($sql_query, $start, $end, $limit, $collection, $mdbConnection) {
	try{
		$user_query = mysql_query("$sql_query LIMIT $start, $limit") or die(write_log(mysql_error()));

		$fields = mysql_num_fields($user_query);
		$rows   = mysql_num_rows($user_query);
		$table  = mysql_field_table($user_query, 0);
		//echo "QUERY : $sql_query LIMIT $start, $limit \n";
		//echo "Your '" . $table . "' table has " . $fields . " fields and " . $rows . " record(s)\n\n";
		//echo "The table has the following fields:\n";
		write_log("QUERY : $sql_query LIMIT $start, $limit");
		write_log("TABLE => '" . $table . "' has " . $fields . " fields and " . $rows . " record(s)");
		
		$table_cols_type = $table_cols_name = array(); 

		for ($i=0; $i < $fields; $i++) {
			$table_cols_type[$i] = mysql_field_type($user_query, $i);
			$table_cols_name[$i] = mysql_field_name($user_query, $i);
		    $type  = mysql_field_type($user_query, $i);
		    $name  = mysql_field_name($user_query, $i);
		    $len   = mysql_field_len($user_query, $i);
		    $flags = mysql_field_flags($user_query, $i);
		    // echo $type . " " . $name . " " . $len . " " . $flags . "\n";
		}
		while($user_info = mysql_fetch_array($user_query)) {
			$user_details = array();
			for ($i=0; $i < $fields; $i++) {
				if($table_cols_type[$i]=='int') {
					$user_details[strtolower($table_cols_name[$i])] = (int)$user_info[$i];
				}
				else if($table_cols_type[$i]=='float') {
					$user_details[strtolower($table_cols_name[$i])] = (float)$user_info[$i];
				}
				// else if($table_cols_type[$i]=='bool') {
				// 	$user_details[strtolower($table_cols_name[$i])] = (float)$user_info[$i];
				// }
				else if($table_cols_type[$i]=='datetime') {
					$user_details[strtolower($table_cols_name[$i])] = new MongoDate(strtotime($user_info[$i]));
				} 
				else {
					$user_details[strtolower($table_cols_name[$i])] = $user_info[$i];
				}
			}
			$dbName = MONGO_DB_NAME;
			$output = $mdbConnection->$dbName->$collection->insert($user_details);
		}
		unset($user_query);
		if($rows >= $limit) {
			export_rows($sql_query, $end, ($end+$limit), $limit, $collection, $mdbConnection);
		}
	}
	catch(Exception $e) {
		email_log('Caught exception: ',  $e->getMessage());
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
}

?>