<?php


function manage_POST_document($db){
    show_debug();
    if((!empty($_POST['type']))&&$_POST['type']=='eng_drawing')
	{
		if($_SESSION['temp']['eng_drawing']==false){
			$_SESSION['temp']['eng_drawing']=true;
		}
		else{
			$_SESSION['temp']['eng_drawing']=false;
		}
	}
	
	
	
	//show($_POST);
	if(!empty($_POST['family'])){
		$_SESSION['temp']['doc']['family']=$_POST['family'];
		
		
	}
	
	if(!empty($_POST['search'])){
		$_SESSION['temp']['doc']['family']='All';
		$_SESSION['temp']['doc']['workarea']='All';
		
	}
	
	
	if (empty($_SESSION['temp']['doc']['family'])){
			$_SESSION['temp']['doc']['family']='All';
			
		}
		
	if(!empty($_POST['workarea'])){
		$_SESSION['temp']['doc']['workarea']=$_POST['workarea'];
		
		
	}
	if (empty($_SESSION['temp']['doc']['workarea'])){
			$_SESSION['temp']['doc']['workarea']='All';
			
		}
    if($_POST['action']=='show_where_doc_is_used'){
        show_where_doc_is_used_details($db,$_POST['Code']);
    }
	
}
function main_view_document($db){
   
    if($_SESSION['temp']['eng_drawing']==false){
        show_where_doc_is_used();?>
         <br>
        <div class="col-sm-1">
        <!--<h3><center>Product Family</center></h3>-->
        <?php show_productfamily($db,$_SESSION['temp']['doc']['workarea']);?>
        </div>
        
        <div class="col-sm-1" >
        <!--<h3><center>Work Area</center></h3>-->
        <?php show_workarea($db,'All',$_SESSION['temp']['doc']['family']);?>
        </div>
        
        <div class="col-sm-10">
        <!--<h3><center>Product List</center></h3>-->
        <?php show_doc($db,$_SESSION['temp']['doc']['workarea'],$_SESSION['temp']['doc']['family']);?>
        </div>
        <?php
    }
    else{
        scan_files_ofFolder();
    }	
}

