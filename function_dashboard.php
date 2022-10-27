<?php




function piechart($data,$title='MyTitle',$option='1:1',$headers="['How Found','Numbers']"){
			echo'<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([';
			echo $headers.",";
			 foreach ($data as &$entry){
			  
			  echo'[\'';
			 if( empty($entry['0'])){echo 'blank';}else{echo $entry['0'];}
			  echo"',   ".$entry['1'].' ],';
		  }
         
		  
		  
		  
        echo"]);

        var options = {
          title: '".$title."',
          is3D: true,
		   legend: 'none',
		  
		".$option.",
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d".$title."'));
        chart.draw(data, options);
      }".'
    </script>';
        
	echo'<div id="piechart_3d'.$title.'" ></div>';
	
	}	
	
function stackedchart($data,$title='MyTitle',$option='',$headers="['Product','Issue']"){
		echo'<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {'."
        var data = google.visualization.arrayToDataTable([";
    
        echo $headers.",";
            foreach ($data as &$entry){
            
            echo'[\'';
            if( empty($entry['0'])){echo 'blank';}else{echo $entry['0'];}
            echo"',   ".$entry['1'].' ],';
        }
    
    
    echo"]);

        var options = {
        title: '".$title."',
        bar: { groupWidth: '75%' },
        isStacked: 'absolute',
        legend: 'none',
        
        ".$option.",
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('stackedchart".$title."'));

    chart.draw(data, options);
    }".'
    </script>';
        
    echo'<div id="stackedchart'.$title.'" ></div>';

}	

function tableview($data,$title='MyTitle',$option='',$headers=[['string','Product'],['number','Issue']],$id=1,$option2){
    echo'<script>/**
    * sends a request to the specified url from a form. this will change the window location.
    * @param {string} path the path to send the post request to
    * @param {object} params the parameters to add to the url
    * @param {string} [method=post] the method to use on the form
    */

    function post(path, params, method=\'post\') {

    // The rest of this code assumes you are not using a library.
    // It can be made less verbose if you use one.
    const form = document.createElement(\'form\');
    form.method = method;
    form.action = path;

    for (const key in params) {
    if (params.hasOwnProperty(key)) {
        const hiddenField = document.createElement(\'input\');
        hiddenField.type = \'hidden\';
        hiddenField.name = key;
        hiddenField.value = params[key];

        form.appendChild(hiddenField);
    }
    }

    document.body.appendChild(form);
    form.submit();
    }
    </script>';
	//show($headers);
    echo'<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load("current", {packages:["table"]});
    google.charts.setOnLoadCallback(drawTable);
    
    function drawTable() {'."
        var data = new google.visualization.DataTable();";
        if($title<>'Summary'){echo" data.addColumn('boolean', 'Show');";}
        foreach ($headers as &$header){
            echo" data.addColumn('".$header[0]."', '".$header[1]."');";
            
        }

        
    
        echo" data.addRows([";
        foreach ($data as &$entry){
            
            echo'[';
            if($title<>'Summary'){
                if(empty( $_SESSION['temp'][$option2])){echo'true,';}else{
                    $render='false,';
                    foreach($_SESSION['temp'][$option2] as $keyword){
                        if($keyword=='blank,'){$keyword='';}
                        if($keyword==$entry['0']){$render='true,';}
                    }
                    echo $render;
                }
                
            }
            
            
            
            echo'\'';
            if( empty($entry['0'])){echo 'blank';}else{echo $entry['0'];}
            echo"',   ".$entry['1'].' ],';
        }
        
        
        
        
        echo"]);

        var table".$id." = new google.visualization.Table(document.getElementById('tableview".$title."'));

        table".$id.".draw(data, { width: '100%',$option});
        google.visualization.events.addListener(table".$id.", 'select', function() {
            var row = table".$id.".getSelection()[0].row;
            post('', {".$option2.":  data.getValue(row, 1),show_dashboard:  'show_dashboard'});
            //alert('You selected ' + data.getValue(row, 1));
          });
    }"."
   


    </script>";
        
    echo'<div id="tableview'.$title.'" ></div>';

}

function tableviewnormal($data,$title='MyTitle',$option='',$headers=[['string','Product'],['number','Issue']],$id,$option2){
    
    echo'<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load("current", {packages:["table"]});
    google.charts.setOnLoadCallback(drawTable);
    
    function drawTable() {'."
        var data = new google.visualization.DataTable();";
       
        foreach ($headers as &$header){
            echo" data.addColumn('".$header[0]."', '".$header[1]."');";
            
        }

        
    
        echo" data.addRows([";
        foreach ($data as &$entry){
            
            echo"['";
            echo $entry['0'];
            echo"',   ".$entry['1'].' ],';
        }
        
        
        
        
        echo"]);

        var table".$id." = new google.visualization.Table(document.getElementById('tableview".$title."'));

        table".$id.".draw(data, { width: '100%',$option});
        
    }"."
   


    </script>";
        
    echo'<div id="tableview'.$title.'" ></div>';

}

function tableview_summary($data,$title='MyTitle',$option='',$headers=[['string','Product'],['number','Issue']],$id=1,$option2){
    echo'<script>/**
    * sends a request to the specified url from a form. this will change the window location.
    * @param {string} path the path to send the post request to
    * @param {object} params the parameters to add to the url
    * @param {string} [method=post] the method to use on the form
    */

    function post(path, params, method=\'post\') {

    // The rest of this code assumes you are not using a library.
    // It can be made less verbose if you use one.
    const form = document.createElement(\'form\');
    form.method = method;
    form.action = path;

    for (const key in params) {
    if (params.hasOwnProperty(key)) {
        const hiddenField = document.createElement(\'input\');
        hiddenField.type = \'hidden\';
        hiddenField.name = key;
        hiddenField.value = params[key];

        form.appendChild(hiddenField);
    }
    }

    document.body.appendChild(form);
    form.submit();
    }
    </script>';
	//show($headers);
    echo'<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load("current", {packages:["table"]});
    google.charts.setOnLoadCallback(drawTable);
    
    function drawTable() {'."
        var data = new google.visualization.DataTable();";
        
        foreach ($headers as &$header){
            echo" data.addColumn(".$header[0].", ".$header[1].");";
           // echo"  data.addColumn({type: 'string', role: 'annotation', p: {html: true}}); ";
            
        }

        
        
        echo" data.addRows([";
        //echo"['All',0],";
        foreach ($data as &$entry){
            
            echo"['";
            if($id==1){
                if(date('Yn',strtotime('1-'.$entry['0']))==$_SESSION['temp']['summary']['yearmonth']){echo'<b><big>';}
                echo date('M Y',strtotime('1-'.$entry['0']));
                if(date('Yn',strtotime('1-'.$entry['0']))==$_SESSION['temp']['summary']['yearmonth']){echo'</big></b>';}
            }elseif($id==2){
                if($entry['0']==$_SESSION['temp']['summary']['days']){echo'<b><big>';}
                echo $entry['0'];
                if($entry['0']==$_SESSION['temp']['summary']['days']){echo'</big></b>';}
            }elseif($id==3){
                if($entry['0']==$_SESSION['temp']['summary']['operatorname']){echo'<b><big>';}
                    if(empty($entry['0'])){echo'blank';}else{echo $entry['0'];}
                if($entry['0']==$_SESSION['temp']['summary']['operatorname']){echo'</big></b>';}
            }elseif($id==4){
                if($entry['0']==$_SESSION['temp']['summary']['workarea']){echo'<b><big>';}
                    if(empty($entry['0'])){echo'blank';}else{echo $entry['0'];}
                if($entry['0']==$_SESSION['temp']['summary']['workarea']){echo'</big></b>';}
            }else{
                echo $entry['0'];
            }
            
            echo"',   ".round($entry['2']/3600,1).'  ],';
        }
        
        
        if($id==1){
            //month filter
            $post_string="monthyear:  data.getValue(row, 0)";
        }else{
            $post_string=$option2.":  data.getValue(row, 0)";
        }
        
        echo"]);

        var table".$id." = new google.visualization.Table(document.getElementById('tableview".$title."'));

        table".$id.".draw(data, { allowHtml:true,width: '100%',$option});
        google.visualization.events.addListener(table".$id.", 'select', function() {
            var row = table".$id.".getSelection()[0].row;
            post('', {".$post_string."});
            //alert('You selected ' + data.getValue(row, 0));
          });
    }"."
   


    </script>";
        
    echo'<div id="tableview'.$title.'" ></div>';

}

function graphview($data,$title='MyTitle',$option='',$headers=[['number','X'],['number','Result']]){
	//show($headers);
    echo'<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load("current", {packages:["table"]});
    google.charts.setOnLoadCallback(drawTable);
    function drawTable() {'."
        var data = new google.visualization.DataTable();";
        
        foreach ($headers as &$header){
            echo" data.addColumn('".$header[0]."', '".$header[1]."');";
            
        }
        echo"  data.addColumn({type: 'string', role: 'annotation', p: {html: true}}); ";
        echo"  data.addColumn({type: 'string', role: 'tooltip', p: {html: true}}); ";
        
    
        echo" data.addRows([";
        $i=0;
        foreach ($data as &$entry){
            
            echo'['.$i.",   ".$entry['0']." ,";
            if($entry['5']=='Fail'){echo'\'Fail\'';}
            echo",createCustomHTMLContent( '".date("jS M y",strtotime($entry['1']))."', '".date("G:i",$entry['2'])."', '".$entry['3']."', '".$entry['4']."', '".$entry['5']."', '".$entry['6']."', '".$entry['7']."', '".round($entry['0'],2)."', '".$entry['8']."', '".$entry['9']."')  ],";
            $i++;
        }
        
        
        
        
        echo"]);

        var options = {
           
            hAxis: {
              title: 'Test'
            },
            vAxis: {
              title: 'Result'
            },
            vAxis: {
                minValue:0,
                viewWindow: {
                    min: 0
                }
            },
            height: 300,
            tooltip: { isHtml: true }
          };
      
          var chart = new google.visualization.LineChart(document.getElementById('chart_div".$title."'));
          chart.draw(data, options);
        
    }

    function createCustomHTMLContent( test_date, test_time, tested_by, Product, Pass, MISNumber, Test_name, Result, Unit,Test_id) {
        return '<div style=\"width:200px; padding:5px 5px 5px 5px;\">' +
            
            '<table >' + '<tr>' +
            '<td> ' + Test_id +'</td>' + '</tr>' + '<tr>' +
            '<td> ' + test_date +' ' + test_time + '</td>' + '</tr>' + '<tr>' +
            
            '<td> ' + Product + '</td>' + '</tr>' + '<tr>' +
            '<td> ' + MISNumber + '</td>' + '</tr>' + '<tr>' +
            '<td> ' + Test_name + '</td>' + '</tr>' + '<tr>' +
            '<td>Tested by ' + tested_by + '</td>' + '</tr>' + '<tr>' +
            '<td><b>Result: ' + Result + ' ' + Unit + '</b></td>' + '</tr>' + '<tr>' +
            
            '<td><b>Pass: ' + Pass + '</b></td>' + '</tr>' + '<tr>' +
            '</table>' + '</div>';
      }
   

    
    </script>";
        
    echo'<div id="chart_div'.$title.'" ></div>';

}

function gethowfound($db){
    $filter=do_the_filter($db);
	$query='SELECT issue_how,count(distinct (issue_number))as Count_issue FROM dbo.issue_log  
	
	 '.$filter.'  
	GROUP BY issue_how
	order by Count_issue desc
	';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	// show($query);
	// show($row);
	return $row;
}

function getrootcause($db){
    $filter=do_the_filter($db);
	$query='SELECT issue_root_cause,count(distinct (issue_number))as Count_issue FROM dbo.issue_log  
	 '.$filter.'  and issue_closed<>0
	 
	GROUP BY issue_root_cause
	order by Count_issue desc
	';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	// show($query);
	// show($row);
	return $row;
}

function getproductlist($db){
	$filter=do_the_filter($db);
    $query='SELECT TOP 10 issue_product_code,count(distinct (issue_number))as Count_issue FROM dbo.issue_log  
	 '.$filter.'  
	
	GROUP BY issue_product_code
	order by Count_issue desc
	';
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	// show($query);
	// show($row);
	return $row;
}

function getMonthview($db){
	$filter=do_the_filter($db);
    $query="SELECT Concat(MONTH(issue_date_created_excel),' - ',YEAR(issue_date_created_excel)),count(distinct (issue_number))as Count_issue FROM dbo.issue_log  
	
 ".$filter."  
	
	GROUP BY Concat(MONTH(issue_date_created_excel),' - ',YEAR(issue_date_created_excel))
	
	
	
	";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	// show($query);
	// show($row);
	return $row;
}

function getallMonth($db){
	$filter=do_the_filter($db);
    $query="SELECT MONTH(issue_date_created_excel) FROM dbo.issue_log  
	".$filter." 
	
	GROUP BY MONTH(issue_date_created_excel)
	
	
	order by MONTH(issue_date_created_excel) ASC
	";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	// show($query);
	// show($row);
	return $row;
}

function getallYear($db){
	$filter=do_the_filter($db);
    $query="SELECT YEAR(issue_date_created_excel) FROM dbo.issue_log  
	'.$filter.' 
	
	GROUP BY YEAR(issue_date_created_excel)
	
	
	order by YEAR(issue_date_created_excel) ASC
	";
	
	$sql = $db->prepare($query); 
	//show($query);
	$sql->execute();

	$row=$sql->fetchall();
	// show($query);
	// show($row);
	return $row;
}

function tableviewfilter($data,$title='MyTitle'){
		echo'<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load("current", {packages:["table"]});
    google.charts.setOnLoadCallback(drawTable);
    function drawTable() {'."
        var data = new google.visualization.DataTable();
        data.addColumn('string', '".$title."');
        
    
        data.addRows([";
        foreach ($data as &$entry){
            
            echo'[\'';
            if( empty($entry['0'])){echo '0';}else{echo $entry['0'];}
            echo"'],";
        }
        
        
        
        
        echo"]);

        var table = new google.visualization.Table(document.getElementById('tableview".$title."'));

        table.draw(data, { width: '100%', height: '100%'});
    }".'
    </script>';
        
    echo'<div id="tableview'.$title.'" ></div>';

}	

function getallinfos($db){
	$filter=do_the_filter($db);
    $query="SELECT count(distinct (issue_number))as Count_issue FROM dbo.issue_log  
	".$filter." AND issue_closed=0
	";
	//show($query);
	$sql = $db->prepare($query); 
	$sql->execute();
	$row=$sql->fetch();	
	$info['still_open']=$row[0];
	
	$query="SELECT count(distinct (issue_number))as Count_issue  FROM dbo.issue_log  ".$filter."
	";
	
	$sql = $db->prepare($query); 
	$sql->execute();
	$row=$sql->fetch();	
	$info['total']=$row[0];
	
		$query="SELECT count(distinct (issue_number))as Count_issue  FROM dbo.issue_log  ".$filter."
		WHERE issue_IAF<>0
	";
	
	$sql = $db->prepare($query); 
	$sql->execute();
	$row=$sql->fetch();	
	$info['IAF']=$row[0];
	
	$query="SELECT AVG((issue_nbr_day_open*10)) as Avg_day,
	Min((issue_nbr_day_open))as Min_day_open,
	Max((issue_nbr_day_open))as Max_day_open  FROM dbo.issue_log   ".$filter."
		
	";
	
	$sql = $db->prepare($query); 
	$sql->execute();
	$row=$sql->fetch();	
	$info['Avg_day_open']=$row[0]/10;
	$info['Min_day_open']=$row[1];
	$info['Max_day_open']=$row[2];
	//show ($info);
	return $info;
}


function showDashboard($db){
	
    navbar_dashboard($db);
    echo'<div class="row ">';
        echo'<div class="col-sm-8 ">';
            echo'<div class="col-sm-3 ">';
            echo'<h4>Top 10 Product :</h4>';
            $data=getproductlist($db);
           // show ($data);
            tableviewnormal($data,$title='Product List',$option="titlePosition:'out',orientation:'vertical',height: 300",[['string','Product'],['number','Issue']],1,'');
            echo'</div>';
            echo'<div class="col-sm-9 ">';
                echo'<div class="col-sm-6 ">';
                $data=gethowfound($db);
                
                piechart($data,$title='How Found',"theme:'maximized',pieSliceText: 'label',pieSliceTextStyle:{fontSize: 8},height: 250");
                echo'</div>';
                echo'<div class="col-sm-6 ">';
                $data=getrootcause($db);
                
                piechart($data,$title='Root Cause',"theme:'maximized',pieSliceText: 'label',height: 250");
                echo'</div>';
                echo'<div class="col-sm-12 ">';
                $data=getMonthview($db);
                
                stackedchart($data,$title='Trend',' height: 200');
                echo'</div>';
            echo'</div>';
        echo'</div>';
        echo'<div class="col-sm-4 ">';
            echo'<div class="row ">';
                echo'<div class="col-sm-6 ">';
                
                $data=getallYear($db);
                //tableviewfilter($data,$title='Year');
                echo'</div>';
                echo'<div class="col-sm-6 ">';
                
                $data=getallMonth($db);
                
                //tableviewfilter($data,$title='Month');
                echo'</div>';
            echo'</div>';
            echo'<div class="row ">';
                echo'<div class="col-sm-6 ">';
                // gauge("Issues",39,$valuemini=0,$valuemax=100);
                echo'<br>';
                $info=getallinfos($db);
                $data=array();
                $data[0][]=$info['total'];
                tableviewfilter($data,$title='Total Nº Entries');
                $data=array();
                $data[0][]=$info['still_open'];
                tableviewfilter($data,$title='Nº Outstanding');
                $data=array();
                $data[0][]=$info['IAF'];
                tableviewfilter($data,$title='Total Nº IAF Raised');
                echo'<br>';
                $data=array();
                
                $data[0][]=$info['Avg_day_open'];
                tableviewfilter($data,$title='Avg Days Open');
                $data=array();
                $data[0][]=$info['Min_day_open'];
                tableviewfilter($data,$title='Min Days Open');
                $data=array();
                $data[0][]=$info['Max_day_open'];
                tableviewfilter($data,$title='Max Days Open');
                
                echo'</div>';
            echo'</div>';
        echo'</div>';
    echo'</div>';

}

function navbar_dashboard($db){
	

    echo'<div class="row">';
        
		echo'<div class="col-sm-1 ">';
            echo'<form method="POST">';
            $listmember=list_assign_to($db);
            echo '<br><select name="issue_assignto" class="form-control" id="issue_assignto" onChange="submit();">';
                echo'<option value="">All</option>';
                
                //show($listmember);
                foreach($listmember as $member){
                    echo'<option value="'.$member['issue_assignto'].'" ';
                    if($_SESSION['temp']['issue_assignto']==$member['issue_assignto']){echo' selected ';}
                    echo'>'.$member['issue_assignto'].' ('.$member['count_issue'].')</option>';
                }
               
               

            echo'</select>';
            echo'<input class="form-control" type="hidden"   name="type" value="filter_issue_assignto">';
            echo'</form>';
			
		echo'</div>';
		
        echo'<div class="col-sm-1 " onclick="document.getElementById(\'remove-member\').submit();">';
			echo'<form id="remove-member" method="POST">';
            if(!empty($_SESSION['temp']['issue_assignto'])){
            
            echo' <br>
            <small><small><small>Assign To: <br>'.$_SESSION['temp']['issue_assignto'].' </small></small></small><span class="glyphicon glyphicon-remove" > </span>
                                <input class="form-control" type="hidden"   name="type" value="filter_issue_assignto">
                                ';
            }
			echo'</form>';
		echo'</div>'; 
		echo'<div class="col-sm-1 ">';
			echo'<form method="POST">';
			
			echo '<br><input class="form-control" type="text"  onEnter="submit()" name="search_word" placeholder="search">';
            echo'<input class="form-control" type="hidden"   name="type" value="search">';
			echo'</form>';
		echo'</div>';
		echo'<div class="col-sm-1 ">';
            echo'<form method="POST">';
            if(!empty($_SESSION['temp']['search'])){
            
            echo'<br><button type="submit" name="type" value="search"  class="btn btn-default" >
            '.$_SESSION['temp']['search'].' <span class="glyphicon glyphicon-remove" > </span>
                                <input class="form-control" type="hidden"   name="search_word" value="">
                                </button><br>';
            }
            echo'</form>';
		echo'</div>';
		echo'<div class="col-sm-2 ">';
			
		
		
		
		echo'</div>';
		echo'<div class="col-sm-2 ">';
			
			
			if($_SESSION['temp']['show_closed']==true){
				
                echo'<form method="POST">';
                echo'<br><button type="submit" name="type" value="show_closed"  class="btn btn-default" >
                Only Active
				</button><br>';
                echo'</form>';
			}
			else{
				echo'<form method="POST">';
                echo'<br><button type="submit" name="show_closed" value=""  class="btn btn-default" >Only Active: On
                    <span class="glyphicon glyphicon-remove" > </span>
                                        <input class="form-control" type="hidden"   name="type" value="show_closed">
                                        </button><br>';
                                        echo'</form>';
			}
			
			
			
		echo'</div>';
        echo'<div class="col-sm-2 ">';
			if($_SESSION['temp']['show_all']==true){
					
				echo'<form method="POST">';
				echo'<br><button type="submit" name="type" value="show_all"  class="btn btn-default" >
				Only Yours
				</button><br>';
				echo'</form>';
			}
			else{
				echo'<form method="POST">';
				echo'<br><button type="submit" name="show_all" value=""  class="btn btn-default" >Only yours: On
					<span class="glyphicon glyphicon-remove" > </span>
										<input class="form-control" type="hidden"   name="type" value="show_all">
										</button><br>';
										echo'</form>';
			}
            
		echo'</div>';


		
		
		
		
			echo'<div class="col-sm-2 ">';
                echo'<form method="POST">';
                echo'<br><button type="submit" name="type" value="return-2" class="btn btn-default" >
									<span class="glyphicon glyphicon-arrow-up" > Hide Dashboard</span>
									</button><br>';
				echo'</form>';
			echo'</div>';
		
		echo'</div>';
}


function do_the_filter($db){
	if(empty($_SESSION['temp']['show_all'])){
		$id=$_SESSION['temp']['id'];
		$myfilter="AND (issue_openby='$id' OR issue_assignto='$id' OR issue_closeby='$id' OR issue_ccto like '%$id%' ) ";

		
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
    $filter='WHERE 1=1 '.$myfilter.' '.$filterclose.' '.$searchfilter.'';
    return $filter;
}

function list_assign_to($db){
    $filter=do_the_filter($db);
    $query="SELECT issue_assignto, Count(issue_number) as count_issue FROM dbo.issue_log Left JOIn dbo.employee on employee_code=issue_assignto
	".$filter." AND issue_closed=0 
    GROUP BY issue_assignto
	";
	//show($query);
	$sql = $db->prepare($query); 
	$sql->execute();
	$row=$sql->fetchall();	
	return $row;
}
?>