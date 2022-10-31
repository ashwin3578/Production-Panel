<?php
load_role($db,$_SESSION['temp']['id']);

function manage_post_injury($db){
   //show($_POST);
   if(($_POST['attachment']=='delete_document')){
        //show($_FILES);
        delete_document($db,$_POST['attachment_number']);
        ajax_load([['injuryreport_number',"'".$_POST['injuryreport_number']."'"],['dialog',"'followup'"]],'injury-ajax.php','dialog-box');  
        ajax_load([['injuryreport_number',"'".$_POST['injuryreport_number']."'"],['followup',"'show'"]],'injury-ajax.php','report-box');
    }

    if(!empty($_GET['debug'])){
        $_SESSION['temp']['debug']=$_GET['debug'];
    }
    if(!empty($_POST['Start_new'])){
        initiate_report($db);
        $_POST['dialog']='initial';
        
    }
    if(!empty($_POST['add_injury'])){
        update_report($db);
        $_POST['editing']=1;
    }
    if(!empty($_POST['Add_details1'])){
        update_report($db);
        $_POST['editing']=1;
    }
    if(!empty($_POST['view'])){
       if($_POST['view']=='View All Details'){
           $_POST['restricted']='yes';
       }
    }

    if(!empty($_POST['add_notes']) and !empty($_POST['notes_to_add'])){
        add_notes($db);
        ajax_load([['injuryreport_number',"'".$_POST['injuryreport_number']."'"],['dialog',"'notes'"]],'injury-ajax.php','dialog-box');  
        ajax_load([['injuryreport_number',"'".$_POST['injuryreport_number']."'"],['notes',"'show'"]],'injury-ajax.php','report-box');
     }
     if(!empty($_POST['remove_notes']) ){
        remove_injurynotes($db);
        ajax_load([['injuryreport_number',"'".$_POST['injuryreport_number']."'"],['dialog',"'notes'"]],'injury-ajax.php','dialog-box');  
        ajax_load([['injuryreport_number',"'".$_POST['injuryreport_number']."'"],['notes',"'show'"]],'injury-ajax.php','report-box');
     }

    if(!empty($_POST['save_report'])){
        update_report($db);
        ajax_load([['injuryreport_number',"'".$_POST['injuryreport_number']."'"],['dialog',"'edit'"]],'injury-ajax.php','dialog-box');  
        ajax_load([['injuryreport_number',"'".$_POST['injuryreport_number']."'"],['edit_injury',"'show'"]],'injury-ajax.php','report-box');
     }
     if(!empty($_POST['save_investigation'])){
        update_investigation($db);
        change_investigation_status($db,'In-Progress');
        ajax_load([['injuryreport_number',"'".$_POST['injuryreport_number']."'"],['dialog',"'investigation'"]],'injury-ajax.php','dialog-box');  
        ajax_load([['injuryreport_number',"'".$_POST['injuryreport_number']."'"],['investigation',"'show'"]],'injury-ajax.php','report-box');
     }
     if(!empty($_POST['save_followup'])){
        update_followup($db);
        change_followup_status($db,'In-Progress');
        ajax_load([['injuryreport_number',"'".$_POST['injuryreport_number']."'"],['dialog',"'followup'"]],'injury-ajax.php','dialog-box');  
        ajax_load([['injuryreport_number',"'".$_POST['injuryreport_number']."'"],['followup',"'show'"]],'injury-ajax.php','report-box');
     }
    if(!empty($_POST['delete'])){
        delete_report($db);
        $_POST=array();
        $_POST['dialog']='initial';
        
    }
    //show($_POST['injurytype']);
   if(!empty($_POST['injurytype'])){
    check_if_body_part_needed($db);
    check_if_left_right_needed($db);
   }

    if(!empty($_POST['dialog'])){
        dialog_box($db);
    }
    if(!empty($_POST['show_injury'])){
        show_report_injury($db,$_POST['injuryreport_number']);
    }
    if(!empty($_POST['edit_injury'])){
        edit_report_injury($db,$_POST['injuryreport_number']);
    }

    if(!empty($_POST['submit_injury'])){
        change_report_status($db,'Opened');
        show_report_injury($db,$_POST['injuryreport_number']);
        send_email_injury_notification($_POST['injuryreport_number']);
    }

    if(!empty($_POST['submit_investigation'])){
        close_investigation($db,$_POST['injuryreport_number']);
        show_investigation($db,$_POST['injuryreport_number']);
    }
    if(!empty($_POST['submit_followup'])){
        close_followup($db,$_POST['injuryreport_number']);
        show_followup($db,$_POST['injuryreport_number']);
    }

    if(!empty($_POST['unsubmit_injury'])){
       change_report_status($db,'Created');
       show_report_injury($db,$_POST['injuryreport_number']);
    }
    if(!empty($_POST['unsubmit_investigation'])){
        change_investigation_status($db,'In-Progress');
        show_investigation($db,$_POST['injuryreport_number']);
    }
    if(!empty($_POST['unsubmit_followup'])){
        change_followup_status($db,'In-Progress');
        show_followup($db,$_POST['injuryreport_number']);
    }

    if(!empty($_POST['investigation'])){
       show_investigation($db,$_POST['injuryreport_number']);
    }
    if(!empty($_POST['edit_investigation'])){
        edit_investigation($db,$_POST['injuryreport_number']);
    }
    
    if(!empty($_POST['followup'])){
        show_followup($db,$_POST['injuryreport_number']);
    }
    if(!empty($_POST['edit_followup'])){
        edit_followup($db,$_POST['injuryreport_number']);
    }

    if(!empty($_POST['notes'])){
        show_notes($db,$_POST['injuryreport_number']);
    }

    if(($_POST['attachment']=='add_document')){
        show_window_add_document($db,$_POST['injuryreport_number']);
    }

    if(($_POST['add_attachment']=='yes')){
        //show($_FILES);
        upload_document($db);
        ajax_load([['injuryreport_number',"'".$_POST['injuryreport_number']."'"],['dialog',"'followup'"]],'injury-ajax.php','dialog-box');  
        ajax_load([['injuryreport_number',"'".$_POST['injuryreport_number']."'"],['followup',"'show'"]],'injury-ajax.php','report-box');
    }
    
   

    if($_POST['type']=='ListOfInjuries'){
        view_all_report($db);
        ajax_load(array(),'injury-ajax.php','dashboard-box'); 
    }
    if($_POST['type']=='ShowIssues'){
        view_all_report($db,$option="only_issue");
        ajax_load(array(),'injury-ajax.php','dashboard-box'); 
    }
    if($_SESSION['temp']['debug']=='1'){
        show($_POST);
    }
    if($_POST['type']=='Dashboard'){
        show_dasboard_injury_v2();
        ajax_load(array(),'injury-ajax.php','dialog-box');  
        ajax_load(array(),'injury-ajax.php','report-box');
    }


    //if need add or remove filter
    if(!empty($_POST['manage_filter'])or !empty($_POST['remove_filter'])){
        manage_POST_filtering();
    }
    //save the new time period
    if(!empty($_POST['change_time_period'])){
        $_SESSION['temp']['injury']['time_period']=$_POST['time_period'];
    }
    if(empty($_SESSION['temp']['injury']['time_period'])){
        $_SESSION['temp']['injury']['time_period']='month';
    }
    //save the new time period
    if(!empty($_POST['change_cat'])){
        $_SESSION['temp']['injury']['cat']=$_POST['cat'];
    }
    if(empty($_SESSION['temp']['injury']['cat'])){
        $_SESSION['temp']['injury']['cat']='quantity';
    }
    


    if($_POST['type']=='show_navbar'){
        navbar_injury($db);
    }else{
        ajax_load([['type',"'show_navbar'"]],'injury-ajax.php','navbar_injury_container');
    }
    
    
}

function general_view($db){
   
    
   echo'<div class="row ">';
       
        echo'<div class="col-md-7 col-lg-5 report-box">';
        if(empty($_POST)){
            ajax_load([['type',"'ListOfInjuries'"]],'injury-ajax.php','report-box');
        }
         
        
        echo'</div>';
        echo'<div class="col-sm-4 dialog-box" >';
        //dialog_box($db);
        
        
        echo'</div>';
    echo'</div>';
    echo'<div class="col-sm-4 hidden-box" >';
        //dialog_box($db);
        
        
    echo'</div>';
    
}

function navbar_injury($db){?>

   
    <div class="row navbar navbar_injury">
        <form method="POST">
            <div class="col-sm-2 ">
                <?php if(!empty($_SESSION['temp']['id'])){?>
                    <div id="create_new" 
                    class="btn btn-primary injury_button">New Report</div>
                <?php }?>
                
                <?php ajax_button_v2('create_new',[['Start_new',"'show'"]],'injury-ajax.php','dialog-box');?>
            </div>
            <div class="col-sm-2 ">
                <div 
                    id="show_list" 
                    name="type" 
                    value="type"  
                    class="btn btn-primary injury_button" 
                    >All Reports</div>
                <?php ajax_button_v2('show_list',[['type',"'ListOfInjuries'"]],'injury-ajax.php','report-box');?>
            </div>
            <!-- //Show a button if they are reports with hasn't been filled properly and submitted -->
            <?php $count_report_with_issue=count(get_report_opened_but_not_submited());
            if($count_report_with_issue>0){?>
                <div class="col-sm-2 ">
                    <div 
                        id="show_issue" 
                        type="submit" 
                        name="type"
                        class="btn btn-danger injury_button" 
                        ><?php echo $count_report_with_issue?> Reports to be checked</div>
                    <?php ajax_button_v2('show_issue',[['type',"'ShowIssues'"]],'injury-ajax.php','report-box');?>
                </div>
            <?php }else{?>
                <div class="col-sm-2 "></div>
            <?php }
            ?>
            <div class="col-sm-6 " >
            
            
            </div>
            <div class="col-sm-2 ">
                <div 
                    id="show_dashboard" 
                    type="submit" 
                    name="type" 
                    value="Dashboard"  
                    class="btn btn-primary injury_button" 
                    >Dashboard</div>
                <?php ajax_button_v2('show_dashboard',[['type',"'Dashboard'"]],'injury-ajax.php','dashboard-box');?>
            </div>
        </form>
    </div>
    <?php
}

function general_view_report($db){
    echo'<div class="row dashboard-box">';
    if($_POST['action']=='show_dashboard'){
        show_dasboard_injury_v2();
    }
       
    echo'</div>';
    
    echo'<div class="row ">';
       
        echo'<div class="col-md-10 col-lg-10 report-box">';
        if(!empty($_POST['injuryreport_number'])){
           // show_report_injury($db,$_POST['injuryreport_number']);
        }
        
        echo'</div>';
        echo'<div class="col-sm-2 dialog-box" >';
        //dialog_box($db);
        if(empty($_POST)){
            ajax_load(array(),'injury-ajax.php','dialog-box');
        }
        
        echo'</div>';
    echo'</div>';
    echo'<div class="col-sm-4 hidden-box" style="display:none;" >';
        //dialog_box($db);
        
        
    echo'</div>';
    
}
function navbar_list($db){
    echo'<div class="row navbar_report_list">';
        echo'<div class="col-sm-8">';
            echo'<div class="col-sm-4">';
            echo'<div id="show_dashboard" type="submit" name="type" value="Dashboard"  class="btn btn-primary injury_button" >Report Number</div>';
            echo'</div>';
            echo'<div class="col-sm-4">';
            echo'<div id="show_dashboard" type="submit" name="type" value="Dashboard"  class="btn btn-primary injury_button" >Name</div>';
            echo'</div>';
            echo'<div class="col-sm-4">';
            echo'<div id="show_dashboard" type="submit" name="type" value="Dashboard"  class="btn btn-primary injury_button" >Date</div>';
            echo'</div>';
        echo'</div>';
        echo'<div class="col-sm-4">';
            echo'<div class="col-sm-4">';
            echo'<div id="show_dashboard" type="submit" name="type" value="Dashboard"  class="btn btn-primary injury_button" >Report</div>';
            echo'</div>';
            echo'<div class="col-sm-4">';
            echo'<div id="show_dashboard" type="submit" name="type" value="Dashboard"  class="btn btn-primary injury_button" >Investigation</div>';
            echo'</div>';
            echo'<div class="col-sm-4">';
            echo'<div id="show_dashboard" type="submit" name="type" value="Dashboard"  class="btn btn-primary injury_button" >Followup</div>';
            echo'</div>';
        echo'</div>';
    echo'</div>';
}
function view_all_report($db,$option1=''){
    ajax_load(array(),'injury-ajax.php','dashboard-box'); 
    //navbar_list($db);get_report_opened_but_not_submited()
    if($option1==''){
        $all_report=get_all_injury_report($db);
    }else{
        $all_report=get_report_opened_but_not_submited();
    }
    
    foreach($all_report as $injuryreport){
        $can_see=0;
        if(!empty($_SESSION['temp']['role_injury_viewall'])){
            $can_see=1;
        }
        if($injuryreport['injuryreport_openby']==$_SESSION['temp']['id']){
            $can_see=1;
        }
        ?>
        <div class="row report_list">
            <div class="col-sm-8">
                <div class="btn btn-primary injury_button" <?php
                if($can_see==1){?>
                    onClick="thenumber='<?php echo $injuryreport['injuryreport_number']?>';show_injury();show_menu();"
                <?php }?>
                >
                    <div class="col-sm-4"><?php echo $injuryreport['injuryreport_number']?></div>
                    <div class="col-sm-4 <?php if($can_see==0){ echo' text_blur';}?>
                    "><?php if(!empty($injuryreport['injuryreport_name'])){ echo$injuryreport['injuryreport_name'];}?>
                    </div>
                    <div class="col-sm-4">
                    <?php if(!empty($injuryreport['injuryreport_timetag_incident'])){
                        echo date('jS M Y',$injuryreport['injuryreport_timetag_incident']).' at '.date('G:i',$injuryreport['injuryreport_timetag_incident']);
                    }?>
                    </div>
                </div>
                
            </div><?php
            echo'<div class="col-sm-1">';
                if($injuryreport['injuryreport_status']=='Created' and (!empty($_SESSION['temp']['role_injury_viewall']) or $_SESSION['temp']['id']== $injuryreport['injuryreport_openby'])){
                    echo'<div class="btn btn-primary injury_button" onClick="thenumber=\''.$injuryreport['injuryreport_number'].'\';delete_injury();refreshpage();">X</div>';
                }
            echo'</div>';
            echo'<div class="col-sm-3">';
                echo'<div class=" progress ">';
                    if($injuryreport['injuryreport_status']=='Created'){
                        echo '<div class="progress-bar progress-bar-striped created" role="progressbar" style="width: 10%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">Report</div>';
                    }elseif($injuryreport['injuryreport_status']=='Opened'){
                        echo '<div class="progress-bar progress-bar-striped closed" role="progressbar" style="width: 25%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">Report</div>';
                    }
                    if($injuryreport['injuryreport_status']=='Closed'){
                        echo '<div class="progress-bar progress-bar-striped closed" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Report Closed</div>';
                    }else{
                        if($injuryreport['injuryreport_investigation_status']=='Required'){
                            echo '<div class="progress-bar progress-bar-striped created" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">Invest.</div>';
                        }elseif($injuryreport['injuryreport_investigation_status']=='In-Progress'){
                            echo '<div class="progress-bar progress-bar-striped opened" role="progressbar" style="width: 25%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">Invest.</div>';
                        }elseif($injuryreport['injuryreport_investigation_status']=='Closed'){
                            echo '<div class="progress-bar progress-bar-striped closed" role="progressbar" style="width: 30%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Invest.</div>';
                        }
                        if($injuryreport['injuryreport_followup_status']=='Required'){
                            echo '<div class="progress-bar progress-bar-striped created" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">HR</div>';
                        }elseif($injuryreport['injuryreport_followup_status']=='In-Progress'){
                            echo '<div class="progress-bar progress-bar-striped opened" role="progressbar" style="width: 30%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">HR</div>';
                        }elseif($injuryreport['injuryreport_followup_status']=='Closed'){
                            echo '<div class="progress-bar progress-bar-striped closed" role="progressbar" style="width: 45%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">HR</div>';
                        }
                    }
                echo'</div>';
            echo'</div>';
           
        echo'</div>';
        
       // echo'</form>';
        
    } 
    ajax_button('show_injury',[['injuryreport_number','thenumber'],['show_injury',"'show'"]],'injury-ajax.php','report-box');
    ajax_button('delete_injury',[['injuryreport_number','thenumber'],['delete',"'X'"]],'injury-ajax.php','report-box');
    ajax_button('show_menu',[['injuryreport_number','thenumber'],['dialog',"'show_injury'"]],'injury-ajax.php','dialog-box');
    ajax_button('refreshpage',[['type',"'ListOfInjuries'"]],'injury-ajax.php','report-box');
    ajax_load(array(),'injury-ajax.php','dialog-box');
   
    //dialog_box($db);
    show_div('create_new');
   
    
}


