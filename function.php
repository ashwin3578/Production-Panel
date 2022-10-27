<?php
date_default_timezone_set('Australia/Brisbane');

include ('function_scanning.php');

include ('function_admin.php');

include ('function_view.php'); 

include ('function_log.php'); 

 
function headerline(){
	
	
	echo' <div class="row"><center>
  
	
	  
		
		<div class="col-sm-3">
		<a href="list-view.php?day=d">
		<center><svg width="3em" height="3em" viewBox="0 0 16 16" class="bi bi-arrow-left" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
			<path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
		</svg></center>
		</a>
		</div>
		<div class="col-sm-4">
		<center>
		
		<h3>';
		echo "" . date("l jS M Y", mktime(0, 0, 0, $_SESSION['temp']['filter']['month'], $_SESSION['temp']['filter']['day'], $_SESSION['temp']['filter']['year']));
		
		echo'</h3></center>
		
		</div>
		<div class="col-sm-3">
		<a href="list-view.php?day=u">
		<center><svg width="3em" height="3em" viewBox="0 0 16 16" class="bi bi-arrow-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
			<path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
		</svg></center>
		</div> 
		</a>
  </center></div>
  
	<div class="row"><center>
		<div class="col-sm-1">
		 
		<b>
		MIS <a href="list-view.php?sort=MIS"  ><span class="glyphicon glyphicon-sort" aria-hidden="true"></span></a>
		</b>
		</div>
		<div class="col-sm-2">
		<b>
		Product Code <a href="list-view.php?sort=Product"  ><span class="glyphicon glyphicon-sort" aria-hidden="true"></span></a>
		</b>
		
		</div>
		<div class="col-sm-2">
		<b>
		Operator <a href="list-view.php?sort=Operator"  ><span class="glyphicon glyphicon-sort" aria-hidden="true"></span></a>
		</b>
		 
		</div>
		<div class="col-sm-1">
		<b>
		Start <a href="list-view.php?sort=Start"  ><span class="glyphicon glyphicon-sort" aria-hidden="true"></span></a>
		</b>
		
		</div>
		<div class="col-sm-1">
		<b>
		Finish <a href="list-view.php?sort=Finish"  ><span class="glyphicon glyphicon-sort" aria-hidden="true"></span></a>
		</b>  
		</div>
		<div class="col-sm-1">
		<b>
		Hours <a href="list-view.php?sort=Hours"  ><span class="glyphicon glyphicon-sort" aria-hidden="true"></span></a>
		</b>   
		</div>
		<div class="col-sm-1">
		<b>
		Ratio <a href="list-view.php?sort=Ratio"  ><span class="glyphicon glyphicon-sort" aria-hidden="true"></span></a>
		</b>  
		</div>
		<div class="col-sm-1">
		 
		</div>
	 </center></div>
	 
	 
  ';
	
}

function load_all_scan($db,$date,$operator,$workarea,$finishedjob){
	
	$operatorfilter='';
	$workareafilter='';
	$finishedjobfilter='';
	
	if ($operator<>'All'){
		$operatorfilter=' AND operator_fullname=\''.$operator.'\' ';
	}
	
	if ($workarea<>'All'){
		$workareafilter=' AND operator_workarea=\''.$workarea.'\' ';
	}
	if (!empty($_SESSION['temp']['filter']['showfinishedjob'])){
		$finishedjobfilter=' AND scan_still_open=1 ';
	}
	
	if(empty($_SESSION['temp']['sort']))
	{
		$sort='scan_timetag ASC';
	}
	else
	{
		$sort=$_SESSION['temp']['sort'];
	}
	$query='SELECT * FROM dbo.scan 
	LEFT JOIN
	operator
	ON
	scan_operatorcode=operator_code
	
	WHERE scan_date=\''.$date.'\'
	AND scan_statut=\'start\'
	'.$operatorfilter.'
	'.$workareafilter.'
	'.$finishedjobfilter.'
	
	Order by '.$sort;
	$sql = $db->prepare($query); 

	$sql->execute();

	$row=$sql->fetchall();
	//show($query);
	return $row;
}

function showline($db,$operator='All',$workarea='All',$finishedjob=0){
	
	 headerline();
	$date=$_SESSION['temp']['filter']['year'].'-'.$_SESSION['temp']['filter']['month'].'-'.$_SESSION['temp']['filter']['day'];
	$allscan=load_all_scan($db,$date,$operator,$workarea,$finishedjob);
	
	//show($allscan);
	$sumhours=0;
	foreach ($allscan as &$scan){
		echo'<form action="list-view.php" method="post">';
		echo'<div class="row "><center>';
			echo'<div class="col-sm-1">';
			  echo $scan['scan_jobnumber'];
			echo'</div>';
			echo'<div class="col-sm-2">';
			  echo get_product_code($db,$scan['scan_jobnumber']);
			echo'</div>';
			echo'<div class="col-sm-2"><a href="list-view.php?operator='.$scan['operator_fullname'].'"  >';
			  echo $scan['operator_fullname'];
			echo'</a></div>';
			echo'<div class="col-sm-1">';
			  echo date('G:i:s',$scan['scan_timetag']);
			echo'</div>';
			echo'<div class="col-sm-1">';
			 if(!empty($scan['scan_timetag_finish'])){echo date('G:i:s',$scan['scan_timetag_finish']);}else{echo '-';}
			echo'</div>';
			echo'<div class="col-sm-1">';
			  if($scan['scan_still_open']==0){echo showhours($scan['scan_time_distributed'],2);} else { echo"-";}
			echo'</div>';
			echo'<div class="col-sm-1">';
			  if($scan['scan_still_open']==0){echo ($scan['scan_ratio']*100).'%';} else { echo"-";}
			echo'</div>';
			echo'<div class="col-sm-1">';
			  
			  echo'<button type="submit" value="'.$scan['scan_id'].'" name="load_scan_id" class="btn btn-default" aria-label="Left Align">
				  <span class="glyphicon glyphicon glyphicon-circle-arrow-right" aria-hidden="true"></span>
				</button>  ';
		//	  echo' <button type="submit" value="'.$scan['scan_id'].'" name="delete_scan_id" onclick="return confirm(\'Are you sure to delete MIS32544?\')"class="btn btn-default" aria-label="Left Align">
		//		  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
		//		</button>';
			echo'</div>';
		echo'</center></div>';
		echo'</form>';
		$sumhours=$sumhours+$scan['scan_time_distributed'];
	}
	echo'<div class="row ">';
			
			echo'<div class="col-sm-6">';
			 
			echo'</div>';
			echo'<div class="col-sm-1">';
			  echo '<b>Total :</b>';
			echo'</div>';
			echo'<div class="col-sm-1"><b>';
			  echo showhours($sumhours);
			echo'</b></div>';
			
			echo'<div class="col-sm-1">';
			  
			 
		
			echo'</div>';
		echo'</div>';
	
}

