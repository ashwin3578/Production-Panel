

<?php 
$page_title='Live View';
include ('header.php'); ?>


<div class="container">

	
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	
	
	
	
	
	
	
	
	
	
	
	
	<?php
	 
	 function get_data_days($db,$operator='All'){
	$operatorfilter='';
	if($operator<>'All'){
		$operatorfilter='AND scan_operatorcode = \''.$operator.'\'';
	}
	
	
	$query='SELECT round(sum(scan_time_distributed)/3600,1) as Hours,scan_date as TheDate,month([scan_date])as themonth, year([scan_date])as theyear,day([scan_date])as theday FROM dbo.scan 
	WHERE 
	scan_statut=\'start\'
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
	 
	 
	function calendar($data,$operator='All'){
		echo' <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["calendar"]});
      google.charts.setOnLoadCallback(drawChart);

   function drawChart() {
       var dataTable = new google.visualization.DataTable();
       dataTable.addColumn({ type: \'date\', id: \'Date\' });
       dataTable.addColumn({ type: \'number\', id: \'Won/Loss\' });
       dataTable.addRows([';
	   
		foreach ($data as &$days){
			echo' [ new Date('.$days['theyear'].', '.($days['themonth']-1).', '.$days['theday'].'), '.$days['Hours'].' ],';
		}
         
        echo']);

       var chart = new google.visualization.Calendar(document.getElementById(\'calendar_basic\'));

       var options = {
         title: "Yearly View",
         height: 350,
       };

       chart.draw(dataTable, options);
   }
    </script>';
	echo' <div id="calendar_basic" style="width: 1000px; height: 350px;"></div>';
	}
	
	$data=get_data_days($db,'RHI');
		
		
		
		//show($data);
		calendar($data);
		
		//gauge('Hours',7.6,0,12)
	
	
	
	
	
	?>
	
	
	
	
	<?php
	
	
	
	?>
</div>
