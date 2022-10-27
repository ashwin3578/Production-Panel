

<?php 
$page_title='Password Change';
$title_top='Password Change';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/issue_log.css">	
	
	<?php include ('navbar.php'); ?>
	
	<?php
	//show($_POST);

    if($_POST['type']=='save_password'){
        change_password($db);
    }



	echo'<form method="POST">';
	echo'<div class="row">';
        echo'<div class="col-sm-4">';
        echo'</div>';
		echo'<div class="col-sm-4">';
			
			echo'<div class="col-sm-12">';
			echo' <input class="form-control btn btn-default" type="password" required  name="old_password" placeholder="Old Password">';
			echo'</div>';
			
			echo'<div class="col-sm-12">';
			echo' <input class="form-control btn btn-default" type="password" required  name="new_password" placeholder="New Password">';
			echo'</div>';
			
			echo'<div class="col-sm-12">';
			echo' <input class="form-control btn btn-default" type="password"  required name="confirm_password" placeholder="Confirm New Password">';
			echo'</div>';
			
			echo'<div class="col-sm-12">';
			echo'<button type="submit" name="type" value="save_password" class="btn btn-default form-control" >
            Save New Password
            </button>';
			echo'</div>';
		echo'</div>';
	echo'</div>';
	echo'</form>';


    function change_password($db){
        if ($_POST['confirm_password']<>$_POST['new_password']){
            echo'<div class="alert alert-danger" role="alert">
            The confirm and new password don\'t match
            </div>';
            return;
        }
        if (checkpassword($db,$_SESSION['temp']['id'],$_POST['old_password'])==false){
            echo'<div class="alert alert-danger" role="alert">
            The old password doesn\'t match
            </div>';
            return;
        }
        $username=$_SESSION['temp']['id'];
        $password=$_POST['new_password'];

        $query='UPDATE dbo.employee 
        SET 
        employee_password=\''.$password.'\'
        WHERE 
        employee_code=\''.$username.'\'
        ';
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();




        echo'<div class="alert alert-success" role="alert">
            Password changed
            </div>';



    }







	?>
	


	
	
	
</div>
