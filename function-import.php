<?php

function import_a_shear_test($db){
    $query='SELECT TOP 5000 *
	from [barcode].[dbo].[\'Raw data Moulding$\']
    ';

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();
	
	foreach($row as $test_init){
		//show($test_init);
		$result=array();
		$date=date('Y-m-d',strtotime($test_init['Date']));
		$time=date('G:i:s',strtotime($test_init['Time']));
		$timetag=strtotime($date.' '.date('G:i:s',strtotime($test_init['Time'])));
		$test_id=(date('ymd',strtotime($test_init['Date']))).'-'.sprintf("%03d", get_count_test_today($db,$date)+1);
		$product=$test_init['Product'];
		$jobnumber=$test_init['Job Nº'];
		if(empty($jobnumber)){$jobnumber='No MIS';}
		$tested_by=$test_init['Tested By'];
		$ambiant_temp=round($test_init['Ambiant Temp (ºC)'],3);
		$RH=round($test_init['RH (%)'],3);
		$part_temp=round($test_init['Part Temp (ºC)'],3);
		$result[]=round($test_init['1'],3);
		$result[]=round($test_init['2'],3);
		$result[]=round($test_init['3'],3);
		$result[]=round($test_init['4'],3);
		$result[]=round($test_init['5'],3);
		$result[]=round($test_init['6'],3);
		$result[]=round($test_init['7'],3);
		$result[]=round($test_init['8'],3);
		$notes=$test_init['Notes'];
		$minimum=round($test_init['Shear Min'],3);
		$maximum=round($test_init['Shear Max'],3);
		
		// create Test
		$query="INSERT INTO dbo.metro_test
		( test_id,
		test_date,
		test_timetag,
		test_product,
		test_jobnumber,
		test_created_by,
		test_finished
		) 
		VALUES (
		'".$test_id."',
		'".$date."',
		'".$timetag."',
		'".$product."',
		'".$jobnumber."',
		'".$tested_by."',
		1)";	
		
		
		$sql = $db->prepare($query); 
		//show($query);
		$sql->execute();
		
		// add ambiant single
		if(!empty($ambiant_temp)){
			$info_basesingle=get_basesingle_info($db,'Temperature');
			$single_id=$test_id.'-'.sprintf("%02d", get_count_single_test($db,$test_id,$date)+1);
			$single_name='Temperature';
			$single_description=$info_basesingle['basesingle_description'];
			$single_yesno=$info_basesingle['basesingle_yesno'];
			$cabledetail='Ambient';
			$unit="'".get_id($db,$single_name)[1]."'";
			$minimum=0;
			$maximum=0;
			$query="INSERT INTO dbo.metro_single
			( 
			single_id,
			single_testid,
			single_name, 
			single_description, 
			single_cabledetails,
			
			single_result,
			single_timetag,
			single_tested_by,
			single_yesno,
			single_finished,
			single_unit
			) 
			VALUES (
			'".$single_id."',
			'".$test_id."',	
			'".$single_name."',
			'".$single_description."',
			'".$cabledetail."',
			
			'".$ambiant_temp."',
			'".($timetag+1)."',
			'".$tested_by."',
			'".$single_yesno."',
			1,
			".$unit."
			)";	
			$sql = $db->prepare($query); 
			//show($query);
			$sql->execute();
		}

		// add Part Temperature single
		if(!empty($part_temp)){
			$info_basesingle=get_basesingle_info($db,'Temperature');
			$single_id=$test_id.'-'.sprintf("%02d", get_count_single_test($db,$test_id,$date)+1);
			$single_name='Temperature';
			$single_description=$info_basesingle['basesingle_description'];
			$single_yesno=$info_basesingle['basesingle_yesno'];
			$cabledetail='Bolt';
			$unit="'".get_id($db,$single_name)[1]."'";
			$minimum=0;
			$maximum=0;
			$query="INSERT INTO dbo.metro_single
			( 
			single_id,
			single_testid,
			single_name, 
			single_description, 
			single_cabledetails,
			
			single_result,
			single_timetag,
			single_tested_by,
			single_yesno,
			single_finished,
			single_unit
			) 
			VALUES (
			'".$single_id."',
			'".$test_id."',	
			'".$single_name."',
			'".$single_description."',
			'".$cabledetail."',
			
			'".$part_temp."',
			'".($timetag+2)."',
			'".$tested_by."',
			'".$single_yesno."',
			1,
			".$unit."
			)";	
			$sql = $db->prepare($query); 
			//show($query);
			$sql->execute();
		}

		// add Humidity single
		if(!empty($RH)){
			$info_basesingle=get_basesingle_info($db,'Humidity');
			$single_id=$test_id.'-'.sprintf("%02d", get_count_single_test($db,$test_id,$date)+1);
			$single_name='Humidity';
			$single_description=$info_basesingle['basesingle_description'];
			$single_yesno=$info_basesingle['basesingle_yesno'];
			$cabledetail='Bolt';
			$unit="'".get_id($db,$single_name)[1]."'";
			$minimum=0;
			$maximum=0;
			$query="INSERT INTO dbo.metro_single
			( 
			single_id,
			single_testid,
			single_name, 
			single_description, 
			single_cabledetails,
			
			single_result,
			single_timetag,
			single_tested_by,
			single_yesno,
			single_finished,
			single_unit
			) 
			VALUES (
			'".$single_id."',
			'".$test_id."',	
			'".$single_name."',
			'".$single_description."',
			'".$cabledetail."',
			
			'".$RH."',
			'".($timetag+3)."',
			'".$tested_by."',
			'".$single_yesno."',
			1,
			".$unit."
			)";	
			$sql = $db->prepare($query); 
			//show($query);
			$sql->execute();
		}


		// add Shear Test single
		$i=0;
		foreach ($result as $shear){
			if(!empty($shear)){
				$info_basesingle=get_basesingle_info($db,'Shear');
				$single_id=$test_id.'-'.sprintf("%02d", get_count_single_test($db,$test_id,$date)+1);
				$single_name='Shear';
				$single_description=$info_basesingle['basesingle_description'];
				$single_yesno=$info_basesingle['basesingle_yesno'];
				$cabledetail='';
				$unit="'".get_id($db,$single_name)[1]."'";
				$minimum=round($test_init['Shear Min'],3);
				$maximum=round($test_init['Shear Max'],3);
				if(empty($minimum)){$minimum='NULL';}else{$minimum="'".$minimum."'";}
				if(empty($maximum)){$maximum='NULL';}else{$maximum="'".$maximum."'";}
				
				
				$query="INSERT INTO dbo.metro_single
				( 
				single_id,
				single_testid,
				single_name, 
				single_description, 
				single_cabledetails,
				single_minimum,
				single_maximum,
				single_result,
				single_timetag,
				single_tested_by,
				single_notes,
				single_yesno,
				single_finished,
				single_unit
				) 
				VALUES (
				'".$single_id."',
				'".$test_id."',	
				'".$single_name."',
				'".$single_description."',
				'".$cabledetail."',
				".$minimum.",
				".$maximum.",
				'".$shear."',
				'".($timetag+3+$i)."',
				'".$tested_by."',
				'".$notes."',
				'".$single_yesno."',
				1,
				".$unit."
				)";
				$sql = $db->prepare($query); 	
				//show($query);
				$sql->execute();
			}

		}
		
			
			



		// create Singles

		//check if pass/fail



	check_test_pass_fail($db,$test_id);
	$query='Delete
	from [barcode].[dbo].[\'Raw data Moulding$\']
	WHERE ID=\''.$test_init['ID'].'\'
    ';

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
		

	
	}
    echo'<script></script>';
}

function import_a_pushon_test($db){
    $query='SELECT TOP 100 *
	from [barcode].[dbo].[\'Raw data PushOn$\']
    ';

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();
	
	foreach($row as $test_init){
		//show($test_init);
		$result_pullout=array();
        $result_shear=array();
		$date=date('Y-m-d',strtotime($test_init['Date']));
		$time=date('G:i:s',strtotime($test_init['Time']));
		$timetag=strtotime($date.' '.date('G:i:s',strtotime($test_init['Time'])));
		$test_id=(date('ymd',strtotime($test_init['Date']))).'-'.sprintf("%03d", get_count_test_today($db,$date)+1);
		$product=$test_init['Product'];
		$jobnumber=$test_init['Job Nº'];
		if(empty($jobnumber)){$jobnumber='No MIS';}
		$tested_by=$test_init['Tested By'];
		$ambiant_temp=round($test_init['Ambiant Temp (ºC)'],3);
		$RH=round($test_init['RH (%)'],3);
		$part_temp=round($test_init['Part Temp (ºC)'],3);
		$result_pullout[]=round($test_init['Pull-out _Chute 1'],3);
		$result_pullout[]=round($test_init['Pull-out _Chute 2'],3);
		$result_shear[]=round($test_init['Shear_Chute1'],3);
		$result_shear[]=round($test_init['Shear_Chute2'],3);
		$notes=$test_init['Notes'];
		$shear_minimum=round($test_init['Shear Min'],3);
		$shear_maximum=round($test_init['Shear Max'],3);
        $pullout_minimum=round($test_init['PullOut Mini'],3);
		show($product.' '.$date.' '.$test_id);
		// create Test
		$query="INSERT INTO dbo.metro_test
		( test_id,
		test_date,
		test_timetag,
		test_product,
		test_jobnumber,
		test_created_by,
		test_finished
		) 
		VALUES (
		'".$test_id."',
		'".$date."',
		'".$timetag."',
		'".$product."',
		'".$jobnumber."',
		'".$tested_by."',
		1)";	
		
		
		$sql = $db->prepare($query); 
		//show($query);
		$sql->execute();
		
		// add ambiant single
		if(!empty($ambiant_temp)){
			$info_basesingle=get_basesingle_info($db,'Temperature');
			$single_id=$test_id.'-'.sprintf("%02d", get_count_single_test($db,$test_id,$date)+1);
			$single_name='Temperature';
			$single_description=$info_basesingle['basesingle_description'];
			$single_yesno=$info_basesingle['basesingle_yesno'];
			$cabledetail='Ambient';
			$unit="'".get_id($db,$single_name)[1]."'";
			$minimum=0;
			$maximum=0;
			$query="INSERT INTO dbo.metro_single
			( 
			single_id,
			single_testid,
			single_name, 
			single_description, 
			single_cabledetails,
			
			single_result,
			single_timetag,
			single_tested_by,
			single_yesno,
			single_finished,
			single_unit
			) 
			VALUES (
			'".$single_id."',
			'".$test_id."',	
			'".$single_name."',
			'".$single_description."',
			'".$cabledetail."',
			
			'".$ambiant_temp."',
			'".($timetag+1)."',
			'".$tested_by."',
			'".$single_yesno."',
			1,
			".$unit."
			)";	
			$sql = $db->prepare($query); 
			//show($query);
			$sql->execute();
		}

		// add Part Temperature single
		if(!empty($part_temp)){
			$info_basesingle=get_basesingle_info($db,'Temperature');
			$single_id=$test_id.'-'.sprintf("%02d", get_count_single_test($db,$test_id,$date)+1);
			$single_name='Temperature';
			$single_description=$info_basesingle['basesingle_description'];
			$single_yesno=$info_basesingle['basesingle_yesno'];
			$cabledetail='Bolt';
			$unit="'".get_id($db,$single_name)[1]."'";
			$minimum=0;
			$maximum=0;
			$query="INSERT INTO dbo.metro_single
			( 
			single_id,
			single_testid,
			single_name, 
			single_description, 
			single_cabledetails,
			
			single_result,
			single_timetag,
			single_tested_by,
			single_yesno,
			single_finished,
			single_unit
			) 
			VALUES (
			'".$single_id."',
			'".$test_id."',	
			'".$single_name."',
			'".$single_description."',
			'".$cabledetail."',
			
			'".$part_temp."',
			'".($timetag+2)."',
			'".$tested_by."',
			'".$single_yesno."',
			1,
			".$unit."
			)";	
			$sql = $db->prepare($query); 
			//show($query);
			$sql->execute();
		}

		// add Humidity single
		if(!empty($RH)){
			$info_basesingle=get_basesingle_info($db,'Humidity');
			$single_id=$test_id.'-'.sprintf("%02d", get_count_single_test($db,$test_id,$date)+1);
			$single_name='Humidity';
			$single_description=$info_basesingle['basesingle_description'];
			$single_yesno=$info_basesingle['basesingle_yesno'];
			$cabledetail='Bolt';
			$unit="'".get_id($db,$single_name)[1]."'";
			$minimum=0;
			$maximum=0;
			$query="INSERT INTO dbo.metro_single
			( 
			single_id,
			single_testid,
			single_name, 
			single_description, 
			single_cabledetails,
			
			single_result,
			single_timetag,
			single_tested_by,
			single_yesno,
			single_finished,
			single_unit
			) 
			VALUES (
			'".$single_id."',
			'".$test_id."',	
			'".$single_name."',
			'".$single_description."',
			'".$cabledetail."',
			
			'".$RH."',
			'".($timetag+3)."',
			'".$tested_by."',
			'".$single_yesno."',
			1,
			".$unit."
			)";	
			$sql = $db->prepare($query); 
			//show($query);
			$sql->execute();
		}


		// add Shear Test single
		$i=1;
		foreach ($result_shear as $shear){
			if(!empty($shear)){
				$info_basesingle=get_basesingle_info($db,'Shear');
				$single_id=$test_id.'-'.sprintf("%02d", get_count_single_test($db,$test_id,$date)+1);
				$single_name='Shear';
				$single_description=$info_basesingle['basesingle_description'];
				$single_yesno=$info_basesingle['basesingle_yesno'];
				$cabledetail='Chute '.$i;
				$unit="'".get_id($db,$single_name)[1]."'";
				$minimum=round($test_init['Shear Min'],3);
				$maximum=round($test_init['Shear Max'],3);
				if(empty($minimum)){$minimum='NULL';}else{$minimum="'".$minimum."'";}
				if(empty($maximum)){$maximum='NULL';}else{$maximum="'".$maximum."'";}
				
				
				$query="INSERT INTO dbo.metro_single
				( 
				single_id,
				single_testid,
				single_name, 
				single_description, 
				single_cabledetails,
				single_minimum,
				single_maximum,
				single_result,
				single_timetag,
				single_tested_by,
				single_notes,
				single_yesno,
				single_finished,
				single_unit
				) 
				VALUES (
				'".$single_id."',
				'".$test_id."',	
				'".$single_name."',
				'".$single_description."',
				'".$cabledetail."',
				".$minimum.",
				".$maximum.",
				'".$shear."',
				'".($timetag+3+$i)."',
				'".$tested_by."',
				'".$notes."',
				'".$single_yesno."',
				1,
				".$unit."
				)";
				$sql = $db->prepare($query); 	
				//show($query);
				$sql->execute();
                $i++;
			}

		}

        $i=1;
		foreach ($result_pullout as $pullout){
			if(!empty($pullout)){
				$info_basesingle=get_basesingle_info($db,'Bolt Pull-Out');
				$single_id=$test_id.'-'.sprintf("%02d", get_count_single_test($db,$test_id,$date)+1);
				$single_name='Bolt Pull-Out';
				$single_description=$info_basesingle['basesingle_description'];
				$single_yesno=$info_basesingle['basesingle_yesno'];
				$cabledetail='Chute '.$i;
				$unit="'".get_id($db,$single_name)[1]."'";
				$minimum=round($test_init['PullOut Mini'],3);
				$maximum=0;
				if(empty($minimum)){$minimum='NULL';}else{$minimum="'".$minimum."'";}
				if(empty($maximum)){$maximum='NULL';}else{$maximum="'".$maximum."'";}
				
				
				$query="INSERT INTO dbo.metro_single
				( 
				single_id,
				single_testid,
				single_name, 
				single_description, 
				single_cabledetails,
				single_minimum,
				single_maximum,
				single_result,
				single_timetag,
				single_tested_by,
				single_notes,
				single_yesno,
				single_finished,
				single_unit
				) 
				VALUES (
				'".$single_id."',
				'".$test_id."',	
				'".$single_name."',
				'".$single_description."',
				'".$cabledetail."',
				".$minimum.",
				".$maximum.",
				'".$pullout."',
				'".($timetag+3+$i)."',
				'".$tested_by."',
				'".$notes."',
				'".$single_yesno."',
				1,
				".$unit."
				)";
				$sql = $db->prepare($query); 	
				//show($query);
				$sql->execute();
                $i++;
			}

		}
		
			
			



		// create Singles

		//check if pass/fail



	check_test_pass_fail($db,$test_id);
	$query='Delete
	from [barcode].[dbo].[\'Raw data PushOn$\']
	WHERE ID=\''.$test_init['ID'].'\'
    ';

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
		

	
	}
    echo'<script></script>';
}

function check_all_pass_fail($db){
    $query='SELECT  test_id
	from metro_test
    left join metro_single
	on single_testid=test_id
	where test_date<\'2021-08-01\'
	
	group by test_id
    ';

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();
	$count=0;
	foreach($row as $test_init){
		
	check_test_pass_fail($db,$test_init['test_id']);
	
	}
    echo'<script></script>';
}

function add_Average_test($db){
    $query='SELECT Top 1000 test_id
	from metro_test
	left join 
	(Select single_testid from metro_single where single_name=\'Average\')as temp on single_testid=test_id
    where test_date<\'2021-08-01\' and temp.single_testid is null

	group by test_id
	order by test_id asc
	
    ';

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();
	$count=0;
	foreach($row as $test_init){
        $test_id=$test_init['test_id'];
        $date=date('Y-m-d',strtotime($test_init['Date']));
        //insert Single Average
       
        $info_basesingle=get_basesingle_info($db,'Average');
        $single_id=$test_id.'-'.sprintf("%02d", get_count_single_test($db,$test_id,$date)+1);
        $single_name='Average';
        $single_description=$info_basesingle['basesingle_description'];
        $single_yesno=$info_basesingle['basesingle_yesno'];
        $cabledetail='Shear';
        
        $getminmax=get_min_max($db,$test_id);
        //show($getminmax);
        $minimum=round($getminmax['single_minimum'],3);
        $maximum=round($getminmax['single_maximum'],3);;
        $query="INSERT INTO dbo.metro_single
        ( 
        single_id,
        single_testid,
        single_name, 
        single_description, 
        single_cabledetails,
        single_minimum,
        single_maximum,
        
        single_yesno,
        single_finished
        ) 
        VALUES (
        '".$single_id."',
        '".$test_id."',	
        '".$single_name."',
        '".$single_description."',
        '".$cabledetail."',
        '".$minimum."',
        '".$maximum."',
        
       
        
        '".$single_yesno."',
        1
        )";	
        $sql = $db->prepare($query); 
        //show($query);
       $sql->execute();
		
	calculate_calculated_test($db,$test_init['test_id']);	
	check_test_pass_fail($db,$test_init['test_id']);
	
	}
    
}

function get_min_max($db,$test_id){
    $query='SELECT TOP 1 single_minimum,single_maximum
	from metro_single
    left join metro_test on test_id= single_testid
    where single_name=\'Shear\' and test_id=\''.$test_id.'\'
    ';

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetch();
    return $row;
}

function check_name_MIS($db){
    $query='SELECT test_product,test_jobnumber,test_id
	from metro_test
	
	
    where  test_jobnumber=\'\'
    
    order by test_product
	
    ';

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $row=$sql->fetchall();
	$count=0;
	foreach($row as $test_init){
        
        $query='UPDATE dbo.metro_test SET 
        
        test_jobnumber=\'No MIS\'
        WHERE test_id=\''.$test_init['test_id'].'\'';

        $sql = $db->prepare($query); 
        //show($query);
       $sql->execute();
		
	
	
	}
}

function update_temp_RH_test($db,$test_id=''){
    $query='Select test_id,
    the_RH,
    temp_ambient,
    temp_part 
	from metro_test
    left join(
            select single_result as the_RH,single_testid as thetestid from metro_single
            where single_name=\'humidity\' ) as tempRH on thetestid=test_id
            left join(
            select single_result as temp_ambient,single_testid as thetestid2 from metro_single
            where single_name=\'Temperature\' and single_cabledetails=\'Ambient\' ) as tempambient on thetestid2=test_id
            left join(
            select single_result as temp_part,single_testid as thetestid3 from metro_single
            where single_name=\'Temperature\' and single_cabledetails=\'Bolt\' ) as temppart on thetestid3=test_id
    WHERE test_id=\''.$test_id.'\'
		group by test_id,
    the_RH,
    temp_ambient,
    temp_part 
	
    ';

    //

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();

    $test_init=$sql->fetch();
	$count=0;
	
        
    $query='UPDATE dbo.metro_test SET 
    test_ambianttemp=\''.$test_init['temp_ambient'].'\',
    test_humidity=\''.$test_init['the_RH'].'\',
    test_bolt_temp=\''.$test_init['temp_part'].'\'
    WHERE test_id=\''.$test_init['test_id'].'\'';

    $sql = $db->prepare($query); 
    //show($query);
    $sql->execute();
		
	
	
	
}

?>