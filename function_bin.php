<?php

function manage_POST_bin($db){
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

function navbar_bin($db){
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

function general_view_bin($db){
    echo'<div class="postinfo">';
    echo'</div>';
    echo'<div class="allview">';
        showall_bin($db);
    echo'</div>';
    
}

function showall_bin($db){
    echo'<div class="row">';
        echo'<div class="col-sm-6 col-lg-4" >';
            echo'<div class="row">';
            show_bin($db);
            echo'</div><br>';
            echo'<div class="row" style="max-height: 600px; overflow-y: scroll;">';
            if(!empty($_POST['bin_no'])){
                $allbin=get_all_bin($db,' WHERE BinNo like \'%'.$_POST['bin_no']."%' ");
                //show($allbin);
                foreach( $allbin as $bin){
                    if(strpos($bin['Description']['BinNo'], $_POST['bin_no']) !== false){
                        
                        $rowname=substr($bin['Description']['BinNo'],0,4);
                        $_POST['bintoshow'][]=$rowname;
                        echo'<div onmouseover="focuslocator(\'locator-'.$rowname.'\');" onmouseout="unfocuslocator(\'locator-'.$rowname.'\');">';
                        show_product($db,$bin['Description']['BinNo']);
                        echo'</div>';
                    }
                    
                }
                
            }
            echo'</div>';
        echo'</div>';
        echo'<div class="col-sm-6 col-lg-4" >';
            echo'<div class="row">';
            show_productlist($db);
            echo'</div><br>';
            echo'<div class="row" style="max-height: 600px; overflow-y: scroll;">';
            if(!empty($_POST['product'])){
                $allproduct=get_all_product_list($db,' WHERE Bin_Location.Code like \'%'.$_POST['product']."%' and Quantity>0");
                foreach( $allproduct as $product){
                    show_bin_location($db,$product['Code']);
                }

                
        
            }
            echo'</div>';
        echo'</div>';
        echo'<div class="col-sm-6 col-lg-4" >';
            //if(($_SESSION['temp']['id']=='CorentinHillion')){
                showlayout($db);
            //}
            
        echo'</div>';
        
       
    echo'</div>';
    
}

function showlayout($db){
    echo'<style>
    
    </style>';
    echo '<div class="parent">';
        echo'<img class="image1 "  src="img/layout.jpg" width="100%"  >';
        show_all_locator($db);
    echo'</div>';
    

    
}

function show_all_locator($db){
    $rowname='A';
    $row[$rowname]['rowname']=$rowname;
    $row[$rowname]['constant']='top';
    $row[$rowname]['min']=30;
    $row[$rowname]['max']=50;
    $row[$rowname]['top']=54;
    $row[$rowname]['count']=5;

    $rowname='B';
    $row[$rowname]['rowname']=$rowname;
    $row[$rowname]['constant']='top';
    $row[$rowname]['min']=50;
    $row[$rowname]['max']=30;
    $row[$rowname]['top']=51.5;
    $row[$rowname]['count']=5;

    $rowname='C';
    $row[$rowname]['rowname']=$rowname;
    $row[$rowname]['constant']='top';
    $row[$rowname]['min']=30;
    $row[$rowname]['max']=50;
    $row[$rowname]['top']=49;
    $row[$rowname]['count']=5;

    $rowname='D';
    $row[$rowname]['rowname']=$rowname;
    $row[$rowname]['constant']='top';
    $row[$rowname]['min']=50;
    $row[$rowname]['max']=30;
    $row[$rowname]['top']=46.5;
    $row[$rowname]['count']=5;

    $rowname='E';
    $row[$rowname]['rowname']=$rowname;
    $row[$rowname]['constant']='top';
    $row[$rowname]['min']=30;
    $row[$rowname]['max']=50;
    $row[$rowname]['top']=44;
    $row[$rowname]['count']=5;

    $rowname='F';
    $row[$rowname]['rowname']=$rowname;
    $row[$rowname]['constant']='top';
    $row[$rowname]['min']=58;
    $row[$rowname]['max']=78;
    $row[$rowname]['top']=54;
    $row[$rowname]['count']=5;

    $rowname='G';
    $row[$rowname]['rowname']=$rowname;
    $row[$rowname]['constant']='top';
    $row[$rowname]['min']=78;
    $row[$rowname]['max']=58;
    $row[$rowname]['top']=51.15;
    $row[$rowname]['count']=5;

    $rowname='H';
    $row[$rowname]['rowname']=$rowname;
    $row[$rowname]['constant']='top';
    $row[$rowname]['min']=58;
    $row[$rowname]['max']=78;
    $row[$rowname]['top']=48.3;
    $row[$rowname]['count']=5;

    $rowname='I';
    $row[$rowname]['rowname']=$rowname;
    $row[$rowname]['constant']='top';
    $row[$rowname]['min']=78;
    $row[$rowname]['max']=58;
    $row[$rowname]['top']=45.45;
    $row[$rowname]['count']=5;

    $rowname='J';
    $row[$rowname]['rowname']=$rowname;
    $row[$rowname]['constant']='top';
    $row[$rowname]['min']=58;
    $row[$rowname]['max']=78;
    $row[$rowname]['top']=42.6;
    $row[$rowname]['count']=5;

    $rowname='K';
    $row[$rowname]['rowname']=$rowname;
    $row[$rowname]['constant']='top';
    $row[$rowname]['min']=78;
    $row[$rowname]['max']=58;
    $row[$rowname]['top']=39.75;
    $row[$rowname]['count']=5;

    $rowname='L';
    $row[$rowname]['rowname']=$rowname;
    $row[$rowname]['constant']='top';
    $row[$rowname]['min']=58;
    $row[$rowname]['max']=78;
    $row[$rowname]['top']=36.9;
    $row[$rowname]['count']=5;

    $rowname='M';
    $row[$rowname]['rowname']=$rowname;
    $row[$rowname]['constant']='top';
    $row[$rowname]['min']=78;
    $row[$rowname]['max']=58;
    $row[$rowname]['top']=34.05;
    $row[$rowname]['count']=5;

    $rowname='N';
    $row[$rowname]['rowname']=$rowname;
    $row[$rowname]['constant']='left';
    $row[$rowname]['min']=20;
    $row[$rowname]['max']=8;
    $row[$rowname]['left']=54;
    $row[$rowname]['count']=5;

    $rowname='O';
    $row[$rowname]['rowname']=$rowname;
    $row[$rowname]['constant']='left';
    $row[$rowname]['min']=8;
    $row[$rowname]['max']=20;
    $row[$rowname]['left']=45;
    $row[$rowname]['count']=5;

    
    foreach($row as $therow){
        $interval=($therow['max']-$therow['min'])/$therow['count'];
        if($therow['constant']=='top'){
            for ($x = 0; $x <= $therow['count']; $x++) {
                echo'<img class="locator hidestart" id="locator-'.$therow['rowname'].'-0'.$x.'" src="img/locator.png" width="5%"  style="  top: '.$therow['top'].'%;left: '.round($therow['min']+$x*$interval).'%;">';
            }
        }
        if($therow['constant']=='left'){
            for ($x = 0; $x <= $therow['count']; $x++) {
                echo'<img class="locator hidestart" id="locator-'.$therow['rowname'].'-0'.$x.'" src="img/locator.png" width="5%"  style="  left: '.$therow['left'].'%;top: '.round($therow['min']+$x*$interval).'%;">';
            }
        }
        
        
        
        
    }

    //show($_POST['bintoshow']);
    foreach($_POST['bintoshow'] as $bintoshow){
        echo'<script>document.getElementById("locator-'.$bintoshow.'").classList.remove("hidestart");
     </script>';
        
        
        
    }

    


    echo'<script>
    function focuslocator(x) {
        document.getElementById(x).className = "locator2";
      }
      
      function unfocuslocator(x) {
        document.getElementById(x).className = "locator";
      }
    </script>';
}

function show_productlist($db){
    echo'<div class="row header-check">';
        echo'<div class="col-sm-4" >Product</div>';
        echo'<div class="col-sm-8" onclick="sortby(\'Code\');">';
            echo'<form id="form-sort" method="POST">';
            echo '<input type="text" list="thelist2" name="product" class="form-control" onchange="submit();" id="list_product"">';
            echo'<datalist id="thelist2">';
                foreach (get_all_product_list($db) as &$item){
                    echo"<option >".$item[0]."</option>";
                }
            echo '</datalist>';
            echo'</form>';
        echo'</div>';
    echo'</div>';
}

function show_bin($db){
    //show(($allbin));
    echo'<div class="row header-check">';
        echo'<div class="col-sm-4" >Bin</div>';
        echo'<div class="col-sm-8" onclick="sortby(\'Code\');">';
            echo'<form id="form-sort" method="POST">';
            echo '<input type="text" list="thelist" name="bin_no" class="form-control" onchange="submit();" id="list_product"">';
            echo'<datalist id="thelist">';
                foreach (get_all_bin($db) as &$item){
                    echo"<option >".$item['Description']['BinNo']."</option>";
                }
            echo '</datalist>';
            echo'</form>';
        echo'</div>';
    echo'</div>';
       
}

function show_product($db,$binno){
    $allproduct=(get_product_in_bin($db,$binno));
    echo'<div class="row header-check A01A">';
        echo'<div class="col-sm-12" style="text-align: center;">Bin No : '.$binno.'</div>';
        echo'<div class="col-sm-8" style="text-align: center;">Product</div>';
        echo'<div class="col-sm-4" style="text-align: center;">Qty</div>';
        
    echo'</div>';
    foreach ($allproduct as $product){
        echo'<div class="row row_check" onclick="document.getElementById(\'form-sort'.$product['Code'].'\').submit();">';
       
            echo'<div class="col-sm-8" style="text-align: center;" >'.$product['Code'].'</div>';
            echo'<div class="col-sm-4" style="text-align: center;" >'.number_format($product['Stock']).'</div>';

        echo'<form id="form-sort'.$product['Code'].'" method="POST">';
        echo '<input type="hidden"  name="product" value="'.$product['Code'].'">';
        echo'</form>';    
        echo'</div>';
    }
}

function show_bin_location($db,$product){

    //,
    $allbin=(get_bin_list($db,$product));
    
    echo'<div class="row header-check">';
        echo'<div class="col-sm-12" style="text-align: center;">Product : '.$product.'</div>';
        echo'<div class="col-sm-4" style="text-align: center;">Bin No</div>';
        echo'<div class="col-sm-4" style="text-align: center;">Location</div>';
        echo'<div class="col-sm-4" style="text-align: center;">Qty</div>';
        
    echo'</div>';
    foreach ($allbin as $bin){
        $rowname=substr($bin['Description']['BinNo'],0,4);
        $_POST['bintoshow'][]=$rowname;
        echo'<div class="row row_check" onmouseover="focuslocator(\'locator-'.$rowname.'\');" onmouseout="unfocuslocator(\'locator-'.$rowname.'\');" onclick="document.getElementById(\'form-sort'.$bin['Description']['BinNo'].'\').submit();">';
            
            echo'<div class="col-sm-4" style="text-align: center;" >'.$bin['Description']['BinNo'].'</div>';           
            echo'<div class="col-sm-4" style="text-align: center;" >'.$bin['Description']['Description'].'</div>';
            echo'<div class="col-sm-4" style="text-align: center;" >'.number_format($bin['Description']['Stock']).'</div>';
            echo'<form id="form-sort'.$bin['Description']['BinNo'].'" method="POST">';
            echo '<input type="hidden"  name="bin_no" value="'.$bin['Description']['BinNo'].'">';
            echo'</form>';
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

function get_all_bin($db,$option=''){
    $query='SELECT [BinNo],Description
            
        FROM [barcode].[dbo].[Bin_Location]
        
       '.$option.'
        group by [BinNo],Description
        
        order by BinNo';
	
    
    $sql = $db->prepare($query); 
    $sql->execute();
    //show($query);
    $allbin=$sql->fetchall();
    foreach($allbin as $bin){
        if(strpos($bin['BinNo'], ';')===false){
            $newbin[trim($bin['BinNo'],' ')]['Description']['BinNo']=trim($bin['BinNo'],' ');
            $newbin[trim($bin['BinNo'],' ')]['Description']['Description']=trim($bin['Description'],' ');
        }else{
            $str_arr = explode (";", $bin['BinNo']); 
            //show($str_arr);
            foreach($str_arr as $bin2){
                $newbin[trim($bin2,' ')]['Description']['BinNo']=trim($bin2,' ');
                $newbin[trim($bin2,' ')]['Description']['Description']=trim($bin['Description'],' ');
            }
        }
    }
    sort($newbin);
  
   $allbin=$newbin;
    return $allbin;
}

function get_bin_list($db,$product){
    $query='SELECT [BinNo],Description,sum([Quantity]) as Stock
            
        FROM [barcode].[dbo].[Bin_Location]
        left join 
       current_Stock on (Description=[TheLocation] and [Bin_Location].Code=current_Stock.Code)
        WHERE [Bin_Location].Code=\''.$product.'\' and [Quantity]>0
        group by [BinNo],Description
        
        order by BinNo';
	
    
    $sql = $db->prepare($query); 
    $sql->execute();
    //show($query);
    $allbin=$sql->fetchall();
    foreach($allbin as $bin){
        if(strpos($bin['BinNo'], ';')===false){
            $newbin[trim($bin['BinNo'],' ')]['Description']['BinNo']=trim($bin['BinNo'],' ');
            $newbin[trim($bin['BinNo'],' ')]['Description']['Description']=trim($bin['Description'],' ');
            $newbin[trim($bin['BinNo'],' ')]['Description']['Stock']=$newbin[trim($bin['BinNo'],' ')]['Stock']+$bin['Stock'];
        }else{
            $str_arr = explode (";", $bin['BinNo']); 
            //show($str_arr);
            foreach($str_arr as $bin2){
                $newbin[trim($bin2,' ')]['Description']['BinNo']=trim($bin2,' ');
                $newbin[trim($bin2,' ')]['Description']['Description']=trim($bin['Description'],' ');
                $newbin[trim($bin2,' ')]['Description']['Stock']=$newbin[trim($bin2,' ')]['Stock']+$bin['Stock'];
            }
        }
    }
    sort($newbin);
  
   $allbin=$newbin;
    return $allbin;
}

function get_all_product_list($db,$option=''){
    $query='SELECT Bin_Location.Code
    FROM [barcode].[dbo].[Bin_Location]
    left join 
       current_Stock on (Description=[TheLocation] and [Bin_Location].Code=current_Stock.Code)
    '.$option.'
    group by Bin_Location.[Code]
    
    order by Bin_Location.Code asc';


    $sql = $db->prepare($query); 
    $sql->execute();
    //show($query);
    $allbin=$sql->fetchall();
    
    return $allbin;
}

function get_product_in_bin($db,$binno){
    $query='SELECT 
            [Bin_Location].[Code] as Code,
            sum(quantity) as Stock
        FROM [barcode].[dbo].[Bin_Location]
        left join 
       current_Stock on (Description=[TheLocation] and [Bin_Location].Code=current_Stock.Code)
        WHERE BinNo like \'%'.$binno.'%\'
        group by [Bin_Location].Code
        order by [Bin_Location].Code';
	
    
    $sql = $db->prepare($query); 
    $sql->execute();
    //show($query);
    $allproduct=$sql->fetchall();
   
    return $allproduct;
}



?>