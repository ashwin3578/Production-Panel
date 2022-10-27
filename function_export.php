<?php 
function manage_post_export(){
    //show_debug();
    if($_POST['action']=='export_excel'){
        export_to_excel_v2();
    }
}
function navbar_export(){}
function general_view_export(){?>
    <div class="row all_view">
        <div class="col-xs-3 "><?php show_list_table()?></div>
        <div class="col-xs-3 "><?php show_list_field()?></div>
        <div class="col-xs-2 "><?php show_export()?></div>
    </div>
    <style>
        .all_view{
            text-align: center;
        }
    </style>
    
    <?php
}


function show_list_table(){
    $db=$GLOBALS['db'];
    $query="SELECT TABLE_NAME
    FROM information_schema.tables
    order by TABLE_NAME";

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $alltable['alltables']=$sql->fetchall();
    $query="SELECT TABLE_NAME
    FROM information_schema.views
    order by TABLE_NAME";

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $alltable['allviews']=$sql->fetchall();
    ?>
    <div class="row">All Table</div>
    <div class="list_table">
        <?php
        //foreach($alltable as $allrow){
            //show($allrow);
            foreach($alltable['alltables'] as $table){?>
                <form method="POST" id="<?php echo $table['TABLE_NAME']?>">
                <div class="row btn_table " onclick="document.getElementById('<?php echo $table['TABLE_NAME']?>').submit()"><?php echo $table['TABLE_NAME']?></div>
                <input name="TABLE_NAME" value="<?php echo $table['TABLE_NAME']?>" type="hidden">
                </form>
                <?php

            }
        //}?>
    </div>
    <div class="row"><br>All Views</div>
    <div class="list_table">
        <?php
        //foreach($alltable as $allrow){
            //show($allrow);
            foreach($alltable['allviews'] as $table){?>
                <form method="POST" id="<?php echo $table['TABLE_NAME']?>">
                <div class="row btn_table " onclick="document.getElementById('<?php echo $table['TABLE_NAME']?>').submit()"><?php echo $table['TABLE_NAME']?></div>
                <input name="TABLE_NAME" value="<?php echo $table['TABLE_NAME']?>" type="hidden">
                </form>
                <?php

            }
        //}?>
    </div>
    <style>
        .list_table{
            overflow-y:scroll;
            max-height: 250px;
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
function show_list_field(){
    $db=$GLOBALS['db'];
    if(!empty($_POST['TABLE_NAME'])){
        $table=$_POST['TABLE_NAME'];
        $query="
        select name from syscolumns where id=object_id('$table')";
    
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        $allrow=$sql->fetchall();//show($allrow);?>
        <div class="row">All Field From <?php echo $table?></div>
        <div class="list_field">
            <form method="POST" id="field_form">
                <input name="TABLE_NAME" value="<?php echo $table?>" type="hidden">
                <input name="action" id="action" value="" type="hidden">
                <div class="row btn_field ">
                    <div class="col-xs-8">All</div>
                    <div class="col-xs-4">
                        <input 
                        type="checkbox" 
                        oninput="document.getElementById('field_form').submit()" 
                        name="field[*]" 
                        <?php if(!empty($_POST['field']['*'])){echo 'checked';}?>>
                    </div>
                </div>
                <?php
                foreach($allrow as $table){?>
                    <div class="row btn_field ">
                        <div class="col-xs-8">
                            <?php echo $table['name']?>
                        </div>
                        <div class="col-xs-4">
                            <input 
                                type="checkbox" 
                                oninput="document.getElementById('field_form').submit()" 
                                name="field[<?php echo $table['name']?>]" 
                            <?php if(!empty($_POST['field'][$table['name']])){echo 'checked';}?>>
                        </div>
                    </div>
                    <?php
            
                }?>
           
        </div>
        <br>
        <textarea name="option" class="form-control" placeholder="WHERE [field]=[condition] ORDER BY [field] ASC/DESC"><?php echo $_POST['option']?></textarea>
        </form>
        <style>
            .list_field{
                overflow-y:scroll;
                max-height: 500px;
                text-align: center;
            }
            .btn_field{
                border-radius: 8px;
                border: solid 1px #a9a1a1;
                box-shadow: 3px 1px 1px;
            }
        </style>
        <?php
    }
    
}
function show_export(){
    $db=$GLOBALS['db'];
    if(!empty($_POST['field'])){?>
       

        <div class="btn_export" 
        onclick="document.getElementById('action').value='export_excel';
        document.getElementById('field_form').action='export_excel_debug.php';
        document.getElementById('field_form').submit();
        document.getElementById('field_form').action='';
        document.getElementById('action').value='';" >Export To Excel</div>    
        <style>
        
        .btn_export{
            border-radius: 8px;
            border: solid 1px #a9a1a1;
            box-shadow: 3px 1px 1px;
            padding:10px;
            font-size:20px;
            text-align: center;
        }
    </style>
        <?php
    }
}


function export_to_excel_v2(){
    $db=$GLOBALS['db'];
    $table=$_POST['TABLE_NAME'];
    $option=$_POST['option'];
    // Excel file name for download 
    $fileName = "Export_" .$table."_". date(' Y-m-d G:i:s') . ".xls"; 
    $column='';
    foreach(array_keys($_POST['field']) as $field){
        $column=$column.$field.',';
    }
    $column=substr($column, 0,-1);
    // Fetch records from database 
    
    $query="SELECT TOP 5000 $column
    FROM $table
    $option
    ";
   
   $sql = $db->prepare($query); 
   //Show($query);
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



?>
