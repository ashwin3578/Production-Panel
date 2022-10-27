<?php 
function manage_post_doc_management($db){
    
    show_debug();
    if(!empty($_POST['show_hide_active'])){
        if(!empty($_SESSION['temp']['doc']['show_active'])){
            unset($_SESSION['temp']['doc']['show_active']);
        }else{
            $_SESSION['temp']['doc']['show_active']=1;
        }
    }
    if (empty($_POST['document_type'])){
        $_POST['document_type']='MD&S';
    }
    if(!empty($_POST['keyword_to_add'])){
        $_SESSION['temp']['doc']['keyword'][]=$_POST['keyword_to_add'];
    }
    if(!empty($_POST['remove_keyword'])){
        unset($_SESSION['temp']['doc']['keyword']);
    }

    if($_POST['action']=='show_product_list'){show_product_list($db);}
    
    if($_POST['action']=='save_doc'){
        save_doc($db);
        $_POST['action']='';
        
    }
    if($_POST['action']=='unlink_product'){
        unlink_product($db);
        //$_POST['action']='show_product_list';
        show_manage_product_link($db,$_POST['doc_number'],$_POST['document_type']);
        ?><script>document.getElementById('window_link_product').style.display = "block";show_link=0;</script><?php
        
    }
    if($_POST['action']=='link_product'){
        link_product($db);
        //$_POST['action']=array();
        show_manage_product_link($db,$_POST['doc_number'],$_POST['document_type']);
        ?><script>document.getElementById('window_link_product').style.display = "block";show_link=0;</script><?php
    }
    
    if($_POST['action']=='delete_doc'){
        delete_doc($db);
        $_POST['action']='';
    }
    if($_POST['action']=='delete_file_doc'){
        delete_upload_doc($db);
        $_POST['action']='';
    }
    if($_POST['action']=='show_all_doc'){
        show_all_doc($db);
    }
    if($_POST['action']=='save_new_type'){
        save_new_type($db);
        
    }
    if($_POST['action']=='delete_type'){
        delete_type($db);
        
        show_all_doc($db);
        
    }
    
    if($_POST['action']=='add_type'){
        add_type();
    }
    if($_POST['action']=='show_manage_doc'){
        show_manage_doc($db);
    }
    if($_POST['action']=='show_temp_import'){
        show_temp_import($db);
    }
}

function general_view_doc_management($db){?>
    <div class="navbar_top">
    <?php //navbar_top_doc_management($db);?>
    </div>
     
    
    <div class="main_page">
        <?php //show_all_schedule($db);
        
            show_all_doc($db);
        
        ?>
    </div>
    <?php
}

function navbar_top_doc_management($db){?>
    <div class="row navbar" style="text-align:center">
        <!--<div  class="col-xs-3 col-sm-3 col-md-2 col-lg-2"><span class="btn btn-default">Add a new Document</span></div>-->
        <div  class="col-xs-3 col-sm-3 col-md-2 col-lg-2"><span class="btn btn-default" onclick="load('show_all_doc')">View All Documents</span></div>
        <div  class="col-xs-3 col-sm-3 col-md-2 col-lg-2"><span class="btn btn-default" onclick="load('show_temp_import')">Temp Import</span></div>
    </div>
    <script>
        function load(action){
            var request =$.ajax({
            type:'POST',
            url:'doc_management_ajax.php',
            data: {
              action: action
            },
            success:function(html){
                $('.main_page').empty().append(html);
            }
        });
        }
    </script>
    
    <?php
}
function navbar_top_list_doc_management($db){?>
    <div class="row navbar" style="text-align:center">
        <!--<div  class="col-xs-3 col-sm-3 col-md-2 col-lg-2"><span class="btn btn-default" onclick="load('show_temp_import')">Temp Import</span></div>-->
        
        <div  class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
            <input class="form-control" type="text" id="search_bar" onkeydown="search(this)" Placeholder="keyword to search">
        </div>
        <div  class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
            <span class="btn btn-default" onclick="load_type_with_search()">Search</span>
        </div>

        <div  class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
            <div  class="col-xs-8 ">
                <?php foreach($_SESSION['temp']['doc']['keyword'] as $keyword){ echo$keyword.'<br>';}?>
            </div>
            <div  class="col-xs-2 "><?php if(!empty($_SESSION['temp']['doc']['keyword'] )){?><span class="btn btn-default" onclick="load_type_with_remove_search()">X</span><?php }?></div>
       </div>
       <div  class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
            <?php if(!empty($_SESSION['temp']['role_doc_change'])){
                if(!empty($_SESSION['temp']['doc']['show_active'])){
                    $show='Hide Old Issue';
                }else{
                        $show='Show Old Issue';
                }
                ?>
                <span class="btn btn-default" onclick="show_hide_active()"><?php echo $show?></span>
            <?php }?>
        </div>
    </div>
    <script>
        function search(ele) {
            if(event.key === 'Enter') {
                load_type_with_search();     
            }
        }
        function load_type_with_search(){
            var request =$.ajax({
            type:'POST',
            url:'doc_management_ajax.php',
            data: {
              action: 'show_all_doc',
              document_type:'<?php echo $_POST['document_type']?>',
              keyword_to_add:document.getElementById('search_bar').value

            },
            success:function(html){
                $('.main_page').empty().append(html);
            }
        });
        }
        function show_hide_active(){
            var request =$.ajax({
            type:'POST',
            url:'doc_management_ajax.php',
            data: {
              action: 'show_all_doc',
              document_type:'<?php echo $_POST['document_type']?>',
              show_hide_active:1

            },
            success:function(html){
                $('.main_page').empty().append(html);
            }
        });
        }
        function load_type_with_remove_search(){
            var request =$.ajax({
            type:'POST',
            url:'doc_management_ajax.php',
            data: {
              action: 'show_all_doc',
              document_type:'<?php echo $_POST['document_type']?>',
              remove_keyword:1

            },
            success:function(html){
                $('.main_page').empty().append(html);
            }
        });
        }
    </script>
    
    <?php
}

