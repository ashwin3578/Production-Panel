<form  action="list-view.php" method="post">

<div class="row">




	<div class="col-sm-2">
	<br>
      
	  <label class="form-check-label"  for="showfinishedjob">Hide Finished Jobs:</label>
	  <input class="form-check-input" name="showfinishedjob" onChange="this.form.submit()" type="checkbox"  id="showfinishedjob"  <?php if(!empty($_SESSION['temp']['filter']['showfinishedjob'])&&($_SESSION['temp']['filter']['showfinishedjob'])=='on'){echo 'checked';}?>>
    </div>
	
	
	
	
    <div class="col-sm-2">
     Operator : 
	 
	<?php 
		echo '<select name="filter_operator" onChange="this.form.submit()" class="form-control" id="sel1">';
			echo"<option ";
				if(!empty($_SESSION['temp']['filter']['filter_operator'])&&$_SESSION['temp']['filter']['filter_operator']=='All'){echo 'selected';}
				echo">All</option>";
				if(!empty($_SESSION['temp']['filter']['filter_workarea'])){$_SESSION['temp']['filter']['filter_workarea']=='All';}
		
		
		$listOperator=listoperator($db,$_SESSION['temp']['filter']['filter_workarea']);
				
				
		foreach ($listOperator as &$Operator){
				echo"<option ";
				if(!empty($_SESSION['temp']['filter']['filter_operator'])&&$_SESSION['temp']['filter']['filter_operator']==$Operator['operator_fullname']){echo 'selected';}
				echo">".$Operator['operator_fullname']."</option>";
				}
		echo '</select>';
		
		
		?>
	  	  
	 
    </div>   
	
	
	
	
	
	<div class="col-sm-2">
     Work Area : 
	 
		<?php 
		echo '<select name="filter_workarea" onChange="this.form.submit()" class="form-control" id="sel1">';
		
		$listWorkarea=listworkarea($db);
					echo"<option>All</option>";
		foreach ($listWorkarea as &$workarea){
				echo"<option ";
				if(!empty($_SESSION['temp']['filter']['filter_workarea'])&&$_SESSION['temp']['filter']['filter_workarea']==$workarea['workarea']){echo 'selected';}
				echo">".$workarea['workarea']."</option>";
				}
		echo '</select>';
		
		
		
		?>
        
     
    </div>   
	
	
	
	
	<?php 
	  if(!empty($_SESSION['temp']['filter']['datetimepicker1'])){
		   if(empty($_SESSION['temp']['filter']['datetime'])){$_SESSION['temp']['filter']['datetime'] = new DateTime($_SESSION['temp']['filter']['datetimepicker1']);}
			   
			$_SESSION['temp']['filter']['month'] = $_SESSION['temp']['filter']['datetime']->format('m');
			$_SESSION['temp']['filter']['day'] = $_SESSION['temp']['filter']['datetime']->format('d');
			$_SESSION['temp']['filter']['year'] = $_SESSION['temp']['filter']['datetime']->format('Y');
		  
		  
		  
		  $datevalue = $_SESSION['temp']['filter']['year'] . '-' . $_SESSION['temp']['filter']['month'] . '-' . $_SESSION['temp']['filter']['day'];
		  
		  } 
	  else {
			$_SESSION['temp']['filter']['month'] = date('m');
			$_SESSION['temp']['filter']['day']= date('d');
			$_SESSION['temp']['filter']['year'] = date('Y');
			$datevalue = $_SESSION['temp']['filter']['year'] . '-' . $_SESSION['temp']['filter']['month'] . '-' . $_SESSION['temp']['filter']['day'];
		   }
	

	
	?>
	
	<div class="col-sm-2">
      Date Filter:
	  <input type="date" name="datetimepicker1" value="<?php echo $datevalue; ?>" id="datetimepicker1" onChange="this.form.submit()" class="form-control" id="usr">
    </div>  
	
</form>	
	
	
	
	
	<div class="col-sm-4">
       <br>
	   <form action="?" method="post">
	   <input type="hidden" id="add_a_scan" name="add_a_scan" value="ok">
	   <input type="hidden" id="add_a_scan_init" name="add_a_scan_init" value="ok">
	  <button type="button" name="add_a_scan" value="ok" class="btn btn-primary" onClick="this.form.submit()" >Add Scan</button>
	  </form>
    </div>  
  </div>
  
