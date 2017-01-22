<?php

require_once 'connect.php';
require_once 'functions.php';

$user_data = array();

if(!isset($_SESSION)){
	session_start();
}

$current_file = explode('/', $_SERVER['SCRIPT_NAME']);
$current_file = end($current_file);

if(logged_in() === true){
	$session_user_id = $_SESSION['user_id'];
	$user_data = user_data($session_user_id, 'user_id', 'username', 'password', 'first_name', 'last_name', 'email', 'password_recover', 'type', 'allow_email', 'profile');
	if(user_active($user_data['username']) === false){
		session_destroy();
		echo "<meta http-equiv=\"refresh\" content=\"1;URL=http://grocery.kennys-spot.org/index.php \">";
		exit();
	}
}

$errors = array();
