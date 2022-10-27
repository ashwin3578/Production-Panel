

<?php $starttime = microtime(true); // Top of page
$page_title='Layout View';
$title_top='MIS Live Tracking';
$page = $_SERVER['PHP_SELF'];
$sec = "5000";
?><head>
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    </head>
<?php
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/issue_log.css">	


	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	<?php include ('function_framework.php'); ?>
	<?php include ('function_roster.php'); ?>
	
	<?php create_css ($db,'allocationwork');
	
	echo'<div class="row navbar-view">';
		echo'<div class="col-sm-1 ">';
			echo'<form method="POST">';
			echo'<div class="visible-xs-block visible-sm-block visible-md-block">';	
				echo'<button type="submit"  class="btn btn-default" >
									<span class="glyphicon glyphicon-refresh" ></span>
				</button><br>&nbsp';
			echo'</div>';
			echo'<div class="hidden-xs hidden-sm hidden-md">';	
				echo'<button type="submit"  class="btn btn-default" >
									<span class="glyphicon glyphicon-refresh" ></span>
				</button><br>&nbsp';
			echo'</div>';
			echo'</form>';
		echo'</div>';
		echo'<div class="col-sm-2 ">';
			$currenttimevalue=time()-strtotime(date('Y-m-d',time()));
			//show($currenttimevalue);
			echo'<input id="time_input"  type="range" min="1" max="86400" step="900" value="'.$currenttimevalue.'" ><br>';
		echo'</div>';
		echo'<div  class="col-sm-2 ">';
			//show(date('H:i',time()));
			echo'<input id="show_time" class="form-control" type="time"  readonly value="'.date('H:i',time()).'" ><br>';
		echo'</div>';
		
		echo'<div  class="col-sm-2 ">';
			
			echo'<input id="date_input" class="form-control" type="date"  value="'.date('Y-m-d',time()).'" ><br>';
		echo'</div>';
		echo'<div  class="col-sm-1 ">';
		echo'</div>';
		echo'<div  class="col-sm-2 ">';
			
			$livedetails=get_details_open($db);
			gauge('Operator',$livedetails['Count_Operator'],0,50);
			//echo'Number of Operator : '.$livedetails['Count_Operator'];
		echo'</div>';
		echo'<div  class="col-sm-2 ">';
			gauge('Process',$livedetails['Count_MIS'],0,50);
			//echo'Number of Process : '.$livedetails['Count_MIS'];
		echo'</div>';
	echo'</div>';

	


	echo'<script>
	const input = document.getElementById("time_input");
	const input2 = document.getElementById("date_input");
	let timer = null;
	
	
	function refreshgraph() {
		testfunction();
	}	

	function restartTimer() {
		clearTimeout(timer);
  		timer = setTimeout(refreshgraph, 100);
		}
	function updateshow() {
		thedate= new Date();
		thedate.setTime(-10*3600*1000+document.getElementById("time_input").value*1000);
		currentHours = thedate.getHours();
		currentMinutes = thedate.getMinutes();
		currentHours = ("0" + currentHours).slice(-2);
		currentMinutes = ("0" + currentMinutes).slice(-2);
		document.getElementById("show_time").value = currentHours + ":" + currentMinutes;
		
		}
		  
		input.addEventListener(\'input\', restartTimer);
		input.addEventListener(\'input\', updateshow);
		input2.addEventListener(\'input\', restartTimer);
		
	
	
	</script>';
	$name='testfunction';
    $data[]=['Timetoshow','document.getElementById("show_time").value'];
	$data[]=['Timedatetoshow','document.getElementById("date_input").value'];
    $target='view_ajax.php';
    $classtarget='postinfo';
	ajax_button($name,$data,$target,$classtarget);
	
	
		
		
		
		
		
		
		










	
	echo'<div class="postinfo" >';
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
		show($allprocess);
		//show($data);
		
		



		//$allprocess=get_all_current_open($db);

		echo'<div class="row ">';
		//show($_POST);
		echo'</div>';
		//show(prepare_data($allprocess));
		live_view_layout($db,$allprocess);

	echo'</div>';


	$endtime = microtime(true); // Bottom of page
	//printf("Page loaded in %f seconds", $endtime - $starttime );
	//update_div('Moulding-title','dont','Test');
	?>
	
	
	


	
	
	
</div>


<?php

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
        echo'<div class="Other Workarea">';
		echo'<div class="title-workarea">Other';
		$count=$data['Other']['nbr_operator'];
		if(!empty($count)){echo' ('.$count.')';}
		echo'</div>';
			echo'<div class="containeur-MIS">';
			foreach ($data['Other']['Process'] as &$process){
				show_MIS($process);
			}
			
			echo'</div>';
		echo'</div>';


	echo'</div>';
}




?>