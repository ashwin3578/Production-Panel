<?php
date_default_timezone_set('Australia/Brisbane');

function managing_POST_scan_summary($db){
    if(!empty($_POST['load_scan_id'])){
        $a_scan=load_a_scan($db,$_POST['load_scan_id']);
        //$_POST['days']=$a_scan['scan_date'];
        $_SESSION['temp']['days']['workarea']=date('Y-m-d',strtotime($a_scan['scan_date']));
       
        if(($_SESSION['temp']['summary']['workarea']<>'All')){
            $_SESSION['temp']['summary']['workarea']=$a_scan['WorkArea'];
        }
    }


    if (empty($_SESSION['temp']['summary']['yearmonth'])){
        $_POST['monthyear']=date('M Y',time());
    }
    if (empty($_SESSION['temp']['summary']['days'])){
        $_POST['days']=date('Y-m-d',time());
    }
   
    $_POST['days'] =str_replace('<b><big>', '',$_POST['days']);	
    $_POST['days'] =str_replace('</big></b>', '',$_POST['days']);	
    $_POST['workarea'] =str_replace('<b><big>', '',$_POST['workarea']);	
    $_POST['workarea'] =str_replace('</big></b>', '',$_POST['workarea']);	
    $_POST['monthyear'] =str_replace('<b><big>', '',$_POST['monthyear']);	
    $_POST['monthyear'] =str_replace('</big></b>', '',$_POST['monthyear']);	
    $_POST['operatorname'] =str_replace('<b><big>', '',$_POST['operatorname']);	
    $_POST['operatornames'] =str_replace('</big></b>', '',$_POST['operatorname']);	
    $_POST['days'] =str_replace(' ', '',$_POST['days']);
    $_SESSION['temp']['summary']['days'] =str_replace(' ', '',$_SESSION['temp']['summary']['days']);	

    
    
    if(!empty($_POST['monthyear'])){

        
        $_POST['month']=date('n',strtotime('1 '.$_POST['monthyear']));
        
        $_POST['year']=date('Y',strtotime('1 '.$_POST['monthyear']));
        
       
	}
   
    
    if ($_SESSION['temp']['summary']['workarea']==$_POST['workarea']){
        unset($_SESSION['temp']['summary']['workarea']);
        $_POST['workarea']='All';
    }
    if ($_SESSION['temp']['summary']['days']==$_POST['days']){
        unset($_SESSION['temp']['summary']['days']);
        $_POST['days']='All';
    }
    if ($_SESSION['temp']['summary']['operatorcode']==get_operator_code($db,$_POST['operatorname'])){
        unset($_SESSION['temp']['summary']['operatorcode']);
        unset($_SESSION['temp']['summary']['operatorname']);
        $_POST['operatorcode']='All';
        $_POST['operatorname']='All';   
    }
    
    if ($_SESSION['temp']['summary']['yearmonth']==$_POST['year'].$_POST['month']){
        unset($_SESSION['temp']['summary']['yearmonth']);
        unset($_SESSION['temp']['summary']['year']);
        unset($_SESSION['temp']['summary']['month']);
        $_POST['month']='All';
        unset($_POST['year']);   
    }
    if(!empty($_POST['workarea'])){
		$_SESSION['temp']['summary']['workarea']=$_POST['workarea'];
        if($_SESSION['temp']['summary']['workarea']<>'All'){
            unset($_SESSION['temp']['summary']['operatorcode']);
            unset($_SESSION['temp']['summary']['operatorname']);
        }
    }
    
    if(!empty($_POST['month'])){
		$_SESSION['temp']['summary']['yearmonth']=$_POST['year'].$_POST['month'];
		$_SESSION['temp']['summary']['year']=$_POST['year'];
		$_SESSION['temp']['summary']['month']=$_POST['month'];
	}
	
    
	if(!empty($_POST['operatorname'])){
		$_SESSION['temp']['summary']['operatorname']=$_POST['operatorname'];
        if(empty($_POST['operatorcode'])){
            $_SESSION['temp']['summary']['operatorcode']=get_operator_code($db,$_POST['operatorname']);
            
        }else{
            $_SESSION['temp']['summary']['operatorcode']=$_POST['operatorcode'];
        }
		
		
	}
	
		if (empty($_SESSION['temp']['summary']['operatorcode'])){
			$_SESSION['temp']['summary']['operatorcode']='All';
			$_SESSION['temp']['summary']['operatorname']='All';
		}
	if(!empty($_POST['days'])){
		$_SESSION['temp']['summary']['days']=$_POST['days'];
		
		
	}
	
		if (empty($_SESSION['temp']['summary']['days'])){
			$_SESSION['temp']['summary']['days']='All';
			
		}
		
	
	
		if (empty($_SESSION['temp']['summary']['workarea'])){
			$_SESSION['temp']['summary']['workarea']='All';
			
		}	
	
	
	
	
	
	
	if(!empty($_POST['mode'])){
		$_SESSION['temp']['summary']['mode']=$_POST['mode'];
		
		
	}
	
		if (empty($_SESSION['temp']['summary']['mode'])){
			$_SESSION['temp']['summary']['mode']='Total';
			
		}	

	if(!empty($_POST['view'])){
		$_SESSION['temp']['summary']['view']=$_POST['view'];
		
		
	}
	
		if (empty($_SESSION['temp']['summary']['view'])){
			$_SESSION['temp']['summary']['view']='Block';
			
		}	
		
		
        
	
	// show($_SESSION['temp']);
	// show($_POST);
}

function get_summary_listmonth_v2($db,$filter1='',$filter2='',$filter3=''){
    $query='SELECT  [Month_Year],themonth,theyear
    
    ,sum([total]/3600) as total
    
    FROM [barcode].[dbo].[Summary-month-view]
    LEFT JOIN
    operator
    ON
    scan_operatorcode=operator_code
    
    
    WHERE 
    1=1
    
    
    '.$filter1.'
    '.$filter2.'
    '.$filter3.'
    
    GROUP BY Month_Year,themonth,theyear
    ORDER BY Month_Year DESC
    ';
    
    $sql = $db->prepare($query); 
    // show($query);
    $sql->execute();

    $row=$sql->fetchall();
    
    return $row;
        
}

