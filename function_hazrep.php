<?php


class HazRep {
    
    /** Get all Hazard Report 
     * @param string $limit default "TOP 50" to get only the top 50 row
     * 
     */
    public static function get_all($limit="TOP 50"){     
        $db=$GLOBALS['db'];
        $addon="";
        if($_SESSION['temp']['hazrep']['show_closed_one']=='no' or empty($_SESSION['temp']['hazrep']['show_closed_one'])){
            $addon="and hazrep_status<>'Closed'";
        }
        if(!empty($_SESSION['temp']['hazrep']['search'])){
            $keyword=$_SESSION['temp']['hazrep']['search'];
            $fields=[
                'hazrep_priority',
                'hazrephowfound_name',
                'hazreplocation_name',
                'hazrep_description',
                'hazrep_who_was_notified',
                'hazrep_immediate_corrective_action',
                'hazrep_status',
                'hazrep_closeby',
                'hazrep_date_closed',
                'hazrep_submittedby',
                'hazrep_date_submitted',
                'hazreptype_name',
                'hazrepcategory_name',
                'hazrep_likelyhood_score',
                'hazrep_consequence_score',
                'hazrep_action_taken',
                'hazrep_verification',
                'hazrep_SIMPL',
                'hazrep_FIIX',
                'hazrep_other_ref',
                'hazrep_assigned_to',
                'hazrep_date_email_assigned_to',
                'hazrep_member_email_assigned_to'
            ];
            $addon=$addon."and ( 0=1";
            foreach($fields as $field){
                $addon=$addon."or $field like '%$keyword%'";
            }
            $addon=$addon." )";
        }
        
        $query="SELECT $limit *
        FROM hazrep 
        left join hazrep_priority on hazrep_priority=hazreppriority_id
        left join hazrep_howfound on hazrep_howfound=hazrephowfound_id
        left join hazrep_location on hazrep_location=hazreplocation_id
        left join hazrep_type on hazrep_type=hazreptype_id
        left join hazrep_category on hazrep_category=hazrepcategory_id
        left join hazrep_likelyhoodscore on hazrep_likelyhood_score=hazreplikelyhoodscore_id
        left join hazrep_consequencescore on hazrep_consequence_score=hazrepconsequencescore_id
        WHERE 1=1 $addon
        order by (hazrep_likelyhood_score*hazrep_consequence_score) DESC,hazrep_date desc";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        
        $hazreps=$sql->fetchall();
        return $hazreps;
    }
    /** Get one Hazard Report 
     * 
     */
    public static function get_one($hazrep_id){     
        $db=$GLOBALS['db'];
        if(!empty($hazrep_id)){
            $query="SELECT *
            FROM hazrep
            left join hazrep_priority on hazrep_priority=hazreppriority_id
            left join hazrep_howfound on hazrep_howfound=hazrephowfound_id
            left join hazrep_location on hazrep_location=hazreplocation_id
            left join hazrep_type on hazrep_type=hazreptype_id
            left join hazrep_category on hazrep_category=hazrepcategory_id
            left join hazrep_likelyhoodscore on hazrep_likelyhood_score=hazreplikelyhoodscore_id
            left join hazrep_consequencescore on hazrep_consequence_score=hazrepconsequencescore_id
            where hazrep_id=$hazrep_id";
            $sql = $db->prepare($query); 
            $sql->execute();
            //show($query);
            $hazreps=$sql->fetch();
        }
       
        return $hazreps;
    }
    /** Create a new Hazard Report 
     * 
     */
    public static function create_new(){     
        $db=$GLOBALS['db'];
        
        $new_number=HazRep::get_the_next_hazrep_number();
        $hazrep_openby=$_SESSION['temp']['id'];
        $hazrep_date=date('Y-m-d G:i');
        $hazrep_priority=3;
        $hazrep_status='Created';
        $query="INSERT INTO hazrep(
            hazrep_number,
            hazrep_openby,
            hazrep_date,
            hazrep_priority,
            hazrep_status
            ) values(
                '$new_number',
                '$hazrep_openby',
                '$hazrep_date',
                '$hazrep_priority',
                '$hazrep_status'
                )";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        $a=1;
        $query="SELECT * from hazrep where hazrep_number='$new_number'";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        
        $hazreps=$sql->fetch();
        $log_entry="Report Created";
        HazRep_Log::create_new($hazreps['hazrep_id'],$log_entry);
        return $hazreps;
    }
    /** Update an Hazard Report 
     * 
     */
    public static function update(){     
        $db=$GLOBALS['db'];
        $hazrep_id=$_POST['hazrep_id'];
        $oldhazrep=HazRep::get_one($hazrep_id);
        
        $query="UPDATE hazrep
            SET ";

        $columns=array();
        $fields=[
            'hazrep_priority',
            'hazrep_howfound',
            'hazrep_location',
            'hazrep_description',
            'hazrep_who_was_notified',
            'hazrep_immediate_corrective_action',
            'hazrep_status',
            'hazrep_closeby',
            'hazrep_date_closed',
            'hazrep_submittedby',
            'hazrep_date_submitted',
            'hazrep_type',
            'hazrep_category',
            'hazrep_likelyhood_score',
            'hazrep_consequence_score',
            'hazrep_action_taken',
            'hazrep_verification',
            'hazrep_SIMPL',
            'hazrep_FIIX',
            'hazrep_other_ref',
            'hazrep_assigned_to',
            'hazrep_date_email_assigned_to',
            'hazrep_member_email_assigned_to'
        ];
        $count=0;
        foreach($fields as $field){
            if(isset($_POST[$field]) and $oldhazrep[$field]<>$_POST[$field]){
                array_push($columns,['name'=>$field,'value'=>$_POST[$field]]);
                $count++;
                $log_entries[]="Report Updated : $field -> ".$_POST[$field];
            }   
            
        }
        if(isset($_POST['hazrep_date']) and strtotime($oldhazrep['hazrep_date'])<>strtotime($_POST['hazrep_date'].' '.$_POST['hazrep_time'])){
            array_push($columns,['name'=>'hazrep_date','value'=>$_POST['hazrep_date'].' '.$_POST['hazrep_time']]);
            $count++;
            $log_entries[]="Report Updated : $field -> ".$_POST[$field];
        }
        
        
        foreach($columns as $column){
            $query=$query."
            ".$column['name']."= '".$column['value']."',";
        }
        //show($columns);        
        //remove the last coma
        $query=substr($query, 0, -1);
        $query=$query."
        WHERE hazrep_id=$hazrep_id";
        $sql = $db->prepare($query); 
        //show($query);
        if($count>0){
            $sql->execute(); 
            foreach($log_entries as $log_entry){
                HazRep_Log::create_new($hazrep_id,$log_entry);
            }
            
        }
        
        
        
    }
    /** Delete a new Hazard Report 
     * 
     */
    public static function delete($hazrep_id){     
        $db=$GLOBALS['db'];
        $query="DELETE FROM hazrep WHERE hazrep_id=$hazrep_id";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();

        foreach(HazRep_Log::get_all($hazrep_id) as $log){
            HazRep_Log::delete($log['log_id']);
        } 
        foreach(HazRep_Comment::get_all($hazrep_id) as $comment){
            HazRep_Comment::delete($comment['comment_id']);
        }   
        foreach(HazRep_Attachment::get_all($hazrep_id) as $attachment){
            HazRep_Attachment::delete($attachment['attachment_id']);
        }       
        
        return ;
    }



    /** Get all Hazard Priority 
     * 
     */
    public static function get_all_priority(){     
        $db=$GLOBALS['db'];
        $query="SELECT *
        FROM hazrep_priority         
        order by hazreppriority_id dESC";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();        
        $priorities=$sql->fetchall();
        return $priorities;
    }
    /** Get all Hazard How Found
     * 
     */
    public static function get_all_howfound(){     
        $db=$GLOBALS['db'];
        $query="SELECT *
        FROM hazrep_howfound         
        order by hazrephowfound_name ASC";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();        
        $howfounds=$sql->fetchall();
        return $howfounds;
    }
    /** Get all Hazard Location
     * 
     */
    public static function get_all_location(){     
        $db=$GLOBALS['db'];
        $query="SELECT *
        FROM hazrep_location         
        order by hazreplocation_name ASC";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();        
        $locations=$sql->fetchall();
        return $locations;
    }
    /** Get all Hazard Type
     * 
     */
    public static function get_all_type(){     
        $db=$GLOBALS['db'];
        $query="SELECT *
        FROM hazrep_type         
        order by hazreptype_name ASC";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();        
        $types=$sql->fetchall();
        return $types;
    }
    /** Get all Hazard Category
     * 
     */
    public static function get_all_category(){     
        $db=$GLOBALS['db'];
        $query="SELECT *
        FROM hazrep_category         
        order by hazrepcategory_name ASC";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();        
        $categories=$sql->fetchall();
        return $categories;
    }
    /** Get all Hazard Likelyhood Score
     * 
     */
    public static function get_all_likelyhoodscore(){     
        $db=$GLOBALS['db'];
        $query="SELECT *
        FROM hazrep_likelyhoodscore         
        order by hazreplikelyhoodscore_id ASC";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();        
        $likelyhoodscores=$sql->fetchall();
        return $likelyhoodscores;
    }
    /** Get all Hazard Consequence Score
     * 
     */
    public static function get_all_consequencescore(){     
        $db=$GLOBALS['db'];
        $query="SELECT *
        FROM hazrep_consequencescore         
        order by hazrepconsequencescore_id ASC";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();        
        $consequencescores=$sql->fetchall();
        return $consequencescores;
    }
    /** Get the Hazard Report Number
     * 
     */
    public static function get_the_next_hazrep_number(){     
        $db=$GLOBALS['db'];
        $curent_year=date('Y');
        $query="SELECT top (1) (hazrep_number)
        FROM hazrep       
        WHERE year(hazrep_date)= $curent_year 
        order by hazrep_number DESC";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();        
        $last_hazrep=$sql->fetch();
       
        $last_hazrep[0]=substr ( $last_hazrep[0] , -3 );
	    $last_hazrep[0]=(int)$last_hazrep[0];

        $next_number=$last_hazrep[0];
        $next_number=sprintf("%04d", $next_number+1);
        $hazrep_number='HR-'.date('y').'-'.$next_number;
        return $hazrep_number;
    }


    /** Send the email to nofity the person who has been assigned
     * @param Hazrep $hazrep array of the Hazrep
     */
    public static function send_email_assigned_to($hazrep){
        $assigned_to=$hazrep['hazrep_assigned_to'];
        $assigned_by=$_SESSION['temp']['id'];
        $hazrep_number=$hazrep['hazrep_number'];
        $hazrep_description=nl2br($hazrep['hazrep_description']);
        $location_name=$hazrep['hazreplocation_name'];
        $hazrep_id=$hazrep['hazrep_id'];
        $priority_name=$hazrep['hazreppriority_name'];
        
        $content="Hi $assigned_to,<br>";
        $content=$content."<br>";
        $content=$content."A new Hazard report has been assigned to you by $assigned_by, <br>";
        $content=$content."Report: $hazrep_number<br>";
        $content=$content."Location: $location_name<br>";
        $content=$content."Description: $hazrep_description<br>";
        $content=$content."<br>";
        $content=$content."See more details <a href=\"http://192.168.1.30/hazrep.php?id=$hazrep_id\">here</a><br>";


        $subject="[$priority_name] - New Hazard Report Assignment - $hazrep_number";
        $address=Employee::get_one('employee_fullname',$assigned_to)['employee_email'];
        $cc=Employee::get_one('employee_code',$_SESSION['temp']['id'])['employee_email'];
        



        //for testing only
        // $address='corentin@sicame.com.au';
        // $cc='';
        // show($assigned_to.":".$address);
        // show($cc);
        // show($subject);
        // show($content);        
        //send_email($address,$assigned_to,$content,$subject,$cc);
    }
    /** Send the email to nofity the Health and Safety Group that a report has been submitted
     * @param Hazrep $hazrep array of the Hazrep
     */
    public static function send_email_hazrep_submitted($hazrep){
        $created_by=$_SESSION['temp']['id'];
        $hazrep_number=$hazrep['hazrep_number'];
        $hazrep_description=nl2br($hazrep['hazrep_description']);
        $location_name=$hazrep['hazreplocation_name'];
        $hazrep_id=$hazrep['hazrep_id'];
        $priority_name=$hazrep['hazreppriority_name'];
        
        $content="Hi all,<br>";
        $content=$content."<br>";
        $content=$content."A new Hazard report has been submitted by $created_by, <br>";
        $content=$content."Report: $hazrep_number<br>";
        $content=$content."Location: $location_name<br>";
        $content=$content."Description: $hazrep_description<br>";
        $content=$content."<br>";
        $content=$content."See more details <a href=\"http://192.168.1.30/hazrep.php?id=$hazrep_id\">here</a><br>";


        $subject="[$priority_name] - New Hazard Report Assignment - $hazrep_number";
        $address='production-assistant@sicame.com.au';
        $cc='';
        foreach(Employee::get_all_from_group('Health & Safety') as $employee){
            $cc=$cc.$employee['employee_email'].';';
        }


        //for testing only
        // show($cc);
        // $address='corentin@sicame.com.au';
        // $cc='';
        //show($assigned_to.":".$address);
        // show($cc);
        // show($subject);
        // show($content);        
        //send_email($address,'Production Assistant',$content,$subject,$cc);
    }


    /** Get all Hazard Priority 
     * 
     */
    public static function get_all_to_be_checked(){     
        $db=$GLOBALS['db'];
        $member=$_SESSION['temp']['id'];
        $query="SELECT *
        FROM hazrep  
        left join hazrep_priority on hazrep_priority=hazreppriority_id
        left join hazrep_howfound on hazrep_howfound=hazrephowfound_id
        left join hazrep_location on hazrep_location=hazreplocation_id
        left join hazrep_type on hazrep_type=hazreptype_id
        left join hazrep_category on hazrep_category=hazrepcategory_id
        left join hazrep_likelyhoodscore on hazrep_likelyhood_score=hazreplikelyhoodscore_id
        left join hazrep_consequencescore on hazrep_consequence_score=hazrepconsequencescore_id
        where hazrep_status='Created' and hazrep_openby='$member'   
        order by hazreppriority_id dESC";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();        
        $hazreps=$sql->fetchall();
        return $hazreps;
    }



    /** Get data for the dashboard
     * 
     */
    public static function get_data_dashboard(){
        $db=$GLOBALS['db'];
    
        $addon='';
        $filter_types=['location','category','howfound','type'];
        foreach($filter_types as $filter_name){
            if(!empty($_SESSION['temp']['hazrep']['filter'][$filter_name])){
                $addon=$addon.'and (';
                $count=0;
                foreach($_SESSION['temp']['hazrep']['filter'][$filter_name]as $item){
                    if($count<>0){$addon=$addon.' or ';}
                    $addon=$addon."hazrep".$filter_name."_name='$item'";
                    $count++;                
                }
                $addon=$addon.')';
            }
        }

        $query="SELECT *
        FROM hazrep
        left join hazrep_howfound on hazrep_howfound=hazrephowfound_id
        left join hazrep_location on hazrep_location=hazreplocation_id
        left join hazrep_type on hazrep_type=hazreptype_id
        left join hazrep_category on hazrep_category=hazrepcategory_id
        left join hazrep_likelyhoodscore on hazrep_likelyhood_score=hazreplikelyhoodscore_id
        left join hazrep_consequencescore on hazrep_consequence_score=hazrepconsequencescore_id
        Where 1=1 $addon
        order by hazrep_date asc";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        
    
        $hazreps=$sql->fetchall();
        
        return $hazreps;
    }
    /** Reformat data for the dashboard
     * @param array $hazreps array of the Hazard reports for the dahsboard
     * 
     */
    public static function reformat_data_dashboard($hazreps){
        
        $return=array();
        if(!empty($hazreps)){

            
            if(empty($_SESSION['temp']['hazrep']['time_period'])){$_SESSION['temp']['hazrep']['time_period']='month';}
            if($_SESSION['temp']['hazrep']['time_period']=='year'){$format="Y";}
            if($_SESSION['temp']['hazrep']['time_period']=='month'){$format="M-Y";}
            if($_SESSION['temp']['hazrep']['time_period']=='week'){$format="W-Y";}
            if($_SESSION['temp']['hazrep']['time_period']=='weekday'){$format="N";$return=array([],[],[],[],[],[],[]);}
            foreach($hazreps as $report){
                $return[date($format,strtotime($report['hazrep_date']))]['x']=date($format,strtotime($report['hazrep_date']));
                $return[date($format,strtotime($report['hazrep_date']))]['y']=$return[date($format,strtotime($report['hazrep_date']))]['y']+1;
                
                $filter_types=['location','category','howfound','type'];
                foreach($filter_types as $filter_name){
                    $return[date($format,strtotime($report['hazrep_date']))][$report['hazrep'.$filter_name.'_name']]['y']=$return[date($format,strtotime($report['hazrep_date']))][$report['hazrep'.$filter_name.'_name']]['y']+1;
                    $all_filter[$filter_name][$report['hazrep'.$filter_name.'_name']]=$report['hazrep'.$filter_name.'_name'];
                }
                
                
                
            }
            
            if($_SESSION['temp']['hazrep']['time_period']=='weekday'){
            
                $return[1]['x']='Mon';
                $return[2]['x']='Tue';
                $return[3]['x']='Wed';
                $return[4]['x']='Thu';
                $return[5]['x']='Fri';
                $return[6]['x']='Sat';
                $return[7]['x']='Sun';
            }
            if(empty($_SESSION['temp']['hazrep']['cat'])){$_SESSION['temp']['hazrep']['cat']='quantity';}
            if($_SESSION['temp']['hazrep']['cat']=='quantity'){
                $string='["Date", "Quantity", { role: "style" } ],';
                foreach($return as $data){
                $string=$string."['".$data['x']."', ".$data['y'].", '#333'],";
                }
                $return['string']=$string;
            }else{
                $string='["Date",';
                
                
                foreach($all_filter[$_SESSION['temp']['hazrep']['cat']] as $location){
                    if(empty($location)){$location='blank';}
                    $string=$string."'$location',";
                }
                $string=$string.' { role: "style" } ],';
                foreach($return as $data){
                    $string=$string."['".$data['x']."', ";
                    
                    foreach($all_filter[$_SESSION['temp']['hazrep']['cat']] as $location){
                        $string=$string.$data[$location]['y'].",";
                    }
                    $string=$string."''],";
                }
                $return['string']=$string;
            }
        }

        $return['stats']['day_since_last']=4;
        $return['stats']['total_reports']=count($hazreps);
        $return['stats']['total_reports_closed']=0;

        foreach($hazreps as $report){
            if($report['hazrep_status']=='Closed'){
                $return['stats']['total_reports_closed']++;
            }
        }
        $return['stats']['total_reports_opened']=$return['stats']['total_reports']-$return['stats']['total_reports_closed'];
        return $return;
    }
    
}
class HazRepController{
    
