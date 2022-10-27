<?php

function manage_post_manage_group($db){
    show_debug();
    
    if($_POST['action']=='add_user'){
        add_user($db);
        refresh_div_once('list_group');
        load_group($db);
       
    }
    if($_POST['action']=='remove_user'){
        remove_user($db);
        refresh_div_once('list_group');
        load_group($db);
    }
    if($_POST['action']=='change_leader'){
        change_leader($db);
        refresh_div_once('list_group');
        load_group($db);
    }


    if($_POST['action']=='load_group'){
        load_group($db);
    }
    
}
function general_view_manage_group($db){?>
    <div class="row" >
        <div id="list_group">
            <div class="col-xs-2 list_group" ><?php show_list_group($db);?></div>
        </div>
        <!--<div class="col-xs-2 list_roles"><?php //show_list_roles($db);?></div>-->
        <div class="col-xs-4 manage_group">
            <div class="row"><?php //manage_users($db);?></div>
            <div class="row"><?php //show_list_roles_permission($db);?></div>
        </div>
    </div>
    <style>
       .manage_group{
        position:sticky;
        top:0;
       } 
    </style>

    <?php
}

function show_list_group($db){
    ?>
     <div class="row header_user">List of Group</div>
    <?php
    foreach(get_all_group($db) as $group){?>
        <div class="row line_group" onclick="load_group('<?php echo $group['group_id']?>')">
            <div class="col-xs-6"><?php echo $group['group_name']?></div>
            <div class="col-xs-6"><?php echo $group['employee_count']+0?> <span class="glyphicon glyphicon-user"></span></div>
            <!--<div class="col-xs-6"><?php echo round(0+$group['employee_email']+0)?></div>-->
            <!--<div class="col-xs-1"><span class="glyphicon glyphicon-arrow-right"></span></div>-->
        </div>
    <?php } ?>
        <style>
                .line_group{
                border: 0.25px solid rgb(211 211 211);
                border-radius: 1rem;
                text-align: center;
                padding: 6px;
                box-shadow: 5px 5px 10px rgb(0 0 0 / 10%);
                margin-top: 3px;
                }
        </style>
        <script>
            function load_group(group_id){
                var request =$.ajax({
                    type:'POST',
                    url:'group-ajax.php',
                    data: {group_id:group_id,action:'load_group'},
                    success:function(html){
                        $('.manage_group').empty().append(html);
                    }
                });
            }
                
        </script>
    <?php
}
function get_all_users($db,$option=''){
	$query="SELECT * FROM dbo.employee WHERE 1=1 $option order by employee_fullname ASC";
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetchall();
	
	return $row;
		
}
function get_all_group($db){
    $query="SELECT * 
    FROM dbo.employee_group  
    left join (
        SELECT count(groupallocation_employee)as employee_count ,
        groupallocation_groupid 
        from employee_group_allocation 
        GROUP by groupallocation_groupid)as a on a.groupallocation_groupid=group_id
    order by group_name ASC";
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetchall();
	//show($query);
	return $row;
}


