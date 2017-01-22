<?php

$dbhost = "localhost";
$dbuser = "kenny";
$dbpass = "kc226975";
$dbname = "grocery";

//Connect to MySQL Server
mysql_connect($dbhost, $dbuser, $dbpass);

//Select Database
mysql_select_db($dbname) or die(mysql_error());

// Retrieve data from Query String and Escape User Input to help prevent SQL Injection
$newItem = htmlentities(strip_tags(mysql_real_escape_string($_GET['item'])));
$sql = "INSERT INTO `grocery`.`items` (`id`, `name`, `user`, `done`, `created`) VALUES (NULL, '$newItem', '', '', CURRENT_TIMESTAMP);";
//$query = "INSERT INTO `grocery`.`items` (`id`, `name`, `user`, `done`, `created`) VALUES ('', '$newItem', '', '', 'CURRENT_TIMESTAMP')";
mysql_query($sql) or die(mysql_error());
//build query
$query1 = "SELECT * FROM `items`";

//Execute query
$qry_result = mysql_query($query1) or die(mysql_error());

//Build Result String <div id="item" ></div><br>
// Insert a new row in the table for each person returned
while($row = mysql_fetch_assoc($qry_result)){
	echo "<div id=\"item\" data-itemID=\"" . $row['id'] . "\">" . $row['name'] . "</div>";
}