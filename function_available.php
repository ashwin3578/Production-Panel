<?php
function available_managing_POST($db){
    if($_SESSION['temp']['debug']=='1'){
        show($_POST);
    }
    if(empty($_POST['date_to_show'])){
        //if(empty($_SESSION['temp']['date_to_show'])){
        $today2=(date('Y-m-d',time()))	;
        $_POST['date_to_show']=$today2;
        $_SESSION['temp']['date_to_show']=$_POST['date_to_show'];
    }else{
            $_SESSION['temp']['date_to_show']=$_POST['date_to_show'];
    }

    if($_POST['action']=='allocated_job'){
        allocated_job($db,$_POST['operatorid'],$_POST['date_to_show']);
    }

    if(!empty($_POST['WorkArea'])){
        $_SESSION['temp']['WorkArea_Operator']=$_POST['WorkArea'];
    }
    if(empty($_SESSION['temp']['WorkArea_Operator'])){
        $_SESSION['temp']['WorkArea_Operator']='All';
    }

}


function navbar_available($db){
    $timetag=strtotime($_POST['date_to_show']);
    $datetoshow=date('D jS M Y',$timetag);
    create_css ($db)
    ?>
    <script>
       
        function loaddate(date){
            var request =$.ajax({
                type:'POST',
                url:'available_ajax.php',
                data: {date_to_show:date},
                success:function(html){
                    $('.here').empty().append(html);
                }
            });
        }
        function loadworkarea(workarea){
            var request =$.ajax({
                type:'POST',
                url:'available_ajax.php',
                data: {WorkArea:workarea},
                success:function(html){
                    $('.here').empty().append(html);
                }
            });
        }
    </script>



    <div class="row "style="text-align:center">
        <div  class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <div  class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <span class="btn btn-primary" onclick="loadworkarea('All');">All</span>
            </div>
            <div  class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <span class="btn btn-primary" onclick="loadworkarea('Morning');">Morning</span>
            </div>
            <div  class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <span class="btn btn-primary" onclick="loadworkarea('Afternoon');">Afternoon</span>
            </div>
        </div>
        <div  class="col-xs-3 col-sm-3 col-md-1 col-lg-1">
            <span class="glyphicon glyphicon-step-backward" onclick="loaddate('<?php echo date('Y-m-d',strtotime($_POST['date_to_show'])-3600*24);?>');"></span>
        </div>
        <div  class="col-xs-6 col-sm-6 col-md-2 col-lg-1" >
            <?php echo $datetoshow;?>
        </div>
        <div  class="col-xs-3 col-sm-3 col-md-1 col-lg-1">
        <span class="glyphicon glyphicon-step-forward" onclick="loaddate('<?php echo date('Y-m-d',strtotime($_POST['date_to_show'])+3600*24);?>');"></span>
        </div>
    </div>
    <div class="row "style="text-align:center"><!--
    <?php
    $alldata=get_data_allocation($db,'allocation');
    
    
    //show(get_data_allocation($db,'allocationwork'));
   
        foreach($alldata['Total'] as $workarea){
            ?>
             <div  class="col-xs-3 col-sm-3 col-md-1 col-lg-1  day-roster WorkArea <?php echo $workarea['name'];?>">
                <?php echo $workarea['name'].' - '.$workarea['number'] ;?>
            </div>
            <?php
        }
    ?>
       
    --> 
    </div>

    
    
    <?php 
}

function available_view_general($db){
    $alldata=get_data_allocation($db,'allocation');
    unset($alldata['Total']);
    //array_multisort($alldata,SORT_DESC);
    //show($alldata);
    //show($alldata);
    //show($alldata);
    ?>
    <script>
       
       function tick_name(date,operator){
           var request =$.ajax({
               type:'POST',
               url:'available_ajax.php',
               data: {date_to_show:date,operatorid:operator,action:'allocated_job'},
               success:function(html){
                   $('.here').empty().append(html);
               }
           });
       }
   </script>
    <div class="row ">
    <?php
    foreach($alldata as $workarea){
        
       
            ?>
             <div  class="col-xs-3 col-sm-3 col-md-2 col-lg-2  day-roster WorkArea  <?php echo $workarea['name'];?>">
                <div  class="row">
                    <b><?php echo $workarea['name'].' - '.$workarea['number'];unset($workarea['name']);?></b>
                </div>
                <?php 
                unset ($workarea['name']);unset ($workarea['number']);
                foreach($workarea as $shift){
                    array_multisort($shift,SORT_ASC);
                    
                    ?>
                        <div  class="row  day-roster  <?php echo $shift['shift'];?>"><?php echo $shift['shift'].' - '.$shift['number'];?>
                    <?php
                    unset ($shift['shift']);unset ($shift['number']);
                    foreach($shift as $operator){
                        //show($operator);
                        if(!empty($operator['name'])){
                        ?>
                        <div  class="row">
                            <div  class="col-xs-10">
                            <?php echo $operator['name'];?>
                            </div>
                            <div  class="col-xs-2">
                                <?php if(empty($operator['allocationjobdone_done'])){?>
                                    <span class="glyphicon glyphicon-unchecked" onclick="tick_name('<?php echo date('Y-m-d',strtotime($_POST['date_to_show'])).'\',\''.$operator['name'];?>');"></span>
                                <?php }else{?>
                                    <span class="glyphicon glyphicon-check" onclick="tick_name('<?php echo date('Y-m-d',strtotime($_POST['date_to_show'])).'\',\''.$operator['name'];?>');"></span>
                                <?php }?>
                                
                            </div>
                        </div>
                        <?php
                        }
                    }
                    ?>
                </div>
                <?php
                }
                ?>
            </div>
            <?php
        }
    ?>
    </div>
    <?php
}




 





