

<?php 
$page_title='Tools';
include ('header.php'); 
redirect_if_not_logged_in();
?>


<div class="container">

	
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	
	<?php
	 
	// show($_POST);
	
	echo '<div class="row"  >';
		echo '<div class="col-sm-4">';
			search_job_details($db);
			echo'<br>';
			check_MIS_Material_issue($db);
		echo '</div>';
		echo '<div class="col-sm-4">';
			transfer_job($db);
			echo'<br>';
			MIS_product_check($db);
		echo '</div>';
		echo '<div class="col-sm-4"  >';
			operator_name_check($db);
		echo '</div>';
		
	echo '</div>';
	echo '<div class="row"  >';
		echo '<div class="col-sm-4">';
			
		echo '</div>';
		echo '<div class="col-sm-4">';
			
		echo '</div>';
		echo '<div class="col-sm-4">';
			
		echo '</div>';
		
	echo '</div>';
	


	function check_MIS_Material_issue($db){
		$query='SELECT [ManufactureIssueNumber]
		,[TransactionDate]
		,[Code]
	FROM [barcode].[dbo].[List_MIS_with_Material_Issue]
	order by TransactionDate desc';
	
    
    $sql = $db->prepare($query); 
    $sql->execute();
    
    $allMIS=$sql->fetchall();
	echo '<link rel="stylesheet" href="css/checkstock.css?v='.time().'">';
	echo'<div class="row header-check">';
        echo'<div class="col-sm-12" style="text-align: center;">MIS With Material Issue</div>';
        echo'<div class="col-sm-4" style="text-align: center;">MIS</div>';
        echo'<div class="col-sm-4" style="text-align: center;">Code</div>';
		echo'<div class="col-sm-4" style="text-align: center;">Date</div>';
        
    echo'</div>';
	foreach ($allMIS as $MIS){
        echo'<div class="row row_check" >';
        	echo'<div class="col-sm-4" style="text-align: center;" >'.$MIS['ManufactureIssueNumber'].'</div>';
            echo'<div class="col-sm-4" style="text-align: center;" >'.$MIS['Code'].'</div>';
            echo'<div class="col-sm-4" style="text-align: center;" >'.$MIS['TransactionDate'].'</div>';

         
        echo'</div>';
    }
	}
	
	?>
	
	
	
	
	<?php
	
	
	
	?>
</div>
