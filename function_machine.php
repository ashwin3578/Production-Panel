<?php
//phpinfo();
load_role($db,$_SESSION['temp']['id']);

function manage_post_machine($db){
    
    if(!empty($_GET['debug'])){
        $_SESSION['temp']['debug']=$_GET['debug'];
    }
    
    if($_SESSION['temp']['debug']=='1'){
        show($_POST);
    }

    if(!empty($_POST['date_filter']) or !empty($_SESSION['temp']['date_filter'])){
        if(empty($_POST['date_filter'])){$_POST['date_filter']=$_SESSION['temp']['date_filter'];}
        $_SESSION['temp']['date_filter']=$_POST['date_filter'];
        if(empty($_POST['date_filter_start'])){$_POST['date_filter_start']=$_SESSION['temp']['date_filter_start'];}
        if(empty($_POST['date_filter_end'])){$_POST['date_filter_end']=$_SESSION['temp']['date_filter_end'];}
        if(empty($_POST['time_filter_start'])){$_POST['time_filter_start']=$_SESSION['temp']['time_filter_start'];}
        if(empty($_POST['time_filter_end'])){$_POST['time_filter_end']=$_SESSION['temp']['time_filter_end'];}
        if($_POST['date_filter']=='Custom'){
            if(empty($_POST['date_filter_start'])){
                $timetag_start=time()-24*3600;    
            }else{
                $timetag_start=strtotime($_POST['date_filter_start'].' '.$_POST['time_filter_start'].'');
            }
            if(empty($_POST['date_filter_end'])){
                $timetag_end=time()+3;
            }else{
                $timetag_end=strtotime($_POST['date_filter_end'].' '.$_POST['time_filter_end'].'');
                
            }
            
        }elseif($_POST['date_filter']=='Today'){
            $timetag_start=strtotime(date('Y-m-d',time()));
            $timetag_end=time()+3;
        }elseif($_POST['date_filter']=='Yesterday'){
            $timetag_start=strtotime(date('Y-m-d',time()).' -1 days');
            $timetag_end=strtotime(date('Y-m-d',time()));
        }elseif($_POST['date_filter']=='Last Hour'){
            $timetag_start=time()-3600;
            $timetag_end=time()+3;
        }elseif($_POST['date_filter']=='Last 24 Hours'){
            $timetag_start=time()-24*3600;
            $timetag_end=time()+3;
        }elseif($_POST['date_filter']=='Last 7 Days'){
            $timetag_start=time()-7*24*3600;
            $timetag_end=time()+3;
        }elseif($_POST['date_filter']=='Last Month'){
            $timetag_start=time()-30*24*3600;
            $timetag_end=time()+3;
        }elseif($_POST['date_filter']=='Last Year'){
            $timetag_start=time()-365*24*3600;
            $timetag_end=time()+3;
        }
        
    }else{
        
        $timetag_start=strtotime(date('Y-m-d',time()));
        $timetag_end=time()+3;
    }

    
    $_SESSION['temp']['date_filter_start']=date('Y-m-d',$timetag_start);
    $_SESSION['temp']['time_filter_start']=date('H:i:s',$timetag_start);
    $_SESSION['temp']['date_filter_end']=date('Y-m-d',$timetag_end);
    $_SESSION['temp']['time_filter_end']=date('H:i:s',$timetag_end);
    $_POST['date_filter']=$_SESSION['temp']['date_filter'];
    $_POST['date_filter_start']=$_SESSION['temp']['date_filter_start'];
    $_POST['date_filter_end']=$_SESSION['temp']['date_filter_end'];
    $_POST['time_filter_start']=$_SESSION['temp']['time_filter_start'];
    $_POST['time_filter_end']=$_SESSION['temp']['time_filter_end'];

    if(!empty($_POST['type'])){  
        $_SESSION['temp']['type']=$_POST['type'];
    }
    if(!empty($_SESSION['temp']['type'])){
        $_POST['type']=$_SESSION['temp']['type'];
    }
    


    if($_POST['action']=='show_all_factory'){
        show_all_factory($db);
    }
    if($_POST['action']=='show_factory_summary'){
        show_table_factory_summary($db);
    }
    if($_POST['action']=='show_navbar_top'){
        navbar_top($db,$_POST['option']);
    }
    if(empty($_SESSION['temp']['view'])){
        
        $_SESSION['temp']['view']='View All Machine';
        
    }
    if(!empty($_POST['view'])){
        $_SESSION['temp']['view']=$_POST['view'];
    }
    if(!empty($_POST['clean_temptable_not_allocated'])){
        clean_temptable_not_allocated($db);
    }
    
    if($_POST['action']=='save_position'){
        save_position($db,$_POST['machine_id'],$_POST['machine_location_x'],$_POST['machine_location_y']);
    }
    if($_POST['action']=='create_machine'){
        create_machine($db,$_POST['machine_location_x'],$_POST['machine_location_y'],$_POST['machine_workarea']);
    }
    if($_POST['action']=='delete_machine'){
        delete_machine($db,$_POST['machine_id']);
        unset($_POST['machine_id']);
    }
    if($_POST['action']=='save_changes'){
        save_changes($db,$_POST['machine_id'],$_POST['machine_name'],$_POST['machine_workarea'],$_POST['machineallocation_MAC'],$_POST['machine_number'],$_POST['machinepin_pindescription'],$_POST['machine_cycle_pinumber']);
        //$_POST['action']='show_details_machine';
    }
    if($_POST['action']=='save_machinebaseproduct'){
        save_machinebaseproduct($db,$_POST['machine_id'],$_POST['machinebaseproduct_productcode']);
    }
    if($_POST['action']=='remove_machinebaseproduct'){
        remove_machinebaseproduct($db,$_POST['machine_id'],$_POST['machinebaseproduct_productcode']);
    }
    if($_POST['action']=='save_machineproduct'){
        save_machineproduct($db,$_POST['machine_id'],$_POST['machineproduct_productcode']);
    }
    if($_POST['action']=='remove_machineproduct'){
        remove_machineproduct($db,$_POST['machine_id']);
    }
    if($_POST['action']=='view_admin'){
        if(!empty($_SESSION['temp']['factory_admin_view'])){
            unset($_SESSION['temp']['factory_admin_view']);
        }else{
            $_SESSION['temp']['factory_admin_view']=1;
            unset($_SESSION['temp']['factory_user_view']);
        }
        
    }
    if($_POST['action']=='view_user'){
        if(!empty($_SESSION['temp']['factory_user_view'])){
            unset($_SESSION['temp']['factory_user_view']);
        }else{
            $_SESSION['temp']['factory_user_view']=1;
            unset($_SESSION['temp']['factory_admin_view']);
        }
        
    }
    if($_POST['action']=='show_details_machine'){
        show_windows_details_machine($db);   
    }
        
    
   


    
    if(!empty($_POST['temptable_MAC'])and !empty($_POST['type'])){
        if($_POST['type']=='add'){
            if(empty($_SESSION['temp']['pin_to_show'][$_POST['temptable_MAC']])){
                unset($_SESSION['temp']['pin_to_show']);
            }
            $entry['temptable_MAC']=$_POST['temptable_MAC'];
            $entry['temptable_pin']=$_POST['temptable_pin'];
            $_SESSION['temp']['pin_to_show'][$_POST['temptable_MAC']][$_POST['temptable_pin']]=$entry;
        }
        if($_POST['type']=='remove'){
            unset($_SESSION['temp']['pin_to_show'][$_POST['temptable_MAC']][$_POST['temptable_pin']]);
            if(empty($_SESSION['temp']['pin_to_show'][$_POST['temptable_MAC']])){
                unset($_SESSION['temp']['pin_to_show']);
            }
        }
     }
     if(!empty($_POST['minutestoshow'])){
        $_SESSION['temp']['minutestoshow']=$_POST['minutestoshow'];
     }
     $_POST['minutestoshow']=$_SESSION['temp']['minutestoshow'];
    // $_POST['temptable_pin']=$_SESSION['temp']['temptable_pin'];
    // $_POST['temptable_MAC']=$_SESSION['temp']['temptable_MAC'];
    
    if(!empty($_POST['machine_list']) ){
        $_SESSION['temp']['machine_list']=$_POST['machine_list'];
        
    }
    
    
    
    
    if(!empty($_POST['machine_name']) ){
        $_SESSION['temp']['machine_name']=$_POST['machine_name'];
        if($_POST['machine_name']=='All'){
            $_SESSION['temp']['machine_name']='';
            $_POST['entry_type']='All';
        }
    }
    if(!empty($_POST['entry_type']) ){
        $_SESSION['temp']['entry_type']=$_POST['entry_type'];
        if($_POST['entry_type']=='All'){
            $_SESSION['temp']['entry_type']='';
        }
    }
    



    if(!empty($_POST['reset_import'])){
        
        foreach(get_all_MAC($db,$_POST['machine_name']) as $mac){
            
            re_import_all_event($db,$mac['machineallocation_MAC']);
        }
    }
    $_POST['machine_list']=$_SESSION['temp']['machine_list'];
    $_POST['view']=$_SESSION['temp']['view'];
    $_POST['entry_type']=$_SESSION['temp']['entry_type'];
    $_POST['machine_name']=$_SESSION['temp']['machine_name'];
    
    //show($_POST);

    if(!empty($_POST['view_factory'])){
        
        if(!empty($_POST['workarea'])){$_SESSION['factory']['workarea']=$_POST['workarea'];}
        if(!empty($_POST['type'])){$_SESSION['factory']['type']=$_POST['type'];}
        
        if(empty($_POST['filter'])){
            show_factory2($db,$_SESSION['factory']['workarea'],$_SESSION['factory']['type']);
        }else{
            //show($_POST);
            show_factory_filter($db,$_SESSION['factory']['workarea'],$_SESSION['factory']['type']);
        }
        
        
    }

    if($_POST['import_ajax']=='import_ajax'){
        //show($_POST['import_ajax']);
        script_import_ajax($db,$_POST['count']+1);
    }
    if($_POST['action']=='save_view'){
        if(!empty($_POST['workarea'])){
            $_SESSION['factory']['workarea']= $_POST['workarea'];
        }
        if(!empty($_POST['type'])){
            $_SESSION['factory']['type']= $_POST['type'];
        }
        show_windows_details_machine($db);  
    }

}



function general_view_machine($db){
    //show_debug();
    $defaultcol='col-md-7 col-lg-7';
    if($_POST['view']=='View All Machine'){
        $defaultcol='col-md-12 col-lg-12';
    }
   
    echo'<div class="row ">';
       
        echo'<div  class="'.$defaultcol.'">';
            if($_POST['view']=='View Details'){
                navbar_all_stats_machine($db);
            }elseif($_POST['view']=='View All Machine' or $_POST['view']=='View All Device'){
                navbar_all_machine($db);
            }

            echo'<div id="here">';
                if($_POST['view']=='View Live'){
                    show_view_live_temptable($db);
                }elseif($_POST['view']=='View All Machine'){
                    show_view_all_machine($db);
                }elseif($_POST['view']=='View All Device'){
                    show_view_all_device($db);
                }elseif($_POST['view']=='Import Cycle'){
                
                    import_ajax($db);
                    
                }elseif($_POST['view']=='test_import'){
                    import_temptable($db);

                    //clean_data($db);
                }elseif($_POST['view']=='View Details'){
                    show_all_stats($db);
                    }elseif($_POST['view']=='Last Installed'){
                    show_last_installed($db);
                }elseif($_POST['view']=='view_all'){
                    show_all_temptable($db);
                }elseif($_POST['view']=='manage_machine'){
                    show_manage_machine($db);
                }elseif($_POST['view']=='Allocation Machine'){
                    show_view_all_device($db);
                }elseif($_POST['view']=='View Summary'){
                    show_view_all_summary($db);
                }elseif($_POST['view']=='Reset Cycle'){
                    //remove_all_cycle($db);
                    remove_all_cycle($db,'02-03-2022');
                    
                }else{
                    //show_view_all_machine($db);
                }
            

            echo'</div>';
        echo'</div>';
        echo'<div  class="col-md-1 ">';
            
        echo'</div>';
        echo'<div  class="col-md-3 col-lg-3 ">';
            echo'<div id="all_stats">';
            if($_POST['view']=='View Details'){
                echo'<div id="stats">';
                show_details_stats($db);
                echo'</div>';
               }elseif($_POST['view']=='Allocation Machine'){
                show_allocation_machine($db);
            }elseif($_POST['view']=='View Live'){
                if(!empty($_SESSION['temp']['pin_to_show'] )){
                    //show($_SESSION['temp']['pin_to_show']);
                    show_view_live_temptable_details($db,$_POST['temptable_MAC'],$_POST['temptable_pin']);
                }
               
            }?>
            </div>
        </div>

        <div class="col-sm-4 dialog-box" ></div>
    </div>
    <div class="col-sm-4 hidden-box" ></div>
    <?php
}

function general_view_factory($db){
    echo'<div class="navbar_top">';
    navbar_top($db);
    echo'</div>';
    ?>
    <script>
        
        function dragElement(elmnt,workarea) {
            
            var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
            max_width=(document.getElementById('factory'+workarea).clientWidth-document.getElementById(elmnt.id).clientWidth);
            max_height=(document.getElementById('factory'+workarea).clientHeight-document.getElementById(elmnt.id).clientHeight);
            //alert(document.getElementById('factoryMachining').clientWidth);
            if (document.getElementById(elmnt.id + "header")) {
                // if present, the header is where you move the DIV from:
                document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
            } else {
                // otherwise, move the DIV from anywhere inside the DIV:
                elmnt.onmousedown = dragMouseDown;
            }
            
            
        
            function dragMouseDown(e) {
                var current_selection="";  
                var current = document.getElementsByClassName("button_manage_machine_selected");
                if (current.length > 0) { 
                    current_selection=String(current[0].id);
                    
                }
                if(current_selection=="move_machine"){
                
                    e = e || window.event;
                    e.preventDefault();
                    // get the mouse cursor position at startup:
                    pos3 = e.clientX;
                    pos4 = e.clientY;
                    
                    document.onmouseup = closeDragElement;
                    // call a function whenever the cursor moves:
                    document.onmousemove = elementDrag;
                }
            }
        
            function elementDrag(e) {
                e = e || window.event;
                e.preventDefault();
                // calculate the new cursor position:
                pos1 = pos3 - e.clientX;
                pos2 = pos4 - e.clientY;
                pos3 = e.clientX;
                pos4 = e.clientY;
                // set the element's new position:
                x_percent=Math.round((Math.max(0,Math.min((document.getElementById('factory'+workarea).clientWidth-document.getElementById(elmnt.id).clientWidth),(elmnt.offsetLeft - pos1)))*100/(document.getElementById('factory'+workarea).clientWidth-document.getElementById(elmnt.id).clientWidth))*10,0)/10;
                y_percent=Math.round((Math.max(0,Math.min((document.getElementById('factory'+workarea).clientHeight-document.getElementById(elmnt.id).clientHeight),(elmnt.offsetTop - pos2)))*100/(document.getElementById('factory'+workarea).clientHeight-document.getElementById(elmnt.id).clientHeight))*10,0)/10;
                
                x_px=Math.max(0,Math.min((document.getElementById('factory'+workarea).clientWidth-document.getElementById(elmnt.id).clientWidth),(elmnt.offsetLeft - pos1))) ;
                y_px=Math.max(0,Math.min((document.getElementById('factory'+workarea).clientHeight-document.getElementById(elmnt.id).clientHeight),(elmnt.offsetTop - pos2))) ;
                elmnt.style.left = x_px+ "px";
                elmnt.style.top = y_px+ "px";
                
                //document.getElementById(elmnt.id +'x').innerHTML="x="+x_percent+"%";//+" "+x_px+ "px"
                //document.getElementById(elmnt.id +'y').innerHTML="y="+y_percent+"%";//+" "+y_px+ "px"
                save_position(elmnt.id,x_percent,y_percent);
            }
        
            function closeDragElement() {
                // stop moving when mouse button is released:
                document.onmouseup = null;
                document.onmousemove = null;
            }
            function save_position(machineid,x,y){
                var request =$.ajax({
                    type:'POST',
                    url:'machine_ajax.php',
                    data: {machine_location_x:x,machine_location_y:y,machine_id:machineid.substring(3),action:'save_position'},
                    success:function(html){
                        $('.ajaxbox').empty().append(html);
                    }
                });
            }
        }
    
    </script>
    <?php
    if(empty($_SESSION['factory']['workarea'])){

        $_SESSION['factory']['workarea']='Moulding';
    }
    if(empty($_SESSION['factory']['type'])){

        $_SESSION['factory']['type']='Count';
    }
    $defaultcol='col-md-12 col-lg-12';
    echo'<div class="row ">';
        
        echo'<div  class="'.$defaultcol.'">';
            $_POST['workarea']=$_SESSION['factory']['workarea'];
            $_POST['type']=$_SESSION['factory']['type'];
            echo'<script>
            theworkarea="'.$_POST['workarea'].'";
            thetype="'.$_POST['type'].'";
            
            </script>';
            ?>
            <form method="POST" id="user_form">
            <input type="hidden" name="action" value="view_user">
            </form >
            <form method="POST" id="admin_form">
            <input type="hidden" name="action" value="view_admin">
            </form > 
            <div id="navbar_workarea" class="row " style="width:75%">
                <div id="Machining" class="col-xs-3 col-md-2 button_workarea" onclick="theworkarea='Machining';load_workarea_v2(theworkarea)">Machining</div>
                <div id="Moulding" class="col-xs-3 col-md-2  button_workarea" onclick="theworkarea='Moulding';load_workarea_v2(theworkarea)">Moulding</div>
                <div id="Assembly" class="col-xs-3 col-md-2  button_workarea" onclick="theworkarea='Assembly';load_workarea_v2(theworkarea)">Assembly</div>
                <div  class="col-xs-1 col-md-4 "></div>
                <!--<?php if(!empty($_SESSION['temp']['role_factory_admin'])){?>
                    
                        <?php 
                        $class='';
                        if(!empty($_SESSION['temp']['factory_user_view'])){$class='button_admin_selected';}
                        ?>
                    <div id="User" class="col-xs-1 button_workarea glyphicon glyphicon-user <?php echo $class;?>" style="line-height: inherit;" onclick="document.getElementById('user_form').submit()">
                    </div>
                    
                <?php } ?> -->
                <?php if(!empty($_SESSION['temp']['role_factory_admin'])){?>
                   
                        <?php 
                        $class='';
                        if(!empty($_SESSION['temp']['factory_admin_view'])){$class='button_admin_selected';}
                        ?>
                    <div id="Admin" class="col-xs-1 button_workarea glyphicon glyphicon-wrench <?php echo $class;?>" style="line-height: inherit;" onclick="document.getElementById('admin_form').submit()">
                    </div>
                     
                <?php } ?> 
               <!--<div id="Count" class="col-xs-1 button_workarea" onclick="thetype=\'Count\';load_workarea_v2()">Count</div>
               //echo'<div id="Hours" class="col-xs-1 button_workarea" onclick="thetype=\'Hours\';load_workarea_v2()">Hours</div>-->
            </div>
            
            <?php
            // echo'<div id="here" class="here">';
            //     $workarea=$_POST['workarea'];
                 
            //     show_factory($db,$workarea,$type);
            // echo'</div>';
            echo'<div class="here">';
                show_all_factory($db);
            echo'</div>';
            
        echo'</div>';
        
        ajax_button('load_workarea',[['workarea','theworkarea'],['view_factory',"'ok'"]],'machine_ajax.php','here','empty().append(html)');
        ajax_button('load_type',[['type','thetype'],['view_factory',"'ok'"]],'machine_ajax.php','here','empty().append(html)');
        ajax_button('load_filter',[['filter','thefilter'],['view_factory',"'ok'"]],'machine_ajax.php','here','empty().append(html)');
            ?>
            
            <script>
                document.getElementById(theworkarea).classList.add("button_workarea_selected");
            function load_workarea_v2(){
                document.getElementById('Count-Machining').style.display = "none";
                document.getElementById('Count-Moulding').style.display = "none";
                document.getElementById('Count-Assembly').style.display = "none";
                // document.getElementById('Allocation-Machining').style.display = "none";
                // document.getElementById('Allocation-Moulding').style.display = "none";
                // document.getElementById('Allocation-Assembly').style.display = "none";
               

                document.getElementById("Count-"+theworkarea).style.display = "block";
                //document.getElementById("Allocation-"+theworkarea).style.display = "block";
                
                var elems = document.querySelectorAll(".button_workarea_selected");

                [].forEach.call(elems, function(el) {
                    el.classList.remove("button_workarea_selected");
                });
                document.getElementById(theworkarea).classList.add("button_workarea_selected");
                var request =$.ajax({
                            type:'POST',
                            url:'machine_ajax.php',
                            data: {action:'save_view',workarea:theworkarea},
                            success:function(html){
                                $('.manage_machine').empty().append(html);
                            }
                        });
            }
           
                
            </script><?php

        echo'<div  class="col-md-1 ">';
            
        echo'</div>';
        echo'<div  class="col-md-3 col-lg-3 ">';
            echo'<div id="all_stats">';
            
            echo'</div>';
        echo'</div>';

        echo'<div class="col-sm-4 dialog-box" >';
        
        
        
        echo'</div>';
    echo'</div>';
    echo'<div class="col-sm-4 hidden-box" >';
        
        
        
    echo'</div>';
    
}

function general_view_factory_summary($db){
    echo'<div class="navbar_top">';
    navbar_top($db,'Summary');
    echo'</div>';
    
    //show($allinfos);
    ?>
    <div class="factory_summary">
        <?php show_table_factory_summary($db)?>
    </div>
    
    <?php
}


