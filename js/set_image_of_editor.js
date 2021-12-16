function send_image_to_server(){
	var pic = lc.getImage().toDataURL();				//getting DataURL of pic from editor
	var pic_without_background = LC.renderSnapshotToImage(lc.getSnapshot(['shapes', 'imageSize', 'colors', 'position', 'scale'])).toDataURL();
	document.getElementById('my-drawing').classList.add('my-drawing-unactive');
	
	$.ajax(
	{
	url:"db/set_image.php",
	type:'POST',
	data:{'pic':pic,'pic_without_background':pic_without_background},
	success: function(data){
		alert(data);
		document.getElementById('set_tags').disabled = false;
		document.getElementById('set_tags').onclick = function(){
		document.location.href = "tag_search.php?editing_img="+data;
		}
	},
	error: function()
	{
		alert("error during getting");
	}
	});
}