function show_doc($db,$workarea='All',$family='All'){
    ?>
    <div class="row header_document">
        <div class="col-sm-5">Product</div>
        <div class="col-sm-1">MD&S</div>
        <div class="col-sm-1">Install. Inst.</div>
        <div class="col-sm-1">IPIP&R</div>
        <div class="col-sm-1">IGIP&R</div>
        <div class="col-sm-1">Work Inst.</div>
        <div class="col-sm-1">BOM</div>
    </div>


    <?php
    
    if($family<>'All'){
        $familyfilter=' AND PRODUCT_FAMILY=\''.$family.'\' ';
    }
    if($workarea<>'All'){
        $workareafilter=' AND WorkArea=\''.$workarea.'\' ';
    }
    $namefilter='';
    if(!empty($_POST['search'])){
        $namefilter=' AND Product_code LIKE \'%'.$_POST['search'].'%\' ';
    }
    
    $query="SELECT TOP 500 * FROM dbo.List_Document 		
    WHERE 1=1 $familyfilter $workareafilter $namefilter ORDER BY Product_Code ASC";
    
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetchall();
    
    $y=1;?>
    <script>
    var mouseX;
    var mouseY;
    $(document).mousemove( function(e) {
    mouseX = e.pageX; 
    mouseY = e.pageY;
    });  
    function show_wheredocisused(e,mds){
        document.getElementById('window_where_doc').style.display = "block";
        document.getElementById('window_where_doc').style.top = mouseY+"px" ;
        document.getElementById('window_where_doc').style.left = mouseX+"px" ;
        var request =$.ajax({
            type:'POST',
            url:'document_ajax.php',
            data: {Code:mds,action:'show_where_doc_is_used'},
            success:function(html){
                $('.window_where_doc').empty().append(html);
            }
        });
    }
    function hide_wheredocisused(){
        
        document.getElementById('window_where_doc').style.display = "none";
    }
    </script>
    <?php
    foreach ($row as &$logentry){?>
        <div class="row row_document" >
            
            <div class="col-sm-5"><?php echo$logentry['Product_Code']?></div>
            
            <div class="col-sm-1">
            <?php
                
            if(!empty($logentry['Open MDS_File'])){
                
                if(file_exists('/var/www/html/'.str_replace('\\', '/', $logentry['Open MDS_File']))){
                    $path=$logentry['Open MDS_File'];
                    $caption='MD&S';						
                }else{					
                    $logentry['Open MDS_File']=substr($logentry['Open MDS_File'], 0, -3)."PDF";	
                    $path=$logentry['Open MDS_File'];	
                    if(file_exists('/var/www/html/'.str_replace('\\', '/', $logentry['Open MDS_File']))){
                        $caption='MD&S';
                    }else{
                        $caption='<div class="alert-danger" >MD&S!</div>';
                    }
                }
                ?>
                <a target="blank"  
                href="<?php echo $path?>"
                onmouseout="hide_wheredocisused();" 
                onmouseover="show_wheredocisused(this,'<?php echo $logentry['Product_Code']?>');">
                <?php echo $caption?>
                </a>
                <?php
                
                
            }?>
            </div>

        
        
            <div class="col-sm-1">
            <?php
            if(!empty($logentry['Open Installn Inst_File'])){
                
                if(file_exists('/var/www/html/'.str_replace('\\', '/', $logentry['Open Installn Inst_File']))){?>
                    <a target="blank"  href="<?php echo$logentry['Open Installn Inst_File']?>">Installation Instr.</a>	
                    <?php
                }else
                {
                $logentry['Open Installn Inst_File']=substr($logentry['Open Installn Inst_File'], 0, -3)."PDF";	
                if(file_exists('/var/www/html/'.str_replace('\\', '/', $logentry['Open Installn Inst_File']))){?>
                    
                    <a target="blank"  href="<?php echo$logentry['Open Installn Inst_File']?>">Installation Instr.</a>
                    <?php		
                    }else
                    {?>
                    <a target="blank"  href="<?php echo$logentry['Open Installn Inst_File']?>"><div class="alert-danger" >Installation Instr.!</div></a>
                    <?php
                    }	
                }
                
                
            }?>
            </div>
            
            <div class="col-sm-1">
            <?php
            if(!empty($logentry['Open IPIPR_File'])){
                if(file_exists('/var/www/html/'.str_replace('\\', '/', $logentry['Open IPIPR_File']))){?>
                    
                    <a target="blank"  href="<?php echo$logentry['Open IPIPR_File']?>">IPIP&R</a>	
                    <?php
                }else
                {
                $logentry['Open IPIPR_File']=substr($logentry['Open IPIPR_File'], 0, -3)."PDF";	
                if(file_exists('/var/www/html/'.str_replace('\\', '/', $logentry['Open IPIPR_File']))){?>
                    
                    <a target="blank"  href="<?php echo$logentry['Open IPIPR_File']?>">IPIP&R</a>
                    <?php 						
                    }else
                    {?>
                    <a target="blank"  href="<?php echo$logentry['Open IPIPR_File']?>"><div class="alert-danger" >IPIP&R!</div>	</a>
                    <?php 
                    }	
                }
                
            }?>
            </div>
            
            <div class="col-sm-1">
            <?php
            if(!empty($logentry['Open IGIPR_File'])){
                if(file_exists('/var/www/html/'.str_replace('\\', '/', $logentry['Open IGIPR_File']))){?>
                    <a target="blank"  href="<?php echo$logentry['Open IGIPR_File']?>">IGIP&R	</a>
                <?php
                }else
                {
                $logentry['Open IGIPR_File']=substr($logentry['Open IGIPR_File'], 0, -3)."PDF";	
                if(file_exists('/var/www/html/'.str_replace('\\', '/', $logentry['Open IGIPR_File']))){?>
                    
                    <a target="blank"  href="<?php echo$logentry['Open IGIPR_File']?>">IGIP&R	</a>
                    <?php					
                    }else
                    {?>
                    <a target="blank"  href="<?php echo$logentry['Open IGIPR_File']?>"><div class="alert-danger" >IGIP&R!</div>	</a>
                    <?php
                    }	
                }
                
            }?>
            </div>
            
            <div class="col-sm-1">
            <?php
            if(!empty($logentry['Open Work Inst_File'])){
                if(file_exists('/var/www/html/'.str_replace('\\', '/', $logentry['Open Work Inst_File']))){?>
                    <a target="blank"  href="<?php echo $logentry['Open Work Inst_File']?>">WI</a>
                    <?php
                }else
                {
                $logentry['Open Work Inst_File']=substr($logentry['Open Work Inst_File'], 0, -3)."PDF";	
                if(file_exists('/var/www/html/'.str_replace('\\', '/', $logentry['Open Work Inst_File']))){
                    $caption='WI';
                }else{
                $caption='<div class="alert-danger" >WI!</div>';
                }
                ?>
                <a target="blank"  
                href="<?php echo $logentry['Open Work Inst_File']?>"
                ><?php echo$caption?></a>
                <?php						
                }
                    
            } ?>
            </div>
            <div class="col-sm-1">
            <div class="glyphicon glyphicon-arrow-right" onClick="document.getElementById('form-id<?php echo$logentry['Product_Code']?>').submit();">
                
            </div>
            
            <form id="form-id<?php echo$logentry['Product_Code']?>" method="POST" action="BOM.php">
                <input type="hidden" name="code" value="<?php echo$logentry['Product_Code']?>">
            </form>
            </div>
            
            </div>
            <?php
            $y++;
        }
        if($y>500){ ?>
        <div class="row row_document" ><div class="col-sm-5">(...) - 500 rows max</div></div><?php
        }
		 
		 
		
}

