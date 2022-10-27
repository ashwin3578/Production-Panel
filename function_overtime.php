<?php

function manage_post_overtime($db){
    if(!empty($_GET['debug'])){
        $_SESSION['temp']['debug']=$_GET['debug'];
    }
    
    if($_SESSION['temp']['debug']=='1'){
        show($_POST);
    }
    if(empty($_POST['date_productlist'])){
        $_POST['date_productlist']=date('Y-m-d',strtotime(date('Y-m-d',time()-3600*24).' next friday'));
    }

    if(!empty($_POST['workarea'])){
        $_SESSION['temp']['ot_workarea']=$_POST['workarea'];
    }else{
        if(!empty($_SESSION['temp']['ot_workarea'])){
            $_POST['workarea']=$_SESSION['temp']['ot_workarea'];
        }
    }  
    if(!empty($_POST['shift'])){
        $_SESSION['temp']['ot_shift']=$_POST['shift'];
    }else{
        if(!empty($_SESSION['temp']['ot_shift'])){
            $_POST['shift']=$_SESSION['temp']['ot_shift'];
        }
    } 

    if(!empty($_POST['sort'])){
        if($_SESSION['temp']['ot_sort']==$_POST['sort']){
            if($_SESSION['temp']['ot_sort_ascdesc']=='ASC'){
                $_SESSION['temp']['ot_sort_ascdesc']='DESC';
            }else{
                $_SESSION['temp']['ot_sort_ascdesc']='ASC';
            }
            
        }
        $_SESSION['temp']['ot_sort']=$_POST['sort'];
    }else{
        if(!empty($_SESSION['temp']['ot_sort'])){
            $_POST['sort']=$_SESSION['temp']['ot_sort'];
        }
    }



    



    $_POST['date_filter']=date('Y-m-d',strtotime(($_POST['date_filter']).' +1days '));
    $_POST['date_filter']=date('Y-m-d',strtotime(($_POST['date_filter']).' last monday'));

    if($_POST['action']=='save_ot'){
       
        save_ot($db);
        
        
        
        $alldata=get_list_operator_ot($db,$_POST['date_filter']);
        
        $operator=$alldata[$_POST['ot_operatorcode']];
        show_line_ot_operator(
            $db,
            $operator,
            $_POST['rownumber']
            
        );
        
    }


    if($_POST['action']=='show_all_ot'){
        show_all_ot($db);
    }
    if($_POST['action']=='show_navbar_top'){
        navbar_top_overtime($db);
    }
}

function general_view_overtime($db){
    echo'<div class="navbar_top">';
    navbar_top_overtime($db);
    echo'</div>';
    create_css_overtime ($db)
    
     
    ?>
    <div class="main_page">
        <?php show_all_ot($db);?>
    </div>
    <?php
}