function show_report_injury($db,$report_number){
    
    $line_number=get_line_number($db);
    $entry=array();
    $entry=get_injury_report($db,$report_number);
    $allowed=$_SESSION['temp']['role_injury_viewall'];
    echo'<script>thenumber=\''.$entry['injuryreport_number'].'\'</script>';
    echo'<div class="row main-box report">';
        echo'<div class="row">';
            echo'<div class="col-sm-10 title-report">';
            echo $entry['injuryreport_number'];
            echo'</div>';
            echo'<div class="col-sm-2 title-report">';
                if($entry['injuryreport_status']=='Opened'){
                    echo '<img class="attachment" src="img/checked.png" width="30">';
                }
            echo'</div>';
        echo'</div>';
        echo'<div class="row">';
            echo'<div class="col-sm-12 subtitle-report">';
            echo 'Raised on '. date('jS M Y',$entry['injuryreport_timetag_report']).' at '.date('G:i',$entry['injuryreport_timetag_report']).' by '.$entry['injuryreport_openby'];
            echo'</div>';
            
        echo'</div>';
        echo'<br><div class="row">';
            echo'<div class="col-lg-2 ">';
            echo'</div>';
            echo'<div class="col-md-12 col-lg-12">';
                if(!empty($entry['injuryreport_name'])){writeline('Name of injured/ill person',$entry['injuryreport_name'],'tobehidden');}
                if(!empty($entry['injuryreport_timetag_incident'])){writeline('Date & time injury/illness occurred',date('jS M Y',$entry['injuryreport_timetag_incident']).' at '.date('G:i',$entry['injuryreport_timetag_incident']));}
                if(!empty($entry['injuryreport_location'])){writeline('Where did the injury occur?',$entry['injuryreport_location']);}
                if(!empty($entry['injuryreport_injury1'])){writeline('Primary injury',$entry['injuryreport_injury1'],'tobehidden');}
                if(!empty($entry['injuryreport_injury2'])){writeline('Secondary injury',$entry['injuryreport_injury2'],'tobehidden');}
                if(!empty($entry['injuryreport_injury3'])){writeline('Secondary injury',$entry['injuryreport_injury3'],'tobehidden');}
                if(!empty($entry['injuryreport_injury4'])){writeline('Secondary injury',$entry['injuryreport_injury4'],'tobehidden');}
                if(!empty($entry['injuryreport_injury_description'])){writeline('Describe the injury',$entry['injuryreport_injury_description'],'tobehidden');}
                if(!empty($entry['injuryreport_treatment_give'])){writeline('Treatment given',$entry['injuryreport_treatment_give'],'tobehidden');}
                if(!empty($entry['injuryreport_first_aid_provider'])){writeline('Who provide first aid',$entry['injuryreport_first_aid_provider']);}
                if(!empty($entry['injuryreport_further_treament_req'])){writeline('Further medical treatment required',$entry['injuryreport_further_treament_req'],'tobehidden');}
                if(!empty($entry['injuryreport_supervisor_advised'])){writeline('Supervisor/leading hand/manager advised',$entry['injuryreport_supervisor_advised']);}
                if(!empty($entry['injuryreport_how_happen'])){writeline('How did the injury occur',$entry['injuryreport_how_happen']);}
                if(!empty($entry['injuryreport_plant_damaged'])){writeline('Has any plant/equipment been damaged',$entry['injuryreport_plant_damaged']);}
                if(!empty($entry['injuryreport_witnesses'])){writeline('Were they any witnesses to the injury',$entry['injuryreport_witnesses']);}
                if(!empty($entry['injuryreport_comments'])){writeline('Additional Comments',$entry['injuryreport_comments'],'tobehidden');}
                if(!empty($entry['injuryrepsdfort_name'])){writeline('Document Linked',$entry['injuryrepsdfort_name']);}
                if(!empty($entry['injuryrepsdfort_name'])){writeline('Photos',$entry['injuryrepsdfort_name']);}
            echo'</div>';
        echo'</div>';

        
    echo'</div>';
    
    if($entry['injuryreport_status']=='Opened'){
        show_investigation_mini($db,$report_number);
        show_followup_mini($db,$report_number);
        //show_notes_mini($db,$report_number);
    }
    
    
    show_injury_log($db,$entry['injuryreport_number']);
    hide_div('create_new');

}

function show_report_injury_mini($db,$report_number){
    $line_number=get_line_number($db);
    $entry=array();
    $entry=get_injury_report($db,$report_number);
    echo'<script>thenumber=\''.$entry['injuryreport_number'].'\'</script>';
    echo'<div class="row main-box report">';
        echo'<div class="row" onclick="backtoreport();show_menu_backtoreport();">';
            echo'<div class="col-sm-10 title-report">';
            echo $entry['injuryreport_number'].' - '.$entry['injuryreport_name'].' '.date('jS M Y',$entry['injuryreport_timetag_incident']).' at '.date('G:i',$entry['injuryreport_timetag_incident']);
            echo'</div>';
            echo'<div class="col-sm-2 title-report">';
                if($entry['injuryreport_status']=='Opened'){
                    echo '<img class="attachment" src="img/checked.png" width="30">';
                }
            echo'</div>';
        echo'</div>';
    echo'</div>';
    
    ajax_button('backtoreport',[['injuryreport_number','thenumber'],['show_injury',"'show'"]],'injury-ajax.php','report-box');
    ajax_button('show_menu_backtoreport',[['injuryreport_number','thenumber'],['dialog',"'show_injury'"]],'injury-ajax.php','dialog-box');  
}

function show_investigation($db,$report_number){
    show_report_injury_mini($db,$report_number);
    $entry=get_injury_report($db,$report_number);
    echo'<div class="row main-box investigation" style="min-height:100px">';
        echo'<div class="row" >';
            echo'<div class="col-sm-10 title-report">Investigation - '.$entry['injuryreport_investigation_status'].'</div>';
        
            echo'<div class="col-sm-2 title-report">';
                    if($entry['injuryreport_investigation_status']=='Closed'){
                        echo '<img class="attachment" src="img/checked.png" width="30">';
                    }
            echo'</div>';
        echo'</div>';
        echo'<br><div class="row">';
            echo'<div class="col-lg-2 ">';
            echo'</div>';
            echo'<div class="col-md-12 col-lg-12">';
                writeline('Does WorkSafe (WHSQ) &/or the Electrical Safety Office (ESO) need to be notified?',$entry['injuryreport_worksafenotify']);
                if(!empty($entry['injuryreport_worksafeREF'])){writeline('WorkSafe/ESO reference number',$entry['injuryreport_worksafeREF']);}
                if(!empty($entry['injuryreport_worksafe_receipt'])){writeline('Notification receipt from WorkSafe/ESO',$entry['injuryreport_worksafe_receipt']);}
                writeline('Investigation details',$entry['injuryreport_investigation_details'],'tobehidden');
                writeline('Machine/Assembly Line Number',$entry['injuryreport_machinenumber'],'tobehidden');
                writeline('Do actions need to be taken to prevent recurrence',$entry['injuryreport_further_action']);
                writeline('Comments',$entry['injuryreport_investigation_comments'],'tobehidden');
                if(!empty($entry['injuryreport_investigation_closedate'])){
                    writeline('Closed by',$entry['injuryreport_investigation_closedby']);
                    writeline('Close Date',date('jS M Y G:i',$entry['injuryreport_investigation_closedate']));
                }
                
            echo'</div>';
        echo'</div>';
    echo'</div>';
    show_followup_mini($db,$report_number);
    //show_notes_mini($db,$report_number);
    show_injury_log($db,$report_number);
}

function show_investigation_mini($db,$report_number){
    $entry=array();
    $entry=get_investigation_report($db,$report_number);
    
    echo'<div class="row main-box investigation">';
        echo'<div class="row" ';
        if(!empty($_SESSION['temp']['role_injury_viewall'])){echo'onclick="investigation();show_menu_investigation();"';}
        echo'>';
            echo'<div class="col-sm-10 title-report">Investigation - '.$entry['injuryreport_investigation_status'];
            
            echo'</div>';
            echo'<div class="col-sm-2 title-report">';
                    if($entry['injuryreport_investigation_status']=='Closed'){
                        echo '<img class="attachment" src="img/checked.png" width="30">';
                    }
            echo'</div>';
        echo'</div>';
    echo'</div>';
    ajax_button('investigation',[['injuryreport_number','thenumber'],['investigation',"'show'"]],'injury-ajax.php','report-box');
    ajax_button('show_menu_investigation',[['injuryreport_number','thenumber'],['dialog',"'investigation'"]],'injury-ajax.php','dialog-box');
      
    

}

function show_followup($db,$report_number){
    $entry=get_injury_report($db,$report_number);
    show_report_injury_mini($db,$report_number);
    show_investigation_mini($db,$report_number);
    echo'<div class="row main-box followup" style="min-height:100px">';
        echo'<div class="row title-report" >';
            echo'<div class="col-sm-10 title-report">HR Follow-Up - '.$entry['injuryreport_followup_status'];
                
            echo'</div>';
            echo'<div class="col-sm-2 title-report">';
                    if($entry['injuryreport_followup_status']=='Closed'){
                        echo '<img class="attachment" src="img/checked.png" width="30">';
                    }
            echo'</div>';
        echo'</div>';
    
        echo'<br><div class="row">';
            echo'<div class="col-lg-2 ">';
            echo'</div>';
            echo'<div class="col-md-12 col-lg-12">';
                writeline('Does WorkCover need to be notified?',$entry['injuryreport_workcovernotify']);
                if(!empty($entry['injuryreport_workcoverREF'])){writeline('WorkCover reference number',$entry['injuryreport_workcoverREF']);}
                if(!empty($entry['injuryreport_workcover_date_accepted'])){writeline('Date claim was accepted by WorkCover',$entry['injuryreport_workcover_date_accepted']);}
                writeline('Has further medical treament been required since the original report?',$entry['injuryreport_treatment_after_report']);
                writeline('What is the injury/illness category for this report?',$entry['injuryreport_category'],'tobehidden');
                writeline('Is this a lost time injury (LTI)?',$entry['injuryreport_LTI'],'tobehidden');
                if(!empty($entry['injuryreport_LTI_hours'])){writeline('Total lost time hours',(round($entry['injuryreport_LTI_hours'],1)),'tobehidden');}
                
            echo'</div>';
        echo'</div>';
    echo'</div>';
    show_notes_mini($db,$report_number);
    show_injury_log($db,$report_number);
}

function show_followup_mini($db,$report_number){
    $entry=array();
    $entry=get_followup($db,$report_number);
    echo'<div class="row main-box followup">';
        echo'<div class="row" ';
        if(!empty($_SESSION['temp']['role_injury_viewall'])){echo'onclick="followup();show_menu_followup();"';}
        echo'>';
            echo'<div class="col-sm-10 title-report">HR Follow-Up - '.$entry['injuryreport_followup_status'];
            
            echo'</div>';
            echo'<div class="col-sm-2 title-report">';
                    if($entry['injuryreport_followup_status']=='Closed'){
                        echo '<img class="attachment" src="img/checked.png" width="30">';
                    }
            echo'</div>';
        echo'</div>';
    echo'</div>';
    ajax_button('followup',[['injuryreport_number','thenumber'],['followup',"'show'"]],'injury-ajax.php','report-box');
    ajax_button('show_menu_followup',[['injuryreport_number','thenumber'],['dialog',"'followup'"]],'injury-ajax.php','dialog-box');  
    

}

function show_notes($db,$report_number){
    show_report_injury_mini($db,$report_number);
    show_investigation_mini($db,$report_number);
    show_followup_mini($db,$report_number);
    echo'<div class="row main-box notes" style="min-height:100px">';
        echo'<div class="row" >';
            echo'<div class="col-sm-12 title-report">Notes</div>';
        echo'</div>';
        foreach(get_all_injury_notes($db,$report_number) as $notes){
            echo'<div class="row">';
                echo'<div class="col-sm-2 ">';
                echo date('jS M Y G:i',$notes['injurynotes_timetag']);
                echo'</div>';
                echo'<div class="col-sm-8 ">';
                echo $notes['injurynotes_entry'];
                echo'</div>';
                echo'<div class="col-sm-1 ">';
                echo'<form method="POST" id="report_to_edit">';   
                echo '<button class="btn btn-primary injury_button" >X</button>';
                echo'<input type="hidden" value="'.$_POST['injuryreport_number'].'" name="injuryreport_number" id="injuryreport_number">';  
                echo'<input type="hidden" value="'.$notes['injurynotes_number'].'" name="injurynotes_number" id="injurynotes_number">'; 
                echo'<input type="hidden" value="remove" name="remove_notes">';  
                echo'</form>';
                echo'</div>';
            echo'</div>';
        }
        echo'<div class="row">';
            echo'<form method="POST" id="report_to_edit">';    
            echo'<div class="col-sm-3 ">';
            
            echo'</div>';
            echo'<div class="col-sm-6 ">';
            echo '<input type="text" class="form-control" name="notes_to_add" placeholder="Add a Notes"> ';
            echo'</div>';
            echo'<div class="col-sm-1 ">';
            echo '<button class="btn btn-primary injury_button" >+</button>';
            echo'</div>';
            echo'<input type="hidden" value="'.$_POST['injuryreport_number'].'" name="injuryreport_number" id="injuryreport_number">';  
            echo'<input type="hidden" value="add" name="add_notes">';  
            echo'</form>';
            echo'</div>';
    echo'</div>';
    show_injury_log($db,$report_number);
    
}

function show_notes_mini($db,$report_number){
    $entry=array();
    $entry=get_investigation_report($db,$report_number);
    echo'<div class="row main-box notes">';
        echo'<div class="row" ';
        if(!empty($_SESSION['temp']['role_injury_viewall'])){echo'onclick="notes();show_menu_notes();"';}
        echo'>';
            echo'<div class="col-sm-12 title-report">Notes';
            echo ' ('.number_format(count_all_injury_notes($db,$_POST['injuryreport_number'])).')';
            echo'</div>';
        echo'</div>';
    echo'</div>';
    ajax_button('notes',[['injuryreport_number','thenumber'],['notes',"'show'"]],'injury-ajax.php','report-box');
    ajax_button('show_menu_notes',[['injuryreport_number','thenumber'],['dialog',"'notes'"]],'injury-ajax.php','dialog-box');  
    

}


function show_injury_log($db,$report_number){
    echo'<div class="row log-history" onclick="showhistory();">';
    
    echo'<b>All history of the report:</b><i>Click to Expand</i>';
    echo'<div class="alllog" id="alllog" style="display: none;">';
        foreach(get_all_injury_log($db,$report_number) as $log){
            echo'<div class="row">';
                echo'<div class="col-sm-3 ">';
                echo date('jS M Y G:i',$log['injurylog_timetag']);
                echo'</div>';
                echo'<div class="col-sm-8 ">';
                echo $log['injurylog_entry'];
                echo'</div>';
                echo'<div class="col-sm-1 ">';
                echo $log['injurylog_REF'];
                echo'</div>';
            echo'</div>';
        }
    echo'</div>';
    

    echo'<script>
    function showhistory() {
        var x = document.getElementById("alllog");
        if (x.style.display === "block") {
        x.style.display = "none";
        } else {
        x.style.display = "block";
        }
    }
    
    </script>';
    echo'</div>';
}

