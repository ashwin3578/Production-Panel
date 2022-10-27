<?php

function manage_post_manage_users($db){
    show_debug();
    
    if($_POST['action']=='load_user'){
        load_user($db);
    }
    if($_POST['action']=='change_role'){
        change_role($db);
        load_user($db);
    }
}
function general_view_manage_users($db){?>
    <div class="row">
        <div class="col-xs-2 list_user"><?php show_list_users($db);?></div>
        <!--<div class="col-xs-2 list_roles"><?php //show_list_roles($db);?></div>-->
        <div class="col-xs-4 manage_users">
            <div class="row"><?php //manage_users($db);?></div>
            <div class="row"><?php //show_list_roles_permission($db);?></div>
        </div>
    </div>
    <style>
       .manage_users{
        position:sticky;
        top:0;
       } 
    </style>

    <?php
}

function show_list_users($db){
    ?>
     <div class="row header_user">List of Users</div>
    <?php
    foreach(get_all_users($db) as $user){?>
        <div class="row line_user" onclick="load_user('<?php echo $user['employee_code']?>')">
            <div class="col-xs-6"><?php echo $user['employee_name']?></div>
            <div class="col-xs-6"><?php echo $user['employee_lastname']?></div>
            <!--<div class="col-xs-6"><?php echo $user['employee_email']?></div>-->
            <!--<div class="col-xs-1"><span class="glyphicon glyphicon-arrow-right"></span></div>-->
        </div>
    <?php } ?>
        <style>
                .line_user{
                border: 0.25px solid rgb(211 211 211);
                border-radius: 1rem;
                text-align: center;
                padding: 6px;
                box-shadow: 5px 5px 10px rgb(0 0 0 / 10%);
                margin-top: 3px;
                }
        </style>
        <script>
            function load_user(employee_code){
                var request =$.ajax({
                    type:'POST',
                    url:'manage_employee-ajax.php',
                    data: {employee_code:employee_code,action:'load_user'},
                    success:function(html){
                        $('.manage_users').empty().append(html);
                    }
                });
            }
                
        </script>
    <?php
}
function show_list_roles($db){
    ?>
    <div class="row header_user">List of Roles</div>
   <?php
   foreach(get_all_role($db) as $role){?>
        <div class="row line_user">
            <div class="col-xs-12"><?php echo $role['role_name']?></div>
            
        </div>
        <?php } ?>
        <style>
                .line_user{
                border: 0.25px solid rgb(211 211 211);
                border-radius: 1rem;
                text-align: center;
                padding: 6px;
                box-shadow: 5px 5px 10px rgb(0 0 0 / 10%);
                margin-top: 3px;
                }
        </style>
    <?php
}
function show_list_roles_permission($db){
    ?>
    <div class="row header_user">List of Permissions</div>
   <?php
   foreach(get_all_role_permission($db) as $role_permission){?>
        <div class="row line_user">
            <div class="col-xs-12 <?php echo $role_permission['rolepermission_name']?>"><?php echo $role_permission['rolepermission_caption']?></div>
            
        </div>
        <?php } ?>
        <style>
                .line_user{
                border: 0.25px solid rgb(211 211 211);
                border-radius: 1rem;
                text-align: center;
                padding: 6px;
                box-shadow: 5px 5px 10px rgb(0 0 0 / 10%);
                margin-top: 3px;
                }
        </style>
    <?php
}

function get_all_users($db){
	$query="SELECT * FROM dbo.employee  order by employee_fullname ASC";
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetchall();
	
	return $row;
		
}

function get_all_role($db){
	$query="SELECT * FROM dbo.role  order by role_name ASC";
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetchall();
	
	return $row;
		
}
function get_all_role_permission($db){
	$query="SELECT * FROM dbo.role_permission  order by rolepermission_name ASC";
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetchall();
	
	return $row;
		
}


function load_user($db){
    //show($_SESSION);
    if(!empty($_SESSION['temp']['role_barcode_admin'])){$allow=1;}
    $employee_code=$_POST['employee_code'];
    $query="SELECT * FROM dbo.role_attribution LEFT JOIN dbo.role on role_name=attribution_role_id  WHERE attribution_employee_code ='$employee_code' order by role_name ASC";
    $sql = $db->prepare($query); 
    $sql->execute();
    $role_attributions=$sql->fetchall();
    $query="SELECT * FROM dbo.role_permission  order by rolepermission_name ASC";
    $sql = $db->prepare($query); 
    $sql->execute();
    $permissions=$sql->fetchall();
    foreach($permissions as $permission){
        foreach($role_attributions as $role){
            if($role[$permission['rolepermission_name']]==1){
                $current_permission[$permission['rolepermission_name']]=1;
            }
            $current_role[$role['role_name']]=$role['role_name'];
        }
        
    }
    //show($current_permission);
    ?>
    <div class="row header_role">List of Roles - <?php echo $_POST['employee_code']?></div>
   <?php
   foreach(get_all_role($db) as $role){
        if(!empty($current_role[$role['role_name']])){$class='line_selected';$glyphicon='minus';}else{$class='';$glyphicon='plus';};?>
        <div class="row line_role <?php echo $class?>">
            <div class="col-xs-8 "><?php echo $role['role_name']?></div>
            <div class="col-xs-2 "><?php info_button(1,$role['role_description'])?></div>
            
            <?php  if(!empty($_SESSION['temp']['role_barcode_admin'])){?>
                <div class="col-xs-2 " onclick="change_role('<?php echo $_POST['employee_code']?>','<?php echo $role['role_name']?>')"><span class="glyphicon glyphicon-<?php echo $glyphicon?>"></span></div>
            <?php } ?>
            
        </div>
        <?php } ?>
        <style>
                .line_role{
                border: 0.25px solid rgb(211 211 211);
                border-radius: 1rem;
                text-align: center;
                padding: 6px;
                box-shadow: 5px 5px 10px rgb(0 0 0 / 10%);
                margin-top: 3px;
                }
                .line_selected{
                background:#649ed9;
                }
        </style>
        <script>
            function change_role(employee_code,role_name){
                var request =$.ajax({
                    type:'POST',
                    url:'manage_employee-ajax.php',
                    data: {employee_code:employee_code,role_name:role_name,action:'change_role'},
                    success:function(html){
                        $('.manage_users').empty().append(html);
                    }
                });
            }
                
        </script>
    <?php
}
function change_role($db){
    $employee_code=$_POST['employee_code'];
    $role_name=$_POST['role_name'];
    $query="SELECT * FROM dbo.role_attribution WHERE attribution_employee_code ='$employee_code' and attribution_role_id  ='$role_name' ";
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();
    if(empty($row)){
        $query="INSERT INTO dbo.role_attribution( attribution_employee_code,attribution_role_id) VALUES ('$employee_code','$role_name')";
    }else{
        $query="DELETE FROM dbo.role_attribution WHERE attribution_employee_code ='$employee_code' and attribution_role_id  ='$role_name' ";
    }
    //show($query);
    $sql = $db->prepare($query); 
    $sql->execute();
}


?>