function navbar_top($db,$option=''){
    
    if(empty($_POST['date_filter'])){
        $_POST['date_filter']=date('Y-m-d',time());
    }
    //$_POST['date_filter']=date('Y-m-d',time());
    //show($_POST['date_filter']);
    $timetag=strtotime($_POST['date_filter']);
    $datetoshow=date('D jS M Y',$timetag);
    
    ?>
    <script>
       
        function loaddate(date){
            var request =$.ajax({
                type:'POST',
                url:'machine_ajax.php',
                data: {date_filter:date,action:'show_all_factory'},
                success:function(html){
                    $('.here').empty().append(html);
                }
            });
            var request =$.ajax({
                type:'POST',
                url:'machine_ajax.php',
                data: {date_filter:date,action:'show_navbar_top',option:'<?php echo$option;?>'},
                success:function(html){
                    $('.navbar_top').empty().append(html);
                }
            });
            var request =$.ajax({
                type:'POST',
                url:'machine_ajax.php',
                data: {date_filter:date,action:'show_factory_summary'},
                success:function(html){
                    $('.factory_summary').empty().append(html);
                }
            });
        }

        function loadtype(date,type){
            var request =$.ajax({
                type:'POST',
                url:'machine_ajax.php',
                data: {date_filter:date,action:'show_navbar_top',option:'<?php echo$option;?>',type:type},
                success:function(html){
                    $('.navbar_top').empty().append(html);
                }
            });
            var request =$.ajax({
                type:'POST',
                url:'machine_ajax.php',
                data: {date_filter:date,action:'show_factory_summary'},
                success:function(html){
                    $('.factory_summary').empty().append(html);
                }
            });
        }
        
    </script>



    <div class="row "style="text-align:center">
       
        <div  class="col-xs-3 col-sm-3 col-md-1 col-lg-1">
            <span class="glyphicon glyphicon-step-backward" onclick="loaddate('<?php echo date('Y-m-d',strtotime($_POST['date_filter'])-3600*24);?>');"></span>
        </div>
        <div  class="col-xs-6 col-sm-6 col-md-2 col-lg-2" >
            <input type=date class="form-control" id="datetoshow" onchange="loaddate(this.value);" value="<?php echo $_POST['date_filter'];?>">
        </div>
        <div  class="col-xs-3 col-sm-3 col-md-1 col-lg-1">
        <span class="glyphicon glyphicon-step-forward" onclick="loaddate('<?php echo date('Y-m-d',strtotime($_POST['date_filter'])+3600*24);?>');"></span>
        </div>
        <?php
        if($option=='Summary'){
            ?>
            <div  class="col-xs-3 col-sm-3 col-md-1 col-lg-1">
            <?php $class='';if($_POST['type']=='Minutes'){$class=' btn-primary ';}?>
            <button class='btn btn-default <?php echo $class;?>' onclick="loadtype('<?php echo $_POST['date_filter'];?>','Minutes');">Minutes</button>
            </div>
            <div  class="col-xs-3 col-sm-3 col-md-1 col-lg-1">
            <?php $class='';if($_POST['type']=='Cycles'){$class=' btn-primary ';}?>
            <button class='btn btn-default <?php echo $class;?>' onclick="loadtype('<?php echo $_POST['date_filter'];?>','Cycles');">Cycles</button>
            </div>
            <div  class="col-xs-3 col-sm-3 col-md-1 col-lg-1">
            <?php $class='';if($_POST['type']=='Parts'){$class=' btn-primary ';}?>
            <button class='btn btn-default <?php echo $class;?>' onclick="loadtype('<?php echo $_POST['date_filter'];?>','Parts');">Parts</button>
            </div>
            <div  class="col-xs-3 col-sm-3 col-md-1 col-lg-1">
            <form method="POST" action="factory.php">
            <button class='btn btn-default' >Live View</button>
            </form>
            </div>
            
            <?php
        }else{
            ?>
            <div  class="col-xs-3 col-sm-3 col-md-1 col-lg-1">
            <form method="POST" action="factory_summary.php">
            <button class='btn btn-default' >Summary</button>
            </form>
            </div>
            
            
            
            <?php
        }
        ?>
    </div>
    
    
    

    
    
    <?php 
}
function navbar_machine($db){

   
    echo'<div class="row navbar navbar_injury">';
        echo'<form method="POST">';
        echo'<div class="col-sm-1 ">';
         
        //if($_SESSION['temp']['id']=='CorentinHillion'){
        echo'<input type="submit" name="view" value="View Live"  class="btn btn-primary injury_button" onclick="submit();" >';
       // }
        echo'</div>';
        echo'<div class="col-sm-1 ">';
        if($_SESSION['temp']['id']=='CorentinHillion'){
        echo'<input type="submit" name="view" value="Import Cycle"  class="btn btn-primary injury_button" onclick="submit();" >';
        }
       
        echo'</div>';
        echo'<div class="col-sm-1 ">';
        //if($_SESSION['temp']['id']=='CorentinHillion'){
        echo'<input type="submit" name="view" value="view_all"  class="btn btn-primary injury_button" onclick="submit();" >';
        //}
        echo'</div>';
        echo'<div class="col-sm-1 ">';
        if($_SESSION['temp']['id']=='CorentinHillion'){
        echo'<input type="submit" name="view" value="Reset Cycle"  class="btn btn-primary injury_button" onclick="submit();" >';
        }
        echo'</div>';
        echo'<div class="col-sm-1 ">';
        //if($_SESSION['temp']['id']=='CorentinHillion'){
        echo'<input type="submit" name="view" value="Last Installed"  class="btn btn-primary injury_button" onclick="submit();" >';
        //}
        echo'</div>';
        echo'<div class="col-sm-2 ">';
        
        echo'<input type="submit" name="view" value="View All Machine"  class="btn btn-primary injury_button" onclick="submit();" >';
       
        echo'</div>';
        echo'<div class="col-sm-2 ">';
            echo'<input type="submit" name="view" value="View Summary"  class="btn btn-primary injury_button" onclick="submit();" >';
        echo'</div>';
        
        echo'</form>';
    echo'</div>';
    
}
function navbar_factory($db){
   
    echo'<div class="row navbar navbar_injury">';
        echo'<form method="POST">';
        echo'<div class="col-sm-1 ">';
        //echo'<input type="submit" name="view" value="View Live"  class="btn btn-primary injury_button" onclick="submit();" >';
       
        echo'</div>';
        echo'<div class="col-sm-1 ">';
        if($_SESSION['temp']['id']=='CorentinHillion'){
        //echo'<input type="submit" name="view" value="Clean Data"  class="btn btn-primary injury_button" onclick="submit();" >';
         }
       
        echo'</div>';
        echo'<div class="col-sm-1 ">';
        if($_SESSION['temp']['id']=='CorentinHillion'){
        //echo'<input type="submit" name="view" value="view_all"  class="btn btn-primary injury_button" onclick="submit();" >';
        }
        echo'</div>';
        echo'<div class="col-sm-1 ">';
        if($_SESSION['temp']['id']=='CorentinHillion'){
        //echo'<input type="submit" name="view" value="View All Device"  class="btn btn-primary injury_button" onclick="submit();" >';
        }
        echo'</div>';
        echo'<div class="col-sm-2 ">';
        
        //echo'<input type="submit" name="view" value="View All Machine"  class="btn btn-primary injury_button" onclick="submit();" >';
       
        echo'</div>';
        echo'<div class="col-sm-2 ">';
        //echo'<input type="submit" name="view" value="View Details"  class="btn btn-primary injury_button" onclick="submit();" >';
       
        echo'</div>';
        
        echo'</form>';
    echo'</div>';
    
}
function refresh_navbar_workarea(){

    echo'<script>
    var elems = document.querySelectorAll(".button_workarea_selected");

    [].forEach.call(elems, function(el) {
        el.classList.remove("button_workarea_selected");
    });
    document.getElementById(\''.$_SESSION['factory']['workarea'].'\').classList.add("button_workarea_selected");
    document.getElementById(\''.$_SESSION['factory']['type'].'\').classList.add("button_workarea_selected");
    </script>';
}

function navbar_all_stats_machine($db){

   
    echo'<div class="row navbar navbar_injury">';
        
        echo'<div class="col-sm-3 ">';
        echo'<form method="POST">';
        echo'<select name="machine_name" class="form-control" onChange=\'submit();\'>';
            
            echo'<option ';
            if(empty($_POST['machine_name'])){echo'selected';}
            echo'>All</option>';
            
            echo'<option disabled>_________</option>';
            foreach(get_all_machine_event($db) as $single){
                echo'<option ';
                if($_POST['machine_name']==$single['machine_name']){
                    echo'selected';
                
                }
                echo'>'.$single['machine_name'].'</option>';
            }
        echo'</select>';
        echo'</div>';
        echo'<div class="col-sm-3 ">';
        if(!empty($_POST['machine_name'])){
        echo'<select name="entry_type" class="form-control" onChange=\'submit();\'>';
            
            echo'<option ';
            if(empty($_POST['entry_type'])){echo'selected';}
            echo'>All</option>';
            
            echo'<option disabled>_________</option>';
            foreach(get_all_type_event($db) as $single){
                echo'<option ';
                if($_POST['entry_type']==$single['machineevent_type']){
                    echo'selected';
                
                }
                echo'>'.$single['machineevent_type'].'</option>';
            }
        echo'</select>';
        }
       
        echo'</div>';
        
        
        echo'<div class="col-sm-2 ">';
            echo'<select name="date_filter" class="form-control" onChange=\'submit();\'>';
                echo'<option ';
                if(empty($_POST['date_filter'])or $_POST['date_filter']=='Today'){echo'selected';}
                echo'>Today</option>';
                echo'<option ';
                if( $_POST['date_filter']=='Yesterday'){echo'selected';}
                echo'>Yesterday</option>';
                echo'<option ';
                if($_POST['date_filter']=='Last Hour'){echo'selected';}
                echo'>Last Hour</option>';
                echo'<option ';
                if($_POST['date_filter']=='Last 24 Hours'){echo'selected';}
                echo'>Last 24 Hours</option>';
                echo'<option ';
                if($_POST['date_filter']=='Last 7 Days'){echo'selected';}
                echo'>Last 7 Days</option>';
                echo'<option ';
                if($_POST['date_filter']=='Last Month'){echo'selected';}
                echo'>Last Month</option>';
                echo'<option ';
                if($_POST['date_filter']=='Last Year'){echo'selected';}
                echo'>Last Year</option>';
                echo'<option ';
                if($_POST['date_filter']=='Custom'){echo'selected';}
                echo'>Custom</option>';
                
            echo'</select>';
        echo'</div>';
        echo'<div class="col-sm-2 ">';
            if($_POST['date_filter']=='Custom'){
                echo'<input type="date" name="date_filter_start" value="'.$_POST['date_filter_start'].'"  class="form-control" onChange="submit();" >';
                echo'<input type="date" name="date_filter_end" value="'.$_POST['date_filter_end'].'"  class="form-control" onChange="submit();" >';
            }
        
        echo'</div>';
        echo'<div class="col-sm-2 ">';
            if($_POST['date_filter']=='Custom'){
                echo'<input type="time" name="time_filter_start" value="'.$_POST['time_filter_start'].'" step="1" class="form-control" onChange="submit();" >';
                echo'<input type="time" name="time_filter_end" value="'.$_POST['time_filter_end'].'" step="1" class="form-control" onChange="submit();" >';
            }
        echo'</form>';
        echo'</div>';
        echo'<div class="col-sm-2 ">';
        echo'<form method="POST">';
        if(!empty($_POST['machine_name']) and $_SESSION['temp']['id']=='CorentinHillion'){
            echo'<input type="submit" name="reset_import" value="Reset_all"  class="btn btn-primary injury_button" onclick="submit();" >';
            echo'<input type="hidden" name="machine_name" value="'.$_POST['machine_name'].'"   >';
        }
        echo'</form>';
        echo'</div>';
        
        
    echo'</div>';
    
}

function navbar_all_machine($db){

   
    echo'<div class="row navbar navbar_injury">';
        
    echo'<form method="POST">';
        echo'<div class="col-sm-3 ">';
        
        echo'</div>';
        echo'<div class="col-sm-3 ">';
        if($_POST['view']=='View All Machine'){
        if($_POST['machine_list']=='All Machine'){$value='Not All Machine';}else{$value='All Machine';}
        echo'<input type="submit" name="machine_list" value="'.$value.'"  class="btn btn-primary injury_button" onclick="submit();" >';
        }
        echo'</div>';
        
        
        // echo'<div class="col-sm-2 ">';
        //     echo'<select name="date_filter" class="form-control" onChange=\'submit();\'>';
        //         echo'<option ';
        //         if(empty($_POST['date_filter'])or $_POST['date_filter']=='Today'){echo'selected';}
        //         echo'>Today</option>';
        //         echo'<option ';
        //         if( $_POST['date_filter']=='Yesterday'){echo'selected';}
        //         echo'>Yesterday</option>';
        //         echo'<option ';
        //         if($_POST['date_filter']=='Last Hour'){echo'selected';}
        //         echo'>Last Hour</option>';
        //         echo'<option ';
        //         if($_POST['date_filter']=='Last 24 Hours'){echo'selected';}
        //         echo'>Last 24 Hours</option>';
        //         echo'<option ';
        //         if($_POST['date_filter']=='Last 7 Days'){echo'selected';}
        //         echo'>Last 7 Days</option>';
        //         echo'<option ';
        //         if($_POST['date_filter']=='Last Month'){echo'selected';}
        //         echo'>Last Month</option>';
        //         echo'<option ';
        //         if($_POST['date_filter']=='Last Year'){echo'selected';}
        //         echo'>Last Year</option>';
        //         echo'<option ';
        //         if($_POST['date_filter']=='Custom'){echo'selected';}
        //         echo'>Custom</option>';
                
        //     echo'</select>';
        // echo'</div>';
        // echo'<div class="col-sm-2 ">';
        //     if($_POST['date_filter']=='Custom'){
        //         echo'<input type="date" name="date_filter_start" value="'.$_POST['date_filter_start'].'"  class="form-control" onChange="submit();" >';
        //         echo'<input type="date" name="date_filter_end" value="'.$_POST['date_filter_end'].'"  class="form-control" onChange="submit();" >';
        //     }
        
        // echo'</div>';
        // echo'<div class="col-sm-2 ">';
        //     if($_POST['date_filter']=='Custom'){
        //         echo'<input type="time" name="time_filter_start" value="'.$_POST['time_filter_start'].'" step="1" class="form-control" onChange="submit();" >';
        //         echo'<input type="time" name="time_filter_end" value="'.$_POST['time_filter_end'].'" step="1" class="form-control" onChange="submit();" >';
        //     }
       
        // echo'</div>';
        echo'</form>';
        
        
    echo'</div>';
    
}

function show_table_factory_summary($db){
    $allinfos=get_info_factory_summary($db);
    ?>
    <div class="table_summary">
        
    <?php
    $last=array();
    foreach(get_all_machine($db) as $machine){
        if($machine['machine_workarea']<>$last['machine_workarea']){
            ?>
                <br><div class="row machine_header">
                <?php echo $machine['machine_workarea']; ?>
                </div>
                <div class="row machine_header">
                    <div class="col-xs-3 col-sm-3 col-md-2 col-lg-1">
                    Machine Name
                    </div>
                    <div class="col-xs-9 col-sm-9 col-md-10 col-lg-11">
                        <?php for($i=0;$i<24;$i++){ ?>
                            <div class="hours">
                                <?php echo $i; ?>
                            </div>
                            <?php
                        }?>
                    </div>
                </div>
            <?php
        }

        //if(!empty($allinfos[$machine['machine_name']]['Total'])){
            
            ?>
            <div class="row machine_row">
                <div class="col-xs-3 col-sm-3 col-md-2 col-lg-1">
                <?php echo $machine['machine_name']; ?>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-10 col-lg-11">
                    <?php for($i=0;$i<24;$i++){
                        $hour=$allinfos[$machine['machine_name']]['Cycles'][$i]['Hours'];
                        if($hour>0.7){
                            $class='hours_70_100';
                        }elseif($hour>0.6){
                            $class='hours_50_70';
                        }elseif($hour>0.4){
                            $class='hours_0_50';
                        }elseif($hour>0){
                            $class='hours_0';
                        }else{
                            $class='hours_00';
                        }
                        ?>
                        <div class="hours <?php echo $class; ?>">
                            <?php 
                            if($_POST['type']=='Minutes' or empty($_POST)){
                                echo number_format($allinfos[$machine['machine_name']]['Cycles'][$i]['Hours']*60,0); ?><br><?php
                            }
                            if($_POST['type']=='Cycles' ){
                                echo number_format($allinfos[$machine['machine_name']]['Cycles'][$i]['Count'],0); ?><br><?php
                            }
                            if($_POST['type']=='Parts' ){
                                echo number_format($allinfos[$machine['machine_name']]['Cycles'][$i]['Count_Parts'],0); ?><br><?php
                            }
                            
                            ?>
                            <!--<?php echo number_format($allinfos[$machine['machine_name']]['Cycles'][$i]['Hours']*60,0); ?><br>-->
                            <!--<?php echo number_format($allinfos[$machine['machine_name']]['Cycles'][$i]['Count'],1); ?><br>-->
                            <!--<?php echo number_format($allinfos[$machine['machine_name']]['Cycles'][$i]['count_scan'],1); ?>-->
                        </div>
                        <?php
                    }?>
                </div>
            </div>
            <?php
        //}
        $last['machine_workarea']=$machine['machine_workarea'];
    }

    ?>
    </div>
    <style>
    

    </style>
    <?php
}
function show_factory($db,$workarea,$type){
    //refresh_navbar_workarea();
    $allmachine=(get_info_factory($db,$workarea));
    echo'<div class="factory" style="margin-bottom:20px"> ';
    foreach($allmachine as $machine){
        if(isset($machine['machine_location_x'])){
            echo'<div id="'.$machine['machine_name'].'" class="machine';
                if(!empty($machine['machine_location_horizontal'])){echo' machine_v ';}
                if(!empty($machine[$type])){echo' machine_active ';}
                echo'" ';
                echo' style="left:'.$machine['machine_location_x'].'%;top:'.$machine['machine_location_y'].'%;" ';
                //echo' onClick="detailsmachine(\''.$machine['machine_name'].'\')" ';
            echo'>';
                echo'<div style="display:block">';
                    echo'<div id="'.$machine['machine_name'].'header" class="row">';
                        echo'<div class="col-md-9" style="text-align:center "> ';
                            echo $machine['machine_name'];
                        echo'</div>';

                        echo'<div class="col-md-3" style="text-align:center"> ';
                        if(!empty($machine['Count'])){
                            echo number_format($machine['Count'],0).'';
                        }
                        echo'</div>';
                    echo'</div>';
                
                    echo'<div id="'.$machine['machine_name'].'-machine_more" style="display:none;" class="row">';
                        if(!empty($machine['Hours'])){
                            //echo'<div class="col-md-6" style="text-align:center ">Hours</div>';
                            echo'<div class="col-md-12" style="text-align:center"> '.number_format($machine['Hours'],1).' h'.'</div>';
                            //echo'<div class="col-md-6" style="text-align:center ">Cycle/H</div>';
                            echo'<div class="col-md-12" style="text-align:center"> '.number_format($machine['Count']/$machine['Hours'],0).' cycle/h'.'</div>';
                        }
                    echo'</div>';
                echo'</div>';
            echo'</div>';
            echo'<script>dragElement(document.getElementById("'.$machine['machine_name'].'"));</script>';
        }
        
    }
    echo'<script>
    countzindex=99;
    function detailsmachine(id){
        var para=document.getElementById(id+"-machine_more");
        if (para.style.display === "none") {
            para.style.display = "block";
          } else {
            para.style.display = "none";
          }
          document.getElementById(id).style.zindex=countzindex;
          countzindex=countzindex+1;
    }
    </script>';
    echo'</div>';
    ?>
   

    <?php



    //refresh_div('here',60000);
}
function show_factory2($db,$workarea,$type){
    ?><script>
        function load_details_machine(machine_id){
            var current_selection="";  
                var current = document.getElementsByClassName("button_manage_machine_selected");
                if (current.length > 0) { 
                    current_selection=String(current[0].id);
                    
                }
                if(current_selection!="move_machine"){
                    var request =$.ajax({
                            type:'POST',
                            url:'machine_ajax.php',
                            data: {machine_id:machine_id,action:'show_details_machine'},
                            success:function(html){
                                $('.manage_machine').empty().append(html);
                            }
                        });
                }
        }
    </script>
    <?php
    //refresh_navbar_workarea();
    $allmachine=(get_info_factory($db,$workarea));?>
    <div id="factory<?php echo$workarea;?>" class="factory" style="margin-bottom:20px">
    <?php
    foreach($allmachine as $machine){
        //show($machine);
        if(isset($machine['machine_location_x'])){
                $class='';
                //if(!empty($machine['machine_location_horizontal'])){$class=' machine_v ';}
                if(!empty($machine['Count'])){
                    if(empty($machine['machineproduct_productcode'])){
                        $class=$class.' machine_notallocated ';
                    }else{
                        if((time()-$machine['lasttimetag'])>=1800){
                            $class=$class.' machine_waiting ';
                        }else{
                            $class=$class.' machine_running ';
                        }
                    }
                }else{
                    if(empty($machine['machineallocation_MAC'])){
                        $class=$class.' machine_not_plugged ';
                    }else{
                        $class=$class.' machine_not_running ';
                    }
                }
                
                
                
                $widthmac=10;//width of machine is 10%
                $heightmac=10;//width of machine is 10%
                $left=round($machine['machine_location_x']*(100-$widthmac)/100);
                $top=round($machine['machine_location_y']*(100-$heightmac)/100);
                if(empty($_SESSION['temp']['factory_user_view'])){
                $onclick='onclick="load_details_machine(\''.$machine['machine_id'].'\')"';
                }else{
                    $onclick='';
                }
            ?>
            <div id="mac<?php echo $machine['machine_id'];?>" class="machine <?php echo $class;?>" 
            style="left:<?php echo $left;?>%;top:<?php echo $top;?>%;"
            <?php echo $onclick; ?> >
                <div id="mac<?php echo $machine['machine_id'];?>header" class="row ">
                    <div class="col-md-12 machineheader" style="text-align:center ">
                        <?php echo $machine['machine_name'];?>
                        <?php 
                        //$machine['Count_operator']=rand(1,3);
                        $count_operator=$machine['Count_Operator'];
                        if($count_operator>0){
                            //for ($i=0;$i<$count_operator;$i++){
                                ?>
                                <span class="glyphicon glyphicon-barcode"></span>
                                <?php
                            //}
                            
                        }
                        ?>
                    </div>
                    <div class="col-md-12 machinecode" style="text-align:center "><?php echo $machine['machineproduct_productcode'];?></div>
                    <div class="col-md-12 machineheader" style="text-align:center" >
                        <div class="col-md-12" style="text-align:center">
                        <?php 
                        if(!empty($machine['Count'])){
                            $number=$machine['Count'];
                            if(!empty($machine['machineproduct_productcode'])){
                                if(!empty($machine['asset_cavity'])){
                                    $number=$number*$machine['asset_cavity'];
                                }
                                $end='parts';
                            }else{
                                $end='cycles';
                            }
                            echo number_format($number,0)." $end";
                        }
                            ?>
                        </div>
                        <!--<div class="col-md-12" style="text-align:center">
                        <?php if(!empty($machine['count_scan'])){echo number_format($machine['count_scan'],0).' scans';}?>
                        </div>-->
                    </div>
                    <div class="col-md-12 machinecode" style="text-align:center ">
                        
                    </div>
                    <div class="col-md-12">
                        <!--<div id="mac<?php echo$machine['machine_id']; ?>x">x=<?php echo$machine['machine_location_x']; ?>%</div>
                        <div id="mac<?php echo$machine['machine_id']; ?>y">y=<?php echo$machine['machine_location_y'];?>%</div>-->
                    </div>
                </div>


                
                
               
            </div>
            <script>dragElement(document.getElementById("mac<?php echo$machine['machine_id'];?>"),"<?php echo$machine['machine_workarea']?>"); </script>
                
               
        <?php  
        }?>
        <style>
       
    </style>
    <?php    
    }?>
    </div>
    <?php
    //refresh_div('here',60000);
}
function show_all_factory($db){
    
	
    $_POST['workarea']=$_SESSION['factory']['workarea'];
    $_POST['type']=$_SESSION['factory']['type'];
    $allworkarea[]='Machining';
    $allworkarea[]='Moulding';
    $allworkarea[]='Assembly';
    $type='Count';
    //show($_POST);
        foreach($allworkarea as $area){
            $style='display:none;';
            if($_POST['workarea']==$area ){
                $style='display:block;';
            }
            
            echo'<div id="'.$type.'-'.$area.'" class="heref" style="'.$style.'">';
            //show('test');
                show_factory2($db,$area,$type);
            echo'</div>';
        }
        echo'<div id="manage_machine" class="manage_machine">';
        show_windows_details_machine($db);
        
        
        echo'</div>';
}
function show_factory_filter($db,$workarea,$type){
    $options[]='Today';
    $options[]='Yesterday';
    //$options[]='Last Hour';
    //$options[]='Last 24 Hours';
    $options[]='Last 7 Days';
    $options[]='Last Month';
    $options[]='Last Year';
    //$options[]='Custom';
    if(empty($_POST['date_filter'])){$_POST['date_filter']='Today';}
    echo'<div class="factory" style="margin-bottom:20px"><br><br> ';
        echo'<div class="col-xs-3" ></div> ';
        echo'<div class="col-xs-2" > ';
        echo'<form method="POST">';
        echo'<select name="date_filter" class="form-control" onChange=\'submit();\'>';
            foreach($options as $option){
                echo'<option ';
                if($_POST['date_filter']==$option){echo'selected';}
                echo'>'.$option.'</option>';
            }
        echo'</select>';
        //echo'<input type="hidden" name="filter" value="Filter">';
        echo'</form>';
        echo'</div> ';
    echo'</div>';
    
}



function show_view_all_summary($db){
   

    $col1='col-lg-1 col-md-1 col-sm-1 hidden-xs';
    $col2='col-lg-2 col-md-2 col-sm-3 col-xs-6';
    $col3='col-lg-1 col-md-1 col-sm-1 col-xs-3';
    $col4='col-lg-1 col-md-1 col-sm-1 col-xs-3';
    $col5='col-lg-2 col-md-2 col-sm-4 col-xs-6';
    $col6='col-lg-2 col-md-2 col-sm-4 col-xs-6';
    

    echo'<div class="row machine_header">';
        echo'<div class="'.$col1.'">WorkArea</div>';
        echo'<div class="'.$col2.'">Machine</div>';
        echo'<div class="'.$col4.'">Count</div>';
        echo'<div class="'.$col5.'">Hours</div>';
        echo'<div class="'.$col6.'">Cycle (sec)</div>';
        echo'<div class="'.$col6.'">Cycle/h</div>';
    echo'</div>';
    echo'<div class="all_line_container">';
    
    foreach(get_all_machine_summary_cycle($db) as $entry){
        
        echo'<div class="row machine_row">';
            echo'<div class="'.$col1.'">';
                echo $entry['machine_workarea'];
            echo'</div>';
            echo'<div class="'.$col2.'">';
                echo $entry['machine_name'];
            echo'</div>';
            echo'<div class="'.$col4.'">';
                echo number_format($entry['count_cycle']);
            echo'</div>';
            echo'<div class="'.$col5.'">';
                echo show_time($entry['hours_cycle']*3600);
            echo'</div>';
            echo'<div class="'.$col6.'">';
                echo number_format($entry['average_cycle']);
            echo'</div>';
            echo'<div class="'.$col6.'">';
                echo number_format(3600/$entry['average_cycle'],0);
            echo'</div>';
            
            
    
        echo'</div>';
        $lastimetag[$entry['machineevent_entry']]=$entry['machineevent_timetag'];
    }
    
    
    echo'</div>';
    //refresh_div('here',500);
}

function show_all_temptable($db){
    echo'<div class="row machine_header">';
        echo'<form method="POST"><div class="col-sm-6 col-md-3"><Select name="temptable_MAC" oninput="submit();">';
        echo'<option ';
        if(empty($_POST['temptable_MAC'])){echo' selected ';}
        echo'></option>';
        foreach(get_list_MAC($db) as $MAC){
            echo'<option value="'.$MAC['temptable_MAC'].'"';
            if($_POST['temptable_MAC']==$MAC['temptable_MAC']){echo' selected ';}
            echo'>'.$MAC['machine_name'].'</option>';
        }
        echo'</Select></div>';
        echo'<div class="col-sm-6 col-md-3"><input type="submit" value="Refresh">';
        
        echo'</Select></div></form>';
        
        
    echo'</div>';
    echo'<div class="row machine_header">';
        echo'<div class="col-sm-6 col-md-2">MAC Adress</div>';
        echo'<div class="col-sm-6 col-md-3">Machine name</div>';
        echo'<div class="col-sm-6 col-md-3">Date</div>';
        echo'<div class="col-md-1 col-sm-2">Pin</div>';
        echo'<div class="col-md-1 col-sm-2">Value</div>';

    echo'</div>';

    foreach(get_all_temptable($db) as $entry){
        echo'<div class="row machine_row">';
            echo'<div class="col-sm-6 col-md-2">';
                echo $entry['temptable_MAC'];
            echo'</div>';
            echo'<div class="col-sm-6 col-md-3">';
                echo $entry['machine_name'];
            echo'</div>';
            echo'<div class="col-sm-6 col-md-3">';
            echo date('Y-m-d G:i:s',$entry['temptable_timetag']);
            echo'</div>';
            echo'<div class="col-md-1 col-sm-2">';
            echo $entry['temptable_pin'];
            echo'</div>';
            echo'<div class="col-md-1 col-sm-2">';
            echo round($entry['temptable_value'],2);
            echo'</div>';

        echo'</div>';
    }
    // echo'<script> 
    //     $(document).ready(function(){
    //     setInterval(function(){
    //           $("#here").load(window.location.href + " #here" );
    //     }, 500);
    //     });
    //     </script>';
}

function show_last_installed($db){
    $query='SELECT temptable_MAC, max(temptable_timetag) as lasttimetag,machine_name
    FROM temptable_v2
    left join machine_allocation on machineallocation_MAC=temptable_MAC
    left join machine on machine_id=machineallocation_machineid
    WHERE temptable_pin=9 and temptable_value=1
    group by temptable_MAC,machine_name
    
    order by max(temptable_timetag) desc,temptable_MAC
    
    
    ';
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $results=$sql->fetchall();?>
    <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-3">
               <form method="POST">
                   <input type="submit" name="clean_temptable_not_allocated" value="Clean not Allocated Scan">
                   <input type="hidden" name="view" value="Last Installed">
               </form>
            </div>
            

        </div><?php
    foreach($results as $result){
        ?>
         
        <div class="row">
            <div class="col-xs-2">
                <?php echo $result['temptable_MAC'];?>
            </div>
            <div class="col-xs-2">
                <?php echo $result['machine_name'];?>
            </div>
            <div class="col-xs-2">
                <?php echo date('Y-m-d G:i:s',$result['lasttimetag']);?>
            </div>
            <div class="col-xs-2">
                <?php show_time(time()-$result['lasttimetag']);?>
            </div>

        </div>
        <?php
    }

}

function show_view_live_temptable($db){
    echo'<div class="row machine_header">';
        echo'<form method="POST"><div class="col-sm-6 col-md-3"><Select name="temptable_MAC" oninput="submit();">';
        echo'<option value="All"';
        if(empty($_POST['temptable_MAC'])){echo' selected ';}
        echo'>All</option>';
        foreach(get_list_MAC($db) as $MAC){
            echo'<option value="'.$MAC['temptable_MAC'].'"';
            if($_POST['temptable_MAC']==$MAC['temptable_MAC']){echo' selected ';}
            echo'>';
            if(!empty($MAC['machine_name'])){
                echo $MAC['machine_name'];
            }else{
                echo $MAC['temptable_MAC'];
            }
            echo'</option>';
        }
        echo'</Select></div>';
        echo'<div class="col-sm-6 col-md-3"><input type="submit" value="Refresh">';
        
        echo'</Select></div></form>';
        
        
    echo'</div>';
    if($_POST['temptable_MAC']=='All'){
        unset($_SESSION['temp']['temptable_MAC']);unset($_POST['temptable_MAC']);
    }
    if(empty($_POST['temptable_MAC'])){
        if(empty($_SESSION['temp']['temptable_MAC'])){
            $get_last=get_last_temptable($db);
        }else{
            $get_last=get_last_temptable($db,"Where temptable_MAC='".$_SESSION['temp']['temptable_MAC']."'"); 
        }
    }else{
        
        $get_last=get_last_temptable($db,"Where temptable_MAC='".$_POST['temptable_MAC']."'");
        $_SESSION['temp']['temptable_MAC']=$_POST['temptable_MAC'];
    }
        $i=1;
    foreach($get_last as $entry){
        //show($entry);
        echo'<div id="line'.$i.'" class="row machine_row">';
            $i++;
            echo'<div class="col-xs-9 ">';
            if(empty($entry['machine_name'])){
                echo'<div class="col-sm-12 time_big">';
                echo $entry['temptable_MAC'];
                echo'</div>';
            }else{
                echo'<div class="col-sm-12 time_big">';
                echo $entry['machine_name'];
                echo'</div>';
                echo'<div class="col-sm-12"><i>';
                echo $entry['temptable_MAC'];
                echo'</i></div>';
                
            }
            echo'</div>';
            echo'<div class="col-xs-3 ">';
            echo'</div>';
            echo'<div class="col-md-12 col-sm-12" style="margin-bottom:20px;">';
            sort($entry['pin']);
            foreach($entry['pin'] as $pin){
                
                if(!empty($pin['machinepin_pindescription']) or (time()-$pin['temptable_timetag'])<1200){?>
                    <div class="pin_live  <?php 
                    if(!empty($_SESSION['temp']['pin_to_show'][$entry['temptable_MAC']][$pin['temptable_pin']])){
                        echo ' pin_selected ';
                    }
                    ?>" onclick="loaddetails('<?php echo $entry['temptable_MAC'].'\',\''.$pin['temptable_pin'].'\',\'';
                    if(!empty($_SESSION['temp']['pin_to_show'][$entry['temptable_MAC']][$pin['temptable_pin']])){
                        echo'remove';
                    }else{
                        echo'add';
                    }
                    echo '\''; ?>)">
                        <div class="row ">PIN <?php echo ( $pin['temptable_pin'])?></div>
                        <div class="row ">
                            <?php
                            if(!empty($pin['machinepin_pindescription'])){
                                echo $pin['machinepin_pindescription'];
                            }else{
                                echo'<br>';
                            }
                            ?>
                        </div>

                        
                        <div class="row time_big">
                        <?php echo round( $pin['temptable_value'],2)?><br>
                            
                        </div>
                        <div class="row ">
                            <?php echo date('G:i:s',$pin['temptable_timetag']);?>
                        </div>
                        <div class="row ">
                            <?php echo show_time(time()-$pin['temptable_timetag'])?><br>
                            
                        </div>
                        
                    </div>
                <?php  
                }  
            }
            echo'</div>';

        echo'</div>';
         
         //show($_SESSION['temp']['pin_to_show']);
         
         if(!empty($_SESSION['temp']['pin_to_show'][$entry['temptable_MAC']] )){
            $minutestoshow=5;
            if(!empty($_POST['minutestoshow'])){
                $minutestoshow=$_POST['minutestoshow']+0;
            }
            echo'<div class="row machine_row">';
            show_timeline_pin_time($db,$minutestoshow);
            echo'</div>';
         }
         
         ?>
    <form method="POST" id="load_details_form">
        <input type="hidden" id="temptable_MAC" name="temptable_MAC" value="">
        <input type="hidden" id="temptable_pin" name="temptable_pin" value="">
        <input type="hidden" id="type" name="type" value="add">
        <input type="hidden" id="view" name="" value="View Live">
    </form>
    <script>
        function loaddetails(temptable_MAC,temptable_pin,type){
            document.getElementById('temptable_MAC').value=temptable_MAC;
            document.getElementById('temptable_pin').value=temptable_pin;
            document.getElementById('type').value=type;
            document.getElementById('load_details_form').submit();

        }
    </script>


        <?php
       
    }
       
    echo'<script> 
        $(document).ready(function(){
        setInterval(function(){';
            for($j=1;$j<=$i;$j++){
                echo'$("#line'.$j.'").load(window.location.href + " #line'.$j.'" );';
            }
            
            echo'//$("#all_stats").load(window.location.href + " #all_stats" );
        }, 500);
        });
        </script>';
}
function show_view_live_temptable_details($db,$temptable_MAC,$temptable_pin){
    $minutestoshow=5;
    if(!empty($_POST['minutestoshow'])){
        $minutestoshow=$_POST['minutestoshow']+0;
    }
    $filter='WHERE 1=1';
    
    if(!empty($_SESSION['temp']['pin_to_show'])){
        $filter= $filter.' and (0=1 ';
        foreach($_SESSION['temp']['pin_to_show'] as $temparray){
            foreach($temparray as $pintoshow){
                $filter= $filter.' or (temptable_MAC=\''.$pintoshow['temptable_MAC'].'\' and temptable_pin=\''.$pintoshow['temptable_pin'].'\')';
                $listpin[$pintoshow['temptable_MAC'].$pintoshow['temptable_pin']]['name']='PIN'.$pintoshow['temptable_pin'];
                $listpin[$pintoshow['temptable_MAC'].$pintoshow['temptable_pin']]['temptable_pin']=$pintoshow['temptable_pin'];
                }
            }
        $filter= $filter.')';
    }
    $query="SELECT TOP 100 *
    FROM temptable_v2
    $filter
    and temptable_timetag>='".(time()-($minutestoshow*60))."'
    order by temptable_MAC,temptable_timetag desc,temptable_id desc";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $all_temptable_details=$sql->fetchall();?>
    <div class="row machine_header">
        <div class="col-sm-12"><?php foreach($_SESSION['temp']['pin_to_show'] as $pintoshow){
           $mactoshow=$pintoshow['temptable_MAC'].' ' ;
        } echo$mactoshow;?></div>
        <div class="col-sm-12"><?php foreach($_SESSION['temp']['pin_to_show'] as $pintoshow){
           echo 'PIN '.$pintoshow['temptable_pin'].', ' ;
        }  ?></div>
    </div>
    <div class="row machine_row">
        <form method="POST">
            <input type="hidden" id="temptable_MAC" name="temptable_MAC" value="<?php echo $temptable_MAC; ?>">
            <input type="hidden" id="temptable_pin" name="temptable_pin" value="<?php echo $temptable_pin; ?>">
            <?php
                $options=[1,2,3,4,5,15,30,60,360,1440];
                foreach ($options as $option){
                    ?><div class="col-sm-2">
                        <input type="submit" 
                        class="btn <?php if(!empty($_POST['minutestoshow'])and $_POST['minutestoshow']==$option ){echo'btn-primary';}?> " 
                        name="minutestoshow" 
                        value="<?php echo $option; ?>">
                    </div>
                <?php
                }
            ?>
            
           </form>
    </div>
    
    <?php 
    show_graph_pin_time($db,$minutestoshow);
    //show_timeline_pin_time($db,$minutestoshow,1);
    echo'<div class="row machine_header">';
        echo'<div class="col-sm-6 col-md-6">Date</div>';
        echo'<div class="col-md-3 col-sm-3">Pin</div>';
        echo'<div class="col-md-3 col-sm-3">Value</div>';

    echo'</div>';
    foreach($all_temptable_details as $entry){
        //show($entry);
        if(!empty($last_timetag)){
            echo'<div class="row machine_row">';
            echo show_time($last_timetag-$entry['temptable_timetag']);
            echo'</div>';
        }
        
        $last_timetag=$entry['temptable_timetag'];
        echo'<div class="row machine_row">';
            echo'<div class="col-sm-6 col-md-6">';
            echo date('Y-m-d G:i:s',$entry['temptable_timetag']);
            echo'</div>';
            echo'<div class="col-md-3 col-sm-3">';
            echo $entry['temptable_pin'];
            echo'</div>';
            echo'<div class="col-md-3 col-sm-3">';
            echo round($entry['temptable_value'],2);
            echo'</div>';

        echo'</div>';
       
    }
}
function show_view_all_machine($db){
    //import_temptable($db);
    
    $col1='col-lg-1 col-md-1 col-sm-1 hidden-xs';
    $col2='col-lg-2 col-md-2 col-sm-3 col-xs-6';
    $col3='col-lg-1 col-md-1 col-sm-1 col-xs-3';
    $col4='col-lg-1 col-md-1 col-sm-1 col-xs-3';
    $col5='col-lg-2 col-md-2 col-sm-4 col-xs-6';
    

    echo'<div class="row machine_header">';
        echo'<div class="'.$col1.'">WorkArea</div>';
        echo'<div class="'.$col2.'">Machine</div>';
        echo'<div class="'.$col2.'">Mac address</div>';
        echo'<div class="'.$col3.'">Last Scan</div>';
        echo'<div class="'.$col4.'">Sensors</div>';
        echo'<div class="'.$col4.'">Count</div>';
        echo'<div class="'.$col5.'">Action</div>';
    echo'</div>';
    echo'<div class="all_line_container">';
    
    foreach(get_all_machine_summary($db) as $entry){
        
        echo'<div class="row machine_row">';
            echo'<div class="'.$col1.'">';
                echo $entry['machine_workarea'];
            echo'</div>';
            echo'<div class="'.$col2.'">';
                echo $entry['machine_name'];
            echo'</div>';
            echo'<div class="'.$col2.'">';
                echo $entry['temptable_MAC'];
            echo'</div>';
            echo'<div class="'.$col3.'">';
                if(!empty($entry['lastscan'])){
                    echo date('Y-m-d',$entry['lastscan']);
                    echo '<br>';
                    echo date('G:i:s',$entry['lastscan']);
                }else{
                    echo'-';
                }
            echo'</div>';
            echo'<div class="'.$col4.'">';
            if(!empty($entry['numberofsensor'])){
                echo number_format($entry['numberofsensor']);
            }else{
                echo'-';
            }
            echo'</div>';
            echo'<div class="'.$col4.'">';
            if(!empty($entry['thecount'])){
                echo number_format(($entry['thecount'])).'';
            }else{
                echo'-';
            }
            echo'</div>';
            //thecount
            echo'<div class="'.$col5.'">';
            echo'<form method="POST">';
            echo '<button type="submit" name="view" value="manage_machine" class="btn btn-default">';
                echo'<span class="glyphicon glyphicon-wrench"> </span>';
            echo'</button>';
            echo '<button type="submit" name="view" value="remove_machine" class="btn btn-default">';
                echo'<span class="glyphicon glyphicon-trash"> </span>';
            echo'</button>';
            echo'<input type="hidden" name="machine_name" value="'.$entry['machine_name'].'">';
            echo'</form>';
            echo'</div>';
            
    
        echo'</div>';
        $lastimetag[$entry['machineevent_entry']]=$entry['machineevent_timetag'];
    }
    
    
    echo'</div>';
    //refresh_div('here',500);
}
function show_view_all_device($db){
    //import_temptable($db);
    
    $col1='col-sm-2';
    $col2='col-sm-2';
    $col3='col-sm-2';
    $col4='col-sm-2';
    $col5='col-sm-2';
    $col6='col-sm-2';
    if(!empty($_POST['MAC'])){
        echo'<div class="row machine_header">'.$_POST['MAC'];
        echo'</div>';
    }
    
    echo'<div class="row machine_header">';
        echo'<div class="'.$col1.'">MAC Adress</div>';
        echo'<div class="'.$col2.'">Current Allocation</div>';
        echo'<div class="'.$col3.'">Count</div>';
        echo'<div class="'.$col4.'">Scan</div>';
        //echo'<div class="'.$col5.'">Allocation</div>';
        echo'<div class="'.$col6.'">Action</div>';
        
    echo'</div>';
    echo'<div class="all_line_container">';
    
    foreach(get_all_device_summary($db) as $entry){
        $stats=get_hours_trend($db,$entry['machine_name']);
        echo'<div class="row machine_row ';
        if (empty($entry['machineallocation_timetag_start'])){echo ' not_allocated';}
        echo'">';
            echo'<div class="'.$col1.'">';
            if (!empty($entry['temptable_MAC'])){
                echo $entry['temptable_MAC'];
            }else{
                echo $entry['machineallocation_MAC'];
            }
            echo'</div>';
            echo'<div class="'.$col2.'">';
            if (empty($entry['machineallocation_timetag_start'])){
                echo 'Device not Allocated ';
            }else{
                echo $entry['machine_name'];
            }
            
            echo'</div>';
            echo'<div class="'.$col3.'">';
            echo number_format($entry['thecount']);
            echo'</div>';
            echo'<div class="'.$col4.'">';
            if (!empty($entry['firstscan'])){
                echo date('Y-m-d G:i:s',$entry['firstscan']);
                echo'</br>';
                echo date('Y-m-d G:i:s',$entry['lastscan']);
            }
                
            echo'</div>';
            echo'<div class="'.$col6.'">';
            if (empty($entry['machineallocation_timetag_start'])){
                echo'<form method="POST">';
                echo'<input type="submit" name="view" value="Allocation Machine"  class="btn btn-primary injury_button" onclick="submit();" >';
                echo'<input type="hidden" name="MAC" value="'.$entry['temptable_MAC'].'">';
               
                echo'</form >';
            }
            echo'</div>';
    
        echo'</div>';
        $lastimetag[$entry['machineevent_entry']]=$entry['machineevent_timetag'];
    }
    
    
    echo'</div>';
    if(empty($_POST['MAC'])){
    refresh_div('here',2000);
    }
}
function show_manage_machine($db){
    $info=get_machine_info($db,$_POST['machine_name']);
    echo'<div  class="col-lg-4 col-md-6 col-xs-12 machine_details ">';
        echo'<div class="row ">';
            echo'<div class="col-xs-6" >Machine Name</div>';
            echo'<div class="col-xs-6" >'.$info['machine_name'].'</div>';
        echo'</div>';
        echo'<div class="row ">';
            echo'<div class="col-xs-6" >Machine Number</div>';
            echo'<div class="col-xs-6" >'.$info['machine_number'].'</div>';
        echo'</div>';
        echo'<div class="row ">';
            echo'<div class="col-xs-6" >Workarea</div>';
            echo'<div class="col-xs-6" >'.$info['machine_workarea'].'</div>';
        echo'</div>';
        echo'<div class="row ">';
            echo'<div class="col-xs-6" >MAC Address</div>';
            echo'<div class="col-xs-6" >'.$info['machineallocation_MAC'].'</div>';
        echo'</div>';
    echo'</div>';
    echo'<div  class="col-lg-6 col-md-6 col-xs-12 all_sensor_details ">';
        echo'<div class="row ">';
            
            foreach($info['sensors'] as $sensor){
                echo'<div  class="col-lg-4 col-md-6 col-xs-12 sensor_details ">';
                echo'<div class="row ">'.$sensor['machinepin_pindescription'].'</div>';
                showline_machine('Pin',$sensor['machinepin_pinnumber']);
                //if(empty($sensor['machinepin_triggerpull'])){$showline='Raising';}else{$showline='I/O';}
                //showline_machine('Type',$showline);
                showline_machine('Count',number_format($sensor['thecount']));
                echo'</div>';
            }
            
        echo'</div>';
    echo'</div>';
}
function show_allocation_machine($db){
    $macinfo=get_MAC_info($db,$_POST['MAC']);
    echo'<form method="POST">';
    echo'<div class="row machine_header">Machine Allocation</div>';
    echo'<div class="row machine_row">';
        echo'<div class="col-sm-6">Start Date</div>';
        echo'<div class="col-sm-6"><input type="time" name="time_allocation_start" value="'.date('G:i:s',$macinfo['firstscan']).'" step="1" class="form-control"  ></div>';
    echo'</div>';
    echo'<div class="row machine_row">';
        echo'<div class="col-sm-6">Start Time</div>';
        echo'<div class="col-sm-6"><input type="date" name="date_allocation_start" value="'.date('Y-m-d',$macinfo['firstscan']).'" step="1" class="form-control"  ></div>';
    echo'</div>';
    echo'<div class="row machine_row">';
        echo'<div class="col-sm-6">Machine</div>';
        echo'<div class="col-sm-6">';
        echo'<select name="machine_to_allocated" class="form-control">';
            $machines=get_all_machine($db);
            foreach($machines as $machine){
                echo'<option value="'.$machine['machine_id'].'">'.$machine['machine_name'].'</option>';
            }
           
        echo'</select>';
        echo'</div>';
    echo'</div>';
    echo'<div class="row machine_row">';
        echo'<div class="col-sm-6">End Date</div>';
        echo'<div class="col-sm-6"><input type="time" name="time_allocation_end" value="'.date('G:i:s',1999999999).'" step="1" class="form-control"  ></div>';
    echo'</div>';
    echo'<div class="row machine_row">';
        echo'<div class="col-sm-6">End Time</div>';
        echo'<div class="col-sm-6"><input type="date" name="date_allocation_end" value="'.date('Y-m-d',1999999999).'" step="1"  class="form-control"  ></div>';
    echo'</div>';
    echo'<div class="row machine_row">';
        echo'<input type="submit"  value="Save"   class="btn btn-primary injury_button"  >';
    echo'</div>';
    echo'<input type="hidden"  name="view" value="View All Device"   class="btn btn-primary injury_button"  >';
    echo'<input type="hidden"  name="MAC_to_allocated" value="'.$_POST['MAC'].'"   class="btn btn-primary injury_button"  >';
    
    echo'</form>';//View All Device
}
function show_all_stats($db){
    $col[1]='col-sm-3';
    $col[2]='col-sm-3';
    $col[3]='col-sm-1';
    $col[4]='col-sm-3';
    $col[5]='col-sm-1';
    $col[6]='col-sm-1';

    //import_temptable($db);
    echo'<div class="row machine_header">';
        echo'<div class="'.$col[1].'">Date</div>';
        echo'<div class="'.$col[2].'">Entry</div>';
        echo'<div class="'.$col[3].'">Time On</div>';
        echo'<div class="'.$col[5].'">Time Off</div>';
        echo'<div class="'.$col[4].'">Cycle Time</div>';
    echo'</div>';
    echo'<div class="all_line_container">';
    if(!empty($_POST['machine_name'])){
        foreach(get_all_event($db) as $entry){
            showlineevent($entry,$col);
        }
    }
    
    echo'</div>';
    //refresh_div('here',500);
}
function showlineevent($entry,$col){

    if(!empty($_POST['lastimetag'][$entry['machineevent_entry']])){
        $cycletime= ($_POST['lastimetag'][$entry['machineevent_entry']]-$entry['machineevent_timetag']);
    }else{
        $cycletime= (strtotime($_POST['date_filter_end'].' '.$_POST['time_filter_end'].'')-$entry['machineevent_timetag']);
    }
    echo'<div class="row machine_row">';
        echo'<div class="'.$col[1].'">';
        echo date('Y-m-d',$entry['machineevent_timetag']);
        echo '<br>'.date('G:i:s.u',$entry['machineevent_timetag']);
        echo'<br></div>';
        echo'<div class="'.$col[2].'">';
        echo $entry['machineevent_entry'].' - '.$entry['machineevent_hour'];
        echo'</div>';
        echo'<div class="'.$col[3].'">';
        echo show_time($entry['duration']);
        echo'</div>';
        echo'<div class="'.$col[5].'">';
        if(!empty($entry['duration'])){
         echo show_time($cycletime-$entry['duration']);
        }
        echo'</div>';
        echo'<div class="'.$col[4].'">';
        echo show_time($entry['CycleTime']);
        
        echo'</div>';
        

    echo'</div>';
    $_POST['lastimetag'][$entry['machineevent_entry']]=$entry['machineevent_timetag'];
    
}
function show_details_stats($db){
    echo'<div class="row navbar navbar_injury"><br>';
    echo'</div>';
    if($_POST['date_filter']=='Custom'){
        echo'<br><br>';
    }
    $stats=get_all_stats($db);
    echo'<div class="row machine_header">All Stats</div>';
    showline_machine('Count',$stats['thecount'],'machine_row');
    if(!empty($_POST['entry_type']) and is_there_duration($db,$_POST['entry_type'],$_POST['machine_name'])=='duration'){
        ;
        showline_machine('Average Duration',show_time($stats['average_duration']),'machine_row');
        showline_machine('Median Duration',show_time($stats['median_duration']),'machine_row');
    }
    showline_machine('Total Time',show_time(round(($stats['max_timetag']-$stats['min_timetag']),0)),'machine_row');
    showline_machine('Average Cycle Time',show_time(round(($stats['ActualTime'])/($stats['thecount']-1),0)),'machine_row');
    showline_machine('Median Cycle Time',show_time($stats['median_cycletime']),'machine_row');
    echo'<div class="row machine_row">';
        echo'<div class=" col-sm-6">';
        $efficiency=round($stats['median_cycletime']/($stats['ActualTime']/($stats['thecount']-1))*100);
        show_gauge_efficiency('Efficiency',$efficiency,'');
        echo'</div>';
        echo'<div class=" col-sm-6">';
        $partperhour=(round($stats['thecount']/($stats['ActualTime'])*3600,0));
        show_gauge_efficiency('Cycle/hours',$partperhour,'max:200,');
        echo'</div>';
    echo'</div>';
    
    showline_machine('Part/hours',$partperhour,'machine_row');
    showline_machine('Trend',show_hours_count(get_hours_trend($db,$_POST['machine_name'])),'machine_row');
    
    //show_distribution_chart($stats['All Cycle'],0,10,11);
    //show($stats['All Cycle']);
    refresh_div('stats',50000);

}
function showline_machine($caption,$value,$class=''){
    echo'<div class="row '.$class.'">';
        echo'<div class=" col-sm-6">'.$caption.'</div>';
        echo'<div class=" col-sm-6">'.$value.'</div>';
    echo'</div>';
}

function show_hours_count($stats,$limit=999){
    
   
    $max=0;
    $return="";
    $i=0;
    $olddate="";
    $allhours=$stats['all_hours'];
    foreach($allhours as $hours){
        
        $max=max($max,$hours['thecount']);
    }
    $limit=min($stats['MaxHours'],$limit);
    //show($stats['MaxHours']);
    //show($hours['thecount']);
    //show($hours['thecount']>0.8*$max);
    $return=$return. '<div class="day_block">';

    //foreach($allhours as $hours){
    for($count=0;$count<$limit ;$count++){
        //if($i<$limit){
            if(($i<>0 and $olddate<>$allhours[$count]['thedate'] and !empty($olddate)and !empty($allhours[$count]['thedate']))or $i>24){
                $return=$return. '</div>';
                $return=$return. '<div class="day_block">';
                $i=0;
            }
            $return=$return. '<div class="hours_block';
            if($allhours[$count]['thecount']>(0.75*$max)){
                $return=$return. ' color_1';
            }elseif($allhours[$count]['thecount']>(0.5*$max)){
                $return=$return. ' color_2';
            }else{
                $return=$return. ' color_3';
            }
            $return=$return.'">';
        // echo $hours['theHours'];
        if(empty($allhours[$count]['thecount'])){$return=$return. '0';}
        $return=$return. $allhours[$count]['thecount'];
        $return=$return. '</div>';
        //}
        if(!empty($allhours[$count]['thedate'])){
            $olddate=$allhours[$count]['thedate'];
        }
       
       $i++;
    }
    $return=$return. '</div>';
    return $return;
}

function show_part_per_hour($allhours,$limit){

    $max=0;
    $return="";
    $i=0;
    $count=0;
    $hours_counted=0;
    foreach($allhours as $hours){
        
        $max=max($max,$hours['thecount']);
    }
    //show($hours['thecount']);
    //show($hours['thecount']>0.8*$max);
    //$return=$return. '<div class="day_block">';

    foreach($allhours as $hours){
        if($i<$limit){
            
        // echo $hours['theHours'];
        $count=$count+$hours['thecount'];
        $hours_counted++;
        }
      
       $i++;
    }
    return round(($count/$hours_counted),2); 
    
}


function show_windows_details_machine($db){
    ?><script>
        function close_details_machine(){
            var current_selection="";  
                var current = document.getElementsByClassName("button_manage_machine_selected");
                if (current.length > 0) { 
                    current_selection=String(current[0].id);
                    
                }
                if(current_selection!="move_machine"){
                    var request =$.ajax({
                            type:'POST',
                            url:'machine_ajax.php',
                            data: {},
                            success:function(html){
                                $('.manage_machine').empty().append(html);
                            }
                        });
                }
        }
    </script>
    <?php
    if(!empty($_SESSION['temp']['factory_admin_view'])){
            
        manage_machine_default_menu($db);
        if(!empty($_POST['machine_id'])){
        show_details_manage_machine($db,$_POST['machine_id']);
        show_allocate_baseproduct_machine($db,$_POST['machine_id']);
        }
        
    }elseif(!empty($_SESSION['temp']['factory_user_view'])){
            
        manage_allocation_operator($db);
        
        
    }else{
        if(!empty($_POST['machine_id'])){
            show_details_machine($db,$_POST['machine_id']);
            if(!empty($_SESSION['temp']['role_factory_admin'])){
                show_allocate_product_machine($db,$_POST['machine_id']);
            }
        }
    }
    ?>
    <br><div class="row button_manage_machine" onclick="close_details_machine();">
        Close
    </div>
    <?php
}

function manage_machine_default_menu($db){
    ?>
    <script>
        function add_machine(elmnt) {
            var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
            
            var current_selection="";  
                var current = document.getElementsByClassName("button_manage_machine_selected");
                if (current.length > 0) { 
                    current_selection=String(current[0].id);
                    
                }
            if(current_selection=="create_machine"){
                
                pos3 = elmnt.clientX;
                pos4 = elmnt.clientY;
                //x_percent=Math.round((Math.max(0,Math.min((document.getElementById('factory'+theworkarea).clientWidth),(elmnt.offsetLeft - pos1)))*100/(document.getElementById('factory'+theworkarea).clientWidth-document.getElementById(elmnt.id).clientWidth))*10,0)/10;
                //y_percent=Math.round((Math.max(0,Math.min((document.getElementById('factory'+theworkarea).clientHeight-document.getElementById(elmnt.id).clientHeight),(elmnt.offsetTop - pos2)))*100/(document.getElementById('factory'+theworkarea).clientHeight-document.getElementById(elmnt.id).clientHeight))*10,0)/10;
                max_width=document.getElementById('factory'+theworkarea).clientWidth;
                max_height=document.getElementById('factory'+theworkarea).clientHeight;
                var offsets = document.getElementById('factory'+theworkarea).getBoundingClientRect();
                var top = offsets.top;
                var left = offsets.left;
                x_percent=Math.round((elmnt.clientX-left)*1000/max_width)/10;
                y_percent=Math.round((elmnt.clientY-top)*1000/max_height)/10;
                document.getElementById('machine_location_x').value=x_percent;
                document.getElementById('machine_location_y').value=y_percent;
                document.getElementById('machine_workarea').value=theworkarea;
                document.getElementById('create_machine_form').submit();
            }
        }

        document.getElementById("factory"+theworkarea).addEventListener("click", add_machine);

        function activate_button(element){
            
            
            if(element.classList.contains("button_manage_machine_selected")==true){
               element.classList.remove("button_manage_machine_selected");
            }else{
                var current = document.getElementsByClassName("button_manage_machine_selected");
                if (current.length > 0) { 
                    current[0].className = current[0].className.replace("button_manage_machine_selected", "");
                    
                }
                //element.className += " button_manage_machine_selected";
                element.classList.add("button_manage_machine_selected");
            }
            
            
            
            //alert(btns[i].id);
                
                
                
            
            //
           
        }
        function refresh_constant(){
            
            var current_selection="";  
            var current = document.getElementsByClassName("button_manage_machine_selected");
            if (current.length > 0) { 
                current_selection=String(current[0].id);
                
            }
            
            if(current_selection=='move_machine'){
                var current = document.getElementsByClassName("machineheader");
                len = current !== null ? current.length : 0,
                i = 0;
                for(i; i < len; i++) {
                    current[i].className += " cursor_move"; 
                }
            }else{
                var current = document.getElementsByClassName("cursor_move");
                len = current !== null ? current.length : 0,
                i = 0;
                for(i; i < len; i++) {
                    current[0].classList.remove("cursor_move");
                }
                
            }
           
            
            //alert(current_selection);  
        }
    </script>
    <div class="row">
        Admin
    </div>
    <div class="row">
        <div class="col-xs-3 button_manage_machine" id="move_machine" onclick="activate_button(this);refresh_constant();">
            <span class="glyphicon glyphicon-move"></span>
        </div>
        <div class="col-xs-3 button_manage_machine" id="create_machine" onclick="activate_button(this);refresh_constant();">
            <span class="glyphicon glyphicon-plus"></span>
        </div>
        
        <!--<div class="col-xs-3 button_manage_machine" id="delete_machine" onclick="activate_button(this);refresh_constant();">
            <span class="glyphicon glyphicon-trash"></span>
        </div>-->
    </div>
    <br>
    <form id="create_machine_form" method="POST">
        <input type="hidden" id="machine_location_x" name="machine_location_x" value="0">
        <input type="hidden" id="machine_location_y" name="machine_location_y" value="0">
        <input type="hidden" id="machine_workarea" name="machine_workarea" value="test">
        <input type="hidden" name="action" value="create_machine">
    </form>
    
    <?php
}
function manage_allocation_operator($db){
    $_POST['workarea']=$_SESSION['factory']['workarea'];
    $_POST['type']=$_SESSION['factory']['type'];
    
    $type='Allocation';
            
    //show("$area:  $style");
    echo'<div id="'.$type.'-'.$_POST['workarea'].'"  >';
    //show('test');
    //show($_POST);
    show_list_operator_available($db,$_POST['date_filter'],$_POST['workarea']);
    echo'</div>';
        
    
}
function show_list_operator_available($db,$date,$workarea){
    $alldata=get_data_allocation($db,$date,$workarea);
    create_css($db);
    //show($alldata);
    unset($alldata['Total']);
    foreach($alldata as $workarea){
        
       
        ?>
         
           
            <?php 
            unset ($workarea['name']);unset ($workarea['number']);
            foreach($workarea as $shift){
                array_multisort($shift,SORT_ASC);
                
                ?>
                    <div  class="row  day-roster  <?php echo $shift['shift'];?>"><?php echo $shift['shift'].' - '.$shift['number'];?>
                <?php
                unset ($shift['shift']);unset ($shift['number']);
                foreach($shift as $operator){
                    //show($operator);
                    if(!empty($operator['name'])){
                    ?>
                    <div  class="row">
                        <div  class="col-xs-10">
                        <?php echo $operator['name'];?>
                        </div>
                        <!--<div  class="col-xs-2">
                            <?php if(empty($operator['allocationjobdone_done'])){?>
                                <span class="glyphicon glyphicon-unchecked" onclick="tick_name('<?php echo date('Y-m-d',strtotime($_POST['date_to_show'])).'\',\''.$operator['name'];?>');"></span>
                            <?php }else{?>
                                <span class="glyphicon glyphicon-check" onclick="tick_name('<?php echo date('Y-m-d',strtotime($_POST['date_to_show'])).'\',\''.$operator['name'];?>');"></span>
                            <?php }?>
                            
                        </div>-->
                    </div>
                    <?php
                    }
                }
                ?>
            
            <?php
            }
            ?>
        </div>
        <?php
    }
}
function show_details_manage_machine($db,$machine_id){
    $info=get_machine_info_from_id($db,$machine_id)?>
    <form method="POST" id="form_to_save">
    <input type="hidden" id="machine_id" name="machine_id" value="<?php echo $info['machine_id'];?>" >
    <input type="hidden" name="action" value="save_changes" >
       
   
    <div class="row button_manage_machine">
        <div class="col-xs-4">
            Name: 
       </div>
       <div class="col-xs-8">
        <input class="form-control" id="machine_name" name="machine_name" value="<?php echo $info['machine_name'];?>" placeholder="Machine Name" oninput="changes_done();">
       </div>
    </div>
    <div class="row button_manage_machine">
        <div class="col-xs-4">
            WorkArea: 
       </div>
       <div class="col-xs-8">
        <select class="form-control" id="machine_workarea" name="machine_workarea" oninput="changes_done();">
                <option <?php if($info['machine_workarea']=='Machining'){echo'selected';};?> >Machining</option>
                <option <?php if($info['machine_workarea']=='Moulding'){echo'selected';};?> >Moulding</option>
                <option <?php if($info['machine_workarea']=='Assembly'){echo'selected';};?> >Assembly</option>
            </select>
       </div>
    </div>
    <div class="row button_manage_machine">
        <div class="col-xs-4">
            Machine #: 
       </div>
       <div class="col-xs-8">
        <input class="form-control" id="machine_number" name="machine_number" value="<?php echo $info['machine_number'];?>" placeholder="Machine Number" oninput="changes_done();">
       </div>
    </div>
    <div class="row button_manage_machine">
       
        <div class="col-xs-4">
            MAC: 
       </div>
       <div class="col-xs-8">
        <select class="form-control" id="machineallocation_MAC" name="machineallocation_MAC" oninput="changes_done();">
            
            <?php $allMAC=get_list_MAC($db,"WHERE machine_name is null or machine_name='".$info['machine_name']."'");
            if(!empty($info['machineallocation_MAC'])){
                ?><option>Remove</option><?php
            }else{
                ?><option></option><?php
            }
            foreach($allMAC as $MAC){?>
                    <option <?php if($info['machineallocation_MAC']==$MAC['temptable_MAC']){echo'selected';};?> ><?php echo $MAC['temptable_MAC']; ?></option>
            <?php } ?>
            
        </select>
       </div>
    </div>
    <?php if(!empty($info['machineallocation_MAC'])){?>
    <div class="row">
        Sensors: <?php echo count($info['sensors']);?>
        <?php //echo show($info['sensors']);?>
    </div>
    <div class="row">
        <?php for ($x = 2; $x <= 8; $x++) {?>
            <div class="row">
                <div class="col-xs-4">Pin 
                    <?php echo $x;?> 
                </div>
                <div class="col-xs-6">
                    
                    <input oninput="changes_done();" class="form-control" type="text" id="machinepin_pindescription" name="machinepin_pindescription[]" value="<?php echo $info['sensors'][$x]['machinepin_pindescription'];?>" >
                </div>
                <div class="col-xs-2">
                    <?php if($info['machine_cycle_pinumber']==$info['sensors'][$x]['machinepin_pinnumber']){$checked='checked';}else{$checked='';}?> 
                    <input oninput="changes_done();" oncontextmenu="$('.check_cycle_pin').prop('checked', false);return false;" class="form-control check_cycle_pin" type="radio" id="checkbox<?php echo $x;?>" name="machine_cycle_pinumber[]" value="<?php echo $x;?>" <?php echo $checked?>>
                </div>
            </div>
            <?php } ?>
    </div>
    </form>
    
    <?php }?>
    <div class="row">
        <div class="col-xs-12">
        <button class="btn btn-primary" id="save_button" onclick="document.getElementById ('form_to_save').submit()" style="display:none;">Save</button>
        </div>
    </div>
    </form>
    <form method="POST" id="form_delete_machine">
    <input type="hidden" id="machine_id" name="machine_id" value="<?php echo $info['machine_id'];?>" >
    <input type="hidden" name="action" value="delete_machine" >
    <div class="row">
        <div class="col-xs-12">
        <button class="btn btn-primary glyphicon glyphicon-trash"   onclick="return confirm('Are you sure to delete <?php echo $info['machine_name'];?>?');return confirm('Are you really sure?');document.getElementById ('form_delete_machine').submit()" ></button>
        </div>
    </div>
    </form>
    <script>
        function changes_done(){
            document.getElementById ("save_button").style.display = "";
        }
        function save_changes(){
            machine_workarea=document.getElementById ("machine_workarea").value;
            machineallocation_MAC=document.getElementById ("machineallocation_MAC").value;
            machine_number=document.getElementById ("machine_number").value;
            machine_id=document.getElementById ("machine_id").value;

            var request =$.ajax({
            type:'POST',
            url:'machine_ajax.php',
            data: {
                machine_workarea:machine_workarea,
                machineallocation_MAC:machineallocation_MAC,
                machine_number:machine_number,
                machine_id:machine_id,
                action:'save_changes'
                },
            success:function(html){
                $('.manage_machine').empty().append(html);
            }
        });
        }
    </script>
    <br>
    <?php
}
function show_allocate_baseproduct_machine($db,$machine_id){
    $info=get_machine_info_from_id($db,$machine_id);
    //show($info);?>
    <br>
    <div class="row">Products made in that machine</div>
    
    <form method="POST" id="form_to_save_02">
    <input type="hidden" id="machine_id" name="machine_id" value="<?php echo $info['machine_id'];?>" >
    <input type="hidden" name="action" value="save_machinebaseproduct" >
    
    <div class="row">
        <div class="col-xs-9">
        <input type="input" class="form-control" list="product_list" name="machinebaseproduct_productcode" >
        <datalist id="product_list">
            <?php
            foreach(get_all_product_factory($db) as $product){
                echo'<option value="'.$product['Product_Code'].'">';
            }
            ?>
        </datalist>
        </div>
        <div class="col-xs-3">
        <input type="submit" class="form-control" >
        </div>
    </div>
    </form>
    
    <?php
    if(!empty($info['machine_baseproduct'])){?>
        
        <?php 
        foreach($info['machine_baseproduct'] as $baseproduct){?>
            <div class="row baseproduct">
                <div class="col-xs-10"><?php echo $baseproduct['machinebaseproduct_productcode'];?></div>
                <div class="col-xs-1">
                <form method="POST" id="form_to_save_02">
                <input type="hidden" id="machine_id" name="machine_id" value="<?php echo $info['machine_id'];?>" >
                <input type="hidden" name="action" value="remove_machinebaseproduct" >
                <input type="hidden" name="machinebaseproduct_productcode" value="<?php echo $baseproduct['machinebaseproduct_productcode'];?>" >
                <input type="submit" class="" value="X ">
                </form>
            </div>
        </div>
        <?php 
        }
        ?>        
        
    <?php
    }
    ?>  
       
    <?php
    
    ?>

    
    
    
    
    <script>
        function changes_done(){
            document.getElementById ("save_button").style.display = "";
        }
        function save_changes(){
            machine_workarea=document.getElementById ("machine_workarea").value;
            machineallocation_MAC=document.getElementById ("machineallocation_MAC").value;
            machine_number=document.getElementById ("machine_number").value;
            machine_id=document.getElementById ("machine_id").value;

            var request =$.ajax({
            type:'POST',
            url:'machine_ajax.php',
            data: {
                machine_workarea:machine_workarea,
                machineallocation_MAC:machineallocation_MAC,
                machine_number:machine_number,
                machine_id:machine_id,
                action:'save_changes'
                },
            success:function(html){
                $('.manage_machine').empty().append(html);
            }
        });
        }
    </script>
    <br>
    <?php
}
function show_allocate_product_machine($db,$machine_id){
    $info=get_machine_info_from_id($db,$machine_id);
    //show($info);?>
    <br>
    <div class="row">Current Process</div>
    <?php
    if(!empty($info['machineproduct_productcode'])){?>
        <form method="POST" id="form_to_save_02">
        <input type="hidden" id="machine_id" name="machine_id" value="<?php echo $info['machine_id'];?>" >
        <input type="hidden" name="action" value="remove_machineproduct" >
        <div class="row">
            <div class="col-xs-10"><?php echo $info['machineproduct_productcode'];?></div>
            <div class="col-xs-1">
            <input type="submit" class="btn" value="X ">
            </div>
        </div>
        </form>
    <?php
    }else{
    ?>  
        <form method="POST" id="form_to_save_02">
        <input type="hidden" id="machine_id" name="machine_id" value="<?php echo $info['machine_id'];?>" >
        <input type="hidden" name="action" value="save_machineproduct" >
        <div class="row">
            <?php 
            
            if($info['machine_workarea']=='Moulding'){
                $allproduct=get_all_product_fromasset($db,$info['machine_id']);
            }else{
                $allproduct=get_all_product_frombaseproduct($db,$info['machine_id']);
            }
            
            if(!empty($allproduct)){
            ?>    
            <div class="col-xs-9">
           
            <select class="form-control" list="product_list" name="machineproduct_productcode" >
                
                <?php
                foreach( $allproduct as $product){
                    echo'<option >'.$product['product_code'].'</option>';
                }
                ?>
            </select>
            
            </div>
            <div class="col-xs-3">
            <input type="submit" class="form-control" >
            </div>
            <?php 
            }
            ?>
        </div>
        </form>
    <?php
    }
    ?>

    
    
    
    <div class="row">
        <div class="col-xs-12">
        <button class="btn btn-primary" id="save_button" onclick="document.getElementById ('form_to_save').submit()" style="display:none;">Save</button>
        </div>
    </div>
    <script>
        function changes_done(){
            document.getElementById ("save_button").style.display = "";
        }
        function save_changes(){
            machine_workarea=document.getElementById ("machine_workarea").value;
            machineallocation_MAC=document.getElementById ("machineallocation_MAC").value;
            machine_number=document.getElementById ("machine_number").value;
            machine_id=document.getElementById ("machine_id").value;

            var request =$.ajax({
            type:'POST',
            url:'machine_ajax.php',
            data: {
                machine_workarea:machine_workarea,
                machineallocation_MAC:machineallocation_MAC,
                machine_number:machine_number,
                machine_id:machine_id,
                action:'save_changes'
                },
            success:function(html){
                $('.manage_machine').empty().append(html);
            }
        });
        }
    </script>
    <br>
    <?php
}
function show_details_machine($db,$machine_id){
    
    $info=get_machine_info_from_id($db,$machine_id);
    $allinfo=get_info_machine($db,$machine_id);
    $lasttemptable=get_last_temptable_machine($db,"WHERE temptable_MAC='".get_MAC($db,$machine_id)."'");
    //show($allinfo);?>
    <div class="row">
    <?php echo $info['machine_name'];?>
    </div>
    <?php if(empty($info['machineallocation_MAC'])){?>
        <!--<div class="row button_manage_machine" >
            No Device Installed yet
        </div>-->
    <?php }else{?>
        <div class="row button_manage_machine" >
            Count Cycle : <?php echo count($allinfo);?>
        </div>
        <div class="row button_manage_machine" >
            Operating Time : <?php echo show_time($info['summary']['hours_cycle']*3600);?>
        </div>
        <div class="row button_manage_machine" >
            Last Cycle: <?php echo show_time(time()-$allinfo['0']['machinecycle_timetag_finish']);?>
        </div>
        <!--<div class="row button_manage_machine" >
            Last Scan: <?php echo show_time(time()-$lasttemptable['lasttimetag']);?>
        </div>-->
        <div class="row button_manage_machine">
           Cadency : <?php echo number_format($info['summary']['count_cycle']/$info['summary']['hours_cycle']).' cyc/h';?>
        </div>
        <div class="row button_manage_machine">
           Cycle Time : <?php echo show_time($info['summary']['average_cycle']);?>
        </div>
        
    <?php }?>
   
    <?php
}

function create_css ($db){
    $basetable='baseallocationwork';
    $query="SELECT  *
        FROM [barcode].[dbo].[baseallocationwork]
        order by  baseallocationwork_id asc
        ";
    $sql = $db->prepare($query); 
    $sql->execute();
    $rowtemp=$sql->fetchall();
    
    echo'<style>';
    foreach($rowtemp as $category){
        echo'
        .'.$category[$basetable.'_code'].'{
            background:#'.$category[$basetable.'_color'].';
        }
        ';
    }
 
    echo'</style>';
}
function get_data_allocation($db,$date,$workarea='Assembly'){
    
  
    
    $filter=$filter." AND allocationwork_code='$workarea'";



    $query="SET DATEFIRST 1;
    SELECT * FROM dbo.allocationwork
    left join allocation on  allocation_date=allocationwork_date and allocation_operatorid=allocationwork_operatorid
    left join allocationshift on  allocationshift_date=allocationwork_date and allocationshift_operatorid=allocationwork_operatorid
    left join allocationcontract on  allocationcontract_date=allocationwork_date and allocationcontract_operatorid=allocationwork_operatorid
    left join allocationjobdone on  allocationjobdone_date=allocationwork_date and allocationjobdone_operatorid=allocationwork_operatorid
    left join operator on operator_fullname=allocationwork_operatorid
    left join baseallocation on  baseallocation_code=allocation_code
    left join baseallocationcontract on  baseallocationcontract_code=allocationcontract_code 
    left join baseallocationwork on  baseallocationwork_code=allocationwork_code  
    WHERE allocationwork_date='".$date."' ".$filter." 
    and allocationshift_code <> 'Null' and operator_active=1
    AND ((DATEPART(DW,allocationcontract_date)<>6 and DATEPART(DW,allocationcontract_date)<>7 
        ) or((DATEPART(DW,allocationcontract_date)=6 or DATEPART(DW,allocationcontract_date)=7)and baseallocation_working<100 ))
    AND (baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100)>0
    
    ";
    
    //show($query);
    $sql = $db->prepare($query); 
    $sql->execute();
    //show(nbr_of_line);
    $rowtemp=$sql->fetchall();
    $row['Total']=array();
    foreach($rowtemp as $line){
        //$row[$line[$table.'_date']][$line[$table."_operatorid"]]=$line[$table."_code"];
        
        if($line["allocationwork_working"]<100){
            $row['Total'][$line["allocationwork_code"]]['number']++;
            $row['Total'][$line["allocationwork_code"]]['name']=$line["allocationwork_code"];
            $row[$line["allocationwork_code"]]['name']=$line["allocationwork_code"];
            $row[$line["allocationwork_code"]]['number']++;
            $row[$line["allocationwork_code"]][$line["allocationshift_code"]]['shift']=$line["allocationshift_code"];
            $row[$line["allocationwork_code"]][$line["allocationshift_code"]]['number']++;
            $row[$line["allocationwork_code"]][$line["allocationshift_code"]][$line["allocationwork_operatorid"]]['name']=$line["allocationwork_operatorid"];
            $row[$line["allocationwork_code"]][$line["allocationshift_code"]][$line["allocationwork_operatorid"]]['code']=$line["allocation_code"];
            $row[$line["allocationwork_code"]][$line["allocationshift_code"]][$line["allocationwork_operatorid"]]['allocationjobdone_done']=$line["allocationjobdone_done"];
        }
        
    }
    array_multisort($row['Total'],SORT_DESC);
    //array_multisort($row,SORT_DESC);
        
   
    // }
    
    
    
    
   
   return $row;
}
function get_info_factory($db,$workarea){
    // $filter='';
    
    // $filter=$filter." machineevent_date>='".$_POST['date_filter_start']."'";
    // $filter=$filter." AND machineevent_date<='".$_POST['date_filter_end']."'";
    $filter='machinecycle_date=\''.date('Y-m-d',strtotime($_POST['date_filter'])).'\'';
    $query='SELECT machine.machine_name,machine.machine_id,
    machine.machine_workarea,
    machine.machine_location_x,
    machine.machine_location_y,
    machine.machine_location_horizontal,
    machineproduct_productcode,
    a.Count,
    a.Hours,
    machineallocation_MAC,
    b.lasttimetag,
    machinecycle_date,
	count_scan,
    asset_cavity,
    ope.Count_Operator
    
        FROM machine
        left join machine_allocation on machineallocation_machineid=machine_id
        left join machine_product  on machineproduct_machineid=machine_id and machineproduct_date_start=\''.$_POST['date_filter'].'\' 
        
        left join assetproduct on assetproduct_productcode=machineproduct_productcode
	    left join asset on assetproduct_assetid=asset_id
        left join (SELECT Code,count(distinct (scan_operatorcode))as Count_Operator FROM dbo.scan  
	LEFT JOIN dbo.operator ON scan_operatorcode=operator_code
	LEFT JOIN MIS_List ON scan_jobnumber=ManufactureIssueNumber		
	WHERE 
	scan_statut=\'start\'
	
	AND scan_date=\''.date('Y-m-d',strtotime($_POST['date_filter'])).'\'
	group by Code)as ope on ope.Code=machineproduct_productcode


        left join (
	SELECT machine_name,machinecycle_date,sum(Count) as Count, Sum(Hours)as Hours from
	Machine_summary
	where '.$filter.' 
    group by machine_name,machinecycle_date
	)as a on machine.machine_name=a.machine_name
    left join (
        SELECT machinecycle_machineid,max(machinecycle_timetag_finish)as lasttimetag from
        machine_cycle
        where '.$filter.' and machinecycle_timetag_finish is not null
        group by machinecycle_machineid,machinecycle_date
        )as b on b.machinecycle_machineid=machine_id
    left join (
        SELECT count(temptable_id)as count_scan, temptable_MAC from
        temptable_v2
        where temptable_timetag>=('.time().'-3600*24) 
        group by temptable_MAC
        )as c on c.temptable_MAC=machineallocation_MAC
    WHERE ( machine.machine_location_x is not null) and machine.machine_workarea=\''.$workarea.'\' ' ;//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}
function get_info_factory_summary($db){
    $filter='machinecycle_date=\''.date('Y-m-d',strtotime($_POST['date_filter'])).'\'';
    $query='SELECT machine.machine_name,machine.machine_id,
    machine.machine_workarea,
    machine.machine_location_x,
    machine.machine_location_y,
    machine.machine_location_horizontal,
    a.Count,
    a.Hours,
    machineallocation_MAC,
    b.lasttimetag,
    machinecycle_date,
	count_scan,machinecycle_hour,
	asset_cavity
    
        FROM machine
        left join machine_allocation on machineallocation_machineid=machine_id
        left join machine_product  on machineproduct_machineid=machine_id and machineproduct_date_start=\''.$_POST['date_filter'].'\' 
        left join assetproduct on assetproduct_productcode=machineproduct_productcode
	    left join asset on assetproduct_assetid=asset_id
        left join (
	SELECT machine_name,machinecycle_hour,machinecycle_date,sum(Count) as Count, Sum(Hours)as Hours from
	Machine_summary_per_hour
	where '.$filter.' 
    group by machine_name,machinecycle_date,machinecycle_hour
	)as a on machine.machine_name=a.machine_name
    left join (
        SELECT machinecycle_machineid,max(machinecycle_timetag_finish)as lasttimetag from
        machine_cycle
        where '.$filter.' and machinecycle_timetag_finish is not null
        group by machinecycle_machineid,machinecycle_date
        )as b on b.machinecycle_machineid=machine_id
    left join (
        SELECT count(temptable_id)as count_scan, temptable_MAC from
        temptable_v2
        where temptable_timetag>=('.time().'-3600*24) 
        group by temptable_MAC
        )as c on c.temptable_MAC=machineallocation_MAC
    WHERE 1=1';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $rows=$sql->fetchall();
  foreach($rows as $row){
      $return[$row['machine_name']]['Cycles'][$row['machinecycle_hour']]['Hours']=$row['Hours'];
      $return[$row['machine_name']]['Cycles'][$row['machinecycle_hour']]['Count']=$row['Count'];
      $return[$row['machine_name']]['Cycles'][$row['machinecycle_hour']]['count_scan']=$row['count_scan'];
      
      $return[$row['machine_name']]['Cycles'][$row['machinecycle_hour']]['Count_Parts']=$row['Count']*max($row['asset_cavity'],1);
      $return[$row['machine_name']]['Total']=$row['Count']+$return[$row['machine_name']]['Total'];
  }
  //show($query);
  //show($return);
  return $return;
}
function get_info_machine($db,$machine_id){
    // $filter='';
    
    // $filter=$filter." machineevent_date>='".$_POST['date_filter_start']."'";
    // $filter=$filter." AND machineevent_date<='".$_POST['date_filter_end']."'";
    $filter='machinecycle_date=\''.date('Y-m-d',time()).'\'';
    $query='SELECT*
    FROM machine_cycle
	left join machine on machine_id= machinecycle_machineid
       
    where '.$filter.' and machine.machine_id=\''.$machine_id.'\'
	order by machinecycle_timetag_start desc';
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}

function get_all_machine_summary($db){
    $filter='Where 1=1 ';
    $filter=$filter." ";
    
        $timetag_start=strtotime($_POST['date_filter_start'].' '.$_POST['time_filter_start'].'');
        $timetag_end=strtotime($_POST['date_filter_end'].' '.$_POST['time_filter_end'].'');
        //$filter=$filter." AND temptable_timetag>='".$timetag_start."'";
       // $filter=$filter." AND temptable_timetag<='".$timetag_end."'";
       $filter=$filter." and (temptable_timetag)>0 ";
       //show($_POST['machine_list']);
    if($_POST['machine_list']=='All Machine'){
        $filter=$filter.' or ( temptable_timetag is null)';
        //show('test');
    }

    
    
    $query='SELECT  max(temptable_timetag)as lastscan,count(temptable_timetag) as thecount ,machine_name,machine_workarea,numberofsensor,machineallocation_MAC,temptable_MAC
    FROM temptable_v2
   
    left join machine_allocation on  temptable_MAC=machineallocation_MAC
    left join machine on machine_id=machineallocation_machineid
    left join (SELECT [machinepin_machineid]
        
        ,count([machinepin_pindescription]) as numberofsensor
        
    FROM [barcode].[dbo].[machine_pin]
    group by machinepin_machineid) as a on a.machinepin_machineid=machine_id
    '.$filter.'
    group by machine_name,machine_workarea,numberofsensor,machineallocation_MAC,temptable_MAC
    order by machine_workarea asc,machine_name asc
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}

function get_all_machine_summary_cycle($db){
    $filter='Where 1=1 ';
    $filter=$filter." ";
    
        $timetag_start=strtotime($_POST['date_filter_start'].' '.$_POST['time_filter_start'].'');
        $timetag_end=strtotime($_POST['date_filter_end'].' '.$_POST['time_filter_end'].'');
       
        //$filter=$filter." and (temptable_timetag)>0 ";
        $filter=$filter." and machinecycle_date='".date('Y-m-d',time())."' ";
    if($_POST['machine_list']=='All Machine'){
        //$filter=$filter.' or ( temptable_timetag is null)';
        //show('test');
    }

    
    
    $query='SELECT  count([machinecycle_id])as count_cycle
    ,round(sum([machinecycle_duration])/3600.0,1) as hours_cycle
    ,avg([machinecycle_duration]) as average_cycle,
    machine_name,machine_workarea
    FROM [barcode].[dbo].[machine_cycle]
    left join machine on machine_id=machinecycle_machineid
    '.$filter.'
    group by machine_name,machine_workarea
    order by machine_workarea,machine_name;
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}

function get_all_machine($db){
    $query='SELECT Distinct machine_name,machine_id,machine_workarea,machine_group
    FROM machine
   
    left join machine_allocation on machine_id=machineallocation_machineid
   
    order by machine_workarea desc,machine_group,machine_name
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}

function get_all_device_summary($db){
    $filter='Where 1=1 ';
    $filter2='Where 1=1 ';
    //$filter=$filter." AND machineevent_type='Cycle Start'";
    
    $timetag_start=strtotime($_POST['date_filter_start'].' '.$_POST['time_filter_start'].'');
    $timetag_end=strtotime($_POST['date_filter_end'].' '.$_POST['time_filter_end'].'');
    $filter=$filter." AND temptable_timetag>='".$timetag_start."'";
    $filter=$filter." AND temptable_timetag<='".$timetag_end."'";
   

    if(!empty($_POST['MAC'])){
        $filter=$filter." AND temptable_MAC='".$_POST['MAC']."'";
    }
    $query='(SELECT  max(temptable_timetag)as lastscan,min(temptable_timetag)as firstscan,count(temptable_id) as thecount ,
    temptable_MAC,machine_name,machineallocation_timetag_start,machineallocation_MAC,coalesce(temptable_MAC, machineallocation_MAC)
   from temptable
   left join machine_allocation on (  temptable_MAC=machineallocation_MAC 
    )
   left join machine on machineallocation_machineid=machine_id

    '.$filter.'
     group by temptable_MAC,machine_name,machineallocation_timetag_start,machineallocation_MAC
     union
    SELECT  max(temptable_MAC)as lastscan,min(temptable_timetag)as firstscan,count(temptable_id) as thecount ,
    temptable_MAC,machine_name,machineallocation_timetag_start,machineallocation_MAC,coalesce(temptable_MAC, machineallocation_MAC)
    from machine_allocation
    left join temptable on  (  temptable_MAC=machineallocation_MAC 
     )
        
    left join machine on machineallocation_machineid=machine_id

    '.$filter2.'  AND temptable_timetag is null
        group by temptable_MAC,machine_name,machineallocation_timetag_start,machineallocation_MAC
    
    
    )order by coalesce(temptable_MAC, machineallocation_MAC),min(temptable_timetag) DESC
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}

function get_MAC_info($db,$MAC){
    $query='SELECT  max(temptable_timetag)as lastscan,min(temptable_timetag)as firstscan
   from temptable 
   WHERE temptable_MAC=\''.$MAC.'\'';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  return $row;
}
function get_machine_info_from_id($db,$machine_id){
    $query='SELECT  *
    from machine 
    left join machine_allocation on machine_id=machineallocation_machineid 
    left join machine_product  on machineproduct_machineid=machine_id and machineproduct_date_start=\''.$_POST['date_filter'].'\' 
    left join assetproduct on assetproduct_productcode=machineproduct_productcode
	left join asset on assetproduct_assetid=asset_id
	 
    WHERE machine_id=\''.$machine_id.'\'  ';//where machineevent_entry like \'%Door%\'
   $sql = $db->prepare($query); 
   //show($query);
   $sql->execute();
 
   $info=$sql->fetch();
   $query='SELECT  *
   from machine_pin 
   left join (
   SELECT  count( [temptable_timetag]) as thecount,machine_id,temptable_pin
     
  FROM [barcode].[dbo].[temptable_v2]
   left join machine_allocation on 
    (  temptable_MAC=machineallocation_MAC 
     )
	left join machine on machine_id=machineallocation_machineid
  WHERE machine_id='.$info['machine_id'].'
  group by machine_id,temptable_pin

   ) as a on a.temptable_pin=machinepin_pinnumber and a.machine_id=machinepin_machineid
   WHERE machinepin_machineid='.$info['machine_id'].'
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();
  $tempinfo['sensors']=$sql->fetchall();
    foreach($tempinfo['sensors'] as $sensor){
        $info['sensors'][$sensor['machinepin_pinnumber']]=$sensor;
    }

  $filter='machinecycle_date=\''.date('Y-m-d',time()).'\'';
  $query='SELECT  count([machinecycle_id])as count_cycle
    ,round(sum([machinecycle_duration])/3600.0,1) as hours_cycle
    ,avg([machinecycle_duration]) as average_cycle,
    machine_name,machine_workarea
    FROM [barcode].[dbo].[machine_cycle]
    left join machine on machine_id=machinecycle_machineid
    WHERE machine_id='.$info['machine_id'].' and '.$filter.'
    group by machine_name,machine_workarea
    order by machine_workarea,machine_name;';
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
  
    $info['summary']=$sql->fetch();

    $query='SELECT  *
    from machine_baseproduct 
    WHERE machinebaseproduct_machineid=\''.$machine_id.'\'  ';//where machineevent_entry like \'%Door%\'
   $sql = $db->prepare($query); 
   //show($query);
   $sql->execute();
 
   $info['machine_baseproduct']=$sql->fetchall();




   return $info;
}
function get_machine_info($db,$machine_name){
    $query='SELECT  *
    from machine 
    left join machine_allocation on machine_id=machineallocation_machineid 
    
    WHERE machine_name=\''.$machine_name.'\'';//where machineevent_entry like \'%Door%\'
   $sql = $db->prepare($query); 
   //show($query);
   $sql->execute();
 
   $info=$sql->fetch();
   $query='SELECT  *
   from machine_pin 
   left join (
   SELECT  count( [temptable_timetag]) as thecount,machine_id,temptable_pin
     
  FROM [barcode].[dbo].[temptable_v2]
   left join machine_allocation on 
    (  temptable_MAC=machineallocation_MAC 
     )
	left join machine on machine_id=machineallocation_machineid
  WHERE machine_id='.$info['machine_id'].'
  group by machine_id,temptable_pin

   ) as a on a.temptable_pin=machinepin_pinnumber and a.machine_id=machinepin_machineid
   WHERE machinepin_machineid='.$info['machine_id'].'
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $info['sensors']=$sql->fetchall();
   return $info;
}

function get_all_event($db){
    $filter='Where 1=1 ';
    if(!empty($_POST['entry_type'])){
        $filter=$filter." AND machineevent_type='".$_POST['entry_type']."'";
    }
    if(!empty($_POST['machine_name'])){
        $filter=$filter." AND machine_name='".$_POST['machine_name']."'";
    }
    if(!empty($_POST['machine_name'])){
        $filter=$filter." AND machine_name='".$_POST['machine_name']."'";
    }
    
    $timetag_start=strtotime($_POST['date_filter_start'].' '.$_POST['time_filter_start'].'');
    $timetag_end=strtotime($_POST['date_filter_end'].' '.$_POST['time_filter_end'].'');
    $filter=$filter." AND machineevent_timetag>='".$timetag_start."'";
    $filter=$filter." AND machineevent_timetag<='".$timetag_end."'";
    
    $query='SELECT TOP 120 *, (machineevent_timetag_finished-machineevent_timetag) as duration,
        min(machineevent_timetag) over(
            ORDER BY machineevent_timetag ASC  ROWS BETWEEN 1 following AND 1 following) - machineevent_timetag AS CycleTime
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
     )
    left join machine on machine_id=machineallocation_machineid

    '.$filter.'
    order by machineevent_timetag desc
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}

function get_all_stats($db){
    $filter='Where 1=1 ';
    if(!empty($_POST['entry_type'])){
        $filter=$filter." AND machineevent_type='".$_POST['entry_type']."'";
    }
    if(!empty($_POST['machine_name'])){
        $filter=$filter." AND machine_name='".$_POST['machine_name']."'";
    }
    if(!empty($_POST['machine_name'])){
        $filter=$filter." AND machine_name='".$_POST['machine_name']."'";
    }
    
    $timetag_start=strtotime($_POST['date_filter_start'].' '.$_POST['time_filter_start'].'');
    $timetag_end=strtotime($_POST['date_filter_end'].' '.$_POST['time_filter_end'].'');
    $filter=$filter." AND machineevent_timetag>='".$timetag_start."'";
    $filter=$filter." AND machineevent_timetag<='".$timetag_end."'";
    
    $query='SELECT  min(machineevent_timetag) as min_timetag,max(machineevent_timetag) as max_timetag,count(machineevent_timetag)as thecount, AVG(machineevent_timetag_finished-machineevent_timetag) as average_duration
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
     )
    left join machine on machine_id=machineallocation_machineid

    '.$filter.'
    
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  $return['thecount']=$row['thecount'];
  $return['average_duration']=$row['average_duration'];
  $return['min_timetag']=$row['min_timetag'];
  $return['max_timetag']=$row['max_timetag'];

  $query='SELECT
  (SELECT MAX(duration) FROM(SELECT  TOP 50 PERCENT (machineevent_timetag_finished-machineevent_timetag) as duration
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
     )
    left join machine on machine_id=machineallocation_machineid

    '.$filter.'
    ORDER BY duration ASC)as BottomHalf)
    +
    (SELECT MIN(duration) FROM(SELECT  TOP 50 PERCENT (machineevent_timetag_finished-machineevent_timetag) as duration
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
     )
    left join machine on machine_id=machineallocation_machineid

    '.$filter.'
    ORDER BY duration DESC)as TopHalf)/2 As Median
    
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  $return['median_duration']=$row['Median'];


  
  $query='SELECT
  ((SELECT MAX(Cycletime) FROM(SELECT  TOP 50 PERCENT min(machineevent_timetag) over(
                   ORDER BY machineevent_timetag ASC  ROWS BETWEEN 1 following AND 1 following) - machineevent_timetag AS CycleTime
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
     )
    left join machine on machine_id=machineallocation_machineid

    '.$filter.'
    ORDER BY Cycletime ASC)as BottomHalf)
    +
    (SELECT MIN(Cycletime) FROM(SELECT  TOP 50 PERCENT min(machineevent_timetag) over(
        ORDER BY machineevent_timetag ASC  ROWS BETWEEN 1 following AND 1 following) - machineevent_timetag AS CycleTime
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
     )
    left join machine on machine_id=machineallocation_machineid

    '.$filter.'
    ORDER BY Cycletime DESC)as TopHalf))/2 As Median
    
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  $return['median_cycletime']=$row['Median'];


    //$timetag_end-$timetag_start;
    $query='SELECT  floor(('.$timetag_end.'-machineevent_timetag)/3600) as theHours,min(machineevent_timetag) as min_timetag,max(machineevent_timetag) as max_timetag,count(machineevent_timetag)as thecount, AVG(machineevent_timetag_finished-machineevent_timetag) as average_duration
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
     )
    left join machine on machine_id=machineallocation_machineid

    '.$filter.'
    group by floor(('.$timetag_end.'-machineevent_timetag)/3600)
    order by floor(('.$timetag_end.'-machineevent_timetag)/3600) ASC


    ';//where machineevent_entry like \'%Door%\'
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $row=$sql->fetchall();
    foreach($row as $hours){
        $return['all_hours'][$hours['theHours']]['thecount']=$hours['thecount'];
        $return['all_hours'][$hours['theHours']]['theHours']=$hours['theHours'];
        $return['all_hours'][$hours['theHours']]['ActualTime']=$hours['max_timetag']-$hours['min_timetag'];
        $return['all_hours'][$hours['theHours']]['thedate']=date('Y-m-d',$hours['min_timetag']);
        $return['ActualTime']=$return['ActualTime']+$hours['max_timetag']-$hours['min_timetag'];
    }

    $query='SELECT
    min(machineevent_timetag) over(
                   ORDER BY machineevent_timetag ASC  ROWS BETWEEN 1 following AND 1 following) - machineevent_timetag AS CycleTime
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
     )
    left join machine on machine_id=machineallocation_machineid

    '.$filter.'
    ORDER BY Cycletime ASC
    
    
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();
  $row=$sql->fetchall();
  $return['All Cycle']=$row;


  return $return;
}