function edit_scan($db,$scanid){
	$scan=load_a_scan($db,$scanid);
	echo'<form  method="post">';
	echo'<br><br>';
	echo'<div class="row">';
		echo'<div class="col-sm-12">';
		echo'<h3><center>Edit / Delete</center></h3>';
		echo'</div>';
		
	echo'</div>';
	echo'<br><br>';
	echo'<div class="row">';
		echo'<div class="col-sm-4">';
		echo'Operator';
		echo'</div>';
		echo'<div class="col-sm-6">';
		echo $scan['operator_fullname'];
		echo'</div>';
	echo'</div><br>';
	
	echo'<div class="row">';
		echo'<div class="col-sm-4">';
		echo'MIS #';
		echo'</div>';
		echo'<div class="col-sm-6">';
		
		echo'  	  
		  <script>
				
		function addHash(elem) {
				  var val = elem.value;
				  if(!val.match(/^MIS/)) {
					elem.value = "MIS" + val;
				  }
				}
		  </script>';
		echo'<input type="textbox" placeholder="MIS12345" name="jobnumber" class="form-control" value="'.$scan['scan_jobnumber'].'" id="jobmanual"  onkeyup="addHash(this)" >';
		
		echo'</div>';
	echo'</div><br>';
	
	echo'<div class="row">';
		echo'<div class="col-sm-4">';
		echo'Product';
		echo'</div>';
		echo'<div class="col-sm-6">';
		echo "<i>".get_product_code($db,$scan['scan_jobnumber'])."</i>";
		echo'</div>';
	echo'</div><br>';
	
	echo'<div class="row">';
		echo'<div class="col-sm-3">';
		echo'Time Started';
		echo'</div>';
		echo'<div class="col-sm-6">';
		  echo'<input type="time" name="timestart" value="'.date('H:i',$scan['scan_timetag']).'" id="timestart" class="form-control" id="usr">';
		
		echo'</div>';
		echo'<div class="col-sm-2">';
			 echo' <button type="submit" value="'.date('H:i:s',$scan['scan_timetag']).''.$scan['scan_id'].'" name="delete_scan_start" onclick="return confirm(\'Are you sure to delete '.date('H:i:s',$scan['scan_timetag']).'?\')"class="btn btn-default" >
				  <span class="glyphicon glyphicon-remove " ></span>
				</button>';
			echo'</div>';
	echo'</div><br>';
	
	echo '<input type="hidden" id="load_scan_id" name="load_scan_id" value="'.$scan['scan_id'].'">';
	echo '<input type="hidden" id="operator_code" name="operator_code" value="'.$scan['scan_operatorcode'].'">';
	echo '<input type="hidden" id="timetag" name="timetag" value="'.$scan['scan_timetag'].'">';
	echo '<input type="hidden" id="timetag_finish" name="timetag_finish" value="'.$scan['scan_timetag_finish'].'">';
	//echo '<input type="hidden" id="jobnumber" name="jobnumber" value="'.$scan['scan_jobnumber'].'">';
	
	if(empty($scan['scan_timetag_finish'])){
		echo'<div class="row">';
		echo'<div class="col-sm-12">';
			echo'<i><center>Add Finished Scan</center></i>';
			echo'</div>';
		echo'</div>';
		echo'<div class="row">';
		echo'<div class="col-sm-12">';
			 echo'<center><input type="checkbox" id="finish_it" onChange="this.form.submit()" name="finish_it" value="finish_it"	class="allvenueall2 switch_1" ';
			 if(!empty($_POST['finish_it'])){echo "checked";}
			 echo '></center>';
			echo'</div>';
		echo'</div>';
		
			
		
	}
	if((!empty($_POST['finish_it'])&&empty($_POST['modify']))OR !empty($scan['scan_timetag_finish'])){
		if(empty($scan['scan_timetag_finish'])){$newfinish=$scan['scan_timetag']+3600;}else{$newfinish=$scan['scan_timetag_finish'];}
		echo '<div class="row">';
			echo '<div class="col-sm-3">';
			echo 'Time Finished';
			echo '</div>';
			echo '<div class="col-sm-6">';
			echo '<input type="time" name="timefinish" value="'.date('H:i',$newfinish).'" id="timefinish" class="form-control" id="usr" ';
			
			echo '>';
			
			echo '</div>';
			echo '<div class="col-sm-2">';
				if(!empty($scan['scan_timetag_finish'])){
					echo ' <button type="submit" value="'.date('H:i:s',$scan['scan_timetag_finish']).'" name="delete_scan_finish" onclick="return confirm(\'Are you sure to delete '.date('H:i:s',$scan['scan_timetag_finish']).'?\')"class="btn btn-default" aria-label="Left Align">
						  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
						</button>';
				}
			echo'</div>';
			
		echo'</div><br>';
	
	
	echo'<div class="row">';
		echo'<div class="col-sm-12">';
			echo'<center>Total Hours : <input disabled id="diff"></center>


				<script>
				var timestart = document.getElementById("timestart").value;
				var timefinish = document.getElementById("timefinish").value;

				document.getElementById("timestart").onchange = function() {diff(timestart,timefinish)};
				document.getElementById("timefinish").onchange = function() {diff(timestart,timefinish)};


				function diff(timestart, timefinish) {
					timestart = document.getElementById("timestart").value; //to update time value in each input bar
					timefinish = document.getElementById("timefinish").value; //to update time value in each input bar
					
					timestart = timestart.split(":");
					timefinish = timefinish.split(":");
					var startDate = new Date(0, 0, 0, timestart[0], timestart[1], 0);
					var endDate = new Date(0, 0, 0, timefinish[0], timefinish[1], 0);
					var diff = endDate.getTime() - startDate.getTime();
					var hours = Math.floor(diff / 1000 / 60 / 60);
					diff -= hours * 1000 * 60 * 60;
					var minutes = Math.floor(diff / 1000 / 60);

					return (hours < 9 ? "0" : "") + hours + ":" + (minutes < 9 ? "0" : "") + minutes;
				}

				setInterval(function(){document.getElementById("diff").value = diff(timestart, timefinish);}, 50); //to update time every second (1000 is 1 sec interval and function encasing original code you had down here is because setInterval only reads functions) You can change how fast the time updates by lowering the time interval
				</script>';
		echo'</div>';
	echo'</div><br>';
	}
	
	
	echo'<div class="row">';
		
		echo' <center><button type="submit" value='.$scan['scan_id'].' name="modify" class="btn btn-primary">Modify Scan</button></center><br>';
		//echo' <a href="manage_employee.php"  ><button type="button" class="btn btn-primary">View employee</button></a><br><br>';
		
	echo'</div><br>';
	
	
	echo'</form>';
	//show($scan);
}

function load_a_scan($db,$scanid){
	$query='SELECT * FROM dbo.scan 
	LEFT JOIN
	MIS_List
	ON
	scan_jobnumber=ManufactureIssueNumber 
	LEFT JOIN
	dbo.operator
	ON
	scan_operatorcode=operator_code 
	WHERE scan_id=\''.$scanid.'\'	
	
	
	';
	$sql = $db->prepare($query); 

	$sql->execute();

	$row=$sql->fetch();
	
	return $row;
}

function show_last_scan($db,$operator='all',$machine='all'){
	
	if($operator=='all'){
		$operator_filter='';
	}
	else{
		$operator_filter=' AND scan_operatorcode = \''.$operator.'\'';
	}
	
	if($machine=='all'){
		$machine_filter='';
	}
	else{
		$machine_filter=' AND scan_ip_last_change = \''.$machine.'\'';
	}
	
	$query='SELECT Top 10 * FROM dbo.scan 
	LEFT JOIN
	dbo.operator
	ON
	scan_operatorcode=operator_code
	
	WHERE 
	scan_timetag>1 
	'.$operator_filter.'
	'.$machine_filter.'
	
	
	Order by scan_timetag DESC
	
	';
	$sql = $db->prepare($query); 

	$sql->execute();
	//show($query);
	$allscan=$sql->fetchall();
	$olddate='';
	foreach ($allscan as &$scan){
		if($scan['scan_date']<>$olddate)
		{
			echo'<b>';
			echo date('D j F',$scan['scan_timetag']);
			echo'</b><br>';
		}
		echo '<small><i>- ';
		echo $scan['operator_fullname'];
		if($scan['scan_statut']=='start'){echo ' started';}else{echo ' finished';}
		echo ' to work on ';
		echo get_product_code($db,$scan['scan_jobnumber']).' '.$scan['scan_jobnumber'];
		echo ' at ';
		 echo date('G:i:s',$scan['scan_timetag']);
		echo'</i></small>';echo'<br>';
		$olddate=$scan['scan_date'];
	}
		
	
}

function Total_hours_today($db,$operator){
	$date=(date('Y-m-d',time()));
	$query='SELECT SUM(scan_time_distributed) FROM dbo.scan 
	LEFT JOIN
	dbo.operator
	ON
	scan_operatorcode=operator_code
	
	WHERE 
	 
	scan_operatorcode = \''.$operator.'\'
	and scan_date=\''.$date.'\'
	AND scan_statut=\'start\'
	GROUP BY scan_operatorcode
	
	
	
	';
	$sql = $db->prepare($query); 

	$sql->execute();

	$row=$sql->fetch();
	$total['hours']=floor($row[0] /3600);
	$total['minutes']=round(($row[0] %3600)/60,0);
	
	return $total;
}

function load_scan_import($db){
	$query='SELECT TOP (25) * FROM dbo.import 
	
	Order by import_timetag ASC
	
	';
	$sql = $db->prepare($query); 

	$sql->execute();

	$row=$sql->fetchall();
	
	return $row;
}

function delete_scan_import($db,$operator,$jobnumber,$timestamp){
	$query='DELETE FROM dbo.import 
	
	WHERE [import_operatorcode]=\''.$operator.'\' AND [import_jobnumber]=\''.$jobnumber.'\' AND [import_timetag]=\''.$timestamp.'\'
	
	';
	$sql = $db->prepare($query); 

	$sql->execute();

	
}

function show_list_still_open ($db,$showtoday='not') {
	if($showtoday=='not'){
		$details_query='<>';
	}
	else{
		$details_query='=';
	}
	$today=(date('Y-m-d',time()))	;
	$oneYearAgo=date('Y-m-d',strtotime($today . " - 365 day"));
	
	$query='SELECT * FROM dbo.scan 
	LEFT JOIN
	dbo.operator
	ON
	scan_operatorcode=operator_code
	
	WHERE 
	scan_statut=\'start\'
	AND scan_still_open=1
	AND scan_date'.$details_query.'\''.$today.'\'
	AND scan_date>\''.$oneYearAgo.'\'
	ORDER bY scan_timetag ASC
	';
	
	$sql = $db->prepare($query); 
	// show($query);
	$sql->execute();

	$allscan=$sql->fetchall();
	$olddate='';
	foreach ($allscan as &$scan){
		echo'<form id="form-'.$scan['scan_id'].'" method="post">';
		echo '<input type="hidden" id="load_scan_id" name="load_scan_id" value="'.$scan['scan_id'].'">';
		echo '<input type="hidden" id="operator_code" name="operator_code" value="'.$scan['scan_operatorcode'].'">';
		echo '<input type="hidden" id="timetag" name="timetag" value="'.$scan['scan_timetag'].'">';
		echo '<input type="hidden" id="timetag_finish" name="timetag_finish" value="'.$scan['scan_timetag_finish'].'">';
		echo '<input type="hidden" id="jobnumber" name="jobnumber" value="'.$scan['scan_jobnumber'].'">';
	
		if($scan['scan_date']<>$olddate)
		{
			echo'<b>';
			echo date('D j F Y',$scan['scan_timetag']);
			echo'</b><br>';
		}
		echo '<span onclick="document.forms[\'form-'.$scan['scan_id'].'\'].submit();" >';
		if(!empty($_POST['load_scan_id'])&&$_POST['load_scan_id']==$scan['scan_id']){echo'<div class="alert alert-success" role="alert">';}
		echo'<small><i>- ';
		
		echo $scan['operator_fullname'];
		if($scan['scan_statut']=='start'){echo ' started';}else{echo ' finished';}
		echo ' at ';
		 echo date('G:i:s',$scan['scan_timetag']);
		 echo ' on ';
		echo get_product_code($db,$scan['scan_jobnumber']).' - '.$scan['scan_jobnumber'];
		
		echo'</i></small>';
		if(!empty($_POST['load_scan_id'])&&$_POST['load_scan_id']==$scan['scan_id']){echo'</div>';}
		echo'</span> ';
		$olddate=$scan['scan_date'];
		
		echo'</form >';
	}
		
}

