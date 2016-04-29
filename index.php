<?php
	session_start();
	include "include/config.php";
 	include "include/db_connect.php";
  	include "include/functions.php";
	$info="";
	if(isset($_GET['id']) and $_GET['id']!="") {
		$id=$_GET['id'];
	}
	elseif(isset($_COOKIE['user']) and $_COOKIE['user']!="") {
		$id=$_COOKIE['user'];
	}
	if(isset($id) and $id!="") {
		$info=get_id($id, $secret, $appid);
		$info=json_decode($info);
		if (isset($info->status) and $info->status=="Success"){
			$userid=$info->userid;
			$info=get_user($id, $secret, $appid);
			$info=json_decode($info);
			if ($info->status=="Success"){
				setcookie('user', $id, time()+(3600*24*365));  
				$ln=$info->last_name;
				$fn=$info->first_name;
				$username=$info->username;
				$login_status=true;
				
			}
			else {
				$login_status=false;
			}
		}		
		else {
			$login_status=false;
		}
	}
	else {
		$login_status=false;
	}
	
?>

<html>
	<head>
		<link rel="icon" href="favicon.png">
		<link href="https://msn.ldkf.de/css/bootstrap.min.css" rel="stylesheet">
    	<link href="https://msn.ldkf.de/css/bootstrap-theme.min.css" rel="stylesheet">
		<title>Startseite</title>
		<script type="text/javascript">
    		window.cookieconsent_options = {"message":"This website uses cookies to ensure you get the best experience on our website","dismiss":"Got it!","learnMore":"More info","link":null,"theme":"dark-bottom"};
		</script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.9/cookieconsent.min.js"></script>
		<script type="text/javascript">
  			/*function getcalendar(){
				$.post("include/get.php",{
					0: "<?php echo $user_in; ?>",
					1: $(this).attr("value"),
				},
   			function (data) {
					if (data.indexOf("div") != -1) {
						$( ".del" ).replaceWith( "" );
						$( ".repl" ).replaceWith( data );
					}
				}
   			);		 		
			}
			$(document).ready(
				function(){
					getcalendar();
		   	}
			); */
		</script>
		<style>
			.calendar{
				vertical-align: middle;	
				margin: 0px auto;	
			}
			.main{
				padding: 0 30px 0 30px;
			}
			.month{
				vertical-align: middle;
				text-align: center;	
			
			}
			.head{
				margin: 0px auto;	
				vertical-align: middle;	
				padding: 5px 0 5px 0;
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
    					<li class="active"><a href="">Startseite<span class="sr-only">(current)</span></a></li>
    					<?php if($login_status==false): ?>
    					<li><a href="https://xauth.ldkf.de/connect.php?appid=<?php echo $appid; ?>&ret=login.php">Login</a></li> 
    					<?php else : ?>
    					<li><a href="#">Logout</a></li> 
    					<?php endif; ?>   					
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
			<?php if($login_status==true): ?>
				<h3>Hallo <?php echo $fn." ".$ln; ?>.</h3>
    					
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
							<table class="kalender" style="width:100%;">
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
										$getdate = $db->query("SELECT `date` FROM `dates` WHERE MONTH(date) = '$month' and `type` = 1 and `uid` = $id"); 
										while($name = $getdate->fetch_assoc()){
											$dbdate[]= strtotime( $name['date'] );
										}
										$getyday = $db->query("SELECT `date` FROM `dates` WHERE MONTH(date) = '$month' and `type` = 2 and `uid` = $id"); 
										while($namey = $getyday->fetch_assoc()){
											$dbyday[]= date("m-d", strtotime( $namey['date'] ));
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
      									if (in_array($kal_anzeige_heute_timestamp, $dbdate)) {
    											echo " date";
											}
      									if (in_array(date("m-d",$kal_anzeige_heute_timestamp), $dbyday)) {
    											echo " yday";
											}					
      									
    										if(date("dmY", time()) == date("dmY", $kal_anzeige_heute_timestamp)){
      										echo ' top"';
      									}
    										elseif($kal_anzeige_akt_tag >= 0 and $kal_anzeige_akt_tag < $kal_tage_gesamt){
     											echo ' this" ';
     										}
    										else {
      										echo ' other" ';
      									}
      									echo ' id="'.$date.'"><span style="cursor:pointer">'.$kal_anzeige_heute_tag.'</span></td>';
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
						<< Tag >>
					</div>
				</div>
				<div class="days">
				
				</div>
				<?php else : ?>
					<h2>here to login</h2>
    			<?php endif; ?>  
			</div>
		</div>  
	</body>
</html> 