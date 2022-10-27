

<?php 
$page_title='1st Mould Check';
$title_top='1st Mould Check';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/issue_log.css">
<link rel="stylesheet" href="css/asset.css">	
	
	<?php include ('navbar.php'); 
	//show($_POST);
	echo '<div class="row ">';
        echo'<div class="col-sm-3 asset-mould-id">';
            echo '<div class="row ">';
                echo'<div class="col-sm-4 ">';
                echo 'Document No :';
                echo'</div>';
                echo'<div class="col-sm-8 ">';
                echo 'XXXXXXXXX-XXX';
                echo'</div>';
            echo'</div>';
            echo '<div class="row ">';
                echo'<div class="col-sm-4 ">';
                echo 'Product :';
                echo'</div>';
                echo'<div class="col-sm-8 ">';
                echo 'BODY PHM4-FPL OM';
                echo'</div>';
            echo'</div>';
            echo '<div class="row ">';
                echo'<div class="col-sm-4 ">';
                echo 'Die Name :';
                echo'</div>';
                echo'<div class="col-sm-8 ">';
                echo 'PHM4-FPL over mould die';
                echo'</div>';
            echo'</div>';
            echo '<div class="row ">';
                echo'<div class="col-sm-4 ">';
                echo 'Die Number :';
                echo'</div>';
                echo'<div class="col-sm-8 ">';
                echo '1187';
                echo'</div>';
            echo'</div>';
        echo'</div>';
        echo'<div class=" ">';
        echo'</div>';
        echo'<div class="col-sm-2 asset-mould-id">';
            echo '<div class="row ">';
                echo'<div class="col-sm-4 ">';
                echo 'Date :';
                echo'</div>';
                echo'<div class="col-sm-8 ">';
                
	            $date_entry=date("Y-m-d",time());
                echo $date_entry;
                echo'</div>';
            echo'</div>';
            echo '<div class="row ">';
                echo'<div class="col-sm-4 ">';
                echo 'Time :';
                echo'</div>';
                echo'<div class="col-sm-8 ">';
                $hour_entry=date("H:i",time());
                echo $hour_entry;
                echo'</div>';
            echo'</div>';
            
        echo'</div>';

	echo '</div>';
	
    echo '<div class="row ">';
        echo'<div class="col-sm-8 ">';

        $checks[1]['caption']='All Moulding Pins and components from the first moulding cycle have been inspected for any damage, internal splitting or deformities.';
        $checks[2]['caption']='Each Machined Body has been inspected before moulding to ensure the appropriate End Caps, Spacers and Teeth are secure and undamaged.';
        $checks[3]['caption']='Teeth are present and orientated correctly in accordance with the appropriate MD&S and Work Instruction.';
        $checks[4]['caption']='All Date stamps MUST be set at the appropriate month/year for traceability purposes.';
        $checks[5]['caption']='All Mouldings are free from external flaws, flow lines, deformities, splits, exposed metal and bolt thread damage.';
        $checks[6]['caption']='All Mouldings are free from internal fill-ups and excess rubber.';
        $checks[7]['caption']='All Cable Entry Ports from the first moulding cycle have been cut open and inspected for any splitting or deformities.';
        
        for($j=1;$j<=7;$j++){
            $checks[$j]['number']=$j;
            if($_POST['check']=="detail-$j" or $_POST['save'.$j]=="detail-$j" ){
                $checks[$j]['checked']=1;
                
            }
        }

        //show($checks);

        $i=1;
        echo '<form method="POST">';
        $notchecked=0;
        //shuffle($checks);
        foreach($checks as &$item){
           
            echo '<div class="row ';
            if(!empty($item['checked']) ){
                echo'asset-mould-checked';
            }else{
                echo'asset-mould-unchecked';
                $notchecked++;
            }
            echo'">';
                echo'<div class="col-sm-10 ">';
                echo $item['number'].'.	'.$item['caption'];
                echo'</div>';
                echo'<div class="col-sm-1 ">';
                
                if(!empty($item['checked'])  ){
                    echo'<center><img class="attachment" src="img/checked.png" width="30"  ></center>';
                }else{
                    echo'<button type="submit" name="check" value="detail-'.$i.'"   class="col-sm-12 btn btn-default" >';
            
                    echo'<span class="glyphicon glyphicon-check" ></span>';
                    echo '</button>';
                }
                
                echo'</div>';
            echo '</div>';
            if(!empty($item['checked']) ){echo '<input type="hidden"  name="save'.$i.'" value="detail-'.$i.'">';}
            $i++;
        }
        echo'</form>';

        if($notchecked==0){
            echo '<div class="row asset-mould-unchecked">';
                echo'<div class="col-sm-10 ">';
                    echo'Die Setter: All checked done by '.$_SESSION['temp']['id'].' on the '.date("Y-m-d",time()).' at '.date("H:i",time());
                echo'</div>';
                echo'<div class="col-sm-1 ">';
                    echo'<button type="submit" name="check" value="detail-'.$i.'"   class="col-sm-12 btn btn-default" >';
                
                    echo'<span class="glyphicon glyphicon-send" ></span>';
                    echo '</button>';
                    echo'</div>';
            echo'</div>';
        }


        echo '</div>';
    echo '</div>';
    
    




	

	

	
	

	?>

	
	
	
</div>
