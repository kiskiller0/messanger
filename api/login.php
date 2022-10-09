<?php
require_once("../db/connection.php");
require_once("../php/functions.php");

if (($username = $_POST['username']) && ($givenPassword = $_POST['password'])) {
	$realPassword = $USER->getWhere("password", "username", $username)['password'];
	if (!$realPassword) {
		die("no such user in db!");
	}
	if ($realPassword == $givenPassword) {
		echo "correct password!";
	} else {
		echo "incorrect password";
	}
} else {
	echo "required fields not filled!";
}
