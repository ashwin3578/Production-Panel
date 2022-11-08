

<?php 
$page_title='HazRep';
$title_top='Hazard Report Register';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="/css/hazrep.css?v=<?php echo time()?>">
	
    <?php include('function_framework.php');?>
	<?php include('function_hazrep.php');?>
	<?php include ('navbar.php'); ?>	
	<div class="navbar_hazrep_container">
        <?php HazRepController::navbar_hazrep();?>
    </div>
	<?php HazRepController::manage_post_hazrep();?>
	<?php HazRepController::general_view_hazrep();?>
</div>





