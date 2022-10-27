<?php
load_role($db,$_SESSION['temp']['id']);
//show(allow_modify($db,'prodplan_risk'));

function manage_POST_prodplan($db){
    if(empty($_SESSION['temp']['prodplan']['sort'])){
        $_SESSION['temp']['prodplan']['sort']='Code ASC';
    }
    if(!empty($_POST['sort'])){
        if( $_SESSION['temp']['prodplan']['sort']==$_POST['sort'].' DESC'){
            $_SESSION['temp']['prodplan']['sort']=$_POST['sort'].' ASC';
        }else{
            $_SESSION['temp']['prodplan']['sort']=$_POST['sort'].' DESC';
        }    
       
    }
    
    if(!empty($_POST['show_completed'])){
        
        if($_POST['show_completed']=='no'){
            unset($_SESSION['temp']['prodplan']['show_completed']);
            unset($_SESSION['temp']['prodplan']['start_date_to_show']);
            unset($_POST['start_date_to_show']);
            //show($_SESSION['temp']['prodplan']['start_date_to_show']);
           
        }else{
            $_SESSION['temp']['prodplan']['show_completed']=$_POST['show_completed'];
           
        }
       
    }
    if(!empty($_POST['date_only'])){
        
        if($_POST['date_only']=='no'){
            unset($_SESSION['temp']['prodplan']['date_only']);
               
        }else{
            $_SESSION['temp']['prodplan']['date_only']=$_POST['date_only'];
           
        }
       
    }
    if(!empty( $_POST['WorkArea'])){
        if(( $_POST['WorkArea']=='All')){
            unset($_POST['WorkArea']);
            unset($_SESSION['temp']['prodplan']['WorkArea']);
        }else{
            $_SESSION['temp']['prodplan']['WorkArea']=$_POST['WorkArea'];
        }
       
        
    }
    if(!empty( $_POST['Location'])){
        if(( $_POST['Location']=='All')){
            unset($_POST['Location']);
            unset($_SESSION['temp']['prodplan']['Location']);
        }else{
            $_SESSION['temp']['prodplan']['Location']=$_POST['Location'];
        }
       
        
    }

    if(!empty( $_POST['date_to_show'])){
       $_SESSION['temp']['prodplan']['date_to_show']=$_POST['date_to_show'];
    }
    if(!empty( $_POST['start_date_to_show'])){
        $_SESSION['temp']['prodplan']['start_date_to_show']=$_POST['start_date_to_show'];
     }
     //show($_SESSION['temp']['prodplan']['start_date_to_show']);
    
    
    if(!empty( $_POST['end_date_to_show'])){
        $_SESSION['temp']['prodplan']['end_date_to_show']=$_POST['end_date_to_show'];
     }
     
    
    if(empty( $_SESSION['temp']['prodplan']['date_to_show'])){
        $_POST['date_to_show']=date('Y-m-d',strtotime(date('Y-m-d',time()) . ' -60 days'));
        $_SESSION['temp']['prodplan']['date_to_show']=$_POST['date_to_show'];
        
    }
    if(empty( $_SESSION['temp']['prodplan']['end_date_to_show'])){
        $_POST['end_date_to_show']=date('Y-m-d',strtotime(date('Y-m-d',time()). ' next saturday -1 days'));
        $_SESSION['temp']['prodplan']['end_date_to_show']=$_POST['end_date_to_show'];
    }
    if(empty( $_SESSION['temp']['prodplan']['start_date_to_show'])){
        $_POST['start_date_to_show']=date('Y-m-d',strtotime($_SESSION['temp']['prodplan']['end_date_to_show']. ' last sunday +1 days'));
        $_SESSION['temp']['prodplan']['start_date_to_show']=$_POST['start_date_to_show'];
    }
    $_POST['show_completed']=$_SESSION['temp']['prodplan']['show_completed'];
    $_POST['date_only']=$_SESSION['temp']['prodplan']['date_only'];
    $_POST['WorkArea']=$_SESSION['temp']['prodplan']['WorkArea'];
    $_POST['date_to_show']=$_SESSION['temp']['prodplan']['date_to_show'];
    $_POST['end_date_to_show']=$_SESSION['temp']['prodplan']['end_date_to_show'];
    $_POST['start_date_to_show']=$_SESSION['temp']['prodplan']['start_date_to_show'];
    //show($_SESSION['temp']['prodplan']['start_date_to_show']);
    if(!empty($_POST['MO']) and empty($_POST['type'])and empty($_POST['Notes_id'])){
        load_all_MIS_from_MO($db,$_POST['MO']);
    }
    if(!empty($_POST['MO']) and !empty($_POST['Notes'])){
        save_notes($db,$_POST['MO'],$_POST['Notes']);
        update_notes($db,$_POST['MO']);
        //show('test');
    }
    if(!empty($_POST['MO']) and ($_POST['type']=='changestatuts')){
        show_window_change_statuts($db,$_POST['MO']);
        //show('test');
    }
    if(!empty($_POST['MO']) and ($_POST['type']=='savestatus')){
       save_status($db,$_POST['MO'],$_POST['status']);
       showallstats($db); 
    }
    if(!empty($_POST['MO']) and ($_POST['type']=='changerisk')){
        
        show_window_change_risk($db,$_POST['MO']);
    }
    if(!empty($_POST['MO']) and ($_POST['type']=='saverisk')){
        save_risk($db,$_POST['MO'],$_POST['status']);
        
    }

    if(!empty($_POST['MO']) and ($_POST['type']=='Follow')){
        //show($_POST);
        save_follow($db,$_POST['MO']);
    
    }
    if(!empty($_POST['MO']) and ($_POST['type']=='addfollower')){
        show_window_add_follower($db,$_POST['MO']);
        //show('test');
    }
    if(!empty($_POST['MO']) and ($_POST['type']=='add_one_follower')){
        save_one_follower($db,$_POST['MO'],$_POST['employee']);
        //show('test');
    }

    if(!empty($_POST['MO']) and ($_POST['type']=='Preferences')){
        save_preference($db,$_POST['MO']);
        
    }

    if(!empty($_POST['Notes_id']) ){
        remove_notes($db,$_POST['Notes_id']);
        update_notes($db,$_POST['MO']);
    }

    if(!empty($_POST['MIS'])){
        show_window_summary_scan($db,$_POST['MIS']);
    }

    //show($_POST);
    
}


function navbar_prodplan($db){

	echo'<div class="row">';
        echo'<form method="POST">';
        if(empty($_POST['summary-view'])){
            echo'<div class="col-sm-1 ">';
                $filter='[IsLineOUtstanding]=1
                and manufactureON>=\''.date('Y-m-d',strtotime($_POST['date_to_show'])).'\'
                and manufactureBefore<=\''.date('Y-m-d',strtotime($_POST['end_date_to_show'])).'\'';
                $query='SELECT WorkArea
                FROM MO_List
                where  '.$filter.'
                group by WorkArea
                order by WorkArea ASC';
                $sql = $db->prepare($query); 
                $sql->execute();
                $WorkAreaList=$sql->fetchall();
                echo'<select class="form-control" name="WorkArea" onchange="submit();">
                <option >All</option>
                <option value="All Exploded" ';
                if('All Exploded'==$_POST['WorkArea']){echo' selected ';}
                echo'>All Exploded</option>
                <option disabled>________</option>
                <option value="Export" ';
                if('Export'==$_POST['WorkArea']){echo' selected ';}
                echo'>Export</option>
                <option disabled>________</option>
                <option value="Manufacturing" ';
                if('Manufacturing'==$_POST['WorkArea']){echo' selected ';}
                echo'>Manufacturing</option>';
                
                foreach($WorkAreaList as $workarea){
                    echo'<option value="'.$workarea[0].'" ';
                    if($workarea[0]==$_POST['WorkArea']){echo' selected ';}
                    echo'>'.$workarea[0].'</option>';
                }
                
            echo'</select>';
            echo'</div>';
            
                
            echo'<div class="col-sm-1 ">';
                //echo'<input class="form-control" type="date" name="date_to_show" onchange="submit();" value="'.$_POST['date_to_show'].'">';
                if(!empty($_SESSION['temp']['prodplan']['show_completed'])){
                    if(empty($_SESSION['temp']['prodplan']['date_only'])){
                        echo'<button type="submit" name="date_only" value="show_all"  class="btn btn-default" >
                        Date Only
                        </button>';
                    }
                    else{
                        echo'<button type="submit" name="date_only" value="no"  class="btn btn-default" ><b>Date Only</b>
                            <span class="glyphicon glyphicon-remove" > </span></button>';
                    }
                }
            
            echo'</div>';
                
            
            
            
            
            echo'<div class="col-sm-2 ">';
            if(!empty($_SESSION['temp']['prodplan']['show_completed'])){
                echo'<input class="form-control" type="date" name="start_date_to_show" onChange="submit();" value="'.$_POST['start_date_to_show'].'">'; 
            }
                echo'<input class="form-control" type="date" name="end_date_to_show" onChange="submit();" value="'.$_POST['end_date_to_show'].'">';   
                //echo'<input class="form-control" type="date" name="start_date_to_show" onChange="submit();" value="'.$_POST['end_date_to_show'].'">';        
            echo'</div>';

            echo'<div class="col-sm-2 ">';
            
            if(empty($_SESSION['temp']['prodplan']['show_completed'])){
                echo'<button type="submit" name="show_completed" value="show_all"  class="btn btn-default" >
                Show MO Completed
                </button>';
            }
            else{
                echo'<button type="submit" name="show_completed" value="no"  class="btn btn-default" ><b>Show MO Completed</b>
                    <span class="glyphicon glyphicon-remove" > </span></button>';
            }
                    
            echo'</div>';
            echo'<div class="col-sm-2 " id="allstats" >';
            
                
            echo'</div>';
            echo'<div class="col-sm-1 ">';
                $filter='[IsLineOUtstanding]=1
                and manufactureON>=\''.date('Y-m-d',strtotime($_POST['date_to_show'])).'\'
                and manufactureBefore<=\''.date('Y-m-d',strtotime($_POST['end_date_to_show'])).'\'';
                $query='SELECT Location
                FROM MO_List
                where  '.$filter.'
                group by Location
                order by Location ASC';
                $sql = $db->prepare($query); 
                $sql->execute();
                $WorkAreaList=$sql->fetchall();
                echo'<select class="form-control" name="Location" onchange="submit();">
                <option >All</option>
                <option disabled>________</option>';
                foreach($WorkAreaList as $workarea){
                    echo'<option value="'.$workarea[0].'" ';
                    if($workarea[0]==$_POST['Location']){echo' selected ';}
                    echo'>'.$workarea[0].'</option>';
                }
                
            echo'</select>';
            echo'</div>';
            
            echo'<div class="col-sm-1 " >';
            
                
            echo'</div>';
        }else{
            echo'<div class="col-sm-10 " >';
            
                
            echo'</div>';
        }
        echo'<div class="col-sm-1 ">';
        if(empty($_POST['summary-view'])){
            echo'<button type="submit" name="summary-view" value="view"  class="btn btn-default" >Summary View</button>';
        }else{
            echo'<button type="submit"   class="btn btn-default" >Back</button>';
        }
        echo'</div>';
        echo'<div class="col-sm-1 ">';
               
        if(empty($_POST['summary-view'])){   
            echo'<button type="submit" name="refres" value="no"  class="btn btn-default" >
                <span class="glyphicon glyphicon-refresh" > </span></button>';
        }
                
            
        echo'</div>';
        echo'</form >';   
        showallstats($db); 	
		
		echo'</div>';	
}

