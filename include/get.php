 <?php
 	session_start();
 	include "config.php";
 	include "db_connect.php";
 	echo '<div class="listetdates">';
		if(isset($_SESSION['login']) and $_SESSION['login']==true){
			$date=$_POST[1];
			$id=$_POST[0];
			$m=date('n', $date);
			$d=date('d', $date);
			$y=date('Y', $date);
			$getdate = $db->query("SELECT `date`, `place`, `disc` FROM `dates` WHERE MONTH(date) = '$m' and DAY(date) = '$d' and  `type` = 2 and `id` = $id order by `date` asc"); 
			while($name = $getdate->fetch_assoc()){
				$datesa[]= date("G:i", strtotime( $name['date'] ));
				$placea[]= $name['place'] ;
				$disca[]= $name['disc'];
			}
			$i=0;
			if(isset($datesa[0])) {
				foreach($datesa as $time){
					echo '<b>Beschreibung:</b> '.$disca[$i]."<br><b>Ort:</b> ".$placea[$i]."<br><br>";
					$i++;
				}	
				echo "_______________________________";
			}
			$getdate = $db->query("SELECT `date`, `place`, `disc` FROM `dates` WHERE MONTH(date) = '$m' and DAY(date) = '$d' and YEAR(date) = '$y' and  `type` = 1 and `id` = $id order by `date` asc"); 
				while($name = $getdate->fetch_assoc()){
					$dates[]= date("G:i", strtotime( $name['date'] ));
					$place[]= $name['place'] ;
					$disc[]= $name['disc'];
				}
				$i=0;
				if(isset($dates[0])) {
					foreach($dates as $time){
						echo '<h4 style="margin-top:0" >Uhrzeit: '.$time." Uhr</h4><b>Beschreibung:</b> ".$disc[$i]."<br><b>Ort:</b> ".$place[$i]."<br><br>";
						$i++;
					}	
				}
			}
			echo '</div>';
 
 ?>
