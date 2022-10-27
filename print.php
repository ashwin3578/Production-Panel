<?php 


$page_title='BarCode Management';
$page = $_SERVER['PHP_SELF'];
$sec = "60";
?>

<?php



if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$start_time=time();
include ('dbconnection.php');
include ('function.php');
include ('function_framework.php'); ?>
<html lang="en">
<head>
  <title><?php echo $page_title; ?></title>
  <meta charset="utf-8">
 
  <script src="/css/styles.css?v=<?=time();?>"></script>
  <link rel="stylesheet" href="css/test.css">
  <link rel="stylesheet" href="css/checkbox.css">
  <?php // <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> ?>
   <link rel="stylesheet" href="css/bootstrap.css">
   <link rel="stylesheet" href="css/summary.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</head>
<body>

<div class="container " style="padding:15px;">

  

  
  
  
  
  <div class="row " style="padding:15px;text-align: center;">
    <div class="col-sm-12" style="padding:5px; text-align: center;font-size: 33px;">
      <?php
	  $operatorcode=$_GET['operator'];
	  
	 echo get_operator_name($db,$operatorcode);
		
		?>
		
    </div>
    <div class="col-sm-12" style="padding:15px; text-align: center;">
    <img src="img/Sicame01.png" alt="SicameLogoNew" width="300" />
	  </div>
  
	  <div class="col-sm-12" style="padding:15px; text-align: center;">
      <?php
	  $operatorcode=$_GET['operator'];
	  
	  print_barcode('OAA'.$operatorcode);
    
		?>
		
    </div>
	
	
	
	
  </div>
</div></form>

<?php


?>

 