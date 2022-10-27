

<?php 
$page_title='Still-Open';
include ('header.php'); 
redirect_if_not_logged_in();
?>


<div class="container">

	
	<?php 
	if(empty($_POST)){$_SESSION['temp']['addscan'] = array();}?>
	<?php $_SESSION['temp']['filter'] = array(); ?>
	<?php include ('navbar.php'); ?>
	
	
	<?php
		
	edit_scan_procedure($db);
	// show($_POST);
	// show($_SESSION['temp']);
	
	
	?>
	
	
	
	
	<?php
	
	if(!empty($_POST['view'])){
		$_SESSION['temp']['summary']['view']=$_POST['view'];
		
		
	}
	
		if (empty($_SESSION['temp']['summary']['view'])){
			$_SESSION['temp']['summary']['view']='Block';
			
		}	
	
	
	
	$date='';
	
	if (!empty($_POST['load_scan_id'])){
		$_SESSION['temp']['addscan']['operator']=$_POST['operator_code'];
		$_SESSION['temp']['addscan']['timetag']=$_POST['timetag'];
		$operatorname=get_operator_name($db,$_SESSION['temp']['addscan']['operator']);
			
			
		$_SESSION['temp']['summary']['operatorname']=$operatorname;
		$_SESSION['temp']['summary']['operator']=$_POST['operator_code'];
		$_SESSION['temp']['summary']['operatorcode']=$_POST['operator_code'];
		$_SESSION['temp']['summary']['workarea']='All';
		$_SESSION['temp']['summary']['yearmonth']='All';
		$_SESSION['temp']['summary']['days']=date('Y-m-d',$_POST['timetag']);
		
	}
	// if(empty()){

	// }
	
	function load_scan_day2($db,$operator='All',$date='All'){
        
        $nbr_filter=0;
        $daysfilter='';
        if($date=='All'){
            if ($_SESSION['temp']['summary']['days']<>'All'){
                $daysfilter=''.date('d M',strtotime($_SESSION['temp']['summary']['days']));
                $nbr_filter=$nbr_filter+1;
                $date=$_SESSION['temp']['summary']['days'];
            }
        }else{
            $daysfilter=''.date('d M',strtotime($date));
            $nbr_filter=$nbr_filter+1;
            
        }
        $operatorfilter='';
        if ($_SESSION['temp']['summary']['operatorname']<>'All'){
            if ($_SESSION['temp']['summary']['operatorname']=='blank'){
                $operatorfilter="- [blank]";
                }
                else{
                    $operatorfilter='- '.$_SESSION['temp']['summary']['operatorname'];
                    $operator=$_SESSION['temp']['summary']['operatorcode'];
                }
            $nbr_filter=$nbr_filter+1;
        }
        
        

        
        echo'<form id="form-view" method="post">';
        echo'<div class="row" >';
        echo'<Center><h4 onclick="document.forms[\'form-view\'].submit();">';
        
        
        echo''.$daysfilter.' '.$operatorfilter.' ';
        
        if (($_SESSION['temp']['summary']['operatorname']<>'All')and($_SESSION['temp']['summary']['days']<>'All')){button_log($db,$_SESSION['temp']['summary']['operatorcode'],$date);}
        echo'</h4></Center>';
        
        echo '<input type="hidden" id="view" name="view" value="';
            if($_SESSION['temp']['summary']['view']=='Normal'){echo'Block';}else{echo'Normal';}
            echo'">';
        echo'</div>';
        echo'</form>';
        
        
        
            
          //  show_table_scan_day($db,$allscan,$nbr_filter,$daysfilter,$operatorfilter);
        
            show_all_scan_operator($db,$operator,$date);
           
    }
	
	
	
	
	
	
	
	
	
	 echo'<div class="row" >';
		echo'<div class="col-sm-3">';
			echo'<div class="row" >';
			echo'<Center><h3>List of Scan Still Open</h3></Center>';
			echo'</div>';
			show_list_still_open($db);
		echo'</div>';
		// echo'<div class="col-sm-1">';
		// echo'</div>';
		
		if(!empty($_SESSION['temp']['addscan']['operator'])){
			echo'<div class="col-sm-5 ">';
			// echo'<div class="row " ">';
				$_SESSION['temp']['sort']='scan_timetag ASC';
                echo'<div class="row " style="font-size:6px;">';load_scan_day2($db,$_SESSION['temp']['summary']['operator'],date('Y-m-d', strtotime($_SESSION['temp']['summary']['days'].' - 1 day' )));echo'</div>';
                separator();
                
				echo'<div class="row " style="background:#d7eaff;border-radius: 30px;border:3px solid #46515e;padding:5px;">';load_scan_day2($db,$_SESSION['temp']['summary']['operator'],$_SESSION['temp']['summary']['days']);echo'</div>';
                separator();
                echo'<div class="row " style="font-size:6px;">';load_scan_day2($db,$_SESSION['temp']['summary']['operator'],date('Y-m-d', strtotime($_SESSION['temp']['summary']['days'].' + 1 day' )));echo'</div>';
			 echo'</div>';
			
			
			 if(!empty($_POST['load_scan_id'])&&!empty($_POST['load_scan_id'])AND empty($_POST['add_a_scan'])){
				echo'<div class="col-sm-4 editpanel">';
				// echo'<div class="row editpanel" >';
				$scanid=$_POST['load_scan_id'];
				edit_scan($db,$scanid);
				
				 if ($_SESSION['temp']['summary']['days']<>'All' and  ((empty($_POST['add_a_scan_init'])and empty($_POST['add_a_scan']))or !empty($_POST['refresh']))){
					echo'<form  method="post">';
					echo'<input type="hidden" id="add_a_scan" name="add_a_scan" value="ok">';
					echo'<input type="hidden" id="load_scan_id" name="load_scan_id" value="'.$_POST['load_scan_id'].'">';	
					echo'<input type="hidden" id="timetag" name="timetag" value="'.$_POST['timetag'].'">';		
					echo'<input type="hidden" id="operator_code" name="operator_code" value="'.$_POST['operator_code'].'">';							
					echo'<br><br>';
					// echo'<div class="row">';
				
					echo' <center><button type="submit" value="ok" name="add_a_scan_init" class="btn btn-primary">Add a Scan</button></center><br>';
					
						
					// echo'</div>';
					echo'<br></form>';
				}
				
				
				echo'</div>'; 
			 }
			
			
			 
			 
			 
			 
			 
			 
			 
			 if (!empty($_POST['add_a_scan'])and empty($_POST['refresh'])){
				echo'<div class="col-sm-4 editpanel">';
				// echo'<div class="row editpanel" >';
				$date=$_SESSION['temp']['summary']['days'];
				if(empty($_POST['add_a_scan_init'])){$_POST['add_a_scan_init']='ok';}
				
				add_scan_window($db,$date);
				
				 echo'</div>'; 
			 }
			 
			 echo'</div>'; 
			 
			 
			 
			echo'</div>'; 
		}
	 echo'</div>';
	//show($_POST);
	//show($_SESSION);
	?>
</div>
