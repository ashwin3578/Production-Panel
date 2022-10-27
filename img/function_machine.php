<?php
load_role($db,$_SESSION['temp']['id']);

function manage_post_machine($db){
  
    if(!empty($_GET['debug'])){
        $_SESSION['temp']['debug']=$_GET['debug'];
    }
    
    if($_SESSION['temp']['debug']=='1'){
        show($_POST);
    }
    if(!empty($_POST['machine_to_allocated'])){
        allocate_machine_MAC($db);
    }
    if(empty($_SESSION['temp']['view'])){
        
        $_SESSION['temp']['view']='View All Machine';
        
    }
    if(!empty($_POST['view'])){
        $_SESSION['temp']['view']=$_POST['view'];
    }
    if(!empty($_POST['machine_list'])){
        $_SESSION['temp']['machine_list']=$_POST['machine_list'];
    }
    

    
    
    
    if(!empty($_POST['machine_name']) ){
        $_SESSION['temp']['machine_name']=$_POST['machine_name'];
        if($_POST['machine_name']=='All'){
            $_SESSION['temp']['machine_name']='';
            $_POST['entry_type']='All';
        }
    }
    if(!empty($_POST['entry_type']) ){
        $_SESSION['temp']['entry_type']=$_POST['entry_type'];
        if($_POST['entry_type']=='All'){
            $_SESSION['temp']['entry_type']='';
        }
    }
    if(!empty($_POST['date_filter']) or !empty($_SESSION['temp']['date_filter'])){
        if(empty($_POST['date_filter'])){$_POST['date_filter']=$_SESSION['temp']['date_filter'];}
        $_SESSION['temp']['date_filter']=$_POST['date_filter'];
        if(empty($_POST['date_filter_start'])){$_POST['date_filter_start']=$_SESSION['temp']['date_filter_start'];}
        if(empty($_POST['date_filter_end'])){$_POST['date_filter_end']=$_SESSION['temp']['date_filter_end'];}
        if(empty($_POST['time_filter_start'])){$_POST['time_filter_start']=$_SESSION['temp']['time_filter_start'];}
        if(empty($_POST['time_filter_end'])){$_POST['time_filter_end']=$_SESSION['temp']['time_filter_end'];}
        if($_POST['date_filter']=='Custom'){
            if(empty($_POST['date_filter_start'])){
                $timetag_start=time()-24*3600;    
            }else{
                $timetag_start=strtotime($_POST['date_filter_start'].' '.$_POST['time_filter_start'].'');
            }
            if(empty($_POST['date_filter_end'])){
                $timetag_end=time()+3;
            }else{
                $timetag_end=strtotime($_POST['date_filter_end'].' '.$_POST['time_filter_end'].'');
                
            }
            
        }elseif($_POST['date_filter']=='Today'){
            $timetag_start=strtotime(date('Y-m-d',time()));
            $timetag_end=time()+3;
        }elseif($_POST['date_filter']=='Yesterday'){
            $timetag_start=strtotime(date('Y-m-d',time()).' -1 days');
            $timetag_end=strtotime(date('Y-m-d',time()));
        }elseif($_POST['date_filter']=='Last Hour'){
            $timetag_start=time()-3600;
            $timetag_end=time()+3;
        }elseif($_POST['date_filter']=='Last 24 Hours'){
            $timetag_start=time()-24*3600;
            $timetag_end=time()+3;
        }elseif($_POST['date_filter']=='Last 7 Days'){
            $timetag_start=time()-7*24*3600;
            $timetag_end=time()+3;
        }elseif($_POST['date_filter']=='Last Month'){
            $timetag_start=time()-30*24*3600;
            $timetag_end=time()+3;
        }elseif($_POST['date_filter']=='Last Year'){
            $timetag_start=time()-365*24*3600;
            $timetag_end=time()+3;
        }
        
    }else{
        
        $timetag_start=strtotime(date('Y-m-d',time()));
        $timetag_end=time()+3;
    }




    
    $_SESSION['temp']['date_filter_start']=date('Y-m-d',$timetag_start);
    $_SESSION['temp']['time_filter_start']=date('H:i:s',$timetag_start);
    $_SESSION['temp']['date_filter_end']=date('Y-m-d',$timetag_end);
    $_SESSION['temp']['time_filter_end']=date('H:i:s',$timetag_end);

    if(!empty($_POST['reset_import'])){
        
        foreach(get_all_MAC($db,$_POST['machine_name']) as $mac){
            
            re_import_all_event($db,$mac['machineallocation_MAC']);
        }
    }
    $_POST['machine_list']=$_SESSION['temp']['machine_list'];
    $_POST['view']=$_SESSION['temp']['view'];
    $_POST['entry_type']=$_SESSION['temp']['entry_type'];
    $_POST['machine_name']=$_SESSION['temp']['machine_name'];
    $_POST['date_filter']=$_SESSION['temp']['date_filter'];
    $_POST['date_filter_start']=$_SESSION['temp']['date_filter_start'];
    $_POST['date_filter_end']=$_SESSION['temp']['date_filter_end'];
    $_POST['time_filter_start']=$_SESSION['temp']['time_filter_start'];
    $_POST['time_filter_end']=$_SESSION['temp']['time_filter_end'];
    //show($_POST);

}



function general_view_machine($db){
   
    $defaultcol='col-md-7 col-lg-7';
    if($_POST['view']=='View All Machine'){
        $defaultcol='col-md-12 col-lg-12';
    }
   
    echo'<div class="row ">';
       
        echo'<div  class="'.$defaultcol.'">';
            if($_POST['view']=='View Details'){
                navbar_all_stats_machine($db);
            }elseif($_POST['view']=='View All Machine' or $_POST['view']=='View All Device'){
                navbar_all_machine($db);
            }

            echo'<div id="here">';
           if($_POST['view']=='view_live'){
            show_view_live_temptable($db);
           }elseif($_POST['view']=='View All Machine'){
            show_view_all_machine($db);
           }elseif($_POST['view']=='View All Device'){
            show_view_all_device($db);
           }elseif($_POST['view']=='Clean Data'){
            clean_data($db);
           }elseif($_POST['view']=='test_import'){
            import_temptable($db);
           }elseif($_POST['view']=='View Details'){
            show_all_stats($db);
           }elseif($_POST['view']=='view_all'){
            show_all_temptable($db);
           }elseif($_POST['view']=='manage_machine'){
            show_manage_machine($db);
           }elseif($_POST['view']=='Allocation Machine'){
            show_view_all_device($db);
           }else{
            show_view_all_machine($db);
           }
            

            echo'</div>';
        echo'</div>';
        echo'<div  class="col-md-1 ">';
            
        echo'</div>';
        echo'<div  class="col-md-3 col-lg-3 ">';
            echo'<div id="all_stats">';
            if($_POST['view']=='View Details'){
                echo'<div id="stats">';
                show_details_stats($db);
                echo'</div>';
               }elseif($_POST['view']=='Allocation Machine'){
                show_allocation_machine($db);
            }
            echo'</div>';
        echo'</div>';

        echo'<div class="col-sm-4 dialog-box" >';
        
        
        
        echo'</div>';
    echo'</div>';
    echo'<div class="col-sm-4 hidden-box" >';
        
        
        
    echo'</div>';
    
}