function count_open_scan($db){
	$today=(date('Y-m-d',time()))	;
	$oneYearAgo=date('Y-m-d',strtotime($today . " - 365 day"));
	
	$query='SELECT count(scan_statut)as total FROM dbo.scan 
	LEFT JOIN
	dbo.operator
	ON
	scan_operatorcode=operator_code
	
	WHERE 
	scan_statut=\'start\'
	AND scan_still_open=1
	AND scan_date<>\''.$today.'\'
	AND scan_date>\''.$oneYearAgo.'\'
	';
	
	$sql = $db->prepare($query); 
	 //show($query);
	$sql->execute();

	$row=$sql->fetch();
	return $row['total'];
}

function edit_scan_procedure($db){
	//show($_POST);
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
		
		$_POST['timetag_finish']=intval($_POST['timetag_finish']);
		$_POST['timetag']=intval($_POST['timetag']);
		
		$testdate=new datetime(date('Y-m-d',$_POST['timetag']).' '.$_POST['timestart']);
		$newtimetag_start = $testdate->getTimestamp();
		//show('1');
		modify_scan($db,$_POST['operator_code'],$_POST['timetag'],$newtimetag_start,$_POST['jobnumber']);
		//show('2');
		//show($_POST['timefinish']);
		if(!empty($_POST['timefinish'])){
			$testdate2=new datetime(date('Y-m-d',$_POST['timetag']).' '.$_POST['timefinish']);
			$newtimetag_finish = $testdate2->getTimestamp();
			//show('3');
			if (!empty($_POST['finish_it'])){
				savescan($db,$_POST['operator_code'],$_POST['jobnumber'],$newtimetag_finish);
				//Show("4");
			}
			else{
				//show('5');
				modify_scan($db,$_POST['operator_code'],$_POST['timetag_finish'],$newtimetag_finish,$_POST['jobnumber']);
			}
			
			//show('6');
			
			
		}
		$_POST=[];
	}
}

function all_filter_list(){
	$workareafilter='';
	if ($_SESSION['temp']['summary']['workarea']<>'All'){
		$workareafilter=' AND operator_workarea=\''.$_SESSION['temp']['summary']['workarea'].'\' ';
	}
	$operatorfilter='';
	if ($_SESSION['temp']['summary']['operatorname']<>'All'){
		if ($_SESSION['temp']['summary']['operatorname']=='blank'){
			$operatorfilter=" AND [operator_code] is null";
			}
			else{
				 $operatorfilter=' AND [operator_fullname]=\''.$_SESSION['temp']['summary']['operatorname'].'\' ';
			}
	}
	$daysfilter='';
	if ($_SESSION['temp']['summary']['days']<>'All'){
		$daysfilter=' AND [scan_date]=\''.$_SESSION['temp']['summary']['days'].'\' ';
	}
	$yearmonthfilter='';
	if ($_SESSION['temp']['summary']['yearmonth']<>'All'){
		$yearmonthfilter=' AND month([scan_date])=\''.$_SESSION['temp']['summary']['month'].'\' 
		AND year([scan_date])=\''.$_SESSION['temp']['summary']['year'].'\'';
	}
	$filter['workareafilter']=$workareafilter;
	$filter['operatorfilter']=$operatorfilter;
	$filter['daysfilter']=$daysfilter;
	$filter['yearmonthfilter']=$yearmonthfilter;
	
	return $filter;
}

function summary_listoperator($db,$workareafilter='',$yearmonthfilter='',$daysfilter=''){
	
	
	$query='SELECT operator_fullname,operator_code,sum([scan_time_distributed])';
	
	if ($_SESSION['temp']['summary']['mode']=='Average'){$query=$query.'/count(distinct concat([scan_date],[scan_operatorcode]))';}
	
	$query=$query.'as total FROM dbo.scan 
	LEFT JOIN
	operator
	ON
	scan_operatorcode=operator_code
	
	WHERE 
	scan_statut=\'start\'
	
	'.$workareafilter.'
	'.$yearmonthfilter.'
	'.$daysfilter.'	
	GROUP BY operator_fullname,operator_code
	ORDER BY operator_fullname ASC
	';
	
	$sql = $db->prepare($query); 
		//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	
	return $row;
		
}

function summary_listdays($db,$workareafilter='',$operatorfilter='',$yearmonthfilter=''){
	
	
	$query='SELECT Top 31 [scan_date],sum([scan_time_distributed])';
	
	if ($_SESSION['temp']['summary']['mode']=='Average'){$query=$query.'/count(distinct concat([scan_date],[scan_operatorcode]))';}
	
	$query=$query.'as total FROM dbo.scan 
	LEFT JOIN
	operator
	ON
	scan_operatorcode=operator_code
	
	WHERE 
	scan_statut=\'start\'
	
	'.$workareafilter.'
	'.$yearmonthfilter.'
    '.$operatorfilter.'	
	GROUP BY [scan_date]
	ORDER BY [scan_date] DESC
	';
	
	$sql = $db->prepare($query); 
		//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	
	return $row;
		
}

function summary_listmonth($db,$workareafilter='',$operatorfilter='',$daysfilter=''){
	
	
	
	
	$query='SELECT  month([scan_date])as themonth, year([scan_date])as theyear,sum([scan_time_distributed])';
	
	if ($_SESSION['temp']['summary']['mode']=='Average'){$query=$query.'/count(distinct concat([scan_date],[scan_operatorcode]))';}
	
	$query=$query.'as total FROM dbo.scan 
	LEFT JOIN
	operator
	ON
	scan_operatorcode=operator_code
	
	WHERE 
	scan_statut=\'start\'
	
	'.$workareafilter.'
	'.$operatorfilter.'
	'.$daysfilter.'
	GROUP BY month([scan_date]), year([scan_date])
	ORDER BY year([scan_date]) DESC, month([scan_date]) DESC
	';
	
	$sql = $db->prepare($query); 
		//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	
	return $row;
		
}

function summary_listscan($db,$workareafilter='',$operatorfilter='',$yearmonthfilter='',$daysfilter=''){
	
	
	$query='SELECT TOP 200 * FROM dbo.scan 
	LEFT JOIN
	operator
	ON
	scan_operatorcode=operator_code
	
	WHERE 
	scan_statut=\'start\'
	
	'.$workareafilter.'
	'.$operatorfilter.'
	'.$yearmonthfilter.'
	'.$daysfilter.'
	
	ORDER BY [scan_timetag] ASC 
	';
	
	$sql = $db->prepare($query); 
		 //show($query);
	$sql->execute();

	$row=$sql->fetchall();
	
	return $row;
}

function load_summary_listmonth($db){
	$filter=all_filter_list();
	$listmonth=summary_listmonth($db,$filter['workareafilter'],$filter['operatorfilter'],$filter['daysfilter']);
		//show($listmonth);
		if(!empty($_SESSION['temp']['summary']['filter_month'])){$_SESSION['temp']['summary']['filter_month']=='All';}
			echo'<form  id="form-monthall" method="post">';
			echo'<div class="row rowsummary';
				if(!empty($_SESSION['temp']['summary']['yearmonth'])&&$_SESSION['temp']['summary']['yearmonth']=='All'){echo' bg-primary text-white';}
				echo '" onclick="document.forms[\'form-monthall\'].submit();">';
				
				echo '<input type="hidden" id="month" name="month" value="All">';
				echo '<input type="hidden" id="year" name="year" value="">';
				
				echo'<div class="col-sm-6 linesummary">';
				
				echo"All";
				echo '</div>';
				echo'<div class="col-sm-4 linesummary">';
					
				echo '</div>';
				
				
			echo '</div>';
			echo'</form>';
		
		foreach ($listmonth as &$months){
			echo'<div class="row rowsummary';
				if(!empty($_SESSION['temp']['summary']['yearmonth'])&&$_SESSION['temp']['summary']['yearmonth']==$months['theyear'].$months['themonth']){echo' bg-primary text-white ';}
				
				echo'" onclick="document.forms[\'form-'.$months['theyear'].$months['themonth'].'\'].submit();" >';
				
			
				
				echo'<form  id="form-'.$months['theyear'].$months['themonth'].'" method="post">';
				echo '<input type="hidden" id="month" name="month" value="'.$months['themonth'].'">';
				echo '<input type="hidden" id="year" name="year" value="'.$months['theyear'].'">';
				
				echo'<div class="col-sm-6 linesummary ">';
				
				// if(!empty($_SESSION['temp']['summary']['filter_month'])&&$_SESSION['temp']['summary']['filter_month']==$months['operator_fullname']){echo 'selected';}
					echo"".date('M',strtotime($months['theyear']."-".$months['themonth']."-01"))." ".$months['theyear']."";
				echo '</div>';
				echo'<div class="col-sm-4 linesummary">';
					if($months['total']>0){echo showhours($months['total']).' h';}else{echo'-';}
				echo '</div>';
				
				
				echo'</form>';
			echo '</div>';
			}
		
}

