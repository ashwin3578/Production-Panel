

<?php $starttime = microtime(true); // Top of page
$page_title='Test Kitting';
$title_top='Test Kitting';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/issue_log.css">	
<link rel="stylesheet" href="css/roster.css">

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<?php $_SESSION['temp']['addscan'] = array();
	 //include ('navbar.php'); 
  include ('function_framework.php'); 
  include ('function_kit.php'); 
  
  echo '<link rel="stylesheet" href="css/kitting.css?v='.time().'">';
  //echo '<link rel="stylesheet" href="css/checkstock.css?v='.time().'">';


    manage_POST_kit($db);
    navbar_kit($db,$_POST['code']);
    general_view_kit($db);?>

    <div class="row">
        <?php show_list_kit($db,$_POST['code']);?>
    </div>
    <div class="postinfo" ></div><br>
    <?php //old_navbar_kit($db,$_POST['code']);
    //ajax_load([['showproductlist',"'ok'"],['view',"'".$_POST['view']."'"]],"kit-ajax.php","showproductlist",'empty().append(html)');?>
 


