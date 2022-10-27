<?php

function show_range(){
    $firstcards=['A','K','Q','J','T','9','8','7','6','5','4','3','2'];
    $secondcards=['A','K','Q','J','T','9','8','7','6','5','4','3','2'];
    echo'<div ></div>';
    $i=0;
    foreach($firstcards as $firstcard){
        echo'<div class="row">';
        $j=0;
        foreach($secondcards as $secondcard){
            //echo'<div >';
            
            if($j==$i){
                $suited='';
                $combo= $firstcard.$secondcard;
                
            }elseif($j>$i){
                $suited='s';
                $combo= $firstcard.$secondcard.$suited;
                
            }else{
                $suited='o';
                $combo= $secondcard.$firstcard.$suited;
                
            }

            echo '<div class="combo" id="'.$combo.'">'.$combo;
            echo'</div>';
            //echo'</div>';
            $j++;
        }
        $i++;
        echo'</div>';
    }
   
}

function Hero_position(){
    $positions=['UTG','UTG1','MP','HJ','CO','BU','SB','BB'];
    echo'<div class="row">';
    foreach($positions as $position){
        echo'<div class="position" id="'.$position.'">';
        echo $position;
        echo'</div>';
        
    }
    echo'</div>';
   
}

function Stack(){
    $stacks=['100BB','50BB','30BB','20BB','15BB','10BB'];
    echo'<div class="row">';
    foreach($stacks as $stack){
        echo'<div class="stack" id="'.$stack.'">';
        echo $stack;
        echo'</div>';
        
    }
    echo'</div>';
   
}



?>