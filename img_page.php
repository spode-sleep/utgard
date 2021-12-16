<!DOCTYPE html>
<html>
<head>
	<title>Picture page</title>
<script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>
<style type="text/css">
	.user_part_img{
		position:absolute;				/*Must be changed*/
		width:1024px;
		height:512px;
		left:0;
		opacity: 1;
	}
</style>
</head>
<body>
	<?php 
    require "db/auth_header.php";
?>
	<?php
	$img_id = $_GET['id'];

	$user_id = get_user_id($_COOKIE["token_hod"]);							//get it from cookies

	$user_drawn_parts_counter = 0;
	$user_drawn_parts_array = array();
	$authors_counter = 0;
	$authors_array = array();

	echo "<div id='menu'>";

	echo "<h3>Artists</h3>";

	$authors = array();
	$req_list_of_authors = $db->prepare("SELECT id_author,number_of_change FROM authors WHERE id_picture = ?");
	$req_list_of_authors->bind_param("i",$img_id);
	$req_list_of_authors->execute();
	$authors_id = $req_list_of_authors->get_result();
	while($author_id = $authors_id->fetch_array())
	{
		array_push($authors, $author_id[0]);

		$req_name = $db->query("SELECT login FROM users WHERE id = $author_id[0]");
		$current_author_name = $req_name->fetch_all(MYSQLI_ASSOC);
		$current_author_name = $current_author_name[0]['login'];

		$find_author_flag = false;
		if(count($authors_array))
			{for($i=0;$i<count($authors_array);$i++)
				if($authors_array[$i] === $current_author_name)
				{
					$find_author_flag = true;
					break;
				}
			}
		if(!$find_author_flag)
		{
			$authors_array[$authors_counter++] = $current_author_name;
			echo "<input type='checkbox' checked='checked' user_id_checkbox = '$author_id[0]' onchange='checkbox_change($author_id[0])' class = 'checkbox'><a onmouseenter = 'show_part_of_image($author_id[0])' onmouseleave='hide_part_of_image($author_id[0])' class = 'author_name' href='search.php?search_req_type=artist&search_req=$current_author_name'>$current_author_name</a><br>";
		}

		$user_drawn_parts = glob('db/img/'.$img_id.'/'.$img_id.'_'.$author_id[0].'_'.$author_id[1].'_*.png');
		$user_drawn_parts_array[$user_drawn_parts_counter] = "<img src = '$user_drawn_parts[0]' class = 'user_part_img' user_id = '$author_id[0]' style='z-index: $author_id[1]'>";
				$user_drawn_parts_counter++;
	}


	echo "<h3>Tags</h3>";

	$req_is_moderator = $db->query("SELECT * FROM moderators WHERE moderator_id = $user_id");

	$req_list_of_tags = $db->prepare("SELECT tags.tag,tags.id FROM
	 tags INNER JOIN tags_of_pics ON tags.id = tags_of_pics.id_tag
	 WHERE tags_of_pics.id_picture = ?");
	$req_list_of_tags->bind_param("i",$img_id);
	$req_list_of_tags->execute();
	$tags= $req_list_of_tags->get_result();
	while($tag = $tags->fetch_array())
		{
			echo "<a href = 'tag_page.php?id=$tag[1]'>?</a> <a class = 'tag_name' href='search.php?search_req_type=tag&search_req=$tag[0]'>$tag[0]</a>";
			if($req_is_moderator -> num_rows)
				echo "<form action = 'db/delete_tag.php' method='POST'><input type = 'hidden' name = 'img_id' value = '$img_id'><input type = 'hidden' name='tag_id' value = '$tag[1]'><input type='submit' value='Del'></form>";
			echo "<br>";
		}


	$req_state = $db->prepare("SELECT count_of_changes FROM pics WHERE id = ?");
	$req_state->bind_param("i",$img_id);
	$req_state->execute();
	$state = $req_state->get_result();
	$state = $state ->fetch_array();
	$state = $state[0];

	echo "<br><span>Current state: </span>";

	if($state!=6)
		echo "<span style='color:red'>In progress</span>";
	else
		echo "<span style='color:green'>Finished</span>";

	$req_likes = $db->prepare("SELECT count(*) FROM
		likes WHERE id_picture = ?");
	$req_likes->bind_param("i",$img_id);
	$req_likes->execute();
	$likes= $req_likes->get_result();
	$likes = $likes->fetch_array();
	$likes = $likes[0];

	echo "<h5>Likes: <span id='count_of_likes'>$likes</span></h5>";

	$req_is_already_liked = $db->prepare("SELECT id_picture FROM
		likes WHERE id_picture = ? and id_user = ?");
	$req_is_already_liked->bind_param("ii",$img_id,$user_id);
	$req_is_already_liked->execute();
	$req_is_already_liked = $req_is_already_liked->get_result();
	if($req_is_already_liked->num_rows || $user_id == -1 || !isset($_GET['id']))
		echo "<button disabled>I like it!</button>";
	else
		echo "<button id='like_button'>I like it!</button>";

	

	if(in_array($user_id,$authors) || $req_is_moderator -> num_rows)
		echo "<br><a href='tag_search.php?editing_img=$img_id'>Add tags</a>";

	if($user_id!=-1)
		echo "<br><a href='javascript:void(0);' id='deletion' onclick = 'flag_for_deletion()'>Flag for deletion</a><br><a href='javascript:void(0);' id='tag_change' onclick = 'flag_for_tag_change()'>Flag for change tags</a>";

	echo "</div>";

	echo "<div id = 'pic'>";

	for($i=0;$i<count($user_drawn_parts_array);$i++)
		echo $user_drawn_parts_array[$i];

	echo "</div>";

	?>

<script type="text/javascript">
		function show_part_of_image(user_id)
		{
			document.querySelectorAll('[user_id="'+user_id+'"]').forEach(function(element)
				{
					element.style.opacity = "1";
				});
		}

		function hide_part_of_image(user_id)
		{
			if(!(document.querySelectorAll('[user_id_checkbox="'+user_id+'"]'))[0].checked)
				{
					document.querySelectorAll('[user_id="'+user_id+'"]').forEach(function(element)
					{
						element.style.opacity = "0.25";
					});
				}
		}


		function checkbox_change(user_id){
			/*
			var isAnyChecked = false;
			Array.prototype.forEach.call(document.getElementsByClassName('checkbox'),function(element){			//ancient magic
				if(element.checked)
				{
					isAnyChecked = true;
					document.querySelectorAll('[user_id="'+element.getAttribute("user_id_checkbox")+'"]').forEach(function(element)
					{
						element.style.opacity = "1";
					});
				}
				else
				{
					document.querySelectorAll('[user_id="'+element.getAttribute("user_id_checkbox")+'"]').forEach(function(element)
					{
						element.style.opacity = "0.25";
					});
				}	
			})
			*/
			if((document.querySelectorAll('[user_id_checkbox="'+user_id+'"]'))[0].checked)
				document.querySelectorAll('[user_id="'+user_id+'"]').forEach(function(element)
					{
						element.style.opacity = "1";
					});
			else
				document.querySelectorAll('[user_id="'+user_id+'"]').forEach(function(element)
					{
						element.style.opacity = "0.25";
					});
		} 

		function set_like()
		{
			document.getElementById('like_button').disabled = true;
			document.getElementById('like_button').id = "";
			var img_id = "<?php echo $img_id?>";
			$.ajax(
			{
				url:"db/set_like.php",
				type:"POST",
				data:{"img_id":img_id},
				success:
				function(){
					document.getElementById('count_of_likes').innerHTML = parseInt(document.getElementById('count_of_likes').innerHTML) + 1;
				},
				error:
				function(){
					alert("Error during setting like");
				}
			});
		}


		function send_to_search(type,search_req)
		{
			window.location.href = "search.php?search_req_type="+type+"&search_req="+search_req;
		}

		
		function flag_for_deletion()
		{
			var img_id = "<?php echo $_GET['id']; ?>";
			if(img_id!="")
			$.ajax({
				url:"db/suggest_deletion.php",
				type:"POST",
				data:{"img_id":img_id},
				success:function(){
					document.getElementById('deletion').onclick = function(){};
				},
				error: function(){
					alert("error during suggestion of deletion");
				}
			});
		}

		function flag_for_tag_change()
		{
			var img_id = "<?php echo $_GET['id']; ?>";
			if(img_id!="")
			$.ajax({
				url:"db/suggest_tag_change.php",
				type:"POST",
				data:{"img_id":img_id},
				success:function(){
					document.getElementById('tag_change').onclick = function(){};
				},
				error: function(){
					alert("error during suggestion of tag change");
				}
			});
		}

		document.getElementById('like_button').addEventListener("click", function(){set_like();});

		$("#pics").position({my:"right",at:"center",of:"#menu"});


</script>
</body>
</html>