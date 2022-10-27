

<?php 
$page_title='Metrology';
$title_top='Metrology';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/issue_log.css">		
<link rel="stylesheet" href="css/metrology.css?v=<?=time();?>">
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	
	
	
	<?php
	include ('function_metrology.php');
	
	//if(empty($_SESSION['temp']['id'])){header('Location: index.php');}
	//if($_SESSION['temp']['id']=='CorentinHillion'){show($_POST);}
	
	managing_POST($db);
   // if($_SESSION['temp']['id']=='CorentinHillion'){show($_POST);}
	navbar_metrology($db);
	echo'<div class="row ">';
        echo'<div id="postinfo" class="postinfo">';
        echo'</div>';
    echo'</div>';
   // if($_SESSION['temp']['id']=='CorentinHillion'){show($_POST);}
	if(!empty($_POST['template_name']) or !empty($_POST['manage_template'])){
		manage_template($db);
	}
	elseif(!empty($_POST['new_test']) or !empty($_POST['product_test'])){
		new_test_view($db);
	}else{
		view_general_menu($db);
	}
    //if($_SESSION['temp']['id']=='CorentinHillion'){show($_POST);}
	//if($_SESSION['temp']['id']=='CorentinHillion'){show($_POST);}


	?> 
	
	
	
	
	


	
	
	
</div>