function load_summary_listdays($db){
	$filter=all_filter_list();
	$listdays=summary_listdays($db,$filter['workareafilter'],$filter['operatorfilter'],$filter['yearmonthfilter']);
		//show($listdays);
		if(!empty($_SESSION['temp']['summary']['filter_month'])){$_SESSION['temp']['summary']['filter_month']=='All';}
			echo'<form  id="form-daysall" method="post">';
			echo'<div class="row rowsummary';
				if(!empty($_SESSION['temp']['summary']['days'])&&$_SESSION['temp']['summary']['days']=='All'){echo' bg-primary text-white';}
			echo '" onclick="document.forms[\'form-daysall\'].submit();" >';
				
				echo '<input type="hidden" id="days" name="days" value="All">';
				
				echo'<div class="col-sm-6 linesummary"><center>';
				
				echo"All";
				echo '</center></div>';
				echo'<div class="col-sm-4 linesummary"><center>';
					
				echo '</div>';
				
				
			echo '</div>';echo'</form>';
		
		foreach ($listdays as &$days){
			echo'<form  id="form-'.$days['scan_date'].'" method="post">';
			echo'<div class="row rowsummary';
				if(!empty($_SESSION['temp']['summary']['days'])&&$_SESSION['temp']['summary']['days']==$days['scan_date']){echo' bg-primary text-white';}
			echo '" onclick="document.forms[\'form-'.$days['scan_date'].'\'].submit();" >';
			
				
				
				echo '<input type="hidden" id="days" name="days" value="'.$days['scan_date'].'">';
				echo'<div class="col-sm-6 linesummary">';
					//echo date('d-m',strtotime($scan['scan_date']));
					echo date('d M',strtotime($days['scan_date']));
				echo '</div>';
				echo'<div class="col-sm-4 linesummary">';
					if($days['total']>0){echo showhours($days['total']).' h';}else{echo'-';}
				echo '</div>';
				
				
				
			echo '</div>';echo'</form>';
			}
		
}

function load_summary_listoperator($db){
	$filter=all_filter_list();
	$listOperator=summary_listoperator($db,$filter['workareafilter'],$filter['yearmonthfilter'],$filter['daysfilter']);
	
	
		if(!empty($_SESSION['temp']['summary']['operatorname'])){$_SESSION['temp']['summary']['operatorname']=='All';}
			echo'<form  id="form-operatorall" method="post">';
			echo'<div class="row rowsummary';
				if(!empty($_SESSION['temp']['summary']['operatorname'])&&$_SESSION['temp']['summary']['operatorname']=='All'){echo' bg-primary text-white';}
			echo '"  onclick="document.forms[\'form-operatorall\'].submit();" >';
				
				echo '<input type="hidden" id="operatorcode" name="operatorcode" value="All">';
				echo '<input type="hidden" id="operatorname" name="operatorname" value="All">';
				
				
				echo'<div class="col-sm-6 linesummary">';
				
				echo"All";
				echo '</div>';
				echo'<div class="col-sm-6 linesummary">';
					
				echo '</div>';
				
				
			echo '</div>';echo'</form>';
		
		foreach ($listOperator as &$Operator){
			if($Operator['operator_fullname']==''){$Operator['operator_fullname']='blank';$Operator['operator_code']='blank';}
			echo'<form  id="form-'.$Operator['operator_code'].'" method="post">';
			echo'<div class="row rowsummary';
				if(!empty($_SESSION['temp']['summary']['operatorname'])&&$_SESSION['temp']['summary']['operatorname']==$Operator['operator_fullname']){echo' bg-primary text-white';}
			echo '" onclick="document.forms[\'form-'.$Operator['operator_code'].'\'].submit();" >';
			
				
				
				echo '<input type="hidden" id="operatorcode" name="operatorcode" value="'.$Operator['operator_code'].'">';
				echo '<input type="hidden" id="operatorname" name="operatorname" value="'.$Operator['operator_fullname'].'">';
				
				echo'<div class="col-sm-6 linesummary">';
					echo $Operator['operator_fullname'];
				echo '</div>';
				echo'<div class="col-sm-6 linesummary">';
					if($Operator['total']>0){echo showhours($Operator['total']).' h';}else{echo'-';}
				echo '</div>';
				
				
				
			echo '</div>';echo'</form>';
			}
	
}

function show_table_scan_day($db,$allscan,$nbr_filter,$daysfilter,$operatorfilter){
	echo'<div class="row " >';
		foreach ($allscan as &$scan){
			echo'<form  id="form-'.$scan['scan_id'].'" method="post">';
			echo '<input type="hidden" id="load_scan_id" name="load_scan_id" value="'.$scan['scan_id'].'">';
			echo '<input type="hidden" id="operator_code" name="operator_code" value="'.$scan['scan_operatorcode'].'">';
			echo '<input type="hidden" id="timetag" name="timetag" value="'.$scan['scan_timetag'].'">';
			echo '<input type="hidden" id="timetag_finish" name="timetag_finish" value="'.$scan['scan_timetag_finish'].'">';
			echo '<input type="hidden" id="jobnumber" name="jobnumber" value="'.$scan['scan_jobnumber'].'">';
			
			
			echo'<div class="row rowsummary linesummary ';
			if($nbr_filter<>2){echo 'linesummary-small';} 
			if(!empty($_POST['load_scan_id'])&&($_POST['load_scan_id'])==$scan['scan_id']){echo' bg-primary text-white';}
			echo ' " " onclick="document.forms[\'form-'.$scan['scan_id'].'\'].submit();" >';
				if ($_SESSION['temp']['summary']['days']=='All'){
					echo'<div class="col-sm-2">';
					echo date('d M',$scan['scan_timetag']);
					echo'</div>';
				}
				echo'<div class="col-sm-4">';
				echo date('G:i:s',$scan['scan_timetag']);
				if($scan['scan_timetag_finish']<>''){echo ' - '.date('G:i:s',$scan['scan_timetag_finish']);}else{echo' - xx:xx:xx';}
				echo'</div>';
				// echo'<div class="col-sm-2">';
				// if($scan['scan_timetag_finish']<>''){echo date('G:i:s',$scan['scan_timetag_finish']);}
				// echo'</div>';
		//if you click on an operator we dont show that but the name of the operator is in the title
				if ($_SESSION['temp']['summary']['operatorname']=='All'){
					echo'<div class="col-sm-2">';
					if($scan['operator_fullname']<>''){
						echo $scan['operator_fullname'];
					}else{
						echo $scan['scan_operatorcode'].' ?';
					}
					echo'</div>';
				}elseif($_SESSION['temp']['summary']['operatorname']=='blank'){
					echo'<div class="col-sm-2">';
					echo $scan['scan_operatorcode'];
					echo' ?</div>';
				}
				echo'<div class="col-sm-'.(2+$nbr_filter*2).'">';
				echo get_product_code($db,$scan['scan_jobnumber']).' - ('.$scan['scan_jobnumber'].')</i>';
				echo'</div>';
				echo'<div class="col-sm-2">';
				if($scan['scan_still_open']==0){echo showhours($scan['scan_time_distributed'],1).' h';} else { echo"-";}
				echo'</div>';
				// echo'<div class="col-sm-3">';
				// echo $scan['scan_jobnumber'];
				// echo'</div>';
				
			echo'</div>';
			echo'</form>';
			}
	echo'</div>';
}

function separator(){
	echo '<div class="row" style="background:grey; border-radius: 10px; margin-top:5px;margin-bottom:5px;padding:5px"></div>';
}

function load_scan_day2($db,$operator='All',$date='All'){
        
	$nbr_filter=0;
	$daysfilter='';
	if($date=='All'){
		if ($_SESSION['temp']['summary']['days']<>'All'){
			$daysfilter=''.date('d M',strtotime($_SESSION['temp']['summary']['days']));
			$nbr_filter=$nbr_filter+1;
			$date=$_SESSION['temp']['summary']['days'];
		}
	}else{
		$daysfilter=''.date('d M',strtotime($date));
		$nbr_filter=$nbr_filter+1;
		
	}
	$operatorfilter='';
	if ($_SESSION['temp']['summary']['operatorname']<>'All'){
		if ($_SESSION['temp']['summary']['operatorname']=='blank'){
			$operatorfilter="- [blank]";
			}
			else{
				$operatorfilter='- '.$_SESSION['temp']['summary']['operatorname'];
				$operator=$_SESSION['temp']['summary']['operatorcode'];
			}
		$nbr_filter=$nbr_filter+1;
	}
	
	

	
	echo'<form id="form-view" method="post">';
	echo'<div class="row" >';
	echo'<Center><h4 >';
	
	
	echo''.$daysfilter.' '.$operatorfilter.' ';
	
	if (($_SESSION['temp']['summary']['operatorname']<>'All')and($_SESSION['temp']['summary']['days']<>'All')){button_log($db,$_SESSION['temp']['summary']['operatorcode'],$date);}
	echo'</h4></Center>';
	
	echo '<input type="hidden" id="view" name="view" value="';
		if($_SESSION['temp']['summary']['view']=='Normal'){echo'Block';}else{echo'Normal';}
		echo'">';
	echo'</div>';
	echo'</form>';
	
	
	
		
	  //  show_table_scan_day($db,$allscan,$nbr_filter,$daysfilter,$operatorfilter);
	
		show_all_scan_operator($db,$operator,$date);
	   
}