function get_hours_trend($db,$machine){
    $filter='Where 1=1 ';
    
    $filter=$filter." AND machineevent_type='Cycle Start'";
    
    $filter=$filter." AND machine_name='".$machine."'";
    
    $timetag_start=strtotime($_POST['date_filter_start'].' '.$_POST['time_filter_start'].'');
    $timetag_end=strtotime($_POST['date_filter_end'].' '.$_POST['time_filter_end'].'');
    $filter=$filter." AND machineevent_timetag>='".$timetag_start."'";
    $filter=$filter." AND machineevent_timetag<='".$timetag_end."'";
    
    
    $query='SELECT floor(('.$timetag_end.'-machineevent_timetag)/3600) as theHours,min(machineevent_timetag) as min_timetag,max(machineevent_timetag) as max_timetag,count(machineevent_timetag)as thecount, AVG(machineevent_timetag_finished-machineevent_timetag) as average_duration
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
     )
    left join machine on machine_id=machineallocation_machineid

    '.$filter.'
    group by floor(('.$timetag_end.'-machineevent_timetag)/3600)
    order by floor(('.$timetag_end.'-machineevent_timetag)/3600) ASC


    ';//where machineevent_entry like \'%Door%\'
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $row=$sql->fetchall();
    foreach($row as $hours){
        $return['all_hours'][$hours['theHours']]['thecount']=$hours['thecount'];
        $return['all_hours'][$hours['theHours']]['theHours']=$hours['theHours'];
        $return['all_hours'][$hours['theHours']]['ActualHours']=date('G:i',$hours['min_timetag']);
        $return['all_hours'][$hours['theHours']]['ActualTime']=$hours['max_timetag']-$hours['min_timetag'];
        $return['all_hours'][$hours['theHours']]['thedate']=date('Y-m-d',$hours['min_timetag']);
        
        $return['ActualTime']=$return['ActualTime']+$hours['max_timetag']-$hours['min_timetag'];
        $return['MaxHours']=max($return['MaxHours'],$hours['theHours']);
    }

    $query='SELECT
    ((SELECT MAX(Cycletime) FROM(SELECT  TOP 50 PERCENT min(machineevent_timetag) over(
                   ORDER BY machineevent_timetag ASC  ROWS BETWEEN 1 following AND 1 following) - machineevent_timetag AS CycleTime
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
    )
    left join machine on machine_id=machineallocation_machineid

    '.$filter.'
    ORDER BY Cycletime ASC)as BottomHalf)
    +
    (SELECT MIN(Cycletime) FROM(SELECT  TOP 50 PERCENT min(machineevent_timetag) over(
        ORDER BY machineevent_timetag ASC  ROWS BETWEEN 1 following AND 1 following) - machineevent_timetag AS CycleTime
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
    )
    left join machine on machine_id=machineallocation_machineid

    '.$filter.'
    ORDER BY Cycletime DESC)as TopHalf))/2 As Median
    
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  $return['median_cycletime']=$row['Median'];

    

    //show($return);


  return $return;
}

