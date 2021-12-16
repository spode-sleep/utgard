<!DOCTYPE html>
<html>
<head>
	<title>Main page</title>
<script src="//cdnjs.cloudflare.com/ajax/libs/react/0.14.7/react-with-addons.js"></script>
 <script src="//cdnjs.cloudflare.com/ajax/libs/react/0.14.7/react-dom.js"></script>
<script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>
 <link href="/js/lc/css/literallycanvas.css" rel="stylesheet">
 <script src="/js/lc/js/literallycanvas.js"></script>
 <script type="text/javascript" src="js/TimeCircles/TimeCircles1.js"></script>
<link href="js/TimeCircles/TimeCircles.css" rel="stylesheet">
<link href="css/loader.css" rel="stylesheet">
<link href="css/modal.css" rel="stylesheet">
<style type="text/css">
	html, body { height: 100%; width: 100%; margin: 0; }
</style>

</head>

<body>

<?php 
    require "db/auth_header.php";
?>

<?php require "db/start_button_access.php" ?>

<div id="modal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div id="timer_container"></div>
    

    <div id="my-drawing"></div>
      <script>
       document.getElementById('my-drawing').style.width = Math.round(document.documentElement.clientWidth * .75) + 61 + 'px';
       document.getElementById('my-drawing').style.height = Math.round(document.documentElement.clientWidth * .75)*0.5 + 31 +  'px';
      </script>
  </div>
</div>

<script src = "/js/set_image_of_editor.js"></script>
<script src = "/js/get_image_for_editor.js"></script>
<script src = "/js/timeCircleControl.js"></script>
<script src = "/js/modalControl.js"></script>
</body>
</html>