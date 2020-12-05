<?php
$link = mysqli_connect("localhost", "q9505304_bd", "mami115N", "q9505304_bd");
if ($link==false) 
{
	echo "Error.";
	echo mysqli_connect_error();
	exit();
}
?>