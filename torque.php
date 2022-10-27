<?php 
$page_title='Torque';
$page = $_SERVER['PHP_SELF'];
$sec = "1";

echo'<head><meta http-equiv="refresh" content="'.$sec.'" URL="'.$page.'"></head>';


?>


<?php
include ('header.php'); ?>



<div class="container">



	
	
	
	
	<?php
	
	//show($_POST);
	
	if(isset($_GET["reset"])){
		$query="DELETE FROM dbo.torque ";
			
			$sql = $db->prepare($query); 
	//show($query);
			$sql->execute();
		}
	
	
	if(isset($_GET["timestamp"]) && isset($_GET["value"])){
	$timestamp = $_GET["timestamp"];
    $value = $_GET["value"];
	$valuemax = $_GET["valuemax"];
	
	$timestamp = microtime(true);
		
	 $query="INSERT INTO dbo.torque
			( torque_timestamp,
			torque_value,
			torque_valuemax) 
			VALUES (
			'$timestamp',
			'$value',
			'$valuemax')";
			
			$sql = $db->prepare($query); 

			$sql->execute();
	
	
	
	}else{
		
	
	echo'<h1>Torque Meter</h1>';
	echo '<div class="row"  >';
		
		
		
		
		
		echo '<div class="col-sm-2">';
			echo '<div class="row">';

			$query='SELECT TOP 1 * FROM dbo.torque 
				
			ORDER BY torque_timestamp DESC 
			';
			
			$sql = $db->prepare($query); 
				 //show($query);
			$sql->execute();

			$row=$sql->fetch();

		

			
			$value=$row['torque_value'];
			$valuemax=$row['torque_valuemax'];
			// gauge('',$value,0,20);
			echo'Torque : '.round($value,2).' Nm';
			echo '</div>';
			echo '<div class="row">';
			// gauge('Nm',$valuemax,0,20);
			echo'Torque Max: '.round($valuemax,2).' Nm';
			echo '</div>';
		echo '</div>';
		
		echo '<div class="col-sm-8">';
			
			$query='SELECT TOP 1000 * FROM dbo.torque 
				
			ORDER BY torque_timestamp ASC 
			';
			
			$sql = $db->prepare($query); 
				 //show($query);
			$sql->execute();

			$row=$sql->fetchall();
			$i=0;
			foreach ($row as &$point){
				//$dataPoints[$i]['x']=$i+1;
				$dataPoints[$i]['x']=date('i',$point['torque_timestamp'])*100+date('s',$point['torque_timestamp']);
				$dataPoints[$i]['y']=$point['torque_value'];
				$i++;
			}
			//show($dataPoints);
			// $dataPoints = array();
			// $y = 40;
			// for($i = 0; $i < 1000; $i++){
				// $y += rand(0, 10) - 5; 
				// array_push($dataPoints, array("x" => $i, "y" => $y));
			// }
			//show($dataPoints);
	}
			?>
			<!-- <script>
			window.onload = function () {
			 
			var chart = new CanvasJS.Chart("chartContainer", {
				theme: "light2", // "light1", "light2", "dark1", "dark2"
				animationEnabled: false,
				zoomEnabled: true,
				title: {
					text: "Torque Measured"
				},
				data: [{
					type: "area",     
					dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
				}]
			});
			chart.render();
			 
			}
			</script>
			<div id="chartContainer" style="height: 370px; width: 100%;"></div>
			-->
			
			
			<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
			<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
			
			
			<script>
			google.charts.load('current', {packages: ['corechart', 'line']});
			google.charts.setOnLoadCallback(drawBasic);

			function drawBasic() {

				  var data = new google.visualization.DataTable();
				  data.addColumn('number', 'X');
				  data.addColumn('number', 'Torque');

				  data.addRows([
					<?php 
					foreach ($dataPoints as &$point){
						echo' ['.$point['x'].','.$point['y'].'] ';
						echo' , ';
					}
					echo'  ';

					?>
				  ]);

				  var options = {
					hAxis: {
					  title: 'Measurements Point'
					},
					vAxis: {
					  title: 'Torque'
					},
					chartArea:{width:'80%',height:'80%'}
				  };

				  var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

				  chart.draw(data, options);
				}
			</script>
			
			
			
			
			<div id="chart_div"></div>
			
			
			<?php
			
		echo '</div>';

	?> 
	
	
	
	
	


	
	
	
</div>


