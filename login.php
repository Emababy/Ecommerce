<?php 
    ob_start();
    session_start();
    $pageTitle = 'Login';
    if (isset($_SESSION['User'])){
        header('Location: index.php');  // Redirect to HomePage
    }
    include 'InitLoginSignUp.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $Username = $_POST['Username'];
        $Password = $_POST['Password'];
    
        // Check if the user exists
        $stmt = $con->prepare("SELECT UserID, Username, Password, GroupID FROM users WHERE Username = ?");
        $stmt->execute(array($Username));
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($userData) {
            // Verify the entered password with the stored hashed password
            if (password_verify($Password, $userData['Password'])) {
                $_SESSION['User'] = $Username;  // Save session name 
                header('Location: index.php');  // Redirect to index page
                exit();
            } else {
                echo '<div class="alert alert-danger">Invalid username or password</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Invalid username or password</div>';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php getTitle() ?></title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="<?php echo $css; ?>all.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>fontawesome.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>style_user.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800;900&family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/alpine-collective/alpine-magic-helpers@0.5.x/dist/component.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</head>

<div class="h-screen flex">
            <div class="hidden lg:flex w-full lg:w-1/2 login_img_section justify-around items-center">
                <div class=" bg-black opacity-20 inset-0 z-0"></div>
                <div class="w-full mx-auto px-20 flex-col items-center space-y-6">
                    <h1 class="text-white font-bold text-4xl font-sans capitalize">Embaby</h1>
                    <p class="text-white mt-1 capitalize">you can find anything here</p>
                    <div class="flex justify-center lg:justify-start mt-6">
                        <a href="Login.php" class="hover:bg-indigo-700 hover:text-white hover:-translate-y-1 transition-all duration-500 bg-white text-indigo-800 mt-4 px-4 py-2 rounded-2xl font-bold mb-2">Get Started</a>
                    </div>
                </div>
            </div>
            <div class="flex w-full lg:w-1/2 justify-center items-center bg-white space-y-8">
                <div class="w-full px-8 md:px-32 lg:px-24">
                <form class="bg-white rounded-md shadow-2xl p-5" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                    <h1 class="text-gray-800 font-bold text-2xl mb-1">Hello Again!</h1>
                    <p class="text-sm font-normal text-gray-600 mb-8">Welcome Back</p>
                    <div class="flex items-center border-2 mb-8 py-2 px-3 rounded-2xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                        <input class=" pl-2 w-full outline-none border-none" type="text" name="Username" placeholder="UserName" autocomplete="off" />
                    </div>
                    <div class="flex items-center border-2 mb-12 py-2 px-3 rounded-2xl ">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fillRule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clipRule="evenodd" />
                        </svg>
                        <input class="pl-2 w-full outline-none border-none" type="password" name="Password"  placeholder="Password" autocomplete="off" />
                    </div>
                    <button type="submit" name="Login" value="Login" class="block w-full bg-indigo-600 mt-5 py-2 rounded-2xl hover:bg-indigo-700 hover:-translate-y-1 transition-all duration-500 text-white font-semibold mb-2">Login</button>
                    <div class="flex justify-between mt-4">
                        <a href="SignUp.php" class="text-sm ml-2 hover:text-blue-500 cursor-pointer hover:-translate-y-1 duration-500 transition-all">Don't have an account yet?</a>
                    </div>
                </form>
                </div>
            </div>
</div>

<?php
    include $tpl . "footer.php";
    ob_end_flush();

?>