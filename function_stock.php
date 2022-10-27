<?php

function manage_POST_check($db){
    if(empty($_SESSION['temp']['stock']['sort'])){
        $_SESSION['temp']['stock']['sort']=' [Code] asc';
    }
    if(!empty($_POST['sort'])){
        if( $_SESSION['temp']['stock']['sort']==$_POST['sort'].' DESC'){
            $_SESSION['temp']['stock']['sort']=$_POST['sort'].' ASC';
        }else{
            $_SESSION['temp']['stock']['sort']=$_POST['sort'].' DESC';
        }    
       
    }

    if(!empty($_POST['category'])){
        if( $_SESSION['temp']['stock'][$_POST['category']]==$_POST['field']){
            unset($_SESSION['temp']['stock'][$_POST['category']]);
        }else{
            $_SESSION['temp']['stock'][$_POST['category']]=$_POST['field'];
        }
        echo'<br><br><br><br><br><center><img src="img/loading.gif" width="100" height="100"></center>';
        ajax_load([['loadall',1]],'stock-ajax.php','allview','empty().append(html)');
        
    }

    if(!empty($_POST['loadall'])){
        showall($db);
    }
   
}

function navbar_stock($db){
    echo'<div class="row">';
        echo'<div class="col-sm-3 showproductlist">';
            
        echo'</div>';
        
        echo'<div class="col-sm-1">';
            
        echo'</div>';
        echo'<div class="col-sm-4 ">';
          
        echo'</div>';
        echo'<div class="col-sm-2">';
       
        echo'</div>';

    echo'</div>';
}

