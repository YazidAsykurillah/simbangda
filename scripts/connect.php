<?php

$db = new mysqli('localhost', 'root', '', 'simbangdabaru');

if($db->connect_error){
	
	die("Sorry, we have some problems $db->connect_error");
}
