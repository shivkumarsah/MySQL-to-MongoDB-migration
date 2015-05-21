<?php
ini_set("display_errors", 1);
error_reporting(E_ALL); //E_ALL
ini_set("SMTP","smtp.gmail.com" ); 
ini_set('sendmail_from', 'shiv.web@gmail.com'); 

$APPLICATION_ENV = "dev"; //stage, prod, dev

if($APPLICATION_ENV=='dev') {
	define("MYSQL_DB_HOST", 		"localhost");
	define("MYSQL_DB_USER", 		'username');
	define("MYSQL_DB_PASSWORD", 	'password');
	define("MYSQL_DB_NAME", 		'db_name');

	define("MONGO_DB_HOST", 		"localhost");
	define("MONGO_DB_USER", 		"root");
	define("MONGO_DB_PASSWORD", 	"root");
	define("MONGO_DB_NAME", 		"db_name");
	define("MONGO_DB_PORT", 		"27017");
	
	define("LOG_PATH",				"log/".time().".log");
	define("LOG_EMAIL_TO",			'shiv.web@gmail.com');

}
else if($APPLICATION_ENV=='stage') {
	define("LOG_PATH",			"log/".time().".log");
	
}
else if($APPLICATION_ENV=='prod') {
	define("LOG_PATH",			"log/".time().".log");
	
}
?>