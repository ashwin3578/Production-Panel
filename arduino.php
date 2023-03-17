<?php 
$page_title='Arduino Manager';
$title_top='Arduino Manager';
$page = $_SERVER['PHP_SELF'];
?>

<?php include ('header.php');?>

<div class="container">
<!--Load Css -->
<link rel="stylesheet" href="css/xxx.css">
<!--Load Php navbar -->	
<?php include ('navbar.php'); ?>
<!--Load Php function -->	
<?php include ('function_framework.php'); ?>
<?php include ('function_arduino.php'); ?>
<!--Main page -->	
<?php ArduinoMangerController::show_main();?>

