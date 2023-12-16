<?php 
    session_start();
    $pageTitle = 'Profile';
    include 'init.php';
    if(isset($_SESSION['User'])){

        $getUserStmt = $con->prepare("SELECT * FROM users WHERE Username = ?");

        $getUserStmt->execute(array($sessionUser));

        $info = $getUserStmt->fetch();

?>

<h1 class="text-[#6A64F1] text-4xl font-bold mb-4 text-center">My Profile</h1>

<div class="container mx-auto p-3 bg-gray-100 rounded-lg shadow-lg">
    <!-- User Information Section -->
    <div class="flex flex-col md:flex-row items-center mb-8">
        <div class="mb-4 md:mb-0 md:p-4 bg-blue-500 text-white rounded-l-lg p-2">
            <i class="fas fa-info-circle"></i> Information
        </div>
        <div class="md:ml-4 md:mr-4 mt-4 md:mt-0">
            <img src="./layout/images/woman-face-expression-clipart-design-illustration-free-png.webp" alt="User Image" class="h-12 w-12 rounded-full">
        </div>
        <div class="flex flex-col mt-4 md:mt-0 sm:w-full md:w-auto text-sm">
            <h3 class="text-lg font-semibold">
                Name: <?php echo $info['Username']; ?>
            </h3>
            <h3 class="text-gray-600">
                <?php echo $info['Email'] ?>
            </h3>
            <h3 class="text-gray-600">
                <?php echo $info['FullName'] ?>
            </h3>
            <h3 class="text-gray-600">
                Register Date: <?php echo $info['Date'] ?>
            </h3>
        </div>
    </div>

    <!-- My Items Section -->
    <div class="my-3 p-3 bg-blue-500 text-white rounded-lg">
        <h2 class="text-lg font-semibold mb-2">My Items</h2>
        <div class="flex flex-wrap gap-4 justify-center">
            <?php 
                if (!empty(getItem('Member_ID', $info['UserID']))){

                    foreach (getItem('Member_ID', $info['UserID']) as $item): ?>
                        <div class="max-w-sm rounded overflow-hidden shadow-lg bg-gray-800 cursor-pointer">
                            <a href="ShowAds.php?ItemID=<?php echo $item['Item_ID'] ?>">
                                <img class="w-full h-3/4" src="layout/images/5856.jpg" alt="Item Image">

                                <div class="px-6 py-4">
                                    <div class="font-bold text-xl mb-2 text-white"><?php echo $item['Name']; ?></div>
                                    <p class="text-green-600 text-base font-semibold">Price: <?php echo $item['Price']; ?></p>
                                </div>
                            </a>
                        </div>
                        
                <?php endforeach; 
                    echo '<a href="Items.php" class="text-gray-800">Add New Item</a>';
                } else { ?>
                    <p class="text-black mx-auto px-2">No Items to show.</p>
                    <a href="Items.php">Add New Item</a>
                <?php }?>
        </div>
    </div>



    <!-- Latest Comments Section -->
    <div class="my-3 p-3 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-semibold mb-2">Latest Comments</h2>

        <?php

            $comments = getCommentsByUserID($info['UserID']);

        if (!empty($comments)) :
            foreach ($comments as $comment) : ?>
                <div class="border-t border-gray-200 p-3">
                    <p class="text-gray-700"><?php echo $comment['Comment']; ?></p>
                </div>
            <?php endforeach;
        else : ?>
            <p class="text-gray-500 mx-auto px-2">No comments to show.</p>
        <?php endif; ?>

    </div>


</div>

<?php

    } else {
        header("Location: login.php");
        exit();
    }

    include $tpl . "footer.php";
?>