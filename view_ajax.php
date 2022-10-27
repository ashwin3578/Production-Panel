

<?php 
session_start();
include ('dbconnection.php');
include ('function.php');


$timetag=strtotime($_POST['Timedatetoshow'].' '.$_POST['Timetoshow'])	;
$today=date('Y-m-d',$timetag)	;
$query='SELECT * FROM dbo.scan 
LEFT JOIN
dbo.operator
ON
scan_operatorcode=operator_code
LEFT JOIN
dbo.MIS_List
ON
scan_jobnumber=ManufactureIssueNumber

WHERE 
scan_statut=\'start\'

and (scan_timetag_finish >='.$timetag.' or scan_timetag_finish is null)
and scan_timetag <='.$timetag.'
AND scan_date=\''.$today.'\'

ORDER bY WorkArea,scan_jobnumber, operator_fullname
';

$sql = $db->prepare($query); 
//show($query);
$sql->execute();

$allprocess=$sql->fetchall();




//$allprocess=get_all_current_open($db);

echo'<div class="row ">';
//show($_POST);
echo'</div>';
live_view_layout($db,$allprocess);

	
	
	
function show_MIS($process){
	//show($process);
	echo' <div class="popover__wrapper2">';
		echo'<div class="MIS ">';
			echo'<div class="row title-MIS">'.$process['Process'].'</div>';
			foreach ($process['MIS'] as &$MIS){
				echo'<div class="row row-MIS">'.$MIS.'</div>';
				foreach ($process[$MIS]['Operator'] as &$operator){
					echo'<div class="row row-operator">'.$operator['name'].'</div>';
				}
			}
			
		echo'</div>';
		echo'<div class="popover__content2">';
			echo'<div class="MIS-Big ">';
				echo'<div class="row title-MIS">'.$process['Process'].'</div>';
				foreach ($process['MIS'] as &$MIS){
					echo'<div class="row row-MIS">'.$MIS.'</div>';
					foreach ($process[$MIS]['Operator'] as &$operator){
						echo'<div class="row row-operator">'.$operator['name'].'</div>';
						echo '<div class="row row-operator">'.date('G:i',$operator['timetag']);
						if(!empty($operator['timetag_finish'])){echo ' - '.date('G:i',$operator['timetag_finish']);}
						echo'</div>';
					}
				}
				
			echo'</div>';
		echo'</div>';
	echo'</div>';
   
	
	
}

function prepare_data($allprocess){
	$workarea='';
	$operator='';
	$code='';
	$data=array();
	$i=0;
	$oldcode='';
	foreach ($allprocess as &$process){
		$workarea=$process['WorkArea'];
		$code=$process['Code'];
		if(substr($code,0,8)=='PRINTING'){$workarea='Printing';}
		$uniquecode=$process['Code'].' - '.$process['scan_jobnumber'];
		$MIS=$process['scan_jobnumber'];
		$time=round((time()-$process['scan_timetag'])/3600,1);
		$data[$workarea]['workarea']=$workarea;
		
		$data[$workarea]['Process'][$code]['Process']=$code;
		$data[$workarea]['Process'][$code]['MIS'][]=$MIS;
		$data[$workarea]['Process'][$code]['MIS']=array_unique($data[$workarea]['Process'][$code]['MIS']);
		
		$data[$workarea]['Process'][$code][$MIS]['Operator'][$process['operator_fullname']]['name']=$process['operator_fullname'];
		$data[$workarea]['Process'][$code][$MIS]['Operator'][$process['operator_fullname']]['timetag']=$process['scan_timetag'];
		$data[$workarea]['Process'][$code][$MIS]['Operator'][$process['operator_fullname']]['timetag_finish']=$process['scan_timetag_finish'];
		
		$oldcode=$code;			
		//$data[$workarea][$code]['total_time']=$data[$workarea][$code]['total_time']+$time;
	}
	return $data;
}

function live_view_layout($db,$allprocess){
	
	$data= prepare_data($allprocess);


	


	echo '<link rel="stylesheet" href="css/factory.css?v='.time().'">';
	echo'<div class="Factory">';
		echo'<div class="Machining Workarea">';
			echo'<div class="title-workarea">Machining';
			$count=$data['Machining']['nbr_operator'];
			if(!empty($count)){echo' ('.$count.')';}
			echo'</div>';
			echo'<div class="containeur-MIS">';
			foreach ($data['Machining']['Process'] as &$process){
				show_MIS($process);
			}
			
			
			echo'</div>';
		echo'</div>';
		echo'<div class="Moulding Workarea">';
		echo'<div id="Moulding-title" class="title-workarea">Moulding';
		$count=$data['Moulding']['nbr_operator'];
		if(!empty($count)){echo' ('.$count.')';}
		echo'</div>';
			echo'<div class="containeur-MIS">';
			foreach ($data['Moulding']['Process'] as &$process){
				show_MIS($process);
			}
			echo'</div>';
		echo'</div>';
		echo'<div class="BoltAsmb Workarea">';
		echo'<div class="title-workarea">Push-On Bolt';
		$count=$data['Push-On Bolt']['nbr_operator'];
		if(!empty($count)){echo' ('.$count.')';}
		echo'</div>';
			echo'<div class="containeur-MIS">';
			foreach ($data['Push-On Bolt']['Process'] as &$process){
				show_MIS($process);
			}
			
			echo'</div>';
		echo'</div>';
		echo'<div class="Bolt Workarea">';
		echo'<div class="title-workarea">Bolt';
		$count=$data['Bolt']['nbr_operator'];
		if(!empty($count)){echo' ('.$count.')';}
		echo'</div>';
			echo'<div class="containeur-MIS">';
			foreach ($data['Bolt']['Process'] as &$process){
				show_MIS($process);
			}
			
			echo'</div>';
		echo'</div>';
		echo'<div class="Cutting Workarea">';
		echo'<div class="title-workarea">Cutting';
		$count=$data['Cutting']['nbr_operator'];
		if(!empty($count)){echo' ('.$count.')';}
		echo'</div>';
			echo'<div class="containeur-MIS">';
			foreach ($data['Cutting']['Process'] as &$process){
				show_MIS($process);
			}
			
			echo'</div>';
		echo'</div>';
		echo'<div class="CATU Workarea">';
		echo'<div class="title-workarea">CATU';
		$count=$data['CATU']['nbr_operator'];
		if(!empty($count)){echo' ('.$count.')';}
		echo'</div>';
			echo'<div class="containeur-MIS">';
			foreach ($data['CATU']['Process'] as &$process){
				show_MIS($process);
			}
			
			echo'</div>';
		echo'</div>';
		echo'<div class="Assembly Workarea">';
		echo'<div class="title-workarea">Assembly';
		$count=$data['Assembly']['nbr_operator'];
		if(!empty($count)){echo' ('.$count.')';}
		echo'</div>';
			echo'<div class="containeur-MIS">';
			foreach ($data['Assembly']['Process'] as &$process){
				show_MIS($process);
			}
			
			echo'</div>';
		echo'</div>';
		echo'<div class="Printing Workarea">';
		echo'<div class="title-workarea">Printing';
		$count=$data['Printing']['nbr_operator'];
		if(!empty($count)){echo' ('.$count.')';}
		echo'</div>';
			echo'<div class="containeur-MIS">';
			foreach ($data['Printing']['Process'] as &$process){
				show_MIS($process);
			}
			
			echo'</div>';
		echo'</div>';


	echo'</div>';
}		
	
	


?>