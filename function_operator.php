<?php

function manage_post_operator($db){
    //unset($_SESSION['temp']['operator']);
    if(!empty($_POST['presetdate'])){presetdate();}

    if(!empty($_POST['date_operator_start'])){
        $_SESSION['temp']['operator']['date_operator_start']=$_POST['date_operator_start'];
    }
    if(!empty($_POST['date_operator_end'])){
        $_SESSION['temp']['operator']['date_operator_end']=$_POST['date_operator_end'];
    }
    if(!empty($_SESSION['temp']['operator']['date_operator_start'])){
        $_POST['date_operator_start']=$_SESSION['temp']['operator']['date_operator_start'];
    }
    if(!empty($_SESSION['temp']['operator']['date_operator_end'])){
        $_POST['date_operator_end']=$_SESSION['temp']['operator']['date_operator_end'];
    }
    show_debug();
    
    if($_POST['action']=='show_main_page'){
        show_main_page($db);
    }
   
}

function general_view_operator($db){//show_radar_chart($data);?>
    <div class="row all_page">
        <div class="col-xs-3"><?php show_list_operator($db)?></div>
        <div class="col-xs-9 main_page"><?php show_main_page($db)?></div>
    </div>
    <style>
        .all_page{
            text-align: center;
        }
        .operator_list,.contract_list{
            margin-top:5px;
            margin-bottom:5px;
            border: 0.25px solid #dfdede; 
            border-radius: 1rem;
            text-align: center;
            padding: 5px;
            box-shadow: 5px 5px 10px rgb(0 0 0 / 10%);
        }
        .operator_container{
           padding: 5px;
            
        }
        .main_page {
            position: sticky;
            top: 0;
            
            z-index: 100;
        }
        .content_page{
            padding:10px;
        }
        .tile_main{
            padding:10px;
        }
        .operator_title{
            font-size: 30px ;
            padding:10px;
        }
        .tile_title{
            font-size: 20px ;
        }
        .scan_tile{
            margin-top:5px;
            margin-bottom:5px;
            border: 0.25px solid #dfdede; 
            border-radius: 1rem;
            text-align: center;
            padding: 5px;
            box-shadow: 5px 5px 10px rgb(0 0 0 / 10%);
        }
        .scan_header{
            font-size: 16px ;
            border-radius: 0.5rem;
        }
    </style>
    <?php
    create_css_operaror($db);
}

function show_list_operator($db){?>
    <div class="operator_container">
        <?php
        foreach(get_list_operator_view($db) as $operator){?>
            <div class="col-xs-6 operator_list" onclick="load_operator('<?php echo $operator['name']?>');"><?php echo $operator['name']?></div>
        <?php
        }?>
    </div>
    <script>
        function load_operator(operator){
            var request =$.ajax({
                type:'POST',
                url:'operator_ajax.php',
                data: {operator:operator,action:'show_main_page'},
                success:function(html){
                    $('.main_page').empty().append(html);
                }
            });
        }
    </script>
    <?php
}
function show_filter_operator($db){?>
    <div class="contract_list Full">Permanent</div>
    <div class="contract_list Casual">Casual</div>
    <div class="contract_list PartTime">PartTime</div>
    <?php
}

