<?php 

include ('dbconnection.php');
include ('function.php');
include ('function_framework.php');

if(!empty($_POST['Data'])){
    
    
    $all_packet=explode("x",$_POST['Data']);
    $removed = array_pop($all_packet);
    //show($all_packet);
    foreach($all_packet as $packet){
        $explode_packet=explode(";",$packet);
        $removed = array_pop($explode_packet);
        //show($explode_packet);
        foreach($explode_packet as $details_packet){
            $details=explode(",",$details_packet);
            //show($details);
            $timetag=$details[0];
            $value=$details[1];
            $all_sql_insert[]="(
                ' $timetag',
                '$value'
                )";
        }
    }
    $i=0;
    //show($all_sql_insert);
    foreach($all_sql_insert as $sql_insert){
           if($i==0){
            $insert=$sql_insert;
        }else{
            $insert=$insert.','.$sql_insert;
        }
        $i++;
    }

    $query="INSERT INTO torque
            (torque_timetag,
            torque_read)
            VALUES
            $insert
            ";
    
    $sql = $db->prepare($query); 
    show($query);
    $sql->execute();
    
    echo'ok';

}else{
    show($_POST);
    
    echo'<form method="POST" ">';
    //echo'<input type="hidden" name="hello" value="test">';
    //echo'<input type="submit" name="test" value="1">';
   
    echo'</form>';
    //sleep(4);
 }
  
?>