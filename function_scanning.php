<?php


function rescan_temp_table($db){
	$tempscan=load_temp_scan($db);
	//show($tempscan);
	$nbr_of_process=0;
	$oldscan=array();
	foreach ($tempscan as &$rescan) {
		$rescan['scan_timetag']=check_scan_exist($db,$rescan['scan_operatorcode'],$rescan['scan_timetag']);
		$date=(date('Y-m-d',$rescan['scan_timetag']));	
		//find what will be the status of that scan, start scan or finish scan
		$status=status_previous_scan($db,$rescan['scan_operatorcode'],$rescan['scan_jobnumber'],$rescan['scan_timetag'],$date);
		//show(date('G:i:s',$rescan['scan_timetag']));
		//show($status);
		//load the last scan with all the info
		$oldscan=load_previous_scan($db,$rescan['scan_operatorcode'],$rescan['scan_timetag'],$date);
		//show($oldscan);
		//if no old scan we just add the new one
		
		if(empty($oldscan)){
			add_start_scan($db,$rescan['scan_operatorcode'],$rescan['scan_jobnumber'],$rescan['scan_timetag'],$date,$status);
			$nbr_of_process=1;
			
		}
		else{
			//if there is an old scan 
			
			//echo'nbr de process';
			//show($nbr_of_process);
			
			
			if($nbr_of_process==0)
			{
				$rawtime=0;
				$distributed_time=0;
				
			}
			else
			{
				$rawtime=$rescan['scan_timetag']-$oldscan[0]['scan_timetag'];
				$distributed_time=$rawtime/$nbr_of_process;		
							
			}
					///help putain!!
			add_distributed_time_to_all_still_open($db,$rescan['scan_operatorcode'],$date,$distributed_time);
			
			if($status=='finish'){
				close_job($db,$rescan['scan_operatorcode'],$rescan['scan_jobnumber'],$rescan['scan_timetag'],$date,$rawtime);
				$nbr_of_process=$nbr_of_process-1;
			}
			else{
				$nbr_of_process=$nbr_of_process+1;
			}
			
			
			add_scan($db,$rescan['scan_operatorcode'],$rescan['scan_jobnumber'],$rescan['scan_timetag'],$date,$status,$nbr_of_process);
			
			
			
		//	show($timetag);
		//	show($oldscan[0]['scan_timetag']);
		//	show($rawtime);
			
		}
		
	}
	
	
	empty_temp_table($db);
}

function savescan($db,$operatorcode,$jobnumber,$timetag) {
	
	//check there are no scan with the same timetag 
	//if so add 1secondes to newtimetag and rescan
	check_if_scan_opened_yesterday ($db,$operatorcode,$jobnumber,$timetag);
	$timetag=check_scan_exist($db,$operatorcode,$timetag);
	
	//reformate date and time:
	$date=(date('Y-m-d',$timetag));
	$time=(date('H:m:s',$timetag));
	
	
	log_add_a_scan($db,$operatorcode,$jobnumber,$timetag);
	
	//copy all scan all scan for the day in table temp so they will be rescan after that
	empty_temp_table($db);
	copy_scan_of_today($db,$operatorcode,$timetag,$date);
	add_current_temp_table($db,$operatorcode,$timetag,$date,$jobnumber);
	rescan_temp_table($db);
	
}

function rescan_the_day ($db,$operatorcode,$timetag){
	$date=(date('Y-m-d',$timetag));
	empty_temp_table($db);
	copy_scan_of_today($db,$operatorcode,$timetag,$date);
	
	rescan_temp_table($db);
	
}

function modify_scan($db,$operator,$timetag,$newtimetag,$jobnumber){
	
	deletescan($db,$operator,$timetag);
	savescan($db,$operator,$jobnumber,$newtimetag);
		
}

function deletescan($db,$operator,$timetag){
	$date=(date('Y-m-d',$timetag));
	
	$query='DELETE FROM dbo.scan
	WHERE scan_operatorcode=\''.$operator.'\'
	
	and scan_timetag='.$timetag;
	
	$sql = $db->prepare($query); 

	$sql->execute();
	
	log_delete_scan($db,$operator,$timetag);
	copy_scan_of_today($db,$operator,$timetag,$date);
	rescan_temp_table($db);
	
}

function add_start_scan($db,$operatorcode,$jobnumber,$timetag,$date,$status){
	$query="INSERT INTO dbo.scan
	( scan_operatorcode,
	scan_jobnumber,
	scan_date,
	scan_timetag,
	scan_statut,
	scan_process_started,
	scan_still_open,
	scan_ip_last_change
	) 
	VALUES (
	'$operatorcode',
	'$jobnumber',
	'$date',
	'$timetag',
	'$status',
	1,
	1,
	'".$_SERVER['REMOTE_ADDR']."')";
	
	$sql = $db->prepare($query); 

	$sql->execute();
	//show($query);
	
}

