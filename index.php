<?php
global $mdbConnection;
require_once("config.php");
require_once("function.php");
require_once("mysql_queries.php");

try {
	write_log("Connection to mysql");
	$mysqlConnection 	= mysql_connect(MYSQL_DB_HOST, MYSQL_DB_USER, MYSQL_DB_PASSWORD);
	$mysqlDBConnection 	= mysql_select_db(MYSQL_DB_NAME, $mysqlConnection);
	if($mysqlConnection && $mysqlDBConnection) {
		write_log("Mysql connection established");
		write_log("Connection to mongodb");
		//$mdbConnection 		= new MongoClient('mongodb://root:root@localhost:27017');
		$mdbConnection 		= new MongoClient('mongodb://'.MONGO_DB_USER.':'.MONGO_DB_PASSWORD.'@'.MONGO_DB_HOST.':'.MONGO_DB_PORT);
		if($mdbConnection) {
			write_log("MongoDb connection established");
			
			write_log("************* Script starts... *********************************");

			$start = 0;
			foreach ($mysql_queries as $key => $value) {
				$limit 		= $value['limit'];
				$collection = $value['collection'];
				$sql_query 	= $value['query'];
				$end = $limit;
				export_rows($sql_query, $start, $end, $limit, $collection, $mdbConnection);
			}
		} else {
			write_log("Connection to mongodb failed.", true);
		}
	} else {
		write_log("Connection to mysql failed.", true);
	}
	write_log("************* Script completed *********************************");
}
catch(Exception $e) {
	email_log('Caught exception: ',  $e->getMessage());
	echo 'Caught exception: ',  $e->getMessage(), "\n";
}

?>


