

<?php 
$page_title='Log';
include ('header.php'); ?>


<div class="container">
	
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	<?php
	
	 
	 
	 
	 
	 echo'<div class="col-sm-9">';
	  echo show_log($db);
	 echo'</div>';
	
	 
	 
	 
	 
	//echo'<button type="button" class="btn btn-secondary" data-container="body" data-toggle="popover" title="Log" data-content="aa">';
	// echo'<big><span class="glyphicon glyphicon-info-sign" ></span></big></button>';
	
	 //;
	// echo show_log($db);

	?>
	
	
	


	
	
	
</div>
