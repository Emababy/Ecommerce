<?php 
    ob_start();
    session_start();
    include 'init.php';
?>

        <h1 class="text-center md:text-3xl sm:text-xl text-indigo-800">
            <?php echo str_replace('-' , ' ' , $_GET["PageName"]) ?>
        </h1>


        <div class="container mx-auto flex flex-wrap items-center justify-center my-5 text-center">
        <?php
                    $stmt = $con->prepare("SELECT
                        items.*, 
                        categories.Name AS Cat_Name, 
                        users.Username AS Member_Name 
                    FROM
                        items 
                    INNER JOIN
                        categories ON categories.ID = items.Cat_ID 
                    INNER JOIN
                        users ON users.UserID = items.Member_ID 
                    WHERE
                        items.Cat_ID = ?");
                    
                    // Bind the parameter
                    $stmt->execute(array($_GET["PageID"]));

                    // Assign to vars:
                    $items = $stmt->fetchAll();

                    foreach ($items as $item) { ?>
                        <div class="max-w-sm rounded overflow-hidden shadow-lg mx-2 my-2 ">

                            <!-- Image (if you have one) -->
                            <!-- <img class="w-full" src="<?php echo $item['Image']; ?>" alt="Item Image"> -->

                            <div class="px-6 py-4">
                                <div class="font-bold text-xl mb-2"><?php echo $item['Name']; ?></div>
                                <p class="text-gray-700 text-base"><?php echo $item['Description']; ?></p>
                                <p class="text-red-600 text-base">Seller: <?php echo $item['Member_Name']; ?></p>
                                <p class="text-green-600 text-base">Price: <?php echo $item['Price']; ?></p>
                                <a href=""><button class="hover:scale-95 transition-ease duration-500 hover:shadow-form rounded-md bg-[#6A64F1] p-2 my-2 text-base font-semibold text-white outline-none">Buy Now</button></a>
                            </div>
                        </div>
            <?php } ?>
        </div>

<?php
    include $tpl . "footer.php";
    ob_end_flush();
?>

