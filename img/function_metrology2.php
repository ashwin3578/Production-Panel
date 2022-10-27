<?php

include('function_dashboard.php');

function managing_POST($db){
    if(!empty($_POST['Shift'])){
        $_SESSION['temp']['Shift']=$_POST['Shift'];
    }

    if($_POST['type']=='Create Template'){
		add_template($db);
	}
	if($_POST['type']=='Add Single Test'){
        if($_POST['single_id']=='add-single'){
            add_single_in_test($db);
            check_test_finished($db,$_POST['test_id']);
            $_POST['single_id']='';
        }else{
            add_single_template($db);
        }
		
	}
    if($_POST['type']=='Edit Single Test'){
        add_single_template($db);
        
		
	}
	if(!empty($_POST['delete_single'])){
		delete_single_template($db);
	}
	if(!empty($_POST['delete_template'])){
		delete_template($db);
		$_POST['template_name']='';
        $_POST['manage_template']='Template';
	}
	if(!empty($_POST['list_product'])){
		add_product_template($db);
	}
	if(!empty($_POST['delete_product'])){
		delete_product_template($db);
	}
	if($_POST['template_name']=='Cancel'){
		$_POST=array();
	}
    if($_POST['type']=='return'){
		$_POST=array();
	}
    if($_POST['product_test']=='Cancel'){
		$_POST=array();
	}
    if(!empty($_POST['initiate_new_test'])){
		initiate_new_test($db);
	}
    if(!empty($_POST['save_single_result'])){
		save_single_test_result($db);
        check_test_finished($db,$_POST['test_id']);
        $_POST['single_id']='';
	}

    if(!empty($_POST['copy_single_result'])){
		copy_single_test_result($db);
        check_test_finished($db,$_POST['test_id']);
        $_POST['single_id']='';
	}
    if(!empty($_POST['delete_single_result'])){
		delete_single_result($db);
        check_test_finished($db,$_POST['test_id']);
        $_POST['single_id']='';
	}
    if(!empty($_POST['cancel_single_result'])){
		
        $_POST['single_id']='';
	}
    if(!empty($_POST['delete_test'])){
		delete_test($db);
        $_POST['test_id']='';
	}
    if(!empty($_POST['cancel'])){
		
        $_POST['single_id']='';
	}
   if(empty($_POST['date_to_show'])){
    $_POST['date_to_show']=date('jS F Y',time());
    //$_POST['date_to_filter']=date('Y-m-d',time());
   }
   if(!empty($_POST['change_day'])){
    
    $_POST['date_to_show']=date('jS F Y',strtotime($_POST['date_to_show']." +".$_POST['change_day']." day"));
    //show($_POST['date_to_show']);
    //$_POST['date_to_filter']=date('Y-m-d',time());
   }

   if(empty( $_POST['dashboard_end_date_to_show'])){
        $_POST['dashboard_end_date_to_show']=date('Y-m-d',time());
        $_POST['dashboard_date_to_show']=date('Y-m-d',strtotime($_POST['dashboard_end_date_to_show'].' -7days'));
    
   }

   //show($_SESSION['temp']['search']);
    if(!empty( $_POST['remove-keyword']) ){
        if (($key = array_search($_POST['remove-keyword'], $_SESSION['temp']['search'])) !== false) {
            unset($_SESSION['temp']['search'][$key]);
        }

    }

   if(!empty( $_POST['Search_text']) ){
        
        $_SESSION['temp']['search'][]=$_POST['Search_text'];
   }

   if(!empty( $_POST['Clear_search']) ){
    unset($_SESSION['temp']['search']);
   
}



}



function view_choose_template($db){
    echo'<form method="POST">';
    echo'<div class="row">';
    echo'<b>Choose Template</b>';
    echo'</div>';
    
    echo'<div class="row">';
        echo'<div class="col-sm-9">';
            //echo'<div class="col-sm-4"></div>';
            echo'<select name=template_name class="form-control" onChange=\'submit();\'>';
                if(empty($_POST['template_name'])){echo'<option selected>Choose Template Test</option>';}
                echo'<option >Create New</option>';
                echo'<option >Cancel</option>';
                echo'<option disabled>_________</option>';
                foreach(load_all_template($db) as $single){
                    echo'<option ';
                    if(!empty($_POST['template_name'])&$_POST['template_name']==$single['default_name']){
                        echo'selected';
                    
                    }
                    echo'>'.$single['default_name'].'</option>';
                }
                echo'</select>';
        echo'</div>';
        echo'<form method="POST">';
        echo'<div class="col-sm-3">';
            
            if(!empty($_POST['template_name'])&$_POST['template_name']<>'Create New'){
                $template=load_template($db,$_POST['template_name']);
                if(can_delete_template($db,$template['id'])==0){
                    echo' <input class="form-control" type="hidden" name="template_id" value="'.$template['id'].'">';
                    echo' <input class="form-control" type="submit" name="delete_template" value="X">';
                }
                
            }
        echo'</div>';
        
        
        echo' <div class="col-sm-9"><br><input class="form-control" type="submit"  name="template_name" value="Cancel"></div>';
        echo'</form>';
    echo'</div>';
    
    
    echo'</form>';
}


function view_create_template($db){
    echo'<form method="POST">';
    echo'<div class="row">';
    echo'<b>Create Template</b>';
    echo'</div>';
    
    echo'<div class="row">';
        //echo'<div class="col-sm-4"></div>';
        echo'<div class="col-sm-12">';
            echo' <input class="form-control" type="text" required  name="template_name" placeholder="Template Name">';
        echo'</div>';
    echo'</div>';
    echo'<div class="row">';
        //echo'<div class="col-sm-4"></div>';
        echo'<div class="col-sm-12">';
            echo' <input class="form-control" type="submit" name="type" value="Create Template">';
        echo'</div>';
        echo'</form>';echo'<form method="POST">';
        echo'<div class="col-sm-12">';
        echo' <br><input class="form-control" type="submit"  name="cancel_single_result" value="Cancel">';
        echo'</div>';
    echo'</div>';
    echo'</form>';
        
}

function modify_template($db,$template_id){
    $template=load_template($db,$template_id);

    echo'<form method="POST">';
    echo '<input type="hidden"  name="template_name" value="'.$_POST['template_name'].'">';
    echo '<input type="hidden"  name="template_id" value="'.$template['id'].'">';
    echo'<div class="col-sm-8 test-single-menu">';
        echo'<div class="row">';
        echo'<b>Add Single Test </b>';
        echo'</div>';
    
        echo'<div class="row">';
            echo'<div class="col-sm-6">';
            if(empty($_POST['defaultdetails_id']) or !empty($_POST['delete_single'])or $_POST['type']=='Edit Single Test'){
                echo'<br><select name=single_name class="form-control" onChange=\'submit();\'>';
                if(empty($_POST['single_name'])or $_POST['type']=='Edit Single Test'){echo'<option selected>Add a Single Test</option>';}
                foreach(load_type_single($db) as $single){
                    echo'<option ';
                    if((!empty($_POST['single_name'])&$_POST['single_name']==$single['basesingle_name'])and $_POST['type']<>'Edit Single Test'){
                        echo'selected';
                        $yesno=$single['basesingle_yesno'];
                    }
                    echo'>'.$single['basesingle_name'].'</option>';
                    
               
                }echo'</select>';
            }else{
                prepare_edit_single_template($db);   
            }   
                if(!empty($_POST['single_name'])and $_POST['type']<>'Edit Single Test'){
                    echo' <input class="form-control" type="text" name="Cable" placeholder="Cable"';
                    if(!empty($_POST['Cable'])){ echo'value="'.$_POST['Cable'].'"';}
                    echo'>';
                    if(empty($yesno)){
                        echo' <input class="form-control" type="text" name="min" placeholder="Miniumum"';
                        if(!empty($_POST['min'])){ echo'value="'.round($_POST['min'],4).'"';}
                        echo'>';
                        echo' <input class="form-control" type="text" name="max" placeholder="Maximum"';
                        if(!empty($_POST['max'])){ echo'value="'.round($_POST['max'],4).'"';}
                        echo'>';
                    }
                    echo' <input class="form-control" type="text" name="note" placeholder="Notes"';
                    if(!empty($_POST['note'])){ echo'value="'.$_POST['note'].'"';}
                    echo'>';
                    echo' <br><input class="form-control" type="submit" name="type" value="';
                    if(empty($_POST['defaultdetails_id']) or !empty($_POST['delete_single'])){
                        echo'Add Single Test'; 
                    }else{
                        echo'Edit Single Test';
                    }
                    echo'">';
                }   
             
            echo'<br></div>';
        echo'</div>';
        echo'</form>';
    
        foreach(load_all_single_template($db,$_POST['template_id']) as $single){
            echo'<form method="POST" id="defaultdetails_id-'.$single['defaultdetails_id'].'">';
            echo'<div class="col-sm-6 test-single ';
            if($_POST['defaultdetails_id']==$single['defaultdetails_id']){echo' test-selected ';}
            echo'" >';
                echo'<div class="col-sm-9" onClick="document.forms[\'defaultdetails_id-'.$single['defaultdetails_id'].'\'].submit();" >';
                $line= $single['basesingle_name'].' Test ';
                if($single['defaultdetails_cabledetails']<>''){$line=$line.'<br> Cable: '.$single['defaultdetails_cabledetails'];}
                if($single['defaultdetails_minimum']<>0){$line=$line.'<br> Minimum: '.round($single['defaultdetails_minimum'],4).' '.$single['basesingle_unit'];}
                if($single['defaultdetails_maximum']<>0){$line=$line.'<br> Maximum: '.round($single['defaultdetails_maximum'],4).' '.$single['basesingle_unit'];}
                if(!empty($single['defaultdetails_notes'])){$line=$line.'<br> Notes: '.$single['defaultdetails_notes'].'';}
               
                echo $line;
                echo'</div>';
                
                echo'<div class="col-sm-3">';
                echo '<input type="hidden"  name="template_name" value="'.$_POST['template_name'].'">';
                
                echo' <input class="form-control" type="hidden" name="defaultdetails_id" value="'.$single['defaultdetails_id'].'">';
                echo' <input class="form-control" type="submit" name="delete_single" value="X">';
                echo'</div>';
                echo'</form>';
            echo'</div>';
        }
        
    echo'</div>';
    

    
    echo'<div class="col-sm-4 product-link">';
        echo'<div class="row"><b>Link Product</b></div>';
        echo'<div class="row">';
            echo'<form method="POST">';
            echo '<input type="hidden"  name="template_name" value="'.$_POST['template_name'].'">';
            echo '<input type="hidden"  name="template_id" value="'.$template['id'].'">';
            echo '<input type="text" list="thelist" name="list_product" class="form-control" onchange="submit();" id="list_product"">
            <datalist id="thelist">';
            foreach (get_all_product_metro($db) as &$item){
                echo"<option >".$item[0]."</option>";
            }
            echo '</datalist>';
            echo'</form>';
        echo'</div>';
        foreach (load_all_product_metro($db,$template['id']) as &$product){
            echo'<form method="POST">';
            echo'<div class="row">';
                echo'<div class="col-sm-9">';
                    echo $product['assign_productcode'];
                echo'</div>';
                echo'<div class="col-sm-3">';
                    echo '<input type="hidden"  name="template_name" value="'.$_POST['template_name'].'">';
                    echo '<input type="hidden"  name="template_id" value="'.$product['assign_defaultid'].'">';
                    echo '<input type="hidden"  name="product_code" value="'.$product['assign_productcode'].'">';
                    echo' <input class="form-control" type="submit" name="delete_product" value="X">';
                echo'</div>';
            echo'</div>';
            echo'</form>';
        }
    echo'</div>';
    
    
}