function edit_report_injury($db,$report_number){
    $line_number=get_line_number($db);
    $entry=array();
    $entry=get_injury_report($db,$report_number);
    echo'<script>thenumber=\''.$entry['injuryreport_number'].'\'</script>';

    echo'<div class="row main-box report">';
        echo'<div class="row">';
            echo'<div class="col-sm-12 title-report">';
            echo $entry['injuryreport_number'];
            echo'</div>';
        echo'</div>';
        echo'<div class="row">';
            echo'<div class="col-sm-12 subtitle-report">';
            echo 'Raised on '. date('jS M Y',$entry['injuryreport_timetag_report']).' at '.date('G:i',$entry['injuryreport_timetag_report']).' by '.$entry['injuryreport_openby'];
            echo'</div>';
            
        echo'</div>';
        echo'<br><div class="row">';
            echo'<div class="col-lg-2 ">';
            echo'</div>';
            echo'<div class="col-md-12 col-lg-12">';
                echo'<form method="POST" id="report_to_edit">';
                editline('Name of injured/ill person',$entry['injuryreport_name'],'tobefilled','injuryreport_name','input','Who has been injured');
                if(!empty($entry['injuryreport_timetag_incident'])){
                    $datetoshow=date('jS M Y',$entry['injuryreport_timetag_incident']).' '.date('G:i',$entry['injuryreport_timetag_incident']);
                }
                editline('Date & time injury/illness occurred',$datetoshow,'tobefilled','injuryreport_timetag_incident');
                editline('Where did the injury occur?',$entry['injuryreport_location'],'tobefilled','injuryreport_location','location','Location where the incident happen');
                editline('Primary injury',$entry['injuryreport_injury1'],'tobefilled','injuryreport_injury1');
                editline('Secondary injury',$entry['injuryreport_injury2'],'','injuryreport_injury2');
                editline('Secondary injury',$entry['injuryreport_injury3'],'','injuryreport_injury3');
                editline('Secondary injury',$entry['injuryreport_injury4'],'','injuryreport_injury4');
                editline('Describe the injury',$entry['injuryreport_injury_description'],'tobefilled','injuryreport_injury_description','textarea');
                editline('Treatment given',$entry['injuryreport_treatment_give'],'tobefilled','injuryreport_treatment_give','input','Treatment Given');
                editline('Who provide first aid',$entry['injuryreport_first_aid_provider'],'tobefilled','injuryreport_first_aid_provider','input','Name of the 1st aid officier');
                editline('Further medical treatment required',$entry['injuryreport_further_treament_req'],'tobefilled','injuryreport_further_treament_req','input','No or put the further medical treatment required');
                editline('Supervisor/leading hand/manager advised',$entry['injuryreport_supervisor_advised'],'tobefilled','injuryreport_supervisor_advised','input','Name of the supervisor');
                editline('How did the injury occur',$entry['injuryreport_how_happen'],'tobefilled','injuryreport_how_happen','input','');
                editline('Has any plant/equipment been damaged',$entry['injuryreport_plant_damaged'],'tobefilled','injuryreport_plant_damaged','input','No or put the details of the damage');
                editline('Were they any witnesses to the injury',$entry['injuryreport_witnesses'],'tobefilled','injuryreport_witnesses','input','No or put the name of the witnesses');
                editline('Additional Comments',$entry['injuryreport_comments'],'','injuryreport_comments','input','More comments');
                //editline('Document Linked',$entry['injuryrepsdfort_name'],'','injuryrepsdfort_name');
                //editline('Photos',$entry['injuryrepsdfort_name'],'','injuryrepsdfort_name');
                echo'<input type="hidden" value="'.$_POST['injuryreport_number'].'" name="injuryreport_number" id="injuryreport_number">';  
                echo'<input type="hidden" value="save" name="save_report">';  
                 
                echo'<br><button class="btn btn-primary injury_button" >Save</button>';
                echo'</form >';
                ajax_button('showdialog',[['dialog','theentry']],'injury-ajax.php','dialog-box');
            echo'</div>';
        echo'</div>';

        
    echo'</div>';
    hide_div('create_new');
}

function edit_investigation($db,$report_number){
    //show_report_injury_mini($db,$report_number);
    $entry=get_injury_report($db,$report_number);
    echo'<div class="row main-box investigation" style="min-height:100px">';
        echo'<div class="row" >';
            echo'<div class="col-sm-10 title-report">Investigation - '.$entry['injuryreport_investigation_status'].'</div>';
        
            echo'<div class="col-sm-2 title-report">';
                    if($entry['injuryreport_investigation_status']=='Closed'){
                        echo '<img class="attachment" src="img/checked.png" width="30">';
                    }
            echo'</div>';
        echo'</div>';
        echo'<br><div class="row">';
            echo'<div class="col-lg-2 ">';
            echo'</div>';
            echo'<div class="col-md-12 col-lg-12">';
                echo'<form method="POST" id="report_to_edit">';
                echo'<div class="row line-report ';
                    if(empty($entry['injuryreport_worksafenotify'])){echo'tobefilled';}
                echo'" >
                    <div class="col-lg-2 "><br></div>
                    <div class="col-sm-6 col-lg-4">Does WorkSafe (WHSQ) &amp;/or the Electrical Safety Office (ESO) need to be notified?</div>
                    <div class="col-sm-6 col-lg-6">';
                    echo'<select class="form-control" onchange="moredetailsWHSQ()" name="injuryreport_worksafenotify" id="injuryreport_worksafenotify-input">
                    <option value="No" ';
                    if($entry['injuryreport_worksafenotify']=='No'){echo'selected';}
                    echo'>No</option>
                    <option value="Yes" ';
                    if($entry['injuryreport_worksafenotify']=='Yes'){echo'selected';}
                    echo'>Yes</option>
                   </select>';
                    echo'</div>
                </div>';
                echo'<div class="row line-report ';
                if(empty($entry['injuryreport_worksafenotify'])){echo'tobefilled';}
            echo'" id="injuryreport_worksafeREF-input" ';
                if($entry['injuryreport_worksafenotify']<>'Yes'){echo'style="display:none;"';}
                echo'>
                    <div class="col-lg-2 "><br></div>
                    <div class="col-sm-6 col-lg-4">WorkSafe/ESO reference number</div>
                    <div class="col-sm-6 col-lg-6">
                        <input type="text" class="form-control" value="'.$entry['injuryreport_worksafeREF'].'" name="injuryreport_worksafeREF"  placeholder="WorkSafe/ESO reference number">
                    </div>
                </div>';
                echo'<div class="row line-report ';
                if(empty($entry['injuryreport_worksafenotify'])){echo'tobefilled';}
            echo'" id="injuryreport_worksafe_receipt-input" ';
                if($entry['injuryreport_worksafenotify']<>'Yes'){echo'style="display:none;"';}
                echo'>
                    <div class="col-lg-2 "><br></div>
                    <div class="col-sm-6 col-lg-4">Notification receipt from WorkSafe/ESO</div>
                    <div class="col-sm-6 col-lg-6">
                        <input type="text" class="form-control" value="'.$entry['injuryreport_worksafe_receipt'].'" name="injuryreport_worksafe_receipt" placeholder="Notification receipt from WorkSafe/ESO">
                    </div>
                </div>';
                editline('Investigation details',$entry['injuryreport_investigation_details'],'tobefilled','injuryreport_investigation_details','textarea','Investigation details','noOnClick');
                editline('Machine/Assembly Line Number',$entry['injuryreport_machinenumber'],'','injuryreport_machinenumber','input','Machine/Assembly Line Number','noOnClick');
                editline('Do actions need to be taken to prevent recurrence',$entry['injuryreport_further_action'],'tobefilled','injuryreport_further_action','input','Do actions need to be taken to prevent recurrence','noOnClick');
                editline('Comments',$entry['injuryreport_investigation_comments'],'','injuryreport_investigation_comments','input','Comments','noOnClick');
                echo'<input type="hidden" value="'.$_POST['injuryreport_number'].'" name="injuryreport_number" id="injuryreport_number">';  
                echo'<input type="hidden" value="save" name="save_investigation">';  
                 
                echo'<br><button class="btn btn-primary injury_button" >Save</button>';
                
                //writeline('Does WorkSafe (WHSQ) &/or the Electrical Safety Office (ESO) need to be notified?',$entry['injuryreport_worksafenotify']);
                // if(!empty($entry['injuryreport_worksafeREF'])){writeline('WorkSafe/ESO reference number',$entry['injuryreport_worksafeREF']);}
                // if(!empty($entry['injuryreport_worksafe_receipt'])){writeline('Notification receipt from WorkSafe/ESO',$entry['injuryreport_worksafe_receipt']);}
                // writeline('Investigation details',$entry['injuryreport_treatment_after_report'],'tobehidden');
                // writeline('Do actions need to be taken to prevent recurrence',$entry['injuryreport_category']);
                // writeline('Comments',$entry['injuryreport_investigation_comments'],'tobehidden');
                // writeline('Closed by',$entry['injuryreport_investigation_closedby']);
                // writeline('Close Date',$entry['injuryreport_investigation_closedate']);
                echo'</form>';
            echo'</div>';
        echo'</div>';
    echo'</div>';
    echo'<script>
    function moredetailsWHSQ() {
        var x = document.getElementById("injuryreport_worksafenotify-input");
        
        if (x.value == "Yes" || x.value == "yes" || x.value == "YES" ) {
            document.getElementById("injuryreport_worksafeREF-input").style.display = "block";
            document.getElementById("injuryreport_worksafe_receipt-input").style.display = "block";
        } else {
            document.getElementById("injuryreport_worksafeREF-input").style.display = "none";
            document.getElementById("injuryreport_worksafe_receipt-input").style.display = "none";
            document.getElementById("injuryreport_worksafeREF-input").value = "";
            document.getElementById("injuryreport_worksafe_receipt-input").value = "";
        
        }
    }
    
    </script>';
    // show_followup_mini($db,$report_number);
    // show_notes_mini($db,$report_number);
    // show_injury_log($db,$report_number);
}

function edit_followup($db,$report_number){
    //show_report_injury_mini($db,$report_number);
    $entry=get_injury_report($db,$report_number);
    echo'<div class="row main-box followup" style="min-height:100px">';
        echo'<div class="row" >';
            echo'<div class="col-sm-10 title-report">HR Follow-Up - '.$entry['injuryreport_followup_status'].'</div>';
        
            echo'<div class="col-sm-2 title-report">';
                    if($entry['injuryreport_followup_status']=='Closed'){
                        echo '<img class="attachment" src="img/checked.png" width="30">';
                    }
            echo'</div>';
        echo'</div>';
        echo'<br><div class="row">';
            echo'<div class="col-lg-2">';
            echo'</div>';
            echo'<div class="col-md-12 col-lg-12">';
                echo'<form method="POST" id="report_to_edit">';
                echo'<div class="row line-report ';
                    if(empty($entry['injuryreport_workcovernotify'])){echo'tobefilled';}
                echo'" >
                    <div class="col-lg-2"><br></div>
                    <div class="col-sm-6 col-lg-4">Does WorkCover need to be notified?</div>
                    <div class="col-sm-6 col-lg-6">';
                    echo'<select class="form-control" onchange="moredetailsWHSQ()" name="injuryreport_workcovernotify" id="injuryreport_workcovernotify-input">
                    <option value="No" ';
                    if($entry['injuryreport_workcovernotify']=='No'){echo'selected';}
                    echo'>No</option>
                    <option value="Yes" ';
                    if($entry['injuryreport_workcovernotify']=='Yes'){echo'selected';}
                    echo'>Yes</option>
                   </select>';
                    echo'</div>
                </div>';
                echo'<div class="row line-report ';
                if(empty($entry['injuryreport_workcovernotify'])){echo'tobefilled';}
            echo'" id="injuryreport_workcoverREF-input" ';
                if($entry['injuryreport_workcovernotify']<>'Yes'){echo'style="display:none;"';}
                echo'>
                    <div class="col-lg-2"><br></div>
                    <div class="col-sm-6 col-lg-4">WorkCover reference number</div>
                    <div class="col-sm-6 col-lg-6">
                        <input type="text" class="form-control" value="'.$entry['injuryreport_workcoverREF'].'" name="injuryreport_workcoverREF"  placeholder="WorkCover reference number">
                    </div>
                </div>';
                echo'<div class="row line-report ';
                if(empty($entry['injuryreport_workcovernotify'])){echo'tobefilled';}
            echo'" id="injuryreport_workcover_date_accepted-input" ';
                if($entry['injuryreport_workcovernotify']<>'Yes'){echo'style="display:none;"';}
                echo'>
                    <div class="col-lg-2"><br></div>
                    <div class="col-sm-6 col-lg-4">Date claim was accepted by WorkCover</div>
                    <div class="col-sm-6 col-lg-6">
                        <input type="text" class="form-control" value="'.$entry['injuryreport_workcover_date_accepted'].'" name="injuryreport_workcover_date_accepted" placeholder="Date claim was accepted by WorkCover">
                    </div>
                </div>';
                editline('Has further medical treament been required since the original report?',$entry['injuryreport_treatment_after_report'],'tobefilled','injuryreport_treatment_after_report','textarea','Has further medical treament been required since the original report?','noOnClick');
                
                echo'<div class="row line-report ';
                    if(empty($entry['injuryreport_category'])){echo'tobefilled';}
                echo'" >
                    <div class="col-lg-2"><br></div>
                    <div class="col-sm-6 col-lg-4">What is the injury/illness category for this report?</div>
                    <div class="col-sm-6 col-lg-6">';
                    echo'<select class="form-control" onchange="moredetailsWHSQ()" name="injuryreport_category" id="injuryreport_category-input">
                    <option value="First aid" ';
                    if($entry['injuryreport_category']=='First aid'){echo'selected';}
                    echo'>First aid</option>
                    <option value="Medical treatment" ';
                    if($entry['injuryreport_category']=='Medical treatment'){echo'selected';}
                    echo'>Medical treatment</option>
                    <option value="Fatality" ';
                    if($entry['injuryreport_category']=='Fatality'){echo'selected';}
                    echo'>Fatality</option>
                   </select>';
                    echo'</div>
                </div>';
                

                echo'<div class="row line-report ';
                    if(empty($entry['injuryreport_LTI'])){echo'tobefilled';}
                echo'" >
                    <div class="col-lg-2"><br></div>
                    <div class="col-sm-6 col-lg-4">Is this a lost time injury (LTI)?</div>
                    <div class="col-sm-6 col-lg-6">';
                    echo'<select class="form-control" onchange="moredetailsWHSQ()" name="injuryreport_LTI" id="injuryreport_LTI-input">
                    <option value="No" ';
                    if($entry['injuryreport_LTI']=='No'){echo'selected';}
                    echo'>No</option>
                    <option value="Yes" ';
                    if($entry['injuryreport_LTI']=='Yes'){echo'selected';}
                    echo'>Yes</option>
                   </select>';
                    echo'</div>
                </div>';
                
                echo'<div class="row line-report ';
                if(empty($entry['injuryreport_LTI'])){echo'tobefilled';}
                echo'" id="injuryreport_LTI_hours-input" ';
                if($entry['injuryreport_LTI']<>'Yes'){echo'style="display:none;"';}
                echo'>
                    <div class="col-lg-2"><br></div>
                    <div class="col-sm-6 col-lg-4">Total lost time hours</div>
                    <div class="col-sm-6 col-lg-6">
                        <input type="number" step="0.1" class="form-control" value="'.$entry['injuryreport_LTI_hours'].'" name="injuryreport_LTI_hours"  placeholder="Total lost time hours">
                    </div>
                </div>';


                
                echo'<input type="hidden" value="'.$_POST['injuryreport_number'].'" name="injuryreport_number" id="injuryreport_number">';  
                echo'<input type="hidden" value="save" name="save_followup">';  
                 
                echo'<br><button class="btn btn-primary injury_button" >Save</button>';
                
                //writeline('Does WorkSafe (WHSQ) &/or the Electrical Safety Office (ESO) need to be notified?',$entry['injuryreport_worksafenotify']);
                // if(!empty($entry['injuryreport_worksafeREF'])){writeline('WorkSafe/ESO reference number',$entry['injuryreport_worksafeREF']);}
                // if(!empty($entry['injuryreport_worksafe_receipt'])){writeline('Notification receipt from WorkSafe/ESO',$entry['injuryreport_worksafe_receipt']);}
                // writeline('Investigation details',$entry['injuryreport_treatment_after_report'],'tobehidden');
                // writeline('Do actions need to be taken to prevent recurrence',$entry['injuryreport_category']);
                // writeline('Comments',$entry['injuryreport_investigation_comments'],'tobehidden');
                // writeline('Closed by',$entry['injuryreport_investigation_closedby']);
                // writeline('Close Date',$entry['injuryreport_investigation_closedate']);
                echo'</form>';
            echo'</div>';
        echo'</div>';
    echo'</div>';
    echo'<script>
    function moredetailsWHSQ() {
        var x = document.getElementById("injuryreport_workcovernotify-input");
        
        if (x.value == "Yes" || x.value == "yes" || x.value == "YES" ) {
            document.getElementById("injuryreport_workcoverREF-input").style.display = "block";
            document.getElementById("injuryreport_workcover_date_accepted-input").style.display = "block";
        } else {
            document.getElementById("injuryreport_workcoverREF-input").style.display = "none";
            document.getElementById("injuryreport_workcover_date_accepted-input").style.display = "none";
            document.getElementById("injuryreport_workcoverREF-input").value = "";
            document.getElementById("injuryreport_workcover_date_accepted-input").value = "";
        
        }

        var x = document.getElementById("injuryreport_LTI-input");
        
        if (x.value == "Yes" || x.value == "yes" || x.value == "YES" ) {
            document.getElementById("injuryreport_LTI_hours-input").style.display = "block";
            
        } else {
            document.getElementById("injuryreport_LTI_hours-input").style.display = "none";
            document.getElementById("injuryreport_LTI_hours-input").value = "";
           
        }
    }
    
    </script>';
    // show_followup_mini($db,$report_number);
    // show_notes_mini($db,$report_number);
    // show_injury_log($db,$report_number);
}

