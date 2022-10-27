<?php 
$page_title='Production Control Panel';
$page = $_SERVER['PHP_SELF'];
$sec = "3000";
?>
<head>
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
</head>

<?php include ('header.php');?>

<div class="container">
<link rel="stylesheet" href="css/issue_log.css">	
<link rel="stylesheet" href="css/roster.css">

	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	<?php include ('function_framework.php'); ?>
    <?php $class_card='card col-xs-8  col-sm-5 col-md-2 box-shadow';?>
	<div class="row card-container">
        <a href="live_scan.php">
            <div class="<?php echo $class_card?>">
                <div class="card-header">Live View Scans</div>
                <div class="card-body">
                    <?php $livedetails=get_details_open($db);echo $livedetails['Count_Operator']?> Operators
                    <img class="card-img" src="img/live-view.jpg">
                </div>
            </div>
        </a>
        <a href="factory.php">
            <div class="<?php echo $class_card?>">
                <div class="card-header">Live View Factory</div>
                <div class="card-body">
                    <img class="card-img" src="img/factory-view.jpg">
                </div>
            </div>
        </a>
        <a href="production-plan.php">
            <div class="<?php echo $class_card?>">
                <div class="card-header">Production Plan</div>
                <div class="card-body">
                    <img class="card-img" src="img/prodplan-view.jpg">
                </div>
            </div>
        </a>
        <a href="roster.php">
            <div class="<?php echo $class_card?>">
                <div class="card-header">Labour Allocation</div>
                <div class="card-body">
                <img class="card-img" src="img/labour-view.jpg">
                </div>
            </div>
        </a>
        <a href="prod-issue-log.php">
            <div class="<?php echo $class_card?>">
                <div class="card-header">Production Issue</div>
                <div class="card-body">
                    <?php echo count_active_issue($db,'').' Issues Open' ?> 
                    <?php if(!empty($_SESSION['temp']['id'])){echo '<br>'.count_active_issue($db,$_SESSION['temp']['id']).' Issues Open Assigned to you';} ?> 
                    <img class="card-img" src="img/PIL-view.jpg">
                </div>
            </div>
        </a>
        <a href="metrology.php">
            <div class="<?php echo $class_card?>">
                <div class="card-header">Metrology</div>
                <div class="card-body">
                <?php echo number_format(count_test_done_today_metro($db)).' Tests done today' ?> 
                <img class="card-img" src="img/metro-view.jpg"> 
                </div>
            </div>
        </a>
        <a href="injury.php">
            <div class="<?php echo $class_card?>">
                <div class="card-header">Report Injury</div>
                <div class="card-body">
                <?php echo number_format(days_since_last_injury_menu($db)).' days since last Injury' ?> 
                <img class="card-img" src="img/injury-view.jpg"> 
                </div>
            </div>
        </a>
        <?php //if(!empty($_SESSION['temp']['role_schedule_admin'])){?>
            <a href="schedule.php">
                <div class="<?php echo $class_card?>">
                    <div class="card-header">Operator Schedule</div>
                    <div class="card-body">
                        <img class="card-img" src="img/schedule-view.jpg">
                    </div>
                </div>
            </a>
        <?php //} ?>
        <?php if($_SESSION['temp']['id']=='FinneyKessler' or $_SESSION['temp']['id']=='CorentinHillion1'){?>
            <?php 
             $query='SELECT count(chess_id)as victories,chess_winner
             FROM chess
             group by chess_winner';
         
            $sql = $db->prepare($query); 
            //show($query);
            $sql->execute();
        
            $allresult=$sql->fetchall();
            //show($allresult);
            $caption=$allresult[0]['chess_winner'].' '.$allresult[0]['victories'].'-'.$allresult[1]['victories'].' '.$allresult[1]['chess_winner']
            ?>
            <a href="chess.php">
                <div class="<?php echo $class_card?>">
                    <div class="card-header">Chess</div>
                    <div class="card-body">
                    <?php echo $caption ?> 
                    <img class="card-img" src="img/chess.jpg"> 
                    </div>
                </div>
            </a>
        <?php } ?>
    </div>
   
</div>	

<style>
    .card-img{
        max-width:100%;
        max-height:100%;
        border-radius:3rem;
        /*border:1px solid #111111;*/
    }
    .card-container{
        /*padding: 1.25rem;*/
    }
    .card{
        color: black;
        /*width:20%;*/
        margin:2%;
        float: left;
        border:1px solid black;
        border-radius:3rem  ;
        text-align:center;
        padding: 1.25rem;
    }
    .card-header {
        padding:1.25rem;
        border-radius:5px;
        
        background-color: rgba(0,0,0,.03);
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    
    .card-body{
        padding: 1.25rem;
        height:15rem;
    }
    .box-shadow {
        box-shadow: 5px 10px 10px;
    }
</style>