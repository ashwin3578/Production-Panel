<!DOCTYPE html>


<?php 
$page_title='Scanning';
$page = $_SERVER['PHP_SELF'];
$sec = "120";
?><head>
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    </head>
<?php
include ('header.php'); ?>




<script>
window.onload = function() {
  document.getElementById("barcode").focus();
};

</script>

<style>
.counter{
	font-size: 50px;
	text-align: center;
	margin: auto;
	top: 50%;
    
}
.active-user{
	font-size: 30px;
	text-align: center;
	font-weight: bold;
}

</style>




<?php 
include ('function_scanner.php');

manage_POST_scanning($db);
//show($_POST);

?>
<div class="container" style="padding-top:20px;">
	<div class="col-xs-4">
		<?php showscanner($db);	?>
	</div>
	<div class="col-xs-1"><br></div>
	<div class="col-xs-3" style="border:1px solid black; border-radius: 25px; padding:5px;">
		<?php showdetails_operator($db);?>
	</div>
	<div class="col-xs-1"><br></div>
	<div class="col-xs-3" style="border:1px solid black; border-radius: 25px; padding:5px;">
		<?php show_schedule_operator($db);?>
	</div>

</div>

<?php

function show_schedule_operator($db){
	$date=(date('Y-m-d',time()));
	?>
	<div class="row" style="min-height: 30em;">
		<div class="row"><center><h2>Schedule :</h2></center></div>
		<div class="row">
			<?php $i=0;$last_hours=0;
			foreach(get_schedule($db,$_SESSION['temp']['operator']) as $schedule){ 
				
				$end_hour=$schedule['schedule_hour_start']+$schedule['schedule_duration'];
				if($last_hours<>$schedule['schedule_hour_start'] and $i>0){?>
					<br><div class="col-sm-12 scanner-sidepanel break-panel"><?php echo($schedule['schedule_hour_start']-$last_hours)?> hours</div><br><br>
					<?php
				
				}
				?>
				<?php $document=get_one_doc($db,$schedule['schedule_productcode']);
					$filepath="ressource_v2/MD&S/".$document['document_filename'];?>
				<div class="  scanner-sidepanel "><?php if(empty($document['document_filename'])){echo'<br>';}?>
					<?php if($schedule['schedule_productcode']<>'blank'){echo $schedule['schedule_productcode'];}?><br>
					<?php if($schedule['schedule_productcode']=='blank' and empty($schedule['schedule_notes'])){echo $schedule['schedule_productcode'];}?><br>
					<?php if(!empty($schedule['schedule_notes'])){echo '<i>'.$schedule['schedule_notes'].'</i><br>';}?>
					<?php //echo ($schedule['schedule_hour_start']+$schedule['schedule_duration'])?>
					
						<?php if(!empty($document['document_filename'])){?>
						<iframe class="scrollDiv" src="<?php echo $filepath?>"  style="width:50%;border:none;overflow:hidden;" ></iframe><?php
						}else{echo'<br>';}?>
				</div>
				
				<?php
				
				$last_hours=$schedule['schedule_hour_start']+$schedule['schedule_duration'];
				$i++;
				
			}
			
			
			?>
		</div>
		
	</div>
<?php
}
function get_schedule($db,$operator){
	$date=date('Y-m-d',time());
	$query="SELECT TOP (1000) [schedule_id]
    ,[schedule_operatorcode]
    ,[schedule_productcode]
    ,[schedule_date]
    ,[schedule_hour_start]
    ,[schedule_duration],
    schedule_notes,
    WorkArea
    FROM [barcode].[dbo].[schedule]
    left join List_Document on Product_Code=schedule_productcode
	left join operator on operator_fullname=schedule_operatorcode
    where schedule_date='$date' and operator_code='$operator'
	ORDER BY schedule_hour_start ASC";
     $sql = $db->prepare($query); 
     $sql->execute();
     $row=$sql->fetchall();
     //show($query);
     
    return $row;
}
function get_one_doc($db,$Product_code){
    $query="SELECT TOP 1 [document_id]
    ,[document_name]
    ,[document_type]
    ,[document_number]
    ,[document_issue]
    ,[document_date_issue]
    ,[document_date_added]
    ,[document_added_by]
    ,[document_timetag_added_by]
    ,[document_filename]
    ,[document_upload]
    ,[document_active]
    FROM document 
    LEFT JOIN doc_link on [document_number]=[doclink_docnumber]
    WHERE document_type='MD&S' and document_active=1 and doclink_productcode like '$Product_code'
    order by document_upload,CAST(document_number AS int) DESC";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $row=$sql->fetch();
      
  return $row;
}

?>