function navbar_machine($db){

   
    echo'<div class="row navbar navbar_injury">';
        echo'<form method="POST">';
        echo'<div class="col-sm-1 ">';
        //echo'<button type="submit" name="type" value="CreateNewReport"  class="btn btn-primary injury_button" >Create new Report</button>';
        
        //if($_SESSION['temp']['id']=='CorentinHillion'){
        echo'<input type="submit" name="view" value="view_live"  class="btn btn-primary injury_button" onclick="submit();" >';
       // }
        echo'</div>';
        echo'<div class="col-sm-1 ">';
        if($_SESSION['temp']['id']=='CorentinHillion'){
        echo'<input type="submit" name="view" value="Clean Data"  class="btn btn-primary injury_button" onclick="submit();" >';
         }
       //
        echo'</div>';
        echo'<div class="col-sm-1 ">';
        if($_SESSION['temp']['id']=='CorentinHillion'){
        echo'<input type="submit" name="view" value="view_all"  class="btn btn-primary injury_button" onclick="submit();" >';
        }
        echo'</div>';
        echo'<div class="col-sm-1 ">';
        if($_SESSION['temp']['id']=='CorentinHillion'){
        echo'<input type="submit" name="view" value="View All Device"  class="btn btn-primary injury_button" onclick="submit();" >';
        }
        echo'</div>';
        echo'<div class="col-sm-2 ">';
        
        echo'<input type="submit" name="view" value="View All Machine"  class="btn btn-primary injury_button" onclick="submit();" >';
       
        echo'</div>';
        echo'<div class="col-sm-2 ">';
        echo'<input type="submit" name="view" value="View Details"  class="btn btn-primary injury_button" onclick="submit();" >';
       
        echo'</div>';
        
        echo'</form>';
    echo'</div>';
    
}

function navbar_all_stats_machine($db){

   
    echo'<div class="row navbar navbar_injury">';
        
        echo'<div class="col-sm-3 ">';
        echo'<form method="POST">';
        echo'<select name="machine_name" class="form-control" onChange=\'submit();\'>';
            
            echo'<option ';
            if(empty($_POST['machine_name'])){echo'selected';}
            echo'>All</option>';
            
            echo'<option disabled>_________</option>';
            foreach(get_all_machine_event($db) as $single){
                echo'<option ';
                if($_POST['machine_name']==$single['machine_name']){
                    echo'selected';
                
                }
                echo'>'.$single['machine_name'].'</option>';
            }
        echo'</select>';
        echo'</div>';
        echo'<div class="col-sm-3 ">';
        if(!empty($_POST['machine_name'])){
        echo'<select name="entry_type" class="form-control" onChange=\'submit();\'>';
            
            echo'<option ';
            if(empty($_POST['entry_type'])){echo'selected';}
            echo'>All</option>';
            
            echo'<option disabled>_________</option>';
            foreach(get_all_type_event($db) as $single){
                echo'<option ';
                if($_POST['entry_type']==$single['machineevent_type']){
                    echo'selected';
                
                }
                echo'>'.$single['machineevent_type'].'</option>';
            }
        echo'</select>';
        }
       
        echo'</div>';
        
        
        echo'<div class="col-sm-2 ">';
            echo'<select name="date_filter" class="form-control" onChange=\'submit();\'>';
                echo'<option ';
                if(empty($_POST['date_filter'])or $_POST['date_filter']=='Today'){echo'selected';}
                echo'>Today</option>';
                echo'<option ';
                if( $_POST['date_filter']=='Yesterday'){echo'selected';}
                echo'>Yesterday</option>';
                echo'<option ';
                if($_POST['date_filter']=='Last Hour'){echo'selected';}
                echo'>Last Hour</option>';
                echo'<option ';
                if($_POST['date_filter']=='Last 24 Hours'){echo'selected';}
                echo'>Last 24 Hours</option>';
                echo'<option ';
                if($_POST['date_filter']=='Last 7 Days'){echo'selected';}
                echo'>Last 7 Days</option>';
                echo'<option ';
                if($_POST['date_filter']=='Last Month'){echo'selected';}
                echo'>Last Month</option>';
                echo'<option ';
                if($_POST['date_filter']=='Last Year'){echo'selected';}
                echo'>Last Year</option>';
                echo'<option ';
                if($_POST['date_filter']=='Custom'){echo'selected';}
                echo'>Custom</option>';
                
            echo'</select>';
        echo'</div>';
        echo'<div class="col-sm-2 ">';
            if($_POST['date_filter']=='Custom'){
                echo'<input type="date" name="date_filter_start" value="'.$_POST['date_filter_start'].'"  class="form-control" onChange="submit();" >';
                echo'<input type="date" name="date_filter_end" value="'.$_POST['date_filter_end'].'"  class="form-control" onChange="submit();" >';
            }
        
        echo'</div>';
        echo'<div class="col-sm-2 ">';
            if($_POST['date_filter']=='Custom'){
                echo'<input type="time" name="time_filter_start" value="'.$_POST['time_filter_start'].'" step="1" class="form-control" onChange="submit();" >';
                echo'<input type="time" name="time_filter_end" value="'.$_POST['time_filter_end'].'" step="1" class="form-control" onChange="submit();" >';
            }
        echo'</form>';
        echo'</div>';
        echo'<div class="col-sm-2 ">';
        echo'<form method="POST">';
        if(!empty($_POST['machine_name']) and $_SESSION['temp']['id']=='CorentinHillion'){
            echo'<input type="submit" name="reset_import" value="Reset_all"  class="btn btn-primary injury_button" onclick="submit();" >';
            echo'<input type="hidden" name="machine_name" value="'.$_POST['machine_name'].'"   >';
        }
        echo'</form>';
        echo'</div>';
        
        
    echo'</div>';
    
}

function navbar_all_machine($db){

   
    echo'<div class="row navbar navbar_injury">';
        
        echo'<div class="col-sm-3 ">';
        echo'<form method="POST">';
        
        echo'</div>';
        echo'<div class="col-sm-3 ">';
        if($_POST['view']=='View All Machine'){
        if($_POST['machine_list']=='All Machine'){$value='Not All Machine';}else{$value='All Machine';}
        echo'<input type="submit" name="machine_list" value="'.$value.'"  class="btn btn-primary injury_button" onclick="submit();" >';
        }
        echo'</div>';
        
        
        echo'<div class="col-sm-2 ">';
            echo'<select name="date_filter" class="form-control" onChange=\'submit();\'>';
                echo'<option ';
                if(empty($_POST['date_filter'])or $_POST['date_filter']=='Today'){echo'selected';}
                echo'>Today</option>';
                echo'<option ';
                if( $_POST['date_filter']=='Yesterday'){echo'selected';}
                echo'>Yesterday</option>';
                echo'<option ';
                if($_POST['date_filter']=='Last Hour'){echo'selected';}
                echo'>Last Hour</option>';
                echo'<option ';
                if($_POST['date_filter']=='Last 24 Hours'){echo'selected';}
                echo'>Last 24 Hours</option>';
                echo'<option ';
                if($_POST['date_filter']=='Last 7 Days'){echo'selected';}
                echo'>Last 7 Days</option>';
                echo'<option ';
                if($_POST['date_filter']=='Last Month'){echo'selected';}
                echo'>Last Month</option>';
                echo'<option ';
                if($_POST['date_filter']=='Last Year'){echo'selected';}
                echo'>Last Year</option>';
                echo'<option ';
                if($_POST['date_filter']=='Custom'){echo'selected';}
                echo'>Custom</option>';
                
            echo'</select>';
        echo'</div>';
        echo'<div class="col-sm-2 ">';
            if($_POST['date_filter']=='Custom'){
                echo'<input type="date" name="date_filter_start" value="'.$_POST['date_filter_start'].'"  class="form-control" onChange="submit();" >';
                echo'<input type="date" name="date_filter_end" value="'.$_POST['date_filter_end'].'"  class="form-control" onChange="submit();" >';
            }
        
        echo'</div>';
        echo'<div class="col-sm-2 ">';
            if($_POST['date_filter']=='Custom'){
                echo'<input type="time" name="time_filter_start" value="'.$_POST['time_filter_start'].'" step="1" class="form-control" onChange="submit();" >';
                echo'<input type="time" name="time_filter_end" value="'.$_POST['time_filter_end'].'" step="1" class="form-control" onChange="submit();" >';
            }
        echo'</form>';
        echo'</div>';
        
        
        
    echo'</div>';
    
}




