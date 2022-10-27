

<?php 
$page_title='Log';
include ('header.php');
redirect_if_not_logged_in();


function log_summary_listmonth($db){
	$filter=all_filter_list();
	$listmonth=log_listmonth($db,$filter['workareafilter'],$filter['operatorfilter'],$filter['daysfilter']);
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
					if($months['total']>0){echo $months['total'];}else{echo'-';}
				echo '</div>';
				
				
				echo'</form>';
			echo '</div>';
			}
		
}

function log_summary_listdays($db){
	$filter=all_filter_list();
	$listdays=loglistdays($db,$filter['workareafilter'],$filter['operatorfilter'],$filter['yearmonthfilter']);
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

function log_summary_listoperator($db){
	$filter=all_filter_list();
	$listOperator=log_listoperator($db,$filter['workareafilter'],$filter['yearmonthfilter'],$filter['daysfilter']);
	
	
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

function log_scan_day($db,$operator='All',$date='All'){
	
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
	$allscan=log_listscan($db,$filter['workareafilter'],$filter['operatorfilter'],$filter['yearmonthfilter'],$filter['daysfilter']);
		
	
	 
	
	echo'<form id="form-view" method="post">';
	echo'<div class="row" >';
	echo'<Center><h4 onclick="document.forms[\'form-view\'].submit();">';
	if($_SESSION['temp']['summary']['view']=='Normal'){echo'List View ';}else{echo'Block View';}
	
	echo''.$daysfilter.' '.$operatorfilter.'</h4></Center>';
	
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

function log_summary_workareafilter($db){
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

function log_listoperator($db,$workareafilter='',$yearmonthfilter='',$daysfilter=''){
	
	
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

function log_listdays($db,$workareafilter='',$operatorfilter='',$yearmonthfilter=''){
	
	
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

function log_listmonth($db,$workareafilter='',$operatorfilter='',$daysfilter=''){
	
	
	
	
	$query='SELECT  month([log_date])as themonth, year([log_date])as theyear,count([log_id])';
	
	if ($_SESSION['temp']['summary']['mode']=='Average'){$query=$query.'/count(distinct concat([log_date],[log_operatorcode]))';}
	
	$query=$query.'as total FROM dbo.log 
	LEFT JOIN
	operator
	ON
	log_operatorcode=operator_code

	GROUP BY month([log_date]), year([log_date])
	ORDER BY year([log_date]) DESC, month([log_date]) DESC
	';
	
	$sql = $db->prepare($query); 
		//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	
	return $row;
		
}

function log_listscan($db,$workareafilter='',$operatorfilter='',$yearmonthfilter='',$daysfilter=''){
	
	
	$query='SELECT TOP 20 * FROM dbo.scan 
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

















 ?>


<div class="container">

	
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	
	<?php
	
	
	
	if(!empty($_POST['month'])){
		$_SESSION['temp']['summary']['yearmonth']=$_POST['year'].$_POST['month'];
		$_SESSION['temp']['summary']['year']=$_POST['year'];
		$_SESSION['temp']['summary']['month']=$_POST['month'];
	}
	
		if (empty($_SESSION['temp']['summary']['yearmonth'])){
			$_SESSION['temp']['summary']['yearmonth']='All';
		}
	if(!empty($_POST['operatorname'])){
		$_SESSION['temp']['summary']['operatorname']=$_POST['operatorname'];
		$_SESSION['temp']['summary']['operatorcode']=$_POST['operatorcode'];
		
	}
	
		if (empty($_SESSION['temp']['summary']['operatorcode'])){
			$_SESSION['temp']['summary']['operatorcode']='All';
			$_SESSION['temp']['summary']['operatorname']='All';
		}
	if(!empty($_POST['days'])){
		$_SESSION['temp']['summary']['days']=$_POST['days'];
		
		
	}
	
		if (empty($_SESSION['temp']['summary']['days'])){
			$_SESSION['temp']['summary']['days']='All';
			
		}
		
	if(!empty($_POST['workarea'])){
		$_SESSION['temp']['summary']['workarea']=$_POST['workarea'];
		
		
	}
	
		if (empty($_SESSION['temp']['summary']['workarea'])){
			$_SESSION['temp']['summary']['workarea']='All';
			
		}	
	
	
	
	
	
	
	if(!empty($_POST['mode'])){
		$_SESSION['temp']['summary']['mode']=$_POST['mode'];
		
		
	}
	
		if (empty($_SESSION['temp']['summary']['mode'])){
			$_SESSION['temp']['summary']['mode']='Total';
			
		}	

	
		
	

	?>
	
	
	
	
	<?php
	
	
	
	echo'<form id="form-title" method="post">';
	 echo'<div class="row"  >';
		echo'<Center><h3 onclick="document.forms[\'form-title\'].submit();">Summary View - '.$_SESSION['temp']['summary']['mode'].'</h3></Center>';
		echo '<input type="hidden" id="mode" name="mode" value="';
		if($_SESSION['temp']['summary']['mode']=='Average'){echo'Total';}else{echo'Average';}
		echo'">';
	echo'</div>';
	echo'</form>';
	echo'<div class="row " ><small>';
		echo'<div class="col-sm-3">';
			echo'<div class="row" >';
			echo'<Center><h4>WorkArea</h4></Center>';
			echo'</div>';
			echo'<div class="row" >';
			log_summary_workareafilter($db);
			echo'</div>';
			
			echo'<div class="row" >';
			echo'<Center><h4>Operator</h4></Center>';
			echo'</div>';
			echo'<div class="row" >';
				
				log_summary_listoperator($db);
				
			echo'</div>';
		echo'</div>';
		
		
		echo'<div class="col-sm-2 ">';
			echo'<div class="row" >';
			echo'<Center><h4>Month-Year</h4></Center>';
			echo'</div>';
			echo'<div class="row" >';
			
				log_summary_listmonth($db);
				
				
			echo'</div>';
		echo'</div>';
		echo'<div class="col-sm-2" >';
			echo'<div class="row"  >';
			echo'<Center><h4>Days</h4></Center>';
			echo'</div>';
			echo'<div class="row"  >';
				log_summary_listdays($db);
			echo'</div>';
		echo'</div>';
		
		echo'<div class="col-sm-5">';
			
				log_scan_day($db,$operator,$date);
			
			
			 
			
		echo'</div>';
		
	 echo'</small></div>';
	// show($_POST);
	?>
</div>
