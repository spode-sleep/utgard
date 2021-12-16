<?php 
	require "connect.php";

	$sort = $_GET["sort"];
	$offset = $_GET["offset"];
	$user_id = get_user_id($_COOKIE["token_hod"]);

	$order_column = "";
	$joining_table = "";
	$joining_table_name = "";
	$joining_type = "";
	$req = "";

	if($sort == "date")
		{
			$req = $db->prepare("SELECT DISTINCT pics.id as id,pics.filepath as filepath FROM pics INNER JOIN authors ON pics.id = authors.id_picture WHERE authors.id_author = ?
				ORDER BY pics.last_update DESC
				LIMIT 40 OFFSET ?");
		}
	else
		{

			$req = $db->prepare("SELECT DISTINCT pics.id as id,pics.filepath as filepath FROM pics LEFT JOIN (SELECT COUNT(*) as rating, id_picture as id
			FROM likes
			GROUP BY id_picture) as likes_count
			ON pics.id = likes_count.id
			WHERE pics.id IN (SELECT id_picture FROM authors WHERE id_author = ?) AND pics.last_update <> 0
			ORDER BY likes_count.rating DESC
			LIMIT 40 OFFSET ?");
		}
	$req -> bind_param('ii',$user_id,$offset);
	$req -> execute();
	$result = $req -> get_result();
	$id_with_filenames = array();
	while($row = $result -> fetch_array())
	{
		$img_data = array('id'=>$row[0],'filename'=>$row[1]);
		array_push($id_with_filenames,$img_data);
	}

	echo json_encode($id_with_filenames,JSON_FORCE_OBJECT);

	$req -> free_result();
	$db->close();
?>