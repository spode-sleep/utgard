<!DOCTYPE html>
<html>
<head>
	<title>Search</title>
<script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>
<style type="text/css">
	table
	{
		background-color:white;
		position: absolute;
		visibility: hidden;
		border-collapse: collapse;
		z-index: 10;
	}

	#search_string
	{
		width:70%;
	}

	table, th, td {
  		border: 1px solid black;
	}

	tr:hover {background-color: #f5f5f5; cursor: pointer;}

}
</style>
<link href="css/loader.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/pic_borders.css">
</head>
<body>
	<?php 
    require "db/auth_header.php";
?>


	<input id = "search_string" type="text" name = "reqest_str" oninput = "guess_request()" onclick="on_first_click()" placeholder = "example:artist:first_user tag:picture">
	<input type="submit" value="search" onclick = "search(true,'date')">

<br><table id = "search_hint_table"></table>

<div id="sort_buttons"></div>
<br>


<div id="result_of_search" ></div>
<div id="loader"></div>

<script type="text/javascript">
	var search_offset;
	var full_request_parts;
	var show_end;
	var full_start_req;
	var timer;
	var loading_in_process;

function print_r(arr, level) {
    var print_red_text = "";
    if(!level) level = 0;
    var level_padding = "";
    for(var j=0; j<level+1; j++) level_padding += "    ";
    if(typeof(arr) == 'object') {
        for(var item in arr) {
            var value = arr[item];
            if(typeof(value) == 'object') {
                print_red_text += level_padding + "'" + item + "' :\n";
                print_red_text += print_r(value,level+1);
		} 
            else 
                print_red_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
        }
    } 

    else  print_red_text = "===>"+arr+"<===("+typeof(arr)+")";
    return print_red_text;
}

	function getScrollPercent() {
    var h = document.documentElement, 
        b = document.body,
        st = 'scrollTop',
        sh = 'scrollHeight';
    return (h[st]||b[st]) / ((h[sh]||b[sh]) - h.clientHeight) * 100;
}

