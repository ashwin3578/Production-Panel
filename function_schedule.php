<?php

function manage_post_schedule($db){
    if(!empty($_GET['debug'])){
        $_SESSION['temp']['debug']=$_GET['debug'];
    }
    
    if($_SESSION['temp']['debug']=='1'){
        show($_POST);
    }
    


    if(empty($_SESSION['temp']['date_productlist'])){
        $_SESSION['temp']['date_productlist']=date('Y-m-d',strtotime(date('Y-m-d',time()-3600*24).' next friday'));
    }
    if(empty($_POST['date_productlist'])){
        $_POST['date_productlist']=$_SESSION['temp']['date_productlist'];
    }else{
        $_SESSION['temp']['date_productlist']=$_POST['date_productlist'];
    }

    
    if(empty($_SESSION['temp']['date_filter'])){
        $_SESSION['temp']['date_filter']=date('Y-m-d',time());
    }
    if(empty($_POST['date_filter'])){
        $_POST['date_filter']=$_SESSION['temp']['date_filter'];
    }else{
        $_SESSION['temp']['date_filter']=$_POST['date_filter'];
    }




    if(!empty($_POST['workarea'])){
        $_SESSION['temp']['schedule_workarea']=$_POST['workarea'];
    }else{
        if(!empty($_SESSION['temp']['schedule_workarea'])){
            $_POST['workarea']=$_SESSION['temp']['schedule_workarea'];
        }
    }  
    if(!empty($_POST['shift'])){
        $_SESSION['temp']['schedule_shift']=$_POST['shift'];
    }else{
        if(!empty($_SESSION['temp']['schedule_shift'])){
            $_POST['shift']=$_SESSION['temp']['schedule_shift'];
        }
    } 
    if(!empty($_POST['sort_productlist'])){
        if($_SESSION['temp']['sort_productlist']==$_POST['sort_productlist']){
            if($_SESSION['temp']['sort_productlist_ascdesc']=='ASC'){
                $_SESSION['temp']['sort_productlist_ascdesc']='DESC';
            }else{
                $_SESSION['temp']['sort_productlist_ascdesc']='ASC';
            }
            
        }
        $_SESSION['temp']['sort_productlist']=$_POST['sort_productlist'];
    }else{
        if(!empty($_SESSION['temp']['sort_productlist'])){
            $_POST['sort_productlist']=$_SESSION['temp']['sort_productlist'];
        }
    } 

    if(!empty($_POST['sort'])){
        if($_SESSION['temp']['schedule_sort']==$_POST['sort']){
            if($_SESSION['temp']['schedule_sort_ascdesc']=='ASC'){
                $_SESSION['temp']['schedule_sort_ascdesc']='DESC';
            }else{
                $_SESSION['temp']['schedule_sort_ascdesc']='ASC';
            }
            
        }
        $_SESSION['temp']['schedule_sort']=$_POST['sort'];
    }else{
        if(!empty($_SESSION['temp']['schedule_sort'])){
            $_POST['sort']=$_SESSION['temp']['schedule_sort'];
        }
    } 
    if($_POST['action']=='add_allocation' or $_POST['action']=='remove_allocation' or $_POST['action']=='save_notes'){
        if($_POST['action']=='add_allocation'){
            add_allocation($db);
        }elseif($_POST['action']=='remove_allocation'){
            remove_allocation($db);
        }elseif($_POST['action']=='save_notes'){
            save_notes_schedule($db);
        }
        
        
        $alldata=get_list_operator_schedule($db,$_POST['date_filter']);
        //show($alldata);
        $operator=$alldata[$_POST['schedule_operatorcode']];
        $allallocation=get_all_schedule($db,$_POST['date_filter']);
        //$operator['name']=$_POST['schedule_operatorcode'];
        show_line_calendar_operator(
            $db,
            $operator,
            $_POST['rownumber'],
            $allallocation[$operator['name']]
        );
        
    }
    if($_POST['action']=='show_last7days_operator'){
        show_last7days_operator_details($db,$_POST['operator']);
    }

    if($_POST['action']=='show_navbar_top'){
        navbar_top_schedule($db);
    }
    if($_POST['action']=='show_all_schedule'){
        show_all_schedule($db);
    }
    if($_POST['action']=='show_print'){
        show_print($db);
    }
    if($_POST['action']=='show_details_MIS_Prodplan'){
        show_details_MIS_Prodplan($db);
    }
    
}

function general_view_schedule($db){
    echo'<div class="navbar_top">';
    navbar_top_schedule($db);
    echo'</div>';
    create_css_schedule ($db)
    
     
    ?>
    <div class="main_page">
        <?php show_all_schedule($db);?>
    </div>
    <?php
}
function show_all_schedule($db){?>
    
    <?php if($_SESSION['temp']['id']=='CorentinHillion'){show_window_note2();}else{show_window_note2();}?>
    <?php show_last7days_operator();?>
    <div class="row">
        <div class="col-md-8">
        <?php show_calendar_operator($db); ?>
        </div>
        <div class="col-md-4 all_MIS" style="position: sticky;top: 0;">
        <div class="no-print">
        <?php show_MIS_Prodplan($db); ?>
        </div></div>
    </div>
    
    <?php
}
function show_print($db){
    ?>
    <div class="row">
        <div class="col-md-8">
        <?php show_calendar_operator_print($db); ?>
        </div>
        
    </div>
    
    <?php
}

