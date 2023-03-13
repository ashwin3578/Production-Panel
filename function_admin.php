<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use chillerlan\QRCode\{QRCode, QROptions};


function show($array){
	
	echo'<pre style="text-align:left">';
	// echo print_var_name($array);
	// echo"<br>";
	print_r($array);
	echo'</pre>';
}

function print_var_name($var) {
    foreach($GLOBALS as $var_name => $value) {
        if ($value === $var) {
            return $var_name;
        }
    }

    return false;
}

function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}

function saveemployee($db){
	 
	 if($_POST['admin']=='True'){$admin=1;}else{$admin=0;}
	 
	 $query="INSERT INTO dbo.employee(
	 employee_name, 
	 employee_lastname, 
	 employee_fullname, 
	 employee_code, 
	 employee_password,
	 employee_email,
	 employee_admin) VALUES 
	 ('".$_POST['firstname']."',
	 '".$_POST['lastname']."',
	 '".$_POST['firstname']." ".$_POST['lastname']."',
	 '".$_POST['firstname'].$_POST['lastname']."',
	 '".$_POST['employeepassword']."',
	 '".$_POST['employeeemail']."',
	 '".$admin."')";
	 
	 //show($query);
	 
 $sql = $db->prepare($query); 

	$sql->execute();
	
 }
 function modifyemployee($db){
	 
	 if($_POST['admin']=='True'){$admin=1;}else{$admin=0;}
	  $query="UPDATE dbo.employee
	 SET 
	 employee_name= '".$_POST['employee_name']."',
	 employee_lastname= '".$_POST['employee_lastname']."',
	 employee_fullname= '".$_POST['employee_name'].' '.$_POST['employee_lastname']."',
	 
	 
	 employee_email='".$_POST['employee_email']."',
	 employee_admin='".$admin."'
	 WHERE
	 employee_code='".$_POST['employee_code']."'
	";
	 
	// show($query);
	 
 $sql = $db->prepare($query); 

	$sql->execute();
	
 }
 
 
 
 
 
 
 
 
 function deleteoperator($db){
	 $sql = $db->prepare("DELETE FROM dbo.operator WHERE operator_code='".$_GET['id']."'"); 

	$sql->execute();
	
 }
 
 function deleteemployee($db){
	 $sql = $db->prepare("DELETE FROM dbo.employee WHERE employee_code='".$_GET['id']."'"); 

	$sql->execute();
	
 }
 
 function saveworkarea($db){
	 $sql = $db->prepare("INSERT INTO dbo.workarea(
	 workarea) VALUES 
	 ('".$_POST['addworkarea']."')"); 

	$sql->execute();
	
 }
 
 function deleteworkarea($db){
	 $sql = $db->prepare("DELETE FROM dbo.workarea WHERE workarea='".$_GET['delete']."'"); 

	$sql->execute();
	
 }

 function listemployee($db){
	$sql = $db->prepare("SELECT * FROM dbo.employee  order by employee_fullname ASC"); 

	$sql->execute();

	$row=$sql->fetchall();
	
	return $row;
		
}

function listoperator($db,$workarea='All',$detail=0){
	

	
	if(empty($_SESSION['temp']['sort_Operator']))
	{
		$sort='operator_code ASC';
	}
	else
	{
		$sort=$_SESSION['temp']['sort_Operator'];
	}
	
	if(empty($_SESSION['temp']['filter_Operator'])or ((substr($_SERVER['REQUEST_URI'], 1, 15))<>'manage_operator'))
	{
		$filter='AND operator_active=1 ';
	}
	else
	{
		$filter=$_SESSION['temp']['filter_Operator'];
	}
	
	
	if ($detail==0){
		$addon1="";
		$addon2="";
		$addon3="";
		
	}else{
		
		$addon1=', min(scan_date) as min_date, max(scan_date) as max_date, count(scan_statut)as total_count, round(sum([scan_time_distributed])/3600,1) as total_hours ';
		$addon2='LEFT JOIN dbo.scan

		ON
		scan_operatorcode=operator_code';
		$addon3='GROUP BY operator_code,operator_name,operator_lastname,operator_fullname,operator_workarea,operator_active';
	}
	
	
	if($workarea=='All'){
		$query="SELECT operator_code,operator_name,operator_lastname,operator_fullname,operator_workarea,operator_active,total_count=0 $addon1 FROM operator $addon2 WHERE 1=1 $filter $addon3 order by $sort";
		
	}
	else{
		$query="SELECT operator_code,operator_name,operator_lastname,operator_fullname,operator_workarea,operator_active,total_count=0 $addon1 FROM operator $addon2 WHERE operator_workarea='$workarea' $filter $addon3 order by $sort";
		
	}
	$sql = $db->prepare($query); 
	$sql->execute();
	//show($query);
	$row=$sql->fetchall();
	
	return $row;
		
}

