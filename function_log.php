<?php

function log_enter_scan($db,$operator,$jobnumber,$timetag) {
		 $operatorname=get_operator_name($db,$operator);
		 $date=(date('Y-m-d',$timetag));
		 $entry="[ ".$operatorname." scanned on $jobnumber - ".date('G:i:s',$timetag)." ] - IP : ".$_SERVER['REMOTE_ADDR'];
		 
		 $query="INSERT INTO dbo.log
			( log_operatorcode,
			log_date,
			log_timestamp_entry,
			log_entry,
			log_ip,
			log_type,
			log_timestamp_modification) 
			VALUES (
			'$operator',
			'$date',
			'$timetag',
			'$entry',
			'".$_SERVER['REMOTE_ADDR']."',
			'scan',
			".time().")";
			
			$sql = $db->prepare($query); 

			$sql->execute();
		 
		 
		 
		 // return $entry;
	 }

function log_delete_scan($db,$operator,$timetag) {
		 $employee_name=get_employee_name($db,$_SESSION['temp']['id']);
		 
		 $operatorname=get_operator_name($db,$operator);
		 $date=(date('Y-m-d',$timetag));
		 $entry=$employee_name." delete the scan [ ".$operatorname." - ".date('G:i:s',$timetag)." ] - IP : ".$_SERVER['REMOTE_ADDR'];
		 
		 $query="INSERT INTO dbo.log
			( log_operatorcode,
			log_date,
			log_timestamp_entry,
			log_entry,
			log_ip,
			log_type,
			log_timestamp_modification) 
			VALUES (
			'$operator',
			'$date',
			'$timetag',
			'$entry',
			'".$_SERVER['REMOTE_ADDR']."',
			'delete',
			".time().")";
			
			$sql = $db->prepare($query); 

			$sql->execute();
		 
		 
		 
		 //echo $entry;
	 }

function log_add_a_scan($db,$operator,$jobnumber,$timetag) {
		$operatorname=get_operator_name($db,$operator);
		 $date=(date('Y-m-d',$timetag));
		 
		 
		if(empty($_SESSION['temp']['id'])){
			 $entry="[ ".$operatorname." scanned on $jobnumber - ".date('G:i:s',$timetag)." ] - IP : ".$_SERVER['REMOTE_ADDR'];
			 $type='scan';
		}
		else
		{
			 $employee_name=get_employee_name($db,$_SESSION['temp']['id']);
			 $entry=$employee_name." add the scan [ ".$operatorname." on $jobnumber - ".date('G:i:s',$timetag)." ] - IP : ".$_SERVER['REMOTE_ADDR'];
			 $type='add';
		}


		 $query="INSERT INTO dbo.log
			( log_operatorcode,
			log_date,
			log_timestamp_entry,
			log_entry,
			log_ip,
			log_type,
			log_timestamp_modification) 
			VALUES (
			'$operator',
			'$date',
			'$timetag',
			'$entry',
			'".$_SERVER['REMOTE_ADDR']."',
			'$type',
			".time().")";
			
			$sql = $db->prepare($query); 

			$sql->execute();
		 
		 
		 
		 // return $entry;
	 }

function show_log($db,$operator='All',$date='All'){
		$operatorfilter='';
		$daysfilter='';
		if($operator<>'All'){
			$operatorfilter='AND log_operatorcode=\''.$operator.'\'';
		}
		
		if($date<>'All'){
			$daysfilter='AND log_date=\''.$date.'\'';
		}
		
		$query='SELECT TOP 500 * FROM dbo.log 
		LEFT JOIN
		operator
		ON
		log_operatorcode=operator_code
		
		WHERE 
		1=1		
		
		'.$operatorfilter.'
		
		'.$daysfilter.'
		
		ORDER BY log_id DESC,[log_timestamp_modification] ASC,[log_timestamp_entry] ASC 
		';
		
		$sql = $db->prepare($query); 
			 //show($query);
		$sql->execute();

		$row=$sql->fetchall();
		// show($row);
		foreach ($row as &$logentry){
			  $table=$table.='<div class="row" >';
			  
			if(substr ($logentry['log_type'],0,4)=='scan'){
				$table=$table.'<big><span class="glyphicon glyphicon-barcode" ></span></big> ';
			}
			if(substr ($logentry['log_type'],0,3)=='add'){
				$table=$table.'<big><span class="glyphicon glyphicon-plus" ></span></big> ';
			}
			if(substr ($logentry['log_type'],0,6)=='delete'){
				$table=$table.'<big><span class="glyphicon glyphicon-remove" ></span></big> ';
			}
			
			
			if(empty($logentry['log_timestamp_modification'])){
				$timetoshow=$logentry['log_timestamp_entry'];
			}else{
				$timetoshow=$logentry['log_timestamp_modification'];
			}
			
			 $table=$table.$logentry['log_type'].' - '.date('Y-m-d G:i:s',$timetoshow).' - '.$logentry['log_entry']. "";
			 
			 $table=$table.'</div>';
		 }
		 
		 return $table;
		
	 }
	 
 function button_log($db,$operator='All',$date='All'){
		 echo'<div class="popover__wrapper">';
		
	  echo'';
	 echo'<big><span class="glyphicon glyphicon-info-sign popover__title" ></span></big>	 ';
	 
	  echo'<div class="popover__content">';
		echo show_log($db,$operator,$date);
	 echo' </div>
	</div>';
	 }
	 
	 
?>