function show_calendar_operator($db){?>
    <br>
    <div class="row machine_header sticky_header">
        <div class="col-xs-5  col-sm-4 col-md-4 col-lg-3">
            <div class="col-lg-4 col-xs-6 ">Workarea <span class="glyphicon glyphicon-sort" onclick="sortby('<?php echo $_POST['date_filter']?>','workarea')"></span></div>
            <div class="col-lg-4 col-xs-6 ">Shift</div>
            <div class="col-lg-4 col-xs-12" >Operator <span class="glyphicon glyphicon-sort" onclick="sortby('<?php echo $_POST['date_filter']?>','operator')"></span></div>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">Available</div>
        <div class="col-xs-5 col-sm-6 col-md-7 col-lg-8">
            <div class="row">Order</div>
            <div class="row">
                <?php for($i=0;$i<24;$i++){ ?>
                    <div  id="hour<?php echo $i; ?>" class="hours">
                        <?php //echo $i; ?>
                    </div>
                    <?php
                }?>
            </div>
        </div>
            
    </div>
    <?php
    $alldata=get_list_operator_schedule($db,$_POST['date_filter']);
    $allallocation=get_all_schedule($db,$_POST['date_filter']);
    //show($alldata);
    $rownumber=0;
    $allocations=array();
    foreach($alldata as $operator){
                
        //show($allallocation[$operator['name']]);
        ?>
        <div class="row machine_row row_<?php echo $rownumber?> ">

        <?php show_line_calendar_operator($db,$operator,$rownumber,$allallocation[$operator['name']]);$rownumber++;?>
        </div>
        
         <?php
    }
    ?>
    <style>
        .mini-btn {
            font-size: 0.8vw;
            margin-top: 1px;
            margin-bottom: 1px;
            border: 0.25px solid #dfdede;
            border-radius: 1rem;
            text-align: center;
            padding: 2px;
            box-shadow: 5px 5px 10px rgb(0 0 0 / 10%);
        }
    </style>
    <script>
        brushsize=4;
        is_delete=0;
        brush_code='';
        var mouseX;
        var mouseY;
        $(document).mousemove( function(e) {
        mouseX = e.pageX; 
        mouseY = e.pageY;
        });  
        function show_last7days(e,operator){
            document.getElementById('window_last7').style.display = "block";
            var rect = e.getBoundingClientRect();
            document.getElementById('window_last7').style.top = mouseY ;
            document.getElementById('window_last7').style.left = mouseX ;
            var request =$.ajax({
                type:'POST',
                url:'schedule_ajax.php',
                data: {operator:operator,action:'show_last7days_operator'},
                success:function(html){
                    $('.window_last7').empty().append(html);
                }
            });
        }
        function hide_last7days(){
            document.getElementById('window_last7').style.display = "none";
        }
        function show_notes(e,date,operator,hour,brushsize,brush_code,rownumber,workarea,shift,notes){
            //alert(hours+operator+product+notes);
            //alert("Notes "+operator+" "+hours+":00");
            var rect = e.getBoundingClientRect();
            //alert(rect.top);
            document.getElementById('window_notes').style.display = "block";
            document.getElementById('window_notes').style.top = rect.top ;
            document.getElementById('window_notes').style.left = rect.left ;
            document.getElementById('window_header').innerText = "Notes : "+operator+" "+hour+":00";
            document.getElementById('notes').value=notes.replace(/<br\s*[\/]?>/gi, "\n");
            
            document.getElementById('date_filter').value=date;
            document.getElementById('schedule_operatorcode').value=operator;
            document.getElementById('schedule_hour_start').value=hour;
            document.getElementById('schedule_duration').value=brushsize;
            document.getElementById('schedule_productcode').value=brush_code;
            document.getElementById('tempworkarea').value=workarea;
            document.getElementById('tempshift').value=shift;
            document.getElementById('notes').value=notes.replace(/<br\s*[\/]?>/gi, "\n");
            document.getElementById('rownumber').value=rownumber;
        }
        function hide_notes(){
            
            document.getElementById('window_notes').style.display = "none";
        }
        function save_notes_v2(){
            var request =$.ajax({
                type:'POST',
                url:'schedule_ajax.php',
                data: {
                    date_filter:document.getElementById('date_filter').value,
                    schedule_operatorcode:document.getElementById('schedule_operatorcode').value,
                    schedule_hour_start:document.getElementById('schedule_hour_start').value,
                    schedule_duration:document.getElementById('schedule_duration').value,
                    schedule_productcode:document.getElementById('schedule_productcode').value,
                    tempworkarea:document.getElementById('tempworkarea').value,
                    tempshift:document.getElementById('tempshift').value,
                    schedule_notes:document.getElementById('notes').value,
                    rownumber:document.getElementById('rownumber').value,
                    action:'save_notes'
                    },
                success:function(html){
                    $('.row_'+document.getElementById('rownumber').value).empty().append(html);
                }
            });
            hide_notes();
       }



        function sortby(date,sort){
            var request =$.ajax({
                type:'POST',
                url:'schedule_ajax.php',
                data: {date_filter:date,action:'show_all_schedule',sort:sort},
                success:function(html){
                    $('.main_page').empty().append(html);
                }
            });
        }
        function update_BOMLIST(code){
            
            document.getElementById('QTY_'+code).innerText =Math.round(QTY[code],0);
            document.getElementById('Hours_'+code).innerText =Math.round(hours[code],0);
            document.getElementById('Allocated_'+code).innerText =Math.round(allocated[code],0);
            document.getElementById('Operator_'+code).innerText =Math.round(Operator[code],0);
        }
       function highlight(operator,hour,brushsize){
           //alert(operator+' '+hour);
           //document.getElementById(operator+hour).innerHTML += '1';
           if(brush_code!=''){
            for(let i = 0; i < brushsize; i++){
               thehour=hour+i;
               if(thehour<=23){
                document.getElementById(operator+thehour).classList.add("hooverpicked");
                //document.getElementById('hour'+thehour).classList.add("hooverpicked");
               }
                
            }
           }
           
       }
       function removeall(){
        var elems = document.querySelectorAll(".hooverpicked");

        [].forEach.call(elems, function(el) {
            el.classList.remove("hooverpicked");
        });
          
            
       }
       function save_notes(date,operator,hour,brushsize,brush_code,rownumber,workarea,shift){
            var request =$.ajax({
                type:'POST',
                url:'schedule_ajax.php',
                data: {
                    date_filter:date,
                    schedule_operatorcode:operator,
                    schedule_hour_start:hour,
                    schedule_duration:brushsize,
                    schedule_productcode:brush_code,
                    tempworkarea:workarea,
                    tempshift:shift,
                    schedule_notes:notes,
                    rownumber:rownumber,
                    action:'save_notes'
                    },
                success:function(html){
                    $('.row_'+rownumber).empty().append(html);
                }
            });
       }
       function allocate(date,operator,hour,brushsize,brush_code,rownumber,workarea,shift){
           
          if(brush_code!=''){
            var request =$.ajax({
                type:'POST',
                url:'schedule_ajax.php',
                data: {
                    date_filter:date,
                    schedule_operatorcode:operator,
                    schedule_hour_start:hour,
                    schedule_duration:brushsize,
                    schedule_productcode:brush_code,
                    tempworkarea:workarea,
                    tempshift:shift,
                    rownumber:rownumber,
                    action:'add_allocation'
                    },
                success:function(html){
                    $('.row_'+rownumber).empty().append(html);
                }
            });
          }
           
       }
       function remove(date,operator,hour,brushsize,brush_code,rownumber,workarea,shift){
           if(is_delete==1){
                var request =$.ajax({
                    type:'POST',
                    url:'schedule_ajax.php',
                    data: {
                        date_filter:date,
                        schedule_operatorcode:operator,
                        schedule_hour_start:hour,
                        schedule_duration:brushsize,
                        schedule_productcode:brush_code,
                        tempworkarea:workarea,
                        tempshift:shift,
                        rownumber:rownumber,
                        action:'remove_allocation'
                        },
                    success:function(html){
                        $('.row_'+rownumber).empty().append(html);
                    }
                });
           }
       } 
    </script>
    <?php    
}
function show_calendar_operator_print($db){?>
    <br>
    <div class="all_print">
        <div class="row machine_header sticky_header">
            <div class="col-xs-5  col-sm-4 col-md-4 col-lg-3">
                <div class="col-lg-4 col-xs-4 ">Workarea</div>
                <div class="col-lg-4 col-xs-4 ">Shift</div>
                <div class="col-lg-4 col-xs-4" >Operator</div>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">Available</div>
            <div class="col-xs-5 col-sm-6 col-md-7 col-lg-8"><?php echo date('D jS M Y',strtotime($_POST['date_filter']));?> - Job Schedule</div>
                
        </div>
        <?php
        $alldata=get_list_operator_schedule($db,$_POST['date_filter']);
        $allallocation=get_all_schedule($db,$_POST['date_filter']);
        //show($alldata);
        $rownumber=0;
        $allocations=array();
        foreach($alldata as $operator){
                    
            //show($allallocation[$operator['name']]);
            ?>
            <div class="row machine_row row_<?php echo $rownumber?> ">

            <?php show_line_calendar_operator_print($db,$operator,$rownumber,$allallocation[$operator['name']]);$rownumber++;?>
            </div>
            
            <?php
        }
        ?>
   </div>
   <style>
       .all_print{
           font-size:10px;
           padding:0px;
       }
   </style>
    
    <?php    
}
function show_line_calendar_operator($db,$operator,$rownumber,$allocations){
    
    //allocation[hours_start]=duration
    //show($operator);
    ?>
   
        <div class="col-xs-5  col-sm-4 col-md-4 col-lg-3">
            <div class="col-md-4 col-xs-6 mini-btn <?php echo $operator['workarea']?>"><?php echo $operator['workarea']?></div>
            <div class="col-md-4 col-xs-6 mini-btn <?php echo $operator['shift']?>"><?php echo $operator['shift']?></div>
            <div class="col-md-4 col-xs-12 mini-btn <?php echo $operator['contract']?>" onmouseout="hide_last7days();" onmouseover="show_last7days(this,'<?php echo $operator['name']?>');" ><?php echo $operator['name']?></div>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-1 col-lg-1"><?php echo round($operator['ot_before'],0)?> / <?php echo round($operator['hours_available'],0)?> / <?php echo round($operator['ot_after'],0)?></div>
        <div class="col-xs-5 col-sm-6 col-md-7 col-lg-8">
            <?php for($i=0;$i<24;$i++){ 
                if(empty($allocations[$i])){?>
                <div 
                id="<?php echo $operator['name'].$i?>" 
                class="hours tobepicked" 
                onmouseout="removeall()" 
                onmouseover="highlight('<?php echo $operator['name']?>',<?php echo $i?>,brushsize)"
                onclick="allocate(
                    '<?php echo $_POST['date_filter']?>',
                    '<?php echo $operator['name']?>',
                    <?php echo $i?>,
                    brushsize,
                    brush_code,
                    <?php echo $rownumber?>,
                    '<?php echo $operator['workarea']?>',
                    '<?php echo $operator['shift']?>'
                    )"
                >
                    <br>
                </div>
                <?php
                }else{?>
                    <div 
                id="" 
                class="hours picked <?php echo $allocations[$i]['workarea']?>" style="width:<?php echo($allocations[$i]['duration']*4)?>% ;"
                onclick="remove(
                    '<?php echo $_POST['date_filter']?>',
                    '<?php echo $operator['name']?>',
                    <?php echo $i?>,
                    '<?php echo $allocations[$i]['duration']?>',
                    '<?php echo $allocations[$i]['productcode']?>',
                    <?php echo $rownumber?>,
                    '<?php echo $operator['workarea']?>',
                    '<?php echo $operator['shift']?>'
                    )"
                oncontextmenu="
                show_notes(this,
                '<?php echo $_POST['date_filter']?>',
                '<?php echo $operator['name']?>',
                <?php echo $i?>,
                '<?php echo $allocations[$i]['duration']?>',
                '<?php echo $allocations[$i]['productcode']?>',
                <?php echo $rownumber?>,
                '<?php echo $operator['workarea']?>',
                '<?php echo $operator['shift']?>',
                '<?php echo$allocations[$i]['notes']?>');
                return false;"
                >   
                <!--
                oncontextmenu="
                notes=prompt('add notes:','<?php echo$allocations[$i]['notes']?>');
                save_notes('<?php echo $_POST['date_filter']?>',
                '<?php echo $operator['name']?>',
                    <?php echo $i?>,
                    '<?php echo $allocations[$i]['duration']?>',
                    '<?php echo $allocations[$i]['productcode']?>',
                    <?php echo $rownumber?>,
                    '<?php echo $operator['workarea']?>',
                    '<?php echo $operator['shift']?>');
                return false;"
                -->

                    <?php 
                    if($allocations[$i]['productcode']=='blank'){
                        $caption='';
                    }else{
                        $caption=$allocations[$i]['productcode'];
                    }
                     
                     if($allocations[$i]['productcode']=='blank' and empty($allocations[$i]['notes'])){
                         $caption='<br>';
                     }
                     if(!empty($allocations[$i]['notes'])){
                        if($allocations[$i]['productcode']=='blank'){
                            $caption=$caption.'';
                        }else{
                            $caption=$caption.'<br>';
                        }
                        $caption=$caption.$allocations[$i]['notes'];
                     }
                    if($allocations[$i]['duration']<=2){
                        info_button($rownumber,$caption,";height: auto;top: 1rem;width: 15rem;left: -15rem;text-align:center");
                    }else{
                        echo $caption;
                    }
                   
                    ?>
                    
                </div>
                <?php
                $i=$i+$allocations[$i]['duration']-1;
                }
            }?>
        </div>
            
    

    <?php
}
function show_line_calendar_operator_print($db,$operator,$rownumber,$allocations){
    ?>
   
        <div class="col-xs-5  col-sm-4 col-md-4 col-lg-3">
            <div class="col-md-4 col-xs-4 mini-btn <?php echo $operator['workarea']?>"><?php echo $operator['workarea']?></div>
            <div class="col-md-4 col-xs-4 mini-btn <?php echo $operator['shift']?>"><?php echo $operator['shift']?></div>
            <div class="col-md-4 col-xs-4 mini-btn <?php echo $operator['contract']?>" onmouseout="hide_last7days();" onmouseover="show_last7days(this,'<?php echo $operator['name']?>');" ><?php echo $operator['name']?></div>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-1 col-lg-1"><?php echo round($operator['ot_before'],0)?> / <?php echo round($operator['hours_available'],0)?> / <?php echo round($operator['ot_after'],0)?></div>
        <div class="col-xs-5 col-sm-6 col-md-7 col-lg-8">
            <?php for($i=0;$i<24;$i++){ 
                if(empty($allocations[$i])){?>
                
                <?php
                }else{?>
                    <div 
                id="" 
                class="hours picked <?php echo $allocations[$i]['workarea']?>" style="width:100% ;"
                
                >   
                <!--
                oncontextmenu="
                notes=prompt('add notes:','<?php echo$allocations[$i]['notes']?>');
                save_notes('<?php echo $_POST['date_filter']?>',
                '<?php echo $operator['name']?>',
                    <?php echo $i?>,
                    '<?php echo $allocations[$i]['duration']?>',
                    '<?php echo $allocations[$i]['productcode']?>',
                    <?php echo $rownumber?>,
                    '<?php echo $operator['workarea']?>',
                    '<?php echo $operator['shift']?>');
                return false;"
                -->

                    <?php 
                    if($allocations[$i]['productcode']=='blank'){
                        $caption='';
                    }else{
                        $caption=$allocations[$i]['productcode'];
                    }
                     
                     if($allocations[$i]['productcode']=='blank' and empty($allocations[$i]['notes'])){
                         $caption='<br>';
                     }
                     if(!empty($allocations[$i]['notes'])){
                        if($allocations[$i]['productcode']=='blank'){
                            $caption=$caption.'';
                        }else{
                            $caption=$caption.'<br>';
                        }
                        $caption=$caption.$allocations[$i]['notes'];
                     }
                    if($allocations[$i]['duration']<=2){
                        info_button($rownumber,$caption,";height: auto;top: 1rem;width: 15rem;left: -15rem;text-align:center");
                    }else{
                        echo $caption;
                    }
                   
                    ?>
                    
                </div>
                <?php
                $i=$i+$allocations[$i]['duration']-1;
                }
            }?>
        </div>
            
    

    <?php
}
function show_window_note(){
    ?>
    <div id="window_notes" class="window_notes">
        <div id="window_header" class="window_header" >Manage Notes</div>
        <div class="row">
            <textarea rows = "2"  class="form-control" name="notes" id="notes" placeholder="notes to add"></textarea><br>
        </div>
        <div class="row">
            <?php button_notes('QTY : ')?>
            <?php button_notes('PRINTING ')?>
            <?php button_notes('PLEASE REFER TO PRINTING SCHEDULE ')?>
        </div><br>
        <div class="row">
            <?php button_notes('STAGE 1 ONLY ')?>
            <?php button_notes('STAGE 2 ONLY ')?>
            <?php button_notes('STAGE 3 ONLY ')?>
            <?php button_notes('2ND & 3RD STAGE ')?>
            <?php button_notes('3RD STAGE & PACK ')?>
            <?php button_notes('TRAINING REQUIRED ')?>
        </div><br>
        <div class="row">            
            
            <?php button_notes('HEAT SHRINK CRIMPED CABLES ')?>
            <?php button_notes('BLACK INSERTS ')?>
            <?php button_notes('PACKING ')?>
            <?php button_notes('BAGGING ')?>
            <?php button_notes('BOLTING ')?>
            <?php button_notes('BOLTING AND PACKING ')?>
            <?php button_notes('CAPPING ')?>
            <?php button_notes('CABLE CRIMPING ')?>
            <?php button_notes('CABLE INSERTION ')?>
            <?php button_notes('CABLE STRIPPING ')?>
            <?php button_notes('ENGRAVING ')?>
            <?php button_notes('WELDING ')?>
            <?php button_notes('GAUGING ')?>
            <?php button_notes('PRESSING ID PLATES ON TO FUSE CARRIES ')?>
            <?php button_notes('BLACK PLATES ')?>
            <?php button_notes('BLUE PLATES ')?>
            <?php button_notes('WHITE PLATES ')?>
            <?php button_notes('RED PLATES ')?>
            <?php button_notes('YELLOW PLATES ')?>
        </div>
        
        <br>
        <div class="row">
            <div class="col-xs-6">
                <div class=" btn btn-primary" onclick="save_notes_v2();">Save Note</div>
            </div>
            <div class="col-xs-6">
                <div class=" btn btn-primary" onclick="clear_notes();save_notes_v2();">Clear</div>
            </div>
        </div>
        <div class="window_closer"><input id="button" type="button" value="X" onclick="hide_notes()"></div>
        <input type="hidden" id="date_filter" value="">
        <input type="hidden" id="schedule_operatorcode" value="">
        <input type="hidden" id="schedule_hour_start" value="">
        <input type="hidden" id="schedule_duration" value="">
        <input type="hidden" id="schedule_productcode" value="">
        <input type="hidden" id="tempworkarea" value="">
        <input type="hidden" id="tempshift" value="">
        <input type="hidden" id="rownumber" value="">


    </div>

    <script>
        function append_notes(word){
            document.getElementById('notes').value=document.getElementById('notes').value+ word  ; 
            document.getElementById("notes").focus();
        }
        function clear_notes(word){
            document.getElementById('notes').value=''  ; 
            document.getElementById("notes").focus();
        }
        
    </script>
        
    <style>
        .window_closer{
            position:absolute; top:4%; right:4%;
            line-height: 12px;
            width: 18px;
            font-size: 8pt;
            font-family: tahoma;
            margin-top: 1px;
            margin-right: 2px;
        }
        .window_notes{
            position: absolute;
            background: white;
            width:700;
            top:0px;
            color: black;
            margin: 2%;
            float: left;
            border: 1px solid black;
            border-radius: 3rem;
            text-align: center;
            padding: 1.25rem;
            z-index: 200;
            display:none;
        }
        .window_header{
            border-radius: 5px;
            background-color: rgba(0,0,0,.03);
            border-bottom: 1px solid rgba(0,0,0,.125);
            min-height: 30px;
            font-size: 20px;
        }
    </style>
    <?php
}
function show_window_note2(){
    ?>
    <div id="window_notes" class="window_notes">
        <div id="window_header" class="window_header" >Manage Notes</div>
        <div class="row">
            <textarea rows = "2"  class="form-control" name="notes" id="notes" placeholder="notes to add"></textarea><br>
        </div>
        <?php
        $allbutton['General'][]='QTY : ';
        $allbutton['General'][]='PRINTING ';
        $allbutton['General'][]='TRAINING REQUIRED ';
        $allbutton['General'][]='REFER TO PRINTING SCHEDULE ';
        $allbutton['General'][]='CONTINUE IN OVERTIME ';
        $allbutton['General'][]='INCLUDING OVERTIME ';
        $allbutton['General'][]='PRIORITY ';
        $allbutton['General'][]='AFTER PRINTING ';
        
        // $allbutton['Stage'][]='STAGE 1 ONLY ';
        // $allbutton['Stage'][]='STAGE 2 ONLY ';
        // $allbutton['Stage'][]='STAGE 3 ONLY ';
        // $allbutton['Stage'][]='2ND & 3RD STAGE ';
        // $allbutton['Stage'][]='3RD STAGE & PACK ';

        $allbutton['Stage'][]='STAGE 1 ';
        $allbutton['Stage'][]='STAGE 2 ';
        $allbutton['Stage'][]='STAGE 3 ';
        $allbutton['Stage'][]='ONLY ';
        $allbutton['Stage'][]='& ';
        $allbutton['Stage'][]='PACK ';

        $allbutton['Process'][]='HEAT SHRINK CRIMPED CABLES ';
        $allbutton['Process'][]='PACKING ';
        $allbutton['Process'][]='BAGGING ';
        $allbutton['Process'][]='BOLTING ';
        $allbutton['Process'][]='BOLTING AND PACKING ';
        $allbutton['Process'][]='CAPPING ';
        $allbutton['Process'][]='CABLE CRIMPING ';
        $allbutton['Process'][]='CABLE INSERTION ';
        $allbutton['Process'][]='CABLE STRIPPING ';
        $allbutton['Process'][]='ENGRAVING ';
        $allbutton['Process'][]='GREASING ';
        $allbutton['Process'][]='WELDING ';
        $allbutton['Process'][]='GAUGING ';
        $allbutton['Process'][]='PRESSING ID PLATES ON TO FUSE CARRIES ';
        $allbutton['Process'][]='USE THE MACHINE TO DO BOLTING ';
        $allbutton['Process'][]='NEEDS TO BE CLIPPED ';
        $allbutton['Process'][]='NO CLIPS REQUIRED ';
         

        $allbutton['Part'][]='PLATES ';
        $allbutton['Part'][]='BLACK INSERTS ';
        $allbutton['Part'][]='SEAL CAP 1-0 ';
        $allbutton['Part'][]='SEAL CAP 250/350 ';
        $allbutton['Part'][]='SEAL CAP 250/350-VO ';
        $allbutton['Part'][]='SEAL CAP 4-0 ';
        $allbutton['Part'][]='SEAL CAP 4-0-VO ';
         
        
        $allbutton['Color'][]='BLACK ';
        $allbutton['Color'][]='BLUE ';
        $allbutton['Color'][]='WHITE ';
        $allbutton['Color'][]='RED ';
        $allbutton['Color'][]='YELLOW ';

        $allbutton['Other'][]='ROTATE ';
        $allbutton['Other'][]='MAXIMUM ';
        $allbutton['Other'][]='HRS ';
        $allbutton['Other'][]='ANY ';
        $allbutton['Other'][]='PROCESS ';
        $allbutton['Other'][]='GREASING ';
        $allbutton['Other'][]='ON ';
        $allbutton['Other'][]='BOOK IN AT THE END OF THE SHIFT ';
        
        $allbutton['Number'][]='NO. ';
        $allbutton['Number'][]='1 ';
        $allbutton['Number'][]='2 ';
        $allbutton['Number'][]='3 ';
        $allbutton['Number'][]='4 ';
        $allbutton['Number'][]='5 ';
        $allbutton['Number'][]='6 ';
        $allbutton['Number'][]='7 ';
        $allbutton['Number'][]='8 ';
        $allbutton['Number'][]='9 ';
        
        
        
        //show($allbutton);
        foreach($allbutton as $group){?>
            <div class="row btn_grp">
                <?php foreach($group as $button){button_notes2($button);} ?>
            </div>
        <?php
        }
        ?>
        
        
        <br>
        <div class="row">
            <div class="col-xs-6">
                <div class=" btn btn-primary" onclick="save_notes_v2();">Save Note</div>
            </div>
            <div class="col-xs-6">
                <div class=" btn btn-primary" onclick="clear_notes();save_notes_v2();">Clear</div>
            </div>
        </div>
        <div class="window_closer"><input id="button" type="button" value="X" onclick="hide_notes()"></div>
        <input type="hidden" id="date_filter" value="">
        <input type="hidden" id="schedule_operatorcode" value="">
        <input type="hidden" id="schedule_hour_start" value="">
        <input type="hidden" id="schedule_duration" value="">
        <input type="hidden" id="schedule_productcode" value="">
        <input type="hidden" id="tempworkarea" value="">
        <input type="hidden" id="tempshift" value="">
        <input type="hidden" id="rownumber" value="">


    </div>

    <script>
        function append_notes(word){
            document.getElementById('notes').value=document.getElementById('notes').value+ word  ; 
            document.getElementById("notes").focus();
        }
        function clear_notes(word){
            document.getElementById('notes').value=''  ; 
            document.getElementById("notes").focus();
        }
        
    </script>
        
    <style>
        .btn_grp{
            padding: 10px;
            border: 1px solid #b6b5b5;
            border-radius: 5px;
            
        }
        .button_notes{
            background: transparent;
            font-size: 14px;
            /*border-color: #c2c3c4;*/
            /*border-style: solid;*/
            /*border-width: 2px;*/
            border-radius: 5px;
            padding: 3px 3px;
            text-transform: uppercase;
            transition: all 0.2s linear;
            display:inline-block;
            margin-left: 10px;
            box-shadow: 5px 5px 10px rgb(0 0 0 / 10%);
        }
        .button_notes:hover {
            background: #d0dae0;
            border-color: #d0dae0;
            
            transition: all 0.2s linear;
        }
        .window_closer{
            position:absolute; top:4%; right:4%;
            line-height: 12px;
            width: 18px;
            font-size: 8pt;
            font-family: tahoma;
            margin-top: 1px;
            margin-right: 2px;
        }
        .window_notes{
            position: absolute;
            background: white;
            width:700;
            top:0px;
            color: black;
            margin: 2%;
            float: left;
            border: 1px solid black;
            border-radius: 3rem;
            text-align: center;
            padding: 1.25rem;
            z-index: 200;
            display:none;
        }
        .window_header{
            border-radius: 5px;
            background-color: rgba(0,0,0,.03);
            border-bottom: 1px solid rgba(0,0,0,.125);
            min-height: 30px;
            font-size: 20px;
        }
    </style>
    <?php
}
function button_notes($word,$col='col-xs-4'){?>
    <div class="<?php echo$col?>" onclick="append_notes('<?php echo $word?>')"><div class="btn btn-default " style="padding:1px"><?php echo $word?></div></div>
    <?php
}
function button_notes2($word){?>
    <div class="button_notes" onclick="append_notes('<?php echo $word?>')"><?php echo $word?></div>
    <?php
}
function show_last7days_operator(){
    ?>
    <div id="window_last7" class="window_last7">
        
       

    </div>

        
    <style>
        .window_last7{
            position: absolute;
            background: white;
            width:500;
            top:0px;
            color: black;
            margin: 2%;
            float: left;
            border: 1px solid black;
            border-radius: 3rem;
            text-align: center;
            padding: 1.25rem;
            z-index: 200;
            display:none;
        }
        .window_last7_header{
            border-radius: 5px;
            background-color: rgba(0,0,0,.03);
            border-bottom: 1px solid rgba(0,0,0,.125);
        }
    </style>
    <?php
}
function show_last7days_operator_details($db,$operator){
    ?>
    <div id="window_last7_header" class="window_last7_header">Last 7 days for <?php echo$operator?></div>
        <div class="row">
            <?php foreach(get_all_scan_last_7days($db,$operator)as $product){?>
                <div class="row">
                    <div class="col-xs-8"><?php echo$product['Code'];?></div>
                    <div class="col-xs-4"><?php echo round($product['HOURS'],1);?> h</div>
                </div>
            <?php } ?>
        </div>
    <?php
}

