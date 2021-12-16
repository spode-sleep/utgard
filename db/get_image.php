<?php
	

 	if(isset($_COOKIE['token_hod']))
 	{
 		require "connect.php";

 		$user_id = get_user_id($_COOKIE['token_hod']);

 		if($user_id!=-1)
 		{

 		$req_is_user_has_another_drawing_session = $db -> query("SELECT id_user FROM drawing_sessions WHERE id_user = $user_id");
 		if($req_is_user_has_another_drawing_session->num_rows != 0)
 			echo json_encode(array("pic_filepath" => "err"), JSON_FORCE_OBJECT);
 		else
 		{
			$req = $db->query("SELECT id,filepath,count_of_changes FROM pics WHERE count_of_changes < 6 AND id NOT IN (SELECT id_pic FROM drawing_sessions)");
			while($req->num_rows == 0)
			{
				$db->query("INSERT INTO pics VALUES ()");
				$req = $db->query("SELECT id,filepath,count_of_changes FROM pics WHERE count_of_changes < 6 AND id NOT IN (SELECT id_pic FROM drawing_sessions)");
			}
			$available_pics = $req->fetch_all(MYSQLI_ASSOC);
			$rand_index = rand(0,$req->num_rows-1);
			$chosen_id = $available_pics[$rand_index]["id"];
			$chosen_pic = $available_pics[$rand_index]["filepath"];
			$chosen_count_of_changes = $available_pics[$rand_index]["count_of_changes"];

			//$db->query("UPDATE pics SET is_in_processing = $user_id WHERE id = $chosen_id");
			$db -> query("INSERT INTO drawing_sessions(id_user,id_pic,date_of_start,number_of_change) VALUES ($user_id,$chosen_id,now(),$chosen_count_of_changes)");

			echo json_encode(array("pic_filepath" => $chosen_pic), JSON_FORCE_OBJECT);
		}
		}

		
		$db->close();
	}

?>