<?php
include('function_dashboard2.php');

function manage_post_issue(){
	show_debug();
	$db=$GLOBALS['db'];
	update_days_open($db);

	if($_SESSION['temp']['id']=='CorentinHillion')
	{
	//show($_SESSION['temp']);
	}
	
	if((!empty($_GET['issue'])))
	{
		$_POST['type']='edit';
		$_POST['issue_number']=$_GET['issue'];
		clean_GET('prod-issue-log2.php');
	}
	
	if((!empty($_SESSION['temp']['GET']))AND empty($_GET['issue']))
	{
		unset($_SESSION['temp']['GET']);
	}

	if((!empty($_POST['type']))&&$_POST['type']=='edit')
	{ 
        //$_SESSION['temp']['show_dashboard']=false;
		
	}
	
	
	if((!empty($_POST['type']))&&$_POST['type']=='show_all')
	{
		if($_SESSION['temp']['show_all']==false){
			$_SESSION['temp']['show_all']=true;
		}
		else{
			$_SESSION['temp']['show_all']=false;
		}
	}
	
	if((!empty($_POST['type']))&&$_POST['type']=='show_closed')
	{
		if($_SESSION['temp']['show_closed']==false){
			$_SESSION['temp']['show_closed']=true;
		}
		else{
			$_SESSION['temp']['show_closed']=false;
		}
	}
	
	
	if((!empty($_POST['type']))&&$_POST['type']=='search')
	{
		$_SESSION['temp']['search']=$_POST['search_word'];
	}
	else{
		//$_SESSION['temp']['search']='';
	}
	
	if((!empty($_POST['type']))&&$_POST['type']=='show_dashboard')
	{
		if($_SESSION['temp']['show_dashboard']==false){
			$_SESSION['temp']['show_dashboard']=true;
		}
		else{
			$_SESSION['temp']['show_dashboard']=false;
		}
	}
	if((!empty($_POST['type']))&&$_POST['type']=='return-2')
	{
		
			$_SESSION['temp']['show_dashboard']=false;
			$_SESSION['temp']['issue_assignto']='';
		
	}
    if((!empty($_POST['type']))&&$_POST['type']=='filter_issue_assignto')
	{
		
			$_SESSION['temp']['issue_assignto']=$_POST['issue_assignto'];
		
	}
	if(empty($_SESSION['temp']['show_dashboard'])){
		$_SESSION['temp']['show_dashboard']==false;
	}
	
	
	
	
	//show();
	//show();
	if((!empty($_POST['type'])& substr($_POST['type'],0,14)=='remove_comment')){
		//add_comment();
		remove_comment();
		$_POST['type']='edit';
	}
	
	if((!empty($_POST['type'])&&$_POST['type']=='add_comment')){
		add_comment();
		$_POST['type']='edit';
	}
	if((!empty($_POST['type'])&&$_POST['type']=='re_assign_issue')){
		re_assign_log($db,$_POST['issue_number']);
		$_POST['type']='edit';
	}
	
	if((!empty($_POST['type']))&&$_POST['type']=='save')
	{
		save_log($db);
		if(!empty($_POST['type'])){
			add_PIL_to_single_test($db);
		}
	}
	if((!empty($_POST['type']))&&$_POST['type']=='reopen')
	{
		reopen_log($db);
	}
	if((!empty($_POST['type']))&&$_POST['type']=='linkto'){
		link_log();
	}
	
	
	if((!empty($_POST['type']))&&$_POST['type']=='delete')
	{
		delete_log($db,$_POST['issue_number']);
		remove_PIL_to_single_test($db);
	}
	if((!empty($_POST['type']))&&$_POST['type']=='assignto')
	{
		assign_log($db,$_POST['issue_number']);
	}
	if((!empty($_POST['type']))&&$_POST['type']=='remove_assign')
	{
		assign_log($db,$_POST['issue_number'],'blank');
		
	}
	if((!empty($_POST['type']))&&$_POST['type']=='upload_attachment')
	{
		
		upload_image($db);
		$_POST['type']='edit';
	}
	if((!empty($_POST['type']))&&$_POST['type']=='delete_attachment')
	{
		
		delete_image($db,$_POST['issue_number'],$_POST['attachment_number']);
		$_POST['type']='edit';
	}
}
function general_view_prod_isue_log(){
	$db=$GLOBALS['db'];	
	if ((!empty($_POST['type']))&&($_POST['type']=='edit' or $_POST['type']=='close' or $_POST['type']=='assign' or $_POST['type']=='link' or $_POST['type']=='linkto' or $_POST['type']=='transfer_assign')){		
		show_navbar_issue_log();
		edit_issue_log_v2($_POST['issue_number']);		
	}else{
		if($_SESSION['temp']['show_dashboard']==false){
			show_navbar_issue_log();
			$entry['closed']=1;
			$alllog=load_all_log();
			foreach ($alllog as &$entry){show_issue_log($entry);}
		}else{
			showDashboard($db);
			$alllog=load_all_log();
			foreach ($alllog as &$entry){show_issue_log($entry);}
		}

	}
}

function show_issue_log($entry){
	echo'<form id="formtosortnumber'.$entry['issue_number'].'" method="POST">';
	echo'<div class="row line-log" onClick="document.forms[\'formtosortnumber'.$entry['issue_number'].'\'].submit();" >';
		echo'<div class="col-sm-6 line-entry">';
			echo'<div class="col-sm-2"><center>';
				echo '<b>'.$entry['issue_number'].' </b>';
				echo'<img src="img/';
				if(!empty($entry['issue_priority']) and $entry['issue_priority']==1){ echo 'non-urgent';}
				if(empty($entry['issue_priority']) or $entry['issue_priority']==2){ echo 'normal';}
				if(!empty($entry['issue_priority']) and $entry['issue_priority']==3){ echo 'urgent';}
				echo'.png" width="25" height="25" ><br>';
				echo date('l',$entry['issue_date_created']).'<br>';
				echo date('d-m-Y',$entry['issue_date_created']).'<br>';
				echo date('G:i:s',$entry['issue_date_created']);
				
				
			echo'</center></div>';
			echo'<div class="col-sm-5">';
				
				echo '<b>Open By: </b>'.$entry['issue_openby'].'<br>';
				echo '<b>How Found: </b>'.$entry['issue_how'].'<br>';
				echo '<b>Product Code: </b>'.$entry['issue_product_code'].'';
				if(!empty($entry['issue_qty'])){echo '<br><div class="col-sm-6"><b>Qty: </b>'.$entry['issue_qty'].'</div>';}
				if(!empty($entry['issue_cost'])){echo '<div class="col-sm-6"><b>Cost: </b>$'.number_format($entry['issue_cost']).'</div>';}

			echo'</div>';
			echo'<div class="col-sm-5">';
			$limitentry=200;
			if (strlen($entry['issue_details']) > $limitentry){$entry['issue_details'] = substr($entry['issue_details'], 0, $limitentry) . ' (...)';}
			$limitentry=200;
			if (strlen($entry['issue_action_taken']) > $limitentry){$entry['issue_action_taken'] = substr($entry['issue_action_taken'], 0, $limitentry) . ' (...)';}
			echo '<b>Issue Details: </b><br>'.nl2br($entry['issue_details']).'';
			echo'</div>';
		echo'</div>';
		
		if(empty($entry['issue_closeby'])){$entry['closed']=0;}else{$entry['closed']=1;}
		if($entry['closed']==1){
			echo'<div class="col-sm-5 line-close">';
			echo'	<div class="progress">
				  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="70"
				  aria-valuemin="0" aria-valuemax="100" style="width:100%">
					 Issue Closed
				  </div>
				</div>';
				echo'<div class="col-sm-5">';
					if(!empty($entry['issue_assignto'])){echo 'Assigned to: '.$entry['issue_assignto'].'<br>';}
					echo '<b>Closed by: </b>'.$entry['issue_closeby'].'<br>';
					echo '<b>Root Cause Cat.: </b>'.$entry['issue_root_cause'].'<br>';
					if(!empty($entry['issue_verification']) or !empty($entry['issue_comments']) or !empty($entry['issue_IAF'])){
						echo'<span class="glyphicon glyphicon-info-sign" ></span>';
					}
				echo'</div>';
				echo'<div class="col-sm-7">';
					
					echo '<b>Actions Taken:</b><br>'.nl2br($entry['issue_action_taken']).'';
					
				echo'</div>';
			echo'</div>';
		}
		elseif(!empty($entry['issue_assignto']))
		{
			echo'<div class="col-sm-5 line-assigned">';
			echo'	<div class="progress">
				  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="70"
				  aria-valuemin="0" aria-valuemax="100" style="width:50%">
					 Issue Active';
					 if($entry['issue_nbr_day_open']>0){echo ' ( '.$entry['issue_nbr_day_open'].' days)';}
				  echo'</div>
				</div>';
			// echo ' Issue Active<br><br>';
			echo'<div class="col-sm-12">';
			echo ' Assigned to '.$entry['issue_assignto'].' on the ';
			echo date('d/m/y',$entry['issue_assign_date']).' at '.date('G:i:s',$entry['issue_assign_date']).'';
			echo'</div>';
			if (!empty($entry['issue_ccto'])){echo ' <div class="col-sm-12"><i><small>CC : '.$entry['issue_ccto'].'</small></i></div>';}
				
			if (!empty($entry['issue_action_taken'])){echo '<div class="col-sm-12"><br><b>Actions Taken: </b>'.nl2br($entry['issue_action_taken']).'</div>';	}
			
				
			
			
			echo'</div>';
		}
		elseif(empty($entry['assignedto']))
		{
			echo'<div class="col-sm-5 line-open">';
			echo'	<div class="progress">
				  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="70"
				  aria-valuemin="0" aria-valuemax="100" style="width:20%">
					 Issue Raised
				  </div>
				</div>';
			echo ' Issue not Assigned yet<br><br>';
			
			
			
				// echo'<button type="submit" name="type" '.allow_modify().' value="assign" class="btn btn-default" >
						// Assigned Issue
						// </button>';
			
			
			echo'</div>';
		}
		
		
		echo'<div class="col-sm-1 last-part">';
				
				
					echo '<input type="hidden"  name="issue_number" value="'.$entry['issue_number'].'">';
					echo '<input type="hidden"  name="type" value="edit">';
					
					echo'<br><button type="submit"  class="btn btn-default" >
								<span class="glyphicon glyphicon-resize-full" ></span>
								</button>';
				
				
			
		echo'</div>';
	echo'</div>';
	echo'</form>';
	
	
	
	
}

