<?php
function manage_POST_asset($db){
    if(!empty($_GET['asset_id'])){
        $_POST['asset_id']=$_GET['asset_id'];
        $_POST['type']='detail';
    }
    if(!empty($_GET['debug'])){
        $_SESSION['temp']['debug']=$_GET['debug'];
    }
    
    if($_SESSION['temp']['debug']=='1'){
        show($_POST);
    }

    

    if(!empty($_POST['type'] )& $_POST['type']=='save'){
		save_asset_movement($db);
		$_POST['type']='detail';
	}

	if(!empty($_POST['type'] )& $_POST['type']=='remove_material'){
		remove_material($db,$_POST['asset_id']);
		$_POST['type']='detail';
	}
	if(!empty($_POST['type'] )& $_POST['type']=='add_material'){
		add_material($db,$_POST['asset_id'],$_POST['add_material_id']);
		$_POST['type']='detail';
	}


	if(!empty($_POST['type'] )& $_POST['type']=='save_new_asset'){
		save_new_asset($db);
		$_POST['asset_id']=get_asset_id($db,$_POST['asset_name']);
		$_POST['type']='detail';
	}

	if(!empty($_POST['type'] )& $_POST['type']=='save_mod_asset'){
		save_mod_asset($db);
		$_POST['type']='detail';
		if(!empty($_FILES['fileToUpload']['name'])){
		
		
			upload_image_asset($db);
			$_POST['type']='detail';
		}
	}

	if(!empty($_POST['type'] )& $_POST['type']=='delete'){
		delete_asset($db);
	}

	if(!empty($_POST['type'] )& $_POST['type']=='add_notes'){
		add_notes($db);
		$_POST['type']='detail';
	}


	if((!empty($_POST['type']))&&$_POST['type']=='remove_product')
	{
		
		remove_product_asset($db);

		$_POST['type']='detail';
	}

	if((!empty($_POST['type']))&&$_POST['type']=='add_product')
	{
		
		add_product_asset($db);

		$_POST['type']='detail';
	}

	$sort='ORDER BY asset_type ASC,asset_name ASC';
	if(!empty($_POST['type'] )& $_POST['type']=='sort_tool'){
		$sort='ORDER BY asset_tool_number ASC,asset_type ASC,asset_name ASC';
	}





	$filter='';
	if(!empty($_POST['type'] )& $_POST['type']=='search'){
		$_SESSION['temp']['search']=$_POST['search_word'];
		
	}

	if((!empty($_POST['type']))&&$_POST['type']=='add_picture_asset')
	{
		
		upload_image_asset($db);

		$_POST['type']='detail';
	}

	if((!empty($_POST['type']))&&$_POST['type']=='remove_picture_asset')
	{
		
		remove_image_asset($db);
		$_POST['type']='detail';
	}

	if((!empty($_POST['type']))&&$_POST['type']=='remove_setting')
	{
		
		remove_setting($db);
		$_POST['type']='detail';
	}

	if(((!empty($_POST['type']))&&$_POST['type']=='show_all_location')or empty($_SESSION['temp']['show_all_location']))
	{
		//show($_SESSION['temp']['show_all_location']);
		if(empty($_SESSION['temp']['show_all_location']) or $_SESSION['temp']['show_all_location']==2){
			$_SESSION['temp']['show_all_location']=1;
			
		}else{
			$_SESSION['temp']['show_all_location']=2;
			
		}	
	}
}

function show_list_asset($db,$filter='',$sort='ORDER BY asset_type ASC,asset_name ASC'){
    echo'<b><div class="hidden-xs hidden-sm hidden-md row asset-header">';
    echo'<form id="formtosortnumber" method="POST">';
    echo '<input type="hidden"  name="type" value="sort_tool">';
    echo'<div class="col-sm-1" onClick="document.forms[\'formtosortnumber\'].submit();" >Tool Number</div>';
    echo '</form>';
    echo'<div class="col-sm-2 ">Name</div>';
    echo'<div class="col-sm-2 ">Type</div>';
    echo'<div class="col-sm-1 ">Asset Picture</div>';
    echo'<div class="col-sm-2 ">Location</div>';
    echo'<div class="col-sm-1 ">Product</div>';
    echo'<div class="col-sm-1 ">Action</div>';
    echo'<div class="col-sm-1 ">QRCode</div>';
    
    echo '</div>
    <div class="visible-xs-block visible-sm-block visible-md-block row asset-header">';
    
    echo'<div class="col-sm-3 ">Name</div>';
    echo'<div class="col-sm-3 ">Asset Picture</div>';
    echo'<div class="col-sm-3 ">Location</div>';
    echo'<div class="col-sm-3 ">Action</div>';
    echo '</div></b>';
    
    $listeasset=load_all_asset($db,$filter,$sort);
    foreach ($listeasset as &$asset){
        echo'<form id="formtosubmit-'.$asset['asset_id'].'" method="POST">';
        echo '<input type="hidden"  name="asset_id" value="'.$asset['asset_id'].'">';
        echo '<input type="hidden"  name="type" value="detail">';
        echo '</form>';
        
        echo'<div class="hidden-xs hidden-sm hidden-md row asset-line" onClick="document.forms[\'formtosubmit-'.$asset['asset_id'].'\'].submit();" >';
           
            echo'<div class="col-sm-1 line-entry">';
            echo $asset['asset_tool_number'];
            echo '</div>';
            echo'<div class="col-sm-2 line-entry">';
            echo $asset['asset_name'];
            echo '</div>';
            echo'<div class="col-sm-2 line-entry">';
            echo $asset['asset_type'];
            if(!empty($asset['assetmaterial_name'])){echo " - ".$asset['assetmaterial_name'];}
            echo '</div>';
            echo'<div class="col-sm-1 line-entry">';
            if(!empty($asset['asset_picture'])){
                //echo'<a target="blank"  href="asset/'.$asset['asset_picture'].'">';
                echo'<img class="attachment" src="asset/mini-'.$asset['asset_picture'].'" width="50"  >';
                //echo'</a>';
            }
            echo '</div>';
            //find the latest location
            $location_data=find_location_data($db,$asset['asset_id']);
            echo'<div class="col-sm-2 line-entry">';
            echo  $location_data['location_description'].' - '.$location_data['location_reason'];
            echo '</div>';
            echo'<div class="col-sm-1 line-entry">';
            $allproduct=get_product_asset($db,$asset['asset_id']);
            foreach ($allproduct as &$product){
                echo'<small><small><div class="row">'.$product['assetproduct_productcode'].'</div></small></small>';
            }
            echo '</div>';
            
            echo'<div class="col-sm-3 col-lg-1 line-entry">';
                // echo'<div class="col-sm-6 col-lg-4 ">';
                // echo'<form method="POST">';
                // echo '<input type="hidden"  name="asset_id" value="'.$asset['asset_id'].'">';
                // echo'<button type="submit" name="type" value="move" '.allow_modify($db,'everyone_logged_in','','','disabled').' class="col-sm-12 btn btn-default" >';
                // echo'<span class="glyphicon glyphicon-move" ></span>';
                // echo '</button>';
                // echo '</form>';
                // echo '</div>';
                echo'<div class="col-sm-12 col-lg-12 ">';
                if($_SESSION['temp']['role_asset_admin']==1){
                    echo'<form method="POST">';
                        echo '<input type="hidden"  name="asset_id" value="'.$asset['asset_id'].'">';
                        echo'<button type="submit" name="type" value="delete" '.allow_modify($db,'everyone_logged_in','','','disabled').' ';
                        echo ' onclick="return confirm(\'Are you sure you want to delete this asset?\' );" ';
                        echo'class="col-sm-12 btn btn-default" >';
                        echo'<span class="glyphicon glyphicon-trash" ></span>';
                    echo '</button>';
                    echo '</form>';
                }
                echo '</div>';
                echo'<div class="col-sm-6 col-lg-1 ">';
                echo '</div>';
                // echo'<div class="col-sm-6 col-lg-4 ">';
                // echo'<form method="POST">';
                // echo'<button type="submit" name="type" value="detail" '.allow_modify($db,'admin_or_just_created','','','disabled').'  class="col-sm-12 btn btn-default" >';
                // echo '<input type="hidden"  name="asset_id" value="'.$asset['asset_id'].'">';
                // echo'<span class="glyphicon glyphicon-resize-full" ></span>';
                // echo '</button>';
                // echo '</form>';
                // echo '</div>';
            echo '</div>';
            echo'<div class="col-sm-1 ">';
            print_QRcode('http://192.168.1.30/asset.php?asset_id='.$asset['asset_id'],'50%x50%'); 
            echo '</div>';
        echo '</div>';






        echo'<div class="visible-xs-block visible-sm-block visible-md-block row asset-line" onClick="document.forms[\'formtosubmit-'.$asset['asset_id'].'\'].submit();" >';
           
            echo'<div class="col-sm-3 line-entry">';
                echo'<div class="row ">';
                if(!empty($asset['asset_name'])){
                    echo $asset['asset_name']."(".$asset['asset_tool_number'].")";
                }else{
                    echo $asset['asset_name'];
                }
                echo '</div>';
                echo'<div class="row ">';
                    echo $asset['asset_type'];
                    if(!empty($asset['assetmaterial_name'])){echo " - ".$asset['assetmaterial_name'];}
                echo '</div>';
            echo '</div>';
            
            echo'<div class="col-sm-3 line-entry">';
                if(!empty($asset['asset_picture'])){
                   // echo'<a target="blank"  href="asset/'.$asset['asset_picture'].'">';
                    echo'<img class="attachment" src="asset/'.$asset['asset_picture'].'" width="50"  >';
                   // echo'</a>';
                }
            echo '</div>';
            //find the latest location
            $location_data=find_location_data($db,$asset['asset_id']);
            echo'<div class="col-sm-3 line-entry">';
            echo  $location_data['location_description'].' - '.$location_data['location_reason'];
            echo '</div>';
            
            echo'<div class="col-sm-3 line-entry">';
                // echo'<div class="col-sm-6 ">';
                // echo'<form method="POST">';
                // echo '<input type="hidden"  name="asset_id" value="'.$asset['asset_id'].'">';
                // echo'<button type="submit" name="type" value="move" '.allow_modify($db,'everyone_logged_in','','','disabled').' class="col-sm-12 btn btn-default" >';
                // echo'<span class="glyphicon glyphicon-move" ></span>';
                // echo '</button>';
                // echo '</form>';
                // echo '</div>';
                echo'<div class="col-sm-6  ">';
                echo'<form method="POST">';
                echo '<input type="hidden"  name="asset_id" value="'.$asset['asset_id'].'">';
                echo'<button type="submit" name="type" value="delete" '.allow_modify($db,'everyone_logged_in','','','disabled').' ';
                echo ' onclick="return confirm(\'Are you sure you want to delete this asset?\' );" ';
                echo'class="col-sm-12 btn btn-default" >';
                echo'<span class="glyphicon glyphicon-trash" ></span>';
                echo '</button>';
                echo '</form>';
                echo '</div>';
                echo'<div class="col-sm-6 ">';
                echo '</div>';
                // echo'<div class="col-sm-6 ">';
                // echo'<form method="POST">';
                // echo'<button type="submit" name="type" value="detail" '.allow_modify($db,'admin_or_just_created','','','disabled').'  class="col-sm-12 btn btn-default" >';
                // echo '<input type="hidden"  name="asset_id" value="'.$asset['asset_id'].'">';
                // echo'<span class="glyphicon glyphicon-resize-full" ></span>';
                // echo '</button>';
                // echo '</form>';
                // echo '</div>';
            echo '</div>';
        echo '</div>';

        //show($asset);
    }
   

}

