<?php 
    session_start();

    if (isset($_SESSION['Username'])){

        $pageTitle = 'Dashboard';
        include "init.php";

        // start dashboard page :

        ?>
            <div class="container home-stats text-center">
                <h1 class="text-xl sm:text-2xl md:text-3xl text-indigo-800 mb-10">Dashboard</h1>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 md:gap-4 lg:grid-cols-4 lg:gap-6 md:p-4 rounded-md">
                    <!-- Total Members Stat -->
                    <div class="px-2 mb-4 md:mb-4">
                        <div class="stat rounded-md bg-gradient-to-tr from-blue-600 to-blue-400">
                            <i class="fa-solid fa-users mx-2 text-4xl"></i>
                            <span class="text-xl sm:text-2xl md:text-3xl lg:text-5xl">
                                <a href="members.php"><?php echo CountThings('UserID','users') ?></a>
                            </span>
                        </div>
                    </div>

                    <!-- Pending Members Stat -->
                    <div class="px-2 mb-4 md:mb-4">
                        <div class="stat bg-gradient-to-tr from-pink-600 to-pink-400">
                            <i class="fa-solid fa-hourglass-start mx-2 text-4xl"></i>
                            <span class="text-xl sm:text-2xl md:text-3xl lg:text-5xl">
                                <a href="members.php?action=Manage&Page=Pending"><?php echo CheckDb("RegStatues" , "users" , 0) ?></a>
                            </span>
                        </div>
                    </div>

                    <!-- Total Items Stat -->
                    <div class="px-2 mb-4 md:mb-4">
                        <div class="stat bg-gradient-to-tr from-green-600 to-green-400">
                            <i class="fa-solid fa-paperclip mx-2 text-4xl"></i>
                            <span class="text-xl sm:text-2xl md:text-3xl lg:text-5xl">
                                <a href="Items.php"><?php echo CountThings('Item_ID','items') ?></a>
                            </span>
                        </div>
                    </div>

                    <!-- Top Comments Stat -->
                    <div class="px-2 mb-4 md:mb-4">
                        <div class="stat bg-gradient-to-tr from-purple-600 to-purple-400">
                            <i class="fa-solid fa-comment mx-2 text-4xl"></i>
                            <span class="text-xl sm:text-2xl md:text-3xl lg:text-5xl">
                            <a href="Comments.php"><?php echo CountThings('C_ID','comments') ?></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:home-stats lg:home-stats lg:container md:container xl:container xl:home-stats">
                <div class="mx-auto p-4">
                    <div class="flex flex-wrap md:mx-4 md:flex-1 md:flex-row justify-between">
                            <div class="bg-gray-800 rounded-lg border px-4 w-full mb-4 lg:mr-0  md:md:mb-4  md:w-1/2">
                                <div class="px-1 py-3 text-white border-b">
                                    <h2 class="font-bold text-lg mb-2 "><i class="fa-solid fa-users mx-2"></i>The Latest Users</h2>
                                    <div>
                                        <?php 
                                            $theLatest  = getLatest("*", "users" , "UserID" , "GroupID" != 1 , 4);
                                            foreach($theLatest as $user){ ?>
                                                <div class="flex justify-between items-center">
                                                    <h3 class ="my-3 px-5"><?php echo $user["Username"] ?></h3>
                                                    <div class ="flex justify-center items-center">
                                                            <a href="members.php?action=Edit&ID=<?php echo $user['UserID']?>">
                                                                <i class="fa-solid fa-user-pen p-3 text-green-400 hover:text-green-600 hover:font-medium cursor-pointer"></i>
                                                            </a>
                                                            <?php 
                                                            if($user['RegStatues'] == 0){?>
                                                                    <a href='members.php?action=Activate&ID=<?php echo $user['UserID'] ?>' class="p-3 text-blue-400 hover:text-blue-600 hover:font-medium cursor-pointer"><i class="fa-solid fa-check"></i></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php }
                                        ?>
                                    </div>
                                </div>
                                <div class="px-1 py-4">
                                    <a href="members.php" class="text-blue-500 hover:underline">See More</a>
                                </div>
                            </div>
                            <div class="bg-gray-800 rounded-lg border w-full mb-4 md:md:mb-4 lg:mr-0 md:w-1/2 px-4">
                                <div class="px-1 py-3 text-white border-b">
                                    <h2 class="font-bold text-lg mb-2"><i class="fa-solid fa-paperclip mx-2"></i>The Latest Items</h2>
                                    <div>
                                        <?php 
                                            $theLatest  = getLatest("*", "items"  , "Item_ID" , null , 4);
                                            foreach($theLatest as $item){ ?>
                                                <div class="flex justify-between items-center">
                                                    <h3 class ="my-3 px-5"><?php echo $item["Name"] ?></h3>
                                                    <div class ="flex justify-center items-center">
                                                            <a href="Items.php?action=Edit&ItemID=<?php echo $item['Item_ID']?>">
                                                                <span class="material-symbols-outlined p-3 text-green-400 hover:text-green-600 hover:font-medium cursor-pointer">edit</span>
                                                            </a>
                                                            <?php 
                                                            if($item['Approve'] == 0){?>
                                                                    <a href='Items.php?action=Approved&ItemID=<?php echo $item['Item_ID'] ?>' class="p-3 text-blue-400 hover:text-blue-600 hover:font-medium cursor-pointer"><i class="fa-solid fa-check"></i></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php }
                                        ?>
                                    </div>
                                </div>
                                <div class="px-1 py-4">
                                    <a href="Items.php" class="text-blue-500 hover:underline">See More</a>
                                </div>
                            </div>
                            <div class="bg-gray-800 rounded-lg border w-full md:w-1/2 px-4">
                                <div class="px-1 py-3 text-white border-b">
                                    <h2 class="font-bold text-lg mb-2"><i class="fa-solid fa-comment mx-2"></i>Latest Comments</h2>
                                    <div>
                                        <?php 
                                            $latestComments = getLatestCommentsWithItems($limit = 4);
                                            foreach($latestComments as $latestComment): ?>
                                                <div class="flex justify-between items-center py-3">
                                                    <h3 class="mx-2"><?php echo $latestComment["Comment"] ?></h3>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="text-sm"><?php echo $latestComment["Name"]; ?></span>
                                                        <a href="Comments.php?action=Edit&CID=<?php echo $latestComment['C_ID']?>">
                                                            <i class="fa-solid fa-comment-dots text-green-400 hover:text-green-600 hover:font-medium cursor-pointer"></i>
                                                        </a>
                                                        <?php 
                                                            if($latestComment['Statues'] == 0): ?>
                                                                <a href='Items.php?action=Approved&ItemID=<?php echo $latestComment['C_ID'] ?>' class="text-blue-400 hover:text-blue-600 hover:font-medium cursor-pointer">
                                                                    <i class="fa-solid fa-check"></i>
                                                                </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="px-4 py-4">
                                    <a href="Comments.php" class="text-blue-500 hover:underline">See More</a>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

        <?php

        // end dashboard page 

        // footer :
        include $tpl ."footer.php";
    }

    else{
        header('Location:index.php');
        exit();
    }

?>