function edit_issue_log($db,$issue_number){
	
	$entry=load_one_log($db,$issue_number);
	//show($entry);
	$line_number=next_rownumber($db);
	if(!empty($entry['issue_single_id'])){
		$_POST['single_id']=$entry['issue_single_id'];
	}
	echo'<form id="metrologylink" action="metrology.php" method="post">
	<input type="hidden" name="single_id" value="'.$_POST['single_id'].'">
	<input type="hidden" name="test_id" value="'.substr($_POST['single_id'],0,-3).'">
	<input type="hidden" name="product_test" value="'.$entry['issue_product_code'].'">
  </form>';
	echo'<form method="POST"><div class="row line-log">';
	
		echo'<div class="col-md-6 col-sm-12 line-entry">';
			
			// 
			
				echo '<div class="col-sm-4"><input class="form-control" type="text" readonly id="issue_number"  name="issue_number" value="';
				if(!empty($entry['issue_number'])){echo $entry['issue_number'];}else {echo 'PIL-'.date('y').'-'.sprintf("%04d", $line_number+1);}
				
				echo '"></div>';
				
				echo '<div class="col-sm-1">Priority</div><div class="col-sm-2">';
				echo '<input type="hidden"  name="issue_priority" value="'.$entry['issue_priority'].'">';				
				echo'<select class="form-control" id="issue_priority" name="issue_priority" '.allow_modify($db,'admin_or_just_created','',$entry['issue_number'],'disabled').'  >
				<option ';
				if(!empty($entry['issue_priority']) and $entry['issue_priority']==3){ echo 'selected';}
				echo' value="3">Urgent</option>
				<option ';
				if(empty($entry['issue_priority']) or $entry['issue_priority']==2){ echo 'selected';}
				echo' value="2">Normal</option>
				<option ';
				if(!empty($entry['issue_priority']) and $entry['issue_priority']==1){ echo 'selected';}
				echo' value="1">Non-Urgent</option>
				 </select>';
				
				
				
				
				
				
				echo '<br></div>';
				echo '<div class="col-sm-12">';
				echo '<input type="hidden"  name="issue_rownumber" value="';
				if(!empty($entry['issue_rownumber'])){echo $entry['issue_rownumber'];}else {echo $line_number;}
				echo'">';//<div class="col-sm-2">Date: </div>
				echo '<div class="col-sm-4"><input class="form-control" type="date" id="issue_date_created" '.allow_modify($db,'admin_or_just_created','',$entry['issue_number']).' name="issue_date_created" value="';
				if(!empty($entry['issue_date_created'])and $entry['issue_date_created']>0){echo date('Y-m-d',$entry['issue_date_created']);}else {echo date('Y-m-d');}
				echo'"></div>';//<div class="col-sm-2">Time: </div>
				echo '<div class="col-sm-4"><input class="form-control" type="time" id="issue_time_created" '.allow_modify($db,'admin_or_just_created','',$entry['issue_number']).' name="issue_time_created" value="';
				if(!empty($entry['issue_date_created'])and $entry['issue_date_created']>0){echo date('H:i',$entry['issue_date_created']);}else {echo date('H:i');}
				echo '"></div>';
				if(!empty($_POST['single_id']) or !empty($entry['issue_single_id'])){
					
					echo' <input type="hidden" name="from" value="metrology">';
					echo' <div class="col-sm-2">
					
					<input onClick="document.getElementById(\'metrologylink\').submit();" class="form-control" readonly type="text" name="single_id" value="'.$_POST['single_id'].'">
					
					</div>';
				}
				echo '</div>';
				// echo date('G:i:s',$entry['issue_date_created']).'<br>';
			
			
				echo '<div class="col-sm-12">Open By : </div><div class="col-sm-12"><input class="form-control" type="text" id="issue_openby" '.allow_modify($db,'admin_or_just_created','',$entry['issue_number']).' name="issue_openby" value="';
				if(!empty($entry['issue_openby'])and $entry['issue_openby']<>""){echo $entry['issue_openby'];}else {echo $_SESSION['temp']['id'];}
				echo '"></div>';
				echo '<input type="hidden"  name="issue_how" value="'.$entry['issue_how'].'">';
				echo '<div class="col-sm-12">How Found : </div><div class="col-sm-12">';
				list_input($db,get_all_how_found($db),$value=$entry['issue_how'],$name='issue_how','','',$entry['issue_number']);
				
				echo'</div>';
				echo '<input type="hidden"  name="issue_product_code" value="'.$entry['issue_product_code'].'">';
				echo '<div class="col-sm-6">Product Code : </div><div class="col-sm-3">Qty : </div><div class="col-sm-3">Cost : </div><div class="col-sm-6">';
				list_input($db,get_all_product($db),$value=$entry['issue_product_code'],$name='issue_product_code','blank','1',$entry['issue_number']);
				
				echo'</div>';
				echo'<div class="col-sm-3"><input class="form-control" type="number" min="0" id="qty" name="issue_qty" '.allow_modify($db,'admin_or_openedby_for_24hours','',$entry['issue_number']).' value="';
				if(!empty($entry['issue_qty'])){echo $entry['issue_qty'];}else {echo '1';}
				echo '"></div><div class="col-sm-3">';
				if(!empty($entry['issue_cost'])){echo '$'.number_format(round($entry['issue_cost']),2);} else {echo'';}
				echo'</div>';
				
				// echo '<div class="col-sm-12">Product Code : </div><div class="col-sm-12"><input class="form-control" type="text" id="issue_product_code"  name="issue_product_code" value="'.$entry['issue_product_code'].'"></div> ';
			
			echo '<div class="col-sm-12">Issue Details : </div><div class="col-sm-12"><textarea class="form-control" id="issue_details" '.allow_modify($db,'admin_or_openedby_for_24hours','',$entry['issue_number']).' name="issue_details" name="Text1" cols="30" rows="5" >'.$entry['issue_details'].'</textarea></div> ';
			
			//if coming from Metrology, Prefill form
			if(!empty($_POST['single_id']) and (empty($entry['issue_rownumber']))){
				echo'<script>document.getElementById("issue_how").value = "Metrology Testing";</script>';
				echo'<script>document.getElementById("issue_product_code").value = "'.$_POST['product_test'].'";</script>';
				echo'<script>document.getElementById("issue_details").value = "'.$_POST['caption'].'";</script>';
				
			}
		echo'</div>';

		if(empty($entry['issue_closeby'])){$entry['closed']=0;}else{$entry['closed']=1;}
		
		
		if($entry['closed']==1 or $_POST['type']=='close'){   ///issue closed
			echo'<div class="col-md-5 col-sm-10 line-close">';
			echo'	<div class="progress">
				  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="70"
				  aria-valuemin="0" aria-valuemax="100" style="width:100%">
					 Issue Closed
				  </div>
				</div>';
				echo '<div class="row">Closed by : </div><div class="row"><div class="col-sm-6"><input class="form-control" type="text" id="issue_closeby"  '.allow_modify($db,'admin_closeby_everyoneloggedin','',$entry['issue_number']).'  name="issue_closeby" value="';
				if(!empty($entry['issue_closeby'])and $entry['issue_closeby']<>""){echo $entry['issue_closeby'];}else {echo $_SESSION['temp']['id'];}  
				echo '"></div>';
				echo'<div class="col-sm-6">';
				
				if($entry['closed']==1){
				echo ' <button type="submit" name="type" value="reopen" '.allow_modify($db,'admin_closeby_everyoneloggedin','',$entry['issue_number'],'disabled').'  class="btn btn-default btn-sm" >Re-Open Issue</button>';
				}
				echo'</div></div>';
				
				echo '<div class="row"><div class="col-sm-2">Date: </div>
				<div class="col-sm-10">
				<input class="form-control" type="date" id="issue_close_date"  '.allow_modify($db,'admin_closeby_everyoneloggedin','',$entry['issue_number']).'  name="issue_close_date" value="';
				if(!empty($entry['issue_close_date'])and $entry['issue_close_date']>0){echo date('Y-m-d',$entry['issue_close_date']);}else {echo date('Y-m-d');}
				echo'">
				</div></div>';
				
				echo '<input class="form-control" type="hidden" id="issue_time_closed" '.allow_modify($db,'admin_or_just_created','',$entry['issue_number']).' name="issue_time_closed" value="';
				if(!empty($entry['issue_close_date'])and $entry['issue_close_date']>0){echo date('H:i',$entry['issue_close_date']);}else {echo date('H:i');}
				echo '">';
				
				echo '<div class="row">Root Cause Cat.: </div><div class="col-sm-12">';
				list_input($db,get_all_root_cause($db),$value=$entry['issue_root_cause'],$name='issue_root_cause','','',$entry['issue_number'],'admin_closeby_everyoneloggedin');
				
				echo'</div>';
				
				echo '<div class="row">Action Taken : </div><div class="col-sm-12"><textarea class="form-control" id="issue_action_taken"  '.allow_modify($db,'admin_closeby_everyoneloggedin','',$entry['issue_number']).'  name="issue_action_taken" name="Text1" cols="30" rows="5" >';
				if(!empty($_POST['issue_action_taken'])){
					echo $_POST['issue_action_taken'];
				}else{
					echo $entry['issue_action_taken'];
				}
				echo'</textarea></div> ';
			
			
				echo '<div class="row">Verifications: </div><div class="col-sm-12"><textarea class="form-control" id="issue_verification"  '.allow_modify($db,'admin_closeby_everyoneloggedin','',$entry['issue_number']).'  name="issue_verification" name="Text1" cols="30" rows="2" >'.$entry['issue_verification'].'</textarea></div> ';
				echo '<div class="row">IAF No(if applicable) : </div><div class="col-sm-12"><input class="form-control" type="text" '.allow_modify($db,'admin_closeby_everyoneloggedin','',$entry['issue_number']).'  id="issue_IAF"  name="issue_IAF" value="'.$entry['issue_IAF'].'"></div>';
				
			
				echo '<div class="row">Comments: </div><div class="col-sm-12"><textarea class="form-control" id="issue_comments"  '.allow_modify($db,'admin_closeby_everyoneloggedin','',$entry['issue_number']).'  name="issue_comments" name="Text1" cols="30" rows="2" >'.$entry['issue_comments'].'</textarea></div> ';
			
				//verification
				//iaf number
				//comments
				
				
				
				
				
				
				// echo'<div class="col-sm-5">';
					
					// echo 'Root Cause Cat.: '.$entry['issue_root_cause'].'<br>';
					// echo'<span class="glyphicon glyphicon-info-sign" ></span>';
				// echo'</div>';
				// echo'<div class="col-sm-7">';
					
					// echo 'Actions Taken:<br>'.$entry['issue_action_taken'].'';
					
				// echo'</div>';
			echo'</div>';
		}
		elseif((!empty($entry['issue_assignto']) or $_POST['type']=='assign'))  ///issue assigned to someone
		{
			echo'<div class="col-md-5 col-sm-10 line-assigned">';
			echo'	<div class="progress">
				  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="70"
				  aria-valuemin="0" aria-valuemax="100" style="width:50%">
					 Issue Active';
					 if($entry['issue_nbr_day_open']>0){echo ' ( '.$entry['issue_nbr_day_open'].' days)';}
				  echo'
				  </div>
				</div>';
			echo ' Issue still Active<br><br>';
			
			if(!empty($entry['issue_assignto'])){
				echo ' Assigned to '.$entry['issue_assignto'];
				echo ' <button type="submit" name="type" value="remove_assign" '.allow_modify($db,'admin_assignto_openby','',$entry['issue_number'],'disabled').'  class="btn btn-default btn-sm" ><span class="glyphicon glyphicon-remove" ></span></button>';
				if (!empty($entry['issue_ccto'])){echo ' <br><i><small>CC : '.$entry['issue_ccto'].'</small></i>';}
				echo '<br>Action Taken : <textarea class="form-control" id="issue_action_taken"  '.allow_modify($db,'admin_assignto_openby','',$entry['issue_number']).'  name="issue_action_taken" name="Text1" cols="30" rows="5" >'.$entry['issue_action_taken'].'</textarea> ';
			echo'<br><button type="submit" name="type" value="close" '.allow_modify($db,'everyone_logged_in','','','disabled').'  class="btn btn-default"  >
						Close Issue
						</button>';
			echo'<br><button type="submit" name="type" value="link" '.allow_modify($db,'everyone_logged_in','','','disabled').'  class="btn btn-default"  >
			Link to another PIL
			</button>';
						
						
						
						
						
				
			}
			else     ///issue to be assigned
			{
				echo ' Assigned to :';
				 $listeemployee=listemployee($db); 
				 	echo '<input type="hidden"  name="issue_assignto" value="'.$_SESSION['temp']['id'].'">';
					echo '<select name="issue_assignto" '.allow_modify($db,'admin_assignto_openby','',$entry['issue_number'],'disabled').' class="form-control" id="employee">';	
						foreach ($listeemployee as &$employee){
								echo '<option ';
								if($_SESSION['temp']['id']==$employee['employee_code']){echo 'selected';}
								echo' value="'.$employee['employee_code'].'">'.$employee['employee_name'].' '.$employee['employee_lastname'].'</option> ';
						}
				  
				  echo' </select>';
				  
				  echo ' <br>CC to:';
				  echo '<select multiple size="6" name="issue_ccto[]" '.allow_modify($db,'admin_assignto_openby','',$entry['issue_number'],'disabled').' class="form-control" id="employee">';	
						
						echo '<option value=""></option> ';
						
						
						foreach ($listeemployee as &$employee){
								echo '<option ';
								echo' value="'.$employee['employee_code'].'">'.$employee['employee_name'].' '.$employee['employee_lastname'].'</option> ';
						}
				  
				  echo' </select>';
				  
							
				echo'<br><button type="submit" name="type" '.allow_modify($db,'everyone_logged_in','','','disabled').' value="assignto" class="btn btn-default" >
						Assign Issue
						</button>';
						
			}	
		
			
			
			
			
			
			
				
			
			
			echo'</div>';
		}
		
		elseif(empty($entry['assignedto'])and(!empty($entry['issue_number'])))
		{
			echo'<div class="col-md-5 col-sm-10 line-open">';
			echo'	<div class="progress">
				  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="70"
				  aria-valuemin="0" aria-valuemax="100" style="width:20%">
					 Issue Raised 
				  </div>
				</div>';
			echo ' Issue Active and not Assigned<br><br>';
			
			
			
				echo'<button type="submit" name="type" value="assign" class="btn btn-default" >
						Assign Issue
						</button>';
			
			
			echo'</div>';
		}
		else{
			echo'<div class="col-md-5 col-sm-10 line-open">';
			
			
			
			echo'</div>';
		}
		
		
		echo'<div class="col-md-1 col-sm-2 last-part">';
				
				echo'<br>';
					
					
				//	echo'<button type="submit" name="type" value="return" class="btn btn-default" >
				//				<span class="glyphicon glyphicon-resize-small" ></span>
				//				</button>';
				
				echo'<br><br>';
					//echo '<input type="hidden"  name="issue_number" value="'.$entry['issue_number'].'">';
					
					echo'<button type="submit" '.allow_modify($db,'everyone_logged_in','',$entry['issue_number'],'disabled').' name="type" value="save" class="btn btn-default" >
								<span class="glyphicon glyphicon-floppy-disk" ></span>
								</button><br>';
				
					//echo '<br><input type="hidden"  name="issue_number" value="'.$entry['issue_number'].'">';
					
					if (nextattachment($db,$issue_number)>1) {$protected=' disabled ';}
					
					
					if(!empty($entry['issue_number'])){
					echo'<button type="submit" '.allow_modify($db,'delete_log','',$entry['issue_number'],'disabled').' name="type" value="delete" '.$protected.' class="btn btn-default" >
								<span class="glyphicon glyphicon-trash" ></span>
								</button>';
					}
				echo'</form>';
			
		echo'</div>';
		
		
		
	echo'</div>';
	if(!empty($entry['issue_number'])){
		$all_attach=load_attachment($db,$issue_number);
		foreach ($all_attach as &$attachment){
			echo'<form method="POST"><div class="row line-attachment">';
				echo'<div class="col-sm-6 ">';
				if (substr($attachment['attachment_path'], -3)=='pdf'){
					echo'<img class="attachment" src="img/pdf.png"  >';
				}else{
					echo'<a target="blank"  href="attachment/'.$attachment['attachment_path'].'">';
						echo'<img class="attachment" src="attachment/'.$attachment['attachment_path'].'"  >';
					echo'</a>';
				}
				
				
				echo '<br>'.$attachment['attachment_caption'];
				echo'</div>';
				echo'<div class="col-sm-5 ">';
				
				echo'</div>';
				echo'<div class="col-sm-1 last-part">';
				echo'<br>';
						//echo '<input type="hidden"  name="issue_number" value="'.$entry['issue_number'].'">';
						
						echo'<a target="blank"  href="attachment/'.$attachment['attachment_path'].'">';
							echo'<button type="button" class="btn btn-default" >';
							echo'<span class="glyphicon glyphicon-resize-full" ></span>';
							echo'</button>';
						echo'</a>';
					echo'<br><br>';
						//echo '<input type="hidden"  name="issue_number" value="'.$entry['issue_number'].'">';
						
						//echo '<br><input type="hidden"  name="issue_number" value="'.$entry['issue_number'].'">';
						echo '<input type="hidden"   name="attachment_number" value="'.$attachment['attachment_number'].'">';
						echo '<input type="hidden"  name="issue_number" value="'.$issue_number.'">';
				
						echo'<button type="submit" name="type" '.allow_modify($db,'everyone_logged_in','',$entry['issue_number'],'disabled').' value="delete_attachment" class="btn btn-default" >
									<span class="glyphicon glyphicon-trash" ></span>
									</button>';
				echo'</div>';
			echo'</div>';
			echo'</form>';
		}
		
		echo'<form method="POST" enctype="multipart/form-data" ><div class="row line-attachment">';
				echo'<div class="col-sm-2 ">';
				echo'Add a picture/document (2Mo max)';
				echo'</div>';
				echo'<div class="col-sm-3 ">';
				echo'<input class="form-control" type="file" '.allow_modify($db,'everyone_logged_in','',$entry['issue_number'],'disabled').' name="fileToUpload" id="fileToUpload" accept="image/*;capture=camera">';
				echo '<input type="hidden"  name="issue_number" value="'.$issue_number.'">';
						
				echo'</div>';
				echo'<div class="col-sm-3 ">';
				echo '<input class="form-control" type="text"  '.allow_modify($db,'everyone_logged_in','',$entry['issue_number'],'disabled').' name="attachment_caption" placeholder="Add a caption if needed">';
				echo'</div>';
				echo'<div class="col-sm-1 ">';
				
				echo'</div>';
				echo'<div class="col-sm-1 ">';
				echo'<button type="submit" name="type" '.allow_modify($db,'everyone_logged_in','',$entry['issue_number'],'disabled').' value="upload_attachment" class="btn btn-default" >
									<span class="glyphicon glyphicon-upload" ></span> Upload
									</button>';
				echo'</div>';
				echo'<div class="col-sm-1 ">';
				
				echo'</div>';
				echo'<div class="col-sm-1 last-part">';
				echo'</div>';
			echo'</div>';
		echo'</form>';
	
	}
	
	
	
	//show ($entry);
}
function edit_issue_log_v2($issue_number){
	
	$db=$GLOBALS['db'];
	$entry=load_one_log($db,$issue_number);
	
	$line_number=next_rownumber($db);
	if(!empty($entry['issue_single_id'])){
		$_POST['single_id']=$entry['issue_single_id'];
	}?>
	<form id="metrologylink" action="metrology.php" method="post">
		<input type="hidden" name="single_id" value="<?php echo$_POST['single_id']?>">
		<input type="hidden" name="test_id" value="<?php echo substr($_POST['single_id'],0,-3)?>">
		<input type="hidden" name="product_test" value="<?php echo$entry['issue_product_code']?>">
	</form>
	<div class="row line-log">
		<form method="POST">
		<div class="col-md-6 col-sm-12 line-entry"><?php edit_issue_log_initial_report($issue_number,$entry)?></div>
		<div class="col-md-5 col-sm-10"><?php edit_issue_log_rightpart_report($issue_number,$entry)?></div>
		<div class="col-md-1 col-sm-2 last-part"><?php edit_issue_log_console($issue_number,$entry)?></div>
		</form>
	</div>
	
	<?php edit_issue_log_attachments($issue_number,$entry)?>
	<?php edit_issue_log_linked($issue_number,$entry)?>
	<?php edit_issue_log_log($issue_number,$entry)?>
  <?php
}
function edit_issue_log_initial_report($issue_number,$entry){
	$db=$GLOBALS['db'];
	$entry=load_one_log($db,$issue_number);
	$line_number=next_rownumber($db);
	echo '<div class="col-sm-4"><input class="form-control" type="text" readonly id="issue_number"  name="issue_number" value="';
				if(!empty($entry['issue_number'])){echo $entry['issue_number'];}else {echo 'PIL-'.date('y').'-'.sprintf("%04d", $line_number+1);}
				
				echo '"></div>';
				
				echo '<div class="col-sm-1">Priority</div><div class="col-sm-2">';
				echo '<input type="hidden"  name="issue_priority" value="'.$entry['issue_priority'].'">';				
				echo'<select class="form-control" id="issue_priority" name="issue_priority" '.allow_modify($db,'admin_or_just_created','',$entry['issue_number'],'disabled').'  >
				<option ';
				if(!empty($entry['issue_priority']) and $entry['issue_priority']==3){ echo 'selected';}
				echo' value="3">Urgent</option>
				<option ';
				if(empty($entry['issue_priority']) or $entry['issue_priority']==2){ echo 'selected';}
				echo' value="2">Normal</option>
				<option ';
				if(!empty($entry['issue_priority']) and $entry['issue_priority']==1){ echo 'selected';}
				echo' value="1">Non-Urgent</option>
				 </select>';
				
				
				
				
				
				
				echo '<br></div>';
				echo '<div class="col-sm-12">';
				echo '<input type="hidden"  name="issue_rownumber" value="';
				if(!empty($entry['issue_rownumber'])){echo $entry['issue_rownumber'];}else {echo $line_number;}
				echo'">';//<div class="col-sm-2">Date: </div>
				echo '<div class="col-sm-4"><input class="form-control" type="date" id="issue_date_created" '.allow_modify($db,'admin_or_just_created','',$entry['issue_number']).' name="issue_date_created" value="';
				if(!empty($entry['issue_date_created'])and $entry['issue_date_created']>0){echo date('Y-m-d',$entry['issue_date_created']);}else {echo date('Y-m-d');}
				echo'"></div>';//<div class="col-sm-2">Time: </div>
				echo '<div class="col-sm-4"><input class="form-control" type="time" id="issue_time_created" '.allow_modify($db,'admin_or_just_created','',$entry['issue_number']).' name="issue_time_created" value="';
				if(!empty($entry['issue_date_created'])and $entry['issue_date_created']>0){echo date('H:i',$entry['issue_date_created']);}else {echo date('H:i');}
				echo '"></div>';
				if(!empty($_POST['single_id']) or !empty($entry['issue_single_id'])){
					
					echo' <input type="hidden" name="from" value="metrology">';
					echo' <div class="col-sm-2">
					
					<input onClick="document.getElementById(\'metrologylink\').submit();" class="form-control" readonly type="text" name="single_id" value="'.$_POST['single_id'].'">
					
					</div>';
				}
				echo '</div>';
				// echo date('G:i:s',$entry['issue_date_created']).'<br>';
			
			
				echo '<div class="col-sm-12">Open By : </div><div class="col-sm-12"><input class="form-control" type="text" id="issue_openby" '.allow_modify($db,'admin_or_just_created','',$entry['issue_number']).' name="issue_openby" value="';
				if(!empty($entry['issue_openby'])and $entry['issue_openby']<>""){echo $entry['issue_openby'];}else {echo $_SESSION['temp']['id'];}
				echo '"></div>';
				echo '<input type="hidden"  name="issue_how" value="'.$entry['issue_how'].'">';
				echo '<div class="col-sm-12">How Found : </div><div class="col-sm-12">';
				list_input($db,get_all_how_found($db),$value=$entry['issue_how'],$name='issue_how','','',$entry['issue_number']);
				
				echo'</div>';
				echo '<input type="hidden"  name="issue_product_code" value="'.$entry['issue_product_code'].'">';
				echo '<div class="col-sm-6">Product Code : </div><div class="col-sm-3">Qty : </div><div class="col-sm-3">Cost : </div><div class="col-sm-6">';
				list_input($db,get_all_product($db),$value=$entry['issue_product_code'],$name='issue_product_code','blank','1',$entry['issue_number']);
				
				echo'</div>';
				echo'<div class="col-sm-3"><input class="form-control" type="number" min="0" id="qty" name="issue_qty" '.allow_modify($db,'admin_or_openedby_for_24hours','',$entry['issue_number']).' value="';
				if(!empty($entry['issue_qty'])){echo $entry['issue_qty'];}else {echo '1';}
				echo '"></div><div class="col-sm-3">';
				if(!empty($entry['issue_cost'])){echo '$'.number_format(round($entry['issue_cost']),2);} else {echo'';}
				echo'</div>';
				
				// echo '<div class="col-sm-12">Product Code : </div><div class="col-sm-12"><input class="form-control" type="text" id="issue_product_code"  name="issue_product_code" value="'.$entry['issue_product_code'].'"></div> ';
			
			echo '<div class="col-sm-12">Issue Details : </div><div class="col-sm-12"><textarea class="form-control" id="issue_details" '.allow_modify($db,'admin_or_openedby_for_24hours','',$entry['issue_number']).' name="issue_details" name="Text1" cols="30" rows="5" >'.$entry['issue_details'].'</textarea></div> ';
			
			//if coming from Metrology, Prefill form
		if(!empty($_POST['single_id']) and (empty($entry['issue_rownumber']))){
			echo'<script>document.getElementById("issue_how").value = "Metrology Testing";</script>';
			echo'<script>document.getElementById("issue_product_code").value = "'.$_POST['product_test'].'";</script>';
			echo'<script>document.getElementById("issue_details").value = "'.$_POST['caption'].'";</script>';
			
		}
}
function edit_issue_log_rightpart_report($issue_number,$entry){
	if(empty($entry['issue_closeby'])){$entry['closed']=0;}else{$entry['closed']=1;}
	if($_POST['type']=='link'){?>
		<div class="line-close"><?php edit_issue_log_link($issue_number,$entry)?></div>
		<?php
	}elseif($_POST['type']=='transfer_assign'){?>
		<div class="line-assigned"><?php edit_issue_log_transfer_assign($issue_number,$entry)?></div>
		<?php
	}elseif($entry['closed']==1 or $_POST['type']=='close'){?>
		<div class="line-close"><?php edit_issue_log_closed($issue_number,$entry)?></div>
		<?php
	}elseif((!empty($entry['issue_assignto']) or $_POST['type']=='assign')){?>
		<div class="line-assigned"><?php edit_issue_log_assigned($issue_number,$entry)?></div>
			<?php
	}elseif(empty($entry['assignedto'])and(!empty($entry['issue_number']))){?>
		<div class="line-open"><?php edit_issue_log_assign_to($issue_number,$entry)?></div>
			<?php
	}else{?>
		<div class="line-open"></div>
			<?php
	}
		
		
}
function edit_issue_log_assign_to($issue_number,$entry){
	$db=$GLOBALS['db'];?>
	
		<div class="progress">
			<div class="progress-bar bg-info" role="progressbar" aria-valuenow="70"aria-valuemin="0" aria-valuemax="100" style="width:20%">Issue Raised </div>
		</div>
		<br>Issue Active and not Assigned
		<br>
		<br>
		<button type="submit" name="type" value="assign" class="btn btn-default" >Assign Issue</button>
	<?php		
	
}
function edit_issue_log_assigned($issue_number,$entry){
	$db=$GLOBALS['db'];?>
	<div class="progress">
				  <div class="progress-bar bg-info" 
				  role="progressbar" 
				  aria-valuenow="70"
				  aria-valuemin="0" 
				  aria-valuemax="100" 
				  style="width:50%">Issue Active<?php if($entry['issue_nbr_day_open']>0){echo ' ( '.$entry['issue_nbr_day_open'].' days)';}?></div>
				</div>
				Issue still Active<br><br>
			
			<?php if(!empty($entry['issue_assignto'])){?>
				Assigned to <?php echo $entry['issue_assignto']?>
				<button type="submit" 
				name="type" 
				value="remove_assign" 
				<?php echo allow_modify($db,'admin_assignto_openby','',$entry['issue_number'],'disabled')?>  
				class="btn btn-default btn-sm" ><span class="glyphicon glyphicon-remove" ></span></button>

				<button type="submit" 
				name="type" 
				value="transfer_assign" 
				<?php echo allow_modify($db,'admin_assignto_openby','',$entry['issue_number'],'disabled')?>  
				class="btn btn-default btn-sm" ><span class="glyphicon glyphicon-transfer" ></span></button>

				<?php if (!empty($entry['issue_ccto'])){echo ' <br><i><small>CC : '.$entry['issue_ccto'].'</small></i>';}?>
			<br>Action Taken : 
			<textarea class="form-control" 
			id="issue_action_taken"  
			<?php echo allow_modify($db,'admin_assignto_openby','',$entry['issue_number'])?>  
			name="issue_action_taken" 
			 
			cols="30" 
			rows="3" ><?php echo$entry['issue_action_taken']?></textarea> 
			<br>		
			<div class="row">
				<?php $all_comment=get_all_comment($issue_number);
				foreach($all_comment as $comment){?>
					<div class="row">
						<div class="col-xs-3"><?php echo date('jS M G:i',$comment['comment_timetag'])?></div>
						<div class="col-xs-2"><?php echo $comment['comment_member']?></div>
						<div class="col-xs-6"><?php echo $comment['comment_entry']?></div>
						<?php if($_SESSION['temp']['id']==$comment['comment_member'] or $_SESSION['temp']['role_issue_log_modify']==1){?>
							<div class="col-xs-1"><button type="submit" name="type" value="remove_comment,<?php echo $comment['comment_id']?>"class="form-control"><span class="glyphicon glyphicon-trash"></span></button></div>
						<?php }?>
					</div>
					<?php
				}
				?>
				
				<?php if(!empty($_SESSION['temp']['id'])){?>
					<div class="row">
						<div class="col-xs-3"></div>
						<div class="col-xs-8"><input type="text" name="comment_to_add" class="form-control" placeholder="Add a comment"></div>
						<div class="col-xs-1"><button type="submit" name="type" value="add_comment"class="form-control"><span class="glyphicon glyphicon-plus"></span></button></div>
					</div>
				<?php }?>
			</div>
			<br><br>
			<div class="row">
				<div class="col-xs-6">
					<button type="submit" 
					name="type" 
					value="close" 
					<?php echo allow_modify($db,'everyone_logged_in','','','disabled')?>  
					class="btn btn-default"  >Close Issue</button>
				</div><div class="col-xs-6">
					<button type="submit" 
					name="type" 
					value="link" 
					<?php echo allow_modify($db,'everyone_logged_in','','','disabled')?>  
					class="btn btn-default"  >Link to another PIL</button>
				</div>
			</div>
			
			
						
						
						
			<?php	
				///issue to be assigned
			}else  {
				$listeemployee=listemployee_issue();$listegroupe=list_group_issue();?>

				Assigned to :
				<input type="hidden"  name="issue_assignto" value="<?php echo$_SESSION['temp']['id']?>">
				<select name="issue_assignto" <?php echo allow_modify($db,'admin_assignto_openby','',$entry['issue_number'],'disabled')?> class="form-control" id="employee">	
						<?php foreach ($listegroupe as &$employee){?>
							<option 
							<?php if($_SESSION['temp']['id']==$employee['employee_code']){echo 'selected';}?>
							value="<?php echo $employee['id_to_use']?>"><?php echo $employee['name_to_show']?></option> 
							<?php
						}?>
						<option disabled>______</option>
						<?php foreach ($listeemployee as &$employee){?>
							<option 
							<?php if($entry['issue_assignto']==$employee['employee_code']){echo 'selected';}?>
							value="<?php echo $employee['id_to_use']?>"><?php echo $employee['name_to_show']?></option> 
							<?php
						}?>
				  
				   </select>
				  
				  <br>CC to:
				  <select multiple size="6" name="issue_ccto[]" <?php echo allow_modify($db,'admin_assignto_openby','',$entry['issue_number'],'disabled')?> class="form-control" id="employee">';	
						
						<option value=""></option> 
						<?php foreach ($listegroupe as &$employee){?>
							<option 
							
							value="<?php echo $employee['id_to_use']?>"><?php echo $employee['name_to_show']?></option> 
							<?php
						}?>
						<option disabled>______</option>
						<?php foreach ($listeemployee as &$employee){?>
							<option 
							
							value="<?php echo $employee['id_to_use']?>"><?php echo $employee['name_to_show']?></option> 
							<?php
						}?>
				  
				  </select>
				  
							
				<br>
				<button type="submit" 
				name="type" 
				<?php echo allow_modify($db,'everyone_logged_in','','','disabled')?> 
				value="assignto" 
				class="btn btn-default" >Assign Issue</button>
				<?php
			}	
		
	
}
function edit_issue_log_transfer_assign($issue_number,$entry){
	$db=$GLOBALS['db'];?>
	<div class="progress">
				  <div class="progress-bar bg-info" 
				  role="progressbar" 
				  aria-valuenow="70"
				  aria-valuemin="0" 
				  aria-valuemax="100" 
				  style="width:50%">Issue Active<?php if($entry['issue_nbr_day_open']>0){echo ' ( '.$entry['issue_nbr_day_open'].' days)';}?></div>
				</div>
				
				Currently assigned to  <?php echo $entry['issue_assignto']?>
				

				<button type="submit" 
				name="type" 
				value="transfer_assign" 
				<?php echo allow_modify($db,'admin_assignto_openby','',$entry['issue_number'],'disabled')?>  
				class="btn btn-default btn-sm" ><span class="glyphicon glyphicon-transfer" ></span></button>

				<?php if (!empty($entry['issue_ccto'])){echo ' <br><i><small>CC : '.$entry['issue_ccto'].'</small></i>';}?>
				<br>
				<?php 
				$listeemployee=listemployee_issue();
				$listegroupe=list_group_issue();
				$allcc=get_all_cc($issue_number);
				?>

				<div class="row"><br>Re-Assigned to :<br></div>
				
				<div class="row">
					<div class="col-xs-3"></div>
					<div class="col-xs-3">
						<select name="issue_assignto" <?php echo allow_modify($db,'admin_assignto_openby','',$entry['issue_number'],'disabled')?> class="form-control" id="employee">	
							<?php foreach ($listegroupe as &$employee){?>
								<option 
								<?php if($entry['issue_assignto']==$employee['employee_code']){echo 'selected';}?>
								value="<?php echo $employee['id_to_use']?>"><?php echo $employee['name_to_show']?></option> 
								<?php
							}?>
							<option disabled>______</option>
							<?php foreach ($listeemployee as &$employee){?>
								<option 
								<?php if($entry['issue_assignto']==$employee['employee_code']){echo 'selected';}?>
								value="<?php echo $employee['id_to_use']?>"><?php echo $employee['name_to_show']?></option> 
								<?php
							}?>
						
						</select>
					</div>
					<div class="col-xs-3">
						<button type="submit" 
						name="type" 
						value="re_assign_issue" 
						<?php echo allow_modify($db,'admin_assignto_openby','',$entry['issue_number'],'disabled')?>  
						class="btn btn-default btn-sm" >Save</button>
					</div>
					<div class="col-xs-3"></div>
				</div>
				<div class="row"><br>CC to :<br></div>
				<div class="row">
					<div class="col-xs-3"></div>
					<div class="col-xs-6">
						<select multiple size="10" name="issue_ccto[]" <?php echo allow_modify($db,'admin_assignto_openby','',$entry['issue_number'],'disabled')?> class="form-control" id="employee">	
							<option value=""></option> 	
							<?php foreach ($listegroupe as &$employee){?>
								<option 
								<?php if($allcc[$employee['id_to_use']]==$employee['id_to_use']){echo 'selected';}?>
								value="<?php echo $employee['id_to_use']?>"><?php echo $employee['name_to_show']?></option> 
								<?php
							}?>
							<option disabled>----------</option>
							<?php foreach ($listeemployee as &$employee){?>
								<option 
								<?php if($allcc[$employee['id_to_use']]==$employee['id_to_use']){echo 'selected';}?>
								value="<?php echo $employee['id_to_use']?>"><?php echo $employee['name_to_show']?></option> 
								<?php
							}?>
						
						</select>
					</div>
					<div class="col-xs-3"></div>
					
				</div>

				
			
			
			<?php	
			
						
						
						
	
}
function edit_issue_log_closed($issue_number,$entry){
	$db=$GLOBALS['db'];
	if(empty($entry['issue_closeby'])){$entry['closed']=0;}else{$entry['closed']=1;}
	
	echo'	<div class="progress">
		<div class="progress-bar bg-info" role="progressbar" aria-valuenow="70"
		aria-valuemin="0" aria-valuemax="100" style="width:100%">
			Issue Closed
		</div>
	</div>';
	echo '<div class="row">Closed by : </div><div class="row"><div class="col-sm-6"><input class="form-control" type="text" id="issue_closeby"  '.allow_modify($db,'admin_closeby_everyoneloggedin','',$entry['issue_number']).'  name="issue_closeby" value="';
	if(!empty($entry['issue_closeby'])and $entry['issue_closeby']<>""){echo $entry['issue_closeby'];}else {echo $_SESSION['temp']['id'];}  
	echo '"></div>';
	echo'<div class="col-sm-6">';
	
	if($entry['closed']==1){
	echo ' <button type="submit" name="type" value="reopen" '.allow_modify($db,'admin_closeby_everyoneloggedin','',$entry['issue_number'],'disabled').'  class="btn btn-default btn-sm" >Re-Open Issue</button>';
	}
	echo'</div></div>';
	
	echo '<div class="row"><div class="col-sm-2">Date: </div>
	<div class="col-sm-10">
	<input class="form-control" type="date" id="issue_close_date"  '.allow_modify($db,'admin_closeby_everyoneloggedin','',$entry['issue_number']).'  name="issue_close_date" value="';
	if(!empty($entry['issue_close_date'])and $entry['issue_close_date']>0){echo date('Y-m-d',$entry['issue_close_date']);}else {echo date('Y-m-d');}
	echo'">
	</div></div>';
	
	echo '<input class="form-control" type="hidden" id="issue_time_closed" '.allow_modify($db,'admin_or_just_created','',$entry['issue_number']).' name="issue_time_closed" value="';
	if(!empty($entry['issue_close_date'])and $entry['issue_close_date']>0){echo date('H:i',$entry['issue_close_date']);}else {echo date('H:i');}
	echo '">';
	
	echo '<div class="row">Root Cause Cat.: </div><div class="col-sm-12">';
	list_input($db,get_all_root_cause($db),$value=$entry['issue_root_cause'],$name='issue_root_cause','','',$entry['issue_number'],'admin_closeby_everyoneloggedin');
	
	echo'</div>';
	
	echo '<div class="row">Action Taken : </div><div class="col-sm-12"><textarea class="form-control" id="issue_action_taken"  '.allow_modify($db,'admin_closeby_everyoneloggedin','',$entry['issue_number']).'  name="issue_action_taken" name="Text1" cols="30" rows="5" >';
	if(!empty($_POST['issue_action_taken'])){
		echo $_POST['issue_action_taken'];
	}else{
		echo $entry['issue_action_taken'];
	}
	echo'</textarea></div> ';


	echo '<div class="row">Verifications: </div><div class="col-sm-12"><textarea class="form-control" id="issue_verification"  '.allow_modify($db,'admin_closeby_everyoneloggedin','',$entry['issue_number']).'  name="issue_verification" name="Text1" cols="30" rows="2" >'.$entry['issue_verification'].'</textarea></div> ';
	echo '<div class="row">IAF No(if applicable) : </div><div class="col-sm-12"><input class="form-control" type="text" '.allow_modify($db,'admin_closeby_everyoneloggedin','',$entry['issue_number']).'  id="issue_IAF"  name="issue_IAF" value="'.$entry['issue_IAF'].'"></div>';
	

	echo '<div class="row">Comments: </div><div class="col-sm-12"><textarea class="form-control" id="issue_comments"  '.allow_modify($db,'admin_closeby_everyoneloggedin','',$entry['issue_number']).'  name="issue_comments" name="Text1" cols="30" rows="2" >'.$entry['issue_comments'].'</textarea></div> ';
			
				
			
}
function edit_issue_log_link($issue_number,$entry){
	$db=$GLOBALS['db'];
	$id=$_POST['issue_number'];
	$listePIL=load_all_log("and issue_number<>'$id'","issue_closed asc,issue_date_created DESC,issue_rownumber DESC"); ?>
	
	<div class="col-xs-10">
		<select name="issue_linkto" <?php echo allow_modify($db,'admin_assignto_openby','',$entry['issue_number'],'disabled')?> class="form-control" id="employee">
		   <?php foreach ($listePIL as &$PIL){
			   if($PIL['issue_closed']==1){$closed='';}else{$closed='[Still Open] - ';}
			   $caption=$closed.''.$PIL['issue_number'].' | '.$PIL['issue_product_code'];?>
				   <option value="<?php echo$PIL['issue_number']?>"><?php echo$caption?></option>
		   <?php }?>
	   </select>
	</div>
	<div class="col-xs-2">
		<button type="submit" name="type" <?php echo allow_modify($db,'everyone_logged_in','','','disabled')?> value="linkto" class="btn btn-default" >Link</button>
		<button type="submit" name="type"  value="edit" class="btn btn-default" >Cancel</button>
	</div>
		
	 
	<br>
	<?php
}
function edit_issue_log_linked($issue_number,$entry){
	$db=$GLOBALS['db'];
	$alllinked=get_all_PIL_linked($issue_number);
	if(!empty($alllinked)){?>
		<div class="row all_linked"><b>PIL Linked</b>
			<?php
			foreach($alllinked as $linked_PIL){?>
				<div class="row one_linked">
					<div class="col-xs-1"><?php echo date('jS M y',$linked_PIL['issue_date_created'])?></div>
					<div class="col-xs-1"><a href="prod-issue-log2.php?issue=<?php echo $linked_PIL['issue_number']?>"><?php echo $linked_PIL['issue_number']?></a></div>
					<div class="col-xs-6"><?php echo $linked_PIL['issue_details']?></div>
					<div class="col-xs-4"><?php
					$all_attach=load_attachment($db,$linked_PIL['issue_number']);
					foreach ($all_attach as &$attachment){
						if (substr($attachment['attachment_path'], -3)=='pdf'){
							$img='<img class="attachment" src="img/pdf.png"  >';
						}else{
							$img='<img class="attachment" src="attachment/'.$attachment['attachment_path'].'"  >';
						}
						?>
						<div class="col-xs-6 "><a target="blank"  href="attachment/<?php echo$attachment['attachment_path']?>"><?php echo $img?></a><br><?php echo $attachment['attachment_caption']?></div>
					<?php 
					}
					?>
					</div>
					
				</div>

				<?php
				//show($linked_PIL);
			}?>
		</div>
		<style>
			.all_linked{
				height: auto;
				background: #cbdbcd;
				border: 1px solid black;
				border-radius: 25px;
				text-align: center;
				padding:5px;
			}
			.one_linked{
				border: 1px solid black;
				border-radius: 25px;
				padding:5px;
				
			}
			
		</style>
	<?php
	}
}
function edit_issue_log_console($issue_number,$entry){
	$db=$GLOBALS['db'];
	?>
	
	<br><br><br>
	<button type="submit" <?php echo allow_modify($db,'everyone_logged_in','',$entry['issue_number'],'disabled')?> name="type" value="save" class="btn btn-default" ><span class="glyphicon glyphicon-floppy-disk" ></span></button>
	<br>
		<?php if (nextattachment($db,$issue_number)>1) {$protected=' disabled ';}
		
		
		if(!empty($entry['issue_number'])){?>
		<button 
			type="submit" <?php echo allow_modify($db,'delete_log','',$entry['issue_number'],'disabled')?> 
			name="type" 
			value="delete" <?php echo$protected?> 
			class="btn btn-default">
			<span class="glyphicon glyphicon-trash" ></span>
		</button>
		<?php }
}
function edit_issue_log_attachments($issue_number,$entry){
	$db=$GLOBALS['db'];
	if(!empty($entry['issue_number'])){
		$all_attach=load_attachment($db,$issue_number);
		foreach ($all_attach as &$attachment){
			echo'<form method="POST"><div class="row line-attachment">';
				echo'<div class="col-sm-6 ">';
				if (substr($attachment['attachment_path'], -3)=='pdf'){
					echo'<img class="attachment" src="img/pdf.png"  >';
				}else{
					echo'<a target="blank"  href="attachment/'.$attachment['attachment_path'].'">';
						echo'<img class="attachment" src="attachment/'.$attachment['attachment_path'].'"  >';
					echo'</a>';
				}
				
				
				echo '<br>'.$attachment['attachment_caption'];
				echo'</div>';
				echo'<div class="col-sm-5 ">';
				
				echo'</div>';
				echo'<div class="col-sm-1 last-part">';
				echo'<br>';
						//echo '<input type="hidden"  name="issue_number" value="'.$entry['issue_number'].'">';
						
						echo'<a target="blank"  href="attachment/'.$attachment['attachment_path'].'">';
							echo'<button type="button" class="btn btn-default" >';
							echo'<span class="glyphicon glyphicon-resize-full" ></span>';
							echo'</button>';
						echo'</a>';
					echo'<br><br>';
						//echo '<input type="hidden"  name="issue_number" value="'.$entry['issue_number'].'">';
						
						//echo '<br><input type="hidden"  name="issue_number" value="'.$entry['issue_number'].'">';
						echo '<input type="hidden"   name="attachment_number" value="'.$attachment['attachment_number'].'">';
						echo '<input type="hidden"  name="issue_number" value="'.$issue_number.'">';
				
						echo'<button type="submit" name="type" '.allow_modify($db,'everyone_logged_in','',$entry['issue_number'],'disabled').' value="delete_attachment" class="btn btn-default" >
									<span class="glyphicon glyphicon-trash" ></span>
									</button>';
				echo'</div>';
			echo'</div>';
			echo'</form>';
		}
		
		echo'<form method="POST" enctype="multipart/form-data" ><div class="row line-attachment">';
				echo'<div class="col-sm-2 ">';
				echo'Add a picture/document (2Mo max)';
				echo'</div>';
				echo'<div class="col-sm-3 ">';
				echo'<input class="form-control" type="file" '.allow_modify($db,'everyone_logged_in','',$entry['issue_number'],'disabled').' name="fileToUpload" id="fileToUpload" accept="image/*;capture=camera">';
				echo '<input type="hidden"  name="issue_number" value="'.$issue_number.'">';
						
				echo'</div>';
				echo'<div class="col-sm-3 ">';
				echo '<input class="form-control" type="text"  '.allow_modify($db,'everyone_logged_in','',$entry['issue_number'],'disabled').' name="attachment_caption" placeholder="Add a caption if needed">';
				echo'</div>';
				echo'<div class="col-sm-1 ">';
				
				echo'</div>';
				echo'<div class="col-sm-1 ">';
				echo'<button type="submit" name="type" '.allow_modify($db,'everyone_logged_in','',$entry['issue_number'],'disabled').' value="upload_attachment" class="btn btn-default" >
									<span class="glyphicon glyphicon-upload" ></span> Upload
									</button>';
				echo'</div>';
				echo'<div class="col-sm-1 ">';
				
				echo'</div>';
				echo'<div class="col-sm-1 last-part">';
				echo'</div>';
			echo'</div>';
		echo'</form>';
	
	}
}
function edit_issue_log_log($issue_number,$entry){
	if(!empty($entry['issue_number'])){
		?>
		<div class="line_log">
			<div class="log_header" onclick="toggle_log()">Log</div>
			<div class="log_content" id="thelog" style="display:none">
				<?php $all_log=get_all_log($issue_number);
				foreach($all_log as $log){?>
					<div class="row">
						<div class="col-xs-3"></div>
						<div class="col-xs-1"><?php echo date('Y-m-d G:i:s',$log['issuelog_timetag'])?></div>
						<div class="col-xs-1"><?php echo $log['issuelog_member']?></div>
						<div class="col-xs-4"><?php echo $log['issuelog_entry']?></div>
					</div>
					<?php
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
				height: auto;
				background: #acc5c8;
				background: #f5f5f5;
				border: 1px solid black;
				border-radius: 25px;
				overflow: hidden;
				margin-bottom: 5px;
			}
		</style>
		<?php
	}
}


