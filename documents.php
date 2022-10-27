

<?php 
$page_title='Documents';
$title_top='Documents List';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/document.css?v=<?php echo time()?>">		
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	<?php include('function_doc2.php');
	include('function_framework.php');
	manage_POST_document($db);
		
    show_navbar_document();
		
	main_view_document($db);
	?>
	
	
</div>
