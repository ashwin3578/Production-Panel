<?php

include('function_dashboard.php');

define("nbr_of_line", 93); // nbr of lines, 3 months
load_role($db,$_SESSION['temp']['id']);

function roster_managing_POST($db){





    if(!empty($_POST['Notes'])){
        if($_POST['Notes']=='Remove'){
            remove_notes_allocation($db);
            
        }else{
            add_notes_allocation($db);
        }
        update_graphic_table($db);
    }


    if(!empty($_POST['filter'])){
        if( $_SESSION['temp']['filter_workarea']==$_POST['filter']){
            unset($_SESSION['temp']['filter_workarea']);
        }else{
            $_SESSION['temp']['filter_workarea']=$_POST['filter'];
        }
        
        //show($_SESSION['temp']);
    }
    if(empty($_POST['transpose_view'])){
        
        if(empty($_SESSION['temp']['transpose_view'])){
            $_SESSION['temp']['transpose_view']='normal';
        }
        
       
        
    }else{
        if(empty($_SESSION['temp']['transpose_view'])or$_SESSION['temp']['transpose_view']=='transpose'){
            $_SESSION['temp']['transpose_view']='normal';
        }else{
            $_SESSION['temp']['transpose_view']='transpose';
        }
        
        
    }


    if(!empty($_POST['brush_Action'])){
        $_POST['brush']=$_POST['brush_Action'];
    }

    if(!empty($_POST['day'])and !empty($_POST['operator']) and empty($_POST['Notes'])){

        //show($_SESSION['temp']['Fill']);
        if(!empty($_SESSION['temp']['Fill'])){
            if($_SESSION['temp']['Fill']=='horizontal'){
                $operator_list=get_list_operator($db);
                foreach($operator_list as $operator){
                    $_POST['operator']=$operator['operator_fullname'];
                    add_allocation($db);
                    update_graphic_table($db);
                   
                    
                }
                //unset($_SESSION['temp']['Fill']);
                // echo'<script>
                // document.getElementById("FillHorinzontal").className=\'btn btn-default\';
                // document.getElementById("FillVertical").className=\'btn btn-default\';
                
                // </script>';

            }elseif($_SESSION['temp']['Fill']=='vertical'){
                $next_day=get_next_day($db,$_POST['brush']);
                //show($next_day);
                $max_day=365;
                $ii=0;
                While($next_day<>$_POST['day'] and $ii<$max_day){

                    //show($_POST['day']);
                    add_allocation($db);
                    update_graphic_table($db);
                    $_POST['day']=date('Y-m-d', strtotime($_POST['day'] . ' +1 day'));
                    $ii++;
                    
                }
                //unset($_SESSION['temp']['Fill']);
                // echo'<script>
                // document.getElementById("FillHorinzontal").className=\'btn btn-default\';
                // document.getElementById("FillVertical").className=\'btn btn-default\';
                
                // </script>';
                
            }
        }else{
            add_allocation($db);
            update_graphic_table($db);
            
        }

    }

    if(empty($_POST['date_to_show'])){
        if(empty($_SESSION['temp']['date_to_show'])){
        $today2=(date('Y-m-d',time()))	;
        $_POST['date_to_show']=$today2;
        $_SESSION['temp']['date_to_show']=$_POST['date_to_show'];
        }else{
            $_POST['date_to_show']=$_SESSION['temp']['date_to_show'];
        }
       
        
    }else{
            $_SESSION['temp']['date_to_show']=$_POST['date_to_show'];
    }

    if(empty($_POST['end_date_to_show'])){
        if(empty($_SESSION['temp']['end_date_to_show'])){
        $today2=(date('Y-m-d',strtotime($_SESSION['temp']['date_to_show'] . ' +31 days')))	;
        $_POST['end_date_to_show']=$today2;
        $_SESSION['temp']['end_date_to_show']=$_POST['end_date_to_show'];
        }else{
            $_POST['end_date_to_show']=$_SESSION['temp']['end_date_to_show'];
        }
       
        
    }else{
            $_SESSION['temp']['end_date_to_show']=$_POST['end_date_to_show'];
    }

    if(!empty($_POST['Operator_list'])){
        if($_POST['Operator_list']=='up'){$_SESSION['temp']['offset']=$_SESSION['temp']['offset']+20;}
        if($_POST['Operator_list']=='down'){$_SESSION['temp']['offset']=max(0,$_SESSION['temp']['offset']-20);}
        if($_POST['Operator_list']=='zero'){$_SESSION['temp']['offset']=0;}
        //show($_SESSION['temp']['offset']);
        
    }

    if(!empty($_POST['Week_list'])){
        if($_POST['Week_list']=='up')  {
            $_SESSION['temp']['date_to_show']=date('Y-m-d',strtotime($_SESSION['temp']['date_to_show'] . ' +7 days'));
            $_SESSION['temp']['end_date_to_show']=date('Y-m-d',strtotime($_SESSION['temp']['end_date_to_show'] . ' +7 days'));
            $_POST['end_date_to_show']=$_SESSION['temp']['end_date_to_show'];
            $_POST['date_to_show']=$_SESSION['temp']['date_to_show'];
        }
        if($_POST['Week_list']=='down'){
            $_SESSION['temp']['date_to_show']=date('Y-m-d',strtotime($_SESSION['temp']['date_to_show'] . ' -7 days'));
            $_SESSION['temp']['end_date_to_show']=date('Y-m-d',strtotime($_SESSION['temp']['end_date_to_show'] . ' -7 days'));
            $_POST['end_date_to_show']=$_SESSION['temp']['end_date_to_show'];
            $_POST['date_to_show']=$_SESSION['temp']['date_to_show'];
        }   
       
    }
    
    if($_SESSION['temp']['transpose_view']=='transpose'){
        $_POST['date_to_show']=date('Y-m-d',strtotime($_SESSION['temp']['date_to_show'] . ' last sunday +1 days'));;
        $_SESSION['temp']['date_to_show']=$_POST['date_to_show'];
       //show($_POST['date_to_show']);
    }


    


    if(!empty($_POST['Fill'])){
        
        if($_SESSION['temp']['Fill']==$_POST['Fill']){
            unset($_SESSION['temp']['Fill']);
        }else{
            $_SESSION['temp']['Fill']=$_POST['Fill'];
        }

    }
	
    if(!empty($_POST['brush'])){
        
        if($_SESSION['temp']['brush']===$_POST['brush']){
            unset($_POST['brush']);
        }else{
            $_SESSION['temp']['brush']=$_POST['brush'];
        }
        unset($_SESSION['temp']['brush_Contract']);
        unset( $_SESSION['temp']['brush_WorkArea']);
        unset( $_SESSION['temp']['brush_Shift']);
        unset($_POST['brush_Contract']);
        unset($_POST['brush_WorkArea']);
        unset($_POST['brush_Shift']);

    }else{
        $_POST['brush']=$_SESSION['temp']['brush'];
    }
	
    if(!empty($_POST['brush_WorkArea'])){
        
        if($_SESSION['temp']['brush_WorkArea']===$_POST['brush_WorkArea']){
            unset($_POST['brush_WorkArea']);
        }else{
            $_SESSION['temp']['brush_WorkArea']=$_POST['brush_WorkArea'];
        }
        unset($_SESSION['temp']['brush_Contract']);
        unset( $_SESSION['temp']['brush']);
        unset( $_SESSION['temp']['brush_Shift']);
        unset($_POST['brush_Shift']);
        unset($_POST['brush_Contract']);
        unset($_POST['brush']);

    }else{
        $_POST['brush_WorkArea']=$_SESSION['temp']['brush_WorkArea'];
    }
	
    if(!empty($_POST['brush_Contract'])){
        
        if($_SESSION['temp']['brush_Contract']===$_POST['brush_Contract']){
            unset($_POST['brush_Contract']);
        }else{
            $_SESSION['temp']['brush_Contract']=$_POST['brush_Contract'];
        }
        unset($_SESSION['temp']['brush_WorkArea']);
        unset( $_SESSION['temp']['brush']);
        unset( $_SESSION['temp']['brush_Shift']);
        unset($_POST['brush_Shift']);
        unset($_POST['brush_WorkArea']);
        unset($_POST['brush']);

    }else{
        $_POST['brush_Contract']=$_SESSION['temp']['brush_Contract'];
    }
	
    if(!empty($_POST['brush_Shift'])){
        
        if($_SESSION['temp']['brush_Shift']===$_POST['brush_Shift']){
            unset($_POST['brush_Shift']);
        }else{
            $_SESSION['temp']['brush_Shift']=$_POST['brush_Shift'];
        }
        unset($_SESSION['temp']['brush_WorkArea']);
        unset( $_SESSION['temp']['brush']);
        unset( $_SESSION['temp']['brush_Contract']);
        unset($_POST['brush_Contract']);
        unset($_POST['brush_WorkArea']);
        unset($_POST['brush']);

    }else{
        $_POST['brush_Shift']=$_SESSION['temp']['brush_Shift'];
    }

    
    if(empty($_POST['change_view'])){
        if(empty($_SESSION['temp']['view_roster'])){
            $_SESSION['temp']['view_roster']='Action';
        }
        
       
        
    }else{
        unset($_SESSION['temp']['brush_Shift']);
        unset($_SESSION['temp']['brush_Contract']);
        unset($_SESSION['temp']['brush_WorkArea']);
        unset( $_SESSION['temp']['brush']);
        unset($_POST['brush_Shift']);
        unset($_POST['brush_Contract']);
        unset($_POST['brush_WorkArea']);
        unset($_POST['brush']);
        $_SESSION['temp']['view_roster']=$_POST['change_view'];
        
    }
	
    if(empty($_POST['collapse_day'])){
        
        if(empty($_SESSION['temp']['collapse_day'])){
            $_SESSION['temp']['collapse_day']='week';
        }
        
       
        
    }else{
        unset($_SESSION['temp']['brush_Shift']);
        unset($_SESSION['temp']['brush_Contract']);
        unset($_SESSION['temp']['brush_WorkArea']);
        unset( $_SESSION['temp']['brush']);
        unset($_POST['brush_Shift']);
        unset($_POST['brush_Contract']);
        unset($_POST['brush_WorkArea']);
        unset($_POST['brush']);
        $_SESSION['temp']['collapse_day']=$_POST['collapse_day'];
        
    }

    
    
    
    if(empty($_POST['change_sort'])){
        if(empty($_SESSION['temp']['roster_sort'])){
            $_SESSION['temp']['roster_sort']='operator_lastname ASC,operator_name ASC';
        }
    }else{
        $i=0;
        if($i==0 and (empty($_SESSION['temp']['transpose_view'])or$_SESSION['temp']['roster_sort']=='operator_name DESC,operator_lastname DESC')){$_SESSION['temp']['roster_sort']='operator_lastname ASC,operator_name ASC';$i=1;}
        if($i==0 and $_SESSION['temp']['roster_sort']=='operator_lastname ASC,operator_name ASC'){$_SESSION['temp']['roster_sort']='operator_lastname DESC,operator_name DESC';$i=1;}
        if($i==0 and $_SESSION['temp']['roster_sort']=='operator_lastname DESC,operator_name DESC'){$_SESSION['temp']['roster_sort']='operator_name ASC,operator_lastname ASC';$i=1;}
        if($i==0 and $_SESSION['temp']['roster_sort']=='operator_name ASC,operator_lastname ASC'){$_SESSION['temp']['roster_sort']='operator_name DESC,operator_lastname DESC';$i=1;}
          
    }

    if(empty($_POST['group_contract'])){
        if(empty($_SESSION['temp']['group_contract'])){
            $_SESSION['temp']['group_contract']='';
            
        }
    }else{
        if(empty($_SESSION['temp']['group_contract'])){
            $_SESSION['temp']['group_contract']=$_POST['group_contract'];
            //show_alert('Group Contract');
        }else{
            unset($_SESSION['temp']['group_contract']);
            //show_alert('Unroup Contract');
        }
        
          
    }
   
    




}


