<?php

function manage_POST_kit($db){
    show_debug();
  //show($_POST);
    if(!empty($_POST['view'])){
        $_SESSION['temp']['check_view']=$_POST['view'];
    }elseif(!empty($_SESSION['temp']['check_view'])){
        $_POST['view']=$_SESSION['temp']['check_view'];
    }


    if($_POST['action']=='show_all_order'){
        show_all_order($db);
    }


  // show('Session :'.$_SESSION['temp']['check_view']);
  if(!empty($_POST['showproductlist'])){
    showproductlist($db);
   }
   if(!empty($_POST['show_list_kit'])){
    show_list_kit($db,$_POST['code']);
   }
   if(!empty($_POST['show_general_action'])){
    show_general_action($db,$_POST['code']);
    update_div('details-item','dont','');
   }
   if(!empty($_POST['loadthat'])){
    $_POST['show_details_item']='ok';
    $_POST['from']='ok';
    $_POST['Quantity']=1;
    $_POST['code']=$_POST['Scanner'];
    $_POST['namefunction']=reformat($_POST['code']);
    $_POST['parent']='ok';
    
   }
   if(!empty($_POST['show_details_item'])){
    show_details_item($db,$_POST['code']);
    update_div('general-action','dont','');
   }
   if(!empty($_POST['Add_box'])){
    echo'<script>
    document.getElementById("list'.$_POST['namefunction'].'").remove();
    </script>';
    update_div('details-item','dont','');
    add_to_box($db,$_POST['code']);
    
   }
   if(!empty($_POST['Remove_box'])){
    echo'<script>
    document.getElementById("box'.$_POST['namefunction'].'").remove(); 
    </script>';
    update_div('details-item','dont','');//general-action
    remove_to_box($db,$_POST['code']);
    
   }

   if(!empty($_POST['MO'])){
      
    load_MO($db,$_POST['MO']);
   }else{
    if(empty($_POST['code'])){
        $_POST['code']='CRJ-TMB16300-01';
    }
      
   }
   
   
   
   
   
}

function showproductlist($db){
    
    echo'<div class="col-sm-4">Product:</div>';$time[] = microtime(true);
        echo'<div class="col-sm-6 ">';
        echo'<form method="POST">';
            
            echo '<input type="text" list="thelist" name="code" class="form-control" placeholder="Product" onchange="submit();" id="list_product"">
            <datalist id="thelist">';
            foreach (get_all_product_check($db) as &$item){
                echo"<option >".$item[0]."</option>";
            }
            echo '</datalist>';
            echo '<input type="hidden"  name="view" value="'.$_POST['view'].'">';
            echo '<input type="text" class="form-control" name="Quantity"  placeholder="QTY">';
        echo'</form>';
        
        echo'</div>';
    
    
}


function navbar_kit_old($db,$thecode){?>
    <div class="row">
        <div class="col-sm-3">
            <div class="col-sm-4">Order:</div>
            <div class="col-sm-6 ">
            <form method="POST">
            <input type="text" list="thelist2" name="MO" class="form-control" onchange="submit();" id="list_product"">
            <datalist id="thelist2">';
            <?php foreach (get_all_order($db) as &$item){?>
                <option value="<?php echo $item['order']?>" ><?php echo $item['name']?></option>
                <?php 
            }?>
            </datalist>
            </form>
            </div>
           
        </div>
        
        <div class="col-sm-4 header-kitting">
            <?php if(!empty($_POST['MO'])){
                echo $_POST['MO'];
            }?>
            
        </div>
        <div class="col-sm-3 title-BOM">
           
        </div>
        <div class="col-sm-2">
        
        </div>

    </div>
    <?php
}
function navbar_kit($db,$thecode){
    $col='col-xs-3 col-sm-3 col-md-2 col-lg-2';?>
    <div class="row">
        <div class="<?php echo $col?>">
            <span class="btn btn-default" onclick="load('show_all_order')">View All Orders</span>
        </div>
        <div class="<?php echo $col?>">
           
        </div>
    </div>
    <script>
        function load(action){
            var request =$.ajax({
            type:'POST',
            url:'kit-ajax.php',
            data: {
              action: action
            },
            success:function(html){
                $('.main_page').empty().append(html);
            }
        });
        }
    </script>
    <?php
}