function get_all_type_event($db){
    $filter='Where 1=1 ';
    if(!empty($_POST['machine_name'])){
        $filter=$filter." AND machine_name='".$_POST['machine_name']."'";
    }
    
    $query='SELECT distinct machineevent_type
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
     )
    left join machine on machine_id=machineallocation_machineid
    
    '.$filter.'
    order by machineevent_type desc
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}

function get_all_machine_event($db){
    $query='SELECT distinct machine_name
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
     )
    left join machine on machine_id=machineallocation_machineid
    order by machine_name desc
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}

function get_all_MAC($db,$machinename){
    $query='SELECT distinct machineallocation_MAC
    FROM machine_event
    left join machine_allocation on 
    (  machineevent_MAC_adress=machineallocation_MAC 
    )
    left join machine on machine_id=machineallocation_machineid
    where machine_name=\''.$_POST['machine_name'].'\'
    order by machineallocation_MAC desc
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}
function get_list_MAC($db,$option='WHERE 1=1'){
    $query='SELECT temptable_MAC,machine_name, max(temptable_timetag)
    FROM temptable_v2 
	left join machine_allocation on temptable_MAC=machineallocation_MAC
    left join machine on machine_id= machineallocation_machineid
    '.$option.'
    group by temptable_MAC,machine_name,machine_location_y,machine_location_x,machine_workarea
    order by machine_workarea desc,machine_location_y,machine_location_x,max(temptable_timetag) desc
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
}
function get_mac($db,$machineid){
    $query="SELECT  *
    from machine 
    left join machine_allocation on machine_id=machineallocation_machineid 
    
    WHERE machine_id='$machineid'";//where machineevent_entry like \'%Door%\'
   $sql = $db->prepare($query); 
   //show($query);
   $sql->execute();
 
   $info=$sql->fetch();
   //show($query);
   return $info['machineallocation_MAC'];
}
function get_all_product_factory($db){
    $query='SELECT Product_Code
    FROM List_Document
    
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
function get_all_product_frombaseproduct($db,$machineid){
    $query="SELECT machinebaseproduct_productcode as product_code
    FROM machine_baseproduct
    
    Where machinebaseproduct_machineid='$machineid'
    
    order by machinebaseproduct_productcode ASC
    ";

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();
    //
    return $row;
}
function get_all_product_fromasset($db,$machineid){
    $query="SELECT Distinct assetproduct_productcode as product_code
    FROM assetproduct
    order by assetproduct_productcode ASC
    ";

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();
    //
    return $row;
}

function get_all_temptable($db){
    $filter='WHERE 1=1';
    if(!empty($_POST['temptable_MAC'])){
        $filter='WHERE temptable_MAC=\''.$_POST['temptable_MAC'].'\'';
    }
    

  
   
   $query='SELECT TOP 1000 *
	  FROM temptable_v2
      left join machine_allocation on (  temptable_MAC=machineallocation_MAC 
       )
      left join machine on machine_id=machineallocation_machineid
      '.$filter.'
      order by temptable_timetag desc,temptable_id desc
	  
	  
	';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
    //show($row);
    return $row;
}

function get_last_temptable($db,$option='Where 1=1'){
 
  $query='SELECT temptable_MAC, max(temptable_timetag) as lasttimetag,temptable_pin
    FROM temptable_v2
    '.$option.'
    group by temptable_MAC,temptable_pin
    
    order by temptable_MAC,max(temptable_timetag) desc
    
    
    ';
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $allMAC=$sql->fetchall();
    //show($allMAC);
    foreach($allMAC as $MAC){
        $query='SELECT TOP 1 *
        FROM temptable_v2
        left join machine_allocation on 
      (  temptable_MAC=machineallocation_MAC 
       )
      left join machine on machine_id=machineallocation_machineid
      left join machine_pin on machine_id=machinepin_machineid and  temptable_pin=machinepin_pinnumber
      where temptable_MAC=\''.$MAC['temptable_MAC'].'\' and temptable_timetag=\''.$MAC['lasttimetag'].'\' and temptable_pin=\''.$MAC['temptable_pin'].'\'
      order by temptable_timetag desc,temptable_id desc
        
        
      ';
      $sql = $db->prepare($query); 
      //show($query);
      $sql->execute();
  
      $row=$sql->fetch();
      $return[$row['temptable_MAC']]['temptable_MAC']=$row['temptable_MAC'];
      $return[$row['temptable_MAC']]['machine_name']=$row['machine_name'];
      $return[$row['temptable_MAC']]['pin'][$row['temptable_pin']]['temptable_pin']=$row['temptable_pin'];
      $return[$row['temptable_MAC']]['pin'][$row['temptable_pin']]['machinepin_pindescription']=$row['machinepin_pindescription'];
      $return[$row['temptable_MAC']]['pin'][$row['temptable_pin']]['temptable_value']=$row['temptable_value'];
      $return[$row['temptable_MAC']]['pin'][$row['temptable_pin']]['temptable_timetag']=$row['temptable_timetag'];
      
    }
    //show($return);
    return $return;
   
}
function get_last_temptable_machine($db,$option='Where 1=1'){
 
    $query='SELECT temptable_MAC, max(temptable_timetag) as lasttimetag
      FROM temptable_v2
      '.$option.'
      group by temptable_MAC
      
      order by temptable_MAC,max(temptable_timetag) desc
      
      
      ';
      $sql = $db->prepare($query); 
      //show($query);
      $sql->execute();
  
      $allMAC=$sql->fetch();
      
      //show($return);
      return $allMAC;
     
}

function get_pins_machine($db,$machine_id){
    $query='SELECT  *
	  FROM machine
      left join machine_pin on machine_id=machinepin_machineid
      WHERE machine_id=\''.$machine_id.'\'
     ';//left join machine on machine_id=machineallocation_machineid
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
    return $row;
}

function get_last_event($db,$MAC,$type){
    $query='SELECT  TOP 1 *
        FROM machine_event
        
        WHERE machineevent_MAC_adress=\''.$MAC.'\' and machineevent_type=\''.$type.'\' and machineevent_finished is null
        order by machineevent_timetag DESC
    ';//left join machine on machine_id=machineallocation_machineid
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetch();
    return $row;
}




function allocate_machine_MAC($db){
    
    $timetag_start=strtotime($_POST['date_allocation_start'].' '.$_POST['time_allocation_start'].'');
    $timetag_end=strtotime($_POST['date_allocation_end'].' '.$_POST['time_allocation_end'].'');
    

    //check if machine not already allocated 
    if(check_machine_allocated($db,$_POST['machine_to_allocated'],$timetag_start,$timetag_end)==false){
        //if not, allocate the machine
       
        $query="INSERT INTO dbo.machine_allocation
        ( machineallocation_MAC,
        machineallocation_machineid,
        machineallocation_timetag_start
        ) 
        VALUES (
        '".$_POST['MAC_to_allocated']."',    
        '".$_POST['machine_to_allocated']."',
        '".time()."')";	

        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();

    }else{
        //else show message error and come back to the allocation windows
        $_POST['MAC']=$_POST['MAC_to_allocated'];
        $_POST['view']=$_POST['Allocation Machine'];
    }
    

    
}

function import_temptable($db){
    $query='SELECT TOP 1000 *
	  FROM temptable
      left join machine_allocation on 
        (  temptable_MAC=machineallocation_MAC 
         )
      left join machine on machine_id=machineallocation_machineid
      where temptable_imported is null and machine_id is not null and temptable_version is null
      order by temptable_timetag ASC,temptable_id ASC
      
	  
	  
	';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$alldata=$sql->fetchall();
    //show($alldata);
    foreach($alldata as $import_line){
        //show($import_line);
        $pins=explode(";",$import_line['temptable_entry']);

        foreach(get_pins_machine($db,$import_line['machine_id']) as $pinobject){
               
                $pinobject['value']=$pins[$pinobject['machinepin_pinnumber']];
                $pinobject['change']=$pins[($pinobject['machinepin_pinnumber']+8)];
                $pinobject['MAC']=$import_line['temptable_MAC'];
                $pinobject['timetag']=$import_line['temptable_timetag'];
                $pinobject['temptable_id']=$import_line['temptable_id'];
                //show($pinobject);

                if ($pinobject['machinepin_triggerpull']==1){ // if it is a trigger exemple start button
                    if ($pinobject['change']==1 and $pinobject['value']==1){
                        //create or stop event
                        create_stop_event($db,$pinobject);
                    }else{
                        //nothing happen
                    }
                }else{  // if it is a trigger a pull exemple door open or power on
                    if ($pinobject['change']==1){
                        //create or stop event
                        create_stop_event($db,$pinobject);
                    }elseif ($pinobject['value']==1){
                        //increase the time of the last status for that event
                        update_time_event($db,$pinobject);
                    }
                }
        }
        update_row_temptable_imported($db,$pinobject['temptable_id']);
    }
    update_hours_event($db);
    
}
function import_temptable_v2_backup($db){
    
    $query='SELECT  *
	  FROM machine
      order by machine_name ASC';
    $sql = $db->prepare($query); 
    $sql->execute();
    $allmachines=$sql->fetchall();
    foreach($allmachines as $machine){
        $cyclemini[$machine['machine_name']]=$machine['machine_cycle_min'];
        $cyclemax[$machine['machine_name']]=$machine['machine_cycle_max'];
    }
    //show($cyclemax);
  
    $query='SELECT machine_name,max([machinecycle_timetag_finish])as timetag
    FROM [barcode].[dbo].[machine_cycle]
    left join machine on machine_id=machinecycle_machineid
    group by machine_name';
    $sql = $db->prepare($query); 
    $sql->execute();
    $allmachine=$sql->fetchall();
    $last=array();
    foreach($allmachine as $machine){
        $last[$machine['machine_name']]['timetag']=$machine['timetag'];
        $last[$machine['machine_name']]['status']='finish';
    }
    //show($allmachine);
    
    $query='SELECT TOP 900 machine_id,machine_name,temptable_timetag,temptable_id,temptable_MAC
	  FROM temptable_v2
      left join machine_allocation on temptable_MAC=machineallocation_MAC 
      left join machine on machine_id=machineallocation_machineid  and machine_cycle_pinumber=temptable_pin
      where temptable_cycle_imported is null and machine_id is not null and temptable_value=1
      order by temptable_timetag ASC,temptable_id ASC';
    $sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$alldata=$sql->fetchall();
    
    $cycle=array();
    $i=array();
    //show($alldata);
    foreach($alldata as $import_line){

        if(empty($i[$import_line['machine_name']])){
            $i[$import_line['machine_name']]++;
        }
       
        $duration_since_last=$import_line['temptable_timetag']-$last[$import_line['machine_name']]['timetag'];
        if($duration_since_last>$cyclemini[$import_line['machine_name']] or empty($last[$import_line['machine_name']])){
            //show($duration_since_last.'<'.$cyclemax[$import_line['machine_name']]);
            if($duration_since_last<$cyclemax[$import_line['machine_name']] or empty($last[$import_line['machine_name']])){
                
                if($last[$import_line['machine_name']]['status']=='start'){
                    $cycle[$import_line['machine_name']][$i[$import_line['machine_name']]]['timetag_finish']=$import_line['temptable_timetag'];
                    $cycle[$import_line['machine_name']][$i[$import_line['machine_name']]]['duration']=$import_line['temptable_timetag']-$cycle[$import_line['machine_name']][$i[$import_line['machine_name']]]['timetag_start'];
                    
                    //$last[$import_line['machine_name']]['status']='finish';
                    //$last[$import_line['machine_name']]['timetag']=$import_line['temptable_timetag'];
                    $last_id_for_cyle[$import_line['machine_name']]['temptable_timetag']=$import_line['temptable_timetag'];
                    $last_id_for_cyle[$import_line['machine_name']]['temptable_id']=$import_line['temptable_id'];
                    $last_id_for_cyle[$import_line['machine_name']]['temptable_MAC']=$import_line['temptable_MAC'];

                    $i[$import_line['machine_name']]++;

                    $cycle[$import_line['machine_name']][$i[$import_line['machine_name']]]['timetag_start']=$import_line['temptable_timetag'];
                    $cycle[$import_line['machine_name']][$i[$import_line['machine_name']]]['machine_id']=$import_line['machine_id'];
                    $cycle[$import_line['machine_name']][$i[$import_line['machine_name']]]['date']=date('Y-m-d',$import_line['temptable_timetag']);
                    $last[$import_line['machine_name']]['status']='start';
                    $last[$import_line['machine_name']]['timetag']=$import_line['temptable_timetag'];
                }else{
                    $cycle[$import_line['machine_name']][$i[$import_line['machine_name']]]['timetag_start']=$import_line['temptable_timetag'];
                    $cycle[$import_line['machine_name']][$i[$import_line['machine_name']]]['machine_id']=$import_line['machine_id'];
                    $cycle[$import_line['machine_name']][$i[$import_line['machine_name']]]['date']=date('Y-m-d',$import_line['temptable_timetag']);

                    $last[$import_line['machine_name']]['status']='start';
                    $last[$import_line['machine_name']]['timetag']=$import_line['temptable_timetag'];
                }
            }else{
                $cycle[$import_line['machine_name']][$i[$import_line['machine_name']]]['timetag_start']=$import_line['temptable_timetag'];
                $cycle[$import_line['machine_name']][$i[$import_line['machine_name']]]['machine_id']=$import_line['machine_id'];
                $cycle[$import_line['machine_name']][$i[$import_line['machine_name']]]['date']=date('Y-m-d',$import_line['temptable_timetag']);

                $last[$import_line['machine_name']]['status']='start';
                $last[$import_line['machine_name']]['timetag']=$import_line['temptable_timetag'];
            }
            
        }
        $id_to_switch_to_import[$import_line['machine_name']][]=$import_line['temptable_id'];
        
       
    }
    
    $filter='0=1';
    foreach($last_id_for_cyle as $machine){
        //foreach($machine as $temptable_id){
            
            //$filter=$filter." or( temptable_id='$temptable_id'";
            $filter=$filter." or ( temptable_timetag<='".$machine['temptable_timetag']."' and temptable_MAC<='".$machine['temptable_MAC']."')";
        //}
        
    }
    $query="UPDATE temptable_v2 SET temptable_cycle_imported=1 WHERE $filter";
    //show($query);
    $sql = $db->prepare($query);
    //$sql->execute(); 
   
   
    $query='INSERT INTO machine_cycle (
        machinecycle_machineid, 
        machinecycle_timetag_start, 
        machinecycle_timetag_finish,
        machinecycle_duration,
        machinecycle_hour,
        machinecycle_date
        )
    VALUES ';
    $c=0;
    foreach($cycle as $machine){
        foreach($machine as $cycle_to_add){
            
            if(!empty($cycle_to_add['timetag_finish'])){
                if( $c==0){
                    $c=1;
                }else{
                    $query=$query.',';
                   
                }
                $query=$query."('".$cycle_to_add['machine_id']."',
                '".round($cycle_to_add['timetag_start'])."',
                '".round($cycle_to_add['timetag_finish'])."',
                '".$cycle_to_add['duration']."',
                '".date('G',$cycle_to_add['timetag_start'])."',
                '".$cycle_to_add['date']."')";
            }
            
        }
    }
    
    if( $c==1){
        //show($query);

        $sql = $db->prepare($query);
        //$sql->execute();
    }
    //show($query);
    //show($cycle);
   
    
}
function import_temptable_v2($db){
    separator();

    $query="SELECT min([temptable_timetag])as[temptable_timetag],machine_name,temptable_MAC
        FROM [barcode].[dbo].[Temptablev2_view]
        group by machine_name,temptable_MAC
        order by [temptable_timetag] asc";
    $sql = $db->prepare($query); 
    $sql->execute();
    $allmachine=$sql->fetchall();

    foreach($allmachine as $machine){
        $temptable_timetag=$machine['temptable_timetag'];
        $temptable_MAC=$machine['temptable_MAC'];
        $query="SELECT machine_id,machine_name,temptable_timetag,temptable_id,temptable_MAC,machine_cycle_min,machine_cycle_max
        FROM temptable_v2
        left join machine_allocation on temptable_MAC=machineallocation_MAC 
        left join machine on machine_id=machineallocation_machineid  and machine_cycle_pinumber=temptable_pin
        where temptable_MAC='$temptable_MAC' and temptable_timetag=$temptable_timetag and temptable_value=1";
        $sql = $db->prepare($query); 
       
        $sql->execute();
        $import_line=$sql->fetch();
        
        //show($query);
    
    
        if(!empty($import_line) ){
            $query='SELECT machine_name,max([machinecycle_timetag_finish])as [machinecycle_timetag_finish],max([machinecycle_timetag_start])as [machinecycle_timetag_start]
            FROM [barcode].[dbo].[machine_cycle]
            left join machine on machine_id=machinecycle_machineid
            
            where machine_id=\''.$import_line['machine_id'].'\'
            group by machine_name';
            $sql = $db->prepare($query); 
            $sql->execute();
            $machine=$sql->fetch();
           
            $last=array();
            //show($query);
    
            
            if(!empty($machine) ){
                if(!empty($machine['machinecycle_timetag_finish'])){
                    if($machine['machinecycle_timetag_start']>$machine['machinecycle_timetag_finish']){
                        $last['status']='start';
                        $query='SELECT machinecycle_id
                        FROM [barcode].[dbo].[machine_cycle]
                        left join machine on machine_id=machinecycle_machineid
                        where machine_id=\''.$import_line['machine_id'].'\' and machinecycle_timetag_start='.round($machine['machinecycle_timetag_start']).'';
                        $sql = $db->prepare($query); 
                        $sql->execute();
                        $temp=$sql->fetch();
                        //show('test');
                        $last['machinecycle_id']=$temp['machinecycle_id'];
                        $last['timetag']=$machine['machinecycle_timetag_start'];
                    }else{
                        $last['status']='finish';
                        $last['timetag']=$machine['machinecycle_timetag_finish'];
                    }
                }else{
                    
                    
                    
                    $query='SELECT machinecycle_id
                    FROM [barcode].[dbo].[machine_cycle]
                    left join machine on machine_id=machinecycle_machineid
                    where machine_id=\''.$import_line['machine_id'].'\' and machinecycle_timetag_start='.round($machine['machinecycle_timetag_start']).'';
                    $sql = $db->prepare($query); 
                    $sql->execute();
                    $temp=$sql->fetch();
                    //show($query);
                    
                    $last['status']='start';
                    $last['machinecycle_id']=$temp['machinecycle_id'];
                    $last['timetag']=$machine['machinecycle_timetag_start'];
                }
            }
            
            
            
            $duration_since_last=$import_line['temptable_timetag']-$last['timetag'];
            //show('duration:'.$duration_since_last.' sec : '.show_time($duration_since_last).'='.$import_line['temptable_timetag'].'-'.$last['timetag']);
            //show($last);
            //show($import_line);
            //show($duration_since_last.">=".$import_line['machine_cycle_min']);
            //show($duration_since_last>=$import_line['machine_cycle_min']);
            $query='';
            if(!empty($machine)){
                if($duration_since_last>=$import_line['machine_cycle_min'] ){
                        
                    if($duration_since_last<=$import_line['machine_cycle_max'] ){
                        if( $last['status']=='finish'){
                            $query='INSERT INTO machine_cycle (
                                machinecycle_machineid, 
                                machinecycle_timetag_start, 
                                machinecycle_timetag_finish,
                                machinecycle_duration,
                                machinecycle_hour,
                                machinecycle_date
                                )
                            VALUES '."('".$import_line['machine_id']."',
                             '".round($last['timetag'])."',
                             '".round($import_line['temptable_timetag'])."',
                             '".$duration_since_last."',
                             '".date('G',$import_line['temptable_timetag'])."',
                             '".date('Y-m-d',$import_line['temptable_timetag'])."');";
                        }else{
                            if(!empty($last['machinecycle_id'])){
                                $query="UPDATE machine_cycle SET 
                                machinecycle_timetag_finish='".round($import_line['temptable_timetag'])."',
                                machinecycle_duration='".$duration_since_last."',
                                machinecycle_hour='".date('G',$import_line['temptable_timetag'])."',
                                machinecycle_date='".date('Y-m-d',$import_line['temptable_timetag'])."'
                
                                WHERE machinecycle_id=".$last['machinecycle_id'].";";
                            }else{
                                $query='INSERT INTO machine_cycle (
                                    machinecycle_machineid, 
                                    machinecycle_timetag_start
                                    
                                    )
                                VALUES '."('".$import_line['machine_id']."',
                                 '".round($import_line['temptable_timetag'])."');";
                            }
                            
                        }
                    }else{
                        if( $last['status']=='finish' or empty($last)){
                            $query='INSERT INTO machine_cycle (
                                machinecycle_machineid, 
                                machinecycle_timetag_start
                                
                                )
                            VALUES '."('".$import_line['machine_id']."',
                             '".round($import_line['temptable_timetag'])."');";
                        }else{
                            if(!empty($last['machinecycle_id'])){
                                $query="UPDATE machine_cycle SET 
                                machinecycle_timetag_start='".round($import_line['temptable_timetag'])."'
                
                                WHERE machinecycle_id=".$last['machinecycle_id'].";";
                            }else{
                                $query='INSERT INTO machine_cycle (
                                    machinecycle_machineid, 
                                    machinecycle_timetag_start
                                    
                                    )
                                VALUES '."('".$import_line['machine_id']."',
                                 '".round($import_line['temptable_timetag'])."');";
                            }
                        }
                    }
                    
                }
            }else{
                $query='INSERT INTO machine_cycle (
                    machinecycle_machineid, 
                    machinecycle_timetag_start
                    
                    )
                VALUES '."('".$import_line['machine_id']."',
                 '".round($import_line['temptable_timetag'])."');";
            }
            
            $query=$query."UPDATE temptable_v2 SET temptable_cycle_imported=1 WHERE temptable_timetag=".$import_line['temptable_timetag'].";";
            //show($query);
            $sql = $db->prepare($query); 
            $sql->execute();
            
        }
    }

    
    


    
}
function import_v2_multiple($db,$number_of_import){
    for ($x = 1; $x <= $number_of_import; $x++) {
        import_temptable_v2($db);
      }
}
function import_ajax($db){
    ?>
    <div class="row">

    </div>
    
    <div class="import_ajax" id="import_ajax">
        <?php script_import_ajax($db,1); ?>
    </div>
    <?php
}
function script_import_ajax($db,$count){
    if($count<=5000){
        ?><script>
        var request =$.ajax({
        type:'POST',
        url:'machine_ajax.php',
        data: {
            count:'<?php echo $count; ?>',
            import_ajax:'import_ajax',
            time_POST:'<?php echo time()?>'
        },
        success:function(html){
            $('.import_ajax').empty().append(html);
        }
        });
        </script>
        <?php
        
        
    }
    $tobedone=100;
    if(!empty($_POST['time_POST'])){
        $sec_since=time()-$_POST['time_POST'];
        $import_per_hour=$tobedone*3600/$sec_since;
        show(show_time($sec_since)." / $import_per_hour imports/hour");
    }
    
    
    import_v2_multiple($db,$tobedone);
    show($count);
    
}


