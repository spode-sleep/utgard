<!DOCTYPE html>
<html>
<head>
	<title>Tag page</title>

<link rel="stylesheet" type="text/css" href="css/pic_borders.css">
<style type="text/css">
	#current_description{
		white-space: pre-wrap;
	}
</style>

</head>
<body>
<?php 
if(isset($_GET["id"]))
{
	require_once "db/connect.php";

	$tag_id = $_GET["id"];

	$req_is_exist = $db -> prepare("SELECT id FROM tags WHERE id = ?");
	$req_is_exist -> bind_param('i',$tag_id);
	$req_is_exist -> execute();
	$result_is_exist = $req_is_exist -> get_result();
	if($result_is_exist -> num_rows != 0)
	{

    	require "db/auth_header.php";

    	$user_id = get_user_id($_COOKIE['token_hod']);
    	$is_allowed_to_edit = "";
    	if($user_id != -1)
    		$is_allowed_to_edit = "<a id='edit' href='#' onclick = 'start_editing()'>Edit</a><br>";

    	$req_tag_info = $db->prepare("SELECT tag,description FROM tags WHERE id = ?");
    	$req_tag_info -> bind_param("i",$tag_id);
    	$req_tag_info -> execute();
    	$tag_info = $req_tag_info -> get_result();
    	$tag_info = $tag_info -> fetch_all(MYSQLI_ASSOC);

    	echo "<h2>".$tag_info[0]["tag"]."</h2>
    <br>
    <div id = 'description'>".$is_allowed_to_edit."<span id = 'current_description'>".$tag_info[0]["description"]."</span></div>
    <br>
    <h4>Examples</h4>
    <br>
    <div id = 'examples'>";

    	$req_examples = $db->prepare("SELECT id, filepath FROM pics INNER JOIN (SELECT id_picture FROM tags_of_pics WHERE id_tag = ?) as rightTagged ON rightTagged.id_picture = pics.id ORDER BY pics.last_update DESC LIMIT 4");
    	$req_examples -> bind_param("i",$tag_id);
    	$req_examples -> execute();
    	$examples = $req_examples -> get_result();
    	$examples = $examples -> fetch_all(MYSQLI_ASSOC);
    	for($i=0;$i<count($examples);$i++)
    		echo "<a href = 'img_page.php?id=".$examples[$i]["id"]."' class = 'pic_a'><img src='db/".$examples[$i]["filepath"]."' class = 'pic'></a>";
    echo "</div>";

	}
}

?>

<script type="text/javascript">
current_description = document.getElementById('current_description').innerHTML;

	function start_editing()
	{
		document.getElementById('description').innerHTML = "<a id='cancel' href='javascript:void(0);' onclick = 'cancel_editing()'>Cancel</a><br><form method = 'POST' action = 'db/suggest_edit_tag.php'><input type ='hidden' name = 'tag_id' value = '<?php echo $tag_id; ?>'><textarea rows='10' cols='45' name='tag_description' placeholder = 'Description'>"+current_description+"</textarea><br><input type = 'submit' value = 'Suggest' id = 'send'></form>";
	}

	function cancel_editing()
	{
		document.getElementById('description').innerHTML = "<a id='edit' href='javascript:void(0);' onclick = 'start_editing()'>Edit</a><br><span id = 'current_description'>"+current_description+"</span>";
	}

</script>

</body>
</html>