function load_all_log($option='',$orderby='issue_date_created DESC,issue_rownumber DESC'){
	$db=$GLOBALS['db'];
		
		if(empty($_SESSION['temp']['show_all'])){
			$id=$_SESSION['temp']['id'];
			$myfilter="AND (issue_openby='$id' OR issue_assignto='$id' OR issue_closeby='$id' OR issue_ccto like '%$id%' OR groupallocation_employee='$id' ) ";

			
		}
		if(!empty($_SESSION['temp']['issue_assignto'])){
			$id=$_SESSION['temp']['issue_assignto'];
			$myfilter="AND ( issue_assignto='$id' ) ";
		}
		if(empty($_SESSION['temp']['show_closed'])){
			$filterclose="AND (issue_closeby=''  ) ";
		}
		if(!empty($_SESSION['temp']['search'])){
			$searchfilter='AND (
			issue_number LIKE \'%'.$_SESSION['temp']['search'].'%\' 
			OR issue_openby LIKE \'%'.$_SESSION['temp']['search'].'%\' 
			OR issue_how LIKE \'%'.$_SESSION['temp']['search'].'%\' 
			OR issue_product_code LIKE \'%'.$_SESSION['temp']['search'].'%\' 
			OR issue_details LIKE \'%'.$_SESSION['temp']['search'].'%\' 
			
			OR issue_root_cause LIKE \'%'.$_SESSION['temp']['search'].'%\' 
			OR issue_action_taken LIKE \'%'.$_SESSION['temp']['search'].'%\' 
			OR issue_verification LIKE \'%'.$_SESSION['temp']['search'].'%\' 
			OR issue_comments LIKE \'%'.$_SESSION['temp']['search'].'%\' 
			OR issue_assignto LIKE \'%'.$_SESSION['temp']['search'].'%\' 
			OR issue_ccto LIKE \'%'.$_SESSION['temp']['search'].'%\'
			
			OR issue_date_created_excel LIKE \'%'.$_SESSION['temp']['search'].'%\'
			 
			
			) ';
		}
		
		
		
		$query="SELECT [issue_number]
		,[issue_rownumber]
		,[issue_year]
		,[issue_date_created]
		,[issue_date_created_excel]
		,[issue_openby]
		,[issue_how]
		,[issue_product_code]
		,[issue_qty]
		,[issue_cost]
		,[issue_details]
		,[issue_closeby]
		,[issue_root_cause]
		,[issue_action_taken]
		,[issue_closed]
		,[issue_verification]
		,[issue_IAF]
		,[issue_comments]
		,[issue_close_date]
		,[issue_close_date_excel]
		,[issue_assignto]
		,[issue_assign_date]
		,[issue_assign_date_excel]
		,[issue_nbr_day_open]
		,[issue_inprogress_comment]
		,[issue_priority]
		,[issue_ccto]
		,[issue_single_id]
		,[issue_parent_issue]
  FROM issue_log
  left join employee_group on group_name=issue_assignto
  left join employee_group_allocation on group_id=groupallocation_groupid
  WHERE 1=1 $myfilter $filterclose $searchfilter $option
  group by [issue_number]
      ,[issue_rownumber]
      ,[issue_year]
      ,[issue_date_created]
      ,[issue_date_created_excel]
      ,[issue_openby]
      ,[issue_how]
      ,[issue_product_code]
      ,[issue_qty]
      ,[issue_cost]
      ,[issue_details]
      ,[issue_closeby]
      ,[issue_root_cause]
      ,[issue_action_taken]
      ,[issue_closed]
      ,[issue_verification]
      ,[issue_IAF]
      ,[issue_comments]
      ,[issue_close_date]
      ,[issue_close_date_excel]
      ,[issue_assignto]
      ,[issue_assign_date]
      ,[issue_assign_date_excel]
      ,[issue_nbr_day_open]
      ,[issue_inprogress_comment]
      ,[issue_priority]
      ,[issue_ccto]
      ,[issue_single_id]
      ,[issue_parent_issue]
  order by $orderby
  
	";
	
	$sql = $db->prepare($query); 
	//show($_SESSION['temp']['search']);
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	//
	return $row;
}