function bold_if_sort($header,$sort){
    
    if( $_SESSION['temp']['prodplan']['sort']==$sort.' DESC' or $_SESSION['temp']['prodplan']['sort']==$sort.' ASC' ){
        return ''.$header.' <span class="glyphicon glyphicon-sort" > </span>';
    }else{
        return $header;
    }

}

function show_header_order(){
    echo'<div class="row header-prod-plan">';
        echo'<div class="col-sm-4">';
        
            echo'<div class="col-sm-1" onclick="sortby(\'prodplanrisk_name\');">'.bold_if_sort('Risk','prodplanrisk_name').'</div>';
            
            
            echo'<div class="col-sm-3" onclick="sortby(\'MO\');">'.bold_if_sort('Manufacture<br>Order','MO').'</div>';
        
        
            echo'<div class="col-sm-6" onclick="sortby(\'Code\');">'.bold_if_sort('Product Code','Code').'</div>';
            
            echo'<div class="col-sm-2 thedate" onclick="sortby(\'ManufactureBefore\');">';
            // echo'<div class="col-sm-12"</div>';
                
                echo''.bold_if_sort('Manufacture<br>Before','ManufactureBefore').'';
            echo'</div>';
        echo'</div>';
        echo'<div class="col-sm-1 ">';
            echo'<div class="col-sm-12">Qty</div>';
            //echo'<div class="col-sm-3 qty-header" onclick="sortby(\'BaseQuantityOrdered\');">'.bold_if_sort('Ordered','BaseQuantityOrdered').'</div>';
            //echo'<div class="col-sm-3 qty-header" onclick="sortby(\'QTY_MADE\');">'.bold_if_sort('Made','QTY_MADE').'</div>';
            echo'<div class="col-sm-6 qty-header" onclick="sortby(\'BaseQuantityOrdered\');">'.bold_if_sort('Ordered','BaseQuantityOrdered').'</div>';
            echo'<div class="col-sm-6 qty-header" onclick="sortby(\'(BaseQuantityOrdered-COALESCE(QTY_MADE, 0)-COALESCE(QTY_INPROGRESS, 0))\');">'.bold_if_sort('Remaining','(BaseQuantityOrdered-COALESCE(QTY_MADE, 0)-COALESCE(QTY_INPROGRESS, 0))').'</div>';
        echo'</div>';
        echo'<div class="col-sm-1">';
            echo'<div class="col-sm-12">Hours</div>';
            echo'<div class="col-sm-6 qty-header" onclick="sortby(\'HOURS_Made\');">'.bold_if_sort('Made','HOURS_Made').'</div>';
            //echo'<div class="col-sm-4 qty-header" onclick="sortby(\'total_hours\');">'.bold_if_sort('Scanned','total_hours').'</div>';
            echo'<div class="col-sm-6 qty-header" onclick="sortby(\'HOURS_Remaining\');">'.bold_if_sort('Remaining','HOURS_Remaining').'</div>';
        echo'</div>';
        echo'<div class="col-sm-4">';
            echo'<div class="col-sm-1" onclick="sortby(\'Preferences\');">'.bold_if_sort('Pref.','Preferences').'</div>';
            echo'<div class="col-sm-7" >Notes</div>';
            echo'<div class="col-sm-4" onclick="sortby(\'prodplanstatus_name\');">'.bold_if_sort('Status','prodplanstatus_name').'</div>';
        echo'</div>';
        echo'<div class="col-sm-2">';
            echo'<div class="col-sm-6" onclick="sortby(\'(COALESCE(QTY_MADE,0))/(BaseQuantityOrdered)\');">'.bold_if_sort('Progress','(COALESCE(QTY_MADE,0))/(BaseQuantityOrdered)').'</div>';
            echo'<div class="col-sm-6" >Action</div>';
        echo'</div>';
        
        echo'<form id="form-sort" method="POST">';
        echo'<input type="hidden" id="sort" name="sort" value="">';
        echo'</form>';
    echo'</div>';
}

