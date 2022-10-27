

<?php $starttime = microtime(true); // Top of page
$page_title='Summary';
$title_top='Summary';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/issue_log.css">	
<link rel="stylesheet" href="css/roster.css">

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<?php $_SESSION['temp']['addscan'] = array();
	 include ('navbar.php'); 
    include ('function_framework.php'); 
    include ('function_matrix.php'); 
    include ('function_scanning_v2.php'); 
    edit_scan_procedure($db);
    echo '<link rel="stylesheet" href="css/matrix.css?v='.time().'">';
    manage_POST_matrix($db);
    navbar_matrix($db);
    main_view_matrix($db);
	?>
    <div class="row">
  
  
   
  
  
   
	
</div>