function show_details_asset($db,$asset_id){
    
    $details_asset=get_asset_details($db,$asset_id);
    ?>

    <div class="row ">
        <?php 
        show_block_details($db,$details_asset);
        show_block_location($db,$details_asset);
        show_block_material($db,$details_asset);
        show_block_product($db,$details_asset);
        show_block_work_instruction($db,$details_asset);
        ?>
    </div>
    <style>
        .box-shadow{
            box-shadow: 3px 3px 10px #7b7d7e;
        }
    </style>
    <?php
    if ( $_POST['type']=='add_asset' or $_POST['type']=='move' or $_POST['type']=='edit'){}else{
        show_settings($db,$details_asset);
        show_history($db,$details_asset);
    }
    
}


function show_block_details($db,$details_asset){
    $size=3;
    $sizesmall=6;
    if($_POST['type']=='edit' or $_POST['type']=='add_asset'){ $size=4;$sizesmall=12;}
    ?>
    <div class="col-lg-<?php echo$size?> col-sm-<?php echo$sizesmall?> asset-detail box-shadow" >
            <div <?php
            if ($_POST['type']=='detail' and $_SESSION['temp']['role_asset_admin']==1){ echo ' onClick="document.forms[\'formtosubmit\'].submit();" ';}?>
            >
                <div class="row"><b>Asset Details :</b></div>
                    <?php
                    if ( $_POST['type']=='add_asset' or $_POST['type']=='add_asset'){?>
                        
                        <form method="POST">
                            <div class="row">
                                <div class="col-sm-6 ">Asset name: </div>
                                <div class="col-sm-6 ">
                                    <input class="form-control" type="text" required  name="asset_name" placeholder="Asset Name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 ">Asset type: </div>
                                <div class="col-sm-6 ">
                                    <select name="asset_type" <?php echo allow_modify($db,'everyone_logged_in','','','disabled')?> class="form-control" id="asset_type">
                                        <?php 
                                            $list=['Die','Machining Center','Moulding Machine','Other'];
                                            $list=get_all_asset_type($db);
                                            foreach ($list as &$item){
                                                echo"<option >".$item."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        <div class="row">
                            <div class="col-sm-6 ">Tool number:</div>
                            <div class="col-sm-6 ">
                                <input class="form-control" type="text"   name="asset_tool_number" placeholder="Tool Number">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 ">Other Reference/Tool number: </div>
                            <div class="col-sm-6 ">
                             <input class="form-control" type="text"   name="asset_ext_number" placeholder="Other Reference">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 ">Asset number: </div>
                            <div class="col-sm-6 ">
                             <input class="form-control" type="text"   name="asset_asset_number" placeholder="Asset Number">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 ">Number of Cavities :</div>
                            <div class="col-sm-6 ">
                             <input class="form-control" type="text"   name="asset_cavity" placeholder="Number of Cavities">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 ">Cycle Time in seconds:</div>
                            <div class="col-sm-6 ">
                             <input class="form-control" type="number"   name="asset_cycle_time" placeholder="Cycle Time (sec)">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 ">Cooling Time :</div>
                            <div class="col-sm-6 ">
                             <input class="form-control" type="text"   name="asset_cooling" placeholder="Cooling Time">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 "></div>
                            <div class="col-sm-6 ">
                             <button type="submit" name="type" value="save_new_asset" class="btn btn-default" >Add Asset</button>
                            </div>
                        </div>

                    </form>
                    <?php
                }elseif ( $_POST['type']=='edit' ){
                    echo '<form method="POST" enctype="multipart/form-data">';

                    echo'<div class="row">';
                        
                    echo '</div>';
                    echo'<div class="row">';
                        echo'<div class="col-sm-6 ">';
                        echo'Asset name: ';
                        echo '</div>';
                        echo'<div class="col-sm-6 ">';
                        echo' <input class="form-control" type="text" required  name="asset_name" placeholder="Asset Name" value="'.$details_asset['asset_name'].'">';
                        echo '</div>';
                    echo '</div>';
                    echo'<div class="row">';
                        echo'<div class="col-sm-6 ">';
                        echo'Asset type: ';
                        echo '</div>';
                        echo'<div class="col-sm-6 ">';
                        echo'<select name="asset_type" '.allow_modify($db,'everyone_logged_in','','','disabled').' class="form-control" id="asset_type">';
                            
                            $list=['Die','Machining Center','Moulding Machine','Other'];
                            $list=get_all_asset_type($db);
                            foreach ($list as &$item){
                                echo"<option ";
                                if($details_asset['asset_type']==$item){echo 'selected';}
                                echo">".$item."</option>";
                            }


                            
                            
                            echo' </select>';
                        
                        
                        
                        echo '</div>';
                    echo '</div>';
                    echo'<div class="row">';
                        echo'<div class="col-sm-6 ">';
                        echo'Tool number: ';
                        echo '</div>';
                        echo'<div class="col-sm-6 ">';
                        echo' <input class="form-control" type="text"   name="asset_tool_number" placeholder="Tool Number" value="'.$details_asset['asset_tool_number'].'">';
                        echo '</div>';
                    echo '</div>';
                    
                    echo'<div class="row">';
                        echo'<div class="col-sm-6 ">';
                        echo'Other Reference/Tool number: ';
                        echo '</div>';
                        echo'<div class="col-sm-6 ">';
                        echo' <input class="form-control" type="text"   name="asset_ext_number" placeholder="Other Reference" value="'.$details_asset['asset_ext_number'].'">';
                        echo '</div>';
                    echo '</div>';
                    echo'<div class="row">';
                        echo'<div class="col-sm-6 ">';
                        echo'Asset number: ';
                        echo '</div>';
                        echo'<div class="col-sm-6 ">';
                        echo' <input class="form-control" type="text"   name="asset_asset_number" placeholder="Asset Number" value="'.$details_asset['asset_asset_number'].'">';
                        echo '</div>';
                    echo '</div>';
                    echo'<div class="row">';
                        echo'<div class="col-sm-6 ">';
                        echo'Number of Cavities : ';
                        echo '</div>';
                        echo'<div class="col-sm-6 ">';
                        echo' <input class="form-control" type="text"   name="asset_cavity" placeholder="Number of Cavities" value="'.$details_asset['asset_cavity'].'">';
                        echo '</div>';
                    echo '</div>';
                    echo'<div class="row">';
                        echo'<div class="col-sm-6 ">';
                        echo'Cycle Time in seconds:';
                        echo '</div>';
                        echo'<div class="col-sm-6 ">';
                        echo' <input class="form-control" type="number"   name="asset_cycle_time" placeholder="Cycle Time (sec)" value="'.$details_asset['asset_cycle_time'].'">';
                        echo '</div>';
                    echo '</div>';
                    echo'<div class="row">';
                        echo'<div class="col-sm-6 ">';
                        echo'Cooling Time :';
                        echo '</div>';
                        echo'<div class="col-sm-6 ">';
                        echo' <input class="form-control" type="text"   name="asset_cooling" placeholder="Cooling Time" value="'.$details_asset['asset_cooling'].'">';
                        echo '</div>';
                    echo '</div>';
                    
                    echo'<div class="row">';
                        echo'<div class="col-sm-6 ">';
                        
                        echo '</div>';
                        echo'<div class="col-sm-6 ">';
                        echo '<input type="hidden"  name="asset_id" value="'.$details_asset['asset_id'].'">';
                                
                        echo'<button type="submit" name="type" value="save_mod_asset" class="btn btn-default" >
                        Save Asset
                        </button>';
                        echo '</div>';
                    echo '</div>';


                }else{        
                    echo'<form id="formtosubmit" method="POST">';
                    echo '<input type="hidden"  name="asset_id" value="'.$details_asset['asset_id'].'">';
                    echo '<input type="hidden"  name="type" value="edit">';
                    //onClick="document.forms['search-form'].submit();"
                    if(!empty($details_asset['asset_asset_number'])){
                    echo'<div class="row" >';
                    echo'Asset number: '.$details_asset['asset_asset_number'];
                    echo '</div>';
                    }
                    if(!empty($details_asset['asset_tool_number'])){
                    echo'<div class="row">';
                    echo'Tool number: '.$details_asset['asset_tool_number'];
                    echo '</div>';
                    }
                    
                    echo'<div class="row" >';
                    echo'Asset name: '.$details_asset['asset_name'];
                    echo '</div>';
                    echo'<div class="row">';
                    echo'Asset type: '.$details_asset['asset_type'];
                    echo '</div>';
                    if(!empty($details_asset['asset_ext_number'])){
                        echo'<div class="row">';
                        echo'Other Reference: '.$details_asset['asset_ext_number'];
                        echo '</div>';
                    }
                    if(!empty($details_asset['asset_cavity'])){
                        echo'<div class="row">';
                        echo'Number of Cavities: '.$details_asset['asset_cavity'];
                        echo '</div>';
                    }
                    if(!empty($details_asset['asset_cycle_time'])){
                        echo'<div class="row">';
                        echo'Cycle Time: '.$details_asset['asset_cycle_time'].' sec';
                        echo '</div>';
                    }
                    if(!empty($details_asset['asset_cooling'])){
                        echo'<div class="row">';
                        echo'Cooling Time: '.$details_asset['asset_cooling'];
                        echo '</div>';
                    }
                    if(!empty($details_asset['asset_cycle_time'])){
                        $part_per_hours=round(3600/$details_asset['asset_cycle_time']);
                        if(!empty($details_asset['asset_cavity'])){$part_per_hours=$part_per_hours*$details_asset['asset_cavity'];}
                        echo'<div class="row">';
                        echo'Part/Hours: '.$part_per_hours.'';
                        echo '</div>';
                    }
                    
                    echo '</form>';
                }

        echo '</div>';
        echo'<div class="row ">';
            echo'<div class="row"><b>Picture :</b/></div>';
            //echo '<form method="POST" enctype="multipart/form-data">';
            echo '<input type="hidden"  name="asset_id" value="'.$details_asset['asset_id'].'">';
            if(!empty($details_asset['asset_picture'])){
                if($_POST['type']=='edit'){echo'<div class="col-sm-6 ">';}
                echo'<a target="blank"  href="asset/'.$details_asset['asset_picture'].'">';
                echo'<img class="attachment" src="asset/'.$details_asset['asset_picture'].'" width="200"  >';
                echo'</a>';
                if($_POST['type']=='edit'){
                echo '</div>';
                echo'<div class="col-sm-6 ">';
                echo'<button type="submit" name="type" value="remove_picture_asset" class="btn btn-default" >
                Remove Picture
                </button>';echo '</div>';
                }
                
            }
            else{
                
                
                if(empty($details_asset['asset_picture']) and $_POST['type']=='edit'){
                    echo'<div class="col-sm-6 ">';
                        echo'<input class="form-control" type="file" '.allow_modify($db,'everyone_logged_in','','','disabled').' name="fileToUpload" id="fileToUpload" accept="image/*;capture=camera">';
                    echo '</div>';
                    echo'<div class="col-sm-6 ">';
                        echo'<button type="submit" name="type" value="save_mod_asset" class="btn btn-default" >
                        Add
                        </button>';
                    echo '</div>';
                }
                
                
            }
            echo '</form>';
        echo '</div>';

    echo '</div>'; 
}
function show_block_location($db,$details_asset){
    $asset_id=$details_asset['asset_id'];
    if ( $_POST['type']=='move' or $_POST['type']=='add_location' ){
        echo'<div class="col-sm-4 col-lg-2 asset-location box-shadow">';
            echo'<form method="POST">';
            $lastmovement_asset=get_last_movement($db,$asset_id);
            echo'<div class="row"><b>New Location :</b/></div>';
            echo '<input type="hidden"  name="assetmovement_moveby" value="'.$_SESSION['temp']['id'].'">';
            echo'<div class="row">';
                echo'<div class="col-sm-10">';
                if ($_POST['type']=='add_location'){
                    echo '<input type="text" class="form-control" name="assetmovement_locationto" placeholder="Location" >';
                }else{
                    // echo '<input type="text" class="form-control" name="assetmovement_locationto" placeholder="Location" value="Sicame QLD">';

                    $defaultvalue='Sicame QLD';
                    if(!empty($lastmovement_asset['assetmovement_locationto'])){$defaultvalue=$lastmovement_asset['assetmovement_locationto'];}
                    echo '<input type="text" list="thelist" name="assetmovement_locationto" class="form-control" required id="assetmovement_locationto" value="'.$defaultvalue.'" >
                    <datalist id="thelist">';
                    
                    $list=get_all_location($db);
                    foreach ($list as &$item){
                        echo"<option >".$item[0]."</option>";
                    }

                    echo '</datalist>';
                }
                
                echo'</div>';
                echo'<div class="col-sm-1">';
                if ($_POST['type']=='add_location'){
                    echo'<button type="submit" name="type" value="move" '.allow_modify($db,'everyone_logged_in','','','disabled').' class="btn btn-default" >';
                    echo'<span class="glyphicon glyphicon-list-alt" ></span>';
                    echo '</button>';
                }else{
                    // echo'<button type="submit" name="type" value="add_location" '.allow_modify($db,'everyone_logged_in','','','disabled').' class="btn btn-default" >';
                    // echo'<span class="glyphicon glyphicon-plus" ></span>';
                    // echo '</button>';
                }
                
                echo'</div>';
            echo'</div>';
            echo'<div class="row">';
                echo'<div class="col-sm-10">';

                echo'<select name="assetmovement_reason" '.allow_modify($db,'everyone_logged_in','','','disabled').' class="form-control" id="assetmovement_reason">';
                echo"<option selected>Production</option>";
                echo"<option >Tool Modification</option>";
                echo"<option >Tool Repair</option>";
                
                echo"<option >Tool Design</option>";
                echo"<option >Storage</option>";
                echo' </select>';


                echo'</div>';
                echo'<div class="col-sm-1">';
                echo'</div>';
            echo'</div>';
            echo'<div class="row">';
                echo'<div class="col-sm-10">';
                echo '<input type="text" class="form-control" name="assetmovement_note" placeholder="notes" value="">';
                echo'</div>';
                echo'<div class="col-sm-1">';
                echo'</div>';
            echo'</div>';
            echo'<div class="row">';
                echo'<div class="col-sm-10">';
                //echo '<input type="date" class="form-control" name="assetmovement_date_return" value="">';
                echo'</div>';
                echo'<div class="col-sm-1">';
                echo'</div>';
            echo'</div>';
            echo '<input type="hidden"  name="assetmovement_moveby" value="'.$_SESSION['temp']['id'].'">';
            echo '<input type="hidden"  name="assetmovement_timetag" value="'.time().'">';
            //if(!empty($lastmovement_asset['assetmovement_date_return'])){echo'<div class="row">Expected Return Date : '.$lastmovement_asset['assetmovement_date_return'].'</div>';}
        
            
                
            
            echo'<button type="submit" name="type" value="save" '.allow_modify($db,'everyone_logged_in','','','disabled').' class="btn btn-default" >';
            
            echo 'Save';
            echo '</button>';
            echo'<button type="submit" name="type" value="detail" '.allow_modify($db,'everyone_logged_in','','','disabled').' class="btn btn-default" >';
            echo '<input type="hidden"  name="asset_id" value="'.$details_asset['asset_id'].'">';
            echo 'Cancel';
            echo '</button>';
            echo '</form>';
                
            
        
        
        
        echo '</div>';
    }
    if ( $_POST['type']=='add_asset' or $_POST['type']=='edit'){

    }else
    {   $lastmovement_asset=get_last_movement($db,$asset_id);
        if(($_POST['type']<>'move' and empty($lastmovement_asset))or!empty($lastmovement_asset)){
                echo'<form id="locationtosubmit" method="POST">';
                echo '<input type="hidden"  name="asset_id" value="'.$details_asset['asset_id'].'">';
                echo '<input type="hidden"  name="type" value="move">';
                echo '</form>';

                echo'<div class="col-sm-4 col-lg-2 asset-location box-shadow" ';
                if($_SESSION['temp']['role_asset_admin']==1){echo' onClick="document.forms[\'locationtosubmit\'].submit();" '; }
                
                echo '>';
                    echo'<div class="row"><b>Current Location :</b/></div>';
            if(!empty($lastmovement_asset)){
                    echo'<div class="row">'.$lastmovement_asset['assetmovement_locationto'].'</div>';
                    echo'<div class="row">Reason : '.$lastmovement_asset['assetmovement_reason'].'</div>';
                    echo'<div class="row">Date of Change : '.$lastmovement_asset['assetmovement_date'].'</div>';
                    echo'<div class="row">Booked by : '.$lastmovement_asset['assetmovement_movedby'].'</div>';
                    echo'<div class="row">';
                        print_QRcode('http://192.168.1.30/asset.php?asset_id='.$asset_id,'50%x50%'); 
                        
                    echo'</div>';
                    
                    if(!empty($lastmovement_asset['assetmovement_date_return'])){echo'<div class="row">Expected Return Date : '.$lastmovement_asset['assetmovement_date_return'].'</div>';}
                    if(!empty($lastmovement_asset['assetmovement_notes'])){echo'<div class="row">Notes : '.$lastmovement_asset['assetmovement_notes'].'</div>';}
            }else{
                echo'<div class="row">Add a Location</div>';
            }   
                echo '</div>';
        }   
        

        
        
        
        
        

    }
}
function show_block_material($db,$details_asset){
    if ( $_POST['type']=='detail'  and !empty($details_asset['asset_material']) ){
        $infomaterial=get_info_material($db,$details_asset['asset_material']);
        echo'<div class="col-sm-4 col-lg-2 asset-material box-shadow">';
            echo'<div class="row"><b>Material :</b/></div>';
            echo'<form method="POST">';
            echo'<div class="row">'.$infomaterial['assetmaterial_name'];
                if($_SESSION['temp']['role_asset_admin']==1){
                    echo'<button type="submit" name="type" value="remove_material" '.allow_modify($db,'everyone_logged_in','','','disabled').' class="btn btn-default" >';
                    echo '<input type="hidden"  name="asset_id" value="'.$details_asset['asset_id'].'">';
                    echo '<span class="glyphicon glyphicon-remove" > </span>';
                    echo '</button>';
                }
            echo'</div>';
            echo '</form>';
    
            if (!empty($infomaterial['assetmaterial_datasheet1'])){
                echo'<div class="row">';
                    echo'<a target="blank"  href="material/'.$infomaterial['assetmaterial_datasheet1'].'">';
                    echo'<button   class="btn btn-default" >';
                    echo '<span class="glyphicon glyphicon-info" > </span> Datasheet';
                    echo '</button>';
                    echo '</a>';
                echo'</div>';
                echo'<div class="row"></div>';
                echo'<div class="row"></div>';
            }
            if (!empty($infomaterial['assetmaterial_datasheet2'])){
                echo'<div class="row">';
                    echo'<a target="blank"  href="material/'.$infomaterial['assetmaterial_datasheet2'].'">';
                    echo'<button  class="btn btn-default" >';
                    echo '<span class="glyphicon glyphicon-info" > </span> Datasheet';
                    echo '</button>';
                    echo '</a>';
                echo'</div>';
                echo'<div class="row"></div>';
                echo'<div class="row"></div>';
            }
            if (!empty($infomaterial['assetmaterial_datasheet3'])){
                echo'<div class="row">';
                    echo'<a target="blank"  href="material/'.$infomaterial['assetmaterial_datasheet3'].'">';
                    echo'<button  class="btn btn-default" >';
                    echo '<span class="glyphicon glyphicon-info" > </span> Datasheet';
                    echo '</button>';
                    echo '</a>';
                echo'</div>';
                echo'<div class="row"></div>';
                echo'<div class="row"></div>';
            }
            if (!empty($infomaterial['assetmaterial_datasheet4'])){
                echo'<div class="row">';
                    echo'<a target="blank"  href="material/'.$infomaterial['assetmaterial_datasheet4'].'">';
                    echo'<button   class="btn btn-default" >';
                    echo '<span class="glyphicon glyphicon-info" > </span> Datasheet';
                    echo '</button>';
                    echo '</a>';
                echo'</div>';
                echo'<div class="row"></div>';
                echo'<div class="row"></div>';
            }
            
            
        echo '</div>';
    
        } elseif($_POST['type']<>'add_asset' and $_POST['type']<>'move' and $_POST['type']<>'edit' and $details_asset['asset_type']=='Die'){
    
        echo'<div class="col-sm-4 col-lg-2 asset-material box-shadow">';
            echo'<div class="row"><b>Material :</b/></div>';
            echo'<form method="POST">';
            
            echo'<select name="add_material_id" class="form-control"  id="add_material_id" >' ;
            
            $list=get_all_material($db);
            foreach ($list as &$item){
                echo"<option value=\"".$item[1]."\">".$item[0]."</option>";
            }
    
            echo '</select>';
    
    
    
    
            echo'<button type="submit" name="type" value="add_material" '.allow_modify($db,'everyone_logged_in','','','disabled').' class="btn btn-default" >';
                        echo '<input type="hidden"  name="asset_id" value="'.$details_asset['asset_id'].'">';
                        echo '<span class="glyphicon glyphicon-plus" > </span> Add';
                        echo '</button>';
            echo'<div class="row"></div>';
            echo '</form>';
        echo '</div>';
        }
}
function show_block_product($db,$details_asset){
    if ( $_POST['type']=='move' or $_POST['type']=='add_location' or $_POST['type']=='edit' or $_POST['type']=='add_asset'){
        
    }else{
       
    }

    $allinfoproduct=get_product_asset($db,$details_asset['asset_id']);
    //show($allinfoproduct);
    if (  $_POST['type']=='detail' and $details_asset['asset_type']<>'Moulding Machine' ){
        echo'<div class="col-sm-4 col-lg-2 asset-product box-shadow">';
            echo'<div class="row"><b>Product List :</b/></div>';
            
            foreach($allinfoproduct as &$infoproduct){
                echo'<div class="row">';
                    echo'<div class="col-sm-9">';
                        
                        if(!empty($infoproduct['Open MDS_File'])){
                            
                            if(file_exists('/var/www/html/'.str_replace('\\', '/', $infoproduct['Open MDS_File']))){
                                echo'<a target="blank"  href="'.$infoproduct['Open MDS_File'].'">';
                            }else
                            {
                                $infoproduct['Open MDS_File']=substr($infoproduct['Open MDS_File'], 0, -3)."PDF";	
                            
                                if(file_exists('/var/www/html/'.str_replace('\\', '/', $infoproduct['Open MDS_File']))){
                                echo'<a target="blank"  href="'.$infoproduct['Open MDS_File'].'">';}
                                }
                            
                            
                        }



                        echo '<button class="btn btn-default" ';
                        if(empty($infoproduct['Open MDS_File'])){echo ' disabled ';}
                        echo'>';
                        //echo"<small>";
                        if(strlen($infoproduct['assetproduct_productcode'])>8){echo"<small>";}
                        if(strlen($infoproduct['assetproduct_productcode'])>16){echo"<small>";}
                        if(strlen($infoproduct['assetproduct_productcode'])>24){echo"<small>";}
                        echo $infoproduct['assetproduct_productcode'].' ';
                        if(strlen($infoproduct['assetproduct_productcode'])>8){echo"</small>";}
                        if(strlen($infoproduct['assetproduct_productcode'])>16){echo"</small>";}
                        if(strlen($infoproduct['assetproduct_productcode'])>24){echo"</small>";}
                        echo '</button>';
                        // echo"</small>";
                        if(!empty($infoproduct['Open MDS_File'])){echo'</a>';}
                    echo'</div>';
                    echo'<div class="col-sm-2">';
                    if($_SESSION['temp']['role_asset_admin']==1){
                        echo'<form method="POST">';
                        echo '<button type="submit" name="type" value="remove_product" '.allow_modify($db,'everyone_logged_in','','','disabled').' class="btn btn-default" >';
                        echo '<input type="hidden"  name="asset_id" value="'.$details_asset['asset_id'].'">';
                        echo '<input type="hidden"  name="product_code" value="'.$infoproduct['assetproduct_productcode'].'">';
                        echo '<span class="glyphicon glyphicon-remove" > </span>';
                        echo '</button>';
                        echo '</form>';
                    }
                    echo'</div>';
                echo'</div>';
                

            }
            if($_SESSION['temp']['role_asset_admin']==1){
                echo'<div class="row">';
                    echo'<form method="POST">';
                    echo'<div class="col-sm-12">';
                    echo '<input type="text" list="the_list" name="new_product_code" required class="form-control"  >
                    <datalist id="the_list">';
                
                        $list=get_list_of_product($db);
                        foreach ($list as &$item){
                            echo"<option >".$item[0]."</option>";
                        }
                
                        echo '</datalist>';
                    echo'</div>'; 
                    echo'<div class="col-sm-6">';
                        echo '<button type="submit" name="type" value="add_product" '.allow_modify($db,'everyone_logged_in','','','disabled').' class="btn btn-default" >';
                        echo'Add Product';
                        echo'</button>';
                        echo '<input type="hidden"  name="asset_id" value="'.$details_asset['asset_id'].'">';
                    echo'</div>';
                        
                    echo '</form>';
                echo'</div>';
            }

                
        echo '</div>';





    }
}
function show_block_work_instruction($db,$details_asset){
    $asset_id=$details_asset['asset_id'];
    $all_WI=get_all_WI_asset($db,$asset_id);
    //show($all_WI);
   if ( $_POST['type']=='move' or $_POST['type']=='add_location' or $_POST['type']=='edit' or $_POST['type']=='add_asset'){
    //show($all_WI);
    }else{?>
        <div class="col-sm-4 col-lg-2 asset-WI box-shadow">
            <div class="row"><b>Work Instructions :</b></div>
            <?php
            if(!empty($all_WI)){
            foreach($all_WI as $WI){?>
                <div class="row">
                    <a target="blank"  href="ressource_v2/Work Instruction/<?php echo $WI['document_filename']?>">
                        <button class="btn btn-default" ><?php echo $WI['document_name']?></button>
                    </a>
                </div>
            <?php
            }}?>
        </div>

        <?php
    }
   ?>
    
    <style>
        .asset-WI{
            background:#e9e8ff; /*light gray*/
            border:0.5px solid black; 
            border-radius: 5px;
            padding:10px;
            margin-bottom: 2px;
            margin-top: 2px;
            margin-right: 2px;
            margin-left:2px;
            min-height: 200px ;
            
        }
    </style>
    <?php
}
function show_settings($db,$details_asset){
    echo'<div class="row ">';   
    echo'<div class="col-sm-12 col-lg-8 asset-settings box-shadow">';
        echo'<div class="row"><b>Settings/Notes :</b/></div>';
        echo'<div class="row">';
            echo '<form method="POST" enctype="multipart/form-data">';
            echo '<input type="hidden"  name="asset_id" value="'.$details_asset['asset_id'].'">';
            echo'<div class="col-sm-3 col-lg-4">';
            echo'<input class="form-control" type="file" '.allow_modify($db,'everyone_logged_in','','','disabled').' name="fileToUpload" id="fileToUpload" accept="image/*;capture=camera">';
            echo'</div>';
            echo'<div class="col-sm-6 col-lg-6">';
            echo'<input class="form-control" type="text" '.allow_modify($db,'everyone_logged_in','','','disabled').' name="caption" placeholder="notes/caption">';
            echo'</div>';

            echo'<div class="col-sm-3 col-lg-2">';
            echo'<button class="col-sm-12 btn btn-default" '.allow_modify($db,'everyone_logged_in','','','disabled').' style="font-size:smaller;" type="submit" name="type" value="add_notes"  >
            Add notes
            </button>';
            echo'</div>';
        echo '</form>';
        echo'</div>';    

        echo'<div class="row">';
        $all_notes=get_all_notes($db,$details_asset['asset_id']);
            foreach ($all_notes as &$item){
                echo'<div class="col-sm-12 col-lg-4 setting-item align-middle">';
                    if(!empty($item['assetsetting_path'])){
                        echo'<div class="row">';
                        echo'<a target="blank"  href="setting/'.$item['assetsetting_path'].'">';
                        echo'<img class="attachment" src="setting/'.$item['assetsetting_path'].'" height="200"  >';
                        echo'</a>';
                        echo '</div>';
                    }else{
                        echo'<br><br><br>';
                    }
                    $date_setting=date("Y-m-d",$item['assetsetting_timetag']);
                    $hour_setting=date("H:i",$item['assetsetting_timetag']);
                    echo'<div class="row">'.$date_setting.' '.$hour_setting;
                    if(!empty($item['assetsetting_caption'])){echo' - '.$item['assetsetting_caption']." ";}
                    if($_SESSION['temp']['role_asset_admin']==1 or $item['assetsetting_added_by']==$_SESSION['temp']['id']){    
                        echo '<form method="POST" >';
                        echo'<button type="submit" name="type" value="remove_setting" '.allow_modify($db,'everyone_logged_in','','','disabled').' class="btn btn-default" >';
                        echo '<input type="hidden"  name="asset_id" value="'.$details_asset['asset_id'].'">';
                        echo '<input type="hidden"  name="assetsetting_id" value="'.$item['assetsetting_id'].'">';
                        echo '<input type="hidden"  name="assetsetting_path" value="'.$item['assetsetting_path'].'">';
                        echo '<span class="glyphicon glyphicon-trash" > </span>';
                        echo '</button>';
                        echo '</form>';
                    }
                    echo'</div>';
                echo'</div>';
            }
        echo'</div>'; 
    echo '</div>';

    echo '</div>';
    echo'<script>
    function showhistory() {
        var x = document.getElementById("allhistory");
        if (x.style.display === "block") {
        x.style.display = "none";
        } else {
        x.style.display = "block";
        }
    }
    
    </script>';
        
}
function show_history($db,$details_asset){
    echo'<div class="row ">';
    echo'<div class="col-sm-7 asset-history box-shadow">';
    echo'<div class="row" onclick="showhistory()"><b>History :</b/><i> Click to expand</i></div>';
        echo'<div class="allhistory" id="allhistory">';
        $all_log=get_last_log_movement($db,$details_asset['asset_id']);
        foreach ($all_log as &$item){
            echo'<div class="row">'.$item['assetlog_entry'].'</div>';
        }
        echo '</div>';
    echo '</div>';
    echo '</div>';
}
function find_location_data($db,$asset_id){
    $info=get_last_movement($db,$asset_id);
    
    $row['location_description']=$info['assetmovement_locationto'];
    $row['location_reason']=$info['assetmovement_reason'];
    return $row;

}

function get_last_movement($db,$asset_id){
    $query='SELECT *
    FROM assetmovement
    WHERE assetmovement_assetid=\''.$asset_id.'\'
    Order by assetmovement_timetag desc';
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  //
  return $row;
}

function get_last_log_movement($db,$asset_id){
    $query='SELECT *
    FROM assetlog
    WHERE assetlog_assetid=\''.$asset_id.'\'
    Order by assetlog_timetag desc';
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  //
  return $row;
}

function get_all_notes($db,$asset_id){
    $query='SELECT *
    FROM assetsetting
    WHERE assetsetting_assetid=\''.$asset_id.'\'
    Order by assetsetting_timetag desc';
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  //
  return $row;
}





function save_new_asset($db){
    $timetag_entry=time();
	$date_entry=date("Y-m-d",$timetag_entry);
    $hour_entry=date("H:i",$timetag_entry);
    $asset_name=$_POST['asset_name'];
	$asset_type=$_POST['asset_type'];
	$tool_number=$_POST['asset_tool_number'];
	$other_ref=$_POST['asset_ext_number'];
	$asset_number=$_POST['asset_asset_number'];
    $asset_cavity=$_POST['asset_cavity'];
    $asset_cycle_time=$_POST['asset_cycle_time'];
    $asset_cooling=$_POST['asset_cooling'];
    $employee=$_SESSION['temp']['id'];

	
    $query="INSERT INTO dbo.asset
	( asset_name,
	asset_type,
	asset_tool_number,
	asset_ext_number,
	asset_asset_number,
    asset_cavity,
    asset_cycle_time,
    asset_cooling
	
	) 
	VALUES (
	'$asset_name',
	'$asset_type',
	'$tool_number',
	'$other_ref',
	'$asset_number',
	'$asset_cavity',
    '$asset_cycle_time',
    '$asset_cooling')";	

    //show($query);
    $sql = $db->prepare($query); 

    $sql->execute();

    $asset_id=get_asset_id($db,$asset_name);


    $entry='Asset created by '.$employee.' on the '.$date_entry.' at '.$hour_entry;
    save_log_asset_movement($db,$asset_id,$entry,$timetag_entry,$employee);
    
}

function save_mod_asset($db){
    $timetag_entry=time();
	$date_entry=date("Y-m-d",$timetag_entry);
    $hour_entry=date("H:i",$timetag_entry);
    $asset_name=$_POST['asset_name'];
	$asset_type=$_POST['asset_type'];
	$tool_number=$_POST['asset_tool_number'];
	$other_ref=$_POST['asset_ext_number'];
	$asset_number=$_POST['asset_asset_number'];
    $asset_cavity=$_POST['asset_cavity'];
    $employee=$_SESSION['temp']['id'];
	$asset_id=$_POST['asset_id'];
    $asset_cycle_time=$_POST['asset_cycle_time'];
    $asset_cooling=$_POST['asset_cooling'];
    $query="
    
    UPDATE dbo.asset
     SET
     asset_name='".$asset_name."',
     asset_type='".$asset_type."',
     asset_tool_number='".$tool_number."',
     asset_ext_number='".$other_ref."',
     asset_asset_number='".$asset_number."',
     asset_cavity='".$asset_cavity."',
     asset_cycle_time='".$asset_cycle_time."',
     asset_cooling='".$asset_cooling."'
     WHERE
     asset_id='".$asset_id."'
    ";	

    //show($query);
    $sql = $db->prepare($query); 

    $sql->execute();

    


    $entry='Asset modified by '.$employee.' on the '.$date_entry.' at '.$hour_entry;
    save_log_asset_movement($db,$asset_id,$entry,$timetag_entry,$employee);
    
}

function remove_material($db,$asset_id){
    $timetag_entry=time();
	$date_entry=date("Y-m-d",$timetag_entry);
    $hour_entry=date("H:i",$timetag_entry);
    
    $employee=$_SESSION['temp']['id'];
	
    
    $query=" UPDATE dbo.asset
     SET
     asset_material='0'
     WHERE
     asset_id='".$asset_id."'";	

    //show($query);
    $sql = $db->prepare($query); 

    $sql->execute();

    


    $entry='Material removed by '.$employee.' on the '.$date_entry.' at '.$hour_entry;
    save_log_asset_movement($db,$asset_id,$entry,$timetag_entry,$employee);
}




function add_material($db,$asset_id,$material_id){
    $timetag_entry=time();
	$date_entry=date("Y-m-d",$timetag_entry);
    $hour_entry=date("H:i",$timetag_entry);
    
    $employee=$_SESSION['temp']['id'];
	
    
    $query=" UPDATE dbo.asset
     SET
     asset_material='".$material_id."'
     WHERE
     asset_id='".$asset_id."'
    ";	

    //show($query);
    $sql = $db->prepare($query); 

    $sql->execute();

    


    $entry='Material added by '.$employee.' on the '.$date_entry.' at '.$hour_entry;
    save_log_asset_movement($db,$asset_id,$entry,$timetag_entry,$employee);
}

function add_notes($db){
    $timetag=time();
    $timetag_entry=time();
	$date_entry=date("Y-m-d",$timetag_entry);
    $hour_entry=date("H:i",$timetag_entry);
	$asset_id=$_POST['asset_id'];
    $caption=$_POST['caption'];
    $employee=$_SESSION['temp']['id'];
    $namefile=$asset_id."-".$timetag;
    if(!empty($_FILES['fileToUpload']['name'])){ $path=upload_image_setting($db,$namefile);}
   
    //show($namefile);


   $query="INSERT INTO dbo.assetsetting
	( assetsetting_timetag,
	assetsetting_caption,
	assetsetting_assetid,
	assetsetting_added_by,
    assetsetting_path
	
	) 
	VALUES (
	'$timetag',
	'$caption',
	'$asset_id',
	'$employee',
	'$path')";	

    //show($query);
    $sql = $db->prepare($query); 

    $sql->execute();


    $entry='Notes added by '.$employee.' on the '.$date_entry.' at '.$hour_entry;
    save_log_asset_movement($db,$asset_id,$entry,$timetag_entry,$employee);

}

function get_asset_id($db,$asset_name){
    $query='SELECT asset_id
    FROM asset
    WHERE asset_name=\''.$asset_name.'\'  
    order by asset_id DESC  ';
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  //
  return $row[0];
}

function delete_asset($db){
    
    $asset_id=$_POST['asset_id'];
	
    $query="Update dbo.asset
	SET asset_delete=1
    WHERE asset_id='$asset_id'";	

    //show($query);
    $sql = $db->prepare($query); 

    $sql->execute();

    


    $entry='Asset deleted by '.$employee.' on the '.$date_entry.' at '.$hour_entry;
    save_log_asset_movement($db,$asset_id,$entry,$timetag_entry,$employee);

}


function get_all_location($db){
    $query='SELECT assetmovement_locationto
    FROM assetmovement
    group by assetmovement_locationto
    Order by assetmovement_locationto asc';
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  //
  return $row;
}
function get_all_asset_type($db){
    $query='SELECT assettype_name
    FROM asset_type
    order by assettype_name asc';
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $temp=$sql->fetchall();
  foreach($temp as $type){
    $return[]=$type['assettype_name'];
  }
  return $return;
}

function get_all_material($db){
    $query='SELECT assetmaterial_name,assetmaterial_id
    FROM assetmaterial
    ORDER BY assetmaterial_name ASC';
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  //
  return $row;
}

function get_list_of_product($db){
    $query='SELECT Product_Code
    FROM List_Document
    ORDER BY Product_Code ASC';
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  //
  return $row;
}

function get_info_material($db,$material_id){
    $query='SELECT *
    FROM assetmaterial
    WHere assetmaterial_id=\''.$material_id.'\'';
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  //
  return $row;
}

function get_product_asset($db,$asset_id){
    $query='SELECT *
    FROM assetproduct
    left join
    List_Document on List_document.Product_Code=assetproduct_productcode
    WHERE assetproduct_assetid=\''.$asset_id.'\'  
     ';
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  //
  return $row;

}

function get_all_WI_asset($db,$asset_id){
    $query="SELECT document_name,document_number,document_issue,document_filename
    FROM assetproduct
    left join
    doc_link on doclink_productcode=assetproduct_productcode and doclink_doctype='Work Instruction'
	left join document on document_number=doclink_docnumber and document_active=1
    
	WHERE assetproduct_assetid='$asset_id'  and document_number is not null
	group by document_name,document_number,document_issue,document_filename";
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  //
  return $row;
}



function save_asset_movement($db){
    $timetag_move=$_POST['assetmovement_timetag'];
	$date_move=(date("Y-m-d",$_POST['assetmovement_timetag']));;
	$asset_id=$_POST['asset_id'];
	$moved_by=$_SESSION['temp']['id'];
	$location_to=$_POST['assetmovement_locationto'];
	$reason=$_POST['assetmovement_reason'];
	$notes=$_POST['assetmovement_note'];

    $query="INSERT INTO dbo.assetMovement
	( assetmovement_timetag,
	assetmovement_date,
	assetmovement_assetid,
	assetmovement_movedby,
	assetmovement_locationto,
	assetmovement_reason,
    assetmovement_notes
	
	) 
	VALUES (
	'$timetag_move',
	'$date_move',
	'$asset_id',
	'$moved_by',
	'$location_to',
	'$reason',
	'$notes')";	

    //show($query);
    $sql = $db->prepare($query); 

    $sql->execute();

    $entry='Asset moved to '.$location_to.' by '.$moved_by.' on the '.$date_move.' at '.date("H:i",$_POST['assetmovement_timetag']).' for '.$reason;
    save_log_asset_movement($db,$asset_id,$entry,$timetag_move,$moved_by);

}

function save_log_asset_movement($db,$asset_id,$entry,$timetag,$employee){
     $query="INSERT INTO dbo.assetlog
	( assetlog_timetag,
	assetlog_entry,
	assetlog_assetid,
	assetlog_employee
	
	) 
	VALUES (
	'$timetag',
	'$entry',
	'$asset_id',
	'$employee')";	

    //show($query);
    $sql = $db->prepare($query); 

    $sql->execute();



}


function update_filter($db){
    $filter='';
    if(!empty($_SESSION['temp']['search']))
    {
        $keyword=$_SESSION['temp']['search'];




        $filter=$filter."and (asset_name like '%".$keyword."%'
        or asset_asset_number like '%".$keyword."%'
        or asset_tool_number like '%".$keyword."%'
        or asset_type like '%".$keyword."%'
        or asset_ext_number like '%".$keyword."%'
        or assetmaterial_name like '%".$keyword."%'
        or asset_id like '%".$keyword."%'
        or assetmovement_locationto like '%".$keyword."%'
        or assetmaterial_name like '%".$keyword."%'
        or assetmaterial_name like '%".$keyword."%'

        ) ";
    }
    if(empty($_SESSION['temp']['show_all_location']) or $_SESSION['temp']['show_all_location']==2){

        $filter=$filter." and  assetmovement_locationto='Sicame QLD'";
    }
    //show($filter);

    return $filter;

}

