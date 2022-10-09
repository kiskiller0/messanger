<?php

// NULL! VOID

function nothing()
{
	echo "nothing!";
}


function pre($arr)
{
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
}

function sanitize($value, $conn)
{
	$value = htmlentities(trim($value));
	$value = mysqli_real_escape_string($conn, $value);
	return $value;
}
