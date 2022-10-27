

<?php 
$page_title='Chess';
$title_top='Chess Champion';
$page = $_SERVER['PHP_SELF'];
?>

<?php
include ('header.php'); 
//show($_POST);
if(!empty($_POST)){
    $query="INSERT INTO chess
        (chess_date,chess_winner,
        chess_winner_color
        )
        VALUES
        (
        '".date('Y-m-d',time())."',
        '".$_POST['chess_winner']."',
        '".$_POST['chess_winner_color']."'
        
        )
    ";

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
}
?>


<div class="container">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php echo '<link rel="stylesheet" href="css/machine.css?v='.time().'">';?>
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	
    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="row">
                <?php if($_SESSION['temp']['id']=='CorentinHillion'){?>
                <form method="POST" >
                    <div class="col-sm-4  col-md-4">
                        <select name="chess_winner" class="form-control"  >
                        <option selected>Corentin</option>
                        <option >Finney</option>
                        </select>
                    </div>
                    <div class="col-sm-4  col-md-4">
                        <select name="chess_winner_color" class="form-control"  >
                        <option selected>White</option>
                        <option >Black</option>
                        </select>
                    </div>
                    <div class="col-sm-4  col-md-2">
                        <input type="submit" class="form-control" >
                    </div>
                </form>
                <?php }?>
            </div>
            <div class="col-sm-12 col-md-10">
                <?php show_chessstats($db);?>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="row header_chess">Result</div>
            <?php $query='SELECT *
                FROM chess
                Order by chess_date desc,chess_id desc';
            
            $sql = $db->prepare($query); 
            //show($query);
            $sql->execute();

            $allresult=$sql->fetchall();    
            foreach($allresult as $result){?>
            <div class="row row_chess">
                <div class="col-sm-4 col-md-3">
                    <?php echo date('D jS M',strtotime($result['chess_date'])); ?>
                </div>
                <div class="col-sm-8 col-md-6">
                    <?php if($result['chess_winner']=='Corentin'){
                        $color['Corentin']=$result['chess_winner_color'];
                    }else{
                        if($result['chess_winner_color']=='White'){
                            $color['Corentin']='Black';
                        }else{
                            $color['Corentin']='White';
                        }
                    }
                    if($color['Corentin']=='White'){
                        $color['Finney']='Black';
                    }else{
                        $color['Finney']='White';
                    }
                    $style['Corentin']='';
                    $style['Finney']='';
                    $style[$result['chess_winner']]='border:2px solid #c5c5c5;border-radius:20px; ';
                    //show($style);
                    ?>
                    <div class="col-xs-6 col-md-6" style="<?php echo $style['Corentin']; ?>">
                        <div class="col-xs-8">Corentin</div>
                        <div class="col-xs-4">
                            <?php echo '<img src="img/'.$color['Corentin'].'_king.png" alt="Girl in a jacket" width="100%" >'; ?>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-6" style="<?php echo $style['Finney']; ?>">
                        <div class="col-xs-8">Finney</div>
                        <div class="col-xs-4">
                            <?php echo '<img src="img/'.$color['Finney'].'_king.png" alt="Girl in a jacket" width="100%" >'; ?>
                        </div>
                    </div>
                    
                </div>
            </div>
            <?php
            }?>
        </div>
    </div>
    
    
    
	
	


	
	
	
</div>

<?php 