function load_group($db){
    //show($_SESSION);
    
    $group_id=$_POST['group_id'];
    $query="SELECT * FROM employee_group 
        WHERE group_id ='$group_id'";
    $sql = $db->prepare($query); 
    $sql->execute();
    $group=$sql->fetch();
    $query="SELECT * FROM dbo.employee_group_allocation 
    left join employee_group on group_id=groupallocation_groupid 
    left join employee on employee_code=groupallocation_employee 
    WHERE [groupallocation_groupid] ='$group_id' order by groupallocation_employee ASC";
    $sql = $db->prepare($query); 
    $sql->execute();
    $list_user=$sql->fetchall();
    
    //show($current_permission);
    ?>
    <div class="row header_role">List of User - <?php echo $group['group_name']?></div>
    <?php  if(!empty($_SESSION['temp']['role_barcode_admin'])){?>
    <div class="row line_role ">
            <div class="col-xs-8 "><select class="form-control" id="user_to_add" onchange="add_user('<?php echo $group_id?>')">
            <option selected disabled>Select users to add</option>
            <?php
                if(!empty($list_user)){
                    $option='';
                    foreach($list_user as $user){
                    $option=$option." and employee_code<>'".$user['groupallocation_employee']."'";
                    }
                }
                foreach(get_all_users($db,$option)as $user){?>
                    <option><?php echo$user['employee_code']?></option>
                    <?php
                }?>
            </select></div>
            <div class="col-xs-2 "></div>
            <div class="col-xs-2 " ></span></div>
           
            
        </div>
    </div>
   <?php
    }
   foreach($list_user as $user){
       
       if ($user['groupallocation_leader']==1){$glyphicon='glyphicon-star';}else{$glyphicon='glyphicon-star-empty';}
       ?>
        <div class="row line_role ">
            <div class="col-xs-8 "><?php echo $user['employee_fullname']?></div>
            <div class="col-xs-2 ">
                <span 
                <?php  if(!empty($_SESSION['temp']['role_barcode_admin'])){?>onclick="change_leader('<?php echo $user['employee_code']?>','<?php echo $group_id?>')" <?php }?>
                class="glyphicon <?php echo$glyphicon?>"></span>
            </div>
            
            <?php  if(!empty($_SESSION['temp']['role_barcode_admin'])){?>
                <div class="col-xs-2 " onclick="remove_user('<?php echo $user['employee_code']?>','<?php echo $group_id?>')"><span class="glyphicon glyphicon-trash"></span></div>
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
            function remove_user(employee_code,group_id){
                var request =$.ajax({
                    type:'POST',
                    url:'group-ajax.php',
                    data: {employee_code:employee_code,group_id:group_id,action:'remove_user'},
                    success:function(html){
                        $('.manage_group').empty().append(html);
                    }
                });
            }
            function add_user(group_id){
                employee_code=document.getElementById('user_to_add').value;
                var request =$.ajax({
                    type:'POST',
                    url:'group-ajax.php',
                    data: {employee_code:employee_code,group_id:group_id,action:'add_user'},
                    success:function(html){
                        $('.manage_group').empty().append(html);
                    }
                });
            }
            function change_leader(employee_code,group_id){
                var request =$.ajax({
                    type:'POST',
                    url:'group-ajax.php',
                    data: {employee_code:employee_code,group_id:group_id,action:'change_leader'},
                    success:function(html){
                        $('.manage_group').empty().append(html);
                    }
                });
            }
                
        </script>
    <?php
}
function add_user($db){
    $employee_code=$_POST['employee_code'];
    $group_id=$_POST['group_id'];
    $query="INSERT INTO employee_group_allocation( groupallocation_employee,groupallocation_groupid) VALUES ('$employee_code','$group_id')";
    $sql = $db->prepare($query); 
    $sql->execute();
    //show($query);
    check_assign_leader($db,$group_id);

}
function remove_user($db){
    $employee_code=$_POST['employee_code'];
    $group_id=$_POST['group_id'];
    $query="DELETE FROM employee_group_allocation WHERE groupallocation_employee ='$employee_code' and groupallocation_groupid  ='$group_id' ";
    $sql = $db->prepare($query); 
    $sql->execute();
    check_assign_leader($db,$group_id);
}
function change_leader($db){
    $employee_code=$_POST['employee_code'];
    $group_id=$_POST['group_id'];
    $query="SELECT * FROM dbo.employee_group_allocation WHERE groupallocation_employee ='$employee_code' and groupallocation_groupid  ='$group_id' ";
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();
    //if(empty($row['groupallocation_leader'])){
    $value=1;
    $prequery="UPDATE employee_group_allocation SET groupallocation_leader=NULL WHERE groupallocation_groupid  ='$group_id';";

    $query=$prequery."UPDATE employee_group_allocation SET groupallocation_leader=$value WHERE groupallocation_employee ='$employee_code' and groupallocation_groupid  ='$group_id'";
    //show($query);
    $sql = $db->prepare($query); 
    $sql->execute();
    //}

    //if no leader now, one is 
}
function check_assign_leader($db,$group_id){
    $query="SELECT count(groupallocation_leader)as nbr_leader FROM dbo.employee_group_allocation WHERE groupallocation_groupid  ='$group_id' ";
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();
    $nbr_leader=$row['nbr_leader'];
    $query="SELECT TOP 1 * FROM dbo.employee_group_allocation WHERE groupallocation_groupid  ='$group_id' order by groupallocation_employee";
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();
    $defaultleader=$row['groupallocation_employee'];
    if($nbr_leader<>1){
    
        $prequery="UPDATE employee_group_allocation SET groupallocation_leader=NULL WHERE groupallocation_groupid  ='$group_id';";

        $query=$prequery."UPDATE employee_group_allocation SET groupallocation_leader=1 WHERE groupallocation_employee ='$defaultleader' and groupallocation_groupid  ='$group_id'";
        //show($query);
        $sql = $db->prepare($query); 
        $sql->execute();
    }

    //if no leader now, one is 
}



?>