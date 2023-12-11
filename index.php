<?php 
    ob_start();
    session_start();
    $pageTitle = 'Home page';
    include 'init.php';

    if (isset($_SESSION['User'])){

?>

<div class="container mx-auto py-8">
    <h1 class="text-[#6A64F1] text-4xl font-bold mb-4 text-left">
        Welcome, <?= $_SESSION['User']; ?>
    </h1>

    <?php
    $status = checkUserStatus($_SESSION['User']);

    if ($status == 1) : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Alert:</strong>
            <span class="block sm:inline">Your Membership Needs Activation. Please take action.</span>
        </div>
    <?php endif; ?>
</div>


<?php
    include $tpl . "footer.php";
    }
    ob_end_flush();
?>