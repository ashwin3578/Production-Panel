<?php
session_start();
include ('dbconnection.php');
include ('function.php');
include('function_framework.php');
include('function_machine2.php');

//import_temptable($db);
//import_temptable_v2($db);

if(get_setting('script_create_cycle')==1){
    import_v2_multiple($db,30);
}


?>