function load_scan_day($db,$operator='All',$date='All'){
	
	$nbr_filter=0;
	$daysfilter='';
	if ($_SESSION['temp']['summary']['days']<>'All'){
		$daysfilter='- '.date('d M',strtotime($_SESSION['temp']['summary']['days']));
		$nbr_filter=$nbr_filter+1;
		$date=$_SESSION['temp']['summary']['days'];
	}
	$operatorfilter='';
	if ($_SESSION['temp']['summary']['operatorname']<>'All'){
		if ($_SESSION['temp']['summary']['operatorname']=='blank'){
			$operatorfilter="- [blank]";
			}
			else{
				 $operatorfilter='- '.$_SESSION['temp']['summary']['operatorname'];
				 $operator=$_SESSION['temp']['summary']['operatorcode'];
			}
		$nbr_filter=$nbr_filter+1;
	}
	
	$filter=all_filter_list();
	$allscan=summary_listscan($db,$filter['workareafilter'],$filter['operatorfilter'],$filter['yearmonthfilter'],$filter['daysfilter']);
		
	
	 
	
	echo'<form id="form-view" method="post">';
	echo'<div class="row" >';
	echo'<Center><h4 onclick="document.forms[\'form-view\'].submit();">';
	if($_SESSION['temp']['summary']['view']=='Normal'){echo'List View ';}else{echo'Block View';}
	
	echo''.$daysfilter.' '.$operatorfilter.' ';
	
	if (($_SESSION['temp']['summary']['operatorname']<>'All')and($_SESSION['temp']['summary']['days']<>'All')){button_log($db,$_SESSION['temp']['summary']['operatorcode'],$_SESSION['temp']['summary']['days']);}
	echo'</h4></Center>';
	
	echo '<input type="hidden" id="view" name="view" value="';
		if($_SESSION['temp']['summary']['view']=='Normal'){echo'Block';}else{echo'Normal';}
		echo'">';
	echo'</div>';
	echo'</form>';
	
	
	if(empty($_SESSION['temp']['summary']['view'])or $_SESSION['temp']['summary']['view']=='Normal'){
		
		show_table_scan_day($db,$allscan,$nbr_filter,$daysfilter,$operatorfilter);
		}
	else{
		show_all_scan_operator($db,$operator,$date);
		}
}

function load_summary_workareafilter($db){
	echo'<form   method="post">';
	echo '<select name="workarea" onChange="this.form.submit()" class="form-control" id="sel1">';
		
		$listWorkarea=listworkarea($db);
					echo"<option>All</option>";
		foreach ($listWorkarea as &$workarea){
				echo"<option ";
				if(!empty($_SESSION['temp']['summary']['workarea'])&&$_SESSION['temp']['summary']['workarea']==$workarea['workarea']){echo 'selected';}
				echo">".$workarea['workarea']."</option>";
				}
		echo '</select>';
		echo'</form>';
}

function showhours($hours,$resolution=100){
	
	if($resolution==100){
		if($hours/3600>100){
			$resolution=0;
		}
		else
		{
			$resolution=1;
		}
	}else{
		
			$resolution=$resolution;
		
	}
	
	
	return (round($hours/3600,$resolution));
	
}

function sort_list(){
	if (!empty($_GET['sort'])){
		if($_GET['sort']=='MIS'){
			if($_SESSION['temp']['sort']=='scan_jobnumber ASC'){
				$_SESSION['temp']['sort']='scan_jobnumber DESC';
			}
			else{
				$_SESSION['temp']['sort']='scan_jobnumber ASC';
			}
		}
		if($_GET['sort']=='Product'){
			if($_SESSION['temp']['sort']=='scan_jobnumber ASC'){
				$_SESSION['temp']['sort']='scan_jobnumber DESC';
			}
			else{
				$_SESSION['temp']['sort']='scan_jobnumber ASC';
			}
		}
		if($_GET['sort']=='Operator'){
			if($_SESSION['temp']['sort']=='operator_fullname ASC'){
				$_SESSION['temp']['sort']='operator_fullname DESC';
			}
			else{
				$_SESSION['temp']['sort']='operator_fullname ASC';
			}
		}
		if($_GET['sort']=='Start'){
			if($_SESSION['temp']['sort']=='scan_timetag ASC'){
				$_SESSION['temp']['sort']='scan_timetag DESC';
			}
			else{
				$_SESSION['temp']['sort']='scan_timetag ASC';
			}
		}
		if($_GET['sort']=='Finish'){
			if($_SESSION['temp']['sort']=='scan_timetag_finish ASC'){
				$_SESSION['temp']['sort']='scan_timetag_finish DESC';
			}
			else{
				$_SESSION['temp']['sort']='scan_timetag_finish ASC';
			}
		}
		if($_GET['sort']=='Hours'){
			if($_SESSION['temp']['sort']=='scan_time_distributed ASC'){
				$_SESSION['temp']['sort']='scan_time_distributed DESC';
			}
			else{
				$_SESSION['temp']['sort']='scan_time_distributed ASC';
			}
		}
		if($_GET['sort']=='Ratio'){
			if($_SESSION['temp']['sort']=='scan_ratio ASC'){
				$_SESSION['temp']['sort']='scan_ratio DESC';
			}
			else{
				$_SESSION['temp']['sort']='scan_ratio ASC';
			}
		}
		
		
	}

}

function button_add_scan(){
	echo'<form method="post">';
	
	echo '<center><button type="submit" name="filter" value="ok" class="btn btn-primary">Add Scan</button></center>';
	echo'</form>';
}

function loading_time($start_time){
	$time_load=time()-$start_time;
	$message='this page loaded in '.$time_load.' secondes';
	
	show($message);
}

function count_scan($db,$jobnumber){
	$query='SELECT min(scan_date) as min_date, max(scan_date) as max_date, count(scan_statut)as total_count, round(sum([scan_time_distributed])/3600,1) as total_hours FROM dbo.scan 
	LEFT JOIN
	dbo.operator
	ON
	scan_operatorcode=operator_code
	
	WHERE 
	scan_jobnumber=\''.$jobnumber.'\'
	
	';
	
	$sql = $db->prepare($query); 
	 //show($query);
	$sql->execute();

	$row=$sql->fetch();
	return $row;
}

function search_job_details($db){
	echo '<script>function addHash(elem) {
  var val = elem.value;
  if(!val.match(/^MIS/)) {
    elem.value = "MIS" + val;
  }
 }</script>';
	
	echo '<form id="form-countscan" method="post">';
	echo '<div class="row"  >';
		echo '<div class="col-sm-12 ">';
		echo' <h3>Find Details on MIS</h3>';
		echo '</div>';
	echo '</div>';
	echo '<div class="row"  >';
		echo '<div class="col-sm-6 ">';
		echo' <input type="text" class="form-control" id="job_check" name="job_check" value="MIS" placeholder="MIS Number" onkeyup="addHash(this)">';
		echo '</div>';
		echo '<div class="col-sm-6">';
		echo' <input type="submit" class="form-control"  value="Search" >';
		echo '</div>';
		echo '<div class="col-sm-12 ">';
		
		if(!empty($_POST['job_check'])){
			
		$jobnumber=$_POST['job_check'];
		$result=count_scan($db,$jobnumber);
		echo ' <br><b> '.$jobnumber.'</b>';
		echo ' <br> Product : '.get_product_code($db,$jobnumber).'';
		echo '<br> # of  Scans : '.$result['total_count'];
		echo '<br> Time Scanned : '.round(($result['total_hours']),1);
		echo '<br> Date Started : '.$result['min_date'];
		echo '<br> Date Finished : '.$result['max_date'];
		
		//echo  $jobnumber.' has '.$result['total_count'].' scans and '.round(($result['total_hours']),1).' hours from the '.$result['min_date'].' to the '.$result['max_date'];
		}
		echo '</div>';
	echo '</div>';
	echo '</form>';
	
}

function transfer_job($db){
	echo '<form id="form-transfer" method="post">';
	echo '<div class="row"  >';
		echo '<div class="col-sm-12 ">';
		echo' <h3>Transfer all Scan MIS -> MIS</h3>';
		echo '</div>';
	echo '</div>';
	if(!empty($_POST['cancel'])){$_POST=array();}
	if(($_POST['job_1st']=='MIS')){$_POST=array();}
	if(($_POST['job_2nd']=='MIS')){$_POST=array();}
	
	if(!empty($_POST['check'])){
		echo '<div class="row"  >';
		echo '<div class="col-sm-12 ">';
		echo'Transfer all scan FROM '.$_POST['job_1st'];
		echo' <input type="hidden" class="form-control" id="job_1st" name="job_1st" value="'.$_POST['job_1st'].'" placeholder="MIS Number">';
		
		echo'<br> TO '.$_POST['job_2nd'];
		echo' <input type="hidden" class="form-control" id="job_2nd" name="job_2nd" value="'.$_POST['job_2nd'].'" placeholder="MIS Number">';
		echo '</div>';
		echo '<div class="col-sm-12">';
		echo'<br>';
		echo' <input type="submit" name="cancel" class="form-control"  value="Cancel" >';
		echo '</div>';
		
		echo '<div class="col-sm-5">';
			$jobnumber=$_POST['job_1st'];
			$result=count_scan($db,$jobnumber);
			echo ' <br><b> '.$jobnumber.'</b>';
			echo '<br> # of  Scans : '.$result['total_count'];
			echo '<br> Time Scanned : '.round(($result['total_hours']),1);
			echo '<br> Date Started : '.$result['min_date'];
			echo '<br> Date Finished : '.$result['max_date'];
		echo '</div>';
		echo '<div class="col-sm-2">';
			echo'<br><br><br><large><span class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true"></large></span>';
		echo '</div>';
		echo '<div class="col-sm-5">';
			$jobnumber=$_POST['job_2nd'];
			$result=count_scan($db,$jobnumber);
			echo ' <br><b> '.$jobnumber.'</b>';
			echo '<br> # of  Scans : '.$result['total_count'];
			echo '<br> Time Scanned : '.round(($result['total_hours']),1);
			echo '<br> Date Started : '.$result['min_date'];
			echo '<br> Date Finished : '.$result['max_date'];
		echo '</div>';
		//echo  $jobnumber.' has '.$result['total_count'].' scans and '.round(($result['total_hours']),1).' hours from the '.$result['min_date'].' to the '.$result['max_date'];
		echo '<div class="col-sm-12">';
			echo' <input type="submit" name="transfer" class="form-control"  value="Transfer" onclick="return confirm(\'Are you sure to transfer this scan?\')">';
		echo '</div>';
		
	echo '</div>';
		
		
		
		
	}elseif (!empty($_POST['transfer'])){
		echo(transfer_scan ($db,$_POST['job_1st'],$_POST['job_2nd']));
		
	}else{
		
	
	echo '<script>function addHash(elem) {
  var val = elem.value;
  if(!val.match(/^MIS/)) {
    elem.value = "MIS" + val;
  }
 }</script>';
	echo '<div class="row"  >';
		echo '<div class="col-sm-6 ">';
		echo'MIS Number FROM';
		echo' <input type="text" class="form-control" id="job_1st" name="job_1st" value="MIS" placeholder="MIS Number" onkeyup="addHash(this)">';
		echo' <input type="hidden" class="form-control" id="check" name="check" value="ok" placeholder="MIS Number" >';
		echo'MIS Number TO ';
		echo' <input type="text" class="form-control" id="job_2nd" name="job_2nd" value="MIS" placeholder="MIS Number" onkeyup="addHash(this)">';
		echo '</div>';
		echo '<div class="col-sm-6">';
		echo'<br>';
		echo' <input type="submit" class="form-control"  value="Transfer" >';
		echo '</div>';
		
	echo '</div>';
	echo '</form>';
	}
}