function general_view_kit($db){?>
    <div class="main_page">
        
    </div>


    <?php
}

function show_all_order($db){
    show('test');
    foreach(get_all_order($db) as $order){
        show($order);
    }
}

function get_all_order($db){
    $item[0]['order']='MO12345';
    $item[0]['code']='CRJ-TMB16300-01';
    $item[0]['Quantity']='5';
    $item[0]['name']='CRJ-TMB16300-01 x 5';

    $item[1]['order']='MO11111';
    $item[1]['code']='CRJ-LV-X4-2595-V';
    $item[1]['Quantity']='3';
    $item[1]['name']='CRJ-LV-X4-2595-V x 3';

    $item[2]['order']='MO23232';
    $item[2]['code']='XBJ-11-X1-400-IN1';
    $item[2]['Quantity']='1';
    $item[2]['name']='XBJ-11-X1-400-IN1 x 1';

    return $item;
}

function load_MO($db,$MO){
    $item[0]['order']='MO12345';
    $item[0]['code']='CRJ-TMB16300-01';
    $item[0]['Quantity']='5';
    $item[0]['name']='CRJ-TMB16300-01 x 5';

    $item[1]['order']='MO11111';
    $item[1]['code']='CRJ-LV-X4-2595-V';
    $item[1]['Quantity']='3';
    $item[1]['name']='CRJ-LV-X4-2595-V x 3';

    $item[2]['order']='MO23232';
    $item[2]['code']='XBJ-11-X1-400-IN1';
    $item[2]['Quantity']='1';
    $item[2]['name']='XBJ-11-X1-400-IN1 x 1';
    if($MO==$item[0]['order']){$toload= $item[0];}
    if($MO==$item[1]['order']){$toload= $item[1];}
    if($MO==$item[2]['order']){$toload= $item[2];}
    $_POST['code']=$toload['code'];
    $_POST['Quantity']=$toload['Quantity'];
    
}

function old_navbar_kit($db,$thecode){
    echo'<div class="row">';
        echo'<div class="col-sm-3 showproductlist">';
            echo'<div class="col-sm-4">Product:</div>';$time[] = microtime(true);
            echo'<div class="col-sm-6 ">';
            echo'<form method="POST">';
            echo '<input type="text" disabled list="thelist" name="code" class="form-control" onchange="submit();" id="list_product"">';
            echo '<input type="hidden"  name="view" value="'.$_POST['view'].'">';
           
            echo'</form>';
        
            echo'</div>';
        echo'</div>';
        
        echo'<div class="col-sm-1">';
            // echo'<form method="POST">';
            // echo'<button type="submit" name="show_dashboard" value="show_dashboard" class="btn btn-default" >
            //                     <span class="glyphicon glyphicon-refresh" ></span>
            // </button><br>&nbsp';
            // echo '<input type="hidden"  name="view" value="'.$_POST['view'].'">';
            // echo '<input type="hidden"  name="code" value="'.$_POST['code'].'">';
            // echo'</form>';
        echo'</div>';
        echo'<div class="col-sm-4 title-BOM">';
            echo $thecode;
        echo'</div>';
        echo'<div class="col-sm-2">';
        echo'<form method="POST">';
        
        echo '<input type="hidden"  name="code" value="'.$thecode.'">';
        echo'</form>';
        echo'</div>';

    echo'</div>';

}

function get_component($db,$Code){
    $query='SELECT sum([LastCost]*[Quantity]) as Cost_Total
    ,[Component]   
    ,sum([Quantity])as[Quantity] 
    ,avg([LastCost])as[LastCost]
    from BOM_Detailled
    where  Parent=\''.$Code.'\' and ([LastCost]*[Quantity])>0 and hasSub=0
    group by [Component]
    order by sum([LastCost]*[Quantity]) desc
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $ListComponent=$sql->fetchall();
    return $ListComponent;
}

function get_details_component($db,$Code){
    $query='SELECT ([LastCost]*[Quantity]) as Cost_Total
    ,[Component],[Code]  
    ,([Quantity])as[Quantity] 
    ,([LastCost])as[LastCost]
    from BOM_Detailled
    where  Parent=\''.$Code.'\' and ([LastCost]*[Quantity])>0 and hasSub=0
   
    order by ([LastCost]*[Quantity]) desc
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $ListComponent=$sql->fetchall();
    return $ListComponent;
}


