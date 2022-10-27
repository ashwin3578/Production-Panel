<!DOCTYPE html>


<?php

//ini_set("memory_limit", "1G");

date_default_timezone_set('Australia/Brisbane');
if (session_status() == PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', 3600*8);
	ini_set('session.cookie_lifetime', 3600*8);

	
	session_set_cookie_params(3600*8);
	session_start();
	//$_SESSION['discard_after'] = $now + 3600*8;
}
$start_time=time();
include ('dbconnection.php');
include ('function.php');

if (empty($title_top)){
	
  if($_SESSION['temp']['id']=='FinneyKessler'){
    $title_top= 'Finney Not #1';
  }else{
    $title_top='Production Control Panel';
  }
}
//<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

?>
<html lang="en">
<head>
  <title><?php echo $page_title; ?></title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
  <!--<script src="/css/styles.css?v=<?=time();?>"></script>-->
  <?php // <link rel="stylesheet" href="css/test.css">?>
  <link rel="stylesheet" href="css/checkbox.css?v=<?php echo time()?>">
  <?php // <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> ?>
  
    
<?php 
	if((!empty($_SESSION['temp']['id']))&&$_SESSION['temp']['id']=='CorentinHillion                    '){  
		echo '<link rel="stylesheet" href="css/bootstrap.css">';
	}else{
		echo '<link rel="stylesheet" href="css/bootstrap.css">';
	}
	?> 
   
   
   <link rel="stylesheet" href="css/summary.css">
    <link rel="stylesheet" href="css/tooltip_log.css">
   
    <link rel="stylesheet" href="css/general.css?v=<?=time();?>">
   
  <script src="js/jquery3.min.js"></script>
  <!--<script src="js/jquery3.min.js"></script>-->
   <script src="js/pooper.min.js"></script> 
  

  <link href="css/jquery.signature.css" rel="stylesheet">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="js/jquery.signature.js"></script>
  
  <script src="js/angular.js"></script>
    
  <script src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
  
</head>
<body>




<?php if($page_title=="Scanning")
{
	echo'<a href="index.php" >
<div class="jumbotron text-center">
  <h1>Scanning Page</h1>
  
</div></a>';
}

else
{
	echo '<div class="jumbotron text-center">
  <h1>'.$title_top.'</h1>
  
</div>';
}
?>
