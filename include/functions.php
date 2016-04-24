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

	function month_rename($date_eng) {
		$date_eng =  str_replace("Monday", "Montag",$date_eng);
		$date_eng =  str_replace("Tuesday", "Dienstag",$date_eng);
		$date_eng =  str_replace("Wednesday", "Mittwoch",$date_eng);
		$date_eng =  str_replace("Thursday", "Donnerstag",$date_eng);
		$date_eng =  str_replace("Friday", "Freitag",$date_eng);
		$date_eng =  str_replace("Saturday", "Samstag",$date_eng);
		$date_eng =  str_replace("Sunday", "Sonntag",$date_eng);
		$date_eng =  str_replace("January", "Januar",$date_eng);
		$date_eng =  str_replace("February", "Februar",$date_eng);
		$date_eng =  str_replace("March", "M&auml;rz",$date_eng);
		$date_eng =  str_replace("May", "Mai",$date_eng);
		$date_eng =  str_replace("June", "Juni",$date_eng);
		$date_eng =  str_replace("July", "Juli",$date_eng);
		$date_eng =  str_replace("October", "Oktober",$date_eng);
		$date_eng =  str_replace("December", "Dezember",$date_eng);  		
  		return $date_eng;
  	}

?>
