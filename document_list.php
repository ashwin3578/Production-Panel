

<?php 
$page_title='Log';
include ('header.php'); ?>


<div class="container">
	
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	<?php
	
	
	include('function_doc.php');
	
	// echo'<div class="col-sm-2">';
	
	// echo'</div>';	
	
	echo'<div class="col-sm-2">';
	echo (show_productfamily($db));
	echo'</div>';
	
	echo'<div class="col-sm-2">';
	echo show_workarea($db);
	echo'</div>';
	
	echo'<div class="col-sm-6">';
	echo (show_doc($db));
	echo'</div>';
	
	 
	 
	 
	 
	//echo'<button type="button" class="btn btn-secondary" data-container="body" data-toggle="popover" title="Log" data-content="aa">';
	// echo'<big><span class="glyphicon glyphicon-info-sign" ></span></big></button>';
	
	 //;
	// echo show_log($db);

	?>
	
	
	


	
	
	
</div>