function check_scan_exist($db,$operatorcode,$timetag){
	$query='SELECT * FROM dbo.scan 
	WHERE scan_operatorcode=\''.$operatorcode.'\'
	
	and scan_timetag='.$timetag;
	
	$sql = $db->prepare($query); 

	$sql->execute();
	//show($query);
	$row=$sql->fetchall();
	
	if(empty($row)){
		$newtimetag=$timetag;
	}
	else{
		$newtimetag=$timetag+1;
	}
	
	return $newtimetag;
}

function status_previous_scan($db,$operatorcode,$jobnumber,$timetag,$date){
	$query='SELECT * FROM dbo.scan 
	WHERE scan_operatorcode=\''.$operatorcode.'\'
	and scan_jobnumber=\''.$jobnumber.'\'
	and scan_timetag<\''.$timetag.'\'
	and scan_date=\''.$date.'\'
	Order by scan_timetag DESC
	
	';
	$sql = $db->prepare($query); 
	
	$sql->execute();

	$row=$sql->fetch();
	//echo'previous_scan:';
	//show($row);
	if(empty($row)){
		$status='start';
	}
	else{
		if($row['scan_statut']=='finish'){
			$status='start';
		}
		else{
		$status='finish';
		}
	}
	return $status;
}

function load_previous_scan($db,$operatorcode,$timetag,$date){
	$query='SELECT * FROM dbo.scan 
	WHERE scan_operatorcode=\''.$operatorcode.'\'	
	and scan_timetag<\''.$timetag.'\'
	and scan_date=\''.$date.'\'
	Order by scan_timetag DESC
	
	';
	$sql = $db->prepare($query); 

	$sql->execute();

	$row=$sql->fetchall();
	
	return $row;
}

function load_scan_from_job($db,$jobnumber){
	$query='SELECT * FROM dbo.scan 
	WHERE scan_jobnumber=\''.$jobnumber.'\'	
	';
	$sql = $db->prepare($query); 

	$sql->execute();
//show($query);
	$row=$sql->fetchall();
	
	return $row;
}

function load_starting_scan($db,$operatorcode,$jobnumber,$timetag,$date){
	$query='SELECT * FROM dbo.scan 
	WHERE scan_operatorcode=\''.$operatorcode.'\'	
	and scan_jobnumber=\''.$jobnumber.'\'
	and scan_timetag<\''.$timetag.'\'
	and scan_date=\''.$date.'\'
	and scan_still_open=1
	Order by scan_timetag DESC
	
	';
	$sql = $db->prepare($query); 
	
	$sql->execute();

	$row=$sql->fetch();
	
	return $row;
}

function add_current_temp_table($db,$operatorcode,$timetag,$date,$jobnumber){
	$query="INSERT INTO dbo.temp_scan
	( scan_operatorcode,
	scan_jobnumber,
	scan_date,
	scan_timetag) 
	VALUES (
	'$operatorcode',
	'$jobnumber',
	'$date',
	'$timetag')";
	
	$sql = $db->prepare($query); 
	// show($query);
	$sql->execute();
}

function copy_scan_of_today($db,$operatorcode,$timetag,$date){
	$query='INSERT INTO dbo.temp_scan
	SELECT * FROM dbo.scan
	WHERE scan_operatorcode=\''.$operatorcode.'\'
	and scan_date=\''.$date.'\'
	
	
	';
	$sql = $db->prepare($query); 

	$sql->execute();
	
	$query='DELETE FROM dbo.scan
	WHERE scan_operatorcode=\''.$operatorcode.'\'
	and scan_date=\''.$date.'\'
	
	
	';
	$sql = $db->prepare($query); 

	$sql->execute();
	
	
	
	
	
	
	
	
}

function empty_temp_table($db){
	$query='DELETE FROM dbo.temp_scan WHERE 1=1 ';
	$sql = $db->prepare($query); 

	$sql->execute();
}

function load_temp_scan($db){
	$query='SELECT scan_operatorcode,scan_jobnumber,scan_timetag FROM dbo.temp_scan 
	WHERE 1=1 
	Order by scan_timetag ASC
	
	';
	$sql = $db->prepare($query); 

	$sql->execute();

	$row=$sql->fetchall();
	
	return $row;
}