function show_chessstats($db){
    $query='SELECT *
        FROM chess
        Order by chess_date asc';
    
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $allresult=$sql->fetchall();
       
    $stats=array();
    foreach($allresult as $result){
        $stats[$result['chess_winner']]['nbr_of_win']=$stats[$result['chess_winner']]['nbr_of_win']+1;
        $stats[$result['chess_winner']]['day_since_last_win']=(strtotime(date('Y-m-d',time()))-strtotime($result['chess_date']))/86400;
        $stats[$result['chess_winner']][$result['chess_winner_color']]['nbr_of_win']=$stats[$result['chess_winner']][$result['chess_winner_color']]['nbr_of_win']+1;
        $stats[$result['chess_winner']][$result['chess_winner_color']]['day_since_last_win']=(strtotime(date('Y-m-d',time()))-strtotime($result['chess_date']))/86400;
        $stats['total']['total']++;
        $stats['total'][$result['chess_winner_color']]++;
    }
    
    $stats['Finney']['win_percentage']=$stats['Finney']['nbr_of_win']/$stats['total']['total'];
    $stats['Corentin']['win_percentage']=$stats['Corentin']['nbr_of_win']/$stats['total']['total'];
    $stats['Finney']['White']['win_percentage']=$stats['Finney']['White']['nbr_of_win']/($stats['Finney']['White']['nbr_of_win']+$stats['Corentin']['Black']['nbr_of_win']);
    $stats['Corentin']['White']['win_percentage']=$stats['Corentin']['White']['nbr_of_win']/($stats['Corentin']['White']['nbr_of_win']+$stats['Finney']['Black']['nbr_of_win']);
    $stats['Finney']['Black']['win_percentage']=$stats['Finney']['Black']['nbr_of_win']/($stats['Finney']['Black']['nbr_of_win']+$stats['Corentin']['White']['nbr_of_win']);
    $stats['Corentin']['Black']['win_percentage']=$stats['Corentin']['Black']['nbr_of_win']/($stats['Corentin']['Black']['nbr_of_win']+$stats['Finney']['White']['nbr_of_win']);

    //show($stats);
    ?>
    <br>
    <div class="row header_chess">
        <div class="col-sm-4 ">Stats <?php echo $stats['Corentin']['nbr_of_win']; ?>-<?php echo $stats['Finney']['nbr_of_win']; ?></div>
        <div class="col-sm-4 ">Corentin</div>
        <div class="col-sm-4 ">Finney</div>
    </div>
    <!--<div class="row row_chess">
        <div class="col-sm-4 ">number of win</div>
        <div class="col-sm-4 "><?php echo $stats['Corentin']['nbr_of_win']; ?></div>
        <div class="col-sm-4 "><?php echo $stats['Finney']['nbr_of_win']; ?></div>
    </div>
    <div class="row row_chess">
        <div class="col-sm-4 ">win percentage</div>
        <div class="col-sm-4 "><?php echo number_format($stats['Corentin']['win_percentage']*100,2); ?>%</div>
        <div class="col-sm-4 "><?php echo number_format($stats['Finney']['win_percentage']*100,2); ?>%</div>
    </div>-->
    <div class="row row_chess">
        <div class="col-sm-4 ">days since last win</div>
        <div class="col-sm-4 "><?php echo $stats['Corentin']['day_since_last_win']; ?></div>
        <div class="col-sm-4 "><?php echo $stats['Finney']['day_since_last_win']; ?></div>
    </div>
    <div class="row row_chess">
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <div id="piechart" style="width: 100%;"></div>
        <script>
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var data = google.visualization.arrayToDataTable([
                ['Players', 'Count'],
                ['Finney',     <?php echo $stats['Finney']['nbr_of_win']; ?>],
                ['Corentin',      <?php echo $stats['Corentin']['nbr_of_win']; ?>]
                ]);

                var options = {
                title: 'Wins',
                is3D: true,
                
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                chart.draw(data, options);
            }
        </script>
    </div>
    <br>
    <div class="row header_chess">
        <div class="col-xs-6 col-md-6">
            <div class="col-xs-8">Corentin - <?php echo $stats['Corentin']['White']['nbr_of_win']; ?></div>
            <div class="col-xs-4"><img src="img/White_king.png" width="100%" ></div>
        </div>
        <div class="col-xs-6 col-md-6">
            <div class="col-xs-8">Finney - <?php echo $stats['Finney']['Black']['nbr_of_win']; ?></div>
            <div class="col-xs-4"><img src="img/Black_king.png" width="100%" ></div>
        </div>
    </div>
    <!--<div class="row row_chess">
        <div class="col-sm-4 ">number of win</div>
        <div class="col-sm-4 "><?php echo $stats['Corentin']['Black']['nbr_of_win']; ?></div>
        <div class="col-sm-4 "><?php echo $stats['Finney']['White']['nbr_of_win']; ?></div>
    </div>
    <div class="row row_chess">
        <div class="col-sm-4 ">win percentage</div>
        <div class="col-sm-4 "><?php echo number_format($stats['Corentin']['Black']['win_percentage']*100,2); ?>%</div>
        <div class="col-sm-4 "><?php echo number_format($stats['Finney']['White']['win_percentage']*100,2); ?>%</div>
    </div>-->
    <div class="row row_chess">
        <div class="col-sm-4 ">days since last win</div>
        <div class="col-sm-4 "><?php echo $stats['Corentin']['White']['day_since_last_win']; ?></div>
        <div class="col-sm-4 "><?php echo $stats['Finney']['Black']['day_since_last_win']; ?></div>
    </div>
    <div class="row row_chess">
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <div id="piechart-1" style="width: 100%;"></div>
        <script>
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart1);

            function drawChart1() {

                var data = google.visualization.arrayToDataTable([
                ['Players', 'Count'],
                ['Finney',     <?php echo $stats['Finney']['Black']['nbr_of_win']; ?>],
                ['Corentin',      <?php echo $stats['Corentin']['White']['nbr_of_win']; ?>]
                ]);

                var options = {
                title: '',
                is3D: true,
                colors: ['#585858', '#f3ebeb'],
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart-1'));

                chart.draw(data, options);
            }
        </script>
    </div>
    <br>
    <div class="row header_chess">
        <div class="col-xs-6 col-md-6">
            <div class="col-xs-8">Corentin - <?php echo $stats['Corentin']['Black']['nbr_of_win']; ?></div>
            <div class="col-xs-4"><img src="img/Black_king.png" width="100%" ></div>
        </div>
        <div class="col-xs-6 col-md-6">
            <div class="col-xs-8">Finney -<?php echo $stats['Finney']['White']['nbr_of_win']; ?></div>
            <div class="col-xs-4"><img src="img/White_king.png" width="100%" ></div>
        </div>
    </div>
    <!--<div class="row row_chess">
        <div class="col-sm-4 ">number of win</div>
        <div class="col-sm-4 "><?php echo $stats['Corentin']['White']['nbr_of_win']; ?></div>
        <div class="col-sm-4 "><?php echo $stats['Finney']['Black']['nbr_of_win']; ?></div>
    </div>
    <div class="row row_chess">
        <div class="col-sm-4 ">win percentage</div>
        <div class="col-sm-4 "><?php echo number_format($stats['Corentin']['White']['win_percentage']*100,2); ?>%</div>
        <div class="col-sm-4 "><?php echo number_format($stats['Finney']['Black']['win_percentage']*100,2); ?>%</div>
    </div>-->
    <div class="row row_chess">
        <div class="col-sm-4 ">days since last win</div>
        <div class="col-sm-4 "><?php echo $stats['Corentin']['Black']['day_since_last_win']; ?></div>
        <div class="col-sm-4 "><?php echo $stats['Finney']['White']['day_since_last_win']; ?></div>
    </div>
    <div class="row row_chess">
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <div id="piechart-2" style="width: 100%;"></div>
        <script>
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart2);

            function drawChart2() {

                var data = google.visualization.arrayToDataTable([
                ['Players', 'Count'],
                ['Finney',     <?php echo $stats['Finney']['White']['nbr_of_win']; ?>],
                ['Corentin',      <?php echo $stats['Corentin']['Black']['nbr_of_win']; ?>]
                ]);

                var options = {
                title: '',
                is3D: true,
                colors: ['#f3ebeb', '#585858'],
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart-2'));

                chart.draw(data, options);
            }
        </script>
    </div>
    

    <?php
}


?>
<style>
    .header_chess{ 
        padding:5px;
        border:1px solid #c5c5c5; 
        border-radius: 5px;
        text-align: center;
        background-color: #a4c4ff;
    }

    .row_chess{
        padding:5px;
        border:1px solid #c5c5c5; 
        border-radius: 5px;
        text-align: center;
    }

</style>
