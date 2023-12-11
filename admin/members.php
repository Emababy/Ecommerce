<?php 
    session_start();

    $pageTitle = 'Members';

    if (isset($_SESSION['Username'])){

        include "init.php";
        
        $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

        // start mange page :
        if ($action == 'Manage'){  // manage page : 

            $query = ''; // To Show The Pending Members 
            if(isset($_GET['Page']) && $_GET['Page'] == 'Pending'){
                $query ='And RegStatues = 0';
            }

            // select all users :
            $stmt = $con->prepare("SELECT * FROM users WHERE GroupId != 1 $query");
            $stmt->execute();

            // Assign to vars :
            $rows = $stmt->fetchAll();
        ?>
                    <h1 class="text-center md:text-3xl sm:text-xl text-indigo-800">Manage Members</h1>
                    <!-- component -->
                        <div class="container flex items-center justify-center">
                            <table class="w-full flex flex-row flex-no-wrap sm:bg-white rounded-lg overflow-hidden sm:shadow-lg my-5">
                                <thead class="hidden md:table-header-group">
                                    <tr class="border border-grey-800">
                                        <th class="bg-gray-800 p-2 text-white font-bold border md:border-grey-500">UserName</th>
                                        <th class="bg-gray-800 p-2 text-white font-bold border md:border-grey-500">Email</th>
                                        <th class="bg-gray-800 p-2 text-white font-bold border md:border-grey-500">FullName</th>
                                        <th class="bg-gray-800 p-2 text-white font-bold border md:border-grey-500">Registered Data</th>
                                        <th class="bg-gray-800 p-2 text-white font-bold border md:border-grey-500">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="flex-1 sm-flex-none">
                                <?php 
                                    // loop to make rows depend on the users information 
                                    foreach($rows as $row){
                                        ?> 
                                            <tr class="flex flex-col flex-nowrap sm:table-row mb-2 sm:mb-0 text-center flex-1">
                                                <td class="border-grey-light border hover:bg-gray-100 p-3 md:p-4 text-10px md:text-base lg:text-lg xl:text-xl"><span class="md:hidden">User: </span><?php echo $row['Username'] ?></td>
                                                <td class="border-grey-light border hover:bg-gray-100 p-3 md:p-4 text-10px md:text-base lg:text-lg xl:text-xl"><?php echo $row['Email'] ?></td>
                                                <td class="border-grey-light border hover:bg-gray-100 p-3 md:p-4 text-10px md:text-base lg:text-lg xl:text-xl"><span class="md:hidden">Name: </span><?php echo $row['FullName'] ?></td>
                                                <td class="border-grey-light border hover:bg-gray-100 p-3 md:p-4 text-10px md:text-base lg:text-lg xl:text-xl"><span class="md:hidden">RegDate: </span><?php echo $row['Date'] ?></td>
                                                <td class="flex flex-row border-grey-light border p-3 justify-center">
                                                    <a href='members.php?action=Edit&ID=<?php echo $row['UserID'] ?>' class=" p-3 text-green-400 hover:text-green-600 hover:font-medium cursor-pointer"><i class="fa-solid fa-user-pen"></i></a>
                                                    <a href='members.php?action=Delete&ID=<?php echo $row['UserID'] ?>' class="p-3 text-red-400 hover:text-red-600 hover:font-medium cursor-pointer"><i class="fa-solid fa-user-xmark"></i></a>
                                                    <?php if($row['RegStatues'] == 0){?>
                                                        <a href='members.php?action=Activate&ID=<?php echo $row['UserID'] ?>' class="p-3 text-blue-400 hover:text-blue-600 hover:font-medium cursor-pointer"><i class="fa-solid fa-check"></i></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <!-- Link To Add New Member -->
                    <div class = "flex justify-center">
                        <a href="members.php?action=Add" class="p-4"><button class="hover:scale-95 transition-ease duration-500 hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-6 text-base font-semibold text-white outline-none">Add New Member</button></a>
                    </div>
<?php   
        } elseif ($action == 'Add'){ ?> <!-- add members page: -->
                <h1 class="text-center md:text-3xl sm:text-xl text-indigo-800">Add New Member</h1>
                    <!-- component -->
                    <div class="flex items-center justify-center p-12">
                        <div class="mx-auto w-full max-w-[550px]">
                            <form action="?action=Insert" method="POST">
                                <!-- username -->
                                <div class="mb-5">
                                    <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">UserName</label>
                                    <input type="text" name="username" placeholder="Should Be At least 4 Char And Less Than 20 Char" autocomplete="off" required="required"  class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>
                                <!-- password -->
                                <div class="mb-5 relative">
                                    <label for="password" class="mb-3 block text-base font-medium text-[#07074D]">Password</label>
                                    <input type="password" name="password" placeholder="Must contain At Least 8 Chars, Numbers And Special Chars ($,#,&,...)" autocomplete="off" required="required" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                    <i id="password-toggle" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 cursor-pointer"></i>
                                </div>
                                <!-- email -->
                                <div class="mb-5">
                                    <label for="email" class="mb-3 block text-base font-medium text-[#07074D]">Email</label>
                                    <input type="email" name="email" placeholder="Enter Valid Email Please!" autocomplete="off" required="required" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>
                                <!-- full name -->
                                <div class="mb-5">
                                    <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">Full Name</label>
                                    <input type="text" name="fullName" placeholder="Enter Your Full Name!"  autocomplete="off" required="required" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>
                                <div>
                                    <!-- button -->
                                    <button value="Add" type="submit" class="hover:scale-95 transition-ease duration-500 hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-base font-semibold text-white outline-none">Add Member</button>
                                </div>
                            </form>
                        </div>
                    </div>
<?php
        } elseif($action == 'Insert'){ // Insert Page :
    
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                echo "<h1 class='text-center md:text-3xl sm:text-xl text-indigo-800'>Insert Member</h1>";
                echo "<div class='container text-center'>";
                // get var from the form :
                $user  =  $_POST['username'];
                $pass  =  $_POST['password'];
                $email =  $_POST['email'];
                $name  =  $_POST['fullName'];
        
                // validate Form :
                $formErr = array();
                if (strlen($user) < 4 || strlen($user) > 20 || empty($user)){
                    $formErr[] = '<strong>User Must Be At least 4 Characters And less than 20 characters</strong>';
                }
                if (empty($pass)){
                    $formErr[] = '<strong>Password Can\'t Be Empty</strong>';
                }  
                if (empty($name)){
                    $formErr[] = '<strong>Please enter Your Full Name</strong>';
                }
                if (empty($email)){
                    $formErr[] = '<strong>Please enter Your Email Address</strong>';
                }
                if(!empty($formErr)){
                    displayErrors($formErr);
                }
                
                // check if the form is submitted successfully and no errors :
                if (empty($formErr)){
                    // check if user exists in db:
                    $check = CheckDb("Username", "users", $user);
                    if ($check == 1){
                        ?>
                        <script>
                            window.onload = function() {
                                swal({
                                    title: "Error",
                                    text: "Sorry, User Is already exists",
                                    icon: "error",
                                }).then(() => {
                                    window.location.href = 'members.php?action=Add';
                                });
                            };
                        </script>
                        <?php
                    } else {
                        // Hash the password using password_hash
                        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
        
                        // Insert user information in  db :
                        $stmt = $con->prepare("INSERT INTO 
                                                users(Username , Password , Email , FullName , RegStatues , Date)
                                                VALUES(:username , :password , :email , :fullName , 1 , now())");
                        $stmt->execute(array(
                            'username' => $user,
                            'password' => $hashedPass,
                            'email'=> $email,
                            'fullName' => $name
                        ));
        
                        ?>
                        <script>
                            window.onload = function() {
                                <?php if ($stmt->rowCount() > 0): ?>
                                    swal({
                                        title: "Success",
                                        text: "Member Added",
                                        icon: "success",
                                    }).then(() => {
                                        window.location.href = 'members.php?action=Add';
                                    });
                                <?php endif; ?>
                            };
                        </script>
                        <?php
                    }
                }
            } else {
                $errorMsg = ' You\'re Not Allowed To Be Here';
                redirect($errorMsg , 4 , 'Dashboard.php');
            }
            echo '</div>';
        
        } elseif ($action == 'Edit'){ // edit page : 

            // check in user id and make sure it numeric
            $userId = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;

            // select all data based on userid
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
        
            $stmt->execute(array($userId));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            // check if id is already exists show the form
            if ($count > 0){ ?> 
                
                <h1 class="text-center md:text-3xl sm:text-xl text-indigo-800">Edit Member</h1>
                <!-- component -->
                <div class="flex items-center justify-center p-12">
                    <div class="mx-auto w-full max-w-[550px]">
                        <form action="?action=Update" method="POST">
                            <!-- userID -->
                            <input type="hidden" name = "ID" value = "<?php echo $userId ?>"/>
                            <!-- username -->
                            <div class="mb-5">
                                <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">UserName</label>
                                <input type="text" name="username" placeholder="username" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                            </div>
                            <!-- password -->
                            <div class="mb-5">
                                <label for="password" class="mb-3 block text-base font-medium text-[#07074D]">Password</label>
                                <input type="hidden" name="oldPassword" value="<?php echo $row['Password'] ?>"/>
                                <input type="password" name="newPassword"  placeholder="You Can Leave It Empty If You Don't Want To Change It" autocomplete="New-password" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                            </div>
                            <!-- email -->
                            <div class="mb-5">
                                <label for="email" class="mb-3 block text-base font-medium text-[#07074D]">Email</label>
                                <input type="email" name="email" placeholder="Email" value="<?php echo $row['Email'] ?>" autocomplete="off" required="required" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                            </div>
                            <!-- full name -->
                            <div class="mb-5">
                                <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">Full Name</label>
                                <input type="text" name="fullName" placeholder="Full Name" value="<?php echo $row['FullName'] ?>" autocomplete="off" required="required" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                            </div>
                            <div>
                                <!-- button -->
                                <button value="Save" type="submit" class="hover:scale-95 transition-ease duration-500 hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-base font-semibold text-white outline-none">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
<?php 

        } else { // if there is no id like this:
            $errorMsg = "There's No Id Like This";
            redirect($errorMsg , 4 , 'Dashboard.php');
        }
        } elseif($action == 'Update'){ // update page :

            echo "<h1 class='text-center md:text-3xl sm:text-xl text-indigo-800'>Update Member</h1>";
            echo "<div class='container text-center'>";
        
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // get var from the form :
                $id = $_POST['ID'];
                $user = $_POST['username'];
                $email = $_POST['email'];
                $name = $_POST['fullName'];
        
                // password :
                $newPassword = $_POST['newPassword'];
                $oldPassword = $_POST['oldPassword'];
        
                // validate Form :
                $formErr = array();
                if (strlen($user) < 4 || strlen($user) > 20 || empty($user)){
                    $formErr[] = '<strong>User Must Be At least 4 Characters And less than 20 characters</strong>';
                }
                if (empty($name)){
                    $formErr[] = '<strong>Please enter Your Full Name</strong>';
                }
                if (empty($email)){
                    $formErr[] = '<strong>Please enter Your Email Address</strong>';
                }
        
                displayErrors($formErr);
        
                if (!empty($formErr)){
                    ?>
                        <div>
                            <!-- button -->
                            <a href="members.php?action=Edit&ID=<?php echo $id ?>"><button value="Return" type="submit" class="hover:scale-95 transition-ease duration-500 hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-base font-semibold text-white outline-none">Return</button></a>
                        </div>                
                    <?php
                }
        
                // check if the form is submitted successfully and no errors :
                if (empty($formErr)){
                    // Check if a new password is provided
                    $pass = empty($newPassword) ? $oldPassword : password_hash($newPassword, PASSWORD_DEFAULT);
        
                    // update user information in db :
                    $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
                    $stmt->execute(array($user, $email, $name, $pass, $id));
        
                    // echo success message:
                    ?>
                    <script>
                        window.onload = function() {
                            <?php if ($stmt->rowCount() > 0): ?>
                                swal({
                                    title: "Success",
                                    text: "Member Updated",
                                    icon: "success",
                                }).then(() => {
                                    window.location.href = 'members.php?action=Edit&ID=<?php echo $id ?>';
                                });
                            <?php else: ?>
                                swal({
                                    title: "Failed!",
                                    text: "No Member Updated",
                                    icon: "warning",
                                }).then(() => {
                                    window.location.href = 'members.php?action=Edit&ID=<?php echo $id ?>';
                                });
                            <?php endif; ?>
                        };
                    </script>   
                <?php 
                }
            } else {
                $errorMsg = ' You\'re Not Allowed To Be Here';
                redirect($errorMsg , 4 , 'Dashboard.php');
            }
            echo '</div>';
        } elseif ($action == 'Delete'){ // Delete member page 

            echo "<h1 class='text-center md:text-3xl sm:text-xl text-indigo-800'>Delete Member</h1>";
            echo "<div class='container text-left'>";
            // check in user id and make sure it numeric:
            $userId = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;

            // Check on all data based on userid
            $check = CheckDb('userid', 'users' , $userId);

            if ($check > 0) {

                $stmt = $con->prepare("DELETE FROM users WHERE UserID = :userID");
                // link the id of the user to the database:
                $stmt->bindParam(":userID", $userId); // Use ":userID" with the colon
                // execute the delete 
                $stmt->execute();
                ?>
                    <script>
                        window.onload = function() {
                            <?php if ($stmt->rowCount() > 0): ?>
                                swal({
                                    title: "Success",
                                    text: "Member Deleted",
                                    icon: "success",
                                }).then(() => {
                                    window.location.href = 'members.php?action=Manage';
                                });
                            <?php endif; ?>
                        };
                    </script>
<?php   } else {
                $errorMsg = $stmt->rowCount() . ' Member Found';
                redirect($errorMsg , 4 , 'Dashboard.php');
            }
            echo "</div>";
        } elseif($action == 'Activate'){ // Activate Member 

            echo "<h1 class='text-center md:text-3xl sm:text-xl text-indigo-800'>Activate Member</h1>";
            echo "<div class='container text-left'>";
            // check in user id and make sure it numeric:
            $userId = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;

            // select all data based on userid
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
            
            // execute the query
            $stmt->execute(array($userId));
            $count = $stmt->rowCount();

            if ($count > 0) {

                $stmt = $con->prepare("UPDATE users SET RegStatues = 1 WHERE UserID = ? ");
                // execute 
                $stmt->execute(array($userId));
                ?>
                    <script>
                        window.onload = function() {
                            <?php if ($stmt->rowCount() > 0): ?>
                                swal({
                                    title: "Success",
                                    text: "Member Activated",
                                    icon: "success",
                                }).then(() => {
                                    window.location.href = 'members.php?action=Manage&Page=Pending';
                                });
                            <?php endif; ?>
                        };
                    </script>
<?php   } else {
                $errorMsg = $stmt->rowCount() . ' Member Found';
                redirect($errorMsg,4);
        }
            echo "</div>";
        }
        include $tpl ."footer.php";  
    } else {
        header('Location:index.php');
            exit();
    }
?>