function show_main_page($db){
    if(!empty($_POST['operator'])){?>
    <div class="row"><?php show_header_operator($db)?></div>
    <div class="content_page">
        <div class="col-xs-6 tile_main"><?php show_roster_summary($db)?></div>
        <div class="col-xs-6 tile_main"><?php show_scan_summary($db)?></div>
        <div class="col-xs-6 tile_main"><?php show_training_summary($db)?></div>
        <div class="col-xs-6 tile_main"><?php show_injury_summary($db)?></div>
    </div>
    <?php
    }
}
function show_header_operator($db){
    
        if(empty($_POST['date_operator_start'])){$_POST['date_operator_start']=date('Y-m-d',strtotime('first day of January ' . date('Y')));}
        if(empty($_POST['date_operator_end'])){$_POST['date_operator_end']=date('Y-m-d',time());}
    ?>
    <div class="col-xs-3"><!--<span class="glyphicon glyphicon-arrow-left"><span>--></div>
    <div class="col-xs-6">
        <div class="row operator_title"><?php echo $_POST['operator']?></div>
        <div class="row">
            
            <div class="col-xs-4"><input oninput="load_date();" type="date" class="form-control" id="date_operator_start" name="date_operator_start" value="<?php echo $_POST['date_operator_start']?>"></div>
            <div class="col-xs-4"><input oninput="load_date();" type="date" class="form-control" id="date_operator_end" name="date_operator_end" value="<?php echo $_POST['date_operator_end']?>"></div>
            <div class="col-xs-4">
                <select class="form-control" id="presetdate" oninput="load_presetdate();">
                    <option selected></option>
                    <option>Current Year</option>
                    <option>Last Year</option>
                    <option>Current Month</option>
                    <option>Last Month</option>
                    <!--<option>Current Week</option>
                    <option>Last Week</option>-->
                </select>
            </div>
        </div>
    </div>
    <div class="col-xs-3"><!--<span class="glyphicon glyphicon-arrow-right"><span>--></div>
    <script>
        function load_date(){
            date_operator_start=document.getElementById('date_operator_start').value;
            date_operator_end=document.getElementById('date_operator_end').value;
            var request =$.ajax({
                type:'POST',
                url:'operator_ajax.php',
                data: {operator:'<?php echo $_POST['operator']?>',action:'show_main_page',date_operator_start:date_operator_start,date_operator_end:date_operator_end},
                success:function(html){
                    $('.main_page').empty().append(html);
                }
            });
        }
        function load_presetdate(){
            presetdate=document.getElementById('presetdate').value;
            var request =$.ajax({
                type:'POST',
                url:'operator_ajax.php',
                data: {operator:'<?php echo $_POST['operator']?>',action:'show_main_page',presetdate:presetdate},
                success:function(html){
                    $('.main_page').empty().append(html);
                }
            });
        }
    </script>
    
    <?php
    
}
function show_roster_summary($db){
    $summary_roster=get_summary_roster_operator($db);//show($summary_scan);?>
    <div class="row tile_title">Roster Summary</div>
    <div class="col-xs-3 ">
        <div class="scan_tile">
            <div class="scan_header ">Total Overtime</div>
            <div class="scan_content">999 h</div>
        </div>
    </div>
    <?php foreach($summary_roster as $allocation){?>
        <div class="col-xs-2 ">
            <div class="scan_tile">
                <div class="scan_header <?php echo $allocation['allocation_code']?>"><?php echo $allocation['allocation_code']?></div>
                <div class="scan_content"><?php echo $allocation['nbr_day']?> day<?php if($allocation['nbr_day']>1){echo's';}?></div>
            </div>
        </div>

        <?php        
    }?> 
    
    
    <?php
}
function show_scan_summary($db){
    $summary_scan=get_summary_scan_operator($db);//show($summary_scan);?>
    <div class="row tile_title">Scan Summary</div>
    <div class="col-xs-6 ">
        <div class="scan_tile">
            <div class="scan_header">Total Hours</div>
            <div class="scan_content"><?php echo number_format($summary_scan['Total'])?></div>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="scan_tile">
            <div class="scan_header">Days with Scans</div>
            <div class="scan_content"><?php echo number_format($summary_scan['days_scanned'])?></div>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="scan_tile">
            <div class="scan_header">Workarea</div>
            <div class="scan_content">
                <?php foreach($summary_scan['WorkArea'] as $workarea){?>
                    <div class="row"><?php echo$workarea['WorkArea']?> : <?php echo round($workarea['Total'],1)?> h</div>
                    <?php
                }?>
            </div>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="scan_tile">
            <div class="scan_header">Top Process</div>
            <div class="scan_content">
                <?php foreach($summary_scan['Code'] as $Code){?>
                    <div class="row"><?php echo$Code['Code']?> : <?php echo round($Code['Total'],1)?> h</div>
                    <?php
                }?>
            </div>
        </div>
    </div>
    <?php
    
}
function show_training_summary($db){
    $summary_training=get_summary_training_operator($db);?>
    <div class="row tile_title">Training Summary</div>
    <div class="row">
        <div class="col-xs-6 ">
            <div class="scan_tile">
                <div class="scan_header">Last Training</div>
                <div class="scan_content">
                    <div class="row">OverMoulding of the PHM4-70-300 Range</div>
                    <div class="row">29-03-2022</div>
                </div>
            </div>
        </div>
        <div class="col-xs-6 ">
            <div class="scan_tile">
                <div class="scan_header">Training Completed</div>
                <div class="scan_content">
                    <div class="row">25</div>
                </div>
            </div>
        </div>  
    </div>
    <div class="row">
        <?php foreach($summary_training['WorkArea'] as $workarea) { ?>
            <div class="col-xs-3 ">
                <div class="scan_tile">
                    <div class="scan_header"><?php echo $workarea['WorkArea']?></div>
                    <div class="scan_content">
                        <div class="row">
                            <div class="col-xs-6 "><?php echo $workarea['Done']?></div>
                            <div class="col-xs-6 "><?php echo round($workarea['Done']/($workarea['Done']+$workarea['Tobedone'])*100)?>%</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }?>
    </div>
    <style>

    </style>
    <script>

    </script>

    <?php
    
}
function show_injury_summary($db){
    $summary_injury=get_summary_injury_operator($db);?>
    <div class="row tile_title">Injury Risk Summary</div>
    <div class="row">
        <?php foreach($summary_injury as $injury_risk) { ?>
            <div class="col-xs-3 ">
                <div class="scan_tile">
                    <div class="scan_header"><?php echo $injury_risk['Type']?></div>
                    <div class="scan_content">
                        <div class="row"><?php echo $injury_risk['date_start']?></div>
                        <div class="row"><?php echo $injury_risk['date_end']?></div>
                    </div>
                </div>
            </div>
            <?php
        }?>
    </div>
    <?php
    
}