function load_one_log($db,$issue_number){
		$query='SELECT *
  FROM issue_log
  WHERE issue_number=\''.$issue_number.'\'
	';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
	//
	return $row;
}

function get_all_how_found($db){
		$query='SELECT *
  FROM issue_how_found
  WHERE [how_went_wrong]=1
  Order by how_name ASC
	';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	//
	return $row;
}

function get_all_root_cause($db){
		$query='SELECT *
  FROM issue_root_cause
  Order by root_name ASC
	';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	//
	return $row;
}

function get_all_product($db){
		$query='SELECT Product_Code
  FROM List_Document
  Where (PRODUCT_FAMILY=\'CAW/CCW\' or
	PRODUCT_FAMILY=\'HSC/ILC/MCB\' or
	PRODUCT_FAMILY=\'MTRS\' or
	PRODUCT_FAMILY=\'MUCI\' or
	PRODUCT_FAMILY=\'OVERHEAD\' or
	PRODUCT_FAMILY=\'PFV\' or
	PRODUCT_FAMILY=\'PHSR\' or
	PRODUCT_FAMILY=\'Piranha\' or
	PRODUCT_FAMILY=\'Piranha/MUCI\' or
	PRODUCT_FAMILY=\'SOLAR\' or
	PRODUCT_FAMILY=\'TTD/NDT\' or
	PRODUCT_FAMILY=\'UNSPECIFIED\' or 1=1 )
	AND 1=1
	
	order by Product_Code ASC
	';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	//
	return $row;
}
function get_all_PIL_linked($issue_number){
	$db=$GLOBALS['db'];
	$row=array();
	if(!empty($issue_number)){
		$query="SELECT * FROM issue_log
		WHERE issue_parent_issue='$issue_number' and issue_parent_issue is <>''
		order by issue_date_created desc";
		
		$sql = $db->prepare($query); 
		//show($query);
		$sql->execute();
	
		$row=$sql->fetchall();
	}
	
	//
	return $row;

}

function next_rownumber($db){
	$current_year=date('Y');
	$query='SELECT top (1) (issue_number)
	  FROM issue_log
	  Where issue_year=\''.$current_year.'\'
	  ORDER BY issue_date_created DESC
	';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
	//
	// $temp=$row[0];
	$row[0]=substr ( $row[0] , -3 );
	$row[0]=(int)$row[0];
	
	
	return $row[0];
}

function list_input($db,$list,$value='',$name='default',$firstrow='',$custom='',$issue_number='',$role='admin_or_just_created'){
	$i=0;
	if($custom==''){echo '<select name="'.$name.'" '.allow_modify($db,$role,'',$issue_number,'disabled').' class="form-control" id="'.$name.'">';}else
	{
		echo '<input type="text" list="thelist" name="'.$name.'" class="form-control" id="'.$name.'" value="'.$value.'">
		<datalist id="thelist">';
	}
		
		// echo '<input type="text" list="thelist" name="'.$name.'" class="form-control" id="'.$name.'">
		 // <datalist id="thelist">';
		
		if ($firstrow<>''){
			if($firstrow=='blank'){$firstrow='';}
			echo"<option >".$firstrow."</option>";
		}
		foreach ($list as &$item){
				echo"<option ";
				if($item[0]==$value){echo 'selected';$i++;}
				echo">".$item[0]."</option>";
				}
		 if($custom==''){echo '</select>';}else{echo '</datalist>';}
		//echo '</datalist>';
		if($i==0){echo '<i>'.$value.'</i>';}
	
	
}

function calculate_cost($db,$product,$qty){

	$query='SELECT LastCost 
  FROM List_Document
  Where Product_Code=\''.$product.'\' ';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
	
	if(!empty($row)){
		$cost=$row[0]*$qty;
	}else{
		$cost=0;
	}
	


	
	return $cost;
}

function save_log($db){
	
	
	$id=$_POST['issue_number'];
	
	
		
	
	
	
	
	$openby=$_POST['issue_openby'];
	
		$tempdate=new datetime($_POST['issue_date_created'].' '.$_POST['issue_time_created']);
		// show($_POST['issue_date_created'].' '.$_POST['issue_time_created']);
		// show($tempdate);
		// show($tempdate->getTimestamp());
	$rownumber=$_POST['issue_rownumber'];
	$datecreated = $tempdate->getTimestamp();
	$datecreated_excel=(date('Y-m-d',$datecreated));
	$year=date('Y',$datecreated);
	$how=$_POST['issue_how'];
	$product=$_POST['issue_product_code'];
	$qty=$_POST['issue_qty'];
	$cost=calculate_cost($db,$product,$qty);
	$details=$_POST['issue_details'];
	$closeby=$_POST['issue_closeby'];
	$rootcause=$_POST['issue_root_cause'];
	$actiontaken=$_POST['issue_action_taken'];
	$priority=$_POST['issue_priority'];
	if(empty($closeby)){$closed=0;}else{$closed=1;}
	$verification=$_POST['issue_verification'];
	$IAF=$_POST['issue_IAF'];
	$comments=$_POST['issue_comments'];
	$dateclosed_excel='NULL';
	$single_id=$_POST['single_id'];
		if(!empty($_POST['issue_close_date'])){
			$tempdate=new datetime($_POST['issue_close_date'].' '.$_POST['issue_time_closed']);
		$dateclosed = $tempdate->getTimestamp();
		$dateclosed_excel=(date("'Y-m-d'",$dateclosed));
		$nbrdayopen=max(0,floor(($dateclosed-$datecreated)/3600/24));
		}
		else
		{
			
			$nbrdayopen=max(0,floor((time()-$datecreated)/3600/24));
		}
	
	
	
	if(does_log_exist($db,$id)==true){
	
	
		$query='UPDATE dbo.issue_log SET 
		issue_openby=\''.$openby.'\',
		issue_date_created=\''.$datecreated.'\',
		issue_date_created_excel=\''.$datecreated_excel.'\',
		issue_year=\''.$year.'\',
		issue_how=\''.$how.'\',
		issue_product_code=\''.$product.'\',
		issue_qty=\''.$qty.'\',
		issue_cost=\''.$cost.'\',
		issue_details=\''.$details.'\',
		issue_closeby=\''.$closeby.'\' ,
		issue_root_cause=\''.$rootcause.'\',
		issue_action_taken=\''.$actiontaken.'\',
		issue_closed=\''.$closed.'\',
		issue_verification=\''.$verification.'\',
		issue_IAF=\''.$IAF.'\',
		issue_comments=\''.$comments.'\',
		issue_close_date=\''.$dateclosed.'\',
		issue_close_date_excel='.$dateclosed_excel.',
		issue_priority=\''.$priority.'\',
		issue_nbr_day_open=\''.$nbrdayopen.'\',
		issue_rownumber=\''.$rownumber.'\',
		issue_single_id=\''.$single_id.'\'
		
		WHERE issue_number=\''.$id.'\'';
	}
	else
	{
	$query="INSERT INTO dbo.issue_log
	( issue_openby,
	issue_date_created,
	issue_date_created_excel,
	issue_year,
	issue_how,
	issue_product_code,
	issue_qty,
	issue_cost,
	issue_details,
	issue_closeby,
	issue_root_cause,
	issue_action_taken,
	issue_closed,
	issue_verification,
	issue_IAF,
	issue_comments,
	issue_close_date,
	issue_close_date_excel,
	issue_number,
	issue_rownumber,
	issue_nbr_day_open,
	issue_priority,
	issue_single_id
	
	) 
	VALUES (
	'$openby',
	'$datecreated',
	'$datecreated_excel',
	'$year',
	'$how',
	'$product',
	'$qty',
	'$cost',
	'$details',
	'$closeby',
	'$rootcause',
	'$actiontaken',
	'$closed',
	'$verification',
	'$IAF',
	'$comments',
	'$dateclosed',
	$dateclosed_excel,
	'$id',
	'$rownumber',
	'$nbrdayopen',
	'$priority',
	'$single_id')";	
	}
	// if ($_SESSION['temp']['id']=='CorentinHillion'){show($_POST);}
	// if ($_SESSION['temp']['id']=='CorentinHillion'){show($query);}
	//show($query);
	$sql = $db->prepare($query); 

	$sql->execute();
	$entry="PIL saved";
	//$entry=$entry." by ".$_SESSION['temp']['id'];
	add_entry_log($id,$_SESSION['temp']['id'],$entry);
}

function add_PIL_to_single_test($db){
	$query='UPDATE dbo.metro_single SET 
		single_PIL_number=\''.$_POST['issue_number'].'\'
		
		WHERE single_id=\''.$_POST['single_id'].'\'';
		$sql = $db->prepare($query); 

		$sql->execute();

}

function remove_PIL_to_single_test($db){
	$query='UPDATE dbo.metro_single SET 
		single_PIL_number=\'\'
		
		WHERE single_PIL_number=\''.$_POST['issue_number'].'\'';
		$sql = $db->prepare($query); 

		$sql->execute();

}

function reopen_log($db){
		
	$id=$_POST['issue_number'];
	$entry=load_one_log($db,$id);
	if(!empty($entry['issue_parent_issue'])){
		$query="UPDATE dbo.issue_log SET 
		issue_closeby='' ,
		issue_close_date='',
		issue_parent_issue='',
		issue_action_taken=''
		
		WHERE issue_number='$id'";
	}else{
		$query="UPDATE dbo.issue_log SET 
		issue_closeby='' ,
		issue_close_date=''
		
		WHERE issue_number='$id'";
	}
	
	//show($query);
	
	$sql = $db->prepare($query); 

	$sql->execute();
	$entry="PIL reopen";
	if(!empty($string_cc_to)){$entry=$entry." CC:".$string_cc_to;}
	//$entry=$entry." by ".$_SESSION['temp']['id'];
	add_entry_log($id,$_SESSION['temp']['id'],$entry);
}
function link_log(){
	$db=$GLOBALS['db'];
	$id=$_POST['issue_number'];
	$issue_parent_issue=$_POST['issue_linkto'];
	$issue_action_taken="Linked to ".$_POST['issue_linkto'];
	$issue_action_taken="Linked to ".$_POST['issue_linkto'];

	$query="UPDATE dbo.issue_log 
	SET 
		issue_parent_issue='$issue_parent_issue',
		issue_action_taken='$issue_action_taken' 
	WHERE issue_number='$id'";
	$sql = $db->prepare($query); 
	$sql->execute();
	$parent=load_one_log($db,$_POST['issue_linkto']);
	$_POST['issue_action_taken']='Linked to <a href="prod-issue-log2.php?issue='.$_POST['issue_linkto'].'">'.$_POST['issue_linkto']."</a>";
	$_POST['issue_close_date']=date('Y-m-d',time());
	$_POST['issue_time_closed']=date('H:i',time());
	$_POST['issue_closeby']=$_SESSION['temp']['id'];
	$_POST['issue_root_cause']=$parent['issue_root_cause'];

	save_log($db);
	//show($query);
	$entry="PIL Link to ".$_POST['issue_linkto'];
	//$entry=$entry." by ".$_SESSION['temp']['id'];
	add_entry_log($id,$_SESSION['temp']['id'],$entry);
}

function add_comment(){
	$db=$GLOBALS['db'];
	$issue_number=$_POST['issue_number'];
	$entry=$_POST['comment_to_add'];
	$memberid=$_SESSION['temp']['id'];
	$sql = $db->prepare("INSERT INTO issue_comment(comment_issue_number,comment_timetag,comment_member,comment_entry)
	VAlUES('$issue_number',".time().",'$memberid','$entry')"); 
	
	$sql->execute();
	$entry="Comment added";
	//$entry=$entry." by ".$_SESSION['temp']['id'];
	add_entry_log($_POST['issue_number'],$_SESSION['temp']['id'],$entry);
}
function remove_comment(){
	$db=$GLOBALS['db'];
	$comment_id=substr($_POST['type'],15);
	$sql = $db->prepare("DELETE FROM issue_comment WHERE comment_id='$comment_id'"); 
	$sql->execute();
	$entry="Comment removed";
	//$entry=$entry." by ".$_SESSION['temp']['id'];
	add_entry_log($_POST['issue_number'],$_SESSION['temp']['id'],$entry);
}

function delete_log($db,$id){
	$query='DELETE FROM dbo.issue_log 
		WHERE issue_number=\''.$id.'\'';
	$sql = $db->prepare($query); 

	$sql->execute();
	
	$entry="PIL deleted";
	//$entry=$entry." by ".$_SESSION['temp']['id'];
	add_entry_log($id,$_SESSION['temp']['id'],$entry);
	
	
	
}

function assign_log($db,$id,$blank=''){
	
	$string_cc_to='';
	if ($blank=='blank'){
		$_POST['issue_assignto']='';
		$dateassign ='';
	}else{
		$dateassign = time();
		notify_assign_email($db);
	}
	$i=0;
	foreach ($_POST['issue_ccto'] as &$ccto){
		if($i==0){
			$string_cc_to=$ccto;
			$i=1;
		}
		else
		{
			$string_cc_to=$string_cc_to.','.$ccto;
		}
			
	}
	
	
	
	
	$query='UPDATE dbo.issue_log SET 
		issue_assignto=\''.$_POST['issue_assignto'].'\',
		issue_assign_date=\''.$dateassign.'\',
		issue_ccto=\''.$string_cc_to.'\'
		WHERE issue_number=\''.$id.'\'';
	$sql = $db->prepare($query); 
	
	$sql->execute();
	if ($blank=='blank'){
		$entry="PIL assigned to [removed] ";
	}else{
		$entry="PIL assigned to ".$_POST['issue_assignto']." ";
	}
	
	if(!empty($string_cc_to)){$entry=$entry." CC: ".$string_cc_to;}
	//$entry=$entry." by ".$_SESSION['temp']['id'];
	add_entry_log($id,$_SESSION['temp']['id'],$entry);
	
}
function re_assign_log($db,$id,$blank=''){
	$string_cc_to='';
	$i=0;
	foreach ($_POST['issue_ccto'] as &$ccto){
		if($i==0){
			$string_cc_to=$ccto;
			$i=1;
		}
		else
		{
			$string_cc_to=$string_cc_to.','.$ccto;
		}
			
	}	
	$dateassign = time();
	
	$query="UPDATE dbo.issue_log SET 
		issue_assignto='".$_POST['issue_assignto']."',
		issue_assign_date='$dateassign',
		issue_ccto='$string_cc_to'
		WHERE issue_number='$id'";
	$sql = $db->prepare($query); 
	
	$sql->execute();
	$entry="PIL re-assigned to ".$_POST['issue_assignto']." ";
	if(!empty($string_cc_to)){$entry=$entry." CC: ".$string_cc_to;}
	//$entry=$entry." by ".$_SESSION['temp']['id'];
	add_entry_log($id,$_SESSION['temp']['id'],$entry);
	
}

function does_log_exist($db,$id){
	$query='SELECT *
  FROM issue_log
  WHERE issue_number=\''.$id.'\'
	';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	if(empty($row)){return false;}else{return true;}
	
}

function show_navbar_issue_log(){
	$db=$GLOBALS['db'];?>
	<div class="row">
		<div class="col-sm-2 ">
			<form method="POST">
			<?php
			if($_SESSION['temp']['show_dashboard']==false){?>

			<div class="visible-xs-block visible-sm-block visible-md-block">
				<br><button type="submit" name="type" value="edit" <?php echo allow_modify($db,'everyone_logged_in','','','disabled')?> class="btn btn-default" >
									<span class="glyphicon glyphicon-plus" ></span>
				</button><br>&nbsp
			</div>
			<div class="hidden-xs hidden-sm hidden-md">	
				<br><button type="submit" name="type" value="edit" <?php echo allow_modify($db,'everyone_logged_in','','','disabled')?> class="btn btn-default" ><span class="glyphicon glyphicon-plus" ></span> Raise an Issue</button><br>&nbsp
			</div>
			<?php }?>
			</form>
		</div>
		<div class="col-sm-2 ">
			<form method="POST"><?php
			if($_SESSION['temp']['show_dashboard']==false){?>
			<br><input class="form-control" type="text"   name="search_word" placeholder="search">
			<?php }?>
		</div>
		<div class="col-sm-1 ">
			<?php if($_SESSION['temp']['show_dashboard']==false){?>
			<div class="visible-xs-block visible-sm-block visible-md-block">	
				<br><button type="submit" name="type" value="search" class="btn btn-default" >
								<span class="glyphicon glyphicon-search" > </span>
								</button><br>
			</div>
			<div class="hidden-xs hidden-sm hidden-md">	
				<br><button type="submit" name="type" value="search" class="btn btn-default" >
				<span class="glyphicon glyphicon-search" ></span> Search
				</button><br>
			</div>
			
			<?php }?>
			</form>
		</div>
		<div class="col-sm-1 ">
			<form method="POST"><?php
			if(!empty($_SESSION['temp']['search'])){?>
			
			<br><button type="submit" name="type" value="search"  class="btn btn-default" >
			<?php echo$_SESSION['temp']['search']?> <span class="glyphicon glyphicon-remove" > </span>
								<input class="form-control" type="hidden"   name="search_word" value="">
								</button><br>
			<?php }?>
			</form>
		
		
		
		</div>
		<div class="col-sm-2 ">
			<form method="POST">
			<?php
			if($_SESSION['temp']['show_dashboard']==false){
				if($_SESSION['temp']['show_closed']==true){?>
				
					<form method="POST">
					<br>
					<button type="submit" name="type" value="show_closed"  class="btn btn-default" >Only Active</button>
					<br>
					</form>
					<?php
				}
				else{?>
					<form method="POST">
					<br>
					<button type="submit" name="show_closed" value=""  class="btn btn-default" ><b>Only Active:</b><span class="glyphicon glyphicon-remove" > </span></button>
					<br>
					<input class="form-control" type="hidden"   name="type" value="show_closed">
					</form>
					<?php
				}
			}?>
			</form>
		</div>
		<div class="col-sm-2 ">
			<form method="POST">
			<?php
			if($_SESSION['temp']['show_dashboard']==false){
				if($_SESSION['temp']['show_all']==true){?>
				
					<form method="POST">
					<br><button type="submit" name="type" value="show_all"  class="btn btn-default" >
					Only Yours
					</button><br>
					</form>
					<?php
				}
				else{?>
					<form method="POST">
					<br><button type="submit" name="show_all" value=""  class="btn btn-default" ><b>Only Yours</b>
						<span class="glyphicon glyphicon-remove" > </span>
											<input class="form-control" type="hidden"   name="type" value="show_all">
											</button><br>
											</form>
				<?php }?>
				
			
			
			
				
			<?php }?>
			</form>
		</div>
		<div class="col-sm-2 ">
			<?php
			if ((!empty($_POST['type']))and !($_POST['type']=='return-2' or $_POST['type']=='return'or $_POST['type']=='show_all' or $_POST['type']=='show_closed')){?>
				<form method="POST">
					<br>
					<button type="submit" name="type" value="return" class="btn btn-default" ><span class="glyphicon glyphicon-arrow-up" > Back to the list</span></button>
					<br>
				</form>
				<?php
			}else
			{?>
				
				<form method="POST">
					<div class="visible-xs-block visible-sm-block visible-md-block">	
						<br>
						<button type="submit" name="type" value="show_dashboard" class="btn btn-default" ><span class="glyphicon glyphicon-signal" ></span> </button>
						<br>
					</div>
					<div class="hidden-xs hidden-sm hidden-md">
						<br>
						<button type="submit" name="type" value="show_dashboard" class="btn btn-default" ><span class="glyphicon glyphicon-signal" ></span> Dashboard</button>
						<br>
					</div>
				</form>
				
			<?php } ?>
		</div>
	</div>
			
		<?php
}

function all_actor_issue($db,$issue_number){
	$query='SELECT issue_openby,issue_assignto,issue_closeby,issue_ccto,issue_date_created_excel
	  FROM issue_log
	  Where issue_number=\''.$issue_number.'\'
	 
	';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
	if(!empty($row['issue_openby'])){
		$actors['issue_openby'][$row['issue_openby']]=$row['issue_openby'];
	}
	if(!empty($row['issue_assignto'])){
		$actors['issue_assignto'][$row['issue_assignto']]=$row['issue_assignto'];
	}
	if(!empty($row['issue_closeby'])){
		$actors['issue_closeby'][$row['issue_closeby']]=$row['issue_closeby'];
	}
	
	
	$actors['issue_date_created_excel']=$row['issue_date_created_excel'];

	$allcc=$row['issue_ccto'];
	$actors['issue_ccto']=array();
	$allcc=explode(",", $allcc);
	foreach($allcc as $cc){
		$ccs[$cc]=$cc;
	}
	$actors['issue_ccto']=array_filter($ccs);

	$alluser=check_if_group($row['issue_assignto']);
	foreach($alluser as $user){
		$actors['issue_assignto'][$user['groupallocation_employee']]=$user['groupallocation_employee'];
	}




	//show($actors);
	return $actors;
}


function allow_modify($db='',$role='everyone_logged_in',$exception='',$issue_number='',$disabled=''){
	$allow=0;
	if($role=='everyone_logged_in'){
		if(!empty($_SESSION['temp']['id'])){$allow=1;}
	}
	if($role=='only_admin'){
		if($_SESSION['temp']['Issue Log Admin']==1){$allow=1;}
	}
	if($role=='admin_or_just_created'){
		
		if($_SESSION['temp']['role_issue_log_modify']==1){$allow=1;}
		
		//if issue not assign, and session id = openby
		$actors=all_actor_issue($db,$issue_number);
		//show($actors);
		if(!empty($_SESSION['temp']['id'])){
			if(empty($actors['issue_assignto'])){
				if($actors['issue_openby'][$_SESSION['temp']['id']]==$_SESSION['temp']['id']){$allow=1;}
			}
		}
		if(empty($actors['issue_openby'])){
			$allow=1;
		}
		
		
	}
	if($role=='admin_or_openedby_for_24hours'){
		
		if($_SESSION['temp']['role_issue_log_modify']==1){$allow=1;}
		
		//if issue not assign, and session id = openby
		$actors=all_actor_issue($db,$issue_number);
		//show($actors);
		if(!empty($_SESSION['temp']['id'])){
			if(empty($actors['issue_assignto'])){
				if($actors['issue_openby'][$_SESSION['temp']['id']]==$_SESSION['temp']['id']){$allow=1;}
			}
			if(empty($actors['issue_openby'][$_SESSION['temp']['id']])){
				$allow=1;
			}
		}
		if(date('Y-m-d',time())==$actors['issue_date_created_excel'] and $actors['issue_openby'][$_SESSION['temp']['id']]==$_SESSION['temp']['id']){
			$allow=1;
		}
		
		
	}
	
	
	
	if($role=='admin_assignto_openby'){
		
		if($_SESSION['temp']['role_issue_log_modify']==1){$allow=1;}
		
		//if issue not assign, and session id = openby
		$actors=all_actor_issue($db,$issue_number);
		//show($actors);
		if(!empty($_SESSION['temp']['id'])){
			if(empty($actors['issue_closeby'])){
				if($actors['issue_openby'][$_SESSION['temp']['id']]==$_SESSION['temp']['id']){$allow=1;}
				if($actors['issue_assignto'][$_SESSION['temp']['id']]==$_SESSION['temp']['id']){$allow=1;}
				if($actors['issue_ccto'][$_SESSION['temp']['id']]==$_SESSION['temp']['id']){$allow=1;}
				//
			}
		}
		
		
		
		
		
	}
	
	if($role=='admin_closeby_everyoneloggedin'){
		
		if($_SESSION['temp']['role_issue_log_modify']==1){$allow=1;}
		
		//if issue not closed, everyone logged in can 
		$actors=all_actor_issue($db,$issue_number);
		//show($actors);
		if(!empty($_SESSION['temp']['id'])){
			if(empty($actors['issue_closeby'])){
					if(!empty($_SESSION['temp']['id'])){$allow=1;}
			}
			else
			{
				if($actors['issue_closeby'][$_SESSION['temp']['id']]==$_SESSION['temp']['id']){$allow=1;}
			}
		}
		
		
		
	}
	
	if($role=='delete_log'){
		
		if($_SESSION['temp']['Issue Log Admin']==1){$allow=1;}
		
		
		$actors=all_actor_issue($db,$issue_number);
		
		if(!empty($_SESSION['temp']['id'])){
			if(empty($actors['issue_closeby'])and empty($actors['issue_assignto'])){
				
				if($actors['issue_openby'][$_SESSION['temp']['id']]==$_SESSION['temp']['id']){$allow=1;}
			}
		}
		
		
		
	}


	if($role=='admin_asset'){
		
		if($_SESSION['temp']['role_asset_admin']==1){$allow=1;}
		
	}
	
	
	
	
	
	
	if(!empty($_SESSION['temp']['id']) and $exception==$_SESSION['temp']['id']){$allow=1;}
	
	
	
	
	
	
	if($allow==1){return '';} else {
		
		if($disabled==''){
			return 'readonly';
		}
		else
		{
			return 'disabled';
		}
		
		
	}
	
	
	
	
	
	
	
}

function load_attachment($db,$issue_number){
			$query='SELECT *
	  FROM attachment
	  WHERE attachment_issue_number=\''.$issue_number.'\'
		';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	//
	return $row;
}

function upload_image($db){
	$target_dir = "attachment/";

	if($_FILES['fileToUpload']['type']=='application/pdf'){
		$extension='pdf';
	}else{
		$extension='jpg';
	}

	$new_name=$_POST['issue_number']."-".nextattachment($db,$_POST['issue_number']).".".$extension;


	$target_file = $target_dir .$new_name ;
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	if (file_exists($target_file)) {
	echo "Sorry, file already exists.";
	$uploadOk = 0;
	}
	// Check file size


	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} 
	else 
	{
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			//echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
			
			//add the line in the database
			$attachment_number=nextattachment($db,$_POST['issue_number']);
			$issue_number=$_POST['issue_number'];
			$name=$new_name;
			$added_by=$_SESSION['temp']['id'];
			$tempdate=new datetime(date());
			$date_added = $tempdate->getTimestamp();
			
			$caption=$_POST['attachment_caption'];

			if(empty($caption)){$caption=$_FILES["fileToUpload"]["name"];}
			
			
			
			$query="INSERT INTO dbo.attachment
				( attachment_number,
				attachment_issue_number,
				attachment_name,
				attachment_path,
				attachment_added_by,
				attachment_date_added,
				attachment_caption
				
				) 
				VALUES (
				'$attachment_number',
				'$issue_number',
				'$name',
				'$name',
				'$added_by',
				'$date_added',
				'$caption')";	
				
				
				//show($query);
				
				$sql = $db->prepare($query); 

				$sql->execute();
				
				
				
				
			
		} else {
			echo "Sorry, there was an error uploading your file.". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
		}
	}
		
}