function dialog_box($db){
    echo'<div class="main-box">';//main-box
    if($_POST['dialog']=='initial'){
        dialog_initial($db);
    }
    if($_POST['dialog']=='report_start'){
        dialog_report_start($db);
    }
    if($_POST['dialog']=='show_injury'){
        dialog_show_injury($db);
    }
    if($_POST['dialog']=='edit'){
        dialog_edit($db);
    }
    //
    if($_POST['dialog']=='injuryreport_timetag_incident'){
        dialog_timepicker($db);
    }
    if($_POST['dialog']=='injuryreport_injury1' or $_POST['dialog']=='injuryreport_injury2' or $_POST['dialog']=='injuryreport_injury3'or $_POST['dialog']=='injuryreport_injury4'){
        $injury_number=substr($_POST['dialog'], -1);
        dialog_choose_injury($db,$injury_number);
    }
    if($_POST['dialog']=='investigation'){
        dialog_investigation($db);
    }
    if($_POST['dialog']=='followup'){
        dialog_followup($db);
    }
    if($_POST['dialog']=='notes'){
        dialog_notes($db);
    }
    if($_POST['dialog']=='edit_investigation'){
        dialog_edit_investigation($db);
    }
    if($_POST['dialog']=='edit_followup'){
        dialog_edit_followup($db);
    }
    echo'</div>';
    if($_POST['dialog']=='followup' ){
        if(checkstatus($db,$_POST['injuryreport_number'])['injuryreport_status']<>'Created'){
            show_all_attachment($db,$_POST['injuryreport_number']);
        }
    }
      
     
}

function dialog_initial($db){
    //fsdf

}

function dialog_choose_injury($db,$injury_number){
    
    //echo'<div class="row ">
        
    //    <div class="col-sm-12 " id="show_injury">Injury 1 </div>
        
    //</div>';
    echo'<br><div class="row ">';
        
        echo'<div class="col-sm-12 ">';
            echo '<select id="injuryintensity" name="injuryintensity" onchange="theinjuryintensity=document.getElementById (\'injuryintensity\').value;update_dialog_choose_injury();" class="form-control" >';
            //echo '<option >Choose the intensity</option> ';
            //echo '<option value="Cancel">Back</option> ';
            //echo '<option disabled>______</option> ';	
            if(empty($_POST['injuryintensity'])){$_POST['injuryintensity']='Insignificant';}
            echo '<option value="Insignificant"';
            if('Insignificant'==$_POST['injuryintensity']){echo 'selected';}
            echo'>Insignificant</option> ';
            echo '<option value="Minor"';
            if('Minor'==$_POST['injuryintensity']){echo 'selected';}
            echo'>Minor</option> '; 
            echo '<option value="Major"';
            if('Major'==$_POST['injuryintensity']){echo 'selected';}
            echo'>Major</option> ';    
            echo' </select>';
        echo'</div>';
        echo'<div class="col-sm-12 ">';
            echo '<select id="injurytype" name="injurytype" onchange="theinjurytype=document.getElementById (\'injurytype\').value;update_dialog_choose_injury();" class="form-control" >';
            //echo '<option >Choose the Injury Type</option> ';
            //echo '<option disabled>______</option> ';	
            $i=0;
                foreach (get_all_injury_type($db) as &$injurytype){
                        if(empty($_POST['injurytype'])){if($i==0){$_POST['injurytype']=$injurytype['injury_name'];}}
                        echo '<option value="'.$injurytype['injury_name'].'" ';
                        if($injurytype['injury_name']==$_POST['injurytype']){echo 'selected';}
                        echo'>'.$injurytype['injury_name'].'</option> ';
                }
            echo' </select>';
        echo'</div>';
        if(!empty($_POST['choose_body_part'])){
            echo'<div class="col-sm-12 ">';
                echo '<select id="injurybody" name="injurybody" class="form-control" onchange="theinjurybody=document.getElementById (\'injurybody\').value;update_dialog_choose_injury();" >';
                //echo '<option >Choose the Body Part</option> ';
                //echo '<option value="Cancel">Back</option> ';
                //echo '<option disabled>______</option> ';	
                $i=0;
                    foreach (get_all_injury_body($db) as &$injurytype){
                            if(empty($_POST['injurybody'])){if($i==0){$_POST['injurybody']=$injurytype['injury_name'];}}
                            echo '<option value="'.$injurytype['injury_name'].'" ';
                            if($_POST['injurybody']==$injurytype['injury_name']){echo 'selected';}
                            echo'>'.$injurytype['injury_name'].'</option> ';
                            $i++;
                    }
                echo' </select>';
            echo'</div>';
            
            if(!empty($_POST['choose_left_right'])){
                echo'<div class="col-sm-12 ">';
                    echo '<select id="injuryleftright" name="injuryleftright" class="form-control" onchange="theinjuryleftright=document.getElementById (\'injuryleftright\').value;update_dialog_choose_injury();" >';
                    //echo '<option >Choose the side</option> ';
                    //echo '<option value="Cancel">Back</option> ';
                // echo '<option disabled>______</option> ';
                if(empty($_POST['injuryleftright'])){$_POST['injuryleftright']='Left';}	
                    echo '<option value="Left"';
                    if('Left'==$_POST['injuryleftright']){echo 'selected';}
                    echo'>Left</option> ';
                    echo '<option value="Right"';
                    if('Right'==$_POST['injuryleftright']){echo 'selected';}
                    echo'>Right</option> ';    
                    echo' </select>';
                echo'</div>';
            }
            else{
                echo '<input type=hidden id="injuryleftright" name="injuryleftright" value="">';
            }
        }else{
            echo '<input type=hidden id="injurybody" name="injurybody" value="">';
            echo '<input type=hidden id="injuryleftright" name="injuryleftright" value="">';
        }


        echo'<script>
        theinjuryintensity=document.getElementById ("injuryintensity").value;
        theinjurytype=document.getElementById ("injurytype").value;
        theinjurybody=document.getElementById ("injurybody").value;
        theinjuryleftright=document.getElementById ("injuryleftright").value;

        </script>';
        
        ajax_button('update_dialog_choose_injury',[['dialog',"'".$_POST['dialog']."'"],['injuryintensity',"theinjuryintensity"],['injurytype',"theinjurytype"],['injurybody',"theinjurybody"],['injuryleftright',"theinjuryleftright"]],'injury-ajax.php','dialog-box');
        $newinjury=create_injury($db);
        echo'<script>document.getElementById ("injuryreport_injury'.$injury_number.'-input").value=\''.$newinjury.'\';</script>';
        update_div('injuryreport_injury'.$injury_number.'','dont',$newinjury);
        echo'<form method="POST">';
        echo'</form>';
        
    echo'</div>';
    echo'<br><div class="row "><span class="glyphicon glyphicon-trash" aria-hidden="true" onclick="removeinjury();empty_dialog();"></span></div>';

    echo'<script>
    function removeinjury(){
        document.getElementById ("injuryreport_injury'.$injury_number.'-input").value=\'\';
        document.getElementById("injuryreport_injury'.$injury_number.'").innerHTML = "";
    }
    </script>';
    

}

function dialog_report_start($db){
    
    ?>
    <input 
    type="hidden" 
    value="<?php echo $_POST['injuryreport_number']?>" 
    name="injuryreport_number">
    <input 
    type="text" 
    class="form-control" 
    value="<?php echo $_POST['injuryreport_name']?>" 
    name="injuryreport_name" 
    placeholder="Name of Injured/ill person">
    <input 
    type="date" 
    class="form-control" 
    value="<?php echo date('Y-m-d')?>" 
    name="injuryreport_date_incident">
    <input 
    type="time" 
    class="form-control" 
    value="<?php echo date('H:i')?>" 
    name="injuryreport_time_incident">
    <input 
    type="text" 
    class="form-control" 
    value="" 
    name="injuryreport_location" 
    placeholder="Where did the injury occur?" required> 
    <br><input 
    type="submit" 
    class="btn btn-primary injury_button" 
    value="Next" 
    name="add_injury">
    <?php
    
}

function dialog_show_injury($db){
    //echo'<div class="btn btn-primary injury_button" onClick="thenumber=\''.$_POST['injuryreport_number'].'\';show_injury_details();show_menu();">View All Details</div>';

    $status=checkstatus($db,$_POST['injuryreport_number']);
    $openby=checkopenby($db,$_POST['injuryreport_number']);
    
   if(!empty($_SESSION['temp']['role_injury_viewall']) or $_SESSION['temp']['id']==$openby['injuryreport_openby']){
        if($status['injuryreport_status']=='Created'){
            echo'<br><div class="btn btn-primary injury_button" onClick="thenumber=\''.$_POST['injuryreport_number'].'\';edit_injury();empty_dialog()">Edit</div>';
        $option=checkifsubmitallowed($db,$_POST['injuryreport_number']);
            echo'<br><div class="btn btn-primary injury_button '.$option.'" ';
            if(empty($option)){echo'onClick="thenumber=\''.$_POST['injuryreport_number'].'\';submit_injury();empty_dialog2()"';}
            echo'>Submit Report</div>';
        }elseif($status['injuryreport_status']=='Opened'  and !empty($_SESSION['temp']['role_injury_viewall'])){
            if($status['injuryreport_investigation_status']=='Closed' or $status['injuryreport_followup_status']=='Closed'){
                $option='disabled';
            }
            
            echo'<br><div class="btn btn-primary injury_button '.$option.'" ';
            if(empty($option)){echo'onClick="thenumber=\''.$_POST['injuryreport_number'].'\';unsubmit_injury();empty_dialog2()"';}
            echo'>Unsubmit Report</div>';
            
        }elseif($status['injuryreport_status']=='Closed'){
        
        }
    }
    if($status['injuryreport_status']=='Created' and (!empty($_SESSION['temp']['role_injury_viewall']) or $_SESSION['temp']['id']== $openby['injuryreport_openby'])){
        echo'<div class="btn btn-primary injury_button" onClick="thenumber=\''.$_POST['injuryreport_number'].'\';delete_injury();refreshpage();">Delete Report</div>';
    }
    dialog_back_button($db);

    

    ajax_button('submit_injury',[['injuryreport_number','thenumber'],['submit_injury',"'show'"]],'injury-ajax.php','report-box');
    ajax_button('unsubmit_injury',[['injuryreport_number','thenumber'],['unsubmit_injury',"'show'"]],'injury-ajax.php','report-box');
    ajax_button('edit_injury',[['injuryreport_number','thenumber'],['edit_injury',"'show'"]],'injury-ajax.php','report-box');
    ajax_button('show_menu',[['injuryreport_number','thenumber'],['dialog',"'show_injury'"]],'injury-ajax.php','dialog-box');  
    ajax_button('empty_dialog',[['injuryreport_number','thenumber'],['dialog',"'edit'"]],'injury-ajax.php','dialog-box');   
    ajax_button('empty_dialog2',[['injuryreport_number','thenumber'],['dialog',"'show_injury'"]],'injury-ajax.php','dialog-box');
}

function dialog_back_button($db){
    echo'<br>';
    echo'<div class="btn btn-primary injury_button" onClick="empty_injury();show_menu_initial();">Back</div>';
   
    
    //ajax_button('refreshpage',[['type',"'ListOfInjuries'"]],'injury-ajax.php','report-box');
    
    if($_POST['dialog']=='edit' or $_POST['dialog']=='investigation' or $_POST['dialog']=='followup' or $_POST['dialog']=='notes' ){
        $array2=[['dialog',"'show_injury'"],['injuryreport_number',"'".$_POST['injuryreport_number']."'"]];
        $array=[['show_injury',"'show'"],['injuryreport_number',"'".$_POST['injuryreport_number']."'"]];
    }elseif($_POST['dialog']=='edit_investigation' ){
        $array2=[['dialog',"'investigation'"],['injuryreport_number',"'".$_POST['injuryreport_number']."'"]];
        $array=[['investigation',"'show'"],['injuryreport_number',"'".$_POST['injuryreport_number']."'"]];
    }elseif($_POST['dialog']=='edit_followup' ){
        $array2=[['dialog',"'followup'"],['injuryreport_number',"'".$_POST['injuryreport_number']."'"]];
        $array=[['followup',"'show'"],['injuryreport_number',"'".$_POST['injuryreport_number']."'"]];
    }else{
        $array2=[['dialog',"'initial'"]];
        $array=[['type',"'ListOfInjuries'"]];
    }
    ajax_button('empty_injury',$array,'injury-ajax.php','report-box');
    ajax_button('show_menu_initial',$array2,'injury-ajax.php','dialog-box');   
}

function dialog_edit($db){
    // echo'<div class="btn btn-primary injury_button" onClick="thenumber=\''.$_POST['injuryreport_number'].'\';save_report();empty_dialog();">Save</div>';
    // ajax_update_report($db);
    // ajax_button('edit_injury',[['injuryreport_number','thenumber'],['edit_injury',"'show'"]],'injury-ajax.php','report-box');
    // ajax_button('empty_dialog',[['injuryreport_number','thenumber'],['dialog',"'edit'"]],'injury-ajax.php','dialog-box'); 
    $option=checkifsubmitallowed($db,$_POST['injuryreport_number']);
    if(empty($option)){echo'<br><div class="btn btn-primary injury_button '.$option.'" ';
    echo'onClick="thenumber=\''.$_POST['injuryreport_number'].'\';submit_injury();empty_dialog2()"';
    echo'>Submit Report</div>';}
    ajax_button('submit_injury',[['injuryreport_number','thenumber'],['submit_injury',"'show'"]],'injury-ajax.php','report-box');
    ajax_button('empty_dialog2',[['injuryreport_number','thenumber'],['dialog',"'show_injury'"]],'injury-ajax.php','dialog-box');
    dialog_back_button($db);
}

function dialog_investigation($db){
    
    $status=checkstatus($db,$_POST['injuryreport_number']);
    
    
   if(!empty($_SESSION['temp']['role_injury_viewall']) ){
        if($status['injuryreport_investigation_status']=='Required' or $status['injuryreport_investigation_status']=='In-Progress'){
            echo'<br><div class="btn btn-primary injury_button" onClick="thenumber=\''.$_POST['injuryreport_number'].'\';edit_investigation();dialog_edit_investigation()">Edit</div>';
            
        }

        if($status['injuryreport_investigation_status']=='In-Progress'){
            $option=checkif_close_investigation_allowed($db,$_POST['injuryreport_number']);
            echo'<br><div class="btn btn-primary injury_button '.$option.'" ';
            if(empty($option)){echo'onClick="thenumber=\''.$_POST['injuryreport_number'].'\';submit_investigation();dialog_show_investigation()"';}
            echo'>Close Investigation</div>';
        }

        if($status['injuryreport_investigation_status']=='Closed'){
            
            echo'<br><div class="btn btn-primary injury_button " ';
            echo'onClick="thenumber=\''.$_POST['injuryreport_number'].'\';reopen_investigation();dialog_show_investigation()"';
            echo'>Re-Open Investigation</div>';
        }
    }
    

    dialog_back_button($db);
    ajax_button('edit_investigation',[['injuryreport_number','thenumber'],['edit_investigation',"'show'"]],'injury-ajax.php','report-box');
    ajax_button('dialog_edit_investigation',[['injuryreport_number','thenumber'],['dialog',"'edit_investigation'"]],'injury-ajax.php','dialog-box');
    ajax_button('submit_investigation',[['injuryreport_number','thenumber'],['submit_investigation',"'show'"]],'injury-ajax.php','report-box');
    ajax_button('dialog_show_injury',[['injuryreport_number','thenumber'],['dialog',"'show_injury'"]],'injury-ajax.php','dialog-box');
    ajax_button('reopen_investigation',[['injuryreport_number','thenumber'],['unsubmit_investigation',"'show'"]],'injury-ajax.php','report-box');
    ajax_button('dialog_show_investigation',[['injuryreport_number','thenumber'],['dialog',"'investigation'"]],'injury-ajax.php','dialog-box');
}

function dialog_edit_investigation($db){
    // echo'<div class="btn btn-primary injury_button" onClick="thenumber=\''.$_POST['injuryreport_number'].'\';save_report();empty_dialog();">Save</div>';
    // ajax_update_report($db);
    // ajax_button('edit_injury',[['injuryreport_number','thenumber'],['edit_injury',"'show'"]],'injury-ajax.php','report-box');
    // ajax_button('empty_dialog',[['injuryreport_number','thenumber'],['dialog',"'edit'"]],'injury-ajax.php','dialog-box'); 
   
    dialog_back_button($db);
}

function dialog_edit_followup($db){
    // echo'<div class="btn btn-primary injury_button" onClick="thenumber=\''.$_POST['injuryreport_number'].'\';save_report();empty_dialog();">Save</div>';
    // ajax_update_report($db);
    // ajax_button('edit_injury',[['injuryreport_number','thenumber'],['edit_injury',"'show'"]],'injury-ajax.php','report-box');
    // ajax_button('empty_dialog',[['injuryreport_number','thenumber'],['dialog',"'edit'"]],'injury-ajax.php','dialog-box'); 
   
    dialog_back_button($db);
}


