<?php 
    ob_start();
    session_start();
    $pageTitle = "ADS";
    include 'init.php';

     // check in Item ID and make sure it numeric
    $ItemID = isset($_GET['ItemID']) && is_numeric($_GET['ItemID']) ? intval($_GET['ItemID']) : 0;

    // select all data based on Item ID
    $stmt = $con->prepare("SELECT
                                items.*, 
                                categories.Name AS category_name,
                                users.Username
                            FROM 
                                items 
                            INNER JOIN 
                                categories
                            ON
                                categories.ID = items.Cat_ID
                            INNER JOIN 
                                users
                            ON 
                                users.UserID = items.Member_ID
                            WHERE 
                                Item_ID = ? "
                        );
    
    $stmt->execute(array($ItemID));

    $count = $stmt->rowCount();

    if ( $count > 0 ){

        $item = $stmt->fetch();



?>

<div class="container mx-auto my-8 px-4">
    <div class="flex flex-col lg:flex-row justify-between items-center bg-white rounded-lg shadow-lg p-4 lg:p-8 mb-8">

        <div class="w-full lg:w-1/2 mb-4 lg:mb-0">
            <img src="layout/images/5856.jpg" alt="<?php echo $item['Name']; ?>" class="w-full h-auto rounded-lg shadow-lg">
        </div>

        <div class="w-full lg:w-1/2 mx-4 lg:mx-8">
            <h2 class="text-4xl font-bold mb-4 text-gray-800"><?php echo $item['Name']; ?></h2>
            <p class="text-gray-600 mb-6"><?php echo $item['Description']; ?></p>
            <div class="flex items-center mb-4">
                <a href="Categories.php?PageID=<?php echo $item['Cat_ID']; ?>" class="text-blue-500">
                    <span class="font-bold text-gray-800">Category:</span>
                    <span class="ml-2"><?php echo $item['category_name']; ?></span>
                </a>
            </div>
            <div class="flex items-center mb-4">
                <span class="font-bold text-gray-800">Added:</span>
                <span class="ml-2"><?php echo $item['Add_Date'] ?></span>
            </div>
            <div class="flex items-center mb-4">
                <span class="font-bold text-gray-800">Price:</span>
                <span class="ml-2 text-green-500"><?php echo $item['Price']; ?></span>
            </div>
            <div class="flex items-center mb-4">
                <span class="font-bold text-gray-800">Made in:</span>
                <span class="ml-2"><?php echo $item['Country']; ?></span>
            </div>
            <div class="flex items-center mb-4">
                <span class="font-bold text-gray-800">Added By:</span>
                <a href="Profile.php?MemberID=" class="text-blue-500 ml-2"><?php echo $item['Username']; ?></a>
            </div>
            <button class="hover:scale-95 transition-ease duration-500 rounded-md bg-[#6A64F1] py-3 px-8 text-lg font-semibold text-white outline-none">
                Add to Cart
            </button>
        </div>
    </div>

    <div class="flex items-center justify-center shadow-lg my-8 mx-4 lg:mx-8 mb-4 max-w-lg">
        <form class="w-full max-w-xl bg-white rounded-lg px-4 pt-2">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-2/3 md:px-3 mb-2 mt-2 flex items-center">
                    <img src="layout/images/woman-face-expression-clipart-design-illustration-free-png.webp" alt="User Profile Image" class="md:w-12 md:h-12 rounded-full shadow-lg mr-2 hidden">
                    <textarea class="bg-gray-100 rounded border border-gray-400 leading-normal resize-none w-full h-20 py-2 px-3 mx-3 font-medium placeholder-gray-700 focus:outline-none focus:bg-white" name="body" placeholder='Type Your Comment' required></textarea>
                </div>
                <div class="w-full flex justify-start">
                    <button type="submit" class="hover:scale-95 transition-ease duration-500 hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-sm font-semibold text-white outline-none">
                        Comment
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>


<?php

} else { ?>
    <script>
        window.onload = function() {
            <?php if (!$count > 0): ?>
                swal({
                    title: "Error",
                    text: "There's No Such ID Like That!",
                    icon: "error",
                }).then(() => {
                    window.location.href = 'index.php';
                });
            <?php endif; ?>
        };
    </script>

<?php }

include $tpl . "footer.php";
ob_end_flush();
?>
