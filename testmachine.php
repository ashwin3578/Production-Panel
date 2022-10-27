

<?php 

include ('dbconnection.php');
include ('function.php');
include ('function_framework.php');

if(!empty($_POST['Data'])){
    $query="INSERT INTO temptable
        (temptable_timetag,
        temptable_MAC,
        temptable_entry)
        VALUES
        (
        '".time()."',
        '".$_POST['MAC']."',
        '".$_POST['Data']."'
        
        )
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
}

   
?>
	
	



