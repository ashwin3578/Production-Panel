<?php
if (!empty($_GET['sort'])){
		if($_GET['sort']=='MIS'){
			if($_SESSION['temp']['sort']=='scan_jobnumber ASC'){
				$_SESSION['temp']['sort']='scan_jobnumber DESC';
			}
			else{
				$_SESSION['temp']['sort']='scan_jobnumber ASC';
			}
		}
		if($_GET['sort']=='Product'){
			if($_SESSION['temp']['sort']=='scan_jobnumber ASC'){
				$_SESSION['temp']['sort']='scan_jobnumber DESC';
			}
			else{
				$_SESSION['temp']['sort']='scan_jobnumber ASC';
			}
		}
		if($_GET['sort']=='Operator'){
			if($_SESSION['temp']['sort']=='operator_fullname ASC'){
				$_SESSION['temp']['sort']='operator_fullname DESC';
			}
			else{
				$_SESSION['temp']['sort']='operator_fullname ASC';
			}
		}
		if($_GET['sort']=='Start'){
			if($_SESSION['temp']['sort']=='scan_timetag ASC'){
				$_SESSION['temp']['sort']='scan_timetag DESC';
			}
			else{
				$_SESSION['temp']['sort']='scan_timetag ASC';
			}
		}
		if($_GET['sort']=='Finish'){
			if($_SESSION['temp']['sort']=='scan_timetag_finish ASC'){
				$_SESSION['temp']['sort']='scan_timetag_finish DESC';
			}
			else{
				$_SESSION['temp']['sort']='scan_timetag_finish ASC';
			}
		}
		if($_GET['sort']=='Hours'){
			if($_SESSION['temp']['sort']=='scan_time_distributed ASC'){
				$_SESSION['temp']['sort']='scan_time_distributed DESC';
			}
			else{
				$_SESSION['temp']['sort']='scan_time_distributed ASC';
			}
		}
		if($_GET['sort']=='Ratio'){
			if($_SESSION['temp']['sort']=='scan_ratio ASC'){
				$_SESSION['temp']['sort']='scan_ratio DESC';
			}
			else{
				$_SESSION['temp']['sort']='scan_ratio ASC';
			}
		}
		
		
	}

?>