function remove_all_cycle($db,$date="",$machineid=""){
    $filter1="1=1";
    if(!empty($date)){
        $filter1=$filter1." and machinecycle_date>='$date'";
    }
    if(!empty($machineid)){
        $filter1=$filter1." and machinecycle_machineid=$machineid";
    }
    $filter2="1=1";
    if(!empty($date)){
        $filter2=$filter2." and temptable_timetag>=".strtotime($date)."";
    }
    if(!empty($machineid)){
        $filter2=$filter2." and temptable_MAC='".get_mac($db,$machineid)."'";
    }

    $query="delete from machine_cycle where $filter1 or machinecycle_date is null;
    UPDATE temptable_v2 SET temptable_cycle_imported=NULL WHERE $filter2 ";
    $sql = $db->prepare($query); 
	show($query);
	$sql->execute();
}

function re_import_all_event($db,$mac){
    //delete all event
        $query='Delete
        FROM machine_event
        where machineevent_MAC_adress=\''.$mac.'\'';
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
    // update all temptable -> not imported
        $query='UPDATE dbo.temptable SET 
        temptable_imported=NULL
            
        WHERE temptable_MAC=\''.$mac.'\'';
        
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
}

function clean_data($db){
    $startime=strtotime(date('Y-m-d',1637729937));
    $endtime=$startime+3600-1;
    
    for($i=0;$i<6000;$i++){
        $hourtoadd=date('G',$startime);
        $query='Update machine_cycle
        SET machinecycle_hour=\''.$hourtoadd.'\'
        Where machinecycle_timetag_start>='.$startime.' and machinecycle_timetag_start<='.$endtime.'
        ';//
        $sql = $db->prepare($query); 
        show($hourtoadd.':'.$query);
        show('start:'.date('Y-m-d G:i:s',$startime));
        show('finish:'.date('Y-m-d G:i:s',$endtime));
        $sql->execute();
        $startime=$endtime+1;
        $endtime=$startime+3600-1;
    }



    
    
   
}

