<?php 

    // Start The Session :

    session_start();

    session_unset(); // unset the session

    session_destroy(); // destroy the session

    header('Location: index.php');

    exit();
    
?>