function add_distributed_time_to_all_still_open($db,$operatorcode,$date,$distributed_time){
	
	
	
	$query='UPDATE dbo.scan
	SET scan_time_distributed=scan_time_distributed+'.$distributed_time.'
	WHERE scan_operatorcode=\''.$operatorcode.'\'
	
	
	and scan_date=\''.$date.'\'
	and scan_still_open=1
	
	
	';
	// show($query);
	$sql = $db->prepare($query); 

	$sql->execute();
}

function close_job($db,$operatorcode,$jobnumber,$timetag,$date){
	
	$starting_scan=load_starting_scan($db,$operatorcode,$jobnumber,$timetag,$date);
	$time_real=$timetag-$starting_scan['scan_timetag'];
	
	$query='UPDATE dbo.scan
	SET scan_time_real='.$time_real.',
	scan_ratio=scan_time_distributed/'.$time_real.',
	scan_still_open=0,
	scan_timetag_finish='.$timetag.'
	
	WHERE scan_operatorcode=\''.$operatorcode.'\'	
	and scan_timetag=\''.$starting_scan['scan_timetag'].'\'
	';
	// show($query);
	$sql = $db->prepare($query); 

	$sql->execute();
	
	
}

function add_scan($db,$operatorcode,$jobnumber,$timetag,$date,$status,$nbr_of_process){
	if($status=='start'){$stillopen=1;}else{$stillopen=0;}
	
	$query="INSERT INTO dbo.scan
	( scan_operatorcode,
	scan_jobnumber,
	scan_date,
	scan_timetag,
	scan_statut,
	scan_process_started,
	scan_still_open,
	scan_ip_last_change) 
	VALUES (
	'$operatorcode',
	'$jobnumber',
	'$date',
	'$timetag',
	'$status',
	'$nbr_of_process',
	'$stillopen',
	'".$_SERVER['REMOTE_ADDR']."')";
	
	$sql = $db->prepare($query); 

	$sql->execute();
	//show($query);
	
}

function transfer_scan ($db,$job1,$job2){
	//select all scan from job1
	$allscan=load_scan_from_job ($db,$job1);
	//show($allscan);
	//for each scan, delete the old one and scan the new one
	foreach ($allscan as &$rescan) {
		deletescan($db,$rescan['scan_operatorcode'],$rescan['scan_timetag']);
		savescan($db,$rescan['scan_operatorcode'],$job2,$rescan['scan_timetag']);
	}
	
	return 'scan done';
}

function check_if_scan_opened_yesterday ($db,$operatorcode,$jobnumber,$timetag){
	//check if the scan was opened the day before, if so a scan is added at 23:59:00 and another is added at 00:01:00
	
	if(is_it_open_yesterday($db,$operatorcode,$jobnumber,$timetag)==true){
		//show('add two scans');
		$first_scan_to_add = strtotime(date('Y-m-d',$timetag).' midnight')-20;
		$first_scan_to_add=check_scan_exist($db,$operatorcode,$first_scan_to_add);
		$date=(date('Y-m-d',$first_scan_to_add));
		empty_temp_table($db);
		copy_scan_of_today($db,$operatorcode,$first_scan_to_add,$date);
		add_current_temp_table($db,$operatorcode,$first_scan_to_add,$date,$jobnumber);
		rescan_temp_table($db);
		
		$second_scan_to_add=$first_scan_to_add+40;
		$second_scan_to_add=check_scan_exist($db,$operatorcode,$second_scan_to_add);
		$date=(date('Y-m-d',$second_scan_to_add));
		empty_temp_table($db);
		copy_scan_of_today($db,$operatorcode,$second_scan_to_add,$date);
		add_current_temp_table($db,$operatorcode,$second_scan_to_add,$date,$jobnumber);
		rescan_temp_table($db);
		
		
	}
}

function is_it_open_yesterday($db,$operatorcode,$jobnumber,$timetag){
	
	$timetag_midnight_day_before = strtotime(date('Y-m-d',$timetag).' midnight');
	
	$query='SELECT scan_id FROM dbo.scan 
	WHERE scan_operatorcode=\''.$operatorcode.'\'
	and scan_jobnumber=\''.$jobnumber.'\'
	and scan_timetag>('.$timetag.'-86400)
	and scan_timetag<'.$timetag_midnight_day_before.'
	and scan_still_open=1';
	
	$sql = $db->prepare($query); 
	
	$sql->execute();

	$row=$sql->fetch();
	//echo'previous_scan:';
	//show($query);
	if(empty($row)){
		$status=false;
	}
	else{
		$status=true;
	}
	return $status;
}

function redirect_if_not_logged_in(){
		if(empty($_SESSION['temp']['role_barcode_management'])){
			header('Location: index.php');
		}
	}


?>