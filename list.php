<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset='UTF-8'>
		<link rel="stylesheet" href="styles/main.css" />
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>		
		<script src='js/main.js'></script>
	</head>
	<body>
		<h1>Kenny's-Spot</h1>
		<nav>
			<a href="">Home</a>&nbsp;&nbsp;&nbsp;
			<a href="http://calendar.kennys-spot.org">Calendar</a>&nbsp;&nbsp;&nbsp;
			<a href="php/logout.php">Logout</a>&nbsp;&nbsp;&nbsp;
			<a href="">Help</a>
		</nav>
		<div class="tab-panels wrapper">
			<ul class="tabs">
				<li rel="panel1" class="active">Grocery List</li>
				<li rel="panel2">panel2</li>
			</ul>

			<div id="panel1" class="panel active">
				<h3>Grocery List</h3>
				<div id="itemList">					
				</div>
				<input type="text" id="newItem" /><br />
				<input type="button" value="Add Item" id="add-item" />
				<input type="button" value="Delete Items" id="del-item" />
			</div>

			<div id="panel2" class="panel">content2
				<br/> content2
				<br/> content2
				<br/> content2
				<br/> content2
				<br/>
			</div>
		</div>
		<footer><section id="foot">Designed by web designer, Kenny Coltharp, Copyright &copy; 2015.</section><br />
			<a href="" class="bottomNav">Home</a>&nbsp;&nbsp;&nbsp;
			<a href="php/logout.php" class="bottomNav">Logout</a>&nbsp;&nbsp;&nbsp;
			<a href="" class="bottomNav">Help</a>
		</footer>
		<script>
			$(function(){
				getItems();
				$('newItem').focus();
			}); //end jQuery function
		</script>
	</body>
</html>