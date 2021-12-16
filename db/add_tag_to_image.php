<?php
	if(isset($_COOKIE["token_hod"]) && isset($_POST["tag_id"]) && isset($_POST["pic_id"]))
	{
		require_once "connect.php";

		$user_id = get_user_id($_COOKIE["token_hod"]);
		if($user_id!=-1)
		{
			$req_to_check_is_exist = $db -> prepare("SELECT id_picture FROM tags_of_pics WHERE id_picture = ? and id_tag = ?");
			$req_to_check_is_exist -> bind_param("ii",$_POST["pic_id"],$_POST["tag_id"]);
			$req_to_check_is_exist -> execute();
			$copies = $req_to_check_is_exist -> get_result();
			if($copies -> num_rows == 0)
			{
				$req_to_check_is_author = $db -> prepare("SELECT id_picture FROM authors WHERE id_picture = ? and id_author = ?");
				$req_to_check_is_author -> bind_param("ii",$_POST["pic_id"],$user_id);
				$req_to_check_is_author -> execute();
				$author = $req_to_check_is_author -> get_result();

				$req_is_moderator = $db->query("SELECT * FROM moderators WHERE moderator_id = $user_id");
				if($author -> num_rows != 0 || $req_is_moderator -> num_rows != 0)
					{
						$req = $db->prepare("INSERT INTO tags_of_pics(id_picture,id_tag) VALUES (?,?)");
						$req -> bind_param("ii",$_POST["pic_id"],$_POST["tag_id"]);
						$req -> execute();
					}
				else
					echo 'You are not an author of this work';
			}
			else
				echo 'An attempt to add copy of tag';
		}
		else
			echo 'A wrong cookie';
	}
?>