<?php

class HazRep {
    
    /** Get all Hazard Report 
     * 
     */
    public static function get_all(){     
        $db=$GLOBALS['db'];
        $query="SELECT *
        FROM hazrep 
        left join hazrep_priority on hazrep_priority=hazreppriority_id
        left join hazrep_howfound on hazrep_howfound=hazrephowfound_id
        left join hazrep_location on hazrep_location=hazreplocation_id
        left join hazrep_type on hazrep_type=hazreptype_id
        left join hazrep_category on hazrep_category=hazrepcategory_id
        left join hazrep_likelyhoodscore on hazrep_likelyhood_score=hazreplikelyhoodscore_id
        left join hazrep_consequencescore on hazrep_consequence_score=hazrepconsequencescore_id
        order by hazrep_id desc";
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
        $new_number=date('Y-m-d G:i:s');
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
            'hazrep_other_ref',
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
    /** Create a new Hazard Report 
     * 
     */
    public static function delete($hazrep_id){     
        $db=$GLOBALS['db'];
        $query="DELETE FROM hazrep WHERE hazrep_id=$hazrep_id";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();
        
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
    
}
class HazRepController{
    
    /** Manage the POST query on page HazRep
     * 
     */ 
    public static function manage_post_hazrep(){
        //if GET debug=1 show POST Array 
        show_debug();
        
        //if there is a view in the POST, show the view
        if(!empty($_POST['view'])){
            //if($_POST['view']=='show_all'){HazRepController::show_all();}
            //if($_POST['view']=='show_create'){HazRepController::show_report_details();}
            if($_POST['view']=='show_details'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}
        }

        //if there an action in the POST, do the action
        if(!empty($_POST['action'])){
            if($_POST['action']=='delete'){HazRep::delete($_POST['hazrep_id']);}
            if($_POST['action']=='save'){HazRep::update();}
            if($_POST['action']=='submit'){
                $_POST['hazrep_submittedby']=$_SESSION['temp']['id'];
                $_POST['hazrep_date_submitted']=date('Y-m-d G:i:s'); 
                $_POST['hazrep_status']='Submitted';
                HazRep::update();
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
            
            
        }
    }
    
    /** Show the navbar on page HazRep
     * 
     */
    public static function navbar_hazrep(){?>
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
                    class="btn btn-primary hazrep_button">Show All</button>
                </div>
                <div class="col-sm-2"></div>
                <div class="col-sm-2"></div>
                <div class="col-sm-2"></div>
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
    public static function general_view_hazrep(){?>
        <div class="row">
            <div class="col-md-12 main-body">
            
                <?php if($_POST['view']=='show_all'){HazRepController::show_all();}?>
                <?php if($_POST['view']=='show_create'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
                <?php if($_POST['action']=='delete'){HazRepController::show_all();}?>
                <?php if($_POST['action']=='save'){HazRepController::show_all();}?>
                <?php if($_POST['action']=='submit'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
                <?php if($_POST['action']=='unsubmit'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
                <?php if($_POST['action']=='close'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
                <?php if($_POST['action']=='reopen'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
                <?php if($_POST['action']=='add_comment'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
                <?php if(substr($_POST['action'], 0, 14)=='delete_comment'){HazRepController::show_report_details(HazRep::get_one($_POST['hazrep_id']));}?>
                <?php if(empty($_POST['view'])and empty($_POST['action'])){HazRepController::show_all();}?>
            </div>
            
            <div class="hidden-box" style="display:none"></div>
        </div>
        
        
        <?php
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
                }?>
                <?php if($hazrep['hazrep_status']=='Submitted'){?>
                    <div class="col-sm-12 text-center">Report Submitted by <?php echo $hazrep['hazrep_submittedby']?> <?php echo date('d/m/y',strtotime($hazrep['hazrep_date_submitted']))?> at <?php echo date('G:i:s',strtotime($hazrep['hazrep_date_submitted']));?></div>
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


    /** Show the line on the main view of the Hazrep
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
        <script src="/js/hazrep.js"></script>

        
        <?php
    }
    /** Show initial report form
     * 
     */
    public static function show_initial_report($hazrep){
        $protected='protected';
        if($_SESSION['temp']['permission_hazrep']['can edit initial']){$protected=0;}
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
                    date('G:i',strtotime($hazrep['hazrep_date'])),
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
                    $always_protected);?> 
                <?php HazRepController::show_select(
                    'hazrep_priority',
                    'Priority',
                    $hazrep['hazreppriority_name'],                    
                    $protected,
                    HazRep::get_all_priority(),
                    'hazreppriority_name',
                    'hazreppriority_id' );?>     
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
                    $protected);?>       
            </div>
            <div class="initial-row initial-row-5">
                <?php HazRepController::show_input(
                    'hazrep_who_was_notified',
                    'Who has been notified',
                    $hazrep['hazrep_who_was_notified'],
                    'text',
                    $protected);?> 
                <?php HazRepController::show_input(
                    'hazrep_immediate_corrective_action',
                    'Immediate Corrective Actions',
                    $hazrep['hazrep_immediate_corrective_action'],
                    'text',
                    $protected);?> 
               
            </div>            
        </div>

        
        <?php
    }
    /** Show initial report form
     * 
     */
    public static function show_middle_report($hazrep=''){
        $protected='protected';
        if($_SESSION['temp']['permission_hazrep']['can edit middle']){$protected=0;}
        
        $always_protected='protected';
        ?>
        <div class="middle-container ">
            <?php HazRepController::show_progress_bar($hazrep);?>
            <?php if($hazrep['hazrep_status']<>'Created'){?>
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
                        if($risk_score>0){?>
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
                    $protected);?>
                    
                
                </div>
                <div class="initial-row ">
                    <?php HazRepController::show_textarea(
                    'hazrep_verification',
                    'Verification',
                    $hazrep['hazrep_verification'],
                    $protected);?>
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
                        'hazrep_other_ref',
                        'Other Refs',
                        $hazrep['hazrep_other_ref'],
                        'text',
                        $protected,
                        "Enter other reference if needed");?>  
                    
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
     * 
     */
    public static function show_textarea($name,$caption,$verification,$protected=0,$rows=3,$cols=30){
        
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
                    placeholder="Describe the Hazard"><?php echo $verification;?></textarea>
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
            'can edit middle'
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
        //if the report is closed nothing can be touch
        if (($hazrep['hazrep_status']=='Closed')){
            $_SESSION['temp']['permission_hazrep']['can edit middle']=0;
            $_SESSION['temp']['permission_hazrep']['can save']=0;
            $_SESSION['temp']['permission_hazrep']['can edit']=0;
        }
        //if the report is submitted nothing can be touch
        if (($hazrep['hazrep_status']=='Submitted')){
            $_SESSION['temp']['permission_hazrep']['can edit initial']=0;
        }
        
        
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
        show($query);
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
                <div class="col-xs-2"><?php echo $comment['comment_member']?></div>
                <div class="col-xs-6"><?php echo $comment['comment_entry']?></div>
                    <div class="col-xs-1">
                        <button type="submit" name="action" value="delete_comment<?php echo $comment['comment_id']?>" class="form-control">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                    </div>
            </div>
            <?php
        }
        HazRep_CommentController::create($hazrep_id);
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
    /** Get all Attachment from Hazrep Report
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
}




?>




