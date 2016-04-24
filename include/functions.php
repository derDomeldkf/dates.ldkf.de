<?php
	function get_user($secret, $appid) {
		if(isset($_COOKIE['user']) and $_COOKIE['user']!="") {
			$user_id=$_COOKIE['user'];
			$action="username";
			$method="action=".$action;
			$hash = hash("sha256", $secret.$appid.$user_id.$action);
			post($user_id, $appid, $method, $hash);

		}	
		

	}
	
	function post($user_id, $appid, $method, $hash){
		$content=file_get_contents("https://xauth.ldkf.de/api.php?appid=".$appid."&id" . $user_id . "&hash=" . $hash . "&" . $method);	
		return $content;
	} 
	
	function say_login(){
		$content="";
		return $content;
	} 



?>
