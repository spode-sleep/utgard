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

			$id = $_POST["id"];

			$req_data = $db -> prepare("SELECT * FROM suggestions WHERE id = ?");
			$req_data -> bind_param("i",$id);
			$req_data -> execute();
			$res_data = $req_data->get_result();

			if($res_data -> num_rows == 1)
			{
				$sug = $res_data -> fetch_assoc();
				switch($sug["type"])
				{
					case "new tag": $req = $db->prepare("INSERT INTO tags(tag,description) VALUES (?,?)");
					$req -> bind_param("ss",$sug["text_arg_32"],$sug["text_arg_text"]);
					$req -> execute();
					break;
					case "edit tag": $req = $db->prepare("UPDATE tags SET description = ? WHERE id = ?");
					$req -> bind_param("si",$sug["text_arg_text"],$sug["id_tag"]);
					$req -> execute();
					break;
					case "delete": 
					$req_filepath = $db->prepare("SELECT filepath FROM pics WHERE id = ?");
					$req_filepath -> bind_param("i",$sug["id_pic"]);
					$req_filepath -> execute();
					$filepath = $req_filepath -> get_result();
					$filepath = $filepath -> fetch_array();
					$filepath = $filepath[0];
					unlink($filepath);
					array_map('unlink', glob("img/".$sug['id_pic']."/*.*"));
					rmdir("img/".$sug['id_pic']);

					$req = $db->prepare("DELETE FROM pics WHERE id = ?");
					$req -> bind_param("i",$sug["id_pic"]);
					$req -> execute();
					$req_delete_from_authors = $db->prepare("DELETE FROM authors WHERE id_picture = ?");
					$req_delete_from_authors -> bind_param("i",$sug["id_pic"]);
					$req_delete_from_authors -> execute();
					$req_delete_from_tags_of_pics = $db->prepare("DELETE FROM tags_of_pics WHERE id_picture = ?");
					$req_delete_from_tags_of_pics -> bind_param("i",$sug["id_pic"]);
					$req_delete_from_tags_of_pics -> execute();
					$req_delete_from_likes = $db->prepare("DELETE FROM likes WHERE id_picture = ?");
					$req_delete_from_likes -> bind_param("i",$sug["id_pic"]);
					$req_delete_from_likes -> execute();
					break;
				}

				$req_to_delete = $db->prepare("DELETE FROM suggestions WHERE id = ?");
				$req_to_delete-> bind_param("i",$id);
				$req_to_delete-> execute();
			}
		}
	}
}
?>