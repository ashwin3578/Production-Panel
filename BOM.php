

<?php $starttime = microtime(true); // Top of page
$page_title='BOM';
$title_top='BOM';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/issue_log.css">	
<link rel="stylesheet" href="css/roster.css">

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<?php $_SESSION['temp']['addscan'] = array();
	 include ('navbar.php'); 
  include ('function_framework.php'); 
  include ('function_check.php'); 
  
  echo '<link rel="stylesheet" href="css/checkstock.css?v='.time().'">';

if(empty($_POST)){
    $thecode='PHM4-6-4/0 B';
    
    
  }else{
    //show($_POST);
   $thecode=$_POST['code'];
   
  }
  manage_POST_check($db);
  navbar_check($db,$thecode);
 
    if($_POST['view']=='stock_analysis'){
        echo'<div class="row">';
           
            echo'<div class="col-sm-12 show_org" style="overflow-x: scroll;">';
            echo'<center><img src="img/loading.gif" width="100" height="100"></center>';
            echo'</div>';
            echo'<div class="col-sm-4 showstockanalysis" >';
            echo'<br><br><br><br><br><br><center><img src="img/loading.gif" width="100" height="100"></center>';
            echo'</div>';
            
        echo'</div>';
        echo'<div class="postinfo" >';
        //showtimes($time);
        echo'</div>';
        

        ajax_load([['showstockanalysis',"'ok'"],['code',"'".$thecode."'"]],"check-ajax.php","showstockanalysis",'empty().append(html)');
        ajax_load([['show_org2',"'ok'"],['code',"'".$thecode."'"]],"check-ajax.php","show_org",'empty().append(html)');
       

    }else{
        echo'<div class="row">';
            echo'<div class="col-sm-6 showBOM" >';
            echo'<br><br><br><br><br><br><center><img src="img/loading.gif" width="100" height="100"></center>';
            echo'</div>';
           
            echo'<div class="col-sm-4 showpiechart">';
            showpiechart($db,$thecode);
            echo'</div>';
            
            
        echo'</div>';
        echo'<div class="row show_org" style="overflow-x: scroll;">';
            echo'<center><img src="img/loading.gif" width="100" height="100"></center>';
            
            echo'</div>';
        echo'<div class="postinfo" >';
        //showtimes($time);
        echo'</div>';
        

        ajax_load([['showBOM',"'ok'"],['code',"'".$thecode."'"]],"check-ajax.php","showBOM",'empty().append(html)');
        //ajax_load([['showpiechart',"'ok'"],['code',"'".$thecode."'"]],"check-ajax.php","postinfo",'empty().append(html)');
        
       
        ajax_load([['showStats',"'ok'"],['code',"'".$thecode."'"]],"check-ajax.php","showStats",'empty().append(html)');
        ajax_load([['show_org',"'ok'"],['code',"'".$thecode."'"]],"check-ajax.php","show_org",'empty().append(html)');
    }
    ajax_load([['showproductlist',"'ok'"],['view',"'".$_POST['view']."'"]],"check-ajax.php","showproductlist",'empty().append(html)');

    
    
   

    echo'<script>
    document.getElementById(\'details-manufacturable\').style.display = \'none\';
        $(".details-manufacturable").css(\'display\', \'none\');
        function show_detail2(Component,Class){
            document.getElementById(\'details-manufacturable\').style.display = \'block\';
            
            $("."+Class).css(\'display\', \'block\');
        
        }
        function dont_show_detail2(Component,Class){
            document.getElementById(\'details-manufacturable\').style.display = \'none\';
        
            $("."+Class).css(\'display\', \'none\');
            
        }



    </script>';


	?>
    <div class="row">
  
  
   
  
  
   
	
</div>


