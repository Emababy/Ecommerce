<?php 

    ob_start(); // output buffering start 

    session_start();
    $pageTitle = 'Categories';

    if(isset($_SESSION['Username'])){
        
        include 'init.php';

        $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

        if ($action == 'Manage'){

            $sort = 'ASC';
            $sort_array = array('ASC' , 'DESC');
            if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){
                $sort = $_GET['sort'];
            }

            $stmt = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
            $stmt->execute();
            $cats = $stmt->fetchAll();

            ?>

            <h1 class="text-center md:text-3xl sm:text-xl text-indigo-800">Manage Categories</h1>

            <div class="container mx-auto my-6">
                <div class="bg-gray-800 p-6 text-white font-bold border md:border-grey-500 rounded-md">
                    <div class="text-lg text-center p-3 flex flex-col justify-between md:flex-row items-center mx-4">Categories
                    <div class="text-sm md:text-lg my-2">
                        <!-- Ordering: -->
                        <a href="?sort=ASC"><i class="fa-solid fa-arrow-up mx-4 text-green-400 hover:text-green-600"></i></a>
                        <a href="?sort=DESC "><i class="fa-solid fa-arrow-down text-red-400 hover:text-red-600"></i></a>
                    </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($cats as $index => $category): ?>
                            <div class="flex flex-col bg-gray-700 p-3 rounded-md mb-4">
                                <div class="mb-2">
                                    <h3 class="text-lg font-semibold cursor-pointer categoryName" data-index="<?php echo $index; ?>">
                                        <?php echo $category['Name']; ?>
                                    </h3>
                                </div>
                                <div class="toggle-details toggle-details-<?php echo $index; ?> hidden transition-ease">
                                    <p class="text-gray-400 mb-2">
                                        <?php echo ($category['Description'] == '') ? "There's No Description" : $category['Description']; ?>
                                    </p>
                                    <p>
                                        <span class="font-bold"></span>
                                        <?php echo ($category['Ordering'] == null) ? "" : "Ordering: " . $category['Ordering']; ?>
                                    </p>
                                    <p>
                                        <span class="font-bold" style='<?php echo ($category['Visibility'] == 1) ? "color: red;" : "color: green;"; ?>'>
                                            Visibility: <?php echo ($category['Visibility'] == 1) ? "<i class='fa-solid fa-eye-slash'></i>" : "<i class='fa-solid fa-eye'></i>"; ?>
                                        </span>
                                    </p>
                                    <p>
                                        <span class="font-bold" style='<?php echo ($category['AllowComment'] == 1) ? "color: red;" : "color: green;"; ?>'>
                                            Commenting: <?php echo ($category['AllowComment'] == 1) ? "<i class='fa-solid fa-lock'></i>" : "<i class='fa-solid fa-check'></i>"; ?>
                                        </span>
                                    </p>
                                    <p>
                                        <span class="font-bold" style='<?php echo ($category['AllowAds'] == 1) ? "color: red;" : "color: green;"; ?>'>
                                            Ads: <?php echo ($category['AllowAds'] == 1) ? "<i class='fa-solid fa-lock'></i>" : "<i class='fa-solid fa-check'></i>"; ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <a href='Categories.php?action=Edit&catid=<?php echo $category['ID'] ?>' class="text-green-400 hover:text-green-600 hover:font-medium cursor-pointer">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                    <a href='Categories.php?action=Delete&catid=<?php echo $category['ID'] ?>' class="text-red-400 hover:text-red-600 hover:font-medium cursor-pointer">
                                    <span class="material-symbols-outlined">delete</span>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class = "flex justify-center">
                <a href="Categories.php?action=Add" class="p-4"><button class="hover:scale-95 transition-ease duration-500 hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-6 text-base font-semibold text-white outline-none">Add New Category</button></a>
            </div>

<?php

        } elseif ($action == 'Add'){

            ?> <!-- add Categories : -->

                <h1 class="text-center md:text-3xl sm:text-xl text-indigo-800">Add New Category</h1>
                    <!-- component -->
                    <div class="flex items-center justify-center p-12">
                        <div class="mx-auto w-full max-w-[550px]">
                            <form action="?action=Insert" method="POST">
                                <!-- Name -->
                                <div class="mb-5">
                                    <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">Name</label>
                                    <input type="text" name="name" placeholder="Name of the Category" required="required"  class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>
                                <!-- Description -->
                                <div class="mb-5 relative">
                                    <label for="Description" class="mb-3 block text-base font-medium text-[#07074D]">Description</label>
                                    <input type="text" name="Description" placeholder="Describe the Category" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>
                                <!-- Ordering -->
                                <div class="mb-5">
                                    <label for="Ordering" class="mb-3 block text-base font-medium text-[#07074D]">Ordering</label>
                                    <input type="text" name="Ordering" placeholder="Number to Order the Category" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>
                                <!-- Visible -->
                                <div class="mb-5">
                                    <label for="Visible" class="mb-3 block text-base font-medium text-[#07074D]">Visible</label>
                                    <div class="px-3">
                                        <input id="vis-yes" type="radio" name="Visible" value="0" checked  required="required"/>
                                        <label for="vis-yes">Yes</label>
                                    </div>
                                    <div class="px-3">
                                        <input id="vis-no" type="radio" name="Visible" value="1"  required="required"/>
                                        <label for="vis-no">No</label>
                                    </div>
                                </div>
                                <!-- Comments -->
                                <div class="mb-5">
                                    <label for="Comments" class="mb-3 block text-base font-medium text-[#07074D]">Allow Commenting</label>
                                    <div class="px-3">
                                        <input id="com-yes" type="radio" name="Comments" value="0" checked  required="required"/>
                                        <label for="com-yes">Yes</label>
                                    </div>
                                    <div class="px-3">
                                        <input id="com-no" type="radio" name="Comments" value="1"  required="required"/>
                                        <label for="com-no">No</label>
                                    </div>
                                </div>
                                <!-- Ads -->
                                <div class="mb-5">
                                    <label for="Ads" class="mb-3 block text-base font-medium text-[#07074D]">Allow Ads</label>
                                    <div class="px-3">
                                        <input id="Ads-yes" type="radio" name="Ads" value="0" checked  required="required"/>
                                        <label for="Ads-yes">Yes</label>
                                    </div>
                                    <div class="px-3"> 
                                        <input id="Ads-no" type="radio" name="Ads" value="1"  required="required"/>
                                        <label for="Ads-no">No</label>
                                    </div>
                                </div>
                                <div>
                                    <!-- button -->
                                    <button value="Add" type="submit" class="hover:scale-95 transition-ease duration-500 hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-base font-semibold text-white outline-none">Add Category</button>
                                </div>
                            </form>
                        </div>
                    </div>
            <?php

        } elseif ($action == 'Delete'){

            echo "<h1 class='text-center md:text-3xl sm:text-xl text-indigo-800'>Delete Category</h1>";
            echo "<div class='container text-left'>";
            // check in Category id and make sure it numeric:
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

            $check = CheckDb('ID', 'categories' , $catid);

            if ($check > 0) {

                $stmt = $con->prepare("DELETE FROM categories WHERE ID = :cid");
                // link the id of the category to the database:
                $stmt->bindParam(":cid", $catid); // Use ":cid" with the colon
                // execute the delete 
                $stmt->execute();
                ?>
                    <script>
                        window.onload = function() {
                            <?php if ($stmt->rowCount() > 0): ?>
                                swal({
                                    title: "Success",
                                    text: "Category Deleted",
                                    icon: "success",
                                }).then(() => {
                                    window.location.href = 'Categories.php?action=Manage';
                                });
                            <?php endif; ?>
                        };
                    </script>
<?php   
        } else {
                $errorMsg ='No Category Found';
                redirect($errorMsg , 4 , 'Dashboard.php');
            }
            echo "</div>";

        } elseif ($action == 'Edit'){

            // check in Cat id and make sure it numeric
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

            // select all data based on Cat
            $stmt = $con->prepare("SELECT * FROM categories WHERE ID  = ?");
            $stmt->execute(array($catid));
            $cat = $stmt->fetch();
            $count = $stmt->rowCount();

            // check if id is already exists show the form
            if ($count > 0){ ?> 
                
                <h1 class="text-center md:text-3xl sm:text-xl text-indigo-800">Edit Category</h1>

                <div class="flex items-center justify-center p-12">
                        <div class="mx-auto w-full max-w-[550px]">
                            <form action="?action=Update" method="POST">
                                <!-- catid -->
                                <input type="hidden" name = "catid" value = "<?php echo $catid ?>"/>
                                <!-- Name -->
                                <div class="mb-5">
                                    <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">Name</label>
                                    <input type="text" name="name" placeholder="Name of the Category" required="required"  class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" value="<?php echo $cat['Name']  ?>"/>
                                </div>
                                <!-- Description -->
                                <div class="mb-5 relative">
                                    <label for="password" class="mb-3 block text-base font-medium text-[#07074D]">Description</label>
                                    <input type="text" name="Description" placeholder="Describe the Category" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" value="<?php echo $cat['Description']  ?>"/>
                                </div>
                                <!-- Ordering -->
                                <div class="mb-5">
                                    <label for="Ordering" class="mb-3 block text-base font-medium text-[#07074D]">Ordering</label>
                                    <input type="text" name="Ordering" placeholder="Number to Order the Category" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" value="<?php echo $cat['Ordering']  ?>"/>
                                </div>
                                <!-- Visible -->
                                <div class="mb-5">
                                    <label for="Visible" class="mb-3 block text-base font-medium text-[#07074D]">Visible</label>
                                    <div class="px-3">
                                        <input id="vis-yes" type="radio" name="Visible" required="required"  value="0" <?php if($cat['Visibility'] == 0) {echo 'checked' ;} ?>/>
                                        <label for="vis-yes">Yes</label>
                                    </div>
                                    <div class="px-3">
                                        <input id="vis-no" type="radio" name="Visible" required="required" value="1" <?php if($cat['Visibility'] == 1) {echo 'checked' ;} ?>/>
                                        <label for="vis-no">No</label>
                                    </div>
                                </div>
                                <!-- Comments -->
                                <div class="mb-5">
                                    <label for="Comments" class="mb-3 block text-base font-medium text-[#07074D]">Allow Commenting</label>
                                    <div class="px-3">
                                        <input id="com-yes" type="radio" name="Comments" value="0" required="required" <?php if($cat['AllowComment'] == 0) {echo 'checked' ;} ?>/>
                                        <label for="com-yes">Yes</label>
                                    </div>
                                    <div class="px-3">
                                        <input id="com-no" type="radio" name="Comments" value="1"  required="required" <?php if($cat['AllowComment'] == 1) {echo 'checked' ;} ?>/>
                                        <label for="com-no">No</label>
                                    </div>
                                </div>
                                <!-- Ads -->
                                <div class="mb-5">
                                    <label for="Ads" class="mb-3 block text-base font-medium text-[#07074D]">Allow Ads</label>
                                    <div class="px-3">
                                        <input id="Ads-yes" type="radio" name="Ads" value="0"  required="required" <?php if($cat['AllowAds'] == 0) {echo 'checked' ;} ?>/>
                                        <label for="Ads-yes">Yes</label>
                                    </div>
                                    <div class="px-3"> 
                                        <input id="Ads-no" type="radio" name="Ads" value="1"  required="required" <?php if($cat['AllowAds'] == 1) {echo 'checked' ;} ?>/>
                                        <label for="Ads-no">No</label>
                                    </div>
                                </div>
                                <div>
                                    <!-- button -->
                                    <button value="Add" type="submit" class="hover:scale-95 transition-ease duration-500 hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-base font-semibold text-white outline-none">Update</button>
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

                echo "<h1 class='text-center md:text-3xl sm:text-xl text-indigo-800'>Add Category</h1>";
                echo "<div class='container text-center'>";
                // get var from the form :
                $name  =  $_POST['name'];
                $Description  =  $_POST['Description'];
                $Ordering =  $_POST['Ordering'];
                $Visible  =  $_POST['Visible'];
                $Comments  =  $_POST['Comments'];
                $Ads  =  $_POST['Ads'];

                    // check if Category exists in db:
                    $check = CheckDb("Name", "categories", $name);
                    if ($check == 1){
                        ?>
                            <script>
                                window.onload = function() {
                                        swal({
                                            title: "Error",
                                            text: "Sorry, Category Is already exists",
                                            icon: "error",
                                        }).then(() => {
                                            window.location.href = 'Categories.php?action=Add';
                                        });
                                };
                            </script>
                    <?php
                    } else {
                        // Insert Category information in  db :
                        $stmt = $con->prepare("INSERT INTO 
                                                categories(Name , Description , Ordering , Visibility , AllowComment , AllowAds)
                                                VALUES(:zname , :zdescription , :zordering , :zvisible , :zcomments , :zads)");
                        $stmt->execute(array(
                            'zname'         => $name,
                            "zdescription"  => $Description,
                            "zordering"     => $Ordering,
                            "zvisible"      => $Visible,
                            "zcomments"     => $Comments,
                            "zads"          => $Ads
                        ));
                        ?>
                        <script>
                            window.onload = function() {
                                <?php if ($stmt->rowCount() > 0): ?>
                                    swal({
                                        title: "Success",
                                        text: "Category Added",
                                        icon: "success",
                                    }).then(() => {
                                        window.location.href = 'Categories.php';
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

        echo "<h1 class='text-center md:text-3xl sm:text-xl text-indigo-800'>Update Category</h1>";
        echo "<div class='container text-center'>";

            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                // get var from the form :
                $id               = $_POST['catid'];
                $name             = $_POST['name'];
                $Description      = $_POST['Description'];
                $Ordering         = $_POST['Ordering'];

                $Visible          = $_POST['Visible'];
                $Comments         = $_POST['Comments'];
                $Ads              = $_POST['Ads'];

                // check if the form is submitted successfully and no errors :
                if (!empty($name)){

                    // update Category information in  db :
                    $stmt = $con->prepare("UPDATE
                                                categories 
                                            SET     
                                                Name            = ?,
                                                Description     = ?,
                                                Ordering        = ?, 
                                                Visibility      = ?, 
                                                AllowComment    = ?,
                                                AllowAds        = ? 
                                            WHERE 
                                                ID = ?
                                        ");

                    // execute the query
                    $stmt->execute(array($name,$Description,$Ordering,$Visible,$Comments,$Ads,$id));

                    // echo success message:
                        ?>
                        <script>
                            window.onload = function() {
                                <?php if ($stmt->rowCount() > 0): ?>
                                    swal({
                                        title: "Success",
                                        text: "Category Updated",
                                        icon: "success",
                                    }).then(() => {
                                        window.location.href = 'Categories.php?action=Edit&catid=<?php echo $id ?>';
                                    });
                                    <?php else: ?>
                                        swal({
                                            title: "Confirmation!",
                                            text: "No Category Updated",
                                            icon: "warning",
                                        }).then(() => {
                                            window.location.href = 'Categories.php?action=Edit&catid=<?php echo $id ?>';
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

        }
            include $tpl . 'footer.php';

    } else {
        header('Location:index.php');
        exit();
    }

    ob_end_flush();

?>