function nextattachment($db,$issue_number){
	$query='SELECT top (1) (attachment_number)
	  FROM attachment
	  Where attachment_issue_number=\''.$issue_number.'\'
	  ORDER BY attachment_number DESC';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
	//
	// $temp=$row[0];
	$row[0]=max($row[0],0);
	$row[0]=$row[0]+1;
	
	
	return $row[0];
}

function delete_image($db,$issue_number,$attachment_number){
	//delete database entry
	
	$query='DELETE FROM dbo.attachment 
		WHERE attachment_issue_number=\''.$issue_number.'\' AND attachment_number=\''.$attachment_number.'\' ';
	$sql = $db->prepare($query); 

	$sql->execute();
	
	
	//delete file
	$target_dir = "attachment/";
	$new_name=$issue_number."-".$attachment_number.".jpg";
	
	$target_file = $target_dir .$new_name ;
	unlink( $target_file);
	$new_name=$issue_number."-".$attachment_number.".pdf";
	$target_file = $target_dir .$new_name ;
	unlink( $target_file);
}




function notify_assign_email($db){
	$cc='';
	if(empty(check_if_group($_POST['issue_assignto']))){
		$details=get_email($db,$_POST['issue_assignto']);
		$address=$details['employee_email'];
		$name=$details['employee_fullname'];
		$firstname=$details['employee_name'];
	}else{
		$i=0;
		foreach(check_if_group($_POST['issue_assignto']) as $member){
			if($i==0){
				$details=get_email($db,$member['groupallocation_employee']);
				$address=$details['employee_email'];
				$name=$details['employee_fullname'];
				$firstname=$details['employee_name'];
			}else{
				$details=get_email($db,$member['groupallocation_employee']);
				$cc=$cc.$details['employee_email'].';';
				//show($cc.' '.$member['groupallocation_employee']);
			}
			$i++;
		}
	}
	$details_open=get_email($db,$_POST['issue_openby']);
	$name_open=$details_open['employee_fullname'];
	
	
	$content='Hi '.$firstname.',<br>A new Issue '.$_POST['issue_number'].' had been raised on '.$_POST['issue_date_created'].' at '.$_POST['issue_time_created'].' by '.$details_open['employee_fullname'];
	if($_POST['issue_product_code']<>''){$content=$content.' about the product '.$_POST['issue_product_code'].' ';}
	$content=$content.' and has been assigned to you.';
	$content=$content.'<br><br> Issue Details: '.$_POST['issue_details'];
	$content=$content.'<br><br> Find more details <a href="http://192.168.1.30/prod-issue-log.php?issue='.$_POST['issue_number'].'" >here</a> ';
	
	$subject='New Production Issue - '.$_POST['issue_number'];
	if($_POST['issue_product_code']<>''){$subject=$subject.' - '.$_POST['issue_product_code'].' ';}
	
	if($_POST['issue_openby']==$_POST['issue_assignto']){
		
	}
	else{
	$details=get_email($db,$_POST['issue_openby']);
	$cc=$cc.$details['employee_email'].';';
	}
	//show($cc);
	if (!empty($_POST['issue_ccto'])){
		$cc=$cc.create_cc_string($db);
	}
	//show($cc);
	send_email($address,$name,$content,$subject,$cc);
	
	
	
	
}