function add_scan_procedure($db){
	echo'<form  method="post">
   <div class="row">
	  <div class="col-sm-2">';
	  
	  echo '<h3>Operator List</h3>';
	  echo '<h5>WorkArea</h5>';
	  echo '<select name="addscan_workarea" onChange="location = this.options[this.selectedIndex].value;" class="form-control" id="sel1">';
		$listWorkarea=listworkarea($db);
					echo"<option value='addscan.php?workarea=All' ";
					if(!empty($_SESSION['temp']['filter']['filter_workarea'])&&$_SESSION['temp']['filter']['filter_workarea']=='All'){echo 'selected';}
					echo">All</option>";
		foreach ($listWorkarea as &$workarea){
				echo"<option ";
				if(!empty($_SESSION['temp']['filter']['filter_workarea'])&&$_SESSION['temp']['filter']['filter_workarea']==$workarea['workarea']){echo 'selected';}
				echo" value='addscan.php?workarea=".$workarea['workarea']."' >".$workarea['workarea']."</option>";
				}
		echo '</select>';
		//show($_SESSION['temp']['addscan']);
	  echo '<h5>Operator</h5>';
	  echo '<select name="addscan_operator"  class="form-control" id="sel1">';
		$listOperator=listoperator($db,$_SESSION['temp']['filter']['filter_workarea']);
					
		foreach ($listOperator as &$Operator){
				echo"<option value='".$Operator['operator_code']."'";
				if(!empty($_SESSION['temp']['filter']['filter_operator'])&&$_SESSION['temp']['filter']['filter_operator']==$Operator['operator_fullname']){echo 'selected';}
				echo">".$Operator['operator_fullname']."</option>";
				}
		echo '</select>';
	  
	  
	echo'  </div>
	  <div class="col-sm-1">
	  </div>
	  <div class="col-sm-4">
	  
	  <script>
	     function showMe (box) {
        
        var chboxs = document.getElementsByName("jobentry");
        var vis = "none";
        for(var i=0;i<chboxs.length;i++) { 
            if(chboxs[i].checked){
            
            }
			else
			{
			 vis = "block";
                break;	
			}
        }
        document.getElementById(box).style.display = vis;
	
    }
	function dontshowMe (box) {
        
        var chboxs = document.getElementsByName("jobentry");
        var vis = "none";
        for(var i=0;i<chboxs.length;i++) { 
            if(chboxs[i].checked){
            vis = "block";
                break;	
            }
			
        }
        document.getElementById(box).style.display = vis;
	}
	
	function addHash(elem) {
			  var val = elem.value;
			  if(!val.match(/^MIS/)) {
				elem.value = "MIS" + val;
			  }
			}
	  </script>';
	
	  echo '<h3>Manufacture Issue Number</h3>';
	  
	  echo'<label for="jobentry" >Liste &nbsp;&nbsp;&nbsp;</label><input type="checkbox" name="jobentry" id="jobentry" onclick="showMe(\'joblist\'); dontshowMe(\'jobmanualshow\') "  class=" switch_1" ><label for="jobentry" >&nbsp;&nbsp;&nbsp;Manual </label>';
	  echo'<br>';
	   echo '<select  name="joblist" class="form-control" id="joblist">';
		
			echo"<option value='MIS35456' selected >MIS35456</option>";
			echo"<option value='MIS66666'  >MIS66666</option>";
			echo"<option value='MIS55555'  >MIS55555</option>";
			echo"<option value='MIS33333'  >MIS33333</option>";
			echo"<option value='MIS22222'  >MIS22222</option>";
				
		echo '</select>';
	  //echo'<br>';
	  
	  echo '<div style="display:none" id="jobmanualshow">';
	   echo'<input type="textbox" placeholder="MIS12345" name="jobmanual" class="form-control" id="jobmanual"  onkeyup="addHash(this)" >';
	  echo '</div>';
	  
	  
	 
	 echo ' </div>';
	 echo' <div class="col-sm-3">';
	
	  if(!empty($_SESSION['temp']['addscan']['datetimepicker1'])){
		  
		  $datevalue = $_SESSION['temp']['addscan']['datetimepicker1'];
		  } 
	  elseif(!empty($_SESSION['temp']['filter']['datetime'])){
		  $_SESSION['temp']['addscan']['datetime'] = $_SESSION['temp']['filter']['datetime']  ;
		  
		  $datevalue = $_SESSION['temp']['filter']['year'] . '-' . $_SESSION['temp']['filter']['month'] . '-' . $_SESSION['temp']['filter']['day'];
	  }
	  else	{
		  	$_SESSION['temp']['addscan']['month'] = date('m');
			$_SESSION['temp']['addscan']['day']= date('d');
			$_SESSION['temp']['addscan']['year'] = date('Y');
			$datevalue = $_SESSION['temp']['addscan']['year'] . '-' . $_SESSION['temp']['addscan']['month'] . '-' . $_SESSION['temp']['addscan']['day'];
			$_SESSION['temp']['addscan']['datetimepicker1']=$_SESSION['temp']['addscan']['year'] . '-' . $_SESSION['temp']['addscan']['month'] . '-' . $_SESSION['temp']['addscan']['day'];
		   }
		   
		   
		if(empty($_SESSION['temp']['addscan']['timepicker'])){
		   	$time = date('G:i');
		  
		  } 
	  else {
			
			$time = $_SESSION['temp']['addscan']['timepicker'] ;
		   }
	  
	  //show($time);
	  echo '<h3>Time Scanned</h3>';
	  echo'Date:<br><input type="date" name="datetimepicker1" value="'.$datevalue.'" id="datetimepicker1" class="form-control" id="usr">';
	  echo'Time:<br><input type="time" name="timepicker" value="'.$time.'" id="timepicker" class="form-control" id="usr">';
	  
	  echo'</div>';
	  echo'<div class="col-sm-2">';
	  
	  echo '<h3> Add </h3>';
	  
	  echo'<input type="submit" value="Add This Scan"  class="form-control" ></input>';
	   echo'</div>';
   echo'</div>';
  
  
  
  echo'</form>';
}