function roster_view_general($db){

    
    create_css ($db);

    echo'<div class="row ">';
        echo'<div id="postinfo" class="postinfo">';
        echo'</div>';
    echo'</div>';

    echo'<div class="row ">';
          
        echo'<div class="col-sm-1 ">';
            if($_SESSION['temp']['view_roster']<>'Summary' and $_SESSION['temp']['view_roster']<>'Hours' and empty(allow_modify($db,'roster_modify')) and empty($_POST['detail_operator'])){
                
                create_menu($db);    
            }
        echo'</div>';

        echo'<div class="col-sm-11 main-box">';
           
            if(!empty($_POST['detail_operator'])){
                show_details_operator($db);
            }else{

                if($_SESSION['temp']['view_roster']<>'Summary' ){
                    if($_SESSION['temp']['transpose_view']=='transpose'){
                        show_table_normal_transpose($db);
                    }else{
                        show_table_normal($db);
                    }
                    
                }else{
                    if($_SESSION['temp']['transpose_view']=='transpose'){
                        show_table_summary_transpose($db);
                    }else{
                        show_table_summary($db);
                    }
                    

                }
            }




        echo'</div>';
       
        
        
    echo'</div>';
    
}

function navbar_roster($db){
    echo'<div class="row">';
		echo'<div class="col-sm-1 ">';
			echo'<form method="POST">';
			echo'<div class="visible-xs-block visible-sm-block visible-md-block">';	
				echo'<br><button type="submit" name="new_test" value="New Test" class="btn btn-default" >
									<span class="glyphicon glyphicon-refresh" ></span>
				</button><br>&nbsp';
			echo'</div>';
            echo'<div class="hidden-xs hidden-sm hidden-md">';	
				echo'<br><button type="submit" name="Change" value="Change"  class="btn btn-default" >
									<span class="glyphicon glyphicon-refresh" > Refresh</span>
				</button><br>&nbsp';
			echo'</div>';
			
		echo'</div>';
		
		echo'<div class="col-sm-2 "><br>';
            echo'<div class="col-sm-12 "><div class="col-sm-3 ">Date:</div><div class="col-sm-9 "><input class="form-control" type="date" name="date_to_show" onChange="submit();"value="'.$_POST['date_to_show'].'"></div></div>';
            echo'<div class="col-sm-12 "><div class="col-sm-3 ">Date:</div><div class="col-sm-9 "><input class="form-control" type="date" name="end_date_to_show" onChange="submit();"value="'.$_POST['end_date_to_show'].'"></div></div>';
            echo'<input class="form-control" type="hidden" name="detail_operator" value="'.$_POST['detail_operator'].'" >';
            //echo'<input class="form-control" type="hidden" name="change_view" value="Action" >';

        echo'</form>';
        echo'</div>';
		echo'<div class="col-sm-2 ">';
            if(empty(allow_modify($db,'roster_admin'))){
                echo'<br><form method="POST"><div class="col-sm-8 "><button id="FillHorinzontal" type="submit" name="Fill" value="vertical"  class="btn btn-default ';
                if($_SESSION['temp']['Fill']=='vertical'){echo'active';}
                echo'" style="width:100%;" >
                <span class="glyphicon glyphicon-resize-';
                if($_SESSION['temp']['transpose_view']=='normal'){echo'vertical';}else{echo'horizontal';}
                echo'" > Fill ';
                if($_SESSION['temp']['transpose_view']=='normal'){echo'Vertical';}else{echo'Horizontal';}
                echo'</span>
                </button></div>';
                // echo'<div class="col-sm-8 "><button type="submit" id="FillVertical" name="Fill" value="horizontal"  class="btn btn-default ';
                // if($_SESSION['temp']['Fill']=='horizontal'){echo'active';}
                // echo'" style="width:100%;">
                // <span class="glyphicon glyphicon-resize-';
                // if($_SESSION['temp']['transpose_view']=='normal'){echo'horizontal';}else{echo'vertical';}
                // echo'" > Fill ';
                // if($_SESSION['temp']['transpose_view']=='normal'){echo'Horizontal';}else{echo'Vertical';}
                // echo'</span>
                // </button></div>';
                echo'</form>';
            }
			
		echo'</div>';
        echo'<div class="col-sm-1 ">';
        //if(empty($_POST['detail_operator'])){
            echo'<br><form method="POST"><div class="col-sm-10 "><button  type="submit" name="change_sort" value="change_sort"  class="btn btn-default " style="width:100%;" >
            <span class="glyphicon glyphicon-sort" > Sort </span>
            </button></div>';
            echo'<div class="col-sm-10 "><button  type="submit" name="group_contract" value="group_contract"  class="btn btn-default ';
            if(!empty($_SESSION['temp']['group_contract'])){echo'active';}
            echo'" style="width:100%;" >
            <span class="glyphicon glyphicon-list-alt" > Contract </span>
            </button></div>';
            echo'<input class="form-control" type="hidden" name="detail_operator" value="'.$_POST['detail_operator'].'" >';
            echo'</form>';
       // }
        echo'</div>';
		echo'<div class="col-sm-2 ">';
        //if(empty($_POST['detail_operator'])){
            echo'<br><form method="POST">';
            echo'<input class="form-control" type="hidden" name="detail_operator" value="'.$_POST['detail_operator'].'" >';
            if(empty(allow_modify($db,'roster_admin')) or !empty($_POST['detail_operator'])){
                echo'<div class="col-sm-4 "><button type="submit" name="change_view" value="Contract"  class="btn btn-default form-control ';
                if($_SESSION['temp']['view_roster']=='Contract'){echo' active ';}
                echo' viewlist" >Contract</button></div>';
                echo'<div class="col-sm-4 "><button type="submit" name="change_view" value="Shift"  class="btn btn-default form-control ';
                if($_SESSION['temp']['view_roster']=='Shift'){echo' active ';}
                echo' viewlist" >Shift</button></div>';
                echo'<div class="col-sm-4 "><button type="submit" name="change_view" value="WorkArea"  class="btn btn-default form-control ';
                if($_SESSION['temp']['view_roster']=='WorkArea'){echo' active ';}
                echo' viewlist" >Area</button></div>';
            }
            if(empty($_POST['detail_operator'])){
                echo'<div class="col-sm-4 "><button type="submit" name="change_view" value="Action"  class="btn btn-default form-control ';
                if($_SESSION['temp']['view_roster']=='Action'){echo' active ';}
                echo' viewlist" >Action</button></div>';
                if($_SESSION['temp']['transpose_view']=='normal'){
                    echo'<div class="col-sm-4 "><button type="submit" name="change_view" value="Hours"  class="btn btn-default form-control ';
                    if($_SESSION['temp']['view_roster']=='Hours'){echo' active ';}
                    echo' viewlist" >Hours</button></div>';
                }
                if($_SESSION['temp']['transpose_view']=='normal'){
                    echo'<div class="col-sm-4 "><button type="submit" name="change_view" value="Summary"  class="btn btn-default form-control ';
                    if($_SESSION['temp']['view_roster']=='Summary'){echo' active ';}
                    echo' viewlist" >Summary</button></div>';
                }
            }
            echo'</form>';
       // }	
		echo'</div>';
        echo'<div class="col-sm-1 ">';
        if(empty($_POST['detail_operator'])){
            echo'<br><form method="POST">
            <div class="col-sm-12 "><button type="submit" name="transpose_view" value="Transpose"  class="btn btn-default form-control ';
            
            echo' viewlist" >';
            if($_SESSION['temp']['transpose_view']=='transpose'){
                echo' Tracking Calendar ';
            }else{
                echo' Weekly View ';
                echo'<input class="form-control" type="hidden" name="change_view" value="Action" >';
            }
            echo'</button></div>';
            echo'</form>';
        }
        echo'</div>';
		
		
		if(!(empty($_POST['detail_operator']) and empty($_POST['product_test']) and empty($_POST['new_test']) and empty($_POST['manage_template']) and empty($_POST['template_name']))){
			echo'<div class="col-sm-2 ">';
			echo'<form method="POST">';
			echo'<br><button type="submit" name="type" value="return" class="btn btn-default" >
									<span class="glyphicon glyphicon-arrow-up" > Back </span>
									</button><br>';

            if(!empty($_POST['detail_operator'])){echo'<input class="form-control" type="hidden" name="change_view" value="Action" >';}
            echo'</form>';
			echo'</div>';
        }else{
            // echo'<div class="col-sm-2 ">';
            //     echo'<form method="POST">';
            //     echo'<div class="visible-xs-block visible-sm-block visible-md-block">';	
            //         echo'<br><button type="submit" name="show_dashboard" value="show_dashboard" class="btn btn-default" >
            //         <span class="glyphicon glyphicon-signal" > </span>
            //         </button><br>';
            //     echo'</div>';
            //     echo'<div class="hidden-xs hidden-sm hidden-md">';	
            //         echo'<br><button type="submit" name="show_dashboard" value="show_dashboard" class="btn btn-default" >
            //         <span class="glyphicon glyphicon-signal" > Dashboard</span>
            //         </button><br>';
            //     echo'</div>';
            //     echo'</form>';
            // echo'</div>';
		
        }	
		
	echo'</div>';	
}

