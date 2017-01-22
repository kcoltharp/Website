<?php

$dbhost = "localhost";
$dbuser = "kenny";
$dbpass = "kc226975";
$dbname = "grocery";

//Connect to MySQL Server
mysql_connect($dbhost, $dbuser, $dbpass);

//Select Database
mysql_select_db($dbname) or die(mysql_error());

//$itemName = $_GET['name'];
$itemID = $_GET['itemID'];

$item_ID = htmlentities(strip_tags(mysql_real_escape_string($itemID)));
//$item_Name = htmlentities(strip_tags(mysql_real_escape_string($itemName)));

$sql = "DELETE FROM `items` WHERE `id`=" . $item_ID;

$qry_result = mysql_query($sql) or die(mysql_error());

$sql1 = "SELECT * FROM `items` ORDER BY `id` ASC ";

//Execute query
$qry_result1 = mysql_query($sql1) or die(mysql_error());

//Build Result String <div id="item" ></div><br>
// Insert a new row in the table for each person returned
$displayString1 = null;
while($row = mysql_fetch_assoc($qry_result1)){
	$displayString1 .= "<div id=\"item\" data-itemID=\"" . $row['id'] . "\">" . $row['name'] . "</div>";
}

echo $displayString1;