function show_all_temptable($db){
    echo'<div class="row machine_header">';
        echo'<div class="col-sm-6 col-md-3">MAC Adress</div>';
        echo'<div class="col-sm-6 col-md-3">Date</div>';
        echo'<div class="col-md-3 col-sm-6">PIN Status</div>';
        echo'<div class="col-md-3 col-sm-6">Changes of PIN</div>';

    echo'</div>';

    foreach(get_all_temptable($db) as $entry){
        echo'<div class="row machine_row">';
            echo'<div class="col-sm-6 col-md-3">';
            
            if(!empty($entry['machine_name'])){
                echo $entry['machine_name'];
                //echo $entry['temptable_id'];
            }else{
                echo $entry['temptable_MAC'];
            }
            
            echo'</div>';
            echo'<div class="col-sm-6 col-md-3">';
            echo date('Y-m-d G:i:s',$entry['temptable_timetag']);
            echo'</div>';
            echo'<div class="col-md-3 col-sm-6">';
            $pins=explode(";",$entry['temptable_entry']);
            $i=0;
            foreach($pins as $pin){
                echo'<div class="pin';
                if($pin==1){echo ' activated';}
                echo'">';
                echo $pin;
                echo'</div>';
                if($i==7){
                    echo'</div>';
                    echo'<div class="col-md-3 col-sm-6">';
                }
                $i++;

            }
            echo'</div>';

        echo'</div>';
    }
    echo'<script> 
        $(document).ready(function(){
        setInterval(function(){
              $("#here").load(window.location.href + " #here" );
        }, 500);
        });
        </script>';
}

function show_view_live_temptable($db){
   

    foreach(get_last_temptable($db) as $entry){
    echo'<div class="row machine_row">';
        echo'<div class="col-sm-12 time_big">';
        echo $entry['temptable_MAC'];
        echo ' - ';
        echo $entry['machine_name'];
        echo'<br></div>';
        echo'<div class="col-sm-12 col-md-3 time_big">';
        echo date('Y-m-d',$entry['temptable_timetag']);
        echo '<br>'.date('G:i:s',$entry['temptable_timetag']);
        echo'<br></div>';
        echo'<div class="col-md-9 col-sm-12">';
        $pins=explode(";",$entry['temptable_entry']);
        $i=0;
        foreach($pins as $pin){
            if($i<8){
               
            echo'<div class="pin_live">';
                echo'<div class="row ">PIN '.($i+2).'</div>';
                echo'<div class="row ">';
                    if($pin==1){
                        $image='led_on.png';
                    }else{
                        $image='led_off.png';
                    }
                    echo'<img src="img/'.$image.'" width="100%" height="75" ><br>';
                echo'</div>';
            echo'</div>';
            }
            $i++;

        }
        echo'</div>';

    echo'</div>';
    }
    echo'<script> 
        $(document).ready(function(){
        setInterval(function(){
              $("#here").load(window.location.href + " #here" );
        }, 500);
        });
        </script>';
}

function show_view_all_machine($db){
    import_temptable($db);
    
    $col1='col-lg-1 col-md-1 col-sm-1 hidden-xs';
    $col2='col-lg-2 col-md-2 col-sm-3 col-xs-6';
    $col3='col-lg-1 col-md-1 col-sm-1 col-xs-3';
    $col4='col-lg-1 col-md-1 col-sm-1 col-xs-3';
    $col5='col-lg-2 col-md-2 col-sm-4 col-xs-6';
    $col6='col-lg-1 col-md-2 col-sm-2 col-xs-6';
    $col7='col-lg-1 col-md-1 col-sm-1 hidden-xs';
    $col8='col-lg-1 col-md-2 col-sm-2 hidden-xs';

    echo'<div class="row machine_header">';
        echo'<div class="'.$col1.'">WorkArea</div>';
        echo'<div class="'.$col2.'">Machine</div>';
        echo'<div class="'.$col3.'">Count</div>';
        echo'<div class="'.$col4.'">Part/h</div>';
        echo'<div class="'.$col5.'">Last 5H</div>';
        echo'<div class="'.$col6.'">Last Scan</div>';
        echo'<div class="'.$col7.'">Sensors</div>';
        echo'<div class="'.$col8.'">Action</div>';
    echo'</div>';
    echo'<div class="all_line_container">';
    
    foreach(get_all_machine_summary($db) as $entry){
        $stats=get_hours_trend($db,$entry['machine_name']);
        echo'<div class="row machine_row">';
            echo'<div class="'.$col1.'">';
            echo $entry['machine_workarea'];
            echo'</div>';
            echo'<div class="'.$col2.'">';
            echo $entry['machine_name'];
            echo'</div>';
            echo'<div class="'.$col3.'">';
            if(!empty($entry['thecount'])){
                echo number_format($entry['thecount']);
            }else{
                echo'-';
            }
            
            echo'</div>';
            echo'<div class="'.$col4.'">';
            if(!empty($entry['thecount'])){
                echo number_format(show_part_per_hour($stats['all_hours'],500));
            }else{
                echo'-';
            }
            
            echo'</div>';
            echo'<div class="'.$col5.'">';
            if(!empty($entry['thecount'])){
                echo show_hours_count($stats['all_hours'],5);  
            }
            
                     
            echo'</div>';
            echo'<div class="'.$col6.'">';
            if(!empty($entry['lastscan'])){
            echo date('Y-m-d G:i:s',$entry['lastscan']);
            }else{
                echo'-';
            }
            echo'</div>';
            echo'<div class="'.$col7.'">';
            if(!empty($entry['thecount'])){
                echo number_format($entry['numberofsensor']);
            }else{
                echo'-';
            }
            
            echo'</div>';
            echo'<div class="'.$col8.'">';
            echo'<form method="POST">';
            echo '<button type="submit" name="view" value="manage_machine" class="btn btn-default">';
                echo'<span class="glyphicon glyphicon-wrench"> </span>';
            echo'</button>';
            echo '<button type="submit" name="view" value="remove_machine" class="btn btn-default">';
                echo'<span class="glyphicon glyphicon-trash"> </span>';
            echo'</button>';
            echo'<input type="hidden" name="machine_name" value="'.$entry['machine_name'].'">';
            echo'</form>';
            echo'</div>';
            
    
        echo'</div>';
        $lastimetag[$entry['machineevent_entry']]=$entry['machineevent_timetag'];
    }
    
    
    echo'</div>';
    refresh_div('here',500);
}

