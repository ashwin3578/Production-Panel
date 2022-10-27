<?php 

$page_title='Test';
$page = $_SERVER['PHP_SELF'];

?>

<?php include ('header.php');?>

<div class="container">
<link rel="stylesheet" href="css/issue_log.css">	
<link rel="stylesheet" href="css/roster.css">

<?php $_SESSION['temp']['addscan'] = array();?>
<?php include ('navbar.php'); ?>
<?php include ('function_framework.php'); ?>
<form method="POST">
<?php show($_POST);show_signature_box('sign'); ?>
<br/><br>
<button class="btn btn-success">Submit</button>
<br><br><br>

<?php if(!empty($_POST['sign'])){
    ?>
    <img src="<?php echo $_POST['sign'];?>" alt="Red dot" /><?php
}?>
</form>

