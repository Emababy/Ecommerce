
<div class="Main Functions">
    <?php 

    /* 
        InsertUser Function
        Description
        The InsertUser function is used to insert a new user into the database. It takes user details such as username, full name, email, and hashed password as parameters.

        Parameters
        $username (string): The username of the new user.
        $fullName (string): The full name of the new user.
        $email (string): The email address of the new user.
        $hashedPass (string): The hashed password of the new user.
        Return Value
        boolean: Returns true if the user is inserted successfully, and false if the insertion fails.

    */

    // Example usage:
    /*
        $username   = "$_POST['example_user']";
        $fullName   = "$_POST['FullName']";
        $email      = "$_POST['Email']";
        $hashedPass = password_hash($_POST['Password'], PASSWORD_DEFAULT);

        if (InsertUser($username, $fullName, $email, $hashedPass)) {
                ?>
                        <script>
                            window.onload = function() {
                                swal({
                                    title: "Success",
                                    text: "example Successfully",
                                    icon: "success",
                                }).then(() => {
                                    window.location.href = 'example.php';
                                });
                            };
                        </script>
                <?php
        } else {
                ?>
                    <script>
                        window.onload = function() {
                            swal({
                                title: "Error",
                                text: "Sorry, You Have Already example",
                                icon: "error",
                            }).then(() => {
                                window.location.href = 'example.php';
                            });
                        };
                    </script>
                <?php
        }
    */
    function InsertUserAndSignUp($username, $fullName, $email, $hashedPass) {
        global $con;

        try {
            // Prepare the SQL query to insert a new user
            $stmt = $con->prepare("INSERT INTO users (Username, FullName, Email, Password, GroupID, RegStatus, Date) 
                                    VALUES (:UserName, :UserFullName, :UserEmail, :UserPassword, 0, 0, NOW())");

            // Bind parameters
            $stmt->bindParam(':UserName', $username);
            $stmt->bindParam(':UserFullName', $fullName);
            $stmt->bindParam(':UserEmail', $email);
            $stmt->bindParam(':UserPassword', $hashedPass);

            // Execute the query
            $stmt->execute();

            // Check if the user was inserted successfully
            if ($stmt->rowCount() > 0) {
                return true; // Insertion successful
            } else {
                return false; // Insertion failed
            }
        } catch (PDOException $e) {
            // Handle any exceptions (errors) that occur during the execution of the query.
            // You might want to log the error or handle it in a way that makes sense for your application.
            throw new PDOException("Error inserting user: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    #####################################################################################################################

    // Example usage:
    // $con = new PDO("your_database_connection_details");
    // $formErrArray = validateAndCheckDatabase($_POST['Username'], $_POST['Email'], $_POST['Password'], $_POST['FullName']);
    // if (empty($formErrArray)) {
    //     // Proceed with user registration or other actions
    // }
    // Handle validation errors...

    /**
     * Validate user input and check for existing records in the database.
     *
     * @param PDO $con           The PDO database connection object.
     * @param string $username   The username to be checked.
     * @param string $email      The email address to be checked.
     * @param string $password   The password to be checked.
     * @param string $fullName   The full name to be checked.
     *
     * @return array             An array of validation errors. If the array is empty, validation is successful.
     */
    function validateSignUpFormsAndCheckDb(string $username, string $email, string $password, string $fullName): array {

        global $con;

        $formErr = array();

        if (strlen($username) < 4 || strlen($username) > 20 || empty($username)) {
            $formErr[] = '<strong>User Must Be At least 4 Characters And less than 20 characters</strong>';
        }
        
        if (empty($password) || strlen($password) <= 5) {
            $formErr[] = '<strong>Password Can\'t Be Empty And Must Be More Than 5 Characters</strong>';
        }
        
        if (empty($fullName)) {
            $formErr[] = '<strong>Please enter Your Full Name</strong>';
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $formErr[] = '<strong>Please enter a valid Email Address</strong>';
        }

        if (empty($formErr)) {
            // Check if user exists in the database
            $check = CheckDb("Username,Email", "users", "Username", $username);

            if ($check) {
                // Handle existing account
                ?>
                <script>
                    window.onload = function() {
                        swal({
                            title: "Error",
                            text: "Sorry, You Have Already Account",
                            icon: "error",
                        }).then(() => {
                            window.location.href = 'login.php';
                        });
                    };
                </script>
                <?php
            }
        }

        return $formErr;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function CheckDb($select, $from, $whereColumn, $whereValue) {
        global $con;

        try {
            // Prepare and execute the SQL query to check the existence of the specified value.
            $statement = $con->prepare("SELECT $select FROM $from WHERE $whereColumn = ?");
            $statement->execute(array($whereValue));

            // Fetch the result as an associative array.
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            // Return the result (associative array) or null if no matching row is found.
            return $result;
        } catch (PDOException $e) {
            // Handle any exceptions (errors) that occur during the execution of the query.
            // You might want to log the error or handle it in a way that makes sense for your application.
            throw new PDOException("Error checking $whereValue in $from: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    #####################################################################################################################

    // Example usage:
    // $result = authenticateUser($con, $_POST['Username'], $_POST['Password']);
    // if ($result) {
    //     $_SESSION['User'] = $result['Username'];
    //     header('Location: index.php');
    //     exit();
    // }

    /**
     * Authenticate user based on provided credentials.
     *
     * @param string $username The username entered by the user.
     * @param string $password The password entered by the user.
     *
     * @return array|null Returns user data (UserID, Username, Password, GroupID) if authentication is successful,
     *                   otherwise returns null.
     */
    function Login(string $username, string $password): ?array {
        global $con;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Check if the user exists
            $stmt = $con->prepare("SELECT UserID, Username, Password, GroupID FROM users WHERE Username = ?");
            $stmt->execute(array($username));
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            $formErr = array();

            if ($userData) {
                // Verify the entered password with the stored hashed password
                if (password_verify($password, $userData['Password'])) {
                    // Authentication successful
                    return $userData;
                } else {
                    $formErr[] = '<strong>Invalid username or password</strong>';
                }
            } else {
                $formErr[] = '<strong>Invalid username or password</strong>';
            }
        }
        return $formErr;
    }

    ######################################################################################################################

    // Example Usage:
        // Display errors only if $formErr is not empty
        // if (!empty($formErr)) {
        //     displayErrors($formErr);
        // }


    /**
     * Display errors with a consistent style.
     *
     * @param array $errors - Array of error messages to be displayed.
     *
     * @return void
     */
    function displayErrors($errors){
        if (!empty($errors)) {
            echo '<div class="error-container my-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">';
            echo '<strong class="font-bold">Error!</strong>';
            echo '<span class="block sm:inline"> Please check the following issues:</span>';
            echo '<div class="error-message my-2">' . implode('</div><div class="error-message my-2">', $errors) . '</div>';

            echo '</div>';
        }
    }

    #########################################################################################################################


    // Example usage:
    // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //     updateUser($_POST['ID'], $_POST['username'], $_POST['email'], $_POST['fullName'], $_POST['newPassword'], $_POST['oldPassword']);
    // } else {
    //     $errorMsg = ' You\'re Not Allowed To Be Here';
    //     redirect($errorMsg , 4 , 'Dashboard.php');
    // }


    /**
     * Update user information in the database based on the provided parameters.
     *
     * @param PDO $con      The PDO database connection object.
     * @param int $id       The user ID to update.
     * @param string $user  The new username.
     * @param string $email The new email.
     * @param string $name  The new full name.
     * @param string $newPassword The new password (can be empty if not changed).
     * @param string $oldPassword The old password for validation.
     */
    function updateUser(int $id, string $user, string $email, string $name, string $newPassword, string $oldPassword): void {

        global $con;

        try {
            // Check if the form is submitted successfully and no errors
            $formErr = validateUpdateFormUser($user, $name, $email);
            
            if (!empty($formErr)) {
                displayErrors($formErr);
                ?>
                <div>
                    <!-- button -->
                    <a href="members.php?action=Edit&ID=<?php echo $id ?>"><button value="Return" type="submit" class="hover:scale-95 transition-ease duration-500 hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-base font-semibold text-white outline-none">Return</button></a>
                </div>
                <?php
            } else {
                // Check if a new password is provided
                $pass = empty($newPassword) ? $oldPassword : password_hash($newPassword, PASSWORD_DEFAULT);

                // Update user information in db
                $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
                $stmt->execute(array($user, $email, $name, $pass, $id));

                // Echo success message
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
        } catch (PDOException $e) {
            // Handle any exceptions (errors) that occur during the execution of the query
            // You might want to log the error or handle it in a way that makes sense for your application
            throw new PDOException("Error updating user: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    #####################################################################################################################

    /**
     * Validate the form data.
     *
     * @param string $user  The username.
     * @param string $name  The full name.
     * @param string $email The email address.
     *
     * @return array An array containing validation error messages.
     */
    function validateUpdateFormUser(string $user, string $name, string $email): array {
        $formErr = array();
        if (strlen($user) < 4 || strlen($user) > 20 || empty($user)) {
            $formErr[] = '<strong>User Must Be At least 4 Characters And less than 20 characters</strong>';
        }
        if (empty($name)) {
            $formErr[] = '<strong>Please enter Your Full Name</strong>';
        }
        if (empty($email)) {
            $formErr[] = '<strong>Please enter Your Email Address</strong>';
        }

        return $formErr;
    }

    #####################################################################################################################

    // Example Usage:

    // $formErr = validateInsertItemForm($name,$Price,$Description,$CountryMade,$Statues,$Member,$Category);

    /**
     * Validate the form data and display errors if any.
     *
     * @param array $formData An associative array containing the form data.
     */
    function validateInsertUpdateItemForm($name,$Price,$Description,$CountryMade,$Statues,$Member,$Category){
        $formErr = array();

        // Validate each form field
        if (empty($name['name'])) {
            $formErr[] = '<strong>Name Can\'t Be Empty</strong>';
        }
        if (empty($Price['Price'])) {
            $formErr[] = '<strong>Price Can\'t Be Empty</strong>';
        }
        if (empty($Description['Description'])) {
            $formErr[] = '<strong>Description Can\'t Be Empty</strong>';
        }
        if (empty($CountryMade['CountryMade'])) {
            $formErr[] = '<strong>Country Made Can\'t Be Empty</strong>';
        }
        if ($Statues['Statues'] == 0) {
            $formErr[] = '<strong>You Must Choose An Option for Statues</strong>';
        }
        if ($Member['Member'] == 0) {
            $formErr[] = '<strong>You Must Choose An Option for Member</strong>';
        }
        if ($Category['Category'] == 0) {
            $formErr[] = '<strong>You Must Choose An Option for Category</strong>';
        }

        return $formErr;
    }

    ####################################################################################################################

    // Example usage:
    // addItem($con, $name, $price, $description, $country, $statues, $category, $member);

    /**
     * Add a new item to the database.
     *
     * @param string $name     The name of the item.
     * @param float $price     The price of the item.
     * @param string $description The description of the item.
     * @param string $country  The country of origin.
     * @param int $statues     The statues of the item.
     * @param int $category    The category ID of the item.
     * @param int $member      The member ID of the item.
     */
    function addItem(string $name, float $price, string $description, string $country, int $statues, int $category, int $member): void {

        global $con;

        try {
            // Prepare and execute the SQL query to insert the new item.
            $stmt = $con->prepare("INSERT INTO 
                                    items(Name , Price , Description , Country , Statues , Add_Date , Cat_ID , Member_ID)
                                    VALUES(:zName , :zPrice , :zDescription , :zCountry , :zStatues , now() , :zCategory , :zMember)");

            $stmt->execute(array(
                'zName'         => $name,
                'zPrice'        => $price,
                'zDescription'  => $description,
                'zCountry'      => $country,
                'zStatues'      => $statues,
                'zCategory'     => $category,
                'zMember'       => $member
            ));

            // Output JavaScript code to redirect on success.
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
        } catch (PDOException $e) {
            // Handle any exceptions (errors) that occur during the execution of the query.
            // You might want to log the error or handle it in a way that makes sense for your application.
            throw new PDOException("Error adding item: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    ####################################################################################################################

    // Example usage:
    // updateItem($con, $itemID, $name, $description, $price, $country, $statues, $category, $member);]

    /**
     * Update an item in the database.
     *
     * @param int $itemID      The ID of the item to update.
     * @param string $name     The updated name of the item.
     * @param string $description The updated description of the item.
     * @param float $price     The updated price of the item.
     * @param string $country  The updated country of origin.
     * @param int $statues     The updated statues of the item.
     * @param int $category    The updated category ID of the item.
     * @param int $member      The updated member ID of the item.
     */
    function updateItem(int $itemID, string $name, string $description, float $price, string $country, int $statues, int $category, int $member): void {

        global $con;

        try {
            // Prepare and execute the SQL query to update the item.
            $stmt = $con->prepare("UPDATE 
                                    items 
                                SET
                                    Name        = ?,
                                    Description = ?,
                                    Price       = ?, 
                                    Country     = ?,
                                    Statues     = ?,
                                    Cat_ID      = ?,
                                    Member_ID   = ?
                                WHERE 
                                    Item_ID = ?");
            $stmt->execute(array($name, $description, $price, $country, $statues, $category, $member, $itemID));

            // Output JavaScript code to redirect on success.
            ?>
            <script>
                window.onload = function() {
                    <?php if ($stmt->rowCount() > 0): ?>
                        swal({
                            title: "Success",
                            text: "Item Updated",
                            icon: "success",
                        }).then(() => {
                            window.location.href = 'Items.php?action=Edit&ItemID=<?php echo $itemID ?>';
                        });
                    <?php else: ?>
                        // Output JavaScript code for failure.
                        swal({
                            title: "Failed!",
                            text: "No Item Updated",
                            icon: "warning",
                        }).then(() => {
                            window.location.href = 'Items.php?action=Edit&ItemID=<?php echo $itemID ?>';
                        });
                    <?php endif; ?>
                };
            </script>
            <?php 
        } catch (PDOException $e) {
            // Handle any exceptions (errors) that occur during the execution of the query.
            // You might want to log the error or handle it in a way that makes sense for your application.
            throw new PDOException("Error updating item: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    #####################################################################################################################

    // Example usage:
    // $allItems = getAllItems();
    // if ($allItems) {
    //     // Process the retrieved items as needed
    // }
    // Handle retrieval errors...

    /**
     * Get all items with additional information from the database.
     *
     * @param PDO $con The PDO database connection object.
     *
     * @return array|null An array containing all items with additional information, or null if an error occurs.
     */
    function getAllItems(PDO $con): ?array {
        try {
            // Select all items with additional information using INNER JOINs.
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

            // Fetch all items as an associative array.
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return the result (array of items with additional information) or null if no items are found.
            return $items;
        } catch (PDOException $e) {
            // Handle any exceptions (errors) that occur during the execution of the query.
            // You might want to log the error or handle it in a way that makes sense for your application.
            throw new PDOException("Error retrieving items: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    #####################################################################################################################

    // Example usage:
    // and $sort contains the sorting order from $_GET or elsewhere
    // $sort = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';
    // $categories = getCategories($con, $sort);

    /**
     * Get categories from the database and apply sorting.
     *
     * @param string $sort  The sorting order ('ASC' or 'DESC').
     * @return array        An array of categories.
     */
    function getCategories(string $sort = 'ASC'): array {
        global $con;
        $sortArray = array('ASC', 'DESC');
        $sort = in_array($sort, $sortArray) ? $sort : 'ASC';

        try {
            // Prepare and execute the SQL query to select categories with sorting.
            $stmt = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle any exceptions (errors) that occur during the execution of the query.
            // You might want to log the error or handle it in a way that makes sense for your application.
            throw new PDOException("Error fetching categories: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    ####################################################################################################################

    // Example usage:
    // addCategory($con, $name, $description, $ordering, $visible, $comments, $ads);

    /**
     * Add a new category to the database.
     *
     * @param string $name     The name of the category.
     * @param string $description The description of the category.
     * @param int $ordering    The ordering value of the category.
     * @param int $visible     The visibility status of the category.
     * @param int $comments    The comments status of the category.
     * @param int $ads         The ads status of the category.
     */
    function addCategory(string $name, string $description, int $ordering, int $visible, int $comments, int $ads): void {

        global $con;

        try {
            // Prepare and execute the SQL query to insert the new category.
            $stmt = $con->prepare("INSERT INTO 
                                    categories(Name, Description, Ordering, Visibility, AllowComment, AllowAds)
                                    VALUES(:zname, :zdescription, :zordering, :zvisible, :zcomments, :zads)");

            $stmt->execute(array(
                'zname'         => $name,
                'zdescription'  => $description,
                'zordering'     => $ordering,
                'zvisible'      => $visible,
                'zcomments'     => $comments,
                'zads'          => $ads
            ));

            // Output JavaScript code to redirect on success.
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
        } catch (PDOException $e) {
            // Handle any exceptions (errors) that occur during the execution of the query.
            // You might want to log the error or handle it in a way that makes sense for your application.
            throw new PDOException("Error adding category: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    ####################################################################################################################


    // Example usage:
    // updateCategory($id, $name, $description, $ordering, $visible, $comments, $ads);

    /**
     * Update category information in the database.
     *
     * @param int $id          The ID of the category to update.
     * @param string $name     The updated name of the category.
     * @param string $description The updated description of the category.
     * @param int $ordering    The updated ordering value of the category.
     * @param int $visible     The updated visibility status of the category.
     * @param int $comments    The updated comments status of the category.
     * @param int $ads         The updated ads status of the category.
     */
    function updateCategory( int $id, string $name, string $description, int $ordering, int $visible, int $comments, int $ads): void {

        global $con;

        try {
            // Prepare and execute the SQL query to update the category.
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

            // Execute the query
            $stmt->execute(array($name, $description, $ordering, $visible, $comments, $ads, $id));

            // Output JavaScript code to redirect on success.
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
        } catch (PDOException $e) {
            // Handle any exceptions (errors) that occur during the execution of the query.
            // You might want to log the error or handle it in a way that makes sense for your application.
            throw new PDOException("Error updating category: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    #####################################################################################################################

    // Example usage:
    // $comments = getAllComments($con);

    /**
     * Get all comments from the database.
     *
     * @return array An array containing all comments with additional information.
     */
    function getAllComments(): array {
        global $con;
        try {
            // Prepare and execute the SQL query to select all comments.
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

            // Fetch the result as an associative array.
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $rows;

        } catch (PDOException $e) {
            // Handle any exceptions (errors) that occur during the execution of the query.
            // You might want to log the error or handle it in a way that makes sense for your application.
            throw new PDOException("Error getting comments: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    ###################################################################################################################

    // Example usage:
    // $comment and $cID contain the updated comment text and comment ID respectively
    // updateComment($comment, $cID);

    /**
     * Update a comment in the database.
     *
     * @param string $comment The updated comment text.
     * @param int $cID        The ID of the comment to update.
     */
    function updateComment(string $comment, int $cID): void {

        global $con;

        try {
            // Prepare and execute the SQL query to update the comment.
            $stmt = $con->prepare("UPDATE comments SET Comment = ? WHERE C_ID = ?");
            $stmt->execute(array($comment, $cID));

            // Output JavaScript code to redirect on success.
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
        } catch (PDOException $e) {
            // Handle any exceptions (errors) that occur during the execution of the query.
            // You might want to log the error or handle it in a way that makes sense for your application.
            throw new PDOException("Error updating comment: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    #####################################################################################################################


    // Example usage:
    // deleteRecord('your_table', 'your_column', $your_record_id,items);

    /**
     * Delete a record from the database based on the provided parameters.
     *
     * @param string $tableName  The name of the table.
     * @param string $columnName The name of the column to identify the record.
     * @param mixed $id          The value of the identifier to delete the record.
     * @param string $redirectUrl The URL to redirect to after the operation.
     */
    function deleteRecord(string $tableName, string $columnName, $id, string $redirectUrl): void {
        global $con;
        try {
            // Check if the record exists in the database.
            $check = checkDb($columnName, $tableName, $id);

            if ($check > 0) {
                // Prepare and execute the SQL query to delete the record.
                $stmt = $con->prepare("DELETE FROM $tableName WHERE $columnName = :id");
                $stmt->bindParam(":id", $id);
                $stmt->execute();

                // Output JavaScript code to redirect on success.
                ?>
                <script>
                    window.onload = function() {
                        <?php if ($stmt->rowCount() > 0): ?>
                            swal({
                                title: "Success",
                                text: "<?= $tableName ?> Deleted",
                                icon: "success",
                            }).then(() => {
                                window.location.href = '<?= $redirectUrl ?>.php?action=Manage';
                            });
                        <?php endif; ?>
                    };
                </script>
                <?php 
            } else {
                // Output JavaScript code or handle the case when the record doesn't exist.
                ?>
                <script>
                    window.onload = function() {
                        swal({
                            title: "Error",
                            text: "<?= $tableName ?> Not Found",
                            icon: "error",
                        }).then(() => {
                            window.location.href = '<?= $redirectUrl ?>.php?action=Manage';
                        });
                    };
                </script>
                <?php 
            }
        } catch (PDOException $e) {
            // Handle any exceptions (errors) that occur during the execution of the query.
            // You might want to log the error or handle it in a way that makes sense for your application.
            throw new PDOException("Error deleting record from $tableName: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    #####################################################################################################################

    /**
     * Activate a record in the database by setting a specified column to 1.
     *
     * @param PDO $con               The PDO database connection object.
     * @param string $table          The name of the table.
     * @param string $columnToActivate The name of the column to activate.
     * @param string $primaryKey     The primary key column name.
     * @param mixed $primaryKeyValue The value of the primary key to identify the record.
     * * @throws PDOException If an error occurs during the database operation.
     */

    function activate(string $table, string $columnToActivate, string $primaryKey, $primaryKeyValue){

        global $con;

        try {
            // Check if the record exists in the database.
            $check = checkDb($primaryKey, $table, $primaryKeyValue);

            if ($check > 0) {
                // Update the specified column to 1 to activate the record.
                $stmt = $con->prepare("UPDATE $table SET $columnToActivate = 1 WHERE $primaryKey = ? ");
                $stmt->execute([$primaryKeyValue]);

                // Output JavaScript code to redirect on success.
                echo "<script>
                        window.onload = function() {
                            swal({
                                title: 'Success',
                                text: 'Record Activated',
                                icon: 'success',
                            }).then(() => {
                                window.location.href = 'example.php';
                            });
                        };
                    </script>";
            } else {
                // Output JavaScript code to redirect on failure.
                echo "<script>
                        window.onload = function() {
                            swal({
                                title: 'Error',
                                text: 'Record Not Found',
                                icon: 'error',
                            }).then(() => {
                                window.location.href = 'Dashboard.php';
                            });
                        };
                    </script>";
            }
        } catch (PDOException $e) {
            // Handle any exceptions (errors) that occur during the execution of the query.
            throw new PDOException("Error activating record: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    #####################################################################################################################

    /**
     * Redirects the user to a specified URL after displaying a message.
     *
     * @param string $errorMsg The error message to display.
     * @param int $seconds The number of seconds before redirection (default is 3).
     * @param string $redirectUrl The URL to redirect to (default is 'index.php').
     */
    function redirect($errorMsg, $seconds = 3, $redirectUrl = 'index.php') {
        // Escape the error message
        $escapedErrorMsg = htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8');
        
        // Output HTML with Tailwind CSS classes
        echo '<div class="container mx-auto my-4 p-5 bg-red-500 text-white">' . $escapedErrorMsg  . '</div>';
        echo '<div class="container mx-auto my-4 p-5 bg-blue-500 text-white">You will be redirected to the home page after ' . $seconds . ' seconds.</div>';

        // Perform the redirection
        header("refresh:$seconds;url=$redirectUrl");
        exit();
    }

    ##################################################################################################################
    ?>
</div>

<!--******************************************************************************************************************************** -->

<div>
    <?php 

    

    ?>
</div>






