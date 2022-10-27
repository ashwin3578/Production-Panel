<?php

function manage_POST_matrix($db){
    //show ($_SESSION['temp']['summary']);
    if(empty($_POST['date_to_show_start'])){
        $_POST['date_to_show_start']=date('Y-m-d',time());
    }
    if(empty($_POST['date_to_show_end'])){
        $_POST['date_to_show_end']=date('Y-m-d',time());
    }

    if(!empty($_POST['scan_operatorcode']) and !empty($_POST['scan_date'])){
        $_SESSION['temp']['summary']['operator']=$_POST['scan_operatorcode'];
        $_SESSION['temp']['summary']['operatorname']=$_POST['operatorname'];
        $_SESSION['temp']['summary']['operatorcode']=$_POST['scan_operatorcode'];
        $_SESSION['temp']['summary']['year']=substr($_POST['scan_date'],0,4);
        $_SESSION['temp']['summary']['month']=substr(substr($_POST['scan_date'],0,7),5,2);
        $_SESSION['temp']['summary']['yearmonth']=$_SESSION['temp']['summary']['year'].$_SESSION['temp']['summary']['month'];
        $_SESSION['temp']['summary']['days']=$_POST['scan_date'];
    }

    if(!empty($_GET['debug'])){
        $_SESSION['temp']['debug']=$_GET['debug'];
    }
    if($_SESSION['temp']['debug']=='1'){
        show($_POST);
    }
}

function navbar_matrix($db){
    echo'<div class="row ">';
        echo'<form method="POST">';
        if(!empty($_POST['scan_operatorcode'])){
            echo'<input type="hidden" name="scan_operatorcode" value="'.$_POST['scan_operatorcode'].'">';
        }
        

        echo'<div class="col-sm-3 ">';
            echo'<div class="col-sm-6 ">';
            //echo'<button type="submit" name="type" value="CreateNewReport"  class="btn btn-primary injury_button" >Create new Report</button>';
            echo'<input class="form-control" type="date" name="date_to_show_start" onchange="submit();" value="'.$_POST['date_to_show_start'].'">';
            echo'</div>';
            echo'<div class="col-sm-6 ">';
            echo'<input class="form-control" type="date" name="date_to_show_end" onchange="submit();" value="'.$_POST['date_to_show_end'].'">';
            echo'</div>';
        echo'</div>';
        echo'<div class="col-sm-2 " >';
        echo '<button type="submit" name="scan_operatorcode" value="" class="btn btn-default">Reset</button>';
        
        echo'</div>';
        echo'<div class="col-sm-2 ">';
        
       

        echo'</div>';
        echo'</form>';
    echo'</div>';
}

function main_view_matrix($db){
    echo'<div class="row ">';
        if(!empty($_POST['scan_operatorcode'])  and !empty($_POST['scan_date'])){
            $col=5;
        }else{
            $col=7;
        }
        echo'<div class="col-sm-'.$col.'" >';
            echo'<div class="col-sm-5 " >';
            showList_matrix($db);
            echo'</div>';
            echo'<div class="col-sm-4 ">';
            if(!empty($_POST['scan_operatorcode'])){
                showListprocess_matrix($db);
            }
            
            echo'</div>';
            echo'<div class="col-sm-3 ">';
            if(!empty($_POST['scan_operatorcode'])){
                showListday_matrix($db);
            }
            echo'</div>';
        echo'</div>';
        //echo'<div class="col-sm-6 ">';
            echo'<div class="Operator_show">';
            //summary_show_operator_detail($db);
            if(!empty($_POST['scan_operatorcode']) and !empty($_POST['scan_date'])){
            summary_show_operator_detail($db);
            }
            echo'</div>';
        //echo'</div>';
        
    echo'</div>';
}

