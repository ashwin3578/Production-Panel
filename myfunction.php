<?php 
function show_navbar(){
    ?>

    <!-- Construction of Navbar -->
    <nav class="navbar navbar-default">
        <div class= "container-fluid  ">
            <a class="navbar-brand" href="index.php">Home</a>
            <a class="navbar-brand" href="http://192.168.1.30/index.php">Main Page</a>
            <ul class="nav navbar-nav">
           
            <?php button_navbar("chat.php","CHAT")?>
            <?php button_navbar("list.php","LIST")?>
                
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <?php button_navbar("Types.php","TYPES")?>
                    <?php button_navbar("Heading.php","HEADING")?>
                    <?php button_navbar("Materials.php","TEST")?>
                </ul>
            </li>
            

            <?php if(!empty($_SESSION['admin'])){?>
                <?php button_navbar("Admin.php","ADMIN")?>
                
            <?php
            }?>
            <?php if(!empty($_SESSION['id'])){?>
            <p class="navbar-text navbar-right">Signed in as <?php echo $_SESSION['user']?>  </p>
            <?php
            }?>
            <?php if(empty($_SESSION['id'])){?>
                <?php button_navbar("login.php","Login")?>
                
            <?php
            }else{?>
                <?php button_navbar("login.php","Logout")?>
                
            <?php
            }?>
            </ul>
            
        </div>
    </nav>
    
    <?php
}

function button_navbar($link,$caption){
    //PHP_SELF
    if($_SERVER['PHP_SELF']=="/Programs/".$link or $_SERVER['PHP_SELF']=="/tom/".$link){
        $class="active";
    }
    ?>
    <li class="<?php echo $class?>"><a href="<?php echo $link?>"><?php echo $caption?></a></li>
    <?php
}
function show($array){
    ?>
    <pre style="background:white;text-align:left;font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;">
    <?php print_r($array)?>
    <style>
    pre {
        padding-left: 5%;
    }
    </style>
    </pre>
    <?php
}
function redirect_if_not_login(){
    if (empty($_SESSION)){
        header("Location: http://www.google.com/"); 
    }
    else {
        header("Location: http://localhost/Programs/SITE1.php");
    }
        
}
function show_alert($message,$type){?>
    <div class="alert <?php echo $type?>"><?php echo $message?></div>
    <style>
        .alert{width:100%;}
        .problem{background-color: red;}
        .good{background-color: green;}
    </style>
    <?php
}
function show_bolt($bolt){
    ?>
    <form method="POST" >
    
    <button class="bolt">
    <div class="bolt_code">
            <?php echo $bolt['bolt_code']?>
        </div>
        <!-- <div class="bolt_material">
            <//?php echo $entry['bolt_material']?>
        </div>
        <div class="bolt_machine">
            <//?php echo $entry['bolt_machine']?>
        </div>
        <div class="bolt_stages" hidden>
            <//?php echo $entry['bolt_stages']?>
        </div>
        <div class="bolt_postp">
            <//?php echo $entry['bolt_postp']?>
        </div>-->
    </button>
    
    <input type="hidden" name="bolt_id" value="<?php echo $bolt['bolt_id']?>">
    <input type="hidden" name="how_i_get_there" value="Came from Types"> 

    </form>

    <?php
}
function show_list(){
    $db=$GLOBALS['db'];
    $query="SELECT * FROM tom_bolts";
    $sql=$db->prepare($query);
    $sql->execute();
    $allentries=$sql->fetchall();
    //show($allentries);

    foreach($allentries as $bolt){
    //show($entry);
        show_bolt($bolt);
    }
}
function show_details(){
    $db=$GLOBALS['db'];
    $query="SELECT * FROM tom_bolts WHERE bolt_id=".$_POST['bolt_id'];
    //show($query);
    $sql=$db->prepare($query);
    $sql->execute();
    $entry=$sql->fetch(); //fetchall if you want to get several entries
    //show($allentries);
    //show($allentries[0]['user_firstname']);?>
    
    <div class="bolt_header">Type Details</div>
    <div class="bolt_details">
        <?php echo $entry['bolt_code']?>
        <?php echo $entry['bolt_material']?>
        <?php echo $entry['bolt_machine']?>
        <?php echo $entry['bolt_stages']?>
        <?php echo $entry['bolt_postp']?>
    </div>
    <style>
         .bolt_info{
             background: white;/*rgb(34, 35, 36);*/
             width: 100%;
             height: 60%;
             border-radius:20px;
             border: 2px solid blue;
             text-align: center;
             margin-top: 1%;
             margin-right: auto;
             margin-left: auto;
         }
         .bolt_data {
             position: relative;
             border: 2px solid black;
             margin-top: 15%;
         }
         .bolt_header {
             font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
             font-size: 50px;
             color: black;
             position: relative;
             margin-top: 0%;
             margin-bottom: 1%;
         }
     </style>

    

    <?php
}

function show_dep_list(){
    $db=$GLOBALS['db'];
    $query="SELECT [department_name] FROM [barcode].[dbo].[tom_department]";
    $sql=$db->prepare($query);
    $sql->execute();
    $alldeps=$sql->fetchall();
    //show($alldeps);

    foreach($alldeps as $dep){
        if($dep['department_name']==$_POST['department_name']){
            $color="btn-primary";
        }else{
            $color="btn-default";
        }
        
        ?>
        <form method="POST" >
            <button class=" btn <?php echo $color?> dep-button_v2" name="department_name" value="<?php echo $dep['department_name']?>">
                <div class="dep_name">
                    <?php echo $dep['department_name']?>
                </div>
            </button>
        </form>
        
        <?php
    }?>
    <style>
        .dep-button_v2{
            width: 100%;
            margin-top:1%;
            border-radius:10px;
        }
        .dep-button {
            width: 100%;
            height: 3.2vw;
            font-size: 1vw;
            border-radius: 5px;
            font-weight: bolder;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            
            background-color: navajowhite;
            box-shadow: inset 2px 2px 3px rgba(255, 255, 255, .6),
                         inset -2px -2px 3px rgba(0, 0, 0, .6);

        }
    </style>
    <?php
}


function show_time_stamp(){
    $timestamp = date('Y-m-d H:i:s');
    show($timestamp);
}
?>