function get_list_operator_view($db){
    $date=date('Y-m-d',time());
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
    if($_SESSION['temp']['schedule_sort']=='workarea'){ 
        if($_SESSION['temp']['schedule_sort_ascdesc']=='ASC'){
            $sort='allocationwork_code,allocationshift_code DESC,allocationwork_operatorid';
        }else{
            $sort='allocationwork_code DESC,allocationshift_code DESC,allocationwork_operatorid';
        }
    }elseif($_SESSION['temp']['schedule_sort']=='operator'){
        if($_SESSION['temp']['schedule_sort_ascdesc']=='ASC'){
            $sort='allocationwork_operatorid,allocationwork_code,allocationshift_code DESC';
        }else{
            $sort='allocationwork_operatorid DESC,allocationwork_code,allocationshift_code DESC';
        }
    }else{
        $sort='allocationwork_operatorid,allocationwork_code,allocationshift_code DESC';
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
    WHERE allocationwork_date='".$date."' ".$filter." 
    and allocationshift_code <> 'Null' and operator_active=1
    AND ((DATEPART(DW,allocationcontract_date)<>6 and DATEPART(DW,allocationcontract_date)<>7 
        ) or((DATEPART(DW,allocationcontract_date)=6 or DATEPART(DW,allocationcontract_date)=7)and baseallocation_working<100 ))
    AND (baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100)>0
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
            
            
            $row[$line["allocationwork_operatorid"]]['name']=$line["allocationwork_operatorid"];
            $row[$line["allocationwork_operatorid"]]['workarea']=$line["allocationwork_code"];
            $row[$line["allocationwork_operatorid"]]['shift']=$line["allocationshift_code"];
            $row[$line["allocationwork_operatorid"]]['contract']=$line["allocationcontract_code"];
            $row[$line["allocationwork_operatorid"]]['hours_available']=$line["hours_available"];
            $row[$line["allocationwork_operatorid"]]['ot_before']=$line["ot_before"];
            $row[$line["allocationwork_operatorid"]]['ot_after']=$line["ot_after"];
        }
        
    }
    
    //array_multisort($row,SORT_DESC);
        
   
    // }
    
    
    
    //show($query);
   
   return $row;
}
function get_summary_scan_operator($db){
    $query="SELECT WorkArea,sum(total_hours) as total_hours
        FROM MIS_Operator_hours_scanned
        left join MIS_List on scan_jobnumber=ManufactureIssueNumber
        where operator_fullname='".$_POST['operator']."' and scan_date>='".$_POST['date_operator_start']."' and scan_date<='".$_POST['date_operator_end']."'
        group by WorkArea
        order by sum(total_hours) desc";
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetchall();
    $return=array();
    foreach($row as $line){
        $return['Total']=$return['Total']+$line['total_hours'];
        if(empty($line['WorkArea'])){$line['WorkArea']='Other';}
        $return['WorkArea'][$line['WorkArea']]['Total']=$return['WorkArea'][$line['WorkArea']]['Total']+$line['total_hours'];
        $return['WorkArea'][$line['WorkArea']]['WorkArea']=$line['WorkArea'];
    }


    $query="SELECT TOP 5 Code,sum(total_hours) as total_hours
        FROM MIS_Operator_hours_scanned
        left join MIS_List on scan_jobnumber=ManufactureIssueNumber
        where operator_fullname='".$_POST['operator']."' and scan_date>='".$_POST['date_operator_start']."' and scan_date<='".$_POST['date_operator_end']."'
        group by Code
        order by sum(total_hours) DESC";
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetchall();
    foreach($row as $line){
       $return['Code'][$line['Code']]['Total']=$return['Code'][$line['Code']]['Total']+$line['total_hours'];
       $return['Code'][$line['Code']]['Code']=$line['Code'];
    }

    $query="SELECT count(DISTINCT scan_date) as days_scanned
        FROM MIS_Operator_hours_scanned
        where operator_fullname='".$_POST['operator']."' and scan_date>='".$_POST['date_operator_start']."' and scan_date<='".$_POST['date_operator_end']."' and total_hours>1
         ";
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();
    $return['days_scanned']=$row['days_scanned'];

    return $return;
}
function get_summary_roster_operator($db){
    $query="SELECT * 
    from allocation 
    left join baseallocation on  baseallocation_code=allocation_code
    WHERE allocation_operatorid='".$_POST['operator']."' and allocation_date>='".$_POST['date_operator_start']."' and allocation_date<='".$_POST['date_operator_end']."' and baseallocation_name is not null
    order by allocation_date ASC ";
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetchall();
    $return=array();
    //show($row);
    $last_date='';
    $last_code='';
    foreach($row as $line){
        //$return[$line['allocation_code']][$line['allocation_date']]['allocation_date']=$line['allocation_date'];
        //$return[$line['allocation_code']][$line['allocation_date']]['allocation_code']=$line['baseallocation_name'];
        
        
        // if($last_date==date('Y-m-d',strtotime($line['allocation_date'].'-'.$return[$last_date]['nbr_day'].'days')) and $last_code==$line['allocation_code']){
        //     $return[$last_date]['date_end']=$line['allocation_date'];
        //     $return[$last_date]['nbr_day']++;
        // }else{
        //     $return[$line['allocation_date']]['allocation_code']=$line['allocation_code'];
        //     $return[$line['allocation_date']]['date_start']=$line['allocation_date'];
        //     $return[$line['allocation_date']]['date_end']=$line['allocation_date'];
        //     $return[$line['allocation_date']]['nbr_day']++;
        //     $last_date=$line['allocation_date'];
        // }
        // $last_code=$line['allocation_code'];

       
        $return[$line['allocation_code']]['allocation_code']=$line['allocation_code'];
        $return[$line['allocation_code']]['nbr_day']++;
        $return[$line['allocation_code']]['days'][]=$line['allocation_date'];
    }
    //show($return);
    return $return;
}
function get_summary_training_operator($db){
    $query="SELECT * 
    from allocation 
    left join baseallocation on  baseallocation_code=allocation_code
    WHERE allocation_operatorid='".$_POST['operator']."' and allocation_date>='".$_POST['date_operator_start']."' and allocation_date<='".$_POST['date_operator_end']."' and baseallocation_name is not null
    order by allocation_date ASC ";
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetchall();
    $return=array();
    //show($row);
    $return['WorkArea']['General']['Done']=3;
    $return['WorkArea']['General']['Tobedone']=5;
    $return['WorkArea']['General']['WorkArea']='General';
    $return['WorkArea']['General']['Last_trainin']='29-03-2022';

    $return['WorkArea']['Assembly']['Done']=7;
    $return['WorkArea']['Assembly']['Tobedone']=10;
    $return['WorkArea']['Assembly']['WorkArea']='Assembly';
    $return['WorkArea']['Assembly']['Last_trainin']='29-03-2022';

    $return['WorkArea']['Moulding']['Done']=12;
    $return['WorkArea']['Moulding']['Tobedone']=15;
    $return['WorkArea']['Moulding']['WorkArea']='Moulding';
    $return['WorkArea']['Moulding']['Last_trainin']='29-03-2022';

    $return['WorkArea']['Machining']['Done']=2;
    $return['WorkArea']['Machining']['Tobedone']=10;
    $return['WorkArea']['Machining']['WorkArea']='Machining';
    $return['WorkArea']['Machining']['Last_trainin']='29-03-2022';

    
    
    return $return;
}
function get_summary_injury_operator($db){
    $query="SELECT * 
    from allocation 
    left join baseallocation on  baseallocation_code=allocation_code
    WHERE allocation_operatorid='".$_POST['operator']."' and allocation_date>='".$_POST['date_operator_start']."' and allocation_date<='".$_POST['date_operator_end']."' and baseallocation_name is not null
    order by allocation_date ASC ";
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetchall();
    $return=array();
    //show($row);
    $return['1']['Type']='Shoulder Injury';
    $return['1']['date_start']='01-01-2021';
    $return['1']['date_end']='01-06-2022';
    $return['2']['Type']='Back Injury';
    $return['2']['date_start']='01-01-2021';
    $return['2']['date_end']='01-06-2022';
    
    //show($return);
    
    
    return $return;
}
function create_css_operaror ($db){
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

function show_radar_chart($data){?>
   
    
    <style></style>
    <script></script>
    <?php
}

function presetdate(){
    if($_POST['presetdate']=='Last Year'){
        $_POST['date_operator_start']=date('Y-m-d',strtotime('first day of January last year' . date('Y')));
        $_POST['date_operator_end']=date('Y-m-d',strtotime('last day of December last year' . date('Y')));
    }
    if($_POST['presetdate']=='Current Year'){
        $_POST['date_operator_start']=date('Y-m-d',strtotime('first day of January this year' . date('Y')));
        $_POST['date_operator_end']=date('Y-m-d',strtotime('last day of December this year' . date('Y')));
    }
    if($_POST['presetdate']=='Last Month'){
        $_POST['date_operator_start']=date('Y-m-d',strtotime('first day of last month' ));
        $_POST['date_operator_end']=date('Y-m-d',strtotime('last day of last month'));
    }
    if($_POST['presetdate']=='Current Month'){
        $_POST['date_operator_start']=date('Y-m-d',strtotime('first day of this month' ));
        $_POST['date_operator_end']=date('Y-m-d',strtotime('last day of this month'));
    }
    if($_POST['presetdate']=='Last Week'){
        $_POST['date_operator_start']=date('Y-m-d',strtotime('first day of last week' ));
        $_POST['date_operator_end']=date('Y-m-d',strtotime('last day of last week'));
    }
    if($_POST['presetdate']=='Current Week'){
        $_POST['date_operator_start']=date('Y-m-d',strtotime('first day of this week' ));
        $_POST['date_operator_end']=date('Y-m-d',strtotime('last day of this week'));
    }
}


?>