function listworkarea($db){
	$sql = $db->prepare("SELECT * FROM dbo.workarea ORDER BY workarea ASC "); 

	$sql->execute();

	$row=$sql->fetchall();
	
	return $row;
		
}

 function list_MIS_open($db,$catu='hidden'){
	$query='SELECT * FROM dbo.MIS_Open WHERE PRODUCT_FAMILY<>\'CATU\' order by Product_Code ASC,MIS_Number ASC';
	 
	$sql = $db->prepare($query); 

	$sql->execute();

	$row=$sql->fetchall();
	
	return $row;
		
}

 function saveoperator($db){
	 
	 
	 //check if Operator Exist already
	$query="SELECT * FROM dbo.operator WHERE operator_code='".$_POST['operatorcode']."'";
	 
	$sql = $db->prepare($query); 

	$sql->execute();

	$row=$sql->fetchall();
	
	if (empty($row)){
		 $sql = $db->prepare("INSERT INTO dbo.operator(
	 operator_name, operator_lastname, operator_fullname, operator_code, operator_workarea) VALUES 
	 ('".$_POST['firstname']."','".$_POST['lastname']."','".$_POST['firstname']." ".$_POST['lastname']."','".$_POST['operatorcode']."','".$_POST['workarea']."')"); 

	$sql->execute();
	

	}
	else{
		echo'<div class="alert alert-danger" role="alert">
	  This Operator code was already used, couldnt add that operator
	</div>';
	}
	 
	 
	 
	 
	 
	 
	 
	
	
 }
 
 function modify_operator($db,$old_code,$newcode,$newname,$newlastname,$workarea,$active=1){
	
	//get the last name
	$query="SELECT * FROM dbo.operator WHERE operator_code='".$_POST['old_operator_code']."'";
	$sql = $db->prepare($query);
	$sql->execute();
	$row=$sql->fetch();
	$old_full_name=$row['operator_fullname'];
	$new_full_name=$newname.' '.$newlastname;

	$all_to_be_update[]=['allocationcontract','allocationcontract_operatorid'];
	$all_to_be_update[]=['allocationwork','allocationwork_operatorid'];
	$all_to_be_update[]=['allocationshift','allocationshift_operatorid'];
	$all_to_be_update[]=['allocationlog','allocationlog_operatorid'];
	$all_to_be_update[]=['allocation','allocation_operatorid'];
	$all_to_be_update[]=['schedule','schedule_operatorcode'];
	$all_to_be_update[]=['training_event','trainingevent_operator'];
	$query='';
	foreach($all_to_be_update as $tobeupdate){
		$table=$tobeupdate[0];
		$field=$tobeupdate[1];
		$query=$query."
		update $table
		set $field='$new_full_name'
	   where $field='$old_full_name';";
	}
	

	$query=$query.'UPDATE dbo.operator SET 
	operator_name=\''.$newname.'\',
	operator_lastname=\''.$newlastname.'\',
	operator_fullname=\''.$newname.' '.$newlastname.'\',
	operator_code=\''.$newcode.'\',
	operator_active=\''.$active.'\',
	operator_workarea=\''.$workarea.'\' 
	
	WHERE operator_code=\''.$old_code.'\'';
	
	
	
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
	
 }

 function get_operator_detail($db,$operator){
	
	
	
	
	
	$detail['operator_fullname']=get_operator_name($db,$operator);
	
	$detail['hours_today']=Total_hours_today($db,$operator);
	
	
	
	
	return $detail;
}

function get_operator_name($db,$operator){
	$query='SELECT operator_fullname FROM dbo.operator 
	WHERE 
	operator_code = \''.$operator.'\'
	';
	
	$sql = $db->prepare($query); 

	$sql->execute();
	// show($query);
	$row=$sql->fetch();
	return $row['operator_fullname'];
}

function checkpassword($db,$username,$password){
	$query='SELECT * FROM dbo.employee 
	WHERE 
	employee_code=\''.$username.'\'
	AND employee_password=\''.$password.'\'
	';
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
	$row=$sql->fetch();
	if (empty($row)){$check=false;}Else{$check=True;}
	
	Return $check;
}

function isadmin($db,$username){
	$query='SELECT * FROM dbo.employee 
	WHERE 
	employee_code=\''.$username.'\'
	
	';
	$sql = $db->prepare($query); 
	
	$sql->execute();
	//show($query);
	$row=$sql->fetch();
	if ($row['employee_admin']==1){$_SESSION['temp']['admin']=1;}Else{$_SESSION['temp']['admin']='';}
	
	
}





