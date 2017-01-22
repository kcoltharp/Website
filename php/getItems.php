<?php

require 'init.php';
include 'connect.php';

$sql = "SELECT * FROM `items` ORDER BY `id` ASC ";

//Execute query
$qry_result = mysql_query($sql) or die(mysql_error());

//Build Result String <div id="item" ></div><br>
// Insert a new row in the table for each person returned
$displayString = null;
while($row = mysql_fetch_assoc($qry_result)){
	//$displayString .= "<input type=\"checkbox\" id=\"item\" class=\"myCheckbox\" data-itemID=\"$key\">&nbsp;" . $row['name'] . "<br />";
	$displayString .= "<div id=\"item\" data-itemID=\"" . $row['id'] . "\">" . $row['name'] . "</div>";
}

echo $displayString;
