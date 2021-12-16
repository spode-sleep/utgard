<!DOCTYPE html>
<html>
<head>
	<title>My works</title>
<script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>
<style type="text/css">
#search_string
	{
		width:70%;
	}

	.pic
	{
		border-radius: 5px;
		border: 1px solid rgba(0, 0, 0, 0.2);
		box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.15);
		width: 20%;
		height: 20%;
	}

	.pic_a
	{
		padding: 1%;
	}
</style>
<link href="css/loader.css" rel="stylesheet">
</head>
<body onload="getMyWorks(true,'date')">
<?php 
    require "db/auth_header.php";
?>

<div id="sort_buttons"></div>
<br>
<div id="result_of_search"></div>
<div id="loader"></div>



<script type="text/javascript">
var search_offset;
var show_end;
var loading_in_process;



	function getMyWorks(isFirstCall,sort)
	{
		if(isFirstCall)
		{
			search_offset = 0;
			document.getElementById("result_of_search").innerHTML = "";
			$("#result_of_search").fadeOut();
		}

		document.getElementById("loader").classList.add('loader');
		loading_in_process = true;

		document.getElementById("sort_buttons").innerHTML = "<button id='sort_by_date'>Sort by date</button>"+"<button id='sort_by_rating'>Sort by likes</button>";

		if(sort == "rating")
								{
									document.getElementById('sort_by_rating').disabled = true;
									document.getElementById('sort_by_date').addEventListener("click", function(){getMyWorks(true,"date");});
								}
								else
								{
									document.getElementById('sort_by_date').disabled = true;
									document.getElementById('sort_by_rating').addEventListener("click", function(){getMyWorks(true,"rating");});
								}

		$.ajax({
			url:"db/get_my_works.php",
			type: "GET",
			data: {"sort": sort,"offset":search_offset},
			success: function(data){
				pics = JSON.parse(data);
				html_pics = "";
				document.getElementById("loader").classList.remove('loader');
				loading_in_process = false;
				$("#result_of_search").fadeIn("slow");
				if(Object.keys(pics).length)
						{
							for(var i=0;i<Object.keys(pics).length;i++)
							{

								html_pics = html_pics + "<a href = 'img_page.php?id="+pics[i]["id"]+"' class = 'pic_a'><img src='db/"+pics[i]["filename"]+"' class = 'pic'></a>" + ((i+1)%4==0?"<br>":"");
							}

							if(isFirstCall)
							{
								document.getElementById("result_of_search").innerHTML = html_pics;

								if(Object.keys(pics).length<40)
								{
									show_end = false;
									document.getElementById("result_of_search").innerHTML += "<br><h3>[END]</h3>";
								}
								else
								{
									show_end = true;
									window.onscroll = function(){
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
							document.getElementById("result_of_search").innerHTML = "<h3>Nothing here but fake mirrors</h3>";
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
			error: function()
			{
				alert("error during getting works");
			}


		});
	}
</script>
</body>
</html>