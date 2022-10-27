

<?php 
$page_title='List Barcode';
include ('header.php'); 
redirect_if_not_logged_in();
?>


<div class="container">

	<?php include ('applyfilter.php'); ?>
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	<?php include ('filter.php'); ?>
  
	<?php 
	 // show($_SESSION['temp']);
	 // show($_POST);
	 // show($_SERVER);
	
	edit_scan_procedure($db);
	sort_list();
	
		

  
  
  if((!empty($_POST['load_scan_id'])&&!empty($_POST['load_scan_id']))or(!empty($_POST['add_a_scan']))){  $ratiocolumn=9;}else{$ratiocolumn=12;}

  
 
 
  echo'<div class="row" >
	<div class="col-sm-'.$ratiocolumn.'">';
	
	showline($db,$_SESSION['temp']['filter']['filter_operator'],$_SESSION['temp']['filter']['filter_workarea']); 
  
  echo '</div>';
	
	
	 if(!empty($_POST['load_scan_id'])&&!empty($_POST['load_scan_id'])){
		  echo'<div class="col-sm-'.(12-$ratiocolumn).' editpanel"  ">';
		 // show($_POST);
		  $scanid=$_POST['load_scan_id'];
		 edit_scan($db,$scanid);
		 
		 echo' </div>';
		 }
	if(!empty($_POST['add_a_scan'])){
		
		  echo'<div class="col-sm-'.(12-$ratiocolumn).' editpanel"  ">';
		 // show($_POST);
		 
		 //edit_scan($db,$scanid);
		 $date=$_SESSION['temp']['filter']['datetimepicker1'];
		 add_scan_window($db,$date);
		 echo' </div>';
		 }
	 
	
  echo' </div>';
 
  ?>
  
  
</div>

 <?php
	//loading_time($start_time);
	?> 