function show_order($db,$orderlist){
    $i=0;
    create_css ($db,'allocationwork');
    echo'<style>.Cutting{
            background:#9bb691;
        }
        </style>';
    
    if(empty($_POST['WorkArea'])){
        
            show_header_order();
       
    }

    echo '<script>
    function sortby(thevalue){
        document.getElementById("sort").value = thevalue;
        document.getElementById("form-sort").submit();
        
    }
    </script>';
    $i=0;
    $oldWorkArea='';
    foreach($orderlist as $order){
        //if($i==0){show($order);}
        
        if(!empty($_POST['WorkArea'])){
            if($oldWorkArea<>$order['WorkArea']){
                echo'<div class="row header-WorkArea '.$order['WorkArea'].'">';
                    echo $order['WorkArea'];
                echo'</div>';
                show_header_order();
            }
        }
        $oldWorkArea=$order['WorkArea'];
        echo'<div id="row-'.$order['ManufactureOrderNumber'].'" class="row row-prod-plan';
        if($order['IsLineOutstanding']==0 or($order['QTY_MADE']>=$order['QuantityOrdered'])){
            echo' row-completed ';
        }elseif(!empty($order['prodplanstatus_name'])){
            echo ' Problem ';
        }elseif(strtotime($order['ManufactureBefore'].' +1days')<=time()){
            echo ' Late ';
        }elseif(!empty($order['prodplanstatus_name'])){
            echo ' Problem ';
        }else{
            echo' row_normal ';
        }
        if (!empty($order['prodplanrisk_name'])){
            echo' '.$order['prodplanrisk_name'].' ';
        }
        
        echo'" >';
       //
            echo'<div class="col-sm-4 " >';
                echo'<div class="col-sm-1"';
                if(empty(allow_modify($db,'prodplan_risk'))){echo'onClick="ChangeRisk'.$order['ManufactureOrderNumber'].'();" ';}
                echo'id="CustomRisk'.$order['ManufactureOrderNumber'].'">';
                
                if (empty($order['prodplanrisk_name'])){
                    echo '-';
                }else{
                    echo $order['prodplanrisk_name'];
                }
                echo'</div>';
                echo'<div class="col-sm-3" ';
                if($order['QTY_MADE']+$order['QTY_INPROGRESS']>0){echo'onClick="Details'.$order['ManufactureOrderNumber'].'();"';}
                echo'>';
                echo $order['ManufactureOrderNumber'];
                echo'</div>';
            
                echo'<div class="col-sm-6 " ';//>';
                   // echo'<div class="col-sm-12  date-row"';
                    if($order['QTY_MADE']+$order['QTY_INPROGRESS']>0){echo'onClick="Details'.$order['ManufactureOrderNumber'].'();"';}
                    echo'>';
                        echo $order['Code'];
                    
                echo'</div>';
                echo'<div class="col-sm-2 date-row thedate"';
                if($order['QTY_MADE']+$order['QTY_INPROGRESS']>0){echo'onClick="Details'.$order['ManufactureOrderNumber'].'();"';}
                echo'>';
                    // echo'<div class="col-sm-6 date-row">';
                    // echo date('jS M',strtotime($order['ManufactureOn']));
                    // echo'</div>';
                    //echo'<div class="col-sm-12 date-row">';
                    echo date('jS M',strtotime($order['ManufactureBefore']));
                    //echo'</div>';
                    //echo'<div class="col-sm-2">';
                    //if(!empty($order['ManufactureCompletedOn'])){echo date('jS M',strtotime($order['ManufactureCompletedOn']));}
                    //echo'</div>';
                echo'</div>';
            echo'</div>';
           
            echo'<div class="col-sm-1   "';
                if($order['QTY_MADE']+$order['QTY_INPROGRESS']>0){echo'onClick="Details'.$order['ManufactureOrderNumber'].'();"';}
                echo'>';
                echo' <div class="popover__wrapper3">';
                // echo'<div class="col-sm-3">';
                // echo number_format($order['BaseQuantityOrdered']);
                // echo'</div>';
                
                // echo'<div class="col-sm-3">';
                // echo number_format($order['QTY_MADE']);
                // echo'</div>';
                echo'<div class="col-sm-6" >';
                    if(!empty($order['BaseQuantityOrdered'])){
                        echo number_format($order['BaseQuantityOrdered']);
                    }else{
                        echo '<br>';
                    }
                echo'</div>';
                
                echo'<div class="col-sm-6" ">';
                if(!empty($order['BaseQuantityOrdered']-$order['QTY_MADE']-$order['QTY_INPROGRESS'])){
                    echo number_format($order['BaseQuantityOrdered']-$order['QTY_MADE']-$order['QTY_INPROGRESS']);
                }else{
                    echo '<br>';
                }
            
               
                echo'</div>';
                    echo'<div class="popover__content2 details-qty">';
                       //echo'<div class="row">'.$order['ManufactureOrderNumber'].' - '.$order['Code'].'</div>';
                        echo'<div class="row " ><div class="col-sm-8" >QTY Ordered:</div><div class="col-sm-4" >'.number_format($order['BaseQuantityOrdered']).'</div></div>';
                        echo'<div class="row"><div class="col-sm-8" >QTY Made:</div><div class="col-sm-4" >'.number_format($order['QTY_MADE']).'</div></div>';
                        echo'<div class="row"><div class="col-sm-8" >QTY In Progress:</div><div class="col-sm-4" >'.number_format($order['QTY_INPROGRESS']).'</div></div>';
                        echo'<div class="row topborder"><div class="col-sm-8" >QTY Remaining:</div><div class="col-sm-4" >'.number_format($order['BaseQuantityOrdered']-$order['QTY_MADE']-$order['QTY_INPROGRESS']).'</div></div>';
                    echo'</div>';
                echo'</div> ';
            echo'</div>';
            echo' <div class="popover__wrapper3">';
                echo'<div class="col-sm-1 ">';
                    echo'<div class="col-sm-6"';
                    if($order['QTY_MADE']+$order['QTY_INPROGRESS']>0){echo'onClick="Details'.$order['ManufactureOrderNumber'].'();"';}
                    echo'>';
                        if($order['HOURS_Made']>0){echo round($order['HOURS_Made'],1).'';}
                        
                    echo'</div>';
                    // echo'<div class="col-sm-4"';
                    // if($order['QTY_MADE']+$order['QTY_INPROGRESS']>0){echo'onClick="Details'.$order['ManufactureOrderNumber'].'();"';}
                    // echo'>';
                    //     if($order['total_hours']>0){echo round($order['total_hours'],1).'';}
                        
                    // echo'</div>';
                    echo'<div class="col-sm-6"';
                    if($order['QTY_MADE']+$order['QTY_INPROGRESS']>0){echo'onClick="Details'.$order['ManufactureOrderNumber'].'();"';}
                    echo'>';
                        if($order['HOURS_Remaining']>0){echo round($order['HOURS_Remaining'],1).'';}else{echo'-';}
                        
                    echo'</div>';
                    echo'<div class="popover__content2 details-hours">';
                    //echo'<div class="row">'.$order['ManufactureOrderNumber'].' - '.$order['Code'].'</div>';
                    echo'<div class="row " ><div class="col-sm-10" >Hours Made:</div><div class="col-sm-2" >'.round($order['HOURS_Made'],1).' h</div></div>';
                    echo'<div class="row"><div class="col-sm-10" >Hours In Progress:</div><div class="col-sm-2" >'.round($order['HOURS_Current'],1).' h</div></div>';
                    echo'<div class="row"><div class="col-sm-10" >Hours Remaining:</div><div class="col-sm-2" >'.round($order['HOURS_Remaining'],1).' h</div></div>';
                    echo'<div class="row topborder"><div class="col-sm-10" >Hours Scanned:</div><div class="col-sm-2" >'.round($order['total_hours'],1).' h</div></div>';
                   if($order['HOURS_Made']>0){
                    echo'<div class="row"><div class="col-sm-8" ></div><div class="col-sm-4" >'.(round($order['total_hours']/$order['HOURS_Made'],2)*100).'%</div></div>';
                   }
                    
                echo'</div>';
                echo'</div>';
                
            echo'</div>'; 
            echo'<div class="col-sm-4">';
               
                echo'<div class="col-sm-1" id="Preferences-'.$order['ManufactureOrderNumber'].'" ';
                if(empty(allow_modify($db,'prodplan_pref'))){
                    echo'onClick="thePref=window.prompt(\'Add a Preferences\',);if(thePref){AddPref'.$order['ManufactureOrderNumber'].'();}" ';
                    echo'onContextmenu="thePref=\'Remove\';if(thePref){AddPref'.$order['ManufactureOrderNumber'].'();return false;}" ';
                }
                echo' >';
                if(empty($order['prodplanpref_name'])){
                    echo '<br>';
                }else{
                    echo $order['prodplanpref_name'];
                }
                
                echo'</div>';
            
                
                echo'<div class="col-sm-7 Notes-'.$order['ManufactureOrderNumber'].'"  >';//Count_Notes
                    echo'<div class="" id="Notes-'.$order['ManufactureOrderNumber'].'" ';
                    if(empty(allow_modify($db,'prodplan_notes'))){echo' onClick="theNotes=window.prompt(\'Add a Notes\',);if(theNotes){AddNotes'.$order['ManufactureOrderNumber'].'();}"';}
                    echo'>';
                    //if(!empty($order['Count_Notes'])){
                        
                        show_notes($db,$order['ManufactureOrderNumber']);
                   // }else{
                        //echo'<br>';
                   // }
                    echo'</div>';
                echo'</div>';
                echo'<div class="col-sm-4 row-statuts " >';
                    echo'<div class="col-sm-4">';
                        if($order['IsLineOutstanding']==0 or($order['QTY_MADE']>=$order['QuantityOrdered'])){
                            echo'Completed';
                        }else{
                            if(strtotime($order['ManufactureBefore'].' +1days')<=time()){echo 'Late';}else{echo'-';}
                        }
                    echo'</div>';//allow_modify($db,'roster_admin')
                    
                    echo'<div class="col-sm-8" ';
                    
                   // if(empty(allow_modify($db,'prodplan_input'))){echo'onClick="ChangeStatuts'.$order['ManufactureOrderNumber'].'();" ';}
                    echo'id="CustomStatus'.$order['ManufactureOrderNumber'].'">';
                        if($order['IsLineOutstanding']==0 or($order['QTY_MADE']>=$order['QuantityOrdered'])){
                            echo'';
                        }else{
                            if(!empty($order['prodplanstatus_name'])){echo $order['prodplanstatus_name'];}else{echo'-';}
                        }
                    echo'</div>';
                echo'</div>';
                
            echo'</div>';
            echo'<div class="col-sm-2">';
                echo'<div class="col-sm-6 ">';
                    // echo'<div class="w3-container w3-light-blue w3-round-medium" style="width:'.(100*round($order['Progress'],2)).'%">';
                    // echo 100*round($order['Progress'],3).'%';
                    // echo'</div>';
                    $completed=0;
                    $made=100*round($order['Progress'],2);
                    $made_show=min(100,$made);
                    
                    $started=100*round($order['QTY_INPROGRESS']/$order['BaseQuantityOrdered'],2);
                    $started_show=min(100,$made+$started)-$made;
                    if($made_show+$started_show>=100){
                        $completed=1;
                    }
                    echo'<div class=" progress ">';
                        if($made>0){
                        echo'<div class="progress-bar progress-bar-striped made" role="progressbar" style="width: '.$made_show.'%" aria-valuenow="'.$made_show.'" aria-valuemin="0" aria-valuemax="100">'.$made.'%</div>';
                        }
                        if($started>0){
                            echo'<div class="progress-bar progress-bar-striped started" role="progressbar" style="width: '.$started_show.'%" aria-valuenow="'.$started_show.'" aria-valuemin="0" aria-valuemax="100">'.$started.'%</div>';
                        }
                        //echo'<div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>';
                    echo'</div>';
                    if($completed==1){
                        //echo'<div class="col-sm-2 progress-completed ">';
                        //echo'<img class="attachment" src="img/checked.png"  height="15">';
                       // echo'</div>';
                    }
                echo'</div>';
                echo'<div class="col-sm-6 ">';
                   
                    echo'<div id="button-'.$order['ManufactureOrderNumber'].'" class="col-sm-3 " ';
                    if(empty(allow_modify($db,'prodplan_statuts'))){
                        echo'onClick="ChangeStatuts'.$order['ManufactureOrderNumber'].'();" ';
                    }
                    echo'>';
                    if(empty(allow_modify($db,'prodplan_statuts'))){
                        if(!empty($order['prodplanstatus_name'])){
                            echo '<span class="glyphicon glyphicon-play"></span>';
                        }else{
                            echo '<span class="glyphicon glyphicon-pause"></span>';
                        }
                    }  
                    echo'</div>';
                    echo'<div class="col-sm-3 ">';
                        echo '';
                        $listcc=get_cc($db,$order['ManufactureOrderNumber'],'MO',$order['WorkArea']);
                        $subject='Update on '.$order['ManufactureOrderNumber'].' - '.number_format($order['BaseQuantityOrdered']).' x '.$order['Code'].' due on '.date('jS M',strtotime($order['ManufactureBefore']));
                        $body='%0D %0D%0D  %0D%0D %0DCurrent Situation :%0D';
                        $body=$body.'Quantity Ordered: '.number_format($order['BaseQuantityOrdered']).' %0D';
                        $body=$body.'Quantity Made: '.number_format($order['QTY_MADE']).' %0D';
                        $body=$body.'Quantity In Progress: '.number_format($order['QTY_INPROGRESS']).' %0D';
                        $body=$body.'Quantity Remaining: '.number_format($order['BaseQuantityOrdered']-$order['QTY_MADE']-$order['QTY_INPROGRESS']).' %0D';
                        $body=$body.'Hours Remaining: '.round($order['HOURS_Remaining'],1).'h %0D';
                        $body=$body.show_notes_sendemail($db,$order['ManufactureOrderNumber']);
                        $cc=' ';
                        foreach($listcc as $cc1){
                            $cc=$cc.';'.$cc1['employee_email'];
                        }
                        
                        echo'<a href="mailto:?subject='.$subject.'&body='.$body.'&cc='.$cc.'">';
                            echo'<span class="glyphicon glyphicon-send" ></span>';
                        echo'</a>';
                    echo'</div>';
                    echo'<div class="col-sm-3 " id="check'.$order['ManufactureOrderNumber'].'" onClick="Follow'.$order['ManufactureOrderNumber'].'();">';
                    if(!empty($_SESSION['temp']['id'])){
                        $follow=0;
                        foreach($listcc as $cc){
                            if($_SESSION['temp']['id_full_name']==$cc['prodplanmaillist_employee']){
                                $follow=1;
                            }
                            
                        }
                        if($follow==0){
                            echo '<span class="glyphicon glyphicon-unchecked"></span>';
                        }else{
                            echo '<span class="glyphicon glyphicon-check"></span>';
                        }
                    }   
                    echo'</div>';
                    echo'<div class="col-sm-3 ">';
                        echo' <div class="popover__wrapper3">';
                            echo '<span class="glyphicon glyphicon-list-alt"></span>';
                            echo'<div class="popover__content2 details-qty " id="followlist'.$order['ManufactureOrderNumber'].'" style="left: -100px;width:200px;">';
                            echo'<div class="row " style="font-weight:bold;" >Followers:</div>';
                            
                            
                            foreach($listcc as $cc){
                                echo'<div class="row " >'. $cc['employee_fullname'].'</div>';
                            }
                            if(!empty($_SESSION['temp']['id'])){
                                echo'<div class="row topborder" onClick="AddFollower'.$order['ManufactureOrderNumber'].'();">Add/Remove Follower</div>';
                            }
                            echo'</div>';
                        echo'</div>';
                    echo'</div>';
                echo'</div>';
                 
            echo'</div>';
        echo'</div>';
            
            
        
        
        echo'<div ></div>';
        echo'<div class="row Details'.$order['ManufactureOrderNumber'].'" id="theDetails'.$order['ManufactureOrderNumber'].'"></div>';
        
        ajax_button('AddPref'.$order['ManufactureOrderNumber'],[['type',"'Preferences'"],['Preferences',"thePref"],['MO',"'".$order['ManufactureOrderNumber']."'"]],'prodplan_ajax.php','postinfo');
        ajax_button('Follow'.$order['ManufactureOrderNumber'],[['type',"'Follow'"],['MO',"'".$order['ManufactureOrderNumber']."'"]],'prodplan_ajax.php','postinfo');
        ajax_button('ChangeRisk'.$order['ManufactureOrderNumber'],[['type',"'changerisk'"],['MO',"'".$order['ManufactureOrderNumber']."'"]],'prodplan_ajax.php','postinfo');
        ajax_button('Details'.$order['ManufactureOrderNumber'],[['MO',"'".$order['ManufactureOrderNumber']."'"]],'prodplan_ajax.php','Details'.$order['ManufactureOrderNumber']);
        //ajax_button('ChangeStatuts'.$order['ManufactureOrderNumber'],[['type',"'changestatuts'"],['MO',"'".$order['ManufactureOrderNumber']."'"]],'prodplan_ajax.php','postinfo');
        ajax_button('ChangeStatuts'.$order['ManufactureOrderNumber'],[['type',"'changestatuts'"],['MO',"'".$order['ManufactureOrderNumber']."'"]],'prodplan_ajax.php','postinfo');
        ajax_button('AddFollower'.$order['ManufactureOrderNumber'],[['type',"'addfollower'"],['MO',"'".$order['ManufactureOrderNumber']."'"]],'prodplan_ajax.php','postinfo');
        
    $i++;
    }
    
    echo'<script>

    </script>';
}