function show_import_speed($db){
	 $now=time();
	  
	  
	 
	  
	  //show($_SESSION['temp']);
		 
		$query='SELECT count([import_operatorcode])as total FROM [barcode].[dbo].[import]
				';
				
				$sql = $db->prepare($query); 
				 // shshow($query);
				$sql->execute();

				$row=$sql->fetch();
				$sec=time()-$_SESSION['temp']['old_timetag'];
				echo '<h1> Scan left : '.$row['total'].'</h1>';
				
				echo '<h1>'.($_SESSION['temp']['count2']-$row['total']);
				echo ' in the last '.$sec.' secondes ('.round((($_SESSION['temp']['count2']-$row['total'])/$sec),2).'scan/sec)</h1>';
				$time_left=$row['total']*$sec/($_SESSION['temp']['count2']-$row['total']);
				
				$days=floor($time_left/(3600*24));
				$hours=floor(($time_left-$days*(3600*24))/3600);
				$min=floor(($time_left-$days*(3600*24)-$hours*(3600))/60);
				$secondes=floor($time_left-$days*(3600*24)-$hours*(3600)-$min*60);
				
				
				$respond='';
				if($days>0){$respond=$days.' days ';}
				if($hours>0){$respond=$respond.$hours.' hours ';}
				if($min>0){$respond=$respond.$min.' minutes ';}
				if($secondes>0){$respond=$respond.$secondes.' secondes left';}
				
				echo '<h1>'.($respond).'</h1>';
				
				$_SESSION['temp']['count2']=$row['total'];
				$_SESSION['temp']['old_timetag']=time();
}

function get_employee_name($db,$employee){
	$query='SELECT employee_fullname FROM dbo.employee 
	WHERE 
	employee_code = \''.$employee.'\'
	';
	
	$sql = $db->prepare($query); 

	$sql->execute();
	// show($query);
	$row=$sql->fetch();
	return $row['employee_fullname'];
} 

function recorded_time_connection($db,$username){
	
	$tempdate=new datetime();
	$datenow = $tempdate->getTimestamp();
	
	
	
	$query='UPDATE dbo.employee SET 
	
	employee_datelastconnection=\''.$datenow.'\',
	employee_last_ip=\''.$_SERVER['REMOTE_ADDR'].'\'
	
	WHERE employee_code=\''.$username.'\'';
	
	//show($query);
	
	
	$sql = $db->prepare($query); 

	$sql->execute();
	
}

function last_login_machine($db){
	
	$query='SELECT employee_code FROM dbo.employee 
	WHERE 
	employee_last_ip=\''.$_SERVER['REMOTE_ADDR'].'\'
	order by employee_datelastconnection DESC
	';
	
	$sql = $db->prepare($query); 

	$sql->execute();
	 //show($query);
	$row=$sql->fetch();
	return $row['employee_code'];
} 

