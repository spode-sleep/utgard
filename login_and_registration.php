<!DOCTYPE html>
<html>
<head>
	<title>Login and registration</title>
<script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>
<style type="text/css">
	.error
	{
		color: red;
	}
</style>
<script type="text/javascript">


var is_username_OK;
var is_password_OK;
var is_rep_password_OK;

var is_login_username_OK;

	function check_username()
	{

		var possible_username = document.getElementById('login').value;
		var error = false;

		document.getElementById("username_errors").innerHTML = "";

		if(possible_username.length > 22)
			error = "Too long username.";
		if(possible_username.length < 3)
			error = "Too short username.";
		if(!error)
		{
			var regexp = /^[A-Z0-9_]+$/i;
			if(!regexp.test(possible_username))
				error = "Available characters: (A-Z,a-z,_,0-9).";
		}
		if(!error)
		{
			var is_username_already_exists;
			$.ajax({
				url:"db/check_username.php",
				type: 'GET',
				data: {"username": possible_username},
				success: function(data){
					is_username_already_exists = data;
					if(is_username_already_exists == 1)
						error = "This username already exists.";
					if(error)
					{
						document.getElementById("username_errors").innerHTML = "<span class = 'error'>"+error+"</span><br>";
						is_username_OK = false;
						try_to_enable_register_button();
					}
					else
					{
						is_username_OK = true;
						try_to_enable_register_button();
					}
				},
				error: function(){
					alert("error during username check");
				}
			});
			
		}
		if(error)
			{
				document.getElementById("username_errors").innerHTML = "<span class = 'error'>"+error+"</span><br>";
				is_username_OK = false;
			}
	}

	function check_password_power()
	{
		var actual_password = document.getElementById("password").value;
		var error = false;

		document.getElementById("password_errors").innerHTML = "";

		if(actual_password.length < 6)
			error = "This password too short.";
		if(actual_password.length > 20)
			error = "This password too long.";

		if(error)
			{
				document.getElementById("password_errors").innerHTML = "<span class = 'error'>"+error+"</span><br>";
				is_password_OK = false;
			}
			else
			{
				is_password_OK = true;
			}
	}

	function check_passwords_equality()
	{
		var actual_password = document.getElementById("password").value;
		var rep_password = document.getElementById("rep_password").value;

		if(actual_password != rep_password)
			{
				document.getElementById("password_errors").innerHTML = "<span class = 'error'>Passwords must be equal.</span>";
				is_rep_password_OK = false;
			}
		else
			{
				document.getElementById("password_errors").innerHTML = "";
				is_rep_password_OK = true;
			}
	}

	function try_to_enable_register_button()
	{
		if(is_username_OK && is_password_OK && is_rep_password_OK)
			document.getElementById("register").disabled = false;
		else
			document.getElementById("register").disabled = true;
	}

	function try_to_register()
	{
		if(is_username_OK && is_password_OK && is_rep_password_OK)
			{
				$.ajax({
					url:"db/registration.php",
					type: "POST",
					data:{"username":document.getElementById('login').value,"password":document.getElementById("password").value,"rep_password":document.getElementById("rep_password").value},
					success: function(data)
					{
						if(data == "1")
							document.location.href = "index.php";
						else
						{
							//alert("Something wrong with your data.")
							check_username();
							check_password_power();
							check_passwords_equality();
						}

					},
					error: function()
					{
						alert("error during registration");
					}


				});
			}
	}

	function try_to_find_username()
	{

		$.ajax({
			url:"db/check_username.php",
			type: "GET",
			data:{"username":document.getElementById("login_login").value},
			success: function(data)
			{
				if(data != "1")
					{
						document.getElementById("username_errors_login").innerHTML = "<span class = 'error'> Username doesn't exist</span>";
						is_login_username_OK = false;
						document.getElementById("login_button").disabled = true;
					}
				else
					{
						document.getElementById("username_errors_login").innerHTML = "";
						is_login_username_OK = true;
						document.getElementById("login_button").disabled = false;
					}
			},
			error: function()
			{
				alert("error during username check");
			}

		});

	}



	function try_to_log_in()
	{
		if(is_login_username_OK)
		{
			$.ajax({
				url:"db/log_in.php",
				type:"POST",
				data:{"username":document.getElementById("login_login").value, "password":document.getElementById("password_login").value},
				success:function(data){
					console.log(data);
					if(data == "1")
						document.location.href = "index.php";
					else
						document.getElementById("password_errors_login").innerHTML = "<span class = 'error'>Wrong password</span>";
				},
				error:function(){
					alert("error during log in");
				}

			});
		}
	}




</script>
</head>
<body>
<?php 
    require "db/auth_header.php";
?>

<div id = "registration">

<h3>Sign up</h3>
<div id="registration_errors">
	<div id="username_errors"></div>
	<div id="password_errors"></div>
</div>
	<input type = "text" id = "login" placeholder="Username" oninput="check_username(); try_to_enable_register_button();"><br>
	<input type = "password" id = "password" placeholder="Password" autocomplete = "off" oninput="check_password_power(); try_to_enable_register_button();"><br>
	<input type = "password" id = "rep_password" placeholder="Repeat password" oninput = "check_passwords_equality(); try_to_enable_register_button();" autocomplete = "off"><br>
	<button onclick='try_to_register()' id = "register" disabled>Register</button>
</div>


<div id = "login">

<h3>Log in</h3>
<div id="login_errors">
	<div id="username_errors_login"></div>
	<div id="password_errors_login"></div>
</div>
	<input type = "text" name = "login" id = "login_login" placeholder="Username" oninput="try_to_find_username()"><br>
	<input type = "password" name = "password" id = "password_login" placeholder="Password" autocomplete = "off"><br>
	<button onclick='try_to_log_in()' id = "login_button" disabled>Log in</button>
</div>





</body>
</html>