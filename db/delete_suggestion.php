<?php
if(isset($_COOKIE["token_hod"]) && isset($_POST["id"]))
{
	require "connect.php";

	$user_id = get_user_id($_COOKIE["token_hod"]);
	if($user_id!=-1)
	{
		$req_is_moderator = $db->query("SELECT moderator_id FROM moderators WHERE moderator_id = $user_id");

		if($req_is_moderator -> num_rows == 1)
		{
			$id = intval($_POST["id"]);
			$req = $db->prepare("DELETE FROM suggestions WHERE id = ?");
			$req-> bind_param("i",$id);
			$req-> execute();
		}
	}
}
?>