    /** Manage the POST query on page HazRep
     * 
     */ 
    public static function manage_post(){
        //if GET debug=1 show POST Array 
        show_debug();

       

        if(!empty($_GET['id'])){
            $_POST['hazrep_id']=$_GET['id'];
            $_POST['view']="show_details";
        }
        //if there is a view in the POST, show the view
        if(!empty($_POST['view'])){
            //if($_POST['view']=='show_all'){HazRepController::show_all();}
            //if($_POST['view']=='show_create'){HazRepController::show_report_details();}
            if($_POST['view']=='show_details'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}
        }

        //if there an action in the POST, do the action
        if(!empty($_POST['action'])){

            if($_POST['action']=='search'){$_SESSION['temp']['hazrep']['search']=$_POST['search'];}
            if($_POST['action']=='remove_search'){unset($_SESSION['temp']['hazrep']['search']);}

            if($_POST['action']=='delete'){HazRep::delete($_POST['hazrep_id']);}
            if($_POST['action']=='save'){HazRep::update();}
            if($_POST['action']=='submit'){
                $_POST['hazrep_submittedby']=$_SESSION['temp']['id'];
                $_POST['hazrep_date_submitted']=date('Y-m-d G:i:s'); 
                $_POST['hazrep_status']='Submitted';
                HazRep::update();
                HazRep::send_email_hazrep_submitted(HazRep::get_one($_POST['hazrep_id']));
                $log_entry="Report Submitted";
                HazRep_Log::create_new($_POST['hazrep_id'],$log_entry);
            }
            if($_POST['action']=='unsubmit'){
                $_POST['hazrep_submittedby']='';
                $_POST['hazrep_date_submitted']=''; 
                $_POST['hazrep_status']='Created';
                HazRep::update();
                $log_entry="Report Un-Submitted";
                HazRep_Log::create_new($_POST['hazrep_id'],$log_entry);
            }
            if($_POST['action']=='close'){
                $_POST['hazrep_closeby']=$_SESSION['temp']['id'];
                $_POST['hazrep_date_closed']=date('Y-m-d G:i:s'); 
                $_POST['hazrep_status']='Closed';       
                HazRep::update();
                $log_entry="Report Closed";
                HazRep_Log::create_new($_POST['hazrep_id'],$log_entry);
            }
            if($_POST['action']=='reopen'){
                $_POST['hazrep_closeby']='';
                $_POST['hazrep_date_closed']=''; 
                $_POST['hazrep_status']='Submitted';       
                HazRep::update();
                $log_entry="Report Re-Open";
                HazRep_Log::create_new($_POST['hazrep_id'],$log_entry);
            }
            if($_POST['action']=='add_comment'){
                HazRep_Comment::create_new();
                $log_entry="Comment added";
                HazRep_Log::create_new($_POST['hazrep_id'],$log_entry);                
            }
            if(substr($_POST['action'], 0, 14)=='delete_comment'){
                $comment_id=substr($_POST['action'], 14, 8);
                HazRep_Comment::delete($comment_id);
                $log_entry="Comment deleted";
                HazRep_Log::create_new($_POST['hazrep_id'],$log_entry);  
            }
            if($_POST['action']=='upload_attachment'){
                HazRep_Attachment::create_new();
                $log_entry="Attachment added";
                HazRep_Log::create_new($_POST['hazrep_id'],$log_entry);                
            }
            if($_POST['action']=='delete_attachment'){
                HazRep_Attachment::delete($_POST['attachment_id']);
                $log_entry="Attachment deleted";
                HazRep_Log::create_new($_POST['hazrep_id'],$log_entry);                
            }

            if($_POST['action']=='send_email_assigned_to'){
                $_POST['hazrep_date_email_assigned_to']=date('Y-m-d G:i:s'); 
                $_POST['hazrep_member_email_assigned_to']=$_POST['hazrep_assigned_to']; 
                HazRep::update(); 

                HazRep::send_email_assigned_to(HazRep::get_one($_POST['hazrep_id']));

                $log_entry=$_POST['hazrep_assigned_to']." notified by email";
                HazRep_Log::create_new($_POST['hazrep_id'],$log_entry);
                
                
                              
            }
        }


        if(!empty($_POST['manage_filter'])or !empty($_POST['remove_filter'])){
            HazRepController::manage_filter_dashboard();
        }
        if(!empty($_POST['change_cat'])){
            $_SESSION['temp']['hazrep']['cat']=$_POST['cat'];
        }
        if(!empty($_POST['change_time_period'])){
            $_SESSION['temp']['hazrep']['time_period']=$_POST['time_period'];
        }
        if(!empty($_POST['change_chart_type'])){
            $_SESSION['temp']['hazrep']['chart_type']=$_POST['chart_type'];
        }
        if(!empty($_POST['show_closed_one'])){
            $_SESSION['temp']['hazrep']['show_closed_one']=$_POST['show_closed_one'];
            $_POST['view']='show_all';
        }
        

         //hide the GET value
         ?>
         <script>    
             if(typeof window.history.pushState == 'function') {
                 window.history.pushState({}, "Hide", "hazrep.php");
             }
         </script>
         <?php
    }