function select_all_operator($db){
    $query='SELECT  distinct operator_fullname,scan_operatorcode
    FROM [barcode].[dbo].[MIS_Operator_hours_scanned]
    left join [MIS_List] on [ManufactureIssueNumber]=[scan_jobnumber]
    where [ManufactureIssueNumber] is not null and operator_fullname is not null
    order by operator_fullname asc

    ';
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $alloperator=$sql->fetchall();
    echo '<select id="operator" name="injurytype" onchange="theinjurytype=document.getElementById (\'injurytype\').value;update_dialog_choose_injury();" class="form-control" >';
        foreach ($alloperator as &$operator){
            echo '<option value="'.$operator['scan_operatorcode'].'" ';
            if($operator['injury_name']==$_POST['injurytype']){echo 'selected';}
            echo'>'.$operator['operator_fullname'].'</option> ';
        }
    echo' </select>';
  
}

function showList_matrix($db){

    //$filter=' AND scan_operatorcode=\''.$_POST['scan_operatorcode'].'\' ';
    $filter=' AND scan_date>=\''.$_POST['date_to_show_start'].'\' AND scan_date<=\''.$_POST['date_to_show_end'].'\' ';
    if(!empty($_POST['Code'])){
        $filter=$filter.' AND Code=\''.$_POST['Code'].'\' ';

    }

    $query='SELECT  operator_fullname,scan_operatorcode,sum(total_hours) as total_hours
    FROM [barcode].[dbo].[MIS_Operator_hours_scanned]
    left join [MIS_List] on [ManufactureIssueNumber]=[scan_jobnumber]
    where [ManufactureIssueNumber] is not null and operator_fullname is not null '.$filter.'
    group by operator_fullname,scan_operatorcode
    order by operator_fullname asc

    ';
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $alloperator=$sql->fetchall();
    echo'<div class="row header-summary">';
        echo'<div class="col-sm-8">';
        echo 'Operator';
        echo'</div>';
        
        echo'<div class="col-sm-4">';
        echo 'Hours';
        echo'</div>';
            
            
        
    echo'</div>';
    foreach($alloperator as $operator){
        
        echo'<div class="row row_normal ';
        
            if($_POST['scan_operatorcode']==$operator['scan_operatorcode']){echo 'row-selected';}
        echo' " onClick="document.getElementById(\'form-id'.$operator['scan_operatorcode'].'\').submit();">';
            echo'<form id="form-id'.$operator['scan_operatorcode'].'" method="POST">';
            echo'<input type="hidden" name="Code" value="'.$_POST['Code'].'">';
            echo'<input type="hidden" name="scan_operatorcode" value="'.$operator['scan_operatorcode'].'">';
            echo'<input type="hidden" name="date_to_show_start" value="'.$_POST['date_to_show_start'].'">';
            echo'<input type="hidden" name="date_to_show_end" value="'.$_POST['date_to_show_end'].'">';
            echo'<div   class="col-sm-8">';
            
            echo $operator['operator_fullname'];
            
            echo'</div>';
            echo'<div class="col-sm-4">';
            
            echo number_format(round($operator['total_hours'],1));
           
            echo'</div>';
            echo'</form>';
        echo'</div>';
        
    }

}

