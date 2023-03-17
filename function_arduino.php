<?php 
;

class ArduinoManager{
    public static function get_last_cycles(){
        $db=$GLOBALS['db'];
        $query="Select machine_name,
        machineallocation_MAC as MAC,
        last_date,
		last_timetag
        FROM dbo.machine
        left join (
            SELECT machinecycle_machineid,
            max([machinecycle_date])as last_date 
            FROM [barcode].[dbo].[machine_cycle]
            
            group by machinecycle_machineid,
            machinecycle_machineid) as temp on temp.machinecycle_machineid=machine_id
            
        LEFt JOIN dbo.machine_allocation on machine_id=machineallocation_machineid
        left join (SELECT temptable_MAC,
            max(temptable_timetag)as last_timetag
            FROM [barcode].[dbo].[temptable_v2]
            
            group by temptable_MAC) as temp2
            on temp2.temptable_MAC=machineallocation_MAC 
        where last_date <>'' OR last_timetag<>''
        
        order by last_date desc";
        $sql = $db->prepare($query); 
        //show($query);
        $sql->execute();        
        $last_cycles=$sql->fetchall();
        return $last_cycles;
    }
}

class ArduinoMangerController{
    public static function show_main(){?>
    <div class="row">
        <div class="col-sm-10">
            
            <div class="row">
                    <div class="col-sm-3"><h2>Machine</h2></div>
                    <div class="col-sm-3"><h2>MAC</h2></div>
                    <div class="col-sm-3"><h2>Last Cycle</h2></div>
                    <div class="col-sm-3"><h2>Last Import</h2></div>
                </div>
            <?php foreach(ArduinoManager::get_last_cycles() as $last_cycles){?>
                <div class="row">
                    <div class="col-sm-3"><?php echo $last_cycles['machine_name']?></div>
                    <div class="col-sm-3"><?php echo $last_cycles['MAC']?></div>
                    <div class="col-sm-3"><?php echo $last_cycles['last_date']?></div>
                    <div class="col-sm-3"><?php echo date('Y-m-d',$last_cycles['last_timetag'])?></div>
                </div>
            <?php }?>
        </div>
        <div class="col-sm-3"></div>
        <div class="col-sm-3"></div>
        <div class="col-sm-3"></div>
    </div>

    <?php }

    
    
}

?>