function get_component_complete($db,$Code,$location,$option=''){
    $query='SELECT
    [Component],
    [Code]
    ,([Quantity])as[Quantity] 
    ,([LastCost])as[LastCost],
    Stock_LEVEL,
    [TreeLevel],HasSub
    from BOM_Detailled
    LEFT JOIN( SELECT [Quantity] as Stock_LEVEL,[Code]as Product_code FROM Current_Stock WHERE TheLocation=\''.$location.'\'
        )as temp on temp.Product_code=BOM_Detailled.Component
    where  Parent=\''.$Code.'\' and ([LastCost]*[Quantity])>0 and (
        Component not LIKE \'%LABOUR%\' and Component not LIKE \'%STAMPING%\'
    ) '.$option.'
    
    order by TreeLevel asc
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
   // show('Query :<br> '.$query);
    $sql->execute();
    $ListComponent=$sql->fetchall();
    return $ListComponent;
}

function get_component_stock($db,$Code,$location,$option=''){
    $query='SELECT
    [Component],
    
    sum([Quantity])as[Quantity] 
    ,
    avg(Stock_LEVEL) as Stock_LEVEL,
    HasSub
    from BOM_Detailled
    LEFT JOIN( SELECT [Quantity] as Stock_LEVEL,[Code]as Product_code FROM Current_Stock WHERE TheLocation=\''.$location.'\'
        )as temp on temp.Product_code=BOM_Detailled.Component
    where  Parent=\''.$Code.'\' and ([LastCost]*[Quantity])>0 and (
        Component not LIKE \'%LABOUR%\' and Component not LIKE \'%STAMPING%\'
    ) and (Component not like \'%MACHINING%\' and Component not like \'%LABEL%\' and Component not like \'%GREASE%\' and Component not like \'%ZSTAMPING%\') '.$option.'
    
    group by [Component],HasSub
    order by HasSub,avg(Stock_LEVEL)/sum([Quantity]) desc
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    //show('Query :<br> '.$query);
    $sql->execute();
    $ListComponent=$sql->fetchall();
    return $ListComponent;
}

function get_component_stock2($db,$Code,$location,$option=''){
    $query='SELECT
    [Component],
    
    sum([Quantity])as[Quantity] 
    ,
    avg(Stock_LEVEL) as Stock_LEVEL,
    HasSub
    from BOM_Detailled
    LEFT JOIN( SELECT [Quantity] as Stock_LEVEL,[Code]as Product_code FROM Current_Stock WHERE TheLocation=\''.$location.'\'
        )as temp on temp.Product_code=BOM_Detailled.Component
    where  Parent=\''.$Code.'\' and ([LastCost]*[Quantity])>0 and (
        Component not LIKE \'%LABOUR%\' and Component not LIKE \'%STAMPING%\'
    ) and (Component not like \'%MACHINING%\' and Component not like \'%LABEL%\' and Component not like \'%GREASE%\' and Component not like \'%ZSTAMPING%\') '.$option.'
    
    group by [Component],HasSub
    order by HasSub,avg(Stock_LEVEL)/sum([Quantity]) desc
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    //show('Query :<br> '.$query);
    $sql->execute();
    $ListComponent=$sql->fetchall();
    return $ListComponent;
}

