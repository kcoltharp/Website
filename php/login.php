<?php
include 'init.php';
//var_dump($_POST);
if(empty($_POST) === false){
	$username = $_POST['username'];
	$password = $_POST['password'];

	if(empty($username) === true || empty($password) === true){
		$errors[] = 'You need to enter a username and password';
	}else if($MyDB->user_exists($username) === false){
		$errors[] = 'We can\'t find that username. Have you registered?';
	}else if($MyDB->user_active($username) === false){
		$errors[] = 'You haven\'t activated your account!';
	}else{
		if(strlen($password) > 32){
			$errors[] = 'Password too long';
		}

		$login = login($username, $password);
		if($login === false){
			$errors[] = 'That username/password combination is incorrect';
		}else{
			$_SESSION['user_id'] = $login;
			echo "<meta http-equiv=\"refresh\" content=\"1;URL=http://grocery.kennys-spot.org/list.php \">";
		}
	}
}else{
	$errors[] = 'No data received';
}
if(empty($errors) === false){
	?>
	<h2>We tried to log you in, but...</h2>
	<?php
	echo output_errors($errors);
}
