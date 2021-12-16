<!DOCTYPE html>
<html>
<head>
	<title>Tags</title>
<script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>

<link href="css/loader.css" rel="stylesheet">
<style type="text/css">		
	.user_img{				/*Must be changed*/
		width:512px;	
		height:256px;
	}
</style>

<?php
$already_setted_tags = array();
$user_id = -1;
$is_allowed_to_add_tags = false;

	if(isset($_GET['editing_img']) && isset($_COOKIE['token_hod']))
	{
		require_once 'db/connect.php';

		$user_id = get_user_id($_COOKIE['token_hod']);
		if($user_id!=-1)
		{
			$req_is_author = $db->prepare("SELECT * FROM authors WHERE id_author = $user_id and id_picture = ?");
			$req_is_author -> bind_param("i",$_GET['editing_img']);
			$req_is_author -> execute();
			$res_is_author = $req_is_author -> get_result();
			$req_is_moderator = $db->query("SELECT * FROM moderators WHERE moderator_id = $user_id");

			if($req_is_author->num_rows || $req_is_moderator->num_rows)
			{
				$is_allowed_to_add_tags = true;
				$req = $db -> prepare("SELECT id_tag FROM tags_of_pics WHERE id_picture = ?");
				$req -> bind_param("i",$_GET['editing_img']);
				$req -> execute();
				$result = $req->get_result();
				while($row = $result -> fetch_array())
					array_push($already_setted_tags,$row[0]);
			}
		}
	}
	else
	{
		require_once 'db/connect.php';

		$user_id = get_user_id($_COOKIE['token_hod']);
	}
$already_setted_tags = json_encode($already_setted_tags);
?>
</head>
<body>
<?php 
require 'db/auth_header.php'; 

	if(isset($_GET['editing_img']) && isset($_COOKIE['token_hod']) && $user_id!=-1)
	{
		echo "<h3>Current picture to tag</h3><br>";

		$req_pic_file = $db -> prepare("SELECT filepath FROM pics WHERE id = ?");
		$req_pic_file -> bind_param("i",$_GET['editing_img']);
		$req_pic_file -> execute();
		$filepath = $req_pic_file -> get_result();
		$filepath = $filepath->fetch_array();
		$filepath = $filepath[0];

		echo "<img class='user_img' src = 'db/".$filepath."'><br>";
	}

	if($user_id != -1)
			echo '<a href="tag_creation.php"><button>Create tag</button></a><br>';
	else
			echo '<button disabled>You must log in to create tags</button><br>';

?>


<input id = "search_string" type="text" name = "reqest_str" placeholder = "example:picture">
<input type="submit" value="search" onclick = "search_tags(true)">

<table id="search_result"></table>
<div id="loader"></div>

<script type="text/javascript">
var search_req;
var offset;
var loading_in_process;
var picked_tags = [];

	function getScrollPercent() {
    var h = document.documentElement, 
        b = document.body,
        st = 'scrollTop',
        sh = 'scrollHeight';
    return (h[st]||b[st]) / ((h[sh]||b[sh]) - h.clientHeight) * 100;
}

	function search_tags(isFirstCall)
	{
		var is_add_tags_state = "<?php echo $_GET['editing_img']?>";
		var already_setted_tags = "<?php echo $already_setted_tags?>";
		var is_allowed_to_add_tags = "<?php echo $is_allowed_to_add_tags; ?>";

		if(isFirstCall)
		{
			search_req = document.getElementById('search_string').value;
			offset = 0;
		}
		document.getElementById("loader").classList.add('loader');
		loading_in_process = true;

		$.ajax({
			url:"db/search_tags_main.php",
			type:"GET",
			data:{"search_string":search_req,"offset":offset},
			success: function(data){
				tags = JSON.parse(data);
				html_tags = "";
				document.getElementById("loader").classList.remove('loader');
				loading_in_process = false;
				if(Object.keys(tags).length)
						{
							if(is_add_tags_state == "")
							{
								for(var i=0;i<Object.keys(tags).length;i++)
								{
									html_tags = html_tags + "<tr><td>"+(tags[i]["count_of_pics"]!==null?tags[i]["count_of_pics"]:0)+"</td><td><a href = 'tag_page.php?id="+tags[i]["id"]+"'>"+tags[i]["tag"]+"</a></td></tr>";
								}
							}
							else
							{
								for(var i=0;i<Object.keys(tags).length;i++)
								{
									html_tags = html_tags + "<tr><td>"+(tags[i]["count_of_pics"]!==null?tags[i]["count_of_pics"]:0)+"</td><td><a href = 'tag_page.php?id="+tags[i]["id"]+"'>"+tags[i]["tag"]+"</a></td><td><button onclick = 'add_tag("+tags[i]["id"]+","+is_add_tags_state+")' id='"+tags[i]["id"]+"' "+(picked_tags.includes(tags[i]["id"]) || already_setted_tags.includes(tags[i]["id"]) || (is_allowed_to_add_tags==="" && is_add_tags_state!=="")?"disabled":"")+">Add tag</button></td></tr>";
								}
							}

							if(isFirstCall)
								{
									document.getElementById('search_result').innerHTML = html_tags;
									window.onscroll = function()
									{
										if(getScrollPercent()>95 && !loading_in_process)
										{
											//alert('scroll');
											offset+=40;
											search_tags(false);
										}
									}
								}
							else
								document.getElementById('search_result').innerHTML += html_tags;
						}
				else
				{
					window.onscroll = function(){}
				}
			},
			error: function(){
				alert("error during getting result of tag search");
			}
		});
	}


	function add_tag(tag_id,pic_id){
		document.getElementById(tag_id.toString()).disabled = true;
		picked_tags.push(tag_id);
		$.ajax({
			url:"db/add_tag_to_image.php",
			type:"POST",
			data:{"tag_id":tag_id,"pic_id":pic_id},
			success:function(data){
				if(data!='')
					alert(data);
			},
			error:function(){
				alert("error during adding tag");
			}
		});
	}

</script>
</body>
</html>