function show_view_all_device($db){
    import_temptable($db);
    
    $col1='col-sm-2';
    $col2='col-sm-2';
    $col3='col-sm-2';
    $col4='col-sm-2';
    $col5='col-sm-2';
    $col6='col-sm-2';
    if(!empty($_POST['MAC'])){
        echo'<div class="row machine_header">'.$_POST['MAC'];
        echo'</div>';
    }
    echo'<div class="row machine_header">';
        echo'<div class="'.$col1.'">MAC Adress</div>';
        echo'<div class="'.$col2.'">Current Allocation</div>';
        echo'<div class="'.$col3.'">Count</div>';
        echo'<div class="'.$col4.'">Scan</div>';
        echo'<div class="'.$col5.'">Allocation</div>';
        echo'<div class="'.$col6.'">Action</div>';
        
    echo'</div>';
    echo'<div class="all_line_container">';
    
    foreach(get_all_device_summary($db) as $entry){
        $stats=get_hours_trend($db,$entry['machine_name']);
        echo'<div class="row machine_row ';
        if (empty($entry['machineallocation_timetag_start'])){echo ' not_allocated';}
        echo'">';
            echo'<div class="'.$col1.'">';
            if (!empty($entry['temptable_MAC'])){
                echo $entry['temptable_MAC'];
            }else{
                echo $entry['machineallocation_MAC'];
            }
            echo'</div>';
            echo'<div class="'.$col2.'">';
            if (empty($entry['machineallocation_timetag_start'])){
                echo 'Device not Allocated ';
            }else{
                echo $entry['machine_name'];
            }
            
            echo'</div>';
            echo'<div class="'.$col3.'">';
            echo number_format($entry['thecount']);
            echo'</div>';
            echo'<div class="'.$col4.'">';
            if (!empty($entry['firstscan'])){
                echo date('Y-m-d G:i:s',$entry['firstscan']);
                echo'</br>';
                echo date('Y-m-d G:i:s',$entry['lastscan']);
            }
                
            echo'</div>';
            echo'<div class="'.$col5.'">';
            if (empty($entry['machineallocation_timetag_start'])){
                echo '-';
            }else{
                echo date('Y-m-d G:i:s',$entry['machineallocation_timetag_start']);
                echo'</br>';
                if($entry['machineallocation_timetag_end']==1999999999){
                    echo'-';
                }else{
                    echo date('Y-m-d G:i:s',$entry['machineallocation_timetag_end']);
                }
            }
            echo'</div>';
            echo'<div class="'.$col6.'">';
            if (empty($entry['machineallocation_timetag_start'])){
                echo'<form method="POST">';
                echo'<input type="submit" name="view" value="Allocation Machine"  class="btn btn-primary injury_button" onclick="submit();" >';
                echo'<input type="hidden" name="MAC" value="'.$entry['temptable_MAC'].'">';
               
                echo'</form >';
            }
            echo'</div>';
    
        echo'</div>';
        $lastimetag[$entry['machineevent_entry']]=$entry['machineevent_timetag'];
    }
    
    
    echo'</div>';
    if(empty($_POST['MAC'])){
    refresh_div('here',2000);
    }
}

function show_manage_machine($db){
    $info=get_machine_info($db,$_POST['machine_name']);
    echo'<div  class="col-lg-4 col-md-6 col-xs-12 machine_details ">';
        echo'<div class="row ">';
            echo'<div class="col-xs-6" >Machine Name</div>';
            echo'<div class="col-xs-6" >'.$info['machine_name'].'</div>';
            echo'</div>';
            echo'<div class="row ">';
            echo'<div class="col-xs-6" >Machine Number</div>';
            echo'<div class="col-xs-6" >'.$info['machine_number'].'</div>';
            echo'</div>';
            echo'<div class="row ">';
            echo'<div class="col-xs-6" >Workarea</div>';
            echo'<div class="col-xs-6" >'.$info['machine_workarea'].'</div>';
        echo'</div>';
    echo'</div>';
    echo'<div  class="col-lg-4 col-md-6 col-xs-12 all_sensor_details ">';
        echo'<div class="row ">';
            
            foreach($info['sensors'] as $sensor){
                echo'<div  class="col-lg-4 col-md-6 col-xs-12 sensor_details ">';
                echo'<div class="row ">'.$sensor['machinepin_pindescription'].'</div>';
                showline_machine('Pin',$sensor['machinepin_pinnumber']);
                if(empty($sensor['machinepin_triggerpull'])){$showline='Raising Edge';}else{$showline='I/O';}
                showline_machine('Type',$showline);
                showline_machine('Count',$sensor['machinepin_pinnumber']);
                echo'</div>';
            }
            
        echo'</div>';
    echo'</div>';
}

function show_allocation_machine($db){
    $macinfo=get_MAC_info($db,$_POST['MAC']);
    echo'<form method="POST">';
    echo'<div class="row machine_header">Machine Allocation</div>';
    echo'<div class="row machine_row">';
        echo'<div class="col-sm-6">Start Date</div>';
        echo'<div class="col-sm-6"><input type="time" name="time_allocation_start" value="'.date('G:i:s',$macinfo['firstscan']).'" step="1" class="form-control"  ></div>';
    echo'</div>';
    echo'<div class="row machine_row">';
        echo'<div class="col-sm-6">Start Time</div>';
        echo'<div class="col-sm-6"><input type="date" name="date_allocation_start" value="'.date('Y-m-d',$macinfo['firstscan']).'" step="1" class="form-control"  ></div>';
    echo'</div>';
    echo'<div class="row machine_row">';
        echo'<div class="col-sm-6">Machine</div>';
        echo'<div class="col-sm-6">';
        echo'<select name="machine_to_allocated" class="form-control">';
            $machines=get_all_machine($db);
            foreach($machines as $machine){
                echo'<option value="'.$machine['machine_id'].'">'.$machine['machine_name'].'</option>';
            }
           
        echo'</select>';
        echo'</div>';
    echo'</div>';
    echo'<div class="row machine_row">';
        echo'<div class="col-sm-6">End Date</div>';
        echo'<div class="col-sm-6"><input type="time" name="time_allocation_end" value="'.date('G:i:s',1999999999).'" step="1" class="form-control"  ></div>';
    echo'</div>';
    echo'<div class="row machine_row">';
        echo'<div class="col-sm-6">End Time</div>';
        echo'<div class="col-sm-6"><input type="date" name="date_allocation_end" value="'.date('Y-m-d',1999999999).'" step="1"  class="form-control"  ></div>';
    echo'</div>';
    echo'<div class="row machine_row">';
        echo'<input type="submit"  value="Save"   class="btn btn-primary injury_button"  >';
    echo'</div>';
    echo'<input type="hidden"  name="view" value="View All Device"   class="btn btn-primary injury_button"  >';
    echo'<input type="hidden"  name="MAC_to_allocated" value="'.$_POST['MAC'].'"   class="btn btn-primary injury_button"  >';
    
    echo'</form>';//View All Device
}

function show_all_stats($db){
    $col[1]='col-sm-3';
    $col[2]='col-sm-3';
    $col[3]='col-sm-1';
    $col[4]='col-sm-3';
    $col[5]='col-sm-1';
    $col[6]='col-sm-1';

    import_temptable($db);
    echo'<div class="row machine_header">';
        echo'<div class="'.$col[1].'">Date</div>';
        echo'<div class="'.$col[2].'">Entry</div>';
        echo'<div class="'.$col[3].'">Time On</div>';
        echo'<div class="'.$col[5].'">Time Off</div>';
        echo'<div class="'.$col[4].'">Time to Next</div>';
    echo'</div>';
    echo'<div class="all_line_container">';
    if(!empty($_POST['machine_name'])){
        foreach(get_all_event($db) as $entry){
            showlineevent($entry,$col);
        }
    }
    
    echo'</div>';
    refresh_div('here',500);
}