function get_summary_listday_v2($db,$filter1='',$filter2='',$filter3=''){
    $query='SELECT top 30 scan_date
    
    ,sum([total_hours]) as total_hours
    
    FROM [barcode].[dbo].[MIS_Operator_hours_scanned]
   
    WHERE 
    1=1
    
    
    '.$filter1.'
    '.$filter2.'
    '.$filter3.'
    
    GROUP BY scan_date
    ORDER BY scan_date DESC
    ';
    
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();
    
    return $row;
        
}

function get_summary_listoperator_v2($db,$filter1='',$filter2='',$filter3=''){
    $query='SELECT [operator_fullname]
    
    ,sum([total_hours]) as total_hours
    
    FROM [barcode].[dbo].[MIS_Operator_hours_scanned]
    LEFT OUTER JOIN
    dbo.MIS_List ON dbo.MIS_List.ManufactureIssueNumber = scan_jobnumber
    WHERE 
    1=1
    
    '.$filter1.'
    '.$filter2.'
    '.$filter3.'
    
    GROUP BY operator_fullname
    ORDER BY operator_fullname ASC
    ';
    
    $sql = $db->prepare($query); 
   // show($query);
    $sql->execute();

    $row=$sql->fetchall();
    
    return $row;
        
}

function get_summary_listWorkarea_v2($db,$filter1='',$filter2='',$filter3=''){
    $query='SELECT distinct[WorkArea]
    
    
    
    FROM [barcode].[dbo].[MIS_Operator_hours_scanned]
    LEFT OUTER JOIN
    dbo.MIS_List ON dbo.MIS_List.ManufactureIssueNumber = scan_jobnumber

    
    WHERE 
    1=1
    
    '.$filter1.'
    '.$filter2.'
    '.$filter3.'
    
    
    ORDER BY WorkArea ASC
    ';
    
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();
    
    return $row;
        
}



function get_summary_listday($db,$workareafilter='',$operatorfilter='',$daysfilter=''){
    $query='SELECT TOP 31 ([scan_date])as theday, 
    year([scan_date])as theyear,
    sum([scan_time_distributed]) as total';
    
    $query=$query.' FROM dbo.scan 
    LEFT JOIN
    operator
    ON
    scan_operatorcode=operator_code
    left join
	MIS_LIST
	on [ManufactureIssueNumber]=scan_jobnumber
    
    WHERE 
    scan_statut=\'start\'
    
    '.$workare11afilter.'
    '.$operatorfilter.'
    '.$daysfilter.'
    GROUP BY scan_date
    ORDER BY scan_date DESC
    ';
    
    $sql = $db->prepare($query); 
        //   show($query);
    $sql->execute();

    $row=$sql->fetchall();
    
    return $row;
        
}


function get_summary_listoperator($db,$workareafilter='',$monthfilter='',$daysfilter=''){
    $query='SELECT operator.operator_fullname,
    operator.operator_code,
    total_time FROM dbo.scan 
    LEFT JOIN
    operator
    ON
    scan_operatorcode=operator_code
    left join
	MIS_LIST
	on [ManufactureIssueNumber]=scan_jobnumber
    left join(
		SELECT operator_fullname,
		operator_code,
		sum([scan_time_distributed])as total_time FROM dbo.scan 
		LEFT JOIN
		operator
		ON
		scan_operatorcode=operator_code
		left join
		MIS_LIST
		on [ManufactureIssueNumber]=scan_jobnumber
    
		WHERE 
		scan_statut=\'start\'
        
        '.$monthfilter.'
        '.$daysfilter.'
		GROUP BY operator_fullname,operator_code
		
		) as temp on temp.operator_fullname=operator.operator_fullname

    WHERE 
    scan_statut=\'start\'
    '.$workareafilter.'
    '.$monthfilter.'
    '.$daysfilter.'
    GROUP BY operator.operator_fullname,operator.operator_code,temp.operator_fullname,temp.operator_code,total_time
	ORDER BY operator.operator_fullname ASC
    
  ';
    
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();
    
    return $row;
        
}

function get_summary_listworkarea($db,$workareafilter='',$operatorfilter='',$daysfilter=''){
    $query='SELECT WorkArea,
    1,
    sum([scan_time_distributed])';
    
    $query=$query.' FROM dbo.scan 
    LEFT JOIN
    operator
    ON
    scan_operatorcode=operator_code
    left join
	MIS_LIST
	on [ManufactureIssueNumber]=scan_jobnumber
    
    WHERE 
    scan_statut=\'start\'
    
    '.$workareafilter.'
    '.$operatorfilter.'
    '.$daysfilter.'
    GROUP BY WorkArea
	ORDER BY WorkArea ASC
    ';
    
    $sql = $db->prepare($query); 
    
    $sql->execute();

    $row=$sql->fetchall();
    
    return $row;
        
}




function get_operator_code($db,$operator_name){
    $query='SELECT operator_code FROM dbo.operator 
	
	WHERE operator_fullname=\''.$operator_name.'\'	
	
	
	';
	$sql = $db->prepare($query); 

	$sql->execute();

	$row=$sql->fetch();
	
	return $row[0];
}


function all_filter_list_2(){
	$workareafilter='';
	if ($_SESSION['temp']['summary']['workarea']<>'All'){
		$workareafilter=' AND WorkArea=\''.$_SESSION['temp']['summary']['workarea'].'\' ';
	}
	$operatorfilter='';
	if ($_SESSION['temp']['summary']['operatorname']<>'All'){
		if ($_SESSION['temp']['summary']['operatorname']=='blank'){
			$operatorfilter=" AND [operator_code] is null";
			}
			else{
				 $operatorfilter=' AND [operator_fullname]=\''.$_SESSION['temp']['summary']['operatorname'].'\' ';
			}
	}
	$daysfilter='';
	if ($_SESSION['temp']['summary']['days']<>'All'){
		$daysfilter=' AND [scan_date]=\''.$_SESSION['temp']['summary']['days'].'\' ';
	}
	$yearmonthfilter='';
	if ($_SESSION['temp']['summary']['yearmonth']<>'All'){
		$yearmonthfilter=' AND month([scan_date])=\''.$_SESSION['temp']['summary']['month'].'\' 
		AND year([scan_date])=\''.$_SESSION['temp']['summary']['year'].'\'';
	}
	$filter['workareafilter']=$workareafilter;
	$filter['operatorfilter']=$operatorfilter;
	$filter['daysfilter']=$daysfilter;
	$filter['yearmonthfilter']=$yearmonthfilter;
	
	return $filter;
}