function manage_template($db){
    echo'<div class="row ">';
        echo'<div class="col-sm-3 ">';
            
            if($_POST['template_name']=='Create New'){
                view_create_template($db);
            }else{
                view_choose_template($db);
            }
        echo'</div>';
        echo'<div class="col-sm-8 ">';
            if(!empty($_POST['template_name'])&$_POST['template_name']<>'Create New'){
                modify_template($db,$_POST['template_name']);
            }
        echo'</div>';
        
    echo'</div>';
}

function new_test_view($db){
    
    echo'<div class="row ">';
        echo'<div class="col-sm-3 main-box">';
            echo'<form method="POST">';
            $productlist=get_job_of_today($db);
            if($_POST['product_test']=='Other Product'){$productlist=get_all_product_newtest($db); }
            
            if($_POST['product_test']=='Other Product'){
                echo '<input type="text" list="thelist" name="product_test" class="form-control" onchange="submit();" id="product_test"">
                <datalist id="thelist">';
                foreach ($productlist as &$item){
                    echo"<option >".$item[0]."</option>";
            }
            echo '</datalist>';
            }else{

                echo'<select name="product_test" ';
            // if(!empty($_POST['test_number'])){echo ' disabled ';}
                echo'class="form-control" onChange=\'submit();\'>';
                    if(empty($_POST['product_test'])){echo'<option selected>Choose Product</option>';}
                    echo'<option >Other Product</option>';
                    echo'<option ><b>Cancel</b></option>';
                    echo'<option disabled>_________</option>';
                
                    foreach($productlist as $single){
                        if($single['WorkArea']<>$old_WorkArea){echo'</optgroup ><optgroup label="'.$single['WorkArea'].'">';}
                        if($single['Code']<>$old_Code){
                            echo'<option value="'.$single['Code'].'" ';
                            if(!empty($_POST['product_test'])&$_POST['product_test']==$single['Code']){
                                echo'selected';
                            
                            }
                            echo'>'.$single['Code'];
                            if(empty($single['assign_defaultid'])){echo' (no template)';}
                            echo'</option>';
                        }
                        if($single['WorkArea']<>$old_WorkArea){echo'';}
                        
                        
                        $old_Code=$single['Code'];
                        $old_WorkArea=$single['WorkArea'];
                    }
                echo'</select>';
            }
            echo' <div class="row "><div class="col-sm-6 "><br><input class="form-control" type="submit" name="product_test" value="Cancel"><br></div></form >';
            if(!empty($_POST['product_test'])){
                
            
                    
                    //echo'<div class="col-sm-6 "></div>';
                    echo'<div class="col-sm-6 ">';
                        
                            echo'<form method="POST">';
                            echo' <br><input class="form-control" type="hidden" name="product_test" value="'.$_POST['product_test'].'">';
                            //echo' <input class="form-control" type="hidden" name="MIS_test" value="'.$_POST['MIS_test'].'">';
                            echo' <input class="form-control" type="submit" name="initiate_new_test" value="New Test">';
                        
                            //echo'</form>';
                        
                    echo'</div>';
                
               
            }
            echo'</div>';
           
                
            //echo'</form >';
            if(!empty($_POST['product_test']) & $_POST['product_test']<>'Other Product'){
                echo'<div class="row "><br>';
                
                    echo'<div class="col-sm-6 col-md-4 ">Product :</div>';
                    echo'<div class="col-sm-6 col-md-4">';
                        echo $_POST['product_test'];
                    echo'</div>';
                echo'</div>';
            
                echo'<div class="row ">';
                
                    $allMIS=get_MIS_of_today($db,$_POST['product_test']);
                    echo'<div class="col-sm-6 col-md-4">MIS :</div>';
                    echo'<div class="col-sm-6 col-md-4">';
                    echo'<form method="POST">';    
                        if(!empty($_POST['MIS_test'])){
                            echo $_POST['MIS_test'];
                            echo' <input class="form-control" type="hidden" name="MIS_test" value="'.$_POST['MIS_test'].'">';
                        }else{
                            
                            echo'<select name="MIS_test" class="form-control" onChange=\'submit();\'>';
                            //if(empty($_POST['MIS_test'])){echo'<option selected>Choose MIS</option>';}
                            $i=1;
                            echo'<option>No MIS</option>';
                            foreach($allMIS as $single){
                                echo'<option value="'.$single['scan_jobnumber'].'" ';
                                if($i==1){echo' selected ';}
                                echo'>'.$single['scan_jobnumber'].'</option>';
                                $i++;
                            }
                            echo'</select>';
                            echo' <input class="form-control" type="hidden" name="product_test" value="'.$_POST['product_test'].'">';
                        
                            //echo'</form>';
                        }
                    echo'</div>';
                    
                        
                
                echo'</div>';
                echo'<div class="row ">';
                    echo'<div class="col-sm-6 col-md-4">Shift :</div>';
                    echo'<div class="col-sm-6 col-md-4">';
                        echo'<select name="Shift" class="form-control" >';
                        echo'<option';
                        if($_SESSION['temp']['Shift']=='Morning'){echo' selected';}
                        echo'>Morning</option>';
                        echo'<option';
                        if($_SESSION['temp']['Shift']=='Afternoon'){echo' selected';}
                        echo'>Afternoon</option>';
                        echo'<option';
                        if($_SESSION['temp']['Shift']=='Night'){echo' selected';}
                        echo'>Night</option>';
                        echo'</select>';
                    echo'</div>';
                echo'</div>';

                $template=get_template_complete($db,$_POST['product_test']);
                if(!empty($template)){
                    echo'<div class="row "><br><div class="col-sm-6 col-md-4">Template :</div>';
                    echo'<div class="col-sm-6 col-md-4">'.$template[0]['default_name'].'</div></div>';
                    
                }
                $drawing=get_drawing($db,$_POST['product_test']);
                if(!empty($drawing)){

                    
                   // echo'<div class="row "><div class="col-sm-6 col-md-4">Drawing :</div>';
                   // echo'<div class="col-sm-6 col-md-4"><a target="blank"  href="'.$drawing.'">MD&S</a></div></div>';

                    
                    echo'<div class="row " >
                    <div class="col-sm-12 col-md-12" ><iframe src="'.$drawing.'"  style="height:400px;width:100%;border:none;overflow:hidden;" ></iframe></div></div>';
                    echo "";
                    
                }
                
            }



            
        echo'</div>';
        echo'<div class="col-sm-6 ">';
        if(!empty($_POST['product_test'])){
            
            //load_test_single($db);
            
            //load all test
            
            $alltest=load_all_test($db);
            foreach($alltest as $test){
                if( $last_date<>date('jS F Y',$test['test_timetag'])){
                    echo'<div class="row "><b>';
                    if( date('Y-m-d',$test['test_timetag'])==date('Y-m-d',time())){
                        echo 'Today : ';
                    }
                    echo date('jS F Y',$test['test_timetag']);
                    echo'</b></div>';
                }
                

                echo'<div class="row test-single-menu';
                if ($test['test_finished']==1){echo' test-pass ';}
                if ($_POST['single_id']=='add-single' and $_POST['test_id']==$test['test_id'] ){echo' test-selected ';}
                
                echo'">';
                    echo'<div class="col-sm-3 ">';
                    echo'<form method="POST">';
                        echo '<div class="row"><b>';
                        if ($test['test_finished']==1){
                            echo' <center><img class="attachment" src="img/ok.png" width="25"  ></center>';
                        }else{
                            echo' <center><img class="attachment" src="img/warning.png" width="25"  ></center>';
                        }
                        echo $test['test_id'].'</b></div>';
                        
                        echo '<div class="row">'.date('D d/m/y',$test['test_timetag']).'</div>';
                        echo '<div class="row">'.date('G:i',$test['test_timetag']).'</div>';
                        echo '<div class="row">MIS: '.$test['test_jobnumber'].'</div>';
                        if (!empty($test['test_shift'])){echo '<div class="row">Shift: '.$test['test_shift'].'</div>';}
                        echo '<div class="row">'.$test['test_created_by'].'</div>';
                        //echo '<div class="row">Edit</div>';
                        echo '<div class="row">';
                        if ($test['thecount']==0){echo'<div class="col-sm-4"></div><div class="col-sm-4"><button type="submit" name="delete_test" value="Delete" class="form-control" >
                            <span class="glyphicon glyphicon-trash" ></span></button></div>';}
                        echo'</div>';
                        
                        
                        echo '<input type="hidden"  name="test_id" value="'.$test['test_id'].'">';
                        echo '<input type="hidden"  name="product_test" value="'.$test['test_product'].'">';
                        echo '<input type="hidden"  name="MIS_test" value="'.$test['test_jobnumber'].'">';
                    echo'</form>';
                    echo'</div>';
                    echo'<div class="col-sm-9 ">';

                    
                    $allsingle=load_all_single($db,$test['test_id']);
                    foreach($allsingle as $single){
                        echo'<div class="col-sm-6 test-single-tobedone ';
                        if ($single['single_id']==$_POST['single_id']){echo' test-selected ';}
                        if ($single['single_finished']<>0){echo' test-pass ';}
                        if ($single['single_pass']==-1){echo' test-warning ';}
                        echo'" onClick="document.forms[\'single-'.$single['single_id'].'\'].submit();">';
                            echo'<div class="col-sm-12">';
                            echo '<div class="row">'.$single['single_name'];
                            if (!empty($single['single_cabledetails'])){
                                echo' - '.$single['single_cabledetails'];
                            }
                            echo' </div>';
                            //if($single['single_cabledetails']<>''){echo'<div class="row"> Cable: '.$single['single_cabledetails'].'</div>';}
                            echo'<small><small>';
                            if($single['single_minimum']<>0){echo'<div class="row"> Minimum: '.round($single['single_minimum'],4).' '.$single['single_unit'].'</div>';}
                            if($single['single_maximum']<>0){echo'<div class="row"> Maximum: '.round($single['single_maximum'],4).' '.$single['single_unit'].'</div>';}
                            echo'</small></small>';
                            echo'<div class="row">';
                            if(empty($single['single_yesno'])){
                                if($single['single_finished']<>0){echo' Result: '.round($single['single_result'],4).' '.$single['single_unit'].'';}
                            }else{
                                if($single['single_finished']<>0){
                                    if($single['single_result']==1){$result='Pass';}else{$result='<center><img class="attachment" src="img/warning.png" width="25"  > Fail <img class="attachment" src="img/warning.png" width="25"  ></center>';}
                                    echo' Result: '.$result.'';
                                }else{

                                }
                            }
                            if(!empty($single['single_notes'])and $single['single_notes']<>''){
                                echo' <span class="glyphicon glyphicon-info-sign popover__title"></span>';
                            }
                            echo'</div>';
                            echo'<div class="row">';
                            if(empty($single['single_yesno'])and !empty($single['single_pass'])){
                                if($single['single_pass']==-1){echo'<center><img class="attachment" src="img/warning.png" width="25"  > Fail <img class="attachment" src="img/warning.png" width="25"  ></center>';}else{echo'Pass';}
                            }
                            echo'</div>';
                            
                            echo'</div>';
                            echo'<form method="POST" id="single-'.$single['single_id'].'">';
                            
                            echo'<div class="col-sm-12">';
                                
                            echo'</div>';

                            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            echo'<div class="col-sm-2"><button type="submit" name="delete_single_result" value="Delete" class="form-control" >
                            <span class="glyphicon glyphicon-trash" ></span></button></div>';
                            echo'<div class="col-sm-2"><button type="submit" name="copy_single_result" value="Copy" class="form-control" >
                            <span class="glyphicon glyphicon-plus" ></span></button></div>';


                            echo '<input type="hidden"  name="test_id" value="'.$test['test_id'].'">';
                            echo '<input type="hidden"  name="product_test" value="'.$test['test_product'].'">';
                            echo '<input type="hidden"  name="MIS_test" value="'.$test['test_jobnumber'].'">';
                            echo' <input type="hidden" name="single_id" value="'.$single['single_id'].'">';
                            echo'</form>';
                        echo'</div>';
                    }
                    echo'<div class="col-sm-12"><div class="col-sm-6 test-single-tobedone';
                        //if ($_POST['single_id']=='add-single'){echo' test-selected ';}
                        echo'" onClick="document.forms[\'add-single-'.$test['test_id'].'\'].submit();" style="min-height: 30px;">';
                        echo'<form method="POST" id="add-single-'.$test['test_id'].'">';
                        echo'Add Single Test';
                        echo '<input type="hidden"  name="test_id" value="'.$test['test_id'].'">';
                        echo '<input type="hidden"  name="product_test" value="'.$test['test_product'].'">';
                        echo '<input type="hidden"  name="MIS_test" value="'.$test['test_jobnumber'].'">';
                        echo' <input type="hidden" name="single_id" value="add-single">';
                        echo'</form>';
                    echo'</div></div>';
                    echo'</div>';


                echo'</div>';

            $last_date=date('jS F Y',$test['test_timetag']);
            }

            
        }
        
        echo'</div>';
        if(!empty($_POST['single_id']) & $_POST['single_id']<>'add-single' ){
            //load a single
            $single=load_single($db,$_POST['single_id']);
            echo'<form method="POST" >';
            echo'<br><div class="col-sm-3 product-link">';
                echo '<div class="row"><b>'.$single['single_name'];
                if (!empty($single['single_cabledetails'])){
                    echo' - '.$single['single_cabledetails'];
                }
                
                echo'</b></div>';
                
                echo '<div class="row"><i>'.$single['single_description'].'</i></div>';
                echo '<div class="row"><i>'.$single['single_notes_initial'].'</i></div>';
                if($single['single_finished']==1){
                    echo '<div class="row"><small><i>'.$single['single_tested_by'].' at '.date("G:i - jS F Y",$single['single_timetag']).'</i></small></div>';
                }
                echo '<div class="row">';
                if ($single['single_minimum']<>0){
                    echo'Min: '.round($single['single_minimum'],4).' '.$single['single_unit'];
                    if ($single['single_maximum']<>0){echo' - ';}
                }
                if ($single['single_maximum']<>0){
                    echo'Max: '.round($single['single_maximum'],4).' '.$single['single_unit'];
                }
                echo'</div>';


            if($single['single_finished']==1 and empty($_POST['edit_single_result'])){
                echo '<b>';
                if(empty($single['single_yesno'])){
                    if($single['single_finished']<>0){echo'<div class="row"> Result: '.round($single['single_result'],4).' '.$single['single_unit'].'</div>';}
                }else{
                    if($single['single_finished']<>0){
                        if($single['single_result']==1){$result='Pass';}else{$result='Fail';}
                        echo'<div class="row"> Result: '.$result.'</div>';
                    }else{

                    }
                    
                    
                }
                echo'</b>';
                if (!empty($single['single_notes'])and $single['single_notes']<>''){echo '<div class="row"><i>Notes : '.$single['single_notes'].'</i></div>';}
                
                echo '<div class="row"><br>';
                    echo' <input class="form-control" type="submit"  name="edit_single_result" value="Edit">';
                echo'</div>';
            
            }else{
                echo '<div class="row">';
                    if(empty($single['single_yesno'])){
                        echo' <input class="form-control" type="text"  name="single_result" placeholder="Result" ';
                        if (!empty($single['single_result'])){echo' value="'.round($single['single_result'],4).'"';}
                        echo'>';
                    }else{
                        echo'<div class="row"><div class="col-sm-1"><input class="form-check-input" type="radio" id="Fail" name="single_result" value="-1" ';
                        if($single['single_result']==-1){echo'checked';}
                        echo'></div>';
                        echo'<div class="col-sm-3"><label for="Fail">Fail</label></div>';
                        echo'<div class="col-sm-1"><input class="form-check-input" type="radio" id="Pass" name="single_result" value="1" ';
                        if($single['single_result']==1){echo'checked';}
                        echo'></div>';
                        echo'<div class="col-sm-3"><label for="Pass">Pass</label></div></div>';
                        
                    }
                echo'</div>';
                echo '<div class="row">';
                    echo' <input class="form-control" type="text"  name="single_notes" placeholder="Notes" ';
                    if (!empty($single['single_notes'])and $single['single_notes']<>''){echo' value="'.$single['single_notes'].'"';}
                    echo'>';
                echo'</div>';
                echo '<div class="row"><br>';
                    echo' <input class="form-control" type="submit"  name="save_single_result" value="Save">';
                echo'</div>';
                
            }

            
            
                
                
               
                
                   
                
                
                echo '<div class="row">';
                    //echo' <br><input class="form-control" type="submit"  name="delete_single_result" value="Delete">';

                  //  echo'<div class="col-sm-4"><button type="submit" name="delete_single_result" value="Delete" class="form-control" >
                  //  <span class="glyphicon glyphicon-trash" ></span></button></div>';
                    echo'<div class="col-sm-4">';
                        //echo'<button type="submit" name="copy_single_result" value="Copy" class="form-control" >
                        //<span class="glyphicon glyphicon-trsdash" >Copy</span></button>';
                    echo'</div>';
                   // echo'<div class="col-sm-4"><button type="submit" name="cancel_single_result" value="Cancel" class="form-control" >
                  //  <span class="glyphicon glyphicon-remove" ></span></button></div>';
                   
                    
                echo'</div>';
                //echo '<div class="row">'.$single['single_name'].'</div>';
            echo '<input type="hidden"  name="test_id" value="'.$_POST['test_id'].'">';
            echo '<input type="hidden"  name="product_test" value="'.$_POST['product_test'].'">';
            echo '<input type="hidden"  name="single_id" value="'.$_POST['single_id'].'">';
            echo '<input type="hidden"  name="MIS_test" value="'.$_POST['MIS_test'].'">';
            echo'</div>';
            echo'</form>';
        }if(!empty($_POST['single_id']) & $_POST['single_id']=='add-single'){
            
            ////AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
            load_single_details($db);
            ////AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
            // show($_POST);           
            echo'<form method="POST" >';
            echo '<input type="hidden"  name="test_id" value="'.$_POST['test_id'].'">';
            echo '<input type="hidden"  name="product_test" value="'.$_POST['product_test'].'">';
            echo '<input type="hidden"  name="MIS_test" value="'.$_POST['MIS_test'].'">';
            echo '<input type="hidden"  name="single_id" value="'.$_POST['single_id'].'">';
            echo'<div class="col-sm-3 product-link">';
                echo '<div class="row"><b>Add Single Test</b></div>';
                echo '<input type="hidden"  name="single_name" value="'.$_POST['single_name'].'">';
                echo'<select name=single_name class="form-control"  onChange=\'submit();\' ';
                if(!empty($_POST['single_name'])){echo 'disabled';}else{echo '';}
                echo'>';
                if(empty($_POST['single_name'])){echo'<option selected>Add a Single Test</option>';}
                echo'<option disabled>_________</option>';
                foreach(load_copy_single($db) as $single){
                    echo'<option ';
                    if(!empty($_POST['single_name'])&$_POST['single_name']==$single['single_name']){
                        echo'selected';
                        $yesno=$single['single_yesno'];
                    }
                    echo' value="'.$single['single_id'].'">'.$single['single_name'];
                    if(!empty($single['single_cabledetails'])){echo' - '.$single['single_cabledetails'];}
                    echo'</option>';
                }
                echo'<option disabled>_________</option>';


                foreach(load_type_single($db) as $single){
                    echo'<option ';
                    if(!empty($_POST['single_name'])&$_POST['single_name']==$single['basesingle_name']){
                        echo'selected';
                        $yesno=$single['basesingle_yesno'];
                    }
                    echo'>'.$single['basesingle_name'].'</option>';
                }
                echo'</select>';
                if(!empty($_POST['single_name'])){
                   
                    echo '<input type="hidden"  name="single_description" value="'.$_POST['single_description'].'">';
                    echo '<input type="hidden"  name="single_yesno" value="'.$_POST['single_yesno'].'">';
                    echo' <input class="form-control" type="text" name="Cable" placeholder="Cable" ';
                    if(!empty($_POST['Cable'])){echo 'value="'.$_POST['Cable'].'"';}
                    echo'>';
                    if(empty($yesno)){
                        echo' <input class="form-control" type="text" name="min" placeholder="Miniumum" ';
                        if(!empty($_POST['min'])){echo 'value="'.round($_POST['min'],4).'"';}
                        echo'>';
                        echo' <input class="form-control" type="text" name="max" placeholder="Maximum" ';
                        if(!empty($_POST['max'])){echo 'value="'.round($_POST['max'],4).'"';}
                        echo'>';
                    }
                    echo' <input class="form-control" type="text" name="note" placeholder="Notes" ';
                    if(!empty($_POST['note'])){echo 'value="'.$_POST['note'].'"';}
                    echo'>';
                    echo' <input class="form-control" type="submit" name="type" value="Add Single Test">';
                    echo '<div class="row"><br>';
                    echo' <input class="form-control" type="submit"  name="cancel" value="Cancel">';
                echo'</div>';
                }     
                
                
                
            echo'</div>';
            echo'</form>';
        }
        
        
    echo'</div>';
}



function view_general_menu($db){
    if(!empty($_POST['show_dashboard'])){
        show_dashboard_metrology($db);
    }elseif(!empty($_POST['show_all_test'])){
        show_all_test($db);
    }else{
       

    
    echo'<div class="row ">';
        echo'<div class="col-sm-4 main-box">';
            echo'<div class="row ">';
                echo'<div class="col-sm-8 ">';
                echo '<h3>'.count_test_done_today($db).' Tests done </h3>';
                echo''.$_POST['date_to_show'].'<br>';
                show_tests_done_today($db);
                echo'</div>';
            echo'</div>';  
        echo'</div>';
        echo'<div class="col-sm-4 main-box">';
            echo'<div class="row ">';
                echo'<div class="col-sm-3 ">';
                    echo'<form method="POST">';
                    echo'<br><button type="submit" name="change_day" value="-1"  class="btn btn-default" >
                                    <span class="glyphicon glyphicon-step-backward" > </span>
                        </button><br>&nbsp';
                echo'</div>';
                echo'<div class="col-sm-6 ">';
                    echo'<h3>Product Made';
                    
                    if($_POST['date_to_show']==date('jS F Y',time())){echo' Today';}
                    echo'</h3>';  
                    echo''.$_POST['date_to_show'];
                    
                    
                echo'</div>';
                echo'<div class="col-sm-3 ">';
                    
                    echo'<br><button type="submit" name="change_day" value="1"  class="btn btn-default" >
                                    <span class="glyphicon glyphicon-step-forward" > </span>
                        </button><br>&nbsp';
                    echo '<input type="hidden"  name="date_to_show" value="'.$_POST['date_to_show'].'">'; 
                    echo'</form>';     
                echo'</div>';
                
                
            echo'</div>';
            echo'<div class="row ">';
                show_tests_to_be_done($db);
            echo'</div>';
                 
        echo'</div>';
        echo'<div class="col-sm-4 main-box">';
            
            echo'<div class="row ">';
                echo'<h3>Detailled '.count_single_test_done_today($db).' Single Tests </h3>'; 
                show_last_50_tests($db);  
            echo'</div>';
            
              
        echo'</div>';
        
    echo'</div>';
    }
}


function get_all_product_metro($db){
    $query='SELECT Product_Code
    FROM List_Document
    LEFT JOIN metro_assign on assign_productcode=Product_Code
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
    AND(WorkArea<>\'Cutting\' AND WorkArea<>\'Bolt\' AND WorkArea<>\'Other\')
    
    AND assign_productcode is NULL

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

function get_all_product_newtest($db){
    $query='SELECT Product_Code as Code,WorkArea
    FROM List_Document
    LEFT JOIN
        (SELECT  max([ManufactureIssueNumber]) as MIS
        ,[Code] as TheCode
        
    FROM [barcode].[dbo].[MIS_List]
    group by  [Code]
        ) as temp
    ON
    temp.TheCode=Product_Code  
    Where (PRODUCT_FAMILY=\'CAW/CCW\' or
    PRODUCT_FAMILY=\'HSC/ILC/MCB\' or
    PRODUCT_FAMILY=\'MTRS\' or
    PRODUCT_FAMILY=\'MUCI\' or
    PRODUCT_FAMILY=\'OVERHEAD\' or
    PRODUCT_FAMILY=\'PFV\' or
    PRODUCT_FAMILY=\'PHSR\' or
    PRODUCT_FAMILY=\'Other\' or
    PRODUCT_FAMILY=\'Piranha\' or
    PRODUCT_FAMILY=\'Piranha/MUCI\' or
    PRODUCT_FAMILY=\'SOLAR\' or
    PRODUCT_FAMILY=\'TTD/NDT\' or
    PRODUCT_FAMILY=\'UNSPECIFIED\' )
    

    order by WorkArea ASC,Product_Code ASC
    ';

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();
    //
    return $row;
}



function load_template($db,$template_id){
    $query='SELECT *
    FROM metro_default
    WHERE default_name=\''.$template_id.'\'';

    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();
    
    
    $template['name']=$row['default_name'];
    $template['id']=$row['default_id'];
    $_POST['template_id']=$template['id'];
    return $template;
}

function load_all_template($db){
    $query='SELECT *
    FROM metro_default
    order by default_name asc';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetchall();
 
  return $row;
}

function load_single_details($db){
    //$_POST['single_name']

    if(empty($_POST['single_id'])){$single_id=$_POST['single_name'];}else{$single_id=$_POST['single_id'];}
    $query='SELECT *
    FROM metro_single
    Where single_id=\''.$single_id.'\'
    ';

    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();
    //show($query);
    if (empty($row)){
        return;
    }else{
        $_POST['single_description']=$row['single_description'];
        $_POST['single_yesno']=$row['single_yesno'];
        $_POST['Cable']=$row['single_cabledetails'];
        $_POST['min']=$row['single_minimum'];
        $_POST['max']=$row['single_maximum'];
        $_POST['note']=$row['single_notes_initial'];
        $_POST['single_name']=$row['single_name'];
    }

}

function load_type_single($db){
    $query='SELECT *
    FROM metro_basesingle
    order by basesingle_yesno asc, basesingle_id asc';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetchall();

  return $row;
}

function load_copy_single($db){
    $query='SELECT *
    FROM metro_single
    Where single_testid=\''.$_POST['test_id'].'\'
    ';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetchall();

  return $row;
}

function load_all_single_template($db,$template_id){
    $query='SELECT *
    FROM metro_defaultdetails
    left join metro_basesingle on basesingle_id=defaultdetails_basesingleid
    
    WHERE defaultdetails_defaultid='.$template_id.'
    order by defaultdetails_basesingleid asc';
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetchall();
   
    return $row;

}

function can_delete_template($db,$template_id){
    $count=0;
    
    $query='SELECT count(defaultdetails_id) as countdetails 
    FROM metro_defaultdetails
    WHERE defaultdetails_defaultid='.$template_id.'
    ';
    
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();
   $count=$row[0];

   $query='SELECT count(assign_productcode) as countassign 
    FROM metro_assign
    WHERE assign_defaultid='.$template_id.'
    ';
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();
   $count=$count+$row[0];
   
    return $count;
}

function load_all_product_metro($db,$template_id){
    $query='SELECT *
    FROM metro_assign
    WHERE assign_defaultid='.$template_id.'
    order by assign_productcode asc';
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetchall();
   
    return $row;

}


function add_template ($db){
    $query="INSERT INTO dbo.metro_default
	( default_name
	) 
	VALUES (
	'".$_POST['template_name']."')";	
	
	
	$sql = $db->prepare($query); 
    //show($query);
	$sql->execute();
}

function get_id($db,$name){
    $query='SELECT basesingle_id,basesingle_unit
    FROM metro_basesingle
    WHERE basesingle_name=\''.$name.'\' ';
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();
   
    return $row;
}

function add_single_template($db){
    
    $defaultid=$_POST['template_id'];
    $basesingleid=get_id($db,$_POST['single_name'])[0];
    $unit="'".get_id($db,$_POST['single_name'])[1]."'";
    $cabledetail=$_POST['Cable'];
    if(empty($_POST['min'])){$minimum='NULL';}else{$minimum=$_POST['min'];}
    if(empty($_POST['max'])){$maximum='NULL';}else{$maximum=$_POST['max'];}
    if(empty($_POST['note'])){$notes='NULL';}else{$notes="'".$_POST['note']."'";}
    
    if($_POST['type']=='Edit Single Test'){
        $query="DELETE from dbo.metro_defaultdetails
        WHERE defaultdetails_id='".$_POST['defaultdetails_id']."'
        ";	
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
    }
    
    $query="INSERT INTO dbo.metro_defaultdetails
	( defaultdetails_defaultid,
    defaultdetails_basesingleid,
    defaultdetails_cabledetails,
    defaultdetails_minimum,
    defaultdetails_maximum,
    defaultdetails_notes,
    defaultdetails_unit
	) 
	VALUES (
	'".$defaultid."',
    '".$basesingleid."',
    '".$cabledetail."',
    ".$minimum.",
    ".$maximum.",
    ".$notes.",
    ".$unit."
    
    )";	
	
	
	$sql = $db->prepare($query); 
    //show($query);
	$sql->execute();
}

function add_single_in_test($db){

    $countsingle=get_count_single_test($db,$_POST['test_id']);

    $single_id=$_POST['test_id'].'-'.sprintf("%02d", $countsingle+1);
    $info_basesingle=get_basesingle_info($db,$_POST['single_name']);

    $single_name=$_POST['single_name'];
    $single_description=$info_basesingle['basesingle_description'];
    $single_yesno=$info_basesingle['basesingle_yesno'];
    //if(empty($single_yesno)){$single_yesno='NULL';}else{$single_yesno=$single_yesno;}
    $test_id=$_POST['test_id'];
    $basesingleid=get_id($db,$_POST['single_name'])[0];
    $unit="'".get_id($db,$_POST['single_name'])[1]."'";
    $cabledetail=$_POST['Cable'];
    if(empty($_POST['min'])){$minimum='NULL';}else{$minimum=$_POST['min'];}
    if(empty($_POST['max'])){$maximum='NULL';}else{$maximum=$_POST['max'];}
    if(empty($_POST['note'])){$notes='NULL';}else{$notes="'".$_POST['note']."'";}
    
    
    
    $query="INSERT INTO dbo.metro_single
	( 
    single_id,
    single_testid,
    single_name, 
    single_description, 
    single_cabledetails,
    single_minimum,
    single_maximum,
    single_notes_initial,
    single_yesno,
    single_unit
	) 
	VALUES (
    '".$single_id."',
    '".$test_id."',	
    '".$single_name."',
    '".$single_description."',
    '".$cabledetail."',
    ".$minimum.",
    ".$maximum.",
    ".$notes.",
    '".$single_yesno."',
    ".$unit."
    
    )";	


    

	
	
	$sql = $db->prepare($query); 
   // show($query);
	$sql->execute();
}

function delete_single_template($db){
    $query="DELETE from metro_defaultdetails
    WHERE defaultdetails_id='".$_POST['defaultdetails_id']."'";	
	
	
	$sql = $db->prepare($query); 
    //show($query);
	$sql->execute();
}


function delete_template($db){
    $query="DELETE from metro_default
    WHERE default_id='".$_POST['template_id']."'";	
	
	
	$sql = $db->prepare($query); 
    //show($query);
	$sql->execute();
}

function delete_product_template($db){
    $query="DELETE from metro_assign
    WHERE assign_defaultid='".$_POST['template_id']."' 
    AND assign_productcode='".$_POST['product_code']."'";	
	
	
	$sql = $db->prepare($query); 
    //show($query);
	$sql->execute();
}

function add_product_template($db){
    
    $query='SELECT Product_Code
    FROM List_Document
    LEFT JOIN metro_assign on assign_productcode=Product_Code
    Where assign_productcode is NULL
    and Product_Code=\''.$_POST['list_product'].'\'
    ';
    $sql = $db->prepare($query); 
    $sql->execute();
    $check=$sql->fetch();
    if(!empty($check)){
        $query="INSERT INTO dbo.metro_assign
        ( assign_productcode,assign_defaultid
        ) 
        VALUES (
        '".$_POST['list_product']."',
        '".$_POST['template_id']."')";	
        
        
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
    }
    
    
}


function get_job_of_today($db){
    $today=(date('Y-m-d',time()))	;
    $query='SELECT MIS_List.WorkArea,
    Code,
    scan_jobnumber, 
    sum(scan_time_distributed)as timetotal,
    assign_defaultid
    FROM dbo.scan 
    LEFT JOIN
    dbo.operator
    ON
    scan_operatorcode=operator_code
    LEFT JOIN
    dbo.MIS_List
    ON
    scan_jobnumber=ManufactureIssueNumber
    LEFT JOIN
    dbo.metro_assign
    ON
    assign_productcode=Code 
        
    WHERE 
    scan_statut=\'start\'
    and scan_time_distributed>60
    AND scan_date=\''.$today.'\'
    and Code not like \'%PRINTING%\'
    
    GROUP BY scan_jobnumber,MIS_List.WorkArea,Code,assign_defaultid
    
    order by MIS_List.WorkArea asc, Code asc
    ';
    
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();
    return $row;
}

function get_MIS_of_today($db,$code){
    $today=(date('Y-m-d',time()))	;
    $threedaysago=(date('Y-m-d',strtotime($today.' -3days')));
    $query='SELECT 
    scan_jobnumber
    FROM dbo.scan 
    LEFT JOIN
    dbo.operator
    ON
    scan_operatorcode=operator_code
    LEFT JOIN
    dbo.MIS_List
    ON
    scan_jobnumber=ManufactureIssueNumber
    LEFT JOIN
    dbo.metro_assign
    ON
    assign_productcode=Code 
        
    WHERE 
    scan_statut=\'start\'
    and scan_time_distributed>60
    AND scan_date<=\''.$today.'\'
    AND scan_date>=\''.$threedaysago.'\'
    and Code =\''.$code.'\'
    
    GROUP BY scan_jobnumber,MIS_List.WorkArea,Code,assign_defaultid
    ORDER BY scan_jobnumber DESC
    ';
    
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();
    return $row;
}

function get_count_test_today($db){
    $today=(date('Y-m-d',time()))	;
    $query='SELECT 
    max(RIGHT(test_id,3)+1-1)
    FROM dbo.metro_test
            
    WHERE 
    test_date=\''.$today.'\'
    ';
    
    $sql = $db->prepare($query); 
    $sql->execute();

    $row=$sql->fetch();
    return $row[0];
}

function get_count_single_test($db,$testid){
    $query='SELECT 
    max(RIGHT(single_id,2)+1-1),count(single_id)
    FROM dbo.metro_single
            
    WHERE 
    single_testid=\''.$testid.'\'
    ';
    
    $sql = $db->prepare($query); 
    $sql->execute();
    
    $row=$sql->fetch();
    
    
    return $row[0];
}

function get_template_complete($db,$code){
   
    $query='SELECT *
	  
    FROM [barcode].[dbo].[metro_assign]
    inner join metro_default
        on default_id=assign_defaultid
    inner join metro_defaultdetails
        on defaultdetails_defaultid=assign_defaultid
        left join metro_basesingle
        on basesingle_id=defaultdetails_basesingleid
        where assign_productcode=\''.$code.'\'
    ';
    
    $sql = $db->prepare($query); 
    $sql->execute();

    $row=$sql->fetchall();
    return $row;
}



function get_basesingle_info($db,$name){
   
    $query='SELECT *
	  
    FROM [barcode].[dbo].[metro_basesingle]
   
        where basesingle_name=\''.$name.'\'
    ';
    
    $sql = $db->prepare($query); 
    $sql->execute();
    //show($query);
    $row=$sql->fetch();
    return $row;
}

function initiate_new_test($db){
    
    //make Testnumber
    $testnumber=(date('ymd',time())).'-'.sprintf("%03d", get_count_test_today($db)+1);
    $_POST['test_number']=$testnumber;
    
    //Create test
        create_test($db);
    //create all single based on template
        create_single_from_template($db);


}

function create_test($db){
    
    $query="INSERT INTO dbo.metro_test
	( test_id,
    test_date,
    test_timetag,
    test_product,
    test_jobnumber,
    test_shift,
    test_created_by
	) 
	VALUES (
	'".$_POST['test_number']."',
    '".date('Y-m-d',time())."',
    '".time()."',
    '".$_POST['product_test']."',
    '".$_POST['MIS_test']."',
    '".$_POST['Shift']."',
    '".$_SESSION['temp']['id']."')";	
	
	
	$sql = $db->prepare($query); 
    //show($query);
	$sql->execute();
    

}

function create_single_from_template($db){
    

    //load template
    $template=get_template_complete($db,$_POST['product_test']);
    //show($template);
    //for each single of template, insert in single_test

    $countsingle-get_count_single_test($db,$_POST['test_number']);

    //show($template);
    foreach($template as $one_template){
        $testsingle=$_POST['test_number'].'-'.sprintf("%02d", $countsingle+1);
        if(empty($one_template['defaultdetails_minimum'])){$minimum="NULL";}else{$minimum="'".$one_template['defaultdetails_minimum']."'";}
        if(empty($one_template['defaultdetails_maximum'])){$maximum="NULL";}else{$maximum="'".$one_template['defaultdetails_maximum']."'";}
        $query="INSERT INTO dbo.metro_single
        ( single_id,
        single_testid,
        single_name,
        single_description,
        single_cabledetails,
        single_minimum,
        single_maximum,
        single_notes_initial,
        single_yesno,
        single_unit
        ) 
        VALUES (
        '".$testsingle."',
        '".$_POST['test_number']."',
        '".$one_template['basesingle_name']."',
        '".$one_template['basesingle_description']."',
        '".$one_template['defaultdetails_cabledetails']."',
        ".$minimum.",
        ".$maximum.",
        '".$one_template['defaultdetails_notes']."',
        '".$one_template['basesingle_yesno']."',
        '".$one_template['defaultdetails_unit']."')";	
        
        
        $sql = $db->prepare($query); 
       //show($query);
        $sql->execute();
        $countsingle=$countsingle+1;
    }


   
    

}

function load_all_test($db){
    $query='SELECT TOP 10 *
    FROM metro_test
    LEFT JOIN
    (SELECT count(single_id) as thecount, single_testid  FROM dbo.metro_single where single_finished=1 GROUP BY single_testid)as countsingle
    ON
    countsingle.single_testid=test_id


    where test_product=\''.$_POST['product_test'].'\'
   
    order by test_id desc';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetchall();
 
  return $row;
}

function load_all_single($db,$testid){
    $query='SELECT *
    FROM metro_single
    where single_testid=\''.$testid.'\'
   
    order by single_name desc,single_cabledetails desc';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetchall();
 
  return $row;
}

function load_single($db,$single_id){
    $query='SELECT *
    FROM metro_single
    where single_id=\''.$single_id.'\'
   
    ';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetch();
  
  return $row;

}

function save_single_test_result($db){
    $query='UPDATE dbo.metro_single SET 
		single_result=\''.round($_POST['single_result'],3).'\',
        single_notes=\''.$_POST['single_notes'].'\',
		single_finished=\'1\',
        single_timetag=\''.time().'\',
        single_tested_by=\''.$_SESSION['temp']['id'].'\'
		
		WHERE single_id=\''.$_POST['single_id'].'\'';
        
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
}

function copy_single_test_result($db){
    load_single_details($db);
    add_single_in_test($db);
}


function delete_test($db){
    $query="DELETE from metro_test
    WHERE test_id='".$_POST['test_id']."'";	
	
	
	$sql = $db->prepare($query); 
    //show($query);
	$sql->execute();
    $query="DELETE from metro_single
    WHERE single_testid='".$_POST['test_id']."'";	
	
	
	$sql = $db->prepare($query); 
    //show($query);
	$sql->execute();
}

function delete_single_result($db){
    $query="DELETE from metro_single
    WHERE single_id='".$_POST['single_id']."'";	
	
	
	$sql = $db->prepare($query); 
    //show($query);
	$sql->execute();
}

function show_last_50_tests($db){
    $today=(date('Y-m-d',strtotime($_POST['date_to_show'])))	;
   
   $query='SELECT TOP 300 *
    FROM metro_single
    left join metro_test on test_id=single_testid
    where    single_finished=1 and test_date=\''.$today.'\'
    order by single_timetag desc,single_name desc,single_cabledetails desc';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  
  $alltest=$sql->fetchall();
 
  foreach($alltest as $single){
    if( $last_date<>date('jS F Y',$single['single_timetag'])){
        echo'<div class="row "><b>';
        if( date('Y-m-d',$single['single_timetag'])==date('Y-m-d',time())){
            echo 'Today : ';
        }
        echo date('jS F Y',$single['single_timetag']);
        echo'</b></div>';
    }

    echo'<form method="POST" id="last25-product-'.$single['test_product'].'" >';
    echo' <input type="hidden" name="product_test" value="'.$single['test_product'].'">';
    echo'</form>';

    echo'<div class="row " onClick="document.forms[\'last25-product-'.$single['test_product'].'\'].submit();" >';
    echo'<div class="col-sm-1 ">';
    if(($single['single_pass']==-1)  ){
        // echo '<input type="hidden"  name="job_number" value="'.$line['scan_jobnumber'].'">';
         echo'<center><img class="attachment" src="img/warning.png" width="25"  ></center>';
        
     }
    echo'</div>';
    echo '<div class="col-sm-1 ">'.date("G:i",$single['single_timetag']).'</div>';
    
    echo'<div class="col-sm-8 ">'.$single['test_product'].' - '.$single['single_name'].'';
    if(!empty($single['single_cabledetails'])){echo ' - '.$single['single_cabledetails'];}
    echo'</div>';
    echo '<div class="col-sm-2 ">';
   
    
    if($single['single_yesno']==0){echo round($single['single_result'],3).' '.$single['single_unit'];}else{
        if($single['single_result']==1){
            echo 'Pass';
        }else{
            echo 'Fail';
        }
    }
    echo'</div>';
    
    echo'</div>';
    $last_date=date('jS F Y',$single['single_timetag']);
  }
  
}

function count_test_done_today($db){
    $today=(date('Y-m-d',time()))	;
    $today=(date('Y-m-d',strtotime($_POST['date_to_show'])))	;
    $query='SELECT count(distinct test_id) as total_test
    FROM metro_single
    left join metro_test on test_id=single_testid
    where    single_finished=1 and test_date=\''.$today.'\'
    ';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetch();
 
  return $row[0];
  
}

function count_single_test_done_today($db){
    $today=(date('Y-m-d',time()))	;
    $today=(date('Y-m-d',strtotime($_POST['date_to_show'])))	;
    $query='SELECT count(distinct single_id) as total_test
    FROM metro_single
    left join metro_test on test_id=single_testid
    where    single_finished=1 and test_date=\''.$today.'\'
    ';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetch();
 
  return $row[0];
  
}

function show_tests_to_be_done($db){
    $today2=(date('Y-m-d',time()))	;
    $today=(date('Y-m-d',strtotime($_POST['date_to_show'])))	;
    $query='SELECT MIS_List.WorkArea,
    Code,
     
    sum(scan_time_distributed)as timetotal,
    assign_defaultid,
	max(temp.test_id) as test_id,
	max(temp2.test_id) as test_id_not_finished,
    count(DISTINCT temp.test_id) as count_test_id,
    iif(assign_defaultid<>0,1,0)as thetest
    FROM dbo.scan 
    LEFT JOIN
    dbo.operator
    ON
    scan_operatorcode=operator_code
    LEFT JOIN
    dbo.MIS_List
    ON
    scan_jobnumber=ManufactureIssueNumber
    LEFT JOIN
    dbo.metro_assign
    ON
    assign_productcode=Code
    LEFT JOIN
    (SELECT test_id,test_product,test_date FROM dbo.metro_test WHERE test_finished=1) as temp
    ON
    assign_productcode=temp.test_product and temp.test_date=scan_date
    LEFT JOIN
    (SELECT test_id,test_product,test_date FROM dbo.metro_test WHERE test_finished is NULL) as temp2
    ON
    assign_productcode=temp2.test_product and temp2.test_date=scan_date
        
    WHERE 
    scan_statut=\'start\'
    and scan_time_distributed>60
    AND scan_date=\''.$today.'\'
    
    and Code not like \'%PRINTING%\'
    
    GROUP BY MIS_List.WorkArea,Code,assign_defaultid
    
    order by thetest desc,MIS_List.WorkArea asc, Code asc
    ';
    
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $allproduct=$sql->fetchall();
    $oldworkarea='';
    $checkdefault=0;
    foreach($allproduct as $product){
        if($oldworkarea<>$product['WorkArea'] and !empty($product['assign_defaultid']) ){
            echo '<div class="row " > <b>';
            echo $product['WorkArea'];
            echo'</b></div>';
        }elseif($checkdefault==0 and empty($product['assign_defaultid'] )){
            echo '<div class="row "><b>No Test Required </b></div>';
            $checkdefault=1;
        }
        echo '<div class="row " onClick="document.forms[\'product-'.$product['Code'].'\'].submit();"  >';
            echo '<div class="col-sm-9 ">';
            echo $product['Code'];
            if(!empty($product['count_test_id'])){echo' ('.$product['count_test_id'].')';}
            echo'</div>';
            echo '<div class="col-sm-3 ">';
            if(!empty($product['test_id'])  ){
               // echo '<input type="hidden"  name="job_number" value="'.$line['scan_jobnumber'].'">';
                echo'<center><img class="attachment" src="img/ok.png" width="25"  ></center>';
               
            }elseif(!empty($product['test_id_not_finished'])  ){
                // echo '<input type="hidden"  name="job_number" value="'.$line['scan_jobnumber'].'">';
                 echo'<center><img class="attachment" src="img/warning.png" width="25"  ></center>';
                
             }else{
                echo '<input type="hidden"  name="job_number" value="'.$line['scan_jobnumber'].'">';
                echo'<center><span class="glyphicon glyphicon-unchecked"  ></span></center>';
            }
            echo'<form method="POST" id="product-'.$product['Code'].'" >';
            echo' <input type="hidden" name="product_test" value="'.$product['Code'].'">';
            echo'</form>';

            echo'</div>';
        echo'</div>';
        $oldworkarea=$product['WorkArea'];
    }
}

function check_test_finished($db,$test_id){
   
    $query='DECLARE @thecount as int
    SET @thecount=(
    SELECT count( [single_id]) as thecount
          
      FROM [barcode].[dbo].[metro_single]
      WHERE single_testid=\''.$test_id.'\'and single_finished is NULL
      )
     
     IF (@thecount=0)
     Begin
     UPDATE dbo.metro_test SET 
            test_finished=1
            WHERE test_id=\''.$test_id.'\'
     End
     IF (@thecount>0)
     Begin
     UPDATE dbo.metro_test SET 
            test_finished=NULL
            WHERE test_id=\''.$test_id.'\'
     End


    ';
    
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();


    check_test_pass_fail($db,$test_id);

    
    
}

function check_test_pass_fail($db,$test_id){
    
    $query='SELECT *    
      FROM [barcode].[dbo].[metro_single]
      WHERE single_testid=\''.$test_id.'\' ';
    
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $row=$sql->fetchall();
    //show($row);
    foreach($row as $single){
        $pass_fail="NULL";
        $yesno= $single['single_yesno'];
        if($yesno==1){
            $pass_fail="'".round($single['single_result'],0)."'";
        }elseif(!empty($single['single_finished'])){
            if(!empty($single['single_maximum'])){
                if($single['single_result']<=$single['single_maximum']){
                    $pass_fail=1;
                    
                    if(!empty($single['single_minimum'])){
                        if($single['single_result']>=$single['single_minimum']){$pass_fail=1;}else{$pass_fail=-1;}
                    }
                }else{
                    $pass_fail=-1;
                }
            }elseif(!empty($single['single_minimum'])){
                if($single['single_result']>=$single['single_minimum']){$pass_fail=1;}else{$pass_fail=-1;}
            }
        }
        
        $query=' UPDATE dbo.metro_single SET 
        single_pass='.$pass_fail.'
        WHERE single_id=\''.$single['single_id'].'\' ';
        
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
    }
    

   
    
}

function show_tests_done_today($db){
    $today=(date('Y-m-d',time()))	;
    $today=(date('Y-m-d',strtotime($_POST['date_to_show'])))	;
    $query='SELECT test_product as Code,count(distinct test_id) as count_test_id,count(distinct single_id) as count_single_id, max(test_timetag) as max_timetag,sum(test_finished) as sum_finished
    FROM [barcode].[dbo].[metro_test]
    left join metro_single
    on single_testid=test_id
    where test_date=\''.$today.'\'
    group by test_product
    order by max_timetag desc
    
    ';
    
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $allproduct=$sql->fetchall();
    $oldworkarea='';
    $checkdefault=0;
    foreach($allproduct as $product){
        
        echo '<div class="row " onClick="document.forms[\'product-'.$product['Code'].'\'].submit();"  >';
            echo '<div class="col-sm-9 ">';
            echo $product['Code'];
            if(!empty($product['count_test_id'])){echo' ('.$product['count_test_id'].')';}
            echo'</div>';
            echo '<div class="col-sm-3 ">';
            if($product['sum_finished']==$product['count_single_id']  ){
                // echo '<input type="hidden"  name="job_number" value="'.$line['scan_jobnumber'].'">';
                 echo'<center><img class="attachment" src="img/ok.png" width="25"  ></center>';
                
             }else{
                echo '<input type="hidden"  name="job_number" value="'.$line['scan_jobnumber'].'">';
                echo'<center><img class="attachment" src="img/warning.png" width="25"  ></center>';
            }
            echo'<form method="POST" id="product-'.$product['Code'].'" >';
            echo' <input type="hidden" name="product_test" value="'.$product['Code'].'">';
            echo'</form>';

            echo'</div>';
        echo'</div>';
        $oldworkarea=$product['WorkArea'];
    }
}

function prepare_edit_single_template($db){
    $query='SELECT *
    FROM metro_defaultdetails
    left join metro_basesingle on basesingle_id=defaultdetails_basesingleid
    
    WHERE defaultdetails_id='.$_POST['defaultdetails_id'].'';
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();

    $_POST['single_name']=$row['basesingle_name'];
    $_POST['Cable']=$row['defaultdetails_cabledetails'];
    $_POST['min']=$row['defaultdetails_minimum'];
    $_POST['max']=$row['defaultdetails_maximum'];
    $_POST['note']=$row['defaultdetails_notes'];
    echo '<input type="hidden"  name="single_name" value="'.$_POST['single_name'].'">';
    echo '<input type="hidden"  name="defaultdetails_id" value="'.$_POST['defaultdetails_id'].'">';
    
}

function navbar_metrology($db){

	echo'<div class="row">';
		echo'<div class="col-sm-2 ">';
			echo'<form method="POST">';
			
			if(empty($_POST['show_dashboard']) and empty($_POST['show_all_test']) and empty($_POST['product_test']) and empty($_POST['new_test'])){

			echo'<div class="visible-xs-block visible-sm-block visible-md-block">';	
				echo'<br><button type="submit" name="new_test" value="New Test" class="btn btn-default" >
									<span class="glyphicon glyphicon-plus" ></span>
				</button><br>&nbsp';
			echo'</div>';
			echo'<div class="hidden-xs hidden-sm hidden-md">';	
				echo'<br><button type="submit" name="new_test" value="New Test"  class="btn btn-default" >
									<span class="glyphicon glyphicon-plus" > New Test</span>
				</button><br>&nbsp';
			echo'</div>';
			}
			echo'</form>';
		echo'</div>';
		
		echo'<div class="col-sm-2 ">';
            echo'<form method="POST">';
                
            if(empty($_POST['show_dashboard']) and empty($_POST['show_all_test']) and empty($_POST['manage_template']) and empty($_POST['template_name'])){

            echo'<div class="visible-xs-block visible-sm-block visible-md-block">';	
                echo'<br><button type="submit" name="manage_template" value="Template" class="btn btn-default" >
                                    <span class="glyphicon glyphicon-wrench" ></span>
                </button><br>&nbsp';
            echo'</div>';
            echo'<div class="hidden-xs hidden-sm hidden-md">';	
                echo'<br><button type="submit" name="manage_template" value="Template"  class="btn btn-default" >
                                    <span class="glyphicon glyphicon-wrench" > Template</span>
                </button><br>&nbsp';
            echo'</div>';
            }
            echo'</form>';
		echo'</div>';
        echo'<div class="col-sm-2 ">';
        if((empty($_POST['show_all_test']))){
            echo'<div class="col-sm-2 ">';
			echo'<form method="POST">';
			echo'<div class="visible-xs-block visible-sm-block visible-md-block">';	
				echo'<br><button type="submit" name="show_all_test" value="show_all_test" class="btn btn-default" >
				<span class="glyphicon glyphicon-th-list" > </span>
				</button><br>';
			echo'</div>';
			echo'<div class="hidden-xs hidden-sm hidden-md">';	
				echo'<br><button type="submit" name="show_all_test" value="show_all_test" class="btn btn-default" >
				<span class="glyphicon glyphicon-th-list" > All Tests</span>
				</button><br>';
			echo'</div>';
            echo'</form>';
            echo'</div>';  
            }
        
        echo'</div>';
		echo'<div class="col-sm-2 ">';
        if(!(empty($_POST['show_dashboard']))){
            echo'<form method="POST">';
            echo'<br><div class="col-sm-3 ">Start :</div><div class="col-sm-9 "><input class="form-control" type="date" name="dashboard_date_to_show" onChange="submit();"value="'.$_POST['dashboard_date_to_show'].'"></div>';
            echo'<input type="hidden" name="show_dashboard" value="show_dashboard">';   
            }
			
		echo'</div>';
		echo'<div class="col-sm-2 ">';
            if(!(empty($_POST['show_dashboard']))){
            
            echo'<br><div class="col-sm-3 ">End :</div><div class="col-sm-9 "><input class="form-control" type="date" name="dashboard_end_date_to_show" onChange="submit();"value="'.$_POST['dashboard_end_date_to_show'].'"></div>';
            echo'<input type="hidden" name="show_dashboard" value="show_dashboard">';
            echo'</form >';
            }
		echo'</div>';
		
		
		if(!(empty($_POST['show_dashboard']) and empty($_POST['show_all_test']) and empty($_POST['product_test']) and empty($_POST['new_test']) and empty($_POST['manage_template']) and empty($_POST['template_name']))){
			echo'<div class="col-sm-2 ">';
			echo'<form method="POST">';
			echo'<br><button type="submit" name="type" value="return" class="btn btn-default" >
									<span class="glyphicon glyphicon-arrow-up" > Back </span>
									</button><br>';
				echo'</form>';
			echo'</div>';
        }else{
            echo'<div class="col-sm-2 ">';
			echo'<form method="POST">';
			echo'<div class="visible-xs-block visible-sm-block visible-md-block">';	
				echo'<br><button type="submit" name="show_dashboard" value="show_dashboard" class="btn btn-default" >
				<span class="glyphicon glyphicon-signal" > </span>
				</button><br>';
			echo'</div>';
			echo'<div class="hidden-xs hidden-sm hidden-md">';	
				echo'<br><button type="submit" name="show_dashboard" value="show_dashboard" class="btn btn-default" >
				<span class="glyphicon glyphicon-signal" > Dashboard</span>
				</button><br>';
			echo'</div>';
            echo'</form>';
            echo'</div>';
		
        }	
		
		echo'</div>';	
}

function get_top_product_metro($db){
    $filter=do_the_filter_metro($db);
    
    //$filter='';
    $query='SELECT TOP 10 test_product,  sum(thecount) as thecount
    FROM metro_test
    LEFT JOIN
    (SELECT count(single_id) as thecount, single_testid  FROM dbo.metro_single where single_finished=1 GROUP BY single_testid)as countsingle
    ON
    countsingle.single_testid=test_id
    

    WHERE test_date>=\''.$_POST['dashboard_date_to_show'].'\' and test_date<=\''.$_POST['dashboard_end_date_to_show'].'\' 
   group by test_product
   order by thecount desc';
 // show($query);
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetchall();
 
  return $row;
}

function get_type_of_test($db){
    
	$filter=do_the_filter_metro($db);
    
     
     $query='SELECT single_name,count(single_id)as thecount
     FROM metro_test
     LEFT JOIN
     dbo.metro_single  
     ON
     single_testid=test_id
     WHERE test_date>=\''.$_POST['dashboard_date_to_show'].'\' and test_date<=\''.$_POST['dashboard_end_date_to_show'].'\'  and single_finished=1
	 group by single_name
 
 
     
    
    order by thecount desc';
   
   $sql = $db->prepare($query); 
   $sql->execute();
   $row=$sql->fetchall();
  //show($query);
   return $row;
}

function get_test_ratio($db){
    $filter=do_the_filter_metro($db);
	
    
    
    $query='SELECT IIF(single_pass is NULL, \'No Result\', IIF(single_pass =-1, \'Fail\', \'Pass\'))as Result,count(single_id)as thecount
    FROM metro_test
    LEFT JOIN
    dbo.metro_single  
    ON
    single_testid=test_id
    WHERE test_date>=\''.$_POST['dashboard_date_to_show'].'\' and test_date<=\''.$_POST['dashboard_end_date_to_show'].'\'  and single_finished=1
    group by single_pass

   order by thecount desc';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetchall();
 
  return $row;
}

function show_dashboard_metrology($db){
    
    echo'<div class="row ">';
    echo'<div class="col-sm-8 ">';
        echo'<div class="col-sm-3 ">';
        echo'<h4>Top 10 Product :</h4>';
        $data=get_top_product_metro($db);
        tableview($data,$title='Product List',$option="titlePosition:'out',orientation:'vertical',height: 600",$headers=[['string','Product'],['number','Tests']]);
        echo'</div>';
        echo'<div class="col-sm-9 ">';
            echo'<div class="col-sm-6 ">';
            $data=get_type_of_test($db);
            
            piechart($data,$title='Type of Test',"pieSliceText: 'label',pieSliceTextStyle:{fontSize: 8},height: 350");
            echo'</div>';
            echo'<div class="col-sm-6 ">';
            $data=get_test_ratio($db);
            
            piechart($data,$title='Pass/Fail Ratio',"pieSliceText: 'label',pieSliceTextStyle:{fontSize: 8},height: 350");
            echo'</div>';
            echo'<div class="col-sm-12 ">';
           // $data=getMonthview($db);
            
           // stackedchart($data,$title='Trend',' height: 200');
            echo'</div>';
        echo'</div>';
    echo'</div>';
    echo'<div class="col-sm-4 ">';
        echo'<div class="row ">';
            echo'<div class="col-sm-6 ">';
            
           // $data=getallYear($db);
            //tableviewfilter($data,$title='Year');
            echo'</div>';
            echo'<div class="col-sm-6 ">';
            
           // $data=getallMonth($db);
            
            //tableviewfilter($data,$title='Month');
            echo'</div>';
        echo'</div>';
       
    echo'</div>';
 echo'</div>';
}

function get_drawing($db,$product){
    $query='SELECT [Open MDS_File] FROM dbo.List_Document 
		
    WHERE 
    [Product_Code]=\''.$product.'\'	
     ';
    
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetch();
    if(file_exists('/var/www/html/'.str_replace('\\', '/', $row[0]))){
    return $row[0];
    }
    else{
        $row[0]=substr($row[0], 0, -3)."PDF";
        return $row[0];	
    }
}

function show_all_tests($db){

   $filter='';

   $alltobefiltered[]='single_tested_by';
   //$alltobefiltered[]='single_description';
   $alltobefiltered[]='single_name';
   $alltobefiltered[]='single_cabledetails';
   $alltobefiltered[]='iif(single_pass=1,\'PASS\',iif(single_pass=-1,\'FAIL\',\'NULL\'))';
   $alltobefiltered[]='single_notes_initial';
   $alltobefiltered[]='test_product';
   $alltobefiltered[]='test_jobnumber';
   
    $j=0;
    if(!empty($_SESSION['temp']['search'])){
        $filter='AND (';
        foreach($_SESSION['temp']['search'] as $keyword){
            $i=0;
            if($j<>0){ $filter=$filter.' AND ';}
            $filter=$filter.'(';
            foreach($alltobefiltered as $column){
                if($i<>0){ $filter=$filter.' OR ';}
                $filter=$filter.$column.' like \'%'.$keyword.'%\' ';
                $i++;
            }
            $filter=$filter.')';

            $j++;       
        }
        $filter=$filter.')';
    }


   $query='SELECT  *
    FROM metro_single
    left join metro_test on test_id=single_testid
    where    single_finished=1 and test_date>=\''.$_POST['dashboard_date_to_show'].'\' and test_date<=\''.$_POST['dashboard_end_date_to_show'].'\' '.$filter.'
    order by single_timetag desc,single_name desc,single_cabledetails desc';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  //show($query);
  $alltest=$sql->fetchall();
 
  foreach($alltest as $single){
    if( $last_date<>date('jS F Y',$single['single_timetag'])){
        echo'<div class="row "><b>';
        if( date('Y-m-d',$single['single_timetag'])==date('Y-m-d',time())){
            echo 'Today : ';
        }
        echo date('jS F Y',$single['single_timetag']);
        echo'</b></div>';
    }

    echo'<form method="POST" id="last25-product-'.$single['test_product'].'" >';
    echo' <input type="hidden" name="product_test" value="'.$single['test_product'].'">';
    echo'</form>';

    echo'<div class="row  main-box" onClick="document.forms[\'last25-product-'.$single['test_product'].'\'].submit();" >';
       
        echo '<div class="col-sm-1  " style=" font-size:10px;">'.date("Y-m-d G:i",$single['single_timetag']).'</div>';
        echo'<div class="col-sm-5  ">'.$single['test_product'].' - '.$single['single_name'].'';
        if(!empty($single['single_cabledetails'])){echo ' - '.$single['single_cabledetails'];}
        echo'</div>';
        echo '<div class="col-sm-1 ">';
        if($single['single_yesno']==0){echo round($single['single_result'],3).' '.$single['single_unit'];}else{
            if($single['single_result']==1){
                echo 'Pass';
            }else{
                echo 'Fail';
            }
        }
        echo'</div>';
        echo '<div class="col-sm-2 ">'.$single['single_tested_by'].'</div>';
        echo '<div class="col-sm-1">'.$single['test_jobnumber'].'</div>';
        echo'<div class="col-sm-1 ">';
        if(($single['single_pass']==-1)  ){
            // echo '<input type="hidden"  name="job_number" value="'.$line['scan_jobnumber'].'">';
            echo'<center><img class="attachment" src="img/warning.png" width="25"  ></center>';
            
        }
        echo'</div>';
        
    
    echo'</div>';
    $last_date=date('jS F Y',$single['single_timetag']);
  }
  
}

function show_filter_menu($db){
    echo'<div class="row"><b>Filter</b></div>';
    echo'<form method="POST">';
    echo'<div class="row">';
        echo'<div class="col-sm-3">Search</div>';
        echo'<div class="col-sm-5">';
            echo'<input class="form-control" type="text" name="Search_text" placeholder="Search">';
        echo'</div>';
    echo'</div>';
    echo'<div class="row">';
        echo'<div class="col-sm-3">Start</div>';
        echo'<div class="col-sm-5">';
            echo'<input class="form-control" type="date" name="dashboard_date_to_show" value="'.$_POST['dashboard_date_to_show'].'">';
        echo'</div>';
    echo'</div>';
    echo'<div class="row">';
        echo'<div class="col-sm-3">End</div>';
        echo'<div class="col-sm-5">';
            echo'<input class="form-control" type="date" name="dashboard_end_date_to_show" value="'.$_POST['dashboard_end_date_to_show'].'">';
        echo'</div>';
    echo'</div>';
    echo'<div class="row">';
        echo'<div class="col-sm-3"></div>';
        echo'<div class="col-sm-5">';
            echo'<input class="form-control" type="submit" name="Search_input" value="Search">';
        echo'</div>';
    echo'</div>';
    if(!empty($_SESSION['temp']['search'])){
        echo'<div class="row">';
            echo'<div class="col-sm-3"></div>';
            echo'<div class="col-sm-5">Keyword :</div>';
        echo'</div>';
        echo'<div class="row">';
            echo'<div class="col-sm-3"></div>';
            echo'<div class="col-sm-5">';
            
                foreach($_SESSION['temp']['search'] as $keyword){
                    echo'<div class="col-sm-6 keyword">';
                        echo'<div class="col-sm-8 ">';
                            echo $keyword;
                        echo'</div>';
                        echo'<div class="col-sm-4 "><button name="remove-keyword" value="'.$keyword.'" class="remove-keyword">X</button></div>';
                        
                    echo'</div>';
                }
            echo'</div>';
        echo'</div>';
        




        
        echo'<div class="row">';
            echo'<div class="col-sm-3">';
            echo' <center><img class="attachment" src="img/excel.png" width="25"  ></center>';
            echo'</div>';
            echo'<div class="col-sm-5">';
                echo'<input class="form-control" type="submit" name="Clear_search" value="Clear">';
            echo'</div>';
        echo'</div>';

    }
   
   
    echo'<input type="hidden" name="show_all_test" value="show_all_test">';
    echo'</form>';
    echo'<form method="POST">';
    echo'<div class="row"><b>Export</b></div>';
    echo'<div class="row">';
        echo'<div class="col-sm-3"></div>';
        echo'<div class="col-sm-5">';
            echo'<input class="form-control" type="submit" name="export_to_excel" value="Export To Excel">';
        echo'</div>';
    echo'</div>';
    echo'</form>';
    

}

function do_the_filter_metro($db){
    

    $filter="WHERE test_date>='".$_POST['dashboard_date_to_show']."' and test_date<='".$_POST['dashboard_end_date_to_show']."' "; 
    return $filter;
}

function show_all_test($db){
    echo'<div class="row main-box">';
        echo'<div class="col-sm-8  main-box">';
            show_all_tests($db);
        echo'</div>';
        echo'<div class="col-sm-4  main-box">';
            show_filter_menu($db);
            
        echo'</div>';
    echo'</div>';
}


?>