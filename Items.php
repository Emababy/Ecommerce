<?php 
    ob_start();
    include 'init.php';

?>

<?php
    include $tpl . "footer.php";
    ob_end_flush();
?>