function show_productfamily($db,$workarea='All',$family='All'){
    if($family<>'All'){
        $familyfilter='AND PRODUCT_FAMILY=\''.$family.'\'';
    }
    
    if($workarea<>'All'){
        $workareafilter='AND WorkArea=\''.$workarea.'\'';
    }
    $namefilter='';
    if(!empty($_POST['search'])){
        $namefilter=' AND Product_code LIKE \'%'.$_POST['search'].'%\' ';
    }
    
    $query="SELECT DISTINCT PRODUCT_FAMILY FROM dbo.List_Document 		
    WHERE 1=1 $familyfilter $workareafilter $namefilter	
    ORDER BY PRODUCT_FAMILY ASC ";
    
    $sql = $db->prepare($query); 
    $sql->execute();

    $row=$sql->fetchall();?>
    
    <div class="row row_document<?php if(!empty($_SESSION['temp']['doc']['family'])&&$_SESSION['temp']['doc']['family']=='All'){echo' bg-primary text-white';}?>" 
    onclick="document.forms['form-family'].submit();" ><center>All</center>
        <form  id="form-family" method="post">
        <input type="hidden" id="family" name="family" value="All">
        </form>
    </div>
    
    <?php
        $i=0;
        foreach ($row as &$logentry){?>
            
        
            <div class="row row_document<?php if(!empty($_SESSION['temp']['doc']['family'])&&$_SESSION['temp']['doc']['family']==$logentry['PRODUCT_FAMILY']){ echo' bg-primary text-white ';}?>" 
            onclick="document.forms['form-family-<?php echo $i?>'].submit();" ><center><?php echo$logentry['PRODUCT_FAMILY'] ;?></center>
            <form  id="form-family-<?php echo $i?>" method="post">
            <input type="hidden" id="family" name="family" value="<?php echo$logentry['PRODUCT_FAMILY']?>">
            </form>
        </div>
        
            <?php
            $i++;
        }
        
        
		
}

function show_workarea($db,$workarea='All',$family='All'){
    if($family<>'All'){
        $familyfilter='AND PRODUCT_FAMILY=\''.$family.'\'';
    }
    
    if($workarea<>'All'){
        $workareafilter='AND WorkArea=\''.$workarea.'\'';
    }
    $namefilter='';
    if(!empty($_POST['search'])){
        $namefilter=' AND Product_code LIKE \'%'.$_POST['search'].'%\' ';
    }
    
    $query="SELECT DISTINCT WorkArea FROM dbo.List_Document 
    WHERE 1=1 $familyfilter $workareafilter $namefilter
    ORDER BY WorkArea ASC ";
    
    $sql = $db->prepare($query); 
    $sql->execute();
    $row=$sql->fetchall();
    ?>
    
    
    
        <div class="row row_document<?php if(!empty($_SESSION['temp']['doc']['workarea'])&&$_SESSION['temp']['doc']['workarea']=='All'){ echo' bg-primary text-white';}?>" 
        onclick="document.forms['form-workarea'].submit();" ><center>All</center>
            <form  id="form-workarea" method="post">
                <input type="hidden" id="workarea" name="workarea" value="All">
            </form>
        </div>
    
    <?php $i=0;
        foreach ($row as &$logentry){?>
        
            <div class="row row_document<?php if(!empty($_SESSION['temp']['doc']['workarea'])&&$_SESSION['temp']['doc']['workarea']==$logentry['WorkArea']){ echo' bg-primary text-white';}?>" 
            onclick="document.forms['form-workarea-<?php echo$i?>'].submit();" ><center><?php echo$logentry['WorkArea'];?></center>
                <form  id="form-workarea-<?php echo$i?>" method="post">
                    <input type="hidden" id="workarea" name="workarea" value="<?php echo$logentry['WorkArea']?>">
                </form>
            </div>
            <?php
            $i++;
        }
		 
		 
		 
		
}

