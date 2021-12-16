<!DOCTYPE html>
<html>
<head>
	<title>Admin panel</title>
<style type="text/css">
	.tabs {
  position: relative;   
  min-height: 200px; /* This part sucks */
  clear: both;
  margin: 25px 0;
}
.tab {
  float: left;
}
.tab > label {
  background: #eee; 
  padding: 10px; 
  border: 1px solid #ccc; 
  margin-left: -1px; 
  position: relative;
  left: 1px; 
}
.tab > .tab_radio{
  display: none;   
}
.content {
  position: absolute;
  top: 28px;
  left: 0;
  background: white;
  right: 0;
  bottom: 0;
  padding: 20px;
  border: 1px solid #ccc; 
}
.tab_radio:checked ~ label {
  background: white;
  border-bottom: 1px solid white;
  z-index: 2;
}
.tab_radio:checked ~ label ~ .content {
  z-index: 1;
}

.radio_suggest:checked + .label_suggest {
  font-weight: bold;

}

.label_suggest{
	white-space: pre-wrap;
}
</style>
<script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>
</head>
<body>

<?php

if(isset($_COOKIE["token_hod"]))
{
	require "db/connect.php";

	$user_id = get_user_id($_COOKIE["token_hod"]);
	if($user_id!=-1)
	{
		$req_is_moderator = $db->query("SELECT moderator_id FROM moderators WHERE moderator_id = $user_id");

		if($req_is_moderator -> num_rows == 1)
		{

echo
	'<div class="tabs">
    
   <div class="tab">
       <input type="radio" id="new_tag_tab" name="tab-group-1" checked class="tab_radio" onclick = "picked_tab(1)">
       <label for="new_tag_tab">New tag</label>
       
       <div class="content" id="new_tag_content">';
     
    $req_new_tag_sug = $db -> query("SELECT id,id_user,text_arg_32,text_arg_text FROM suggestions WHERE type = 'new tag'");
    while ($row = $req_new_tag_sug->fetch_array())
    {
    	echo '<div id = "sug_'.$row[0].'"><input type="radio" class="radio_suggest" name="tab_group_new_tag" value = "'.$row[0].'">
  			  <label class="label_suggest">'.$row[0].':'.$row[1].':'.$row[2].':'.$row[3].'</label><br></div>';
    }

echo       '</div> 
   </div>
    
   <div class="tab">
       <input type="radio" id="edit_tag_tab" name="tab-group-1" class="tab_radio" onclick = "picked_tab(2)">
       <label for="edit_tag_tab">Edit tag</label>
       
       <div class="content" id="edit_tag_content">';

    $req_edit_tag_sug = $db -> query("SELECT id,id_user,id_tag,text_arg_text FROM suggestions WHERE type = 'edit tag'");
    while ($row = $req_edit_tag_sug->fetch_array())
    {
    	echo '<div id = "sug_'.$row[0].'"><input type="radio" class="radio_suggest" name="tab_group_edit_tag" value = "'.$row[0].'">
  			  <label class="label_suggest">'.$row[0].':'.$row[1].':<a href="tag_page.php?id='.$row[2].'">'.$row[2].'</a>:'.$row[3].'</label><br></div>';
    }

echo       '</div> 
   </div>
    
    <div class="tab">
       <input type="radio" id="delete_tab" name="tab-group-1" class="tab_radio" onclick = "picked_tab(3)">
       <label for="delete_tab">Delete image</label>
     
       <div class="content" id="delete_content">';

    $req_delete_sug = $db -> query("SELECT id,id_user,id_pic FROM suggestions WHERE type = 'delete'");
    while ($row = $req_delete_sug->fetch_array())
    {
    	echo '<div id = "sug_'.$row[0].'"><input type="radio" class="radio_suggest" name="tab_group_delete" value = "'.$row[0].'">
  			  <label class="label_suggest">'.$row[0].':'.$row[1].':<a href="img_page.php?id='.$row[2].'">'.$row[2].'</a></label><br></div>';
    }
          

echo '</div> 
   </div>

   <div class="tab">
       <input type="radio" id="change_tags_tab" name="tab-group-1" class="tab_radio" onclick = "picked_tab(4)">
       <label for="change_tags_tab">Change tags</label>
     
       <div class="content" id="change_tags_content">';

    $req_tag_chan_sug = $db -> query("SELECT id,id_user,id_pic FROM suggestions WHERE type = 'tag chan'");
    while ($row = $req_tag_chan_sug->fetch_array())
    {
    	echo '<div id = "sug_'.$row[0].'"><input type="radio" class="radio_suggest" name="tab_group_tag_chan" value = "'.$row[0].'">
  			  <label class="label_suggest">'.$row[0].':'.$row[1].':<a href="img_page.php?id='.$row[2].'">'.$row[2].'</a></label><br></div>';
    }
           
echo       '</div> 
   </div>
    
</div>
<br>
<div id="buttons"><button id="accept" onclick="process_sug(1)">Accept</button><button id="reject" onclick="process_sug(0)">Reject</button></div>';
}
}
}
?>

<script type="text/javascript">
	var tab = 1;
	var name_of_tab = "tab_group_new_tag";

	function picked_tab(num)
	{
		tab = num;
		switch(num)
		{
			case 1: name_of_tab = "tab_group_new_tag"; break;
			case 2: name_of_tab = "tab_group_edit_tag"; break;
			case 3: name_of_tab = "tab_group_delete"; break;
			case 4: name_of_tab = "tab_group_tag_chan"; break;
		}
		if(num < 4)
			document.getElementById("buttons").innerHTML = "<button id='accept' onclick='process_sug(1)'>Accept</button><button id='reject' onclick='process_sug(0)'>Reject</button>";
		else
			document.getElementById("buttons").innerHTML = "<button id='done' onclick='process_sug(0)'>Done</button>";
		
	}

	function get_checked_value_by_name(name)
	{
		var radio_buttons = document.getElementsByName(name);
		for(var i=0;i<radio_buttons.length;i++)
			if(radio_buttons[i].checked)
				return radio_buttons[i].value;
		return false;
	}

	function process_sug(is_accepted)
	{
		id_to_process = get_checked_value_by_name(name_of_tab);
		if(id_to_process)
		{
			$.ajax({
				url:is_accepted?"db/accept_suggestion.php":"db/delete_suggestion.php",
				type:"POST",
				data:{"id":id_to_process},
				success:function(data){
					alert(data);
					document.getElementById("sug_"+id_to_process).parentNode.removeChild(document.getElementById("sug_"+id_to_process));
				},
				error:function(){
					alert("error during deletion of suggestion");
				}
			})
		}
	}
</script>
</body>
</html>