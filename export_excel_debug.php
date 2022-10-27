<?php 
session_start();
include ('function_export.php');
include ('function_framework.php');
include ('function');
include ('dbconnection.php');
manage_post_export($db);



?>