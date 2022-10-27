<?php

function manage_post_machine($db){
    
    if(!empty($_GET['debug'])){
        $_SESSION['temp']['debug']=$_GET['debug'];
    }
    
    if($_SESSION['temp']['debug']=='1'){
        show($_POST);
    }
 
    if(empty($_SESSION['temp']['view'])){
        
        $_SESSION['temp']['view']='View Live';
        
    }
    if(!empty($_POST['view'])){
        $_SESSION['temp']['view']=$_POST['view'];
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

    
    
    $_POST['view']=$_SESSION['temp']['view'];
    $_POST['entry_type']=$_SESSION['temp']['entry_type'];
    $_POST['machine_name']=$_SESSION['temp']['machine_name'];
    
    

}



function general_view_machine($db){
   
    echo'<div class="row ">';
       
        echo'<div  class="col-md-7 col-lg-7">';
            
            echo'<div id="here">';
           if($_POST['view']=='View Live'){
            show_view_live_temptable($db);
           }elseif($_POST['view']=='view_all'){
            show_all_temptable($db);
           }else{
            show_view_live_temptable($db);
           }
           echo'</div>';
        echo'</div>';
        echo'<div  class="col-md-1 ">';
            
        echo'</div>';
        echo'<div  class="col-md-3 col-lg-3 ">';
            echo'<div id="all_stats">';
            if($_POST['view']=='View Live'){
                if(!empty($_SESSION['temp']['pin_to_show'] )){
                   show_view_live_temptable_details($db,$_POST['temptable_MAC'],$_POST['temptable_pin']);
                }
               
            }
            echo'</div>';
        echo'</div>';

        echo'<div class="col-sm-4 dialog-box" >';
        
        
        
        echo'</div>';
    echo'</div>';
    echo'<div class="col-sm-4 hidden-box" >';
        
        
        
    echo'</div>';
    
}
function navbar_machine($db){

   
    echo'<div class="row navbar navbar_injury">';
        echo'<form method="POST">';
        echo'<div class="col-sm-1 ">';
        //echo'<button type="submit" name="type" value="CreateNewReport"  class="btn btn-primary injury_button" >Create new Report</button>';
        
        //if($_SESSION['temp']['id']=='CorentinHillion'){
        echo'<input type="submit" name="view" value="View Live"  class="btn btn-primary injury_button" onclick="submit();" >';
       // }
        echo'</div>';
        echo'<div class="col-sm-1 ">';
        if($_SESSION['temp']['id']=='CorentinHillion'){
        //echo'<input type="submit" name="view" value="Clean Data"  class="btn btn-primary injury_button" onclick="submit();" >';
         }
       //
        echo'</div>';
        echo'<div class="col-sm-1 ">';
        //if($_SESSION['temp']['id']=='CorentinHillion'){
        echo'<input type="submit" name="view" value="view_all"  class="btn btn-primary injury_button" onclick="submit();" >';
        //}
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


function show_all_temptable($db){
    echo'<div class="row machine_header">';
        echo'<form method="POST"><div class="col-sm-6 col-md-3"><Select name="machineallocation_MAC" oninput="submit();">';
        echo'<option ';
        if(empty($_POST['machineallocation_MAC'])){echo' selected ';}
        echo'></option>';
        foreach(get_list_MAC($db) as $MAC){
            echo'<option value="'.$MAC['machineallocation_MAC'].'"';
            if($_POST['machineallocation_MAC']==$MAC['machineallocation_MAC']){echo' selected ';}
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
function show_view_live_temptable($db){
    
        $i=1;
    foreach(get_last_temptable($db) as $entry){
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


function get_last_temptable($db){
 
    $query='SELECT temptable_MAC, max(temptable_timetag) as lasttimetag,temptable_pin
      FROM temptable_v2
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
function get_all_temptable($db){
    $filter='WHERE 1=1';
    if(!empty($_POST['machineallocation_MAC'])){
        $filter='WHERE machineallocation_MAC=\''.$_POST['machineallocation_MAC'].'\'';
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
function get_list_MAC($db){
    $query='SELECT distinct machineallocation_MAC,machine_name
    FROM machine_allocation 
    left join machine on machine_id= machineallocation_machineid
    order by machineallocation_MAC desc
    
    
  ';//where machineevent_entry like \'%Door%\'
  $sql = $db->prepare($query); 
  //show($query);
  $sql->execute();

  $row=$sql->fetchall();
  return $row;
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
    return $result['machinepin_pindescription'];
}



function show_time($secondes){
    $secondes=round($secondes,2);
    if($secondes==0){
        $return='-';
    }elseif($secondes<60){
        $return=$secondes.' sec';
    }elseif($secondes<3600){
        $minutes=floor($secondes/60);
        $secondes=$secondes-$minutes*60;
        $return=$minutes.' min '.$secondes.' sec';
    }elseif($secondes<(3600*24)){
        $hours=floor($secondes/3600);
        $secondes=$secondes-$hours*3600;
        $minutes=floor($secondes/60);
        $secondes=$secondes-$minutes*60;
        $return=$hours.' h '.$minutes.' min '.$secondes.' sec';
    }else{
        $days=floor($secondes/3600/24);
        $secondes=$secondes-$days*3600*24;
        $hours=floor($secondes/3600);
        $secondes=$secondes-$hours*3600;
        $minutes=floor($secondes/60);
        $secondes=$secondes-$minutes*60;
        $return=$days.' days '.$hours.' h '.$minutes.' min '.$secondes.' sec';
    }

    return $return;
}
function opposite($binary){
    if($binary==0){
        return 1;
    }
    if($binary==1){
        return 0;
    }
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
    
    
    ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <div id="chart_timeline_<?php echo $id;?>"></div>
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







?>