<?php


function manage_post_training($db){
    show_debug();
    // if (!empty($_POST['delete_process'])){
    //     delele_process($db);
    // }
    // if (!empty($_POST['save_new_process'])){
    //     save_new_process($db);
    // }
    if ($_POST['filter_workarea']=='All'){
        $_POST['filter_workarea']='';
    }
    if(!empty($_POST['view_training'])){
        $_SESSION['temp']['view_training']=$_POST['view_training'];
    }
    if(!empty($_POST['hide_completed'])){
        $_SESSION['temp']['hide_completed']=$_POST['hide_completed'];
    }
    // if (!empty($_POST['add_a_process'])){
    //     add_a_process($db);
    // }
    if($_POST['action']=='show_trainingreport'){
        show_trainingreport_details($db,$_POST['trainingevent_id']);
    }

    if(!empty($_POST['show_list'])){
        listofproduct_training($db,$_POST['name']);
    }

    // if(!empty($_POST['link_product'])){
    //     //listofproduct_training($db,$_POST['name']);
    //     assign_product_process($db);
    //     show_all_product_linked($db,$_POST['trainingprocess_id'],$_POST['trainingprocess_process']);
    // }
    if (empty($_POST['date_process'])){
        $_POST['date_process']=date('Y-m-d',strtotime(date('Y-m-d',time()).' -365days'));
    }
    if(!empty($_POST['show_details_operator'])){
        show_one_line_training_matrix($db,$_POST['operator'],$_POST['trainingid']);
        show_details_training_matrix($db,$_POST['operator'],$_POST['trainingid']);
        
    }
    if(!empty($_POST['show_new_training'])){
        show_new_training($db);
    }
    if(!empty($_POST['signature_supervisor'])){
        save_training_event($db);
    }
    if(!empty($_POST['show_one_operator'])){
        show_one_operator($db);
    }
    
    
  
    
}

function general_view_training($db){?>
   <div class="row ">
        <div class="col-md-12"><?php
        if(!empty($_POST['show_all_operator'])){
            show_all_operator_training($db);
        }
        if(!empty($_POST['show_all_matrix'])){
            //show_training_matrix($db);
        }
        if(!empty($_POST['show_all_training'])){
            show_all_training($db);
        }
        if(!empty($_POST['show_all_process'])){
            show_training_all_process($db);
        }
        if(!empty($_POST['show_all_WI'])){
            show_all_WI($db);
        }?>
        </div>
    </div>
    <div class="col-sm-4 hidden-box" ></div>   
    <?php
}

function navbar_training($db){ ?>
    <div class="row navbar navbar_injury">
        <form method="POST">
            <div class="col-sm-3 col-md-2 "><?php if(!empty($_SESSION['temp']['role_training_admin'])){?>
                <button id="show_all_operator" name="show_new_training" value="1" class="btn btn-primary injury_button">Start New Training</button>
                <?php }?>
            </div>
            <div class="col-sm-3 col-md-2 "><button id="show_one_operator" name="show_one_operator" value="1" class="btn btn-primary injury_button">Show One Operator</button></div>
            <!--<div class="col-sm-3 col-md-2 "><button id="show_all_operator" name="show_all_operator" value="1" class="btn btn-primary injury_button">Show Matrix</button></div>-->
            <div class="col-sm-3 col-md-2 "><button id="show_all_operator" name="show_all_operator" value="1" class="btn btn-primary injury_button">Show Matrix</button></div>
            <div class="col-sm-3 col-md-2 "><button id="show_all_training" name="show_all_training" value="1" class="btn btn-primary injury_button">Show All Training</button></div>
            
            <!--<div class="col-sm-3 col-md-2 "><button id="show_all_process" name="show_all_process" value="1"  class="btn btn-primary injury_button" >Show all Process</button></div>-->
            <div class="col-sm-3 col-md-2 "><button id="show_all_WI" name="show_all_WI" value="1"  class="btn btn-primary injury_button" >Show all WI</button></div>
        </form>
    </div>
    <?php
}

function navbar_process($db){ ?>

   
    <div class="row navbar_matrix">
        <div class="col-sm-2 "></div>
        <div class="col-sm-2 ">
            <form method="POST">
                <button id="add_a_process" name="add_a_process" value="1"  class="btn btn-primary injury_button" >Add Process</button>
            </form>
        </div>
        <div class="col-sm-2 " >
            <form method="POST">
                <input type="hidden" name="show_all_process" value="1" >
                <select   name="filter_workarea" class="form-control" onchange="submit();" id="list_product">
                
                <option <?php if(empty($_POST['filter_workarea'])){echo' selected ';}?>>All</option>
                <?php  $workarea=get_all_workarea_training($db);
                foreach ($workarea as $item){?>
                    <option <?php if($_POST['filter_workarea']==$item[1]){echo' selected ';}?>><?php echo $item[1]?></option>
                    <?php
                } ?>
                </select>
            </form>
        </div>
        <div class="col-sm-2 ">
        </div>
        
    </div>
    <?php
}

function navbar_matrix($db){?>

   
    <div class="row navbar_matrix">
        
        <div class="col-sm-2 ">
        </div>
        <div class="col-sm-2 ">
        <form method="POST">
        <input type="date" name="date_process" value="<?php echo $_POST['date_process']?>"  class="form-control " onchange="submit();">
        <input type="hidden" name="show_all_operator" value="1" >
        </form>
        </div>
        <div class="col-sm-2 " >
        <form method="POST">
        <input type="hidden" name="show_all_operator" value="1" >
        <select   name="filter_workarea" class="form-control" onchange="submit();" id="list_product">
        
        <option <?php
        if(empty($_POST['filter_workarea'])){echo' selected ';} ?>
        >All</option>
        <?php
        $workarea=get_all_workarea_training($db);
        

        foreach ($workarea as $item){?>
            <option <?php
            if($_POST['filter_workarea']==$item[1]){echo' selected ';}?>
            ><?php echo $item[1]?></option>

            <?php
        }
        ?>
        </select>
        </form>
        
        </div>
        <div class="col-sm-2 ">
        </div>
        </form>
    </div>
    <?php
    
}

function show_one_line_training_matrix($db,$operatorfullname,$trainingid){
    $allprocess=get_all_process_training($db,0,10);
    $alldata=format_operator_process(get_all_operator_process_training($db));
    $operator['operator_fullname']=$operatorfullname;
    echo'<div class="row line_matrix '.$operator['operator_fullname'].'">';
        echo'<div class="col-md-2">';
        
        echo $operator['operator_fullname'];
        echo'</div>';
        echo'<div class="col-sm-10">';
        $i=1;
        $blocknumber=1;
            echo'<div class="allblock block-'.$blocknumber.'">';
                foreach($allprocess as $process){
                    $class_to_add='';
                    if(!empty($alldata[$operator['operator_fullname']][$process['trainingprocess_id']])){
                            if($alldata[$operator['operator_fullname']][$process['trainingprocess_id']]>40){
                                $class_to_add='qty-5';
                            }elseif($alldata[$operator['operator_fullname']][$process['trainingprocess_id']]>20){
                                $class_to_add='qty-4';
                            }elseif($alldata[$operator['operator_fullname']][$process['trainingprocess_id']]>10){
                                $class_to_add='qty-3';
                            }elseif($alldata[$operator['operator_fullname']][$process['trainingprocess_id']]>5){
                                $class_to_add='qty-2';
                            }else{
                                $class_to_add='qty-1';
                            }
                    }
                    echo'<div class="col-process process-item process'.$process['trainingprocess_id'].'item '.$operator['operator_fullname'].' '.$class_to_add.'" ';
                    //if(!empty($alldata[$operator['operator_fullname']][$process['trainingprocess_id']])){
                        echo' onclick="theoperator=\''.$operator['operator_fullname'].'\';thetrainingid=\''.$process['trainingprocess_id'].'\';showdetailsoperator(theoperator,thetrainingid);" ';
                    //}
                    echo'>';
                    if(empty($alldata[$operator['operator_fullname']][$process['trainingprocess_id']])){
                        echo '-';
                    }else{
                        echo number_format($alldata[$operator['operator_fullname']][$process['trainingprocess_id']],1);
                    }
                    
                    echo '<br>';
                    echo'</div>';
                    if($i==20){
                        echo'</div>';
                        echo'<div class="allblock block-'.($blocknumber+1).'" style="display:none">';
                        $blocknumber++;
                        $i=0;
                    }
                    $i++;
                }
            echo'</div>';
        echo'</div>';
    echo'</div>';
    echo'<br>';
    echo'<div class="row  ">';
        echo'<div class="col-md-5" "></div>';
        echo'<div class="col-md-2 line_matrix_2" onclick="closedetailsoperator();">Back</div>';
    echo'</div>';
        
        
    
    
    
    
    echo'<script>
    
    document.querySelectorAll(".allblock").forEach(a=>a.style.display = "none");
    document.querySelectorAll(".block-"+number_block).forEach(a=>a.style.display = "initial");
    document.getElementById("one_operator").style.display = "initial";
    function closedetailsoperator(){
        document.getElementById("all_operator").style.display = "initial";
        document.getElementById("one_operator").style.display = "none";
        
        
    }
        
    </script>';
    
       
        
    
}