function add_scan_window($db,$date){
	echo'<form  method="post">';
		echo'<input type="hidden" id="add_a_scan" name="add_a_scan" value="ok">';	
	echo'<br><br>';
	echo'<div class="row">';
		echo'<div class="col-sm-12">';
		echo'<h3><center>Add Scan - '.$date.'</center></h3>';
		echo'</div>';
	echo'</div>';
	echo'<br><br>';
	
	if((empty($_POST['jobmanual'])and !empty($_POST['jobentry']))){$_POST['operatordone']='ok';}
	
	if((!empty($_POST['add_a_scan_init']))and empty($_POST['addscan_operator'])or!empty($_POST['back_init'])){
		
			echo'<input type="hidden" id="add_a_scan_init" name="add_a_scan_init" value="ok">';
		
		echo'<div class="row">';
			echo'<div class="col-sm-12">';
			if(empty($_SESSION['temp']['summary']['operatorname'])){
				
				
				echo '<h5>WorkArea</h5>';
				if(empty($_POST['addscan_workarea'])){$_POST['addscan_workarea']='All';}
				echo '<select name="addscan_workarea" onchange="this.form.submit()" class="form-control" id="sel1">';
				$listWorkarea=listworkarea($db);
							echo"<option value='All' ";
							if(!empty($_POST['addscan_workarea'])&&$_POST['addscan_workarea']=='All'){echo 'selected';}
							echo">All</option>";
				foreach ($listWorkarea as &$workarea){
						echo"<option ";
						if(!empty($_POST['addscan_workarea'])&&$_POST['addscan_workarea']==$workarea['workarea']){echo 'selected';}
						echo" value='".$workarea['workarea']."' >".$workarea['workarea']."</option>";
						}
				echo '</select>';
			}else{$_POST['addscan_workarea']='All';}
		  echo '<h5>Operator</h5>';
		  echo '<select name="addscan_operator[]"  class="form-control" id="sel1" multiple size="10">';
			$listOperator=listoperator($db,$_POST['addscan_workarea']);
						
			foreach ($listOperator as &$Operator){
					echo"<option value='".$Operator['operator_code']."'";
					if(!empty($_SESSION['temp']['filter']['filter_operator'])&&$_SESSION['temp']['filter']['filter_operator']==$Operator['operator_fullname']){echo 'selected';}
					if (($_SESSION['temp']['summary']['operatorname']==$Operator['operator_fullname'])){echo 'selected';}
					echo">".$Operator['operator_fullname']."</option>";
					}
			echo '</select>';
			echo'</div>';
		echo'</div><br>';
		echo'<div class="row">';
		
		echo' <center><button type="submit" value="ok" name="operatordone" class="btn btn-primary">Next</button></center><br>';
			//echo' <a href="manage_employee.php"  ><button type="button" class="btn btn-primary">View employee</button></a><br><br>';
			
		echo'</div><br>';
		
	}
	elseif((!empty($_POST['operatordone'])) ){
		echo'  	  
		  <script>
			 function showMe (box) {
			
			var chboxs = document.getElementsByName("jobentry");
			var vis = "none";
			for(var i=0;i<chboxs.length;i++) { 
				if(chboxs[i].checked){
				
				}
				else
				{
				 vis = "block";
					break;	
				}
			}
			document.getElementById(box).style.display = vis;
		
		}
		function dontshowMe (box) {
			
			var chboxs = document.getElementsByName("jobentry");
			var vis = "none";
			for(var i=0;i<chboxs.length;i++) { 
				if(chboxs[i].checked){
				vis = "block";
					break;	
				}
				
			}
			document.getElementById(box).style.display = vis;
		}
		
		function addHash(elem) {
				  var val = elem.value;
				  if(!val.match(/^MIS/)) {
					elem.value = "MIS" + val;
				  }
				}
		  </script>';
		echo'<div class="row">';
		echo '<h5>Operator :</h5>';
		foreach($_POST['addscan_operator'] as &$operatorcode){
			
			echo '<i>'.get_operator_name($db,$operatorcode).' </i><br>';
			echo'<input type="hidden"  name="addscan_operator[]" value="'.$operatorcode.'">';
			
		}
		echo '</div>';
		echo'<div class="row">';
		  echo '<h5>Manufacture Issue Number :</h5>';
		echo '</div>';  
		echo'<div class="row">';
		  echo'<label for="jobentry" >Liste &nbsp;&nbsp;&nbsp;</label><input type="checkbox" name="jobentry" id="jobentry" onclick="showMe(\'joblist\'); dontshowMe(\'jobmanualshow\') "  class=" switch_1" ><label for="jobentry" >&nbsp;&nbsp;&nbsp;Manual </label>';
		  echo'<br>';
		   echo '<select  name="joblist" class="form-control" id="joblist">';
			
				
				load_list_MIS_open($db);
				
					
			echo '</select>';
		  //echo'<br>';
		  
		  echo '<div style="display:none" id="jobmanualshow">';
		   echo'<input type="textbox" placeholder="MIS12345" name="jobmanual" class="form-control" id="jobmanual"  onkeyup="addHash(this)" >';
		  echo '</div>';
		echo '</div><br>'; 		
		echo'<div class="row" >';
		
			echo'<div class="col-sm-6">';
			//echo'<center><button type="submit" value="ok" name="MISdone" class="btn btn-primary defaultsink" style="display:none;">Next</button></center>';
			echo'<center><button type="submit" value="ok" name="back_init" class="btn btn-primary">Previous</button></center>';
			echo'</div>';
			echo'<div class="col-sm-6">';
			echo'<center><button type="submit" value="ok" name="MISdone" class="btn btn-primary " >Next</button></center>';
			echo'</div>';
			
		echo'</div><br>';		
				
				
				
				
				
				
				
			
	}
	elseif((!empty($_POST['MISdone']))and empty($_POST['save'])){
		
		if (!empty($_POST['jobentry'])){
				$jobnumber=$_POST['jobmanual'];
			}
			else{
				$jobnumber=$_POST['joblist'];
			}
		echo'<input type="hidden"  name="jobentry" value="'.$_POST['jobentry'].'">';
		echo'<input type="hidden"  name="joblist" value="'.$_POST['joblist'].'">';
		echo'<input type="hidden"  name="jobmanual" value="'.$_POST['jobmanual'].'">';
		echo'<input type="hidden"  name="MISdone" value="ok">';
		
		
		
		echo'<div class="row">';
		echo '<h5>Operator :</h5>';
		foreach($_POST['addscan_operator'] as &$operatorcode){
			
			echo '<i>'.get_operator_name($db,$operatorcode).' </i><br>';
			echo'<input type="hidden"  name="addscan_operator[]" value="'.$operatorcode.'">';
			
		}
		echo '</div><br><br>';
		
		echo'<div class="row">';
		  echo 'MIS : '.$jobnumber;
		  echo '<br>Product : '.get_product_code($db,$jobnumber);
		echo '</div><br>'; 
		
		echo'<div class="row">';
			echo'<div class="col-sm-3">';
			echo'Time Started';
			echo'</div>';
			echo'<div class="col-sm-6">';
			  echo'<input type="time" name="timestart" value="06:00" id="timestart" class="form-control" id="usr">';
			
			echo'</div>';
			
		echo'</div><br>';
		
		// echo '<input type="hidden" id="operator_code" name="operator_code" value="'.$scan['scan_operatorcode'].'">';
		// echo '<input type="hidden" id="timetag" name="timetag" value="'.$scan['scan_timetag'].'">';
		// echo '<input type="hidden" id="timetag_finish" name="timetag_finish" value="'.$scan['scan_timetag_finish'].'">';
		// echo '<input type="hidden" id="jobnumber" name="jobnumber" value="'.$scan['scan_jobnumber'].'">';
		
		if(empty($_POST['add_finished'])or !empty($_POST['remove_finish_scan'])){
			echo'<div class="row">';
			echo'<div class="col-sm-12">';
				echo'<i><center>Add Finished Scan</center></i>';
				echo'</div>';
			echo'</div>';
			echo'<div class="row">';
			echo'<div class="col-sm-12">';
				 echo'<center><input type="checkbox" id="finish_it" onChange="this.form.submit()" name="add_finished" value="ok"	class="allvenueall2 switch_1" ';
				 if(!empty($_POST['finish_it'])){echo "checked";}
				 echo '></center>';
				echo'</div>';
			echo'</div><br>';
			
				
			
		}
		
		
		if(!empty($_POST['add_finished'])and empty($_POST['remove_finish_scan'])){
			
			$testdate=new datetime($date.''.$_POST['timestart']);
		
			$timestamp = $testdate->getTimestamp();
				
			echo '<div class="row">';
				echo '<div class="col-sm-3">';
				echo 'Time Finished';
				echo '</div>';
				echo '<div class="col-sm-6">';
				echo '<input type="time" name="timefinish" value="'.date('H:i',$timestamp+3600).'" id="timefinish" class="form-control" id="usr" >';
				echo '</div>';
				echo '<div class="col-sm-2">';
				
					echo ' <button type="submit" value="ok" name="remove_finish_scan" class="btn btn-default" aria-label="Left Align">
						  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
						</button>';
				
			echo'</div>';
				
			echo'</div><br>';
		
			
			echo'<div class="row">';
				echo'<div class="col-sm-12">';
					echo'<center>Total Hours : <input disabled id="diff"></center>


						<script>
						var timestart = document.getElementById("timestart").value;
						var timefinish = document.getElementById("timefinish").value;

						document.getElementById("timestart").onchange = function() {diff(timestart,timefinish)};
						document.getElementById("timefinish").onchange = function() {diff(timestart,timefinish)};


						function diff(timestart, timefinish) {
							timestart = document.getElementById("timestart").value; //to update time value in each input bar
							timefinish = document.getElementById("timefinish").value; //to update time value in each input bar
							
							timestart = timestart.split(":");
							timefinish = timefinish.split(":");
							var startDate = new Date(0, 0, 0, timestart[0], timestart[1], 0);
							var endDate = new Date(0, 0, 0, timefinish[0], timefinish[1], 0);
							var diff = endDate.getTime() - startDate.getTime();
							var hours = Math.floor(diff / 1000 / 60 / 60);
							diff -= hours * 1000 * 60 * 60;
							var minutes = Math.floor(diff / 1000 / 60);

							return (hours < 9 ? "0" : "") + hours + ":" + (minutes < 9 ? "0" : "") + minutes;
						}

						setInterval(function(){document.getElementById("diff").value = diff(timestart, timefinish);}, 50); //to update time every second (1000 is 1 sec interval and function encasing original code you had down here is because setInterval only reads functions) You can change how fast the time updates by lowering the time interval
						</script>';
				echo'</div>';
			echo'</div><br>';
		}
		
		
		echo'<div class="row">';
			echo'<div class="col-sm-6">';
			echo'<center><button type="submit" value="ok" name="operatordone" class="btn btn-primary">Previous</button></center>';
			echo'</div>';
			echo'<div class="col-sm-6">';
			echo'<center><button type="submit" value="ok" name="save" class="btn btn-primary">Save Scan</button></center>';
			echo'</div>';
			//echo' <a href="manage_employee.php"  ><button type="button" class="btn btn-primary">View employee</button></a><br><br>';
			
		echo'</div><br>';	
		
		
	}elseif(!empty($_POST['save'])){
		//savescan($db,$operator,$jobnumber,$timestamp);
		
		if (!empty($_POST['jobentry'])){
				$jobnumber=$_POST['jobmanual'];
			}
			else{
				$jobnumber=$_POST['joblist'];
			}
		$testdate=new datetime($date.''.$_POST['timestart']);
		$timestamp_start = $testdate->getTimestamp();
		if(!empty($_POST['timefinish'])){
			$testdate=new datetime($date.' '.$_POST['timefinish']);
			$timestamp_finish = $testdate->getTimestamp();
			}
		
		
		foreach($_POST['addscan_operator'] as &$operatorcode){
			savescan($db,$operatorcode,$jobnumber,$timestamp_start);
			echo'<div class="row"><div class="alert alert-success" role="alert">
				  Scan added : '.get_operator_name($db,$operatorcode).' '.date('Y-m-d G:i:s',$timestamp_start).' on '.$jobnumber.'
				</div></div><br>';
			
			
			if(!empty($_POST['timefinish'])){
			savescan($db,$operatorcode,$jobnumber,$timestamp_finish);
			echo'<div class="row"><div class="alert alert-success" role="alert">
				  Scan added : '.get_operator_name($db,$operatorcode).' '.date('Y-m-d G:i:s',$timestamp_finish).' on '.$jobnumber.'
				</div></div><br>';
			}
			echo '<br>';
		}
		echo'<center><button value="ok" name="refresh" onclick="window.location.reload(true)" class="btn btn-primary">Refresh</button></center><br><br>';
		
		
		if(($_SESSION['temp']['id'])=='Coco'){
			
		}
		
		//show($_POST);
		
		
		
	}
	
	
	
	
	
	echo'</form>';
	//show($scan);
}