function load_scan_day3($db,$operator='All',$date='All'){
    
        
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
	echo'<Center><h4 >'.$daysfilter.' '.$operatorfilter.' ';
	
	if (($_SESSION['temp']['summary']['operatorname']<>'All')and($_SESSION['temp']['summary']['days']<>'All')){button_log($db,$_SESSION['temp']['summary']['operatorcode'],$date);}
	echo'</h4></Center>';
	
	echo '<input type="hidden" id="view" name="view" value="';
		if($_SESSION['temp']['summary']['view']=='Normal'){echo'Block';}else{echo'Normal';}
		echo'">';
	echo'</div>';
	echo'</form>';
	
	
	
		
	  //  show_table_scan_day($db,$allscan,$nbr_filter,$daysfilter,$operatorfilter);
	
		//show_all_scan_operator($db,$operator,$date);
        show_all_scan_operator2($db,$operator,$date);
	   
}

function show_all_scan_operator2($db,$operator,$date){ //BlockView 
    
   $allscan=load_all_scan($db,$date,get_operator_name($db,$operator),'All',0);
       
           
    $last_finished_time=0;
    echo'<div class="row " ">';
    $width=12/max_job_open($db,$operator,$date);
    foreach ($allscan as &$scan){
        echo'<form  id="form-'.$scan['scan_id'].'" method="post">';
        echo '<input type="hidden" id="load_scan_id" name="load_scan_id" value="'.$scan['scan_id'].'">';
        echo '<input type="hidden" id="operator_code" name="operator_code" value="'.$scan['scan_operatorcode'].'">';
        echo '<input type="hidden" id="timetag" name="timetag" value="'.$scan['scan_timetag'].'">';
        echo '<input type="hidden" id="timetag_finish" name="timetag_finish" value="'.$scan['scan_timetag_finish'].'">';
        echo '<input type="hidden" id="jobnumber" name="jobnumber" value="'.$scan['scan_jobnumber'].'">';
        
        if($scan['scan_timetag']>$last_finished_time and $last_finished_time<>0){
            echo'</div>';
            
            
                if($scan['scan_timetag']>($last_finished_time+280)){
                echo'<br>';
                echo'<div class="row " ">';
                    echo'<div class="col-sm-12 scanner-sidepanel break-panel">';
                    $break_time=$scan['scan_timetag']-$last_finished_time;
                    echo'Break : ';
                    if($break_time>=3600){echo floor(($break_time)/3600).' hours ';}
                    echo floor((($break_time)%3600)/60).' min';
                    echo'</div>';
                echo'</div>';
                echo'<br>';
                }
            
            
            echo'<div class="row  " ">';
        }
        
        
        echo'<div class="col-sm-'.$width.' >" ';
       // echo'onclick="Test_alert();"';
        //Test_alert
        echo'onclick="'."load_scan_id('".$scan['scan_id']."','".$scan['scan_operatorcode']."','".$scan['scan_timetag']."','".$scan['scan_timetag_finish']."','".$scan['scan_jobnumber']."')".';"';
        //echo'onclick="document.forms[\'form-'.$scan['scan_id'].'\'].submit();"';
        
        echo'>';
        

            echo'<div class=" ';
            echo ' scanner-sidepanel ';
            
            if(!empty($_POST['load_scan_id'])&&($_POST['load_scan_id'])==$scan['scan_id']){echo' scanner-selected text-white';}
            if($scan['scan_timetag_finish']==''){echo 'still_open ';}
            echo'">';
            echo date('G:i:s',$scan['scan_timetag']);
            echo'<br>';
            echo get_product_code($db,$scan['scan_jobnumber']);
            echo ' - ';
            echo $scan['scan_jobnumber'];
            //echo'- ('.$scan['scan_jobnumber'].')';
            //if($scan['scan_timetag_finish']<>''){echo ' - '.showhours($scan['scan_timetag_finish']-$scan['scan_timetag']).' hours';}
            echo'<br>';
            if($scan['scan_timetag_finish']<>''){echo date('G:i:s',$scan['scan_timetag_finish']);}
            echo'<br>';
            echo'</div>';
        echo'</div>';
        
        
        
        if($scan['scan_timetag_finish']<>''){$last_finished_time=max($last_finished_time,$scan['scan_timetag_finish']);}else{$last_finished_time=0;}
    echo'</form>';	
    }
    
    echo'</div>';
}

function summary_show_operator_detail($db){
    all_javascript();
    echo'<div class="col-sm-4">';
        
        
        //show($date);
        if($_SESSION['temp']['summary']['days']<>'All' and $_SESSION['temp']['summary']['operatorcode']<>'All'){
            //load_scan_day2($db,$operator,$date);
            echo'<div class="row " style="font-size:6px;">';
            //echo'<div class="col-sm-10">';
                load_scan_day3($db,$_SESSION['temp']['summary']['operator'],date('Y-m-d', strtotime($_SESSION['temp']['summary']['days'].' - 1 day' )));
            echo'</div>';
            
            separator();
            
            echo'<div class="row " style="background:#d7eaff;border-radius: 30px;border:3px solid #46515e;padding:5px;">';
                load_scan_day3($db,$_SESSION['temp']['summary']['operator'],$_SESSION['temp']['summary']['days']);
            echo'</div>';
            separator();
            echo'<div class="row " style="font-size:6px;">';load_scan_day3($db,$_SESSION['temp']['summary']['operator'],date('Y-m-d', strtotime($_SESSION['temp']['summary']['days'].' + 1 day' )));echo'</div>';
        

        }
    echo'</div>';
    echo'<div class="col-sm-3">';		
        
        if(!empty($_POST['load_scan_id'])&&!empty($_POST['load_scan_id'])){
            echo'<br><div class="row editpanel" >';
            $scanid=$_POST['load_scan_id'];
            edit_scan2($db,$scanid);
            echo'</div>'; 
        }
        
        if ($_SESSION['temp']['summary']['days']<>'All' and  ((empty($_POST['add_a_scan_init'])and empty($_POST['add_a_scan']))or !empty($_POST['refresh']))){
                echo'<form  method="post">';
                echo'<input type="hidden" id="add_a_scan" name="add_a_scan" value="ok">';	
                echo'<br><br>';
                echo'<div class="row">';
                echo'</form>';
                //echo' <center><div class="btn btn-primary"   onclick="add_a_scan();">Add a Scan</div></center><br>';
                make_button('Add a Scan','add_a_scan()');
                    
                echo'</div><br>';
                
        }
        
        if (!empty($_POST['add_a_scan'])and empty($_POST['refresh'])){
            echo'<br><div class="row editpanel" >';
            $date=$_SESSION['temp']['summary']['days'];
            if(empty($_POST['add_a_scan_init'])){$_POST['add_a_scan_init']='ok';}
            
            add_scan_window2($db,$date);
            
            echo'</div>'; 
        }
        
    echo'</div>';
}