function allow_modify($db='',$role='everyone_logged_in',$exception='',$issue_number='',$disabled=''){
	$allow=0;
	if($role=='everyone_logged_in'){
		if(!empty($_SESSION['temp']['id'])){$allow=1;}
	}
	if($role=='only_admin'){
		if($_SESSION['temp']['Issue Log Admin']==1){$allow=1;}
	}
	if($role=='admin_or_just_created'){
		
		if($_SESSION['temp']['role_issue_log_modify']==1){$allow=1;}
		
		//if issue not assign, and session id = openby
		$actors=all_actor_issue($db,$issue_number);
		//show($actors);
		if(empty($actors['issue_assignto'])){
			if($actors['issue_openby']==$_SESSION['temp']['id']){$allow=1;}
		}
		if(empty($actors['issue_openby'])){
			$allow=1;
		}
		
		
	}
	
	
	
	if($role=='admin_assignto_openby'){
		
		if($_SESSION['temp']['role_issue_log_modify']==1){$allow=1;}
		
		//if issue not assign, and session id = openby
		$actors=all_actor_issue($db,$issue_number);
		//show($actors);
		if(empty($actors['issue_closeby'])){
			if($actors['issue_openby']==$_SESSION['temp']['id']){$allow=1;}
			if($actors['issue_assignto']==$_SESSION['temp']['id']){$allow=1;}
		}
		
		
		
	}
	
	if($role=='admin_closeby_everyoneloggedin'){
		
		if($_SESSION['temp']['role_issue_log_modify']==1){$allow=1;}
		
		//if issue not closed, everyone logged in can 
		$actors=all_actor_issue($db,$issue_number);
		//show($actors);
		if(empty($actors['issue_closeby'])){
				if(!empty($_SESSION['temp']['id'])){$allow=1;}
		}
		else
		{
			if($actors['issue_closeby']==$_SESSION['temp']['id']){$allow=1;}
		}
		
		
		
	}
	
	if($role=='delete_log'){
		
		if($_SESSION['temp']['Issue Log Admin']==1){$allow=1;}
		
		
		$actors=all_actor_issue($db,$issue_number);
		
		
		if(empty($actors['issue_closeby'])and empty($actors['issue_assignto'])){
			
			if($actors['issue_openby']==$_SESSION['temp']['id']){$allow=1;}
		}
		
		
		
	}


	if($role=='admin_asset'){
		
		if($_SESSION['temp']['role_asset_admin']==1){$allow=1;}
		
	}
	
	
	
	
	
	
	if(!empty($_SESSION['temp']['id']) and $exception==$_SESSION['temp']['id']){$allow=1;}
	
	
	if($role=='roster_admin'){
		
		if($_SESSION['temp']['role_roster_admin']==1){$allow=1;}
		
	}

    if($role=='roster_modify'){
		
		if($_SESSION['temp']['role_roster_modify']==1){$allow=1;}
		
	}
    if($role=='prodplan_input'){
		
		if($_SESSION['temp']['role_prodplan_input']==1){$allow=1;}
		
	}
    if($role=='prodplan_risk'){
		
		if($_SESSION['temp']['role_prodplan_risk']==1){$allow=1;}
		
	}
    if($role=='prodplan_pref'){
		
		if($_SESSION['temp']['role_prodplan_pref']==1){$allow=1;}
		
	}
    if($role=='prodplan_notes'){
		
		if($_SESSION['temp']['role_prodplan_notes']==1){$allow=1;}
		
	}
    if($role=='prodplan_statuts'){
		
		if($_SESSION['temp']['role_prodplan_statuts']==1){$allow=1;}
		
	}
	
	
	
	if($allow==1){return '';} else {
		
		if($disabled==''){
			return 'readonly';
		}
		else
		{
			return 'disabled';
		}
		
		
	}
	
	
	
	
	
	
	
}

function get_date_data($db){
    $today=(date('jS M Y',strtotime($_POST['date_to_show'])))	;
    $tempdate=$today;     
    $nbr_of_line=nbr_of_line;
    if($_SESSION['temp']['transpose_view']=='transpose'){
       
        $nbr_of_line=5;
    }else{
        $nbr_of_line=ceil(abs(strtotime($_POST['end_date_to_show']) - strtotime($_POST['date_to_show'])) / 86400);
    }

    for ($x = 0; $x <= $nbr_of_line; $x++) {

        $row[]=$tempdate;
        $tempdate=date('jS M Y', strtotime($tempdate . ' +1 day'));;
      }
    
    
    return $row;
}

function get_data_allocation($db,$table='',$operator=''){
    $nbr_of_line=nbr_of_line;
    if($_SESSION['temp']['transpose_view']=='transpose'){
       
        $nbr_of_line=5;
    }
    //$alltable=['allocationcontract','allocationwork','allocation'];
    
    //foreach($alltable as $table){
   if(empty($table)){$table=get_table_allocation(); }
   if(!empty($operator)){$filter=" AND ".$table."_operatorid='".$operator."'"; }
      
        
    //date('Y-m-d', strtotime($_POST['day'] . ' +1 day'));

        $query="SELECT * FROM dbo.".$table." 
        WHERE ".$table."_date>='".$_SESSION['temp']['date_to_show']."' ".$filter." 
        AND ".$table."_date<='".date('Y-m-d', strtotime($_SESSION['temp']['date_to_show'] . ' +'.$nbr_of_line.' day'))."' ";
        //show($query);
        $sql = $db->prepare($query); 
        $sql->execute();
        //show(nbr_of_line);
        $rowtemp=$sql->fetchall();
        foreach($rowtemp as $line){
            $row[$line[$table.'_date']][$line[$table."_operatorid"]]=$line[$table."_code"];
        }
   
  
   // }
    return $row;
   
   
   
   
   
   
  
  return $row;
}

function get_data_notes($db,$table='',$operator=''){
    $nbr_of_line=nbr_of_line;
    if($_SESSION['temp']['transpose_view']=='transpose'){
       
        $nbr_of_line=5;
    }
   
   if(empty($table)){$table=get_table_allocation(); }
   if(!empty($operator)){$filter=" AND allocationnotes_operatorid='".$operator."'"; }  
        
   

        $query="SELECT * FROM dbo.allocationnotes 
        WHERE allocationnotes_date>='".$_SESSION['temp']['date_to_show']."'  ".$filter." 
        AND allocationnotes_date<='".date('Y-m-d', strtotime($_SESSION['temp']['date_to_show'] . ' +'.$nbr_of_line.' day'))."'
        AND allocationnotes_table>='".$table."'  ";
        //show($query);
        $sql = $db->prepare($query); 
        $sql->execute();
       
        $rowtemp=$sql->fetchall();
        foreach($rowtemp as $line){
            $row[$line['allocationnotes_date']][$line["allocationnotes_operatorid"]]=$line["allocationnotes_notes"];
        }
   
    
  
  return $row;
}

function get_data_summary($db){

    $nbr_of_line=nbr_of_line;
    if($_SESSION['temp']['transpose_view']=='transpose'){
       
        $nbr_of_line=5;
    }

    $query="SELECT allocationshift_code,baseallocationwork_code,allocationcontract_date,sum(baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100) as hours_available FROM dbo.allocationcontract
    left join dbo.allocationwork on allocationwork_operatorid=allocationcontract_operatorid and allocationwork_date=allocationcontract_date
    left join dbo.allocation on allocation_operatorid=allocationcontract_operatorid and allocation_date=allocationcontract_date
    left join dbo.allocationshift on allocationshift_operatorid=allocationcontract_operatorid and allocationshift_date=allocationcontract_date
    left join dbo.baseallocationshift on baseallocationshift_code=allocationwork_code
    left join dbo.baseallocationwork on baseallocationwork_code=allocationwork_code 
    left join dbo.baseallocation on baseallocation_code=allocation_code 
    left join dbo.baseallocationcontract on baseallocationcontract_code=allocationcontract_code 
    WHERE allocationcontract_date>='".$_SESSION['temp']['date_to_show']."' 
    AND allocationcontract_date<='".date('Y-m-d', strtotime($_SESSION['temp']['date_to_show'] . ' +'.$nbr_of_line.' day'))."' 
    and allocationshift_code <> 'Null'
    AND ((DATEPART(DW,allocationcontract_date)<6 and DATEPART(DW,allocationcontract_date)<>7 
        ) or((DATEPART(DW,allocationcontract_date)=6 or DATEPART(DW,allocationcontract_date)=7)and baseallocation_working<100 ))
    AND (baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100)>0
    group by allocationcontract_date,baseallocationwork_code,allocationshift_code
    order by allocationcontract_date asc";
    //show($query);
    $sql = $db->prepare($query); 
    $sql->execute();
    //show(nbr_of_line);
    $rowtemp=$sql->fetchall();
    foreach($rowtemp as $line){
        $row[$line['allocationcontract_date']] ['total'] =$row[$line['allocationcontract_date']] ['total'] +$line["hours_available"];
        $row[$line['allocationcontract_date']] [$line['baseallocationwork_code']]['total']  = $row[$line['allocationcontract_date']] [$line['baseallocationwork_code']]['total']  +$line["hours_available"];
        $row[$line['allocationcontract_date']]  [$line['baseallocationwork_code']]  [$line['allocationshift_code']]=$row[$line['allocationcontract_date']]  [$line['baseallocationwork_code']]  [$line['allocationshift_code']] +$line["hours_available"];
        $row[$line['baseallocationwork_code']]['total']  = $row [$line['baseallocationwork_code']]['total']  +$line["hours_available"];
        $row[$line['baseallocationwork_code']] [$line['allocationshift_code']]['total']=$row[$line['baseallocationwork_code']] [$line['allocationshift_code']]['total'] +$line["hours_available"];
        
    }

    // show($row);
   
  
  return $row;
}

function get_data_hours($db){
   
    $nbr_of_line=nbr_of_line;
    if($_SESSION['temp']['transpose_view']=='transpose'){
       
        $nbr_of_line=5;
    } 

    $query="SET DATEFIRST 1;
    SELECT allocationwork_operatorid,allocationcontract_date,sum(baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100) as hours_available FROM dbo.allocationcontract
    left join dbo.allocationwork on allocationwork_operatorid=allocationcontract_operatorid and allocationwork_date=allocationcontract_date
    left join dbo.allocation on allocation_operatorid=allocationcontract_operatorid and allocation_date=allocationcontract_date
    left join dbo.allocationshift on allocationshift_operatorid=allocationcontract_operatorid and allocationshift_date=allocationcontract_date
    left join dbo.baseallocationshift on baseallocationshift_code=allocationwork_code
    left join dbo.baseallocationwork on baseallocationwork_code=allocationwork_code 
    left join dbo.baseallocation on baseallocation_code=allocation_code 
    left join dbo.baseallocationcontract on baseallocationcontract_code=allocationcontract_code 
    WHERE allocationcontract_date>='".$_SESSION['temp']['date_to_show']."' 
    AND allocationcontract_date<='".date('Y-m-d', strtotime($_SESSION['temp']['date_to_show'] . ' +'.$nbr_of_line.' day'))."' 
    and allocationshift_code <> 'Null'
    AND ((DATEPART(DW,allocationcontract_date)<>6 and DATEPART(DW,allocationcontract_date)<>7 
        ) or((DATEPART(DW,allocationcontract_date)=6 or DATEPART(DW,allocationcontract_date)=7)and baseallocation_working<100 ))
    AND (baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100)>0
    group by allocationcontract_date,allocationwork_operatorid
    order by allocationcontract_date asc";
    //show($query);
    if($_SESSION['temp']['id']=='CorentinHillion'){
        //show($query);
    }
    $sql = $db->prepare($query); 
    $sql->execute();
    //show(nbr_of_line);
    $rowtemp=$sql->fetchall();
    
    foreach($rowtemp as $line){
        
        $row[$line['allocationcontract_date']] [$line['allocationwork_operatorid']]  = $row[$line['allocationcontract_date']] [$line['allocationwork_operatorid']]  +$line["hours_available"];
        if($_SESSION['temp']['id']=='CorentinHillion' and $line['allocationwork_operatorid']=='DIONCHEVELLE ALLEN'){
            //show($line['allocationcontract_date'].' '.$row[$line['allocationcontract_date']] [$line['allocationwork_operatorid']]);
        }
        
    }
    

   // show($row);


    return $row;
}