function show_all_doc($db){?>
    <div class="row " style="text-align:center">
    <div  class="col-xs-2 list_type"><?php show_list_type($db)?></div>
    <div  class="col-xs-7 list_doc">
    <?php if($_POST['action']<>'delete_type'){show_list_doc($db);}?>
    </div>
    <div  class="col-xs-3 manage_doc">
        <?php //show_manage_doc($db)
        if($_POST['action']=='show_new_issue'){
            show_manage_doc($db);
        }
        ?>
    </div>
    </div>
    <style>
        .manage_doc{
            position: sticky;top: 0;
        }
    </style>
    <?php
}
function show_list_type($db){
    
    $alltype=get_all_doc_type();
    
    foreach($alltype as $type){
        $classadded='';
        if($type['doctype']==$_POST['document_type']){$classadded='btn-primary';}?>
    <div class="row ">
        <span class="btn btn-default <?php echo $classadded?>" onclick="load_type('<?php echo $type['doctype']?>')">
            <?php echo $type['doctype']?>
        </span>
        <?php if(empty($type['thecount'])){?>
        <span class="btn btn-default" onclick="delete_type('<?php echo $type['doctype']?>')"><span class="glyphicon glyphicon-trash"></span></span>
        <?php }?>
    </div><br>
    

    <?php }?>
    <?php if(!empty($_SESSION['temp']['role_doc_change'])){
        $type['doctype']="Add New Type";
        $classadded='';
        if($type['doctype']==$_POST['document_type']){$classadded='btn-primary';}?>
        <div class="row "><span class="btn btn-default <?php echo $classadded?>" onclick="add_type('<?php echo $type['doctype']?>')"><?php echo $type['doctype']?></span></div>
    <?php }?>
    <script>
        function load_type(document_type){
            var request =$.ajax({
            type:'POST',
            url:'doc_management_ajax.php',
            data: {
              action: 'show_all_doc',
              document_type:document_type

            },
            success:function(html){
                $('.main_page').empty().append(html);
            }
        });
        }
        function add_type(document_type){
            var request =$.ajax({
            type:'POST',
            url:'doc_management_ajax.php',
            data: {
              action: 'add_type',
              document_type:document_type

            },
            success:function(html){
                $('.main_page').empty().append(html);
            }
        });
        }
        function delete_type(document_type){
            var request =$.ajax({
            type:'POST',
            url:'doc_management_ajax.php',
            data: {
              action: 'delete_type',
              document_type:document_type

            },
            success:function(html){
                $('.main_page').empty().append(html);
            }
        });
        }
    </script>

    <?php
}
function add_type(){
    $db=$GLOBALS['db'];?>

    <div class="row " style="text-align:center">
    <div  class="col-xs-2 list_type"><?php show_list_type($db)?></div>
    <div  class="col-xs-7 list_doc">
        <form method="POST">
            <div  class="col-xs-4"><input name="document_type" class="form-control" type="text" placeholder="Enter the new type of Document"></div>
            <div  class="col-xs-2"><input  class="form-control" type="submit"></div>
            <input name="action" value="save_new_type" class="form-control" type="hidden">
        </form>
    </div>
    
    </div>
    <style>
        .manage_doc{
            position: sticky;top: 0;
        }
    </style>
    <?php

}
function show_list_doc($db){?>
    <?php navbar_top_list_doc_management($db)?>
    <div class="row header_matrix">
        <div class="col-xs-3"><?php echo $_POST['document_type'];?></div>
        <div class="col-xs-2">Number</div>
        
        <!--<div class="col-xs-2">Date of Issue</div>-->
        <div class="col-xs-2">Files</div>
        <div class="col-xs-3">Product Link</div>
    
        <div class="col-xs-2">
            <?php if(!empty($_SESSION['temp']['role_doc_change'])){?><span class="btn btn-default" onclick="manage_document('Add a New')">Add a New</span><?php }?>
        </div>
        
    </div>
    
    <?php
    $all_doc=get_all_doc($db);
    $all_code_link=get_all_code_link($db,$_POST['document_type']);
    
    foreach($all_doc as $doc){?>
        
        <div class="row line_matrix">
            <div class="col-xs-3"><?php echo $doc['document_name']?></div>
            <div class="col-xs-2"><?php echo $doc['document_number']?> ISS <?php echo $doc['document_issue']?></div>
            <!--<div class="col-xs-2"><?php echo $doc['document_date_issue']?></div>-->
            
            <div class="col-xs-2 ">
                <?php if(!empty($doc['document_upload'])){?>
                <a target="blank" href="ressource_v2/<?php echo $doc['document_type']?>/<?php echo $doc['document_filename']?>" ><span class="btn btn-default"><span class="glyphicon glyphicon-file"></span> View</span></a>
                <?php }else{?>
                    <span><img class="col-xs-4" src="img/warning.png"><span class="col-xs-4">No Files</span><img class="col-xs-4" src="img/warning.png"></span>
                <?php }?>   
            </div>
            <div class="col-xs-3">
            <?php foreach($all_code_link[$doc['document_number']] as $code_link){
                echo $code_link.'<br>';
            }?>
            </div>
            <div class="col-xs-2"><span class="btn btn-default" onclick="manage_document('<?php echo $doc['document_id']?>')"><span class="glyphicon glyphicon-edit"></span></span></div>
            
            
        </div>
        
    <?php
    }?>
    <script>
        function manage_document(doc_id){
            var request =$.ajax({
                type:'POST',
                url:'doc_management_ajax.php',
                data: {
                    action: 'show_manage_doc',
                    document_type:'<?php echo $_POST['document_type'];?>',
                    doc_id:doc_id

                },
                success:function(html){
                    $('.manage_doc').empty().append(html);
                }
            });
        }
    </script>

    <?php   
}
function show_manage_doc($db){
    if($_POST['doc_id']=='Add a New'){
        if(!empty($_POST['document_name'])){$doc['document_name']=$_POST['document_name'];}
        if(!empty($_POST['document_number'])){$doc['document_number']=$_POST['document_number'];}else{$doc['document_number']=get_last_doc($db)+1;}
        if(!empty($_POST['document_date_issue'])){$doc['document_date_issue']=$_POST['document_date_issue'];}else{$doc['document_date_issue']=date('Y-m-d',time());}
        if(!empty($_POST['document_issue'])){$doc['document_issue']=$_POST['document_issue'];}else{$doc['document_issue']=1;}
        
        $doc['document_type']=$_POST['document_type'];
    }else{
        $doc=get_doc_infos($db,$_POST['doc_id']);
    }
    $col1='col-xs-3 ';
    $col='col-xs-6 ';
    $col2='col-xs-3 ';
    $col3='col-xs-6 ';//col-xs-offset-3
    if(empty( $doc['document_upload'])){
        $input_file='<input class="form-control" type="file" id="document_file" name="document_file" >';
        $locked='';//oninput="document.getElementById(\'manage_doc_form\').submit()"
    }else{
        $filepath="ressource_v2/".$doc['document_type']."/".$doc['document_filename'];
        
        $input_file='<span class="btn btn-default"  onclick="if (!confirm(\'Are you sure?\')) return false;document.getElementById(\'delete_file_doc_form\').submit()"><span class="glyphicon glyphicon-trash"></span> Remove PDF</span></div>';
        $locked='readonly';
    }
    if(!empty($_POST['document_number'])){$locked='readonly';}
    ?>
    <form id="manage_doc_form" method="post" enctype="multipart/form-data">
        <div class="row header_matrix">Manage Document</div>
        <?php if(!empty($_SESSION['temp']['role_doc_change'])){?>
            <div class="row "><div class="<?php echo $col1?>">Name:</div><div class="<?php echo $col?>"><input class="form-control" type="text" id="document_name" name="document_name" value="<?php echo $doc['document_name']?>" Placeholder="Document Name"></div></div>
            <div class="row "><div class="<?php echo $col1?>">Number:</div><div class="<?php echo $col?>"><input class="form-control" type="text" id="document_number" name="document_number" <?php echo $locked?> value="<?php echo $doc['document_number']?>" Placeholder="Document Number"></div></div>
            <div class="row ">
                <div class="<?php echo $col1?>">Issue:</div>
                <div class="<?php echo $col2?>"><input class="form-control" type="text" id="document_issue" name="document_issue" <?php echo $locked?> value="<?php echo $doc['document_issue']?>" Placeholder="Document Issue Number"></div>
                <div class="<?php echo $col3?>">
                    <?php if($_POST['doc_id']!='Add a New'){?>
                    <span class="btn btn-default"  onclick="document.getElementById('new_issue_doc_form').submit()">New Issue</span>
                    <?php } ?>
                </div>
            </div>
            <div class="row "><div class="<?php echo $col1?>">Date Issue:</div><div class="<?php echo $col?>"><input class="form-control" type="date" id="document_date_issue" name="document_date_issue" value="<?php echo $doc['document_date_issue']?>" Placeholder="Document Issue Date"></div></div>
            <div class="row "><div class="<?php echo $col1?>">File:</div><div class="<?php echo $col?>"><?php echo $input_file?></div></div>
            <div class="row "><br>
            <div class="<?php echo $col?>">
                <span class="btn btn-default"  onclick="document.getElementById('manage_doc_form').submit()">Save</span>
            </div>
                <?php if($_POST['doc_id']!='Add a New'){?>
                <div class="<?php echo $col?>">
                    <span class="btn btn-default"  onclick="if (!confirm('Are you sure?')) return false;document.getElementById('delete_doc_form').submit()"><span class="glyphicon glyphicon-trash"></span> Delete</span>
                </div>
            </div>
            <?php } ?>
            <div class="manage_product_link">
            <?php show_manage_product_link($db,$doc['document_number'],$_POST['document_type']);?>
            </div>
        <?php }?>
        <input type="hidden" name="document_type" value="<?php echo $doc['document_type']?>"></div>
        <input type="hidden" name="doc_id" value="<?php echo $_POST['doc_id']?>"></div>
        <input type="hidden" name="action" value="save_doc"></div>
    </form>
    <form id="new_issue_doc_form" method="POST" >
        <input type="hidden" name="doc_id" value="Add a New"></div>
        <input type="hidden" name="document_type" value="<?php echo $_POST['document_type']?>"></div>
        <input type="hidden" name="document_name" value="<?php echo $doc['document_name']?>"></div>
        <input type="hidden" name="document_number" value="<?php echo $doc['document_number']?>"></div>
        <input type="hidden" name="document_issue" value="<?php echo ($doc['document_issue']+1)?>"></div>
        <input type="hidden" name="action" value="show_new_issue"></div>
    </form>
    <form id="delete_doc_form" method="POST">
        <input type="hidden" name="doc_id" value="<?php echo $_POST['doc_id']?>"></div>
        <input type="hidden" name="document_type" value="<?php echo $_POST['document_type']?>"></div>
        <input type="hidden" name="action" value="delete_doc"></div>
    </form>

    <form id="delete_file_doc_form" method="POST">
        <input type="hidden" name="doc_id" value="<?php echo $_POST['doc_id']?>"></div>
        <input type="hidden" name="document_type" value="<?php echo $_POST['document_type']?>"></div>
        <input type="hidden" name="action" value="delete_file_doc"></div>
    </form>
    
    
    <br>
    <?php show_mini_pdf($filepath);
    show_doc_log($doc['document_number']);
}
function show_temp_import($db){
    // // // $query="SELECT  [Open Work Inst_File]
    // // // FROM [barcode].[dbo].[List_Document]
    // // // where [Open Work Inst_File]<>'' and [Open Work Inst_File] not like '%WI_104_ISS_17%' and [Open Work Inst_File] not like '%SWI%'
    // // // group by [Open Work Inst_File]
    // // // order by [Open Work Inst_File]";

    // // // $sql = $db->prepare($query); 
    // // // //show($query);
    // // // $sql->execute();

    // // // $row=$sql->fetchall();
    // // // foreach($row as $WI){
    // // //     $offset=35;
    // // //     $len=strlen($WI[0])-4-$offset;
    // // //     $WInumber=substr($WI[0],$offset,$len);
    // // //     $doc_number=substr($WInumber,0,6);
    // // //     $doc_issue=substr($WInumber,strlen($WInumber)-3,3)+0;
    // // //     $document_name=$doc_number.'_ISS_'.$doc_issue;
    // // //     $document_filename=$document_name.'.pdf';
        
    // // //     $allWI[$WInumber]['doc_number']=$doc_number;
    // // //     $allWI[$WInumber]['doc_issue']=$doc_issue;

    // // //     $query="INSERT INTO document
    // // //         (document_name,
    // // //         document_type,
    // // //         document_number,
    // // //         document_issue,
    // // //         document_date_issue,
    // // //         document_date_added,
    // // //         document_added_by,
    // // //         document_timetag_added_by,
    // // //         document_filename)
    // // //         VALUES
    // // //         (
    // // //         '$document_name',
    // // //         'Work Instruction',
    // // //         '$doc_number',
    // // //         '$doc_issue',
    // // //         '".date('Y-m-d',time())."',
    // // //         '".date('Y-m-d',time())."',
    // // //         '".$_SESSION['temp']['id']."',
    // // //         '".time()."',
    // // //         '$document_filename'
    // // //         )";
    // // //         $sql = $db->prepare($query); 
    // // //         show($query);
    // // //         $sql->execute();
    // // //         update_active_doc($db,$doc_number);
    // // // }

    // $query="SELECT  Product_Code,[Open Work Inst_File]
    // FROM [barcode].[dbo].[List_Document]
    // where [Open Work Inst_File]<>'' and [Open Work Inst_File] not like '%WI_104_ISS_17%' and [Open Work Inst_File] not like '%SWI%'
    // order by [Open Work Inst_File]";

    // $sql = $db->prepare($query); 
    // $sql->execute();

    // $row=$sql->fetchall();
    // foreach($row as $WI){
    //     $offset=35;
    //     $len=strlen($WI['Open Work Inst_File'])-4-$offset;
    //     $WInumber=substr($WI['Open Work Inst_File'],$offset,$len);
    //     $doc_number=substr($WInumber,0,6);
    //     $doc_issue=substr($WInumber,strlen($WInumber)-3,3)+0;
    //     $allWI[$doc_number]['Code'][]=$WI['Product_Code'];
    //     $allWI[$doc_number]['doc_number']=$doc_number;
    //     $query="UPDATE";
    //     //$sql->execute();
    // }
    // //show($allWI);
    // foreach($allWI as $WI){
    //     foreach($WI['Code'] as $code){
    //         $query="INSERT INTO doc_link
    //         (doclink_docnumber,
    //         doclink_productcode,
    //         doclink_doctype)
    //         VALUES
    //         (
    //         '".$WI['doc_number']."',
    //         '".$code."',
    //         'Work Instruction'
    //         )";
    //         //show($query);
    //         $sql = $db->prepare($query); 
    //         //$sql->execute();
    //     }
        
    // }
    $query="SELECT doclink_id,doclink_docnumber,doclink_doctype,WorkArea,(count([Product_Code])) as count_product
    FROM [barcode].[dbo].[doc_link]
    left join [List_Document] on convert(nvarchar,doclink_productcode)=[Product_Code]
    where doclink_doctype<>'MD&S'
    group by doclink_docnumber,doclink_doctype,WorkArea,doclink_id
    order by count([Product_Code])ASC,WorkArea DESC
    ";

    $sql = $db->prepare($query); 
    $sql->execute();

    $row=$sql->fetchall();
    foreach($row as $entry){
        $return[$entry['doclink_docnumber']]['WorkArea'][$entry['WorkArea']]=$entry['count_product'];
        $return[$entry['doclink_docnumber']]['count_product']=$return[$entry['doclink_docnumber']]['count_product']+$entry['count_product'];
        $return[$entry['doclink_docnumber']]['nbr_WorkArea']++;
        $return[$entry['doclink_docnumber']]['doclink_workarea']=$entry['WorkArea'];
        $return[$entry['doclink_docnumber']]['document_number']=$entry['doclink_docnumber'];

    }
    foreach($return as $WI){
        
        $doclink_workarea=$WI['doclink_workarea'];
        $document_number=$WI['document_number'];
        $query="UPDATE document
                SET document_workarea='$doclink_workarea'
                where document_number='$document_number' and document_type='Work Instruction'";
                //show($query);
                $sql = $db->prepare($query); 
                $sql->execute();
    }
    //show ($return);
    
    
}
function show_mini_pdf($filepath){?>
    <iframe src="<?php echo $filepath?>"  style="height:400px;width:100%;border:none;overflow:hidden;" ></iframe><?php
}
function show_manage_product_link($db,$doc_number,$doc_type){
    $all_code_link=get_all_code_link($db,$doc_type);?>
    <br><div class="link_box" style="width:100%">
        <div class="row" onclick="show_link_product();">Link Product</div>
        <script>
            show_link=1;
            function show_link_product(){
                if(show_link==1){
                    document.getElementById('window_link_product').style.display = "block";
                    show_link=0;
                }else{
                    document.getElementById('window_link_product').style.display = "none";
                    show_link=1;
                }
                
            }
        </script>
       
        <div class="row" id="window_link_product" style="display:none">
        <br>
        <div class="all_product_linked">
            <?php foreach($all_code_link[$doc_number] as $code){?>
                <div class="row">
                    <div class="col-xs-10"><?php echo $code?></div>
                    <div class="col-xs-2 btn btn-default" onclick="unlink_product('<?php echo $code?>','<?php echo $doc_number?>')"><span class="glyphicon glyphicon-trash"></span></div>
                </div>
            <?php }?>
        </div>
            <div class="row">
                <div class="col-xs-10">
                    <input id="code_to_add" list="thelist" class="form-control" placeholder="Code to Link" onEnter="link_product(document.getElementById('code_to_add').value,'<?php echo $doc_number?>')">
                </div>
                <div class="col-xs-2 btn btn-default" onclick="link_product(document.getElementById('code_to_add').value,'<?php echo $doc_number?>')"><span class="glyphicon glyphicon-plus"></span></div>
            </div>
        
            <div class="show_product_list" >
                <?php if($_POST['action']=='link_product'){show_product_list($db);}?>
                
            </div>
            <script>
                 var request =$.ajax({
                    type:'POST',
                    url:'doc_management_ajax.php',
                    data: {
                        doc_number:'<?php echo $doc_number?>',doc_type:'<?php echo $doc_type?>',action: 'show_product_list'

                    },
                    success:function(html){
                        $('.show_product_list').empty().append(html);
                    }
                });
                
            </script>
            <style>
                .all_product_linked{
                    max-height:200px;
                    overflow-y: scroll;
                }
            </style>

        </div>
    </div>
    <script>
            show_link=1;
            function unlink_product(code,doc_number){
                var request =$.ajax({
                    type:'POST',
                    url:'doc_management_ajax.php',
                    data: {
                        action: 'unlink_product',
                        document_type:'<?php echo $doc_type;?>',
                        code:code,
                        doc_number:doc_number,
                        doc_id:'<?php echo $_POST['doc_id'];?>'

                    },
                    success:function(html){
                        $('.manage_product_link').empty().append(html);
                    }
                });
                
            }
            function link_product(code,doc_number){
                var request =$.ajax({
                    type:'POST',
                    url:'doc_management_ajax.php',
                    data: {
                        action: 'link_product',
                        document_type:'<?php echo $doc_type;?>',
                        code:code,
                        doc_number:doc_number,
                        doc_id:'<?php echo $_POST['doc_id'];?>'

                    },
                    success:function(html){
                        $('.manage_product_link').empty().append(html);
                    }
                });
                
            }
            
        </script>
    <style>
        .link_box{
            color: #333;
            background-color: #fff;
            border-color: #ccc;
            margin-bottom: 0;
            font-weight: normal;
            text-align: center;
            background-image: none;
            border: 1px solid #333;
            padding: 6px 12px;
            border-radius: 4px;
            
        }
    </style>
    <?php
}