function add_scan_window2($db,$date){
	echo'<form  method="post">';
		echo'<input type="hidden" id="add_a_scan" name="add_a_scan" value="ok">';	
	echo'<br><br>';
	echo'<div class="row">';
		echo'<div class="col-sm-12">';
		echo'<h3><center>Add Scan - '.$date.'</center></h3>';
		echo'</div>';
	echo'</div>';
	echo'<br><br>';
	
	if((empty($_POST['jobmanual'])and !empty($_POST['jobentry']))){$_POST['operatordone']='ok';}
	
	if((!empty($_POST['add_a_scan_init']))and empty($_POST['addscan_operator'])or!empty($_POST['back_init'])){
		
			echo'<input type="hidden" id="add_a_scan_init" name="add_a_scan_init" value="ok">';
		
		echo'<div class="row">';
			echo'<div class="col-sm-12">';
			if(empty($_SESSION['temp']['summary']['operatorname'])){
				
				
				echo '<h5>WorkArea</h5>';
				if(empty($_POST['addscan_workarea'])){$_POST['addscan_workarea']='All';}
				echo '<select name="addscan_workarea" onchange="this.form.submit()" class="form-control" id="sel1">';
				$listWorkarea=listworkarea($db);
							echo"<option value='All' ";
							if(!empty($_POST['addscan_workarea'])&&$_POST['addscan_workarea']=='All'){echo 'selected';}
							echo">All</option>";
				foreach ($listWorkarea as &$workarea){
						echo"<option ";
						if(!empty($_POST['addscan_workarea'])&&$_POST['addscan_workarea']==$workarea['workarea']){echo 'selected';}
						echo" value='".$workarea['workarea']."' >".$workarea['workarea']."</option>";
						}
				echo '</select>';
			}else{$_POST['addscan_workarea']='All';}
		  echo '<h5>Operator</h5>';
		  echo '<select name="addscan_operator[]"  class="form-control" id="seloperator" >';
			$listOperator=listoperator($db,$_POST['addscan_workarea']);
						
			foreach ($listOperator as &$Operator){
					echo"<option value='".$Operator['operator_code']."'";
					if(!empty($_SESSION['temp']['filter']['filter_operator'])&&$_SESSION['temp']['filter']['filter_operator']==$Operator['operator_fullname']){echo 'selected';}
					if (($_SESSION['temp']['summary']['operatorname']==$Operator['operator_fullname'])){echo 'selected';}
					echo">".$Operator['operator_fullname']."</option>";
					}
			echo '</select>';
			echo'</div>';
		echo'</div><br>';
		echo'<div class="row">';
		
		//echo' <center><button type="submit" value="ok" name="operatordone" class="btn btn-primary">Next</button></center><br>';

        echo'<div class="col-sm-6">';
        make_button('Cancel','reload_all_page()');
        echo'</div>';
        echo'<div class="col-sm-6">';
        make_button('Next','operatordone();');
        echo'</div>';

        
        
		
        
        
        
        //echo' <a href="manage_employee.php"  ><button type="button" class="btn btn-primary">View employee</button></a><br><br>';
			
		echo'</div><br>';
		
	}
	elseif((!empty($_POST['operatordone'])) ){
		echo'  	  
		  <script>
			 function showMe (box) {
			
			var chboxs = document.getElementsByName("jobentry");
			var vis = "none";
			for(var i=0;i<chboxs.length;i++) { 
				if(chboxs[i].checked){
				
				}
				else
				{
				 vis = "block";
					break;	
				}
			}
			document.getElementById(box).style.display = vis;
		
		}
		function dontshowMe (box) {
			
			var chboxs = document.getElementsByName("jobentry");
			var vis = "none";
			for(var i=0;i<chboxs.length;i++) { 
				if(chboxs[i].checked){
				vis = "block";
					break;	
				}
				
			}
			document.getElementById(box).style.display = vis;
		}
		
		function addHash(elem) {
				  var val = elem.value;
				  if(!val.match(/^MIS/)) {
					elem.value = "MIS" + val;
				  }
				}
		  </script>';
		echo'<div class="row">';
		echo '<h5>Operator :</h5>';
		$operatorcode=$_POST['addscan_operator'] ;
			
			echo '<i>'.get_operator_name($db,$operatorcode).' </i><br>';
			echo'<input type="hidden"  id="seloperator" name="addscan_operator" value="'.$operatorcode.'">';
			
		
		echo '</div>';
		echo'<div class="row">';
		  echo '<h5>Manufacture Issue Number :</h5>';
		echo '</div>';  
		echo'<div class="row">';
		  echo'<label for="jobentry" >Liste &nbsp;&nbsp;&nbsp;</label><input type="checkbox" name="jobentry" id="jobentry" onclick="showMe(\'joblist\'); dontshowMe(\'jobmanualshow\') "  class=" switch_1" ><label for="jobentry" >&nbsp;&nbsp;&nbsp;Manual </label>';
		  echo'<br>';
		   echo '<select  name="joblist" class="form-control" id="joblist" onchange="updatejobmanual()">';
			
				
				load_list_MIS_open($db);
				
					
			echo '</select>';
		  //echo'<br>';
		  echo'<script>
          function updatejobmanual(){
            document.getElementById("jobmanual").value=document.getElementById("joblist").value;;
            
          }
          </script>';

		  echo '<div style="display:none" id="jobmanualshow">';
		   echo'<input type="textbox" placeholder="MIS12345" name="jobmanual" class="form-control" id="jobmanual"  onkeyup="addHash(this)" >';
		  echo '</div>';
		echo '</div><br>'; 		
		echo'<div class="row" >';
		
			echo'<div class="col-sm-6">';
			//echo'<center><button type="submit" value="ok" name="MISdone" class="btn btn-primary defaultsink" style="display:none;">Next</button></center>';
			//echo'<center><button type="submit" value="ok" name="back_init" class="btn btn-primary">Previous</button></center>';
            make_button('Previous','add_a_scan();');
			echo'</div>';
			echo'<div class="col-sm-6">';
            make_button('Next','MISdone();');
			//echo'<center><button type="submit" value="ok" name="MISdone" class="btn btn-primary " >Next</button></center>';
			echo'</div>';
			
		echo'</div><br>';		
				
				
				
				
				
				
				
			
	}
	elseif((!empty($_POST['MISdone']))and empty($_POST['save'])){
		
		if (!empty($_POST['jobentry'])){
				$jobnumber=$_POST['jobmanual'];
			}
			else{
				$jobnumber=$_POST['joblist'];
			}
		echo'<input type="hidden"  name="jobentry" value="'.$_POST['jobentry'].'">';
		echo'<input type="hidden"  name="joblist" value="'.$_POST['joblist'].'">';
		echo'<input type="hidden"  id="jobmanual" name="jobmanual" value="'.$_POST['jobmanual'].'">';
        echo'<input type="hidden"  id="hastimefinished" name="jobmanual" value="0">';
		echo'<input type="hidden"  name="MISdone" value="ok">';
		
		
		
		echo'<div class="row">';
		echo '<h5>Operator :</h5>';
		$operatorcode=$_POST['addscan_operator'] ;
			
			echo '<i>'.get_operator_name($db,$operatorcode).' </i><br>';
			echo'<input type="hidden"  id="seloperator" name="addscan_operator" value="'.$operatorcode.'">';
			
		
		echo '</div>';
		
		echo'<div class="row">';
		  echo 'MIS : '.$jobnumber;
		  echo '<br>Product : '.get_product_code($db,$jobnumber);
		echo '</div><br>'; 
		
		echo'<div class="row">';
			echo'<div class="col-sm-3">';
			echo'Time Started';
			echo'</div>';
			echo'<div class="col-sm-8">';
			  echo'<input type="time" name="timestart" value="06:00" id="timestart" class="form-control" id="usr">';
			
			echo'</div>';
			
		echo'</div><br>';
		
		// echo '<input type="hidden" id="operator_code" name="operator_code" value="'.$scan['scan_operatorcode'].'">';
		// echo '<input type="hidden" id="timetag" name="timetag" value="'.$scan['scan_timetag'].'">';
		// echo '<input type="hidden" id="timetag_finish" name="timetag_finish" value="'.$scan['scan_timetag_finish'].'">';
		// echo '<input type="hidden" id="jobnumber" name="jobnumber" value="'.$scan['scan_jobnumber'].'">';
		
            
                echo'<div class="row" >';
                echo'<div class="col-sm-12">';
                    echo'<i><center>Add Finished Scan</center></i>';
                    echo'</div>';
                echo'</div>';
                echo'<div class="row">';
                echo'<div class="col-sm-12">';
                    echo'<center><input type="checkbox" id="finish_it" onclick="show_finished_box()" value="on"  name="add_finished" class="allvenueall2 switch_1" ';
                    if(!empty($_POST['finish_it'])){echo "checked";}
                    echo '></center>';
                    echo'</div>';
                echo'</div><br>';
                echo'<script>
                function show_finished_box(){
                    //alert("test");
                    var chboxs = document.getElementsByName("add_finished");
                    var vis = "block";
                    var thevalue="1";
                    for(var i=0;i<chboxs.length;i++) { 
                        if(chboxs[i].checked){
                        
                        }
                        else
                        {
                        vis = "none";
                        thevalue="0";
                            break;	
                        }
                    }
                    
                    //alert(vis);
                    //document.getElementById(box).style.display = vis;
                    document.getElementById("box-finished").style.display = vis;
                    document.getElementById("hastimefinished").value=thevalue;
                }
                </script>';
                echo'<div style="display:none" id="box-finished">';     
                    
                
            
                
                
                
                echo '<div class="row" >';
                    echo '<div class="col-sm-3">';
                    echo 'Time Finished';
                    echo '</div>';
                    echo '<div class="col-sm-8">';
                    echo '<input type="time" name="timefinish" value="09:00" id="timefinish" class="form-control" id="usr" >';
                    echo '</div>';
                    echo '<div class="col-sm-2">';
                    
                        // echo ' <button type="submit" value="ok" name="remove_finish_scan" class="btn btn-default" aria-label="Left Align">
                        //     <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                        //     </button>';
                    
                echo'</div>';
                    
                echo'</div><br>';
            
                
                echo'<div class="row">';
                    echo'<div class="col-sm-12">';
                        echo'<center>Total Hours : <input disabled id="diff"></center>


                            <script>
                            var timestart = document.getElementById("timestart").value;
                            var timefinish = document.getElementById("timefinish").value;

                            document.getElementById("timestart").onchange = function() {diff(timestart,timefinish)};
                            document.getElementById("timefinish").onchange = function() {diff(timestart,timefinish)};


                            function diff(timestart, timefinish) {
                                timestart = document.getElementById("timestart").value; //to update time value in each input bar
                                timefinish = document.getElementById("timefinish").value; //to update time value in each input bar
                                
                                timestart = timestart.split(":");
                                timefinish = timefinish.split(":");
                                var startDate = new Date(0, 0, 0, timestart[0], timestart[1], 0);
                                var endDate = new Date(0, 0, 0, timefinish[0], timefinish[1], 0);
                                var diff = endDate.getTime() - startDate.getTime();
                                var hours = Math.floor(diff / 1000 / 60 / 60);
                                diff -= hours * 1000 * 60 * 60;
                                var minutes = Math.floor(diff / 1000 / 60);

                                return (hours < 9 ? "0" : "") + hours + ":" + (minutes < 9 ? "0" : "") + minutes;
                            }

                            setInterval(function(){document.getElementById("diff").value = diff(timestart, timefinish);}, 50); //to update time every second (1000 is 1 sec interval and function encasing original code you had down here is because setInterval only reads functions) You can change how fast the time updates by lowering the time interval
                            </script>';
                    echo'</div>';
                echo'</div><br>';
        echo'</div>'; 
		
		
		echo'<div class="row">';
			echo'<div class="col-sm-6">';
			//echo'<center><button type="submit" value="ok" name="operatordone" class="btn btn-primary">Previous</button></center>';
            make_button('Previous','operatordone();');
			echo'</div>';
			echo'<div class="col-sm-6">';
			//echo'<center><button type="submit" value="ok" name="save" class="btn btn-primary">Save Scan</button></center>';
            make_button('Save Scan','save_scan();');
			echo'</div>';
			//echo' <a href="manage_employee.php"  ><button type="button" class="btn btn-primary">View employee</button></a><br><br>';
			
		echo'</div><br>';	
		
		
	}elseif(!empty($_POST['save'])){
		//savescan($db,$operator,$jobnumber,$timestamp);
		if (($_POST['hastimefinished']<>1)){
            unset($_POST['timefinish']);
        }
		if (!empty($_POST['jobentry'])){
				$jobnumber=$_POST['jobmanual'];
			}
			else{
				$jobnumber=$_POST['joblist'];
			}
		$testdate=new datetime($date.''.$_POST['timestart']);
		$timestamp_start = $testdate->getTimestamp();
		if(!empty($_POST['timefinish'])){
			$testdate=new datetime($date.' '.$_POST['timefinish']);
			$timestamp_finish = $testdate->getTimestamp();
        }
		
		
        $operatorcode=$_POST['addscan_operator'];
        savescan($db,$operatorcode,$jobnumber,$timestamp_start);
        echo'<div class="row"><div class="alert alert-success" role="alert">
                Scan added : '.get_operator_name($db,$operatorcode).' '.date('Y-m-d G:i:s',$timestamp_start).' on '.$jobnumber.'
            </div></div><br>';
        
        
        if(!empty($_POST['timefinish'])){
        savescan($db,$operatorcode,$jobnumber,$timestamp_finish);
        echo'<div class="row"><div class="alert alert-success" role="alert">
                Scan added : '.get_operator_name($db,$operatorcode).' '.date('Y-m-d G:i:s',$timestamp_finish).' on '.$jobnumber.'
            </div></div><br>';
        }
        echo '<br>';
		
		//echo'<center><button value="ok" name="refresh" onclick="window.location.reload(true)" class="btn btn-primary">Refresh</button></center><br><br>';
		make_button('Refresh','reload_all_page()');
		
		if(($_SESSION['temp']['id'])=='Coco'){
			
		}
		
		//show($_POST);
		
		
		
	}

    
	
	
	
	
	
	echo'</form>';
	//show($scan);
}

