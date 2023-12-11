<?php 

    ob_start();

    session_start();
    $pageTitle = 'Comments';

    if(isset($_SESSION['Username'])){
        include 'init.php';

        $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

        // check on the action request 
    
        if ($action == 'Manage'){

            // select all Comments :
            $stmt = $con->prepare("SELECT 
                                            comments.*, 
                                            items.Name AS ItemName,
                                            users.Username AS UserName
                                    FROM 
                                            comments
                                    INNER JOIN 
                                            items 
                                    ON 
                                            items.Item_ID = comments.Items_ID
                                    INNER JOIN
                                            users 
                                    ON 
                                            users.UserID = comments.User_ID
                                ");
            $stmt->execute();

            // Assign to vars :
            $rows = $stmt->fetchAll();
        ?>
                    <h1 class="text-center md:text-3xl sm:text-xl text-indigo-800">Manage Comments</h1>
                    <!-- component -->
                        <div class="container flex items-center justify-center">
                                <table class="w-full flex flex-row flex-no-wrap sm:bg-white rounded-lg overflow-hidden sm:shadow-lg my-5">
                                <thead class="hidden md:table-header-group">
                                    <tr class="border border-grey-800">
                                        <th class="bg-gray-800 p-2 text-white font-bold border md:border-grey-500">Comment</th>
                                        <th class="bg-gray-800 p-2 text-white font-bold border md:border-grey-500">Item Name</th>
                                        <th class="bg-gray-800 p-2 text-white font-bold border md:border-grey-500">Username</th>
                                        <th class="bg-gray-800 p-2 text-white font-bold border md:border-grey-500">Date</th>
                                        <th class="bg-gray-800 p-2 text-white font-bold border md:border-grey-500">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="flex-1 sm-flex-none">
                                    <?php foreach ($rows as $row): ?>
                                        <tr class="flex flex-col flex-nowrap sm:table-row mb-2 sm:mb-0 text-center flex-1">
                                                <td class="border-grey-light border hover:bg-gray-100 p-3 md:p-4 text-10px md:text-base lg:text-lg xl:text-xl"><?php echo $row['Comment'] ?></td>
                                                <td class="border-grey-light border hover:bg-gray-100 p-3 md:p-4 text-10px md:text-base lg:text-lg xl:text-xl"><span class="md:hidden ">Item Name: </span><?php echo $row['ItemName'] ?></td>
                                                <td class="border-grey-light border hover:bg-gray-100 p-3 md:p-4 text-10px md:text-base lg:text-lg xl:text-xl"><span class="md:hidden">Username: </span><?php echo $row['UserName'] ?></td>
                                                <td class="border-grey-light border hover:bg-gray-100 p-3 md:p-4 text-10px md:text-base lg:text-lg xl:text-xl"><span class="md:hidden">Date: </span><?php echo $row['C_Date'] ?></td>
                                                <td class="flex flex-row border-grey-light border p-3 justify-center">
                                                    <a href='Comments.php?action=Edit&CID=<?php echo $row['C_ID'] ?>' class=" p-3 text-green-400 hover:text-green-600 hover:font-medium cursor-pointer"><i class="fa-solid fa-comment-dots"></i></a>
                                                    <a href='Comments.php?action=Delete&CID=<?php echo $row['C_ID'] ?>' class="p-3 text-red-400 hover:text-red-600 hover:font-medium cursor-pointer"><i class="fa-solid fa-comment-slash"></i></a>
                                                    <?php if($row['Statues'] == 0){?>
                                                        <a href='Comments.php?action=Approved&CID=<?php echo $row['C_ID'] ?>' class="p-3 text-blue-400 hover:text-blue-600 hover:font-medium cursor-pointer"><i class="fa-solid fa-check"></i></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

<?php

        } elseif ($action == 'Delete'){

            echo "<h1 class='text-center md:text-3xl sm:text-xl text-indigo-800'>Delete Comment</h1>";
            echo "<div class='container text-left'>";

            // check in Comment ID and make sure it numeric:
            $CID = isset($_GET['CID']) && is_numeric($_GET['CID']) ? intval($_GET['CID']) : 0;

            // Check on all data based on Comment ID
            $check = CheckDb('C_ID', 'comments' , $CID);

            if ($check > 0) {

                $stmt = $con->prepare("DELETE FROM comments WHERE C_ID = :C_ID");

                // link the id of the user to the database:
                $stmt->bindParam(":C_ID", $CID); // Use ":C_ID" with the colon

                // execute the delete 
                $stmt->execute();

                ?>
                    <script>
                        window.onload = function() {
                            <?php if ($stmt->rowCount() > 0): ?>
                                swal({
                                    title: "Success",
                                    text: "Comment Deleted",
                                    icon: "success",
                                }).then(() => {
                                    window.location.href = 'Comments.php?action=Manage';
                                });
                            <?php endif; ?>
                        };
                    </script>

<?php

        } else {
                $errorMsg = $stmt->rowCount() . ' Comment Found';
                redirect($errorMsg,4);
        }
            echo "</div>";
        } elseif ($action == 'Edit'){

            // check in Comment ID and make sure it numeric
            $CID = isset($_GET['CID']) && is_numeric($_GET['CID']) ? intval($_GET['CID']) : 0;

            // select all data based on Comment ID
            $stmt = $con->prepare("SELECT * FROM comments WHERE C_ID = ? ");
        
            $stmt->execute(array($CID));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            // check if id is already exists show the form
            if ($count > 0){ ?> 
                
                <h1 class="text-center md:text-3xl sm:text-xl text-indigo-800">Edit Comment</h1>
                <!-- component -->
                <div class="flex items-center justify-center p-12">
                    <div class="mx-auto w-full max-w-[550px]">
                        <form action="?action=Update" method="POST">
                            <!-- Comment ID -->
                            <input type="hidden" name = "CID" value = "<?php echo $CID ?>"/>
                            <!-- Comment -->
                            <div class="mb-5">
                                <label for="Comment" class="mb-3 block text-base font-medium text-[#07074D]">Comment</label>
                                <textarea name="Comment" placeholder="Write A Comment" required="required" class="w-full text-center rounded-md border border-[#e0e0e0] bg-white py-3 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
                                    <?php echo $row['Comment']; ?>
                                </textarea>
                            </div>
                                <!-- button -->
                                <button value="Save" type="submit" class="hover:scale-95 transition-ease duration-500 hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-base font-semibold text-white outline-none">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

<?php

            } else { // if there is no id like this:

                $errorMsg = "There's No Id Like This";
                redirect($errorMsg , 4);
            }

        } elseif ($action == 'Update'){

            echo "<h1 class='text-center md:text-3xl sm:text-xl text-indigo-800'>Update Comment</h1>";
            echo "<div class='container text-center'>";

            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                // get var from the form :
                $CID = $_POST['CID'];
                $Comment = $_POST['Comment'];

                    // update Comment db :
                    $stmt = $con->prepare("UPDATE comments SET Comment = ? WHERE C_ID = ?");
                    $stmt->execute(array($Comment,$CID));

                    // echo success message:
                        ?>
                        <script>
                            window.onload = function() {
                                <?php if ($stmt->rowCount() > 0): ?>
                                    swal({
                                        title: "Success",
                                        text: "Comment Updated",
                                        icon: "success",
                                    }).then(() => {
                                        window.location.href = 'Comments.php';
                                    });
                                <?php endif; ?>
                            };
                        </script>

<?php

            } else {
                $errorMsg = ' You\'re Not Allowed To Be Here';

                redirect($errorMsg , 4);
            }
            echo '</div>';
        } elseif ($action == 'Approved'){

            echo "<h1 class='text-center md:text-3xl sm:text-xl text-indigo-800'>Approve Comment</h1>";
            echo "<div class='container text-left'>";

            // check in Comment ID and make sure it numeric:
            $CID = isset($_GET['CID']) && is_numeric($_GET['CID']) ? intval($_GET['CID']) : 0;

            // Check on all data based on Comment ID
            $check = CheckDb('C_ID',"comments",$CID);

            if ($check > 0) {

                $stmt = $con->prepare("UPDATE comments SET Statues = 1 WHERE C_ID = ? ");

                // execute 
                $stmt->execute(array($CID));

                ?>
                    <script>
                        window.onload = function() {
                            <?php if ($stmt->rowCount() > 0): ?>
                                swal({
                                    title: "Success",
                                    text: "Comment Activated",
                                    icon: "success",
                                }).then(() => {
                                    window.location.href = 'Comments.php?action=Manage';
                                });
                            <?php endif; ?>
                        };
                    </script>

<?php   } 

        else {
                $errorMsg = $stmt->rowCount() . ' Comment Found';
                redirect($errorMsg,4);
        }

            echo "</div>";
        }
            include $tpl . 'footer.php';
        } else {
            header('Location:index.php');
            exit();
    }

    ob_end_flush();

?>