function showListprocess_matrix($db){

    
    $filter=' AND scan_operatorcode=\''.$_POST['scan_operatorcode'].'\' AND scan_date>=\''.$_POST['date_to_show_start'].'\' AND scan_date<=\''.$_POST['date_to_show_end'].'\' ';


    $query='SELECT  Code,operator_fullname,scan_operatorcode,sum(total_hours) as total_hours
    FROM [barcode].[dbo].[MIS_Operator_hours_scanned]
    left join [MIS_List] on [ManufactureIssueNumber]=[scan_jobnumber]
    where [ManufactureIssueNumber] is not null and operator_fullname is not null '.$filter.'
    group by Code,operator_fullname,scan_operatorcode
    order by operator_fullname asc,total_hours desc

    ';
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $alloperator=$sql->fetchall();
    echo'<div class="row header-summary">';
        echo'<div class="col-sm-8">';
        echo 'Process';
        echo'</div>';
        
        echo'<div class="col-sm-4">';
        echo 'Hours';
        echo'</div>';
            
            
        
    echo'</div>';
    foreach($alloperator as $operator){
        
        echo'<div class="row row_normal ';
        
            if($_POST['Code']==$operator['Code']){echo 'row-selected';}
        echo' " onClick="document.getElementById(\'form-id'.$operator['Code'].'\').submit();">';
            echo'<form id="form-id'.$operator['Code'].'" method="POST">';
            
            echo'<input type="hidden" name="scan_operatorcode" value="'.$operator['scan_operatorcode'].'">';
            echo'<input type="hidden" name="Code" value="'.$operator['Code'].'">';
            echo'<input type="hidden" name="date_to_show_start" value="'.$_POST['date_to_show_start'].'">';
            echo'<input type="hidden" name="date_to_show_end" value="'.$_POST['date_to_show_end'].'">';
            
            echo'<div   class="col-sm-8">';
            
            echo $operator['Code'];
            
            echo'</div>';
            echo'<div class="col-sm-4">';
            
            echo number_format(round($operator['total_hours'],1));
           
            echo'</div>';
            echo'</form>';
        echo'</div>';
        
    }

}

function showListday_matrix($db){
    $filter='  AND scan_operatorcode=\''.$_POST['scan_operatorcode'].'\' AND scan_date>=\''.$_POST['date_to_show_start'].'\' AND scan_date<=\''.$_POST['date_to_show_end'].'\' ';

    if(!empty($_POST['Code'])){
        $filter=$filter.' AND Code=\''.$_POST['Code'].'\' ';

    }
    

    $query='SELECT  scan_date,operator_fullname,scan_operatorcode,sum(total_hours) as total_hours
    FROM [barcode].[dbo].[MIS_Operator_hours_scanned]
    left join [MIS_List] on [ManufactureIssueNumber]=[scan_jobnumber]
    where [ManufactureIssueNumber] is not null and operator_fullname is not null '.$filter.'
    group by scan_date,operator_fullname,scan_operatorcode
    order by scan_date desc,total_hours desc

    ';
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $alloperator=$sql->fetchall();
    echo'<div class="row header-summary">';
        echo'<div class="col-sm-8">';
        echo 'Date';
        echo'</div>';
        
        echo'<div class="col-sm-4">';
        echo 'Hours';
        echo'</div>';
            
            
        
    echo'</div>';
    foreach($alloperator as $operator){
        echo'<form id="form-id'.$operator['scan_date'].'" method="POST">';
            
        echo'<input type="hidden" name="scan_operatorcode" value="'.$operator['scan_operatorcode'].'">';
        echo'<input type="hidden" name="Code" value="'.$_POST['Code'].'">';
        echo'<input type="hidden" name="date_to_show_start" value="'.$_POST['date_to_show_start'].'">';
        echo'<input type="hidden" name="date_to_show_end" value="'.$_POST['date_to_show_end'].'">';
        echo'<input type="hidden" name="scan_date" value="'.$operator['scan_date'].'">';
        echo'<input type="hidden" name="operatorname" value="'.$operator['operator_fullname'].'">';
        echo'</form>';
        echo'<div > ';
        echo'</div>';
        echo'<div class="row row_normal ';
        
            if($_POST['scan_date']==$operator['scan_date']){echo 'row-selected';}
        echo' " onClick="document.getElementById(\'form-id'.$operator['scan_date'].'\').submit();">';
           
            echo'<div   class="col-sm-8">';
            
            echo $operator['scan_date'];
            
            echo'</div>';
            echo'<div class="col-sm-4">';
            
            echo number_format(round($operator['total_hours'],1),1);
           
            echo'</div>';
            
        echo'</div>';
        
    }

}

function get_operatorcode($db,$code){

}

?>