function general_view_stock($db){
    echo'<div class="postinfo">';
    echo'</div>';
    echo'<div class="allview">';
        showall($db);
    echo'</div>';
    echo'<script>
    function loadfield(thefield,thecategory){
        var request =$.ajax({
            type:\'POST\',
            url:\'stock-ajax.php\',
            data: {
                field: thefield, 
                category: thecategory,
            },
            success:function(html){
                $(\'.allview\').empty().append(html);
            }
        });
    }
    </script>';
}

function showall($db){
    echo'<div class="row">';
        echo'<div class="col-sm-5 " >';
        show_stock($db);
        echo'</div>';
        echo'<div class="col-sm-3 " >';
        show_filter($db,'WorkArea','WorkArea');
        echo'<br>';
        show_filter($db,'[Product Family]','Product Family');
        echo'</div>';
        echo'<div class="col-sm-3 " >';
        show_filter($db,'TheLocation','Location');
        echo'<br>';
        show_filter($db,'SupplierName','Supplier');
        echo'</div>';
    echo'</div>';
}

function show_stock($db){


    $allstock=get_stock($db);
    $allstock2=get_stock_finish_good($db);
    $value = array_sum(array_column($allstock,'Value'));
   
    $value=$value+$allstock2[0];
    $_POST['total']= $value;
    $_POST['total_finish_good']= $allstock2[0];
    $_POST['total_component']= $value-$allstock2[0];
    //show(($value));
    echo'<div class="row header-check">';
        echo'<div class="col-sm-12" >Current Situation</div>';
        echo'<div class="col-sm-4" onclick="sortby(\'Code\');">'.bold_if_sort('Component','Code').'</div>';
        echo'<div class="col-sm-2" onclick="sortby(\'QTY_Used\');">'.bold_if_sort('Usage Last 24 Months','QTY_Used').'</div>';
        
        echo'<div class="col-sm-1" onclick="sortby(\'Quantity\');">'.bold_if_sort('Stock','Quantity').'</div>';
        echo'<div class="col-sm-1" onclick="sortby(\'round(sum([Quantity])/sum(QTY_Used),0)\');">'.bold_if_sort('Stock in Weeks','round(sum([Quantity])/sum(QTY_Used),0)').'</div>';
        echo'<div class="col-sm-2" onclick="sortby(\'Value\');">'.bold_if_sort('Value','Value').'</div>';
        echo'<div class="col-sm-2" onclick="sortby(\'avg(Turnover)\');">'.bold_if_sort('Turnover','avg(Turnover)').'</div>';
        
    echo'</div>';
    echo'<form id="form-sort" method="POST">';
    echo'<input type="hidden" id="sort" name="sort" value="">';
    echo'</form>';
    echo '<script>
    function sortby(thevalue){
        document.getElementById("sort").value = thevalue;
        document.getElementById("form-sort").submit();
        
    }
    </script>';
    foreach($allstock as $component){
        echo'<div class="row row_check">';
           
            echo'<div class="col-sm-4 cell-stock" >'.$component['Code'].'</div>';
            echo'<div class="col-sm-2 cell-stock" >'.number_format($component['QTY_Used']*100).'</div>';
            
            echo'<div class="col-sm-1 cell-stock" >'.number_format($component['Quantity']).'</div>';
            echo'<div class="col-sm-1 cell-stock" >';
            if($component['QTY_Used']==0){echo 'INF';}else{echo number_format($component['Stock_in_Week']);}
            echo'</div>';
            echo'<div class="col-sm-2 cell-stock" >$'.number_format($component['Value']).'</div>';
            echo'<div class="col-sm-2 cell-stock" >$'.number_format($component['Turnover']/1000).'k</div>';
            
        echo'</div>';
    }
}

function show_filter($db,$field,$caption){
    
    echo'<div class="row header-check" >';
        echo'<div class="col-sm-12" >'.$caption.'</div>';
    echo'</div>';
    $allfield=get_field($db,$field);
    foreach($allfield as $eachfield){ 
        
        echo'<div class="row row_check';
        if( $_SESSION['temp']['stock'][$caption]==$eachfield[0]){echo " row-selected ";}
        echo'" onClick="loadfield(\''.$eachfield[0].'\',\''.$caption.'\');">';
           echo '<div class="col-sm-6" >'.$eachfield[0].'</div>';
           echo '<div class="col-sm-4" >$'.number_format($eachfield[1]).'</div>';//$_POST['total']
           echo '<div class="col-sm-2" >'.round(100*$eachfield[1]/$_POST['total_component'],1).'%</div>';//
        echo'</div>';
        

    }
    
}

function bold_if_sort($header,$sort){
    
    if( $_SESSION['temp']['stock']['sort']==$sort.' DESC' or $_SESSION['temp']['stock']['sort']==$sort.' ASC' ){
        return ''.$header.' <span class="glyphicon glyphicon-sort" > </span>';
    }else{
        return $header;
    }

}

function get_field($db,$field){
    $query='SELECT '.$field.', sum([Value]) as Value
    FROM [barcode].[dbo].[Current_Stock]
    left join (
        SELECT  round(sum([USAGE_COMPONENT]),0)/100 as QTY_Used,
            [Code] as theCode,[Name] as SupplierName
        FROM [barcode].[dbo].[Components_used]
    
        where [TransactionDate]>DATEADD(DAY, -730, GETDATE())
        group by Code,[Name]
            
            )as temp
    ON [Current_Stock].Code=temp.theCode
    left join (
        SELECT  [QTY_LAST_YEAR] as QTY_Made,
            [Code] as theCode2
        FROM [barcode].[dbo].[QTY_Made_Last_Year]
    
            )as temp2
    ON [Current_Stock].Code=temp2.theCode2
    left join (
        SELECT distinct [Component] as theCode3,1 as not_final_assembly
     
        FROM [barcode].[dbo].[BOM_Detailled]
    
            )as temp3
    ON [Current_Stock].Code=temp3.theCode3

    where  not_final_assembly is not null '.filter_stock().'
    group by '.$field.'
    order by '.$field.' ASC
     ';
	
    //and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    //show($query);
    $allstock=$sql->fetchall();
    return $allstock;
}

function get_stock($db){
    $query='SELECT  sum([Value]) as Value
    
    ,[Code]
    
    ,avg(QTY_Used) as QTY_Used
    ,avg([QTY_Made]) as QTY_Made
    ,sum([Quantity]) as Quantity
    ,round(sum([Quantity])/avg(QTY_Used),0) as Stock_in_Week
    ,avg(Turnover) as Turnover
    FROM [barcode].[dbo].[Current_Stock]
    left join (
        SELECT  round(sum([USAGE_COMPONENT]),0)/100 as QTY_Used,
            [Code] as theCode,[Name] as SupplierName,
            round(sum([USAGE_COMPONENT]*[LastCost]),0) as Turnover
        FROM [barcode].[dbo].[Components_used]
    
        where [TransactionDate]>DATEADD(DAY, -730, GETDATE())
        group by Code,[Name]
            
            )as temp
    ON [Current_Stock].Code=temp.theCode
    left join (
        SELECT  [QTY_LAST_YEAR] as QTY_Made,
            [Code] as theCode2
        FROM [barcode].[dbo].[QTY_Made_Last_Year]
    
            )as temp2
    ON [Current_Stock].Code=temp2.theCode2
    left join (
        SELECT distinct [Component] as theCode3,1 as not_final_assembly
     
        FROM [barcode].[dbo].[BOM_Detailled]
    
            )as temp3
    ON [Current_Stock].Code=temp3.theCode3

    where   not_final_assembly is not null '.filter_stock().' 
    group by Code
     ORDER BY '.$_SESSION['temp']['stock']['sort'];
	
    //and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    //show($query);
    $allstock=$sql->fetchall();
    return $allstock;
}

function get_stock_finish_good($db){
    $query='SELECT  sum([Value]) as Value
    
    FROM [barcode].[dbo].[Current_Stock]
    left join (
        SELECT  round(sum([USAGE_COMPONENT]),0)/100 as QTY_Used,
            [Code] as theCode
        FROM [barcode].[dbo].[Components_used]
    
        where [TransactionDate]>DATEADD(DAY, -730, GETDATE())
        group by Code
            ,[SEGMENTATION]
        
            ,[ProductGroupId]
            )as temp
    ON [Current_Stock].Code=temp.theCode
    left join (
        SELECT  [QTY_LAST_YEAR] as QTY_Made,
            [Code] as theCode2
        FROM [barcode].[dbo].[QTY_Made_Last_Year]
    
            )as temp2
    ON [Current_Stock].Code=temp2.theCode2
    left join (
        SELECT distinct [Component] as theCode3,1 as not_final_assembly
     
        FROM [barcode].[dbo].[BOM_Detailled]
    
            )as temp3
    ON [Current_Stock].Code=temp3.theCode3

    where   not_final_assembly is null '.filter_stock().'
     ORDER BY '.$_SESSION['temp']['stock']['sort'];
	
    //and WorkArea=\'Push-On Bolt\' theLocation=\'QLD\' and
    $sql = $db->prepare($query); 
    $sql->execute();
    //show($query);
    $allstock=$sql->fetch();
    return $allstock;
}

function filter_stock($exception=''){
    $filter='';
    $fields[]=['WorkArea','WorkArea'];
    $fields[]=['Product Family','Product Family'];
    $fields[]=['Location','TheLocation'];
    $fields[]=['Supplier','SupplierName'];

    foreach($fields as $field){
        if( !empty($_SESSION['temp']['stock'][$field[0]])){
            $filter=$filter." and [".$field[1]."]='".$_SESSION['temp']['stock'][$field[0]]."'";
        }
    }
    
    //show($filter);
    return $filter;
}



?>