function load_list_MIS_open($db){
	
	$allMIS=list_MIS_open($db);
	foreach ($allMIS as &$MIS){
		echo"<option value='".$MIS['MIS_Number']."' >".$MIS['Product_Code']."  - ".$MIS['MIS_Number']."</option>";
	}
	
}

function show_all_scan_operator($db,$operator,$date){ //BlockView 
	 // show($operator);
	 // show($date);
	$allscan=load_all_scan($db,$date,get_operator_name($db,$operator),'All',0);
		
			//show($allscan);
			
			// foreach ($allscan as &$scan){
				// echo'<div class="row" ">';
					// echo'<div class="col-sm-2"><center>';
					// echo date('G:i:s',$scan['scan_timetag']);
					// echo'</center></div>';
					// echo'<div class="col-sm-2"><center>';
					// if($scan['scan_timetag_finish']<>''){echo date('G:i:s',$scan['scan_timetag_finish']);}
					// echo'</center></div>';
					// echo'<div class="col-sm-8"><center>'.max_job_open($db,$operator,$date);
					// echo' [PRODUCT] - ('.$scan['scan_jobnumber'].')</i>';
					// echo'</center></div>';
					
				// echo'</div>';
				
				// }
				$last_finished_time=0;
				echo'<div class="row " ">';
				$width=12/max_job_open($db,$operator,$date);
				foreach ($allscan as &$scan){
					echo'<form  id="form-'.$scan['scan_id'].'" method="post">';
					echo '<input type="hidden" id="load_scan_id" name="load_scan_id" value="'.$scan['scan_id'].'">';
					echo '<input type="hidden" id="operator_code" name="operator_code" value="'.$scan['scan_operatorcode'].'">';
					echo '<input type="hidden" id="timetag" name="timetag" value="'.$scan['scan_timetag'].'">';
					echo '<input type="hidden" id="timetag_finish" name="timetag_finish" value="'.$scan['scan_timetag_finish'].'">';
					echo '<input type="hidden" id="jobnumber" name="jobnumber" value="'.$scan['scan_jobnumber'].'">';
					
					if($scan['scan_timetag']>$last_finished_time and $last_finished_time<>0){
						echo'</div>';
						
						
							if($scan['scan_timetag']>($last_finished_time+280)){
							echo'<br>';
							echo'<div class="row " ">';
								echo'<div class="col-sm-12 scanner-sidepanel break-panel">';
								$break_time=$scan['scan_timetag']-$last_finished_time;
								echo'Break : ';
								if($break_time>=3600){echo floor(($break_time)/3600).' hours ';}
								echo floor((($break_time)%3600)/60).' min';
								echo'</div>';
							echo'</div>';
							echo'<br>';
							}
						
						
						echo'<div class="row  " ">';
					}
					
					
					
					
					echo'<div class="col-sm-'.$width.' >';
					
					echo ' " " onclick="document.forms[\'form-'.$scan['scan_id'].'\'].submit();" >';
					
						echo'<div class=" ';
						echo ' scanner-sidepanel ';
						
						if(!empty($_POST['load_scan_id'])&&($_POST['load_scan_id'])==$scan['scan_id']){echo' scanner-selected text-white';}
						if($scan['scan_timetag_finish']==''){echo 'still_open ';}
						echo'">';
						echo date('G:i:s',$scan['scan_timetag']);
						echo'<br>';
						echo get_product_code($db,$scan['scan_jobnumber']);
						echo ' - ';
						echo $scan['scan_jobnumber'];
						//echo'- ('.$scan['scan_jobnumber'].')';
						//if($scan['scan_timetag_finish']<>''){echo ' - '.showhours($scan['scan_timetag_finish']-$scan['scan_timetag']).' hours';}
						echo'<br>';
						if($scan['scan_timetag_finish']<>''){echo date('G:i:s',$scan['scan_timetag_finish']);}
						echo'<br>';
						echo'</div>';
					echo'</div>';
					
					
					
					if($scan['scan_timetag_finish']<>''){$last_finished_time=max($last_finished_time,$scan['scan_timetag_finish']);}else{$last_finished_time=0;}
				echo'</form>';	
				}
				
				echo'</div>';
}

function max_job_open($db,$operator,$date){
		$query='SELECT TOP 1 [scan_process_started]as total  FROM dbo.scan 
	LEFT JOIN
	dbo.operator
	ON
	scan_operatorcode=operator_code
	
	WHERE 
	[scan_operatorcode]=\''.$operator.'\'
	and scan_date=\''.$date.'\'
	order by [scan_process_started]DESC
	
	';
	
	$sql = $db->prepare($query); 
	 //show($query);
	$sql->execute();

	$row=$sql->fetch();
	return $row['total'];
}

function get_product_code($db,$jobnumber){
	
	$code='No Code';
	
	$query='SELECT Code FROM dbo.MIS_List
	
	WHERE 
	[ManufactureIssueNumber]=\''.$jobnumber.'\'';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
	
	if (empty($row)){
		$query='SELECT jobProdCode FROM dbo.tblJobs
	
		WHERE 
		[jobJobOrderIndex]=\''.$jobnumber.'\'';
		
		$sql = $db->prepare($query); 
		//show($query);
		$sql->execute();

		$row=$sql->fetch();
		if (empty($row)){
			
			}else
			{
				$code=$row['jobProdCode'];
			}
	}else
	{
		$code=$row['Code'];
	}
	
	
	
	
	
	
	
	
	
	
	
	return $code;
	
	
	
	
	
}

function count_scan_per_operator($db,$operator){
	$query='SELECT min(scan_date) as min_date, max(scan_date) as max_date, count(scan_statut)as total_count, round(sum([scan_time_distributed])/3600,1) as total_hours FROM dbo.scan 
	LEFT JOIN
	dbo.operator
	ON
	scan_operatorcode=operator_code
	
	WHERE 
	scan_operatorcode=\''.$operator.'\'
	
	';
	
	$sql = $db->prepare($query); 
	 //show($query);
	$sql->execute();

	$row=$sql->fetch();
	return $row;
}

function operator_name_check($db){
	echo '<script>function addHash(elem) {
	  var val = elem.value;
	  if(!val.match(/^MIS/)) {
		elem.value = "MIS" + val;
	  }
	}</script>';
	
	echo '<form id="form-countscan" method="post">';
	echo '<div class="row"  >';
		echo '<div class="col-sm-12 ">';
		echo' <h3>List of Operator Code with no Names</h3>';
		echo '</div>';
	echo '</div>';
	echo '<div class="row"  >';
		echo '<div class="col-sm-12 ">';
		$all_code=list_code_with_no_operator($db);
		foreach ($all_code as &$code){
			echo'Code: '.$code['code'].' - Scan: '.$code['number_of_scan'].' - From ' .$code['min_date'].' to '.$code['max_date'].'<br>';
		}
		echo '</div>';
	echo '</div>';
	echo '</form>';
	
}

function MIS_product_check($db){
	echo '<script>function addHash(elem) {
	  var val = elem.value;
	  if(!val.match(/^MIS/)) {
		elem.value = "MIS" + val;
	  }
	}</script>';
	
	echo '<form id="form-countscan" method="post">';
	echo '<div class="row"  >';
		echo '<div class="col-sm-12 ">';
		echo' <h3>List of MIS Number with no Product Code</h3>';
		echo '</div>';
	echo '</div>';
	echo '<div class="row"  >';
		echo '<div class="col-sm-12 ">';
		$all_code=list_MIS_with_no_ProductCode($db);
		foreach ($all_code as &$code){
			echo'Code: '.$code['jobnumber'].' - Scan: '.$code['number_of_scan'].' - From ' .$code['min_date'].' to '.$code['max_date'].'<br>';
		}
		echo '</div>';
	echo '</div>';
	echo '</form>';
	
}

function list_code_with_no_operator($db){
	
	$query='SELECT min([scan_date])as min_date, max([scan_date])as max_date, count([scan_id])as number_of_scan,[scan_operatorcode] as code
	FROM [dbo].[scan]
	left JOIN
	dbo.operator
	ON
	operator_code=scan_operatorcode
	WHERE operator_code is null
	
	group by [scan_operatorcode]
	order by max_date desc
	';
	
	$sql = $db->prepare($query); 
	
	$sql->execute();

	$row=$sql->fetchall();
	
	
	return $row;
}

function list_MIS_with_no_ProductCode($db){
	
	$query='SELECT min([scan_date])as min_date, max([scan_date])as max_date, count([scan_id])as number_of_scan,scan_jobnumber as jobnumber   
	FROM scan left JOIN
	MIS_List ON scan.scan_jobnumber = MIS_List.ManufactureIssueNumber COLLATE SQL_Latin1_General_CP1_CI_AS
	WHERE ManufactureIssueNumber is null
	AND scan_jobnumber LIKE \'MIS%\'
	GROUP BY scan_jobnumber
	ORDER BY max_date DESC';
	
	$sql = $db->prepare($query); 
	
	$sql->execute();

	$row=$sql->fetchall();
	
	
	return $row;
}


?>