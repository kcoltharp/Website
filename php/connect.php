<?php

$link = mysql_connect('localhost', 'kenny', 'kc226975');

if(!$link){
	die("Could not connect: " . mysql_error());
}else{
	mysql_select_db('grocery');
}