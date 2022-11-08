
var elements = document.getElementsByClassName("expand-button");
for (var i = 0; i < elements.length; i++) {
    
    elements[i].addEventListener('click', function(){load_view('show_details','main-body',this.getAttribute("data-value"))}, false);
}
/** Ajax Request to show a view in the div when click on create new button
 * @param view
 * @param div
 * 
 */
function load_view(view,div,hazrep_id=''){
    $.ajax({
        type:'POST',
        url:'hazrep-ajax.php',
        data: {view:view, hazrep_id:hazrep_id},
        success:function(html){
            $('.'+div).empty().append(html);
        }
    });
}



document.getElementById ("hazrep_consequence_score").addEventListener ("change", function(){calculate_risk()}, false);
document.getElementById ("hazrep_likelyhood_score").addEventListener ("input", function(){calculate_risk()}, false);
/** Calculate the risk and update the number on the view
 * @param view
 * @param div
 * 
 */
function calculate_risk(){
    consequencescore=document.getElementById ("hazrep_consequence_score").value;
    likelyhoodscore=document.getElementById ("hazrep_likelyhood_score").value;
    risk_score=consequencescore*likelyhoodscore;
    $('.score').empty().append(risk_score);    
    document.getElementById ("risk_score").classList.remove("green");
    document.getElementById ("risk_score").classList.remove("red");
    document.getElementById ("risk_score").classList.remove("yellow");
    color="green"
    if(risk_score>=9){color="yellow"}
    if(risk_score>=16){color="red"}
    document.getElementById ("risk_score").classList.add(color);

}






