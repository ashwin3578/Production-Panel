<?php
$db=$GLOBALS['db'];

function manage_post_moulding_planning(){
    
    if ($_POST['action']=='load_date'){
        if($_POST['date_type']=='start'){
            $_POST['date_start']=$_POST['date'];
            $_POST['shift_start']=$_POST['shift'];
        }else{
            $_POST['date_end']=$_POST['date'];
            $_POST['shift_end']=$_POST['shift'];
        }
       
        

        
    }
    show_debug();

    if(empty($_SESSION['temp']['date_productlist'])){
        $_SESSION['temp']['date_productlist']=date('Y-m-d',strtotime(date('Y-m-d',time()-3600*24).' next friday'));
    }
    if(empty($_POST['date_productlist'])){
        $_POST['date_productlist']=$_SESSION['temp']['date_productlist'];
    }else{
        $_SESSION['temp']['date_productlist']=$_POST['date_productlist'];
    }


    if(!empty($_POST['change_view'])){
        $_SESSION['temp']['change_view']=$_POST['change_view'];
    }else{
        if(empty( $_SESSION['temp']['change_view'])){
            $_SESSION['temp']['change_view']='Tool';
        }
        $_POST['change_view']=$_SESSION['temp']['change_view'];
    }
    if(!empty($_POST['date_to_show_moulding'])){
        $_SESSION['temp']['date_to_show_moulding']=$_POST['date_to_show_moulding'];
    }
    if(empty($_SESSION['temp']['date_to_show_moulding'])){
        $date=date('Y-m-d',time()+3600*24);
        $date=date('Y-m-d',strtotime($date." last monday"));
        $_SESSION['temp']['date_to_show_moulding']=$date;
    }
    if(!empty($_POST['date_start'])){
        $_SESSION['temp']['date_moulding']['date_start']=$_POST['date_start'];
        $_SESSION['temp']['date_moulding']['shift_start']=$_POST['shift_start'];
    }else{
        $_POST['date_start']=$_SESSION['temp']['date_moulding']['date_start'];
        $_POST['shift_start']=$_SESSION['temp']['date_moulding']['shift_start'];
    }
    if(!empty($_POST['date_end'])){
        $_SESSION['temp']['date_moulding']['date_end']=$_POST['date_end'];
        $_SESSION['temp']['date_moulding']['shift_end']=$_POST['shift_end'];
    }else{
        $_POST['date_end']=$_SESSION['temp']['date_moulding']['date_end'];
        $_POST['shift_end']=$_SESSION['temp']['date_moulding']['shift_end'];
    }
    



    if(!empty($_POST['action']=='update_sort')){
        update_asset_sort($_POST['machine_id'],$_POST['asset_sort_order']);
    }
    if(!empty($_POST['action']=='save_planning')){
        save_planning();
    }
    if ($_POST['action']=='delete_planning'){
        delete_planning();
    }
    if ($_POST['action']=='load_product'){
        show_allocation_details($_POST['m_schedule_id']);
    }
    if ($_POST['action']=='load_tool'){
        show_allocation_details($_POST['m_schedule_id']);
    }
    if ($_POST['action']=='load_block'){
        show_allocation_details($_POST['m_schedule_id']);
    }
    if ($_POST['action']=='load_date'){
        show_allocation_details();
    }
    
    
}

function navbar_moulding_planning(){
    $date=$_SESSION['temp']['date_to_show_moulding'];
    $date_minus_7=date('Y-m-d',strtotime($date." -7 days"));
    $date_plus_7=date('Y-m-d',strtotime($date." +7 days"));
    $date_minus_1=date('Y-m-d',strtotime($date." -1 days"));
    $date_plus_1=date('Y-m-d',strtotime($date." +1 days"));
    ?>
    <div class="row" style="margin-bottom:5px;">
            <div class="col-xs-1">
                <form method="POST">
                <button class="btn btn-primary form-control" name="date_to_show_moulding" onchange="submit()" value="<?php echo $date_minus_7?>">- 7 Days</button>
                </form>
            </div>
            <div class="col-xs-1">
                <form method="POST">
                <button class="btn btn-primary form-control" name="date_to_show_moulding" onchange="submit()" value="<?php echo $date_minus_1?>">- 1 Days</button>
                </form>
            </div>
            <div class="col-xs-2">
                <form method="POST">
                <input class="form-control" type="date" name="date_to_show_moulding" onchange="submit()" value="<?php echo $date?>">
                </form>
            </div>
            <div class="col-xs-1">
                <form method="POST">
                <button class="btn btn-primary form-control" name="date_to_show_moulding" onchange="submit()" value="<?php echo $date_plus_1?>">+ 1 Days</button>
                </form>
            </div>
            <div class="col-xs-1">
                <form method="POST">
                <button class="btn btn-primary form-control" name="date_to_show_moulding" onchange="submit()" value="<?php echo $date_plus_7?>">+ 7 Days</button>
                </form>
            </div>
            <div class="col-xs-1">
                
            </div>
            <div class="col-xs-1">
                <form method="POST">
                <?php if($_POST['change_view']=='Product'){$change_view='Tool';}else{$change_view='Product';}?>
                <button class="btn btn-primary form-control" name="change_view" onchange="submit()" value="<?php echo $change_view?>"><?php echo $change_view?></button>
                </form>
            </div>
        
    </div>

    <?php

}