function get_doc_infos($db,$doc_id){
    $query="SELECT *
	  FROM document 
      WHERE document_id='$doc_id'";
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
	$row=$sql->fetch();
		
	return $row;
}
function get_doc_infos_from_name($db,$document_number,$document_issue){
    $query="SELECT *
	  FROM document 
      WHERE document_number='$document_number' and document_issue='$document_issue'";
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
	$row=$sql->fetch();
		
	return $row;
}
function get_all_doc($db,$option='1=1'){
    if(!empty($_SESSION['temp']['doc']['show_active'])){
        $option2="";
    }else{
        $option2="and document_active=1";
    }
    $search='';
    foreach($_SESSION['temp']['doc']['keyword'] as $keyword){
        $search=$search."and (document_name like '%$keyword%' or document_issue like '%$keyword%' or document_number like '%$keyword%' or doclink_productcode like '%$keyword%' )";
    }
    $option2=$option2.$search;
    $query="SELECT [document_id]
    ,[document_name]
    ,[document_type]
    ,[document_number]
    ,[document_issue]
    ,[document_date_issue]
    ,[document_date_added]
    ,[document_added_by]
    ,[document_timetag_added_by]
    ,[document_filename]
    ,[document_upload]
    ,[document_active]
    FROM document 
    left join doc_link on document_number=doclink_docnumber and document_type=doclink_doctype
    WHERE document_type='".$_POST['document_type']."' and $option $option2
    group by [document_id]
    ,[document_name]
    ,[document_type]
    ,[document_number]
    ,[document_issue]
    ,[document_date_issue]
    ,[document_date_added]
    ,[document_added_by]
    ,[document_timetag_added_by]
    ,[document_filename]
    ,[document_upload]
    ,[document_active]
    order by document_upload,TRY_CAST(document_number AS int) DESC ,document_number DESC";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $row=$sql->fetchall();
      
  return $row;
}
function get_all_doc_type(){
    $db=$GLOBALS['db'];
    $query="SELECT doctype,thecount
    FROM doctype 
	left join (
	SELECT count(document_id)as thecount,document_type from document group by document_type
	)as temp on document_type=doctype
    ";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $allrow=$sql->fetchall();
    
      
  return $allrow;
}
function get_last_doc($db){
    $query="SELECT TOP 1 TRY_CAST(document_number AS int)as last_number
    FROM document 
    ORDER BY TRY_CAST(document_number AS int) DESC,document_number DESC";
    $query="SELECT TOP 1 TRY_CAST(document_number AS int)as last_number
    FROM document 
    WHERE document_type='".$_POST['document_type']."' 
    ORDER BY TRY_CAST(document_number AS int) DESC,document_number DESC";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $row=$sql->fetch();
    return $row[0];
}
function get_all_code_link($db,$doctype){
    $query="SELECT[doclink_id]
            ,[doclink_docnumber]
            ,[doclink_productcode]
            ,[doclink_doctype]
        FROM [barcode].[dbo].[doc_link]
        WHERE doclink_doctype='$doctype'";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $temp=$sql->fetchall();
    foreach($temp as $row){
        $return[$row['doclink_docnumber']][]=$row['doclink_productcode'];
    }
    return $return;
}
function get_all_product_doc($db){
    $doc_number=$_POST['doc_number'];
    $doc_type=$_POST['doc_type'];
    $query="SELECT *
      from [List_Document]
      left join doc_link on convert(nvarchar,doclink_productcode)=Product_Code and doclink_doctype='$doc_type'and doclink_docnumber='$doc_number'
      where  doclink_productcode is null
      order by Product_Code";

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();
    //
    return $row;
}


