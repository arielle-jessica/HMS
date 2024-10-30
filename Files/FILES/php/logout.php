<?php
	session_start();
	$_SESSION=array();
	session_destroy();
	header('Location: http://localhost/CP3520/index.html');
?>