function showlineevent($entry,$col){
    echo'<div class="row machine_row">';
        echo'<div class="'.$col[1].'">';
        echo date('Y-m-d',$entry['machineevent_timetag']);
        echo '<br>'.date('G:i:s',$entry['machineevent_timetag']);
        echo'<br></div>';
        echo'<div class="'.$col[2].'">';
        echo $entry['machineevent_entry'];
        echo'</div>';
        echo'<div class="'.$col[3].'">';
        echo show_time($entry['duration']);
        echo'</div>';
        echo'<div class="'.$col[5].'">';
        echo show_time($entry['duration']);
        echo'</div>';
        echo'<div class="'.$col[4].'">';
        if(!empty($lastimetag[$entry['machineevent_entry']])){
            echo show_time($lastimetag[$entry['machineevent_entry']]-$entry['machineevent_timetag']);
        }else{
            echo show_time(strtotime($_POST['date_filter_end'].' '.$_POST['time_filter_end'].'')-$entry['machineevent_timetag']);
        }
        
        echo'</div>';
        

    echo'</div>';
    $lastimetag[$entry['machineevent_entry']]=$entry['machineevent_timetag'];
}

function show_details_stats($db){
    echo'<div class="row navbar navbar_injury"><br>';
    echo'</div>';
    if($_POST['date_filter']=='Custom'){
        echo'<br><br>';
    }
    $stats=get_all_stats($db);
    echo'<div class="row machine_header">All Stats</div>';
    showline_machine('Count',$stats['thecount'],'machine_row');
    if(!empty($_POST['entry_type']) and is_there_duration($db,$_POST['entry_type'],$_POST['machine_name'])=='duration'){
        ;
        showline_machine('Average Duration',show_time($stats['average_duration']),'machine_row');
        showline_machine('Median Duration',show_time($stats['median_duration']),'machine_row');
    }
    showline_machine('Total Time',show_time(round(($stats['max_timetag']-$stats['min_timetag']),0)),'machine_row');
    showline_machine('Average Cycle Time',show_time(round(($stats['max_timetag']-$stats['min_timetag'])/$stats['thecount'],0)),'machine_row');
    showline_machine('Part/hours',(round($stats['thecount']/($stats['max_timetag']-$stats['min_timetag'])*3600,0)),'machine_row');
    showline_machine('Trend',show_hours_count($stats['all_hours']),'machine_row');
    
    
    refresh_div('stats',500);

}

function showline_machine($caption,$value,$class=''){
    echo'<div class="row '.$class.'">';
        echo'<div class=" col-sm-6">'.$caption.'</div>';
        echo'<div class=" col-sm-6">'.$value.'</div>';
    echo'</div>';
}

function show_hours_count($allhours,$limit=999){
    
   
    $max=0;
    $return="";
    $i=0;
    $olddate="";
    foreach($allhours as $hours){
        
        $max=max($max,$hours['thecount']);
    }
    //show($hours['thecount']);
    //show($hours['thecount']>0.8*$max);
    $return=$return. '<div class="day_block">';

    foreach($allhours as $hours){
        if($i<$limit){
            if($i<>0 and $olddate<>$hours['thedate']){
                $return=$return. '</div>';
                $return=$return. '<div class="day_block">';
            }
            $return=$return. '<div class="hours_block';
            if($hours['thecount']>(0.75*$max)){
                $return=$return. ' color_1';
            }elseif($hours['thecount']>(0.5*$max)){
                $return=$return. ' color_2';
            }else{
                $return=$return. ' color_3';
            }
            $return=$return.'">';
        // echo $hours['theHours'];
        $return=$return. $hours['thecount'];
        $return=$return. '</div>';
        }
       $olddate=$hours['thedate'];
       $i++;
    }
    $return=$return. '</div>';
    return $return;
}

function show_part_per_hour($allhours,$limit){

    $max=0;
    $return="";
    $i=0;
    $count=0;
    $hours_counted=0;
    foreach($allhours as $hours){
        
        $max=max($max,$hours['thecount']);
    }
    //show($hours['thecount']);
    //show($hours['thecount']>0.8*$max);
    //$return=$return. '<div class="day_block">';

    foreach($allhours as $hours){
        if($i<$limit){
            
        // echo $hours['theHours'];
        $count=$count+$hours['thecount'];
        $hours_counted++;
        }
      
       $i++;
    }
    return round(($count/$hours_counted),2); 
    
}



function get_all_machine_summary($db){
    $filter='Where 1=1 ';
    $filter=$filter." AND machineevent_type='Cycle Start'";
    
        $timetag_start=strtotime($_POST['date_filter_start'].' '.$_POST['time_filter_start'].'');
        $timetag_end=strtotime($_POST['date_filter_end'].' '.$_POST['time_filter_end'].'');
        $filter=$filter." AND machineevent_timetag>='".$timetag_start."'";
        $filter=$filter." AND machineevent_timetag<='".$timetag_end."'";
    if($_POST['machine_list']=='All Machine'){
        $filter=$filter.'or ( machineevent_type is null)';
    }

    
    
    $query='SELECT  max(machineevent_timetag)as lastscan,count([machineevent_timetag]) as thecount ,machine_name,machine_workarea,numberofsensor
    FROM machine
   
    left join machine_allocation on machine_id=machineallocation_machineid
    left join machine_event on 
    (  machineevent_MAC_adress=machineallocation_MAC 
   and machineallocation_timetag_start<machineevent_timetag 
    and machineallocation_timetag_end>machineevent_timetag )
    left join (SELECT [machinepin_machineid]
        
        ,count([machinepin_pindescription]) as numberofsensor
        
    FROM [barcode].[dbo].[machine_pin]
    group by machinepin_machineid) as a on a.machinepin_machineid=machine_id
    '.$filter.'
     group by machine_name,machine_workarea,numberofsensor
    order by machine_workarea asc,machine_name asc
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}

function get_all_machine($db){
    $query='SELECT Distinct machine_name,machine_id,machine_workarea
    FROM machine
   
    left join machine_allocation on machine_id=machineallocation_machineid
   
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}

