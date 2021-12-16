<?php
	
	require "connect.php";

	$db->query("SET @@GLOBAL.event_scheduler = 1;");

?>