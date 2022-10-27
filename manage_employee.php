
<?php 
$page_title='Manage Employee';
include ('header.php'); 
redirect_if_not_logged_in();
?>


<div class="container">

	
	<?php include ('navbar.php'); ?>
	
  

  
  
  
  
  
  <div class="row">
  <div class="col-sm-9">
	
	<?php 
	//show ($_POST);
	if (!empty($_POST['type'])and $_POST['type']=='Add a new employee'){
		//show($_POST);
		saveemployee($db);
	}
	if (!empty($_GET['id'])){
		deleteemployee($db);
	}
	if (!empty($_POST['type'])and $_POST['type']=='modify'){
		modifyemployee($db);
	}
	
	
	
	if (!empty($_GET['add'])&&$_GET['add']=='employee'){
		
		echo '<h3>Add User</h3>';
		echo '<form action="manage_employee.php" method="POST">';
			echo "<div class=\"row\">";
			echo '<input type="text"  class="form-control" name="firstname" placeholder="First Name"><br>';
			echo"</div>";
			echo "<div class=\"row\">";
			echo '<input type="text"  class="form-control" name="lastname" placeholder="Last Name"><br>';
			echo"</div>";
			echo "<div class=\"row\">";
			echo '<input type="text"  class="form-control" name="employeepassword" placeholder="Password" maxlength="25"><br>';
			echo"</div>";
			echo "<div class=\"row\">";
			echo '<input type="text"  class="form-control" name="employeeemail" placeholder="Email Adress" maxlength="100"><br>';
			echo"</div>";
			echo "<div class=\"row\">";
			echo '<center>Is admin ? :  <input type="radio" id="Yes" name="admin" value="True">
				  <label for="Yes">Yes</label>
				  <input type="radio" id="No" name="admin" value="FALSE" checked >
				  <label for="No">No</label> </input></center><br>';
			echo"</div>";
			echo "<div class=\"row\">";
			echo '<input type="submit" name="type" value="Add a new employee" class="btn btn-primary form-control" ><br>';
			echo"</div>";
		echo '</form>';
		
		
	}
		
	
	else
	{
		echo '<h3>Users List</h3>';
		$listeemployee=listemployee($db); 
			
			//show($listeemployee);
			
			foreach ($listeemployee as &$employee){
					echo"<div class=\"row\">";
						echo'<form  method="POST">';
						echo '<input  type="hidden"  name="employee_code"  placeholder="Code" value="'.$employee['employee_code'].'">';
						echo'<div class="col-sm-2">';
						echo '<input  type="text"  class="form-control" name="employee_name" style="text-align:center;" placeholder="Name" value="'.$employee['employee_name'].'" >';
						
						echo"</div>";
						echo'<div class="col-sm-2">';
						echo '<input  type="text"  class="form-control" name="employee_lastname" style="text-align:center;" placeholder="Last Name" value="'.$employee['employee_lastname'].'" >';
						
						echo"</div>";
						echo'<div class="col-sm-3">';
						echo '<input  type="text"  class="form-control" name="employee_email" style="text-align:center;" placeholder="Email Adress" value="'.$employee['employee_email'].'" >';
						
						echo"</div>";
						echo'<div class="col-sm-2">';
						
					// 	echo '<input type="radio" id="Yes" name="admin" value="True"  ';
					// 	if ( $employee['employee_admin']==1){echo 'checked';}
					// 	echo'> Admin
					 
					//   <input type="radio" id="No" name="admin" value="FALSE" ';
					//   if ( $employee['employee_admin']<>1){echo 'checked';}
					// echo'> Not
					//   <br>';
						
						
						//if ( $employee['employee_admin']==1){echo 'admin';}
						echo"</div>";
						echo'<div class="col-sm-2">';
						
						
						echo '<button type="submit" name="type" value="modify" title="Save Changes" onclick="return confirm(\'Are you sure to modifiy '.$Operator['operator_fullname'].'?\')" class="btn btn-default" aria-label="Left Align"><span class="glyphicon glyphicon-floppy-disk"></span></button>' ;
						
						echo'<a href="manage_employee.php?id='.$employee['employee_code'].'" onclick="return confirm(\'Are you sure to delete '.$employee['employee_fullname'].'?\')" >
						<button type="button" class="btn btn-default" aria-label="Left Align">
						<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
						</button></a>';
						echo"</div>";
						echo '</form>';
						//floppy-disk
					echo"</div>";
					}	
	}
	
	
	
	
	?>
     



	
	   
    </div>
	
	<div class="col-sm-3">
	<h3>Management</h3>
     <a href="manage_employee.php"  ><button type="button" class="btn btn-primary">View users</button></a><br><br>
	 <a href="manage_employee.php?add=employee"  ><button type="button" class="btn btn-primary">Add user</button></a><br><br>
	 
	  
    </div>
</div>

 