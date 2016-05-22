<?php
	session_start();
	include "include/config.php";
 	include "include/db_connect.php";
  	include "include/functions.php";
	if(isset($_GET['id']) and $_GET['id']!="") {
		$id=$_GET['id'];
		if(isset($id) and $id!="") {
			$info=get_id($id, $secret, $appid, $ssl);
			$info=json_decode($info);
			if (isset($info->status) and $info->status=="Success"){
				$userid=$info->userid;
				echo $userid;
				$check_uid = $db->query("SELECT `uid` FROM `userdates` WHERE tid LIKE '$userid'"); 
				if(isset($check_uid->num_rows) and  $check_uid->num_rows!= 0) {
					$id = $check_uid->fetch_assoc()['uid'];
				}
				else{
					$insert = $db->query("INSERT INTO userdates (uid, tid) VALUES ('$id', '$userid')"); 		
				}
				$check_id = $db->query("SELECT `id` FROM `userdates` WHERE tid LIKE '$userid'"); 
				$id_db = $check_id->fetch_assoc()['id'];
				setcookie('user', $id_db, time()+(3600*24*365));  
				$login_status=true;		
				$_SESSION['id']=$id_db;	
			}		
			else {
				$login_status=false;
			}
		}
		else {
			$login_status=false;
		}
	}
	elseif(isset($_COOKIE['user']) and $_COOKIE['user']!="") {
		$_SESSION['id']=$_COOKIE['user'];
		$login_status=true;	
	}
	else {
		$login_status=false;
	}
   $_SESSION['login']=$login_status;
	//header('Location: ./');

?>