function navbar_top_overtime($db,$option=''){
    
    if(empty($_POST['date_filter'])){
        $_POST['date_filter']=date('Y-m-d',time());
    }
    //$_POST['date_filter']=date('Y-m-d',time());
    //show($_POST['date_filter']);
    $timetag=strtotime($_POST['date_filter']);
    $datetoshow='From '.date('jS M',$timetag).' to '.date('jS M Y',$timetag+6*3600*24);
    
    ?>
    <script>
       
        function loaddate(date){
            var request =$.ajax({
                type:'POST',
                url:'ot_ajax.php',
                data: {date_filter:date,action:'show_all_ot'},
                success:function(html){
                    $('.main_page').empty().append(html);
                }
            });
            var request =$.ajax({
                type:'POST',
                url:'ot_ajax.php',
                data: {date_filter:date,action:'show_navbar_top',option:'<?php echo$option;?>'},
                success:function(html){
                    $('.navbar_top').empty().append(html);
                }
            });
            
        }

        
        
    </script>



    <div class="row "style="text-align:center">
       
        <div  class="col-xs-3 col-sm-3 col-md-1 col-lg-1">
            <span class="glyphicon glyphicon-step-backward" onclick="loaddate('<?php echo date('Y-m-d',strtotime($_POST['date_filter']).' - 7days');?>');"></span>
        </div>
        <div  class="col-xs-6 col-sm-6 col-md-2 col-lg-2" >
            <?php echo $datetoshow;?>
        </div>
        <div  class="col-xs-3 col-sm-3 col-md-1 col-lg-1">
        <span class="glyphicon glyphicon-step-forward" onclick="loaddate('<?php echo date('Y-m-d',strtotime($_POST['date_filter'])+3600*24*8);?>');"></span>
        </div>
        
        <div  class="col-xs-3 col-sm-3 col-md-2 col-lg-1">
            <select class='btn btn-default ' id="workarea" onchange="loadworkarea();">
                <option>All WorkArea</option>   
                <!--<option disabled></option>  -->
                <option <?php if($_POST['workarea']=='Manufacturing'){echo'selected';}?>>Manufacturing</option>
                <?php foreach(get_list_workarea_ot($db) as $workarea){?>
                    <option <?php if($_POST['workarea']==$workarea['allocationwork_code']){echo'selected';}?>><?php echo$workarea['allocationwork_code']?></option>
                <?php
                }?>
                
            </select>
        </div>
        <div  class="col-xs-3 col-sm-3 col-md-2 col-lg-1">
            <select class='btn btn-default ' id="shift" onchange="loadshift();">
                <option>All Shift</option>    
                <option <?php if($_POST['shift']=='Morning'){echo'selected';}?>>Morning</option>
                <option <?php if($_POST['shift']=='Afternoon'){echo'selected';}?>>Afternoon</option>
            </select>
        </div>
        <a href="schedule.php"><div  class="col-xs-2 col-md-2 col-lg-1 "><div  class="btn btn-default">Schedule</div></div></a>
        
    </div>
    <style>
        .btn{
            width:100%;
        }
    </style>
    <script>
        function loadworkarea(){
            date='<?php echo $_POST['date_filter']?>';
            var request =$.ajax({
                type:'POST',
                url:'ot_ajax.php',
                data: {date_filter:date,action:'show_all_ot',workarea:document.getElementById("workarea").value},
                success:function(html){
                    $('.main_page').empty().append(html);
                }
            });
            var request =$.ajax({
                type:'POST',
                url:'ot_ajax.php',
                data: {date_filter:date,action:'show_navbar_top',workarea:document.getElementById("workarea").value},
                success:function(html){
                    $('.navbar_top').empty().append(html);
                }
            });
        }
        function loadshift(){
            date='<?php echo $_POST['date_filter']?>';
            var request =$.ajax({
                type:'POST',
                url:'ot_ajax.php',
                data: {date_filter:date,action:'show_all_ot',shift:document.getElementById("shift").value},
                success:function(html){
                    $('.main_page').empty().append(html);
                }
            });
            var request =$.ajax({
                type:'POST',
                url:'ot_ajax.php',
                data: {date_filter:date,action:'show_navbar_top',shift:document.getElementById("shift").value},
                success:function(html){
                    $('.navbar_top').empty().append(html);
                }
            });
        }
    </script>
    
    
    

    
    
    <?php 
}
function show_all_ot($db){?>
    <div class="row">
        <div class="col-md-10">
        <?php show_calendar_ot($db); ?>
        </div>
        <div class="col-md-2 all_MIS" style="position: sticky;top: 0;">
        <div >
        <?php //show_MIS_Prodplan($db); ?>
        </div></div>
    </div>
    <?php
}
function show_calendar_ot($db){?>
    <br>
    <div class="row machine_header sticky_header">
        <div class="col-xs-5  col-sm-4 col-md-4 col-lg-3">
            <div class="col-md-4 col-xs-6 ">Workarea <span class="glyphicon glyphicon-sort" onclick="sortby('<?php echo $_POST['date_filter']?>','workarea')"></span></div>
            <div class="col-md-4 col-xs-6 ">Shift</div>
            <div class="col-md-4 col-xs-12">Operator <span class="glyphicon glyphicon-sort" onclick="sortby('<?php echo $_POST['date_filter']?>','operator')"></span></div>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">Available</div>
        <!--<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">Action</div>-->
        <div class="col-xs-3 col-sm-4 col-md-5 col-lg-6">
            <div class="col-xs-2">Monday</div>
            <div class="col-xs-2">Tuesday</div>
            <div class="col-xs-2">Wednesday</div>
            <div class="col-xs-2">Thursday</div>
            <div class="col-xs-2">Friday</div>
            <div class="col-xs-2">Saturday</div>
            
        </div>
        <div class="col-xs-1 "></div>    
    </div>
    <?php
    $alldata=get_list_operator_ot($db,$_POST['date_filter']);
    //$allot=get_all_ot($db,$_POST['date_filter']);
    //show($alldata);
    $rownumber=0;
    $allocations=array();
    foreach($alldata as $operator){
    
           
        //show($allallocation[$operator['name']]);
        ?>
        <div class="row machine_row row_<?php echo $rownumber?> ">
        <?php show_line_ot_operator($db,$operator,$rownumber);$rownumber++;?>
        </div>  
        <?php
    }
    ?>
    <script>
       function sortby(date,sort){
            var request =$.ajax({
                type:'POST',
                url:'ot_ajax.php',
                data: {date_filter:date,action:'show_all_ot',sort:sort},
                success:function(html){
                    $('.main_page').empty().append(html);
                }
            });
        }
       function save_ot(date,operator,rownumber,day,beforeafter,hours){
             var request =$.ajax({
                type:'POST',
                url:'ot_ajax.php',
                data: {
                    date_filter:date,
                    ot_operatorcode:operator,
                    day:day,
                    beforeafter:beforeafter,
                    rownumber:rownumber,
                    hours:hours,
                    action:'save_ot'
                    },
                success:function(html){
                    $('.row_'+rownumber).empty().append(html);
                }
            });
       }
       
    </script>
    <?php    
}
function show_line_ot_operator($db,$operator,$rownumber){
    
    //allocation[hours_start]=duration
    //show($operator);
    if(!empty($_SESSION['temp']['role_schedule_admin'])){
        $disabled='';
    }else{
        $disabled=' disabled ';
    }
    ?>

    <div class="col-xs-5  col-sm-4 col-md-4 col-lg-3">
        <div class="col-md-4 col-xs-6 <?php echo $operator['workarea']?>"><?php echo $operator['workarea']?></div>
        <div class="col-md-4 col-xs-6 <?php echo $operator['shift']?>"><?php echo $operator['shift']?></div>
        <div class="col-md-4 col-xs-12 <?php echo $operator['contract']?>"><?php echo $operator['name']?></div>
    </div>
    <div class="col-xs-2 col-sm-2 col-md-1 col-lg-1"><?php echo round($operator['ot_before'],0)?> / <?php echo round($operator['hours_available'],0)?> / <?php echo round($operator['ot_after'],0)?></div>
    <!--<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1 <?php echo $operator['action']?>"><?php echo $operator['action']?></div>-->
    <div class="col-xs-3 col-sm-4 col-md-5 col-lg-6">
        <?php 
        $i=0;
        $alldays[$i]['date']=$_POST['date_filter'];
        for($i=1;$i<6;$i++){
            $alldays[$i]['date']=date('Y-m-d',strtotime($alldays[$i-1]['date'].' +1days'));
        }
        foreach($alldays as $day){?>
            <div class="col-xs-2"style=" border: 1px solid #6b6a6a;">
                <div class="row <?php echo $operator['action'][$day['date']]?>">
                    <?php if(!empty($operator['action'][$day['date']])){echo $operator['action'][$day['date']];}else{echo'<br>';}?>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <?php if(!empty($_SESSION['temp']['role_schedule_admin'])){?>
                        <input 
                        type="text" 
                        class="form-control" 
                        value="<?php echo round($operator['ot'][$day['date']]['before']+0);?>"
                        oninput="save_ot('<?php echo $day['date']?>','<?php echo $operator['name']?>',<?php echo $rownumber?>,'<?php echo $day['date']?>','before',this.value)"
                        >
                    <?php }else{echo round($operator['ot'][$day['date']]['before']+0);}?>
                    </div>
                    <div class="col-xs-6">
                        <?php if(!empty($_SESSION['temp']['role_schedule_admin'])){?>
                        <input 
                        type="text" 
                        class="form-control" 
                        value="<?php echo round($operator['ot'][$day['date']]['after']+0);?>"
                        oninput="save_ot('<?php echo $day['date']?>','<?php echo $operator['name']?>',<?php echo $rownumber?>,'<?php echo $day['date']?>','after',this.value)"
                        >
                        <?php }else{echo round($operator['ot'][$day['date']]['after']+0);}?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        
        
    </div>
    <!--<div class="col-xs-1 "><button class="btn btn-primary glyphicon glyphicon-arrow-right"></button></div> -->
        


    <?php
}


function get_list_workarea_ot($db){
    
    $query="
    SELECT DISTINCT allocationwork_code FROM dbo.allocationwork
    where allocationwork_code<>''  
    order by allocationwork_code asc  
    ";
    
    $sql = $db->prepare($query); 
    $sql->execute();
    
    $row=$sql->fetchall();
    
   return $row;
}
function get_list_operator_ot($db,$date){
    $date2=date('Y-m-d',strtotime($date)+3600*24*7);
    $filter='';
    if(!empty($_POST['workarea']) and $_POST['workarea']<>'All WorkArea'){
        
        if($_POST['workarea']=='Manufacturing'){
            $filter=$filter."AND allocationwork_code<>'Assembly'";
        }else{
            $filter=$filter."AND allocationwork_code='".$_POST['workarea']."'";
        }
    }

    if(!empty($_POST['shift']) and $_POST['shift']<>'All Shift'){
        $filter=$filter."AND allocationshift_code='".$_POST['shift']."'";
    }
    if($_SESSION['temp']['ot_sort']=='workarea'){ 
        if($_SESSION['temp']['ot_sort_ascdesc']=='ASC'){
            $sort='allocationwork_code,allocationshift_code DESC,allocationwork_operatorid';
        }else{
            $sort='allocationwork_code DESC,allocationshift_code DESC,allocationwork_operatorid';
        }
    }elseif($_SESSION['temp']['ot_sort']=='operator'){
        if($_SESSION['temp']['ot_sort_ascdesc']=='ASC'){
            $sort='allocationwork_operatorid,allocationwork_code,allocationshift_code DESC';
        }else{
            $sort='allocationwork_operatorid DESC,allocationwork_code,allocationshift_code DESC';
        }
    }else{
        $sort='allocationwork_code,allocationshift_code DESC,allocationwork_operatorid';
    }

    

    $query="SET DATEFIRST 1;
    SELECT (baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100) as hours_available,* FROM dbo.allocationwork
    left join allocation on  allocation_date=allocationwork_date and allocation_operatorid=allocationwork_operatorid
    left join allocationshift on  allocationshift_date=allocationwork_date and allocationshift_operatorid=allocationwork_operatorid
    left join allocationcontract on  allocationcontract_date=allocationwork_date and allocationcontract_operatorid=allocationwork_operatorid
    left join allocationjobdone on  allocationjobdone_date=allocationwork_date and allocationjobdone_operatorid=allocationwork_operatorid
    left join operator on operator_fullname=allocationwork_operatorid
    left join baseallocation on  baseallocation_code=allocation_code
    left join baseallocationcontract on  baseallocationcontract_code=allocationcontract_code 
    left join baseallocationwork on  baseallocationwork_code=allocationwork_code  
    left join ot on  ot_operatorid=allocationwork_operatorid and ot_date=allocationwork_date
    WHERE allocationwork_date>='".$date."' and allocationwork_date<='".$date2."' ".$filter." 
    and allocationshift_code <> 'Null' and operator_active=1
   
    order by $sort 
    ";
    
    //show($query);
    $sql = $db->prepare($query); 
    $sql->execute();
    //show(nbr_of_line);
    $rowtemp=$sql->fetchall();
    //$row['Total']=array();
    foreach($rowtemp as $line){
        //$row[$line[$table.'_date']][$line[$table."_operatorid"]]=$line[$table."_code"];
        
        if($line["allocationwork_working"]<100){
            //$row['Total'][$line["allocationwork_code"]]['number']++;
            //$row['Total'][$line["allocationwork_code"]]['name']=$line["allocationwork_code"];
            
            $row[$line["allocationwork_operatorid"]]['workarea']=$line["allocationwork_code"];
            $row[$line["allocationwork_operatorid"]]['shift']=$line["allocationshift_code"];
            $row[$line["allocationwork_operatorid"]]['name']=$line["allocationwork_operatorid"];
            $row[$line["allocationwork_operatorid"]]['contract']=$line["allocationcontract_code"];
            $row[$line["allocationwork_operatorid"]]['hours_available']=$row[$line["allocationwork_operatorid"]]['hours_available']+$line["hours_available"];
            $row[$line["allocationwork_operatorid"]]['details_hours_available'][$line['allocationwork_date']]=$line["hours_available"];
            $row[$line["allocationwork_operatorid"]]['action'][$line['allocationwork_date']]=$line["allocation_code"];
            $row[$line["allocationwork_operatorid"]]['ot'][$line['allocationwork_date']]['before']=$line["ot_before"];
            $row[$line["allocationwork_operatorid"]]['ot'][$line['allocationwork_date']]['after']=$line["ot_after"];
            $row[$line["allocationwork_operatorid"]]['ot_before']=$row[$line["allocationwork_operatorid"]]['ot_before']+$line["ot_before"];
            $row[$line["allocationwork_operatorid"]]['ot_after']=$row[$line["allocationwork_operatorid"]]['ot_after']+$line["ot_after"];
        }
        
    }
    //array_multisort($row['Total'],SORT_DESC);
    //array_multisort($row,SORT_DESC);
        
   
    // }
    
    
    
    //show($query);
    //show($row);
   return $row;
}
function get_all_ot($db,$date){

}

function create_css_overtime ($db){
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
    $basetable='baseallocationshift';
    $query="SELECT  *
        FROM [barcode].[dbo].[baseallocationshift]
        order by  baseallocationshift_id asc
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
    $basetable='baseallocationcontract';
    $query="SELECT  *
        FROM [barcode].[dbo].[baseallocationcontract]
        order by  baseallocationcontract_id asc
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
    $basetable='baseallocation';
    $query="SELECT  *
        FROM [barcode].[dbo].[baseallocation]
        order by  baseallocation_id asc
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
function save_ot($db){
    $query="SELECT ot_id FROM ot WHERE
    ot_operatorid='".$_POST['ot_operatorcode']."'
    and ot_date= '".$_POST['day']."'
    
    ";
     $sql = $db->prepare($query); 
     $sql->execute();
     $row=$sql->fetch();
     //show($query);
     if(empty($row)){
        $query="
        INSERT INTO ot(
            ot_operatorid,
            ot_date,
            ot_".$_POST['beforeafter']."
        )
        VALUES(
            '".$_POST['ot_operatorcode']."',
            '".$_POST['day']."',
            '".$_POST['hours']."'
            )";
     }else{
        $query="UPDATE ot
        SET ot_".$_POST['beforeafter']."='".$_POST['hours']."'
        WHERE  ot_operatorid='".$_POST['ot_operatorcode']."'
        and ot_date= '".$_POST['day']."'
        ";
     }
     //show($query);
     $sql = $db->prepare($query); 
     $sql->execute();
}








?>