function update_sort($db){
    $sort='ORDER BY asset_type ASC,asset_name ASC';
    if(!empty($_SESSION['temp']['sort']))
    {
        $sort='ORDER BY asset_tool_number ASC,asset_type ASC,asset_name ASC';

    }
   
    //show($filter);

    return $sort;

}





function load_all_asset($db,$filter='',$sort='ORDER BY asset_type ASC,asset_name ASC'){
    $filter=$filter.'and asset_delete=0';
    //show($sort);
    $query='
  
    SELECT *
    FROM asset
    left join assetmaterial on asset_material=assetmaterial_id
    left join ( SELECT TOP (1000) [assetmovement_timetag]
      
      ,[assetmovement_assetid],assetmovement_locationto
     
      
  FROM [barcode].[dbo].[assetmovement]

  inner join(
    SELECT  max([assetmovement_timetag]) as max_timetag,assetmovement_assetid as asset_id
     FROM [barcode].[dbo].[assetmovement]  
  Group BY [assetmovement_assetid])as t2 on [assetmovement_timetag]=t2.max_timetag and assetmovement_assetid=t2.asset_id)as t3 on asset_id=t3.assetmovement_assetid
  

    WHERE 1=1 '.$filter.'
     '.$sort;
  //show($query);
  $sql = $db->prepare($query); 
  //if($_SESSION['temp']['id']=='CorentinHillion'){show($query);}
  $sql->execute();

  $row=$sql->fetchall();
  //
  return $row;
}

