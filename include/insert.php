<?php
	session_start();
	include "config.php";
 	include "db_connect.php";
	if(isset($_POST['disc']) and $_POST['disc']!="") {
		$id=$_SESSION['id']	;
		$discr=$_POST['disc'];
		$placedb=$_POST['place'];
		$time=$_POST['time'];
		$date_get=$_POST['date_post'];
		$date_in = date ("Y-m-d", $date_get);
		if(preg_match("/(2[0-4]|[01][1-9]|10):([0-5][0-9])/", $time)) {
			$date_db=$date_in." ".$time.":00";
		
		}
		else{
			$date_db=$date_in." 00:00:00";
		}
		
		if(isset($_POST['year'])) {
			$type=2;
		}
		else {
			$type=1;
		}
		
		$insert = $db->query("INSERT INTO dates (id, date, place, disc, type) VALUES (
			'". mysql_escape_string($id) ."',
			'". mysql_escape_string($date_db) ."',
			'". mysql_escape_string($placedb) . "', 
			'". mysql_escape_string($discr) . "', 
			'". mysql_escape_string($type) . "')"
		); 
	}
	header('Location: ../?go='. $_GET['go'] .'&date_post='.$date_get);
?> 