function get_all_device_summary($db){
    $filter='Where 1=1 ';
    $filter2='Where 1=1 ';
    //$filter=$filter." AND machineevent_type='Cycle Start'";
    
    $timetag_start=strtotime($_POST['date_filter_start'].' '.$_POST['time_filter_start'].'');
    $timetag_end=strtotime($_POST['date_filter_end'].' '.$_POST['time_filter_end'].'');
    $filter=$filter." AND temptable_timetag>='".$timetag_start."'";
    $filter=$filter." AND temptable_timetag<='".$timetag_end."'";
    $filter2=$filter2." AND (machineallocation_timetag_start>='".$timetag_start."'";
    $filter2=$filter2." OR machineallocation_timetag_end<='".$timetag_end."')";

    if(!empty($_POST['MAC'])){
        $filter=$filter." AND temptable_MAC='".$_POST['MAC']."'";
    }
    $query='(SELECT  max(temptable_timetag)as lastscan,min(temptable_timetag)as firstscan,count(temptable_id) as thecount ,
    temptable_MAC,machine_name,machineallocation_timetag_start,machineallocation_timetag_end,machineallocation_MAC,coalesce(temptable_MAC, machineallocation_MAC)
   from temptable
   left join machine_allocation on (  temptable_MAC=machineallocation_MAC 
   and machineallocation_timetag_start<temptable_timetag 
    and machineallocation_timetag_end>temptable_timetag )
   left join machine on machineallocation_machineid=machine_id

    '.$filter.'
     group by temptable_MAC,machine_name,machineallocation_timetag_start,machineallocation_timetag_end,machineallocation_MAC
     union
    SELECT  max(temptable_MAC)as lastscan,min(temptable_timetag)as firstscan,count(temptable_id) as thecount ,
    temptable_MAC,machine_name,machineallocation_timetag_start,machineallocation_timetag_end,machineallocation_MAC,coalesce(temptable_MAC, machineallocation_MAC)
    from machine_allocation
    left join temptable on  (  temptable_MAC=machineallocation_MAC 
    and machineallocation_timetag_start<temptable_timetag 
    and machineallocation_timetag_end>temptable_timetag )
        
    left join machine on machineallocation_machineid=machine_id

    '.$filter2.'  AND temptable_timetag is null
        group by temptable_MAC,machine_name,machineallocation_timetag_start,machineallocation_timetag_end,machineallocation_MAC
    
    
    )order by coalesce(temptable_MAC, machineallocation_MAC),min(temptable_timetag) DESC
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}

function get_MAC_info($db,$MAC){
    $query='SELECT  max(temptable_timetag)as lastscan,min(temptable_timetag)as firstscan
   from temptable 
   WHERE temptable_MAC=\''.$MAC.'\'';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  return $row;
}

function get_machine_info($db,$machine_name){
    $query='SELECT  *
    from machine 
    WHERE machine_name=\''.$machine_name.'\'';//where machineevent_entry like \'%Door%\'
   $sql = $db->prepare($query); 
   //show($query);
   $sql->execute();
 
   $info=$sql->fetch();
   $query='SELECT  *
   from machine_pin 
   left join (
   SELECT  count( [machineevent_timetag]) as thecount,machine_id
     ,machineevent_type
  FROM [barcode].[dbo].[machine_event]
   left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
    and machineallocation_timetag_start<machineevent_timetag 
    and machineallocation_timetag_end>machineevent_timetag )
	left join machine on machine_id=machineallocation_machineid
  WHERE machine_id=\''.$info['machine_id'].'\'
  group by machine_id,machineevent_type

   ) as a on a.machineevent_type=machinepin_pindescription and a.machine_id=machinepin_machineid
   WHERE machinepin_machineid=\''.$info['machine_id'].'\'';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $info['sensors']=$sql->fetchall();
   return $info;
}

function get_all_event($db){
    $filter='Where 1=1 ';
    if(!empty($_POST['entry_type'])){
        $filter=$filter." AND machineevent_type='".$_POST['entry_type']."'";
    }
    if(!empty($_POST['machine_name'])){
        $filter=$filter." AND machine_name='".$_POST['machine_name']."'";
    }
    if(!empty($_POST['machine_name'])){
        $filter=$filter." AND machine_name='".$_POST['machine_name']."'";
    }
    
    $timetag_start=strtotime($_POST['date_filter_start'].' '.$_POST['time_filter_start'].'');
    $timetag_end=strtotime($_POST['date_filter_end'].' '.$_POST['time_filter_end'].'');
    $filter=$filter." AND machineevent_timetag>='".$timetag_start."'";
    $filter=$filter." AND machineevent_timetag<='".$timetag_end."'";
    
    $query='SELECT TOP 20 *, (machineevent_timetag_finished-machineevent_timetag) as duration
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
    and machineallocation_timetag_start<machineevent_timetag 
    and machineallocation_timetag_end>machineevent_timetag )
    left join machine on machine_id=machineallocation_machineid

    '.$filter.'
    order by machineevent_timetag desc
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}

function get_all_stats($db){
    $filter='Where 1=1 ';
    if(!empty($_POST['entry_type'])){
        $filter=$filter." AND machineevent_type='".$_POST['entry_type']."'";
    }
    if(!empty($_POST['machine_name'])){
        $filter=$filter." AND machine_name='".$_POST['machine_name']."'";
    }
    if(!empty($_POST['machine_name'])){
        $filter=$filter." AND machine_name='".$_POST['machine_name']."'";
    }
    
    $timetag_start=strtotime($_POST['date_filter_start'].' '.$_POST['time_filter_start'].'');
    $timetag_end=strtotime($_POST['date_filter_end'].' '.$_POST['time_filter_end'].'');
    $filter=$filter." AND machineevent_timetag>='".$timetag_start."'";
    $filter=$filter." AND machineevent_timetag<='".$timetag_end."'";
    
    $query='SELECT  min(machineevent_timetag) as min_timetag,max(machineevent_timetag) as max_timetag,count(machineevent_timetag)as thecount, AVG(machineevent_timetag_finished-machineevent_timetag) as average_duration
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
    and machineallocation_timetag_start<machineevent_timetag 
    and machineallocation_timetag_end>machineevent_timetag )
    left join machine on machine_id=machineallocation_machineid

    '.$filter.'
    
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  $return['thecount']=$row['thecount'];
  $return['average_duration']=$row['average_duration'];
  $return['min_timetag']=$row['min_timetag'];
  $return['max_timetag']=$row['max_timetag'];

  $query='SELECT
  (SELECT MAX(duration) FROM(SELECT  TOP 50 PERCENT (machineevent_timetag_finished-machineevent_timetag) as duration
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
    and machineallocation_timetag_start<machineevent_timetag 
    and machineallocation_timetag_end>machineevent_timetag )
    left join machine on machine_id=machineallocation_machineid

    '.$filter.'
    ORDER BY duration ASC)as BottomHalf)
    +
    (SELECT MIN(duration) FROM(SELECT  TOP 50 PERCENT (machineevent_timetag_finished-machineevent_timetag) as duration
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
    and machineallocation_timetag_start<machineevent_timetag 
    and machineallocation_timetag_end>machineevent_timetag )
    left join machine on machine_id=machineallocation_machineid

    '.$filter.'
    ORDER BY duration DESC)as TopHalf)/2 As Median
    
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  $return['median_duration']=$row['Median'];


    //$timetag_end-$timetag_start;
    $query='SELECT  floor(('.$timetag_end.'-machineevent_timetag)/3600) as theHours,min(machineevent_timetag) as min_timetag,max(machineevent_timetag) as max_timetag,count(machineevent_timetag)as thecount, AVG(machineevent_timetag_finished-machineevent_timetag) as average_duration
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
    and machineallocation_timetag_start<machineevent_timetag 
    and machineallocation_timetag_end>machineevent_timetag )
    left join machine on machine_id=machineallocation_machineid

    '.$filter.'
    group by floor(('.$timetag_end.'-machineevent_timetag)/3600)
    order by floor(('.$timetag_end.'-machineevent_timetag)/3600) ASC


    ';//where machineevent_entry like \'%Door%\'
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $row=$sql->fetchall();
    foreach($row as $hours){
        $return['all_hours'][$hours['theHours']]['thecount']=$hours['thecount'];
        $return['all_hours'][$hours['theHours']]['theHours']=$hours['theHours'];
        $return['all_hours'][$hours['theHours']]['thedate']=date('Y-m-d',$hours['min_timetag']);
    }

    




  return $return;
}

