<?php

function manage_POST_scanning_MIS($db){
    show_debug();
    if (!empty($_POST['barcode'])){
        if ((substr($_POST['barcode'], 0, 1)=='m' OR substr($_POST['barcode'], 0, 1)=='M')){
            $_POST['MIS_number']=$_POST['barcode'];
        }
        if ((substr($_POST['barcode'], 0, 1)=='o' OR substr($_POST['barcode'], 0, 1)=='O')){
            header('Location:scanning.php');
            
        }
        if ((substr($_POST['barcode'], 0, 1)=='x' OR substr($_POST['barcode'], 0, 1)=='X')){
            if ((substr($_POST['barcode'], 1, 1)=='o' OR substr($_POST['barcode'], 1, 1)=='O')){
                header('Location:scanning.php');
                
            }
            
        }
       
    }
}

function general_view_scanning_MIS($db){?>
    <div class="container" style="padding-top:20px;">
        <div class="col-xs-3"><?php show_scanner_MIS();	?><br></div>
        <div class="col-xs-9"><?php show_MIS_info($db);	?></div>
        <div class="col-xs-12"><?php show_MIS_drawing($db);	?></div>
            
        
        
    </div>
    <style>
        .container{
            text-align: center;
        }
    </style>
    <?php
}

function show_scanner_MIS(){?>
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
	    <div class="row"><input class="form-control " type="text" id="barcode" placeholder="SCAN MIS SHEET" name="barcode" ></div>
    </form>
    <?php
}

function show_MIS_info($db){
    if(!empty($_POST['MIS_number'])){
        $info=get_MIS_info($db,$_POST['MIS_number']);?>
        <div class="col-xs-3"><?php echo $info['ManufactureIssueNumber']	?></div>
        <div class="col-xs-3"><?php echo $info['Code']	?></div>
        <div class="col-xs-3"><?php echo $info['ManufactureOrderNumber']	?></div>
        <div class="col-xs-3"><?php echo number_format($info['BaseQuantityOrdered']-$info['QTY_MADE'])?> left</div>
        <?php
    }
}
function show_MIS_drawing($db){
    if(!empty($_POST['MIS_number'])){;
        //show(get_all_doc($db,$_POST['Code']));
        foreach(get_all_doc($db,$_POST['Code']) as $document){
            $filepath="ressource_v2/MD&S/".$document['document_filename'];
            ?>
            <div id='outerdiv<?php echo $document['document_id']?>'>
                <iframe class="scrollDiv" id='inneriframe<?php echo $document['document_id']?>' src="<?php echo $filepath?>"  style="width:100%;border:none;overflow:hidden;" ></iframe>
            </div>
            <script>
                "use strict";

                var page = document.querySelector('#outerdiv<?php echo $document['document_id']?>'),
                height = document.documentElement.clientHeight,
                frame = document.querySelector('#inneriframe<?php echo $document['document_id']?>');

                page.style.height = height + 'px';
                frame.style.height = height + 'px';
            </script>
            <?php
        }
        
        ?>
    
        
    <?php
    }
}

function get_MIS_info($db,$MIS_number){
    $query="SELECT MIS_List.ManufactureIssueNumber
    ,MIS_List.Code
    ,MIS_List.WorkArea
    ,MIS_List.[PRODUCT FAMILY]
    ,MIS_List.BaseQuantity
    ,MIS_List.ManufactureOrderNumber
    ,MIS_List.IsPosted
    ,MIS_List.ManufactureOn
    ,MIS_List.ManufactureBefore
    ,MIS_List.ManufactureCompletedOn
    ,MO_List.QTY_MADE
    ,MO_List.BaseQuantityOrdered
	
    FROM MIS_List
    LEFT JOIN MO_List on MIS_List.ManufactureOrderNumber=MO_List.ManufactureOrderNumber
    where  ManufactureIssueNumber='$MIS_number'";
   //show($query);
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetch();
    $_POST['Code']=$row['Code'];
    return $row;
}
function get_all_doc($db,$Product_code){
   
        $option2="and document_active=1";
    
   
    
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
    LEFT JOIN doc_link on [document_number]=[doclink_docnumber]
    WHERE document_type='MD&S' and document_active=1 and doclink_productcode like '$Product_code'
    order by document_upload,CAST(document_number AS int) DESC";
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
    $row=$sql->fetchall();
      
  return $row;
}