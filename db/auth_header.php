<?php
	require_once "connect.php";

	echo "<h1>ᚖ ᚠ ᚡ ᚢ ᚣ ᚤ ᚥ ᚦ ᚧ ᚨ ᚩ ᚪ ᚫ ᚬ ᚭ ᚮ ᚯ ᚰ ᚱ ᚲ ᚳ ᚴ</h1>";
	echo "<h4>project Útgarðar</h4>";			//заголовок
	if(isset($_COOKIE['token_hod']))			//if для вывода юзернейма и кнопки логаюта или кнопки логина и регистрации 
			echo "HELLO, ".get_user_login($_COOKIE['token_hod'])."<br><form method = 'POST' action = 'db/call_logout.php'><input type = 'submit' value = 'Log out'></form><br>";				
	else
			echo "<a href = 'login_and_registration.php'><button>Log in/Sign up</button></a><br>";
	echo "<a href='index.php'><button>Main page</button></a><a href='search.php'><button>Search</button></a><a href='tag_search.php'><button>Tags</button></a>";	//вывод кнопок
	if(isset($_COOKIE['token_hod']))									//my works активна только когда пользователь вошёл.
		echo "<a href = 'my_works.php'><button>My works</button></a>
<br>";
	else
		echo "<button disabled>My works</button>
<br>";
echo "<h3>ᚖ ᚠ ᚡ ᚢ ᚣ ᚤ ᚥ ᚦ ᚧ ᚨ ᚩ ᚪ ᚫ ᚬ ᚭ ᚮ ᚯ ᚰ ᚱ ᚲ ᚳ ᚴ ᚵ ᚶ ᚷ ᚸ ᚹ ᚺ ᚻ ᚼ ᚽ ᚾ ᚿ ᛁ ᛃ ᛄ ᛅ ᛆ ᛇ ᛈ ᛉ ᛊ ᛋ ᛏ ᛑ ᛒ ᛓ ᛔ ᛕ ᛖ ᛗ ᛘ ᛚ ᛛ ᛜ ᛝ ᛞ ᛟ ᛠ ᛡ ᛢ ᛣ ᛤ ᛥ ᛦ ᛩ ᛪ ᛮ ᛯ ᛰ ᚖ ᚠ ᚡ ᚢ ᚣ ᚤ ᚥ ᚦ ᚧ ᚨ ᚩ ᚪ ᚫ ᚬ ᚭ ᚮ ᚯ ᚰ ᚱ ᚲ ᚳ ᚴ ᚵ ᚶ ᚷ ᚸ ᚹ ᚺ ᚻ </h3><br>"
?>