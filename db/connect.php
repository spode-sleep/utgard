<?php

$db = new mysqli('localhost','root','','u0670074_default');
if ($db->connect_errno)
{
	echo "DB connection error";
	die("Error occured in server side.");
}
else
	$db->set_charset("utf8");

$token_key = 'DA8EF91ADA568';

function neutralize_string($str){
	global $db;
	return mysqli_real_escape_string($db,$str);
}

function get_user_id($token)
{
	global $token_key,$db;
	$hashed_token = hash_hmac('sha256',$token,$token_key);

	$req = $db -> query("SELECT user_id FROM sessions WHERE tokenHash = '$hashed_token'");
	if($req->num_rows)
	{
	$result = $req->fetch_array();
	return $result[0];
	}
	else
		return -1;
}

function get_user_login($token)
{
	global $token_key,$db;
	$hashed_token = hash_hmac('sha256',$token,$token_key);

	$req = $db -> query("SELECT users.login FROM
	sessions INNER JOIN users ON sessions.user_id = users.id
	WHERE sessions.tokenHash = '$hashed_token'");
	if($req->num_rows)
	{
		$result = $req->fetch_array();
		return $result[0];
	}
	else
		return -1;
}

function logout($token)
{
	global $token_key,$db;
	$hashed_token = hash_hmac('sha256',$token,$token_key);

	$req = $db -> query("DELETE FROM sessions WHERE tokenHash = '$hashed_token'");

	setcookie('token_hod', null, -1, '/');
}

function strip_tags_content($text, $tags = '', $invert = False) { 

  preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags); 
  $tags = array_unique($tags[1]); 
    
  if(is_array($tags) AND count($tags) > 0) { 
    if($invert == FALSE) { 
      return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text); 
    } 
    else { 
      return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text); 
    } 
  } 
  elseif($invert == FALSE) { 
    return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text); 
  } 
  return $text; 
} 
?>