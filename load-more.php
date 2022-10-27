<?php
include ('function.php');
include ('dbconnection.php');
//$output=$_POST['countAjaxHit'].'<br>';


if(!empty($_POST["id"])){
    show($_POST);
    // Include the database configuration file
    include 'dbConfig.php';
    
    // Count all records except already displayed
    
    $query = "SELECT COUNT(*) as num_rows FROM asset WHERE asset_id < ".$_POST['id']." ORDER BY asset_id DESC";
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();
    
    $totalRowCount = $row['num_rows'];
    
    $showLimit = 5;
    
    // Get records from the database
    $query='SELECT *  FROM asset order by asset_id offset '.$_POST["id"].' ROWS
	Fetch Next 5 ROWS ONLY';
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

    $row=$sql->fetchall();
	//
	foreach($row as &$line){
		
		echo'<div class="list_item"> '.$line['asset_id'].' - '.$line['asset_name'].'</div>';
		$postID = $line['asset_id'];
	}
	echo'</div>';

	echo '<div class="show_more_main" id="show_more_main'.$postID.'">';
	echo '<span id="'.$postID.'" class="show_more" title="Load more posts">Show more</span>';
	echo '<span class="loding" style="display: none;"><span class="loding_txt">Loading...</span></span>';
    echo '</div>';
}








?>