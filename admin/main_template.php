<?php 

    ob_start();

    session_start();
    $pageTitle = '';

    if(isset($_SESSION['Username'])){
        include 'init.php';

        $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

        // check on the action request 
    
        if ($action == 'Manage'){
    
        } elseif ($action == 'Add'){
    
        } elseif ($action == 'Delete'){
    
        } elseif ($action == 'Edit'){
    
        } elseif ($action == 'Insert'){
    
        } elseif ($action == 'Update'){
    
        }
            include $tpl . 'footer.php';
        } else {
            header('Location:index.php');
            exit();
    }

    ob_end_flush();

?>