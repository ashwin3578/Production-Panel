

<?php $time[] = microtime(true); // Top of page
$page_title='Stock';
$title_top='Stock';
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
  include ('function_stock.php'); 
  
  echo '<link rel="stylesheet" href="css/checkstock.css?v='.time().'">';
  
  manage_POST_check($db);
  navbar_stock($db);

  
  general_view_stock($db)

         
        
           
            
            
        
   
   


	?>
   