function get_asset_details($db,$asset_id){
    $query='SELECT *
    FROM asset
    WHERE asset_id=\''.$asset_id.'\'';
  
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  //
  return $row;
}

function navbar_asset($db){


    if ( $_POST['type']=='detail' or $_POST['type']=='move' or $_POST['type']=='save' or $_POST['type']=='add_location' or $_POST['type']=='add_asset' or $_POST['type']=='edit'){
        echo'<div class="row">';
            echo'<div class="col-lg-10 col-sm-9 ">';
            echo'</div>';
            echo'<div class="col-lg-2 col-sm-2">';
                echo'<form method="POST">';
                $value='list';
                $caption='Back to the list';
                if($_POST['type']=='edit' or $_POST['type']=='move' ){
                    echo '<input type="hidden"  name="asset_id" value="'.$_POST['asset_id'].'">';
                    $value="detail";
                    $caption='Back';
                }
                
                echo'<br><button type="submit" name="type" value="'.$value.'"  class="btn btn-default" >
                                    <span class="glyphicon glyphicon-step-backward" > '.$caption.'</span>
                </button><br>&nbsp';
                
                echo'</form>';
            echo'</div>';
        echo'</div>';
    }else
    {
        echo'<div class="row">';
            echo'<div class="col-lg-2 col-sm-3">';
                echo'<form method="POST">';
                
                
                echo'<br><button type="submit" name="type" value="add_asset" '.allow_modify($db,'admin_asset','','','disabled').' class="btn btn-default" >
                                    <span class="glyphicon glyphicon-plus" > Add an Asset</span>
                </button><br>&nbsp';
                
                echo'</form>';
            echo'</div>';
            echo'<div class="col-lg-1 col-sm-2">';
                echo'<form method="POST">';
                
                echo '<br><input class="form-control" type="text"   name="search_word" placeholder="search Asset">';
                
            echo'</div>';
            echo'<div class="col-lg-1 col-sm-3">';
                
                echo'<br><button type="submit" name="type" value="search" class="btn btn-default" >
                                    <span class="glyphicon glyphicon-search" > Search</span>
                                    </button><br>';
                
                echo'</form>';
            echo'</div>';
            echo'<div class="col-lg-1 col-sm-3">';
                if(!empty( $_SESSION['temp']['search'])){
                    echo'<br>
                    <button disabled type="submit" name="type" value="search" class="btn btn-default" >Active Filter: '.$_SESSION['temp']['search'].'
                    </button><br>';
                }
               
                
                echo'</form>';
            echo'</div>';

           


            echo'<div class="col-lg-1 col-sm-1">';
            
            
            
            
            echo'</div>';
            echo'<div class="col-lg-2 col-sm-2">';
                
            echo'</div>';
            echo'<div class="col-lg-2 col-sm-3">';
                echo'<form method="POST">';
                if(empty($_SESSION['temp']['show_all_location']) or $_SESSION['temp']['show_all_location']==2){
                    echo'<br><button type="submit" name="type" value="show_all_location" class="btn btn-default" >
                                    <span class="glyphicon glyphicon-signal" > Show All Location</span>
                                    </button><br>';
                }else{
                    echo'<br><button type="submit" name="type" value="show_all_location" class="btn btn-default" >
                                    <span class="glyphicon glyphicon-signal" > Show Only Sicame QLD</span>
                                    </button><br>';
                }
                
                echo'</form>';



                
            echo'</div>';
            
            
        
            echo'<div class="col-lg-3 ">';
            echo'<form method="POST">';
            //echo'<br><button type="submit" name="type" value="show_dashboard" class="btn btn-default" >
            //                        <span class="glyphicon glyphicon-signal" > Dashboard</span>
             //                       </button><br>';
                echo'</form>';
            echo'</div>';   
		
	    echo'</div>';	
    }

   
		
}

