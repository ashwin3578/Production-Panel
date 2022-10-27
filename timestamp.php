<?php 
date_default_timezone_set('Australia/Brisbane');
if (!empty($_POST['timestamp'])){
    echo 'Timestamp: '.date('Y-m-d G:i:s',$_POST['timestamp']);
}
?>
<br>
<div>
    <form method="POST">
        <input name="timestamp">
        <input type="submit">
    </form>
</div>