function general_view_moulding_planning(){?>
    <div class="row">
        <?php if(!empty($_SESSION['temp']['role_moulding_planning'])){$col=9;}else{$col=9;}?>
        <div class="col-xs-<?php echo $col?>"><?php show_schedule_moulding() ?></div>
        <div class="col-xs-3">
            <?php if(!empty($_SESSION['temp']['role_moulding_planning'])){show_allocation_details();}?>
            <?php //list_of_all_product_die()?>
            <?php if(!empty($_SESSION['temp']['role_moulding_planning'])){show_details_MIS_moulding();}?>
        </div>
    </div>
    <?php
}
function show_schedule_moulding(){
    
    $date= $_SESSION['temp']['date_to_show_moulding'];
    $all_machine=get_all_machine();
    $all_schedule=get_all_schedule_moulding();
    //show($all_schedule);
    $weekday=['Monday','Tuesday','Wednesday','Thursday','Friday'];
    $allshift=['M','A']
    ?>
    <div class="row header_schedule">
        <div class="col-xs-2">Name</div>
        <div class="col-xs-10">
            <div class="row">Schedule</div>
            <div class="row"><?php 
                for($i=0;$i<=13;$i++){
                    if(date('l',strtotime($date."+ $i days"))=='Sunday'){$class="sunday";}else{$class='';}
                    ?>
                    <div class="day">
                        <div class="row"><?php echo date('D \<\b\r\>d/m',strtotime($date."+ $i days"));?></div>
                        <div class="row">
                            <div class="block_schedule col-xs-6 <?php echo $class?>">M</div>
                            <div class="block_schedule col-xs-6 <?php echo $class?>">A</div>
                        </div>
                    </div>
                    <?php
                }
            ?></div>
        </div>
    </div>
    <script>const toggles=[];</script>
    <?php
    foreach($all_machine as $machine){?>
        <div class="row row_schedule">
            <div class="col-xs-2">
                <div class="col-xs-3">
                    <form id="sort_<?php echo $machine['asset_id'] ?>" method="POST">
                    <?php if(!empty($_SESSION['temp']['role_moulding_planning'])){?>
                    <input class="input_sort" name="asset_sort_order" onchange="update_sort(this,'<?php echo $machine['asset_id'] ?>')" styletype="text" value="<?php echo $machine['asset_sort_order'] ?>">
                    <input type="hidden" name="machine_id" value="<?php echo $machine['asset_id'] ?>">    
                    <input type="hidden" name="action" value="update_sort">
                    <?php }?>
                </form>
                </div>
                <div class="col-xs-9">
                    <?php echo $machine['asset_name'] ?>
                </div>
            </div>
            <div class="col-xs-10">
                <?php  $max_day=13;$max_block=28;
                $count_block=0;
                for($i=0;$i<=$max_day;$i++){
                    if(date('l',strtotime($date."+ $i days"))=='Sunday'){$class="sunday";}else{$class='';}
                    $date_to_show=date('Y-m-d',strtotime($date."+ $i days"));
                    foreach($allshift as $shift){

                        if(!empty($all_schedule[$machine['asset_id']][$date_to_show.$shift])){
                            $counter=1;
                            $max_count=min(($all_schedule[$machine['asset_id']][$date_to_show.$shift]['m_schedule_nbr_shift']),$max_block-$count_block);
                            $tool=$all_schedule[$machine['asset_id']][$date_to_show.$shift]['m_schedule_tool'];
                            $product=$all_schedule[$machine['asset_id']][$date_to_show.$shift]['m_schedule_product'];
                            $m_schedule_id=$all_schedule[$machine['asset_id']][$date_to_show.$shift]['m_schedule_id'];
                            if($_POST['change_view']=='Product'){$to_show=$product;}else{$to_show=$tool;}
                            ?>
                            <div id="<?php echo $m_schedule_id;?>" class="block_schedule scheduled"
                            onclick="load_block(<?php echo $m_schedule_id;?>)" 
                            style="float:left;width:<?php echo (3.5*$max_count);?>%;"><?php echo $to_show;?>
                            <?php
                            
                        }
                        if($counter<>0){
                            if($counter>=$max_count){
                                $counter=0;
                                ?></div><?php
                            }else{
                                $counter++;
                            }
                            
                        }else{
                            ?>
                            <div class="shift <?php echo $class?>" 
                                onclick="load_date('<?php echo $machine['asset_id'] ?>','<?php echo $machine['asset_id'] ?>','<?php echo $date_to_show?>','<?php echo $shift?>','<?php echo $machine['asset_id'] ?>_<?php echo $date_to_show?>_<?php echo $shift?>');"
                                id="<?php echo $machine['asset_id'] ?>_<?php echo $date_to_show?>_<?php echo $shift?>"
                                ><br></div>




                            <?php
                        }
                        
                        $count_block++; 
                    }
                   
                    ?>
                    
                <?php }?>
            </div>
        </div>
        <script>toggles[<?php echo $machine['asset_id'] ?>]=0;</script>
        <?php
        
    }
    ?>
    <style>
        .input_sort{
            display: block;
            width: 100%;
            /*height: 34px;*/
            /*padding: 6px 12px;*/
            text-align: center;
            font-size: 10px;
            
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .header_schedule{
            background-color: #93d2e3;
            text-align: center;
            
        }
        .row_schedule{
            text-align: center;
        }
        .day{
            float:left;
            width:7%;
        }
        .shift{
            float:left;
            width:3.5%;
            border:1px solid #6d7374;
            border-radius:3px;
        }
        .block_schedule{
            /*float:left;*/
            /*width:50%;*/
            border:1px solid #6d7374;
            border-radius:3px;
        }
        .sunday{
            background-color: #a7b3b7;
        }
        .scheduled{
            background-color: #9aa6eb;
        }
        .current{
            background-color: #9aeba2;
        }
        .temp1{
            background-color: #ebe39a;
        }
        .temp2{
            background-color: #ebe39a;
        }
    </style>
    <script>
        function update_sort(el,machine_id){

            document.getElementById("sort_"+machine_id).submit();
        }
        function load_date(machineid,machine,date,shift,id){
            //alert($machine+' '+$date+' '+$shift);
            product=document.getElementById('product').value;
            tool=document.getElementById('tool').value;
            if(toggles[machineid]==0){
                toggles[machineid]=1;
                date_type="start";
                const boxes = document.querySelectorAll('.temp1');
                boxes.forEach(box => {box.classList.remove('temp1');});
                document.getElementById(id).classList.add("temp1");
            }else{
                toggles[machineid]=0;
                date_type="end";
                const boxes = document.querySelectorAll('.temp2');
                boxes.forEach(box => {box.classList.remove('temp2');});
                document.getElementById(id).classList.add("temp2");
            }

            var request =$.ajax({
                type:'POST',
                url:'schedule_moulding_ajax.php',
                data: {action:'load_date',machine:machine,date:date,shift:shift,product:product,tool:tool,date_type:date_type},
                success:function(html){
                    $('.details_allocation').empty().append(html);
                }
            });
            const boxes = document.querySelectorAll('.current');
            boxes.forEach(box => {box.classList.remove('current');});
        }
        function load_block(m_schedule_id){
            var request =$.ajax({
                type:'POST',
                url:'schedule_moulding_ajax.php',
                data: {action:'load_block',m_schedule_id:m_schedule_id},
                success:function(html){
                    $('.details_allocation').empty().append(html);
                }
            });
            
            const boxes = document.querySelectorAll('.current');
            boxes.forEach(box => {box.classList.remove('current');});
            document.getElementById(m_schedule_id).classList.add("current");
        }
    </script>
    <?php 
}
function get_all_machine(){
    $db=$GLOBALS['db'];
    $query="SELECT *
	  FROM asset
      Where asset_type='Moulding Machine' 
      order by asset_sort_order asc
      ";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
	$row=$sql->fetchall();
	return $row;
}
function show_allocation_details($m_schedule_id=''){
    if(!empty($m_schedule_id) and $_POST['action']<>'load_product' and $_POST['action']<>'load_product'){
        get_schedule_details($m_schedule_id);
    }
    $all_machine=get_all_machine();

    if(!empty($_POST['product']) and !empty($_POST['tool'])){
        if(check_if_product_and_tool_match()=='false'){
            if($_POST['last_update']=='product'){
                unset($_POST['tool']);
            }else{
                unset($_POST['product']);
            }
        }
    }
    
    if(!empty($_POST['product'])and empty($_POST['tool']) and $_POST['product']<>'_____'){
        $all_tool=get_all_die_product(" and assetproduct_productcode='".$_POST['product']."'");
        if(count($all_tool)==1){
            $_POST['tool']=$all_tool[0]['asset_id'];
        }
    }else{
        $all_tool=get_all_die_product();
    }
    
    if(!empty($_POST['tool'])and empty($_POST['product'])and $_POST['tool']<>'_____'){
        $all_product=get_all_product_die(" and asset_id='".$_POST['tool']."'");
        if(count($all_product)==1){
            $_POST['product']=$all_product[0]['assetproduct_productcode'];
        }
    }else{
        $all_product=get_all_product_die();
    }
    
    ?>
    <div class="details_allocation">
        <form method="POST">
            <input type="hidden" name="m_schedule_id"  id="m_schedule_id" value="<?php echo $m_schedule_id?>">
        <div class="row">Details Allocation</div>
        <div class="row">
            <div class="col-xs-4">Machine</div>
            <div class="col-xs-8">
                <select class="form-control" type="text" id="machine" name="machine" >
                    
                    <option <?php if(empty($_POST['machine'])){ echo 'selected';}?>>_____</option>
                    <?php 
   
                    foreach ($all_machine as &$item){?>
                        <option <?php if($item['asset_id']==$_POST['machine']){echo'selected';}?> value="<?php echo$item['asset_id']?>"><?php echo$item['asset_name']?></option>
                        <?php 
                    }?>
                </select>
                
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">Product</div>
            <div class="col-xs-8">
                <select class="form-control" oninput="load_details('product')" type="text" id="product" name="product" >
                    
                    <option  <?php if(empty($_POST['product'])){ echo 'selected';}?>>_____</option>
                    <?php 
   
                    foreach ($all_product as &$item){?>
                        <option <?php if($item['assetproduct_productcode']==$_POST['product']){echo'selected';}?> ><?php echo$item['assetproduct_productcode']?></option>
                        <?php 
                    }?>
                </select>
                
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">Die</div>
            <div class="col-xs-8">
                <select class="form-control" onchange="load_details('tool')" type="select" id="tool" name="tool" >
                    
                        <option <?php if(empty($_POST['tool'])){ echo 'selected';}?> >_____</option>
                        <?php 
    
                    foreach ($all_tool as &$item){?>
                        <option value="<?php echo$item['asset_id']?>" <?php if($item['asset_id']==$_POST['tool']){echo'selected';}?> ><?php echo$item['asset_name']?></option>
                        <?php 
                    }?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">Date Start</div>
            <div class="col-xs-8">
                <div class="col-xs-7">
                    <input class="form-control" type="date" id="date_start" name="date_start" value="<?php if(empty($_POST['date_start'])){echo date('Y-m-d');}else{echo$_POST['date_start'];}?>">
                </div>
                <div class="col-xs-5">
                    <select class="form-control"  id="shift_start" name="shift_start">
                        <option value="M"<?php if($_POST['shift_start']=='M'){echo'selected';}?>>Morning</option>
                        <option value="A"<?php if($_POST['shift_start']=='A'){echo'selected';}?>>Afternoon</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">Date End</div>
            <div class="col-xs-8">
                <div class="col-xs-7">
                    <input class="form-control" type="date" id="date_end" name="date_end" value="<?php  if(empty($_POST['date_end'])){echo date('Y-m-d');}else{echo$_POST['date_end'];}?>">
                </div>
                <div class="col-xs-5">
                    <select class="form-control"  id="shift_end" name="shift_end">
                        <option value="M"<?php if($_POST['shift_end']=='M'){echo'selected';}?>>Morning</option>
                        <option value="A"<?php if($_POST['shift_end']=='A'){echo'selected';}?>>Afternoon</option>
                    </select>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-xs-4"></div>
            <div class="col-xs-8"><button class="form-control" type="submit" name="action" value="save_planning" >Save</button></div>
        </div>
        <?php  if(!empty($m_schedule_id)){?>
        <br>
        <div class="row">
            <div class="col-xs-4"></div>
            <div class="col-xs-8"><button class="form-control" type="submit" name="action" value="delete_planning" ><span class="glyphicon glyphicon-trash"></span></button></div>
        </div>
        <?php } ?>
        <br>
        </form>
        <div class="row">
            <div class="col-xs-6">
                <?php if(!empty($_POST['machine'])){?>
                <form method="POST" action="asset.php" target="blank">
                    <input type="hidden" name="asset_id" value="<?php echo$_POST['machine']?>">
                    <input type="hidden" name="type" value="detail">
                    <input type="hidden" name="last_page" value="moulding-planning.php">
                <button class="form-control"  type="submit">See Machine Details</button>
                </form>
                <?php }?>
            </div>
            <div class="col-xs-6">
                <?php if(!empty($_POST['tool'])){?>
                <form method="POST" action="asset.php" target="blank">
                    <input type="hidden" name="asset_id" value="<?php echo$_POST['tool']?>">
                    <input type="hidden" name="type" value="detail">
                    <input type="hidden" name="last_page" value="moulding-planning.php">
                <button class="form-control"  type="submit">See Tool Details</button>
                </form>
                <?php }?>
            </div>
        </div>
        
    </div>
    <style>
        .details_allocation{
            min-height:200px;
            text-align: center;
        }
    </style>
    <script>
        function load_details(last_update){
            //alert(document.getElementById('tool').value);
            product=document.getElementById('product').value;
            tool=document.getElementById('tool').value;
            machine=document.getElementById('machine').value;
            date_start=document.getElementById('date_start').value;
            date_end=document.getElementById('date_end').value;
            shift_start=document.getElementById('shift_start').value;
            shift_end=document.getElementById('shift_end').value;
            m_schedule_id=document.getElementById('m_schedule_id').value;
            var request =$.ajax({
                type:'POST',
                url:'schedule_moulding_ajax.php',
                data: {
                    action:'load_product',
                    product:product,
                    tool:tool,
                    machine:machine,
                    date_start:date_start,
                    date_end:date_end,
                    shift_start:shift_start,
                    shift_end:shift_end,
                    last_update:last_update,
                    m_schedule_id:m_schedule_id},
                success:function(html){
                    $('.details_allocation').empty().append(html);
                }
            });
        }
    </script>
    <?php
}
function show_details_MIS_moulding(){
    ?>
    <br>
    <div class="row ">
        <div class="col-xs-5 ">
            <form method="post" id="load_date_productlist">
                <input type="date" class='form-control' style="width: 100%;" oninput="document.getElementById('load_date_productlist').submit()"name="date_productlist" value="<?php echo $_POST['date_productlist']?>">
            </form>
        </div>
        <div class="col-xs-2 "></div>
        <div class="col-xs-2 "></div>
        <div class="col-xs-2 "></div>
        <div class="col-xs-1 ">
            <?php $caption='1-Choose the date ManufactureBefore<br>
                    2-Choose the Hours<br> 
                    3-Choose the Product Code<br> 
                    4-Click on the Calendar to Allocated it to an Operator<br>
                    <br>
                    Right Click to Add/Modify Notes<br>
                    ';
                    //info_button('info',$caption,',height:auto')?>
            
        </div>
    </div>


    <?php
    $allproduct=(get_list_process_moulding($_POST['date_to_show_moulding']));
    $lastcode='';
    $lastworkarea='';
    foreach($allproduct as $product){?>
        <?php if($product['Code']<>$lastcode){?>
            <?php if($product['WorkArea']<>$lastworkarea){?>
                <div class=" ">
                    <div class="row <?php echo $product['WorkArea'];?>">
                    <div class="col-xs-6" ><?php echo $product['WorkArea'];?></div> 
                    <div class="col-xs-6" >
                        <div class="col-xs-6" >Qty</div>
                        <div class="col-xs-3" >Hours</div>
                        <div class="col-xs-3" >Shift</div>
                    </div>
                       
                </div>   
                
                
            <?php } ?>
            
            <div 
            id="<?php echo $product['Code']?>"
            class="row <?php echo $product['WorkArea'];?> product_to_assign"
            onclick="load_details_from_list('<?php echo $product['Code']?>')"
            >
                <div class="col-xs-6"><?php echo $product['Code']?></div> 
                <div class="col-xs-6">
                    <div class="col-xs-6" ><?php echo number_format($product['QuantityOrdered']-$product['QTY_MADE'])?></div>
                    <div class="col-xs-3" ><?php echo number_format(($product['QuantityOrdered']-$product['QTY_MADE'])*$product['BOM_TIME']/60-$product['hours_allocated'])?></div>
                    <div class="col-xs-3"><?php echo number_format(($product['QuantityOrdered']-$product['QTY_MADE'])*$product['BOM_TIME']/60/7.6-$product['hours_allocated'])?></div>
                </div>
            </div> 
                        
            
        
        <?php
        $lastcode=$product['Code'];
       
        $lastworkarea=$product['WorkArea'];
        
        if($product['WorkArea']<>$lastworkarea){?>
            </div>
        <?php } 
        
        }
    }?>
    
    <script>
        function load_details_from_list(product){
            tool=document.getElementById('tool').value;
            machine=document.getElementById('machine').value;
            date_start=document.getElementById('date_start').value;
            date_end=document.getElementById('date_end').value;
            shift_start=document.getElementById('shift_start').value;
            shift_end=document.getElementById('shift_end').value;
            m_schedule_id=document.getElementById('m_schedule_id').value;
            last_update='product';
            var request =$.ajax({
                type:'POST',
                url:'schedule_moulding_ajax.php',
                data: {
                    action:'load_product',
                    product:product,
                    tool:tool,
                    machine:machine,
                    date_start:date_start,
                    date_end:date_end,
                    shift_start:shift_start,
                    shift_end:shift_end,
                    last_update:last_update,
                    m_schedule_id:m_schedule_id},
                success:function(html){
                    $('.details_allocation').empty().append(html);
                }
            });
        }
    </script>
    <?php
}
function list_of_all_product_die(){
   ?>

    <div class="row">
        <div class="col-xs-6"><?php show_all_product() ?></div>
        <div class="col-xs-6"><?php show_all_die()?></div>
    </div>
    <?php

}
function show_all_product(){
    $all_product=get_all_product_die();?>
    <div class="row header_schedule">Product List</div>
    
    <?php
    foreach($all_product as $product){
        ?>
        <div class="row row_product" onclick="load_product('<?php echo $product['assetproduct_productcode']?>')"><?php echo $product['assetproduct_productcode']?></div>
        <?php
    }?>
    <style>
        .row_product{
            text-align:center ;
        }
    </style>
    <script>
    function load_product(product){
            var request =$.ajax({
                type:'POST',
                url:'schedule_moulding_ajax.php',
                data: {action:'load_product',product:product},
                success:function(html){
                    $('.details_allocation').empty().append(html);
                }
            });
        }
    </script>

    <?php
    
}
function show_all_die(){
    $all_die=get_all_die_product();?>
    <div class="row header_schedule">Tool List</div>
    <?php
    
    foreach($all_die as $die){
        ?>
        <div class="row row_die" onclick="load_tool('<?php echo $die['asset_name']?>')"><?php echo $die['asset_name']?></div>
        <?php
    }?>
    <style>
        .row_die{
            text-align:center ;
        }
    </style>
    <script>
    function load_tool(tool){
            var request =$.ajax({
                type:'POST',
                url:'schedule_moulding_ajax.php',
                data: {action:'load_tool',tool:tool},
                success:function(html){
                    $('.details_allocation').empty().append(html);
                }
            });
        }
    </script>

    <?php
}
function get_all_product_die($option=''){
    $db=$GLOBALS['db'];
    $query="SELECT [assetproduct_productcode],
        count([assetproduct_assetid]) as count_die
    FROM [barcode].[dbo].[assetproduct]
    left join asset on asset_id = assetproduct_assetid
    where asset_type='Die' $option
    group by assetproduct_productcode
     order by assetproduct_productcode asc
      ";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
	$row=$sql->fetchall();
	return $row;
}
function get_all_schedule_moulding(){
    $db=$GLOBALS['db'];
    $date_start= $_SESSION['temp']['date_to_show_moulding'];
    $date_end=date('Y-m-d',strtotime($date_start."+ 14 days"));
    $query="SELECT 
    [m_schedule_id]
    ,[m_schedule_machine_id]
    ,[m_schedule_product]
    ,[m_schedule_tool]
    ,[m_schedule_date_start]
    ,[m_schedule_date_end]
    ,[m_schedule_shift_start]
    ,[m_schedule_shift_end]
    ,[m_schedule_nbr_shift]
    ,[m_schedule_timetag_start]
    ,[m_schedule_timetag_end],
    machine.asset_id,
    machine.asset_name,
    machine.asset_sort_order,
    tool.asset_id,
    tool.asset_name
    FROM schedule_moulding
    left join asset as machine on machine.asset_id = m_schedule_machine_id
    left join asset as tool on tool.asset_id = m_schedule_tool
    where m_schedule_date_end>='$date_start' and m_schedule_date_start<='$date_end'
      ";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
	$allrow=$sql->fetchall();
    foreach($allrow as $row){
        $date_to_use=date('Y-m-d',max(strtotime($date_start),strtotime($row['m_schedule_date_start'])));
        $days_troncate=max(round((strtotime($date_start)-strtotime($row['m_schedule_date_start']))/ (60 * 60 * 24)),0)*2;
        $return[$row['m_schedule_machine_id']][$date_to_use.$row['m_schedule_shift_start']]['m_schedule_nbr_shift']=$row['m_schedule_nbr_shift']-$days_troncate;
        $return[$row['m_schedule_machine_id']][$date_to_use.$row['m_schedule_shift_start']]['m_schedule_tool']=$row['asset_name'];
        $return[$row['m_schedule_machine_id']][$date_to_use.$row['m_schedule_shift_start']]['m_schedule_product']=$row['m_schedule_product'];
        $return[$row['m_schedule_machine_id']][$date_to_use.$row['m_schedule_shift_start']]['m_schedule_id']=$row['m_schedule_id'];
    }
    
    
	return $return;
}
function get_all_die_product($option=''){
    $db=$GLOBALS['db'];
    $query="SELECT asset_id,asset_name FROM asset 
    left join assetproduct on asset_id = assetproduct_assetid
    where asset_type='Die' and asset_delete=0 $option
    group by asset_name,asset_id
     order by asset_name asc
      ";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
	$row=$sql->fetchall();
	return $row;
}
function get_schedule_details($m_schedule_id){
    $db=$GLOBALS['db'];
    $query="SELECT *
    FROM schedule_moulding
    left join asset on asset_id = m_schedule_machine_id
    where m_schedule_id='$m_schedule_id' 
    ";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
	$row=$sql->fetch();
    $_POST['machine']=$row['m_schedule_machine_id'];
    $_POST['product']=$row['m_schedule_product'];
    $_POST['tool']=$row['m_schedule_tool'];
    $_POST['date_start']=$row['m_schedule_date_start'];
    $_POST['date_end']=$row['m_schedule_date_end'];
    $_POST['shift_start']=$row['m_schedule_shift_start'];
    $_POST['shift_end']=$row['m_schedule_shift_end'];
    return $row;
}
function save_planning(){
    $db=$GLOBALS['db'];
    $m_schedule_id=$_POST['m_schedule_id'];
    $m_schedule_machine_id=$_POST['machine'];
    $m_schedule_product=$_POST['product'];
    $m_schedule_tool=$_POST['tool'];
    $m_schedule_date_start=$_POST['date_start'];
    $m_schedule_date_end=$_POST['date_end'];
    $m_schedule_shift_start=$_POST['shift_start'];
    $m_schedule_shift_end=$_POST['shift_end'];
    $time_add=0;
    if($_POST['shift_start']=='A'){
        $time_add=3600*12;
    }
    $m_schedule_timetag_start=strtotime($_POST['date_start'])+$time_add;
    $time_add=0;
    if($_POST['shift_end']=='A'){
        $time_add=3600*12;
    }
    $m_schedule_timetag_end=strtotime($_POST['date_end'])+$time_add;

    $m_schedule_nbr_shift=round((strtotime($_POST['date_end'])-strtotime($_POST['date_start']))/ (60 * 60 * 24)+1)*2;
    if($m_schedule_shift_start=='A'){
        $m_schedule_nbr_shift=$m_schedule_nbr_shift-1;
    }
    if($m_schedule_shift_end=='M'){
        $m_schedule_nbr_shift=$m_schedule_nbr_shift-1;
    }
    if(empty(check_if_new_entry_doesnt_interfere_with_other_entry())){
        if(empty($m_schedule_id)){
            $query="INSERT INTO schedule_moulding(
                m_schedule_machine_id,
                m_schedule_product,
                m_schedule_tool,
                m_schedule_date_start,
                m_schedule_date_end,
                m_schedule_shift_start,
                m_schedule_shift_end,
                m_schedule_nbr_shift,
                m_schedule_timetag_start,
                m_schedule_timetag_end)
                VALUES(
                    '$m_schedule_machine_id',
                    '$m_schedule_product',
                    '$m_schedule_tool',
                    '$m_schedule_date_start',
                    '$m_schedule_date_end',
                    '$m_schedule_shift_start',
                    '$m_schedule_shift_end',
                    '$m_schedule_nbr_shift',
                    '$m_schedule_timetag_start',
                    '$m_schedule_timetag_end'
                )";
        }else{
            $query="UPDATE schedule_moulding
            SET
                m_schedule_machine_id='$m_schedule_machine_id',
                m_schedule_product='$m_schedule_product',
                m_schedule_tool='$m_schedule_tool',
                m_schedule_date_start='$m_schedule_date_start',
                m_schedule_date_end='$m_schedule_date_end',
                m_schedule_shift_start='$m_schedule_shift_start',
                m_schedule_shift_end='$m_schedule_shift_end',
                m_schedule_nbr_shift='$m_schedule_nbr_shift',
                m_schedule_timetag_start='$m_schedule_timetag_start',
                m_schedule_timetag_end='$m_schedule_timetag_end'
            where m_schedule_id='$m_schedule_id' ";
        }
        
        
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
    }else{
        show('Problem, Not Saved');
    }
    
}
function delete_planning(){
    $db=$GLOBALS['db'];
    $m_schedule_id=$_POST['m_schedule_id'];
    $query="DELETE FROM schedule_moulding where m_schedule_id='$m_schedule_id'";
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
}
function get_machine_id($machine_name){
    $db=$GLOBALS['db'];
    $query="SELECT asset_id FROM asset 
    
    where asset_name='$machine_name' ";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
	$row=$sql->fetch();
	return $row['asset_id'];
}