function get_data_allocation($db,$table='',$operator=''){
    
  
   if(!empty($operator)){$filter=" AND allocationwork_date_operatorid='".$operator."'"; }
      
    if ($_SESSION['temp']['WorkArea_Operator']<>'All') {
        $filter=$filter." AND allocationshift_code='".$_SESSION['temp']['WorkArea_Operator']."'";
    }    
   

        $query="SET DATEFIRST 1;
        SELECT * FROM dbo.allocationwork
        left join allocation on  allocation_date=allocationwork_date and allocation_operatorid=allocationwork_operatorid
		left join allocationshift on  allocationshift_date=allocationwork_date and allocationshift_operatorid=allocationwork_operatorid
		left join allocationcontract on  allocationcontract_date=allocationwork_date and allocationcontract_operatorid=allocationwork_operatorid
        left join allocationjobdone on  allocationjobdone_date=allocationwork_date and allocationjobdone_operatorid=allocationwork_operatorid
        left join operator on operator_fullname=allocationwork_operatorid
        left join baseallocation on  baseallocation_code=allocation_code
        left join baseallocationcontract on  baseallocationcontract_code=allocationcontract_code 
		left join baseallocationwork on  baseallocationwork_code=allocationwork_code  
        WHERE allocationwork_date='".$_SESSION['temp']['date_to_show']."' ".$filter." 
        and allocationshift_code <> 'Null' and operator_active=1
        AND ((DATEPART(DW,allocationcontract_date)<>6 and DATEPART(DW,allocationcontract_date)<>7 
            ) or((DATEPART(DW,allocationcontract_date)=6 or DATEPART(DW,allocationcontract_date)=7)and baseallocation_working<100 ))
        AND (baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100)>0
        
        ";
        
        //show($query);
        $sql = $db->prepare($query); 
        $sql->execute();
        //show(nbr_of_line);
        $rowtemp=$sql->fetchall();
        $row['Total']=array();
        foreach($rowtemp as $line){
            //$row[$line[$table.'_date']][$line[$table."_operatorid"]]=$line[$table."_code"];
            
            if($line["allocationwork_working"]<100){
                $row['Total'][$line["allocationwork_code"]]['number']++;
                $row['Total'][$line["allocationwork_code"]]['name']=$line["allocationwork_code"];
                $row[$line["allocationwork_code"]]['name']=$line["allocationwork_code"];
                $row[$line["allocationwork_code"]]['number']++;
                $row[$line["allocationwork_code"]][$line["allocationshift_code"]]['shift']=$line["allocationshift_code"];
                $row[$line["allocationwork_code"]][$line["allocationshift_code"]]['number']++;
                $row[$line["allocationwork_code"]][$line["allocationshift_code"]][$line["allocationwork_operatorid"]]['name']=$line["allocationwork_operatorid"];
                $row[$line["allocationwork_code"]][$line["allocationshift_code"]][$line["allocationwork_operatorid"]]['code']=$line["allocation_code"];
                $row[$line["allocationwork_code"]][$line["allocationshift_code"]][$line["allocationwork_operatorid"]]['allocationjobdone_done']=$line["allocationjobdone_done"];
            }
            
        }
        array_multisort($row['Total'],SORT_DESC);
        //array_multisort($row,SORT_DESC);
       
  
   // }
   
   
   
   
  
  return $row;
}

function allocated_job($db,$operator,$date){
    //show($operator);
    //show($date);
    $query="SELECT * FROM dbo.allocationjobdone
        WHERE allocationjobdone_date='".$date."' and allocationjobdone_operatorid='".$operator."' ";
    
    //show($query);
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();
    if(empty($row)){
        $query="INSERT INTO dbo.allocationjobdone (allocationjobdone_date,allocationjobdone_operatorid,allocationjobdone_done,allocationjobdone_employee,allocationjobdone_timetag) VALUES('".$date."','".$operator."','1','".$_SESSION['temp']['id']."',".time().")";
    }else{
        if($row['allocationjobdone_done']==1){
            $query="DELETE FROM dbo.allocationjobdone
            WHERE allocationjobdone_date='".$date."' and allocationjobdone_operatorid='".$operator."' ";
        }
        
    }
    //show($query);
    $sql = $db->prepare($query); 
    $sql->execute();

}

function create_css ($db){
    $basetable='baseallocationwork';
    $query="SELECT  *
        FROM [barcode].[dbo].[baseallocationwork]
        order by  baseallocationwork_id asc
        ";
    $sql = $db->prepare($query); 
    $sql->execute();
    $rowtemp=$sql->fetchall();
    
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

?>


