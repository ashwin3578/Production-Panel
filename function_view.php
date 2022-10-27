<?php
function gauge($name,$value,$valuemini=0,$valuemax=100,$option='height: 120,'){
		echo' <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	   <script type="text/javascript">
		  google.charts.load(\'current\', {\'packages\':[\'gauge\']});
		  google.charts.setOnLoadCallback(drawChart'.$name.');

		  function drawChart'.$name.'() {

			var data'.$name.' = google.visualization.arrayToDataTable([
			  [\'Label\', \'Value\'],
			  [\''.$name.'\', '.$value.']
			]);

			var options = {
				'.$option.'
			  
			  minorTicks: 10, max:'.$valuemax.', min:'.$valuemini.'
			};

			var chart = new google.visualization.Gauge(document.getElementById(\'chart_div_'.$name.'\'));

			chart.draw(data'.$name.', options);

			setInterval(function() {
			  data'.$name.'.setValue(0, 1, '.$value.');
			  chart.draw(data'.$name.', options);
			}, 130);
			
		  }
		</script>';
		echo' <div id="chart_div_'.$name.'"  ></div>';
	}
	
function treemap($data){
		echo' <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load(\'current\', {\'packages\':[\'treemap\']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
		  
		  var data = google.visualization.arrayToDataTable([
		  [\'Process\',\'Parent\',\'Operator\',\'Hours\'],
		  [\'Global\',    null,                 -10,                               -10]';
		  
		  $i=0;
		  foreach ($data['workarea'] as &$workarea){
			  echo',';
			  echo'[\''.$workarea['workarea'].'\',   \'Global\',           '.$workarea['nbr_operator'].'  ,                               '.$workarea['nbr_operator'].']';
		  $i++;}
		  
		  foreach ($data['Process'] as &$process){
			  echo',';
			  echo'[\''.$process['Process'].'\',   \''.$process['WorkArea'].'\',           '.$process['nbr_operator'].'  ,                               '.$process['nbr_operator'].']';
		  $i++;}
		  
		  foreach ($data['operator'] as &$operator){
			  echo',';
			  echo'[\''.$operator['operator'].'\',   \''.$operator['Process'].'\',           1  ,                               1]';
		  $i++;}
		  
		  echo']);';
		  
     // echo'   var data = google.visualization.arrayToDataTable([
          // [\'Location\', \'Parent\', \'Market trade volume (size)\', \'Market increase/decrease (color)\'],
          // [\'Global\',    null,                 0,                               0],
          // [\'America\',   \'Global\',             0,                               0],
          // [\'Europe\',    \'Global\',             0,                               0],
          // [\'Asia\',      \'Global\',             0,                               0],
          // [\'Australia\', \'Global\',             0,                               0],
          // [\'Africa\',    \'Global\',             0,                               0],
          // [\'Brazil\',    \'America\',            11,                              10],
          // [\'USA\',       \'America\',            52,                              31],
          // [\'Mexico\',    \'America\',            24,                              12],
          // [\'Canada\',    \'America\',            16,                              -23],
          // [\'France\',    \'Europe\',             42,                              -11],
          // [\'Germany\',   \'Europe\',             31,                              -2],
          // [\'Sweden\',    \'Europe\',             22,                              -13],
          // [\'Italy\',     \'Europe\',             17,                              4],
          // [\'UK\',        \'Europe\',             21,                              -5],
          // [\'China\',     \'Asia\',               36,                              4],
          // [\'Japan\',     \'Asia\',               20,                              -12],
          // [\'India\',     \'Asia\',               40,                              63],
          // [\'Laos\',      \'Asia\',               4,                               34],
          // [\'Mongolia\',  \'Asia\',               1,                               -5],
          // [\'Israel\',    \'Asia\',               12,                              24],
          // [\'Iran\',      \'Asia\',               18,                              13],
          // [\'Pakistan\',  \'Asia\',               11,                              -52],
          // [\'Egypt\',     \'Africa\',             21,                              0],
          // [\'S. Africa\', \'Africa\',             30,                              43],
          // [\'Sudan\',     \'Africa\',             12,                              2],
          // [\'Congo\',     \'Africa\',             10,                              12],
          // [\'Zaire\',     \'Africa\',             8,                               10]
        // ]);'
	
      echo'  tree = new google.visualization.TreeMap(document.getElementById(\'chart_div\'));

        tree.draw(data, {
          minColor: \'#fdd\',
          midColor: \'#ddd\',
          maxColor: \'#eee\',
          headerHeight: 15,
          fontColor: \'black\',
          showScale: true,
		  forceIFrame: true
        });
		

      }
	  
	 
    </script>';
	echo'<div id="chart_div" style="width: 900px; height: 500px;"></div>';
	
}
	
function get_details_open($db){
		$today=(date('Y-m-d',time()))	;
		$query='SELECT count(distinct (scan_operatorcode))as Count_Operator,count(distinct (scan_jobnumber))as Count_MIS FROM dbo.scan  
		LEFT JOIN
		dbo.operator
		ON
		scan_operatorcode=operator_code
		
		WHERE 
		scan_statut=\'start\'
		AND scan_still_open=1
		AND scan_date=\''.$today.'\'
		';
		
		$sql = $db->prepare($query); 
		//show($query);
		$sql->execute();

		$row=$sql->fetch();
		// show($query);
		// show($row);
		return $row;
}
	
function get_all_current_open($db){
		$today=(date('Y-m-d',time()))	;
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
		AND scan_still_open=1
		AND scan_date=\''.$today.'\'
		
		ORDER bY WorkArea,scan_jobnumber, operator_fullname
		';
		
		$sql = $db->prepare($query); 
		//show($query);
		$sql->execute();

		$row=$sql->fetchall();
		return $row;
}
	
function sankey($data){
			echo'<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>



 <script type="text/javascript">
  google.charts.load("current", {packages:["sankey"]});
  google.charts.setOnLoadCallback(drawChart);
   function drawChart() {
    var data = new google.visualization.DataTable();
    data.addColumn(\'string\', \'From\');
    data.addColumn(\'string\', \'To\');
    data.addColumn(\'number\', \'Operator\');
    data.addRows([';
        
		  // foreach ($data['workarea'] as &$workarea){
			  
			  // echo'[\'All\',\''.$workarea['workarea'].'\',           '.$workarea['nbr_operator'].'  ],';
			  
		  // }
		
		 foreach ($data['Process'] as &$process){
			  
			  echo'[\''.$process['WorkArea'].'\',   \''.$process['Process'].'\',           '.$process['nbr_operator'].' ],';
		  }
		  
		  foreach ($data['operator'] as &$operator){
			 
			  echo'[\''.$operator['Process'].'\',   \''.$operator['operator'].'\',           1  ],';
		  }
		
		
		
   echo' ]);

    // Set chart options
    var options = {
      height: 600,
	  sankey: { node: { nodePadding: 10 } },
	  sankey: {
 iterations: 0,
      }
    };

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.Sankey(document.getElementById(\'sankey_multiple\'));
    chart.draw(data, options);
   }
 </script> ';
	echo'<div id="sankey_multiple" ></div>';
	
}
	
function orga($data){
			echo'<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load(\'current\', {packages:["orgchart"]});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn(\'string\', \'Name\');
        data.addColumn(\'string\', \'Manager\');
        data.addColumn(\'string\', \'ToolTip\');

        // For each orgchart box, provide the name, manager, and tooltip to show.
        data.addRows([';
          echo' [\'All\',\'\',\'\'],';
		 // echo'[\'Test\',\'Test\',\'All\',]';
		 // echo' [\'Mike\',\'All\', \'The President\'],
          // [\'Jim\',\'Mike\', \'VP\'],
          // [\'Alice\', \'Mike\', \'\'],
          // [\'Bob\', \'Jim\', \'Bob Sponge\'],
          // [\'Carol\', \'Bob\', \'\']';
		 
		 foreach ($data['workarea'] as &$workarea){
			  
			   echo'[\''.$workarea['workarea'].'\',\'All\',          \'\'  ],';
			  
		   }

		 foreach ($data['Process'] as &$process){
			  
			  echo'[\''.$process['Process'].'\',   \''.$process['WorkArea'].'\',         \'\'  ],';
		  }
		  
		  foreach ($data['operator'] as &$operator){
			 
			  echo'[\''.$operator['operator'].'\',   \''.$operator['Process'].'\',   \'\'   ],';
		  }

		 
       echo' ]);

        // Create the chart.
        var chart = new google.visualization.OrgChart(document.getElementById(\'chart_div\'));
        // Draw the chart, setting the allowHtml option to true for the tooltips.
        chart.draw(data, {\'allowHtml\':true});
      }
   </script>';
	echo'<div id="chart_div" ></div>';
	
}	
	
	
	
function live_view($db){
	echo'<h1>Live-View</h1>';
	echo '<div class="row"  >';
		$allprocess=get_all_current_open($db);
		
		
		$workarea='';
		$operator='';
		$code='';
		$data=array();
		$i=0;
		foreach ($allprocess as &$process){
			$workarea=$process['WorkArea'];
			
			
			$code=$process['Code'].' - '.$process['scan_jobnumber'];
			$time=round((time()-$process['scan_timetag'])/3600,1);
			$data['workarea'][$workarea]['nbr_operator']++;
			$data['workarea'][$workarea]['workarea']=$workarea;
			$data['workarea'][$workarea]['total_time']=$data['workarea'][$workarea]['total_time']+$time;
			$data['Process'][$code]['nbr_operator']++;
			$data['Process'][$code]['WorkArea']=$workarea;
			$data['Process'][$code]['Process']=$code;			
			$data['Process'][$code]['total_time']=$data['Process'][$code]['total_time']+$time;
			
			$operator=$process['operator_fullname'];
			if($operator==$process['operator_fullname']){
				$i++;
				$data['operator'][$operator.$i]['operator']=$operator;
				$data['operator'][$operator.$i]['Process']=$code;			
				$data['operator'][$operator.$i]['total_time']=$data['operator'][$operator]['total_time']+$time;
			
			}else
			{ $i=0;
				$data['operator'][$operator]['operator']=$operator;
				$data['operator'][$operator]['Process']=$code;			
				$data['operator'][$operator]['total_time']=$data['operator'][$operator]['total_time']+$time;
			
			}
			
			
			
		}
		
		
		
		
		echo '<div class="col-sm-2">';
			echo '<div class="row">';	
			$livedetails=get_details_open($db);
			gauge('Operator',$livedetails['Count_Operator'],0,50);
			echo'Number of Operator : '.$livedetails['Count_Operator'];
			echo '</div>';
			echo '<div class="row">';
			gauge('Process',$livedetails['Count_MIS'],0,50);
			echo'Number of Process : '.$livedetails['Count_MIS'];
			echo '</div>';
		echo '</div>';
		
		echo '<div class="col-sm-8">';
			//show($data);
			 sankey($data);
			//orga($data);
			//treemap($data);
			
		echo '</div>';
}




	


?>