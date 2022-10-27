<?php 
function manage_post_admin(){
    show_debug();
    if($_POST['action']=='Save'){
        save_setting();
    }
}
function navbar_admin(){}
function general_view_admin(){?>
    <div class="row all_view">
        <div class="col-xs-6 "><?php show_list_setting()?></div>
        <div class="col-xs-3 "></div>
        <div class="col-xs-2 "></div>
    </div>
    <style>
        .all_view{
            text-align: center;
        }
    </style>
    
    <?php
}

function show_list_setting(){
    $db=$GLOBALS['db'];
    $query="SELECT *
    FROM setting
    order by setting_name";

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $all_setting=$sql->fetchall();
    //show(get_setting('email_production_assistant'))?>
    <div class="row header_setting">All Setting</div>
    <div class="list_table">
    <?php
    foreach($all_setting as $setting){?>
        <form method="POST" id="<?php echo $setting['TABLE_NAME']?>">
        <div class="row row_setting "style="">
           
            <div class="col-xs-6  "><div class="form-control btn-primary"><?php echo $setting['setting_name']?></div></div>
            <div class="col-xs-4  "><input name="setting_value"  type="text" class="form-control" value="<?php echo $setting['setting_value']?>"></div>
            <div class="col-xs-2  "><input name="action"  type="submit" class="form-control" value="Save"></div>
            <div class="col-xs-12  ">
                <div class="panel panel-default" role="alert">
                    <span class="glyphicon glyphicon-info"></span> <?php echo $setting['setting_note']?>
                </div>
            </div>
        </div>
        
        <input name="setting_id" value="<?php echo $setting['setting_id']?>" type="hidden">
        
        </form>
        <?php

    }?>
    </div>
    <style>
        .header_setting{
            font-size:25px;
        }
        .row_setting{
            margin-bottom:5px;
            padding: 15px;
            border:solid 1px;
            border-radius:20px;
        }
        .list_table{
            
            text-align: center;
        }
        .btn_table{
            border-radius: 8px;
            border: solid 1px #a9a1a1;
            box-shadow: 3px 1px 1px;
        }
    </style>
    <?php
}

function save_setting(){
    $db=$GLOBALS['db'];
    $setting_value=$_POST['setting_value'];
    $setting_id=$_POST['setting_id'];
    $query="UPDATE setting
    SET setting_value='$setting_value'
    WHERE setting_id=$setting_id";

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

}



?>