function check_machine_allocated($db,$machine,$timetag_start,$timetag_end){
    $query='SELECT distinct machineallocation_MAC
    FROM machine_allocation
    
    left join machine on machine_id=machineallocation_machineid
    where machine_id=\''.$_POST['machine_to_allocated'].'\' 
    
    
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  if(empty($row)){
      return false;
  }else{
    return true;
  }
 
}

function clean_temptable_not_allocated($db){
    $query="delete from temptable_v2 

    where temptable_MAC NOT IN (
    SELECT DISTINCT machineallocation_MAC from machine_allocation)
    ";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

}




function is_there_duration($db,$type,$machinename){
    $query='SELECT  machinepin_triggerpull
    FROM machine
    left join machine_pin on machine_id=machinepin_machineid
    WHERE machine_name=\''.$machinename.'\' and machinepin_pindescription=\''.$type.'\'
   ';//left join machine on machine_id=machineallocation_machineid
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetch();
  
  if($row['machinepin_triggerpull']=='1'){$return='no_duration';}else{$return='duration';}
  return $return;
}

function create_stop_event($db,$pinobject){
    if($pinobject['machinepin_triggerpull']==1 and $pinobject['value']==1 ){
        //show('create trigger event');
        $entry=$pinobject['machinepin_pindescription']." pushed";
        $query="INSERT INTO dbo.machine_event
        ( machineevent_timetag,
        machineevent_timetag_import,
        machineevent_MAC_adress,
        machineevent_type,
        machineevent_entry,
        machineevent_date
        ) 
        VALUES (
        '".$pinobject['timetag']."',    
        '".time()."',
        '".$pinobject['MAC']."',
        '".$pinobject['machinepin_pindescription']."',
        '".$entry."',
        '".date("Y-m-d",$pinobject['timetag'])."')";	

        $sql = $db->prepare($query); 
        show($query);
        $sql->execute();
        //update_row_temptable_imported($db,$pinobject['temptable_id']);
        $import='ok';
    }elseif( $pinobject['value']==1 ){
        //show('create pull event');
        $entry=$pinobject['machinepin_pindescription']." started";
        $query="INSERT INTO dbo.machine_event
        ( machineevent_timetag,
        machineevent_timetag_import,
        machineevent_MAC_adress,
        machineevent_type,
        machineevent_entry
        ) 
        VALUES (
        '".$pinobject['timetag']."',    
        '".time()."',
        '".$pinobject['MAC']."',
        '".$pinobject['machinepin_pindescription']."',
        '".$entry."')";	

        $sql = $db->prepare($query); 
        show($query);
        $sql->execute();
        //update_row_temptable_imported($db,$pinobject['temptable_id']);
    }else{
        //show('stop pull event');
        $lastevent=get_last_event($db,$pinobject['MAC'],$pinobject['machinepin_pindescription']);

        $query='UPDATE dbo.machine_event SET 
        machineevent_finished=\'1\',
        machineevent_timetag_finished=\''.$pinobject['timetag'].'\'
            
        WHERE machineevent_MAC_adress=\''.$pinobject['MAC'].'\' 
        and machineevent_type=\''.$pinobject['machinepin_pindescription'].'\' 
        and machineevent_timetag=\''.$lastevent['machineevent_timetag'].'\'  ' ;
        
        $sql = $db->prepare($query); 
        show($query);
        $sql->execute();
       //update_row_temptable_imported($db,$pinobject['temptable_id']);
    }
}

