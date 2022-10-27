

<?php 
$page_title='Range';
$title_top='Range';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php echo '<link rel="stylesheet" href="css/injury.css?v='.time().'">';?>
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	<style>
    .combo{
        width:7.5%;
        float:left;
        position:relative;
        padding:2px;
        border: 0.5px solid black;
        border-radius: 6px;
        text-align: center;
        min-height:30px;
        max-width:30px;
    }
    .combo:hover{
        background: #afd0fc;
    }
    .position{
        width:12.5%;
        float:left;
        position:relative;
        padding:2px;
        border: 0.5px solid black;
        border-radius: 6px;
        text-align: center;
        min-height:30px;
        max-width:60px;
    }
    .position:hover{
        background: #ffe2e2;
    }
    .stack{
        width:12.5%;
        float:left;
        position:relative;
        padding:2px;
        border: 0.5px solid black;
        border-radius: 6px;
        text-align: center;
        min-height:30px;
        max-width:60px;
    }
    .stack:hover{
        background: #ffe2e2;
    }

    </style>
	<?php
	
	include('function_range.php');
	
    
    Hero_position();
    echo'<br>';
    Stack();
    echo'<br>';
	show_range();
	
	
	
	
	
       
        
		

	?>
	
	
	


	
	
	
</div>