function show_MIS_Prodplan($db){
    
    
    if(!empty($_SESSION['temp']['role_schedule_admin'])){
    ?>
     
    <div class="row ">
        
        <div class="col-xs-5 ">
            <form method="post" id="load_date_productlist">
                <input type="date" class='form-control' style="width: 100%;" oninput="document.getElementById('load_date_productlist').submit()"name="date_productlist" value="<?php echo $_POST['date_productlist']?>">
            </form>
        </div>
        <div class="col-xs-2 "><input id="brushsizeinput" type="number" class='form-control' style="width: 100%;" oninput="brushsize=this.value" value="8" max="24" min="1"></div>
        <div class="col-xs-2 "><button id="button_delete"class='btn btn-default glyphicon glyphicon-trash' onclick="brush_delete()"></button></div>
        <div class="col-xs-2 "><form method="post"><button class='btn btn-default glyphicon glyphicon-refresh'></button></form></div>
        <div class="col-xs-1 ">
            <?php $caption='1-Choose the date ManufactureBefore<br>
                    2-Choose the Hours<br> 
                    3-Choose the Product Code<br> 
                    4-Click on the Calendar to Allocated it to an Operator<br>
                    <br>
                    Right Click to Add/Modify Notes<br>
                    ';
                    info_button('info',$caption,',height:auto')?>
            
        </div>
        
        
        
    </div>
    <br>
    <?php }else{?><br><br> <?php } ?>
    <div class="details_MIS_Prodplan ">
    <?php show_details_MIS_Prodplan($db);?>
    </div>
    <?php
}
function show_details_MIS_Prodplan($db){
    ?>
    <script>
        var QTY = {};
        var hours = {};
        var allocated = {};
        var Operator = {};
        var parts_hours = {};
        function sortby_productlist(date,sort){
            var request =$.ajax({
                type:'POST',
                url:'schedule_ajax.php',
                data: {date_filter:date,action:'show_details_MIS_Prodplan',sort_productlist:sort},
                success:function(html){
                    $('.details_MIS_Prodplan').empty().append(html);
                }
            });
        }
    </script>
    <?php
    
    
    
    $allproduct=(get_list_process_schedule($db,$_POST['date_filter']));
    $lastcode='';
    $lastworkarea='';
    foreach($allproduct as $product){?>
        <?php if($product['Code']<>$lastcode){?>
            <?php if($product['WorkArea']<>$lastworkarea){?>
                <div class=" ">
                    <div class="row <?php echo $product['WorkArea'];?>">
                    <div class="col-xs-4" onclick="sortby_productlist(<?php echo $_POST['date_filter']?>,'Code ')"><?php echo $product['WorkArea'];?><span class="glyphicon glyphicon-sort"></span></div> 
                    <div class="col-xs-2" >Qty</div>
                    <div class="col-xs-2" onclick="sortby_productlist(<?php echo $_POST['date_filter']?>,'HOURS_Remaining2 ')">Remain.<span class="glyphicon glyphicon-sort" ></span></div>
                    <div class="col-xs-2">Allocat.</div>
                    <div class="col-xs-2"><span class="glyphicon glyphicon-user"></span></div>
                        
                    </div>   
                    <div 
                    id="blank"
                    class="row <?php echo $product['WorkArea'];?> product_to_assign"
                    onclick="select_product('blank');brush_code='blank';"
                    >
                        <div class="col-xs-4">blank</div> 
                        <div class="col-xs-2" id="QTY_blank"></div>
                        <div class="col-xs-2" id="Hours_blank"></div>
                        <div class="col-xs-2" id="Allocated_blank"></div>
                        <div class="col-xs-2" id="Operator_blank" onclick='alert(QTY[this.id])'><?php echo $product['count_operator']?></div>
                        
                        
                    
                    
                    </div> 
                    <script>
                        
                        
                        QTY['blank']=<?php echo ($product['QuantityOrdered']-$product['QTY_MADE'])?>;
                        hours['blank']=<?php echo (($product['QuantityOrdered']-$product['QTY_MADE'])*$product['BOM_TIME']/60)?>;
                        allocated['blank']=<?php echo ($product['hours_allocated']+0)?>;
                        Operator['blank']=<?php echo ($product['count_operator']+0)?>;
                        parts_hours['blank']=<?php echo round(60/$product['BOM_TIME']+0,0)?>;
                        
                    </script>
            <?php } ?>
            
            <div 
            id="<?php echo $product['Code']?>"
            class="row <?php echo $product['WorkArea'];?> product_to_assign"
            onclick="select_product('<?php echo $product['Code']?>');brush_code='<?php echo $product['Code']?>';"
            >
                <div class="col-xs-4"><?php echo $product['Code']?></div> 
                <div class="col-xs-2" id="QTY_<?php echo $product['Code']?>"><?php echo number_format($product['QuantityOrdered']-$product['QTY_MADE'])?></div>
                <div class="col-xs-2" id="Hours_<?php echo $product['Code']?>"><?php echo number_format(($product['QuantityOrdered']-$product['QTY_MADE'])*$product['BOM_TIME']/60-$product['hours_allocated'])?></div>
                <div class="col-xs-2" id="Allocated_<?php echo $product['Code']?>"><?php echo number_format($product['hours_allocated'])?></div>
                <div class="col-xs-2" id="Operator_<?php echo $product['Code']?>" onclick='alert(QTY[this.id])'><?php echo $product['count_operator']?></div>
                
                
            
            
            </div> 
            <script>
                
                 
                QTY['<?php echo $product['Code']?>']=<?php echo ($product['QuantityOrdered']-$product['QTY_MADE'])?>;
                hours['<?php echo $product['Code']?>']=<?php echo (($product['QuantityOrdered']-$product['QTY_MADE'])*$product['BOM_TIME']/60)?>;
                allocated['<?php echo $product['Code']?>']=<?php echo ($product['hours_allocated']+0)?>;
                Operator['<?php echo $product['Code']?>']=<?php echo ($product['count_operator']+0)?>;
                parts_hours['<?php echo $product['Code']?>']=<?php echo round(60/$product['BOM_TIME']+0,0)?>;
                
            </script>

            
            
        
        <?php
        $lastcode=$product['Code'];
       
        $lastworkarea=$product['WorkArea'];
        
        if($product['WorkArea']<>$lastworkarea){?>
            </div>
        <?php } 
        
        }
    }?>
    <script>
        function brush_delete(){
            if(is_delete==1){
                is_delete=0;
                document.getElementById("button_delete").classList.remove("button_activated");
                document.getElementById("button_delete").blur();
            }else{
                is_delete=1;
                document.getElementById("button_delete").classList.add("button_activated");
                brush_code='';
                var elems = document.querySelectorAll(".product_to_assign_selected");

                [].forEach.call(elems, function(el) {
                    el.classList.remove("product_to_assign_selected");
                });
            }
            
        }
        function select_product(code){
            is_delete=0;
            document.getElementById("button_delete").classList.remove("button_activated");
            document.getElementById("button_delete").blur();
            var elems = document.querySelectorAll(".product_to_assign_selected");

            [].forEach.call(elems, function(el) {
                el.classList.remove("product_to_assign_selected");
            });
            document.getElementById(code).classList.add("product_to_assign_selected");
            
        }
    </script>
    <?php
}
function navbar_top_schedule($db,$option=''){
    
    
    //$_POST['date_filter']=date('Y-m-d',time());
    //show($_POST['date_filter']);
    $timetag=strtotime($_POST['date_filter']);
    $datetoshow=date('D jS M Y',$timetag);
    
    ?>
    <script>
       
        function loaddate(date){
            var request =$.ajax({
                type:'POST',
                url:'schedule_ajax.php',
                data: {date_filter:date,action:'show_all_schedule'},
                success:function(html){
                    $('.main_page').empty().append(html);
                }
            });
            var request =$.ajax({
                type:'POST',
                url:'schedule_ajax.php',
                data: {date_filter:date,action:'show_navbar_top',option:'<?php echo$option;?>'},
                success:function(html){
                    $('.navbar_top').empty().append(html);
                }
            });
            
        }

        
        
    </script>



    <div class="row no-print"style="text-align:center">
       
        <div  class="col-xs-3 col-sm-3 col-md-1 col-lg-1">
            <span class="glyphicon glyphicon-step-backward" onclick="loaddate('<?php echo date('Y-m-d',strtotime($_POST['date_filter'])-3600*24);?>');"></span>
        </div>
        <div  class="col-xs-6 col-sm-6 col-md-2 col-lg-2" >
            <?php echo $datetoshow;?>
        </div>
        <div  class="col-xs-3 col-sm-3 col-md-1 col-lg-1">
        <span class="glyphicon glyphicon-step-forward" onclick="loaddate('<?php echo date('Y-m-d',strtotime($_POST['date_filter'])+3600*24);?>');"></span>
        </div>
        <div  class="col-xs-2 col-md-2 col-lg-1">
            <select class='btn btn-default ' id="workarea" onchange="loadworkarea();">
                <option>All WorkArea</option>   
                <!--<option disabled></option>  -->
                <option <?php if($_POST['workarea']=='Manufacturing'){echo'selected';}?>>Manufacturing</option>
                <?php foreach(get_list_workarea($db) as $workarea){?>
                    <option <?php if($_POST['workarea']==$workarea['allocationwork_code']){echo'selected';}?>><?php echo$workarea['allocationwork_code']?></option>
                <?php
                }?>
                
            </select>
        </div>
        
        <div  class="col-xs-2 col-md-2 col-lg-1">
            <select class='btn btn-default ' id="shift" onchange="loadshift();">
                <option>All Shift</option>    
                <option <?php if($_POST['shift']=='Morning'){echo'selected';}?>>Morning</option>
                <option <?php if($_POST['shift']=='Afternoon'){echo'selected';}?>>Afternoon</option>
            </select>
        </div>
        <a href="ot.php"><div  class="col-xs-2 col-md-2 col-lg-1 "><div  class="btn btn-default">Overtimes</div></div></a>
        <div  class="col-xs-2 col-md-1 col-lg-1 ">
            <div  class="col-xs-6" onclick="show_print();">
                <div  class="btn btn-default"><span class="glyphicon glyphicon-print"></span></div>
            </div>
        </div>
        
    </div>
    <style>
        .btn{
            width:100%;
            white-space: initial;
        }
    </style>
    <script>
        function loadworkarea(){
            date='<?php echo $_POST['date_filter']?>';
            var request =$.ajax({
                type:'POST',
                url:'schedule_ajax.php',
                data: {date_filter:date,action:'show_all_schedule',workarea:document.getElementById("workarea").value},
                success:function(html){
                    $('.main_page').empty().append(html);
                }
            });
            var request =$.ajax({
                type:'POST',
                url:'schedule_ajax.php',
                data: {date_filter:date,action:'show_navbar_top',workarea:document.getElementById("workarea").value},
                success:function(html){
                    $('.navbar_top').empty().append(html);
                }
            });
        }
        function loadshift(){
            date='<?php echo $_POST['date_filter']?>';
            var request =$.ajax({
                type:'POST',
                url:'schedule_ajax.php',
                data: {date_filter:date,action:'show_all_schedule',shift:document.getElementById("shift").value},
                success:function(html){
                    $('.main_page').empty().append(html);
                }
            });
            var request =$.ajax({
                type:'POST',
                url:'schedule_ajax.php',
                data: {date_filter:date,action:'show_navbar_top',shift:document.getElementById("shift").value},
                success:function(html){
                    $('.navbar_top').empty().append(html);
                }
            });
        }
        function show_print(){
            date='<?php echo $_POST['date_filter']?>';
            var request =$.ajax({
                type:'POST',
                url:'schedule_ajax.php',
                data: {date_filter:date,action:'show_print'},
                success:function(html){
                    $('.main_page').empty().append(html);
                }
            });
            var request =$.ajax({
                type:'POST',
                url:'schedule_ajax.php',
                data: {date_filter:date,action:'show_navbar_top',shift:document.getElementById("shift").value},
                success:function(html){
                    $('.navbar_top').empty().append(html);
                }
            });
        }
    </script>
    
    
    

    
    
    <?php 
}



