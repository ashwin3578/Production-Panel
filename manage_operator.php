
<?php 
$page_title='Manage Operator';
include ('header.php'); 
include ('function_framework.php'); 
redirect_if_not_logged_in();
show_debug();
?>


<div class="container">

	
	<?php include ('navbar.php'); ?>
	
  
  
  
  <div class="row">
  <div class="col-sm-9">
	
	<?php 
	//show($_POST);
	if (!empty($_POST['operatorcode'])){
		saveoperator($db);
	}
	if (!empty($_GET['id'])){
		deleteoperator($db);
	}
	if (!empty($_POST['addworkarea'])){
		saveworkarea($db);
	}
	if (!empty($_GET['delete'])){
		deleteworkarea($db);
	}
	if (!empty($_POST['old_operator_code'])){
		if(empty($_POST['active'])){$active=0;}else{$active=1;}
		if (empty($_POST['operator_code'])){$_POST['operator_code']=$_POST['old_operator_code'];}
		modify_operator($db,$_POST['old_operator_code'],$_POST['operator_code'],$_POST['operator_name'],$_POST['operator_lastname'],$_POST['workarea'],$active);
	}
	
	
	if (!empty($_GET['add'])&&$_GET['add']=='operator'){
		
		echo '<h3>Add Operator</h3>';
		echo '<form action="manage_operator.php" method="POST">';
			echo "<div class=\"row\">";
			echo '<input type="text"  class="form-control" name="firstname" placeholder="Operator First Name"><br>';
			echo"</div>";
			echo "<div class=\"row\">";
			echo '<input type="text"  class="form-control" name="lastname" placeholder="Operator Last Name"><br>';
			echo"</div>";
			echo "<div class=\"row\">";
			echo '<input type="text"  class="form-control" name="operatorcode" placeholder="Operator Code" maxlength="3"><br>';
			echo"</div>";
			echo "<div class=\"row\">";
				echo '<select name="workarea" class="form-control">';
				
				$listWorkarea=listworkarea($db);
						
				foreach ($listWorkarea as &$workarea){
						echo"<option>".$workarea['workarea']."</option>";
						}
				echo '</select>';
			echo '<br>';
			echo"</div>";
			echo "<div class=\"row\">";
			echo '<input type="submit"  class="btn btn-primary form-control" ><br>';
			echo"</div>";
		echo '</form>';
		
		
	}
	elseif (!empty($_GET['add'])&&$_GET['add']=='workarea'){
		
		echo '<h3>Add workarea</h3>';
		echo '<form action="manage_operator.php" method="POST">';
			echo "<div class=\"row\">";
			echo '<input type="text"  class="form-control" name="addworkarea" placeholder="WorkArea Name"><br>';
			echo"</div>";
			
			echo "<div class=\"row\">";
			echo '<input type="submit"  class="btn btn-primary form-control" ><br>';
			echo"</div>";
		echo '</form>';
		
		
	}
	elseif (!empty($_GET['view'])&&$_GET['view']=='workarea'){
		
		echo '<h3>Workarea List</h3>';
		$listworkarea=listworkarea($db);
			foreach ($listworkarea as &$workarea){
			echo"<div class=\"row\">";
				echo'<div class="col-sm-2">';
					echo $workarea['workarea'] ;
				echo"</div>";
				echo'<div class="col-sm-1">';
					echo'<a href="manage_operator.php?delete='.$workarea['workarea'].'" onclick="return confirm(\'Are you sure to delete '.$workarea['workarea'].'?\')" >
					<button type="button" class="btn btn-default" aria-label="Left Align">
					<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
					</button></a>';
				echo"</div>";
			echo"</div>";
	
			}
		
		
	}
	
	
	
	else
	{	

		if (!empty($_GET['sort'])){
				if($_GET['sort']=='Code'){
					if($_SESSION['temp']['sort_Operator']=='operator_code ASC'){
						$_SESSION['temp']['sort_Operator']='operator_code DESC';
					}
					else{
						$_SESSION['temp']['sort_Operator']='operator_code ASC';
					}
				}
				if($_GET['sort']=='Name'){
					if($_SESSION['temp']['sort_Operator']=='operator_name ASC'){
						$_SESSION['temp']['sort_Operator']='operator_name DESC';
					}
					else{
						$_SESSION['temp']['sort_Operator']='operator_name ASC';
					}
				}
				if($_GET['sort']=='LastName'){
					if($_SESSION['temp']['sort_Operator']=='operator_lastname ASC'){
						$_SESSION['temp']['sort_Operator']='operator_lastname DESC';
					}
					else{
						$_SESSION['temp']['sort_Operator']='operator_lastname ASC';
					}
				}
				if($_GET['sort']=='Workarea'){
					if($_SESSION['temp']['sort_Operator']=='operator_workarea ASC'){
						$_SESSION['temp']['sort_Operator']='operator_workarea DESC';
					}
					else{
						$_SESSION['temp']['sort_Operator']='operator_workarea ASC';
					}
				}
				if($_GET['sort']=='Active'){
					if($_SESSION['temp']['filter_Operator']=='AND operator_active=1 '){
						$_SESSION['temp']['filter_Operator']='AND operator_active=0 ';
					}
					else{
						$_SESSION['temp']['filter_Operator']='AND operator_active=1 ';
					}
				}
				if($_GET['sort']=='Scan'){
					if($_SESSION['temp']['sort_Operator']=='total_count ASC '){
						$_SESSION['temp']['sort_Operator']='total_count DESC ';
					}
					else{
						$_SESSION['temp']['sort_Operator']='total_count ASC ';
					}
				}
				if($_GET['sort']=='Last'){
					if($_SESSION['temp']['sort_Operator']=='max_date ASC '){
						$_SESSION['temp']['sort_Operator']='max_date DESC ';
					}
					else{
						$_SESSION['temp']['sort_Operator']='max_date ASC ';
					}
				}
		}

		echo '<h3>Operator List</h3>';
		
		echo"<div class=\"row\"><b><center>";
			echo'<div class="col-sm-1">';
			echo'Code ';
			echo'<a href="manage_operator.php?sort=Code" ><span class="glyphicon glyphicon-sort" aria-hidden="true"></span></a>';
			echo"</div>";
			echo'<div class="col-sm-2">';
			echo'First Name ';
			echo'<a href="manage_operator.php?sort=Name" ><span class="glyphicon glyphicon-sort" aria-hidden="true"></span></a>';
			echo"</div>";
			echo'<div class="col-sm-2">';
			echo'Last Name ';
			echo'<a href="manage_operator.php?sort=LastName" ><span class="glyphicon glyphicon-sort" aria-hidden="true"></span></a>';
			echo"</div>";
			echo'<div class="col-sm-2">';
			echo'Work Area ';
			echo'<a href="manage_operator.php?sort=Workarea" ><span class="glyphicon glyphicon-sort" aria-hidden="true"></span></a>';
			echo"</div>";
			echo'<div class="col-sm-1">';
			echo'Active ';
			echo'<a href="manage_operator.php?sort=Active" ><span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span></a>';
			echo"</div>";
			echo'<div class="col-sm-1">';
			echo'Scans ';
			echo'<a href="manage_operator.php?sort=Scan" ><span class="glyphicon glyphicon-sort" aria-hidden="true"></span></a>';
			echo"</div>";
			echo'<div class="col-sm-1">';
			echo'Last ';
			echo'<a href="manage_operator.php?sort=Last" ><span class="glyphicon glyphicon-sort" aria-hidden="true"></span></a>';
			echo"</div>";
			echo'<div class="col-sm-2">';
			echo'Save/Print/Delete';
			echo"</div>";
		
		
		echo"</center></b></div>";
		$listeoperator=listoperator($db,'All',1); 
			
			//show($listeoperator);
			
			foreach ($listeoperator as &$Operator){
					echo"<div class=\"row\"><center>";
						echo'<form  action="manage_operator.php" method="POST">';
						echo'<div class="col-sm-1">';
						echo '<input  type="hidden"  class="form-control" name="old_operator_code" style="text-align:center;" placeholder="Code" value="'.$Operator['operator_code'].'">';
						echo '<input  type="text"  class="form-control" name="operator_code" maxlength="3" style="text-align:center;" placeholder="Code" value="'.$Operator['operator_code'].'" ';
							if($Operator['total_count']>0){echo 'disabled';}
						echo'>';
						
						echo"</div>";
						echo'<div class="col-sm-2">';
						echo '<input  type="text"  class="form-control" name="operator_name" style="text-align:center;" placeholder="First Name" value="'.$Operator['operator_name'].'">';
						
						echo"</div>";
						echo'<div class="col-sm-2">';
						echo '<input  type="text"  class="form-control" name="operator_lastname" style="text-align:center;" placeholder="Last Name" value="'.$Operator['operator_lastname'].'">';
						
						echo"</div>";
						echo'<div class="col-sm-2">';
						
							echo '<select name="workarea" onChange="this.form.submit()" class="form-control" style="text-align:center;">';
					
							$listWorkarea=listworkarea($db);
									
							foreach ($listWorkarea as &$workarea){
									echo"<option ";
									if($workarea['workarea']==$Operator['operator_workarea']){echo ' selected';}
									echo' style="text-align:center;" >'.$workarea['workarea']."</option>";
									}
							echo '</select>';
						
						echo"</div>";
						echo'<div class="col-sm-1">';
						
						echo '<input  type="checkbox"  class="form-check" name="active" onChange="this.form.submit()" id="active"    ';
						if($Operator['operator_active']==1){echo 'checked value=1';}
						echo' >';
						echo"</div>";
						echo'<div class="col-sm-1">';
						
						//$detail=count_scan_per_operator($db,$Operator['operator_code']);
						echo $Operator['total_count'];
						echo"</div>";
						echo'<div class="col-sm-1">';
						
						$lastscandate=new DateTime($Operator['max_date']);
						$now=new DateTime;
						$interval = $lastscandate->diff($now);
						echo $interval->format('%a days ago');
						//echo $detail['max_date'];
						
						
						echo"</div>";
						
						
						
						
						echo'<div class="col-sm-2" >';
						echo '<button type="submit" title="Save Changes" onclick="return confirm(\'Are you sure to modifiy '.$Operator['operator_fullname'].'?\')" class="btn btn-default" aria-label="Left Align"><span class="glyphicon glyphicon-floppy-disk"></span></button>' ;
						//echo"</div>";
						//echo'<div class="col-sm-1" >';
						echo '<a href="print.php?operator='.$Operator['operator_code'].'" target="_blank" ><button type="button" title="Print Barcode" class="btn btn-default" aria-label="Left Align"><span class="glyphicon glyphicon-print"></span></button></a>' ;
						//echo"</div>";
						//echo'<div class="col-sm-1">';
						if($Operator['total_count']==0){
							echo'<a href="manage_operator.php?id='.$Operator['operator_code'].'" onclick="return confirm(\'Are you sure to delete '.$Operator['operator_fullname'].'?\')" >
							<button type="button" title="Delete Operator" class="btn btn-default" aria-label="Left Align">';
							echo'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
							echo'</button></a>';
						}
						echo"</div>";
						
						
						echo"</form>";
					echo"</center></div>";
					}	
	}
	
	
	
	
	?>
     



	
	   
    </div>
	
	<div class="col-sm-3">
	<h3>Management</h3>
     <a href="manage_operator.php"  ><button type="button" class="btn btn-primary">View Operator</button></a><br><br>
	 <a href="manage_operator.php?add=operator"  ><button type="button" class="btn btn-primary">Add Operator</button></a><br><br>
	 <a href="manage_operator.php?view=workarea"  ><button type="button" class="btn btn-primary">View WorkArea</button></a><br><br> 
	 <a href="manage_operator.php?add=workarea"  ><button type="button" class="btn btn-primary">Add WorkArea</button></a><br><br>
	  
    </div>
	
  </div>
</div>

 