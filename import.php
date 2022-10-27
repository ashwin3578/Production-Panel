

<?php 
$page_title='Import';
include ('header.php'); ?>


<div class="container">

	
	
	<?php include ('navbar.php'); ?>
	
	<?php 
	//show($_SESSION['temp']);
	//show($_POST);
	
	$all_scan=load_scan_import($db);	
	//$time0=time();
	//show($all_scan);
	if(!empty($_SESSION['count'])){
	
			$time1=(time()-$_SESSION['time0']);
			$count=$_SESSION['count'];
			$stats1=round($_SESSION['count']/$time1,2);
			
			show("$count scan in $time1 secondes - $stats1 scan/sec");
			
			
			
	}
	
	$_SESSION['time0']=time();
	$_SESSION['count']=0;
	
	
	foreach ($all_scan as &$scan){
		savescan($db,$scan['import_operatorcode'],$scan['import_jobnumber'],$scan['import_timetag']);
		
		delete_scan_import($db,$scan['import_operatorcode'],$scan['import_jobnumber'],$scan['import_timetag']);
		$_SESSION['count']=$_SESSION['count']+1;
	}
	header("Refresh:0");
	
	
	
	?>
  
  
  
  
 
  
  <?php
  
  
 
  ?>
  
  
</div>
