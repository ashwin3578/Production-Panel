<?php

/* to Add : 
	Add view on barcode database with the table MIS from vision and table Jobs from database – to link the product code to all jobs

	Add a page showing all scans with no operator or with no Jobs/Product Code associated

	Flag hours out of range

	Deal with midnight, if the scan is before 4am check if open day before after 8pm if yes then add a scan at 23:59 and 00:01



/*

	****** Database ******

	Table list

		Operator 
			Name
			Surname
			Code
			Area
			
		Employee
			Name
			Surname
			Password
			date_lastconnection
			
	/	IndividualScan
	/		Scanid
	/		OperatorCode
	/		MIS Number
	/		Timetag
	/		Date
	/		machineID
	/		EmployeeID
	
	
			
		Scan 
			OperatorCode
			MIS Number
			Date
			Timetag_Start
			Timetag_Finished
			TimeRaw
			TimeTotal (distributed)
			Ratio (0-100%)
			Status (Start/Finished)
			Process_started
			Still_Open
			
		
		Modification
			oldOperatorCode
			oldMIS Number
			oldTimetag
			oldDate
			oldmachineID
			oldEmployeeID
			newOperatorCode
			newMIS Number
			newTimetag
			newDate
			machineID
			Datemodification
			EmployeeID

*/


/*
	****** Scanning Procedure ****** (barcode scanner do only that one)

	each day is completely independant

	//function check_scan_exist
	check there are no scan with the same timetag if so remove 1secondes to newtimetag and rescan

	//function save_scan_temp
	save all scan where timetag>=timetag_newscan in temp Timetag table

	//find last scan and calculate raw time and number of process opened
	check the last scan to find how time had spend since-> raw time 
	and how many processes are opened
	
	
	
	//function is_job_open
	check if that job is already open, if not status =start if yes we calculated total time.
	

	

	//function calculate_distributed_time
	raw time = time since last scan
	distributedtime=rawtime / nbr_of_processes

	//function add_distributed_time
	for each process still opened add distributed_time to the current one
	total_distributed = total_distributed + time_distributed

	if scan_finished
	//function calculate_ratio
	ratio=total_distributed / total_raw_time
	
	update starting scan with time finished


	******Rescanning procedure******  ( barcode management methode, all scan are rescan for the day)

	remove/modify the scan timetag
	move all the scan for that operator and that day in temp_scan
		for each scan in temp scan sort by date ASC
			redo the scanning procedure

	******Changing Process only******
		Change the scan MIS dont rescan anything
*/




/*
	******admin page*****
	list of operator page
		add/edit/remove operator
		view name/code/number_of_scan

	list of employee page
		add/edit/remove employee
		

	******Barcode Management******
	
	List of the Scan (top 200 only)
		Filter by Operator / by day / by Area
	Remove Scan
	Modify Scan
	Modify MIS
	Add Scan
	










*/





?>