function get_all_scan_last_7days($db,$operator){
    $thedate=date('Y-m-d',strtotime(date('Y-m-d',time()).' -7days'));
    $query="SELECT  operator_fullname,Code
    ,SUM([total_hours]) AS HOURS
         
      FROM [barcode].[dbo].[MIS_Operator_hours_scanned]
      LEFT JOIN MIS_List ON ManufactureIssueNumber=scan_jobnumber
     WHERE SCAN_DATE >'$thedate'  and operator_fullname='$operator' and total_hours>0.15
     GROUP BY operator_fullname,Code
     ORDER BY HOURS DESC
	  
	";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	//show($row);
	
	return $row;
}
function get_list_operator_schedule($db,$date){
    $filter='';
    if(!empty($_POST['workarea']) and $_POST['workarea']<>'All WorkArea'){
        
        if($_POST['workarea']=='Manufacturing'){
            $filter=$filter."AND allocationwork_code<>'Assembly'";
        }else{
            $filter=$filter."AND allocationwork_code='".$_POST['workarea']."'";
        }
    }

    if(!empty($_POST['shift']) and $_POST['shift']<>'All Shift'){
        $filter=$filter."AND allocationshift_code='".$_POST['shift']."'";
    }
    if($_SESSION['temp']['schedule_sort']=='workarea'){ 
        if($_SESSION['temp']['schedule_sort_ascdesc']=='ASC'){
            $sort='allocationwork_code,allocationshift_code DESC,allocationwork_operatorid';
        }else{
            $sort='allocationwork_code DESC,allocationshift_code DESC,allocationwork_operatorid';
        }
    }elseif($_SESSION['temp']['schedule_sort']=='operator'){
        if($_SESSION['temp']['schedule_sort_ascdesc']=='ASC'){
            $sort='allocationwork_operatorid,allocationwork_code,allocationshift_code DESC';
        }else{
            $sort='allocationwork_operatorid DESC,allocationwork_code,allocationshift_code DESC';
        }
    }else{
        $sort='allocationwork_code,allocationshift_code DESC,allocationwork_operatorid';
    }

    

    $query="SET DATEFIRST 1;
    SELECT (baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100) as hours_available,* FROM dbo.allocationwork
    left join allocation on  allocation_date=allocationwork_date and allocation_operatorid=allocationwork_operatorid
    left join allocationshift on  allocationshift_date=allocationwork_date and allocationshift_operatorid=allocationwork_operatorid
    left join allocationcontract on  allocationcontract_date=allocationwork_date and allocationcontract_operatorid=allocationwork_operatorid
    left join allocationjobdone on  allocationjobdone_date=allocationwork_date and allocationjobdone_operatorid=allocationwork_operatorid
    left join operator on operator_fullname=allocationwork_operatorid
    left join baseallocation on  baseallocation_code=allocation_code
    left join baseallocationcontract on  baseallocationcontract_code=allocationcontract_code 
    left join baseallocationwork on  baseallocationwork_code=allocationwork_code  
    left join ot on  ot_operatorid=allocationwork_operatorid and ot_date=allocationwork_date
    WHERE allocationwork_date='".$date."' ".$filter." 
    and allocationshift_code <> 'Null' and operator_active=1
    AND ((DATEPART(DW,allocationcontract_date)<>6 and DATEPART(DW,allocationcontract_date)<>7 
        ) or((DATEPART(DW,allocationcontract_date)=6 or DATEPART(DW,allocationcontract_date)=7)and baseallocation_working<100 ))
    AND (baseallocationcontract_hours*baseallocationwork_working/100* (100-isnull(baseallocation_working,0))/100)>0
    order by $sort 
    ";
    
    //show($query);
    $sql = $db->prepare($query); 
    $sql->execute();
    //show(nbr_of_line);
    $rowtemp=$sql->fetchall();
    //$row['Total']=array();
   
    foreach($rowtemp as $line){
        //$row[$line[$table.'_date']][$line[$table."_operatorid"]]=$line[$table."_code"];
        
        if($line["allocationwork_working"]<100){
            //$row['Total'][$line["allocationwork_code"]]['number']++;
            //$row['Total'][$line["allocationwork_code"]]['name']=$line["allocationwork_code"];
            
            
            $row[$line["allocationwork_operatorid"]]['name']=$line["allocationwork_operatorid"];
            $row[$line["allocationwork_operatorid"]]['workarea']=$line["allocationwork_code"];
            $row[$line["allocationwork_operatorid"]]['shift']=$line["allocationshift_code"];
            $row[$line["allocationwork_operatorid"]]['contract']=$line["allocationcontract_code"];
            $row[$line["allocationwork_operatorid"]]['hours_available']=$line["hours_available"];
            $row[$line["allocationwork_operatorid"]]['ot_before']=$line["ot_before"];
            $row[$line["allocationwork_operatorid"]]['ot_after']=$line["ot_after"];
        }
        
    }
    
    //array_multisort($row,SORT_DESC);
        
   
    // }
    
    
    
    //show($query);
   
   return $row;
}
function get_list_workarea($db){
    
    $query="
    SELECT DISTINCT allocationwork_code FROM dbo.allocationwork
    where allocationwork_code<>''  
    order by allocationwork_code asc  
    ";
    
    $sql = $db->prepare($query); 
    $sql->execute();
    
    $row=$sql->fetchall();
    
   return $row;
}
function get_all_schedule($db,$date){
    $query="SELECT [schedule_id]
    ,[schedule_operatorcode]
    ,[schedule_productcode]
    ,[schedule_date]
    ,[schedule_hour_start]
    ,[schedule_duration],
    schedule_notes,
    WorkArea
    FROM [barcode].[dbo].[schedule]
    left join List_Document on Product_Code=schedule_productcode
    where schedule_date='$date'";
     $sql = $db->prepare($query); 
     $sql->execute();
     
     $temprow=$sql->fetchall();
     foreach($temprow as $temp){
        $row[$temp['schedule_operatorcode']][$temp['schedule_hour_start']]['duration']=$temp['schedule_duration'];
        $row[$temp['schedule_operatorcode']][$temp['schedule_hour_start']]['productcode']=$temp['schedule_productcode'];
        $row[$temp['schedule_operatorcode']][$temp['schedule_hour_start']]['notes']= $temp['schedule_notes'];
        $row[$temp['schedule_operatorcode']][$temp['schedule_hour_start']]['workarea']=$temp['WorkArea'];
     }
     
    return $row;
}