function show_detailsrow($db,$MISList){
    $i=0;
    echo'<div class="row detailsrow-container" role="alert">';
    foreach($MISList as $MIS){
            //if($i==0){show($MIS);}
            echo'<div  class="row detailsrow-prod-plan">';
                echo'<div class="col-sm-4">';
                    echo'<div class="col-sm-1">';
                        
                    echo'</div>';
                    echo'<div class="col-sm-3">';
                    echo $MIS['ManufactureIssueNumber'];
                    echo'</div>';
                
                    
                    echo'<div class="col-sm-6 date-row">';
                        echo $MIS['Code'];
                    echo'</div>';
                    
                    echo'<div class="col-sm-2 date-row">';
                        
                      //ManufactureCompletedOn
                      if(!empty($MIS['ManufactureCompletedOn'])){
                        echo date('jS M',strtotime($MIS['ManufactureCompletedOn']));
                      }else{
                        echo date('jS M',strtotime($MIS['ManufactureBefore']));
                      }
                           
                        
                    echo'</div>';
                echo'</div>';
                // echo'<div class="col-sm-1">';
                // if(!empty($MIS['ManufactureCompletedOn'])){echo date('jS M Y',strtotime($MIS['ManufactureCompletedOn']));}
                // echo'</div>';
                
                echo'<div class="col-sm-1">';
                    
                    // echo'<div class="col-sm-6">';
                    // if($MIS['IsPosted']==1){echo number_format($MIS['BaseQuantity']);}
                    
                    // echo'</div>';
                    echo'<div class="col-sm-6">';
                    echo number_format($MIS['BaseQuantity']);
                    echo'</div>';
                    echo'<div class="col-sm-6">';
                    if(empty($MIS['ManufactureCompletedOn'])){
                        //echo '<i>Current</i>';
                    }else{
                        echo '<i>done</i>';
                    }
                    echo'</div>';
                    
                   
                echo'</div>';
                
               
                echo'<div class="col-sm-1">';
                    echo'<div class="col-sm-6">';
                    if(!empty($MIS['ManufactureCompletedOn'])){echo round($MIS['HOURS_Made'],1).'';}
                    echo'</div>';
                    // echo'<div id="opener-'.$MIS['ManufactureIssueNumber'].'" class="col-sm-4" ';
                    // if($MIS['total_hours']>0){echo'onClick="Details'.$MIS['ManufactureIssueNumber'].'();"';}
                    // echo'>';
                    // if($MIS['total_hours']>0){echo round($MIS['total_hours'],1).'';}
                    
                    // echo'</div>';
                echo'</div>';
                echo'<div class="col-sm-1">';
                echo'</div>';
                echo'<div class="col-sm-6">';
                echo'</div>';
                
                //show_window_summary_scan($db,$MIS['ManufactureIssueNumber']);
                if($i==0){
                    echo'<button type="button" class="btnclose clodfgse" onclick=" clearBox(\'theDetails'.$_POST['MO'].'\').innerHTML = \'5\';" aria-label="Close">
                    &times;
                    </button>';
                }
                ajax_button('Details'.$MIS['ManufactureIssueNumber'],[['MIS',"'".$MIS['ManufactureIssueNumber']."'"]],'prodplan_ajax.php','postinfo');
            echo'</div>';

            
        $i++;
        }
     echo'<script>
     function clearBox(elementID)
     {
         document.getElementById(elementID).innerHTML = "";
     }</script>';  
    echo'</div>';
}

function load_all_MIS_from_MO($db,$MO){
    $query='SELECT *
    ,(COALESCE(BaseQuantity,0))*BOM_TIME/60 as HOURS_Made
    FROM MIS_List
    left join BOM_List on BOM_List.Productcode=[Code]
    left join MIS_Hours_Scanned on MIS_Hours_Scanned.[scan_jobnumber]=[ManufactureIssueNumber]
    where  ManufactureOrderNumber=\''.$MO.'\'
    
    order by IsPosted desc,ManufactureCompletedOn ASC,ManufactureOn ASC
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $MISList=$sql->fetchall();
   //show($query);
    show_detailsrow($db,$MISList);
}

function load_all_scan_from_MIS($db,$MIS){
    $query='SELECT TOP (1000) [scan_jobnumber]
        ,[total_hours]
        ,[operator_fullname]
        ,[scan_date]
    FROM [barcode].[dbo].[MIS_Operator_hours_scanned]
    where scan_jobnumber=\''.$MIS.'\' ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $scanList=$sql->fetchall();
   //show($query);
    return $scanList;
}


function show_window_summary_scan($db,$MIS){
    echo'<script>
    $( function() {
    $( "#dialog-'.$MIS.'" ).dialog({
        minWidth: 300,
    show: {
        effect: "blind",
        duration: 100
    },
    hide: {
        effect: "blind",
        duration: 100
    }
    });

    
    } );
    </script>';
    $scanList=load_all_scan_from_MIS($db,$MIS);

    echo'<div id="dialog-'.$MIS.'" title="All Scan for '.$MIS.'">
        <div class="row">';
            foreach($scanList as $scan){
                if($olddate<>$scan['scan_date']){echo' <div class="row">'.date('D jS M Y',strtotime($scan['scan_date'])).'</div>';}
            
                echo'<div class="row"><div class="col-sm-9">'.$scan['operator_fullname'].'</div>
                <div class="col-sm-3">'.round($scan['total_hours'],1).' h</div></div>';
                $olddate=$scan['scan_date'];
            
            }


        

        echo'</div>
    </div>';
}

