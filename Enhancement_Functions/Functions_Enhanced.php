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
    $username = "example_user";
    $fullName = "Example User";
    $email = "user@example.com";
    $hashedPass = password_hash("password123", PASSWORD_DEFAULT);

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
function InsertUser($username, $fullName, $email, $hashedPass) {
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


// Example usage:

/* 
    // Replace with the actual ID of the category you want to delete:
    $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0; 
    $userId = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;

    $newUsername = 'newUsername';
    $newEmail = 'newemail@example.com';
    $newFullName = 'New Full Name';
    $newHashedPassword = password_hash('newPassword', PASSWORD_DEFAULT); // Replace 'newPassword' with the new password
    if (updateUser($userIdToUpdate, $newUsername, $newEmail, $newFullName, $newHashedPassword)) {
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

/**
 * Updates user information in the 'users' table.
 *
 * @param int $userId The ID of the user to update.
 * @param string $username The new username for the user.
 * @param string $email The new email for the user.
 * @param string $fullName The new full name for the user.
 * @param string $hashedPassword The new hashed password for the user.
 *
 * @return bool Returns true if the update was successful, otherwise false.
 * @throws PDOException If an error occurs during the database operation.
 */
function updateUser($userId, $username, $email, $fullName, $hashedPassword) {
    global $con; // Assuming $con is your database connection

    try {
        // Prepare and execute the SQL query to update user information
        $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
        $stmt->execute(array($username, $email, $fullName, $hashedPassword, $userId));

        // Return true if the update was successful, otherwise false
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        // Handle any exceptions (errors) that occur during the execution of the query.
        // You might want to log the error or handle it in a way that makes sense for your application.
        throw new PDOException("Error updating user information: " . $e->getMessage(), (int)$e->getCode());
    }
}



// Example usage:

/*
    Replace with the actual ID of the category you want to delete:
    $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0; 
    $userId = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;

    if (deleteRecord('categories', 'ID', $catid)) {
        ?>
        <script>
            window.onload = function() {
                <?php if ($stmt->rowCount() > 0): ?>
                    swal({
                        title: "Success",
                        text: "Example Deleted",
                        icon: "success",
                    }).then(() => {
                        window.location.href = 'Example.php?action=Manage';
                    });
                <?php endif; ?>
            };
        </script>
    <?php
    } else {
        ?>
        <script>
            window.onload = function() {
                swal({
                    title: "Error",
                    text: "Sorry, NO Example Found To Delete",
                    icon: "error",
                }).then(() => {
                    window.location.href = 'example.php';
                });
            };
        </script>
    <?php
    }
*/

/**
 * Deletes a record from the specified table based on the given ID.
 *
 * @param string $tableName The name of the table from which to delete the record.
 * @param string $columnName The name of the column used to identify the record.
 * @param mixed $id The ID of the record to delete.
 *
 * @return bool Returns true if the deletion was successful, otherwise false.
 * @throws PDOException If an error occurs during the database operation.
 */
function deleteRecord($tableName, $columnName, $id) {
    global $con; // Assuming $con is your database connection

    try {
        // Prepare and execute the SQL query to delete the record
        $stmt = $con->prepare("DELETE FROM $tableName WHERE $columnName = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        // Return true if the deletion was successful, otherwise false
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        // Handle any exceptions (errors) that occur during the execution of the query.
        // You might want to log the error or handle it in a way that makes sense for your application.
        throw new PDOException("Error deleting record from $tableName: " . $e->getMessage(), (int)$e->getCode());
    }
}







?>