function get_list_process_schedule($db,$date){
    $filter='';
    if(!empty($_POST['workarea']) and $_POST['workarea']<>'All WorkArea'){
        if($_POST['workarea']=='Manufacturing'){
            $filter=$filter."AND WorkArea<>'Assembly'";
        }else{
            $filter=$filter."AND WorkArea='".$_POST['workarea']."'";
        }
        
    }
    $date2=$_POST['date_productlist'];

    if(empty($_SESSION['temp']['sort_productlist'])){
        $_SESSION['temp']['sort_productlist']='HOURS_Remaining2 ';
        $_SESSION['temp']['sort_productlist_ascdesc']='DESC';
    }
    
    
    $query="SET DATEFIRST 1;SELECT  Code	,WorkArea,sum((COALESCE(QTY_MADE,0))*BOM_TIME/60) as HOURS_Made
	,sum((COALESCE(QTY_INPROGRESS,0))*BOM_TIME/60) as HOURS_Current
	,sum((BaseQuantityOrdered-COALESCE(QTY_MADE,0)-COALESCE(QTY_INPROGRESS,0))*BOM_TIME/60) as HOURS_Remaining
    ,sum((BaseQuantityOrdered-COALESCE(QTY_MADE,0))*BOM_TIME/60) as HOURS_Remaining2,
    sum((COALESCE(QTY_MADE,0))/(BaseQuantityOrdered)) as Progress,
    sum(QuantityOrdered) as QuantityOrdered,
	sum(QTY_MADE) as QTY_MADE,
	avg(hours_allocated) as hours_allocated,
    avg(count_operator)as count_operator,
    avg(BOM_TIME)as BOM_TIME
    FROM MO_List
	left join MO_Hours_Scanned on MO_Hours_Scanned.[MO]=[ManufactureOrderNumber]
	left join prodplanstatus on prodplanstatus_REF=[ManufactureOrderNumber]
    left join prodplanrisk on prodplanrisk_REF=[ManufactureOrderNumber]
    left join prodplanpref on prodplanpref_REF=[ManufactureOrderNumber]
	left join BOM_List on BOM_List.Productcode=[Code]
    left join (
        Select  Count( DISTINCT schedule_operatorcode)as count_operator,Sum(schedule_duration)as hours_allocated,schedule_productcode from schedule where schedule_date='$date' group by schedule_productcode 
        )as allschedule on Code=schedule_productcode
	left join (SELECT count([prodplannotes_notes]) as Count_Notes
      ,[prodplannotes_REF]
  FROM [barcode].[dbo].[prodplannotes]
  group by [prodplannotes_REF])as temp on temp.prodplannotes_REF=[ManufactureOrderNumber]
    where  manufactureON is not null and
	  manufactureBefore<='$date2' $filter
                     AND IsLineOutstanding <>0 
    group by Code	,WorkArea 
    ORDER BY WorkArea ASC,".$_SESSION['temp']['sort_productlist'].$_SESSION['temp']['sort_productlist_ascdesc'];
    $sql = $db->prepare($query); 
    $sql->execute();
    //show($query);
    $allrow=$sql->fetchall();
    foreach($allrow as $row){
        $return[]=$row;
    }
    return $return;
}
function create_css_schedule ($db){
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
    $basetable='baseallocationshift';
    $query="SELECT  *
        FROM [barcode].[dbo].[baseallocationshift]
        order by  baseallocationshift_id asc
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
    $basetable='baseallocationcontract';
    $query="SELECT  *
        FROM [barcode].[dbo].[baseallocationcontract]
        order by  baseallocationcontract_id asc
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

function add_allocation($db){
    $query="DELETE FROM schedule WHERE
    schedule_operatorcode='".$_POST['schedule_operatorcode']."'
    and schedule_date= '".$_POST['date_filter']."'
    and schedule_hour_start>='".$_POST['schedule_hour_start']."'
    and schedule_hour_start<'".($_POST['schedule_hour_start']+$_POST['schedule_duration'])."';

    INSERT INTO schedule(
        schedule_operatorcode,
        schedule_productcode,
        schedule_date,
        schedule_hour_start,
        schedule_duration
    )
    VALUES(
        '".$_POST['schedule_operatorcode']."',
        '".$_POST['schedule_productcode']."',
        '".$_POST['date_filter']."',
        '".$_POST['schedule_hour_start']."',
        '".$_POST['schedule_duration']."' 
        )";
     $sql = $db->prepare($query); 
     $sql->execute();
     //show($query);
     ?>
     <script>
         
        QTY['<?php echo $_POST['schedule_productcode']?>']=QTY['<?php echo $_POST['schedule_productcode']?>']-parts_hours['<?php echo $_POST['schedule_productcode']?>']*<?php echo $_POST['schedule_duration']?>;
        hours['<?php echo $_POST['schedule_productcode']?>']=hours['<?php echo $_POST['schedule_productcode']?>']-<?php echo $_POST['schedule_duration']?>;
        allocated['<?php echo $_POST['schedule_productcode']?>']=allocated['<?php echo $_POST['schedule_productcode']?>']+<?php echo $_POST['schedule_duration']?>;
        Operator['<?php echo $_POST['schedule_productcode']?>']=Operator['<?php echo $_POST['schedule_productcode']?>']+1;
        update_BOMLIST('<?php echo $_POST['schedule_productcode']?>');
        
      </script>
     <?php
}
function remove_allocation($db){
    $query="DELETE FROM schedule WHERE
    schedule_operatorcode='".$_POST['schedule_operatorcode']."'
    and schedule_date= '".$_POST['date_filter']."'
    and schedule_hour_start='".$_POST['schedule_hour_start']."'
    ;
    ";
     $sql = $db->prepare($query); 
     $sql->execute();
     //show($query);
     ?>
     <script>
        
        QTY['<?php echo $_POST['schedule_productcode']?>']=QTY['<?php echo $_POST['schedule_productcode']?>']+parts_hours['<?php echo $_POST['schedule_productcode']?>']*<?php echo $_POST['schedule_duration']?>;
        hours['<?php echo $_POST['schedule_productcode']?>']=hours['<?php echo $_POST['schedule_productcode']?>']+<?php echo $_POST['schedule_duration']?>;
        allocated['<?php echo $_POST['schedule_productcode']?>']=allocated['<?php echo $_POST['schedule_productcode']?>']-<?php echo $_POST['schedule_duration']?>;
        Operator['<?php echo $_POST['schedule_productcode']?>']=Operator['<?php echo $_POST['schedule_productcode']?>']-1;
        update_BOMLIST('<?php echo $_POST['schedule_productcode']?>');
        
      </script>
      <?php
}
function save_notes_schedule($db){
    $_POST['schedule_notes']=str_replace(array("\r", "\n"), '<br>', $_POST['schedule_notes']);
    $query="UPDATE schedule
    SET schedule_notes='".$_POST['schedule_notes']."'
    WHERE  schedule_operatorcode='".$_POST['schedule_operatorcode']."'
    and schedule_date= '".$_POST['date_filter']."'
    and schedule_hour_start='".$_POST['schedule_hour_start']."'
    ";
    $sql = $db->prepare($query); 
    $sql->execute();
   //show($query);
}



?>