<?php	
	if(!empty($_POST['delete_scan_start'])){
		//show($_POST);
		deletescan($db,$_POST['operator_code'],$_POST['timetag']);
		rescan_the_day ($db,$_POST['operator_code'],$_POST['timetag']);
		$_POST=[];
	}
	
	if(!empty($_POST['delete_scan_finish'])){
		
		deletescan($db,$_POST['operator_code'],$_POST['timetag_finish']);
		rescan_the_day ($db,$_POST['operator_code'],$_POST['timetag']);
		$_POST=[];
	}
	//show($_POST);
	
	if(!empty($_POST['modify'])){
		
		$testdate=new datetime($_SESSION['temp']['filter']['datetimepicker1'].''.$_POST['timestart']);
		$newtimetag_start = $testdate->getTimestamp();
		modify_scan($db,$_POST['operator_code'],$_POST['timetag'],$newtimetag_start,$_POST['jobnumber']);
		
		if(!empty($_POST['timefinish'])){
			$testdate2=new datetime($_SESSION['temp']['filter']['datetimepicker1'].''.$_POST['timefinish']);
			$newtimetag_finish = $testdate2->getTimestamp();
			
			if (!empty($_POST['finish_it'])){
				savescan($db,$_POST['operator_code'],$_POST['jobnumber'],$newtimetag_finish);
				//Show("1");
			}
			else{
				
				modify_scan($db,$_POST['operator_code'],$_POST['timetag_finish'],$newtimetag_finish,$_POST['jobnumber']);
			}
			
			
			
			
		}
		$_POST=[];
		
	}
	?>
	