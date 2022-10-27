

<?php 
session_start();
include ('dbconnection.php');
include ('function.php');
include ('function_framework.php');



include ('function_doc_management.php');
//show($_POST);

manage_post_doc_management($db);
	
	
	
	
	


?>