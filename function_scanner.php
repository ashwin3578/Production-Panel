<?php

function manage_POST_scanning($db){
    if(empty($_POST)){
        unset($_SESSION['temp']['jobnumber']);
        unset($_SESSION['temp']['operator']);

    }
    $_GET=$_POST;
    if (!empty($_GET['barcode'])){
		if ((substr($_POST['barcode'], 0, 1)=='x' OR substr($_POST['barcode'], 0, 1)=='X')){
            if ((substr($_POST['barcode'], 1, 1)=='m' OR substr($_POST['barcode'], 1, 1)=='m')){
                header('Location:scanning_MIS.php');
                
            }
            
        }
		//max secondes between 2 scans = 30secondes
		$max_seconds=60;
		
		if(empty($_SESSION['temp']['operator']) ){$_SESSION['temp']['operator']='';}
		if(empty($_SESSION['temp']['lastscan'])){$_SESSION['temp']['lastscan']=time();}
		
		//show(time()-$_SESSION['temp']['lastscan']);
		if(time()-$_SESSION['temp']['lastscan']>$max_seconds){
			$_SESSION['temp']['operator']='';
			$_SESSION['temp']['jobnumber']='';
		}
			if(substr($_GET['barcode'], 0, 1)=='o' or substr($_GET['barcode'], 0, 1)=='O'){
				$_SESSION['temp']['operator']=strtoupper(substr($_GET['barcode'], 3, 3));
				if(strlen($_SESSION['temp']['operator'])<>3){
					unset($_SESSION['temp']['operator']);
				}
				$_SESSION['temp']['lastscan']=time();
			}
			elseif ((substr($_GET['barcode'], 0, 1)=='m' OR substr($_GET['barcode'], 0, 1)=='M')){
				
				if(strlen($_GET['barcode'])>10){
					$_SESSION['temp']['jobnumber']='';
					$_SESSION['temp']['lastscan']=time();
	
				}
				elseif(!empty($_SESSION['temp']['operator']) ){
					$base = array("!","@","#","$","%","^","&","*","(",")");
					$replace   = array("1","2","3","4","5","6","7","8","9","0");

					$_GET['barcode'] = str_replace($base, $replace, $_GET['barcode']);
					
					$_SESSION['temp']['jobnumber']=$_GET['barcode'];
					$_SESSION['temp']['lastscan']=time();
				}
				
				
			}
		
		 	//show($_SESSION['temp']);
		
		if(!empty($_SESSION['temp']['jobnumber']) ){
			if(!empty($_SESSION['temp']['operator']) ){
				//savefromscanning($db,$_SESSION['temp']['operator'],$_SESSION['temp']['jobnumber'],time());
				savescan($db,$_SESSION['temp']['operator'],$_SESSION['temp']['jobnumber'],time());
				//log_enter_scan($db,$_SESSION['temp']['operator'],$_SESSION['temp']['jobnumber'],time());
				unset($_SESSION['temp']['jobnumber']);
                //unset($_SESSION['temp']['operator']);

				//show($_SESSION['temp']);
				
			}
		}
			
	}
}

function showscanner($db){
    echo'<div class="row"></div>';
		
		
		echo'<div class="row">';
			echo'<form action="'.$_SERVER['PHP_SELF'].'" method="POST">';
			echo '<div class="col-sm-6">';
				echo'<div class="row"><input class="form-control " type="text" id="barcode" placeholder="Start Scanning" name="barcode" ></div>';
				echo'<div class="row">';
                    if(!empty($_SESSION['temp']['operator']) ){
                    
                        echo'<div class="col-sm-12 active-user">';
                        echo '<br><center>Active User : </center>';
                        echo'</div>';
                        echo'<div class="col-sm-12 active-user">';
                        echo '<center>'.get_operator_name($db,$_SESSION['temp']['operator']).'</center>';
                        echo'</div>';
                    }
                echo'</div>';
            echo'</div>';
            echo'</form>';
			echo '<div class="col-sm-1">';
			echo'</div>';
            if(!empty($_SESSION['temp']['operator']) ){
			echo '<div class="col-sm-5 counter">';
                $operator_details=get_operator_detail($db,$_SESSION['temp']['operator']);
                $hours=round($operator_details['hours_today']['hours']+$operator_details['hours_today']['minutes']/60,1);
                //echo'<div class="col-sm-4"><center><img class="attachment" src="img/warning.png" width="50"  ></center></div>';
                echo'<div class="col-sm-4"></div>';
                echo '<div class="col-sm-4">'.gauge('Hours',$hours,0,8,'height: 200,redFrom:0,redTo:5,greenFrom:6.5,greenTo:10,yellowFrom:5, yellowTo: 6.5,').'</div>';
                echo'<div class="col-sm-4"></div>';
                
                //echo '<div class="col-sm-4">'.$hours.' h '.count_scan_open($db).'</div>';
                //echo'<div class="col-sm-4"><center><img class="attachment" src="img/warning.png" width="50"  ></center></div>';
            echo'</div>';
            }
        echo'</div>';
        echo'<div class="row"><h2>Last Scan</h2></div>';
        echo'<div class="row">'.show_last_scan($db,'all',$_SERVER['REMOTE_ADDR']).'</div>';
        
		
}

function showdetails_operator($db){
    echo'<div class="row" style="min-height: 30em;">';
			echo '<div class="row"><center><h2>Scans Today:</h2></center></div>';
			echo '<div class="row">';
				$date=(date('Y-m-d',time()));
				if (!empty($_SESSION['temp']['operator'])){
					$_SESSION['temp']['sort']=array();
					
					if(5>(date('G',time()))){
						show_all_scan_operator($db,$_SESSION['temp']['operator'],(date('Y-m-d',strtotime($date.' - 1days'))));
						separator();
					}
					
					show_all_scan_operator($db,$_SESSION['temp']['operator'],$date);
				}
				
				if (!empty($_SESSION['temp']['operator'])){
					
				}
			echo'</div>';
			echo '<div class="row">';
				if (!empty($_SESSION['temp']['operator'])){
					if( $_SESSION['temp']['operator']<>''  ){
							$operator_details=get_operator_detail($db,$_SESSION['temp']['operator']);
							if (!empty($operator_details)){
								echo'<div class="row "  >
								<center><h3>';
								echo $operator_details['operator_fullname'];
							
								echo '</h3></center>
										<center><h2>';
								echo $operator_details['hours_today']['hours'];
								echo ' hours ';
								echo $operator_details['hours_today']['minutes'];
								echo ' minutes </h2></center></div>';
								}
							} 
				}
			echo'</div>';
		echo'</div>';
}


function show_alert($message,$type='warning'){
    echo' <div class="alert alert-info alert.'.$type.' " role="alert">'.$message.'
         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
             <span aria-hidden="true">&times;</span>
         </button>
     </div>';
 }

 function count_scan_open($db){
    $today=(date('Y-m-d',time()))	;
	
	
	$query='SELECT count(scan_statut)as total FROM dbo.scan 
	LEFT JOIN
	dbo.operator
	ON
	scan_operatorcode=operator_code
	
	WHERE 
	scan_statut=\'start\'
	AND scan_still_open=1
	AND scan_date=\''.$today.'\'
	AND scan_operatorcode=\''.$_SESSION['temp']['operator'].'\'
	';
	
	$sql = $db->prepare($query); 
	 //show($query);
	$sql->execute();

	$row=$sql->fetch();
	return $row['total'];
 }


?>