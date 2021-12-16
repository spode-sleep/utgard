function start_drawing()
{
	document.getElementById('timer_container').innerHTML = '<div class="timeCircle" data-timer="30"></div>';

	$(".timeCircle").TimeCircles({ time: {
    	Days: { show: false },
    	Hours: { show: false },
    	Minutes: { show: false },
    	Seconds: { color: "#C0C8CF", text:"Moments" }
		}, fg_width: 0.1, bg_width: 0.05, count_past_zero:false}).addListener(countdownComplete);

		function countdownComplete(unit, value, total)
		{
			if(total<=0)
			{
				document.getElementById('timer_container').style.width = "100%";
				document.getElementById('timer_container').innerHTML = '<div id="control_buttons" style = "text-align:center"><button id = "restart_button">Start again</button><br><button id="set_tags" disabled>Set tags to the previous picture</button></div>';
				send_image_to_server();

				restart_button = document.getElementById('restart_button');

				restart_button.addEventListener("click",function(){
					get_image_from_server();
				});
			}
		}
}

var start_button = document.getElementById('start_button');

start_button.addEventListener("click",function(){
	get_image_from_server();
});

var restart_button;