function show_details_training_matrix($db,$operatorfullname,$trainingid){
   
    echo'<div class="details_operator col-sm-10 col-md-8 col-lg-8">';
        echo'<div class="row details_header">';
            echo'<div class="col-sm-4">';
            echo $operatorfullname;
            echo'</div>';
            echo'<div class="col-sm-8">';
            echo get_trainingprocess_process($db,$trainingid);
            echo'</div>';
        echo'</div>';echo'<br>';
        echo'<div class="row ">';
            echo'<div class="col-sm-6">';
            $random=rand(0,2);
            $status[0]='Not Initiated';
            $status[1]='In-Progress';
            $status[2]='Completed';
            writealine('Training Status',$status[$random],6);
            if($random==2 or $random==1){
                writealine('Training Date','15-11-2021',6);
                writealine('Supervised by','CorentinHillion',6);
            }
            
            //writealine('Work Instruction','Completed',6);
            dialog_training($db,$operatorfullname,$trainingid);
            echo'</div>';
            echo'<div class="col-sm-6">';
            all_activities($db,$operatorfullname,$trainingid) ;   
           
            echo'</div>';
           
        echo'</div>';
    echo'</div>';
   
        
        
    
    
       
        
    
}

function dialog_training($db,$operatorfullname,$trainingid){
    echo'<div class="row dialog_training">';
        echo'<div class="col-sm-6"></div>';
        echo'<div class="col-sm-6"><button id="show_all_operator" name="show_all_operator" value="1" class="btn btn-primary injury_button">Initiated Training</button></div>';
    echo'</div>';
    echo'<div class="row dialog_training">';
        echo'<div class="col-sm-6"></div>';
        echo'<div class="col-sm-6"><button id="show_all_operator" name="show_all_operator" value="1" class="btn btn-primary injury_button">Complete Training</button></div>';
    echo'</div>';
    //showprogressbar('All',20,10);
    //showprogressbar('Assembly',50,30);
    //showprogressbar('Machining',0,20);
    //showprogressbar('Moulding',10,00);
    $summary=get_summary_operator($db);
    //show($summary);
    foreach ($summary as $workarea){
        showprogressbar($workarea['name'],round($workarea['Completed']/$workarea['Total']*100,0),round($workarea['InProgress']/$workarea['Total']*100,0));
    }

}

function add_a_process($db){
    echo'<form method="POST">';
    echo'<input type="hidden" name="show_all_process" value="1">';
    echo'<div class="row dialog_training">';
        
        echo'<div class="col-sm-2"><input required type="text" name="trainingprocess_process" value="'.$_POST['trainingprocess_process'].'" placeholder="Process Name"></div>';
    echo'</div>';
    echo'<div class="row dialog_training">';
    
        echo'<div class="col-sm-2"><input required type="text" name="trainingprocess_workarea" value="'.$_POST['trainingprocess_workarea'].'" placeholder="WorkArea"></div>';
    echo'</div>';
    echo'<div class="row dialog_training">';
    
        echo'<div class="col-sm-2"><button id="save_new_process" name="save_new_process" value="1" class="btn btn-primary injury_button">Save New</button></div>';
    echo'</div>';
    echo'</form>';
    echo'<form method="POST">';
    echo'<div class="row dialog_training">';
    
        echo'<div class="col-sm-2"><button id="show_all_process" name="show_all_process" value="1" class="btn btn-primary injury_button">Back</button></div>';
    echo'</div>';
    echo'</form>';
    
    

}


function showprogressbar($header,$value1,$value2){
    
    //<div class="progress-bar progress-bar-striped made" role="progressbar" style="width: 86%" aria-valuenow="86" aria-valuemin="0" aria-valuemax="100">86%</div>
    
    echo'<div class="row dialog_training">';
        echo'<div class="col-sm-6">'.$header.'</div>';
        echo'<div class="col-sm-4">';
            echo'<div class=" progress ">';
                if($value1>0){echo'<div class="progress-bar progress-bar-striped completed" role="progressbar" style="width: '.$value1.'%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">';
                    echo' '.$value1.'%';
                    echo'</div>';
                }
                if($value2>0){
                    echo'<div class="progress-bar progress-bar-striped inprogress" role="progressbar" style="width: '.$value2.'%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">';
                    echo' '.$value2.'%';
                    echo'</div>';
                }
                
            echo'</div>';
        echo'</div>';
    echo'</div>';
}

function writealine($header,$value,$col){
    echo'<div class="row ">';
        echo'<div class="col-sm-'.$col.'">'.$header.'</div>';
        echo'<div class="col-sm-'.(12-$col).'">'.$value.'</div>';
    echo'</div>';
}

function all_activities($db,$operatorfullname,$trainingid){
    $allactivities=get_all_activities($db,$operatorfullname,$trainingid);
    $totalhour=0;
    $check30=0;
    $check50=0;
    $check100=0;
    $check365=0;
    $nbrdays=0;
    foreach($allactivities as $activity){
        if($nbrdays>30 and empty($check30)){
            
            echo'<div class="row ">'.$totalhour.' hours completed in the last 30 days</div>';
            $check30=1;
        }
        if($nbrdays>50 and empty($check50)){
            $nbrdays=round((time()-strtotime($activity['SCAN_DATE']))/3600/24,0);
            echo'<div class="row ">'.$totalhour.' hours completed in the last 50 days</div>';
            $check50=1;
        }
        if($nbrdays>100 and empty($check100)){
            $nbrdays=round((time()-strtotime($activity['SCAN_DATE']))/3600/24,0);
            echo'<div class="row ">'.$totalhour.' hours completed in the last 100 days</div>';
            $check100=1;
        }
        if($nbrdays>365 and empty($check365)){
            $nbrdays=round((time()-strtotime($activity['SCAN_DATE']))/3600/24,0);
            echo'<div class="row ">'.$totalhour.' hours completed in the last 365 days</div>';
            $check365=1;
        }
        echo'<div class="row ">';
            echo'<div class="col-sm-3">'.$activity['SCAN_DATE'].'</div>';
            echo'<div class="col-sm-1">'.''.'</div>';
            echo'<div class="col-sm-4">'.$activity['Code'].'</div>';
            echo'<div class="col-sm-4">'.number_format($activity['HOURS'],1).' h</div>';
            $totalhour=round($totalhour+$activity['HOURS'],1);
            $nbrdays=round((time()-strtotime($activity['SCAN_DATE']))/3600/24,0);
        echo'</div>';
        $lastdate=$activity['SCAN_DATE'];
    }
    if(empty($check365)){
        $nbrdays=round((time()-strtotime($lastdate))/3600/24,0);
        echo'<div class="row ">'.$totalhour.' hours completed in the last '.$nbrdays.' days</div>';
        
    }
}

function get_all_activities($db,$operatorfullname,$trainingid){
    $thedate=$_POST['date_process'];
    
	$query='SELECT  Code,SCAN_DATE,
    SUM([total_hours]) AS HOURS
         
      FROM [barcode].[dbo].[MIS_Operator_hours_scanned]
      LEFT JOIN MIS_List ON ManufactureIssueNumber=scan_jobnumber
      LEFT JOIN training_assign ON Code=trainingassign_productcode
     WHERE SCAN_DATE >\''.$thedate.'\' and operator_fullname=\''.$operatorfullname.'\' and trainingassign_trainingid=\''.$trainingid.'\'
     GROUP BY Code,SCAN_DATE
     ORDER BY SCAN_DATE desc
	  
	';
	
	$sql = $db->prepare($query); 
	//show($thedate);show($query);
	$sql->execute();

	$row=$sql->fetchall();
	//show($query);
	
	return $row;
}