function upload_image_asset($db){
	//show('test');
    $target_dir = "asset/";

	if($_FILES['fileToUpload']['type']=='application/pdf'){
		$extension='pdf';
	}else{
		$extension='jpg';
	}
    $asset_id=$_POST['asset_id'];
	$new_name=$_POST['asset_id'].".".$extension;


	$target_file = $target_dir .$new_name ;
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	if (file_exists($target_file)) {
	echo "Sorry, file already exists.";
	$uploadOk = 0;
	}
	// Check file size


	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} 
	else 
	{
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			//echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
			
			//add the line in the database
			
			$query=" UPDATE dbo.asset
            SET
            asset_picture='".$new_name."'
            WHERE
            asset_id='".$asset_id."'
            ";	
				
				
				//show($query);
				
				$sql = $db->prepare($query); 

				$sql->execute();
				
            require 'src/claviska/SimpleImage.php';
            $image_path='asset/'.$new_name;	
			$new_image_path='asset/mini-'.$new_name;
            
            create_mini_image($image_path,$new_image_path);
				
			
		} else {
			echo "Sorry, there was an error uploading your file.". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
		}
	}
		
}

function upload_image_setting($db,$namefile){
	//show('test');
    $target_dir = "setting/";

	if($_FILES['fileToUpload']['type']=='application/pdf'){
		$extension='pdf';
	}else{
		$extension='jpg';
	}
    $asset_id=$_POST['asset_id'];
	$new_name=$namefile.".".$extension;


	$target_file = $target_dir .$new_name ;
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	if (file_exists($target_file)) {
	echo "Sorry, file already exists.";
	$uploadOk = 0;
	}
	// Check file size


	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} 
	else 
	{
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			//echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
			
			//add the line in the database
			
			return $new_name;
				
				
				
				
			
		} else {
			echo "Sorry, there was an error uploading your file.". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
		}
	}
		
}

