<!DOCTYPE html>


<?php 
$page_title='MIS SCANNER';
$title_top='MIS SCANNER';
$page = $_SERVER['PHP_SELF'];
$sec = "120";
?><head>
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    </head>
<?php
include ('header.php'); ?>


<script>
window.onload = function() {
  document.getElementById("barcode").focus();
};

</script>

<style>
.counter{
	font-size: 50px;
	text-align: center;
	margin: auto;
	top: 50%;
    
}
.active-user{
	font-size: 30px;
	text-align: center;
	font-weight: bold;
}

</style>




<?php include ('function_scanner_MIS.php');?>
<?php include ('function_framework.php');?>

<?php manage_POST_scanning_MIS($db);?>
<?php general_view_scanning_MIS($db);?>




<?php



?>