function array_unique(array){
    return array.filter(function(el, index, arr) {
        return index == arr.indexOf(el);
    });
}

	function search(isFirstCall,sort,search_text = document.getElementById('search_string').value)
	{
		if(isFirstCall)
		{
			full_request_parts = search_text.trim().split(' ').filter(part => part!='');
			search_offset = 0;
			document.getElementById("result_of_search").innerHTML = "";
			$("#result_of_search").fadeOut();
		}
		var artist_parts = [];
		var artist_parts_count = 0;
		var tag_parts = [];
		var tag_parts_count = 0;
		var isValidReq = true;



		document.getElementById('search_hint_table').style.visibility = "hidden";

		document.getElementById("loader").classList.add('loader');
		loading_in_process = true;

		document.getElementById("sort_buttons").innerHTML = "<button id='sort_by_date'>Sort by date</button>"+"<button id='sort_by_rating'>Sort by likes</button>";

		if(sort == "rating")
								{
									document.getElementById('sort_by_rating').disabled = true;
									document.getElementById('sort_by_date').addEventListener("click", function(){search(true,"date");});
								}
								else
								{
									document.getElementById('sort_by_date').disabled = true;
									document.getElementById('sort_by_rating').addEventListener("click", function(){search(true,"rating");});
								}

		if(search_text.trim().length !== 0)
		for(var i=0;i<full_request_parts.length && isValidReq;i++)
			if(full_request_parts[i].startsWith('artist:'))
				{
					full_request_parts[i] = full_request_parts[i].substr('artist:'.length);
					artist_parts[artist_parts_count++] = full_request_parts[i];
				}
			else if(full_request_parts[i].startsWith('tag:'))
				{
					full_request_parts[i] = full_request_parts[i].substr('tag:'.length);
					tag_parts[tag_parts_count++] = full_request_parts[i];
				}
			else 
				isValidReq = false;
		tag_parts = array_unique(tag_parts);
		artist_parts = array_unique(artist_parts);
		if(isValidReq)
			$.ajax({											//ajax req
					url:"db/search_main.php",
					type:"GET",
					data:{'parts_tags[]':tag_parts, 'parts_authors[]': artist_parts, 'search_offset':search_offset, 'sort':sort},
					success:
					function(data)
					{
					pics = JSON.parse(data);
					html_pics = "";
					document.getElementById("loader").classList.remove('loader');
					loading_in_process = false;
					if(Object.keys(pics).length)
						{
							for(var i=0;i<Object.keys(pics).length;i++)
							{

								html_pics = html_pics + "<a href = 'img_page.php?id="+pics[i]["id"]+"' class = 'pic_a'><img src='db/"+pics[i]["filename"]+"' class = 'pic'></a>" + ((i+1)%4==0?"<br>":"");
							}

							if(isFirstCall)
							{
								$("#result_of_search").fadeIn("slow");

								document.getElementById("result_of_search").innerHTML = html_pics;
								


								if(Object.keys(pics).length<40)
								{
									show_end = false;
									document.getElementById("result_of_search").innerHTML += "<br><h3>[END]</h3>";
								}
								else
								{
									show_end = true;
									window.onscroll = function()
									{
										if(getScrollPercent()>80 && !loading_in_process)
										{
											search_offset+=40;
											search(false,sort);
										}
									}
								}
							}
							else
								document.getElementById("result_of_search").innerHTML += html_pics;
						}
					else
					{
						if(isFirstCall)
						{	
							$("#result_of_search").fadeIn("slow");
							document.getElementById("result_of_search").innerHTML = "<h3>Nothing here but fake mirrors</h3>";
						}
						else
							{
								if(show_end)
								{	
									show_end = false;
									window.onscroll = function(){};
									document.getElementById("result_of_search").innerHTML += "<br><h3>[END]</h3>";
								}
							}

					}
					},
			error:
				function()
				{
					alert("error during getting pics");
				}
	});
	else
	{
		window.onscroll = function(){};
		document.getElementById("loader").classList.remove('loader');
		$("#result_of_search").fadeIn("slow");
		document.getElementById("result_of_search").innerHTML = "<h3>Nothing here but fake mirrors</h3>";
	}
	}

	function on_first_click()
	{
		if(document.getElementById('search_string').value.trim().length == 0)
			show_hint_with_only_classificator('both',"");
		else if(full_start_req)
		{
			show_hint_with_only_classificator('both',full_start_req);
			full_start_req = "";
			guess_request();
		}
	}

	function show_hint_with_only_classificator(classificator,prev_parts_joined)
	{
		if(classificator != 'both')
			document.getElementById('search_hint_table').innerHTML = "<tr><td>"+prev_parts_joined+' '+classificator+':</tr></td>';
		else
			document.getElementById('search_hint_table').innerHTML = "<tr><td>"+prev_parts_joined+" artist:</tr></td><tr><td>"+prev_parts_joined+" tag:</tr></td>";
		document.getElementById('search_hint_table').style.visibility = "visible";
			td_s = document.getElementsByTagName('td');
				for(var i=0;i<td_s.length;i++)
								td_s[i].addEventListener("click",function(){
									var value = this.innerHTML;
									document.getElementById('search_string').value = value;
									document.getElementById('search_string').oninput();
								});
		clearTimeout(timer);
		timer = window.setTimeout(function(){hide_hints();},7000);
	}

	function ajax_req(last_part_of_request,prev_parts_t,prev_parts_a,type_of_last_part,prev_parts_joined){
		$.ajax({											//ajax req
					url:"db/search_support.php",
					type:"POST",
					data:{'request':last_part_of_request, 'prev_parts_tags[]': prev_parts_t, 'prev_parts_authors[]': prev_parts_a,'type_of_last_part':type_of_last_part},
					success:
					function(data)
					{
					hints = JSON.parse(data);
					table_rows = "";
					if(document.getElementById('search_string').value.trim().length != 0)
					if(hints.length)
						{
							var type = (type_of_last_part =="artist"?"login":"tag");
							
							var isHintNeeded = false;
							
							for(var i=0;i<hints.length && !isHintNeeded;i++)
								if(hints[i][type].startsWith(document.getElementById('search_string').value.slice(document.getElementById('search_string').value.lastIndexOf(':')+1)))
								{
									isHintNeeded = true;
								}
							//alert(print_r(hints)+"("+hints[0][type].startsWith(document.getElementById('search_string').value.slice(document.getElementById('search_string').value.lastIndexOf(':')+1)));
							if(isHintNeeded)
							{
								for(var i=0;i<hints.length;i++)
								{
								table_rows = table_rows + "<tr><td>"+prev_parts_joined+' '+type_of_last_part+":"+hints[i][type]+" ("+hints[i]["count_of_pics"]+")</td></tr>";
								}
							//console.log(table_rows.slice(8,table_rows.indexOf('</td></tr>'))+'\n'+document.getElementById('search_string').value+'\n'+table_rows.slice(8,table_rows.indexOf('</td></tr>')).startsWith(document.getElementById('search_string').value));
							//if(table_rows.slice(8,table_rows.indexOf('</td></tr>')).startsWith(document.getElementById('search_string').value)){
								document.getElementById('search_hint_table').innerHTML = table_rows;
								document.getElementById('search_hint_table').style.visibility = "visible";
								
								clearTimeout(timer);
								timer = window.setTimeout(function(){hide_hints();},7000);

								td_s = document.getElementsByTagName('td');
								for(var i=0;i<td_s.length;i++)
									td_s[i].addEventListener("click",function(){
										var value = this.innerHTML.slice(0,this.innerHTML.lastIndexOf(' '));
										document.getElementById('search_string').value = value+' ';
										guess_request();
								});
							//}
							}
							
						}
					else
						{
							document.getElementById('search_hint_table').style.visibility = "hidden";
						}
					},
			error:
				function()
				{
					alert("error during getting search hint");
				}
	});
	}

	function guess_request()
	{

		var full_request = document.getElementById('search_string').value.trim();
		var last_divider_index = full_request.lastIndexOf(" ");
		var last_part_of_request = last_divider_index === -1?full_request:full_request.slice(last_divider_index+1);
		var prev_parts = last_divider_index === -1?[]:full_request.slice(0,last_divider_index).split(' ').filter(part => part!='');
		var prev_parts_t = [];
		var count_of_t = 0;
		var prev_parts_a = [];
		var count_of_a = 0;
		var isValidReq = true;
		var	prev_parts_joined;
		if(prev_parts.length!=1)
			prev_parts_joined = prev_parts.join(' ');
		else
			prev_parts_joined = prev_parts;

		if(document.getElementById('search_string').value[document.getElementById('search_string').value.length-1] == ' ')
		{
			prev_parts_joined = full_request.split(' ').join(' ');
			show_hint_with_only_classificator('both',prev_parts_joined);
		}
		else
		{
		for(var i=0;i<prev_parts.length;i++)														//split prev req to artists and tags
			if (prev_parts[i].startsWith('artist:') && prev_parts[i].split(":").length-1 == 1)
				prev_parts_a[count_of_a++] = prev_parts[i];
			else if (prev_parts[i].startsWith('tag:') && prev_parts[i].split(":").length-1 == 1)
				prev_parts_t[count_of_t++] = prev_parts[i];
			else
				{
					isValidReq = false;
					document.getElementById('search_hint_table').style.visibility = "hidden";
					break;
				}
		if(isValidReq)																				//if everything is OK start process new part
			if(last_part_of_request.split(":").length-1 === 0)											//when user writing a classificator
				{
					if(document.getElementById('search_string').value.trim().length == 0)
						show_hint_with_only_classificator('both',prev_parts_joined);
					else if('artist'.startsWith(last_part_of_request))
						show_hint_with_only_classificator('artist',prev_parts_joined);
					else if('tag'.startsWith(last_part_of_request))
						show_hint_with_only_classificator('tag',prev_parts_joined);
					else
						document.getElementById('search_hint_table').style.visibility = "hidden";
				}
			else if((last_part_of_request.startsWith('artist:') || last_part_of_request.startsWith('tag:')))			//when user writing actual req
			{
				//alert(document.getElementById('search_string').value.trim().length);
				if(count_of_a)										//prepare prev req to sending to server
					for(var i=0;i<count_of_a;i++)
						prev_parts_a[i] = prev_parts_a[i].substr('artist:'.length);
				if(count_of_t)
					for(var i=0;i<count_of_t;i++)
						prev_parts_t[i] = prev_parts_t[i].substr('tag:'.length);
				var type_of_last_part;								//prepare last req to sending to server
				if(last_part_of_request.startsWith('artist:'))	
					{last_part_of_request = last_part_of_request.substr('artist:'.length);
					ajax_req(last_part_of_request,prev_parts_t,prev_parts_a,"artist",prev_parts_joined);}
				else
					{last_part_of_request = last_part_of_request.substr('tag:'.length);
					ajax_req(last_part_of_request,prev_parts_t,prev_parts_a,"tag",prev_parts_joined);}
			}
		else
		{
			document.getElementById('search_hint_table').style.visibility = "hidden";
		}

		}
	}


	function start_search(){
		var start_search_req_from_img = "<?php echo $_GET["search_req"];?>";
		if(start_search_req_from_img == "")
			search(true,"date");
		else
		{
			var start_search_type = "<?php echo $_GET["search_req_type"];?>";
			full_start_req = start_search_type+":"+start_search_req_from_img+" ";
			search(true,"date",full_start_req);
			document.getElementById('search_string').value = full_start_req;
		}
	}

	function hide_hints(){
		document.getElementById("search_hint_table").style.visibility = "hidden";
	}

	function search_by_enter_key(key)
	{
		if(key.keyCode == 13)
			search(true,"date");
	}

	addEventListener("keydown", search_by_enter_key);

	window.addEventListener("load", start_search);
</script>
</body>
</html>