function remove_image_asset($db){
    $query=" UPDATE dbo.asset
            SET
            asset_picture=''
            WHERE
            asset_id='".$_POST['asset_id']."'
            ";	
				
				
    //show($query);
    
    $sql = $db->prepare($query); 

    $sql->execute();

    //delete file
	$target_dir = "asset/";
	$new_name=$_POST['asset_id'].".jpg";
	
	$target_file = $target_dir .$new_name ;
	unlink( $target_file);

}

function remove_setting($db){
    $timetag=time();
    $timetag_entry=time();
	$date_entry=date("Y-m-d",$timetag_entry);
    $hour_entry=date("H:i",$timetag_entry);
	$asset_id=$_POST['asset_id'];
   
    $employee=$_SESSION['temp']['id'];
   
    $query=" delete from dbo.assetsetting
            
            WHERE
            assetsetting_id='".$_POST['assetsetting_id']."'
            ";	
				
				
    //show($query);
    
    $sql = $db->prepare($query); 

    $sql->execute();

    //delete file
	$target_dir = "setting/";
	$new_name=$_POST['assetsetting_path'];
	
	$target_file = $target_dir.$new_name ;
	//show($target_file);
	unlink( $target_file);


    $entry='Notes removed by '.$employee.' on the '.$date_entry.' at '.$hour_entry;
    save_log_asset_movement($db,$asset_id,$entry,$timetag_entry,$employee);
}

