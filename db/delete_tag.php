<?php
	if(isset($_COOKIE['token_hod']) && isset($_POST["img_id"]) && isset($_POST["tag_id"]))
	{
		require_once 'connect.php';

		$user_id = get_user_id($_COOKIE['token_hod']);
		if($user_id!=-1)
			{
				$req_is_moderator = $db->query("SELECT * FROM moderators WHERE moderator_id = $user_id");
				if($req_is_moderator->num_rows)
				{
					$img_id = intval($_POST["img_id"]);
					$tag_id = intval($_POST["tag_id"]);

					$req = $db->prepare("DELETE FROM tags_of_pics WHERE id_picture = ? and id_tag = ?");
					$req -> bind_param("ii",$img_id,$tag_id);
					$req -> execute();
				}
			}
	}

	header('Location:../img_page.php?id='.$_POST["img_id"]);

?>