function get_hours_trend($db,$machine){
    $filter='Where 1=1 ';
    
    $filter=$filter." AND machineevent_type='Cycle Start'";
    
    $filter=$filter." AND machine_name='".$machine."'";
    
    $timetag_start=strtotime($_POST['date_filter_start'].' '.$_POST['time_filter_start'].'');
    $timetag_end=strtotime($_POST['date_filter_end'].' '.$_POST['time_filter_end'].'');
    $filter=$filter." AND machineevent_timetag>='".$timetag_start."'";
    $filter=$filter." AND machineevent_timetag<='".$timetag_end."'";
    
    
    $query='SELECT  floor(('.$timetag_end.'-machineevent_timetag)/3600) as theHours,min(machineevent_timetag) as min_timetag,max(machineevent_timetag) as max_timetag,count(machineevent_timetag)as thecount, AVG(machineevent_timetag_finished-machineevent_timetag) as average_duration
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
    and machineallocation_timetag_start<machineevent_timetag 
    and machineallocation_timetag_end>machineevent_timetag )
    left join machine on machine_id=machineallocation_machineid

    '.$filter.'
    group by floor(('.$timetag_end.'-machineevent_timetag)/3600)
    order by floor(('.$timetag_end.'-machineevent_timetag)/3600) ASC


    ';//where machineevent_entry like \'%Door%\'
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $row=$sql->fetchall();
    foreach($row as $hours){
        $return['all_hours'][$hours['theHours']]['thecount']=$hours['thecount'];
        $return['all_hours'][$hours['theHours']]['theHours']=$hours['theHours'];
        $return['all_hours'][$hours['theHours']]['thedate']=date('Y-m-d',$hours['min_timetag']);
    }

    




  return $return;
}

function get_all_type_event($db){
    $filter='Where 1=1 ';
    if(!empty($_POST['machine_name'])){
        $filter=$filter." AND machine_name='".$_POST['machine_name']."'";
    }
    
    $query='SELECT distinct machineevent_type
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
    and machineallocation_timetag_start<machineevent_timetag 
    and machineallocation_timetag_end>machineevent_timetag )
    left join machine on machine_id=machineallocation_machineid
    
    '.$filter.'
    order by machineevent_type desc
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}

function get_all_machine_event($db){
    $query='SELECT distinct machine_name
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
    and machineallocation_timetag_start<machineevent_timetag 
    and machineallocation_timetag_end>machineevent_timetag )
    left join machine on machine_id=machineallocation_machineid
    order by machine_name desc
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}

function get_all_MAC($db,$machinename){
    $query='SELECT distinct machineallocation_MAC
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
    and machineallocation_timetag_start<machineevent_timetag 
    and machineallocation_timetag_end>machineevent_timetag )
    left join machine on machine_id=machineallocation_machineid
    where machine_name=\''.$_POST['machine_name'].'\'
    order by machineallocation_MAC desc
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}

function get_all_temptable($db){
    $query= "delete
    FROM [barcode].[dbo].[temptable]
    where temptable_entry like'%Data%'";
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();
  
   
   
   $query='SELECT TOP 1000 *
	  FROM temptable
      left join machine_allocation on machineallocation_MAC=temptable_MAC
      left join machine on machine_id=machineallocation_machineid
      order by temptable_timetag desc,temptable_id desc
	  
	  
	';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
    return $row;
}

function get_last_temptable($db){
    $query= "delete
    FROM [barcode].[dbo].[temptable]
    where temptable_entry like'%Data%'";
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();
  
  $query='SELECT temptable_MAC, max(temptable_timetag) as lasttimetag
    FROM temptable
    group by temptable_MAC
    order by max(temptable_timetag) desc
    
    
    ';
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $allMAC=$sql->fetchall();
    foreach($allMAC as $MAC){
        $query='SELECT TOP 1 *
        FROM temptable
        left join machine_allocation on 
      (  temptable_MAC=machineallocation_MAC 
      and machineallocation_timetag_start<temptable_timetag 
      and machineallocation_timetag_end>temptable_timetag )
      left join machine on machine_id=machineallocation_machineid
      where temptable_MAC=\''.$MAC['temptable_MAC'].'\'
        order by temptable_timetag desc
        
        
      ';
      $sql = $db->prepare($query); 
      //show($query);
      $sql->execute();
  
      $return[]=$sql->fetch();
      
    }
    return $return;
   
}

function get_pins_machine($db,$machine_id){
    $query='SELECT  *
	  FROM machine
      left join machine_pin on machine_id=machinepin_machineid
      WHERE machine_id=\''.$machine_id.'\'
     ';//left join machine on machine_id=machineallocation_machineid
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
    return $row;
}

function get_last_event($db,$MAC,$type){
    $query='SELECT  TOP 1 *
        FROM machine_event
        
        WHERE machineevent_MAC_adress=\''.$MAC.'\' and machineevent_type=\''.$type.'\' and machineevent_finished is null
        order by machineevent_timetag DESC
    ';//left join machine on machine_id=machineallocation_machineid
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetch();
    return $row;
}




function allocate_machine_MAC($db){
    
    $timetag_start=strtotime($_POST['date_allocation_start'].' '.$_POST['time_allocation_start'].'');
    $timetag_end=strtotime($_POST['date_allocation_end'].' '.$_POST['time_allocation_end'].'');
    

    //check if machine not already allocated 
    if(check_machine_allocated($db,$_POST['machine_to_allocated'],$timetag_start,$timetag_end)==false){
        //if not, allocate the machine
       
        $query="INSERT INTO dbo.machine_allocation
        ( machineallocation_MAC,
        machineallocation_machineid,
        machineallocation_timetag_start,
        machineallocation_timetag_end
        ) 
        VALUES (
        '".$_POST['MAC_to_allocated']."',    
        '".$_POST['machine_to_allocated']."',
        '".$timetag_start."',
        '".$timetag_end."')";	

        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();

    }else{
        //else show message error and come back to the allocation windows
        $_POST['MAC']=$_POST['MAC_to_allocated'];
        $_POST['view']=$_POST['Allocation Machine'];
    }
    

    
}

function import_temptable($db){
    $query='SELECT TOP 1000 *
	  FROM temptable
      left join machine_allocation on 
        (  temptable_MAC=machineallocation_MAC 
        and machineallocation_timetag_start<temptable_timetag 
        and machineallocation_timetag_end>temptable_timetag )
      left join machine on machine_id=machineallocation_machineid
      where temptable_imported is null and machine_id is not null
      order by temptable_timetag ASC,temptable_id ASC
      
	  
	  
	';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$alldata=$sql->fetchall();
    //show($alldata);
    foreach($alldata as $import_line){
        //show($import_line);
        $pins=explode(";",$import_line['temptable_entry']);

        foreach(get_pins_machine($db,$import_line['machine_id']) as $pinobject){
               
                $pinobject['value']=$pins[$pinobject['machinepin_pinnumber']];
                $pinobject['change']=$pins[($pinobject['machinepin_pinnumber']+8)];
                $pinobject['MAC']=$import_line['temptable_MAC'];
                $pinobject['timetag']=$import_line['temptable_timetag'];
                $pinobject['temptable_id']=$import_line['temptable_id'];
                //show($pinobject);

                if ($pinobject['machinepin_triggerpull']==1){ // if it is a trigger exemple start button
                    if ($pinobject['change']==1 and $pinobject['value']==1){
                        //create or stop event
                        create_stop_event($db,$pinobject);
                    }else{
                        //nothing happen
                    }
                }else{  // if it is a trigger a pull exemple door open or power on
                    if ($pinobject['change']==1){
                        //create or stop event
                        create_stop_event($db,$pinobject);
                    }elseif ($pinobject['value']==1){
                        //increase the time of the last status for that event
                        update_time_event($db,$pinobject);
                    }
                }
        }
        update_row_temptable_imported($db,$pinobject['temptable_id']);
    }
    
}

