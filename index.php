<?php
	session_start();
	include "include/config.php";
 	include "include/db_connect.php";
  	include "include/functions.php";
	$info="";
	if(isset($_GET['id']) and $_GET['id']!="") {
		echo "what did you do?";
	}
	elseif(isset($_COOKIE['user']) and $_COOKIE['user']!="") {
		$id=$_COOKIE['user'];
		$check_uid = $db->query("SELECT `uid` FROM `userdates` WHERE id LIKE '$id'"); 
		if(isset($check_uid->num_rows) and  $check_uid->num_rows!= 0) {
			$id = $check_uid->fetch_assoc()['uid'];
			$_SESSION['id']=$id;	
			$_SESSION['login']=true;
	      $info=get_user($id, $secret, $appid, $ssl);
         $info=json_decode($info);
         if ($info->status=="Success"){
            $ln=$info->last_name;
            $fn=$info->first_name;
            $username=$info->username;
         }
         else {
         	$error=2;
         }
 		}
 		else {
      	$error=1;
   	}
	}





?>

<html>
	<head>
		<link rel="icon" href="favicon.png">
		<link href="https://msn.ldkf.de/css/bootstrap.min.css" rel="stylesheet">
    	<link href="https://msn.ldkf.de/css/bootstrap-theme.min.css" rel="stylesheet">
		<script type="text/javascript" src="https://msn.ldkf.de/js/jquery-1.11.2.min.js"></script>
   	<script type="text/javascript" src="https://msn.ldkf.de/js/bootstrap.min.js"></script>
		<title>Startseite</title>
		<script type="text/javascript">
    		window.cookieconsent_options = {"message":"This website uses cookies to ensure you get the best experience on our website","dismiss":"Got it!","learnMore":"More info","link":null,"theme":"dark-bottom"};
		</script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.9/cookieconsent.min.js"></script>
		<script type="text/javascript">
		 	$(document).on('click','.this',function() {
    			var date= $(this).attr("id");
    			$.post("include/get.php",{
        			0: "<?php echo $id; ?>",
        			1: date ,
    			},
   			function (data) {
					if (data.indexOf("div") != -1) {
						$( "div.listetdates" ).replaceWith( data );
						$("#date_post").val(date);
					}
				}
				);
   		});
   		$(document).on('click','#tobot',function() {
    			var win=window.open("https://telegram.me/date_reminder_bot?start=activate");
    			setTimeout(function(){  
    									win.close();
          						}, 2000);
    			
   		});
  		
   		
		</script>
		<style>
			.dateday{
				margin-top:60px;		
			}
			.calendar{
				vertical-align: middle;	
				margin: 0px auto;	
				text-align: left;
			}
			.main{
				padding: 0 30px 0 30px;
			}
			.month{
				vertical-align: middle;
				text-align: center;	
			}
			.head{

				vertical-align: middle;	
				padding: 5px 0 5px 0;
				width: 300px;
			}
			.week{
				margin: 0px auto;	
				vertical-align: middle;	
				text-align: center;	
				padding: 5px 0 5px 0;
		
			}
			.day{
				padding: 4px;
			
			}
			.navigation{
				float: left;
				margin: 0 4px 0 4px;
			
			}
			.top{
				font-weight: bolder;	
			}
			.other{
				color: grey;			
			}
			.date{
				color: green;
				font-weight: bold;	
			}
			.yday{
				color: orange;
				font-weight: bold;	
			}
			.adddate{
				float: left;	
				margin-bottom: 30px;
				border:solid 4px;
 				border-radius:6px; 
				width: 450px;
				padding: 20px;
			}
			.listetdates{
				float: left;	
				margin-right: 20px;	
			}
			</style>
	</head>
	<body style="">
		<nav class="navbar navbar-inverse navbar-static-top" style="min-height:20px;">
			<div class="container-fluid" id="navigation" style="display:block;">
		  		<div class="navbar-header">
  					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
           			<span class="sr-only">Toggle navigation</span>
        	 			<span class="icon-bar"></span>
        	  			<span class="icon-bar"></span>
       	  			<span class="icon-bar"></span>
        			</button>
  					<a class="navbar-brand" href="http://ldkf.de">LDKF.de</a>
  				</div>
	      	<div id="navbar" class="navbar-collapse collapse">
  					<ul class="nav navbar-nav" >
    					<li class="active"><a href="?">Startseite<span class="sr-only">(current)</span></a></li>
    					<?php if(!isset($_SESSION['login']) or $_SESSION['login']==false): ?>
    					<li><a href="https://xauth.ldkf.de/connect.php?appid=<?php echo $appid; ?>&ret=login.php">Einloggen</a></li> 
    					<?php else : ?>
    					<li><a href="logout.php">Ausloggen</a></li> 
    					<?php endif; ?>
    					<li><a href="#" id="tobot">Erinenrungsbot</a></li>  					
     				</ul>
    			</div>
			</div>
		</nav>
			<?php
				setlocale(LC_TIME, "de_DE.utf8");
				if(isset($_GET['go'])) {
					$change_month=$_GET['go'];
				}
				else {
					$change_month=0;
				}
				$kal_datum = strtotime("+".$change_month." month");
				$kal_tage_gesamt = date("t", $kal_datum);
				$kal_start_timestamp = mktime(0,0,0,date("n",$kal_datum),1,date("Y",$kal_datum));
				$kal_start_tag = date("N", $kal_start_timestamp);
				$kal_ende_tag = date("N", mktime(0,0,0,date("n",$kal_datum),$kal_tage_gesamt,date("Y",$kal_datum)));
			?>
		<div class="main" role="main" style="height:100%">
			<div class="calendar">
			<?php if(isset($_SESSION['login']) and $_SESSION['login']==true): ?>
				<h3 style="padding-left:5px;">Hallo <?php echo $fn." ".$ln; ?>.</h3>
			<?php endif; ?>			
				<div class="row">
					<div class="col-md-4 month" style="">
						<div class="head">
							<div class="navigation">
								<form action="?" style="" method="GET">
									<input type="hidden" name="go" value="<?php echo $change_month -1; ?>">
									<button type="submit" class="btn btn-primary" style="padding-top:6px; padding-bottom:6px;">
										<<
									</button>
								</form>
							</div>
							<div class="navigation" style="width:180px;">
								<h4><?php echo month_rename(utf8_decode(strftime("%B %Y", $kal_datum))) ; ?></h4>
							</div>
							<div class="navigation">
								<form action="?" style="" method="GET">
									<input type="hidden" name="go" value="<?php echo $change_month +1; ?>">
									<button type="submit" class="btn btn-primary" style="padding-top:6px; padding-bottom:6px;">
										>>
									</button>
								</form>
							</div>
						</div>
						<div class="week">
							<table class="kalender" id="t" style="width:100%;">
								<thead>
   								<tr>
      								<td class="day top">Mo</td>
      								<td class="day top">Di</td>
      								<td class="day top">Mi</td>
      								<td class="day top">Do</td>
      								<td class="day top">Fr</td>
      								<td class="day top">Sa</td>
      								<td class="day top">So</td>
   								</tr>
  								</thead>
  								<tbody>
									<?php
										$dbyday[]="";
										$dbdate[]="";
										$month = date("n", $kal_datum);
										if(isset($_SESSION['login']) and $_SESSION['login']==true){
											$getdate = $db->query("SELECT `date` FROM `dates` WHERE MONTH(date) = '$month' and `type` = 1 and `id` = $id"); 
											while($name = $getdate->fetch_assoc()){
												$dbdate[]= date("m-d-y", strtotime( $name['date'] ));
											}
											$getyday = $db->query("SELECT `date` FROM `dates` WHERE MONTH(date) = '$month' and `type` = 2 and `id` = $id"); 
											while($namey = $getyday->fetch_assoc()){
												$dbyday[]= date("m-d", strtotime( $namey['date'] ));
											}
										}
  										for($i = 1; $i <= $kal_tage_gesamt+($kal_start_tag-1)+(7-$kal_ende_tag); $i++){
    										$kal_anzeige_akt_tag = $i - $kal_start_tag;
    										$kal_anzeige_heute_timestamp = strtotime($kal_anzeige_akt_tag." day", $kal_start_timestamp);
    										$kal_anzeige_heute_tag = date("d", $kal_anzeige_heute_timestamp);
    										$date= date("Y-m-d", $kal_anzeige_heute_timestamp); //check in db for entries
    										if(date("N",$kal_anzeige_heute_timestamp) == 1){
      										echo '<tr>';
      									}
      									echo '<td class="day';
      									if (in_array(date("m-d-y",$kal_anzeige_heute_timestamp), $dbdate)) {
    											echo " date";
											}
      									if (in_array(date("m-d",$kal_anzeige_heute_timestamp), $dbyday)) {
    											echo " yday";
											}					
      									if($kal_anzeige_akt_tag >= 0 and $kal_anzeige_akt_tag < $kal_tage_gesamt){
     											echo ' this ';
     											if(date("dmY", time()) == date("dmY", $kal_anzeige_heute_timestamp)){
      											echo ' top';
      										}
     										}
    										else {
      										echo ' other ';
      									}
      									echo '" id="'.$kal_anzeige_heute_timestamp.'"><span style="cursor:pointer">'.$kal_anzeige_heute_tag.'</span></td>';
    										if(date("N",$kal_anzeige_heute_timestamp) == 7){
      										echo '</tr>';
      									}	
      										
 										}
 										
									?>
  								</tbody>
							</table>
						</div>
						
						
					</div>
					<div class="col-md-8" style="">
						<div class="dateday">
							<div class="listetdates">
							<?php
								if(isset($_SESSION['login']) and $_SESSION['login']==true){
									if(isset($_GET['date_post'])) {
										$date=$_GET['date_post'];
									}
									else {
										$date=time();
									}
									$m=date('n', $date);
									$d=date('d', $date);
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
									$getdate = $db->query("SELECT `date`, `place`, `disc` FROM `dates` WHERE MONTH(date) = '$m' and DAY(date) = '$d' and  `type` = 1 and `id` = $id order by `date` asc"); 
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
									echo '</div>
										<div class="adddate">
											<h4>Termin hinzuf&uuml;gen:</h4>
											<form method="post" id="msn" class="" action="include/insert.php?go='.$change_month.'">
        										<div class="form-group" style="width:400px;">
        											<input autocomplete="off" style="width:70px; type="time" maxlength=5 name="time" class="form-control" placeholder="hh:mm">
        												<div class="input-group" style="vertical-align:middle">
															<input style="" type="checkbox" value="1" name="year">
															<font size="3pt">J&auml;hrlich an Termin erinnern</font>
														</div>
													<input autocomplete="off" type="text" name="place" class="form-control" placeholder="Ort">
													<input type="hidden" id="date_post" name="date_post" class="form-control" value="'.$date.'">
													<textarea name="disc" required name="text" class="form-control" placeholder="Beschreibung"></textarea> 
        											<button type="submit" class="btn btn-primary">Eintragen</button>
        										</div>
        					
     	 									</form>
										
										</div>									
									
									
									';
									
									
									
								}							
							?>
						
						</div>						
					</div>
				</div>
				<div class="days">
				
				</div>
			</div>
		</div>  
	</body>
</html> 