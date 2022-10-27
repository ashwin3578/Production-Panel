
<?php 
$page_title='Add Scan';
include ('header.php'); ?>


<div class="container">

	
	<?php include ('navbar.php'); ?>
	
	
	<?php
	
	
	if (!empty($_GET['workarea'])){
		$_SESSION['temp']['filter']['filter_workarea']=$_GET['workarea'];
	}
	if(empty($_SESSION['temp']['filter']['filter_workarea'])){$_SESSION['temp']['filter']['filter_workarea']='All';}
	
	if (!empty($_POST['timepicker'])){$_SESSION['temp']['addscan']['timepicker']=$_POST['timepicker'];}
	if (!empty($_POST['datetimepicker1'])){$_SESSION['temp']['addscan']['datetimepicker1']=$_POST['datetimepicker1'];}
	
	
	
	
	if (!empty($_POST)){
		$testdate=new datetime($_POST['datetimepicker1'].''.$_POST['timepicker']);
		
		$timestamp = $testdate->getTimestamp();
		$operator=$_POST['addscan_operator'];
		if (!empty($_POST['jobentry'])){
				$jobnumber=$_POST['jobmanual'];
			}
			else{
				$jobnumber=$_POST['joblist'];
			}
		
		savescan($db,$operator,$jobnumber,$timestamp);
		
		 $_SESSION['temp']['filter']['filter_operator']=get_operator_name($db,$_POST['addscan_operator']);
		echo'<div class="container"><div class="row"><div class="alert alert-success" role="alert">
				  Scan added : '.$_SESSION['temp']['filter']['filter_operator'].' '.date('Y-m-d G:i:s',$timestamp).' on '.$jobnumber.'
				</div></div></div>';
		
	}
	
	 //show($_SESSION['temp']);
	// show($_POST);
	?>
	
	
	
	
	<?php
  
	add_scan_procedure($db)	
	?>
	
</div>

 