function dialog_followup($db){
    // echo'<div class="btn btn-primary injury_button" onClick="thenumber=\''.$_POST['injuryreport_number'].'\';save_report();empty_dialog();">Save</div>';
    // ajax_update_report($db);
    // ajax_button('edit_injury',[['injuryreport_number','thenumber'],['edit_injury',"'show'"]],'injury-ajax.php','report-box');
    // ajax_button('empty_dialog',[['injuryreport_number','thenumber'],['dialog',"'edit'"]],'injury-ajax.php','dialog-box'); 
    $status=checkstatus($db,$_POST['injuryreport_number']);
    
    
    if(!empty($_SESSION['temp']['role_injury_viewall'])){
        if($status['injuryreport_followup_status']=='Required' or $status['injuryreport_followup_status']=='In-Progress'){
            echo'<br><div class="btn btn-primary injury_button" onClick="thenumber=\''.$_POST['injuryreport_number'].'\';edit_followup();dialog_edit_followup()">Edit</div>';
            
        }

        if($status['injuryreport_followup_status']=='In-Progress'){
            $option=checkif_close_followup_allowed($db,$_POST['injuryreport_number']);
            echo'<br><div class="btn btn-primary injury_button '.$option.'" ';
            if(empty($option)){echo'onClick="thenumber=\''.$_POST['injuryreport_number'].'\';submit_followup();dialog_show_followup()"';}
            echo'>Close HR Follow-Up</div>';
        }

        if($status['injuryreport_followup_status']=='Closed'){
            
            echo'<br><div class="btn btn-primary injury_button " ';
            echo'onClick="thenumber=\''.$_POST['injuryreport_number'].'\';reopen_folowup();dialog_show_followup()"';
            echo'>Re-Open HR Follow-Up</div>';
        }
    }

    dialog_back_button($db);
    ajax_button('edit_followup',[['injuryreport_number','thenumber'],['edit_followup',"'show'"]],'injury-ajax.php','report-box');
    ajax_button('dialog_edit_followup',[['injuryreport_number','thenumber'],['dialog',"'edit_followup'"]],'injury-ajax.php','dialog-box');
    ajax_button('submit_followup',[['injuryreport_number','thenumber'],['submit_followup',"'show'"]],'injury-ajax.php','report-box');
    ajax_button('dialog_show_injury',[['injuryreport_number','thenumber'],['dialog',"'show_injury'"]],'injury-ajax.php','dialog-box');
    ajax_button('reopen_folowup',[['injuryreport_number','thenumber'],['unsubmit_followup',"'show'"]],'injury-ajax.php','report-box');
    ajax_button('dialog_show_followup',[['injuryreport_number','thenumber'],['dialog',"'followup'"]],'injury-ajax.php','dialog-box');
}

function dialog_notes($db){
    // echo'<div class="btn btn-primary injury_button" onClick="thenumber=\''.$_POST['injuryreport_number'].'\';save_report();empty_dialog();">Save</div>';
    // ajax_update_report($db);
    // ajax_button('edit_injury',[['injuryreport_number','thenumber'],['edit_injury',"'show'"]],'injury-ajax.php','report-box');
    // ajax_button('empty_dialog',[['injuryreport_number','thenumber'],['dialog',"'edit'"]],'injury-ajax.php','dialog-box'); 


    dialog_back_button($db);
}


function dialog_timepicker($db){
    echo'<div class="row"><div class="col-md-3 "></div><div class="col-md-12 col-sm-12 "><input type="date" class="form-control" value="'.date('Y-m-d').'" id="Date1" onChange="UpdateDate();"></div></div>';
    echo'<div class="row"><div class="col-md-3 "></div><div class="col-md-12 col-sm-12 "><input type="time" class="form-control" value="'.date('H:i').'" id="Time1" onChange="UpdateDate();"></div></div>';

    echo'<script>
    function UpdateDate(){
        thedate=document.getElementById("Date1").value;
        thetime=document.getElementById("Time1").value;
        strDate=thedate + \' \' + thetime
        var datum = Date.parse(strDate)/1000;
        var datum2 = new Date(datum * 1000);
        document.getElementById("'.$_POST['dialog'].'").innerHTML = datum2.toLocaleString(\'en-GB\', { timeZone: \'Australia/Brisbane\' });
        document.getElementById("'.$_POST['dialog'].'-input").value = datum;
        
     }
     //alert(toTimestamp(\'02/13/2009 23:31:30\'));


    </script>';
}




function editline($title,$content,$option1='',$option2='',$option3='',$placeholder='',$noOnClick=''){
    
    echo'<div class="row line-report ';
    if(empty($content)){echo $option1;}
    echo'" ';
    if(!empty($noOnClick)){
        echo'';
    }elseif(empty($option3)){
        echo'onClick="theentry=\''.$option2.'\';showdialog()"';
    }else{
        echo'onClick="empty_dialog()"';
    }
    echo'>';
    
    ajax_button('empty_dialog',[['injuryreport_number','thenumber'],['dialog',"'edit'"]],'injury-ajax.php','dialog-box');  

        ?><div class="col-lg-2"><br></div>
        <div class="col-sm-6 col-lg-4"><?php echo $title?></div>
        <div class="col-sm-6 col-lg-6"><?php
        if($option3=='location'){
            
            //List of all the Location to choose from
            $locations=[
                'Assembly',
                'Moulding',
                'Machining',
                'Engineering',
                'Store',
                'Office',
                'Lab',
                'Other',
                'Home',
                ];
            ?>  
            <select 
            class="form-control" 
            value="<?php echo $content?>" 
            name="<?php echo $option2?>" 
            id="<?php echo $option2?>-input" 
             
            >    
                <?php foreach($locations as $location){?>
                    <option value="<?php echo $location?>" <?php if ($location==$content){echo'selected';}?>><?php echo $location?></option>
                    <?php
                }?>
            </select>
        <?php }elseif($option3=='input'){?>
            <input 
            type="text" 
            class="form-control" 
            value="<?php echo $content?>" 
            name="<?php echo $option2?>" 
            id="<?php echo $option2?>-input" 
            placeholder="<?php echo $placeholder?>2" 
            >
        <?php }elseif($option3=='checkbox'){?>
            <input 
            type="checkbox" 
            class="form-control" 
            name="<?php echo $option2?>" 
            placeholder="<?php echo $placeholder?>" 
            >
        <?php }elseif($option3=='textarea'){?>
            <textarea 
            rows="5" 
            class="form-control" 
            name="<?php echo $option2?>" 
            placeholder="<?php echo$placeholder?>" ><?php echo$content?>
        </textarea>
        <?php }else{?>
            <div id="<?php echo $option2?>"><?php echo $content?></div><?php
            if($option2=='injuryreport_timetag_incident'){?>
                <input 
                type="hidden" 
                id="<?php echo $option2?>-input" 
                class="form-control" 
                value="<?php echo strtotime($content)?>" 
                name="<?php echo $option2?>"
                >
            <?php }else{?>
                <input 
                type="hidden" 
                id="<?php echo $option2?>-input" 
                class="form-control" 
                value="<?php echo $content?>" 
                name="<?php echo $option2?>"
                >
                <?php
            }
            
        }
        ?>
        </div>
    </div>
    <?php
    
}

function writeline($title,$content,$option1=''){
    
    if($option1=='tobehidden' and empty($_SESSION['temp']['role_injury_viewall'])){

    }else{
        echo'<div class="row line-report ">';//'.$option1.'
            echo'<div class="col-lg-2"><br></div>';
            echo'<div class="col-sm-6 col-lg-4 ">'.$title.'</div>';
            echo'<div class="col-sm-6 col-lg-6 ">'.$content.'</div>';
        echo'</div>';
    }
}

function initiate_report($db){
    $current_year=date('Y');
    $line_number=get_line_number($db);
    //show($line_number);
    //create Report No, record Report On Timetag, Open By
    $report_number='REGII-'.date('y').'-'.sprintf("%04d", $line_number+1);
    $query="INSERT INTO injuryreport
    (injuryreport_number,
    injuryreport_timetag_report,
    injuryreport_openby,
    injuryreport_year,
    injuryreport_status)
    VALUES
    (
    '".$report_number."',
    '".time()."',
    '".$_SESSION['temp']['id']."',
    '".$current_year."',
    'Created'
    )
  ";
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();


  $_POST['injuryreport_number']=$report_number;
  ajax_load([['injuryreport_number',"'".$report_number."'"],['edit_injury',"'show'"]],'injury-ajax.php','report-box');
  ajax_load([['injuryreport_number',"'".$report_number."'"],['dialog',"'edit'"]],'injury-ajax.php','dialog-box');
  ajax_load(array(),'injury-ajax.php','dashboard-box');
  $entry='Report Created by '.$_SESSION['temp']['id'];
  add_entry_log($db,$report_number,$entry);
}

function ajax_update_report($db){
    $allfield[]='injuryreport_name';
    $allfield[]='injuryreport_name';
    $allfield[]='injuryreport_name';
    $allfield[]='injuryreport_name';
    $allfield[]='injuryreport_name';
    $allfield[]='injuryreport_name';
    $allfield[]='injuryreport_name';
    $allfield[]='injuryreport_name';
    $allfield[]='injuryreport_name';
    $allfield[]='injuryreport_name';


    $data[]=['save_report',"'save'"];
    

    ajax_button('save_report',$data,'injury-ajax.php','report-box');
}

function checkstatus($db,$report_number){
    $query='SELECT injuryreport_status,injuryreport_investigation_status,injuryreport_followup_status
	  FROM injuryreport
	  Where injuryreport_number=\''.$report_number.'\'
      ';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
    return $row;
}

function checkopenby($db,$report_number){
    $query='SELECT injuryreport_openby
	  FROM injuryreport
	  Where injuryreport_number=\''.$report_number.'\'
      ';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
    return $row;
}

function checkifsubmitallowed($db,$report_number){
    $query='SELECT *
	  FROM injuryreport
	  Where injuryreport_number=\''.$report_number.'\'
      and injuryreport_name is not NULL
      and injuryreport_timetag_incident is not NULL
      and injuryreport_location is not NULL
      and injuryreport_injury1 is not NULL
      and injuryreport_injury_description is not NULL
      and injuryreport_treatment_give is not NULL
      and injuryreport_first_aid_provider is not NULL
      and injuryreport_further_treament_req is not NULL
      and injuryreport_supervisor_advised is not NULL
      and injuryreport_how_happen is not NULL
      and injuryreport_plant_damaged is not NULL
      and injuryreport_witnesses is not NULL

	  
	';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
    if(empty($row)){
        $return='disabled';
    }else{
        $return='';
    }
    

    return $return;
}

function checkif_close_investigation_allowed($db,$report_number){
    $query='SELECT *
    FROM injuryreport
    Where injuryreport_number=\''.$report_number.'\'
    and injuryreport_investigation_details is not NULL
    and injuryreport_further_action is not NULL
    and (injuryreport_worksafenotify=\'No\' 
    or (injuryreport_worksafenotify=\'Yes\' and
        injuryreport_worksafeREF is not NULL
    and injuryreport_worksafe_receipt is not NULL))

    
  ';
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  if(empty($row)){
      $return='disabled';
  }else{
      $return='';
  }
  

  return $return;
}

function checkif_close_followup_allowed($db,$report_number){
    $query='SELECT *
    FROM injuryreport
    Where injuryreport_number=\''.$report_number.'\'
    and injuryreport_treatment_after_report is not NULL
    and injuryreport_category is not NULL
    and (
        injuryreport_LTI=\'No\' or (
            injuryreport_LTI=\'Yes\' and
            injuryreport_LTI_hours is not NULL
            )
        )
    and (
        injuryreport_workcovernotify=\'No\' or (
            injuryreport_workcovernotify=\'Yes\' and
            injuryreport_workcoverREF is not NULL and
            injuryreport_workcover_date_accepted is not NULL
            )
        )

    
  ';
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  if(empty($row)){
      $return='disabled';
  }else{
      $return='';
  }
  

  return $return;
}


function update_report($db){
    $allfield[]='injuryreport_name';
    $allfield[]='injuryreport_timetag_incident';
    $allfield[]='injuryreport_location';
    $allfield[]='injuryreport_injury1';
    $allfield[]='injuryreport_injury2';
    $allfield[]='injuryreport_injury3';
    $allfield[]='injuryreport_injury4';
    $allfield[]='injuryreport_injury_description';
    $allfield[]='injuryreport_treatment_give';
    $allfield[]='injuryreport_first_aid_provider';
    $allfield[]='injuryreport_further_treament_req';
    $allfield[]='injuryreport_supervisor_advised';
    $allfield[]='injuryreport_how_happen';
    $allfield[]='injuryreport_plant_damaged';
    $allfield[]='injuryreport_comments';
    $allfield[]='injuryreport_witnesses'; 

    $query="UPDATE injuryreport
    SET ";
    $i=0;
    foreach($allfield as $field){
        if(empty($_POST[$field])){$result='NULL';}else{$result="'".$_POST[$field]."'";}
        if($i<>0){$query=$query.",";}
        $query=$query." $field=$result";
        $i++;
    }


    $query=$query." WHERE injuryreport_number='".$_POST['injuryreport_number']."'";

    
   
    
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();
  $entry='Report Updated by '.$_SESSION['temp']['id'];
  add_entry_log($db,$_POST['injuryreport_number'],$entry);
}

function update_investigation($db){
    $allfield[]='injuryreport_worksafenotify';
    $allfield[]='injuryreport_worksafeREF';
    $allfield[]='injuryreport_worksafe_receipt';
    $allfield[]='injuryreport_investigation_details';
    $allfield[]='injuryreport_further_action';
    $allfield[]='injuryreport_investigation_comments'; //
    $allfield[]='injuryreport_machinenumber'; 

    $query="UPDATE injuryreport
    SET ";
    $i=0;
    foreach($allfield as $field){
        if(empty($_POST[$field])){$result='NULL';}else{$result="'".$_POST[$field]."'";}
        if($i<>0){$query=$query.",";}
        $query=$query." $field=$result";
        $i++;
    }


    $query=$query." WHERE injuryreport_number='".$_POST['injuryreport_number']."'";

    
   
    
  
  $sql = $db->prepare($query); 
  show($query);
  $sql->execute();
  $entry='Investigation Updated by '.$_SESSION['temp']['id'];
  add_entry_log($db,$_POST['injuryreport_number'],$entry);
}

function update_followup($db){
    $allfield[]='injuryreport_workcovernotify';
    $allfield[]='injuryreport_workcoverREF';
    $allfield[]='injuryreport_workcover_date_accepted';
    $allfield[]='injuryreport_treatment_after_report';
    $allfield[]='injuryreport_category';
    $allfield[]='injuryreport_LTI'; 
    $allfield[]='injuryreport_LTI_hours'; 

    $query="UPDATE injuryreport
    SET ";
    $i=0;
    foreach($allfield as $field){
        if(empty($_POST[$field])){$result='NULL';}else{$result="'".$_POST[$field]."'";}
        if($i<>0){$query=$query.",";}
        $query=$query." $field=$result";
        $i++;
    }


    $query=$query." WHERE injuryreport_number='".$_POST['injuryreport_number']."'";

    
   
    
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();
  $entry='Investigation Updated by '.$_SESSION['temp']['id'];
  add_entry_log($db,$_POST['injuryreport_number'],$entry);
}

function close_investigation($db,$report_number){
    change_investigation_status($db,'Closed');
    $allfield[]='injuryreport_investigation_closedby';
    $allfield[]='injuryreport_investigation_closedate'; 

    $query="UPDATE injuryreport
    SET injuryreport_investigation_closedby='".$_SESSION['temp']['id']."',
    injuryreport_investigation_closedate='".time()."'
    WHERE injuryreport_number='".$report_number."'";

    
   
    
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();
  $entry='Investigation Closed by '.$_SESSION['temp']['id'];
  add_entry_log($db,$_POST['injuryreport_number'],$entry);
}

function close_followup($db,$report_number){
    change_followup_status($db,'Closed');
    $allfield[]='injuryreport_followup_closedby';
    $allfield[]='injuryreport_followup_closedate'; 

    $query="UPDATE injuryreport
    SET injuryreport_followup_closedby='".$_SESSION['temp']['id']."',
    injuryreport_followup_closedate='".time()."'
    WHERE injuryreport_number='".$report_number."'";

    
   
    
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();
  $entry='Investigation Closed by '.$_SESSION['temp']['id'];
  add_entry_log($db,$_POST['injuryreport_number'],$entry);
}

function change_report_status($db,$status){
    $query="UPDATE injuryreport
    SET 
    injuryreport_status='".$status."'
    WHERE injuryreport_number='".$_POST['injuryreport_number']."'";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $entry='Report Status changed to '.$status.' by '.$_SESSION['temp']['id'];
    change_investigation_status($db,'Required','AND injuryreport_investigation_status is NULL');
    change_followup_status($db,'Required','AND injuryreport_followup_status is NULL');
    add_entry_log($db,$_POST['injuryreport_number'],$entry);
}

function change_investigation_status($db,$status,$option1=''){
    $query="UPDATE injuryreport
    SET 
    injuryreport_investigation_status='".$status."'
    WHERE injuryreport_number='".$_POST['injuryreport_number']."' ".$option1." ";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $entry='Investigation Status changed to '.$status.' by '.$_SESSION['temp']['id'];
    add_entry_log($db,$_POST['injuryreport_number'],$entry);
}

