<?php

function navbar_button($link,$caption,$restriction_to=1){
	if(!empty($restriction_to)){
		if($_SERVER['PHP_SELF']==$link) {$active=' active';}else{$active='';} ?>
		<li class="<?php echo $active?>"><a href="<?php echo $link?>"><?php echo $caption?></a></li>
		<?php }	
	}
?>

<div class="row">
	<nav class="navbar navbar-default">
	  
		<div class="navbar-header">
		  <a class="navbar-brand" href="index.php"><?php if($_SESSION['temp']['id']=='FinneyKfghessler'){echo 'Finney Not #1';}else{echo'Home';} ?></a>
		</div>
		<ul class="nav navbar-nav">
		
		
			<?php if(!empty($_SESSION['temp']['role_barcode_management'])){?>
				<li class="hidden-sm dropdown "><a class="dropdown-toggle" >Barcodes<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<?php navbar_button('/summary-view.php','All Scan')?>
						<?php navbar_button('/check.php','Still-Open ('.count_open_scan($db).')')?>
						<?php navbar_button('','________')?>
						<?php navbar_button('/list-view.php','List View')?>
						<?php navbar_button('/matrix.php','Summary')?>
						<?php //navbar_button('/today.php','Show today scan')?>
					</ul>
			  	</li>
			<?php }?>
		
			
			<li class="hidden-sm dropdown "><a class="dropdown-toggle" >Production<span class="caret"></span></a>
				<ul class="dropdown-menu">
					<?php navbar_button('/factory.php','Factory View')?>
					<?php navbar_button('/prod-issue-log.php','Production Issues ('.count_active_issue($db,$_SESSION['temp']['id']).')',$_SESSION['temp']['id'])?>
					<?php navbar_button('/production-plan.php','Production Plan')?>
					<?php navbar_button('/schedule.php','Operator Schedule',$_SESSION['temp']['role_schedule_admin'])?>
					<?php navbar_button('/roster.php','Labour Allocation')?>
					<?php navbar_button('/available.php','Operator Available')?>
					<?php navbar_button('','________')?>
					<?php navbar_button('/asset.php','Tool Register')?>
					<?php navbar_button('/bin-location.php','Bin Location')?>
					
				</ul>
			</li>
		
			
			<?php navbar_button('/metrology.php','Metrology')?>
			<?php navbar_button('/injury.php','Injury Register',$_SESSION['temp']['role_injury_access'])?>	
			<?php navbar_button('/documents.php','Documents')?>

			<?php if(!empty($_SESSION['temp']['role_barcode_management'])){?>
				<li class="hidden-sm dropdown "><a class="dropdown-toggle" >Admin<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li class="dropdown-header">Barcode Management</li>
						<?php navbar_button('/tools.php','Tools')?>
						<?php navbar_button('/manage_operator.php','Manage Operator')?>
						<?php navbar_button('/manage_employee.php','Manage Employee')?>
						<?php navbar_button('/log.php','Log')?>
						
					</ul>
				</li>
			<?php } ?>

			<?php if(empty($_SESSION['temp']['id'])){$caption='Log-in';}else{$caption='Log-out';}navbar_button('/connection.php',$caption)?>
			
			<?php if(!empty($_SESSION['temp']['id'])){ ?>
				<li class="hidden-sm dropdown "><a class="dropdown-toggle" ><?php echo $_SESSION['temp']['id']; ?><span class="caret"></span></a>
				
				<ul class="dropdown-menu">
					<?php navbar_button('/password.php','Change Password')?>
				</ul>
			<?php } ?>

			

		<?php
		if($_SESSION['temp']['id']=='FinneyKessler' or $_SESSION['temp']['id']=='CorentinHillion'){
			navbar_button('/chess.php','Chess');
			
		}
		?>
		
		
		
	  </div>
	</nav>
</div>