function get_list_operator($db,$table=''){
    if (empty($_SESSION['temp']['offset'])){$_SESSION['temp']['offset']=0;}
    //show($_SESSION['temp']['offset']);
    $offset=$_SESSION['temp']['offset'];
    $nbr_of_row=20;
    if(!empty($_POST['detail_operator'])){$nbr_of_row=999;$offset=0;}
    if($_SESSION['temp']['transpose_view']=='transpose'){
        $offset=0;
        $nbr_of_row=9999;
    }
    $nbr_of_line=nbr_of_line;
    if($_SESSION['temp']['transpose_view']=='transpose'){
       
        $nbr_of_line=5;
    }
    if(empty($table)){$table='allocationcontract';}

    if($_SESSION['temp']['view_roster']<>'Contract'){
        $addition1=" Left Join (
        SELECT max(iif(allocationcontract_code<>'',1,0))as hascontract,allocationcontract_operatorid,allocationcontract_code FROM dbo.allocationcontract 
                WHERE allocationcontract_date>='".$_SESSION['temp']['date_to_show']."' 
                AND allocationcontract_date<='".date('Y-m-d', strtotime($_SESSION['temp']['date_to_show'] . ' +'.$nbr_of_line.' day'))."'
                group by allocationcontract_operatorid,allocationcontract_code
                )as temp
                on temp.allocationcontract_operatorid=operator_fullname ";
        $addition2=" and hascontract=1 ";
        if(!empty($_SESSION['temp']['group_contract'])){
            $orderby="allocationcontract_code ASC,";
        }
        //
            }

    if($_SESSION['temp']['view_roster']<>'Shift' and $_SESSION['temp']['view_roster']<>'Contract'){
        $addition11=" Left Join (
        SELECT max(iif(allocationshift_code<>'',1,0))as hasshift,allocationshift_operatorid FROM dbo.allocationshift
                WHERE allocationshift_date>='".$_SESSION['temp']['date_to_show']."' 
                AND allocationshift_date<='".date('Y-m-d', strtotime($_SESSION['temp']['date_to_show'] . ' +'.$nbr_of_line.' day'))."'
                group by allocationshift_operatorid
                )as temp2
                on temp2.allocationshift_operatorid=operator_fullname ";
        $addition12=" and hasshift=1 ";
            }

    if($_SESSION['temp']['transpose_view']=='transpose' and !empty($_SESSION['temp']['filter_workarea'])){
        $addition21=" Left Join (
            SELECT allocationwork_code,allocationwork_operatorid FROM dbo.allocationwork
                    WHERE allocationwork_date>='".$_SESSION['temp']['date_to_show']."' 
                    AND allocationwork_date<='".date('Y-m-d', strtotime($_SESSION['temp']['date_to_show'] . ' +'.$nbr_of_line.' day'))."'
                    group by allocationwork_code,allocationwork_operatorid
                    )as temp3
                    on temp3.allocationwork_operatorid=operator_fullname ";
       $addition22=" and temp3.allocationwork_code='".$_SESSION['temp']['filter_workarea']."' ";
            }

    $addition99=" Left Join (
        SELECT allocationwork_operatorid,sum(baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100) as hours_available FROM dbo.allocationcontract
    left join dbo.allocationwork on allocationwork_operatorid=allocationcontract_operatorid and allocationwork_date=allocationcontract_date
    left join dbo.allocation on allocation_operatorid=allocationcontract_operatorid and allocation_date=allocationcontract_date
    left join dbo.allocationshift on allocationshift_operatorid=allocationcontract_operatorid and allocationshift_date=allocationcontract_date
    left join dbo.baseallocationshift on baseallocationshift_code=allocationwork_code
    left join dbo.baseallocationwork on baseallocationwork_code=allocationwork_code 
    left join dbo.baseallocation on baseallocation_code=allocation_code 
    left join dbo.baseallocationcontract on baseallocationcontract_code=allocationcontract_code 
    WHERE allocationcontract_date>='".$_SESSION['temp']['date_to_show']."' 
    AND allocationcontract_date<='".date('Y-m-d', strtotime($_SESSION['temp']['date_to_show'] . ' +'.$nbr_of_line.' day'))."' 
    and allocationshift_code <> 'Null'
    AND ((DATEPART(DW,allocationcontract_date)<>6 and DATEPART(DW,allocationcontract_date)<>7 
        ) or((DATEPART(DW,allocationcontract_date)=6 or DATEPART(DW,allocationcontract_date)=7)and baseallocation_working<100 ))
    AND (baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100)>0
	group by allocationwork_operatorid)as temp99
                on temp99.allocationwork_operatorid=operator_fullname ";

    $addition999=" or temp99.hours_available>0 ";
    


    $query='SET DATEFIRST 1;
    SELECT *
    FROM [barcode].[dbo].[operator]
    '.$addition1.' '.$addition11.' '.$addition21.' '.$addition99.'
    WHERE (operator_active=1 '.$addition999.')
    '.$addition2.' '.$addition12.' '.$addition22.'
    ORDER by '.$orderby.' '.$_SESSION['temp']['roster_sort'].'
    OFFSET '.$offset.' ROWS
	FETCH NEXT '.$nbr_of_row.' ROWS ONLY
    ';
   
  
  $sql = $db->prepare($query); 
  $sql->execute();
  //show($query);
  $row=$sql->fetchall();
  
  return $row;
}

function get_list_operator_with_css($db,$table=''){
   
    $nbr_of_row=999;
    $offset=0;
    
    
    if(empty($table)){$table=get_table_allocation();}
    if($table=='allocation'){
        $table='allocationshift';
        $addition1="";
            $addition2="";
            
            $orderby="";
     }

    
        $addition1=" Left Join (
        SELECT max(iif(".$table."_code<>'',1,0))as hascontract,".$table."_operatorid,".$table."_code FROM dbo.".$table."
                WHERE ".$table."_date>='".$_SESSION['temp']['date_to_show']."' 
                AND ".$table."_date<='".date('Y-m-d', strtotime($_SESSION['temp']['end_date_to_show'] ))."'
                group by ".$table."_operatorid,".$table."_code
                )as temp
                on temp.".$table."_operatorid=operator_fullname ";
        $addition2=" and hascontract=1 ";
        
        if($table<>'allocationcontract'){
            $orderby="".$table."_code ASC,";
        }elseif(!empty($_SESSION['temp']['group_contract'])){
            $orderby="allocationcontract_code ASC,";
        }

        
           

    
    


    $query='SELECT *
    FROM [barcode].[dbo].[operator]
    '.$addition1.' 
    WHERE operator_active=1
    '.$addition2.' 
    ORDER by '.$orderby.' '.$_SESSION['temp']['roster_sort'].'
    OFFSET '.$offset.' ROWS
	FETCH NEXT '.$nbr_of_row.' ROWS ONLY
    ';
   
  
  $sql = $db->prepare($query); 
  $sql->execute();
  //show($query);
  $row=$sql->fetchall();
  
  return $row;
}

function remove_allocation($db,$day,$operator){
    
  //  $alltable=['allocation','allocationwork','allocationcontract'];
    
  //  foreach($alltable as $table){
    $table=get_table_allocation();
        $query="SELECT  ".$table."_code
        FROM [barcode].[dbo].[".$table."]
        WHERE ".$table."_date='".$day."' and ".$table."_operatorid='".$operator."'
        ";
        //show($query);
        $sql = $db->prepare($query); 
        $sql->execute();
        $rowtemp=$sql->fetch();
        if(!empty($rowtemp)){
            $query="DELETE FROM dbo.$table
            WHERE
            ".$table."_operatorid =  '".$_POST['operator']."'

            AND ".$table."_date='".$_POST['day']."'
            
            ";
            //show($query);
            $sql = $db->prepare($query); 
            $sql->execute();
            return;
        }

    
  
   // }


}

function get_current_allocation($db,$day,$operator){
    //show('test');
    //$alltable=['allocationcontract','allocationwork','allocation'];
    
    //foreach($alltable as $table){
   
        $table=get_table_allocation();
        $query="SELECT  ".$table."_code
        FROM [barcode].[dbo].[".$table."]
        WHERE ".$table."_date='".$day."' and ".$table."_operatorid='".$operator."'
        ";
        //show($query);
        $sql = $db->prepare($query); 
        $sql->execute();
        $rowtemp=$sql->fetch();
        if(!empty($rowtemp)){$row=$rowtemp[0];}

       

        
        
  
   // }
    return $row;

}

function get_current_notes($db,$day,$operator){
    //show('test');
    //$alltable=['allocationcontract','allocationwork','allocation'];
    
    //foreach($alltable as $table){
   
        $table=get_table_allocation();
        $query="SELECT  allocationnotes_notes
        FROM allocationnotes
        WHERE allocationnotes_date='".$day."' and allocationnotes_operatorid='".$operator."' and allocationnotes_table='".$table."'
        ";
        //show($query);
        $sql = $db->prepare($query); 
        $sql->execute();
        $rowtemp=$sql->fetch();
        if(!empty($rowtemp)){$row=$rowtemp[0];}

       

        
        
  
   // }
    return $row;

}

function add_notes_allocation($db){
    $table=get_table_allocation();
    $day=$_POST['day'];
    $Operator=$_POST['operator'];
    $Notes=$_POST['Notes'];
    $query="DELETE from dbo.allocationnotes

    WHERE allocationnotes_table='".$table."'
    and allocationnotes_operatorid='".$Operator."'

    and allocationnotes_date='".$day."'
      ";	

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $query="INSERT INTO dbo.allocationnotes
        ( allocationnotes_table,
        allocationnotes_operatorid,
        allocationnotes_notes,
        allocationnotes_date
        ) 
        VALUES (
        '".$table."',
        '".$Operator."',
        '".$Notes."',
        '".$day."')";	

        $sql = $db->prepare($query); 
        $sql->execute();
        add_log_allocation($db,$_POST['day'],$_POST['operator'],'Notes added to table '.$table.' - '.$Notes);

}

function remove_notes_allocation($db){
    $table=get_table_allocation();
    $day=$_POST['day'];
    $Operator=$_POST['operator'];
    $Notes=$_POST['Notes'];

    $query="DELETE from dbo.allocationnotes

    WHERE allocationnotes_table='".$table."'
    and allocationnotes_operatorid='".$Operator."'

    and allocationnotes_date='".$day."'
      ";	

        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        add_log_allocation($db,$_POST['day'],$_POST['operator'],'Notes removed from table '.$table);

}


