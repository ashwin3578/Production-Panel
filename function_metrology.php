<?php

include('function_dashboard.php');



function managing_POST($db){
    
    
    if($_POST['type']=='Cancel' and !empty($_POST['template_name'])){
		
        unset($_POST['template_id']);
        unset($_POST['single_name']);
        unset($_POST['defaultdetails_id']);
	}
    
    
    if(!empty( $_POST['export_to_excel']) ){
        export_to_excel($db);
        unset($_POST);
    }


    if(!empty($_POST['Shift'])){
        $_SESSION['temp']['Shift']=$_POST['Shift'];
    }

    if($_POST['type']=='Create Template'){
		add_template($db);
	}
	if($_POST['type']=='Add Single Test'){
        if($_POST['single_id']=='add-single'){
            add_single_in_test($db);
            
            $_POST['single_id']='';
            calculate_calculated_test($db,$_POST['test_id']);

            
            
        }else{
            add_single_template($db);
        }
		
	}
    if($_POST['type']=='Edit Single Test'){
        add_single_template($db);
        calculate_calculated_test($db,$_POST['test_id']);
        
		
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
    if(!empty($_POST['initiate_new_test'])and $_POST['product_test']<>'Other Product'){
		initiate_new_test($db);
	}
    if(!empty($_POST['save_single_result'])){
		save_single_test_result($db);
        
        $_POST['single_id']=get_next_single_id($db,$_POST['single_id']);
        calculate_calculated_test($db,$_POST['test_id']);
        check_test_finished($db,$_POST['test_id']);
        show_alert(date('G:h:s',time()).' - Result Saved for Test #'.$_POST['single_id'],'success');
	}

    if(!empty($_POST['copy_single_result'])){
		copy_single_test_result($db);
        
        $_POST['single_id']='';
        calculate_calculated_test($db,$_POST['test_id']);
        check_test_finished($db,$_POST['test_id']);
	}
    if(!empty($_POST['delete_single_result'])){
		delete_single_result($db);
        
        $_POST['single_id']='';
        calculate_calculated_test($db,$_POST['test_id']);
        check_test_finished($db,$_POST['test_id']);
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
       if(!empty($_SESSION['temp']['date_to_show'])){
        $_POST['date_to_show']=$_SESSION['temp']['date_to_show'];
       }else{
        $_POST['date_to_show']=date('jS F Y',time());
       }
    
   
   }
   if(!empty($_POST['change_day'])){
    
    $_POST['date_to_show']=date('jS F Y',strtotime($_POST['date_to_show']." +".$_POST['change_day']." day"));
    //show($_POST['date_to_show']);
    //$_POST['date_to_filter']=date('Y-m-d',time());
   }

   if(empty($_SESSION['temp']['dashboard_end_date_to_show'])){
        $_POST['dashboard_end_date_to_show']=date('Y-m-d',time());
        
        $_POST['dashboard_date_to_show']=date('Y-m-d',strtotime($_POST['dashboard_end_date_to_show'].' -7days'));
        $_SESSION['temp']['dashboard_end_date_to_show']=$_POST['dashboard_end_date_to_show'];
        $_SESSION['temp']['dashboard_end_date_to_show']=$_POST['dashboard_date_to_show'];
    
   }

   if(!empty($_POST['dashboard_end_date_to_show'])){
    
    $_SESSION['temp']['dashboard_end_date_to_show']=$_POST['dashboard_end_date_to_show'];
    $_SESSION['temp']['dashboard_date_to_show']=$_POST['dashboard_date_to_show'];

    }
    $_POST['dashboard_end_date_to_show']=$_SESSION['temp']['dashboard_end_date_to_show'];
    $_POST['dashboard_date_to_show']=$_SESSION['temp']['dashboard_date_to_show'];

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

    if(!empty($_POST['single_name'])and!empty($_POST['single_name_automation'])){

        add_single_template($db,$_POST['single_name_automation']);
        unset($_POST['single_name']);
        unset($_POST['single_name_automation']);

    }

    
    

    $list=['dashboard_list_product','dashboard_list_test','dashboard_list_details','dashboard_search_product','dashboard_list_workarea','dashboard_list_productfamily','dashboard_list_result','dashboard_list_testedby'];
    $clearlist=['Clear_dashboard','Clear_dashboard_test','Clear_dashboard_details','Clear_dashboard','Clear_dashboard_workarea','Clear_dashboard_productfamily','Clear_dashboard_result','Clear_dashboard_testedby'];
    $i=0;
    $total=0;
    foreach($list as $item){
        
        if(!empty( $_POST['remove-keyword-dashboard']) ){
            if (($key = array_search($_POST['remove-keyword-dashboard'], $_SESSION['temp'][$item])) !== false) {
                unset($_SESSION['temp'][$item][$key]);
            
            }
        }
       
        if(!empty( $_POST[$item]) ){
            $remove=0;
            
            foreach($_SESSION['temp'][$item] as $keyword){
               
                if($_POST[$item]==$keyword){
                    
                    if (($key = array_search($_POST[$item], $_SESSION['temp'][$item])) !== false) {
                        unset($_SESSION['temp'][$item][$key]);
                    
                    }
                    $remove=1;//show('remove from session');
                }
                
            }
            
            if ($remove==0) {
                $_SESSION['temp'][$item][]=$_POST[$item];
               // show('add post');
            }
            if(count($_SESSION['temp'][$item])==0){
                unset($_SESSION['temp'][$item]);//show('empty session');
            }
           
            
        }
        
        if(!empty( $_POST[$clearlist[$i]]) or!empty($_POST['Clear_dashboard_all']) ){
            unset($_SESSION['temp'][$item]);
        }
        $i++;
        $total=$total+count($_SESSION['temp'][$item]);
        //show($_SESSION['temp'][$item]);
    }
    $_SESSION['temp']['dashboard_list_all']=$total;
    

    
    if(!empty($_POST['hidegraph'])){
        $_SESSION['temp']['hidegraph']=1;
    }
    if(!empty($_POST['showgraph'])){
        unset($_SESSION['temp']['hidegraph']);
    }
    if(!empty($_POST['filter_ambient'])){
        $_SESSION['temp']['filter_ambient']['min']=$_POST['min_ambient'];
        $_SESSION['temp']['filter_ambient']['max']=$_POST['max_ambient'];
    }
    if(!empty($_POST['filter_RH'])){
        $_SESSION['temp']['filter_RH']['min']=$_POST['min_RH'];
        $_SESSION['temp']['filter_RH']['max']=$_POST['max_RH'];
    }
    if(!empty($_POST['filter_part'])){
        $_SESSION['temp']['filter_part']['min']=$_POST['min_part'];
        $_SESSION['temp']['filter_part']['max']=$_POST['max_part'];
    }

    if(!empty($_POST['clear_filter'])){
        unset($_SESSION['temp'][$_POST['clear_filter']]);
    }
    
    if(!empty($_POST['newMIS'])){
        $newproduct=get_product_from_MIS($db,$_POST['newMIS']);
        $temp=load_one_test($db,$_POST['test_id']);
        $oldproduct=$temp['test_product'];
        
        if($oldproduct<>$newproduct){
            show_alert('MIS NOT UPDATED. The product linked to '.$_POST['newMIS']. ' is '.$newproduct. '.  and not '.$oldproduct,'danger');

        }else{
            updateMIS($db,$_POST['test_id'],$_POST['newMIS']);
            show_alert('MIS Number Updated','info');
        }

        
        
        
    }
    
    if(!empty($_POST['fail'])){
        if($_POST['fail']=='fail'){
        $_SESSION['temp']['fail']=$_POST['fail'];
        }else{
            unset($_SESSION['temp']['fail']);
        }
    }

    $_POST['fail']=$_SESSION['temp']['fail'];
    $_SESSION['temp']['date_to_show']=$_POST['date_to_show'];

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
                if(empty($_POST['single_name'])or $_POST['type']=='Edit Single Test'){echo'<option selected>Add a Single Test</option><option disabled>_______</option>';}
                $i=0;
                foreach(load_type_single($db) as $single){
                    if($single['basesingle_calculated'] and $i==0){$i=1;echo'<option disabled>_______</option>';};
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

                    $info_basesingle=get_basesingle_info($db,$_POST['single_name']);
                    
                    if(!empty($info_basesingle['basesingle_calculated']) and empty($_POST['defaultdetails_id'])){
                        echo'<br><select name=single_name_automation class="form-control" onChange=\'submit();\'>';
                       echo'<option selected>Choose the field</option><option >Cancel</option><option disabled>_______</option>';
                       
                        foreach(load_type_single($db,'and basesingle_calculated is NULL and basesingle_yesno is NULL') as $single){
                           
                            echo'<option ';
                            if((!empty($_POST['single_name'])&$_POST['single_name']==$single['basesingle_name'])and $_POST['type']<>'Edit Single Test'){
                                echo'selected';
                                $yesno=$single['basesingle_yesno'];
                            }
                            echo'>'.$single['basesingle_name'].'</option>';
                           
                            
                       
                        }echo'</select>';

                    }else{?>
                        <input class="form-control" type="text" name="Cable" placeholder="Details"
                        <?php if(!empty($_POST['Cable'])){ echo'value="'.$_POST['Cable'].'"';}
                        if(!empty($info_basesingle['basesingle_calculated'])){echo' readonly ';}?>
                        >
                        
                        <?php if(empty($yesno)){?>
                            <input class="form-control" type="text" name="min" placeholder="Miniumum"
                            <?php if(!empty($_POST['min'])){ echo'value="'.round($_POST['min'],4).'"';}?>
                            >
                            <input class="form-control" type="text" name="max" placeholder="Maximum"
                            <?php if(!empty($_POST['max'])){ echo'value="'.round($_POST['max'],4).'"';}?>
                            >
                            <?php
                        }?>
                        <input class="form-control" type="text" name="note" placeholder="Notes"
                        <?php if(!empty($_POST['note'])){ echo'value="'.$_POST['note'].'"';}?>
                        >
                        <textarea class="form-control" type="text" name="fail_instruction" placeholder="Failure Instruction" cols="30" rows="3"
                        ><?php if(!empty($_POST['fail_instruction'])){ echo $_POST['fail_instruction'];}?></textarea>
                        <?php if(empty($_POST['defaultdetails_id']) or !empty($_POST['delete_single'])){
                            $caption='Add Single Test'; 
                        }else{
                            $caption='Edit Single Test';
                        }?>
                        <div class="row"><div class="col-sm-6"><input class="form-control"  type="submit" name="type" value="<?php echo $caption?>"></div>
                        <div class="col-sm-6"><input class="form-control"  type="submit" name="type" value="Cancel"></div></div>
                        <?php
                    }
                    
                }   
             
            echo'<br></div>';
        echo'</div>';
        echo'</form>';
    
        foreach(load_all_single_template($db,$_POST['template_id']) as $single){
            
            echo'<form method="POST" id="defaultdetails_id-'.$single['defaultdetails_id'].'">';
            echo'<div class="col-sm-6 test-single ';
            $info_basesingle=get_basesingle_info($db,$single['basesingle_name']);
            if($info_basesingle['basesingle_calculated']==1){echo' calculated ';}
            if($_POST['defaultdetails_id']==$single['defaultdetails_id']){echo' test-selected ';}
            echo'" >';
                echo'<div class="col-sm-9" onClick="document.forms[\'defaultdetails_id-'.$single['defaultdetails_id'].'\'].submit();" >';
                $line= $single['basesingle_name'].' Test ';
                if($single['defaultdetails_cabledetails']<>''){$line=$line.'<br> Details: '.$single['defaultdetails_cabledetails'];}
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
    echo'<script>
                    window.onload = function() {
                        document.getElementById("input1").focus();
                    }
                </script>';

    echo'<div class="row ">';
        echo'<div class="col-sm-3 main-box">';
            echo'<div class="row "><h3><center>New Test Form</h3></center></div>';
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
            }elseif(empty($_POST['product_test']) or $_POST['product_test']=='Other Product'){

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
            if(!empty($_POST['product_test']) and !empty($_SESSION['temp']['role_metro_modify'])){
                
                
                    
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
                if( !empty($_SESSION['temp']['role_metro_modify'])){
                    echo'<div class="row ">';
                    
                        $allMIS=get_MIS_of_today($db,$_POST['product_test']);
                        echo'<div class="col-sm-6 col-md-4">MIS :</div>';
                        echo'<div class="col-sm-6 col-md-4">';
                    // echo'<form method="POST">';    
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
                    echo'</form>';
               
                    $template=get_template_complete($db,$_POST['product_test']);
                    if(!empty($template)){
                        echo'<div class="row "><br><div class="col-sm-6 col-md-4">Template :</div>';
                        echo'<div class="col-sm-6 col-md-4">'.$template[0]['default_name'].'</div></div>';
                        
                    }
                }
                $allMDS=get_drawing_v2($_POST['product_test']);
                //show($allMDS);
                if(!empty($allMDS)) {
                    foreach($allMDS as $MDS){
                        //show($MDS);
                        $caption='MD&S '.$MDS['name'];
                        $path=$MDS['path'];
                        ?>
                        <div class="row " ><iframe src="<?php echo $path?>"  style="height:400px;width:100%;border:none;overflow:hidden;" ></iframe></div>
                        
                        <?php
                    }
                }  
                
                
            }



            
        echo'</div>';
        echo'<div class="col-sm-6 ">';
        if(!empty($_POST['product_test'])){
            
            //load_test_single($db);
            
            //load all test
            
           
            $alltest=array();
            
            //show($alltest);
            $alltest=load_all_test($db);
            //show($alltest);
            if(!empty($_POST['test_id'])){
                $newtest=load_one_test($db,$_POST['test_id']);
               // if($alltest[0]['test_id']<>$newtest['test_id']){
                    array_unshift($alltest , $newtest);
                    $alltest[0]['1st_added']='true';
                //}
               
               
            }
           $i=0;
            foreach($alltest as &$test){
                if(!empty($test['1st_added'])){$last_date='';echo'<div class="row ">';}elseif($i==0){echo'<div style="margin-top:50px;max-height: 700px;overflow-y: scroll; ">';}
                if(!empty($test['1st_added'])){show_test($db,$test,$last_date);}else{show_test_simple($db,$test,$last_date);}
                
                //if ($_POST['test_id']==$test['test_id'] ){show_test_simple($db,$test,$last_date);}else{}
                
                
                
                if(!empty($test['1st_added'])){echo'</div>';}else{$last_date=date('jS F Y',$test['test_timetag']);$i++;}
            }
                echo'</div>';
            
        }
        
        echo'</div>';
        if(!empty($_POST['single_id']) & $_POST['single_id']<>'add-single' ){
            //load a single
            $single=load_single($db,$_POST['single_id']);
            //show($single);
            echo'<form method="POST" >';
            echo'<br><div class="col-sm-3 product-link">';
                echo '<div class="row"><b>'.$single['single_name'];
                if (!empty($single['single_cabledetails'])){
                    echo' - '.$single['single_cabledetails'];
                }
                
                echo'</b></div>';
                
                echo '<div class="row"><i>'.$single['single_description'].'</i></div>';
                echo '<div class="row"><i>'.$single['single_notes_initial'].'</i></div>';

                if (!empty($single['single_fail_instruction'])){
                    echo '<div class="row">Failure Instruction :<br>'.nl2br($single['single_fail_instruction']).'</div>';
                 
                }
                if($single['single_finished']==1){
                    $info_basesingle=get_basesingle_info($db,$single['single_name']);
                    if($info_basesingle['basesingle_calculated']<>1){
                    echo '<div class="row"><small><i>'.$single['single_tested_by'].' at '.date("G:i - jS F Y",$single['single_timetag']).'</i></small></div>';
                    }
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
                if( !empty($_SESSION['temp']['role_metro_modify'])){
                    echo' <input id="input1" class="form-control" type="submit"  name="edit_single_result" value="Edit">';
                }
                echo'</div>';
                
            
            }else{
                echo '<div class="row">';
                    if(empty($single['single_yesno'])){
                        $info_basesingle=get_basesingle_info($db,$single['single_name']);
                        if($info_basesingle['basesingle_calculated']<>1){
                            echo' <input class="form-control" type="text" id="input1" name="single_result" placeholder="Result" ';
                            if (!empty($single['single_result'])){echo' value="'.round($single['single_result'],4).'"';}
                            echo'>';
                        }
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
                    if (!empty($single['single_notes'])and $single['single_notes']<>''){
                        echo' value="'.$single['single_notes'].'"';
                        
                    }
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
        }elseif(!empty($_POST['single_id']) & $_POST['single_id']=='add-single'){
            
            ////AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
            load_single_details($db);
            ////AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
            // show($_POST);           
            echo'<br><form method="POST" >';
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
               // echo'<option disabled>_________</option>';
                // foreach(load_copy_single($db) as $single){
                //     echo'<option ';
                //     if(!empty($_POST['single_name'])&$_POST['single_name']==$single['single_name']){
                //         echo'selected';
                //         $yesno=$single['single_yesno'];
                //     }
                //     echo' value="'.$single['single_id'].'">'.$single['single_name'];
                //     if(!empty($single['single_cabledetails'])){echo' - '.$single['single_cabledetails'];}
                //     echo'</option>';
                // }
                echo'<option disabled>_________</option>';


                foreach(load_type_single($db,'and basesingle_calculated is NULL') as $single){
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
        show_dashboard_metrology_2($db);
    }elseif(!empty($_POST['show_all_test'])){
        show_all_test($db);
    }else{
       

    
    echo'<div class="row ">';
        echo'<div class="col-xs-6 col-md-3">';

        echo'<div class="col-sm-12 main-box" style="text-align: center;">';
            echo'<div class="row ">';
                
                echo'<div class="col-sm-10 ">';
                echo '<h3>'.count_test_done_today($db).' Tests Done </h3>';
                echo'</div>';
                echo'<div class="col-sm-2 ">';
                    echo'<form method="POST">';
                    echo'<div class="visible-xs-block visible-sm-block visible-md-block">';	
                        echo'<br><button type="submit"  class="btn btn-default" >
                                            <span class="glyphicon glyphicon-refresh" ></span>
                        </button><br>&nbsp';
                    echo'</div>';
                    echo'<div class="hidden-xs hidden-sm hidden-md">';	
                        echo'<br><button type="submit"  class="btn btn-default" >
                                            <span class="glyphicon glyphicon-refresh" ></span>
                        </button><br>&nbsp';
                    echo'</div>';
                    echo'</form>';
                echo'</div>';
            echo'</div>';
            echo'<div class="row ">';

                echo'<br><b>'.$_POST['date_to_show'].'</b><br>';
                show_tests_done_today($db);
                
            echo'</div>';  
        echo'</div>';
        echo'</div>';
        echo'<div class="col-lg-3 col-md-4 col-xs-6">';
        echo'<div class="col-sm-12 main-box" style="text-align: center;">';
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
                    echo'<br><b>'.$_POST['date_to_show'].'</b>';
                    
                    
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
                echo'<div id="short_list" style="display:block;">';
                show_tests_to_be_done($db);
                echo'<br><div class="row main-box" onclick="showMe(\'long_list\');dontshowMe(\'short_list\');">';
                    echo'<span class="glyphicon glyphicon-plus"  > </span> Show Jobs Only Started';
                echo'</div>';
                echo'</div>';
                
                echo'<div id="long_list" style="display:none;">';
                show_tests_to_be_done($db,'');
                echo'<br><div class="row main-box" onclick="showMe(\'short_list\');dontshowMe(\'long_list\');">';
                    echo'<span class="glyphicon glyphicon-minus"  > </span> Hide Jobs Only Started';
                echo'</div>';
                echo'</div>';
            echo'</div>';
            echo'<script>
            function showMe (box) {
                document.getElementById(box).style.display = "block";
            }
            function dontshowMe (box) {
                document.getElementById(box).style.display = "none";
            }
            </script>';
                 
        echo'</div>';
        echo'</div>';
        echo'<div class="col-md-5 col-lg-6 col-sm-12">';
        echo'<div class="col-sm-12 main-box" style="text-align: center;">';
            
            echo'<div class="col-sm-8 ">';
                echo'<h3>'.count_single_test_done_today($db).' Single Tests </h3><br>'; 
                
            echo'</div>';
            echo'<div class="col-sm-2 ">';
            echo'<form method="POST">';
            if(empty($_POST['fail'])){
                    echo'<br><button type="submit" name="fail" value="fail" class="btn btn-default" >Fail</button><br>&nbsp';
            }else{
                echo'<br><button type="submit" name="fail" value="all" class="btn btn-default" >All</button><br>&nbsp';
            }
                
            echo'</form>'; 
            echo'</div>'; 
            echo'<div class="col-sm-2 ">';
            echo'<form method="POST">';
                echo'<div class="visible-xs-block visible-sm-block visible-md-block">';	
                    echo'<br><button type="submit"  class="btn btn-default" >
                                        <span class="glyphicon glyphicon-refresh" ></span>
                    </button><br>&nbsp';
                echo'</div>';
                echo'<div class="hidden-xs hidden-sm hidden-md">';	
                    echo'<br><button type="submit"  class="btn btn-default" >
                                        <span class="glyphicon glyphicon-refresh" ></span>
                    </button><br>&nbsp';
                echo'</div>';
            echo'</form>'; 
            echo'</div>'; 
            echo'<div class="col-sm-12 ">';
               
                show_last_50_tests($db);  
            echo'</div>';
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
    AND(WorkArea<>\'Cutting\' AND WorkArea<>\'Bolt\' )
    
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

function load_type_single($db,$option=''){
    $query='SELECT *
    FROM metro_basesingle
    WHERE 1=1 '.$option.'
    order by  basesingle_calculated asc,basesingle_yesno asc, basesingle_id asc';
  //WHERE basesingle_calculated is NULL
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

function add_single_template($db,$option=''){
    
    
    $defaultid=$_POST['template_id'];
    $basesingleid=get_id($db,$_POST['single_name'])[0];
    $unit="'".get_id($db,$_POST['single_name'])[1]."'";
    $cabledetail=$_POST['Cable'];
    if(!empty($option)){
        $cabledetail=$option;
        $info_basesingle=get_basesingle_info($db,$option);
        $unit="'".$info_basesingle['basesingle_unit']."'";
    }
    if(empty($_POST['min'])){$minimum='NULL';}else{$minimum=$_POST['min'];}
    if(empty($_POST['max'])){$maximum='NULL';}else{$maximum=$_POST['max'];}
    if(empty($_POST['note'])){$notes='NULL';}else{$notes="'".$_POST['note']."'";}
    if(empty($_POST['fail_instruction'])){$fail_instruction='';}else{$fail_instruction=$_POST['fail_instruction'];}
    
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
    defaultdetails_unit,
    defaultdetails_fail_instruction
	) 
	VALUES (
	'".$defaultid."',
    '".$basesingleid."',
    '".$cabledetail."',
    ".$minimum.",
    ".$maximum.",
    ".$notes.",
    ".$unit.",
    '".$fail_instruction."'
    
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

function get_last_MIS_of_today($db,$code){
    $today=(date('Y-m-d',time()))	;
    $threedaysago=(date('Y-m-d',strtotime($today.' -3days')));
    $query='SELECT TOP 1
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

    $row=$sql->fetch();
    return $row[0];
}

function get_product_from_MIS($db,$MIS){
    
    $query='SELECT 
    Code
    FROM MIS_List 
    
        
    WHERE 
     ManufactureIssueNumber=\''.$MIS.'\'
    
    ';
    
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetch();
    return $row[0];
}

function get_count_test_today($db,$today=''){
    if(empty($today)){$today=(date('Y-m-d',time()))	;}
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
        order by basesingle_name asc,defaultdetails_cabledetails asc
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
    $_POST['test_id']=$testnumber;
    
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
        single_fail_instruction,
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
        '".$one_template['defaultdetails_fail_instruction']."',
        '".$one_template['basesingle_yesno']."',
        '".$one_template['defaultdetails_unit']."')";	
        
        
        $sql = $db->prepare($query); 
       //show($query);
        $sql->execute();
        $countsingle=$countsingle+1;
    }


   
    

}

function load_all_test($db){
    $query='SELECT TOP 200 *
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

function load_one_test($db,$test_id){
    $query='SELECT  *
    FROM metro_test
    LEFT JOIN
    (SELECT count(single_id) as thecount, single_testid  FROM dbo.metro_single where single_finished=1 GROUP BY single_testid)as countsingle
    ON
    countsingle.single_testid=test_id
    where test_id=\''.$test_id.'\'
   
    ';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetch();
 //show($query);
  return $row;
}

function load_all_single($db,$testid,$option=''){
    $query='SELECT *
    FROM metro_single
    Left join metro_basesingle on basesingle_name=single_name
    left join( SELECT count(single_id)as thecount,single_name as single_name2 FROM metro_single where single_testid=\''.$testid.'\'  GROUP BY single_name) as temp2
	on temp2.single_name2=single_name
    where single_testid=\''.$testid.'\' '.$option.'
   
    order by basesingle_calculated asc,thecount desC,single_name ASC,single_cabledetails ASC,single_finished DESC';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetchall();
 
  return $row;
}

function load_all_single_grouped($db,$testid,$option=''){
    $query='SELECT count(single_id)as thecount,
    single_name as single_name 
    FROM metro_single 
    left join metro_basesingle
    on basesingle_name=single_name
    where single_testid=\''.$testid.'\' and   
    single_finished =1 and
    basesingle_calculated is null
     
    GROUP BY single_name
    ORDER BY thecount DESC';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetchall();
 
  return $row;
}

function load_single($db,$single_id){
    $query='SELECT *
    FROM metro_single
    Left join metro_basesingle on basesingle_name=single_name
    where single_id=\''.$single_id.'\'
   
    ';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetch();
  
  return $row;

}

function updateMIS($db,$testid,$newMIS){
    $query='UPDATE dbo.metro_test SET 
    test_jobnumber=\''.$newMIS.'\'
        
    WHERE test_id=\''.$testid.'\'';
    
    $sql = $db->prepare($query); 
    //if($_SESSION['temp']['id']=='CorentinHillion'){show($query);}
    $sql->execute();

    echo'<script>document.getElementById("MISdiv").innerHTML = "MIS '.$newMIS.'";</script>';
}

function save_single_test_result($db){
    $finished=1;
    if(empty($_POST['single_result'])){
        $result="NULL";
        $finished="NULL";
    }else{
        $result="'".round($_POST['single_result'],3)."'";
    }

    $info=load_single($db,$_POST['single_id']);
    // the last timetag and tested_by
    if(empty($info['single_timetag'])){
        $timetag=time();
        $tested_by=$_SESSION['temp']['id'];
    }else{
        $timetag=$info['single_timetag'];
        $tested_by=$info['single_tested_by'];
    }
    //if empty we filled it the new info 
   

    $query='UPDATE dbo.metro_single SET 
		single_result='.$result.',
        single_notes=\''.$_POST['single_notes'].'\'
			
		WHERE single_id=\''.$_POST['single_id'].'\'';
        
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $name=load_single($db,$_POST['single_id']);
    //show($name);
    $info_basesingle=get_basesingle_info($db,$name['single_name']);
    
    if($info_basesingle['basesingle_calculated']<>1){
        $query='UPDATE dbo.metro_single SET 
            
            single_finished='.$finished.',
            single_timetag=\''.$timetag.'\',
            single_tested_by=\''.$tested_by.'\',
            single_edit_timetag=\''.time().'\',
            single_edit_by=\''.$_SESSION['temp']['id'].'\'
            
            WHERE single_id=\''.$_POST['single_id'].'\'';
            
        $sql = $db->prepare($query); 
       // show($query);
        $sql->execute();
    }
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

function get_next_single_id($db,$single_id){
    $single=load_single($db,$single_id);
    
    $query='SELECT TOP 1 single_id
    FROM metro_single
    where single_testid=\''.$single['single_testid'].'\'
    and single_name=\''.$single['single_name'].'\'
    and single_finished is null
    order by single_id ASC
   
    ';
  
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();
  
    $next_single_id= $row[0];


    return $next_single_id;
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
    if(!empty($_POST['fail'])){
        $addon=' and single_pass=-1 ';
    }
    $query='SELECT count(distinct single_id) as total_test
    FROM metro_single
    left join metro_test on test_id=single_testid
    left join metro_basesingle on basesingle_name=single_name
    where    single_finished=1 and test_date=\''.$today.'\' and basesingle_calculated is null '.$addon.'
    ';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetch();
 
  return $row[0];
  
}

function show_tests_done_today($db){
    $today=(date('Y-m-d',time()))	;
    $today=(date('Y-m-d',strtotime($_POST['date_to_show'])))	;
    $query='SELECT test_product as Code,
    count(distinct test_id) as count_test_id,
    count(distinct single_id) as count_single_id, 
    max(test_timetag) as max_timetag,
    sum(test_finished) as sum_finished
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
        
        echo '<div class="row " onClick="document.forms[\'product-'.$product['Code'].'\'].submit();"';
        if($_SESSION['temp']['role_metro_modify']){echo'';}
        echo'  >';
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

function show_tests_to_be_done($db,$option='and scan_time_distributed>60'){
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
    (SELECT test_id,test_product,test_date FROM dbo.metro_test WHERE test_finished=1) as temp
    ON
    Code=temp.test_product and temp.test_date=scan_date
    LEFT JOIN
    (SELECT test_id,test_product,test_date FROM dbo.metro_test WHERE test_finished is NULL) as temp2
    ON
    Code=temp2.test_product and temp2.test_date=scan_date
    LEFT JOIN
    dbo.metro_assign
    ON
    assign_productcode=Code
        
    WHERE 
    scan_statut=\'start\'
    '.$option.'
    AND scan_date=\''.$today.'\'
    
    and Code not like \'%PRINTING%\'
    
    GROUP BY MIS_List.WorkArea,Code,assign_defaultid
    
    order by thetest desc,MIS_List.WorkArea asc, Code asc
    ';
    
    $sql = $db->prepare($query); 
    //show($query);
    //if($_SESSION['temp']['id']=='CorentinHillion'){show($query);}
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
        echo '<div class="row " ';
        if($_SESSION['temp']['role_metro_modify']){echo'';}
        echo' onClick="document.forms[\'product-'.$product['Code'].'\'].submit();" >';
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

function show_last_50_tests($db){
    $today=(date('Y-m-d',strtotime($_POST['date_to_show'])))	;
   if(!empty($_POST['fail'])){
       $addon=' and single_pass=-1 ';
   }
   $query='SELECT  *
    FROM metro_single
    left join metro_test on test_id=single_testid
    left join metro_basesingle on basesingle_name=single_name

    where    single_finished=1 and test_date=\''.$today.'\' and basesingle_calculated is NULL '.$addon.' 
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

    echo'<form method="POST" id="last25-product-'.$single['single_id'].'" >';
    echo' <input type="hidden" name="product_test" value="'.$single['test_product'].'">';
    echo' <input type="hidden" name="test_id" value="'.$single['test_id'].'">';
    echo' <input type="hidden" name="single_id" value="'.$single['single_id'].'">';
    echo'</form>';

    echo'<div class="row row-metro" ';
    if($_SESSION['temp']['role_metro_modify']){echo'';}
    echo'  >';
        echo'<div class="col-sm-1 ">';
        if(($single['single_pass']==-1)  ){
            // echo '<input type="hidden"  name="job_number" value="'.$line['scan_jobnumber'].'">';
            echo'<center><img class="attachment" src="img/warning.png" width="25"  ></center>';
            
        }
        echo'</div>';
        echo '<div class="col-sm-1 ">'.date("G:i",$single['single_timetag']).'</div>';
        
        echo'<div class="col-sm-6 " onClick="document.forms[\'last25-product-'.$single['single_id'].'\'].submit();">'.$single['test_product'].' - '.$single['single_name'].'';
        if(!empty($single['single_cabledetails'])){echo ' - '.$single['single_cabledetails'];}
        echo'</div>';
        echo '<div class="col-sm-2 ">';
    
        
        if($single['single_yesno']==0){echo round($single['single_result'],2).' '.$single['single_unit'];}else{
            if($single['single_result']==1){
                echo 'Pass';
            }else{
                echo 'Fail';
            }
        }
        echo'</div>';
        echo '<div class="col-sm-2 " onClick="document.forms[\'RaisePIL-'.$single['single_id'].'\'].submit();" >';
        if(($single['single_pass']==-1)  ){
            echo'<form method="POST" action="prod-issue-log.php" id="RaisePIL-'.$single['single_id'].'" >';
            if(empty($single['single_PIL_number'])and!empty($_SESSION['temp']['id'])){
                echo 'Raise PIL';
                
            }else{
                echo $single['single_PIL_number'];
                echo' <input type="hidden" name="issue_number" value="'.$single['single_PIL_number'].'">';
            }
           
                $caption=$single['test_product'].' has failed the test '.$single['single_name'] ;
                if(!empty($single['single_cabledetails'])){$caption=$caption. ' - '.$single['single_cabledetails'];}
                $caption=$caption. ' - ';
                if($single['single_yesno']==0){$caption=$caption.round($single['single_result'],2).' '.$single['single_unit'];}
                echo' <input type="hidden" name="product_test" value="'.$single['test_product'].'">';
                echo' <input type="hidden" name="test_id" value="'.$single['test_id'].'">';
                echo' <input type="hidden" name="single_id" value="'.$single['single_id'].'">';
                echo' <input type="hidden" name="caption" value="'.$caption.'">';
                echo' <input type="hidden" name="type" value="edit">';
                echo'</form>';
        }
        echo'</div>';
    
    echo'</div>';
    echo'<div></div>';
    $last_date=date('jS F Y',$single['single_timetag']);
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
        }elseif(!empty($single['single_result'])){
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

function show_test($db,$test,$last_date){
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
        //notes_value=window.prompt("Add a Notes",);
        //    $.ajax({type:\'POST\',url:\'roster_ajax.php\',data: {day: theday,operator: theoperator,Notes: notes_value},success:function(html){$(\'.postinfo\').append(html);}});
        echo '<div class="row" id="MISdiv" ondblclick="ChangeMIS(\''.$test['test_id'].'\');" >MIS: '.$test['test_jobnumber'].'</div>';
        echo'<script>
        function ChangeMIS(the_testid){
            the_newMIS=window.prompt("Enter MIS Numberer","'.get_last_MIS_of_today($db,$test['test_product']).'");
           // alert(the_testid);
            
            $.ajax({type:\'POST\',url:\'metro_ajax.php\',data: {newMIS: the_newMIS,test_id: the_testid},success:function(html){$(\'.postinfo\').append(html);}});
        }
        </script>';


        if (!empty($test['test_shift'])){echo '<div class="row">Shift: '.$test['test_shift'].'</div>';}
        echo '<div class="row">'.$test['test_created_by'].'</div>';
        //echo '<div class="row">Edit</div>';
        echo '<div class="row">';
        if ($test['thecount']==0 and !empty($_SESSION['temp']['role_metro_modify'])){echo'<div class="col-sm-4"></div><div class="col-sm-4"><button type="submit" name="delete_test" value="Delete" class="form-control" >
            <span class="glyphicon glyphicon-trash" ></span></button></div>';}
        echo'</div>';
        
        
        echo '<input type="hidden"  name="test_id" value="'.$test['test_id'].'">';
        echo '<input type="hidden"  name="product_test" value="'.$test['test_product'].'">';
        echo '<input type="hidden"  name="MIS_test" value="'.$test['test_jobnumber'].'">';
    echo'</form>';
    echo'</div>';
    echo'<div class="col-sm-9 ">';

    
    $allsingle=load_all_single($db,$test['test_id']);
    $count=0;
    echo'<div class="row">';
    foreach($allsingle as $single){
        if($count==2){  echo'</div><div class="row">';}
       
        echo'<div class="col-sm-6 test-single-tobedone ';
        if ($single['single_id']==$_POST['single_id']){echo' test-selected ';}
        if ($single['single_finished']<>0){echo' test-pass ';}
        if ($single['single_pass']==-1){echo' test-warning ';}
        if ($single['single_name']=='Shear'){echo' Shear ';}
        if ($single['single_name']=='Bolt Pull-Out'){echo' Pull-Out ';}

        //$info_basesingle=get_basesingle_info($db,$single['single_name']);
        if($single['basesingle_calculated']==1){echo' calculated ';}
        echo '"';
        if( !empty($_SESSION['temp']['role_metro_modify'])or $single['single_finished']<>0){
            echo' onClick="document.forms[\'single-'.$single['single_id'].'\'].submit();"';
        }
        echo'>';
        
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
                if($single['single_finished']<>0 or ($single['basesingle_calculated']==1 and !empty($single['single_result']))){echo' Result: '.round($single['single_result'],2).' '.$single['single_unit'].'';}
            }else{
                if($single['single_finished']<>0 ){
                    if($single['single_result']==1){$result='Pass';}else{$result='<center><img class="attachment" src="img/warning.png" width="25"  > Fail <img class="attachment" src="img/warning.png" width="25"  ></center>';}
                    echo' Result: '.$result.'';
                }else{

                }
            }
            if(!empty($single['single_notes'])and $single['single_notes']<>''){
                //echo' <span class="glyphicon glyphicon-info-sign popover__title"></span>';
                echo'   <div class="popover__wrapper2">
                <span class="glyphicon glyphicon-info-sign popover__title2"></span> 
                <div class="popover__content2"> Notes: '.$single['single_notes'].'</div>
                </div>      ';
            }
            echo'</div>';
            echo'<div class="row">';
            if(empty($single['single_yesno'])and !empty($single['single_pass'])){
                if($single['single_pass']==-1){
                    echo'<center><img class="attachment" src="img/warning.png" width="25"  > Fail <img class="attachment" src="img/warning.png" width="25"  ></center>';
                }else{
                    echo'Pass';
                }
            }
            echo'</div>';
            
            echo'</div>';
            echo'<form method="POST" id="single-'.$single['single_id'].'">';
            
            echo'<div class="col-sm-12">';
                
            echo'</div>';

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if($single['basesingle_calculated']<>1 and !empty($_SESSION['temp']['role_metro_modify'])){
                echo'<div class="col-sm-2"><button type="submit" name="delete_single_result" value="Delete" class="form-control" >
                <span class="glyphicon glyphicon-trash" ></span></button></div>';
                echo'<div class="col-sm-2"><button type="submit" name="copy_single_result" value="Copy" class="form-control" >
                <span class="glyphicon glyphicon-plus" ></span></button></div>';
            }
           


            echo '<input type="hidden"  name="test_id" value="'.$test['test_id'].'">';
            echo '<input type="hidden"  name="product_test" value="'.$test['test_product'].'">';
            echo '<input type="hidden"  name="MIS_test" value="'.$test['test_jobnumber'].'">';
            echo' <input type="hidden" name="single_id" value="'.$single['single_id'].'">';
            echo'</form>';
        echo'</div>';
        if($count==2){  $count=0;}
        $count++;
    }
    echo'</div>';
    if( !empty($_SESSION['temp']['role_metro_modify'])){
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
    }
    echo'</div>';


    echo'</div>';



}

function show_test_simple($db,$test,$last_date){
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
    if ($_POST['test_id']==$test['test_id'] ){echo' test-selected ';}
    echo'" onClick="document.forms[\'test-'.$test['test_id'].'\'].submit();" >';

        echo'<div class="col-sm-1 ">';
            echo date('G:i',$test['test_timetag']);echo'</div>';
        echo'<div class="col-sm-2 ">';
            echo $test['test_created_by'];
        echo'</div>';
        echo'<div class="col-sm-2 ">';
        echo $test['test_jobnumber'];
        echo'</div>';
        echo'<div class="col-sm-1 ">';
        echo $test['test_shift'];
        echo'</div>';
        echo'<div class="col-sm-5 ">';
        $allsingle=load_all_single_grouped($db,$test['test_id']);
        foreach($allsingle as $single){
            echo'<div class="col-sm-4 ">';
                echo $single['single_name'].' ('.$single['thecount'].')';
            echo'</div>';
        }
        echo'</div>';
        echo'<div class="col-sm-1 ">';
        if ($test['test_finished']<>1){echo'<img class="attachment" src="img/warning.png" width="25"  >';}
        echo'</div>';
        


    echo'</div>';

    echo'<form method="POST" id="test-'.$test['test_id'].'">';
    echo '<input type="hidden"  name="test_id" value="'.$test['test_id'].'">';
    echo '<input type="hidden"  name="product_test" value="'.$test['test_product'].'">';
    echo'</form>';



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
    $_POST['fail_instruction']=$row['defaultdetails_fail_instruction'];
    echo '<input type="hidden"  name="single_name" value="'.$_POST['single_name'].'">';
    echo '<input type="hidden"  name="defaultdetails_id" value="'.$_POST['defaultdetails_id'].'">';
    
}

function navbar_metrology($db){

	echo'<div class="row">';
		echo'<div class="col-sm-2 ">';
			echo'<form method="POST">';
			
			if(empty($_POST['show_dashboard']) and empty($_POST['show_all_test']) and empty($_POST['product_test']) and empty($_POST['new_test']) and $_SESSION['temp']['role_metro_modify']==1){
            
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
			}elseif(!empty($_POST['show_dashboard'])){
                echo'<div class="visible-xs-block visible-sm-block visible-md-block">';	
                    echo'<br><button type="submit" name="show_dashboard" value="show_dashboard" class="btn btn-default" >
                                        <span class="glyphicon glyphicon-refresh" ></span>
                    </button><br>&nbsp';
                echo'</div>';
                echo'<div class="hidden-xs hidden-sm hidden-md">';	
                    echo'<br><button type="submit" name="show_dashboard" value="show_dashboard"  class="btn btn-default" >
                                        <span class="glyphicon glyphicon-refresh" > Refresh</span>
                    </button><br>&nbsp';
                echo'</div>';
            }
			echo'</form>';
		echo'</div>';
		
		echo'<div class="col-sm-2 ">';
            echo'<form method="POST">';
                
            if(empty($_POST['show_dashboard']) and empty($_POST['show_all_test']) and empty($_POST['manage_template']) and empty($_POST['template_name']) and $_SESSION['temp']['role_metro_admin']==1){

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
        
        if(!(empty($_POST['show_dashboard']) and empty($_POST['show_all_test']) and empty($_POST['product_test']) and empty($_POST['new_test']) and empty($_POST['manage_template']) and empty($_POST['template_name']))){
                echo'<div class="col-sm-1 "> <br>&nbsp';
                echo'</div>';
            }else{
                
                echo'<div class="col-sm-3 ">';
                echo'<form method="POST">';
                echo'<div class="col-sm-6 ">';
                echo'<br><input class="form-control" type="date" name="date_to_show" onchange="submit();" value="'.date('Y-m-d',strtotime($_POST['date_to_show'])).'">';
                echo'</div>'; 
                //echo'<div class="col-sm-6">';
                //echo'<br><button type="submit" name="date_to_show" value="'.date('Y-m-d',time()).'" class="btn btn-default" >
               //     <span class="glyphicon glyphicon-calendar" > Load</span>
               //     </button><br>';
                //echo'</div>'; 
                    
                    
                
                echo'</form>';
            echo'</div>';
            }
        
        if(!(empty($_POST['show_dashboard']))){
            echo'<div class="col-sm-2 ">';
        
                echo'<form method="POST">';
                echo'<br><div class="col-sm-2 ">Start</div><div class="col-sm-10 "><input class="form-control" type="date" name="dashboard_date_to_show" onChange="submit();"value="'.$_POST['dashboard_date_to_show'].'"></div>';
                echo'<input type="hidden" name="show_dashboard" value="show_dashboard">';   
                
                
            echo'</div>';
        }else{
            echo'<div class="col-sm-2 "> <br>&nbsp';
            echo'</div>';
        }
		echo'<div class="col-sm-2 ">';
            if(!(empty($_POST['show_dashboard']))){
            
            echo'<br><div class="col-sm-2 ">End</div><div class="col-sm-10 "><input class="form-control" type="date" name="dashboard_end_date_to_show" onChange="submit();"value="'.$_POST['dashboard_end_date_to_show'].'"></div>';
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
        }else{ //if($_SESSION['temp']['role_metro_admin']==1)
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

function get_data_chart($db){
    $filter=do_the_filter_metro($db);
    $query='SELECT single_result,
    test_date,
    single_timetag,
    single_tested_by,
    test_product,
    IIF(single_pass is NULL, \'No Result\', IIF(single_pass =-1, \'Fail\', \'Pass\')),
    test_jobnumber,
	Concat(single_name,\' - \',single_cabledetails),
    single_unit,
    single_id

    FROM metro_single
    left join metro_test on test_id=single_testid
    left join dbo.List_Document on test_product=Product_COde
    
    LEFT JOIN
        (SELECT count(single_id) as thecount, single_testid  
        FROM dbo.metro_single 
        left join metro_test on test_id=single_testid 
        left join dbo.List_Document on test_product=Product_COde 
        where single_finished=1 and single_yesno =0 '.do_the_filter_metro_2($db,'All','').' 
        GROUP BY single_testid)as countsingle
    ON  countsingle.single_testid=test_id
    
    WHERE single_finished=1 
    and single_yesno=0 
    and test_date>=\''.$_POST['dashboard_date_to_show'].'\' 
    and test_date<=\''.$_POST['dashboard_end_date_to_show'].'\' 
    and thecount>0 '.do_the_filter_metro_2($db,'All','').'
    ORDER BY test_date asc, single_timetag asc';

   $sql = $db->prepare($query); 
   $sql->execute();
   $row=$sql->fetchall();
  //show($query);
   return $row;
}

function get_summary_to_analyze($db){
    $filter=do_the_filter_metro($db);
    $query='SELECT MAX(single_result) as themax,MIN(single_result)as themin,STDEV(single_result)as thestdev,AVG(single_result)as theavg

    FROM metro_single
    left join metro_test on test_id=single_testid
    left join dbo.List_Document on test_product=Product_COde
    LEFT JOIN
        (SELECT count(single_id) as thecount, single_testid  
        FROM dbo.metro_single 
        left join metro_test on test_id=single_testid 
        left join dbo.List_Document on test_product=Product_COde 
        where single_finished=1 and single_yesno =0 '.do_the_filter_metro_2($db,'All','').' 
        GROUP BY single_testid)as countsingle
    ON  countsingle.single_testid=test_id
    
    WHERE single_finished=1 
    and single_yesno=0 
    and test_date>=\''.$_POST['dashboard_date_to_show'].'\' 
    and test_date<=\''.$_POST['dashboard_end_date_to_show'].'\' 
    and thecount>0 '.do_the_filter_metro_2($db,'All','').'';

   $sql = $db->prepare($query); 
   $sql->execute();
   $rowtemp=$sql->fetch();
  //show($query);
  $row[0]=['Minimum',round($rowtemp['themin'],3)];
  $row[1]=['Maximum',round($rowtemp['themax'],3)];
  $row[2]=['Average',round($rowtemp['theavg'],3)];
  $row[3]=['StDev',round($rowtemp['thestdev'],3)];


   //show($row);
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
    WHERE test_date>=\''.$_POST['dashboard_date_to_show'].'\' and test_date<=\''.$_POST['dashboard_end_date_to_show'].'\'  and single_finished=1 '.do_the_filter_metro_2($db,'All',).'
    group by single_pass

   order by thecount desc';
  
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetchall();
 
  return $row;
}

function get_list_product_to_analyze($db){
    $filter=do_the_filter_metro($db);
    
    //$filter='';
    $query='SELECT test_product,  count(thecount) as test_id, sum(thecount) as thecount
    FROM metro_single
    left join metro_test on test_id=single_testid
    LEFT JOIN
    (SELECT count(single_id) as thecount, single_testid  FROM dbo.metro_single left join metro_test on test_id=single_testid where single_finished=1 and single_yesno =0 '.do_the_filter_metro($db,'',2).' '.do_the_filter_metro($db,'',3).' GROUP BY single_testid)as countsingle
    ON
    countsingle.single_testid=test_id
    

    WHERE single_finished=1 and single_yesno=0 and test_date>=\''.$_POST['dashboard_date_to_show'].'\' and test_date<=\''.$_POST['dashboard_end_date_to_show'].'\' and thecount>0 '.do_the_filter_metro($db,'',2).' '.do_the_filter_metro($db,'',3).'
   group by test_product
   order by thecount desc';
 //show($query);
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetchall();
 
  return $row;
}

function get_list_workarea_to_analyze($db){
    $filter=do_the_filter_metro($db);
    
    //$filter='';
    $query='SELECT WorkArea,  count(thecount) as test_id, sum(thecount) as thecount
    FROM metro_single
    left join metro_test on test_id=single_testid
    left join dbo.List_Document on test_product=Product_COde
    LEFT JOIN
    (SELECT count(single_id) as thecount, single_testid  FROM dbo.metro_single left join metro_test on test_id=single_testid where single_finished=1 and single_yesno =0 '.do_the_filter_metro($db,'',2).' '.do_the_filter_metro($db,'',3).' GROUP BY single_testid)as countsingle
    ON
    countsingle.single_testid=test_id
    

    WHERE single_finished=1 and single_yesno=0 and test_date>=\''.$_POST['dashboard_date_to_show'].'\' and test_date<=\''.$_POST['dashboard_end_date_to_show'].'\' and thecount>0 '.do_the_filter_metro($db,'',2).' '.do_the_filter_metro($db,'',3).'
   group by WorkArea
   order by thecount desc';
 //show($query);
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetchall();
 
  return $row;
}

function get_list_productfamily_to_analyze($db){
    $filter=do_the_filter_metro($db);
    
    //$filter='';
    $query='SELECT PRODUCT_FAMILY,  count(thecount) as test_id, sum(thecount) as thecount
    FROM metro_single
    left join metro_test on test_id=single_testid
    left join dbo.List_Document on test_product=Product_COde
    LEFT JOIN
        (SELECT count(single_id) as thecount, single_testid  
        FROM dbo.metro_single 
        left join metro_test on test_id=single_testid 
        left join dbo.List_Document on test_product=Product_COde 
        where single_finished=1 and single_yesno =0 '.do_the_filter_metro_2($db,'All',['dashboard_list_productfamily']).' 
        GROUP BY single_testid)as countsingle
    ON  countsingle.single_testid=test_id
    
    WHERE single_finished=1 
    and single_yesno=0 
    and test_date>=\''.$_POST['dashboard_date_to_show'].'\' 
    and test_date<=\''.$_POST['dashboard_end_date_to_show'].'\' 
    and thecount>0 '.do_the_filter_metro_2($db,'All',['dashboard_list_productfamily']).'
   group by PRODUCT_FAMILY
   order by thecount desc';
 
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetchall();
 
  return $row;
}

function get_list_test_to_analyze ($db){
    $filter=do_the_filter_metro($db,'',2);
    
    //$filter='';
    $query='SELECT single_name,  count(thecount) as test_id, sum(thecount) as thecount
    FROM metro_single
    left join metro_test on test_id=single_testid
    left join dbo.List_Document on test_product=Product_COde
    LEFT JOIN
        (SELECT count(single_id) as thecount, single_testid  
        FROM dbo.metro_single 
        left join metro_test on test_id=single_testid 
        left join dbo.List_Document on test_product=Product_COde 
        where single_finished=1 '.do_the_filter_metro_2($db,'All',['dashboard_list_test']).' 
        GROUP BY single_testid)as countsingle
    ON  countsingle.single_testid=test_id
    
    WHERE single_finished=1 
     
    and test_date>=\''.$_POST['dashboard_date_to_show'].'\' 
    and test_date<=\''.$_POST['dashboard_end_date_to_show'].'\' 
    and thecount>0 '.do_the_filter_metro_2($db,'All',['dashboard_list_test']).'
   group by single_name
   order by thecount desc';
 //show($query);
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetchall();
 
  return $row;
}

function get_list_to_analyze($db,$field,$exclusion){
    $filter=do_the_filter_metro($db,'',2);
    
    //$filter='';
    $query='SELECT '.$field.',  count(thecount) as test_id, sum(thecount) as thecount
    FROM metro_single
    left join metro_test on test_id=single_testid
    left join dbo.List_Document on test_product=Product_COde
    LEFT JOIN
        (SELECT count(single_id) as thecount, single_testid  
        FROM dbo.metro_single 
        left join metro_test on test_id=single_testid 
        left join dbo.List_Document on test_product=Product_COde 
        where single_finished=1 '.do_the_filter_metro_2($db,'All',[$exclusion]).' 
        GROUP BY single_testid)as countsingle
    ON  countsingle.single_testid=test_id
    
    WHERE single_finished=1 
    
    and test_date>=\''.$_POST['dashboard_date_to_show'].'\' 
    and test_date<=\''.$_POST['dashboard_end_date_to_show'].'\' 
    and thecount>0 '.do_the_filter_metro_2($db,'All',[$exclusion]).'
   group by '.$field.'
   order by test_id desc';
 //show($query);
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetchall();
 
  return $row;
}

function get_list_test_details_to_analyze ($db){
    $filter=do_the_filter_metro($db,'',2);
    
    //$filter='';
    $query='SELECT single_cabledetails,  count(thecount) as test_id, sum(thecount) as thecount
    FROM metro_single
    left join metro_test on test_id=single_testid
    LEFT JOIN
    (SELECT count(single_id) as thecount, single_testid  FROM dbo.metro_single left join metro_test on test_id=single_testid where single_finished=1 and single_yesno =0 '.do_the_filter_metro($db,'',2).' '.do_the_filter_metro($db,'',1).' GROUP BY single_testid)as countsingle
    ON
    countsingle.single_testid=test_id
    

    WHERE single_finished=1 and single_yesno=0 and test_date>=\''.$_POST['dashboard_date_to_show'].'\' and test_date<=\''.$_POST['dashboard_end_date_to_show'].'\' and thecount>0 '.do_the_filter_metro($db,'',2).' '.do_the_filter_metro($db,'',1).'
   group by single_cabledetails
   order by thecount desc';
 //show($query);
  $sql = $db->prepare($query); 
  $sql->execute();
  $row=$sql->fetchall();
 
  return $row;
}

function get_list_result_to_analyze ($db){
    $query='SELECT IIF(single_pass is NULL, \'No Result\', IIF(single_pass =-1, \'Fail\', \'Pass\')) as Result,  count(thecount) as test_id, sum(thecount) as thecount
    FROM metro_single
    left join metro_test on test_id=single_testid
    LEFT JOIN
    (SELECT count(single_id) as thecount, single_testid  
    FROM dbo.metro_single 
    left join metro_test on test_id=single_testid where single_finished=1 and single_yesno =0 '.do_the_filter_metro($db,'',2).' '.do_the_filter_metro($db,'',1).' GROUP BY single_testid)as countsingle
    ON
    countsingle.single_testid=test_id
    

    WHERE single_finished=1 and single_yesno=0 and test_date>=\''.$_POST['dashboard_date_to_show'].'\' and test_date<=\''.$_POST['dashboard_end_date_to_show'].'\' and thecount>0 '.do_the_filter_metro($db,'',2).' '.do_the_filter_metro($db,'',1).'
   group by IIF(single_pass is NULL, \'No Result\', IIF(single_pass =-1, \'Fail\', \'Pass\'))
   order by thecount desc';
 //show($query);
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

function show_dashboard_metrology_2($db){

    $starttime = microtime(true); // Top of page
	
	

	
    //show(do_the_filter_metro_2($db,$option='All'));
    echo'<div class="row ">';
        echo'<div class="col-sm-0 ">';
        echo'</div>';
        echo'<div class="col-sm-10 " ';
        if(empty($_SESSION['temp']['hidegraph'])){echo'style="height:300px;';}
        echo'">';
            if(empty($_SESSION['temp']['hidegraph'])){
                $data=get_data_chart($db);
                graphview($data,$title='Test Chart');
            }else{
                echo'<form method="POST" >';
                echo'<input type="hidden" name="dashboard_date_to_show" value="'.$_POST['dashboard_date_to_show'].'">';
                echo'<input type="hidden" name="dashboard_end_date_to_show" value="'.$_POST['dashboard_end_date_to_show'].'">';
                echo'<input type="hidden" name="show_dashboard" value="show_dashboard">';
                echo'<br><center>';
                if(empty($_SESSION['temp']['hidegraph'])){echo'<button class="btn btn-default"  name="hidegraph" value="hidegraph">Hide Graph</button>';}else{{echo'<button class="btn btn-default"  name="showgraph" value="showgraph">Show Graph</button>';}}
                echo'</center> ';
                echo'</form>';
            }
            $time[] = microtime(true); // Bottom of page
        echo'</div>';
        echo'<div class="col-sm-2 ">';
        $data=get_summary_to_analyze($db);
        tableview($data,$title='Summary',"",$headers=[['string','Info'],['number','Value']],1,'nothing');
        $time[] = microtime(true); // Bottom of page
        echo'<div class="row">';
            echo'<form method="POST" >';
            echo'<input type="hidden" name="dashboard_date_to_show" value="'.$_POST['dashboard_date_to_show'].'">';
            echo'<input type="hidden" name="dashboard_end_date_to_show" value="'.$_POST['dashboard_end_date_to_show'].'">';
            echo'<input type="hidden" name="show_dashboard" value="show_dashboard">';
            echo'<br><center>';
            echo'</center> ';
            echo'</form>';
        echo'</div>'; 
        echo'<div class="row">';
            echo'<form action="export_excel.php" method="POST" target="blank">';
            echo'<input type="hidden" name="dashboard_date_to_show" value="'.$_POST['dashboard_date_to_show'].'">';
            echo'<input type="hidden" name="dashboard_end_date_to_show" value="'.$_POST['dashboard_end_date_to_show'].'">';
            echo'<br><center>
            <button class="" style="padding:10px;border:1px solid black;border-radius: 10px;width:50%;text-align: center;" name="export_to_excel" value="Export To Excel">
            <img class="attachment" src="img/excel.png" width="30"  >
            </button>
            </center> ';
            echo'</form>'; 
        echo'</div>';    

        echo'</div>';
    echo'</div>';
    echo'<div class="row "><form method=\'POST\'>';
        echo '<input type="hidden" name="show_dashboard" class="form-control" value="show_dashboard"">';
        echo'<div class="col-sm-2 ">';
            echo'<form method="POST">';
            echo '<input type="hidden" name="show_dashboard" class="form-control" value="show_dashboard"">';
            if(!empty($_SESSION['temp']['dashboard_list_all']) ){
                echo'<input class="form-control" type="submit" name="Clear_dashboard_all" value="Clear All">';
            }
                    
            echo'</form>';
        echo'</div>';
        echo'<div class="col-sm-1 ">';
        echo'<form method="POST">';
                echo '<input type="hidden" name="show_dashboard" class="form-control" value="show_dashboard"">';
               echo '<input type="text"  placeholder="Product Search"  name="dashboard_search_product" class="form-control" onEnter="submit();" ">
                <datalist id="thelist">';
                foreach (get_all_product_with_test($db) as &$item){
                    echo"<option >".$item[0]."</option>";
                }
                echo '</datalist>';
                
        echo'</form></div>';
        echo'<div class="col-sm-3 "><form method="POST">';      
        echo '<input type="hidden" name="show_dashboard" class="form-control" value="show_dashboard"">';  
        foreach($_SESSION['temp']['dashboard_search_product'] as $keyword){
            echo'<div class="col-sm-4 keyword">';
                echo'<div class="col-sm-8 ">';
                    echo $keyword;
                echo'</div>';
                echo'<div class="col-sm-4 "><button name="remove-keyword-dashboard" value="'.$keyword.'" class="remove-keyword">X</button></div>';
                
            echo'</div>';
        }
        echo'</form></div>';
        
    echo'</div>';   
    echo'<div class="row ">';
        echo'<div class="col-sm-2 ">';
                        
            // echo'<div class="row ">';
            //     echo'<h4>WorkArea :</h4>';
                
            // echo'</div>';
            echo'<div class="row ">';
            
                echo'<form method="POST">';
                echo '<input type="hidden" name="show_dashboard" class="form-control" value="show_dashboard"">';
                
            echo'</div>';
            echo'<div class="row ">';
            //$data=get_list_workarea_to_analyze($db);
            $data=get_list_to_analyze($db,'WorkArea','dashboard_list_workarea');
            tableview($data,$title='Workarea_List',$option="titlePosition:'out',orientation:'vertical'",$headers=[['string','Workarea'],['number','Tests']],1,'dashboard_list_workarea');
            echo'</div>';
            $time[] = microtime(true); // Bottom of page
            if(!empty($_SESSION['temp']['dashboard_list_workarea']) ){echo'<input class="form-control" type="submit" name="Clear_dashboard_workarea" value="Clear">';}
                
            echo'</form>';
            echo'<div class="row ">';
            
                echo'<form method="POST">';
                echo '<input type="hidden" name="show_dashboard" class="form-control" value="show_dashboard"">';
                
            echo'</div>';
            echo'<div class="row ">';
            $data=get_list_to_analyze($db,'PRODUCT_FAMILY','dashboard_list_productfamily');
            tableview($data,$title='Family_List',$option="titlePosition:'out',orientation:'vertical'",$headers=[['string','Family'],['number','Tests']],1,'dashboard_list_productfamily');
            echo'</div>';
            $time[] = microtime(true); // Bottom of page
            if(!empty($_SESSION['temp']['dashboard_list_productfamily']) ){echo'<input class="form-control" type="submit" name="Clear_dashboard_productfamily" value="Clear">';}
                
            echo'</form>';

        echo'</div>';
    
        echo'<div class="col-sm-2 " >';
                
            // echo'<div class="row ">';
            //     echo'<h4>Product :</h4>';
                
            // echo'</div>';
            echo'<div class="row ">';
            
                echo'<form method="POST">';
                echo '<input type="hidden" name="show_dashboard" class="form-control" value="show_dashboard"">';
                
            echo'</div>';
            echo'<div class="row ">';
            //$data=get_list_product_to_analyze($db);
            $data=get_list_to_analyze($db,'test_product','dashboard_list_product');
            tableview($data,$title='Product_List',$option="height:400",$headers=[['string','Product'],['number','Tests']],1,'dashboard_list_product');
            echo'</div>';
            $time[] = microtime(true); // Bottom of page
            if(!empty($_SESSION['temp']['dashboard_list_product']) or !empty($_SESSION['temp']['dashboard_search_product'])){echo'<input class="form-control" type="submit" name="Clear_dashboard" value="Clear">';}
                
            echo'</form>';

        echo'</div>';
        echo'<div class="col-sm-2 ">';
               
           
            echo'<div class="row ">';
            
                echo'<form method="POST">';
                echo '<input type="hidden" name="show_dashboard" class="form-control" value="show_dashboard"">';
               
                
                
            echo'</div>';
            echo'<div class="row ">';
          
            $data=get_list_to_analyze($db,'single_name','dashboard_list_test');
            tableview($data,$title='Test_List',$option="titlePosition:'out',orientation:'vertical'",$headers=[['string','Test'],['number','Tests']],2,'dashboard_list_test');
            echo'</div>';
            $time[] = microtime(true); // Bottom of page
            if(!empty($_SESSION['temp']['dashboard_list_test'])){echo'<input class="form-control" type="submit" name="Clear_dashboard_test" value="Clear">';}
            echo'</form>';

    //    echo'</div>';
    //    echo'<div class="col-sm-2 ">';
            
           
            echo'<div class="row ">';
           
            $data=get_list_to_analyze($db,'single_cabledetails','dashboard_list_details');
            tableview($data,$title='Details_List',$option="titlePosition:'out',orientation:'vertical'",$headers=[['string','Details'],['number','Tests']],3,'dashboard_list_details');
            echo'</div>';
            $time[] = microtime(true); // Bottom of page
            
            echo'<form method="POST">';
            echo '<input type="hidden" name="show_dashboard" class="form-control" value="show_dashboard"">';
            if(!empty($_SESSION['temp']['dashboard_list_details'])){echo'<input class="form-control" type="submit" name="Clear_dashboard_details" value="Clear">';}
                
            echo'</form>';

        echo'</div>';
        echo'<div class="col-sm-2 ">';
            
            // echo'<div class="row ">';
            //     echo'<h4>Result :</h4>';
            // echo'</div>';
            echo'<div class="row ">';
            //$data=get_list_result_to_analyze($db);
            $data=get_list_to_analyze($db,'IIF(single_pass is NULL, \'No Result\', IIF(single_pass =-1, \'Fail\', \'Pass\'))','dashboard_list_result');
            tableview($data,$title='Result_List',$option="titlePosition:'out',orientation:'vertical'",$headers=[['string','Result'],['number','Tests']],3,'dashboard_list_result');
            echo'</div>';
            $time[] = microtime(true); // Bottom of page
            
            echo'<form method="POST">';
            echo '<input type="hidden" name="show_dashboard" class="form-control" value="show_dashboard"">';
            if(!empty($_SESSION['temp']['dashboard_list_result'])){echo'<input class="form-control" type="submit" name="Clear_dashboard_result" value="Clear">';}
                
            echo'</form>';
            echo'<div class="row ">';
            //$data=get_test_ratio($db);
            
            $data=get_list_to_analyze($db,'IIF(single_pass is NULL, \'No Result\', IIF(single_pass =-1, \'Fail\', \'Pass\'))','');
            
                
            piechart($data,$title='Pass/Fail',"pieSliceText: 'label',pieSliceTextStyle:{fontSize: 8},height: 350");
            $time[] = microtime(true); // Bottom of page

            echo'</div>';

        echo'</div>';
        echo'<div class="col-sm-2 ">';
            
            // echo'<div class="row ">';
            //     echo'<h4>Details :</h4>';
            // echo'</div>';
            echo'<div class="row ">';
            //$data=get_list_test_details_to_analyze($db);
            $data=get_list_to_analyze($db,'single_tested_by','dashboard_list_testedby');
            tableview($data,$title='Tester_List',$option="titlePosition:'out',orientation:'vertical'",$headers=[['string','Tested By'],['number','Tests']],3,'dashboard_list_testedby');
            echo'</div>';
            $time[] = microtime(true); // Bottom of page
            
            echo'<form method="POST">';
            echo '<input type="hidden" name="show_dashboard" class="form-control" value="show_dashboard">';
            if(!empty($_SESSION['temp']['dashboard_list_testedby'])){echo'<input class="form-control" type="submit" name="Clear_dashboard_testedby" value="Clear">';}
                
            echo'</form>';

        echo'</div>';
        echo'<div class="col-sm-2 ">';
            
            // echo'<div class="row ">';
            //     echo'<h4>Option :</h4>';
            // echo'</div>';
            echo'<form method="POST">';
            echo'<center>';
            echo '<input type="hidden" name="show_dashboard" class="form-control" value="show_dashboard"">';
            if(empty($_SESSION['temp']['hidegraph'])){echo'<div class="row "><button class="btn btn-default"  name="hidegraph" value="hidegraph">Hide Graph</button></div>';}
            //echo'<div class="row "><br><button class="btn btn-default"  name="groupby_result" value="groupby_result">Group by Result</button></div>';
            //echo'<div class="row "><br><button class="btn btn-default"  name="groupby_date" value="groupby_date">Group by Date</button></div>';
            
            
            echo'<form method="POST">';
            echo '<input type="hidden" name="show_dashboard" class="form-control" value="show_dashboard"">';
            echo'<div class="row "><div class="col-sm-12 "><b>Ambient Temp</b></div>';
            echo'<div class="row "><div class="col-md-12 col-lg-3"></div>';
            if(!isset($_SESSION['temp']['filter_ambient']['min'])){
                echo'
                <div class="col-md-6 col-lg-3 "><input type="text" name="min_ambient" placeholder="Min" class="form-control" value="0"></div>
                <div class="col-md-6 col-lg-3 "><input type="text" name="max_ambient" placeholder="Max" class="form-control" value="50"></div>
                <div class="col-sm-12 "><button class="btn btn-default"  name="filter_ambient" value="filter_ambient">Filter</button></div>';
            }else{
                echo'
                <div class="col-md-6 col-lg-3 "><input type="text" name="min_ambient" placeholder="Min" class="form-control" value="'.$_SESSION['temp']['filter_ambient']['min'].'"></div>
                <div class="col-md-6 col-lg-3 "><input type="text" name="max_ambient" placeholder="Max" class="form-control" value="'.$_SESSION['temp']['filter_ambient']['max'].'"></div>
                <div class="row "><div class="col-md-12 col-lg-3"></div>
                <div class="col-md-6 col-lg-3 "><button class="btn btn-default"  name="filter_ambient" value="filter_ambient">Filter</button></div>';
                echo'<div class="col-md-6 col-lg-3 "><button class="btn btn-default"  name="clear_filter" value="filter_ambient">Clear</button></div></div>';
            }
            

            echo'</div>';
            echo'</form>';
            echo'<form method="POST">';
            echo '<input type="hidden" name="show_dashboard" class="form-control" value="show_dashboard"">';
            echo'<div class="row "><div class="col-sm-12 "><b>Humidity</b></div>';
            echo'<div class="row "><div class="col-md-12 col-lg-3"></div>';
            if(!isset($_SESSION['temp']['filter_RH']['min'])){
                echo'
                <div class="col-md-6 col-lg-3"><input type="text" name="min_RH" placeholder="Min" class="form-control" value="0"></div>
                <div class="col-md-6 col-lg-3"><input type="text" name="max_RH" placeholder="Max" class="form-control" value="100"></div></div>
                <div class="col-sm-12 "><button class="btn btn-default"  name="filter_RH" value="filter_RH">Filter</button></div>';
            }else{
                echo'
                <div class="col-md-6 col-lg-3"><input type="text" name="min_RH" placeholder="Min" class="form-control" value="'.$_SESSION['temp']['filter_RH']['min'].'"></div>
                <div class="col-md-6 col-lg-3"><input type="text" name="max_RH" placeholder="Max" class="form-control" value="'.$_SESSION['temp']['filter_RH']['max'].'"></div></div>
                <div class="row "><div class="col-md-12 col-lg-3"></div>
                <div class="col-md-6 col-lg-3 "><button class="btn btn-default"  name="filter_RH" value="filter_RH">Filter</button></div>';
                echo'<div class="col-md-6 col-lg-3 "><button class="btn btn-default"  name="clear_filter" value="filter_RH">Clear</button></div></div>';
            }
            

            echo'</div>';
            echo'</form>';
            echo'<form method="POST">';
            echo '<input type="hidden" name="show_dashboard" class="form-control" value="show_dashboard"">';
            echo'<div class="row "><div class="col-sm-12 "><b>Part Temp</b></div>';
            echo'<div class="row "><div class="col-md-12 col-lg-3"></div>';
            if(!isset($_SESSION['temp']['filter_part']['min'])){
                echo'
                <div class="col-md-6 col-lg-3 "><input type="text" name="min_part" placeholder="Min" class="form-control" value="0"></div>
                <div class="col-md-6 col-lg-3 "><input type="text" name="max_part" placeholder="Max" class="form-control" value="50"></div>
                <div class="col-sm-12 "><button class="btn btn-default"  name="filter_part" value="filter_part">Filter</button></div>';
            }else{
                echo'
                <div class="col-md-6 col-lg-3 "><input type="text" name="min_part" placeholder="Min" class="form-control" value="'.$_SESSION['temp']['filter_part']['min'].'"></div>
                <div class="col-md-6 col-lg-3 "><input type="text" name="max_part" placeholder="Max" class="form-control" value="'.$_SESSION['temp']['filter_part']['max'].'"></div>
                <div class="row "><div class="col-md-12 col-lg-3"></div>
                <div class="col-md-6 col-lg-3 "><button class="btn btn-default"  name="filter_part" value="filter_part">Filter</button></div>';
                echo'<div class="col-md-6 col-lg-3 "><button class="btn btn-default"  name="clear_filter" value="filter_part">Clear</button></div></div>';
            }
            

            echo'</div>';
                
            
            echo'';
            echo'</form>';
        echo'</center></div>';
       
        $oldtime=$starttime;
        $totaltime=end($time)-$starttime;

        foreach($time as $track){
            //show(round(($track-$oldtime)/$totaltime*100,2).' %');
            $oldtime=$track;
        }
        //show($totaltime.' sec');
        
    
    
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
function get_drawing_v2($product){
    $db=$GLOBALS['db'];
    $query="SELECT *
    FROM doc_link
    left join (SELECT [document_number],
        max([document_issue])as [document_issue],
        document_filename
        
        FROM dbo.document
        group by document_number,document_filename)as temp on temp.document_number=doclink_docnumber
    WHERE doclink_doctype='MD&S' and doclink_productcode='$product'
    ";

    $sql = $db->prepare($query); 
    $sql->execute();
    //show($query);
    $row=$sql->fetchall();
    //show($row);
    foreach($row as $mds){
        $allMDS[$mds['doclink_docnumber']]['name']=$mds['doclink_docnumber'];
        $allMDS[$mds['doclink_docnumber']]['path']='/ressource_v2/MD&S/'.$mds['document_filename'];
        
    }
    return $allMDS;
    
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

    echo'<form method="POST" id="last25-product-'.$single['single_id'].'" >';
    echo' <input type="hidden" name="product_test" value="'.$single['test_product'].'">';
    echo' <input type="hidden" name="test_id" value="'.$single['test_id'].'">';
    echo' <input type="hidden" name="single_id" value="'.$single['single_id'].'">';
    echo'</form>';

    echo'<div class="row  main-box" ';
    if($_SESSION['temp']['role_metro_modify']){echo'';}
    echo' onClick="document.forms[\'last25-product-'.$single['single_id'].'\'].submit();" >';
       
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
            echo'<div class="col-sm-3"></div>';
            echo'<div class="col-sm-5">';
                echo'<input class="form-control" type="submit" name="Clear_search" value="Clear">';
            echo'</div>';
        echo'</div>';

    }
   
   
    echo'<input type="hidden" name="show_all_test" value="show_all_test">';
    echo'</form>';
    echo'<form action="export_excel.php" method="POST" target="blank">';
    echo'<div class="row"><b>Export</b></div>';
    echo'<div class="row">';
        echo'<div class="col-sm-3">';
        echo' ';
        echo'</div>';
        echo'<div class="col-sm-5">';
        echo'<input type="hidden" name="dashboard_date_to_show" value="'.$_POST['dashboard_date_to_show'].'">';
        echo'<input type="hidden" name="dashboard_end_date_to_show" value="'.$_POST['dashboard_end_date_to_show'].'">';
            echo'<center><button class="" style="padding:10px;border:1px solid black;border-radius: 10px;width:50%;text-align: center;" name="export_to_excel" value="Export To Excel"><img class="attachment" src="img/excel.png" width="30"  ></button></center> ';
        echo'</div>';
    echo'</div>';
    echo'</form>';
    

}

function do_the_filter_metro($db,$option='',$choice=1){
    if(empty($option)){
        $filter1="AND  (";
        if(empty($_SESSION['temp']['dashboard_list_product'])){$filter1=$filter1.'1=1';}else{$filter1=$filter1.'0=1';}
        foreach($_SESSION['temp']['dashboard_list_product'] as $product){
            $filter1=$filter1." or test_product = '$product' ";
        }
        $filter1=$filter1.")";
        
    }else{
        $filter1="and   (";
        if(empty($_SESSION['temp']['dashboard_list_product'])){$filter1=$filter1.'1=1';}else{$filter1=$filter1.'1=1';}
        foreach($_SESSION['temp']['dashboard_list_product'] as $product){
            $filter1=$filter1." and test_product <> '$product' ";
        }
        $filter1=$filter1.")";
       

        }



    if(empty($option)){
        $filter2="AND  (";
        if(empty($_SESSION['temp']['dashboard_list_test'])){$filter2=$filter2.'1=1';}else{$filter2=$filter2.'0=1';}
        foreach($_SESSION['temp']['dashboard_list_test'] as $test_name){
            $filter2=$filter2." or single_name='$test_name' ";
        }
        $filter2=$filter2.")";
    }else{
        $filter2="and   (";
        if(empty($_SESSION['temp']['dashboard_list_test'])){$filter2=$filter2.'1=1';}else{$filter2=$filter2.'1=1';}
        foreach($_SESSION['temp']['dashboard_list_test'] as $test_name){
            $filter2=$filter2." and single_name<>'$test_name' ";
        }
        $filter2=$filter2.")";

        }



    if(empty($option)){
        $filter3="AND  (";
        if(empty($_SESSION['temp']['dashboard_list_details'])){$filter3=$filter3.'1=1';}else{$filter3=$filter3.'0=1';}
        foreach($_SESSION['temp']['dashboard_list_details'] as $test_name){
            $filter3=$filter3." or single_cabledetails='$test_name' ";
        }
        $filter3=$filter3.")";
    }else{
        $filter3="and   (";
        if(empty($_SESSION['temp']['dashboard_list_details'])){$filter3=$filter3.'1=1';}else{$filter3=$filter3.'1=1';}
        foreach($_SESSION['temp']['dashboard_list_details'] as $test_name){
            $filter3=$filter3." and single_cabledetails<>'$test_name' ";
        }
        $filter3=$filter3.")";

        }


        if(empty($option)){
            $filter4=$filter4."AND  (";
            if(empty($_SESSION['temp']['dashboard_search_product'])){$filter4=$filter4.'1=1';}else{$filter4=$filter4.'1=1';}
            foreach($_SESSION['temp']['dashboard_search_product'] as $product){
                $filter4=$filter4." and test_product like '%$product%' ";
            }
            $filter4=$filter4.")";
        }else{
            $filter4=$filter4."and   (";
            if(empty($_SESSION['temp']['dashboard_search_product'])){$filter4=$filter4.'1=1';}else{$filter4=$filter4.'0=1';}
            foreach($_SESSION['temp']['dashboard_search_product'] as $product){
                $filter4=$filter4." or test_product not like '%$product%' ";
            }
            $filter4=$filter4.")";
    
            }

    
    if($choice==-1){$filter=$filter1.' '.$filter2.' '.$filter3.' '.$filter4;}
    if($choice==1){$filter=$filter1;}
    if($choice==2){$filter=$filter2.$filter4;}
    if($choice==3){$filter=$filter3.$filter4;}
    
    return $filter;
    
}

function do_the_filter_metro_2($db,$option='All',$exclusion=['']){
    
    $list[]=['dashboard_list_product','test_product','or','=','<>'];
    $list[]=['dashboard_list_test','single_name','or','=','<>'];
    $list[]=['dashboard_list_details','single_cabledetails','or','=','<>'];
    $list[]=['dashboard_search_product','test_product','and','like','not like'];
    $list[]=['dashboard_list_workarea','WorkArea','or','=','<>'];
    $list[]=['dashboard_list_productfamily','PRODUCT_FAMILY','or','=','<>'];
    $list[]=['dashboard_list_result','IIF(single_pass is NULL, \'No Result\', IIF(single_pass =-1, \'Fail\', \'Pass\'))','or','=','<>'];
    $list[]=['dashboard_list_testedby','single_tested_by','or','=','<>'];
    $list[]=['test','test_ambient','or','=','<>'];
    
   
    
    foreach($list as $item){
        
        $tempfilter='';
        $tempfilter=$tempfilter."AND  (";
        if(empty($_SESSION['temp'][$item[0]])){
            $tempfilter=$tempfilter.'1=1';
        }else{
            if($item[3]=='like'){$tempfilter=$tempfilter.'1=1';}else{$tempfilter=$tempfilter.'0=1';}
        }


        foreach($_SESSION['temp'][$item[0]] as $miniitem){
            $tempfilter=$tempfilter." ".$item[2]." ".$item[1]." ".$item[3]." '";
            if($item[3]=='like'){
                $tempfilter=$tempfilter.'%'.$miniitem.'%';
            }else{
                if($miniitem=='blank'){$miniitem='';}
                $tempfilter=$tempfilter.$miniitem;
            }
            $tempfilter=$tempfilter."' ";
        }
        $tempfilter=$tempfilter.")";

       $filter1[$item[0]]=$tempfilter;
    }
    $filter='';
    
    if ($option<>'All'){
        $filter=$filter1[$option];show('test');
    }else{
        
        foreach($exclusion as $item_exclude){
            unset($filter1[$item_exclude]);
           
        }
        foreach($filter1 as $combine){
           
            $filter=$filter.$combine;
        }
    }
    
   
    if(!empty($_SESSION['temp']['filter_ambient'])){
        $filter=$filter.' AND (test_ambianttemp>='.$_SESSION['temp']['filter_ambient']['min'].' and test_ambianttemp<='.$_SESSION['temp']['filter_ambient']['max'].' ) ';

    }
    if(!empty($_SESSION['temp']['filter_RH'])){
        $filter=$filter.' AND (test_humidity>='.$_SESSION['temp']['filter_RH']['min'].' and test_humidity<='.$_SESSION['temp']['filter_RH']['max'].' ) ';
    }
    if(!empty($_SESSION['temp']['filter_part'])){
        $filter=$filter.' AND (test_bolt_temp>='.$_SESSION['temp']['filter_part']['min'].' and test_bolt_temp<='.$_SESSION['temp']['filter_part']['max'].' ) ';
    }
    
   
    
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

function get_all_product_with_test($db){
    $query='SELECT test_product
    from metro_test
    left join metro_single on single_testid=test_id
    where (single_yesno=0 '.do_the_filter_metro($db,'-1').')
    group by test_product
    order by test_product ASC
    ';

    show( $query);

    $sql = $db->prepare($query); 
    
    $sql->execute();

    $row=$sql->fetchall();
   
    return $row;
}

function get_all_test_name_with_test($db){
    $query='SELECT single_name
    from metro_single
    left join metro_test on test_id=single_testid
    where (single_yesno=0 '.do_the_filter_metro($db,'',1).' '.do_the_filter_metro($db,'',3).' '.do_the_filter_metro($db,'-1',2).')
    group by single_name
    order by single_name ASC
    ';

    show( $query);

    $sql = $db->prepare($query); 
    
    $sql->execute();

    $row=$sql->fetchall();
   
    return $row;
}

function get_all_test_details_name_with_test($db){
    $query='SELECT single_cabledetails
    from metro_single
    left join metro_test on test_id=single_testid
    where (single_yesno=0 '.do_the_filter_metro($db,'',1).' '.do_the_filter_metro($db,'',2).' '.do_the_filter_metro($db,'-1',3).')
    group by single_cabledetails
    order by single_cabledetails ASC
    ';

    //show( $query);

    $sql = $db->prepare($query); 
    
    $sql->execute();

    $row=$sql->fetchall();
   
    return $row;
}


function export_to_excel($db){
    
    // Excel file name for download 
    $fileName = "Metrology_Export_" . date(' Y-m-d G:i:s') . ".xls"; 
    
    
    
    // Fetch records from database 
    
    $query='SELECT  single_id as SINGLE_TEST_ID,
    single_testid as TEST_ID,
    CONCAT(single_name,\' -\',test_product) as Test_Name,
    single_tested_by as Tested_By,
    test_jobnumber as MIS_Number,
    single_result as Result,
    single_unit as Unit,
    iif(single_pass=1,\'PASS\',iif(single_pass=-1,\'FAIL\',\'N/A\')) as Passable,
    single_name as Type,
    test_date as Test_date,
    single_description as Test_Description,
    single_notes_initial as Initial_notes,
    single_cabledetails as Cable_Details, 
    single_minimum as Test_Minimum,
    single_maximum as Test_Maximum,
    single_notes as Test_Notes,
    test_product as Product,
    test_shift as Shift,
    test_ambianttemp as Ambient_temp,
    test_humidity as RH,
    test_bolt_temp as Bolt_temp
    FROM metro_single
    left join metro_test on test_id=single_testid
    left join dbo.List_Document on test_product=Product_COde
    
    
    WHERE single_finished=1 
     
    and test_date>=\''.$_POST['dashboard_date_to_show'].'\' 
    and test_date<=\''.$_POST['dashboard_end_date_to_show'].'\' 
     '.do_the_filter_metro_2($db,'All','').'
    ORDER BY test_date asc, single_timetag asc';
   
   $sql = $db->prepare($query); 
   $sql->execute();
   
   
   $alltest=$sql->fetchAll(PDO::FETCH_ASSOC);
   //echo $query;
        
   header("Content-Disposition: attachment; filename=\"$fileName\"");
   header("Content-Type: application/vnd.ms-excel");
   //header("Content-Type: text/plain");
    // Write data to file
    $flag = false;
    
    //echo'<pre>';
   // print_r($alltest);
    //echo'</pre>';
    //show($alltest);
    foreach($alltest as $row){ 
        if (!$flag) {
            // display field/column names as first row
            echo implode("\t", array_keys($row)) . "\r\n";
            $flag = true;
        }
        echo implode("\t", array_values($row)) . "\r\n";
    }
    
    
    
    
    // Render excel data 
    //echo $excelData; 
    
    exit;
}

function calculate_calculated_test($db,$test_id){
    //load all single with calculated value
    

    foreach(load_all_single($db,$test_id,$option=' and basesingle_calculated=1 ') as $single){
       
            $query="SELECT ".$single['basesingle_sql_formula']."(single_result)as single_result
            FROM metro_single
            Left join metro_basesingle on basesingle_name=single_name
                    
                WHERE single_testid='".$test_id."'  and single_name='".$single['single_cabledetails']."'";
            $sql = $db->prepare($query); 
            $sql->execute();
            $row=$sql->fetch();
            $result=$row[0];
            //show($query);
            if(empty($result)){
                $result="NULL";
            }else{
                $result="'".round($result,3)."'";
            }
            $query="SELECT count(single_id)as count_single,sum(single_finished)as count_finished
            FROM metro_single
           
                    
                WHERE single_testid='".$test_id."'  and single_name='".$single['single_cabledetails']."'";
            $sql = $db->prepare($query); 
            $sql->execute();
            $row2=$sql->fetch();
            //show($query);
           
            if($row2[0]==$row2[1]){
                $finished="'1'";
            }else{
                $finished="NULL";
            }





            $query='UPDATE dbo.metro_single SET 
            single_tested_by=\'Calculated\',
            single_result='.$result.',
            single_finished='.$finished.',
            single_timetag=\''.time().'\'
            WHERE single_id=\''.$single['single_id'].'\'';
            
        $sql = $db->prepare($query); 
        
        $sql->execute();
        check_test_pass_fail($db,$test_id);
        
    }
    check_test_finished($db,$test_id);

}

function show_alert($message,$type='warning'){
    echo' <div class="alert alert-'.$type.' " role="alert">'.$message.'
         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
             <span aria-hidden="true">&times;</span>
         </button>
     </div>';
}

function weekly_mail_metro($db,$date1,$date2){
    $date1=(date('Y-m-d',strtotime($date1)))	;
    $date2=(date('Y-m-d',strtotime($date2)))	;
	
	$address='production-assistant@sicame.com.au';
	$subject='Weekly Metrology Summary - 13th October 2021';
	$name='Production Assistant';
    $cc='corentin@sicame.com.au;pat@sicame.com.au;brett@sicame.com.au;Heath.H@sicame.com.au;finney@sicame.com.au;brent.degier@sicame.com.au;';
    $stats=count_single_test_done_last_week($db,$date1,$date2);
	$content='Hi all,<br><br>Here is the Weekly Summary of the Metrology Department.';
	$content=$content.'<br><br>'.number_format($stats['all']).' single tests were done between the '.date('jS \of F Y',strtotime($date2)).' and the '.date('jS \of F Y',strtotime($date1.''));
	$content=$content.'<br><br>'.number_format($stats['fail']).' single tests were out of specification';
    $content=$content.' and '.number_format($stats['nbr_PIL']).' PIL have been raised: ';
    //$stats['nbr_PIL']
	$content=$content.'<br>';
    $content=$content.get_all_single_fail_last_week($db,$date1,$date2);
    $content=$content.'<br><br>Find more details <a href="http://192.168.1.30/metrology.php" >here</a> ';
	//show($content);
    
	send_email($address,$name,$content,$subject,$cc);
	
	
	
	
}

function count_single_test_done_last_week($db,$date1,$date2){
    $date1=(date('Y-m-d',strtotime($date1)))	;
    $date2=(date('Y-m-d',strtotime($date2)))	;
    $query='SELECT count(distinct single_id) as total_test,single_pass
    FROM metro_single
    left join metro_test on test_id=single_testid
    left join metro_basesingle on basesingle_name=single_name
    where    single_finished=1 and test_date>=\''.$date2.'\' and test_date<=\''.$date1.'\' and basesingle_calculated is null
    group by single_pass
    ';
  
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetchall();
    $stats=array();
    foreach( $row as $result){
        if($result['single_pass']==-1){
            $stats['fail']=$result['total_test'];
        }else{
            $stats['pass']=$stats['pass']+$result['total_test'];
        }
        
    }
    $stats['all']=$stats['pass']+$stats['fail'];

    $query='SELECT count(distinct single_PIL_number) as total_PIL
    FROM metro_single
    left join metro_test on test_id=single_testid
    left join metro_basesingle on basesingle_name=single_name
    where    single_finished=1 and test_date>=\''.$date2.'\' and test_date<=\''.$date1.'\' and basesingle_calculated is null and single_PIL_number <>\'\'
    
    ';
  
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();
    $stats['nbr_PIL']=$row[0];
    return $stats;
  
}

function get_all_single_fail_last_week($db,$date1,$date2){
    $date1=(date('Y-m-d',strtotime($date1)))	;
    $date2=(date('Y-m-d',strtotime($date2)))	;
    $query='SELECT  *
    FROM metro_single
    left join metro_test on test_id=single_testid
    left join metro_basesingle on basesingle_name=single_name

    where    single_finished=1 and test_date>=\''.$date2.'\' and test_date<=\''.$date1.'\' and basesingle_calculated is NULL and single_pass=-1
    order by test_product asc,single_timetag desc,single_name desc,single_cabledetails desc';
  
    $sql = $db->prepare($query); 
    $sql->execute();
    //show($query);
    $alltest=$sql->fetchall();
    $line='';
    $oldproduct='';
    foreach($alltest as $test){
        if($oldproduct<>$test['test_product']){
            $line=$line.'<br>- '.$test['test_product'];
        }
        $line=$line.'<br>   '.date('jS M G:i',$test['single_timetag']);
        $line=$line.' by '.$test['single_tested_by'];
        $line=$line.' - '.$test['single_name'];
        if(!empty($test['single_cabledetails'])){$line=$line.' '.$test['single_cabledetails'];}
        if(!empty($test['single_yesno'])){
            //$line=$line.' Fail';
        }else{
            $line=$line.' - '.round($test['single_result'],2).''.$test['single_unit'];
            if(!empty($test['single_minimum'])and $test['single_result']<$test['single_minimum']){
                $line=$line.' - Min: '.round($test['single_minimum'],2).''.$test['single_unit'];
            }elseif(!empty($test['single_maximum'])and $test['single_result']>$test['single_maximum']){
                $line=$line.' - Max: '.round($test['single_maximum'],2).''.$test['single_unit'];
            }
        }
        if(!empty($test['single_notes'])){
           // $line=$line.'<br>      <i>'.$test['single_notes'].'</i>';
        }
        if(!empty($test['single_PIL_number'])){
            $line=$line.' - <a href="http://192.168.1.30/prod-issue-log.php?issue='.$test['single_PIL_number'].'" >'.$test['single_PIL_number'].'</a>';
         }
        


        //show($test);
        $oldproduct=$test['test_product'];
    }
    return $line;
}




?>