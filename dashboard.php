

<?php 
$page_title='Dashboard';
$title_top='Production Issues Dashboard';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/issue_log.css">	
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	<?php
	include('function_dashboard.php');
	function getallinfos($db){
	$query="SELECT count(distinct (issue_number))as Count_issue FROM dbo.issue_log  
	WHERE issue_closed=0
	";
	
	$sql = $db->prepare($query); 
	$sql->execute();
	$row=$sql->fetch();	
	$info['still_open']=$row[0];
	
	$query="SELECT count(distinct (issue_number))as Count_issue FROM dbo.issue_log  
	";
	
	$sql = $db->prepare($query); 
	$sql->execute();
	$row=$sql->fetch();	
	$info['total']=$row[0];
	
		$query="SELECT count(distinct (issue_number))as Count_issue FROM dbo.issue_log  
		WHERE issue_IAF<>0
	";
	
	$sql = $db->prepare($query); 
	$sql->execute();
	$row=$sql->fetch();	
	$info['IAF']=$row[0];
	
	$query="SELECT AVG((issue_nbr_day_open*10)) as Avg_day,
	Min((issue_nbr_day_open))as Min_day_open,
	Max((issue_nbr_day_open))as Max_day_open FROM dbo.issue_log  
		
	";
	
	$sql = $db->prepare($query); 
	$sql->execute();
	$row=$sql->fetch();	
	$info['Avg_day_open']=$row[0]/10;
	$info['Min_day_open']=$row[1];
	$info['Max_day_open']=$row[2];
	show ($info);
	return $info;
}

	
echo'<div class="row ">';
	echo'<div class="col-sm-8 ">';
		echo'<div class="col-sm-3 ">';
		 echo'<h4>Top 10 Product :</h4>';
		 $data=getproductlist($db);
		 tableview($data,$title='Product List',$option="titlePosition:'out',orientation:'vertical',height: 600");
		echo'</div>';
		echo'<div class="col-sm-9 ">';
			echo'<div class="col-sm-6 ">';
			$data=gethowfound($db);
			
			piechart($data,$title='How Found',"theme:'maximized',pieSliceText: 'label',pieSliceTextStyle:{fontSize: 8},height: 250");
			echo'</div>';
			echo'<div class="col-sm-6 ">';
			$data=getrootcause($db);
			
			piechart($data,$title='Root Cause',"theme:'maximized',pieSliceText: 'label',height: 250");
			echo'</div>';
			echo'<div class="col-sm-12 ">';
			$data=getMonthview($db);
			
			stackedchart($data,$title='Trend',' height: 200');
			echo'</div>';
		echo'</div>';
	echo'</div>';
	echo'<div class="col-sm-4 ">';
		echo'<div class="row ">';
			echo'<div class="col-sm-6 ">';
			
			$data=getallYear($db);
			tableviewfilter($data,$title='Year');
			echo'</div>';
			echo'<div class="col-sm-6 ">';
			
			$data=getallMonth($db);
			
			tableviewfilter($data,$title='Month');
			echo'</div>';
		echo'</div>';
		echo'<div class="row ">';
			echo'<div class="col-sm-6 ">';
			// gauge("Issues",39,$valuemini=0,$valuemax=100);
			echo'<br>';
			$info=getallinfos($db);
			$data=array();
			$data[0][]=$info['total'];
			tableviewfilter($data,$title='Total Nº Entries');
			$data=array();
			$data[0][]=$info['still_open'];
			tableviewfilter($data,$title='Nº Outstanding');
			$data=array();
			$data[0][]=$info['IAF'];
			tableviewfilter($data,$title='Total Nº IAF Raised');
			echo'<br>';
			$data=array();
			
			$data[0][]=$info['Avg_day_open'];
			tableviewfilter($data,$title='Avg Days Open');
			$data=array();
			$data[0][]=$info['Min_day_open'];
			tableviewfilter($data,$title='Min Days Open');
			$data=array();
			$data[0][]=$info['Max_day_open'];
			tableviewfilter($data,$title='Max Days Open');
			
			echo'</div>';
		echo'</div>';
	echo'</div>';
echo'</div>';
	
	
	
	
	
	 
	 
	 
	

	?>
	
	
	


	
	
	
</div>
