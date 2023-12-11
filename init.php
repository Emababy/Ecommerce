<?php 
    // database:

    include 'admin/connect.php';

    $sessionUser = '';
    if(isset($_SESSION['User'])){
        $sessionUser = $_SESSION['User'];
    }

    // Routes : 

    $tpl  =  'includes/templates/';             // template directory
    $css  =  'layout/css/';                     // css directory
    $js   =  'layout/js/';                      // js directory
    $func =  'includes/functions/';           // function directory

    // includes :
    
    include $func . 'functions.php';
    include $tpl . "header.php";
?>