function add_allocation($db){

    //Remove action, we remove 1st all action, if no action if remove WorkArea , if no WorkArea if remove contract
    $remove=(!(empty($_POST['remove'])) or $_POST['brush']=='Remove') ;
    
    if($remove==1){
        $table=get_table_allocation();
        remove_allocation($db,$_POST['day'],$_POST['operator']);
        add_log_allocation($db,$_POST['day'],$_POST['operator'],'allocation removed from table '.$table);
    }else{
        

        //find which table is the brush
        $table=get_table_allocation();
        //show($table);

        $query="DELETE FROM dbo.$table
        WHERE
        ".$table."_operatorid =  '".$_POST['operator']."'

        AND ".$table."_date='".$_POST['day']."'
        ";	

        $sql = $db->prepare($query); 
        $sql->execute();
        //show($query);

        $query="INSERT INTO dbo.$table
        ( ".$table."_operatorid,
        ".$table."_code,
        ".$table."_date
        ) 
        VALUES (
        '".$_POST['operator']."',
        '".$_POST['brush']."',
        '".$_POST['day']."')";	
        
        
        //show($query);
     
        $sql = $db->prepare($query); 
        $sql->execute();
        add_log_allocation($db,$_POST['day'],$_POST['operator'],$_POST['brush'].' added to table '.$table);

    }






    

    
    
}

function add_log_allocation($db,$date,$operatorid,$entry){

    $modifyby=$_SESSION['temp']['id'];
    $query='SELECT TOP 1 allocationlog_line_id
    FROM [barcode].[dbo].[allocationlog]
    WHERE allocationlog_operatorid=\''.$operatorid.'\'
    and allocationlog_date=\''.$date.'\'
    order by allocationlog_line_id DESC

    ';
    $entry=$entry.' by '.$modifyby.' on '. date('jS M Y \a\t G:i:s',time()+10*3600)	;
    $sql = $db->prepare($query); 
    $sql->execute();
    
    $row=$sql->fetch();
   
    $line_id=$row[0]+1;


    $query="INSERT INTO dbo.allocationlog
    ( allocationlog_date,
    allocationlog_line_id,
    allocationlog_operatorid,
    allocationlog_modify_by,
    allocationlog_entry,
    allocationlog_timetag
    ) 
    VALUES (
    '".$date."',
    '".$line_id."',
    '".$operatorid."',
    '".$modifyby."',
    '".$entry."',
    '".time()."')";	


    

    $sql = $db->prepare($query); 
    $sql->execute();
   

    
    
}


function get_operator_code($db,$operator_full_name){
    $query='SELECT operator_code
    FROM [barcode].[dbo].[operator]
    WHERE operator_fullname=\''.$operator_full_name.'\'
    
    ';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  //show($query);
  $row=$sql->fetch();
  
  return $row[0];

}

function get_next_day($db){ 

    $table=get_table_allocation();
    


    $query="SELECT ".$table."_code
    FROM [barcode].[dbo].[".$table."]
    WHERE ".$table."_date='".$_POST['day']."' and ".$table."_operatorid='".$_POST['operator']."'  ";
    
    $sql = $db->prepare($query); 
    $sql->execute();
    
    $row=$sql->fetch();
    $brush=$row[0];
    $brushfilter=" AND ".$table."_code<>'".$brush."'";
    
    
    if($brush=''){$brushfilter='';}

    $query="SELECT min( ".$table."_date)
        FROM [barcode].[dbo].[".$table."]
        WHERE ".$table."_date>='".$_POST['day']."' and ".$table."_operatorid='".$_POST['operator']."' ".$brushfilter."
        
        ";
        
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();
    
    if (empty($row[0])){
       
    }
    
    return $row[0];
    
       

}

function update_graphic_table($db){

    


    $id=date('Ymd',strtotime($_POST['day'])).get_operator_code($db,$_POST['operator']);
    $css=$_POST['brush'];
    $caption=$_POST['brush'];
    
    $remove=(!(empty($_POST['remove'])) or $_POST['brush']=='Remove'or empty($_POST['brush'])) ;
    if($remove==1){$caption='';$css='';}
    
    $css=get_current_allocation($db,$_POST['day'],$_POST['operator'])    ;
    $notes=get_current_notes($db,$_POST['day'],$_POST['operator'])    ;
    
    $caption=$css;
    if($_SESSION['temp']['transpose_view']=='transpose'){
        $caption=$caption.'<br>';
        $hours=get_hours($db,date('Y-m-d',strtotime($_POST['day'])),$_POST['operator']);
        
        if(!empty($hours)){
            $caption=$caption.round($hours,1).' h';
        }else{
            $caption=$caption.' - ';
        }
    }
    if(!empty($notes)){
        //$caption=$caption.' <span class=\"glyphicon glyphicon-info-sign popover__title\"></span>      ';

        $caption=$caption.' <div class=\"popover__wrapper2\">';
        $caption=$caption.'<span class=\"glyphicon glyphicon-info-sign popover__title2\"></span>';
        $caption=$caption.'<div class=\"popover__content2\"> Notes:'.$notes.'</div>';
        $caption=$caption.'</div>';
        
    }
    
    $option1=' detailsshift ';
    if($_SESSION['temp']['transpose_view']=='transpose'){$width='2';$option1=' ';}else{$width='20ths';}

    
    echo'<script>
            document.getElementById("'.$id.'").className=\'col-sm-'.$width.' day-roster '.$option1.' '.$css.'\';
            document.getElementById("'.$id.'").innerHTML = "'.$caption.'";
            </script>';

    if($_SESSION['temp']['transpose_view']=='transpose'){
        show_stats($db);
       
    }
}

function show_stats ($db){
    create_css ($db,'allocationwork');
    $totalhours=get_total_hours($db);
        
        foreach($totalhours as $total){
            
            if(!empty($total['WorkArea'])){
                
                $stats_caption=$stats_caption.'<div class=\"row \" >'.round($total['Hours'],1).' h</div>';
                
                 
               
                $stats_caption2=$stats_caption2.'<div class=\"row ';
                if($_SESSION['temp']['filter_workarea']==$total['WorkArea']){$stats_caption2=$stats_caption2. 'main-box '.$total['WorkArea'];}
                $stats_caption2=$stats_caption2.'\"  onClick=\"document.forms[\'filter-'.$total['WorkArea'].'\'].submit();\" >'.$total['WorkArea'].' Hours :</div>';
                $stats_caption2=$stats_caption2.'<form method=\"POST\" id=\"filter-'.$total['WorkArea'].'\">';
                $stats_caption2=$stats_caption2.'<input type=\"hidden\" name=\"filter\" value=\"'.$total['WorkArea'].'\">';
                $stats_caption2=$stats_caption2.'</form>';
                
            }else{
                $thetotal=$total['total'];
            }
            
        }

                
                
        //show($stats_caption);
        //$stats_caption2='test';
        echo'<script>
            document.getElementById("All_result").innerHTML = "";
            document.getElementById("All_result").innerHTML = "'.round($thetotal,1).' h";
            document.getElementById("Details_result").innerHTML = "'.$stats_caption.'";
            document.getElementById("Details_header").innerHTML = "'.$stats_caption2.'";
            
            </script>';
}

function get_hours($db,$date,$operator_full_name){
    $query="SET DATEFIRST 1;
    SELECT sum(baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100) as hours_available FROM dbo.allocationcontract
    left join dbo.allocationwork on allocationwork_operatorid=allocationcontract_operatorid and allocationwork_date=allocationcontract_date
    left join dbo.allocation on allocation_operatorid=allocationcontract_operatorid and allocation_date=allocationcontract_date
    left join dbo.allocationshift on allocationshift_operatorid=allocationcontract_operatorid and allocationshift_date=allocationcontract_date
    left join dbo.baseallocationshift on baseallocationshift_code=allocationwork_code
    left join dbo.baseallocationwork on baseallocationwork_code=allocationwork_code 
    left join dbo.baseallocation on baseallocation_code=allocation_code 
    left join dbo.baseallocationcontract on baseallocationcontract_code=allocationcontract_code 
    WHERE allocationcontract_date='".$date."' 
    and allocationcontract_operatorid='".$operator_full_name."'
    and allocationshift_code <> 'Null'
    AND ((DATEPART(DW,allocationcontract_date)<>6 and DATEPART(DW,allocationcontract_date)<>7 
        ) or((DATEPART(DW,allocationcontract_date)=6 or DATEPART(DW,allocationcontract_date)=7)and baseallocation_working<100 ))
    AND (baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100)>0
    group by allocationcontract_date,allocationwork_operatorid
    order by allocationcontract_date asc";
   //show($query);
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();
    return $row[0];
}

function get_total_hours($db){
    $query="SET DATEFIRST 1;
    SELECT allocationwork_code,sum(baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100) as hours_available FROM dbo.allocationcontract
    left join dbo.allocationwork on allocationwork_operatorid=allocationcontract_operatorid and allocationwork_date=allocationcontract_date
    left join dbo.allocation on allocation_operatorid=allocationcontract_operatorid and allocation_date=allocationcontract_date
    left join dbo.allocationshift on allocationshift_operatorid=allocationcontract_operatorid and allocationshift_date=allocationcontract_date
    left join dbo.baseallocationshift on baseallocationshift_code=allocationwork_code
    left join dbo.baseallocationwork on baseallocationwork_code=allocationwork_code 
    left join dbo.baseallocation on baseallocation_code=allocation_code 
    left join dbo.baseallocationcontract on baseallocationcontract_code=allocationcontract_code 
    WHERE allocationcontract_date>='".$_SESSION['temp']['date_to_show']."' 
    and allocationcontract_date<='".date('Y-m-d',strtotime($_SESSION['temp']['date_to_show'] . ' +5 days'))."'
    AND ((DATEPART(DW,allocationcontract_date)<>6 and DATEPART(DW,allocationcontract_date)<>7 
        ) or((DATEPART(DW,allocationcontract_date)=6 or DATEPART(DW,allocationcontract_date)=7)and baseallocation_working<100 ))
    and allocationshift_code <> 'Null'
    AND (baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100)>0
    group by allocationwork_code
    order by sum(baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100) desc";
   //show($query);
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetchall();
    $i=0;
    foreach($row as $temp){
        $total=$total+$temp['hours_available'];
        $row2[$i]['WorkArea']=$temp['allocationwork_code'];
        $row2[$i]['Hours']=$temp['hours_available'];
        $i++;
    }
    $row2[$i]['total']=$total;
    //show($row2);
    return $row2;
}



function get_table_allocation($forcetable=''){
   if(empty($forcetable)){
        if($_SESSION['temp']['view_roster']=='Action'and $i==0){$table='allocation';}
        if($_SESSION['temp']['view_roster']=='WorkArea'and $i==0){$table='allocationwork';}
        if($_SESSION['temp']['view_roster']=='Contract'and $i==0){$table='allocationcontract';}
        if($_SESSION['temp']['view_roster']=='Shift'and $i==0){$table='allocationshift';}
    }else{
        $table=$forcetable;
    }
    return $table;
}