function create_cc_string($db){
	$string_cc_to='';

	foreach ($_POST['issue_ccto'] as &$ccto){
		
		if(empty(check_if_group($ccto))){
			$email=get_email($db,$ccto);
			$string_cc_to=$string_cc_to.$email['employee_email'].';';
			
		}else{
			
			foreach(check_if_group($ccto) as $member){
				$details=get_email($db,$member['groupallocation_employee']);
				$string_cc_to=$string_cc_to.$details['employee_email'].';';
			}
		}
		
	}
	return $string_cc_to;
}
	

function get_email($db,$username){
	$query='SELECT employee_email,employee_fullname,employee_name
	  FROM employee
	  Where employee_code=\''.$username.'\'
	  	  
	';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetch();
	//
	return $row;
}

function update_days_open($db){
		
		
		$query='UPDATE dbo.issue_log SET 
		
		
		issue_nbr_day_open=
		
		floor((
		CASE WHEN \''.time().'\'-issue_date_created < 0 THEN 0 ELSE \''.time().'\'-issue_date_created END
		)/3600/24)
		
		
		WHERE issue_closed<>1';
		
		
		$sql = $db->prepare($query); 
	// show($query);
	$sql->execute();
	$query='UPDATE dbo.issue_log SET 
		
		
		issue_nbr_day_open=floor((issue_close_date-issue_date_created)/3600/24)
		
		
		WHERE issue_closed<>0';
		
		
		$sql = $db->prepare($query); 
	// show($query);
	$sql->execute();
	
	
	
		
}