function edit_scan2($db,$scanid){
	$scan=load_a_scan($db,$scanid);
	echo'<form  method="post">';
	echo'<br><br>';
	echo'<div class="row">';
		echo'<div class="col-sm-12">';
		echo'<h3><center>Edit / Delete</center></h3>';
		echo'</div>';
		
	echo'</div>';
	echo'<br><br>';
	echo'<div class="row">';
		echo'<div class="col-sm-4">';
		echo'Operator';
		echo'</div>';
		echo'<div class="col-sm-6">';
		echo $scan['operator_fullname'];
		echo'</div>';
	echo'</div><br>';
	
	echo'<div class="row">';
		echo'<div class="col-sm-4">';
		echo'MIS #';
		echo'</div>';
		echo'<div class="col-sm-6">';
		
		echo'  	  
		  <script>
				
		function addHash(elem) {
				  var val = elem.value;
				  if(!val.match(/^MIS/)) {
					elem.value = "MIS" + val;
				  }
				}
		  </script>';
		echo'<input type="textbox" placeholder="MIS12345" name="jobnumber" class="form-control" value="'.$scan['scan_jobnumber'].'" id="jobmanual"  onkeyup="addHash(this)" >';
		
		echo'</div>';
	echo'</div><br>';
	
	echo'<div class="row">';
		echo'<div class="col-sm-4">';
		echo'Product';
		echo'</div>';
		echo'<div class="col-sm-6">';
		echo "<i>".get_product_code($db,$scan['scan_jobnumber'])."</i>";
		echo'</div>';
	echo'</div><br>';
	
	echo'<div class="row">';
		echo'<div class="col-sm-3">';
		echo'Time Started';
		echo'</div>';
		echo'<div class="col-sm-6">';
		  echo'<input type="time" name="timestart" value="'.date('H:i',$scan['scan_timetag']).'" id="timestart" class="form-control" id="usr">';
		
		echo'</div>';
		echo'<div class="col-sm-2">';
            make_delete_button('delete_timetag');
			echo'</div>';
	echo'</div><br>';
	
	echo '<input type="hidden" id="load_scan_id" name="load_scan_id" value="'.$scan['scan_id'].'">';
	echo '<input type="hidden" id="seloperator" name="operator_code" value="'.$scan['scan_operatorcode'].'">';
	echo '<input type="hidden" id="delete_timetag" name="timetag" value="'.$scan['scan_timetag'].'">';
	echo '<input type="hidden" id="delete_timetag_finish" name="timetag_finish" value="'.$scan['scan_timetag_finish'].'">';
	//echo '<input type="hidden" id="jobnumber" name="jobnumber" value="'.$scan['scan_jobnumber'].'">';
	
    echo'<script>
                function show_finished_box(){
                    //alert("test");
                    var chboxs = document.getElementsByName("add_finished");
                    var vis = "block";
                    var thevalue="1";
                    for(var i=0;i<chboxs.length;i++) { 
                        if(chboxs[i].checked){
                        
                        }
                        else
                        {
                        vis = "none";
                        thevalue="0";
                            break;	
                        }
                    }
                    vis2 = "none";
                    //alert(vis);
                    //document.getElementById(box).style.display = vis;
                    document.getElementById("todelete").style.display = vis2;
                    document.getElementById("box-finished").style.display = vis;
                    document.getElementById("hastimefinished").value=thevalue;
                    document.getElementById("todelete").style.display = vis2;
                }
                </script>';



	if(empty($scan['scan_timetag_finish'])){
        echo'<div  id="todelete">';
		echo'<div class="row">';
		echo'<div class="col-sm-12">';
			echo'<i><center>Add Finished Scan</center></i>';
			echo'</div>';
		echo'</div>';
		echo'<div class="row">';
		echo'<div class="col-sm-12">';
			 echo'<center><input type="checkbox" id="finish_it" onclick="show_finished_box()" name="finish_it" value="finish_it"	class="allvenueall2 switch_1" ';
			 if(!empty($_POST['finish_it'])){echo "checked";}
			 echo '></center>';
			echo'</div>';
		echo'</div>';
        echo'</div>';
		
			
		
	}

    if(empty($scan['scan_timetag_finish'])){$style='style="display:none"';}
    echo'<div '.$style.' id="box-finished">';  
	
		if(empty($scan['scan_timetag_finish'])){$newfinish=$scan['scan_timetag']+3600;}else{$newfinish=$scan['scan_timetag_finish'];}
		echo '<div class="row">';
			echo '<div class="col-sm-3">';
			echo 'Time Finished';
			echo '</div>';
			echo '<div class="col-sm-6">';
			echo '<input type="time" name="timefinish" value="'.date('H:i',$newfinish).'" id="timefinish" class="form-control" id="usr" ';
			
			echo '>';
			
			echo '</div>';
			echo '<div class="col-sm-2">';
				if(!empty($scan['scan_timetag_finish'])){
					// echo ' <button type="submit" value="'.date('H:i:s',$scan['scan_timetag_finish']).'" name="delete_scan_finish" onclick="return confirm(\'Are you sure to delete '.date('H:i:s',$scan['scan_timetag_finish']).'?\')"class="btn btn-default" aria-label="Left Align">
					// 	  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
					// 	</button>';
                        make_delete_button('delete_timetag_finish');
				}
			echo'</div>';
			
		echo'</div><br>';
	
	
        echo'<div class="row">';
            echo'<div class="col-sm-12">';
                echo'<center>Total Hours : <input disabled id="diff"></center>


                    <script>
                    var timestart = document.getElementById("timestart").value;
                    var timefinish = document.getElementById("timefinish").value;

                    document.getElementById("timestart").onchange = function() {diff(timestart,timefinish)};
                    document.getElementById("timefinish").onchange = function() {diff(timestart,timefinish)};


                    function diff(timestart, timefinish) {
                        timestart = document.getElementById("timestart").value; //to update time value in each input bar
                        timefinish = document.getElementById("timefinish").value; //to update time value in each input bar
                        
                        timestart = timestart.split(":");
                        timefinish = timefinish.split(":");
                        var startDate = new Date(0, 0, 0, timestart[0], timestart[1], 0);
                        var endDate = new Date(0, 0, 0, timefinish[0], timefinish[1], 0);
                        var diff = endDate.getTime() - startDate.getTime();
                        var hours = Math.floor(diff / 1000 / 60 / 60);
                        diff -= hours * 1000 * 60 * 60;
                        var minutes = Math.floor(diff / 1000 / 60);

                        return (hours < 9 ? "0" : "") + hours + ":" + (minutes < 9 ? "0" : "") + minutes;
                    }

                    setInterval(function(){document.getElementById("diff").value = diff(timestart, timefinish);}, 50); //to update time every second (1000 is 1 sec interval and function encasing original code you had down here is because setInterval only reads functions) You can change how fast the time updates by lowering the time interval
                    </script>';
            echo'</div>';
        echo'</div><br>';
	
    echo'</div>';
	
	
	echo'<div class="row">';
		
		echo' <center><button type="submit" value='.$scan['scan_id'].' name="modify" class="btn btn-primary">Modify Scan</button></center><br>';
       // make_button('Modify Scan','modify_scan()');
		//echo' <a href="manage_employee.php"  ><button type="button" class="btn btn-primary">View employee</button></a><br><br>';
		
	echo'</div><br>';
	
	
	echo'</form>';
	//show($scan);
}

