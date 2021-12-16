<?php
	require "connect.php";

	$possible_username = $_GET["username"];

	$req = $db -> prepare("SELECT login FROM users WHERE login = ?");
	$req -> bind_param('s',$possible_username);
	$req -> execute();
	$result = $req -> get_result();
	echo $result->num_rows;

	$req->free_result();
	$db->close();
?>