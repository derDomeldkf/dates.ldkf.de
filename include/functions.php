<?php
	function get_user($user_id, $secret, $appid) {
		$action="all";
		$method="action";
		return post($user_id, $appid, $secret, $method, $action);
	}	
	function get_id($user_id, $secret, $appid){
		$action="userid";
		$method="action";
		return post($user_id, $appid, $secret, $method, $action);	
	
	}
	function post($user_id, $appid, $secret, $method, $action){
			$hash = hash("sha256", $secret.$appid.$user_id.$action);
        	$url = 'https://xauth.ldkf.de:4443/api.php';
        	$data = array('appid' => $appid, 'id' => $user_id, 'hash' => $hash, 'action' => $action);
        	$options = array(
         	'http' => array(
            	'header' => "Content-type: application/x-www-form-urlencoded\r\n",
               'method' => 'POST',
               'content' => http_build_query($data),
         	),
        	);
        	$context = stream_context_create($options);
        	$result = @file_get_contents($url, false, $context);
        	if ($result === FALSE) {
				$content=1;
        	}
        	else {
            $content = $result;
			}
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
