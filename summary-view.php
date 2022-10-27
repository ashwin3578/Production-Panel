

<?php  $time[] = microtime(true);
$page_title='Summary';
include ('header.php');
redirect_if_not_logged_in();
 ?>


<div class="container">

	
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); 
    include ('function_dashboard2.php'); 
    include ('function_scanning_v2.php'); 
    echo '<link rel="stylesheet" href="css/summary.css?v='.time().'">';
	edit_scan_procedure($db);
	managing_POST_scan_summary($db);
	
	//show($_POST);
	$filter=all_filter_list_2();
	
	echo'<form id="form-title" method="post">';
	 echo'<div class="row"  >';
		echo'<Center><h3 >All Scan - Summary </h3></Center>';
		
	echo'</div>';
	echo'</form>';
	echo'<div class="row" ><small>';
        echo'<div class="col-sm-3">';
            echo'<div class="col-sm-6">';
                echo'<div class="row" >';
                
                showmonth($db);
                $time[] = microtime(true);
                echo'</div>';
            echo'</div>';
            echo'<div class="col-sm-6" >';
                echo'<div class="row"  >';
                showdays($db);
                $time[] = microtime(true);
                echo'</div>';
            echo'</div>';
        echo'</div>';
		echo'<div class="col-sm-2">';
			echo'<div class="row" >';
			    showworkarea($db);
                $time[] = microtime(true);
			echo'</div><br>';
			
			echo'<div class="row" >';
                showoperator($db);
                $time[] = microtime(true);
				
			echo'</div>';
		echo'</div>';
        echo'<div  id="Operator_show" class="Operator_show">';
            summary_show_operator_detail($db);
        echo'</div>';
		
	 echo'</small></div>';
	// show($_POST);
    $time[] = microtime(true); // Bottom of page
    //showtimes($time);
    //show($_POST);
    //show($_SESSION['temp']);
	?>
</div>
