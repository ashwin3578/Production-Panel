
<?php 
$page_title='Barcode Management';
include ('header.php'); ?>


<?php
  
 
  ?>




<div class="container">

	<?php include ('applyfilter.php'); ?>
	<?php include ('navbar.php'); ?>
  
  
  <?php
   
  //show($_POST);
  
  if(!empty($_POST['Employee'])){
	  
	  //show($_POST);
	   $_SESSION = array();
		if(checkpassword($db,$_POST['Employee'],$_POST['Password'])==true){
			 $_SESSION['temp']['id']=$_POST['Employee'];
			 isadmin($db,$_POST['Employee']);
			 //show($_SESSION['temp']);
			 recorded_time_connection($db,$_POST['Employee']);
			 load_role($db,$_SESSION['temp']['id']);
			 header('Location:index.php');
		}
		else{
			echo'<div class="container"><div class="row " ><div class="alert alert-danger" role="alert" >
				  Wrong Password!!
				</div></div></div>';
		}
	  
	  
	  
	 
  }
    if(!empty($_POST['sign-out'])){
	  
	  
	  $_SESSION = array();
	  
	  
	  $_SESSION['temp']['id']='';
	   header('Location:index.php');
  }
  
  
 
  
  
  if(empty($_SESSION['temp']['id'])){
	  echo'<div class="row">
		  <div class="col-sm-2 col-md-3 col-lg-5">
		  </div>
		  <div class="col-xs-12 col-sm-8 col-md-4 col-lg-2">
			<h3>Sign-in</h3>
			<br>Employee : 
			<form action="connection.php" method="post">';
	  
	  $listeemployee=listemployee($db); 
	  $lastlogin=last_login_machine($db);	
	  //show ($lastlogin);
			//show($listeemployee);
		echo '<select name="Employee" class="form-control" id="employee">';	
			foreach ($listeemployee as &$employee){
					echo '<option ';
					if($lastlogin==$employee['employee_code']){echo 'selected';}
					echo' value="'.$employee['employee_code'].'">'.$employee['employee_name'].' '.$employee['employee_lastname'].'</option> ';
			}
	  
	  echo' </select>';
		
		
       
     
	  
	  
	  echo '<br>
	  Password : 
      <input type="password" class="form-control" id="Password" name="Password" value=""><br>
	  
	  <input type="submit"  class="btn btn-primary form-control" value="Sign-in"><br>
	 </form> 
    </div>';
	  
  }
  else
  {
	  echo'<div class="row">
		  <div class="col-sm-5">
		  </div>
		  <div class="col-sm-2">
			<form action="connection.php" method="post">
			<h3>Sign-out</h3>
			';
	  
		  
	  
	  echo '
	  
	  <input type="submit" name="sign-out" class="btn btn-primary form-control" value="Sign-out"><br>
	 </form> 
    </div>';
  }
  
  
  ?>
  
  
  
  
  
	
	  
	
	
  </div>
 
  
</div>

 