function update_time_event($db,$pinobject){
    //show('update event');
    $lastevent=get_last_event($db,$pinobject['MAC'],$pinobject['machinepin_pindescription']);

        $query='UPDATE dbo.machine_event SET 
        
        machineevent_timetag_finished=\''.$pinobject['timetag'].'\'
            
        WHERE machineevent_MAC_adress=\''.$pinobject['MAC'].'\' 
        and machineevent_type=\''.$pinobject['machinepin_pindescription'].'\' 
        and machineevent_timetag=\''.$lastevent['machineevent_timetag'].'\'  ' ;
        
        $sql = $db->prepare($query); 
        //show($query);
       $sql->execute();
       //update_row_temptable_imported($db,$pinobject['temptable_id']);
}
function update_hours_event($db){
    $query="UPDATE [machine_event]
    SET machineevent_hour=
    floor(
	 iif (
		 ( 10+( ( [machineevent_timetag]-( floor([machineevent_timetag]/3600/24) )*3600*24 )/ 3600 ) )>24,
		 (-14+(([machineevent_timetag]-(floor([machineevent_timetag]/3600/24))*3600*24 )/ 3600)),
		 (10+(([machineevent_timetag]-(floor([machineevent_timetag]/3600/24))*3600*24 )/ 3600))
	 )
	 )
    where machineevent_hour is null";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

}
function save_position($db,$machine_id,$machine_location_x,$machine_location_y){
    $machine_location_x=round($machine_location_x,1);
    $machine_location_y=round($machine_location_y,1);
    $query="UPDATE machine
    SET machine_location_x=$machine_location_x,machine_location_y=$machine_location_y
    where machine_id='$machine_id'";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

}
function create_machine($db,$machine_location_x,$machine_location_y,$machine_workarea){
    $machine_location_x=round($machine_location_x,1);
    $machine_location_y=round($machine_location_y,1);
    $query="INSERT INTO machine(machine_location_x,machine_location_y,machine_workarea,machine_name)
    Values ($machine_location_x,$machine_location_y,'$machine_workarea','New Machine')";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
}
function delete_machine($db,$machine_id){
    $query="Delete from machine
    WHERE machine_id=$machine_id;
    Delete from machineallocation
    WHERE machineallocation_machineid=$machine_id";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
}
function save_changes($db,$machine_id,$machine_name,$machine_workarea,$machineallocation_MAC,$machine_number,$machinepin_pindescription,$machine_cycle_pinumber){
    $query="UPDATE machine
    SET machine_workarea='$machine_workarea',machine_name='$machine_name',machine_number='$machine_number',machine_cycle_pinumber='$machine_cycle_pinumber[0]'
    where machine_id='$machine_id'";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    if(!empty($machineallocation_MAC)){
        if($machineallocation_MAC=='Remove'){
            $query="DELETE
            from machine_allocation
            where machineallocation_machineid='$machine_id'";
            $sql = $db->prepare($query); 
            $sql->execute();
        }else{
            $query="Select machineallocation_MAC
            from machine_allocation
            where machineallocation_machineid='$machine_id'";
            $sql = $db->prepare($query); 
            $sql->execute();
            $test=$sql->fetch();
            if(empty($test)){
                $query="INSERT INTO  machine_allocation(machineallocation_MAC,machineallocation_machineid)
                VALUES ('$machineallocation_MAC','$machine_id')
                ";
            }else{
                $query="UPDATE machine_allocation
                SET machineallocation_MAC='$machineallocation_MAC'
                where machineallocation_machineid='$machine_id'";
            }
            
            $sql = $db->prepare($query); 
            //show($query);
            $sql->execute();
        }
        
    }
    if(!empty($machinepin_pindescription)){
        $query2='';
        for ($x = 2; $x <= 8; $x++) {
            $machinepin_pindescription2=$machinepin_pindescription[$x-2];
            $machinepin_pinnumber=$x;
            $query="Select machinepin_pindescription
            from machine_pin
            where machinepin_pinnumber='$machinepin_pinnumber' and machinepin_machineid='$machine_id'";
            $sql = $db->prepare($query); 
            $sql->execute();
            $test=$sql->fetch();
            if(empty($test)){
                $query2=$query2."INSERT INTO  machine_pin(machinepin_pindescription,machinepin_pinnumber,machinepin_machineid)
                VALUES ('$machinepin_pindescription2','$machinepin_pinnumber','$machine_id');";
            }else{
                $query2=$query2."UPDATE machine_pin
                SET machinepin_pindescription='$machinepin_pindescription2'
                where machinepin_pinnumber='$machinepin_pinnumber' and machinepin_machineid='$machine_id';";
            }
            
            
        }
        $sql = $db->prepare($query2); 
        //show($query2);
        $sql->execute();
    }
}
function save_machineproduct($db,$machine_id,$machineproduct_productcode){
    $query='';
    $datetoinsert=$_POST['date_filter'];
    for($i=0;$i<=100;$i++){
        $query=$query."DELETE FROM machine_product
        where machineproduct_machineid='$machine_id' and machineproduct_date_start ='".$datetoinsert."';
        INSERT INTO  machine_product(machineproduct_productcode,machineproduct_machineid,machineproduct_date_start)
        VALUES ('$machineproduct_productcode','$machine_id','".$datetoinsert."');
        ";
        $datetoinsert=date('Y-m-d',strtotime($datetoinsert.' +1 days'));
    }
    

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
}
function remove_machineproduct($db,$machine_id){
    $query="UPDATE machine_product
    SET machineproduct_date_end='".$_POST['date_filter']."'
    where machineproduct_machineid='$machine_id' and machineproduct_date_end is null";
    $query="DELETE FROM machine_product
    where machineproduct_machineid='$machine_id' and machineproduct_date_start >='".$_POST['date_filter']."'";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
}
function save_machinebaseproduct($db,$machine_id,$machinebaseproduct_productcode){
    $query="INSERT INTO  machine_baseproduct(machinebaseproduct_productcode,machinebaseproduct_machineid)
    VALUES ('$machinebaseproduct_productcode','$machine_id')";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
}
function remove_machinebaseproduct($db,$machine_id,$machinebaseproduct_productcode){
    $query="Delete from machine_baseproduct
    where machinebaseproduct_machineid='$machine_id' and machinebaseproduct_productcode='$machinebaseproduct_productcode'";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
}