function re_import_all_event($db,$mac){
    //delete all event
        $query='Delete
        FROM machine_event
        where machineevent_MAC_adress=\''.$mac.'\'';
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
    // update all temptable -> not imported
        $query='UPDATE dbo.temptable SET 
        temptable_imported=NULL
            
        WHERE temptable_MAC=\''.$mac.'\'';
        
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
}

function clean_data($db){
    $query='SELECT count([temptable_id]) as thecount
    ,[temptable_timetag]
    
    ,[temptable_MAC]
    
    FROM [barcode].[dbo].[temptable]

    group by [temptable_MAC],[temptable_timetag]
    order by thecount desc';
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $alldata=$sql->fetchall();

    $i=0;
    foreach($alldata as $data){
        if($data['thecount']>1){
            $tobeclean[$i]['temptable_timetag']=$data['temptable_timetag'];//
            $tobeclean[$i]['temptable_MAC']=$data['temptable_MAC'];
            $i++;
        }
    }
    //show($tobeclean);
    foreach($tobeclean as $entry){
        $query=' SELECT  temptable_id
        FROM    (
                SELECT  *, ROW_NUMBER() OVER (ORDER BY temptable_id) AS rn
                FROM    temptable
                 where temptable_timetag=\''.$entry['temptable_timetag'].'\' and temptable_MAC=\''.$entry['temptable_MAC'].'\'
                ) q
        WHERE   rn > 1
        ORDER BY temptable_id';
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        $tobedeleteds=$sql->fetchall();
        foreach($tobedeleteds as $temp){
            $tobedeleted[]=$temp['temptable_id'];
        }
    }
    //show($tobedeleted);
    $filter=' WHERE 0=1 ';
    foreach($tobedeleted as $temp){
        $filter=$filter.' OR temptable_id=\''.$temp.'\'';
    }
    $query=' DELETE FROM temptable'.$filter;
    $sql = $db->prepare($query); 
    show($query);
    //$sql->execute();

   
}

function check_machine_allocated($db,$machine,$timetag_start,$timetag_end){
    $query='SELECT distinct machineallocation_MAC
    FROM machine_allocation
    
    left join machine on machine_id=machineallocation_machineid
    where machine_id=\''.$_POST['machine_to_allocated'].'\' 
    and (
        (machineallocation_timetag_start<\''.$timetag_end.'\' 
        and machineallocation_timetag_end>\''.$timetag_end.'\'
        )
    or 
        (machineallocation_timetag_start<\''.$timetag_start.'\'
        and machineallocation_timetag_end>\''.$timetag_start.'\'
        )
    or 
        (machineallocation_timetag_start>\''.$timetag_start.'\'
        and machineallocation_timetag_end<\''.$timetag_end.'\'
        )
    )
    
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  if(empty($row)){
      return false;
  }else{
    return true;
  }
 
}





function is_there_duration($db,$type,$machinename){
    $query='SELECT  machinepin_triggerpull
    FROM machine
    left join machine_pin on machine_id=machinepin_machineid
    WHERE machine_name=\''.$machinename.'\' and machinepin_pindescription=\''.$type.'\'
   ';//left join machine on machine_id=machineallocation_machineid
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  
  if($row['machinepin_triggerpull']=='1'){$return='no_duration';}else{$return='duration';}
  return $return;
}

function create_stop_event($db,$pinobject){
    if($pinobject['machinepin_triggerpull']==1 and $pinobject['value']==1 ){
        //show('create trigger event');
        $entry=$pinobject['machinepin_pindescription']." pushed";
        $query="INSERT INTO dbo.machine_event
        ( machineevent_timetag,
        machineevent_timetag_import,
        machineevent_MAC_adress,
        machineevent_type,
        machineevent_entry
        ) 
        VALUES (
        '".$pinobject['timetag']."',    
        '".time()."',
        '".$pinobject['MAC']."',
        '".$pinobject['machinepin_pindescription']."',
        '".$entry."')";	

        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        //update_row_temptable_imported($db,$pinobject['temptable_id']);
        $import='ok';
    }elseif( $pinobject['value']==1 ){
        //show('create pull event');
        $entry=$pinobject['machinepin_pindescription']." started";
        $query="INSERT INTO dbo.machine_event
        ( machineevent_timetag,
        machineevent_timetag_import,
        machineevent_MAC_adress,
        machineevent_type,
        machineevent_entry
        ) 
        VALUES (
        '".$pinobject['timetag']."',    
        '".time()."',
        '".$pinobject['MAC']."',
        '".$pinobject['machinepin_pindescription']."',
        '".$entry."')";	

        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        //update_row_temptable_imported($db,$pinobject['temptable_id']);
    }else{
        //show('stop pull event');
        $lastevent=get_last_event($db,$pinobject['MAC'],$pinobject['machinepin_pindescription']);

        $query='UPDATE dbo.machine_event SET 
        machineevent_finished=\'1\',
        machineevent_timetag_finished=\''.$pinobject['timetag'].'\'
            
        WHERE machineevent_MAC_adress=\''.$pinobject['MAC'].'\' 
        and machineevent_type=\''.$pinobject['machinepin_pindescription'].'\' 
        and machineevent_timetag=\''.$lastevent['machineevent_timetag'].'\'  ' ;
        
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
       //update_row_temptable_imported($db,$pinobject['temptable_id']);
    }
}

function update_time_event($db,$pinobject){
    //show('update event');
    $lastevent=get_last_event($db,$pinobject['MAC'],$pinobject['machinepin_pindescription']);

        $query='UPDATE dbo.machine_event SET 
        
        machineevent_timetag_finished=\''.$pinobject['timetag'].'\'
            
        WHERE machineevent_MAC_adress=\''.$pinobject['MAC'].'\' 
        and machineevent_type=\''.$pinobject['machinepin_pindescription'].'\' 
        and machineevent_timetag=\''.$lastevent['machineevent_timetag'].'\'  ' ;
        
        $sql = $db->prepare($query); 
        //show($query);
       $sql->execute();
       //update_row_temptable_imported($db,$pinobject['temptable_id']);
}

function update_row_temptable_imported($db,$rowid){
    $query='UPDATE dbo.temptable SET 
    temptable_imported=\'1\'
        
    WHERE temptable_id=\''.$rowid.'\'';
    
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

}

function show_time($secondes){
    if($secondes==0){
        $return='-';
    }elseif($secondes<60){
        $return=$secondes.' sec';
    }elseif($secondes<3600){
        $minutes=floor($secondes/60);
        $secondes=$secondes-$minutes*60;
        $return=$minutes.' min '.$secondes.' sec';
    }elseif($secondes<(3600*24)){
        $hours=floor($secondes/3600);
        $secondes=$secondes-$hours*3600;
        $minutes=floor($secondes/60);
        $secondes=$secondes-$minutes*60;
        $return=$hours.' h '.$minutes.' min '.$secondes.' sec';
    }

    return $return;
}


function refresh_div($divid,$milliseconds){
    echo'<script> 
        $(document).ready(function(){
        setInterval(function(){
                $("#'.$divid.'").load(window.location.href + " #'.$divid.'" );
        }, '.$milliseconds.');
        });
        </script>';
}






?>