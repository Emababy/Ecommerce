<?php 
    ob_start();
    session_start();
    $pageTitle = "ADS";
    include 'init.php';

     // check in Item ID and make sure it numeric
    $ItemID = isset($_GET['ItemID']) && is_numeric($_GET['ItemID']) ? intval($_GET['ItemID']) : 0;

    // select all data based on Item ID
    $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
    
    $stmt->execute(array($ItemID));
    $item = $stmt->fetch();

?>

<div class="container">
    <h1 class="text-[#6A64F1] text-4xl font-bold mb-4 text-left"><?php echo $item['Name'] ?></h1>
</div>



<?php

    include $tpl . "footer.php";
    ob_end_flush();
?>