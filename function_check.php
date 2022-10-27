<?php
load_role($db,$_SESSION['temp']['id']);

function manage_POST_check($db){
    if(!empty($_GET['debug'])){
        $_SESSION['temp']['debug']=$_GET['debug'];
    }
    
    if($_SESSION['temp']['debug']=='1'){
        show($_POST);
    }
 // show('POST :'.$_POST['view']);
    if(!empty($_POST['view'])){
    $_SESSION['temp']['check_view']=$_POST['view'];
   }elseif(!empty($_SESSION['temp']['check_view'])){
    $_POST['view']=$_SESSION['temp']['check_view'];
   }
  // show('Session :'.$_SESSION['temp']['check_view']);
    if(!empty($_POST['showproductlist'])){
    showproductlist($db);
   }
   if(!empty($_POST['showBOM'])){
    showBOM($db,$_POST['code']);
   }
   if(!empty($_POST['showpiechart'])){
    showpiechart($db,$_POST['code']);
   }
   if(!empty($_POST['show_org'])){
    show_org($db,$_POST['code']);
   }
   if(!empty($_POST['show_org2'])){
    show_org2($db,$_POST['code']);
   }
   if(!empty($_POST['showstockanalysis'])){
    showstockanalysis($db,$_POST['code']);
   }
   if(!empty($_POST['showStats'])){
    showStats($db,$_POST['code']);
    //if($_SESSION['temp']['id']=='CorentinHillion'){
        show_where_used($db,$_POST['code']);
    //}
   
   }
   if(!empty($_POST['showManufacturable'])){
        $need=12000;
        $return=calculate_max_prod($db,$_POST['code'],$need);
        //show($return);
        echo'<div class="row row_check  "></div>';

        echo'<div class="row row_check  " onmouseover="show_detail2(this,\'details-manufacturable\');" onmouseout="dont_show_detail2(this,\'details-manufacturable\');" >';
            echo'<div class="col-sm-8" style="text-align: center;">Manufacturable now</div>';
            echo'<div class="col-sm-4 showManufacturable" style="text-align: center;">'.number_format($return['max_manufacturable']).'</div>';
        echo'</div>';
        //echo'<div class="details-manufacturable">';
            echo'<div id="details-manufacturable" class="row  header-check " style="display:none ;">';
                echo'<div class="col-sm-12 details-manufacturable" style="text-align: center;">Details Manufacturable</div>';
                echo'<div class="col-sm-8 details-manufacturable" style="text-align: center;">Component</div>';
                echo'<div class="col-sm-4 details-manufacturable" style="text-align: center;">Max</div>';
            echo'</div>';
            //show($return['problem']);
            foreach($return['problem'] as $problem){
                echo'<div class="row row_check  " >';
                    echo'<div class="col-sm-8 details-manufacturable" style="text-align: center;display:none ;">'.$problem['Component'].'</div>';
                    echo'<div class="col-sm-4 details-manufacturable" style="text-align: center;display:none ;">'.number_format($need-$problem['Equivalent']).'</div>';
                echo'</div>';
            }
        //echo'</div>';
        echo'<script>
        document.getElementById(\'details-manufacturable\').style.display = \'none\';
        $(".details-manufacturable").css(\'display\', \'none\');
        




        </script>';
   
    
   
   }
   
   
   
   
}

function showproductlist($db){
    
    echo'<div class="col-sm-4">Product:</div>';$time[] = microtime(true);
        echo'<div class="col-sm-6 ">';
        echo'<form method="POST">';
            
            echo '<input type="text" list="thelist" name="code" class="form-control" onchange="submit();" id="list_product"">
            <datalist id="thelist">';
            foreach (get_all_product_check($db) as &$item){
                echo"<option >".$item[0]."</option>";
            }
            echo '</datalist>';
            echo '<input type="hidden"  name="view" value="'.$_POST['view'].'">';
        echo'</form>';
        
        echo'</div>';
    
    
}

function get_stats($db,$thecode){
    $query='SELECT [QTY_LAST_YEAR]
    from QTY_Made_Last_Year
    where  Code=\''.$thecode.'\' 
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $result=$sql->fetch();
    $stats['qty_last_year']=$result['QTY_LAST_YEAR'];
    return $stats;
}

function showStats($db,$thecode){
    
   $stats=get_stats($db,$thecode);
    echo'<div class="col-sm-12" >';
        echo'<div class="row header-check">';
            echo'<div class="col-sm-12" style="text-align: center;">Stats - '.$thecode.'</div>';
            
        echo'</div>';
        echo'<div class="row row_check" >';
            echo'<div class="col-sm-8" style="text-align: center;">Made last year</div>';
            echo'<div class="col-sm-4" style="text-align: center;">'.number_format($stats['qty_last_year']).'</div>';
        echo'</div>';
        echo'<div class="row row_check" >';
            echo'<div class="col-sm-8" style="text-align: center;">Average per month</div>';
            echo'<div class="col-sm-4" style="text-align: center;">'.number_format(round($stats['qty_last_year']/12,0)).'</div>';
        echo'</div>';
       ;
        echo'<div class=" showManufacturable" >';
        echo'<div class="row row_check  "></div>';
            echo'<div class="row row_check  ">';
                echo'<div class="col-sm-8" style="text-align: center;">Manufacturable now</div>';
                echo'<div class="col-sm-4 showManufacturable" style="text-align: center;"><img src="img/loading_bar.gif" width="25" height="25"></div>';
            echo'</div>';
        echo'</div>';
       
        

       
    echo'</div>';
    ajax_load([['xxxxxshowManufacturable',"'ok'"],['code',"'".$thecode."'"]],"check-ajax.php","showManufacturable",'empty().append(html)');
   
   
    
}
function show_where_used($db,$thecode){
    
    $allparents=find_where_used($db,$thecode);
    if(!empty($allparents)){
        echo'<div class="col-sm-12" >';
            echo'<div class="row header-check">';
                echo'<div class="col-sm-12" style="text-align: center;">Where Used</div>';
                echo'<div class="col-sm-8" style="text-align: center;">Code</div>';
                echo'<div class="col-sm-4" style="text-align: center;">QTY</div>';
            echo'</div>';
            foreach($allparents as $parent){
                echo'<div class="row row_check" ondblclick="load_code(\''.$parent['Code'].'\')">';
                    echo'<div class="col-sm-8" style="text-align: center;">'.$parent['Code'].'</div>';
                    echo'<div class="col-sm-4" style="text-align: center;">'.number_format($parent['Quantity'],2).'</div>';
                echo'</div>';
            }
            
            
        echo'</div>';
    }
   
     
    
     
}


function navbar_check($db,$thecode){
    echo'<div class="row">';
        echo'<div class="col-sm-3 showproductlist">';
            echo'<div class="col-sm-4">Product:</div>';$time[] = microtime(true);
            echo'<div class="col-sm-6 ">';
            echo'<form method="POST">';
            echo '<input type="text" disabled list="thelist" name="code" class="form-control" onchange="submit();" id="list_product"">
            ';
            echo '<input type="hidden"  name="view" value="'.$_POST['view'].'">';
            echo'</form>';
        
            echo'</div>';
        echo'</div>';
        
        echo'<div class="col-sm-1">';
            echo'<form method="POST">';
            echo'<button type="submit" name="show_dashboard" value="show_dashboard" class="btn btn-default" >
                                <span class="glyphicon glyphicon-refresh" ></span>
            </button><br>&nbsp';
            echo '<input type="hidden"  name="view" value="'.$_POST['view'].'">';
            echo '<input type="hidden"  name="code" value="'.$_POST['code'].'">';
            echo'</form>';
        echo'</div>';
        echo'<div class="col-sm-4 title-BOM">';
            echo $thecode;
        echo'</div>';
        echo'<div class="col-sm-2">';
        echo'<form method="POST">';
        if($_POST['view']=='stock_analysis'){
            echo'<button type="submit" name="view" value="view_BOM"  class="btn btn-default" >
                        View BOM
                        </button>';
            
        }else{ //if($_SESSION['temp']['id']=='CorentinHillion')
            echo'<button type="submit" name="view" value="stock_analysis"  class="btn btn-default" >
        View Stock
            </button>';
        }
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
    LEFT JOIN( SELECT [Quantity] as Stock_LEVEL,[Code] as Product_code FROM Current_Stock WHERE TheLocation=\''.$location.'\'
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

function showBOM($db,$thecode){
    $ListComponent=get_component($db,$thecode);
   // show( $ListComponent);
    echo'<div class="col-sm-7" >';
        echo'<div class="row header-check">';
            echo'<div class="col-sm-12" style="text-align: center;">BOM - '.$thecode.'</div>';
            echo'<div class="col-sm-7" style="text-align: center;">Component</div>';
            echo'<div class="col-sm-2" style="text-align: center;">Quantity</div>';
            echo'<div class="col-sm-3" style="text-align: center;">';
            if(!empty($_SESSION['temp']['role_costing_view'])){echo'Cost';}
            //
            echo'</div>';
        // echo'<div class="col-sm-2" style="text-align: center;">%</div>';

        echo'</div>';
        $cost=0;
        $costLabour=0;
        

        foreach($ListComponent as $component){

            $classname=str_replace(' ', '', str_replace('.', '',$component['Component']));
            $classname=str_replace('/', '', str_replace('.', '',$classname));
            echo'<div id="parent-'.$component['Component'].'" class="row row_check" ondblclick="load_code(\''.$component['Component'].'\')" onmouseover="show_detail(this,\''.$classname.'\');" onmouseout="dont_show_detail(this,\''.$classname.'\');">';
                echo'<div class="col-sm-7" style="text-align: center;">';
                echo $component['Component'];
                echo'</div>';
                echo'<div class="col-sm-2" style="text-align: center;">';
                echo round($component['Quantity'],2);
                echo'</div>';
                echo'<div class="col-sm-3" style="text-align: center;">';
                //
                if(!empty($_SESSION['temp']['role_costing_view'])){echo 'AUD$'.round($component['LastCost']*$component['Quantity'],2);}
                echo'</div>';
                $cost=$cost+$component['LastCost']*$component['Quantity'];
                if($component['Component']=='ZINDIRECT-OVERHEAD' or $component['Component']=='ZLABOUR-ASSEMBLY' or $component['Component']=='ZLABOUR-MANUF.' or $component['Component']=='ZSTAMPING'){$costLabour=$costLabour+$component['LastCost']*$component['Quantity'];}
            echo'</div>';

        }
        $costPurchase=$cost-$costLabour;
        echo'<div id="row_check" class="row row-total-check">';
            
            echo'<div class="col-sm-7" style="text-align: center;">Total</div>';
            echo'<div class="col-sm-2" style="text-align: center;"></div>';
            echo'<div class="col-sm-3" style="text-align: center;">';
            if(!empty($_SESSION['temp']['role_costing_view'])){echo 'AUD$'.round($cost,2);}
            echo'</div>';

        echo'</div>';
        echo'<div class="row row_check">';
            
            echo'<div class="col-sm-7" style="text-align: center;">Labour</div>';
            echo'<div class="col-sm-2" style="text-align: center;">'.round($costLabour/$cost*100,1).'%</div>';
            echo'<div class="col-sm-3" style="text-align: center;">';
            if(!empty($_SESSION['temp']['role_costing_view'])){echo 'AUD$'.round($costLabour,2);}
            echo'</div>';
        

        echo'</div>';
        echo'<div class="row row_check">';
            
            echo'<div class="col-sm-7" style="text-align: center;">Purchase Good</div>';
            echo'<div class="col-sm-2" style="text-align: center;">'.round($costPurchase/$cost*100,1).'%</div>';
            echo'<div class="col-sm-3" style="text-align: center;">';
            if(!empty($_SESSION['temp']['role_costing_view'])){echo 'AUD$'.round($costPurchase,2);}
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
                    if(!empty($_SESSION['temp']['role_costing_view'])){echo 'AUD$'.round($component['LastCost']*$component['Quantity'],2);}
                    echo'</div>';
                    $cost=$cost+$component['LastCost']*$component['Quantity'];
                    if($component['Component']=='ZINDIRECT-OVERHEAD' or $component['Component']=='ZLABOUR-ASSEMBLY' or $component['Component']=='ZLABOUR-MANUF.' or $component['Component']=='ZSTAMPING'){$costLabour=$costLabour+$component['LastCost']*$component['Quantity'];}
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


    echo'<form id="form_to_load" method="POST">';
    echo'<input type="hidden" id="code_to_load" name="code" value="'.$thecode.'">';
    echo'</>';
    echo'<script>
    function load_code(thecode){
        document.getElementById("code_to_load").value=thecode;
        document.getElementById("form_to_load").submit();
    }
    
    </script>';
    
}

function showstockanalysis($db,$thecode){
   
    $ListComponent=get_component_stock($db,$thecode,'QLD');


    //$ListComponent=get_component_complete($db,$thecode,'QLD');
    //show( $ListComponent);

    echo'<div class=""><div class="row header-check">';
        echo'<div class="col-sm-12" style="text-align: center;">Current Stock Situation</div>';
        echo'<div class="col-sm-6" style="text-align: center;">Component</div>';
        
        echo'<div class="col-sm-3" style="text-align: center;">Stock</div>';
        echo'<div class="col-sm-3" style="text-align: center;">Equivalent Connector </div>';
        //echo'<div class="col-sm-3" style="text-align: center;">Final Part Equivalent</div>';
       // echo'<div class="col-sm-2" style="text-align: center;">%</div>';
       echo'</div>';
    
    $cost=0;
    $costLabour=0;
  
    echo '<script>
    function Select_div(Component,Classname){
        
        $("."+Classname).css("background", "#c1ffc6");
    }

    var count=0;
    var total=0;
    function add_total(Component,Class,equivalent){
        
        
            $("."+Class).css("border", "1px solid black");
            $("."+Class).css("border-radius", "border-radius:5px");
            
            total=total+equivalent;
        
           
        document.getElementById("show_total").innerHTML = Math.round(total,0);
       
    }
    function remove_total(Component,Class,equivalent){
        
        $("."+Class).css("border", "none");
           
        total=total-equivalent;
        document.getElementById("show_total").innerHTML = Math.round(total,0);
        
    }
    
    </script>';

    foreach($ListComponent as $component){
        $classname=str_replace(' ', '', str_replace('.', '',$component['Component']));
        $classname=str_replace('/', '', str_replace('.', '',$classname));
       
        echo'<div class="row row_check '.$classname.'" 
        onclick="add_total(\''.$component['Component'].'\',\''.$classname.'\','.round($component['Stock_LEVEL']/$component['Quantity'],2).');"
        oncontextmenu="remove_total(\''.$component['Component'].'\',\''.$classname.'\','.round($component['Stock_LEVEL']/$component['Quantity'],2).');return false;"
        onhover=""
        >';
            echo'<div class="col-sm-6" style="text-align: center;">';
            echo $component['Component'];
            echo'</div>';
            
            echo'<div class="col-sm-3" style="text-align: center;">';
            echo number_format(round($component['Stock_LEVEL'],1));
            echo'</div>';
            echo'<div class="col-sm-3" style="text-align: center;">';
            echo number_format(round($component['Stock_LEVEL']/$component['Quantity'],2));
            echo'</div>';
            //echo'<div class="col-sm-3" style="text-align: center;">';
            //echo 'AUD$'.round($component['LastCost']*$component['Quantity'],2);
            //echo'</div>';
           
        echo'</div>';

    }
    echo'<div class="row row_check " >';
            echo'<div class="col-sm-6" style="text-align: center;">';
            
            echo'</div>';
            
            echo'<div class="col-sm-3" style="text-align: center;">Total</div>';
            echo'<div class="col-sm-3" style="text-align: center;" id="show_total"></div>';
            //echo'<div class="col-sm-3" style="text-align: center;">';
            //echo 'AUD$'.round($component['LastCost']*$component['Quantity'],2);
            //echo'</div>';
           
        echo'</div>';
    echo'</div>';
    ajax_load([['show_org2',"'ok'"],['code',"'".$thecode."'"]],"check-ajax.php","show_org",'empty().append(html)');
}


function make_product($Stock,$need,$ListComponent,$thecode,$movement,$initialParent){
    ///ListComponent Quantity required

    foreach($ListComponent as $component){

        $usage=$need*$component['Quantity'];
       // if()  // usage = need x the br from the BOM 
        show($component['Component'].' : '. $need.'*'.$component['Quantity'] .'| Stock: '.$Stock[$component['Component']].' | NewStock: '.($Stock[$component['Component']]-$usage).'| HasSub: '.$Stock[$component['HasSub']].'');
        //check if we can make it
        $Stock[$component['Component']]=$Stock[$component['Component']]-$usage;
        $movement[$component['Component']]=$movement[$component['Component']]-$usage;
        if($Stock[$component['Component']]>=0){
           
        }elseif($component['HasSub']==1){
            
            $error[$component['Component']]['Component']=$component['Component'];
            $error[$component['Component']]['ParentQuantity']=$component['Quantity'];
            $error[$component['Component']]['need']=-($Stock[$component['Component']]);
           // $error[$component['Component']]=1;
        }else{
            //$stop['test']=$component['Component'];
            
            //$stop['Equivalent']=(-$Stock[$component['Component']]);
            $stop[$component['Component']]['Component']=$component['Component'];
            
            $stop[$component['Component']]['Equivalent']=(-$Stock[$component['Component']]);
        }
        
    }
    $Stock[$thecode]=$Stock[$thecode]+$need;
    $movement[$thecode]=$movement[$thecode]+$need;
    $return['Stock']=$Stock;
    $return['error']=$error;
    $return['stop']=$stop;
    $return['movement']=$movement;
    
    show($return);
    return $return;
}

function calculate_max_prod($db,$thecode,$need){
    $stop='OK';
    $ListComponent=get_component_complete($db,$thecode,'QLD',' and TreeLevel=1 ');
    $i=0;
    foreach($ListComponent as $component){
        $ListComponent2[$component['Code']][$component['Component']]['Component']=$component['Component'];
        $ListComponent2[$component['Code']][$component['Component']]['HasSub']=$component['HasSub'];
        $ListComponent2[$component['Code']][$component['Component']]['Code']=$component['Code'];
        $Stock[$component['Component']]=$component['Stock_LEVEL'];
        $ListComponent2[$component['Code']][$component['Component']]['Quantity']=$component['Quantity'];
        $ListComponent2[$component['Code']][$component['Component']]['TreeLevel']=$component['TreeLevel'];
        if($component['HasSub']==1){
            $tobeexploded[$i][]=$component['Component'];
        }
    }
    //show($tobeexploded[$i]);
    foreach($tobeexploded[$i] as $singleexploded){
        $ListComponent=get_component_complete($db,$singleexploded,'QLD',' and TreeLevel=1 ');
        foreach($ListComponent as $component){
            $ListComponent2[$component['Code']][$component['Component']]['Component']=$component['Component'];
            $ListComponent2[$component['Code']][$component['Component']]['HasSub']=$component['HasSub'];
            $ListComponent2[$component['Code']][$component['Component']]['Code']=$component['Code'];
            $Stock[$component['Component']]=$component['Stock_LEVEL'];
            $ListComponent2[$component['Code']][$component['Component']]['Quantity']=$component['Quantity'];
            $ListComponent2[$component['Code']][$component['Component']]['TreeLevel']=$component['TreeLevel'];
            if($component['HasSub']==1){
                $tobeexploded[$i+1][]=$component['Component'];
            }
        }

    }
    $i++;
    foreach($tobeexploded[$i] as $singleexploded){
        $ListComponent=get_component_complete($db,$singleexploded,'QLD',' and TreeLevel=1 ');
        foreach($ListComponent as $component){
            $ListComponent2[$component['Code']][$component['Component']]['Component']=$component['Component'];
            $ListComponent2[$component['Code']][$component['Component']]['HasSub']=$component['HasSub'];
            $ListComponent2[$component['Code']][$component['Component']]['Code']=$component['Code'];
            $Stock[$component['Component']]=$component['Stock_LEVEL'];
            $ListComponent2[$component['Code']][$component['Component']]['Quantity']=$component['Quantity'];
            $ListComponent2[$component['Code']][$component['Component']]['TreeLevel']=$component['TreeLevel'];
            if($component['HasSub']==1){
                $tobeexploded[$i+1][]=$component['Component'];
            }
        }

    }
    $i++;
    foreach($tobeexploded[$i] as $singleexploded){
        $ListComponent=get_component_complete($db,$singleexploded,'QLD',' and TreeLevel=1 ');
        foreach($ListComponent as $component){
            $ListComponent2[$component['Code']][$component['Component']]['Component']=$component['Component'];
            $ListComponent2[$component['Code']][$component['Component']]['HasSub']=$component['HasSub'];
            $ListComponent2[$component['Code']][$component['Component']]['Code']=$component['Code'];
            $Stock[$component['Component']]=$component['Stock_LEVEL'];
            $ListComponent2[$component['Code']][$component['Component']]['Quantity']=$component['Quantity'];
            $ListComponent2[$component['Code']][$component['Component']]['TreeLevel']=$component['TreeLevel'];
            if($component['HasSub']==1){
                $tobeexploded[$i+1][]=$component['Component'];
            }
        }

    }
    $i++;
    foreach($tobeexploded[$i] as $singleexploded){
        $ListComponent=get_component_complete($db,$singleexploded,'QLD',' and TreeLevel=1 ');
        foreach($ListComponent as $component){
            $ListComponent2[$component['Code']][$component['Component']]['Component']=$component['Component'];
            $ListComponent2[$component['Code']][$component['Component']]['HasSub']=$component['HasSub'];
            $ListComponent2[$component['Code']][$component['Component']]['Code']=$component['Code'];
            $Stock[$component['Component']]=$component['Stock_LEVEL'];
            $ListComponent2[$component['Code']][$component['Component']]['Quantity']=$component['Quantity'];
            $ListComponent2[$component['Code']][$component['Component']]['TreeLevel']=$component['TreeLevel'];
            if($component['HasSub']==1){
                $tobeexploded[$i+1][]=$component['Component'];
            }
        }

    }
    $i++;
    foreach($tobeexploded[$i] as $singleexploded){
        $ListComponent=get_component_complete($db,$singleexploded,'QLD',' and TreeLevel=1 ');
        foreach($ListComponent as $component){
            $ListComponent2[$component['Code']][$component['Component']]['Component']=$component['Component'];
            $ListComponent2[$component['Code']][$component['Component']]['HasSub']=$component['HasSub'];
            $ListComponent2[$component['Code']][$component['Component']]['Code']=$component['Code'];
            $Stock[$component['Component']]=$component['Stock_LEVEL'];
            $ListComponent2[$component['Code']][$component['Component']]['Quantity']=$component['Quantity'];
            $ListComponent2[$component['Code']][$component['Component']]['TreeLevel']=$component['TreeLevel'];
            if($component['HasSub']==1){
                $tobeexploded[$i+1][]=$component['Component'];
            }
        }

    }
    $i++;
    foreach($tobeexploded[$i] as $singleexploded){
        $ListComponent=get_component_complete($db,$singleexploded,'QLD',' and TreeLevel=1 ');
        foreach($ListComponent as $component){
            $ListComponent2[$component['Code']][$component['Component']]['Component']=$component['Component'];
            $ListComponent2[$component['Code']][$component['Component']]['HasSub']=$component['HasSub'];
            $ListComponent2[$component['Code']][$component['Component']]['Code']=$component['Code'];
            $Stock[$component['Component']]=$component['Stock_LEVEL'];
            $ListComponent2[$component['Code']][$component['Component']]['Quantity']=$component['Quantity'];
            $ListComponent2[$component['Code']][$component['Component']]['TreeLevel']=$component['TreeLevel'];
            if($component['HasSub']==1){
                $tobeexploded[$i+1][]=$component['Component'];
            }
        }

    }
    $i++;
    

    //show($ListComponent2);

    
    
    //$ListComponent=get_component_complete($db,$thecode,'QLD'," and Code='$thecode'");
    //show($Stock);
    $movement=array();
    $return[0]=make_product($Stock,$need,$ListComponent2[$thecode],$thecode,$movement,$thecode);
    
    $Stock=$return[0]['Stock'];
    $movement=$return[0]['movement'];
    //show($Stock);
    //show($return[0]['error']);
    if(empty($return[0]['stop'])){
        foreach($return[0]['error'] as $tobemade){
            
            $return[1]=make_product($Stock,$tobemade['need'],$ListComponent2[$tobemade['Component']],$tobemade['Component'],$movement,$thecode);
            
            $Stock=$return[1]['Stock'];
            $movement=$return[1]['movement'];
            //show($return[1]['error']);
            if(empty($return[1]['stop'])){
                foreach($return[1]['error'] as $tobemade){
        
                    $return[2]=make_product($Stock,$tobemade['need'],$ListComponent2[$tobemade['Component']],$tobemade['Component'],$movement,$thecode);
                    
                    $Stock=$return[2]['Stock'];
                    $movement=$return[2]['movement'];
                    if(empty($return[2]['stop'])){
                        foreach($return[3]['error'] as $tobemade){
                
                            $return[3]=make_product($Stock,$tobemade['need'],$ListComponent2[$tobemade['Component']],$tobemade['Component'],$movement,$thecode);
                            
                            $Stock=$return[3]['Stock'];
                            $movement=$return[3]['movement'];
                            if(empty($return[3]['stop'])){
                                foreach($return[4]['error'] as $tobemade){
                        
                                    $return[4]=make_product($Stock,$tobemade['need'],$ListComponent2[$tobemade['Component']],$tobemade['Component'],$movement,$thecode);
                                    
                                    $Stock=$return[4]['Stock'];
                                    $movement=$return[4]['movement'];
                                    
                                }
                            }else{$stop='Problem '.$return[3]['stop']['Component'].' '.$return[3]['stop']['Equivalent'];}
                            
                        }
                    }else{$stop='Problem '.$return[2]['stop']['Component'].' '.$return[2]['stop']['Equivalent'];}
                    
                }
            }else{$stop='Problem '.$return[1]['stop']['Component'].' '.$return[1]['stop']['Equivalent'];}
            
        }

    }else{$stop='Problem '.$return[0]['stop']['Component'].' '.$return[0]['stop']['Equivalent'];}
    //show($Stock);
    //show($movement);
    $i=0;
    foreach($return as $line){
        if (!empty($line['stop'])){
            foreach($line['stop'] as $stop){
                $problem[$i]['Component']=$stop['Component'];
            
                $basequantity=get_component_complete($db,$thecode,'QLD',' and Component=\''.$stop['Component'].'\'');
                $basequantity=$basequantity[0];
               
                $problem[$i]['Equivalent']=$stop['Equivalent']/$basequantity['Quantity'];
                $i++;
            }
            
        }
    
        
    }
    $return['problem']=$problem;
    $max=0;
    foreach($return['problem'] as $problem){
        //show($problem);
        $max=max($max,$problem['Equivalent']);
        //show($max);
    }
    $max=ceil($max);
    $return['max_manufacturable']=$need-$max;
    return $return;
}

function showpiechart($db,$thecode){
    $ListComponent=get_component($db,$thecode);
    $data="['Component','Cost'],";
    foreach($ListComponent as $component){
    $data=$data."['".$component['Component']."',".round($component['Cost_Total'],2)."],"; 
    }

      echo'<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      <script type="text/javascript">
        google.charts.load(\'current\', {\'packages\':[\'corechart\']});
        google.charts.setOnLoadCallback(drawChart);
  
        function drawChart() {
  
          var data = google.visualization.arrayToDataTable([
           '.$data.'
          ]);
  
          var options = {
            title: \'BOM: '.$thecode.'\',
            is3D: true,
          };
  
          var chart = new google.visualization.PieChart(document.getElementById(\'piechart\'));
  
          chart.draw(data, options);
        }
      </script>';
      echo'<div id="piechart" style="width: 900px; height: 500px;"></div>';
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
    where  Parent=\''.$Code.'\' and (Component not like \'%MACHINING%\' and Component not like \'%LABEL%\' and Component not like \'%GREASE%\' and Component not like \'%ZSTAMPING%\')
    order by ([LastCost]*[Quantity])ASC
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $ListComponent=$sql->fetchall();
    return $ListComponent;
}

function get_component_org2($db,$Code){
    $query='SELECT HasSub,Component,Code,Quantity
    from BOM_Detailled
    where  Parent=\''.$Code.'\' and (Component not like \'%MACHINING%\' and Component not like \'%LABEL%\' and Component not like \'%GREASE%\' and Component not like \'%ZSTAMPING%\')
    ';//and WorkArea=\'Push-On Bolt\'
    $sql = $db->prepare($query); 
    $sql->execute();
    $ListComponent=$sql->fetchall();

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
    where  Parent=\''.$Code.'\' 
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

        $ListComponent[$i]['Equivalent']=round($ListComponent2[$key]['Equivalent'],0);
        $ListComponent[$i]['Quantity']=round($ListComponent2[$key]['Quantity'],0);

        $i++;
    }
    //show($ListComponent);
    
    return $ListComponent;
} 
  
function prepare_BOM_graph($ListComponent)  {
    $data['org']='';
    $i=0;
    foreach($ListComponent as $component){
     
      if($component['HasSub']==1){
        $data['org']=$data['org']."[{'v':'".$component['Component']."', 'f':'".$component['Component']."(".round($component['Quantity']/$component['Quantity'],4).")'},'".$component['Code']."',''],";    
      }else{
        $data['org']=$data['org']."[{'v':'".$i.$component['Component']."', 'f':'".$component['Component']."(".round($component['Quantity'],4).")'},'".$component['Code']."',''],";  
        $data['sankey']=$data['sankey']."['".$component['Component']."','".$component['Code']."',".round($component['LastCost']*$component['Quantity'],2)."],";  
      }
      $i++;
    }
    return $data;
}

function prepare_BOM_graph2($ListComponent,$lastyear_qty)  {
    $data['org']='';
    $i=0;
    
    foreach($ListComponent as $component){
        $lastyear_qty=max($lastyear_qty,10);
    $connector_available=floor($component['Equivalent']);
    if ($component['Component']=='ZINDIRECT-OVERHEAD' or $component['Component']=='ZLABOUR-MANUF.' or $component['Component']=='ZLABOUR-ASSEMBLY'or $component['Component']=='ZLABOUR-INDIRECT'or $component['Component']=='ZSTAMPING'){
        $color='#b9ffb6';
    }elseif ($connector_available<($lastyear_qty/12)){
        $color='#ff9999';
    }elseif ($connector_available<($lastyear_qty/6)){
        $color='#ffd9b6';
    }else{
        $color='#b9ffb6';
    }
    $texttoshow="<div style=\" background-color: ".$color." ;border-radius:5px;\">".$component['Component']."(".number_format($connector_available).")</div>";

      if($component['HasSub']==1){
        $data['org']=$data['org']."[{'v':'".$component['Component']."', 'f':'".$texttoshow."'},'".$component['Code']."',''],";    
      }else{
        $data['org']=$data['org']."[{'v':'".$i.$component['Component']."', 'f':'".$texttoshow."'},'".$component['Code']."',''],";  
       
       // $data['sankey']=$data['sankey']."['".$component['Component']."','".$component['Code']."',".round($component['LastCost']*$component['Quantity'],2)."],";  
      }
      $i++;
    }
    return $data;
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

function show_org2($db,$thecode){
    echo'<div id="chart_div_org"></div>
    </div>';
    if(empty($_POST)){
        $thecode='PHM4-6-4/0 B';
        $finalscan[]=$thecode;
        $i=0;
        $data=array();
        $NewList=get_component_org2($db,$thecode);
        $data['org']=$data['org']."[{'v':'".$thecode."', 'f':'<b>".$thecode."</b>'},'',''],";
        //show($data['org']);
        $tempdata=prepare_BOM_graph2($NewList,$lastyear_qty);
        $data['org']=$data['org'].$tempdata['org'];
        
        
      }else{
       // show($_POST);
        $thecode=$_POST['code'];
       //$data=$_POST['data'];
        $NewList=get_component_org2($db,$thecode);
        $data=array();
        $stats=get_stats($db,$thecode);
        $lastyear_qty=$stats['qty_last_year'];
        $data['org']=$data['org']."[{'v':'".$thecode."', 'f':'".$thecode." <br>".number_format($lastyear_qty/12)." parts/month'},'',''],";
        //show($NewList);
        
        
        $tempdata=prepare_BOM_graph2($NewList,$lastyear_qty);
        $data['org']=$data['org'].$tempdata['org'];
        //show($data['org']);
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
        //google.visualization.events.addListener(chart, \'select\', toggleDisplay);
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
function find_where_used($db,$component){
    $query='SELECT distinct Code,Component,Quantity 
    from BOM_Detailled
	where Component=\''.$component.'\' and treelevel=1
    order by code
    
    ';

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();

    return $row;
}


?>