function add_file($filename='',$path=''){
    if (empty($filename)){
        $filename=date('YmdGis').'pdf';
    }
    if (empty($path)){
        $filename='temp';
    }

    return true;

}
function save_doc($db){
    $_POST['document_filename']=$_POST['document_number'].'_ISS_'.$_POST['document_issue'].'.pdf';
    if($_POST['doc_id']=='Add a New' and empty(get_doc_infos_from_name($db,$_POST['document_number'],$_POST['document_issue']))){
       
        //add file to folder
       if(add_file($_POST['document_filename'])==true){  
            //if file is added then add line to database
            $query="INSERT INTO document
            (document_name,
            document_type,
            document_number,
            document_issue,
            document_date_issue,
            document_date_added,
            document_added_by,
            document_timetag_added_by,
            document_filename)
            VALUES
            (
            '".$_POST['document_name']."',
            '".$_POST['document_type']."',
            '".$_POST['document_number']."',
            '".$_POST['document_issue']."',
            '".$_POST['document_date_issue']."',
            '".date('Y-m-d',time())."',
            '".$_SESSION['temp']['id']."',
            '".time()."',
            '".$_POST['document_filename']."'
            )";
            $sql = $db->prepare($query); 
            //show($query);
            $sql->execute();
            update_active_doc($db,$_POST['document_number']);
       }
      
       
    }else{
        $query="UPDATE document
        SET document_name='".$_POST['document_name']."'
        , document_type= '".$_POST['document_type']."'
        , document_number='".$_POST['document_number']."'
        , document_issue='".$_POST['document_issue']."'
        , document_date_issue='".$_POST['document_date_issue']."'
        , document_date_added='".date('Y-m-d',time())."'
        , document_added_by='".$_SESSION['temp']['id']."'
        , document_timetag_added_by='".time()."'
        , document_filename='".$_POST['document_filename']."'
        
        WHERE  document_id='".$_POST['doc_id']."'";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        update_active_doc($db,$_POST['document_number']);
    }
    $memberid=$_SESSION['temp']['id'];
    $doc_number=$_POST['document_number'];
    $entry="Document $doc_number Saved by $memberid <br> Query: <br>$query";
    add_entry_log_doc($doc_number,$memberid,$entry);

    if(!empty($_FILES)){
        upload_doc($db);
       }

}
function delete_doc($db){
    delete_upload_doc($db);
    $doc=get_doc_infos($db,$_POST['doc_id']);
    $query="DELETE FROM document
    WHERE  document_id='".$_POST['doc_id']."'";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    if($doc['document_issue']==1){
        $query="DELETE FROM doc_link
        WHERE  doclink_docnumber='".$doc['document_number']."'";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
    }
   
    update_active_doc($db,$doc['document_number']);
    $memberid=$_SESSION['temp']['id'];
    $doc_number=$doc['document_number'];
    $entry="Document $doc_number Deleted by $memberid";
    add_entry_log_doc($doc_number,$memberid,$entry);
    
}
function upload_doc($db){
    $doc=get_doc_infos_from_name($db,$_POST['document_number'],$_POST['document_issue']);
    $target_name=$_POST['document_type']."/".$_POST['document_filename'];
    $target_dir = "ressource_v2/";
    $target_file = $target_dir . $target_name;
    $uploadOk = 1;
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["document_file"]["tmp_name"], $target_file)) {
            //echo "The file $target_name has been uploaded.";
            $query="UPDATE document
            SET document_upload=1    
            WHERE  document_id='".$doc['document_id']."'";
            $sql = $db->prepare($query); 
            //show($query);
            $sql->execute();
        } else {
            //echo "Sorry, there was an error uploading your file. $target_name";
        }
    }
    $memberid=$_SESSION['temp']['id'];
    $doc_number=$_POST['document_number'];
    $entry="File Upload from $doc_number by $memberid";
    add_entry_log_doc($doc_number,$memberid,$entry);

}
function delete_upload_doc($db){
    $doc=get_doc_infos($db,$_POST['doc_id']);
    $query="UPDATE document
    SET document_upload=0   
    WHERE  document_id='".$_POST['doc_id']."'";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    rename("ressource_v2/".$doc['document_type']."/".$doc['document_filename'], "ressource_v2/".$doc['document_type']."/del_".$doc['document_filename']);
}
function update_active_doc($db,$document_number){
    $query="SELECT TOP 1 *
	  FROM document 
      WHERE document_number='$document_number' 
      ORDER BY document_issue DESC";
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
	$row=$sql->fetch();
    //show($row);
    $doc_id=$row['document_id'];
    $query="UPDATE document SET document_active=0
      WHERE document_number='$document_number' and document_id<>'$doc_id';
      UPDATE document SET document_active=1
      WHERE  document_id='$doc_id';
     ";
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

    
}
function unlink_product($db){
    $query="DELETE FROM doc_link
    WHERE doclink_docnumber='".$_POST['doc_number']."' AND
    doclink_productcode like '".$_POST['code']."' AND
    doclink_doctype='".$_POST['document_type']."'";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $memberid=$_SESSION['temp']['id'];
    $doc_number=$_POST['doc_number'];
    $entry=$_POST['code']."  Unlink from $doc_number by $memberid";
    add_entry_log_doc($doc_number,$memberid,$entry);
}
function link_product($db){
    $query="INSERT INTO doc_link
    (doclink_docnumber,
    doclink_productcode,
    doclink_doctype)
    VALUES
    (
    '".$_POST['doc_number']."',
    '".$_POST['code']."',
    '".$_POST['document_type']."'
    )";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();


    $memberid=$_SESSION['temp']['id'];
    $doc_number=$_POST['doc_number'];
    $entry=$_POST['code']." Link to $doc_number by $memberid";
    add_entry_log_doc($doc_number,$memberid,$entry);
}
function show_product_list($db){?>
    <datalist id="thelist">
    <?php foreach (get_all_product_doc($db) as &$item){
        echo"<option >".$item[0]."</option>";
    }?>
    </datalist>
    
    <?php
}
function save_new_type(){
    $db=$GLOBALS['db'];
    $query="SELECT doctype
	  FROM doctype 
      WHERE doctype='".$_POST['document_type']."'";
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
	$row=$sql->fetch();

    if(empty($row)){
        $query="INSERT INTO doctype
        (doctype)
        VALUES
        (
        '".$_POST['document_type']."'
        )";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        $folderpath="ressource_v2/".$_POST['document_type'];
        if (!file_exists($folderpath)) {
            mkdir($folderpath, 0777, true);
        }
    }
    
   
}
function delete_type(){
    $db=$GLOBALS['db'];
    $query="SELECT *
	  FROM document
      WHERE document_type='".$_POST['document_type']."'";
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
	$row=$sql->fetch();

    if(empty($row)){
        $query="DELETE
        FROM doctype 
        WHERE doctype='".$_POST['document_type']."'";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        $folderpath="ressource_v2/".$_POST['document_type'];
        
        rmdir($folderpath);
        
    }
}
function add_entry_log_doc($doc_number,$memberid,$entry){
	$db=$GLOBALS['db'];
    $entry=str_replace("'","",$entry);
    $query="INSERT INTO doc_log(doclog_doc_number,doclog_timetag,doclog_member,doclog_entry)
	VAlUES('$doc_number',".time().",'$memberid','$entry')";
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();
	
}
function show_doc_log($doc_number){
    $db=$GLOBALS['db'];
    
    $sql = $db->prepare("SELECT *
      
	FROM doc_log
	where doclog_doc_number='$doc_number'
	ORDER BY doclog_timetag desc"); 
	
	$sql->execute();
	$all_log=$sql->fetchall(); 	
    //show($all_log);
    ?>
    <div class="line_log">
        <div class="log_header" onclick="toggle_log()">Log</div>
        <div class="log_content" id="thelog" style="display:none">
            <?php 
            foreach($all_log as $log){?>
                <div class="row">
                    <div class="col-xs-12"><?php echo date('Y-m-d G:i:s',$log['doclog_timetag'])?></div>
                    <div class="col-xs-12"><?php echo $log['doclog_member']?></div>
                    <div class="col-xs-12"><?php echo $log['doclog_entry']?></div>
                </div>
                <?php
                separator();
            }
            ?>

        </div>
    </div>
    <script>
        hide=0;
        function toggle_log(){
            if(hide==0){
                hide=1;
                document.getElementById('thelog').style.display='block';
            }else{
                hide=0;
                document.getElementById('thelog').style.display='none';
            }
        }
    </script>
    <style>
        .line_log{
            padding: 5px;
            text-align: center;
            height: 75px;
            
            background: #acc5c8;
            background: #f5f5f5;
            border: 1px solid black;
            border-radius: 5px;
            overflow-y: scroll;
            margin-bottom: 5px;
        }
        .log_content{
            font-size:10px;
        }
    </style>
    <?php
}



?>