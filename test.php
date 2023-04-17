<?php 
$page_title='Test Page';
$title_top='Test Page';
$page = $_SERVER['PHP_SELF'];
?>

<?php include ('header.php');?>

<div class="container">
<!--Load Css -->
<link rel="stylesheet" href="css/xxx.css">
<!--Load Php navbar -->	
<?php include ('navbar.php'); ?>
<!--Load Php function -->	
<?php include ('function_framework.php'); ?>
<!--Main page -->	
<div class="row">
    <div class="col-xs-6"></div>
    <div class="col-xs-6">
    </div>
    <?php 
    use PHPMailer\PHPMailer\PHPMailer;
    $address="corentinhillion@gmail.com";
    $name="Test";
    $content="content test email";
    $subject="Test Email";
    send_email('production-assistant@sicame.com.au',"Test","content email 123","Test Email",$cc='');    
    ?>
</div>