function update_row_temptable_imported($db,$rowid){
    $query='UPDATE dbo.temptable SET 
    temptable_imported=\'1\'
        
    WHERE temptable_id=\''.$rowid.'\'';
    
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

}

function show_time($secondes){
    $secondes=round($secondes,2);
    if($secondes==0){
        $return='-';
    // }elseif($secondes<60){
    //     $return=$secondes.' sec';
    // }elseif($secondes<3600){
    //     $minutes=floor($secondes/60);
    //     $secondes=$secondes-$minutes*60;
    //     $return=$minutes.' min '.$secondes.' sec';
    // }elseif($secondes<(3600*24)){
    //     $hours=floor($secondes/3600);
    //     $secondes=$secondes-$hours*3600;
    //     $minutes=floor($secondes/60);
    //     $secondes=$secondes-$minutes*60;
    //     $return=$hours.' h '.$minutes.' min '.$secondes.' sec';
    }else{
        $days=floor($secondes/3600/24);
        $secondes=$secondes-$days*3600*24;
        $hours=floor($secondes/3600);
        $secondes=$secondes-$hours*3600;
        $minutes=floor($secondes/60);
        $secondes=$secondes-$minutes*60;
        if($days>0){
            $return=$days.' days ';
        }
        if($hours>0){
            $return=$return.$hours.' h ';
        }
        if($minutes>0){
            $return=$return.$minutes.' min ';
        }
        if($secondes>0){
            $return=$return.$secondes.' sec ';
        }
        
    }

    return $return;
} 


function refresh_div($divid,$milliseconds){
    echo'<script> 
        $(document).ready(function(){
        setInterval(function(){
                $("#'.$divid.'").load(window.location.href + " #'.$divid.'" );
        }, '.$milliseconds.');
        });
        </script>';
}
function refresh_once_div($divid){
    echo'<script> 
            $("#'.$divid.'").load(window.location.href + " #'.$divid.'" );
        </script>';
}

function show_distribution_chart($data,$min,$max,$bucket){
    $bucketsize=round(($max-$min)/$bucket,2);
    $ticks_size=round(($max-$min)/10,2);
    $ticks='['.$min.', ';
    $ticks=$ticks.($min+$ticks_size).', ';
    $ticks=$ticks.($min+2*$ticks_size).', ';
    $ticks=$ticks.($min+3*$ticks_size).', ';
    $ticks=$ticks.($min+4*$ticks_size).', ';
    $ticks=$ticks.($min+5*$ticks_size).', ';
    $ticks=$ticks.($min+6*$ticks_size).', ';
    $ticks=$ticks.($min+7*$ticks_size).', ';
    $ticks=$ticks.($min+7*$ticks_size).', ';
    $ticks=$ticks.($min+8*$ticks_size).', ';
    $ticks=$ticks.($min+9*$ticks_size).', ';
    $ticks=$ticks.$max.']';
    

    echo'<div id="example2.5" style="width: 100%; height: 500px"></div>';
   echo"<script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
   <script type=\"text/javascript\">
     google.charts.load('current', {packages:['corechart']});
     google.charts.setOnLoadCallback(drawChart);
   
   function drawChart() {
   
     
     var arr = [['Cycle Time']];";
     $i=1;
     foreach($data as $number){
         if(!empty($number[0])){
            echo' arr.push(['.$number[0].']);';
         }
         
         $i++;
     }
      
     
     echo"var data = google.visualization.arrayToDataTable(arr);
   
     var options = {
       title: 'Approximating Normal Distribution',
       legend: { position: 'none' },
       colors: ['#4285F4'],
   
       chartArea: { width: 405 },
       
   
       bar: {
         gap: 0
       },
   
       histogram: {
        hideBucketItems:true,
        lastBucketPercentile: 10,
         bucketSize: 1,
         maxNumBuckets: 20,
         min:0,
         max:10
       }
     };
   
     var chart = new google.visualization.Histogram(document.getElementById('example2.5'));
   
     chart.draw(data, options);
   }
   </script>"; 
}

function show_gauge_efficiency($name,$value,$option1=''){
    if(empty($option1)){
        $option1='redFrom: 0, redTo: 50,
        yellowFrom:50, yellowTo: 75,
        greenFrom:75, greenTo: 100,';
    }
   

    echo'<div id="'.$name.'" align="center" class="gauge_machine" style="width: 100%;"></div>';
   echo"<script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
   <script type=\"text/javascript\">
     google.charts.load('current', {packages:['gauge']});
     google.charts.setOnLoadCallback(drawChart);
   
   function drawChart() {";
   
     
    
    
     
     echo" var data = google.visualization.arrayToDataTable([
        ['Label', 'Value'],
        ['".$name."', ".$value."]
      ]);

      var options = {
       height:120,
       ".$option1."
        minorTicks: 5
      };

      var chart = new google.visualization.Gauge(document.getElementById('".$name."'));

      chart.draw(data, options);

      
   }
   </script>"; 
}

function show_graph_pin_time($db,$minutestoshow){
    $filter='WHERE 1=1';
    //show($_SESSION['temp']['pin_to_show']);
    if(!empty($_SESSION['temp']['pin_to_show'])){
        $filter= $filter.' and (0=1 ';
        foreach($_SESSION['temp']['pin_to_show'] as $temparray){
            foreach($temparray as $pintoshow){
                $filter= $filter.' or (temptable_MAC=\''.$pintoshow['temptable_MAC'].'\' and temptable_pin=\''.$pintoshow['temptable_pin'].'\')';
                $listpin[$pintoshow['temptable_MAC'].$pintoshow['temptable_pin']]['name']=get_pin_name($db,$pintoshow['temptable_pin'],$pintoshow['temptable_MAC']);
                $listpin[$pintoshow['temptable_MAC'].$pintoshow['temptable_pin']]['temptable_pin']=$pintoshow['temptable_pin'];
                }
            }
        $filter= $filter.')';
    }
    
    
    $query="SELECT  *
    FROM temptable_v2
    $filter
    and temptable_timetag>='".(time()-($minutestoshow*60))."'
    order by temptable_MAC,temptable_timetag desc,temptable_id desc";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $all_temptable_details=$sql->fetchall();
    $last=array();
    $j=array();
    foreach($all_temptable_details as $entry){
        //nothing yet
        if($j[$entry['temptable_pin']]>0){
            $timetagminusone=$entry['temptable_timetag']-0.5;
            $table[$timetagminusone]['temptable_timetag']=$entry['temptable_timetag'];
            $table[$timetagminusone][$entry['temptable_pin']]=$last[$entry['temptable_pin']];
        }
        $table[$entry['temptable_timetag']]['temptable_timetag']=$entry['temptable_timetag'];
        $table[$entry['temptable_timetag']][$entry['temptable_pin']]=$entry['temptable_value']; 
        $last[$entry['temptable_pin']]=$entry['temptable_value'];  
        $j[$entry['temptable_pin']]=$j[$entry['temptable_pin']]+1;   
        
    }
    $last=array();
    //show($table);
    foreach($table as $entry){
        foreach($listpin as $pintoshow){
            if(empty($table[$entry['temptable_timetag']][$pintoshow['temptable_pin']])){
                $table[$entry['temptable_timetag']][$pintoshow['temptable_pin']]= $last[$pintoshow['temptable_pin']]+0;
            }
            
        }
       
        $last[$entry['temptable_pin']]=$entry['temptable_value'];        
    }
    //show($table);
    
    ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <div id="chart_div"></div>
    <script>
        google.charts.load('current', {packages: ['corechart', 'line']});
        google.charts.setOnLoadCallback(drawBasic);

        function drawBasic() {

            var data = new google.visualization.DataTable();
            data.addColumn('date', 'Date');
            <?php
            foreach($listpin as $pintoshow){
                echo "data.addColumn('number', '".$pintoshow['name']."');";
                echo" data.addColumn({type: 'string', role: 'tooltip'});";
            }
            ?>
            
            
           

            data.addRows([<?php
            
            $i=0;
            foreach($table as $entry){
                if($i==0){
                    $year=date("Y",time());
                    $month=date("m",time())-1;
                    $day=date("d",time());
                    $hours=date("G",time());
                    $minutes=date("i",time());
                    $secondes=date("s",time());
                    
                    echo '[new Date('.$year.','.$month.','.$day.','.$hours.','.$minutes.','.$secondes.'), ';
                    foreach($listpin as $pintoshow){
                        echo $entry[$pintoshow['temptable_pin']].',';
                        echo '\'PIN'.$pintoshow['temptable_pin'].' - '.date("G:i:s",time()).'\',';
                    }
                    echo'],';
                }
                if($entry['temptable_timetag']>100){
                    $year=date("Y",$entry['temptable_timetag']);
                    $month=date("m",$entry['temptable_timetag'])-1;
                    $day=date("d",$entry['temptable_timetag']);
                    $hours=date("G",$entry['temptable_timetag']);
                    $minutes=date("i",$entry['temptable_timetag']);
                    $secondes=date("s",$entry['temptable_timetag']);
                    
                    echo '[new Date('.$year.','.$month.','.$day.','.$hours.','.$minutes.','.$secondes.'), ';
                    foreach($listpin as $pintoshow){
                        echo $entry[$pintoshow['temptable_pin']].',';
                        echo '\'PIN'.$pintoshow['temptable_pin'].' - '.date("G:i:s",$entry['temptable_timetag']).'\',';
                        
                    }
                    echo'],';
                   
                }
                $i++;
            }
            
            ?>
               
            ]);

            var options = {
                legend:{
                    position: 'bottom'
                },
                hAxis: {
                    title: 'Date'
                },
                vAxis: {
                    title: 'Value',
                    maxValue:1,
                    maxValue:0,
                },
                colors: ['#a52714', '#097138'],
                crosshair: {
                    color: '#000',
                    trigger: 'selection'
                },
                title:'Last <?php echo $minutestoshow; ?> minutes',
                series: {
            0: { color: '#43459d' },
            1: { color: '#e2431e' },
            2: { color: '#6f9654' }
          }
                

            };

            var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

            chart.draw(data, options);
            }
    </script>


    <?php
}
function show_timeline_pin_time($db,$minutestoshow,$id=1){
    $filter='WHERE 1=1';
    
    if(!empty($_SESSION['temp']['pin_to_show'])){
        $filter= $filter.' and (0=1 ';
        foreach($_SESSION['temp']['pin_to_show'] as $temparray){
            foreach($temparray as $pintoshow){
                $filter= $filter.' or (temptable_MAC=\''.$pintoshow['temptable_MAC'].'\' and temptable_pin=\''.$pintoshow['temptable_pin'].'\')';

                $listpin[$pintoshow['temptable_MAC'].$pintoshow['temptable_pin']]['name']=get_pin_name($db,$pintoshow['temptable_pin'],$pintoshow['temptable_MAC']);
                $listpin[$pintoshow['temptable_MAC'].$pintoshow['temptable_pin']]['temptable_pin']=$pintoshow['temptable_pin'];
                }
            }
        $filter= $filter.')';
    }
    
    
    
    $query="SELECT  *
    FROM temptable_v2
    left join machine_allocation on machineallocation_MAC=temptable_MAC
    left join machine_pin on machinepin_pinnumber=temptable_pin and machineallocation_machineid=machinepin_machineid
    $filter
    and temptable_timetag>='".(time()-($minutestoshow*60))."'
    order by temptable_MAC,temptable_timetag asc,temptable_id asc";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $all_temptable_details=$sql->fetchall();
    $last=array();
    $j=array();
    
    foreach($all_temptable_details as $entry){
        if($entry['machinepin_nc']==1){
            $value_to_check=0;
        }else{
            $value_to_check=1;
        }
        if($entry['temptable_value']==$value_to_check){
            $table[$entry['temptable_pin']][$j[$entry['temptable_pin']]]['temptable_timetag']=$entry['temptable_timetag'];
        }else{
            $table[$entry['temptable_pin']][$j[$entry['temptable_pin']]]['temptable_timetag_finished']=$entry['temptable_timetag'];
            $j[$entry['temptable_pin']]=$j[$entry['temptable_pin']]+1; 
        }
       
        
        
    }
    //show($table);
    sort($listpin);
    
    ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <div id="chart_timeline_<?php echo $id;?>" ></div>
    <script>
        google.charts.load('current', {'packages':['timeline']});
        google.charts.setOnLoadCallback(drawCharttimeline<?php echo $id;?>);
        function drawCharttimeline<?php echo $id;?>() {
        var container_<?php echo $id;?> = document.getElementById('chart_timeline_<?php echo $id;?>');
        var chart_<?php echo $id;?> = new google.visualization.Timeline(container_<?php echo $id;?>);
        var data_<?php echo $id;?> = new google.visualization.DataTable();

            data_<?php echo $id;?>.addColumn({ type: 'string', id: 'Pin' });
            data_<?php echo $id;?>.addColumn({ type: 'date', id: 'Start' });
            data_<?php echo $id;?>.addColumn({ type: 'date', id: 'End' });
            
            data_<?php echo $id;?>.addRows([
                <?php
                foreach($listpin as $pintoshow){
                    foreach($table[$pintoshow['temptable_pin']] as $entry){
                        if(!empty($entry['temptable_timetag']) and !empty($entry['temptable_timetag_finished'])){
                            $year=date("Y",$entry['temptable_timetag']);
                            $month=date("m",$entry['temptable_timetag'])-1;
                            $day=date("d",$entry['temptable_timetag']);
                            $hours=date("G",$entry['temptable_timetag']);
                            $minutes=date("i",$entry['temptable_timetag']);
                            $secondes=date("s",$entry['temptable_timetag']);
                            echo"[ '".$pintoshow['name']."', new Date($year,$month,$day,$hours,$minutes,$secondes), ";
                            $year=date("Y",$entry['temptable_timetag_finished']);
                            $month=date("m",$entry['temptable_timetag_finished'])-1;
                            $day=date("d",$entry['temptable_timetag_finished']);
                            $hours=date("G",$entry['temptable_timetag_finished']);
                            $minutes=date("i",$entry['temptable_timetag_finished']);
                            $secondes=date("s",$entry['temptable_timetag_finished']);
                            echo"new Date($year,$month,$day,$hours,$minutes,$secondes) ],";
                        }
                        
                    }
                    
                    
                }
            ?>

            ]);
            
            
            

            var options = {
                legend:{
                    position: 'bottom'
                },
                hAxis: {
                    title: 'Date'
                },
                vAxis: {
                    title: 'Value',
                    maxValue:1,
                    maxValue:0,
                },
                colors: ['#a52714', '#097138'],
                crosshair: {
                    color: '#000',
                    trigger: 'selection'
                },
                title:'Last <?php echo $minutestoshow; ?> minutes',
                
                

            };

            chart_<?php echo $id;?>.draw(data_<?php echo $id;?>);
            }
    </script>


    <?php
}
function get_pin_name($db,$pinnumber,$mac){
    $query="SELECT  machinepin_pindescription
    FROM machine_pin
    left join machine on machine_id=machinepin_machineid
	left join machine_allocation on machine_id=machineallocation_machineid
    WHERE machineallocation_MAC='$mac' and machinepin_pinnumber='$pinnumber'
    ";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $result=$sql->fetch();
    if(empty($result)){
        $result['machinepin_pindescription']='Pin '.$pinnumber;
    }
    return $result['machinepin_pindescription'];
}

function opposite($binary){
    if($binary==0){
        return 1;
    }
    if($binary==1){
        return 0;
    }
}




?>