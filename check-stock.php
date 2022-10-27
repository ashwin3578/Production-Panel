

<?php $time[] = microtime(true); // Top of page
$page_title='Check-Stock';
$title_top='Check-Stock';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/issue_log.css">	
<link rel="stylesheet" href="css/roster.css">

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<?php $_SESSION['temp']['addscan'] = array();
	 include ('navbar.php'); 
  include ('function_framework.php'); 
  include ('function_check.php'); 
  
  echo '<link rel="stylesheet" href="css/checkstock.css?v='.time().'">';

if(empty($_POST)){
    $thecode='PHM4-6-4/0 B';
    
    
  }else{
    //show($_POST);
   $thecode=$_POST['code'];
   
  }
  
  navbar_check($db,$thecode);

   
        echo'<div class="row">';
            echo'<div class="col-sm-10 showstockanalysis" >';

                    
           


            //$ListComponent=get_component_complete($db,$thecode,'QLD');
            //show( $ListComponent);

            echo'<div class=""><div class="row header-check">';
                echo'<div class="col-sm-12" style="text-align: center;">Current Stock Situation</div>';
                echo'<div class="col-sm-3" style="text-align: center;">Component</div>';
                echo'<div class="col-sm-3" style="text-align: center;">Parent</div>';
                
                echo'<div class="col-sm-2" style="text-align: center;">Stock</div>';
                echo'<div class="col-sm-2" style="text-align: center;">Equivalent Connector </div>';
                echo'<div class="col-sm-2" style="text-align: center;">Making </div>';
                echo'<div class="col-sm-2" style="text-align: center;">Control </div>';
                //echo'<div class="col-sm-3" style="text-align: center;">Final Part Equivalent</div>';
            // echo'<div class="col-sm-2" style="text-align: center;">%</div>';
            echo'</div>';
            
         
        
            echo '<script>
            function Select_div(Component,Classname){
                
                $("."+Classname).css("background", "#c1ffc6");
            }

            var count=0;
            var total=0;
            function add_todfgdfgdfgdftal(Component,Class,equivalent){
                
                
                    $("."+Class).css("border", "1px solid black");
                    $("."+Class).css("border-radius", "border-radius:5px");
                    
                    total=total+equivalent;
                
                
                document.getElementById("show_total").innerHTML = Math.round(total,0);
            
            }
            function remove_tgdfgdfgdfgotal(Component,Class,equivalent){
                
                $("."+Class).css("border", "none");
                
                total=total-equivalent;
                document.getElementById("show_total").innerHTML = Math.round(total,0);
                
            }

            const product = {
                Name: "PFV100 695E IDPW",
                Stock: 0,
                id:1,
                Component:{
                    
                }
                add_stock : function(qty) {
                    this.Stock=this.Stock+qty;
                  }
              };

              function make_product(product,qty){

              }




              product.add_stock(500);
              alert(product.Stock);




            function Test(Classname,element){
                var stock= parseFloat(document.getElementById("Stock-"+Classname).value);
                var variation=parseFloat(element.value)-parseFloat(document.getElementById("old-"+Classname).value)
                stock=Math.round((stock+variation)*10)/10;
                //alert(element.value);
                document.getElementById("Stock-"+Classname).value=stock;
                document.getElementById("old-"+Classname).value=parseFloat(element.value);
            }
            

            
            </script>';
            
            function create_classname($name){
                $classname=str_replace(' ', '', str_replace('.', '',$name));
                $classname=str_replace('/', '', str_replace('.', '',$classname));
                return $classname;
            }    



                    $first['Component']=$thecode;
                    $first['Stock_LEVEL']=0;
                    $first['Quantity']=1;
                    $first['HasSub']=1;
                    $time[] = microtime(true);   
                $ListComponent=get_component_stock2($db,$thecode,'QLD','');
                $parentlist=findparent($db,$thecode);
               // show($parentlist);
                $time[] = microtime(true);
               array_unshift($ListComponent,$first);
               $time[] = microtime(true);
                foreach($ListComponent as $component){
                    $classname=create_classname($component['Component']);
                
                    echo'<div class="row row_check '.$classname.'" 
                    
                    onhover=""
                    >';
                        echo'<div class="col-sm-3" style="text-align: center;">';
                        echo $component['Component'];
                        echo'</div>';
                        echo'<div class="col-sm-3" style="text-align: center;">';
                        
                        $i=0;
                        foreach($parentlist[$component['Component']] as $parent){
                            if($i>0){echo'<br>';}
                            echo $parent;
                            $i++;
                        }
                        echo'</div>';
                        
                        echo'<div class="col-sm-2" style="text-align: center;">';
                        echo'<input style="text-align: center;" class="form-control "  id="Stock-'.$component['Component'].'" type="number"  value="'.round($component['Stock_LEVEL'],1).'" readonly >';
                        //echo number_format(round($component['Stock_LEVEL'],1));
                        echo'</div>';
                        echo'<div style="text-align: center;" class="col-sm-2" style="text-align: center;">';
                        echo'<input class="form-control" type="number" id="Equivalent-'.$component['Component'].'" value="'.round($component['Stock_LEVEL']/$component['Quantity'],0).'" readonly >';
                        //echo number_format(round($component['Stock_LEVEL']/$component['Quantity'],2));
                        echo'</div>';
                        echo'<div class="col-sm-2" style="text-align: center;">';
                        
                        echo'</div>';
                        echo'<div class="col-sm-2" style="text-align: center;">';
                        if($component['HasSub']==1){
                            echo'<input type="range" min="0" max="100000" step="50" value="0" id="'.$component['Component'].'" oninput="Test(\''.$component['Component'].'\',this);">';
                            echo'<input type="hidden" value="0" id="old-'.$component['Component'].'" >';
                        }
                            
                        echo'</div>';
                        //echo'<div class="col-sm-3" style="text-align: center;">';
                        //echo 'AUD$'.round($component['LastCost']*$component['Quantity'],2);
                        //echo'</div>';
                    
                    echo'</div>';
                    $time[] = microtime(true);
                }
            
                $time[] = microtime(true);
                showtimes($time);
            
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
            
            echo'</div>';
        echo'</div>';
        echo'<div class="postinfo" >';
        //showtimes($time);
        echo'</div>';
        
    
    ajax_load([['showproductlist',"'ok'"],['view',"'".$_POST['view']."'"]],"check-ajax.php","showproductlist",'empty().append(html)');

    
  
   


	?>
    <div class="row">
  
  
   
  
  
   
	
</div>