function remove_product_asset($db){

    $query=" delete from dbo.assetproduct
            
    WHERE
    assetproduct_assetid='".$_POST['asset_id']."' and assetproduct_productcode='".$_POST['product_code']."'
    ";	
        
        
    //show($query);

    $sql = $db->prepare($query); 

    $sql->execute();


}


function add_product_asset($db){
    $query="INSERT INTO dbo.assetproduct
	( assetproduct_assetid,
	assetproduct_productcode
	
	) 
	VALUES (
	'".$_POST['asset_id']."',
	'".$_POST['new_product_code']."')";	

    //show($query);
    $sql = $db->prepare($query); 

    $sql->execute();
}

function create_mini_image($image_path,$new_image_path){
    
    //show('test');
    try {
        $image=NULL;
        $image = new \claviska\SimpleImage();

        // Magic! ✨
        $image
        ->fromFile($image_path)                     // load image.jpg
        //->autoOrient()                              // adjust orientation based on exif data
        ->resize(50, 50)                          // resize to 320x200 pixels
        //->flip('x')                                 // flip horizontally
        //->colorize('DarkBlue')                      // tint dark blue
        //->border('black', 10)                       // add a 10 pixel black border
        //->overlay('img/Sicame02.png', 'bottom right')  // add a watermark image
        ->toFile($new_image_path, 'image/jpeg')      // convert to PNG and save a copy to new-image.png
        // ->toScreen();                               // output to the screen
    ;
    //show($image);
        // And much more! 💪
        return;
    } catch(Exception $err) {
        // Handle errors
        echo $err->getMessage();
    }
}



?>