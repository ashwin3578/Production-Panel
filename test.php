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
    $address="production-assistant@sicame.com.au";
    $name="Test";
    $content="content test email";
    $subject="Test Email";
    //send_email('production-assistant@sicame.com.au',"Test","content email 123","Test Email",$cc='');
    require 'composer/vendor/phpmailer/phpmailer/src/Exception.php';
    require 'composer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
        
    //Load Composer's autoloader
    require 'composer/vendor/autoload.php';
    

    //Instantiation and passing `true` enables exceptions

        //Create a new PHPMailer instance
        $mail = new PHPMailer();
        //Server settings

        if($_SERVER['PHP_SELF']=='/test.php')      {$mail->SMTPDebug = 3;} 
        //$mail->SMTPDebug = 3;                    //Enable verbose debug output SMTP::DEBUG_SERVER
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = '172.31.28.30';             //mail.sicame.com.au Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = get_setting('email_production_assistant');  //SMTP username
        $mail->Password   = get_setting('password_production_assistant');   //SMTP password
        $mail->SMTPSecure = 'tls';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;   		//TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        $mail->SMTPOptions = array(
        'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );   
        
        //Recipients
        $mail->setFrom(get_setting('email_production_assistant'), 'Production Assistant');
        $mail->addAddress($address, $name);     //Add a recipient
        
        //CCs
        if($cc<>''){
            $allcc=explode(";",$cc);
            foreach ($allcc as &$onecc){
                $mail->addCC($onecc);
            }	
        }
        
        //Content
        $mail->isHTML(true);  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;
        $mail->AltBody = $content;
            //show($mail);
        // send the message, check for errors
        if (!$mail->send()) {
        	echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
        	echo 'Message sent!';
        }
    
    ?>
</div>

