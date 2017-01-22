<?php

use HTMLPurifier;

require_once './php/htmlpurifier/HTMLPurifier.standalone.php';

/**
 * Description of MyPDO
 *
 * @author Kenny
 */
class MyPDO extends PDO{
	/**
	 * This will connect to the MySQL server via the PDO class
	 * @param String $username - username required to log into MySQL Server
	 * @param String $password - password for the user
	 * @param String $host - host is preset for localhost, otherwise input IPv4 address
	 */
	function __construct($username, $password, $host = '127.0.0.1'){
		set_exception_handler(array('example', 'exception_handler'));
		try{
			$db = parent::__construct('mysql:host=' . $host . ';dbname=grocery;charset=utf8', $username, $password, array(
					  PDO::ATTR_PERSISTENT => true,
					  PDO::ATTR_EMULATE_PREPARES => false,
					  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			));
			return $db;
		}catch (MyPDOException $e){
			print "<h1><center>ERROR: " . $e->getMessage() . "</center></h1><br />";
			$this->errorLog($e->getMessage());
			exit();
		}
	}

	/**
	 * This function will log an event to the event log for this server/directory
	 * @param String $event2Log
	 */
	function logEvents($event2Log){
		$fp = fopen('./php/events.log', 'r+');

		/* Activate the LOCK_NB option on an LOCK_EX operation */
		if(!flock($fp, LOCK_EX | LOCK_NB)){
			echo 'Unable to obtain lock';
			exit(-1);
		}else{
			$access = date("Y/m/d H:i:s");
			fwrite($fp, "Event on " . $access . " - " . $event2Log . "\n");
			fflush($fp);
			flock($fp, LOCK_UN);
		}
		fclose($fp);
	}

	/**
	 * This function will log an event to the error log for this server/directory
	 * @param String $error2Log
	 */
	function errorLog($error2Log){
		$fp = fopen('./php/error.log', 'r+');

		/* Activate the LOCK_NB option on an LOCK_EX operation */
		if(!flock($fp, LOCK_EX | LOCK_NB)){
			echo 'Unable to obtain lock';
			exit(-1);
		}else{
			$access = date("Y/m/d H:i:s");
			fwrite($fp, "Event on " . $access . " - " . $error2Log . "\n");
			fflush($fp);
			flock($fp, LOCK_UN);
		}
		fclose($fp);
	}

	/**
	 * This function will catch any previously uncaught exception and output & log the error
	 * @param String $exception
	 */
	public static function exception_handler($exception){
		print "Exception Caught: " . $exception->getMessage() . "\n";
		$this->errorLog($exception->getMessage());
	}

	/**
	 * This will send an email to the $to variable with a subject and body
	 * @param String $to - Who to send the email to.
	 * @param String $subject - Subject of the email.
	 * @param String $body - The (un)formatted text to include in the body of the email.
	 */
	function email($to, $subject, $body){
		$this->mail($to, $subject, $body, 'From: hello@Kennys-Spot.org.org');
	}

	/**
	 * This will redirect the user to the correct page by determining if they have logged in appropriately.
	 */
	function logged_in_redirect(){
		if($this->logged_in() === true){
			echo "<meta http-equiv=\"refresh\" content=\"1;URL=http://kennys-spot.org//list.php \">";
			exit();
		}else{
			echo "<meta http-equiv=\"refresh\" content=\"1;URL=http://kennys-spot.org//index.php \">";
			exit();
		}
	}

	/**
	 * This will protect the page from unauthorized visitors by including this function
	 * at the top of each page to protect. If the visitor has logged in appropriately
	 * the visitor will be able to see the page, if not, the visitor will be
	 * redirecting to the page to log in.
	 */
	function protect_page(){
		if($this->logged_in() === false){
			echo "<meta http-equiv=\"refresh\" content=\"1;URL=http://kennys-spot.org//index.php \">";
			exit();
		}
	}

	/**
	 * This will protect the page from anyone viewing the contents of the page
	 * except for admin users of the website. Other visitors will be redirected
	 * to the page to log in.
	 * @global array $user_data
	 */
	function admin_protect(){
		global $user_data;
		if($this->has_access($user_data['user_id'], 1) === false){
			echo "<meta http-equiv=\"refresh\" content=\"1;URL=http://kennys-spot.org//index.php \">";
			exit();
		}
	}

	/**
	 * This will sanitize an item of an array.
	 * @param anytype $item
	 */
	function array_sanitize(&$item){
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$cleanHTML = $purifier->purify($item);
		$item = htmlspecialchars(strip_tags($cleanHTML));
	}

	/**
	 * This will sanitize the data input into the function and return a clean
	 * piece of data that may be used as input into the MySQL database.
	 * @param anytype $data
	 * @return anytype
	 */
	function sanitize($data){
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$cleanHTML = $purifier->purify($data);
		return htmlspecialchars(strip_tags($cleanHTML));
	}

	/**
	 * This will take an input piece of data and return a formatted string into
	 * an HTML unordered list with the error as a list item.
	 * @param anytype $errors
	 * @return a formatted string into an HTML unordered list with the error as
	 * a list item
	 */
	function output_errors($errors){
		return '<ul><li>' . implode('</li><li>', $errors) . '</li></ul>';
	}

