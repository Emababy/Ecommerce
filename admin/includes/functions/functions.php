<?php


/**
 * Display errors with a consistent style.
 *
 * @param array $errors - Array of error messages to be displayed.
 *
 * @return void
 */
function displayErrors($errors)
{
    if (!empty($errors)) {
        echo '<div class="error-container my-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">';
        echo '<strong class="font-bold">Error!</strong>';
        echo '<span class="block sm:inline"> Please check the following issues:</span>';
        echo '<div class="error-message my-2">' . implode('</div><div class="error-message my-2">', $errors) . '</div>';
        echo '</div>';
    }
}





/**
 * Get comments by user ID
 * @param int $userID User ID
 * @return array Array of comments or an empty array if no comments found
 */
function getCommentsByUserID($userID) {
    global $con;
    $stmt = $con->prepare("SELECT * FROM comments WHERE User_ID = ?");
    $stmt->execute(array($userID));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Check the activation status of a user.
 *
 * This function queries the database to check if a user with the provided
 * username is activated or not.
 *
 * @param string $username The username of the user to check.
 *
 * @global PDO $con The global PDO database connection object.
 *
 * @return int Returns the number of rows in the result set. If the user is activated,
 *             it will return 1, otherwise, it will return 0.
 */
function checkUserStatus($username) {
    global $con;

    // Prepare the SQL statement to check the user status
    $stmt = $con->prepare("SELECT UserID, Username, RegStatues 
                                FROM users 
                                WHERE Username = ? AND RegStatues = 0");

    // Execute the prepared statement with the provided username
    $stmt->execute(array($username));

    // Get the number of rows in the result set
    $status = $stmt->rowCount();

    // Return the user status
    return $status;
}


    /**
     * Retrieve all categories from the database.
     *
     * This function retrieves all categories from the database and returns them as an array.
     *
     * @global PDO $con The global PDO (PHP Data Objects) database connection object.
     * @return array An array containing all categories.
     * @throws PDOException If there is an error executing the SQL query.
     */
    function getCats() {
        global $con;
        
        // Prepare and execute the SQL query to retrieve all categories.
        $getCat = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");
        $getCat->execute();
        
        // Fetch all rows from the result set and return them as an array.
        $Cats = $getCat->fetchAll();
        
        return $Cats;
    }


    /**
     * Retrieve items based on category ID.
     *
     * This function retrieves items from the database that belong to a specific category.
     *
     * @param int $Cat_ID The category ID for which items are to be retrieved.
     * @return array An array containing the retrieved items.
     * @throws PDOException If there is an error executing the SQL query.
     */
    function getItem($where,$value) {
        global $con;
        
        // Prepare and execute the SQL query to retrieve items for the specified category.
        $getItems = $con->prepare("SELECT * FROM items WHERE $where = ? ORDER BY Item_ID DESC");
        $getItems->execute(array($value));
        
        // Fetch all rows from the result set and return them as an array.
        $items = $getItems->fetchAll();
        
        return $items;
    }



    ob_start();
    // title function :
    function getTitle(){

        global $pageTitle;
        $pageTitle = (isset($pageTitle)) ? $pageTitle : "default";
        echo $pageTitle;
    }



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

    

    /**
     * Check the existence of a value in a specific column of a table.
     *
     * This function checks whether a specific value exists in a given column of a table.
     *
     * @global PDO $con The global PDO (PHP Data Objects) database connection object.
     * @param string $select The column to select for checking.
     * @param string $from The table to check for the existence of the value.
     * @param mixed $value The value to check for in the specified column.
     * @return int The number of rows that match the specified value in the given column.
     * @throws PDOException If there is an error executing the SQL query.
     */
    function CheckDb($select, $from, $value) {
        global $con;

        try {
            // Prepare and execute the SQL query to check the existence of the specified value.
            $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
            $statement->execute(array($value));

            // Get the number of rows that match the specified value and return it.
            return $statement->rowCount();
        } catch (PDOException $e) {
            // Handle any exceptions (errors) that occur during the execution of the query.
            // You might want to log the error or handle it in a way that makes sense for your application.
            throw new PDOException("Error checking $value in $from: " . $e->getMessage(), (int)$e->getCode());
        }
    }



    /**
     * Count the number of occurrences of a specific item in a table.
     *
     * This function counts the number of occurrences of a specific item in a given table.
     *
     * @param string $item The item to count.
     * @param string $table The table to select for counting.
     * @return int The number of occurrences of the specified item in the table.
     * @throws PDOException If there is an error executing the SQL query.
     */
    function CountThings($item, $table) {
        global $con;

        try {
            // Prepare and execute the SQL query to count occurrences of the specified item.
            $stmt = $con->prepare("SELECT COUNT($item) FROM $table");
            $stmt->execute();

            // Fetch the result as a single integer (the count) and return it.
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            // Handle any exceptions (errors) that occur during the execution of the query.
            // You might want to log the error or handle it in a way that makes sense for your application.
            throw new PDOException("Error counting $item in $table: " . $e->getMessage(), (int)$e->getCode());
        }
    }




    /**
     * getLatest - Fetches the latest records from the specified table based on conditions.
     *
     * Retrieves records from the specified table with optional conditions, ordered by a specified column, and limited in quantity.
     *
     * @param string $select The columns to select in the query.
     * @param string $table The name of the table from which to retrieve records.
     * @param string $order The column by which to order the results.
     * @param string|null $where Optional WHERE clause for filtering records (default is null).
     * @param int $limit The maximum number of records to retrieve (default is 5).
     * @return array An array of associative arrays containing the retrieved records.
     */
    function getLatest($select, $table, $order, $where = null, $limit = 3) {
        global $con;

        $whereClause = ($where !== null) ? "WHERE $where" : '';

        $stmt = $con->prepare("SELECT $select FROM $table $whereClause ORDER BY $order DESC LIMIT $limit");
        $stmt->execute();

        return $stmt->fetchAll();
    }





    /**
     * getLatestCommentsWithItems - Fetches the latest comments with associated item information.
     *
     * Retrieves comments along with details of the associated items based on the specified limit.
     *
     * @param int $limit The maximum number of comments to retrieve.
     * @return array An array of associative arrays containing comment and item information.
     */
    function getLatestCommentsWithItems($limit) {
        global $con; // Assuming $con is your database connection object
        
        // SQL query to retrieve comments with associated item information
        $query = "SELECT comments.*, items.Name
                    FROM comments
                    JOIN items ON comments.Items_ID = items.Item_ID
                    ORDER BY C_ID DESC
                    LIMIT :limit";
        
        // Prepare and execute the SQL query
        $stmt = $con->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        // Fetch the results as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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



?>