function change_followup_status($db,$status,$option1=''){
    $query="UPDATE injuryreport
    SET 
    injuryreport_followup_status='".$status."'
    WHERE injuryreport_number='".$_POST['injuryreport_number']."' ".$option1." ";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $entry='HR Follow-up Status changed to '.$status.' by '.$_SESSION['temp']['id'];
    add_entry_log($db,$_POST['injuryreport_number'],$entry);
}

function get_line_number($db){
	$current_year=date('Y');
	$query='SELECT top (1) (injuryreport_number)
	  FROM injuryreport
	  Where injuryreport_year=\''.$current_year.'\'
	  ORDER BY injuryreport_number DESC
	';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
	//
	// $temp=$row[0];
	$row[0]=substr ( $row[0] , -3 );
	$row[0]=(int)$row[0];
	
	
	return $row[0];
}

function get_injury_report($db,$report_number){
    $query='SELECT *
	  FROM injuryreport
	  Where injuryreport_number=\''.$report_number.'\'
	  
	';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
    return $row;
}

function get_investigation_report($db,$report_number){
    $query='SELECT *
	  FROM injuryreport
	  Where injuryreport_number=\''.$report_number.'\'
	  
	';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
    return $row;
}

function get_followup($db,$report_number){
    $query='SELECT *
	  FROM injuryreport
	  Where injuryreport_number=\''.$report_number.'\'
	  
	';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
    return $row;
}

function get_all_injury_log($db,$report_number){
    $query='SELECT *
	  FROM injurylog
      WHERE injurylog_injury_number=\''.$report_number.'\'
      order by injurylog_timetag ASC
	  
	  
	';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
    return $row;
}

function get_all_injury_notes($db,$report_number){
    $query='SELECT *
	  FROM injurynotes
      WHERE injurynotes_injury_number=\''.$report_number.'\'
      order by injurynotes_timetag ASC
	  
	  
	';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
    return $row;
}

function count_all_injury_notes($db,$report_number){
    $query='SELECT Count(injurynotes_number) as thecount
	  FROM injurynotes
      WHERE injurynotes_injury_number=\''.$report_number.'\'
      
	  
	  
	';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
    return $row['thecount'];
}

function get_all_injury_report($db){
    $query='SELECT *
	  FROM injuryreport
	  order by injuryreport_number desc
	  
	';//injuryreport_number,injuryreport_status,injuryreport_investigation_status,
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
    return $row;
}

function get_all_injury_type($db){
    $query='SELECT *
	  FROM injury
	  Where injury_type_body=\'type\'
	  order by injury_sort ASC
	';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
    return $row;
}

function get_all_injury_body($db){
    $query='SELECT *
	  FROM injury
	  Where injury_type_body=\'body\'
      order by injury_sort ASC
	  
	';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
    return $row;
}

function check_if_body_part_needed($db){
    $query='SELECT injury_body_require
	  FROM injury
	  Where injury_name=\''.$_POST['injurytype'].'\'';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
    if(!empty($row['injury_body_require'])){
        $_POST['choose_body_part']=1;
    }else{
        $_POST['injurybody']='';
    }
    
}

function check_if_left_right_needed($db){
    $query='SELECT injury_leftright
	  FROM injury
	  Where injury_name=\''.$_POST['injurybody'].'\'';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
    if(!empty($row['injury_leftright'])){
        $_POST['choose_left_right']=1;
    }else{
        $_POST['injury_leftright']='';
    }
    
}

function create_injury($db){
    $injury=$_POST['injuryintensity'].' '.$_POST['injurytype'];
    if(!empty($_POST['injurybody'])){
        $injury=$injury.' on the';
        if(!empty($_POST['injuryleftright'])){
            $injury=$injury.' '.$_POST['injuryleftright'];
        }
        $injury=$injury.' '.$_POST['injurybody'];
    }
    
    
    return $injury;
}

function delete_report($db){
    $query="DELETE FROM injuryreport
    WHERE injuryreport_number='".$_POST['injuryreport_number']."'";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $query="DELETE FROM injurylog
    WHERE injurylog_injury_number='".$_POST['injuryreport_number']."'";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
}

function add_entry_log($db,$injury_number,$entry,$type='',$REF=''){

    if(!empty($type)){
        $addon1=',injurylog_type';
        $addon2=",'".$type."'";
    }
    if(!empty($REF)){
        $addon1=$addon1.',injurylog_REF';
        $addon2=$addon2.",'".$REF."'";
    }
    $query="INSERT INTO injurylog
    (injurylog_injury_number,
    injurylog_timetag,
    injurylog_added_by,
    injurylog_entry".$addon1.")
    VALUES
    (
    '".$injury_number."',
    '".time()."',
    '".$_SESSION['temp']['id']."',
    '".$entry."'
    ".$addon2.")
    ";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
}

function get_all_attachment($db,$report_number){
    $query='SELECT *
	  FROM injuryattachment
      WHERE injuryattachment_injury_number=\''.$report_number.'\'
      order by injuryattachment_date_added ASC
	  
	  
	';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
    return $row;
}

function show_all_attachment($db,$report_number){
    echo'<div class="row all_attachement main-box" >';
        echo'<div class="row attachement_title" >Document</div>';
        
        echo'<br>';
        foreach(get_all_attachment($db,$report_number) as $attachment){
            echo'<div class="row attachement_row" >';
                echo'<div class="col-sm-10" >';
            
                    echo'<a target="blank" href="injury_report/attachment/'.$attachment['injuryattachment_name'].'"><div class=" btn btn-primary injury_button" >';
                    if(empty($attachment['injuryattachment_caption'])){
                        echo str_replace($report_number.'-', '', $attachment['injuryattachment_name']);
                    }else{
                        echo $attachment['injuryattachment_caption'];
                    }
                    
                    echo'</div></a>';
                echo'</div>';
                echo'<div class="col-sm-2" ><div class=" btn btn-primary injury_button" onclick="thename=\''.$attachment['injuryattachment_name'].'\';delete_attachment();">X</div>';
                echo'</div>';
                
            echo'</div>';
        }
        echo'<br>';
        echo'<br>';
        echo'<div class="row attachement_row btn btn-primary injury_button" onClick="thenumber=\''.$report_number.'\';add_attachment()">Add Document</div>';
    echo'</div>';
    ajax_button('add_attachment',[['injuryreport_number','thenumber'],['attachment',"'add_document'"]],'injury-ajax.php','hidden-box');
    ajax_button('delete_attachment',[['injuryreport_number','thenumber'],['attachment_number','thename'],['attachment',"'delete_document'"],['dialog',"'show_injury'"]],'injury-ajax.php','dialog-box');
}