function check_if_product_and_tool_match(){
    $db=$GLOBALS['db'];
    $product=$_POST['product'];
    $tool=$_POST['tool'];
    $query="SELECT asset_id
    FROM [barcode].[dbo].[assetproduct]
    left join asset on asset_id = assetproduct_assetid
    where asset_type='Die' and assetproduct_productcode='$product' and (asset_id='".get_machine_id($tool)."')
    
      ";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
	$row=$sql->fetch();
    if (!empty($row)){
        return "true";
    }else{
        return "false";
    }
}

function check_if_new_entry_doesnt_interfere_with_other_entry(){
    $db=$GLOBALS['db'];
    
    $time_add=0;
    $m_schedule_id=$_POST['m_schedule_id'];
    if($_POST['shift_start']=='A'){
        $time_add=3600*12;
    }
    $m_schedule_timetag_start=strtotime($_POST['date_start'])+$time_add;
    $time_add=0;
    if($_POST['shift_end']=='A'){
        $time_add=3600*12;
    }
    $m_schedule_timetag_end=strtotime($_POST['date_end'])+$time_add;
    $m_schedule_machine_id=$_POST['machine'];
    
    if($m_schedule_timetag_end<=$m_schedule_timetag_start){
        $return="problem";
    }
    
    $condition="
    ( m_schedule_timetag_start<='$m_schedule_timetag_start' and m_schedule_timetag_end>='$m_schedule_timetag_start') OR
    ( m_schedule_timetag_start<='$m_schedule_timetag_end' and m_schedule_timetag_end>='$m_schedule_timetag_end') OR
    ( m_schedule_timetag_start>='$m_schedule_timetag_start' and m_schedule_timetag_end<='$m_schedule_timetag_end') ";
    
    
    $query="Select * from schedule_moulding
    WHERE m_schedule_machine_id='$m_schedule_machine_id' and m_schedule_id<>'$m_schedule_id'
    and ( 
        $condition
    ) ";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $row=$sql->fetch();
    //show($query);
    if(!empty($row)){
        $return="problem";
    }
    
    
    return $return;
    
}
function update_asset_sort($machine_id,$asset_sort_order){
    $db=$GLOBALS['db'];
    $query="UPDATE asset
        SET
        asset_sort_order='$asset_sort_order'
        where asset_id='$machine_id' ";
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
}

