<!DOCTYPE html>
<html>
<head>
	<title>Tag creation</title>
<style type="text/css">
	.error
	{
		color: red;
	}
</style>
</head>
<body>
<?php
	require_once 'db/auth_header.php';
?>
<h2>Tag creation</h2>
<span id="tag_errors" class="error"></span>
<form method = "POST" action = "db/suggest_new_tag.php">
	<input type = "text" name = "tag_name" placeholder = "Tag name" id = "tag_name" oninput = "check_tag_name()"><br>
	<textarea rows="10" cols="45" name="tag_description" placeholder = "Description"></textarea><br>
	<input type = "submit" value = "Send" id = "send" disabled>
</form>


<script type="text/javascript">
	function check_tag_name()
	{
		var possible_tag_name = document.getElementById('tag_name').value;
		var error = false;
		if(possible_tag_name.length<1)
			error = "Too short tag.";
		else if(possible_tag_name.length>15)
			error = "Too long tag.";
		else
		{
			var regexp = /^[A-Z0-9_()]+$/i;
			if(!regexp.test(possible_tag_name))
				error = "Available characters: (A-Z,a-z,_,),(,0-9).";
		}
		if(error)
		{
			document.getElementById("tag_errors").innerHTML = error;
			document.getElementById("send").disabled = true;
		}
		else
		{
			document.getElementById("tag_errors").innerHTML = "";
			document.getElementById("send").disabled = false;
		}
	}
</script>
</body>
</html>