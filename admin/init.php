<?php 

    // database:

    include 'connect.php';

    // Routes : 

    $tpl  =  'includes/templates/';  // template directory
    $css  =  'layout/css/';          // css directory
    $js   =  'layout/js/';           // js directory
    $func =  'includes/functions/';           // function directory

    // includes :
    
    include $func . 'functions.php';
    include $tpl . "header.php";
    if (!isset($noNavbar)){ include $tpl . "navbar.php"; }
?>