function get_list_process_moulding($date){
    $db=$GLOBALS['db'];
    $filter='';
    if(!empty($_POST['workarea']) and $_POST['workarea']<>'All WorkArea'){
        
    }
    $filter=$filter."AND WorkArea='Moulding'";
    $date2=$_POST['date_productlist'];
    
    
    $query="SET DATEFIRST 1;SELECT  Code	,WorkArea,sum((COALESCE(QTY_MADE,0))*BOM_TIME/60) as HOURS_Made
	,sum((COALESCE(QTY_INPROGRESS,0))*BOM_TIME/60) as HOURS_Current
	,sum((BaseQuantityOrdered-COALESCE(QTY_MADE,0)-COALESCE(QTY_INPROGRESS,0))*BOM_TIME/60) as HOURS_Remaining
    ,sum((BaseQuantityOrdered-COALESCE(QTY_MADE,0))*BOM_TIME/60) as HOURS_Remaining2,
    sum((COALESCE(QTY_MADE,0))/(BaseQuantityOrdered)) as Progress,
    sum(QuantityOrdered) as QuantityOrdered,
	sum(QTY_MADE) as QTY_MADE,
    avg(BOM_TIME)as BOM_TIME
    FROM MO_List
	left join MO_Hours_Scanned on MO_Hours_Scanned.[MO]=[ManufactureOrderNumber]
	left join prodplanstatus on prodplanstatus_REF=[ManufactureOrderNumber]
    left join prodplanrisk on prodplanrisk_REF=[ManufactureOrderNumber]
    left join prodplanpref on prodplanpref_REF=[ManufactureOrderNumber]
	left join BOM_List on BOM_List.Productcode=[Code]
    
    where  manufactureON is not null and
	  manufactureBefore<='$date2' $filter
                     AND IsLineOutstanding <>0 
    group by Code	,WorkArea 
    ORDER BY WorkArea ASC,HOURS_Remaining2 DESC";
    $sql = $db->prepare($query); 
    $sql->execute();
    //show($query);
    $allrow=$sql->fetchall();
    foreach($allrow as $row){
        $return[]=$row;
    }
    return $return;
}



?>