    /** Show the navbar on page HazRep
     * 
     */
    public static function navbar(){?>
        <form method="POST">
            <div class="row navbar_hazrep">
                <div class="col-sm-2">
                    <?php if (!empty($_SESSION['temp']['id'])){?>
                    <button 
                    name="view" 
                    value="show_create" 
                    type="submit" 
                    class="btn btn-primary hazrep_button">New Report</button>
                    <?php }?>
                </div>
                <div class="col-sm-2">
                    <button 
                    name="view" 
                    value="show_all" 
                    type="submit" 
                    class="btn btn-primary hazrep_button">Show List</button>
                </div>
                <div class="col-sm-2">
                    <?php if (!empty(HazRep::get_all_to_be_checked())){?>
                    <button 
                    name="view" 
                    value="show_tobechecked" 
                    type="submit" 
                    class="btn btn-danger hazrep_button"><?php echo count(HazRep::get_all_to_be_checked())?> Reports to be checked</button>
                    <?php }?>
                </div>
                <div class="col-sm-2">
                    <?php if ($_POST['view']=='show_all' or empty($_POST['view'])){
                        if($_SESSION['temp']['hazrep']['show_closed_one']=='yes'){$value='no';$caption='Hide Closed Reports';}else{$value='yes';$caption='Show Closed Reports';}?>
                        <button 
                        name="show_closed_one" 
                        value="<?php echo $value?>" 
                        type="submit" 
                        class="btn btn-default hazrep_button"><?php echo $caption?></button>
                    <?php }?>
                </div>
                <div class="col-sm-2">
                    <?php if ($_POST['view']=='show_all' or empty($_POST['view'])){?>
                        <?php if (empty($_SESSION['temp']['hazrep']['search'])){?>
                            <div class="col-sm-10">
                                <input class="form-control" name="search" placeholder="Keywords">
                            </div>
                            <div class="col-sm-2">
                                <button 
                                name="action" 
                                value="search" 
                                type="submit" 
                                class="btn btn-default hazrep_button " style="padding:6px 0px"><span class="glyphicon glyphicon-search " ></span></button>
                            </div>
                        <?php }else{?>
                            <div class="col-sm-10">
                                Filtered by: <?php echo $_SESSION['temp']['hazrep']['search']?>
                            </div>
                            <div class="col-sm-2">
                                <button 
                                name="action" 
                                value="remove_search" 
                                type="submit" 
                                class="btn btn-default hazrep_button " style="padding:6px 0px"><span class="glyphicon glyphicon-remove " ></span></button>
                            </div>
                        <?php }?>

                    <?php }?>
                </div>
                <div class="col-sm-2">
                    <button 
                    name="view" 
                    value="show_dashboard" 
                    type="submit" 
                    class="btn btn-primary hazrep_button">Dashboard</button>
                </div>
            </div>
        </form>
        <?php
    }    
    /** Show the main body on page HazRep
     * 
     */
    public static function general_view(){?>
        <div class="row">
            <div class="col-md-12 main-body">
                <?php if(empty($_POST['view'])and empty($_POST['action'])){HazRepController::show_all();}?>
                
                <?php if($_POST['view']=='show_dashboard'){HazRepController::show_dashboard();}?>
                <?php if($_POST['view']=='show_all'){HazRepController::show_all();}?>
                <?php if($_POST['view']=='show_tobechecked'){HazRepController::show_tobechecked();}?>
                <?php if($_POST['view']=='show_create'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
                <?php if($_POST['action']=='delete'){HazRepController::show_all();}?>
                <?php if($_POST['action']=='search'){HazRepController::show_all();}?>
                <?php if($_POST['action']=='remove_search'){HazRepController::show_all();}?>
                <?php if($_POST['action']=='save'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
                <?php if($_POST['action']=='submit'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
                <?php if($_POST['action']=='unsubmit'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
                <?php if($_POST['action']=='close'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
                <?php if($_POST['action']=='reopen'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
                <?php if($_POST['action']=='add_comment'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
                <?php if(substr($_POST['action'], 0, 14)=='delete_comment'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
                <?php if($_POST['action']=='upload_attachment'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
                <?php if($_POST['action']=='delete_attachment'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
                <?php if($_POST['action']=='send_email_assigned_to'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
            </div>
            
            <div class="hidden-box" style="display:none"></div>
        </div>
        
        
        <?php
    }
    /** Show the info bar on page HazRep
     * 
     */
    public static function show_infobar(){
        $type="info";
        if($_POST['action']=='delete'){$message="Report Deleted";$type='danger';}
        if($_POST['action']=='save'){$message="Report Saved";}
        if($_POST['action']=='submit'){$message="Report Submitted";}
        if($_POST['action']=='unsubmit'){$message="Report Un-Submitted";$type='warning';}
        if($_POST['action']=='close'){$message="Report Closed";}
        if($_POST['action']=='reopen'){$message="Report Re-Opened";$type='warning';}
        if($_POST['action']=='add_comment'){$message="Comment Added";}
        if(substr($_POST['action'], 0, 14)=='delete_comment'){$message="Comment Deleted";$type='danger';}
        if($_POST['action']=='upload_attachment'){$message="Attachment Uploaded";}
        if($_POST['action']=='delete_attachment'){$message="Attachment Deleted";$type='danger';}
        if($_POST['action']=='send_email_assigned_to'){$message="Notification Email Sent";}?>
        <?php if($_POST['view']<>'show_dashboard'){?>
            <div class="infobar">
                <?php if($message){?>
                    <div class="alert alert-<?php echo $type?> alert-dismissible f" role="alert">
                    <?php echo $message?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                <?php }?>
            </div>
        <?php }
    }    


    /** Show all the reports 
     * 
     */
    public static function show_all(){
        foreach(HazRep::get_all() as $hazrep){
            HazRepController::show_report_line($hazrep);
        }?>
        <script src="/js/hazrep.js"></script>
        <?php
    }    
    /** Show all the reports to be checked
     * 
     */
    public static function show_tobechecked(){
        foreach(HazRep::get_all_to_be_checked() as $hazrep){
            HazRepController::show_report_line($hazrep);
        }?>
        <script src="/js/hazrep.js"></script>
        <?php
    } 
    /** Show the line on the main view of the Hazrep
     * 
     */
    public static function show_report_line($hazrep){?>
        
        
        <div class="row report-line">
            <div class="col-sm-5 report-left">                
                <div class="col-sm-2">
                    <div class="report-left-1">
                        <div class="text-center"><?php echo $hazrep['hazrep_number']?></div>
                        <div class="text-center"><img src="img/<?php echo $hazrep['hazreppriority_name']?>.png" width="25" height="25"></div>
                        
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class=""><b>Open by: </b><?php echo $hazrep['hazrep_openby']?></div>
                    <div class=""><b>Date: </b><?php echo date('jS M Y',strtotime($hazrep['hazrep_date']))?></div>
                    <div class=""><b>Time: </b><?php echo date('G:i:s',strtotime($hazrep['hazrep_date']))?></div>
                    <div class=""><b>How Found: </b><?php echo $hazrep['hazrephowfound_name']?></div>
                    <div class=""><b>Location: </b><?php echo $hazrep['hazreplocation_name']?></div>
                </div>
                <div class="col-sm-5">
                <div class=""><b>Hazard details:</b><br><?php echo $hazrep['hazrep_description']?></div>
                </div>  
            </div>
            <div class="col-sm-5 report-middle <?php echo $hazrep['hazrep_status']?>">
                <?php HazRepController::show_progress_bar($hazrep);?>
                <?php if($hazrep['hazrep_status']=='Created'){?>
                    <div class="col-sm-12 text-center">Report Not Submitted Yet</div>
                    <?php
                }else{
                    $risk_score=$hazrep['hazrep_likelyhood_score']*$hazrep['hazrep_consequence_score'];
                    $color='green';
                    if( $risk_score>=9){$color='yellow';}
                    if( $risk_score>=16){$color='red';}
                    ?>
                        <div class="col-sm-1 text-center">
                            <?php if(!empty($hazrep['hazrep_likelyhood_score'])){?>
                                Risk<br><span class="score <?php echo $color?>"><?php echo $risk_score?></span>                           
                            <?php }?>
                        </div>
                      
                    <div class="col-sm-3 text-center">
                        <?php if($hazrep['hazrep_status']=='Submitted'){  ?>
                            Submitted by <?php echo $hazrep['hazrep_submittedby']?><br>
                            <?php echo date('jS M',strtotime($hazrep['hazrep_date_submitted']))?> 
                        <?php }else{?>
                            Closed by <?php echo $hazrep['hazrep_closeby']?><br>
                            <?php echo date('jS M',strtotime($hazrep['hazrep_date_closed']))?>
                        <?php }?>
                    </div>
                    <div class="col-sm-6 text-center">
                        <?php if(!empty($hazrep['hazrep_action_taken'])){  ?>
                            <p><b>Action taken:</b><?php echo nl2br($hazrep['hazrep_action_taken'])?></p>
                        <?php }?>
                    </div>
                    <div class="col-sm-1 text-center">
                        <?php if(!empty($count=count(HazRep_Attachment::get_all($hazrep['hazrep_id'])))){  ?>
                        <p><?php echo $count?> <span class="glyphicon glyphicon-paperclip"></span></p>
                        <?php }?>
                    </div>
                    <?php
                }?>
            </div>
            <div class="col-sm-2 report-right">
            <div class="action-container">
                <div 
                name="hazrep_id" 
                data-value="<?php echo $hazrep['hazrep_id']?>" 
                class="btn btn-default action-button expand-button">
                    <span class="glyphicon glyphicon-resize-full"></span>
                </div>                    
            </div>
            </div>
        </div>
        

        
        <?php
    }


    /** Show the details of the Hazard Report
     * 
     */
    public static function show_report_details($hazrep=''){
        if(empty($hazrep)){
            $hazrep=HazRep::create_new();
        }
         //check the permissions
        HazRepController::add_permissions_to_session($hazrep);
        ?>
        <form method="POST">
            <input type="hidden" name="hazrep_id" value="<?php echo $hazrep['hazrep_id']?>">
            <div class="row report-line">
                <div class="col-sm-5 report-left">
                    <div class="">
                        <?php HazRepController::show_initial_report($hazrep)?>
                    </div>
                </div>
                <div class="col-sm-5 report-middle <?php echo $hazrep['hazrep_status']?>">
                    <div class="">
                        <?php HazRepController::show_middle_report($hazrep)?>
                    </div>
                </div>
                <div class="col-sm-2 report-right">
                    <div class="">
                    <?php if (!empty($_SESSION['temp']['id'])){HazRepController::show_action_button($hazrep);}?>
                    </div>
                </div>
            </div>
        </form>
        <?php HazRep_LogController::index($hazrep['hazrep_id']);?>
        <?php HazRep_AttachmentController::index($hazrep['hazrep_id']);?>
        <script src="/js/hazrep.js"></script>

        
        <?php
    }
    /** Show initial report form
     * 
     */
    public static function show_initial_report($hazrep){
        $protected='protected';
        if($_SESSION['temp']['permission_hazrep']['can edit initial']==1){$protected=0;}
        $always_protected='protected';
        
        ?>
        <div class="initial-container">
            <div class="initial-row initial-row-1">
                <?php HazRepController::show_input(
                    'hazrep_number',
                    'Number',
                    $hazrep['hazrep_number'],
                    'text',
                    $always_protected);?>     
                <?php HazRepController::show_input(
                    'hazrep_date',
                    'Date',
                    date('Y-m-d',strtotime($hazrep['hazrep_date'])),
                    'date',
                    $protected,
                    '',
                    'style="display: flex;flex-direction: row;"');?>  
                <?php HazRepController::show_input(
                    'hazrep_time',
                    'Time',
                    date('H:i',strtotime($hazrep['hazrep_date'])),
                    'time',
                    $protected,
                    '',
                    'style="display: flex;flex-direction: row;"');?>      
            </div>
            <div class="initial-row initial-row-2">
                <?php HazRepController::show_input(
                    'hazrep_openby',
                    'Open by',
                    $hazrep['hazrep_openby'],
                    'text',
                    $always_protected);show($protected);?> 
                <?php HazRepController::show_select(
                    'hazrep_priority',
                    'Priority',
                    $hazrep['hazreppriority_name'],                    
                    $protected,
                    HazRep::get_all_priority(),
                    'hazreppriority_name',
                    'hazreppriority_id' );show($protected);?>     
            </div>
            <div class="initial-row initial-row-3">
                <?php HazRepController::show_select(
                    'hazrep_howfound',
                    'How Found',
                    $hazrep['hazrephowfound_name'],                    
                    $protected,
                    HazRep::get_all_howfound(),
                    'hazrephowfound_name',
                    'hazrephowfound_id' );?> 
                <?php HazRepController::show_select(
                    'hazrep_location',
                    'Location',
                    $hazrep['hazreplocation_name'],                    
                    $protected,
                    HazRep::get_all_location(),
                    'hazreplocation_name',
                    'hazreplocation_id' );?>                 
            </div>
            <div class="initial-row initial-row-4">
                <?php HazRepController::show_textarea(
                    'hazrep_description',
                    'Description',
                    $hazrep['hazrep_description'],
                    $protected,3,30,"Describe the Hazard");?>       
            </div>
            <div class="initial-row initial-row-5">
                <?php HazRepController::show_input(
                    'hazrep_who_was_notified',
                    'Who has been notified',
                    $hazrep['hazrep_who_was_notified'],
                    'text',
                    $protected);?> 
               
               
            </div>   
            <div class="initial-row initial-row-4">
                <?php HazRepController::show_textarea(
                    'hazrep_immediate_corrective_action',
                    'Immediate Corrective Actions',
                    $hazrep['hazrep_immediate_corrective_action'],
                    $protected,3,30,"Describe Immediate Corrective Actions");?>       
            </div>         
        </div>

        
        <?php
    }
    /** Show initial report form
     * 
     */
    public static function show_middle_report($hazrep=''){
        $protected='protected';
        if($_SESSION['temp']['permission_hazrep']['can edit middle']==1){$protected=0;}
        
        $always_protected='protected';
        ?>
        <div class="middle-container ">
            <?php HazRepController::show_progress_bar($hazrep);?>
            <?php if($hazrep['hazrep_status']<>'Created'){?>
                <?php if($protected==0 or (!empty($hazrep['hazrep_assigned_to'] and $hazrep['hazrep_assigned_to']<>' '))){?>
                <div class="initial-row ">                    
                    <?php HazRepController::show_select(
                    'hazrep_assigned_to',
                    'Assigned to',
                    $hazrep['hazrep_assigned_to'],                    
                    $protected,
                    Employee::get_all(),
                    'employee_fullname',
                    'employee_fullname' );?> 
                    <div class="initial-item">
                        <?php if(!empty($hazrep['hazrep_date_email_assigned_to'])){?>
                        <p>Email sent</p>
                        <div 
                        class="form-control not-allowed'"
                        style="font-size: 8px;" 
                        ><?php echo date('jS M  \a\t G:i',strtotime($hazrep['hazrep_date_email_assigned_to']))?> to <?php echo $hazrep['hazrep_member_email_assigned_to']?></div>
                        <?php }else{?>
                            <br>
                        <?php }?>
                    </div>
                       
                    <div class="initial-item">
                        <?php if(empty($protected)){?>
                            <p><?php if(empty($hazrep['hazrep_date_email_assigned_to'])){?>Send notification email <?php }else{?>Resend <?php }?></p>
                            <?php HazRepController::show_one_action_button('send_email_assigned_to','<span class="glyphicon glyphicon-send"></span>');?>
                        <?php }?> 
                        
                        
                    </div>
                    
                </div>
                <?php }?>
                <div class="initial-row ">
                    <?php HazRepController::show_select(
                        'hazrep_type',
                        'Type',
                        $hazrep['hazreptype_name'],                    
                        $protected,
                        HazRep::get_all_type(),
                        'hazreptype_name',
                        'hazreptype_id' );?> 
                    <?php HazRepController::show_select(
                        'hazrep_category',
                        'Category',
                        $hazrep['hazrepcategory_name'],                    
                        $protected,
                        HazRep::get_all_category(),
                        'hazrepcategory_name',
                        'hazrepcategory_id' );?> 
                </div>
                <div class="initial-row ">
                    <?php HazRepController::show_select(
                        'hazrep_likelyhood_score',
                        'Likelyhood Score',
                        $hazrep['hazreplikelyhoodscore_name'],                    
                        $protected,
                        HazRep::get_all_likelyhoodscore(),
                        'hazreplikelyhoodscore_name',
                        'hazreplikelyhoodscore_id');?> 
                    <?php HazRepController::show_select(
                        'hazrep_consequence_score',
                        'Consequence Score',
                        $hazrep['hazrepconsequencescore_name'],                    
                        $protected,
                        HazRep::get_all_consequencescore(),
                        'hazrepconsequencescore_name',
                        'hazrepconsequencescore_id'); 

                    $risk_score=$hazrep['hazrep_likelyhood_score']*$hazrep['hazrep_consequence_score'];
                    if($risk_score>0 or $protected==0){?>
                    <div class="initial-item">
                        <div class="tile-wrapper">
                            <p>Risk Score <span class="glyphicon glyphicon-info-sign"></span></p>
                            <div class="tile-content">
                            <div class="score-title ">Risk Score = Likelyhood Score x Consequence Score</div>
                            <div class="row score-line">
                                <div class="score-score">1-8</div>
                                <div class="score-name">Low</div>
                                <div class="score-description"></div>
                            </div>
                            <div class="row score-line">
                                <div class="score-score">9-15</div>
                                <div class="score-name">Medium</div>
                                <div class="score-description"></div>
                            </div>
                            <div class="row score-line">
                                <div class="score-score">>16</div>
                                <div class="score-name">High</div>
                                <div class="score-description"></div>
                            </div>
                            
                            
                            <div class="score-title ">Likelyhood Score</div>
                                <?php foreach(HazRep::get_all_likelyhoodscore() as $score){?>
                                    <div class="row score-line">
                                        <div class="score-score"><?php echo $score['hazreplikelyhoodscore_id']?></div>
                                        <div class="score-name"><?php echo $score['hazreplikelyhoodscore_name']?></div>
                                        <div class="score-description"><?php echo $score['hazreplikelyhoodscore_description']?></div>
                                    </div>
                                <?php }?>
                            <div class="score-title ">Consequence Score</div>
                                <?php foreach(HazRep::get_all_consequencescore() as $score){?>
                                    <div class="row score-line">
                                        <div class="score-score"><?php echo $score['hazrepconsequencescore_id']?></div>
                                        <div class="score-name"><?php echo $score['hazrepconsequencescore_name']?></div>
                                        <div class="score-description"><?php echo $score['hazrepconsequencescore_description']?></div>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                        <?php 
                        $color='green';
                        if( $risk_score>=9){$color='yellow';}
                        if( $risk_score>=16){$color='red';}
                        ?>
                        <div id="risk_score" class="score <?php echo $color?>"><?php echo $risk_score?></div>
                        
                    </div>
                    <?php }?>
                    
                </div>
                <div class="initial-row ">
                    <?php HazRepController::show_textarea(
                    'hazrep_action_taken',
                    'Actions taken to address the hazard',
                    $hazrep['hazrep_action_taken'],
                    $protected,
                    3,30,"Describe the Actions Taken");?>
                    
                
                </div>
                <div class="initial-row ">
                    <?php HazRepController::show_textarea(
                    'hazrep_verification',
                    'Verification',
                    $hazrep['hazrep_verification'],
                    $protected,
                    3,30,"Verifications Details");?>
                </div>
                <div class="initial-row ">
                    <?php HazRepController::show_input(
                        'hazrep_SIMPL',
                        'SIMPL number',
                        $hazrep['hazrep_SIMPL'],
                        'text',
                        $protected,
                        "Enter SIMPL Number");?>  
                    <?php HazRepController::show_input(
                        'hazrep_FIIX',
                        'FIIX Ref',
                        $hazrep['hazrep_FIIX'],
                        'text',
                        $protected,
                        "Enter Fiix Reference");?>  
                    <?php HazRepController::show_input(
                        'hazrep_other_ref',
                        'Other Refs',
                        $hazrep['hazrep_other_ref'],
                        'text',
                        $protected,
                        "Enter Other Reference");?>  
                    
                </div>
                <?php HazRep_CommentController::index($hazrep['hazrep_id'])?>
                <?php if($hazrep['hazrep_status']=='Closed'){?> 
                    <div class="initial-row ">
                        <?php HazRepController::show_input(
                            'hazrep_closeby',
                            'Closed by',
                            $hazrep['hazrep_closeby'],
                            'text',
                            $always_protected);?>  
                        <?php HazRepController::show_input(
                            'hazrep_date_closed',
                            'Date',
                            date('Y-m-d',strtotime($hazrep['hazrep_date_closed'])),
                            'text',
                            $always_protected);?>  
                        <?php HazRepController::show_input(
                            'hazrep_time_closed',
                            'Time',
                            date('G:i',strtotime($hazrep['hazrep_date_closed'])),
                            'text',
                            $always_protected);?> 
                    </div>       
                <?php }?>     
            <?php }?>
            <?php if($hazrep['hazrep_status']=='Created'){?>
            <div class="initial-row ">
                <br><br>
                <p class="initial-item">Report Created but not Submitted</p>
            </div>
            <div class="initial-row ">
                <br><br>
                <p class="initial-item">
                    
                </p>
            </div>
            <?php }?>
                 
           
        </div>

        
        <?php
    }
    /** Show an input in the edit form
     * 
     * @param string $name name of the column in Hazrep table
     * @param string $caption Header shown at the top of the item
     * @param string $verification the value from the hazard Report for that input
     * @param string #type type of input (text/date/time/number)
     * @param string $protected if ==1 read only
     * @param string $placeholder caption of the input when empty
     * 
     */
    public static function show_input($name,$caption,$verification,$type='text',$protected=0,$placeholder=''){
        $div='input';
        if (empty($_SESSION['temp']['id'])){$protected=1;}
        if($protected<>0){$div='div';}

        ?>
        <div class="initial-item">
            <?php if($protected==0 or  !empty($verification)){?>
            <p><?php echo $caption;?></p>
            <<?php echo $div;?> 
            type="<?php echo $type;?>" 
            class="form-control <?php if($protected<>0){echo' not-allowed';}?>" 
            name="<?php echo $name;?>"
            style="text-align:center"
            value="<?php echo $verification;?>" 
            placeholder="<?php echo $placeholder;?>" 
            ><?php  if($protected<>0){echo $verification;}?></<?php echo $div;?>>

            <?php }?>
            
        </div>

        
        <?php
    }
    /** Show a select input in the edit form
     *      * 
     * @param string $name name of the column in Hazrep table
     * @param string $caption Header shown at the top of the item
     * @param string $verification the value from the hazard Report for that input
     * @param string $protected if ==1 read only
     * @param array $list array of all item to be shown in the select
     * @param string $columncaption name of the column to show in the select
     * @param string $columnvalue name of the column for the value of the select
     * 
     */
    public static function show_select($name,$caption,$verification,$protected=0,$list,$columncaption,$columnvalue){
        
        if (empty($_SESSION['temp']['id'])){$protected=1;}
        ?>
        <div class="initial-item">
            <?php if($protected==0 or  !empty($verification)){?>
                <p><?php echo $caption;?></p>
                <?php if($protected==0){?>
                <select class="form-control initial-item" 
                    id="<?php echo $name;?>" 
                    name="<?php echo $name;?>" >
                    <?php foreach($list as $item){?>
                        <option value="<?php echo $item[$columnvalue]?>"
                        <?php if($verification==$item[$columncaption]){echo 'selected';}?>
                        >
                        <?php echo $item[$columncaption]?>
                    </option>
                    <?php }?>
                </select>
                <?php }else{?>
                    <div class="form-control initial-item not-allowed" name="<?php echo $name;?>" value="<?php echo $verification;?>"
                        ><?php echo $verification;?></div>

                <?php }?>
            <?php }?>
            
        </div>

        
        <?php
    }
    /** Show a select input in the edit form
     *      * 
     * @param string $name name of the column in Hazrep table
     * @param string $caption Header shown at the top of the item
     * @param string $verification the value from the hazard Report for that input
     * @param string $protected if ==1 read only
     * @param string $row number of row to show in the textarea
     * @param string $col number of column to show in the textarea
     * @param string $placeholder placeholder of the textarea
     * 
     */
    public static function show_textarea($name,$caption,$verification,$protected=0,$rows=3,$cols=30,$placeholder=""){
        
        if (empty($_SESSION['temp']['id'])){$protected=1;}
        ?>
        <div class="initial-item">
            <?php if($protected==0 or  !empty($verification)){?>
                <p><?php echo $caption;?></p>
                <?php if($protected==0){?>
                <textarea 
                    class="form-control" 
                    id="<?php echo $name;?>" 
                    name="<?php echo $name;?>" 
                    cols="<?php echo $cols;?>" 
                    rows="<?php echo $rows;?>"
                    placeholder="<?php echo $placeholder;?>"><?php echo $verification;?></textarea>
                <?php }else{?>
                    <div class="form-control initial-item not-allowed" name="<?php echo $name;?>" value="<?php echo $verification;?>"
                        ><?php echo nl2br($verification);?></div>

                <?php }?>
            <?php }?>
            
        </div>

        
        <?php
    }

    /** Show all the actionbutton for the line
     * 
     */
    public static function show_action_button($hazrep){?>
        <div class="action-container">
            <?php
            if($hazrep['hazrep_status']<>'Closed' and $_SESSION['temp']['permission_hazrep']['can save']){
                HazRepController::show_one_action_button('save','<span class="glyphicon glyphicon-floppy-disk"></span>');            
            }

            ?>
            <br><br><?php
            if($hazrep['hazrep_status']=='Submitted' and $_SESSION['temp']['permission_hazrep']['can close']){
                HazRepController::show_one_action_button('close','Close Report','style="max-width:100px"');                
            }
            
            if($hazrep['hazrep_status']=='Created'and $_SESSION['temp']['permission_hazrep']['can submit']){
                HazRepController::show_one_action_button('submit','Submit Report','style="max-width:100px"');
            }
            
            ?>
            <br><br><br><br><br><br><?php
            if($hazrep['hazrep_status']=='Closed'and $_SESSION['temp']['permission_hazrep']['can reopen']){
                HazRepController::show_one_action_button('reopen','Re-Open Report',"onclick=\"return confirm('Are you sure you want to re-open this report?');\"",' btn-danger');
            }
            if($hazrep['hazrep_status']=='Submitted'and $_SESSION['temp']['permission_hazrep']['can unsubmit']){
                HazRepController::show_one_action_button('unsubmit','UnSubmit Report',"onclick=\"return confirm('Are you sure you want to un-submit this report?');\"",' btn-danger');
            }
            if($_SESSION['temp']['permission_hazrep']['can delete']){
                HazRepController::show_one_action_button('delete','<span class="glyphicon glyphicon-trash"></span>',"onclick=\"return confirm('Are you sure you want to delete this report?');\"",'btn-danger');
            }
            ?>
            
        </div>

        
        <?php
    }
    /** Show on actiombutton for the line
     * @param string $action action done by this button
     * @param string $caption caption of the button
     * @param string $option html option for the <button> 
     * 
     */
    public static function show_one_action_button($action,$caption,$option='',$btn_type='btn-default'){?>
        <button 
            type="submit" 
            name="action" 
            value="<?php echo $action?>" 
            <?php echo $option?>
            class="btn <?php echo $btn_type?> action-button">
            <?php echo $caption?>
        </button>          

        
        <?php
    }

    /** Show the progress bar
     * @param string $value number from 0 to 100
     * @param string $caption Caption shown on the progress bar
     * 
     */
    public static function show_progress_bar($hazrep){
        if($hazrep['hazrep_status']=='Created'){
            $nbrdayopen=max(0,floor((time()-strtotime($hazrep['hazrep_date']))/3600/24));
            $value=10;
            $caption="Created";
            
        }
        if($hazrep['hazrep_status']=='Submitted'){
            $nbrdayopen=max(0,floor((time()-strtotime($hazrep['hazrep_date_submitted']))/3600/24));
            $value=60;
            $caption="Submitted";
        }
        if($hazrep['hazrep_status']=='Closed'){
            $value=100;
            $caption='Closed';
        }
        if($nbrdayopen>0){$caption=$caption." ($nbrdayopen days)";}
        ?>
        <div class="progress">
            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $value?>%"><?php echo $caption?></div>
        </div>
        <?php
    }

    /** Add the permissions to the session array
     * 
     */
    public static function add_permissions_to_session($hazrep){
        //if part of the HR agroup then you can do everything
        $db=$GLOBALS['db'];
        $query="SELECT TOP (1000) [groupallocation_groupid]
        ,[groupallocation_employee]
        ,[groupallocation_leader]
        FROM employee_group_allocation
        WHERE groupallocation_employee='".$_SESSION['temp']['id']."' 
            AND groupallocation_groupid=14";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        $result=$sql->fetch();
        $permissions=[
            'can edit',
            'can reopen',
            'can submit',
            'can unsubmit',
            'can close',
            'can comment',
            'can delete',
            'can edit',
            'can save',
            'can edit middle',
            'can edit initial',
            'can delete attachment'
        ];
        //set all the permission to 0;
        foreach($permissions as $permission){
            $_SESSION['temp']['permission_hazrep'][$permission]=0;
        }
        //if you are part of Health and safety group you have all right
        if (!empty($result)){
            $_SESSION['temp']['permission_hazrep']['can edit initial']=1;
            $_SESSION['temp']['permission_hazrep']['can reopen']=1;
            $_SESSION['temp']['permission_hazrep']['can submit']=1;
            $_SESSION['temp']['permission_hazrep']['can unsubmit']=1;
            $_SESSION['temp']['permission_hazrep']['can close']=1;
            $_SESSION['temp']['permission_hazrep']['can comment']=1;
            $_SESSION['temp']['permission_hazrep']['can reopen']=1;
            $_SESSION['temp']['permission_hazrep']['can delete']=1;
            $_SESSION['temp']['permission_hazrep']['can save']=1;
            $_SESSION['temp']['permission_hazrep']['can edit middle']=1;
            $_SESSION['temp']['permission_hazrep']['can delete attachment']=1;
        }
        //if you are have created the report you can submit, save, unsubmit if it is less than 1days since the submit
        if (($hazrep['hazrep_openby']==$_SESSION['temp']['id'])){

            $_SESSION['temp']['permission_hazrep']['can submit']=1;            
            $_SESSION['temp']['permission_hazrep']['can save']=1;
            if (($hazrep['hazrep_status']=='Created')){
            $_SESSION['temp']['permission_hazrep']['can edit initial']=1;
            $_SESSION['temp']['permission_hazrep']['can delete']=1;
            }

            if(max(0,floor((time()-strtotime($hazrep['hazrep_date_submitted']))/3600/24))==0){
                $_SESSION['temp']['permission_hazrep']['can unsubmit']=1;
            }          

                        
        }
        //if you have been assigned the hazard you can modify the middle
        if (($hazrep['hazrep_assigned_to']==$_SESSION['temp']['id'])){
            $_SESSION['temp']['permission_hazrep']['can edit middle']=0;

        }

        //if the report is closed nothing can be touch
        if (($hazrep['hazrep_status']=='Closed')){
            $_SESSION['temp']['permission_hazrep']['can edit initial']=0;
            $_SESSION['temp']['permission_hazrep']['can edit middle']=0;
            $_SESSION['temp']['permission_hazrep']['can save']=0;
            $_SESSION['temp']['permission_hazrep']['can edit']=0;
        }
        //if the report is submitted nothing can be touch
        if (($hazrep['hazrep_status']=='Submitted')){
            $_SESSION['temp']['permission_hazrep']['can edit initial']=0;
        }
        
        
    }


    /** Show the dashboard HazRep
     * 
     */
    public static function show_dashboard(){?>
        <!-- Main body of the dashboard -->
        <div id="body_dashboard" class="body_dashboard p-2">
            <?php HazRepController::show_infobar_dashboard()?>
            <div class="row">
                <!-- Main body of the dashboard -->
                <div class="col-md-8 border-pls dashboard_left">
                    <!-- Chart/Graph to show the result -->
                    <?php HazRepController::show_chart()?> 
                </div>
                <div class="col-md-4 border-pls">
                    
                    <div class="row mtb-2">
                        <div id="stats_view" >
                            <?php HazRepController::show_overall_stats()?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row dashboard-list-hazrep">
                <b>List of all the reports:</b>
                <i style="cursor:pointer" onclick="show_all_report()">Click to Expand</i>
                <div class="dashboard-reports" id="all_report" style="display:none">
                    <?php foreach(HazRep::get_data_dashboard() as $hazrep){
                        HazRepController::show_report_line($hazrep);
                    }?>
                </div>
            </div>
        </div>
        <script src="/js/hazrep.js"></script>
        <?php
    } 



    /** Show the dashboard HazRep
     * 
     */
    public static function show_dashboard_navbar(){?>
        
        <!-- //filtering by location/body parts/ hazrep type / time of the day-->
        <div class="row mt-2" style="margin-bottom:6px">
            <div class="col-md-3">
                <form method="POST">
                <input type="hidden" name="view" value="show_dashboard">
                <input type="hidden" name="manage_filter" value="yes">
                <select class="form-control hazrep-btn btn-default " name="location" oninput="submit()">
                    <option selected>All Locations</option>
                    <?php foreach(HazRep::get_all_location() as $location){?>
                        <option ><?php echo $location['hazreplocation_name']?></option>
                    <?php }?>
                    
                </select>
                </form>
            </div>
            <div class="col-md-3">
                <form method="POST">
                    <input type="hidden" name="view" value="show_dashboard">
                    <input type="hidden" name="manage_filter" value="yes">
                    <select class="form-control hazrep-btn btn-default " name="category" oninput="submit()">
                        <option selected>All Categories</option>
                        <?php 
                        foreach(HazRep::get_all_category() as $category){?>
                            <option ><?php echo $category['hazrepcategory_name']?></option>
                        <?php }?>
                        
                    </select>
                </form>
            </div>
            <div class="col-md-3">
                <form method="POST">
                    <input type="hidden" name="view" value="show_dashboard">
                    <input type="hidden" name="manage_filter" value="yes">
                    <select class="form-control hazrep-btn btn-default " name="howfound" oninput="submit()">
                        <option selected>All How Founds</option>
                        <?php 
                        foreach(HazRep::get_all_howfound() as $howfound){?>
                            <option ><?php echo $howfound['hazrephowfound_name']?></option>
                        <?php }?>
                        
                    </select>
                </form>
            </div>
            <div class="col-md-3">
                <form method="POST">
                    <input type="hidden" name="view" value="show_dashboard">
                    <input type="hidden" name="manage_filter" value="yes">
                    <select class="form-control hazrep-btn btn-default " name="type" oninput="submit()">
                        <option selected>All Types</option>
                        <?php 
                        foreach(HazRep::get_all_type() as $type){?>
                            <option ><?php echo $type['hazreptype_name']?></option>
                        <?php }?>
                        
                    </select>
                </form>
            </div>
        </div>
    

        <?php
    } 
    /** Show the info bar of the dashboard HazRep
     * 
     */
    public static function show_infobar_dashboard(){?>
        <!-- //all Tag used for filtering -->
        <div class="infobar">
            <?php $filters=HazRepController::get_session_filter_array();
            $count=0;
            foreach($filters as $filter){
                //show tag for each filter done ?>
                <div class="tag-filter border-pls">
                    <?php echo $filter?>&nbsp;&nbsp;&nbsp;<b>
                        <span 
                        style="cursor: pointer;" 
                        onclick="document.getElementById('tag_<?php echo$count?>').submit()"
                        >X</span>
                    </b>
                </div>
                <form method="POST" id="tag_<?php echo$count?>">
                    <input type="hidden" name="view" value="show_dashboard">
                    <input type="hidden" name="remove_filter" value="yes">
                    <input type="hidden" name="item" value="<?php echo $filter?>">
                </form>
                
                <?php
                $count++;
            }?>
        </div>
        <?php    
    }
    /** Manage and Store Filter for the HazRep dashboard
     * 
     */
    public static function manage_filter_dashboard(){
        $filter_types=['location','category','howfound','type'];
        foreach($filter_types as $filter_name){
            if(!empty($_POST['manage_filter'])){
                if(!empty($_POST[$filter_name])){
                    $_SESSION['temp']['hazrep']['filter'][$filter_name][$_POST[$filter_name]]=$_POST[$filter_name];
                }            
            }
            if(!empty($_POST['remove_filter'])){
                if(isset($_SESSION['temp']['hazrep']['filter'][$filter_name][$_POST['item']])){
                        unset($_SESSION['temp']['hazrep']['filter'][$filter_name][$_POST['item']]);
                    }
                }
            
        }
        
        

    }
    /** Manage and Store Filter for the HazRep dashboard
     * 
     */
    public static function get_session_filter_array(){
        $session_filter=$_SESSION['temp']['hazrep']['filter'];
        $filter_types=['location','category','howfound','type'];
        foreach($filter_types as $filter_type){
            foreach($session_filter[$filter_type] as $filter_item){
                $filters[]=$filter_item;
            }
        }        
        return $filters;
    }
    /** Show Chart of the HazRep Dashboard
     * 
     */
    public static function show_chart(){
        $all_data=HazRep::reformat_data_dashboard(HazRep::get_data_dashboard())?>
        <div class="row border-pls" style="width: 100%; ">
            <!-- navbar for dashboard, with all the filters -->
            <?php HazRepController::show_dashboard_navbar()?>
            <div class="col-md-2 text-center ">
                
                <div class="show-category">
                <br><br>
                    <form method="POST" id="refresh_chart">
                        <input type="hidden" name="view" value="show_dashboard">
                        <button class="btn btn-default  mtb-2" onclick="submit()"><span class="glyphicon glyphicon-refresh"></span> Refresh</button><br>
                    </form>
                    <br><br>
                    <script>
                        function change_cat(cat){
                            document.getElementById('thecat').value=cat;
                            document.getElementById('change_cat').submit();
                        }
                    </script>
                    <?php $active='';if($_SESSION['temp']['hazrep']['cat']=='quantity'){$active='active';}?>
                    <button class="btn btn-default  mtb-2 <?php echo$active?>" onclick="change_cat('quantity')">Quantity</button><br>
                    <?php 
                    $filter_types=[['location','Location'],['category','Category'],['howfound','How Found'],['type','Type']];
                    foreach($filter_types as $filter_type){
                        $active='';if($_SESSION['temp']['hazrep']['cat']==$filter_type[0]){$active='active';}?>
                        <button 
                            class="btn btn-default  mtb-2 <?php echo$active?>" 
                            onclick="change_cat('<?php echo $filter_type[0]?>')">
                            <?php echo $filter_type[1]?>
                        </button>
                        <br>
                        <?php
                    }
                    ?>
                    
                    <form method="POST" id="change_cat">
                        <input type="hidden" name="view" value="show_dashboard">
                        <input type="hidden" name="change_cat" value="yes">
                        <input type="hidden" id="thecat" name="cat" value="">
                    </form>
                </div>
            </div>
            <div class="col-md-10">
                <?php if(!empty($all_data['string'])){?>
                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                        google.charts.load("current", {packages:['corechart']}).then(drawChart_hazrep);
                        //google.charts.setOnLoadCallback(drawChart);
                        //$(window).resize(drawChart);
                        function drawChart_hazrep() {
                            var data = google.visualization.arrayToDataTable([<?php echo $all_data['string']?>]);
                            var view = new google.visualization.DataView(data);
                            <?php if($_SESSION['temp']['hazrep']['cat']=='quantity'){?>
                                view.setColumns([0, 1]);
                            <?php }?>
                            

                            var options = {
                                title: "Number of Incidents Reported",
                                legend: { position: "right" },
                                isStacked: true,
                                vAxis: {minValue: 0},
                                interpolateNulls: true
                            };
                            
                            <?php if(!empty($_SESSION['temp']['hazrep']['chart_type'])){$chart_type=$_SESSION['temp']['hazrep']['chart_type'];}else{$chart_type='Column';}?>
                            var chart = new google.visualization.<?php echo $chart_type?>Chart(document.getElementById("columnchart_values"));
                            chart.draw(view, options);
                        }
                    </script>
                    
                    <div id="columnchart_values" style="width: 100%; height: 50vh;"></div>
                    <div class="show-time-period">
                        
                            <?php $active='';if($_SESSION['temp']['hazrep']['time_period']=='year'){$active='active';}?>
                            <button class="btn btn-default  mtb-2 <?php echo$active?>" onclick="show_time_period('year')">Per Year</button>
                       
                            <?php $active='';if($_SESSION['temp']['hazrep']['time_period']=='month'){$active='active';}?>
                            <button class="btn btn-default  mtb-2 <?php echo$active?>" onclick="show_time_period('month')">Per Month</button>
                       
                            <?php $active='';if($_SESSION['temp']['hazrep']['time_period']=='week'){$active='active';}?>
                            <button class="btn btn-default  mtb-2 <?php echo$active?>" onclick="show_time_period('week')">Per Week</button>
                       
                            <?php $active='';if($_SESSION['temp']['hazrep']['time_period']=='weekday'){$active='active';}?>
                            <button class="btn btn-default  mtb-2 <?php echo$active?>" onclick="show_time_period('weekday')">Per Weekday</button>
                        
                        
                        <form method="POST" id="time_period">
                            <input type="hidden" name="view" value="show_dashboard">
                            <input type="hidden" name="change_time_period" value="yes">
                            <input type="hidden" id="the_period" name="time_period" value="">
                        </form>
                    </div>
                    <div class="chart-type">
                        
                            <?php $active='';if($_SESSION['temp']['hazrep']['chart_type']=='Column'){$active='active';}?>
                            <button class="btn btn-default  mtb-2 <?php echo$active?>" onclick="show_chart_type('Column')">Column Chart</button>
                       
                            <?php $active='';if($_SESSION['temp']['hazrep']['chart_type']=='Line'){$active='active';}?>
                            <button class="btn btn-default  mtb-2 <?php echo$active?>" onclick="show_chart_type('Line')">Line Chart</button>
                       
                        
                        <form method="POST" id="chart_type">
                            <input type="hidden" name="view" value="show_dashboard">
                            <input type="hidden" name="change_chart_type" value="yes">
                            <input type="hidden" id="the_chart_type" name="chart_type" value="">
                        </form>
                    </div>
                <?php }else{?>
                    <div  style="width: 100%; height: 50vh;text-align:center"><br><br><br><br><br><br><br>No Reports</div>
                <?php }?>
            </div>            
        </div>
        <?php 
    }
    /** Show Overall Stats of the HazRep Dashboard
     * 
     */
    public static function show_overall_stats(){
        $stats=HazRep::reformat_data_dashboard(HazRep::get_data_dashboard())['stats']?>
        <div class="row stats-title">Overall Stats</div>
        <div class="row">
            <?php HazRepController::show_stats_card('Days since last reports',$stats['day_since_last'])?>
            <?php HazRepController::show_stats_card('Total reports',$stats['total_reports'])?>
        </div>
        
        <div class="row">
            <?php HazRepController::show_stats_card('Reports closed',$stats['total_reports_closed'])?>
            <?php HazRepController::show_stats_card('Reports still open',$stats['total_reports_opened'])?>
        </div>
        
        <?php 
    }
    /** Show inidividuall stats of the HazRep Dashboard
     * 
     */
    public static function show_stats_card($caption,$stats){?>
        <div class="col-md-6 text-center p-3" style="padding:10px">
            <div class="stats-card "><!-- border-pls -->
                <div class="row stats-header"><?php echo $caption?></div>
                <div class="row stats-value"><?php echo $stats?><br></div>
            </div>   
        </div>
        
        <?php
    }


    


}


class HazRep_Comment {
    /** Get all Comment Report 
     * @return array array of all the comments
     */
    public static function get_all($hazrep_id){     
        $db=$GLOBALS['db'];
        $query="SELECT *
        FROM hazrep_comment
        WHERE comment_hazrep_id='$hazrep_id'
        order by comment_datetime desc";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        
        $comments=$sql->fetchall();
        return $comments;
    }

    /** Delete a comment
     * 
     */
    public static function delete($comment_id){     
        $db=$GLOBALS['db'];
        $query="Delete FROM hazrep_comment
        WHERE comment_id='$comment_id'";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        
    }
    /** Add a new comment
     * 
     */
    public static function create_new(){     
        $db=$GLOBALS['db'];
        $comment_datetime=date('Y-m-d G:i:s');
        $comment_member=$_SESSION['temp']['id'];
        $comment_hazrep_id=$_POST['hazrep_id'];
        $comment_entry=$_POST['comment_entry'];
        $query="INSERT INTO hazrep_comment(
            comment_hazrep_id,
            comment_entry,
            comment_datetime,
            comment_member
            ) values(
                '$comment_hazrep_id',
                '$comment_entry',
                '$comment_datetime',
                '$comment_member'
                )";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        
    }
    
}
class HazRep_CommentController{
    /** Show all comments
     * 
     */
    public static function index($hazrep_id){

        $comments=HazRep_Comment::get_all($hazrep_id);
        foreach($comments as $comment){?>
            <div class="row">
                <div class="col-xs-3"><?php echo date('jS M G:i',strtotime($comment['comment_datetime']))?></div>
                <div class="col-xs-3"><?php echo $comment['comment_member']?></div>
                <div class="col-xs-5"><?php echo $comment['comment_entry']?></div>
                    <div class="col-xs-1">
                        <?php if($comment['comment_member']==$_SESSION['temp']['id']){?>
                            <button type="submit" name="action" value="delete_comment<?php echo $comment['comment_id']?>" class="form-control">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                        <?php }?>                        
                    </div>
            </div>
            <?php
        }
        if(!empty($_SESSION['temp']['id'])){
            HazRep_CommentController::create($hazrep_id);
        }
        
    }
    /** Show create comments form
     * 
     */
    public static function create($hazrep_id){?>
        <div class="row">
            <div class="col-xs-3"></div>
            <div class="col-xs-8"><input type="text" name="comment_entry" class="form-control" placeholder="Add a comment"></div>
            <div class="col-xs-1"><button type="submit" name="action" value="add_comment" class="form-control"><span class="glyphicon glyphicon-plus"></span></button></div>
        </div>
    <?php }
}


class HazRep_Log {
    /** Get all Log from Hazrep Report
     * @param string $hazrep_id the id of the hazard report you want the logs from
     * 
     * @return array array of the logs 
     * 
     */
    public static function get_all($hazrep_id){     
        $db=$GLOBALS['db'];
        $query="SELECT *
        FROM hazrep_log
        WHERE log_hazrep_id='$hazrep_id'
        order by log_datetime desc";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        
        $logs=$sql->fetchall();
        return $logs;
    }

    /** Delete a log
     * 
     */
    public static function delete($log_id){     
        $db=$GLOBALS['db'];
        $query="Delete FROM hazrep_log
        WHERE log_id='$log_id'";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        
    }
    /** Add a new comment
     * 
     */
    public static function create_new($hazrep_id,$log_entry){     
        $db=$GLOBALS['db'];
        $log_datetime=date('Y-m-d G:i:s');
        $log_member=$_SESSION['temp']['id'];
        $log_hazrep_id=$hazrep_id;
        $query="INSERT INTO hazrep_log(
            log_hazrep_id,
            log_entry,
            log_datetime,
            log_member
            ) values(
                '$log_hazrep_id',
                '$log_entry',
                '$log_datetime',
                '$log_member'
                )";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        
    }
    
}
class HazRep_LogController{
    /** Show all logs
     * @param string $hazrep_id id of the Hazard Report
     * 
     */
    public static function index($hazrep_id){
        $logs=HazRep_Log::get_all($hazrep_id);?>
        <div class="line-log">
			<div class="log-header" onclick="toggle_log()">Log</div>
			<div class="log-content" id="thelog" style="display: none;">
            <?php foreach($logs as $log){?>
                <div class="row">
                    <div class="col-xs-2"><?php echo date('jS M G:i:s',strtotime($log['log_datetime'])) ?></div>
                    <div class="col-xs-1"><?php echo $log['log_member']?></div>
                    <div class="col-xs-8" style="text-align:left"><?php echo $log['log_entry']?></div>
                </div>
            <?php }?>
                
                
					
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
            <?php
        
        
    }
}

class HazRep_Attachment{
    /** Get all attachment from Hazrep Report
     * @param string $hazrep_id the id of the hazard report you want the logs from
     * 
     * @return array array of the logs 
     * 
     */
    public static function get_all($hazrep_id){     
        $db=$GLOBALS['db'];
        $query="SELECT *
        FROM hazrep_attachment
        WHERE attachment_hazrep_id='$hazrep_id'
        order by attachment_date_added desc";
        $sql = $db->prepare($query); 
        $sql->execute();
        
        $attachments=$sql->fetchall();
        return $attachments;
    }
    /** Get one attachment from Hazrep Report
     * @param string $attachment_id the id of the attachment you want to get
     * 
     * @return array attachment
     * 
     */
    public static function get_one($attachment_id){     
        $db=$GLOBALS['db'];
        $query="SELECT *
        FROM hazrep_attachment
        WHERE attachment_id='$attachment_id'";
        $sql = $db->prepare($query); 
        $sql->execute();
        
        $attachment=$sql->fetch();
        return $attachment;
    }
    /** Delete an attachment
     * 
     */
    public static function delete($attachment_id){     
        $attachment=HazRep_Attachment::get_one($attachment_id);
        //delete file
        unlink( "attachment/".$attachment['attachment_name']);
        //show("attachment/".$attachment['attachment_name']);
        $db=$GLOBALS['db'];
        $query="Delete FROM hazrep_attachment
        WHERE attachment_id='$attachment_id'";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();

        
       
        
        
    }
    /** Add a new attachment
     * 
     */
    public static function create_new(){     
        $db=$GLOBALS['db'];
        $hazrep_id=$_POST['hazrep_id'];
        $attachment_date_added=date('Y-m-d G:i:s');
        $attachment_added_by=$_SESSION['temp']['id'];
        $attachment_hazrep_id=$hazrep_id;
        $attachment_caption=$_POST['attachment_caption'];
        if(empty($attachment_caption)){$attachment_caption=$_FILES['attachment_file']['name'];}
        $attachment_name=$hazrep_id."-".$_FILES['attachment_file']['name'];
        
        move_uploaded_file($_FILES["attachment_file"]["tmp_name"], 'attachment/'.$attachment_name);
        
        $query="INSERT INTO hazrep_attachment(
            attachment_hazrep_id,
            attachment_name,
            attachment_added_by,
            attachment_date_added,
            attachment_caption
            ) values(
                '$attachment_hazrep_id',
                '$attachment_name',
                '$attachment_added_by',
                '$attachment_date_added',
                '$attachment_caption'
                )";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        
    }
}
class HazRep_AttachmentController{
    /** Show all attachment link to an Hazard Report
     * @param string $hazrep_id id of the Hazard Report
     * 
     */
    public static function index($hazrep_id){
        foreach(HazRep_Attachment::get_all($hazrep_id) as $attachment){?>
            <form method="POST">
                <div class="row line-attachment">
                    <div class="col-sm-6 ">
                        <a target="blank" href="attachment/<?php echo $attachment['attachment_name']?>">
                            <img class="attachment" src="attachment/<?php echo $attachment['attachment_name']?>">
                        </a>
                        <br><?php echo $attachment['attachment_caption']?>
                    </div>
                    <div class="col-sm-5 "></div>
                    <div class="col-sm-1 last-part">
                        <br>
                        <a target="blank" href="attachment/<?php echo $attachment['attachment_name']?>">
                            <button type="button" class="btn btn-default">
                                <span class="glyphicon glyphicon-resize-full"></span>
                            </button>
                        </a>
                        <br>
                        <br>
                        <input type="hidden" name="attachment_id" value="<?php echo $attachment['attachment_id']?>">
                        <input type="hidden" name="hazrep_id" value="<?php echo $attachment['attachment_hazrep_id']?>">
                        <?php if($attachment['attachment_added_by']==$_SESSION['temp']['id'] or $_SESSION['temp']['permission_hazrep']['can delete attachment']){?>
                        <button type="submit" name="action" value="delete_attachment" class="btn btn-default">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                        <?php }?>
                    </div>
                </div>
            </form>
        <?php }
        if(!empty($_SESSION['temp']['id'])){
            HazRep_AttachmentController::create($hazrep_id);
        }
    }

    /** Show form to add attachment
     * 
     */
    public static function create($hazrep_id){?>
        <form method="POST" enctype="multipart/form-data">
            <div class="row line-attachment">
                <div class="col-sm-2 ">Add a picture/document (4Mo max)</div>
                <div class="col-sm-3 ">
                    <input class="form-control" required type="file" name="attachment_file" id="attachment_file" accept="image/*;capture=camera">
                    <input type="hidden" name="hazrep_id" value="<?php echo $hazrep_id?>">
                </div>
                <div class="col-sm-3 ">
                    <input class="form-control" type="text" name="attachment_caption" placeholder="Add a caption if needed">
                </div>
                <div class="col-sm-1 "></div>
                <div class="col-sm-1 ">
                    <button type="submit" name="action" value="upload_attachment" class="btn btn-default">
                        <span class="glyphicon glyphicon-upload"></span> Upload
                    </button>
                </div>
                <div class="col-sm-1 "></div>
                <div class="col-sm-1 last-part"></div>
            </div>
        </form>

        <?php
    }
}

class Employee {
    
    /** Get all Employee 
     * 
     */
    public static function get_all($field='',$value=''){     
        $db=$GLOBALS['db'];
        $addon="";
        if(!empty($field)){$addon="Where $field='$value'";}
         $query="SELECT *
        FROM employee 
        $addon
        order by employee_fullname asc";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        
        $employees=$sql->fetchall();
        return $employees;
    }

    /** Get one Employee 
     * 
     */
    public static function get_one($field,$value){     
        $db=$GLOBALS['db'];
         $query="SELECT *
        FROM employee 
        Where $field='$value'
        order by employee_fullname asc";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        
        $employee=$sql->fetch();
        return $employee;
    }

    /** Get all Employee 
     * 
     */
    public static function get_all_from_group($group_name){     
        $db=$GLOBALS['db'];
        $query="SELECT  *
        FROM employee_group_allocation
        left join employee on employee_code=groupallocation_employee
        left join employee_group on group_id=groupallocation_groupid
        Where group_name='$group_name'";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        
        $employees=$sql->fetchall();
        return $employees;
    }

    
    
    
}

?>