function all_javascript(){
    echo"<script>
    function load_scan_id(the_load_scan_id,the_operatorcode,the_timetag,the_timetagfinish,the_jobnumber) {
    
        $.ajax({
            type:'POST',
            url:'summary_ajax.php',
            data: {
                load_scan_id: the_load_scan_id,
                operator_code: the_operatorcode,
                timetag: the_timetag,
                timetag_finish: the_timetagfinish,
                jobnumber: the_jobnumber
            },
            success:function(html){\$('.Operator_show').empty().append(html);}
        });
    }
   
    function add_a_scan() {
    
        $.ajax({
            type:'POST',
            url:'summary_ajax.php',
            data: {
                add_a_scan: 'ok',
                add_a_scan_init: 'ok'               
            },
            success:function(html){\$('.Operator_show').empty().append(html);}
        });
    }
    function operatordone() {
        operator=document.getElementById(\"seloperator\").value;
        $.ajax({
            type:'POST',
            url:'summary_ajax.php',
            data: {
                add_a_scan: 'ok',
                add_a_scan_init: 'ok' ,
                operatordone: 'ok',
                addscan_operator:operator            
            },
            success:function(html){\$('.Operator_show').empty().append(html);}
        });
    }
    function MISdone() {
        
        thejobmanual=document.getElementById(\"jobmanual\").value;
        operator=document.getElementById(\"seloperator\").value;
        //alert(thejobmanual);
        $.ajax({
            type:'POST',
            url:'summary_ajax.php',
            data: {
                add_a_scan: 'ok',
                addscan_operator:operator,
                MISdone: 'ok',
                jobentry: 'on',
                jobmanual:thejobmanual           
            },
            success:function(html){\$('.Operator_show').empty().append(html);}
        });
    }
    function save_scan() {
         
        thejobmanual=document.getElementById(\"jobmanual\").value;
        operator=document.getElementById(\"seloperator\").value;
        thetimestart=document.getElementById(\"timestart\").value;
        thetimefinish=document.getElementById(\"timefinish\").value;
        thehastimefinished=document.getElementById(\"hastimefinished\").value;
        
        $.ajax({
            type:'POST',
            url:'summary_ajax.php',
            data: {
                add_a_scan: 'ok',
                addscan_operator:operator,
                MISdone: 'ok',
                jobentry: 'on',
                jobmanual:thejobmanual ,
                timestart:thetimestart,
                timefinish:thetimefinish,
                hastimefinished:thehastimefinished,
                save:'save'         
            },
            success:function(html){\$('.Operator_show').empty().append(html);}
        });

       

        
    }
    function reload_all_page() {
    
        $.ajax({
            type:'POST',
            url:'summary_ajax.php',
            data: {
                               
            },
            success:function(html){\$('.Operator_show').empty().append(html);}
        });
    }
    function delete_scan(id_timetag) {
        
       
        operator=document.getElementById(\"seloperator\").value; 
        thetimetag=document.getElementById(id_timetag).value;
        
        
        $.ajax({
            type:'POST',
            url:'summary_ajax.php',
            data: {
                
                operator_code:operator,
                timetag:thetimetag,
                delete_scan_start:'delete'
            },
            success:function(html){\$('.Operator_show').empty().append(html);}
        });
    }
    function modify_scan() {
        
       
        operator=document.getElementById(\"seloperator\").value;
        thetimetag=document.getElementById(\"delete_timetag\").value;
        thetimetag_finish=document.getElementById(\"delete_timetag_finish\").value;
        thejobnumber=document.getElementById(\"jobmanual\").value;
        thefinish_it=document.getElementById(\"finish_it\").value;
        thetimestart=document.getElementById(\"timestart\").value;
        thetimefinish=document.getElementById(\"timefinish\").value;
        
        $.ajax({
            type:'POST',
            url:'summary_ajax.php',
            data: {
                jobnumber:thejobnumber,
                finish_it: thefinish_it,
                operator_code:operator,
                timetag:thetimetag,
                timetag_finish:thetimetag_finish,
                timestart:thetimestart,
                timefinish:thetimefinish,
                modify:'modify'
            },
            success:function(html){\$('.Operator_show').empty().append(html);}
        });
    }
    </script>";
}

function make_button($caption,$function){
    echo' <center><div class="btn btn-primary"   onclick="'.$function.';">'.$caption.'</div></center><br>';
}

function make_delete_button($id_timetag){
    echo' <div onclick="delete_scan(\''.$id_timetag.'\');" class="btn btn-default" >
    <span class="glyphicon glyphicon-remove " ></span>
  </div>';
}

function showList($data,$formatdata,$custom='',$col1='6',$postname=''){
    $col2=12-$col1;
    echo'<div class="row header-summary">';
            echo'<div class="col-sm-'.$col1.'">';
            echo $formatdata['header'][0];
            echo'</div>';
            if (empty($custom)){
                echo'<div class="col-sm-'.$col2.'">';
                echo $formatdata['header'][1];
                echo'</div>';
                
                }elseif ($custom=='workarea'){
                    
                }else{
                    echo'<div class="col-sm-'.$col2.'">';
                    echo $formatdata['header'][1];
                    echo'</div>'; 
                }
            
        echo'</div>';
    foreach($data as $item){
        
        echo'<div class="row row_normal ';
        if ($custom=='month'){
            if(date('M Y',strtotime($_SESSION['temp']['summary']['year'].'-'.$_SESSION['temp']['summary']['month'].'-01'))==date('M Y',strtotime($item[$formatdata['field'][2]].'-'.$item[$formatdata['field'][0]].'-01'))){echo 'row-selected';}
            
        }else{
            if($_SESSION['temp']['summary'][$postname]==$item[$formatdata['field'][0]]){echo 'row-selected';}
        }
       

        echo' ">';
            echo'<form id="form-id'.$item[$formatdata['field'][0]].'" method="POST">';
            if ($custom=='month'){
                echo'<input type="hidden" name="month" value="'.$item[$formatdata['field'][0]].'">';
                echo'<input type="hidden" name="year" value="'.$item[$formatdata['field'][2]].'">';
                echo'<input type="hidden" name="monthyear" value="'.date('M Y',strtotime($item[$formatdata['field'][2]].'-'.$item[$formatdata['field'][0]].'-01')).'">';
            }else{
                echo'<input type="hidden" name="'.$postname.'" value="'.$item[$formatdata['field'][0]].'">';
            }
           

            echo'<div  onClick="document.getElementById(\'form-id'.$item[$formatdata['field'][0]].'\').submit();" class="col-sm-'.$col1.'">';
            if (empty($custom)){
                if(empty($item[$formatdata['field'][0]])){
                    echo 'blank';
                }else{
                    echo $item[$formatdata['field'][0]];
                }
                
            }elseif ($custom=='days'){
                echo date('jS M',strtotime($item[$formatdata['field'][0]]));
            }elseif ($custom=='month'){
                echo date('M Y',strtotime($item[$formatdata['field'][2]].'-'.$item[$formatdata['field'][0]].'-01'));
               
            }elseif ($custom=='workarea'){
                echo $item[$formatdata['field'][0]];
               
            }else{

            }
            
            echo'</div>';
            echo'<div class="col-sm-'.$col2.'">';
            if (empty($custom)){
            echo number_format(round($item[$formatdata['field'][1]],1),1);
            }elseif ($custom=='workarea'){
                
            }else{
                echo number_format(round($item[$formatdata['field'][1]],1),1);
            }
            echo'</div>';
            echo'</form>';
        echo'</div>';
        
    }

}

function showmonth($db){
    $filter=all_filter_list_2();
    $data_month=get_summary_listmonth_v2($db,$filter['workareafilter'],$filter['operatorfilter']);
    //show($data_month[0]);
    $formatdata['header']=['Month','Hours'];
    $formatdata['field']=['themonth','total','theyear'];
    showList($data_month,$formatdata,'month',7);
}

function showdays($db){
    $filter=all_filter_list_2();
    $data_days=get_summary_listday_v2($db,$filter['operatorfilter'],$filter['yearmonthfilter']);
    //show($data_month[0]);
    $formatdata['header']=['Days','Hours'];
    $formatdata['field']=['scan_date','total_hours'];
    showList($data_days,$formatdata,'days',6,'days');

}

function showworkarea($db){
    $filter=all_filter_list_2();
    $data_workarea=get_summary_listworkarea_v2($db,$filter['yearmonthfilter'],$filter['operatorfilter'],$filter['daysfilter']);
    //show($data_month[0]);
    $formatdata['header']=['WorkArea'];
    $formatdata['field']=['WorkArea'];
    showList($data_workarea,$formatdata,'workarea',12,'workarea');

}

function showoperator($db){
    $filter=all_filter_list_2();

    $data_days=get_summary_listoperator_v2($db,$filter['workareafilter'],$filter['yearmonthfilter'],$filter['daysfilter']);
    //show($data_month[0]);
    $formatdata['header']=['Operator','Hours'];
    $formatdata['field']=['operator_fullname','total_hours'];
    showList($data_days,$formatdata,'',9,'operatorname');

}

?>