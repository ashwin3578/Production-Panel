<?php
session_start();
include ('dbconnection.php');
include ('function.php');
include ('function_metrology.php');

$date1=(date('Y-m-d',time()));	
$date2=date('Y-m-d',strtotime($date1.' -6 days'));
weekly_mail_metro($db,$date1,$date2)

	
?>	
	
	