function all_filter_doc(){
	$workareafilter='';
	if ($_SESSION['temp']['doc']['workarea']<>'All'){
		$workareafilter=' AND workarea=\''.$_SESSION['temp']['doc']['workarea'].'\' ';
	}
	$familyfilter='';
	if ($_SESSION['temp']['doc']['family']<>'All'){
		$familyfilter=' AND PRODUCT_FAMILY=\''.$_SESSION['temp']['doc']['family'].'\' ';
	}
	
	$filter['workareafilter']=$workareafilter;
	$filter['familyfilter']=$familyfilter;
		
	return $filter;
}

function show_where_doc_is_used(){
    ?>
    <div id="window_where_doc" class="window_where_doc">
        
       

    </div>

        
    <style>
        .window_where_doc{
            position: absolute;
            background: white;
            
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
        .window_where_doc_header{
            border-radius: 5px;
            background-color: rgba(0,0,0,.03);
            border-bottom: 1px solid rgba(0,0,0,.125);
        }
    </style>
    <?php
}
function show_where_doc_is_used_details($db,$code){
    $allproduct=get_where_doc_is_used($db,$code);
    ?>
    <div id="window_where_doc_header" class="window_where_doc_header">MD&S <?php echo substr($allproduct[0]['Open MDS_File'], 41, -4);?></div>
    <br>
        <div class="row">
            <?php foreach($allproduct as $product){?>
                <div class="row"><?php echo$product['Product_Code'];?></div>
            <?php } ?>
        </div>
    <?php
}
function show_navbar_document(){
	
	echo'<div class="row">';
		
		echo'<form method="POST">';
		echo' <div class="col-sm-3">';
		if($_SESSION['temp']['eng_drawing']==true){
				$caption1='Product Documents';
				$caption2='';
				$icon='chevron-right';
			}
			else{
				$caption1='';
				$caption2='Engineering Drawing';
				$icon='chevron-left';
			}
			echo'<button type="submit" name="type" value="eng_drawing"  class="btn btn-default" >';
			echo $caption1.' <span class="glyphicon glyphicon-'.$icon.'" > </span> '.$caption2.'';
			echo'</button>';
		echo'</div>';
		echo '</form>';
		echo'<form method="POST">';
		echo'<div class="col-sm-3"><input class="form-control" placeholder="Search for a Product Code" type="text" id="search"  name="search" "></div>';
		echo' <div class="col-sm-1"><button type="submit" class="btn btn-default" >
								<span class="glyphicon glyphicon-search" ></span>
								</button></div>';
		echo '</form>';
		
		echo'</div>';
}

function scan_files_ofFolder(){
		echo'<br><div class="row">';
			$defaultdir='/resources/Tool_drawing';
			$root='/var/www/html'.$defaultdir;
			$path = null;
			if (!empty($_GET)and isset($_GET['file'])) {
				$path = $_GET['file'];
				if (!is_in_dir($_GET['file'], $root)) {
					$path = null;
				} else {
					$path = '/'.$path;
				}
			}
			
			// if (is_file($root.$path)) {
				// readfile($root.$path);
				// return;
			// }
			echo'<div class=" col-sm-4 foldercss">';
			if ($path and $_GET['file']<>'') {
				
				echo'<a href="?file='.urlencode(substr(dirname($root.$path), strlen($root)+1 )).'">';
				echo'<div class="col-sm-12 col-md-6">';
				echo'<button type="submit" name="type" value="eng_drawing"  class="btn btn-default folder" style="width: 100%;" >';
				echo '<span class="glyphicon glyphicon-arrow-up" > </span> ';
				echo'</button>';
				echo'</div>';
				echo '</a>';
				
				}
			
			
			foreach (glob($root.$path.'/*') as $file) {
				
				if(($file != '.') && ($file != '..')){
				  if(is_dir($dir.'/'.$file)){
					 $directories[]  = $file;

				  }else{
					 $files_list[]    = $file;

				  }
				}
			}	
				
				//folder-open
			foreach($directories as $directory){
				
				$file = realpath($directory);
				
				$link = substr($file, strlen($root)+1 );
				
				echo'<a href="?file='.urlencode($link).'">';
				
				echo'<div class=" col-sm-12 col-md-6 ">';
				echo'<button type="submit" name="type" value="eng_drawing"  class="btn btn-default folder" style="width: 100%;" >';
				echo '<span class="glyphicon glyphicon-folder-open" > </span>&nbsp;&nbsp;   ';
				if (strlen (basename($file))>16){echo'<small>';}
				if (strlen (basename($file))>24){echo'<small>';}
				if (strlen (basename($file))>32){echo'<small>';}
				echo basename($file);
				if (strlen (basename($file))>16){echo'</small>';}
				if (strlen (basename($file))>24){echo'</small>';}
				if (strlen (basename($file))>32){echo'</small>';}
				echo'</button>';
				echo'</div>';
				echo '</a>';
				
				
			  // echo '<a href="?file='.urlencode($link).'">'.basename($file).'</a><br />';   
			}	
			echo'</div>';
			
			echo'<div class="col-sm-6 filecss">';	
			foreach($files_list  as $file_list){
				$file = realpath($file_list);
				
				$link = substr($file, strlen($root)+1 );
				
				if(substr($file, -3)=='pdf' or substr($file, -3)=='PDF'){
					$size=4;
					if (strlen (basename($file))>16){$size=6;}
					echo'<div class="col-sm-12 col-md-'.$size.' col-lg-'.$size.'">';
					echo'<a target="blank" href="'.$defaultdir.'/'.$link.'"><button type="submit" name="type" value="eng_drawing"  class="btn btn-default folder" style="width: 100%;">';
					
					if (strlen (basename($file))>16){echo'<small>';}
					if (strlen (basename($file))>24){echo'<small>';}
					if (strlen (basename($file))>32){echo'<small>';}
					echo basename($file);
					if (strlen (basename($file))>16){echo'</small>';}
					if (strlen (basename($file))>24){echo'</small>';}
					if (strlen (basename($file))>32){echo'</small>';}
					echo'</button>';
					echo '</a>';
					
					
					
					echo'</div>';
				}
			}
			echo'</div>';	
				// $file = realpath($file);
				
				// $link = substr($file, strlen($root)+1 );
				
				
				
				
				
				// if((substr($file, -3)=='pdf' or substr($file, -3)=='PDF'or substr($file, -4,1)<>'.')and(substr($file, -5,1)<>'.')and(substr($file, -3,1)<>'.')){
					// echo '<a href="?file='.urlencode($link).'">'.basename($file).'</a><br />';
					
					
					//target="blank"
					
					// }
			//}
			echo'</div>';
}

function get_where_doc_is_used($db,$code){
    $query="SELECT *
    FROM [barcode].[dbo].[List_Document]
    left join(SELECT [Open MDS_File] as mds_to_filter FROM dbo.List_Document 		
  WHERE Product_Code='$code') as temp on mds_to_filter=[Open MDS_File]
  where mds_to_filter is not null";
    
    $sql = $db->prepare($query); 
    $sql->execute();
    //show($query);
    $row=$sql->fetchall();
    //show($row);
    return $row;
}


function is_in_dir($file, $directory, $recursive = true, $limit = 1000) {
				$directory = realpath($directory);
				$parent = realpath($directory.'/'.$file);
				// show($directory);
				// show($parent);
				// show($file);
				$i = 0;
				while ($parent) {
					if ($directory == $parent) return true;
					if ($parent == dirname($parent) || !$recursive) break;
					$parent = dirname($parent);
				}
				return false;
			}
			
			
			


?>