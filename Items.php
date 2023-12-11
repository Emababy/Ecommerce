<?php 
    session_start();
    $pageTitle = 'Create New Item';
    include 'init.php';
    if(isset($_SESSION['User'])){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $formErr = array();

            $title          = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
            $Description    = filter_var($_POST['Description'],FILTER_SANITIZE_STRING);
            $Price          = filter_var($_POST['Price'],FILTER_SANITIZE_NUMBER_INT);
            $Country        = filter_var($_POST['CountryMade'],FILTER_SANITIZE_STRING);
            $Statues        = filter_var($_POST['Statues'],FILTER_SANITIZE_NUMBER_INT);
            $Category       = filter_var($_POST['Category'],FILTER_SANITIZE_NUMBER_INT);

            if (strlen($title) < 4 ){
                $formErr[] = "Item Name Must Be More than 4 characters";
            }
            if (empty($title)){
                $formErr[] = "Please enter a title for the Item";
            }
            if (strlen($Description) < 8){
                $formErr[] = "Item Description Must Be More than 4 characters";
            }
            if (empty($Description)){
                $formErr[] = "Please enter a Description for the Item";
            }
            if (empty($Country)){
                $formErr[] = "Please enter a Country Made Of the Item";
            }
            if (empty($Price)){
                $formErr[] = "Please enter The Price Of the Item";
            }
            if (empty($Statues)){
                $formErr[] = "Please enter The Statues Of the Item";
            }
            if (empty($Category)){
                $formErr[] = "Please enter The Category Of the Item";
            }

            if (empty($formErr)){

                // Insert Item information in  db :
                $stmt = $con->prepare("INSERT INTO 
                                        items(Name , Price , Description , Country , Statues , Add_Date , Cat_ID , Member_ID )
                                        VALUES(:zName , :zPrice , :zDescription , :zCountry , :zStatues , now() , :zCategory , :zMember)");
                $stmt->execute(array(
                    'zName'         => $title,
                    "zPrice"        => $Price,
                    "zDescription"  => $Description,
                    "zCountry"      => $Country,
                    "zStatues"      => $Statues,
                    "zCategory"     => $Category,
                    "zMember"       => $_SESSION['User_ID']
                ));
                ?>
                <script>
                    window.onload = function() {
                        <?php if ($stmt->rowCount() > 0): ?>
                            swal({
                                title:  "Success",
                                text:   "Item Added",
                                icon:   "success",
                            }).then(() => {
                                window.location.href = 'Profile.php';
                            });
                        <?php endif; ?>
                    };
                </script>
<?php
                }  
    }
?>

<h1 class="text-[#6A64F1] text-4xl font-bold mb-4 text-center">Create New Item</h1>

<div class="container mx-auto p-4 bg-gray-100 rounded-lg shadow-lg">

    <!-- User Information Section -->
    <div class="mb-4 p-4 bg-blue-500 text-white rounded-t-lg">
        <i class="fas fa-info-circle"></i> Create a New Item
    </div>

    <div class="flex flex-col md:flex-row items-center justify-between">

        <!-- Form Section -->
        <div class="flex items-center justify-center p-4 md:p-8 bg-gray-100 rounded-r-lg">
            <div class="w-full max-w-[550px] bg-white p-6 rounded-md shadow-md">

            <?php
                    // Display errors only if $formErr is not empty
                    if (!empty($formErr)) {
                        displayErrors($formErr);
                    }
                ?>

                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="space-y-4">

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Name
                        </label>
                        <input type="text" id="name" name="name" placeholder="Name of the Item"
                            class="live-name w-full rounded-md border border-gray-300 bg-white py-2 px-4 text-sm text-gray-700 focus:outline-none focus:border-indigo-500 focus:shadow-md" />
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="Description" class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <input type="text" id="Description" name="Description"
                            placeholder="Describe the Item"
                            class="live-desc w-full rounded-md border border-gray-300 bg-white py-2 px-4 text-sm text-gray-700 focus:outline-none focus:border-indigo-500 focus:shadow-md" />
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="Price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                        <input type="text" name="Price" placeholder="The Price Of The Item"
                            class="live-price w-full rounded-md border border-gray-300 bg-white py-2 px-4 text-sm text-gray-700 focus:outline-none focus:border-indigo-500 focus:shadow-md" />
                    </div>

                    <!-- Country Made -->
                    <div>
                        <label for="CountryMade" class="block text-sm font-medium text-gray-700 mb-1">Country Made</label>
                        <input type="text" name="CountryMade"
                            placeholder="The Country Made Of The Item"
                            class="w-full rounded-md border border-gray-300 bg-white py-2 px-4 text-sm text-gray-700 focus:outline-none focus:border-indigo-500 focus:shadow-md" />
                    </div>

                    <!-- Statues -->
                    <div>
                        <label for="Statues" class="block text-sm font-medium text-gray-700 mb-1">Statues</label>
                        <select name="Statues"
                            class="w-full rounded-md border border-gray-300 bg-white py-2 px-4 text-sm text-gray-700 outline-none focus:border-indigo-500 focus:shadow-md">
                            <option value="">..</option>
                            <option value="1">New</option>
                            <option value="2">Used</option>
                            <option value="3">Like New</option>
                        </select>
                    </div>

                    <!-- Category Field -->
                    <div>
                        <label for="Category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="Category"
                            class="w-full rounded-md border border-gray-300 bg-white py-2 px-4 text-sm text-gray-700 mb-5 outline-none focus:border-indigo-500 focus:shadow-md">
                            <option value="">..</option>
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

                    <!-- Button -->
                    <div>
                        <button value="Add" type="submit"
                            class="hover:scale-95 transition-ease duration-500 hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-sm font-semibold text-white outline-none">
                            Add Item
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- Live Review Section -->
        <div class="my-3 p-4 bg-blue-500 text-white rounded-lg md:my-0 md:ml-4">
            <h2 class="text-lg font-semibold mb-2 text-center">Live Review</h2>

            <div class="max-w-sm rounded overflow-hidden shadow-lg bg-gray-800">
                <!-- Image-->
                <img class="w-full" src="layout/images/5856.jpg" alt="Item Image">

                <div class="px-6 py-4 live-review">
                    <div class="font-bold text-xl mb-2 caption">
                        <h3>Title</h3>
                        <p class="text-red-600 text-base">Description</p>
                    </div>
                    <span class="text-green-600 text-base price-tag">Price: $0</span>
                </div>
            </div>
        </div>

    </div>
</div>


<?php

    } else {
        header("Location: login.php");
        exit();
    }

    include $tpl . "footer.php";
?>