function showlist($db,$thecode){
    $ListComponent=get_component($db,$thecode);
    //show( $ListComponent);
    echo'<div class="col-sm-7" >';
        echo'<div class="row header-check">';
            echo'<div class="col-sm-12" style="text-align: center;">BOM - '.$thecode.'</div>';
            echo'<div class="col-sm-7" style="text-align: center;">Component</div>';
            echo'<div class="col-sm-2" style="text-align: center;">Quantity</div>';
            echo'<div class="col-sm-3" style="text-align: center;">';
            if($_SESSION['temp']['id']=='CorentinHillion'){echo'Cost';}
            //
            echo'</div>';
        // echo'<div class="col-sm-2" style="text-align: center;">%</div>';

        echo'</div>';
        $cost=0;
        $costLabour=0;
        

        foreach($ListComponent as $component){

            $classname=str_replace(' ', '', str_replace('.', '',$component['Component']));
            $classname=str_replace('/', '', str_replace('.', '',$classname));
            echo'<div id="parent-'.$component['Component'].'" class="row row_check" onmouseover="show_detail(this,\''.$classname.'\');" onmouseout="dont_show_detail(this,\''.$classname.'\');">';
                echo'<div class="col-sm-7" style="text-align: center;">';
                echo $component['Component'];
                echo'</div>';
                echo'<div class="col-sm-2" style="text-align: center;">';
                echo round($component['Quantity'],2);
                echo'</div>';
                echo'<div class="col-sm-3" style="text-align: center;">';
                //
                if($_SESSION['temp']['id']=='CorentinHillion'){echo 'AUD$'.round($component['LastCost']*$component['Quantity'],2);}
                echo'</div>';
                $cost=$cost+$component['LastCost']*$component['Quantity'];
                if($component['Component']=='ZLABOUR-ASSEMBLY' or $component['Component']=='ZLABOUR-MANUF.' or $component['Component']=='ZSTAMPING'){$costLabour=$costLabour+$component['LastCost']*$component['Quantity'];}
            echo'</div>';

        }
        $costPurchase=$cost-$costLabour;
        echo'<div id="row_check" class="row row-total-check">';
            
            echo'<div class="col-sm-7" style="text-align: center;">Total</div>';
            echo'<div class="col-sm-2" style="text-align: center;"></div>';
            echo'<div class="col-sm-3" style="text-align: center;">';
            if($_SESSION['temp']['id']=='CorentinHillion'){echo 'AUD$'.round($cost,2);}
            echo'</div>';

        echo'</div>';
        echo'<div class="row row_check">';
            
            echo'<div class="col-sm-7" style="text-align: center;">Labour</div>';
            echo'<div class="col-sm-2" style="text-align: center;">'.round($costLabour/$cost*100,1).'%</div>';
            echo'<div class="col-sm-3" style="text-align: center;">';
            if($_SESSION['temp']['id']=='CorentinHillion'){echo 'AUD$'.round($costLabour,2);}
            echo'</div>';
        

        echo'</div>';
        echo'<div class="row row_check">';
            
            echo'<div class="col-sm-7" style="text-align: center;">Purchase Good</div>';
            echo'<div class="col-sm-2" style="text-align: center;">'.round($costPurchase/$cost*100,1).'%</div>';
            echo'<div class="col-sm-3" style="text-align: center;">';
            if($_SESSION['temp']['id']=='CorentinHillion'){echo 'AUD$'.round($costPurchase,2);}
            echo'</div>';
            
        echo'</div>';
    echo'</div>';
    echo'<div class="col-sm-5" style="min-height:200px;">';
        echo'<div class="col-sm-12 " style="min-height:200px;">';
            echo'<div id="details-table" class="row header-check ">';
                echo'<div class="col-sm-12" style="text-align: center;">Where Used?</div>';
                echo'<div class="col-sm-6" style="text-align: center;">Process</div>';
                echo'<div class="col-sm-2" style="text-align: center;">Qty</div>';
                echo'<div class="col-sm-4" style="text-align: center;">';
                //echo'$';
                echo'</div>';
            // echo'<div class="col-sm-2" style="text-align: center;">%</div>';

            echo'</div>';
            $ListComponent=get_details_component($db,$thecode);
            foreach($ListComponent as $component){
                $classname=str_replace(' ', '', str_replace('.', '',$component['Component']));
                $classname=str_replace('/', '', str_replace('.', '',$classname));
                echo'<div  class="row  details-row '.$classname.'" >';
                    echo'<div class="col-sm-6" style="text-align: center;">';
                    echo $component['Code'];
                    echo'</div>';
                    echo'<div class="col-sm-2" style="text-align: center;">';
                    echo round($component['Quantity'],2);
                    echo'</div>';
                    echo'<div class="col-sm-4" style="text-align: center;">';
                    if($_SESSION['temp']['id']=='CorentinHillion'){echo 'AUD$'.round($component['LastCost']*$component['Quantity'],2);}
                    echo'</div>';
                    $cost=$cost+$component['LastCost']*$component['Quantity'];
                    if($component['Component']=='ZLABOUR-ASSEMBLY' or $component['Component']=='ZLABOUR-MANUF.' or $component['Component']=='ZSTAMPING'){$costLabour=$costLabour+$component['LastCost']*$component['Quantity'];}
                echo'</div>';

            }
            echo'<script>
            document.getElementById(\'details-table\').style.display = \'none\';
            function show_detail(Component,Class){
                document.getElementById(\'details-table\').style.display = \'block\';
                
                $("."+Class).css(\'display\', \'block\');
            
            }
            function dont_show_detail(Component,Class){
                document.getElementById(\'details-table\').style.display = \'none\';
            
                $("."+Class).css(\'display\', \'none\');
                
            }


            </script>';
        echo'</div>';
        echo'<div class="col-sm-12 showStats" style="min-height:200px;">';

        echo'</div>';
    echo'</div>';
    
}

function get_all_product_check($db){
    $query='SELECT Parent as Product_Code
    from BOM_Detailled
	where hassub=1 and TreeLevel=1
    group by Parent
    ';

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();
    //
    return $row;
}

function get_component_org($db,$Code){
    $query='SELECT *
    from BOM_Detailled
    where  Parent=\''.$Code.'\' and (Component not like \'%MACHINING%\' and Component not like \'%ZLABOUR%\' and Component not like \'%LABEL%\' and Component not like \'%GREASE%\' and Component not like \'%ZSTAMPING%\')
    order by TreeLevel ASC,SortOrder ASC
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $ListComponent=$sql->fetchall();
    //show($query);
    return $ListComponent;
}

function count_component_org($db,$Code){
    $query='SELECT count(Component) as count_component
    from BOM_Detailled
    where  TreeLevel=1 and Parent=\''.$Code.'\' and (Component not like \'%MACHINING%\' and Component not like \'%LABEL%\' and Component not like \'%GREASE%\' and Component not like \'%ZLABOUR%\' and Component not like \'%ZSTAMPING%\')
    
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $ListComponent=$sql->fetch();
    //show($query);
    return $ListComponent[0];
}

function get_component_org2($db,$Code){
    $query='SELECT HasSub,Component,Code,Quantity
    from BOM_Detailled
    where  Parent=\''.$Code.'\' and (Component not like \'%MACHINING%\' and Component not like \'%ZLABOUR%\' and Component not like \'%LABEL%\' and Component not like \'%GREASE%\' and Component not like \'%ZSTAMPING%\')
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $ListComponent=$sql->fetchall();
    //show($query);
    $query='SELECT
    
	avg(Stock_LEVEL) as Stock_LEVEL,
	round(avg(Stock_LEVEL)/sum(Quantity),0) as Equivalent,
	Component 
    from BOM_Detailled
	LEFT JOIN( 
    SELECT [Quantity] as Stock_LEVEL,
    [Code]as Product_code
        
    FROM Current_Stock 
        WHERE TheLocation=\'QLD\'
    )as temp on temp.Product_code=BOM_Detailled.Component
    where  Parent=\''.$Code.'\' and ([LastCost]*[Quantity])>0 
    group by Component
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $ListComponent2=$sql->fetchall();
    $i=0;
    foreach($ListComponent as $component){
        //find key
        $key=array_search($component['Component'],array_column($ListComponent2, 'Component'));
        //show($key.' :'. $component['Component'].' '.round($ListComponent2[$key]['Equivalent'],0));
        //add the equivalent to component

        $ListComponent[$i]['Equivalent']=round($ListComponent2[$key]['Equivalent'],3);

        $i++;
    }
    //show($ListComponent);
    
    return $ListComponent;
} 

function get_component_org3($db,$Code){
    $NewList=get_component_org2($db,$Code);
    //show($NewList);
    foreach($NewList as $component){
        $NewOrg[$component['Code']][$component['Component']]['Component']=$component['Component'];
        $NewOrg[$component['Code']][$component['Component']]['Quantity']=round($component['Quantity'],3);
        $NewOrg[$component['Code']][$component['Component']]['HasSub']=$component['HasSub'];
    }
    return $NewOrg ;
} 
  


function show_org($db,$thecode){
    echo'<div id="chart_div_org"></div>
    </div>';
    if(empty($_POST)){
        $thecode='PHM4-6-4/0 B';
        $finalscan[]=$thecode;
        $i=0;
        $data=array();
        $NewList=get_component_org($db,$thecode);
        $data['org']=$data['org']."[{'v':'".$thecode."', 'f':'<b>".$thecode."</b>'},'',''],";
        
        $tempdata=prepare_BOM_graph($NewList);
        $data['org']=$data['org'].$tempdata['org'];
        
      }else{
       // show($_POST);
        $thecode=$_POST['code'];
       //$data=$_POST['data'];
        $NewList=get_component_org($db,$thecode);
        $data=array();
        $data['org']=$data['org']."['".$thecode."','',''],";
        $tempdata=prepare_BOM_graph($NewList);
        $data['org']=$data['org'].$tempdata['org'];
    }
    
    echo'<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load(\'current\', {packages:["orgchart"]});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn(\'string\', \'Name\');
        data.addColumn(\'string\', \'Qty\');
        data.addColumn(\'string\', \'ToolTip\');

        // For each orgchart box, provide the name, manager, and tooltip to show.
        data.addRows([';
        echo $data['org'];
        echo']);


       
        // Create the chart.
        var chart = new google.visualization.OrgChart(document.getElementById(\'chart_div_org\'));
        // Draw the chart, setting the allowHtml option to true for the tooltips.
        chart.draw(data, {\'allowHtml\':true});
        google.visualization.events.addListener(chart, \'select\', toggleDisplay);
       //google.visualization.events.addListener(table, \'select\', selectHandler);
        
        function toggleDisplay() {
            var selection = chart.getSelection();
            var row;
            if (selection.length == 0) {
                row = previous;
            }
            else {
                row = selection[0].row;
                previous = row;
            }
            var collapsed = chart.getCollapsedNodes();
            var collapse = (collapsed.indexOf(row) == -1);
            chart.collapse(row, collapse);
            chart.setSelection([{row: row, column: null}]);
        }
        
    }
    </script>';
    
} 

function reformat($word){
    $namefunction=str_replace(' ', '', str_replace('.', '',$word));
    $namefunction=str_replace('-', '', str_replace('/', '',$namefunction));
    $namefunction=str_replace('+', '', str_replace('\\', '',$namefunction));
    return $namefunction;
}
function show_component($component,$parent,$list,$from='list'){
    $namefunction=reformat($component);

    
   if(empty($_POST['Quantity'])){$_POST['Quantity']=1;}
    
    echo'<div class="col-sm-6 list-component '.$namefunction.'" id="'.$from.$namefunction.'" style="" onClick="'.$namefunction.'();"> ';
        echo'<div class="row header-component"> ';
        echo $list[$parent][$component]['Component'];
        echo'</div>';
        echo'<div class="row"> ';
        echo 'Qty: '.($list[$parent][$component]['Quantity']*$_POST['Quantity']);
        echo'</div>';
    echo'</div>';
    ajax_button($namefunction,[['show_details_item',"'ok'"],['from',"'".$from."'"],['Quantity',"'".($list[$parent][$component]['Quantity']*$_POST['Quantity'])."'"],['namefunction',"'".$namefunction."'"],['code',"'".$list[$parent][$component]['Component']."'"],['parent',"'".$parent."'"]],'kit-ajax.php','details-item');
}
function show_general_action(){
    echo'<div class="row line-kitting" ><button class="btn btn-default" >Print Picking List</button></div>';
    echo'<div class="row line-kitting" ><button class="btn btn-default" >Start Kitting</button></div>';
    echo'<div class="row line-kitting" ><input type="text" name="Scanner" id="Scanner" Placeholder="Scan Barcode" class="form-control input-kitting"></div>';
    echo'<div class="row line-kitting" ><button class="btn btn-default" >Print Installation Instruction</button></div>';
    echo'<div class="row line-kitting" ><button class="btn btn-default" >Print Label</button></div>';



    echo'<script>
        document.getElementById("Scanner")
        .addEventListener("keyup", function(event) {
        event.preventDefault();
        if (event.keyCode === 13) {
            scan=document.getElementById("Scanner").value;
            loadthat()
            
        }
    });
    </script>';
    
    ajax_button('loadthat',[['loadthat',"'ok'"],['Scanner',"scan"],['parent','"'.$_POST['code'].'"']],'kit-ajax.php','details-item');
    echo'<script>document.getElementById("Scanner").focus();</script>';
}
function general_action_kitting($db){
    echo'<div class="row ">';
        echo'<div class="row header-kitting" onClick="show_general_action();">'.$_POST['code'].' x '.$_POST['Quantity'].'</div>';
        ajax_button('show_general_action',[['show_general_action',"'ok'"],['code',"'".$_POST['code']."'"]],'kit-ajax.php','general-action');
    echo'</div>';
    echo'<div class="row general-action" id="general-action">';
        show_general_action();
    echo'</div>';
    echo'<div class="row details-item" id="details-item">';
       
    echo'</div>';
}

function show_list_kit($db,$thecode){
    echo'<div class="col-sm-4 initial-list" >';
        echo'<div class="row ">';
            echo'<div class="row header-kitting">Component List</div>';
            $thecode=$_POST['code'];
            //$data=$_POST['data'];
            $NewOrg=get_component_org3($db,$thecode);
            
            echo'';
            echo'<div class="row">';
            foreach($NewOrg[$thecode] as $component){
                show_component($component['Component'],$thecode,$NewOrg);
            }
            
            echo'</div>';

        echo'</div>';
        
    echo'</div>';
    echo'<div class="col-sm-4 middle-part" >';
       general_action_kitting($db,$thecode);
    echo'</div>';
    echo'<div class="col-sm-4 box-list" >';
        echo'<div class="row header-kitting">Box</div>';
        //echo'<div class="row line-kitting">Kit-Number : 20211011-01</div>';

    echo'</div>';
    
}

function show_picture($db,$thecode){
    $query='SELECT TOP (1000) [Picture]
    ,[Code]
    ,[Description]
    FROM [barcode].[dbo].[Picture_List]
    Where Code=\''.$thecode.'\' 
    
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $picture=$sql->fetch();
    if(empty($picture)){
        echo'<img src="img/no-image.svg.png" width="30%;" >';
    }else{
        echo '<img src="data:image/jpeg;base64,'.base64_encode( $picture['Picture'] ).'"/ style="max-height:200px;max-width:100%;">';
    }
}

function show_details_item($db,$thecode){
    //show($_POST);
    $data[]=['namefunction',"'".$_POST['namefunction']."'"];
    $data[]=['code',"'".$_POST['code']."'"];
    $data[]=['parent',"'".$_POST['parent']."'"];
    $data[]=['Quantity',"'".$_POST['Quantity']."'"];
   
    echo'<div class="row picture-kitting" >';
        show_picture($db,$thecode);
    echo'</div>';
    echo'<div class="row header-kitting" >'.$_POST['code'].' x '.$_POST['Quantity'].'</div>';
    echo'<div class="row weight-kitting input-kitting" >
    <div class="col-sm-12" ><input type="text" name="Weight" Placeholder="Weight" class="form-control input-kitting" readonly> </div>
    <div class="col-sm-2 " ></div>
    </div>';//<img src="img/refresh.png" style="max-height:40px;" >
    
    echo'<div class="row batch-kitting input-kitting" >
    <div class="col-sm-12" ><input type="text" name="BatchNumber" Placeholder="Batch Number" class="form-control input-kitting" ></div>
    </div>';


    if($_POST['from']=='list'){
        echo'<div class="row" onClick="Add_box();" ><img src="img/add-box.png" width="25%;" ></div>';
        $data[]=['Add_box',"'ok'"];
        ajax_button('Add_box', $data,'kit-ajax.php','box-list','append(html)');

    }else{
        echo'<div class="row" onClick="Remove_box();"><img src="img/remove-box.png" width="25%;" ></div>';
        $data[]=['Remove_box',"'ok'"];
        ajax_button('Remove_box', $data,'kit-ajax.php','initial-list','append(html)');
    }
    
   


}

function add_to_box($db,$thecode){
    //show($_POST);
   
    $NewOrg=get_component_org3($db,$_POST['parent']);
    show_component($_POST['code'],$_POST['parent'],$NewOrg,'box');
    
   


}

function remove_to_box($db,$thecode){
    //show($_POST);
    
   
    $NewOrg=get_component_org3($db,$_POST['parent']);
    show_component($_POST['code'],$_POST['parent'],$NewOrg,'list');
    
    
   


}

function findparent($db,$parent){
    $query='SELECT Code,Component,Quantity 
    from BOM_Detailled
	where Parent=\''.$parent.'\'
    
    ';

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();

    foreach($row as $line){
        $return[$line['Component']][]=$line['Code'];
    }
    //
    return $return;
}


?>