function show_new_training($db){?>
    <div class="col-xs-12 col-sm-12 col-md-6 newtraining_form">
        <div class="row block ">New Training</div>
        <div class="row block "><?php show_new_training_operator($db)?></div>
        <div class="row block "><?php show_new_training_WI($db)?></div>
        <div class="row block "><?php show_new_training_form($db)?></div>
        <div class="row block "><?php show_new_training_supervisor($db)?></div>
    </div>
    <?php
}
function show_new_training_operator($db){
    if (empty($_POST['operator'])){
        $alloperator=get_all_operator_training($db);
        ?>
        <form method="POST">
        <select class="form-control" name="operator" onchange="submit()">
        <option selected disabled></option><?php
        foreach($alloperator as $operator){?>
            <option><?php echo $operator['operator_fullname']?></option>
            <?php
        }
        ?>
        </select>
        <input type="hidden" name="show_new_training" value=1>
        </form>
        <?php

        
    }else{
        ?>
        <div class="row">Trainee : <?php echo $_POST['operator']?> </div>
    
        <?php
    }
    
}
function show_new_training_WI($db){
    if (empty($_POST['operator'])){?>
         <div class="row">Work Instruction</div>
        <?php
    }else{
        if (empty($_POST['training_start'])){
            $allWI=get_all_WI_training($db,$_POST['operator']);
            ?>
            <form method="POST">
            <?php
            $i=0;
            if(!empty($_POST['document_to_remove'])){
                unset($_POST['document'][$_POST['document_to_remove']]);
            }
            if(!empty($_POST['document_to_add'])){
                $_POST['document'][$_POST['document_to_add']]=$_POST['document_to_add'];
            }
            if(!empty($_POST['document'])){
                ?><div class="row doc_list">Training for :</div><?php
                foreach($_POST['document']as $doc_name){//show($doc_name);?>
                    <input type="hidden" name="document[<?php echo $doc_name?>]" value="<?php echo $doc_name?>">
                    <div class="row doc_list">
                        <div class="col-xs-10"><?php echo $doc_name?></div>
                        <div class="col-xs-2">
                            <button type="submit" class="remove_button" name="document_to_remove" value="<?php echo $doc_name?>">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </div>
                    </div>
                    <?php
                    $i++;
                }?>
                <style>
                    .remove_button{
                        /*display: block;*/
                        width: 100%;
                                               line-height: 1.42857143;
                        color: #555555;
                        background-color: #fff;
                        background-image: none;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
                        box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
                    }
                </style>
                <?php
            }

            ?>
            
            <br>
            <select class="form-control" name="document_to_add" onchange="submit()">
                <option selected disabled>List of Work Instruction to be Trained</option><?php $lastworkarea='';
                foreach($allWI as $WI){
                    
                    if($lastworkarea<>$WI['document_workarea']){?><option disabled><?php echo $WI['document_workarea']?></option><?php }
                    if(empty($_POST['document'][$WI['document_name']])){?>
                        <option value="<?php echo $WI['document_name']?>"><?php echo $WI['document_number']?> ISS <?php echo $WI['document_issue']?> - <?php echo $WI['document_name']?></option>
                    <?php }?>
                    
                    <?php
                    $lastworkarea=$WI['document_workarea'];
                }
                ?>
            </select>
            <?php if(!empty($_POST['document'])){?>
                <br>
                <button type="submit" class="form-control" name="training_start" value="training_start">
                    <span class="glyphicon glyphicon-arrow-down"></span> Validate <?php echo count($_POST['document'])?> Trainings <span class="glyphicon glyphicon-arrow-down"></span>
                </button>
            <?php }
            ?>
            <input type="hidden" name="show_new_training" value=1>
            <input type="hidden" name="operator" value="<?php echo $_POST['operator']?>">
            </form>
            <?php
        }else{ 
            ?><div class="row doc_list">Training for :</div><?php
            foreach($_POST['document']as $doc_name){//show($doc_name);?>
                <input type="hidden" name="document[<?php echo $doc_name?>]" value="<?php echo $doc_name?>">
                <div class="row doc_list">
                    <div class="col-xs-10"><?php echo $doc_name?></div>
                    <div class="col-xs-2"></div>
                </div>
                <?php
                
            }?>
            
            <?php
        }
        
        
    }?>

    <style>
        .doc_list{
            font-size:13px;
        }
    </style>

    <?php
 
}
function show_new_training_form($db){
    if (empty($_POST['training_start'])){?>
    <div class="row">Training Form not Started</div>

    <?php }else{
        ?>
        <div class="row">Training Form</div>


        <?php   foreach($_POST['document'] as $document_name){
                $WI=get_one_WI_training($db,$document_name)?>
                <div class="row">Document number : <?php echo $WI['document_number']?> ISS <?php echo $WI['document_issue']?></div>
                <!--<div class="row">Issue Date : <?php echo date('jS M Y',strtotime($WI['document_date_issue']))?></div>-->
                <div class="row"><a href="ressource_v2/Work Instruction/<?php echo $WI['document_filename']?>" target="blank">Open Work Instruction </a></div>
                <?php
        } ?>
         
         <div class="row">Training Date : <?php echo date('D jS M Y',time())?></div>
         
         <?php
        if(empty($_POST['signature_operator'])){?>
            <div class="row"> 
                <form method="POST">
                <?php show_signature_box('signature_operator'); ?>
                <br/><br>
                <button class="btn btn-success">Save</button>
                <br><br><br>
                <input type="hidden" name="show_new_training" value=1>
                <input type="hidden" name="operator" value="<?php echo $_POST['operator']?>">
                <input type="hidden" name="training_start" value="<?php echo $_POST['training_start']?>">
                <input type="hidden" name="trainingevent_timetag" value=<?php echo time()?>>
                <?php foreach($_POST['document']as $doc_name){?>
                    <input type="hidden" name="document[<?php echo $doc_name?>]" value="<?php echo $doc_name?>">
                    <?php    
                }?>
                </form>
            </div>
        <?php
        }else{?>
            <div class="row">Signed by <?php echo $_POST['operator'];?></div>
            <div class="row"><img src="<?php echo $_POST['signature_operator'];?>" alt="Red dot" /></div>
            <?php
        }
        
    }

}
function show_new_training_supervisor($db){
    if (empty($_POST['signature_operator'])){?>
    <div class="row">Supervisor Validation not Started</div>

    <?php }else{
        if(empty($_POST['signature_supervisor'])){

               $WI=get_one_WI_training($db,$_POST['document_name'])?>
                    <div class="row">Supervisor : <?php echo $_SESSION['temp']['id']?></div>
                    <div class="row"> 
                        <form method="POST">
                        <?php show_signature_box('signature_supervisor'); ?>
                        <br/><br>
                        <button class="btn btn-success">Save</button>
                        <br><br><br>
                        <input type="hidden" name="show_new_training" value=1>
                        <input type="hidden" name="operator" value="<?php echo $_POST['operator']?>">
                        <input type="hidden" name="training_start" value="<?php echo $_POST['training_start']?>">
                        <input type="hidden" name="trainingevent_timetag" value="<?php echo $_POST['trainingevent_timetag']?>">
                        <input type="hidden" name="signature_operator" value="<?php echo $_POST['signature_operator']?>">
                        <input type="hidden" name="supervisor" value="<?php echo $_SESSION['temp']['id']?>">
                        <?php foreach($_POST['document']as $doc_name){?>
                            <input type="hidden" name="document[<?php echo $doc_name?>]" value="<?php echo $doc_name?>">
                            <?php    
                        }?>
                        </form>
                    </div>
                
                <?php
            
        }else{?>
            <div class="row">Signed by <?php echo $_POST['supervisor'];?></div>
            <div class="row"><img src="<?php echo $_POST['signature_supervisor'];?>" alt="Red dot" /></div>
            <?php

        }
        
    }

}