function load_role($db,$username){
		$query='SELECT *
		  FROM dbo.employee
		  left join dbo.role_attribution
		  on employee_code=attribution_employee_code
		  left join dbo.role
		  on role_name=attribution_role_id 
		WHERE 
		employee_code=\''.$username.'\'
		
		';
		$sql = $db->prepare($query); 
		
		$sql->execute();
		//show($query);
		$row=$sql->fetchall();
		//show($row);
		$_SESSION['temp']['id_full_name']=$row[0]['employee_fullname'];
		unset($_SESSION['temp']['role_asset_admin']);
		unset($_SESSION['temp']['role_metro_modify']);
		unset($_SESSION['temp']['role_metro_admin']);
		unset($_SESSION['temp']['role_roster_modify']);
		unset($_SESSION['temp']['role_roster_admin']);
		unset($_SESSION['temp']['role_prodplan_input']);
		unset($_SESSION['temp']['role_prodplan_risk']);
		unset($_SESSION['temp']['role_prodplan_pref']);
		unset($_SESSION['temp']['role_prodplan_notes']);
		unset($_SESSION['temp']['role_prodplan_statuts']);
		unset($_SESSION['temp']['role_costing_view']);
		unset($_SESSION['temp']['role_injury_viewall']);
		unset($_SESSION['temp']['role_injury_access']);
		unset($_SESSION['temp']['role_factory_admin']);
		unset($_SESSION['temp']['role_factory_change']);
		unset($_SESSION['temp']['role_schedule_admin']);
		unset($_SESSION['temp']['role_doc_change']);
		unset($_SESSION['temp']['role_training_admin']);
		unset($_SESSION['temp']['role_moulding_planning']);
		
		
		foreach($row as &$role) {
			//show($role);
			if ($role['role_barcode_management']==1){$_SESSION['temp']['role_barcode_management']=1;}
			if ($role['role_barcode_admin']==1){$_SESSION['temp']['role_barcode_admin']=1;}
			if ($role['role_issue_log_raise']==1){$_SESSION['temp']['role_issue_log_raise']=1;}
			if ($role['role_issue_log_modify']==1){$_SESSION['temp']['role_issue_log_modify']=1;}
			if ($role['role_issue_log_admin']==1){$_SESSION['temp']['role_issue_log_admin']=1;}		
			if ($role['role_issue_admin']==1){$_SESSION['temp']['role_issue_admin']=1;}		
			if ($role['role_asset_admin']==1){$_SESSION['temp']['role_asset_admin']=1;}
			if ($role['role_metro_modify']==1){$_SESSION['temp']['role_metro_modify']=1;}		
			if ($role['role_metro_admin']==1){$_SESSION['temp']['role_metro_admin']=1;}		
			if ($role['role_roster_modify']==1){$_SESSION['temp']['role_roster_modify']=1;}	
			if ($role['role_roster_admin']==1){$_SESSION['temp']['role_roster_admin']=1;}			
			if ($role['role_prodplan_input']==1){$_SESSION['temp']['role_prodplan_input']=1;}		
			if ($role['role_prodplan_risk']==1){$_SESSION['temp']['role_prodplan_risk']=1;}	
			if ($role['role_prodplan_pref']==1){$_SESSION['temp']['role_prodplan_pref']=1;}	
			if ($role['role_prodplan_notes']==1){$_SESSION['temp']['role_prodplan_notes']=1;}	
			if ($role['role_prodplan_statuts']==1){$_SESSION['temp']['role_prodplan_statuts']=1;}	
			if ($role['role_costing_view']==1){$_SESSION['temp']['role_costing_view']=1;}	
			if ($role['role_injury_viewall']==1){$_SESSION['temp']['role_injury_viewall']=1;}		
			if ($role['role_injury_access']==1){$_SESSION['temp']['role_injury_access']=1;}		
			if ($role['role_factory_admin']==1){$_SESSION['temp']['role_factory_admin']=1;}	
			if ($role['role_factory_change']==1){$_SESSION['temp']['role_factory_change']=1;}		
			if ($role['role_schedule_admin']==1){$_SESSION['temp']['role_schedule_admin']=1;}
			if ($role['role_doc_change']==1){$_SESSION['temp']['role_doc_change']=1;}	
			if ($role['role_training_admin']==1){$_SESSION['temp']['role_training_admin']=1;}		
			if ($role['role_moulding_planning']==1){$_SESSION['temp']['role_moulding_planning']=1;}		
        }
    }

	
function send_email($address,$name,$content,$subject='',$cc=''){
		
		
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
			$mail->Host       = 'mail.sicame.com.au';             //Set the SMTP server to send through
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
			// if (!$mail->send()) {
			// 	echo 'Mailer Error: ' . $mail->ErrorInfo;
			// } else {
			// 	//echo 'Message sent!';
			// }
			
}

function count_active_issue($db,$username){
	if(empty($username)) {$addon='';}else{$addon=" and issue_assignto='$username'";}

	$query="SELECT count (issue_number)
	  FROM issue_log
	  Where issue_closed=0 $addon ";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
	//
	return $row[0];
	
}

function count_test_done_today_metro($db){
    $today=(date('Y-m-d',time()))	;
    $query="SELECT count(distinct single_id) as total_test
    FROM metro_single
    left join metro_test on test_id=single_testid
    where    single_finished=1 and test_date='$today' ";
  //show($query);
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetch();
 
  return $row[0];
  
}
function days_since_last_injury_menu($db){
    $query='SELECT TOP 1 (injuryreport_timetag_incident) as last_timetag
    FROM injuryreport
    order by injuryreport_timetag_incident DESC
    ';
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  $days=round((time()-$row['last_timetag'])/24/3600,0);
  
  return $days;
}


function test_mail(){
	$address='corentin@sicame.com.au';
	$name='test';
	$content='Test email, i hope it works';
	$subject='Test Email';
	send_email($address,$name,$content,$subject,$cc);
}

function showtimes($time){
	$i=0;
	foreach($time as $timetag){
		if($i<>0){show('Measure#'.$i.': '.round($timetag-$oldtimetag,3).' secondes');}else{$firsttimetag=$timetag;}
		$oldtimetag=$timetag;
	$i++;
	}
	show('Overall: '.round($timetag-$firsttimetag,3).' secondes');
}
function get_setting($setting_name){
    $db=$GLOBALS['db'];
    $query="SELECT setting_value FROM Setting
    WHERE setting_name='$setting_name'";

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $return=$sql->fetch();
    return $return['setting_value'];

}
?>