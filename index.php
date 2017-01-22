<?php
require "php/init.php";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset='UTF-8'>
		<link rel="stylesheet" href="styles/main.css" />
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
		<script src='js/main.js'></script>
		<script>
			$(function(){
				$('.form__label').click(function(){
					if($(this).hasClass('is-closed')){
						$('input[id=\'' + $(this).attr('for') + '\']').removeClass('is-closed');
						$(this).removeClass('is-closed');
					}
				}); //end form__label->click function
				$('.reset').click(function(){
					$('.form__input').val('');
					$('.form__submit').removeClass('form__submit--animated');
					$('.reset').addClass('reset--hide');
				}); //end reset->click function
			});
		</script>
	</head>
	<body>
		<h1>Kennys-Spot</h1>
		<nav>
			<a href="">Home</a>&nbsp;&nbsp;&nbsp;
			<a href="http://calendar.kennys-spot.org">Calendar</a>&nbsp;&nbsp;&nbsp;
			<a href="php/logout.php">Logout</a>&nbsp;&nbsp;&nbsp;
			<a href="">Help</a>
		</nav>
		<form class="form" action="php/login.php" method="post">
			<h1 class="form__title">Please Sign in</h1>
			<div class="form__group">
				<label class="form__label" for="username">User Name</label>
				<input type="text" name="username" class="form__input  form__input--username" id="username">
			</div>
			<div class="form__group">
				<label class="form__label" for="password">Password</label>
				<input type="password" name="password" class="form__input  form__input--password" id="password">
			</div>
			<a class="form__link" href="#">Forgot Password ?</a>
			<button type="submit" class="form__submit" >Login</button>
			<a href="" class="reset reset--hide">Reset</a>
		</form>
		<footer><section id="foot">Designed by web designer, Kenny Coltharp, Copyright &copy; 2015.</section><br />
			<a href="" class="bottomNav">Home</a>&nbsp;&nbsp;&nbsp;
			<a href="php/logout.php" class="bottomNav">Logout</a>&nbsp;&nbsp;&nbsp;
			<a href="" class="bottomNav">Help</a>
		</footer>
	</body>
</html>