function create_css ($db,$forcetable=''){
   
    $table=get_table_allocation($forcetable);
    $basetable='base'.$table;
    $rowtemp=get_basetable_allocation($db,$forcetable);
    //show($rowtemp);
    echo'<style>';
    foreach($rowtemp as $category){
        echo'
        .'.$category[$basetable.'_code'].'{
            background:#'.$category[$basetable.'_color'].';
        }
        ';

    }
    
    
    



    echo'</style>';
}

function get_basetable_allocation($db,$forcetable=''){
   
    $table=get_table_allocation($forcetable);
    $addon='';
    if($table=='allocation'){$addon='baseallocation_sort asc,';}
    $basetable='base'.$table;
    $query="SELECT  *
    FROM [barcode].[dbo].[".$basetable."]
    order by ".$addon." ".$basetable."_id asc
    ";
    $sql = $db->prepare($query); 
   // show($query);
    $sql->execute();
    $rowtemp=$sql->fetchall();
    return $rowtemp;
}

function create_menu($db){
    $alloption=get_basetable_allocation($db);
    $table=get_table_allocation();
    $basetable='base'.$table;
    echo'<div class="row main-box">';
        echo'<div class="row "><b>'.$_SESSION['temp']['view_roster'].'</b></div>';
        echo'<div id="blockNotes" class="row day-roster Notes action-menu ';
            if($_POST['brush_'.$_SESSION['temp']['view_roster']]=='Notes'){echo' action-selected ';}
        echo'" onClick="activate_brush(\'blockNotes\',\'Notes\');" >Notes</div>';
        foreach($alloption as $category){
            if(empty($category[$basetable.'_adminonly']) or empty(allow_modify($db,'roster_admin'))){

                echo'<div id="block'.$category[$basetable.'_code'].'" class="row day-roster '.$category[$basetable.'_code'].' action-menu ';
                    if($_POST['brush_'.$_SESSION['temp']['view_roster']]==$category[$basetable.'_code']){echo' action-selected ';}
                    if($_POST['brush']==$category[$basetable.'_code']){echo' action-selected ';}
                echo'" onClick="activate_brush(\'block'.$category[$basetable.'_code'].'\',\''.$category[$basetable.'_code'].'\');" >'.$category[$basetable.'_name'].'</div>';

                //onClick="document.forms[\''.$category[$basetable.'_code'].'\'].submit();"
                echo'<form method="POST" id="'.$category[$basetable.'_code'].'"><input class="form-control" type="hidden" name="brush_'.$_SESSION['temp']['view_roster'].'" value="'.$category[$basetable.'_code'].'"></form>';
            }
           
        }
       
        
    echo'</div>'; 

    echo'<script>

    var brush_total="'.$_POST['brush'].'";
    var Brush_work = \''.$_POST['brush_WorkArea'].'\';
    var Brush_contract = \''.$_POST['brush_Contract'].'\';
    var Brush_shift = \''.$_POST['brush_Shift'].'\';
    var REMOVE = \'Remove\';


    function activate_brush(ID,brush){
        
        y=document.getElementsByClassName("action-menu");
        var j;
        for (j = 0; j < y.length; j++) {
          y[j].classList.remove(\'action-selected\');
         
          
        }
        
        document.getElementById(ID).classList.toggle(\'action-selected\');
        
        brush_total=brush;
        
        
        //x=document.getElementsByClassName("brush_hidden");
        
       // var i;
       // for (i = 0; i < x.length; i++) {
       //   x[i].value = brush;
         
          
       // }

       
        
        

        
        
       
      
        
    }
    </script>';
    

}


function show_table_normal($db){
    create_css ($db,'allocationcontract');
    $data_date=get_date_data($db);
    $operator_list=get_list_operator($db);
    $data_allocation=get_data_allocation($db);
    $data_notes=get_data_notes($db);
    if($_SESSION['temp']['view_roster']=='Hours'){$data_allocation=get_data_hours($db);}
    if($_SESSION['temp']['id']=='CorentinHillion'){
        //show($data_allocation);
    }
    
    
    echo'<div style="padding-right:6em;">';
    echo'<div class="row main-box">';
        echo'<div class="col-sm-1 " style="text-align: center;">';
            echo'<div class="col-sm-4 ">';
            if($_SESSION['temp']['offset']<>0){echo'<span class="glyphicon glyphicon-step-backward" onClick="document.forms[\'Operator_down\'].submit();" ></span>';}
            echo'</div>';
            echo'<div class="col-sm-4 ">';
            echo'</div>';
            //if($_SESSION['temp']['offset']>25)echo'<span class="glyphicon glyphicon-home" onClick="document.forms[\'Operator_zero\'].submit();" ></span>';
            echo'<div class="col-sm-4 ">';
            if(count($operator_list)==20){echo'<span class="glyphicon glyphicon-step-forward" onClick="document.forms[\'Operator_up\'].submit();" ></span>';}
            echo'</div>';
            
            echo'</div>';
            echo'<form method="POST" id="Operator_down"><input class="form-control" type="hidden" name="Operator_list" value="down"></form>';
            echo'<form method="POST" id="Operator_zero"><input class="form-control" type="hidden" name="Operator_list" value="zero"></form>';
            echo'<form method="POST" id="Operator_up"><input class="form-control" type="hidden" name="Operator_list" value="up"></form>';
        echo'<div class="col-sm-11 ">';
        
            foreach($operator_list as $operator){
                echo'<div class="col-sm-20ths day-roster header-operator ';
                echo $operator['allocationcontract_code'];

                echo'" onClick="document.forms[\''.$operator['operator_fullname'].'\'].submit();">';

                echo'<form method="POST" id="'.$operator['operator_fullname'].'"><input class="form-control" type="hidden" name="detail_operator" value="'.$operator['operator_fullname'].'"></form>';
            
                if(substr($_SESSION['temp']['roster_sort'],0,10)=='operator_l'){
                    echo $operator['operator_lastname'].'<br>'.$operator['operator_name'];
                }else{
                    echo $operator['operator_name'].'<br>'.$operator['operator_lastname'];
                }
                
                echo'</div>';
                
            }
        echo'</div>';
    echo'</div>';
    echo'</div>';

    echo'<div class="all_date">';
    
    foreach($data_date as $eachday){
        
        echo'<div class="row main-box ';
        if(date('D',strtotime($eachday)) == 'Sat' || date('D',strtotime($eachday)) == 'Sun') {echo' Week-End ';} 
        echo'">';
            echo'<div class="col-sm-1 " style="text-align: center;">'.date('D jS',strtotime($eachday));
            echo'<br>'.date('M Y',strtotime($eachday));
            echo'</div>';
            echo'<div class="col-sm-11 ">';
        
            foreach($operator_list as $operator){
                $id=date('Ymd',strtotime($eachday)).$operator['operator_code'];
                echo'<div id="'.$id.'" class="col-sm-20ths day-roster detailsshift ';
                    if(date('D',strtotime($eachday)) == 'Sat' || date('D',strtotime($eachday)) == 'Sun') {echo' Week-End ';} 

                // $current_allocation=get_current_allocation($db,date('Y-m-d',strtotime($eachday)),$operator['operator_fullname']);
                
                    
                    //if(!empty($current_allocation) ) {echo $current_allocation;}
                    if(!empty($data_allocation[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']]) ) {echo$data_allocation[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']] ;} 
                echo'"   ';

                if(empty(allow_modify($db,'roster_modify'))){ 
                    echo' oncontextmenu="remove_allocation_v2(\''.date('Y-m-d',strtotime($eachday)).'\',\''.$operator['operator_fullname'].'\');return false; " ';
                    echo' onClick="add_allocation_v2(\''.date('Y-m-d',strtotime($eachday)).'\',\''.$operator['operator_fullname'].'\');"';
                }
                echo'>';

                //document.forms[\''.$id.'\'].submit();
                if($_SESSION['temp']['view_roster']=='Hours'){
                    if(!empty($data_allocation[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']])){
                        echo round($data_allocation[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']],1);
                    }else{
                        echo' - ';
                    }
                }else{
                    if(!empty($data_allocation[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']]) ) {
                        echo$data_allocation[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']] ;
                        if(!empty($data_notes[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']])){
                            echo'   <div class="popover__wrapper2">
                            <span class="glyphicon glyphicon-info-sign popover__title2"></span> 
                            <div class="popover__content2"> Notes:'.$data_notes[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']].'</div>
                            </div>      ';
                        }
                    } 
                
                }
                //if(!empty($current_allocation) ) {echo $current_allocation;}
                echo'</div>';
                

                
            
                
            }
            echo'</div>';
        echo'</div>';
    }

    echo'</div>';
    echo'<script>
    function add_allocation_v2(theday,theoperator) {
        
        if (brush_total =="Notes") {
            notes_value=window.prompt("Add a Notes",);
            $.ajax({type:\'POST\',url:\'roster_ajax.php\',data: {day: theday,operator: theoperator,Notes: notes_value},success:function(html){$(\'.postinfo\').append(html);}});
        
        } else{
            $.ajax({type:\'POST\',url:\'roster_ajax.php\',data: {day: theday,operator: theoperator,brush: brush_total,brush_WorkArea: Brush_work,brush_Contract: Brush_contract,brush_Shift: Brush_shift},success:function(html){$(\'.postinfo\').append(html);}});
        }
    
        
        
        
    }

    function remove_allocation_v2(theday,theoperator) {
                
                
                
        if (brush_total =="Notes") {
            if (window.confirm("Are you sure to remove the Note?")) {
                $.ajax({type:\'POST\',url:\'roster_ajax.php\',data: {day: theday,operator: theoperator,Notes: REMOVE},success:function(html){$(\'.postinfo\').append(html);}});
            }
            
        
        } else{
            $.ajax({type:\'POST\',url:\'roster_ajax.php\',data: {day: theday,operator: theoperator,brush: brush_total,brush_WorkArea: Brush_work,brush_Contract: Brush_contract,brush_Shift: Brush_shift, remove: REMOVE},success:function(html){$(\'.postinfo\').append(html);}});
        }
        
       
        
        
        //return false;
        
    }

    </script>';
}

function show_table_normal_transpose($db){
    $data_date=get_date_data($db);
    $operator_list=get_list_operator($db);
    $data_allocation=get_data_allocation($db);
    $data_notes=get_data_notes($db);
    //show($data_notes);
    create_css ($db,'allocationcontract');
    
    $data_hours=get_data_hours($db);
    //show($data_hours);
    if($_SESSION['temp']['view_roster']=='Hours'){$data_allocation=get_data_hours($db);}
    
    
    echo'<div class="row main-box" >';
        echo'<div class="col-sm-6 " style="padding-right:2em;">';
            echo'<div class="row main-box">';
                echo'<div class="col-sm-2 " style="text-align: center;">';
                    echo'<div class="col-sm-4 ">';
                    echo'<span class="glyphicon glyphicon-step-backward" onClick="document.forms[\'Week_down\'].submit();" ></span>';
                    echo'</div>';
                    echo'<div class="col-sm-4 ">';
                    echo'</div>';
                    //if($_SESSION['temp']['offset']>25)echo'<span class="glyphicon glyphicon-home" onClick="document.forms[\'Operator_zero\'].submit();" ></span>';
                    echo'<div class="col-sm-4 ">';
                    echo'<span class="glyphicon glyphicon-step-forward" onClick="document.forms[\'Week_up\'].submit();" ></span>';
                    echo'</div>';
                    
                    echo'</div>';
                    echo'<form method="POST" id="Week_down"><input class="form-control" type="hidden" name="Week_list" value="down"></form>';
                    
                    echo'<form method="POST" id="Week_up"><input class="form-control" type="hidden" name="Week_list" value="up"></form>';
                echo'<div class="col-sm-10">';
                    foreach($data_date as $eachday){
                    //foreach($operator_list as $operator){
                        echo'<div class="col-sm-2 day-roster ">'.date('D jS',strtotime($eachday));
                        echo'<br>'.date('M Y',strtotime($eachday));
                        echo'</div>';
                        
                    }
                echo'</div>';
            echo'</div>';
        echo'</div>';
        echo'<div class="col-sm-6 Stats-table" style="text-align: center;  font-size:35px;">Stats</div">';
        echo'</div>';
        
    echo'</div>';

    //echo'<div class="all_date">';
    echo'<div class="row main-box ">';
        echo'<div class="col-sm-6 all_date">';
            
            foreach($operator_list as $operator){
                
                    echo'<div class="row main-box ">';
                        echo'<div class="col-sm-2 day-roster ';
                        echo $operator['allocationcontract_code'];

                        echo'" style="text-align: center;" onClick="document.forms[\''.$operator['operator_fullname'].'\'].submit();">';

                        echo'<form method="POST" id="'.$operator['operator_fullname'].'"><input class="form-control" type="hidden" name="detail_operator" value="'.$operator['operator_fullname'].'"></form>';
            


                        
                        if(substr($_SESSION['temp']['roster_sort'],0,10)=='operator_l'){
                            echo $operator['operator_lastname'].'<br>'.$operator['operator_name'];
                        }else{
                            echo $operator['operator_name'].'<br>'.$operator['operator_lastname'];
                        }
                        
                        echo'</div>';
                        echo'<div class="col-sm-10 ">';
                    
                        foreach($data_date as $eachday){
                            $total=$total+$data_hours[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']];
                            
                            $id=date('Ymd',strtotime($eachday)).$operator['operator_code'];
                            echo'<div id="'.$id.'" class="col-sm-2 day-roster ';
                                if(date('D',strtotime($eachday)) == 'Sat' || date('D',strtotime($eachday)) == 'Sun') {echo' Week-End ';} 

                            // $current_allocation=get_current_allocation($db,date('Y-m-d',strtotime($eachday)),$operator['operator_fullname']);
                            
                                
                                //if(!empty($current_allocation) ) {echo $current_allocation;}
                                if(!empty($data_allocation[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']]) ) {
                                    echo$data_allocation[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']] ;
                                } 
                            echo'"  ';

                            if(empty(allow_modify($db,'roster_modify'))){
                                echo' oncontextmenu="remove_allocation_v2(\''.date('Y-m-d',strtotime($eachday)).'\',\''.$operator['operator_fullname'].'\');return false; " ';
                                echo' onClick="add_allocation_v2(\''.date('Y-m-d',strtotime($eachday)).'\',\''.$operator['operator_fullname'].'\');"';
                            }
                            
                           echo' >';

                            //document.forms[\''.$id.'\'].submit();
                            if($_SESSION['temp']['view_roster']=='Hours'){
                                if(!empty($data_allocation[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']])){
                                    echo round($data_allocation[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']],1);
                                }else{
                                    echo' - ';
                                }
                            }else{
                                if(!empty($data_allocation[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']]) ) {
                                    echo $data_allocation[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']] ;
                                    
                                } 
                                echo'<br>';
                                if(!empty($data_hours[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']])){
                                    echo round($data_hours[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']],1).' h';
                                }else{
                                    echo' - ';
                                }

                                if(!empty($data_notes[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']])){
                                    echo'   <div class="popover__wrapper2">
                                    <span class="glyphicon glyphicon-info-sign popover__title2"></span> 
                                    <div class="popover__content2"> Notes:'.$data_notes[date('Y-m-d',strtotime($eachday))][$operator['operator_fullname']].'</div>
                                    </div>      ';
                                }
                            }
                            //if(!empty($current_allocation) ) {echo $current_allocation;}
                            echo'</div>';
                            

                        
                            
                        }
                        echo'</div>';
                    echo'</div>';
               
            }
            echo'</div>';
            
            echo'<div class="col-sm-6 Stats-table" >';
                echo'<div class="row">';
                    echo'<div class="col-sm-7 ">Total Hours Available : </div>';
                    echo'<div class="col-sm-3 " id="All_result"></div>';    
                echo'</div>';
                echo'<div class="row">';
                    echo'<div class="col-sm-7 " id="Details_header"> </div>';
                    echo'<div class="col-sm-3 " id="Details_result"></div>';    
                echo'</div>';
            echo'</div>';show_stats($db);
        echo'</div>';
       
    echo'</div>';


    echo'<script>
    function add_allocation_v2(theday,theoperator) {
        

    
        if (brush_total =="Notes") {
            notes_value=window.prompt("Add a Notes",);
            $.ajax({type:\'POST\',url:\'roster_ajax.php\',data: {day: theday,operator: theoperator,Notes: notes_value},success:function(html){$(\'.postinfo\').append(html);}});
        
        } else{
            $.ajax({type:\'POST\',url:\'roster_ajax.php\',data: {day: theday,operator: theoperator,brush: brush_total,brush_WorkArea: Brush_work,brush_Contract: Brush_contract,brush_Shift: Brush_shift},success:function(html){$(\'.postinfo\').append(html);}});
        }
    
        
        
    }

    function remove_allocation_v2(theday,theoperator) {
                
                
                
        if (brush_total =="Notes") {
            if (window.confirm("Are you sure to remove the note?")) {
                $.ajax({type:\'POST\',url:\'roster_ajax.php\',data: {day: theday,operator: theoperator,Notes: REMOVE},success:function(html){$(\'.postinfo\').append(html);}});
            }
            
        
        } else{
            $.ajax({type:\'POST\',url:\'roster_ajax.php\',data: {day: theday,operator: theoperator,brush: brush_total,brush_WorkArea: Brush_work,brush_Contract: Brush_contract,brush_Shift: Brush_shift, remove: REMOVE},success:function(html){$(\'.postinfo\').append(html);}});
        }
        
        
        //return false;
        
    }

    </script>';



}



function show_table_summary($db){
    $data_date=get_date_data($db);
    create_css ($db,'allocationwork');
    $data_summary=get_data_summary($db);
    //show($data_summary);
    echo'<div style="padding-right:2em;">';
    echo'<div class="row main-box">';
        echo'<div class="col-sm-1 " style="text-align: center;">';
        
            if( $_SESSION['temp']['collapse_day']=='day'){
                echo'<form method="POST">';
                echo'<br><button type="submit" name="collapse_day"  value="week" class="btn btn-default" >
                                        <span class="glyphicon glyphicon-resize-small" > </span>
                                        </button><br>';
                    echo'</form>';

            }else{
                echo'<form method="POST">';
                echo'<br><button type="submit" name="collapse_day" value="day" class="btn btn-default" >
                                        <span class="glyphicon glyphicon-resize-full" > </span>
                                        </button><br>';
                    echo'</form>';
            }
            
                
        echo'</div>';
            
        echo'<div class="col-sm-11 ">';
            echo'<div class="col-sm-1 day-roster "><br> Hours Available<br> </div>';
            $workAreaList=get_basetable_allocation($db,'allocationwork');
            foreach($workAreaList as $workArea){
                if($workArea['baseallocationwork_working']>0 and $data_summary[$workArea['baseallocationwork_code']]['total']>0 ){
                    echo'<div class="col-sm-10ths day-roster  '.$workArea['baseallocationwork_code'].'">
                    <div class="row">'.$workArea['baseallocationwork_name'].'</div>';
                    
                    echo'</div>';
                }
            }
            
            
            
        echo'</div>';
    echo'</div>';
    echo'</div>';

    echo'<div class="all_date">';
    //show($data_summary);
    $lastdate=date('Y-m-d ',strtotime($_POST['date_to_show']));

            
    foreach($data_date as $eachday){
        if($_SESSION['temp']['collapse_day']=='week'){
            
            $total=$total+$data_summary[date('Y-m-d',strtotime($eachday))]['total'];

            $workAreaList=get_basetable_allocation($db,'allocationwork');
            foreach($workAreaList as $workArea){
                $shiftList=get_basetable_allocation($db,'allocationshift');
                foreach($shiftList as $shift){
                    $subtotal[$workArea['baseallocationwork_code']]['total']=$subtotal[$workArea['baseallocationwork_code']]['total']+$data_summary[date('Y-m-d',strtotime($eachday))][$workArea['baseallocationwork_code']][$shift['baseallocationshift_code']];
                    $subtotal[$workArea['baseallocationwork_code']][$shift['baseallocationshift_code']]=$subtotal[$workArea['baseallocationwork_code']][$shift['baseallocationshift_code']]+$data_summary[date('Y-m-d',strtotime($eachday))][$workArea['baseallocationwork_code']][$shift['baseallocationshift_code']];
                }
                
            }

            if(date('D',strtotime($eachday)) == 'Sun') {
                echo'<div class="row main-box ">';
                    echo'<div class="col-sm-1 " style="text-align: center;">'.date('D jS',strtotime($lastdate));
                        echo'<br> to '.date('D jS',strtotime($eachday));
                        echo'<br>'.date('M Y',strtotime($eachday));
                    echo'</div>';
                    echo'<div class="col-sm-11 ">';
                        echo'<div class="col-sm-1 day-roster "><br> ';
                        if(!empty($total)){
                            echo number_format(round($total,1)).' h';
                        }else{
                            echo' - ';
                        }

                        echo'<br><br> </div>';   
                    $workAreaList=get_basetable_allocation($db,'allocationwork');
                        foreach($workAreaList as $workArea){
                            if( $workArea['baseallocationwork_working']>0 and $subtotal[$workArea['baseallocationwork_code']]['total']>0){
                                echo'<div class="col-sm-10ths day-roster  '.$workArea['baseallocationwork_code'].'">
                                <div class="row">';
                                    if(!empty($subtotal[$workArea['baseallocationwork_code']]['total'])){
                                        echo number_format(round($subtotal[$workArea['baseallocationwork_code']]['total'],1)).' h';
                                    }else{
                                        echo' - ';
                                    }
                                echo'</div>';
                                $shiftList=get_basetable_allocation($db,'allocationshift');
                                
                                foreach($shiftList as $shift){
                                    if($subtotal[$workArea['baseallocationwork_code']][$shift['baseallocationshift_code']]>0){
                                        echo'<div class="row  '.$shift['baseallocationshift_code'].' detailsshift">';
                                        if(!empty($subtotal[$workArea['baseallocationwork_code']][$shift['baseallocationshift_code']])){
                                            echo $shift['baseallocationshift_code'].': '.number_format(round($subtotal[$workArea['baseallocationwork_code']][$shift['baseallocationshift_code']],1),1).' h';
                                        }else{
                                            echo' - ';
                                        }
                                        echo'</div>';
                                    }
                                }
                                echo'</div>';
                            }
                        }  
                    echo'</div>';
                echo'</div>';

                unset($subtotal);
                unset($total);
                $lastdate=date('Y-m-d',strtotime($eachday.'+1day'));

            }else{
                
                
                
            }
                
            


        }else{




            
            echo'<div class="row main-box ';
            if(date('D',strtotime($eachday)) == 'Sat' || date('D',strtotime($eachday)) == 'Sun') {echo' Week-End ';} 
            echo'">';
                echo'<div class="col-sm-1 " style="text-align: center;">'.date('D jS',strtotime($eachday));
                echo'<br>'.date('M Y',strtotime($eachday));
                echo'</div>';
                echo'<div class="col-sm-11 ">';
                    echo'<div class="col-sm-1 day-roster "><br> ';
                    if(!empty($data_summary[date('Y-m-d',strtotime($eachday))]['total'])){
                        echo round($data_summary[date('Y-m-d',strtotime($eachday))]['total'],1).' h';
                    }else{
                        echo' - ';
                    }
                    
                    echo'<br><br> </div>';
                    
                    $workAreaList=get_basetable_allocation($db,'allocationwork');
                    foreach($workAreaList as $workArea){
                        if($workArea['baseallocationwork_working']>0 and $data_summary[$workArea['baseallocationwork_code']]['total']>0){
                            echo'<div class="col-sm-10ths day-roster  '.$workArea['baseallocationwork_code'].' ">
                            <div class="row">';
                                if(!empty($data_summary[date('Y-m-d',strtotime($eachday))][$workArea['baseallocationwork_code']]['total'])){
                                    echo round($data_summary[date('Y-m-d',strtotime($eachday))][$workArea['baseallocationwork_code']]['total'],1).' h';
                                }else{
                                    echo' - ';
                                }
                            echo'</div>';
                            $shiftList=get_basetable_allocation($db,'allocationshift');
                            
                            foreach($shiftList as $shift){
                                if($data_summary[$workArea['baseallocationwork_code']][$shift['baseallocationshift_name']]['total']>0){
                                    echo'<div class="row '.$shift['baseallocationshift_code'].' detailsshift">';
                                    if(!empty($data_summary[date('Y-m-d',strtotime($eachday))][$workArea['baseallocationwork_name']][$shift['baseallocationshift_code']])){
                                        echo $shift['baseallocationshift_code'].': '.round($data_summary[date('Y-m-d',strtotime($eachday))][$workArea['baseallocationwork_name']][$shift['baseallocationshift_code']],1).' h';
                                    }else{
                                        echo' - ';
                                    }
                                    echo'</div>';
                                }
                            }
                            echo'</div>';
                        }
                    }
                    
                
                
                echo'</div>';
            echo'</div>';
        }
        
    }

    echo'</div>';
}

function show_table_summary_transpose($db){
}

function show_details_operator($db){
    create_css ($db,'allocation');
    create_css ($db,'allocationwork');
    create_css ($db,'allocationcontract');
    create_css ($db,'allocationshift');
    $operator_list=get_list_operator_with_css($db);
    //show($operator_list);
    $data_date=get_date_data($db);
    $data_allocation=get_data_allocation($db,'allocation',$_POST['detail_operator']);
    $data_allocationwork=get_data_allocation($db,'allocationwork',$_POST['detail_operator']);
    $data_allocationcontract=get_data_allocation($db,'allocationcontract',$_POST['detail_operator']);
    $data_allocationshift=get_data_allocation($db,'allocationshift',$_POST['detail_operator']);
    $data_notes=get_data_notes($db,'allocation',$_POST['detail_operator']);
   
    //show($data_allocationwork);
    echo'<div class="row ">';
        echo'<div class="col-sm-6 headersoperator main-box"> ';
            echo'<div class="row h1operator">'.$_POST['detail_operator'].' - Summary</div>';
            echo'<div class="row "><div class="col-sm-2 ">Date: </div><div class="col-sm-10 ">'.date('jS M Y',strtotime($_SESSION['temp']['date_to_show'] )).' to '.date('jS M Y',strtotime($_SESSION['temp']['end_date_to_show'] )).'</div></div>';
            echo'<div class="row "><div class="col-sm-2 ">Contract: </div><div class="col-sm-10 ">';
            echo summarize_data($data_date,$data_allocationcontract,$_POST['detail_operator']);
            echo'</div></div>';
            echo'<div class="row "><div class="col-sm-2 ">WorkArea: </div><div class="col-sm-10 ">';
            echo summarize_data($data_date,$data_allocationwork,$_POST['detail_operator']);
            echo'</div></div>';
            echo'<div class="row "><div class="col-sm-2 ">Shift: </div><div class="col-sm-10 ">';
            echo summarize_data($data_date,$data_allocationshift,$_POST['detail_operator']);
            echo'</div></div>';
            echo'<div class="row "><div class="col-sm-2">Action: </div><div class="col-sm-10 ">';
            echo summarize_data($data_date,$data_allocation,$_POST['detail_operator'],$data_notes);
            echo'</div></div>';
            echo'<div class="row h1operator"><i>Log</i></div>';
            echo'<div class=row " style="max-height:200px;overflow: hidden;overflow-y: scroll"> ';
                show_all_log($db,$_POST['detail_operator']);
            echo'</div>';
        echo'</div>'; 
        echo'<div class="col-sm-6 headersoperator main-box"> ';
            show_all_operator($operator_list);
        echo'</div>'; 
    echo'</div>';
}

function show_all_operator($operator_list){
    $table=get_table_allocation();
    if($table=='allocation'){
        $table='allocationshift';
    }
    foreach($operator_list as $operator){
                
       //show($operator);
            echo'<div class="col-sm-2 detailsoperator main-box ';
            echo $operator[$table.'_code'];
            echo'" style="text-align: center;" onClick="document.forms[\''.$operator['operator_fullname'].'\'].submit();">';

            echo'<form method="POST" id="'.$operator['operator_fullname'].'"><input class="form-control" type="hidden" name="detail_operator" value="'.$operator['operator_fullname'].'"></form>';



            
            if(substr($_SESSION['temp']['roster_sort'],0,10)=='operator_l'){
                echo $operator['operator_lastname'].'<br>'.$operator['operator_name'];
            }else{
                echo $operator['operator_name'].'<br>'.$operator['operator_lastname'];
            }
            
            echo'</div>';
    }
}

function summarize_data($data_date,$data_allocation,$operator,$data_notes=''){
    $i=0;
    $oldallocation='';
    $status='end';
    $result='';
    $date_start='';
    $format_date='jS M';

    $notes='';
    
    foreach($data_date as $eachday){
        if(!empty($data_notes[date('Y-m-d',strtotime($eachday))][$operator])){
            $notes=$notes.'   <div class="popover__wrapper2">
            <span class="glyphicon glyphicon-info-sign popover__title2"></span> 
            <div class="popover__content2">'.date($format_date,strtotime($eachday)).': '.$data_notes[date('Y-m-d',strtotime($eachday))][$operator].'</div>
            </div>      ';
        }
        $allocation=$data_allocation[date('Y-m-d',strtotime($eachday))][$operator];
        if(empty($allocation)){$allocation='blank';}
        if($oldallocation==$allocation){

        }else{
            
            if ($status=='end'){
                if($allocation<>'blank'){
                    $date_start=date($format_date,strtotime($eachday));
                    $status='start';
                    $oldnotes='';
                }
                
            }else{
                if($oldallocation<>'blank'){
                    $result=$result.'<div class="col-sm-3 main-box"><div class="col-sm-12 detailsoperator '.$oldallocation.'">'.$oldallocation.''.$oldnotes.'</div>';
                    if($date_start==date($format_date,strtotime($eachday.' -1day'))){
                        $result=$result.'<div class="col-sm-12 detailsoperator">'.$date_start.'</div>';
                        $oldnotes='';
                    }else{
                        $result=$result.'<div class="col-sm-5 detailsoperator"><small>'.$date_start.'</small></div>';
                        $result=$result.'<div class="col-sm-2 detailsoperator"><small>to</small></div>';
                        $result=$result.'<div class="col-sm-5 detailsoperator"><small>'.date($format_date,strtotime($eachday.' -1day')).'</small></div>';
                        $oldnotes='';
                    }
                    $result=$result.'</div>';
                }

                if($allocation<>'blank'){
                    $date_start=date($format_date,strtotime($eachday));
                   
                }
                $status='start';
            }
        }
        $oldallocation=$allocation;

        $i++;
        $oldnotes=$oldnotes.$notes;
        $notes='';
    }
    if($allocation<>'blank'){
        $result=$result.'<div class="col-sm-3 main-box"><div class="col-sm-12 detailsoperator '.$allocation.'">'.$allocation.''.$notes.'</div>';
        if($date_start==date($format_date,strtotime($eachday.' -1day'))){
            $result=$result.'<div class="col-sm-12 detailsoperator">'.$date_start.'</div>';
        }else{
            $result=$result.'<div class="col-sm-5 detailsoperator"><small>'.$date_start.'</small></div>';
            $result=$result.'<div class="col-sm-2 detailsoperator"><small>to</small></div>';
            $result=$result.'<div class="col-sm-5 detailsoperator"><small>'.date($format_date,strtotime($eachday.' -1day')).'</small></div>';
        }
        $result=$result.'</div>';
    }




    return $result;
}

function show_all_log($db,$operator_full_name){
    $query='SELECT *
    FROM [barcode].[dbo].[allocationlog]
    WHERE allocationlog_operatorid=\''.$operator_full_name.'\'
    order by allocationlog_timetag DESC
    
    ';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  //show($query);
  $row=$sql->fetchall();
  //show($row);
  foreach($row as $entry){
      
    echo'<div class="row list-log">
        <div class="col-sm-3 ">';
             
            echo date('jS M',strtotime($entry['allocationlog_date']))	;
        echo'</div>
        <div class="col-sm-9 ">';
            echo $entry['allocationlog_entry'];
        echo'</div>';
    echo'</div>';
  }
  
    
    
}

function show_alert($message,$type='warning'){
   echo' <div class="alert alert-info alert.'.$type.' " role="alert">'.$message.'
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>';
}



?>