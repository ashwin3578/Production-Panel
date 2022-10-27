<?php 
$page_title='Torque';
$page = $_SERVER['PHP_SELF'];

?>


<?php
include ('header.php'); 



?>



<div class="container">
    <div >
        <?php
        function lastvalue($db){
            $query='SELECT TOP 1 * FROM dbo.torque ORDER BY torque_timetag DESC,torque_id DESC ';
            $sql = $db->prepare($query); 
            $sql->execute();
            $row=$sql->fetch();
            
            return $row;
        }
        
        
        $query='SELECT TOP 1000 * FROM dbo.torque ORDER BY torque_timetag DESC,torque_id DESC ';
                    
        $sql = $db->prepare($query);
        $sql->execute();
        $row=$sql->fetchall();
        $i=0;
        foreach ($row as &$point){
            //$dataPoints[$i]['x']=$i+1;
            $dataPoints[$i]['x']=date('i',$point['torque_timetag'])*100+date('s',$point['torque_timetag']);
            $dataPoints[$i]['date']=date('G:i:s',$point['torque_timetag']);
            $dataPoints[$i]['y']=round($point['torque_read']/1024*5,2);
            $i++;
        }
        ?>
    <h1>Torque Meter</h1>
    <div class="row"  >
        <div class="col-sm-2" id="here">
        <div  id="toload">
        <div class="row"  >Voltage : <?php echo round(lastvalue($db)['torque_read']/1024*5,3);?> V</div>
        <div class="row"  >Date : <?php echo date('Y-m-d G:i:s',lastvalue($db)['torque_timetag']);?> </div>
        </div>
        </div>
        <div class="col-sm-8">
            <?php
            foreach ($dataPoints as &$point){
                ?>
                <div class="col-sm-3"  >
                    <div class="col-sm-3"><?php echo $point['date'];?></div>
                    <div class="col-sm-3"><?php echo $point['y'];?></div>
                </div>
                <?php } ?>
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
                
                
                
                
                <!--<div id="chart_div"></div>-->
        </div>
    </div>
</div>
<script>
   $(document).ready(function(){
        setInterval(function(){
            $("#here").load(window.location.href + " #toload" );          
            
           
        }, 500);
        });
</script>
	
	
	
	
	


	
	
	
</div>