function show_training_matrix($db){
    navbar_matrix($db);
    $allprocess=get_all_process_training($db,0,10);?>
    <div class="row header_matrix">
        <div class="all_header">
            <div class="col-sm-2">
            <div class="col-sm-4 " ><span id="showpreviousdiv" class="glyphicon glyphicon-step-backward" onclick="showpreviousdiv();" style="display:none"></span></div>
            <div class="col-sm-4 "</span></div>
            <div class="col-sm-4 "><span id="shownextdiv"class="glyphicon glyphicon-step-forward" onclick="shownextdiv();"></span></div>
            </div>
            <div class="col-sm-10"><?php
                $i=1;
                $blocknumber=1;
                echo'<div class="allblock block-'.$blocknumber.'">';
                foreach($allprocess as $process){
                    echo'<div class="col-process-header ">';
                    echo $process['trainingprocess_process'];
                    echo'</div>';
                   
                    
                    if($i==20){
                        echo'</div>';
                        echo'<div class="allblock block-'.($blocknumber+1).'" style="display:none">';
                        $blocknumber++;
                        $i=0;
                    }
                    $i++;
                }?>
                </div>
                <script>
                var number_block=1;
                var max_block=<?php echo $blocknumber?>
                function shownextdiv(){
                    next_block=number_block+1;
                    document.querySelectorAll(".block-"+next_block).forEach(a=>a.style.display = "initial");
                    document.querySelectorAll(".block-"+number_block).forEach(a=>a.style.display = "none");
                    
                    document.getElementById("showpreviousdiv").style.display = "initial";
                    number_block=next_block;

                    if((next_block+1)>=max_block){
                        document.getElementById("shownextdiv").style.display = "none";
                    }
                    
                }
                function showpreviousdiv(){
                    previous_block=number_block-1;
                    document.querySelectorAll(".block-"+previous_block).forEach(a=>a.style.display = "initial");
                    document.querySelectorAll(".block-"+number_block).forEach(a=>a.style.display = "none");
                    document.getElementById("shownextdiv").style.display = "initial";

                    number_block=previous_block;

                    if((previous_block)<=1){
                        document.getElementById("showpreviousdiv").style.display = "none";
                    }
                    
                }
                </script>
            </div>
        </div>
    </div>
    <div class="all_operator" id="all_operator">
    <?php
    $alldata=format_operator_process(get_all_operator_process_training($db));
    
    foreach(get_all_operator_training($db) as $operator){
        
        echo'<div class="row line_matrix '.$operator['operator_fullname'].'">';
            echo'<div class="col-md-2">';
            
            echo $operator['operator_fullname'];
            echo'</div>';
            echo'<div class="col-sm-10">';
            $i=1;
            $blocknumber=1;
            echo'<div class="allblock block-'.$blocknumber.'">';
            foreach($allprocess as $process){
                $class_to_add='';
                if(!empty($alldata[$operator['operator_fullname']][$process['trainingprocess_id']])){
                        if($alldata[$operator['operator_fullname']][$process['trainingprocess_id']]>60){
                            $class_to_add='qty-5';
                        }elseif($alldata[$operator['operator_fullname']][$process['trainingprocess_id']]>30){
                            $class_to_add='qty-4';
                        }elseif($alldata[$operator['operator_fullname']][$process['trainingprocess_id']]>15){
                            $class_to_add='qty-3';
                        }elseif($alldata[$operator['operator_fullname']][$process['trainingprocess_id']]>10){
                            $class_to_add='qty-2';
                        }else{
                            $class_to_add='qty-1';
                        }
                }
                echo'<div class="col-process process-item process'.$process['trainingprocess_id'].'item '.$operator['operator_fullname'].' '.$class_to_add.'" ';
                //if(!empty($alldata[$operator['operator_fullname']][$process['trainingprocess_id']])){
                    echo' onclick="theoperator=\''.$operator['operator_fullname'].'\';thetrainingid=\''.$process['trainingprocess_id'].'\';showdetailsoperator(theoperator,thetrainingid);" ';
                //}
                echo'>';
                if(empty($alldata[$operator['operator_fullname']][$process['trainingprocess_id']])){
                    echo '-';
                }else{
                    echo number_format($alldata[$operator['operator_fullname']][$process['trainingprocess_id']],1);
                }
               
                echo '<br>';
                echo'</div>';
                if($i==20){
                    echo'</div>';
                    echo'<div class="allblock block-'.($blocknumber+1).'" style="display:none">';
                    $blocknumber++;
                    $i=0;
                }
                $i++;
            }
            echo'</div>';
        echo'</div>';
        echo'</div>';
        
        
    }
    
    echo'</div>';
    echo'<div class="one_operator" id="one_operator">';
    
    
    echo'</div>';
    echo'<script>
    function showdetailsoperator(theoperator,thetrainingid){
        document.getElementById("all_operator").style.display = "none";
        var request =$.ajax({
            type:\'POST\',
            url:\'training-ajax.php\',
            data: {
              operator: theoperator,
              trainingid: thetrainingid,
              show_details_operator:\'ok\',
              date_process:\''.$_POST['date_process'].'\',
              filter_workarea:\''.$_POST['filter_workarea'].'\'
            },
            success:function(html){
                $(\'.one_operator\').empty().append(html);
            }
        });
    }
        
    </script>';
    
       
        
    
}
function show_all_operator_training($db){
    $allprocess=get_all_process_training($db,0,10);
    ?>
    <?php show_trainingreport();?>
    <div class="row header_matrix">
        <div class="all_header">
            <div class="row ">
                <div class="col-sm-2"></div>
                <div class="col-sm-10 highlight_WI" id="highlight_WI"><br></div>
                <style>.highlight_WI{font-size:25px}</style>
            </div>
            <div class="col-sm-2">
                <div class="col-sm-4 " ><span id="showpreviousdiv" class="glyphicon glyphicon-step-backward" onclick="showpreviousdiv();" style="display:none"></span></div>
                <div class="col-sm-4 "></div>
                <div class="col-sm-4 "><span id="shownextdiv"class="glyphicon glyphicon-step-forward" onclick="shownextdiv();"></span></div>
            </div>
            <div class="col-sm-10">
                <?php
                $i=1;
                $blocknumber=1;
                ?>
                <div class="allblock block-<?php echo$blocknumber?>">
                <?php foreach($allprocess as $process){?>
                    <div class="col-process-header ">
                    <?php echo $process['document_name'];?>
                    </div>
                    <?php
                    if($i==20){?>
                        </div>
                        <div class="allblock block-<?php echo($blocknumber+1)?>" style="display:none">
                        <?php
                        $blocknumber++;
                        $i=0;
                    }
                    $i++;
                }?>
                </div>
                <script>
                var number_block=1;
                var max_block=<?php echo $blocknumber ?>;
                function shownextdiv(){
                    next_block=number_block+1;
                    document.querySelectorAll(".block-"+next_block).forEach(a=>a.style.display = "initial");
                    document.querySelectorAll(".block-"+number_block).forEach(a=>a.style.display = "none");
                    
                    document.getElementById("showpreviousdiv").style.display = "initial";
                    number_block=next_block;

                    if((next_block+1)>=max_block){
                        document.getElementById("shownextdiv").style.display = "none";
                    }
                    
                }
                function showpreviousdiv(){
                    previous_block=number_block-1;
                    document.querySelectorAll(".block-"+previous_block).forEach(a=>a.style.display = "initial");
                    document.querySelectorAll(".block-"+number_block).forEach(a=>a.style.display = "none");
                    document.getElementById("shownextdiv").style.display = "initial";

                    number_block=previous_block;

                    if((previous_block)<=1){
                        document.getElementById("showpreviousdiv").style.display = "none";
                    }
                    
                }
                </script>
            </div>
        </div>
    </div>
    <div class="all_operator" id="all_operator">
        <?php
        $alldata_training_event=get_all_training_event($db);
        //show($alldata_training_event);
        foreach(get_all_operator_training($db) as $operator){//show($alldata_training_event[$operator['operator_fullname']]);?>
            
            <div class="row line_matrix <?php echo$operator['operator_fullname']?>">
                <div class="col-sm-2"><?php echo $operator['operator_fullname']?></div>
                <div class="col-sm-10">
                    <?php
                    $i=1;
                    $blocknumber=1;?>
                    <div class="allblock block-<?php echo$blocknumber?>">
                        <?php
                        foreach($allprocess as $process){
                            $class_to_add='';
                        ?>
                            <div 
                            class="col-process process-item process<?php echo$process['document_id']?>item <?php echo$operator['operator_fullname'].' '.$class_to_add?>"
                            onmouseover="highlight('<?php echo $process['document_name'];?>');hide_trainingreport();
                            <?php if(!empty($alldata_training_event[$operator['operator_code']][$process['document_id']])){?>show_trainingreport(this,'<?php echo $alldata_training_event[$operator['operator_code']][$process['document_id']]?>');
                                <?php }?>"
                            onmouseout="
                            remove_highlight();
                            " 
                            
                            >
                            <?php if(empty($alldata_training_event[$operator['operator_code']][$process['document_id']])){?>
                                -
                            <?php }else{?>
                                <span class=" "><img class="check_schedule" src="img/checked.png" height="22"></span>
                                
                            <?php } ?>
                        
                            <br>
                            </div>
                            <?php
                            if($i==20){?>
                                </div>
                                <div class="allblock block-<?php echo($blocknumber+1)?>" style="display:none">
                                <?php $blocknumber++;
                                $i=0;
                            }
                            $i++;
                        }?>
                    </div>
                </div>
            </div>
            
        <?php 
        }?>
    
    </div>
    <script>

        function highlight(text){
            document.getElementById("highlight_WI").innerHTML=text;
        }
        function remove_highlight(){
            document.getElementById("highlight_WI").innerHTML='<br>';
        }


        var mouseX;
        var mouseY;
        $(document).mousemove( function(e) {
        mouseX = e.pageX; 
        mouseY = e.pageY;
        });  



        function show_trainingreport(e,trainingevent_id){
            document.getElementById('window_trainingreport').style.display = "block";
            var rect = e.getBoundingClientRect();
            document.getElementById('window_trainingreport').style.top = 0 ;
            document.getElementById('window_trainingreport').style.left = 0 ;
            var request =$.ajax({
                type:'POST',
                url:'training-ajax.php',
                data: {trainingevent_id:trainingevent_id,action:'show_trainingreport'},
                success:function(html){
                    $('.window_trainingreport').empty().append(html);
                }
            });
        }
        function hide_trainingreport(){
            document.getElementById('window_trainingreport').style.display = "none";
        }
    </script>
    <div class="one_operator" id="one_operator">
    
    
    </div>
    <script>
        
    function showdetailsoperator(theoperator,thetrainingid){
        document.getElementById("all_operator").style.display = "none";
        var request =$.ajax({
            type:'POST',
            url:'training-ajax.php',
            data: {
              operator: theoperator,
              trainingid: thetrainingid,
              show_details_operator:'ok',
              date_process:'<?php echo $_POST['date_process']?>',
              filter_workarea:'<?php echo $_POST['filter_workarea']?>'
            },
            success:function(html){
                $('.one_operator').empty().append(html);
            }
        });
    }
        
    </script>
    
       
        
    <?php
}

function show_trainingreport(){
    ?>
    <div id="window_trainingreport" class="window_trainingreport"></div>
    <style>
        .window_trainingreport{
            position: absolute;
            background: white;
            width:500px;
            /*top:0px;*/
            color: black;
            margin: 2%;
            float: left;
            border: 1px solid black;
            border-radius: 3rem;
            text-align: center;
            padding: 1.25rem;
            z-index: 200;
            display:none;
        }
        .window_trainingreport_header{
            border-radius: 5px;
            background-color: rgba(0,0,0,.03);
            border-bottom: 1px solid rgba(0,0,0,.125);
        }
    </style>
    <?php
}
function show_training_all_process($db){
    navbar_process($db);?>
    <div class="row header_matrix">
        <div class="col-md-2">Process</div>
        <div class="col-md-2 ">All Product</div>
        <div class="col-md-2 "></div>
        <div class="col-md-2 ">Work Instruction</div>
    </div>
    <?php $oldworkarea=';';?>
    <div class="all_process">
        <?php
    foreach(get_all_process_training($db) as $process){
        if($oldworkarea<>$process['trainingprocess_workarea']){?>
            <div class="row line_matrix_2 workarea" ><?php echo $process['trainingprocess_workarea'];?></div>
            <?php
        } ?>
        <form method="POST" id="delete_<?php echo$process['trainingprocess_id']?>">
            <input type="hidden" name="trainingprocess_id" value="<?php echo$process['trainingprocess_id']?>">
            <input type="hidden" name="show_all_process" value="1">
            <input type="hidden" name="delete_process" value="1">
        </form>     

        <div class="row line_matrix" >
            <div class="col-md-2"
                <?php if(empty(get_all_product_linked_to_process($db,$process['trainingprocess_id']))){?>
                    oncontextmenu="document.getElementById ('delete_<?php echo $process['trainingprocess_id']?>').submit();return false;"
                    <?php
                } ?>
            >
                <?php echo $process['trainingprocess_process'];?>
            </div>
            <div class="col-md-2 product_<?php echo $process['trainingprocess_id']?>">
            <?php show_all_product_linked($db,$process['trainingprocess_id']);?>
            </div>
            <div class="col-md-2 list_<?php echo $process['trainingprocess_id']?>">
            <div id="linkproduct<?php echo $process['trainingprocess_id']?>" type="text" class="form-control" >Link product</div>
            <?php ajax_button_v2('linkproduct'.$process['trainingprocess_id'],[['name',"'list_".$process['trainingprocess_id']."'"],['trainingprocess_id',"'".$process['trainingprocess_id']."'"],['show_list',"'ok'"]],'training-ajax.php','list_'.$process['trainingprocess_id'].'');?>
            
            </div>
            <div class="col-md-2 WI_<?php echo $process['trainingprocess_id']?>">
                <?php if (!empty($process['trainingprocess_WI_id'])){?>
                    <a class="btn btn-primary" href="training/<?php echo $process['document_number']?>_<?php echo $process['document_issue']?>.pdf" target="blank">Open Work Instruction </a><?php
                }else{
                    echo'No Work Instruction';
                }
                ?>
                
            </div>
            
        </div>
        <div></div>
        <script>
        function assign_productlist_<?php echo $process['trainingprocess_id']?>(productcode){
            var request =$.ajax({
                type:'POST',
                url:'training-ajax.php',
                data: {
                    Code:productcode,
                    trainingprocess_id:'<?php echo $process['trainingprocess_id']?>',
                    trainingprocess_process:'<?php echo $process['trainingprocess_process']?>',
                    link_product:'add'
                },
                success:function(html){
                    $('.product_<?php echo $process['trainingprocess_id']?>').empty().append(html);
                }
            });
        }
        </script>
        <?php $oldworkarea=$process['trainingprocess_workarea'];
    } ?>
    </div>
    <?php
    
}

function show_all_product_linked($db,$process_id,$process_process=''){
    echo'<div>';
    foreach(get_all_product_linked_to_process($db,$process_id) as $product){
        echo '<div oncontextmenu="assign_productlist_'.$process_id.'(\''.$product['trainingassign_productcode'].'\');return false;">'.$product['trainingassign_productcode'].'</div>';
       
    }
    echo'</div>';

    echo'<script>
        function assign_productlist_'.$process_id.'(productcode){
            var request =$.ajax({
                type:\'POST\',
                url:\'training-ajax.php\',
                data: {
                    Code:productcode,
                    trainingprocess_id:\''.$process_id.'\',
                    trainingprocess_process:\''.$process_process.'\',
                    link_product:\'add\'
                },
                success:function(html){
                    $(\'.product_'.$process_id.'\').empty().append(html);
                }
            });
        }
        </script>';
}

function show_window_training(){
    ?>
    <div id="window_training" class="window_training">
        <div id="window_header" class="window_header" >Training</div>
       <br>
        <div class="row">
            <div class="col-xs-6"></div>
            <div class="col-xs-6"></div>
        </div>
        <div class="window_closer"><input id="button" type="button" value="X" onclick="hide_notes()"></div>
        <input type="hidden" id="date_filter" value="">
        <input type="hidden" id="schedule_operatorcode" value="">
        <input type="hidden" id="schedule_hour_start" value="">
        <input type="hidden" id="schedule_duration" value="">
        <input type="hidden" id="schedule_productcode" value="">
        <input type="hidden" id="tempworkarea" value="">
        <input type="hidden" id="tempshift" value="">
        <input type="hidden" id="rownumber" value="">


    </div>

    <style>
        .btn_grp{
            padding: 10px;
            border: 1px solid #b6b5b5;
            border-radius: 5px;
            
        }
        .button_notes{
            background: transparent;
            font-size: 14px;
            /*border-color: #c2c3c4;*/
            /*border-style: solid;*/
            /*border-width: 2px;*/
            border-radius: 5px;
            padding: 3px 3px;
            text-transform: uppercase;
            transition: all 0.2s linear;
            display:inline-block;
            margin-left: 10px;
            box-shadow: 5px 5px 10px rgb(0 0 0 / 10%);
        }
        .button_notes:hover {
            background: #d0dae0;
            border-color: #d0dae0;
            
            transition: all 0.2s linear;
        }
        .window_closer{
            position:absolute; top:4%; right:4%;
            line-height: 12px;
            width: 18px;
            font-size: 8pt;
            font-family: tahoma;
            margin-top: 1px;
            margin-right: 2px;
        }
        .window_training{
            position: absolute;
            background: white;
            width:700;
            top:0px;
            color: black;
            margin: 2%;
            float: left;
            border: 1px solid black;
            border-radius: 3rem;
            text-align: center;
            padding: 1.25rem;
            z-index: 200;
            display:none;
        }
        .window_header{
            border-radius: 5px;
            background-color: rgba(0,0,0,.03);
            border-bottom: 1px solid rgba(0,0,0,.125);
            min-height: 30px;
            font-size: 20px;
        }
    </style>
    <?php
}


function show_all_training($db){
    $alltraining=get_all_training($db)
    ?>
    <div class="row header_matrix">
        <div class="col-xs-2">Date</div>
        <div class="col-xs-2">Operator</div>
        <div class="col-xs-2">Name</div>
        <div class="col-xs-2">Issue</div>
        <div class="col-xs-2">Supervisor</div>
    </div>
    <?php
    foreach($alltraining as $training){
        ?>
        <div class="row line_matrix ">
            <div class="col-xs-2"><?php echo date('D jS M Y  \a\t G:i:s',$training['trainingevent_timetag'])?></div>
            <div class="col-xs-2"><?php echo $training['trainingevent_operator']?></div>
            <div class="col-xs-2"><?php echo $training['document_name']?></div>
            <div class="col-xs-2"><?php echo $training['document_number']?> ISS <?php echo $training['document_issue']?></div>
            <div class="col-xs-2"><?php echo $training['trainingevent_supervisor']?></div>
            
        </div>
        <?php
    }

}

function show_one_operator($db){
    $operator_name=$_POST['operator'];
    $alltraining=get_all_training($db," and trainingevent_operator='$operator_name'");
    $alldata_training_event=get_all_training_event_details($db);
    $alloperator=get_all_operator_training($db);
    $allprocess=get_all_process_training($db);
    $allworkarea=get_all_distinct_workarea_training($db);
    $allhours=get_all_hours_works($db);
    ?>
    
    
    <div class="row header_matrix">
        <div class="col-xs-2">Operator</div>
        <div class="col-xs-2">
            <form method="POST">
                <select class="form-control" name="operator" onchange="submit()">
                <option selected disabled></option><?php
                foreach($alloperator as $operator){?>
                    <option <?php if($_POST['operator']==$operator['operator_fullname']){echo'selected';}?>><?php echo $operator['operator_fullname']?></option>
                    <?php
                }
                ?>
                </select>
                <input type="hidden" name="show_one_operator" value=1>
            </form>
        </div>
        <div class="col-xs-2">
            <form method="POST">
                <select class="form-control" name="workarea" onchange="submit()">
                <option selected disabled></option><?php
                foreach($allworkarea as $workarea){?>
                    <option <?php if($_POST['workarea']==$workarea['document_workarea']){echo'selected';}?>><?php echo $workarea['document_workarea']?></option>
                    <?php
                }
                ?>
                </select>
                <input type="hidden" name="operator" value="<?php echo $_POST['operator']?>">
                <input type="hidden" name="show_one_operator" value=1>
            </form>
        </div>
        <div class="col-xs-2">
            <form method="POST">
                <input type="hidden" name="show_one_operator" value=1>
                <input type="hidden" name="operator" value="<?php echo $_POST['operator']?>">
                <input type="hidden" name="workarea" value="<?php echo $_POST['workarea']?>">
                <?php if($_SESSION['temp']['view_training']=='Tiles'){$caption='Table';}else{$caption='Tiles';}?>
                <button type="submit" class="remove_button" name="view_training" value="<?php echo $caption?>"><?php echo $caption?></button>
            </form>
        </div>
        <div class="col-xs-2">
            <form method="POST">
                <input type="hidden" name="show_one_operator" value=1>
                <input type="hidden" name="operator" value="<?php echo $_POST['operator']?>">
                <input type="hidden" name="workarea" value="<?php echo $_POST['workarea']?>">
                <?php if($_SESSION['temp']['hide_completed']=='Show All'){$caption='Hide Completed';}else{$caption='Show All';}?>
                <button type="submit" class="remove_button" name="hide_completed" value="<?php echo $caption?>"><?php echo $caption?></button>
            </form>
        </div>
        <div class="col-xs-1"></div>
        <div class="col-xs-1">
            <?php if(!empty($_POST['operator'])){?>
                <form method="POST">
                <input type="hidden" name="show_new_training" value=1>
                <input type="hidden" name="operator" value="<?php echo $_POST['operator']?>">
                <?php if(!empty($_SESSION['temp']['role_training_admin'])){?>
                <button type="submit" class="remove_button" name="show_new_training" value="1">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
                <?php }?>
                
            <?php } ?>

        </div>
        <style>
            .remove_button{
                /*display: block;*/
                width: 100%;
                line-height: 2;
                color: #555555;
                background-color: #fff;
                background-image: none;
                border: 1px solid #ccc;
                border-radius: 4px;
                -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
                box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
            }
            .tile_training{
                /*display: block;*/
                width: 18%;
                background-color: #fff;
                border: 1px solid #6c6969;
                border-radius: 20px;
                box-shadow: 5px 10px 10px;
                text-align: center;
                float:left;
                min-height:100px;
                margin-right:10px;
                margin-top:10px;
            }
            .box_selected{
                border: 2px solid black;
                font-weight: bold;
                background:#f1f1f1;
            }
        </style>
        <script>
            function toggle_check(name){
                //alert(id);
                //alert(document.getElementById(id).value);
                id="check["+name+"]";
                id_block="block["+name+"]";
                if(document.getElementById(id).checked == false) {
                    document.getElementById(id).checked = true; 
                    document.getElementById(id_block).classList.add("box_selected");
                }
                else {
                    if(document.getElementById(id).checked == true) {
                        document.getElementById(id).checked = false; 
                        document.getElementById(id_block).classList.remove("box_selected");
                    }   
                }
                
                
            }
        </script>
        
    </div>
   <?php
    if(!empty($_POST['operator'])){
        $lastworkarea='nnn';
        ?><!--<div class="process_workarea">--><?php
        foreach($allprocess as $process){//show($process);
            if($lastworkarea<>$process['document_workarea']){
                ?><!--</div><div class="process_workarea"><div class="col-xs-12"><?php echo $process['document_workarea']?></div>--><?php
            }
            if(!empty($alldata_training_event[$operator_name][$process['document_number']])){
                if($alldata_training_event[$operator_name][$process['document_number']]['last_issue']==$process['document_issue']){
                    $class='process_done';
                    $info='done';
                }else{
                    $class='process_obsolete';
                    $info='obsolete';
                }
                }else{
                    $class='';
                    $info='<input type="checkbox" id="check['.$process['document_name'].']" class="remove_button" name="document['.$process['document_name'].']" value="'.$process['document_name'].'" style="width:auto">';
                }?>
                <?php if($_SESSION['temp']['hide_completed']=='Hide Completed' and $class=='process_done'){}else{?>
                    <?php if($_SESSION['temp']['view_training']=='Tiles'){?>
                        <div id="block[<?php echo $process['document_name']?>]" class="tile_training <?php echo $class?>" onclick="toggle_check('<?php echo $process['document_name']?>')">
                            <div class="row"><?php echo $process['document_name']?></div>
                            <div class="row">
                                <div class="col-xs-6"><?php echo round($allhours[$process['document_number']],1)?> h</div>
                                <div class="col-xs-6"><?php echo $info?></div>
                            </div>
                            
                        </div>

                    <?php }else{?>
                        <div id="block[<?php echo $process['document_name']?>]" class="process <?php echo $class?>" onclick="toggle_check('<?php echo $process['document_name']?>')">
                            <div class="col-xs-1"><?php echo $process['document_workarea']?></div>
                            <div class="col-xs-3"><?php echo $process['document_name']?></div>
                            <div class="col-xs-2"><?php echo $process['document_number']?> ISS <?php echo $process['document_issue']?></div>
                            <div class="col-xs-2"><?php echo round($allhours[$process['document_number']],1)?> h</div>
                            <div class="col-xs-1"><?php echo $info?></div>
                            
                        </div>
                    <?php }?>
                <?php }?>
                
            <?php
            
            $lastworkarea=$process['document_workarea'];
        }
        ?>
        <!--</div>-->
        <style>
            .process{
                width:100%;
                /*min-height:40px;*/
                border:1px solid #a59c9c;
                border-radius: 5px;
                float:left;
                /*padding:5px;*/
                text-align: center;
            }
            .process_done{
                background-color: #98df97;
            }
            .process_obsolete{
                background-color: #f3c693;
            }
            .process_workarea{
                width:100%;
                text-align: center;
                margin-top:10px;
            }
        </style>
        </form>

        <?php
    }

}


function show_all_WI($db){?>
    <div class="row header_matrix">
        <div class="col-xs-4">Work instruction</div>
        <div class="col-xs-2">Number</div>
        <div class="col-xs-2">Date of Issue</div>
        <div class="col-xs-2">Files</div>
        
    </div>
    <?php
    $all_WI=get_all_WI($db);
    foreach($all_WI as $WI){?>
        <div class="row line_matrix">
            <div class="col-xs-4"><?php echo $WI['document_name']?></div>
            <div class="col-xs-2"><?php echo $WI['document_number']?> ISS <?php echo $WI['document_issue']?></div>
            <div class="col-xs-2"><?php echo $WI['document_date_issue']?></div>
            <div class="col-xs-2 ">
                <a target="blank" href="ressource_v2/Work Instruction/<?php echo $WI['document_filename']?>" >
                    <span class="btn btn-default"><span class="glyphicon glyphicon-file"></span> View</span>
                </a>
            </div>
            
        </div>
    <?php
    }

}

function show_trainingreport_details($db,$trainingevent_id){
    $query="SELECT *
	  FROM training_event
      left join document on document_id=trainingevent_WI_id
      where trainingevent_id='$trainingevent_id'
      ";
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$document=$sql->fetch();?>
    <div class="row window_trainingreport_header">Document : <?php echo $document['document_number']?> ISS <?php echo $document['document_issue']?></div>
    <br>
    <div class="row">Training done on <?php echo date('D jS M Y  \a\t G:i:s',$document['trainingevent_timetag']);?></div>
    <br>
    <div class="row">
        <div class="col-xs-6 box_signature">
            <div class="row"><img class="signature_render" src="<?php echo $document['trainingevent_signature_operator'];?>" alt="Red dot" /></div>
            <div class="row"><?php echo $document['trainingevent_operator']?></div>
            <div class="row">Operator</div>
        </div>
        <div class="col-xs-6 box_signature">
            <div class="row"><img class="signature_render" src="<?php echo $document['trainingevent_signature_supervisor'];?>" alt="Red dot" /></div>
            <div class="row"><?php echo $document['trainingevent_supervisor']?></div>
            <div class="row">Supervisor</div>
        </div>
    </div>
    <div class="row">Training done <?php echo round((time() - $document['trainingevent_timetag']) / (60 * 60 * 24))?> days ago</div>
    <div class="row">XX hours has been worked on that year</div>
    <div class="row">XX hours since last training</div>
    

    <style>
        .signature_render{
            width:100%;
        }
        .box_signature{
            border:1px solid #a59c9c;
            border-radius:10px;
            padding:10px;
        }
    </style>
    
    
   

    <?php
}




function get_all_training($db,$option=''){
	$query="SELECT *
	  FROM [training_event]
      left join document on document_type='Work Instruction' and document_id=trainingevent_WI_ID
      Where 1=1 $option
      order by trainingevent_timetag desc";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
	$row=$sql->fetchall();
	return $row;
}

function get_all_WI($db){
	$query="SELECT *
	  FROM document
      Where document_type='Work Instruction' and document_active=1
      order by document_number asc";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
	$row=$sql->fetchall();
	return $row;
}

function get_all_operator_training($db){
	
	$query='SELECT *
	  FROM operator
      where operator_active=1
      order by operator_fullname asc
	  
	';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	
	
	return $row;
}
function get_all_supervisor_training($db){
	
	$query="SELECT *
	  FROM employee
      left join role_attribution on attribution_employee_code=employee_code and attribution_role_id='Leading Hand'
      order by employee_fullname asc
	  
	";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	
	
	return $row;
}

function get_all_WI_training($db,$operator){
	
	$query="SELECT *
    FROM document
    left join training_event on trainingevent_WI_id=document_id  and trainingevent_operator='$operator'
    Where document_type='Work Instruction' and document_active=1 and ( trainingevent_operator is null)
    order by document_workarea asc,document_number asc
      ";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	
	
	return $row;
}
function get_one_WI_training($db,$document_name){
	
	$query="SELECT *
    FROM document
    Where document_type='Work Instruction' and document_active=1 and document_name='$document_name'
    order by document_number asc
    ";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
	
	
	return $row;
}

function get_all_operator_process_training($db){
	$thedate=$_POST['date_process'];
    if(!empty($_POST['filter_workarea'])){
        $filter=" and trainingprocess_workarea ='".$_POST['filter_workarea']."'";
    }
	$query='SELECT  operator_fullname,Code,trainingassign_trainingid,trainingassign_process
    ,SUM([total_hours]) AS HOURS
         
      FROM [barcode].[dbo].[MIS_Operator_hours_scanned]
      LEFT JOIN MIS_List ON ManufactureIssueNumber=scan_jobnumber
      LEFT JOIN training_assign ON Code=trainingassign_productcode
      LEFT JOIN training_process ON trainingassign_trainingid=trainingprocess_id
     WHERE SCAN_DATE >\''.$thedate.'\' AND trainingassign_productcode IS NOT NULL '.$filter.' 
     GROUP BY operator_fullname,Code,trainingassign_trainingid,trainingassign_process
     ORDER BY HOURS DESC
	  
	';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	//show($query);
	
	return $row;
}
function get_all_distinct_workarea_training($db){
    $query="SELECT DISTINCT [document_workarea]
    FROM [barcode].[dbo].[document]
    where document_workarea is not null
    order by document_workarea";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
    return $row;
}

function format_operator_process($table){
    foreach ($table as $line){
        $returntable[$line['operator_fullname']][$line['trainingassign_trainingid']]=$returntable[$line['operator_fullname']][$line['trainingassign_trainingid']]+$line['HOURS'];
    }
    return $returntable;
}

function get_all_process_training($db,$offset=0,$nbr_of_row=10000){
    if(!empty($_POST['workarea'])){
        $filter=" and document_workarea='".$_POST['workarea']."'";
    }
	$query="SELECT *
    FROM document
    Where document_type='Work Instruction' and document_active=1 $filter
    order by document_workarea,document_number asc";//OFFSET '.$offset.' ROWS
	  //FETCH NEXT '.$nbr_of_row.' ROWS ONLY
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	
	
	return $row;
}

function get_all_training_event($db,$offset=0,$nbr_of_row=10000){
	$query="SELECT *
    FROM training_event
    left join operator on trainingevent_operator=operator_fullname
    left join document on trainingevent_WI_id=document_id
    where document_active=1
    order by operator_fullname asc";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$temprow=$sql->fetchall();
    foreach($temprow as $row){
        $return[$row['operator_code']][$row['document_id']]=$row['trainingevent_id'];
    }
	
	
	return $return;
}
function get_all_training_event_details($db,$option=""){
	$query="SELECT  trainingevent_operator,document_number,max(document_issue)as last_issue
    FROM [barcode].[dbo].[training_event]
    left join document on trainingevent_WI_id=document_id
    group by trainingevent_operator,document_number";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$temprow=$sql->fetchall();
    foreach($temprow as $row){
        $return[$row['trainingevent_operator']][$row['document_number']]['last_issue']=$row['last_issue'];
        $return[$row['trainingevent_operator']][$row['document_number']]['document_number']=$row['document_number'];
        

    }
	
	
	return $return;
}
function get_all_product_linked_to_process($db,$process){
	
	$query='SELECT  distinct trainingassign_productcode
      
    FROM training_assign
    where  [trainingassign_trainingid]='.$process.'
	  
	';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	
	
	return $row;
}

function get_all_product_training($db,$processid){
    $query='SELECT distinct Product_Code
    FROM List_Document
    LEFT JOIN training_assign on trainingassign_productcode=Product_Code
    Where (PRODUCT_FAMILY=\'CAW/CCW\' or
    PRODUCT_FAMILY=\'HSC/ILC/MCB\' or
    PRODUCT_FAMILY=\'MTRS\' or
    PRODUCT_FAMILY=\'MUCI\' or
    PRODUCT_FAMILY=\'OVERHEAD\' or
    PRODUCT_FAMILY=\'PFV\' or
    PRODUCT_FAMILY=\'PHSR\' or
    PRODUCT_FAMILY=\'Piranha\' or
    PRODUCT_FAMILY=\'Piranha/MUCI\' or
    PRODUCT_FAMILY=\'SOLAR\' or
    PRODUCT_FAMILY=\'Other\' or
    PRODUCT_FAMILY=\'TTD/NDT\' or
    PRODUCT_FAMILY=\'UNSPECIFIED\' )
    AND (trainingassign_productcode is NULL or trainingassign_trainingid<>\''.$processid.'\' )
    order by Product_Code ASC
    ';

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();
    //
    return $row;
}

function get_trainingprocess_process($db,$trainingid){
    $query='SELECT trainingprocess_process
    FROM training_process
    where trainingprocess_id=\''.$trainingid.'\'
    
    
    
  ';//OFFSET '.$offset.' ROWS
    //FETCH NEXT '.$nbr_of_row.' ROWS ONLY
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  
  
  return $row[0];
}

function get_all_workarea_training($db){
    $query='SELECT count(trainingprocess_id) as thecount,trainingprocess_workarea
	  FROM training_process
      
      where 1=1 
      group by trainingprocess_workarea
      order by trainingprocess_workarea asc
      
	  
	';//OFFSET '.$offset.' ROWS
	  //FETCH NEXT '.$nbr_of_row.' ROWS ONLY
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
    return $row;
}
function get_all_hours_works($db){
    $thedate=date('Y-m-d',time()-365*24*3600);
    $operator=$_POST['operator'];
    $query="SELECT  doclink_docnumber
    ,SUM([total_hours]) AS HOURS
     
    FROM [barcode].[dbo].[MIS_Operator_hours_scanned]
    LEFT JOIN MIS_List ON ManufactureIssueNumber=scan_jobnumber
	LEFT JOIN doc_link ON doclink_productcode=Code
    WHERE SCAN_DATE >'$thedate' and  operator_fullname='$operator'
    GROUP BY doclink_docnumber
    ORDER BY HOURS DESC";

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $allrow=$sql->fetchall();
    foreach($allrow as $row){
        $return[$row['doclink_docnumber']]=$row['HOURS'];
    }

    return $return;

}


function get_summary_operator($db){
    
	$row=get_all_workarea_training($db);
    foreach($row as $workarea){
        $result[$workarea['trainingprocess_workarea']]['Total']=$workarea['thecount'];
        $result[$workarea['trainingprocess_workarea']]['name']=$workarea['trainingprocess_workarea'];
    }
	
    $thedate=$_POST['date_process'];
    $query='SELECT  (trainingassign_trainingid),trainingassign_process,trainingprocess_workarea
        ,SUM([total_hours]) AS HOURS
         
        FROM [barcode].[dbo].[MIS_Operator_hours_scanned]
        LEFT JOIN MIS_List ON ManufactureIssueNumber=scan_jobnumber
        LEFT JOIN training_assign ON Code=trainingassign_productcode
        LEFT JOIN training_process ON trainingassign_trainingid=trainingprocess_id
        WHERE SCAN_DATE >\''.$thedate.'\' AND trainingassign_productcode IS NOT NULL and  operator_fullname=\''.$_POST['operator'].'\'
        GROUP BY trainingassign_trainingid,trainingassign_process,trainingprocess_workarea
        ORDER BY HOURS DESC
	  
	';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	//show($query);
	
    foreach($row as $process){
        if($process['HOURS']>10){
            $result[$process['trainingprocess_workarea']]['Completed']=$result[$process['trainingprocess_workarea']]['Completed']+1;
        }elseif($process['HOURS']>0){
            $result[$process['trainingprocess_workarea']]['InProgress']=$result[$process['trainingprocess_workarea']]['InProgress']+1;
        }
    }
	return $result;
}

function listofproduct_training($db,$name){
    $allproduct=get_all_product_training($db,$_POST['trainingprocess_id']);
    //echo'<form method="POST">';
    echo '<input type="text" list="thelist'.$name.'" name="list_product" class="form-control" onchange="assign_product'.$name.'(this.value);this.value=\'\'" id="list_product"">
    <datalist id="thelist'.$name.'">';
    foreach ($allproduct as &$item){
        echo"<option >".$item[0]."</option>";
    }
    echo '</datalist>';
    //echo '</form>';
}

function assign_product_process($db){
    //check if product exist
    if(check_product_exist_training($db,$_POST['Code'])=='yes'){
         //is product already assigned
        if(check_product_already_assign_training($db,$_POST['Code'],$_POST['trainingprocess_id'])=='yes'){
        //if yes remove product
        $query="delete
        FROM training_assign
        where trainingassign_trainingid='".$_POST['trainingprocess_id']."'
        
        AND trainingassign_productcode='".$_POST['Code']."'
        
        ";
        
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        }else{
        //if no add product
        $query="INSERT INTO training_assign
            (trainingassign_trainingid,
            trainingassign_process,
            trainingassign_productcode)
            VALUES
            (
            '".$_POST['trainingprocess_id']."',
            '".$_POST['trainingprocess_process']."',
            '".$_POST['Code']."'
            )
        ";
        
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        }
    }
   

    


    
}

function check_product_exist_training($db,$code){
    $query='SELECT Product_Code
    FROM List_Document
    Where Product_Code=\''.$code.'\'
    
    ';

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetch();
    if(!empty($row)){
        return 'yes';
    }else{
        return 'no';
    }
    //
    
}

function check_process_exist_training($db){
    $query='SELECT trainingprocess_process
    FROM trainingprocess
    Where trainingprocess_process=\''.$_POST['trainingprocess_process'].'\'
    
    ';

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetch();
    if(!empty($row)){
        return 'yes';
    }else{
        return 'no';
    }
    //
    
}

function check_product_already_assign_training($db,$code,$process_id){
    $query='SELECT trainingassign_trainingid
    FROM training_assign
    Where trainingassign_productcode=\''.$code.'\' and trainingassign_trainingid=\''.$process_id.'\'
    
    ';

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetch();
    if(!empty($row)){
        return 'yes';
    }else{
        return 'no';
    }
    //
    
}

function delele_process($db){
    
    $query="delete
    FROM training_process
    where trainingprocess_id='".$_POST['trainingprocess_id']."'
    
    
    ";
    
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
       
    
   

    


    
}

function save_new_process($db){
    if(check_process_exist_training($db)=='no'){
        $query="INSERT INTO training_process
            (trainingprocess_process,
            trainingprocess_workarea)
            VALUES
            (
            '".$_POST['trainingprocess_process']."',
            '".$_POST['trainingprocess_workarea']."'
            
            )
        ";
        
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
    }else{
        alert('Process not added because a process already exists under this name');
    }
}

function save_training_event($db){
    $query='';
    foreach($_POST['document'] as $document_name){
        $WI=get_one_WI_training($db,$document_name);
        $query=$query."INSERT INTO training_event
        (trainingevent_operator,
        trainingevent_supervisor,
        trainingevent_WI_id,
        trainingevent_timetag,
        trainingevent_date,
        trainingevent_signature_operator,
        trainingevent_signature_supervisor
        )
        VALUES
        (
        '".$_POST['operator']."',
        '".$_POST['supervisor']."',
        '".$WI['document_id']."',
        '".$_POST['trainingevent_timetag']."',
        '".date('Y-m-d',$_POST['trainingevent_timetag'])."',
        '".$_POST['signature_operator']."',
        '".$_POST['signature_supervisor']."'
        
        );
    ";
    }
    
    
    
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    
}

        


?>