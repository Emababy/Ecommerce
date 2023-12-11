<?php 

    ob_start();

    session_start();
    $pageTitle = 'Items';

    if(isset($_SESSION['Username'])){
        include 'init.php';

        $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

        // check on the action request 

        if ($action == 'Manage'){
            
            // select all items :
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
                                ");
            $stmt->execute();
            
            // Assign to vars :
            $items = $stmt->fetchAll();
            

        ?>

                    <h1 class="text-center md:text-3xl sm:text-xl text-indigo-800">Manage Items</h1>

                    <!-- component Cards -->
                    <div class="container mx-auto flex flex-wrap items-center justify-center my-5 text-center">
                        <?php foreach ($items as $item) { ?>
                            <div class="max-w-sm rounded overflow-hidden shadow-lg mx-2 my-2 ">

                                <!-- Image (if you have one) -->
                                <!-- <img class="w-full" src="<?php echo $item['Image']; ?>" alt="Item Image"> -->

                                <div class="px-6 py-4 cursor-pointer">
                                    <div class="font-bold text-xl mb-2"><?php echo $item['Name']; ?></div>
                                    <p class="text-green-600 text-base">Price: <?php echo $item['Price']; ?></p>
                                    <p class="text-indigo-600 text-base">Category: <?php echo $item['Cat_Name']; ?></p>
                                    <p class="text-purple-600 text-base">Seller: <?php echo $item['Member_Name']; ?></p>
                                    <p class="text-gray-700 text-base"><?php echo $item['Add_Date']; ?></p>
                                </div>

                                <div class="px-6 py-4 text-sm">
                                    <a href='Items.php?action=Edit&ItemID=<?php echo $item['Item_ID'] ?>' class="text-green-400 hover:text-green-600 hover:font-medium cursor-pointer mr-2"><i class="fa-solid fa-user-pen"></i></a>
                                    <a href='Items.php?action=Delete&ItemID=<?php echo $item['Item_ID'] ?>' class="text-red-400 hover:text-red-600 hover:font-medium cursor-pointer"><i class="fa-solid fa-user-xmark"></i></a>
                                    <?php if ($item['Approve'] == 0): ?>
                                        <a href='Items.php?action=Approved&ItemID=<?php echo $item['Item_ID'] ?>' class="p-3 text-blue-400 hover:text-blue-600 hover:font-medium cursor-pointer"><i class="fa-solid fa-check"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>



                    <!-- Link To Add Item Member -->
                    <div class = "flex justify-center">
                        <a href="Items.php?action=Add" class="p-4"><button class="hover:scale-95 transition-ease duration-500 hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-6 text-base font-semibold text-white outline-none">Add New Item</button></a>
                    </div>

<?php   

        } elseif ($action == 'Add'){

            ?> <!-- add Item: -->

                <h1 class="text-center md:text-3xl sm:text-xl text-indigo-800">Add New Item</h1>
                    <!-- component -->
                    <div class="flex items-center justify-center p-12">
                        <div class="mx-auto w-full max-w-[550px]">
                            <form action="?action=Insert" method="POST">
                                <!-- Name -->
                                <div class="mb-5">
                                    <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">Name</label>
                                    <input type="text" name="name" placeholder="Name of the Item" required="required"  class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>
                                <!-- Description -->
                                <div class="mb-5 relative">
                                    <label for="Description" class="mb-3 block text-base font-medium text-[#07074D]">Description</label>
                                    <input type="text" name="Description" required="required" placeholder="Describe the Category" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>
                                <!-- Price -->
                                <div class="mb-5">
                                    <label for="Price" class="mb-3 block text-base font-medium text-[#07074D]">Price</label>
                                    <input type="text" name="Price" placeholder="The Price Of The Item" required="required" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>
                                <!-- Country Made -->
                                <div class="mb-5">
                                    <label for="CountryMade" class="mb-3 block text-base font-medium text-[#07074D]">Country Made</label>
                                    <input type="text" name="CountryMade" required="required" placeholder="The Country Made Of The Item" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>
                                <!-- Image -->
                                <!-- <div class="mb-5">
                                    <label for="CountryMade" class="mb-3 block text-base font-medium text-[#07074D]">Country Made</label>
                                    <input type="text" name="CountryMade" placeholder="The Country Made Of The Item" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div> -->
                                <!-- Statues -->
                                <div class="mb-5">
                                    <label for="Statues" class="mb-3 block text-base font-medium text-[#07074D]">Statues</label>
                                    <select type="text" name="Statues" required="required" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
                                        <option value="0">..</option>
                                        <option value="1">New</option>
                                        <option value="2">Used</option>
                                        <option value="3">Like New</option>
                                    </select>
                                </div>
                                <!-- Members Field -->
                                <div class="mb-5">
                                    <label for="Member" class="mb-3 block text-base font-medium text-[#07074D]">Member</label>
                                    <select type="text" name="Member" required="required" class="w-full rounded-md border border-[#e0e0e0] bg-white mb-5 py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
                                        <option value="0">..</option>
                                        <?php

                                            // Grab all the users in users table
                                            $stmt=$con->prepare("SELECT * FROM users");
                                            $stmt->execute();
                                            $users = $stmt->fetchAll();

                                            // do loop over all the users in users table
                                            foreach ($users as $user) { 
                                                echo "<option value ='". $user['UserID'] ."'>" . $user['Username'] . "</option>";
                                            }

                                        ?>
                                    </select>
                                </div>
                                <!-- Category Field -->
                                <div class="mb-5">
                                    <label for="Category" class="mb-3 block text-base font-medium text-[#07074D]">Category</label>
                                    <select type="text" name="Category" required="required" class="w-full rounded-md border border-[#e0e0e0] bg-white mb-5 py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
                                        <option value="0">..</option>
                                        <?php

                                            // Grab all the categories in categories table
                                            $stmt2=$con->prepare("SELECT * FROM categories");
                                            $stmt2->execute();
                                            $categories = $stmt2->fetchAll();

                                            // do loop over all the categories in categories table
                                            foreach ($categories as $category) { 
                                                echo "<option value ='". $category['ID'] ."'>" . $category['Name'] . "</option>";
                                            }

                                        ?>
                                    </select>
                                </div>
                                <!-- button -->
                                <div>
                                    <button value="Add" type="submit" class="hover:scale-95 transition-ease duration-500 hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-base font-semibold text-white outline-none">Add Category</button>
                                </div>
                            </form>
                        </div>
                    </div>

            <?php

        } elseif ($action == 'Delete'){

            echo "<h1 class='text-center md:text-3xl sm:text-xl text-indigo-800'>Delete Item</h1>";
            echo "<div class='container text-left'>";

            // check in Item_ID and make sure it numeric:
            $ItemID = isset($_GET['ItemID']) && is_numeric($_GET['ItemID']) ? intval($_GET['ItemID']) : 0;

            // Check on all data based on Item_ID
            $check = CheckDb('Item_ID', 'items' , $ItemID);

            if ($check > 0) {

                $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :ItemID");

                // link the id of the item to the database:
                $stmt->bindParam(":ItemID", $ItemID); // Use ":ItemID" with the colon
                // execute the delete 
                $stmt->execute();
                ?>
                    <script>
                        window.onload = function() {
                            <?php if ($stmt->rowCount() > 0): ?>
                                swal({
                                    title: "Success",
                                    text: "Item Deleted",
                                    icon: "success",
                                }).then(() => {
                                    window.location.href = 'Items.php?action=Manage';
                                });
                            <?php endif; ?>
                        };
                    </script>

<?php   } 

        else {
                $errorMsg = $stmt->rowCount() . ' Item Found';
                redirect($errorMsg , 4 , 'Dashboard.php');
            }

        echo "</div>";

        } elseif ($action == 'Edit'){

            
            // check in Item ID and make sure it numeric
            $ItemID = isset($_GET['ItemID']) && is_numeric($_GET['ItemID']) ? intval($_GET['ItemID']) : 0;

            // select all data based on Item ID
            $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
        
            $stmt->execute(array($ItemID));
            $item = $stmt->fetch();
            $count = $stmt->rowCount();

            // check if id is already exists show the form
            if ($count > 0){ ?> 
                
                <h1 class="text-center md:text-3xl sm:text-xl text-indigo-800">Edit Item</h1>
                <!-- component -->
                <div class="flex items-center justify-center p-12">
                        <div class="mx-auto w-full max-w-[550px]">
                            <form action="?action=Update" method="POST">
                                <!-- Item ID -->
                                <input type="hidden" name="ItemID" value="<?php echo $ItemID ?>">
                                <!-- Name -->
                                <div class="mb-5">
                                    <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">Name</label>
                                    <input type="text" name="name" placeholder="Name of the Item" required="required"  class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" value="<?php echo $item['Name'] ?>"/>
                                </div>
                                <!-- Description -->
                                <div class="mb-5 relative">
                                    <label for="Description" class="mb-3 block text-base font-medium text-[#07074D]">Description</label>
                                    <input type="text" name="Description" required="required" placeholder="Describe the Category" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" value="<?php echo $item['Description'] ?>"/>
                                </div>
                                <!-- Price -->
                                <div class="mb-5">
                                    <label for="Price" class="mb-3 block text-base font-medium text-[#07074D]">Price</label>
                                    <input type="text" name="Price" placeholder="The Price Of The Item" required="required" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" value="<?php echo $item['Price'] ?>"/>
                                </div>
                                <!-- Country Made -->
                                <div class="mb-5">
                                    <label for="CountryMade" class="mb-3 block text-base font-medium text-[#07074D]">Country Made</label>
                                    <input type="text" name="CountryMade" required="required" placeholder="The Country Made Of The Item" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" value="<?php echo $item['Country'] ?>"/>
                                </div>
                                <!-- Image -->
                                <!-- <div class="mb-5">
                                    <label for="CountryMade" class="mb-3 block text-base font-medium text-[#07074D]">Country Made</label>
                                    <input type="text" name="CountryMade" placeholder="The Country Made Of The Item" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" value="<?php echo $item['Image'] ?>"/>
                                </div> -->
                                <!-- Statues -->
                                <div class="mb-5">
                                    <label for="Statues" class="mb-3 block text-base font-medium text-[#07074D]">Statues</label>
                                    <select type="text" name="Statues" required="required" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
                                        <option value="">..</option>
                                        <option value="1" <?php if ($item['Statues'] == 1) {echo 'selected';} ?>>New</option>
                                        <option value="2" <?php if ($item['Statues'] == 2) {echo 'selected';} ?>>Used</option>
                                        <option value="3" <?php if ($item['Statues'] == 3) {echo 'selected';} ?>>Like New</option>
                                    </select>
                                </div>
                                <!-- Members Field -->
                                <div class="mb-5">
                                    <label for="Member" class="mb-3 block text-base font-medium text-[#07074D]">Member</label>
                                    <select type="text" name="Member" required="required" class="w-full rounded-md border border-[#e0e0e0] bg-white mb-5 py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
                                        <option value="0">..</option>
                                        <?php

                                            // Grab all the users in users table
                                            $stmt=$con->prepare("SELECT * FROM users");
                                            $stmt->execute();
                                            $users = $stmt->fetchAll();

                                            // do loop over all the users in users table
                                            foreach ($users as $user) { 

                                                echo "<option value='" . $user['UserID'] . "'";
                                                if ( $item['Member_ID'] == $user['UserID'] ) { echo ' selected'; }
                                                echo ">" . $user['Username'] . "</option>";
                                            }

                                        ?>
                                    </select>
                                </div>
                                <!-- Category Field -->
                                <div class="mb-5">
                                    <label for="Category" class="mb-3 block text-base font-medium text-[#07074D]">Category</label>
                                    <select type="text" name="Category" required="required" class="w-full rounded-md border border-[#e0e0e0] bg-white mb-5 py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
                                        <option value="0">..</option>
                                        <?php

                                            // Grab all the categories in categories table
                                            $stmt2=$con->prepare("SELECT * FROM categories");
                                            $stmt2->execute();
                                            $categories = $stmt2->fetchAll();

                                            // do loop over all the categories in categories table
                                            foreach ($categories as $category) { 
                                                echo "<option value ='". $category['ID'] ."'";
                                                if ( $item["Cat_ID"] == $category['ID'] ) { echo "selected"; }
                                                echo ">" . $category['Name'] . "</option>";
                                            }

                                        ?>
                                    </select>
                                </div>

                                <!-- button -->
                                <div>
                                    <button value="Save" type="submit" class="hover:scale-95 transition-ease duration-500 hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-base font-semibold text-white outline-none">Update Item</button>
                                </div>
                            </form>
                        </div>
                    </div>
<?php 

        } else { // if there is no id like this:

            $errorMsg = "There's No Id Like This";
            redirect($errorMsg , 4 , 'Dashboard.php');
        }
        } elseif ($action == 'Insert'){

            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                echo "<h1 class='text-center md:text-3xl sm:text-xl text-indigo-800'>Insert Item</h1>";
                echo "<div class='container text-center'>";

                // get var from the form :
                $name           =  $_POST['name'];
                $Description    =  $_POST['Description'];
                $Price          =  $_POST['Price'];
                $CountryMade    =  $_POST['CountryMade'];
                $Statues        =  $_POST['Statues'];
                $Member         =  $_POST['Member'];
                $Category       =  $_POST['Category'];

                // validate Form :
                $formErr = array();
                if (empty($name)){
                    $formErr[] = '<strong>Name Can\'t Be Empty</strong>';
                }
                if (empty($Price)){
                    $formErr[] = '<strong>Price Can\'t Be Empty</strong>';
                }  
                if (empty($Description)){
                    $formErr[] = '<strong>Description Can\'t Be Empty</strong>';
                }
                if (empty($CountryMade)){
                    $formErr[] = '<strong>Country Made Can\'t Be Empty/strong>';
                }
                if ($Statues == 0){
                    $formErr[] = '<strong>You Must Choose An Option</strong>';
                }
                if ($Member == 0){
                    $formErr[] = '<strong>You Must Choose An Option</strong>';
                }
                if ($Category == 0){
                    $formErr[] = '<strong>You Must Choose An Option</strong>';
                }
                if (!empty($formErr)) {
                    displayErrors($formErr);
                }
                // check if the form is submitted successfully and no errors :
                if (empty($formErr)){

                        // Insert Item information in  db :
                        $stmt = $con->prepare("INSERT INTO 
                                                items(Name , Price , Description , Country , Statues , Add_Date , Cat_ID , Member_ID)
                                                VALUES(:zName , :zPrice , :zDescription , :zCountry , :zStatues , now() , :zCategory , :zMember)");
                        $stmt->execute(array(
                            'zName'         => $name,
                            "zPrice"        => $Price,
                            "zDescription"  => $Description,
                            "zCountry"      => $CountryMade,
                            "zStatues"      => $Statues,
                            "zCategory"     => $Category,
                            "zMember"       => $Member
                        ));
                        ?>
                        <script>
                            window.onload = function() {
                                <?php if ($stmt->rowCount() > 0): ?>
                                    swal({
                                        title: "Success",
                                        text: "Item Added",
                                        icon: "success",
                                    }).then(() => {
                                        window.location.href = 'Items.php';
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
        } elseif ($action == 'Update'){

            echo "<h1 class='text-center md:text-3xl sm:text-xl text-indigo-800'>Update Item</h1>";
            echo "<div class='container text-center'>";

            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                // get var from the form :
                $ItemID         = $_POST['ItemID'];
                $Name           = $_POST['name'];
                $Description    = $_POST['Description'];
                $Price          = $_POST['Price'];
                $CountryMade    = $_POST['CountryMade'];
                $Statues        = $_POST['Statues'];
                $Category       = $_POST['Category'];
                $Member         = $_POST['Member'];

                
                // validate Form :
                $formErr = array();
                if (empty($Name)){
                    $formErr[] = '<strong>Name Can\'t Be Empty</strong>';
                }
                if (empty($Price)){
                    $formErr[] = '<strong>Price Can\'t Be Empty</strong>';
                }  
                if (empty($Description)){
                    $formErr[] = '<strong>Description Can\'t Be Empty</strong>';
                }
                if (empty($CountryMade)){
                    $formErr[] = '<strong>Country Made Can\'t Be Empty/strong>';
                }
                if ($Statues == 0){
                    $formErr[] = '<strong>You Must Choose An Option</strong>';
                }
                if ($Member == 0){
                    $formErr[] = '<strong>You Must Choose An Option</strong>';
                }
                if ($Category == 0){
                    $formErr[] = '<strong>You Must Choose An Option</strong>';
                }
                foreach ($formErr as $error){
                    echo '<div class="alert-div my-4 p-5">' . $error  . '</div>';
                }
                if (!empty($formErr)){
                    ?>
                        <div>
                            <!-- button -->
                            <a href="Items.php?action=Edit&ItemID=<?php echo $ItemID ?>"><button value="Return" type="submit" class="hover:scale-95 transition-ease duration-500 hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-base font-semibold text-white outline-none">Return</button></a>
                        </div>                
                    <?php
                }

                // check if the form is submitted successfully and no errors :
                if (empty($formErr)){

                    // update Item information in  db :
                    $stmt = $con->prepare("UPDATE 
                                                items 
                                            SET
                                                Name            = ?,
                                                Description     = ?,
                                                Price           = ?, 
                                                Country         = ?,
                                                Statues         = ?,
                                                Cat_ID          = ?,
                                                Member_ID       = ?
                                            WHERE 
                                                Item_ID = ?");
                    $stmt->execute(array($Name,$Description,$Price,$CountryMade,$Statues,$Category,$Member,$ItemID));

                    // echo success message:
                        ?>
                        <script>
                            window.onload = function() {
                                <?php if ($stmt->rowCount() > 0): ?>
                                    swal({
                                        title: "Success",
                                        text: "Item Updated",
                                        icon: "success",
                                    }).then(() => {
                                        window.location.href = 'Items.php?action=Edit&ItemID=<?php echo $ItemID ?>';
                                    });
                    // echo failed message
                                    <?php else: ?>
                                        swal({
                                            title: "Failed!",
                                            text: "No Item Updated",
                                            icon: "warning",
                                        }).then(() => {
                                            window.location.href = 'Items.php?action=Edit&ItemID=<?php echo $ItemID ?>';
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
        } elseif ($action == 'Approved'){

            echo "<h1 class='text-center md:text-3xl sm:text-xl text-indigo-800'>Approve Item</h1>";
            echo "<div class='container text-left'>";

            // check in ItemID and make sure it numeric:
            $ItemID = isset($_GET['ItemID']) && is_numeric($_GET['ItemID']) ? intval($_GET['ItemID']) : 0;

            // Check on all data based on Item_ID
            $check = CheckDb('Item_ID',"items",$ItemID);

            if ($check > 0) {

                $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ? ");

                // execute 
                $stmt->execute(array($ItemID));

                ?>
                    <script>
                        window.onload = function() {
                            <?php if ($stmt->rowCount() > 0): ?>
                                swal({
                                    title: "Success",
                                    text: "Item Activated",
                                    icon: "success",
                                }).then(() => {
                                    window.location.href = 'Items.php?action=Manage';
                                });
                            <?php endif; ?>
                        };
                    </script>

<?php   } 

        else {
                $errorMsg = $stmt->rowCount() . ' Items Found';
                redirect($errorMsg , 4 , 'Dashboard.php');
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