function listemployee_issue(){
	$db=$GLOBALS['db'];
	
	$sql = $db->prepare("SELECT *,employee_code as id_to_use,employee_fullname as name_to_show FROM dbo.employee  order by employee_fullname ASC"); 
	$sql->execute();
	$allrow=$sql->fetchall();
		
	return $allrow;
		
}
function list_group_issue(){
	$db=$GLOBALS['db'];
	$sql = $db->prepare("SELECT group_name as name_to_show,group_name as id_to_use  FROM dbo.employee_group  order by group_name ASC"); 
	$sql->execute();
	$allrow=$sql->fetchall(); 	
	return $allrow;
}

function check_if_group($group_name){
	$db=$GLOBALS['db'];
	$query="SELECT 
	groupallocation_employee
	,group_name
	FROM [barcode].[dbo].[employee_group_allocation]
	left join employee_group on group_id=groupallocation_groupid  
	Where group_name='$group_name'
	order by groupallocation_leader desc";
	$sql = $db->prepare($query); 
	$sql->execute();
	$allrow=$sql->fetchall(); 	
	//show($query);
	return $allrow;
	
}
function get_all_cc($issue_number){
	$db=$GLOBALS['db'];
	$sql = $db->prepare("SELECT [issue_ccto]
      
	FROM [barcode].[dbo].[issue_log]
	where issue_number='$issue_number'"); 
	
	$sql->execute();
	$cc_string=$sql->fetch(); 	
	
	$allcc=explode(",", $cc_string[0]);
	foreach($allcc as $cc){
		$ccs[$cc]=$cc;
	}
	return $ccs;
}

function get_all_comment($issue_number){
	$db=$GLOBALS['db'];
	$sql = $db->prepare("SELECT *
      
	FROM issue_comment
	where comment_issue_number='$issue_number'
	ORDER BY comment_timetag desc"); 
	
	$sql->execute();
	$all_comment=$sql->fetchall(); 	
	return $all_comment;
}
function get_all_log($issue_number){
	$db=$GLOBALS['db'];
	$sql = $db->prepare("SELECT *
      
	FROM issue_log_log
	where issuelog_issue_number='$issue_number'
	ORDER BY issuelog_timetag desc"); 
	
	$sql->execute();
	$all_log=$sql->fetchall(); 	
	return $all_log;
}

function add_entry_log($issue_number,$memberid,$entry){
	$db=$GLOBALS['db'];
	$sql = $db->prepare("INSERT INTO issue_log_log(issuelog_issue_number,issuelog_timetag,issuelog_member,issuelog_entry)
	VAlUES('$issue_number',".time().",'$memberid','$entry')"); 
	
	$sql->execute();
	
}


?>