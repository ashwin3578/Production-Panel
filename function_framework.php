<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use chillerlan\QRCode\{QRCode, QROptions};
require 'composer/vendor/autoload.php';

function update_div($divid,$newclass='dont',$newcontent='dont'){
		//if /remove/ we remove the class
        if($newcontent<>'dont'){echo'<script>document.getElementById("'.$divid.'").innerHTML = "'.$newcontent.'";</script>';}
        if($newclass<>'dont'){echo'<script>document.getElementById("'.$divid.'").className = "'.$newclass.'";</script>';}
		
		
}


function ajax_button($name,$data,$target,$classtarget,$option='empty().append(html)'){
    //Example:
    // $name='testfunction';
    // $data[]=['Field1',1111];
    // $data[]=['Field2',2222];
    // $data[]=['Field3',3333];
    // $target='test_ajax.php';
    // $classtarget='postinfo';

    echo'<script>
    function '.$name.'(){
        var request =$.ajax({
            type:\'POST\',
            url:\''.$target.'\',
            data: {';
                foreach($data as $field){
                    echo $field[0].': '.$field[1].', ';
                }
                

            echo'},
            success:function(html){
                $(\'.'.$classtarget.'\').'.$option.';
            }
        });
    }
    </script>';

    //echo'$.ajax({type:\'POST\',url:\'roster_ajax.php\',data: {day: theday,operator: theoperator,Notes: notes_value},success:function(html){$(\'.postinfo\').append(html);}});';
}

function ajax_button_v2($id,$data,$target,$classtarget,$option='empty().append(html)'){
    //Example:
    // $id='id of the div for the onclick';
    // $data[]=['Field1',1111];
    // $data[]=['Field2',2222];
    // $data[]=['Field3',3333];
    // $target='test_ajax.php';
    // $classtarget='postinfo';

    echo'<script>
    document.getElementById ("'.$id.'").addEventListener ("click", '.$id.', false);

    function '.$id.'(){
        var request =$.ajax({
            type:\'POST\',
            url:\''.$target.'\',
            data: {';
                foreach($data as $field){
                    echo $field[0].': '.$field[1].', ';
                }
                

            echo'},
            success:function(html){
                $(\'.'.$classtarget.'\').'.$option.';
            }
        });
    }
    </script>';

    //echo'$.ajax({type:\'POST\',url:\'roster_ajax.php\',data: {day: theday,operator: theoperator,Notes: notes_value},success:function(html){$(\'.postinfo\').append(html);}});';
}

function ajax_load($data,$target,$classtarget,$option='empty().append(html)'){
    //Example:
    // $name='testfunction';
    // $data[]=['Field1',1111];
    // $data[]=['Field2',2222];
    // $data[]=['Field3',3333];
    // $target='test_ajax.php';
    // $classtarget='postinfo';

    echo'<script>
    
    var request =$.ajax({
            type:\'POST\',
            url:\''.$target.'\',
            data: {';
                foreach($data as $field){
                    echo $field[0].': '.$field[1].', ';
                }
                

            echo'},
            success:function(html){
                $(\'.'.$classtarget.'\').'.$option.';
            }
        });
    
    </script>';

    //echo'$.ajax({type:\'POST\',url:\'roster_ajax.php\',data: {day: theday,operator: theoperator,Notes: notes_value},success:function(html){$(\'.postinfo\').append(html);}});';
}

function hide_div($id){
    echo'<script>
    var x = document.getElementById("'.$id.'");
    x.style.display = "none";
    </script>';
}

function show_div($id){
    echo'<script>
    var x = document.getElementById("'.$id.'");
    x.style.display = "block";
    </script>';
}

function show_debug(){
    if(!empty($_GET['debug'])){
        $_SESSION['temp']['debug']=$_GET['debug'];
    }
    
    if($_SESSION['temp']['debug']=='1'){
        show($_POST);
    }
}

function print_barcode($barcode){
	
	

		// This will output the barcode as HTML output to display in the browser
		$generator = new Picqer\Barcode\BarcodeGeneratorSVG();
		echo'<center>';
		echo $generator->getBarcode($barcode, $generator::TYPE_CODE_128,3, 100);
		echo'</center>';
}

function print_QRcode($data,$size=''){
	// quick and simple:
    
    if(empty($size)){
        $option='';
    }else{
        $sizes=explode("x", $size);
        $width=$sizes[0];
        $height=$sizes[0];
        $option='width="'.$width.'" height="'.$height.'"';
    }
	echo '<img src="'.(new QRCode)->render($data).'" '.$option.'alt="QR Code" />';
    //show('test');
}

function info_button($id,$caption,$optioncss=''){
    ?>
        <span class='glyphicon glyphicon-info-sign infobutton'>
            <div class="infodiv<?php echo $id?>">
                <?php echo $caption?>
            </div>
        </span>
        <style>
            .infodiv<?php echo $id?>{
            display: none;
            
            
            border:1px solid black;
            font-family: Arial, Helvetica, sans-serif;
            position: absolute;
            
            text-align: left;
            background-color: #FFFFFF;
            padding: 1.5rem;
            box-shadow: 0 2px 5px 0 rgb(0 0 0 / 60%);
            
            top: 1rem;
            width: 30rem;
            left: -30rem;
            font-size: 12px;
            z-index: 100;
            <?php echo $optioncss?>

        }
        .infobutton:hover .infodiv<?php echo $id?> {
            display: block;
            
        }
        </style>
    <?php
}

function show_signature_box($id){ ?>
    <textarea id="signature64" name="<?php echo $id?>" style="display: none"></textarea>
    <script>
    $(function() {
        var sig = $('#sig').signature({guideline: true,syncField: '#signature64', syncFormat: 'PNG'});
        
        $('#clear').click(function() {
        sig.signature('clear');
        $("#signature64").val('');
        });
        
    });
    </script>
    <div id="sig"></div>
    <div id="clear" class="btn btn-primary" >Clear</div> 
    <style>
    .kbw-signature { width: 100%; min-height: 200px; }
    </style>
    <?php
}

function convert($size){
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

function refresh_div_once($divid){?>
    <script>$("#<?php echo$divid?>").load(window.location.href + " #<?php echo$divid?>" );</script>
    <?php
}
function clean_GET($address){?>
	<script>
		window.history.pushState({}, document.title, "/" + "<?php echo$address?>");
	</script>
	<?php
}

?>