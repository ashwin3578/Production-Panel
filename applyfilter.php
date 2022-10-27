<?PHP


if(!empty($_POST['filter_operator'])) {
	$_SESSION['temp']['filter']=$_POST;
}
if(empty($_SESSION['temp']['filter']['filter_operator'])){$_SESSION['temp']['filter']['filter_operator']='All';}
 if(empty($_SESSION['temp']['filter']['filter_workarea'])){$_SESSION['temp']['filter']['filter_workarea']='All';}

 if(!empty($_GET['operator'])){
	 $_SESSION['temp']['filter']['filter_operator']=$_GET['operator'];
 }




	//new datetime($_SESSION['temp']['filter']['datetimepicker1'].''.$_POST['timestart']);
	
if(!empty($_GET['day'])) {	
	if($_GET['day']=='u'){
		$_SESSION['temp']['filter']['datetime']->modify('+1 day');
		
	}
	if($_GET['day']=='d'){
		$_SESSION['temp']['filter']['datetime']->modify('-1 day');;
	}
}

	if(empty($_SESSION['temp']['filter']['datetime'])){
		if(empty($_SESSION['temp']['filter']['datetimepicker1'])){
			$_SESSION['temp']['filter']['datetime']=new DateTime('NOW');
		}
		else
		{
			$_SESSION['temp']['filter']['datetime']=new datetime($_SESSION['temp']['filter']['datetimepicker1']);
		}
		
		$_SESSION['temp']['filter']['month'] = $_SESSION['temp']['filter']['datetime']->format('m');
		$_SESSION['temp']['filter']['day'] = $_SESSION['temp']['filter']['datetime']->format('d');
		$_SESSION['temp']['filter']['year'] = $_SESSION['temp']['filter']['datetime']->format('Y');
		$_SESSION['temp']['filter']['datetimepicker1']=$_SESSION['temp']['filter']['year'] . '-' . $_SESSION['temp']['filter']['month'] . '-' . $_SESSION['temp']['filter']['day'];
	}

if(!empty($_SESSION['temp']['filter']['year'])){
		$_SESSION['temp']['filter']['datetimepicker1']=$_SESSION['temp']['filter']['year'] . '-' . $_SESSION['temp']['filter']['month'] . '-' . $_SESSION['temp']['filter']['day'];
	}
	

?>