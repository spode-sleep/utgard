<?php
	require "connect.php";

	if(isset($_COOKIE['token_hod']))
	{
		logout($_COOKIE['token_hod']);
		header('Location: ../index.php'); 
	}

	$db->close();
?>