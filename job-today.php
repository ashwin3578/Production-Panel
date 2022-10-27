

<?php 
$page_title='Job Done Today';
$title_top='Job done Today';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/issue_log.css">
<link rel="stylesheet" href="css/job.css">	
	
	<?php include ('navbar.php'); 
	 manage_post_jobtoday($db);

    if(!empty($_POST)and !empty($_SESSION['temp']['id'])){
        //show($_POST);
        $date_entry=date("Y-m-d",time());
        if(empty($_POST['check'])){
            $query="INSERT INTO dbo.job_check
            ( job_MIS,
            job_employee,
            job_checked,
            job_date
            
            ) 
            VALUES (
            '".$_POST['job_number']."',
            '".$_SESSION['temp']['id']."',
            1,
            '".$date_entry."')";	
        
       
        }else{
            $query=" delete from dbo.job_check
            
            WHERE
            job_MIS='".$_POST['job_number']."' and job_employee='".$_SESSION['temp']['id']."' and job_date='".$date_entry."'
            ";	

        }
        $sql = $db->prepare($query); 
       // show($query);
        $sql->execute();
        
    }
    
    $today=$_POST['date_to_show']	;
    $query='SELECT MIS_List.WorkArea,Code,scan_jobnumber, sum(scan_time_distributed)as timetotal,job_checked,job_employee,thenbrline FROM dbo.scan 
    LEFT JOIN
    dbo.operator
    ON
    scan_operatorcode=operator_code
    LEFT JOIN
    dbo.MIS_List
    ON
    scan_jobnumber=ManufactureIssueNumber
    LEFT JOIN
    dbo.job_check
    ON
    scan_jobnumber=job_MIS 

    left join (
		SELECT WorkArea,count(Code) as thenbrline FROM dbo.scan 
		LEFT JOIN
		dbo.operator
		ON
		scan_operatorcode=operator_code
		LEFT JOIN
		dbo.MIS_List
		ON
		scan_jobnumber=ManufactureIssueNumber

		WHERE 
		scan_statut=\'start\'
		and scan_time_distributed>60
		AND scan_date=\''.$today.'\'
		
		 GROUP BY WorkArea) highscores
		 on MIS_List.WorkArea=highscores.WorkArea

    
    WHERE 
    scan_statut=\'start\'
    and scan_time_distributed>60
    AND scan_date=\''.$today.'\'
    AND	(job_employee is null or job_employee=\''.$_SESSION['temp']['id'].'\')
    GROUP BY scan_jobnumber,MIS_List.WorkArea,Code,job_checked,job_employee,thenbrline
    
    order by thenbrline desc ,MIS_List.WorkArea asc, Code asc
    
    
    ';
    
    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();
    //return $row;
    //show($row);

   

    navbar_jobtoday($db);
    echo '<div class="row ">';
   
        echo'<div class="col-sm-12 col-lg-12">';
        $lastworkarea='';
        Foreach($row as &$line){
            echo '<form id="formtosubmit-'.$line['scan_jobnumber'].'" method="POST">';
            if(empty($lastworkarea)){echo'<div class="col-sm-6 col-md-4 col-lg-3 job-id "><div class="title_workarea" >'.$line['WorkArea'].'</div>';}
            elseif($line['WorkArea']<>$lastworkarea){echo'</div><div class="col-sm-6 col-md-4 col-lg-3 job-id "><div class="title_workarea" >'.$line['WorkArea'].'</div>';}
            echo'<div class="row ';
                if (empty($line['job_checked'])){echo 'job-unchecked';}else{echo'job-checked';}
            echo'" onClick="document.forms[\'formtosubmit-'.$line['scan_jobnumber'].'\'].submit();">';

                //echo'<div class="col-sm-3">';
                //echo $line['WorkArea'];
               // echo'</div>';
                echo'<div class="col-sm-5">';
                echo $line['Code'];
                echo'</div>';
                echo'<div class="col-sm-3">';
                echo $line['scan_jobnumber'];
                echo'</div>';
                echo'<div class="col-sm-4" >';
                echo '<input type="hidden"  name="check" value="'.$line['job_checked'].'">';
                    if(!empty($line['job_checked'])  ){
                        echo '<input type="hidden"  name="job_number" value="'.$line['scan_jobnumber'].'">';
                        //echo'<button type="submit" name="job_number" value="'.$line['scan_jobnumber'].'"   class="col-sm-12 btn btn-default" >';
                        echo'<center><img class="attachment" src="img/checked.png" width="25"  ></center>';
                       // echo'<center><span class="glyphicon glyphicon-check" onClick="document.forms[\'formtosubmit-'.$line['scan_jobnumber'].'\'].submit();" ></span></center>';
                        //echo '</button>';
                    }else{
                       
                        //echo'<button type="submit" name="job_number" value="'.$line['scan_jobnumber'].'"   class="col-sm-12 btn btn-default" >';
                        echo '<input type="hidden"  name="job_number" value="'.$line['scan_jobnumber'].'">';
                        echo'<center><span class="glyphicon glyphicon-unchecked"  ></span></center>';
                        //echo '</button>';
                    }
                    
                    
                   
                echo'</div>';
            echo'</div>';
            
            $lastworkarea=$line['WorkArea'];
            echo '</form>';
        }
        echo'</div>';

        echo '</div>';
    
    echo '</div>';
    

	?>

	
	
	
</div>


<?php


function navbar_jobtoday($db){
    echo'<div class="row">';
        echo'<div class="col-sm-1 ">';
        echo'<br><span class="glyphicon glyphicon-step-backward" onClick="document.forms[\'Week_down\'].submit();" ></span>';
        echo'<form method="POST" id="Week_down"><input class="form-control" type="hidden" name="date_to_show" value="'.date('Y-m-d', strtotime($_POST['day'] . ' -1 day')).'"></form>';
        echo'</div>';
		
		echo'<div class="col-sm-3 "><br>';
            echo'<div class="col-sm-12 "><div class="col-sm-3 ">Date:</div><form method="POST"><div class="col-sm-9 "><input class="form-control" type="date" name="date_to_show" onChange="submit();"value="'.$_POST['date_to_show'].'"></div></form></div>';
            
        echo'</div>';
		echo'<div class="col-sm-1 ">';
        echo'<br><span class="glyphicon glyphicon-step-forward" onClick="document.forms[\'Week_up\'].submit();" ></span>';
        echo'<form method="POST" id="Week_up"><input class="form-control" type="hidden" name="date_to_show" value="'.date('Y-m-d', strtotime($_POST['day'] . ' +1 day')).'"></form>';
			
		echo'</div>';
        echo'<div class="col-sm-1 ">';
       
        echo'</div>';
		echo'<div class="col-sm-2 ">';
       
		echo'</div>';
        echo'<div class="col-sm-1 ">';
        
        echo'</div>';
		
		
		
        echo'<div class="col-sm-2 ">';
        
        echo'</div>';
       
		
	echo'</div>';	
}

function manage_post_jobtoday($db){
    if(empty($_POST['date_to_show'])){
        $_POST['date_to_show']=(date('Y-m-d',time()));
    }

}

?>