	/**
	 * This will change a users profile image & move the file to the appropriate
	 * location on the server.
	 * @param Int $user_id
	 * @param Path $file_temp
	 * @param File Extension $file_extn
	 */
	function change_profile_image($user_id, $file_temp, $file_extn){
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$file_path = 'images/profile/' . substr(md5(time()), 0, 10) . '.' . $file_extn;
		$cleanHTML = $purifier->purify($file_path);
		move_uploaded_file($file_temp, $cleanHTML);






		$this->query("UPDATE `users` SET `profile` = '" . mysql_real_escape_string($cleanHTML) . "' WHERE `user_id` = " . (int)$user_id);
	}

	function mail_users($subject, $body){
		$query = mysql_query("SELECT `email`, `first_name` FROM `users` WHERE `allow_email` = 1");
		while (($row = mysql_fetch_assoc($query)) !== false){
			email($row['email'], $subject, "Hello " . $row['first_name'] . ",\n\n" . $body);
		}
	}

	function has_access($user_id, $type){
		$user_id = (int)$user_id;
		$type = (int)$type;

		return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `user_id` = $user_id AND `type` = $type"), 0) == 1) ? true : false;
	}

	function recover($mode, $email){
		$mode = sanitize($mode);
		$email = sanitize($email);

		$user_data = user_data(user_id_from_email($email), 'user_id', 'first_name', 'username');

		if($mode == 'username'){
			email($email, 'Your username', "Hello " . $user_data['first_name'] . ",\n\nYour username is: " . $user_data['username'] . "\n\n-Company Name");
		}else if($mode == 'password'){
			$generated_password = substr(md5(rand(999, 999999)), 0, 8);
			change_password($user_data['user_id'], $generated_password);

			update_user($user_data['user_id'], array('password_recover' => '1'));

			email($email, 'Your password recovery', "Hello " . $user_data['first_name'] . ",\n\nYour new password is: " . $generated_password . "\n\n-Company Name");
		}
	}

	function update_user($user_id, $update_data){
		$update = array();
		array_walk($update_data, 'array_sanitize');

		foreach ($update_data as $field => $data){
			$update[] = '`' . $field . '` = \'' . $data . '\'';
		}

		mysql_query("UPDATE `users` SET " . implode(', ', $update) . " WHERE `user_id` = $user_id");
	}

	function activate($email, $email_code){
		$email = mysql_real_escape_string($email);
		$email_code = mysql_real_escape_string($email_code);

		if(mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `email` = '$email' AND `email_code` = '$email_code' AND `active` = 0"), 0) == 1){
			mysql_query("UPDATE `users` SET `active` = 1 WHERE `email` = '$email'");
			return true;
		}else{
			return false;
		}
	}

	function change_password($user_id, $password){
		$user_id = (int)$user_id;
		$password = md5($password);

		mysql_query("UPDATE `users` SET `password` = '$password', `password_recover` = 0 WHERE `user_id` = $user_id");
	}

	function register_user($register_data){
		array_walk($register_data, 'array_sanitize');
		$register_data['password'] = md5($register_data['password']);

		$fields = '`' . implode('`, `', array_keys($register_data)) . '`';
		$data = '\'' . implode('\', \'', $register_data) . '\'';

		mysql_query("INSERT INTO `users` ($fields) VALUES ($data)");
		email($register_data['email'], 'Activate your account', "Hello " . $register_data['first_name'] . ",
	\n\nYou need to activate your account, so use the link below:\n\nhttp://grocery.kennys-spot.org/activate.php?email=" .
			   $register_data['email'] . "&email_code=" . $register_data['email_code'] . "\n\n - grocery.kennys-spot.org");
	}

	function user_count(){
		return mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `active` = 1"), 0);
	}

	function user_data($user_id){
		$data = array();
		$user_id = (int)$user_id;

		$func_num_args = func_num_args();
		$func_get_args = func_get_args();

		if($func_num_args > 1){
			unset($func_get_args[0]);

			$fields = '`' . implode('`, `', $func_get_args) . '`';
			$data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM `users` WHERE `user_id` = $user_id"));

			return $data;
		}
	}

	function logged_in(){
		return (isset($_SESSION['user_id'])) ? true : false;
	}

	function user_exists($username){
		$username = sanitize($username);
		return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username'"), 0) == 1) ? true : false;
	}

	function email_exists($email){
		$email = sanitize($email);
		return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `email` = '$email'"), 0) == 1) ? true : false;
	}

	function user_active($username){
		$username = sanitize($username);
		return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username' AND `active` = 1"), 0) == 1) ? true : false;
	}

	function user_id_from_username($username){
		$username = sanitize($username);
		return mysql_result(mysql_query("SELECT `user_id` FROM `users` WHERE `username` = '$username'"), 0, 'user_id');
	}

	function user_id_from_email($email){
		$email = sanitize($email);
		return mysql_result(mysql_query("SELECT `user_id` FROM `users` WHERE `email` = '$email'"), 0, 'user_id');
	}

	function login($username, $password){
		$user_id = user_id_from_username($username);

		$username = sanitize($username);
		$password = md5($password);

		return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username' AND `password` = '$password'"), 0) == 1) ? $user_id : false;
	}

}