function show_window_change_statuts($db,$MO){
    echo'<script>
    $(\'.ui-dialog\').remove();
    $( function() {
    $( "#dialog-'.$MO.'" ).dialog({
        minWidth: 300,
    show: {
        effect: "blind",
        duration: 50
    },
    hide: {
        effect: "blind",
        duration: 50
    }
    });

    
    } );
    </script>';
    //$scanList=load_all_scan_from_MIS($db,$MO);

    $query='SELECT prodplanstatus_name
    FROM prodplanstatus
    where prodplanstatus_REF=\''.$MO.'\' 
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $statuts=$sql->fetch();
    $statuts=$statuts[0];
    echo'<div id="dialog-'.$MO.'" title="Change Statuts for '.$MO.'">
        <div class="row">';
           if(!empty($statuts)){echo'<div class="row"><div class="btn btn-default form-control" onclick="thestatus=\'Remove\';SaveStatus();$(\'.ui-dialog\').remove();">Remove '.$statuts.'</div></div>';};
           
           echo'<div class="row"><div class="btn btn-default form-control" onclick="thestatus=\'On Hold\';SaveStatus();$(\'.ui-dialog\').remove();">On Hold</div></div>';
          // echo'<div class="row"><div class="btn btn-default form-control" onclick="thestatus=\'Incomplete\';SaveStatus();$(\'.ui-dialog\').remove();">Incomplete</div></div>';
           echo'<div class="row"><div class="btn btn-default form-control" onclick="thestatus=\'NotPlanned\';SaveStatus();$(\'.ui-dialog\').remove();">NotPlanned</div></div>';
           


        

        echo'</div>
    </div>';
    ajax_button('SaveStatus',[['type',"'savestatus'"],['status',"thestatus"],['MO',"'".$MO."'"]],'prodplan_ajax.php','postinfo');
}

function show_window_add_follower($db,$MO){
    echo'<script>
    $(\'.ui-dialog\').remove();
    $( function() {
    $( "#dialog-'.$MO.'" ).dialog({
        minWidth: 300,
    show: {
        effect: "blind",
        duration: 50
    },
    hide: {
        effect: "blind",
        duration: 50
    }
    });

    
    } );
    </script>';
    //$scanList=load_all_scan_from_MIS($db,$MO);

    $query='SELECT employee_fullname
    FROM employee
    Left join (
	SELECT prodplanmaillist_employee,prodplanmaillist_REF
    FROM prodplanmaillist
	Where prodplanmaillist_REF=\''.$MO.'\'
	)as temp
    on temp.prodplanmaillist_employee=employee_fullname
    Where prodplanmaillist_REF is null
    
     
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $employeelist=$sql->fetchall();
    $query='SELECT employee_fullname
    FROM employee
    Left join (
	SELECT prodplanmaillist_employee,prodplanmaillist_REF
    FROM prodplanmaillist
	Where prodplanmaillist_REF=\''.$MO.'\'
	)as temp
    on temp.prodplanmaillist_employee=employee_fullname
    Where prodplanmaillist_REF is not null
    
     
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $employeelist2=$sql->fetchall();
    
    echo'<div id="dialog-'.$MO.'" title="Add Followers for '.$MO.'">';
        
           
           echo'<select id="employee_to_add" class="form-control" name="employee_to_add" onchange="Addfollower();$(\'.ui-dialog\').remove();"">
                <option disabled selected>Employee to Add</option>
                <option disabled>________</option>';
                
                foreach($employeelist as $employee){
                    echo'<option value="'.$employee['employee_fullname'].'" >'.$employee['employee_fullname'].'</option>';
                }
                
            echo'</select>';
            if(!empty($employeelist2)){
                echo'<select id="employee_to_remove" class="form-control" name="employee_to_add" onchange="Removefollower();$(\'.ui-dialog\').remove();"">
                    <option disabled selected>Employee to Remove</option>
                    <option disabled>________</option>';
                    
                    foreach($employeelist2 as $employee){
                        echo'<option value="'.$employee['employee_fullname'].'" >'.$employee['employee_fullname'].'</option>';
                    }
                    
                echo'</select>';
            }
            

        

        echo'</div>
    </div>';
    ajax_button('Addfollower',[['type',"'add_one_follower'"],['employee',"document.getElementById('employee_to_add').value"],['MO',"'".$MO."'"]],'prodplan_ajax.php','postinfo');
    ajax_button('Removefollower',[['type',"'add_one_follower'"],['employee',"document.getElementById('employee_to_remove').value"],['MO',"'".$MO."'"]],'prodplan_ajax.php','postinfo');
}

function show_window_remove_follower($db,$MO){
    echo'<script>
    $(\'.ui-dialog\').remove();
    $( function() {
    $( "#dialog-'.$MO.'" ).dialog({
        minWidth: 300,
    show: {
        effect: "blind",
        duration: 50
    },
    hide: {
        effect: "blind",
        duration: 50
    }
    });

    
    } );
    </script>';
    //$scanList=load_all_scan_from_MIS($db,$MO);

    $query='SELECT employee_fullname
    FROM employee
    Left join (
	SELECT prodplanmaillist_employee,prodplanmaillist_REF
    FROM prodplanmaillist
	Where prodplanmaillist_REF=\''.$MO.'\'
	)as temp
    on temp.prodplanmaillist_employee=employee_fullname
    Where prodplanmaillist_REF is not null
    
     
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $employeelist=$sql->fetchall();
    
    echo'<div id="dialog-'.$MO.'" title="Add Followers for '.$MO.'">';
        
           
           echo'<select id="employee_to_add" class="form-control" name="employee_to_add" onchange="Addfollower();$(\'.ui-dialog\').remove();"">
           <option disabled selected>Employee to Add</option>
           <option disabled>________</option>';
          
           foreach($employeelist as $employee){
               echo'<option value="'.$employee['employee_fullname'].'" >'.$employee['employee_fullname'].'</option>';
           }
           
       echo'</select>';

        

        echo'</div>
    </div>';
    ajax_button('Addfollower',[['type',"'add_one_follower'"],['employee',"document.getElementById('employee_to_add').value"],['MO',"'".$MO."'"]],'prodplan_ajax.php','postinfo');
}

function show_window_change_risk($db,$MO){
    echo'<script>
    $(\'.ui-dialog\').remove();
    $( function() {
    $( "#riskdialog-'.$MO.'" ).dialog({
        minWidth: 300,
    show: {
        effect: "blind",
        duration: 20
    },
    hide: {
        effect: "blind",
        duration: 20
    }
    });

    
    } );
    </script>';
    //$scanList=load_all_scan_from_MIS($db,$MO);

    $query='SELECT prodplanrisk_name
    FROM prodplanrisk
    where prodplanrisk_REF=\''.$MO.'\' 
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $statuts=$sql->fetch();
    $statuts=$statuts[0];
    echo'<div id="riskdialog-'.$MO.'" title="Change Risk Statuts ">
        <div class="row">';
           if(!empty($statuts)){echo'<div class="row"><div class="btn btn-default form-control" onclick="thestatus=\'Remove\';SaveRisk();$(\'.ui-dialog\').remove();">Remove '.$statuts.'</div></div>';};
           
           //echo'<div class="row"><div class="btn btn-default form-control" onclick="thestatus=\'Normal\';SaveRisk();$(\'.ui-dialog\').remove();">Normal</div></div>';
           echo'<div class="row"><div class="btn btn-default form-control" onclick="thestatus=\'High\';SaveRisk();$(\'.ui-dialog\').remove();">High</div></div>';
           echo'<div class="row"><div class="btn btn-default form-control" onclick="thestatus=\'Highest\';SaveRisk();$(\'.ui-dialog\').remove();">Highest</div></div>';


        

        echo'</div>
    </div>';
    ajax_button('SaveRisk',[['type',"'saverisk'"],['status',"thestatus"],['MO',"'".$MO."'"]],'prodplan_ajax.php','postinfo');
}

function save_notes($db,$MO,$Notes){
    $query="INSERT INTO dbo.prodplannotes
    ( prodplannotes_REF,
    prodplannotes_employee,
    prodplannotes_notes,
    prodplannotes_timetag
    ) 
    VALUES (
    '".$MO."',
    '".$_SESSION['temp']['id']."',
    '".$Notes."',
    '".time()."')";	

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

}

function show_notes_sendemail($db,$REF){
    $query='SELECT *
    FROM prodplannotes
    where prodplannotes_REF=\''.$REF.'\' 
    ORDER BY prodplannotes_timetag DESC';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $notesList=$sql->fetchall();
   
    $caption='';
    
    if(!empty($notesList)){
        $caption=$caption.'%0DNotes :%0D';
    }
    foreach($notesList as $notes){
        
        $caption=$caption.date('jS M',$notes['prodplannotes_timetag']).' - ';
        $caption=$caption.''.$notes['prodplannotes_employee'].' : ';
        $caption=$caption.''.$notes['prodplannotes_notes'].'%0D';
        
    }

    return $caption;

}

function show_notes($db,$REF,$reftype='MO'){
    $query='SELECT *
    FROM prodplannotes
    where prodplannotes_REF=\''.$REF.'\' 
    ORDER BY prodplannotes_timetag DESC';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $notesList=$sql->fetchall();
    $i=0;
    
    
    if (empty($notesList)){echo'<br>';}
    foreach($notesList as $notes){
        // echo'<div ';
        // if(empty(allow_modify($db,'prodplan_notes'))){
        //     echo'oncontextmenu="if (!confirm(\'Are you sure to delete '.$notes['prodplannotes_notes'].'?\')) return false;;RemoveNotes'.$notes['prodplannotes_id'].'();return false;" ';
        // }
        // echo'>';
        if($i==0){
            $show1=$notes['prodplannotes_notes'];
            $caption=$caption.'<div class="row">';
                $caption=$caption.'<div class="col-sm-3">'.date('jS M',$notes['prodplannotes_timetag']).': </div>';
                $caption=$caption.'<div class="col-sm-3">'.$notes['prodplannotes_employee'].'</div>';
                $caption=$caption.'<div class="col-sm-5">'.$notes['prodplannotes_notes'].'</div>';
                $caption=$caption.'<div class="col-sm-1" ';
                if($notes['prodplannotes_employee']==$_SESSION['temp']['id']){
                        $caption=$caption.' oncontextmenu="if (!confirm(\'Are you sure to delete '.$notes['prodplannotes_notes'].'?\')) return false;;RemoveNotes'.$notes['prodplannotes_id'].'();return false;" ';
                    }
                $caption=$caption.'><span class="glyphicon glyphicon-trash "></span></div>';
            $caption=$caption.'</div>';
        }else{
           // if($i==0){$caption=' ';}else{$caption=$notes['prodplannotes_notes'];}

           $show=' <span class="glyphicon glyphicon-info-sign popover__title2"></span> ';

           $caption=$caption.'<div class="row">';
            $caption=$caption.'<div class="col-sm-3">'.date('jS M',$notes['prodplannotes_timetag']).': </div>';
            $caption=$caption.'<div class="col-sm-3">'.$notes['prodplannotes_employee'].'</div>';
            $caption=$caption.'<div class="col-sm-5">'.$notes['prodplannotes_notes'].'</div>';
            $caption=$caption.'<div class="col-sm-1" ';
            if($notes['prodplannotes_employee']==$_SESSION['temp']['id']){
                    $caption=$caption.' oncontextmenu="if (!confirm(\'Are you sure to delete '.$notes['prodplannotes_notes'].'?\')) return false;;RemoveNotes'.$notes['prodplannotes_id'].'();return false;" ';
                }
            $caption=$caption.'><span class="glyphicon glyphicon-trash "></span></div>';
           $caption=$caption.'</div>';
          
        }
        //echo'<div class="popover__wrapper2 ">';
        
        
       // echo'<div class="popover__content2 details-qty">'.date('jS M',$notes['prodplannotes_timetag']).': '.$notes['prodplannotes_employee'].' '.$caption.'</div>';
       // echo'</div>';
        $i++;
       // echo'</div>';
        ajax_button('RemoveNotes'.$notes['prodplannotes_id'],[[$reftype,"'".$notes['prodplannotes_REF']."'"],['Notes_id',"'".$notes['prodplannotes_id']."'"]],'prodplan_ajax.php','Notes-'.$notes['prodplannotes_REF']);
        
    }
    if(!empty($show)){
        echo'<div class="popover__wrapper2 ">';
        echo $show1.' '.$show;
            echo'<div class="popover__content2 details-notes" style="width:600px">';
            echo $caption;
            echo'</div>';
        echo'</div>';
    }else{
        echo'<div ';
        if($notes['prodplannotes_employee']==$_SESSION['temp']['id']){
            echo' oncontextmenu="if (!confirm(\'Are you sure to delete '.$notes['prodplannotes_notes'].'?\')) return false;;RemoveNotes'.$notes['prodplannotes_id'].'();return false;" ';
        }
        echo'>';
        echo $show1;
        echo'</div>';
    }

    
    //echo $show;

    ajax_button('AddNotes'.$REF,[['type',"'add_notes'"],['Notes',"theNotes"],['MO',"'".$REF."'"]],'prodplan_ajax.php','Notes-'.$REF);
        
    
    
    //return $scanList;
}

function get_cc($db,$REF,$reftype='MO',$workarea=''){
    $query='SELECT employee_fullname,employee_email,prodplanmaillist_employee,employee_code
    FROM prodplanmaillist
    left join employee on [prodplanmaillist_employee]=employee_fullname
    where prodplanmaillist_REF=\''.$REF.'\' 
    ORDER BY prodplanmaillist_employee DESC';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $listcc1=$sql->fetchall(); 

    if($workarea=='Assembly'){
        $query='SELECT employee_fullname,employee_email,employee_code
        FROM employee
        WHERE employee_fullname = \'Caroline Parker\'
        or employee_fullname = \'Lucas Johnston\'
        or employee_fullname = \'Jay Valencia\'
         
        ';
        //show($query);
        $sql = $db->prepare($query); 
        $sql->execute();
        $listcc2=$sql->fetchall(); 
    }else{
        $query='SELECT employee_fullname,employee_email,employee_code
        FROM employee
        WHERE employee_fullname = \'Lucas Johnston\'
        or employee_fullname = \'Jay Valencia\'
        or employee_fullname = \'Nigel Tweed\'
         
        ';
        //show($query);
        $sql = $db->prepare($query); 
        $sql->execute();
        $listcc2=$sql->fetchall(); 
    }


    $listcc = array_merge($listcc1, $listcc2);
    return $listcc;
    
    
    
    //return $scanList;
}

function remove_notes($db,$notes_id){
    $query="DELETE from dbo.prodplannotes

    WHERE prodplannotes_id='".$notes_id."' ";	

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
        

}

function update_notes($db,$MO){
    echo'<script>document.getElementById("Notes-'.$MO.'").innerHTML = "'.show_notes($db,$MO,'MO').'";</script>';
}

function save_status($db,$MO,$status){
    $query='DELETE
    FROM prodplanstatus
    where prodplanstatus_REF=\''.$MO.'\'';	

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    if($status<>'Remove'){
        $query="INSERT INTO dbo.prodplanstatus
        (prodplanstatus_REF,
        prodplanstatus_employee,
        prodplanstatus_name,
        prodplanstatus_timetag
        ) 
        VALUES (
        '".$MO."',
        '".$_SESSION['temp']['id']."',
        '".$status."',
        '".time()."')";	

        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();

       $newclass='Problem';
       $button='play';
    }else{
        $status='-';
        $newclass='';
        $button='pause';
    }
    update_div('CustomStatus'.$MO,'dont',$status);

    update_div('row-'.$MO,$newclass.' row row-prod-plan ','dont');//<span class="glyphicon glyphicon-play"></span>
    update_div('button-'.$MO,'dont','<span class=\"glyphicon glyphicon-'.$button.'\"></span>');
    notify_change_email($db,$MO,$status);
    
   
    
    //echo'<script>document.getElementById("CustomStatus'.$MO.'").innerHTML = "'.$newcontent.'";</script>';
}

function get_email($db,$username){
	$query='SELECT employee_email,employee_fullname,employee_name
	  FROM employee
	  Where employee_code=\''.$username.'\'
	  	  
	';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
	//
	return $row;
}

function notify_change_email($db,$MO,$status){
    
    $order=get_one_orders($db,$MO);
    $listcc=get_cc($db,$order['ManufactureOrderNumber'],'MO',$order['WorkArea']);
    
    $employee_email=get_email($db,$_SESSION['temp']['id']);
    $listcc[]=$employee_email;
    $address='production-assistant@sicame.com.au';
    $name='Production Assistant';
    $cc=' ';
    foreach($listcc as $cc1){
        $cc=$cc.';'.$cc1['employee_email'];
    }
    $content='';

    $query='SELECT *
    FROM prodplannotes
    where prodplannotes_REF=\''.$MO.'\' 
    ORDER BY prodplannotes_timetag DESC';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $notesList=$sql->fetchall();
   
    $content='Hi all,<br><br>';
    $content=$content.'The status of the Manufacture Order '.$order['ManufactureOrderNumber'].' for '.$order['Code'];
    
    if($status=='-'){
        $subject='Update on '.$order['ManufactureOrderNumber'].' - '.number_format($order['BaseQuantityOrdered']).' x '.$order['Code'].' due on '.date('jS M',strtotime($order['ManufactureBefore']));
        $content=$content.' was set back to normal';
    }else{
        $subject='PRODUCTION STOPPED - Update on '.$order['ManufactureOrderNumber'].' - '.number_format($order['BaseQuantityOrdered']).' x '.$order['Code'].' due on '.date('jS M',strtotime($order['ManufactureBefore']));
        $content=$content.' was set to '.$status.'';
    }
    $content=$content.' by '.$employee_email['employee_fullname'].' '.date('G:i:s \o\n \t\h\e jS M',time());
    $content=$content.'<br><br>Current Situation :<br>';
    $content=$content.'Quantity Ordered: '.number_format($order['BaseQuantityOrdered']).' <br>';
    $content=$content.'Quantity Made: '.number_format($order['QTY_MADE']).' <br>';
    $content=$content.'Quantity In Progress: '.number_format($order['QTY_INPROGRESS']).' <br>';
    $content=$content.'Quantity Remaining: '.number_format($order['BaseQuantityOrdered']-$order['QTY_MADE']-$order['QTY_INPROGRESS']).' <br>';
    $content=$content.'Hours Remaining: '.round($order['HOURS_Remaining'],1).'h <br>';
    if(!empty($notesList)){
        $content=$content.'<br>Notes :<br>';
    }
    foreach($notesList as $notes){
        $content=$content.date('jS M',$notes['prodplannotes_timetag']).' - ';
        $content=$content.''.$notes['prodplannotes_employee'].' : ';
        $content=$content.''.$notes['prodplannotes_notes'].'<br>';
    }

    //show($address);      
    //show($employee_email['employee_email']); 
    //$cc='corentin@sicame.com.au';
    //show($cc); 
   //show($subject); 
    //show($content);               
    send_email($address,$name,$content,$subject,$cc);
}

function save_risk($db,$MO,$status){
    $query='DELETE
    FROM prodplanrisk
    where prodplanrisk_REF=\''.$MO.'\'';	

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    if($status<>'Remove'){
        $query="INSERT INTO dbo.prodplanrisk
        (prodplanrisk_REF,
        prodplanrisk_employee,
        prodplanrisk_name,
        prodplanrisk_timetag
        ) 
        VALUES (
        '".$MO."',
        '".$_SESSION['temp']['id']."',
        '".$status."',
        '".time()."')";	

        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();

       $newclass=$status;
       $button='play';
    }else{
        $status='-';
        $newclass='';
        $button='pause';
    }
    update_div('CustomRisk'.$MO,'dont',$status);
    update_div('row-'.$MO,$newclass.' row row-prod-plan ','dont');

   // update_div('row-'.$MO,$newclass.' row row-prod-plan ','dont');//<span class="glyphicon glyphicon-play"></span>
  //  update_div('button-'.$MO,'dont','<span class=\"glyphicon glyphicon-'.$button.'\"></span>');
    //echo'<script>document.getElementById("CustomStatus'.$MO.'").innerHTML = "'.$newcontent.'";</script>';
}

function save_follow($db,$MO){
    $query='SELECT prodplanmaillist_employee
    FROM prodplanmaillist
    where prodplanmaillist_REF=\''.$MO.'\' and prodplanmaillist_employee=\''.$_SESSION['temp']['id_full_name'].'\'
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $follow=$sql->fetch(); 
    if(empty($follow)){
        $query='INSERT INTO prodplanmaillist
        (prodplanmaillist_employee,prodplanmaillist_REF)
        VALUES
        (\''.$_SESSION['temp']['id_full_name'].'\',\''.$MO.'\') ';
        $content='<span class=\"glyphicon glyphicon-check\"></span>';

    }else{
        $query='DELETE FROM prodplanmaillist
        where prodplanmaillist_REF=\''.$MO.'\' and prodplanmaillist_employee=\''.$_SESSION['temp']['id_full_name'].'\' ';
        $content='<span class=\"glyphicon glyphicon-unchecked\"></span>';
    }
    $sql = $db->prepare($query); 
    $sql->execute();
    update_div('check'.$MO,'dont',$content);
    //<span class="glyphicon glyphicon-play"></span>

   // update_div('row-'.$MO,$newclass.' row row-prod-plan ','dont');//<span class="glyphicon glyphicon-play"></span>
  //  update_div('button-'.$MO,'dont','<span class=\"glyphicon glyphicon-'.$button.'\"></span>');
    //echo'<script>document.getElementById("CustomStatus'.$MO.'").innerHTML = "'.$newcontent.'";</script>';
}

function save_one_follower($db,$MO,$follower){
    $query='SELECT prodplanmaillist_employee
    FROM prodplanmaillist
    where prodplanmaillist_REF=\''.$MO.'\' and prodplanmaillist_employee=\''.$follower.'\'
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $follow=$sql->fetch(); 
    if(empty($follow)){
        $query='INSERT INTO prodplanmaillist
        (prodplanmaillist_employee,prodplanmaillist_REF)
        VALUES
        (\''.$follower.'\',\''.$MO.'\') ';
        $content='<span class=\"glyphicon glyphicon-check\"></span>';

    }else{
        $query='DELETE FROM prodplanmaillist
        where prodplanmaillist_REF=\''.$MO.'\' and prodplanmaillist_employee=\''.$follower.'\' ';
        $content='<span class=\"glyphicon glyphicon-unchecked\"></span>';
    }
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    //update_div('check'.$MO,'dont',$content);
    //<span class="glyphicon glyphicon-play"></span>

   // update_div('row-'.$MO,$newclass.' row row-prod-plan ','dont');//<span class="glyphicon glyphicon-play"></span>
  //  update_div('button-'.$MO,'dont','<span class=\"glyphicon glyphicon-'.$button.'\"></span>');
    //echo'<script>document.getElementById("CustomStatus'.$MO.'").innerHTML = "'.$newcontent.'";</script>';
}

function save_preference($db,$MO){
    $status=$_POST['Preferences'];
    $query='DELETE
    FROM prodplanpref
    where prodplanpref_REF=\''.$MO.'\'';	

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    if($status<>'Remove'){
        $query="INSERT INTO dbo.prodplanpref
        (prodplanpref_REF,
        prodplanpref_employee,
        prodplanpref_name,
        prodplanpref_timetag
        ) 
        VALUES (
        '".$MO."',
        '".$_SESSION['temp']['id']."',
        '".$status."',
        '".time()."')";	

        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        $content=$_POST['Preferences'];
       
    }else{
        $status='-';
        $content='<br>';
    }
    
    
    
    
    
    
    update_div('Preferences-'.$MO,'dont',$content);
    //<span class="glyphicon glyphicon-play"></span>

   // update_div('row-'.$MO,$newclass.' row row-prod-plan ','dont');//<span class="glyphicon glyphicon-play"></span>
  //  update_div('button-'.$MO,'dont','<span class=\"glyphicon glyphicon-'.$button.'\"></span>');
    //echo'<script>document.getElementById("CustomStatus'.$MO.'").innerHTML = "'.$newcontent.'";</script>';
}

function make_filter(){
    $filter='manufactureON is not null and
	  manufactureBefore<=\''.date('Y-m-d',strtotime($_POST['end_date_to_show'])).'\'';
	if(!empty($_POST['WorkArea'])){
        if($_POST['WorkArea']=='Export'){
            $filter=$filter.' AND WorkArea=\'Assembly\' AND (
                Code like \'%PHM%\' 
                OR Code like \'%PHM%\'
                OR Code like \'%MTRS%\'
                
                OR Code like \'%PHM%\' )
                ';
        }elseif($_POST['WorkArea']=='Manufacturing'){
            $filter=$filter.' AND ( WorkArea=\'Bolt\' or WorkArea=\'Cutting\' or WorkArea=\'Machining\' or WorkArea=\'Moulding\' or WorkArea=\'Push-on Bolt\' )
                ';
        }elseif($_POST['WorkArea']=='All Exploded'){
            $filter=$filter.' ';
               
        }else{
            $filter=$filter.' AND WorkArea=\''.$_POST['WorkArea'].'\'';
        }
       
    }
    if(!empty($_POST['Location'])){
        
        $filter=$filter.' AND Location=\''.$_POST['Location'].'\'';
        
       
    }
    
	if(empty($_POST['show_completed'])){
		$filter=$filter.' AND IsLineOutstanding <>0 ';
	}else{
        if(empty($_POST['date_only'])){
            $filter=$filter.' AND (IsLineOutstanding <>0 or  manufactureBefore>=\''.date('Y-m-d',strtotime($_POST['start_date_to_show'])).'\')';
        }else{
            $filter=$filter.' AND ( manufactureBefore>=\''.date('Y-m-d',strtotime($_POST['start_date_to_show'])).'\')';
        }
		
	}
    return $filter;
}

function get_all_stats($db){
    $filter=make_filter();
    $query='SELECT 
	sum((COALESCE(QTY_MADE,0))*BOM_TIME/60) as sum_HOURS_Made
	,sum((COALESCE(QTY_INPROGRESS,0))*BOM_TIME/60) as sum_HOURS_Current
	,sum((BaseQuantityOrdered-COALESCE(QTY_MADE,0)-COALESCE(QTY_INPROGRESS,0))*BOM_TIME/60) as sum_HOURS_Remaining
    FROM MO_List
	left join MO_Hours_Scanned on MO_Hours_Scanned.[MO]=[ManufactureOrderNumber]
	left join prodplanstatus on prodplanstatus_REF=[ManufactureOrderNumber]
	left join BOM_List on BOM_List.Productcode=[Code]
	
  
    where  '.$filter.' and (prodplanstatus_name<>\'NotPlanned\' or prodplanstatus_name is null )';
	//show($query);
    $sql = $db->prepare($query); 
    $sql->execute();
    $allstats=$sql->fetch();


    $query='SELECT 
	sum((COALESCE(QTY_MADE,0))*BOM_TIME/60) as sum_HOURS_Made
	,sum((COALESCE(QTY_INPROGRESS,0))*BOM_TIME/60) as sum_HOURS_Current
	,sum((BaseQuantityOrdered-COALESCE(QTY_MADE,0)-COALESCE(QTY_INPROGRESS,0))*BOM_TIME/60) as sum_HOURS_Remaining
    FROM MO_List
	left join MO_Hours_Scanned on MO_Hours_Scanned.[MO]=[ManufactureOrderNumber]
	left join prodplanstatus on prodplanstatus_REF=[ManufactureOrderNumber]
	left join BOM_List on BOM_List.Productcode=[Code]
	
  
    where  '.$filter.' and (prodplanstatus_name=\'NotPlanned\')';
	//show($query);
    $sql = $db->prepare($query); 
    $sql->execute();
    $allstats['NotPlanned']=$sql->fetch();
    return $allstats;
}

function get_all_orders($db){
    $filter=make_filter();
    $pre_sort='';
    if($_SESSION['temp']['prodplan']['sort']=='(COALESCE(QTY_MADE,0))/(BaseQuantityOrdered) DESC' ){
        $olsort=$_SESSION['temp']['prodplan']['sort'];
        $_SESSION['temp']['prodplan']['sort']='(COALESCE(QTY_MADE,0)+COALESCE(QTY_INPROGRESS,0))/(BaseQuantityOrdered) DESC,(COALESCE(QTY_MADE,0))/(BaseQuantityOrdered) DESC';

    }elseif($_SESSION['temp']['prodplan']['sort']=='(COALESCE(QTY_MADE,0))/(BaseQuantityOrdered) ASC' ){
        $olsort=$_SESSION['temp']['prodplan']['sort'];
        $_SESSION['temp']['prodplan']['sort']='(COALESCE(QTY_MADE,0)+COALESCE(QTY_INPROGRESS,0))/(BaseQuantityOrdered) ASC,(COALESCE(QTY_MADE,0))/(BaseQuantityOrdered) ASC';

    }elseif($_SESSION['temp']['prodplan']['sort']=='HOURS_Remaining DESC' ){
        $olsort=$_SESSION['temp']['prodplan']['sort'];
        $_SESSION['temp']['prodplan']['sort']='HOURS_Remaining DESC,HOURS_Current DESC';

    }elseif($_SESSION['temp']['prodplan']['sort']=='HOURS_Remaining ASC' ){
        $olsort=$_SESSION['temp']['prodplan']['sort'];
        $_SESSION['temp']['prodplan']['sort']='HOURS_Remaining ASC,HOURS_Current ASC';

    }else{
        $olsort=$_SESSION['temp']['prodplan']['sort'];
    }
    if($_POST['WorkArea']=='Manufacturing'){$pre_sort='WorkArea ASC,';}
    if($_POST['WorkArea']=='All Exploded'){$pre_sort='WorkArea ASC,';}



    
    
    $query='SELECT *
	,(COALESCE(QTY_MADE,0))*BOM_TIME/60 as HOURS_Made
	,(COALESCE(QTY_INPROGRESS,0))*BOM_TIME/60 as HOURS_Current
	,(BaseQuantityOrdered-COALESCE(QTY_MADE,0)-COALESCE(QTY_INPROGRESS,0))*BOM_TIME/60 as HOURS_Remaining,
    (COALESCE(QTY_MADE,0))/(BaseQuantityOrdered) as Progress
    FROM MO_List
	left join MO_Hours_Scanned on MO_Hours_Scanned.[MO]=[ManufactureOrderNumber]
	left join prodplanstatus on prodplanstatus_REF=[ManufactureOrderNumber]
    left join prodplanrisk on prodplanrisk_REF=[ManufactureOrderNumber]
    left join prodplanpref on prodplanpref_REF=[ManufactureOrderNumber]
	left join BOM_List on BOM_List.Productcode=[Code]
	left join (SELECT count([prodplannotes_notes]) as Count_Notes
      ,[prodplannotes_REF]
  FROM [barcode].[dbo].[prodplannotes]
  group by [prodplannotes_REF])as temp on temp.prodplannotes_REF=[ManufactureOrderNumber]
    where  '.$filter.' 
    ORDER BY '.$pre_sort.$_SESSION['temp']['prodplan']['sort'].' ';
    if(!empty($_SESSION['temp']['prodplan']['sort'])){
        if( $_SESSION['temp']['prodplan']['sort']<>'ManufactureOn DESC' and $_SESSION['temp']['prodplan']['sort']<>'ManufactureOn ASC' ){
            $query=$query.',ManufactureOn asc';
        }
        if( $_SESSION['temp']['prodplan']['sort']<>'Code DESC' and $_SESSION['temp']['prodplan']['sort']<>'Code ASC' ){
            $query=$query.',Code asc';
        }
    }else{
        $query=$query.',ManufactureOn asc,Code asc';
    }
	$_SESSION['temp']['prodplan']['sort']=$olsort;
    $sql = $db->prepare($query); 
    $sql->execute();
   //show($query);
    $orderlist=$sql->fetchall();
    return $orderlist;
}

function get_one_orders($db,$MO){
    
    



    
    
    $query='SELECT *
	,(COALESCE(QTY_MADE,0))*BOM_TIME/60 as HOURS_Made
	,(COALESCE(QTY_INPROGRESS,0))*BOM_TIME/60 as HOURS_Current
	,(BaseQuantityOrdered-COALESCE(QTY_MADE,0)-COALESCE(QTY_INPROGRESS,0))*BOM_TIME/60 as HOURS_Remaining,
    (COALESCE(QTY_MADE,0))/(BaseQuantityOrdered) as Progress
    FROM MO_List
	left join MO_Hours_Scanned on MO_Hours_Scanned.[MO]=[ManufactureOrderNumber]
	left join prodplanstatus on prodplanstatus_REF=[ManufactureOrderNumber]
    left join prodplanrisk on prodplanrisk_REF=[ManufactureOrderNumber]
    left join prodplanpref on prodplanpref_REF=[ManufactureOrderNumber]
	left join BOM_List on BOM_List.Productcode=[Code]
	left join (SELECT count([prodplannotes_notes]) as Count_Notes
      ,[prodplannotes_REF]
  FROM [barcode].[dbo].[prodplannotes]
  group by [prodplannotes_REF])as temp on temp.prodplannotes_REF=[ManufactureOrderNumber]
    where  ManufactureOrderNumber=\''.$MO.'\' ';
     $sql = $db->prepare($query); 
    $sql->execute();
   //show($query);
    $orderlist=$sql->fetch();
    return $orderlist;
}

function get_data_summary_orders($db,$date){
    $nextfriday=date('Y-m-d',strtotime($date. ' next saturday -1 days'));
    $lastmonday=date('Y-m-d',strtotime($date. ' last sunday +1 days'));
    

    $query="SELECT baseallocationwork_code,
    sum(baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100) as hours_available 
    
    FROM dbo.allocationcontract
    left join dbo.allocationwork on allocationwork_operatorid=allocationcontract_operatorid and allocationwork_date=allocationcontract_date
    left join dbo.allocation on allocation_operatorid=allocationcontract_operatorid and allocation_date=allocationcontract_date
    left join dbo.allocationshift on allocationshift_operatorid=allocationcontract_operatorid and allocationshift_date=allocationcontract_date
    left join dbo.baseallocationshift on baseallocationshift_code=allocationwork_code
    left join dbo.baseallocationwork on baseallocationwork_code=allocationwork_code 
    left join dbo.baseallocation on baseallocation_code=allocation_code 
    left join dbo.baseallocationcontract on baseallocationcontract_code=allocationcontract_code 
    WHERE allocationcontract_date>='".$lastmonday."' 
    AND allocationcontract_date<='".$nextfriday."' 
    and allocationshift_code <> 'Null'
    AND ((DATEPART(DW,allocationcontract_date)<>6 and DATEPART(DW,allocationcontract_date)<>7 
        ) or((DATEPART(DW,allocationcontract_date)=6 or DATEPART(DW,allocationcontract_date)=7)and baseallocation_working<100 ))
    AND (baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100)>0
    group by baseallocationwork_code
    ";
    //show($query);
    $sql = $db->prepare($query); 
    $sql->execute();
    //show(nbr_of_line);
    $rowtemp=$sql->fetchall();
    $row['ManufactureBefore']=$nextfriday;
    $row['Manufactureon']=$lastmonday;
    foreach($rowtemp as $line){
        $row ['total'] =$row ['total'] +$line["hours_available"];
        $row [$line['baseallocationwork_code']]['total']  = $row[$date] [$line['baseallocationwork_code']]['total']  +$line["hours_available"];
        $row  [$line['baseallocationwork_code']]  [$line['allocationshift_code']]=$row[$date]  [$line['baseallocationwork_code']]  [$line['allocationshift_code']] +$line["hours_available"];
        
    }
   

     //show($row);
   
  
  return $row;
}

function get_summary_orders($db,$date,$showoutstanding=0){
    $nextfriday=date('Y-m-d',strtotime($date. ' next saturday -1 days'));
    $lastmonday=date('Y-m-d',strtotime($date. ' last sunday +1 days'));
    if($showoutstanding==0){
       $outstandingfilter='(IsLineOutstanding <>0 or manufactureBefore>=\''.$lastmonday.'\')';
   }else{
    $outstandingfilter='manufactureBefore>=\''.$lastmonday.'\'';
   }
   


    $query='SELECT iif(WorkArea=\'Push-On Bolt\',\'BoltAsmb\',WorkArea)as WorkArea,
	sum((COALESCE(QTY_MADE,0))*BOM_TIME/60) as sum_HOURS_Made
	,sum((COALESCE(QTY_INPROGRESS,0))*BOM_TIME/60) as sum_HOURS_Current
	,sum((BaseQuantityOrdered-COALESCE(QTY_MADE,0)-COALESCE(QTY_INPROGRESS,0))*BOM_TIME/60) as sum_HOURS_Remaining
    FROM MO_List
	left join MO_Hours_Scanned on MO_Hours_Scanned.[MO]=[ManufactureOrderNumber]
	left join prodplanstatus on prodplanstatus_REF=[ManufactureOrderNumber]
	left join BOM_List on BOM_List.Productcode=[Code]
	
    where  manufactureON is not null and
	  manufactureBefore<=\''.$nextfriday.'\' AND '.$outstandingfilter.' and (prodplanstatus_name<>\'NotPlanned\' or prodplanstatus_name is null )
      group by WorkArea';
	
    $sql = $db->prepare($query); 
    $sql->execute();
    //show($query);
    $templist=$sql->fetchall();
    $orderlist['ManufactureBefore']=$nextfriday;
    $orderlist['Manufactureon']=$lastmonday;
    foreach($templist as $workarea){
        $orderlist['sum_HOURS_Made']=round($orderlist['sum_HOURS_Made']+$workarea['sum_HOURS_Made'],2);
        $orderlist['sum_HOURS_Remaining']=round($orderlist['sum_HOURS_Remaining']+$workarea['sum_HOURS_Remaining'],2);
        $orderlist['sum_HOURS_Current']=round($orderlist['sum_HOURS_Current']+$workarea['sum_HOURS_Current'],2);
        $orderlist[$workarea['WorkArea']]['sum_HOURS_Made']=round($workarea['sum_HOURS_Made'],2);
        $orderlist[$workarea['WorkArea']]['sum_HOURS_Remaining']=round($workarea['sum_HOURS_Remaining'],2);
        $orderlist[$workarea['WorkArea']]['sum_HOURS_Current']=round($workarea['sum_HOURS_Current'],2);
    }
   
    return $orderlist;
}

function showallstats($db){
    $allstats=get_all_stats($db);
    $newcontent= '<div class=\"row \"><div class=\"col-sm-8 hours-stats\" style=\"font-size: 12px;text-align: right;\">Used:</div><div class=\"col-sm-4 \" style=\"font-size: 12px;text-align: right;\">'.round($allstats['sum_HOURS_Made'],1).' h</div></div>';
    $newcontent=$newcontent.'<div class=\"row \"><div class=\"col-sm-8 hours-stats\" style=\"font-size: 12px;text-align: right;\">In-Progress:</div><div class=\"col-sm-4 \" style=\"font-size: 12px;text-align: right;\">'.round($allstats['sum_HOURS_Current'],1).' h</div></div>';
    $newcontent=$newcontent.'<div class=\"row \"><div class=\"col-sm-8 hours-stats\" style=\"font-size: 12px;text-align: right;\">Remaining:</div><div class=\"col-sm-4 \" style=\"font-size: 12px;text-align: right;\">'.round($allstats['sum_HOURS_Remaining'],1).' h</div></div>';
    $newcontent=$newcontent.'<div class=\"row \"><div class=\"col-sm-8 hours-stats\" style=\"font-size: 12px;text-align: right;\">Total:</div><div class=\"col-sm-4 \" style=\"font-size: 12px;text-align: right;\">'.round($allstats['sum_HOURS_Made']+$allstats['sum_HOURS_Current']+$allstats['sum_HOURS_Remaining'],1).' h</div></div>';
    $newcontent=$newcontent.'<div class=\"row \"><div class=\"col-sm-8 hours-stats\" style=\"font-size: 12px;text-align: right;\">NotPlanned:</div><div class=\"col-sm-4 \" style=\"font-size: 12px;text-align: right;\">'.round($allstats['NotPlanned']['sum_HOURS_Remaining'],1).' h</div></div>';
    update_div('allstats','dont',$newcontent);
    
}

function show_summary($db){
    create_css ($db,'allocationwork');
    $date=date('Y-m-d',time());
    $date=date('Y-m-d',strtotime($date. ' last sunday +1 days'));
    $alldata[$date]=get_summary_orders($db,$date,0);
    $alldata2[$date]=get_data_summary_orders($db,$date);
    $nextdate=date('Y-m-d',strtotime($date. ' +7 days'));
    for ($i = 0; $i <= 10; $i++) {
        $alldata[$nextdate]=get_summary_orders($db,$nextdate,1);
        $alldata2[$nextdate]=get_data_summary_orders($db,$nextdate);
        $nextdate=date('Y-m-d',strtotime($nextdate. ' +7 days'));
      }
    $allworkarea=get_all_workarea($alldata2);
    //show($allworkarea);
   
    //show($alldata);
    echo'<div class="row header-prod-plan">';
            echo'<div class="col-sm-3">
            <div class="col-sm-6 ">Week</div>
            <div class="col-sm-6 ">Hours</div>
            </div>';
            
            echo'<div class="col-sm-8">WorkArea';
                // echo'<div class="col-sm-12">Assembly</div>';
                // echo'<div class="col-sm-4">Planned</div>';
                // echo'<div class="col-sm-4">Available</div>';
                // echo'<div class="col-sm-4">Diff</div>';
            echo'</div>';
            echo'<div class="col-sm-2"></div>';

    echo'</div>';
    //show($alldata);
    $i=1;
    foreach($alldata as $week){
        echo'<div class="row row-prod-plan row_normal ">';
            echo'<div class="col-sm-3">';
                echo'<div class="col-sm-6 ">';
                    echo'<br><br>'.date('jS M',strtotime($week['Manufactureon'])).' - '.date('jS M',strtotime($week['ManufactureBefore']));
                echo'</div>';
                echo'<div class="col-sm-3 ">';
                    echo'<div class="row"><br></div>';
                    echo'<div class="row">Planned</div>';
                    if($i==1){
                        echo'<div class="row">Made</div>';
                        echo'<div class="row">InProgress</div>';
                    }
                    echo'<div class="row">Remaining</div>';
                    echo'<div class="row">Available</div>';
                    echo'<div class="row">Diff</div>';
                echo'</div>';
                echo'<div class="col-sm-3 ">';
                    echo'<div class="row"><b>Total</b></div>';
                    echo'<div class="row">'.number_format($week['sum_HOURS_Made']+$week['sum_HOURS_Current']+$week['sum_HOURS_Remaining'],0).'</div>';
                    if($i==1){
                        echo'<div class="row">'.number_format($week['sum_HOURS_Made'],0).'</div>';
                        echo'<div class="row">'.number_format($week['sum_HOURS_Current'],0).'</div>';
                    }
                    echo'<div class="row">'.number_format($week['sum_HOURS_Remaining'],0).'</div>';
                    echo'<div class="row">'.number_format($alldata2[$week['Manufactureon']]['total'],0).'</div>';
                    echo'<div class="row">'.number_format($alldata2[$week['Manufactureon']]['total']-($week['sum_HOURS_Remaining']),0).'</div>';
            
                echo'</div>';
            
            
            echo'</div>';
            
            
            
            
            echo'<div class="col-sm-8 ">';
            foreach($allworkarea as $workarea){
                echo'<div class="col-sm-1 '.$workarea.'">';
                    echo'<div class="row"><b>'.$workarea.'</b></div>';
                    echo'<div class="row">'.number_format($week[$workarea]['sum_HOURS_Made']+$week[$workarea]['sum_HOURS_Current']+$week[$workarea]['sum_HOURS_Remaining'],0).'</div>';
                    if($i==1){
                        echo'<div class="row">'.number_format($week[$workarea]['sum_HOURS_Made'],0).'</div>';
                        echo'<div class="row">'.number_format($week[$workarea]['sum_HOURS_Current'],0).'</div>';
                    }
                    echo'<div class="row">'.number_format($week[$workarea]['sum_HOURS_Remaining'],0).'</div>';
                    echo'<div class="row">'.number_format($alldata2[$week['Manufactureon']][$workarea]['total'],0).'</div>';
                    echo'<div class="row">'.number_format($alldata2[$week['Manufactureon']][$workarea]['total']-($week[$workarea]['sum_HOURS_Remaining']),0).'</div>';
                echo'</div>';
            }
            echo'</div>';
            

        echo'</div>';
        $i++;
    }
    //show($alldata2);
}

function get_all_workarea($alldata2){
    //show($alldata2);

    foreach($alldata2 as $week){
        
        foreach(array_keys($week) as $workarea){
            if($workarea<>'ManufactureBefore' and $workarea<>'Manufactureon' and $workarea<>'total'){
                $workarealist[$workarea]=$workarea;
            }
        }


    }
    return $workarealist;
}


function testemail123(){
    $address='corentin@sicame.com.au';
	$name='test';
	$content='Test email, i hope it works';
	$subject='Test Email';
	send_email($address,$name,$content,$subject,$cc);
}

?>