function show_window_add_document($db,$report_number){
    echo'<script>
    $(\'.ui-dialog\').remove();
    $( function() {
    $( "#dialog-'.$report_number.'" ).dialog({
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

    echo'<div id="dialog-'.$report_number.'" title="Add Document '.$report_number.'">
        <div class="row"><form id="data" method="post" enctype="multipart/form-data">';
            echo'<div class="row"><input class="form-control" type="file" name="fileToUpload" placeholder="fileToUpload" ></div>';
            echo'<div class="row"><input class="form-control" type="text" name="filename" id="filename" placeholder="Name of the File"></div>';
            echo'<div class="row"><input class="form-control" type="text" name="caption" id="caption" placeholder="Description"></div>';
            echo'<input class="form-control" type="hidden" name="injuryreport_number" id="injuryreport_number" value="'.$report_number.'" >';
            echo'<input class="form-control" type="hidden" name="add_attachment" id="add_attachment" value="yes" >';
           
            echo'<div class="row"><button id="create_new" class="btn btn-primary injury_button" onclick="submitthat();">Upload</button></div>';
        echo'</form></div>
    </div>'; 
    
}

function upload_document($db){
    $target_dir = "injury_report/attachment/";
    $path = $_FILES['fileToUpload']['name'];
	$extension = pathinfo($path, PATHINFO_EXTENSION);
	//show('extension:'. $extension);
    $new_name=$_POST['injuryreport_number']."-".nextattachment_injury($db,$_POST['injuryreport_number'])."-";
    if(!empty($_POST['filename'])){
        $new_name=$new_name.$_POST['filename'];
    }else{
        $new_name=$new_name.$_POST['injuryreport_number'];
    }
    $new_name=$new_name.".".$extension;
	$target_file = $target_dir .$new_name ;
    //show('target_file:'. $target_file);
	$uploadOk = 1;
	

	if (file_exists($target_file)) {
	echo "Sorry, file already exists.";
	$uploadOk = 0;
	}
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
       
    } 
    else 
    {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            
            
            //add the line in the database
            $attachment_number=nextattachment_injury($db,$_POST['injuryreport_number']);
            $issue_number=$_POST['injuryreport_number'];
            $name=$new_name;
            $added_by=$_SESSION['temp']['id'];
            $tempdate=new datetime(date());
            $date_added = $tempdate->getTimestamp();
            
            $caption=$_POST['caption'];

            if(empty($caption)){$caption=$_FILES["fileToUpload"]["name"];}
            
            
            
            $query="INSERT INTO dbo.injuryattachment
                ( injuryattachment_number,
                injuryattachment_injury_number,
                injuryattachment_name,
                injuryattachment_path,
                injuryattachment_added_by,
                injuryattachment_date_added,
                injuryattachment_caption
                
                ) 
                VALUES (
                '$attachment_number',
                '$issue_number',
                '$name',
                '$name',
                '$added_by',
                '$date_added',
                '$caption')";	
                
                
                //show($query);
                
                $sql = $db->prepare($query); 

                $sql->execute();
                
                $entry='Document '.$name.' added by '.$_SESSION['temp']['id'];
                add_entry_log($db,$_POST['injuryreport_number'],$entry);
                
                
            
        } else {
            echo "Sorry, there was an error uploading your file.". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
        }
    }
}


function nextattachment_injury($db,$report_number){
	$query='SELECT top (1) (injuryattachment_number)
	  FROM injuryattachment
	  Where injuryattachment_injury_number=\''.$report_number.'\'
	  ORDER BY injuryattachment_number DESC';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
	//
	// $temp=$row[0];
	$row[0]=max($row[0],0);
	$row[0]=$row[0]+1;
	
	//show('nextnumber:'.$row[0]);
	return $row[0];
}

function delete_document($db,$attachment_number){
    $query='DELETE FROM dbo.injuryattachment 
        WHERE injuryattachment_name=\''.$attachment_number.'\' ';
    $sql = $db->prepare($query); 

    $sql->execute();


    //delete file
    $target_dir = "injury_report/attachment/";
    $new_name=$attachment_number;

    $target_file = $target_dir .$new_name ;
    unlink( $target_file);
    $entry='Document '.$new_name.' deleted by '.$_SESSION['temp']['id'];
    add_entry_log($db,$_POST['injuryreport_number'],$entry);
}

function nextnotes_injury($db,$report_number){
	$query='SELECT top (1) (injurynotes_number)
	  FROM injurynotes
	  Where injurynotes_injury_number=\''.$report_number.'\'
	  ORDER BY injurynotes_number DESC';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
	//
	// $temp=$row[0];
	$row[0]=max($row[0],0);
	$row[0]=$row[0]+1;
	
	//show('nextnumber:'.$row[0]);
	return $row[0];
}

function add_notes($db){
    $attachment_number=nextnotes_injury($db,$_POST['injuryreport_number']);
    $issue_number=$_POST['injuryreport_number'];
    $added_by=$_SESSION['temp']['id'];
    $tempdate=time();
    $entry=$_POST['notes_to_add'];
    $query="INSERT INTO dbo.injurynotes
    ( injurynotes_number,
    injurynotes_injury_number,
    injurynotes_added_by,
    injurynotes_timetag,
    injurynotes_entry
    
    ) 
    VALUES (
    '$attachment_number',
    '$issue_number',
    '$added_by',
    '$tempdate',
    '$entry')";	
    
    
    //show($query);
    
    $sql = $db->prepare($query); 

    $sql->execute();
    
    $entry='Notes added by '.$_SESSION['temp']['id'];
    add_entry_log($db,$_POST['injuryreport_number'],$entry);
}

function remove_injurynotes($db){
    $query='DELETE FROM dbo.injurynotes 
        WHERE injurynotes_number=\''.$_POST['injurynotes_number'].'\' 
        AND injurynotes_injury_number=\''.$_POST['injuryreport_number'].'\' ';
    $sql = $db->prepare($query); 

    $sql->execute();
    $entry='Notes deleted by '.$_SESSION['temp']['id'];
    add_entry_log($db,$_POST['injuryreport_number'],$entry);

}

function show_dashboard_injury($db){
    echo'<div class="row ">';
        
        echo'<div class="col-sm-2">';
        showtile('Injuries',count_injury_last365days($db));
        echo'</div>';
        echo'<div class="col-sm-2">';
        showtile('Days since last injury',days_since_last_injury($db));
        echo'</div>';
        echo'<div class="col-sm-2">';
        showtile('Total LTI',number_format(total_LTI($db)).' hours');
        echo'</div>';
        echo'<div class="col-sm-4" >';
        //showbodypart($db);
        
        echo'</div>';
    echo'</div>';
   
}

function showtile($title,$value){
    echo'<div class="row tile">';
        echo'<div class="tile_titre ">'.$title.'</div>';
        echo'<br>';
        echo'<div class="tile_value ">'.$value.'</div>';
    echo'</div>';
}

function count_injury_last365days($db){
    $query='SELECT Count(injuryreport_number) as thecount
    FROM injuryreport
    WHERE injuryreport_timetag_report>=\''.(time()-365*24*3600).'\'
    ';
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  return $row['thecount'];
}

function total_LTI($db){
    $query='SELECT sum(injuryreport_LTI_hours) as thecount
    FROM injuryreport
    WHERE injuryreport_timetag_report>=\''.(time()-365*24*3600).'\'
    ';
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  return round($row['thecount'],1);
}

function days_since_last_injury($db){
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

function showbodypart($db){
    echo'<div class="row tile" style="">';
        echo'<div class=" "><img class="attachment" src="img/body-diagram.jpg" width="100%">';
        echo'<div class="locator hidestart" id="locator-test-0"  style="  left: 25%;top: 25%;"></div>';
        echo'</div>';
    echo'</div>';
    
     echo'<style>       
    .locator{
        position: absolute;
        display: block; 
        transition-duration: 1s;
        -webkit-transform : scale(1.75);
        border: 0.5px solid black;
        border-radius: 10px;
        width:20px;
        height:20px;
        font-size:15px
    
    }
    </style>';
}


function get_report_opened_but_not_submited(){
    $db=$GLOBALS['db'];
    $user_id=$_SESSION['temp']['id'];
    $query="SELECT *
    FROM injuryreport
    WHERE injuryreport_openby='$user_id' and injuryreport_status='Created'
    order by injuryreport_number desc";
    $sql = $db->prepare($query); 
    $sql->execute();
    //show($query);
    $row=$sql->fetchall();
    return $row;
}






//Dashboard v2 
function show_dasboard_injury_v2(){?>
    
    <!-- Main body of the dashboard -->
    <div id="body_dashboard" class="body_dashboard p-2">
        <div class="row">
            <!-- Main body of the dashboard -->
            <div class="col-md-8 border-pls dashboard_left">
                <!-- Chart/Graph to show the result -->
                <?php dashboard_injury_v2_chart()?>
                
                
            </div>
            <div class="col-md-4 border-pls">
                <div class="row">
                    <div class="col-md-6"><div id="show_stats_view" class="btn-dashboard btn-active" onclick="show_view('stats_view');">Overall Stats</div></div>
                    <div class="col-md-6"><div id="show_body_view" class="btn-dashboard" onclick="show_view('body_view');">Body View</div></div>
                    
                    
                </div>
                <div class="row mtb-2">
                
                    <div id="body_view" style="display:none" class="flip-card">
                        <!-- Visual of which body part has been affected -->
                        <?php dashboard_injury_v2_body_scan()?>
                    </div>
                    <div id="stats_view" class="flip-card">
                        <!-- Overall Stats -->
                        <?php dashboard_injury_v2_stats()?>
                    </div>
                    
                    
                    
                </div>
                
            </div>
            
        </div>
        <div class="row">
            <div>
                
               <?php view_all_report_from_dashboard();
               ?> 
                
            </div>
        </div>
    </div>
   
    <?php //all_css_dasboard()?>
    <?php
}

//navbar for the dashboard
function dashboard_injury_v2_navbar(){?>
    <!-- //all Tag used for filtering -->
    <div class="row">
        <?php $filters=get_filter_to_show();
        $count=0;
        foreach($filters as $filter){
            //show tag for each filter done ?>
            <div class="tag-filter border-pls">
                <?php echo $filter?>&nbsp;&nbsp;&nbsp;<b>
                    <span 
                    style="cursor: pointer;" 
                    onclick="document.getElementById('tag_<?php echo$count?>').submit()"
                    >X</span>
                </b>
            </div>
            <form method="POST" id="tag_<?php echo$count?>">
                <input type="hidden" name="action" value="show_dashboard">
                <input type="hidden" name="remove_filter" value="yes">
                <input type="hidden" name="item" value="<?php echo $filter?>">
            </form>
            
            <?php
        $count++;
        }?>
    </div>
    <!-- //filtering by location/body parts/ injury type / time of the day-->
    <div class="row mt-2" style="margin-bottom:6px">
        <div class="col-md-3">
            <form method="POST">
            <input type="hidden" name="action" value="show_dashboard">
            <input type="hidden" name="manage_filter" value="yes">
            <select class="form-control btn-dashboard" name="location" oninput="submit()">
                <?php $locations=[
                'All Locations ',
                'Assembly',
                'Moulding',
                'Machining',
                'Engineering',
                'Store',
                'Office',
                'Lab',
                'Other',
                'Home',
                ];
                foreach($locations as $location){?>
                    <option ><?php echo $location?></option>
                <?php }?>
                
            </select>
            </form>
        </div>
        <div class="col-md-3">
            <form method="POST">
                <input type="hidden" name="action" value="show_dashboard">
                <input type="hidden" name="manage_filter" value="yes">
                <select class="btn-dashboard" name="body_part" oninput="submit()">
                    <?php $bodyparts=[
                    'All Body Parts',
                    'Head/Face',
                    'Neck',
                    'Upper back',
                    'Lower Back',
                    'Abdomen',
                    'Pelvis/hip/groin',
                    'Shoulder',
                    'Upper Arm',
                    'Elbow',
                    'Lower Arm',
                    'Hand/Wrist/Fingers',
                    'Upper leg',
                    'Knee',
                    'Ankle',
                    'Heel/Achilles',
                    'Foot/Toes'
                    ];
                    foreach($bodyparts as $bodypart){?>
                        <option ><?php echo $bodypart?></option>
                    <?php }?>
                    
                </select>
            </form>
        </div>
        <div class="col-md-3">
            <form method="POST">
                <input type="hidden" name="action" value="show_dashboard">
                <input type="hidden" name="manage_filter" value="yes">
                <select class="btn-dashboard" name="injury_type" oninput="submit()">
                    <?php $types=[
                    'All Injury Types',
                    "Allergic reaction",
                    "Amputation",
                    "Asthma attack/Respiratory problem",
                    "Bites and stings",
                    "Bruising/Contusion",
                    "Burns/scalds (hot or cold)",
                    "Chest pains/Heart attack",
                    "Choking",
                    "Concussion",
                    "Crush",
                    "Cuts/lacerations/abrasions",
                    "Diabetic emergency",
                    "Dislocation",
                    "Electric shock",
                    "Epileptic seizure",
                    "Eye injuries",
                    "Fatality",
                    "Fracture",
                    "Heat stress/stroke",
                    "Loss of consciousness",
                    "Mental Health episode",
                    "Other",
                    "Pain",
                    "Penetrating wound",
                    "Poisoning",
                    "Shock",
                    "Spinal injury",
                    "Sprain and strain",
                    "Stroke"
                    ];
                    foreach($types as $type){?>
                        <option ><?php echo $type?></option>
                    <?php }?>
                    
                </select>
            </form>
        </div>
        <div class="col-md-3">
            <form method="POST">
                <input type="hidden" name="action" value="show_dashboard">
                <input type="hidden" name="manage_filter" value="yes">
                <select class="btn-dashboard" name="time_of_day" oninput="submit()">
                    <?php $bodyparts=[
                    'All Time of the day',
                    'Morning',
                    'Afternoon',
                    'Night',
                    '-----',
                    '0h-4h', 
                    '4h-8h',
                    '8h-12h',
                    '12h-16h',
                    '16h-20h',
                    '20h-24h'             
                    ];
                    foreach($bodyparts as $bodypart){?>
                        <option ><?php echo $bodypart?></option>
                    <?php }?>
                    
                </select>
            </form>
        </div>
    </div>
    <style>
        .tag-filter{
            float:left;
            padding:5px;
            border-radius:10px !important;
            margin-right:5px;
            margin-bottom:5px;
            
        }
    </style>
    <?php
}
//Drawing of which body part for the dashboard
function dashboard_injury_v2_body_scan(){
    $db=$GLOBALS['db'];
    $addon=apply_all_filter();

    //find the location with the most reports
    $query="SELECT count([injuryreport_number]) as thecount ,injury_name
    FROM injuryreport
    left join (
    SELECT * from injury where injury_type_body='body'
    )as a on ([injuryreport_injury1] like Concat('%',injury_name,'%') or [injuryreport_injury2] like Concat('%',injury_name,'%') or[injuryreport_injury3] like Concat('%',injury_name,'%') )
    where [injuryreport_timetag_incident]is not null $addon
    group by injury_name
    order by thecount desc";
    $sql = $db->prepare($query); 
	$sql->execute();
    $all_body=$sql->fetchall();
    //show($query);
    foreach($all_body as $body){
        $reformat_bodies[$body['injury_name']]=$body['thecount'];
    }
    //show($reformat_bodies);
    ?>
    <div class="row border-pls text-center">
        <img src="img/human.png" class="human">
        <?php $bodyparts=[
            ['Head',15,8,$reformat_bodies['Head/Face'],'Head/Face'],
            ['Neck',55,15,$reformat_bodies['Neck'],'Neck'],
            ['Upper back',38,25,$reformat_bodies['Upper back'],'Upper back'],
            ['Lower Back',38,34,$reformat_bodies['Lower back'],'Lower back'],
            ['Abdomen',39,41,$reformat_bodies['Abdomen'],'Abdomen'],
            ['Pelvis',39,50,$reformat_bodies['Pelvis/hip/groin'],'Pelvis/hip/groin'],
            ['Shoulder',16,18,$reformat_bodies['Shoulder'],'Shoulder'],
            ['Upper Arm',65,28,$reformat_bodies['Upper Arm'],'Upper Arm'],
            ['Elbow',17,33,$reformat_bodies['Elbow'],'Elbow'],
            ['Lower Arm',70,40,$reformat_bodies['Lower Arm'],'Lower Arm'],
            ['Hand',8,49,$reformat_bodies['Hand/Wrist/Fingers'],'Hand/Wrist/Fingers'],
            ['Upper leg',20,58,$reformat_bodies['Upper leg'],'Upper leg'],
            ['Knee',20,70,$reformat_bodies['Knee'],'Knee'],
            ['Ankle',22,86,$reformat_bodies['Ankle'],'Ankle'],
            ['Heel',61,89,$reformat_bodies['Heel/Achilles'],'Heel/Achilles'],
            ['Foot',20,92,$reformat_bodies['Foot/Toes'],'Foot/Toes']
        ];
        foreach($bodyparts as $bodypart){
            if($bodypart[3]>0){?>
            <div class="locator "
            style="left: <?php echo $bodypart[1]?>%;top: <?php echo $bodypart[2]?>%;cursor:pointer;"
            onclick="document.getElementById('body_part').value='<?php echo $bodypart[4]?>';document.getElementById('filter_body').submit();"
            >
            <?php echo $bodypart[0]?><br><?php echo $bodypart[3]?>
            </div>
            <?php
            }
        }?>
        <form method="POST" id=filter_body>
            <input type="hidden" name="action" value="show_dashboard">
            <input type="hidden" name="manage_filter" value="yes">
            <input type="hidden" name="body_part" value="" id="body_part">
        </form>

    </div>
    
    
    <style>       
    .locator{
        position: absolute;
        display: block; 
        border: 0.5px solid black;
        border-radius: 20px;
        width:80px;
        height:30px;
        font-size:10px;
        font-weight: bold;
    
    }
    .head{
        left: 25%;top: 25%;
    }
    </style>
    <?php
}
//Chart for the dashboard
function dashboard_injury_v2_chart(){
    $all_data=reformat_data_for_chart(get_injury_report_data_for_chart())
    //show($all_report);

    ?>
    <div class="row border-pls" style="width: 100%; ">
        <!-- navbar for dashboard, with all the filters -->
        <?php dashboard_injury_v2_navbar()?>
        <div class="col-md-2 text-center ">
            <br>
            <form method="POST" id="refresh_chart">
                <input type="hidden" name="action" value="show_dashboard">
                <button class="btn-dashboard mtb-2" onclick="submit()"><span class="glyphicon glyphicon-refresh"></span> Refresh</button><br>
            </form>
            <br>
            <script>
                function change_cat(cat){
                    document.getElementById('thecat').value=cat;
                    document.getElementById('change_cat').submit();
                }
            </script>
            <?php $active='';if($_SESSION['temp']['injury']['cat']=='quantity'){$active='btn-active';}?>
            <button class="btn-dashboard mtb-2 <?php echo$active?>" onclick="change_cat('quantity')">Quantity</button><br>
            <!-- <button class="btn-dashboard mtb-2">Time of the Day</button><br> -->
            <?php $active='';if($_SESSION['temp']['injury']['cat']=='location'){$active='btn-active';}?>
            <button class="btn-dashboard mtb-2 <?php echo$active?>" onclick="change_cat('location')">Location</button><br>

            <form method="POST" id="change_cat">
                <input type="hidden" name="action" value="show_dashboard">
                <input type="hidden" name="change_cat" value="yes">
                <input type="hidden" id="thecat" name="cat" value="">
            </form>


        </div>
        <div class="col-md-10">
            <?php if(!empty($all_data['string'])){?>
                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script type="text/javascript">
                    google.charts.load("current", {packages:['corechart']}).then(drawChart_injury);
                    //google.charts.setOnLoadCallback(drawChart);
                    //$(window).resize(drawChart);
                    function drawChart_injury() {
                        var data = google.visualization.arrayToDataTable([<?php echo $all_data['string']?>]);
                        var view = new google.visualization.DataView(data);
                        <?php if($_SESSION['temp']['injury']['cat']=='quantity'){?>
                            view.setColumns([0, 1]);
                        <?php }?>
                        

                        var options = {
                            title: "Number of Incidents Reported",
                            legend: { position: "none" },
                            isStacked: true,
                        };
                        var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
                        chart.draw(view, options);
                    }
                </script>
                <div id="columnchart_values" style="width: 100%; height: 50vh;"></div>
                <?php dashboard_injury_v2_time_period_filter()?>
            <?php }else{?>
                <div  style="width: 100%; height: 50vh;text-align:center"><br><br><br><br><br><br><br>No Reports</div>
            <?php }?>
        </div>
        
    </div>
    <?php
}
//Overall Stats for the dashboard
function dashboard_injury_v2_stats(){
    $stats=get_stats_injury(get_injury_report_data_for_chart())?>
    <div class="row">
        <?php show_stats_card('Days since last reports',$stats['day_since_last'])?>
        <?php show_stats_card('Total reports',$stats['total_reports'])?>
    </div>
    <div class="row">
        <?php //show_stats_card('Area with most reports',$stats['location_with_most'])?>
        <?php //show_stats_card('','')?>
    </div>  
    <div class="row">
        <?php show_stats_card('Reports closed',$stats['total_reports_closed'])?>
        <?php show_stats_card('Reports still open',$stats['total_reports_opened'])?>
    </div>
    <div class="row">
        <?php show_stats_card('Investigation on-going',$stats['total_reports_investigation_open'])?>
        <?php show_stats_card('HR on-going',$stats['total_reports_hrfollowup_open'])?>
    </div>
      
   
    <?php
}
//Show a Stats Card
function show_stats_card($caption,$stats){?>
    <div class="col-md-6 text-center p-3" style="padding:10px">
        <div class="stats-card "><!-- border-pls -->
            <div class="row stats-header"><?php echo $caption?></div>
            <div class="row stats-value"><?php echo $stats?></div>
        </div>   
    </div>
    
    <?php
}
//Get all Stats for the dashboard
function get_stats_injury($reports){
    $db=$GLOBALS['db'];
    $addon=apply_all_filter();

    //find the location with the most reports
    $query="SELECT top 1 count(injuryreport_number) as thecount,injuryreport_location
    FROM injuryreport
    where [injuryreport_timetag_incident]is not null $addon
    group by injuryreport_location
    order by thecount desc";
    $sql = $db->prepare($query); 
	$sql->execute();
    $area=$sql->fetch();

    //find number of reports closed
    $query="SELECT count(injuryreport_number) as thecount
    FROM injuryreport
    where [injuryreport_timetag_incident]is not null $addon 
    and injuryreport_followup_status='Closed' 
    and injuryreport_investigation_status='Closed'";
    $sql = $db->prepare($query); 
	$sql->execute();
    $closed=$sql->fetch();
    
    //find number of reports investigation required
    $query="SELECT count(injuryreport_number) as thecount
    FROM injuryreport
    where [injuryreport_timetag_incident]is not null $addon 
    and injuryreport_investigation_status<>'Closed'";
    $sql = $db->prepare($query); 
	$sql->execute();
    $investigation=$sql->fetch();
    
    //find number of reports HR Follow-up required
    $query="SELECT count(injuryreport_number) as thecount
    FROM injuryreport
    where [injuryreport_timetag_incident]is not null $addon 
    and injuryreport_investigation_status<>'Closed'";
    $sql = $db->prepare($query); 
	$sql->execute();
    $hrfollowup=$sql->fetch();
    //find number of days since last report
    $query="SELECT max(injuryreport_timetag_incident) as max_timetag
    FROM injuryreport
    where [injuryreport_timetag_incident]is not null $addon ";
    $sql = $db->prepare($query); 
	$sql->execute();
    $day_since_last=$sql->fetch();
    
    $stats['total_reports']=count($reports);
    $stats['location_with_most']=$area['injuryreport_location'].' ('.$area['thecount'].')';
    $stats['total_reports_closed']=$closed['thecount'];
    $stats['total_reports_opened']=$stats['total_reports']-$stats['total_reports_closed'];
    $stats['total_reports_investigation_open']=$investigation['thecount'];
    $stats['total_reports_hrfollowup_open']=$hrfollowup['thecount'];
    $stats['day_since_last']=floor((time()-$day_since_last['max_timetag'])/(24*3600));
    return $stats;
}


//Bottom navbar for filtering the dashboard
function dashboard_injury_v2_time_period_filter(){?>
    <script>
        function show_time_period(period){
            document.getElementById('the_period').value=period;
            document.getElementById('time_period').submit();
        }
    </script>
    
    <div class="row border-pls text-center">
        <div class="col-md-2">
            <?php $active='';if($_SESSION['temp']['injury']['time_period']=='year'){$active='btn-active';}?>
            <button class="btn-dashboard mtb-2 <?php echo$active?>" onclick="show_time_period('year')">Per Year</button>
        </div>
        <div class="col-md-2">
            <?php $active='';if($_SESSION['temp']['injury']['time_period']=='month'){$active='btn-active';}?>
            <button class="btn-dashboard mtb-2 <?php echo$active?>" onclick="show_time_period('month')">Per Month</button>
        </div>
        <div class="col-md-2">
            <?php $active='';if($_SESSION['temp']['injury']['time_period']=='week'){$active='btn-active';}?>
            <button class="btn-dashboard mtb-2 <?php echo$active?>" onclick="show_time_period('week')">Per Week</button>
        </div>
        <div class="col-md-2">
            <?php $active='';if($_SESSION['temp']['injury']['time_period']=='weekday'){$active='btn-active';}?>
            <button class="btn-dashboard mtb-2 <?php echo$active?>" onclick="show_time_period('weekday')">Per Weekday</button>
        </div>
        
        <form method="POST" id="time_period">
            <input type="hidden" name="action" value="show_dashboard">
            <input type="hidden" name="change_time_period" value="yes">
            <input type="hidden" id="the_period" name="time_period" value="">
        </form>
        
        
        
        

    </div>
    <?php
}
//Query to get the data for the dashboard
function get_injury_report_data_for_chart(){
    $db=$GLOBALS['db'];
    
    $addon=apply_all_filter();
    $query="SELECT *
    FROM injuryreport
    where [injuryreport_timetag_incident]is not null $addon
    order by injuryreport_timetag_incident asc";
    $sql = $db->prepare($query); 
	$sql->execute();
    //show($query);

	$reports=$sql->fetchall();
    return $reports;
}


// Create the SQL Condition from the Filter array store in SESSION
function apply_all_filter(){
    $addon='';
    if(!empty($_SESSION['temp']['injury']['filter']['location'])){
        $addon=$addon.'and (';
        $count=0;
        foreach($_SESSION['temp']['injury']['filter']['location']as $item){
            if($count<>0){$addon=$addon.' or ';}
            $addon=$addon."injuryreport_location='$item'";
            $count++;
            
        }
        $addon=$addon.')';
    }
    if(!empty($_SESSION['temp']['injury']['filter']['body_part'])){
        $addon=$addon.'and (';
        $count=0;
        foreach($_SESSION['temp']['injury']['filter']['body_part']as $item){
            if($count<>0){$addon=$addon.' AND ';}
            $addon=$addon."(injuryreport_injury1 like'%$item%' OR
            injuryreport_injury2 like'%$item%' OR
            injuryreport_injury3 like'%$item%' )";
            $count++;
            
        }
        $addon=$addon.')';
        
    }
    if(!empty($_SESSION['temp']['injury']['filter']['injury_type'])){
        $addon=$addon.'and (';
        $count=0;
        foreach($_SESSION['temp']['injury']['filter']['injury_type']as $item){
            if($count<>0){$addon=$addon.' AND ';}
            $addon=$addon."(injuryreport_injury1 like'%$item%' OR
            injuryreport_injury2 like'%$item%' OR
            injuryreport_injury3 like'%$item%' )";
            $count++;
            
        }
        $addon=$addon.')';
        
    }

    // hours is calculated like that (injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0
    if(!empty($_SESSION['temp']['injury']['filter']['time_of_day'])){
        $addon=$addon.'and (';
        $count=0;
        foreach($_SESSION['temp']['injury']['filter']['time_of_day']as $item){
            if($count<>0){$addon=$addon.' OR ';}
            if($item=="Morning"){
                $addon=$addon."((injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0>=6 and 
                (injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0<12)";
            }
            if($item=="Afternoon"){
                $addon=$addon."((injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0>=12 and 
                (injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0<18)";
            }
            if($item=="Night"){
                $addon=$addon."((injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0>=18 or 
                (injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0<6)";
            }


            if($item=="0h-4h"){
                $addon=$addon."((injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0>=0 and 
                (injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0<4)";
            }
            if($item=="4h-8h"){
                $addon=$addon."((injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0>=4 and 
                (injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0<8)";
            }
            if($item=="8h-12h"){
                $addon=$addon."((injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0>=8 and 
                (injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0<12)";
            }
            if($item=="12h-16h"){
                $addon=$addon."((injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0>=12 and 
                (injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0<16)";
            }
            if($item=="16h-20h"){
                $addon=$addon."((injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0>=16 and 
                (injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0<20)";
            }
            if($item=="20h-244h"){
                $addon=$addon."((injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0>=20 and 
                (injuryreport_timetag_incident+3600*10)%(3600*24)/3600.0<24)";
            }
            
            $count++;
        }
        $addon=$addon.')';
        
    }

    return $addon;
}

//reformat the data for the chart
function reformat_data_for_chart($reports){
    $return=array();
    if($_SESSION['temp']['injury']['time_period']=='year'){$format="Y";}
    if($_SESSION['temp']['injury']['time_period']=='month'){$format="M-Y";}
    if($_SESSION['temp']['injury']['time_period']=='week'){$format="W-Y";}
    if($_SESSION['temp']['injury']['time_period']=='weekday'){$format="N";$return=array([],[],[],[],[],[],[]);}
    foreach($reports as $report){
        $return[date($format,$report['injuryreport_timetag_incident'])]['x']=date($format,$report['injuryreport_timetag_incident']);
        $return[date($format,$report['injuryreport_timetag_incident'])]['y']=$return[date($format,$report['injuryreport_timetag_incident'])]['y']+1;
        $return[date($format,$report['injuryreport_timetag_incident'])][$report['injuryreport_location']]['y']=$return[date($format,$report['injuryreport_timetag_incident'])][$report['injuryreport_location']]['y']+1;
        $all_location[$report['injuryreport_location']]=$report['injuryreport_location'];
    }
    //show($return);
    //$return=sort($return);
    //show($return);
    if($_SESSION['temp']['injury']['time_period']=='weekday'){
       
        $return[1]['x']='Mon';
        $return[2]['x']='Tue';
        $return[3]['x']='Wed';
        $return[4]['x']='Thu';
        $return[5]['x']='Fri';
        $return[6]['x']='Sat';
        $return[7]['x']='Sun';
    }
    if($_SESSION['temp']['injury']['cat']=='quantity'){
        $string='["Date", "Quantity", { role: "style" } ],';
        foreach($return as $data){
        $string=$string."['".$data['x']."', ".$data['y'].", '#333'],";
        }
        $return['string']=$string;
    }elseif($_SESSION['temp']['injury']['cat']=='location'){
        $string='["Date",';
        
        
        foreach($all_location as $location){
            $string=$string."'$location',";
        }
        $string=$string.' { role: "style" } ],';
        foreach($return as $data){
            $string=$string."['".$data['x']."', ";
            
            foreach($all_location as $location){
                $string=$string.$data[$location]['y'].",";
            }
            $string=$string."''],";
        }
        $return['string']=$string;
    }
    
    //show($string);
    return $return;
}

//all css and js use for the dashboard 
function all_css_dasboard(){?>
    <style>
        

    </style>
    <script>
        function show_view(id_div){
            document.getElementById('body_view').style.display="none";
            document.getElementById('stats_view').style.display="none";
            document.getElementById('show_stats_view').classList.remove("btn-active");
            document.getElementById('show_body_view').classList.remove("btn-active");
            document.getElementById(id_div).style.display="block";
            document.getElementById('show_'+id_div).classList.add("btn-active");
        }
    </script>

    <?php
}



// Manage of the POST request for filtering to add the filter to the SESSION array
function manage_POST_filtering(){
    if(!empty($_POST['manage_filter'])){
        if(!empty($_POST['location'])){
            $_SESSION['temp']['injury']['filter']['location'][$_POST['location']]=$_POST['location'];
        }
        if(!empty($_POST['body_part'])){
            $_SESSION['temp']['injury']['filter']['body_part'][$_POST['body_part']]=$_POST['body_part'];
        }
        if(!empty($_POST['injury_type'])){
            $_SESSION['temp']['injury']['filter']['injury_type'][$_POST['injury_type']]=$_POST['injury_type'];
        }
        if(!empty($_POST['time_of_day'])){
            $_SESSION['temp']['injury']['filter']['time_of_day'][$_POST['time_of_day']]=$_POST['time_of_day'];
        }
    }
    if(!empty($_POST['remove_filter'])){
        $filter_types=['location','body_part','injury_type','time_of_day'];
        foreach($filter_types as $filter_type){
            if(isset($_SESSION['temp']['injury']['filter'][$filter_type][$_POST['item']])){
                unset($_SESSION['temp']['injury']['filter'][$filter_type][$_POST['item']]);
            }
        }
    }
    
}
//reformat the array $SESSION filters to an array to show all the filter currently on   
function get_filter_to_show(){
    $session_filter=$_SESSION['temp']['injury']['filter'];
    $filter_types=['location','body_part','injury_type','time_of_day'];

     foreach($filter_types as $filter_type){
        foreach($session_filter[$filter_type] as $filter_item){
            $filters[]=$filter_item;
        }
     }
    
    return $filters;
}


//show all the report filtered from dashboard
function view_all_report_from_dashboard(){?>
    <div class="row list-report">
        <script>
            function show_all_report() {
                var x = document.getElementById("all_report");
                if (x.style.display === "block") {
                x.style.display = "none";
                } else {
                x.style.display = "block";
                }
            }    
        </script>
        <b>List of all the reports:</b>
        <i style="cursor:pointer" onclick="show_all_report()">Click to Expand</i>
        <br>
        <div class="all_report" id="all_report" style="display:none">
            <?php
            $all_report=get_injury_report_data_for_chart();
            foreach($all_report as $injuryreport){
                $can_see=0;
                if(!empty($_SESSION['temp']['role_injury_viewall'])){
                    $can_see=1;
                }
                if($injuryreport['injuryreport_openby']==$_SESSION['temp']['id']){
                    $can_see=1;
                }
                ?>
                <div class="row report_list">
                
                    <div class="col-sm-8">
                        <div class="btn btn-primary injury_button" <?php
                        if($can_see==11111){?>
                            onClick="thenumber='<?php echo $injuryreport['injuryreport_number']?>';show_injury();show_menu();"
                        <?php }?>
                        >
                            <div class="col-sm-4"><?php echo $injuryreport['injuryreport_number']?></div>
                            <div class="col-sm-4 <?php if($can_see==0){ echo' text_blur';}?>
                            "><?php if(!empty($injuryreport['injuryreport_name'])){ echo$injuryreport['injuryreport_name'];}?>
                            </div>
                            <div class="col-sm-4">
                            <?php if(!empty($injuryreport['injuryreport_timetag_incident'])){
                                echo date('jS M Y',$injuryreport['injuryreport_timetag_incident']).' at '.date('G:i',$injuryreport['injuryreport_timetag_incident']);
                            }?>
                            </div>
                        </div>
                        
                    </div><?php
                    echo'<div class="col-sm-1">';
                        if($injuryreport['injuryreport_status']=='Created' and (!empty($_SESSION['temp']['role_injury_viewall']) or $_SESSION['temp']['id']== $injuryreport['injuryreport_openby'])){
                            //echo'<div class="btn btn-primary injury_button" onClick="thenumber=\''.$injuryreport['injuryreport_number'].'\';delete_injury();refreshpage();">X</div>';
                        }
                    echo'</div>';
                    echo'<div class="col-sm-3">';
                        echo'<div class=" progress ">';
                            if($injuryreport['injuryreport_status']=='Created'){
                                echo '<div class="progress-bar progress-bar-striped created" role="progressbar" style="width: 10%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">Report</div>';
                            }elseif($injuryreport['injuryreport_status']=='Opened'){
                                echo '<div class="progress-bar progress-bar-striped closed" role="progressbar" style="width: 25%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">Report</div>';
                            }
                            if($injuryreport['injuryreport_status']=='Closed'){
                                echo '<div class="progress-bar progress-bar-striped closed" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Report Closed</div>';
                            }else{
                                if($injuryreport['injuryreport_investigation_status']=='Required'){
                                    echo '<div class="progress-bar progress-bar-striped created" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">Invest.</div>';
                                }elseif($injuryreport['injuryreport_investigation_status']=='In-Progress'){
                                    echo '<div class="progress-bar progress-bar-striped opened" role="progressbar" style="width: 25%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">Invest.</div>';
                                }elseif($injuryreport['injuryreport_investigation_status']=='Closed'){
                                    echo '<div class="progress-bar progress-bar-striped closed" role="progressbar" style="width: 30%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Invest.</div>';
                                }
                                if($injuryreport['injuryreport_followup_status']=='Required'){
                                    echo '<div class="progress-bar progress-bar-striped created" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">HR</div>';
                                }elseif($injuryreport['injuryreport_followup_status']=='In-Progress'){
                                    echo '<div class="progress-bar progress-bar-striped opened" role="progressbar" style="width: 30%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">HR</div>';
                                }elseif($injuryreport['injuryreport_followup_status']=='Closed'){
                                    echo '<div class="progress-bar progress-bar-striped closed" role="progressbar" style="width: 45%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">HR</div>';
                                }
                            }
                        echo'</div>';
                    echo'</div>';
                
                echo'</div>';
                
            // echo'</form>';
                
            } ?>
        </div>
    </div>
        <?php
        
   
    
}


//Prepare and send weekly emaill with the summary of the Injuries
function send_weekly_email(){
    $address='production-assistant@sicame.com.au';
    $name='Production Assistant';
    $subject='Weekly Injuries and Illnesses Summary - '.date('jS M Y',time());
    
    $stats=get_injury_report_data_for_email();
    $cc=$stats['all_cc'];
    $content='Hi all,<br><br>';
    $content=$content.'Here is the Weekly Summary of the Injuries and Illnesses.<br><br>';
    $content=$content.count($stats['new_reports_of_the_week']).' incidents were reported between the '.date('jS M Y',time()).' and the '.date('jS M Y',time()-7*3660*24).'.<br><br>';
    foreach($stats['new_reports_of_the_week'] as $reports){
        $content=$content.'<a href="http://192.168.1.30/injury.php?injuryreport_number='.$reports['injuryreport_number'].'" style="cursor:pointer">'.$reports['injuryreport_number'].'</a> - '.date('jS M Y',$reports['injuryreport_timetag_incident']).' - '.$reports['injuryreport_injury1'].'<br>';
    }
    $content=$content.'<br>';
    $content=$content.$stats['investigation'].' reports needs investigation.<br>';
    $content=$content.$stats['hrfollowup'].' reports needs HR follow-up.<br>';
    $content=$content.$stats['day_since_last'].' days since last report.<br><br>';
    $content=$content.'Find more details <a href="http://192.168.1.30/injury.php" style="cursor:pointer">here</a> <br>';
    //show($cc);
    //show($stats);
    
    //show($content);
    send_email($address,$name,$content,$subject,$cc);
}

//Query to get the data for the weekly email
function get_injury_report_data_for_email(){
    $db=$GLOBALS['db'];
    
    //Find reports created of the last 7days
    $query="SELECT *
    FROM injuryreport
    where [injuryreport_timetag_incident]is not null and injuryreport_timetag_incident>(".time()."-3600*24*7)
    order by injuryreport_timetag_incident asc";
    $sql = $db->prepare($query); 
	$sql->execute();
    //show($query);
    $new_reports_of_the_week=$sql->fetchall();
    $data_email['new_reports_of_the_week']=$new_reports_of_the_week;


    //find number of reports investigation required
    $query="SELECT count(injuryreport_number) as thecount
    FROM injuryreport
    where [injuryreport_timetag_incident]is not null  
    and injuryreport_investigation_status<>'Closed'";
    $sql = $db->prepare($query); 
	$sql->execute();
    $investigation=$sql->fetch();
    $data_email['investigation']=$investigation['thecount'];

    //find number of reports HR Follow-up required
    $query="SELECT count(injuryreport_number) as thecount
    FROM injuryreport
    where [injuryreport_timetag_incident]is not null  
    and injuryreport_investigation_status<>'Closed'";
    $sql = $db->prepare($query); 
	$sql->execute();
    $hrfollowup=$sql->fetch();
    $data_email['hrfollowup']=$hrfollowup['thecount'];
    
    //find number of days since last report
    $query="SELECT max(injuryreport_timetag_incident) as max_timetag
    FROM injuryreport
    where [injuryreport_timetag_incident]is not null  ";
    $sql = $db->prepare($query); 
	$sql->execute();
    $day_since_last=$sql->fetch();
    $data_email['day_since_last']=floor((time()-$day_since_last['max_timetag'])/(24*3600));;
    

    //Find the mail-list for the weekly email
    $query="SELECT *
    FROM [barcode].[dbo].[employee_group_allocation]
    left join employee_group on groupallocation_groupid=[group_id]
    left join employee on employee_code=groupallocation_employee
    where group_name='Health & Safety'
    order by groupallocation_leader desc,groupallocation_employee";
    $sql = $db->prepare($query); 
	$sql->execute();
    //show($query);
    $all_cc=$sql->fetchall();
    $cc='';
    foreach($all_cc as $contact){
        $cc=$cc.$contact['employee_email'].';';
    }
    $data_email['all_cc']=$cc;

    return $data_email;
}

//Prepare and send emaill to notify of a report created
function send_email_injury_notification($report_number){
    $db=$GLOBALS['db'];
    //load the report from the report number
    $query="SELECT *
    FROM injuryreport
    where injuryreport_number='$report_number' ";
    $sql = $db->prepare($query); 
    $sql->execute();
    $report=$sql->fetch();
    //show($report);

    $address='production-assistant@sicame.com.au';
    $name='Production Assistant';
    $subject=$report['injuryreport_number']." - New Report Created by ".$report['injuryreport_openby'];
    
    
    //Find the mail-list for the weekly email
    $query="SELECT *
    FROM [barcode].[dbo].[employee_group_allocation]
    left join employee_group on groupallocation_groupid=[group_id]
    left join employee on employee_code=groupallocation_employee
    where group_name='Health & Safety'
    order by groupallocation_leader desc,groupallocation_employee";
    $sql = $db->prepare($query); 
    $sql->execute();
    //show($query);
    $all_cc=$sql->fetchall();
    $cc='';
    foreach($all_cc as $contact){
        $cc=$cc.$contact['employee_email'].';';
    }
    //$cc='corentin@sicame.com.au';
    
    $content='Hi all,<br><br>';
    $content=$content.'A new injury has been reported on the '.date('jS M Y \a\t G:i:s',$report['injuryreport_timetag_report']).' by '.$report['injuryreport_openby'].'<br><br>';
    $content=$content.'Date of Incident: '.date('jS M Y',$report['injuryreport_timetag_incident']).'<br>';
    $content=$content.'Time of Incident: '.date('G:i:s',$report['injuryreport_timetag_incident']).'<br>';
    $content=$content.'Injury: '.$report['injuryreport_injury1'].'<br>';
    $content=$content.'Location: '.$report['injuryreport_location'].'<br>';
    $content=$content.'Injury Description: '.$report['injuryreport_injury_description'].'<br>';
    
    $content=$content.'<br>Find more details <a href="http://192.168.1.30/injury.php" style="cursor:pointer">here</a> <br>';
    //show($cc);
    //show($stats);
    
    //show($content);
    send_email($address,$name,$content,$subject,$cc);
}








?>


