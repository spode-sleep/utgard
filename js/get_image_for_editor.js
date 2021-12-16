function get_image_from_server(){
	var current_img_data;

	document.getElementById('timer_container').style.width = "100px";
	document.getElementById('timer_container').innerHTML = '<div class="loader"></div>';

    document.getElementById('my-drawing').style.width = Math.round(document.documentElement.clientWidth * .75) + 61 + 'px';
    document.getElementById('my-drawing').style.height = Math.round(document.documentElement.clientWidth * .75)*0.5 + 31 +  'px';
    document.getElementById("my-drawing").style.visibility = "visible";

	$.ajax(
	{
	url:"db/get_image.php",
	type:'GET',
	success: function(data){
		
		//alert("got img "+data);
		//document.getElementById("cont").innerHTML = "<img src=http://hod.ru/db/img/"+data+".png>";
		

		current_img_data = JSON.parse(data);
		//alert(current_img_data.id+" "+current_img_data.pic_filepath);
		if(current_img_data.pic_filepath === "err")
		{
			setTimeout(function(){get_image_from_server();}, 30000);
		}
		else
		{
		var bg = new Image();
		bg.src = "db/"+current_img_data.pic_filepath;

		
		bg.onload = function(){
		document.getElementById('my-drawing').classList.remove('my-drawing-unactive');

		if (window.lc)
			lc.teardown();


		lc = LC.init(
            document.getElementById('my-drawing'),{imageURLPrefix: '/js/lc/img',backgroundShapes:
           	 [
            LC.createShape('Image', {x: 0, y: 0, image: bg})
			],
			imageSize:{width: 4096, height: 2048}
            });

		
		var zoom_coef = ($('#my-drawing').height()-31)/2048;

		lc.setZoom(zoom_coef);

		start_drawing();
		}

		if(current_img_data.pic_filepath === "img/0.png")
			bg.onload();

		}
	},
	error: function()
	{
		alert("error during getting");
	}
	});
}