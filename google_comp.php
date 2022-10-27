

<?php $starttime = microtime(true); // Top of page
$page_title='Test Page';
$title_top='Test Page';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/issue_log.css">	
<link rel="stylesheet" href="css/roster.css">

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	<?php include ('function_framework.php'); ?>
	<?php include ('function_roster.php'); ?>
	<script>
   $( function() {
    $( "#dialog" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 300
      },
      hide: {
        effect: "blind",
        duration: 300
      }
    });
 
    $( "#opener" ).on( "click", function() {
      $( "#dialog" ).dialog( "open" );
    });
  } );
  </script>
  <div id="dialog" title="Basic dialog">
  
</div>
<input id="opener" type="button">
	<?php create_css ($db,'allocationwork');
	
	$f = fopen( 'php://stdin', 'r' );
    $T= fgets( $f );
    $i=1;
    for($i=1;$i<=$T;$i++){
    	//$testcases=explode(" ", fgets( $f ));
    	$N=fgets( $f );
        $S=fgets( $f );
        $Solution=count_x($N,$S);
        
    	
    	//echo "N:".$N."\nS:".$S."\n";
    	echo "Case #".$i.": ".$Solution."\n";
    }
    
    
function count_x($N,$S){
    $x=0;
    $S2=str_split($S);
    //echo "S2: ".$S2[0]."\n";
    //echo "S #: ".$S[0]."\n";
    //echo "S #: ".$S."\n";
    for($i=0;$i<=$N-1;$i++){
        if($S2[$i]==0){
            $x=$x+find_closer($i,$S,$N);
            
        }
   
    }
    return $x;
}

function find_closer($i,$S,$N){
    $j=1;
    //test i+1 and i-1
    $S2=str_split($S);
    while($j<$N){
        //echo "fincloser i:".$i." j:".$j.": ".$S2[$i-$j]." ".$S2[$i+$j]."\n";
        if($S2[$i+$j]==1 or $S2[$i-$j]==1){
            $x= $j;
            $j=$N+1;
             return $x;
        }
        $j++;
    }
    return $x;
    

}
	
	$endtime = microtime(true); // Bottom of page
	//printf("Page loaded in %f seconds", $endtime - $starttime );
	//update_div('Moulding-title','dont','Test');
	?>
	
	
	


	
	
	
</div>


<?php


?>