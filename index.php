<?php
session_start();
include_once('insertNote.php');
$message = "";
include_once('connection/dbConnect.php');



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['signup'])) {
        $email = $_POST['email'];
        $usern = $_POST['usern'];
        $pass = $_POST['pass'];
        $repass = $_POST['repass'];
        $token = rand(999999, 111111);
        $status = 'unverified';
        $salt = "codeflix";
        $hashedPassword = sha1($pass . $salt);

        $conn = connectDB();

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sqli = "SELECT * FROM `accounts` WHERE `email` = :emil";
        $stm = $conn->prepare($sqli);
        $stm->bindParam(':emil', $email);
        $stm->execute();

        if ($pass !== $repass) {

            // echo "<script>alert('Verification sent to your eamil');
            // window.history.back();
            // </script>";
            echo "<script>";
            echo "document.addEventListener('DOMContentLoaded', function() {";
            echo "    document.getElementById('popup').style.display = 'block';";
            echo "});";
            echo "</script>";
            $message = "Password Doesnt Match";

        } else if (strlen($pass) < 8 || strlen($repass) < 8) {
            echo "<script>";
            echo "document.addEventListener('DOMContentLoaded', function() {";
            echo "    document.getElementById('popup').style.display = 'block';";
            echo "});";
            echo "</script>";
            $message = "Password must have at least 8 characters.";
        } else if ($stm->rowCount() > 0) {
            echo "<script>";
            echo "document.addEventListener('DOMContentLoaded', function() {";
            echo "    document.getElementById('popup').style.display = 'block';";
            echo "});";
            echo "</script>";
            $message = "Email Alredy exist";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>";
            echo "document.addEventListener('DOMContentLoaded', function() {";
            echo "    document.getElementById('popup').style.display = 'block';";
            echo "});";
            echo "</script>";
            $message = "Invalid Email";
        } else {

            try {
                $conn = connectDB();

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                insertingUser($email, $usern, $hashedPassword, $token, $status);

            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['logi'])) {

        $users = $_POST['users'];
        $passw = $_POST['passw'];
        $salt = "codeflix";
        $hashedPasswords = sha1($passw . $salt);
        $tokens = rand(999999, 111111);
        try {
            $conn = connectDB();

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sqli = "SELECT * FROM `accounts` WHERE `name` = :users AND `password` = :hashi";
            $stm = $conn->prepare($sqli);
            $stm->bindParam(':users', $users);
            $stm->bindParam(':hashi', $hashedPasswords);
            $stm->execute();

            $userData = $stm->fetch(PDO::FETCH_ASSOC);

            if ($stm->rowCount() > 0) {
                $imil = $userData['email'];
                $stat = $userData['status'];

                if ($stat == 'unverified') {
                    $_SESSION['email'] = $imil;
                    $actions = $userData['name'];


                    requestCode($tokens, $imil, $actions);
                    senEmail($tokens, $imil);
                } else {
                    $_SESSION['email'] = $imil;
                    echo "<script>alert('Welcome " . $userData['name'] . "');
                document.location.href = 'dashboard.php';
                </script>";
                }

            } else {
                echo "<script>";
                echo "document.addEventListener('DOMContentLoaded', function() {";
                echo "    document.getElementById('popup').style.display = 'block';";
                echo "});";
                echo "</script>";
                $message = "Invalid Username And Password";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login And Registration Form</title>
    <link rel="stylesheet" href="css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <!-- <header class="header">
        <nav class="navbar">
           <a href="#">Home</a>
           <a href="#">About</a>
           <a href="#">Services</a>
           <a href="#">Contact</a>

        </nav>

        <form action="#" class="search-bar">
         <input type="text" placeholder="Search..."> 
         <button type="submit"><i class='bx bx-search'></i></button>
        </form>
    </header> -->
    <div class="background"></div>
    <div class="container">
        <div class="content">
            <h2 class="logo"><i class='bx bxs-notepad'></i>MEMOix</h2>

            <div class="text-sci">
                <h2>Welcome !<br><span>To Our Notepad App.</span></h2>

                <p>The online note app is a digital platform designed for users to create, organize, and store notes
                    efficiently.</p>

                <div class="social-icons">
                    <a href=""><i class='bx bxl-facebook'></i></a>
                    <a href=""><i class='bx bxl-instagram-alt'></i></a>
                    <a href=""><i class='bx bxl-twitter'></i></a>
                    <a href=""><i class='bx bxl-youtube'></i></a>
                </div>
            </div>
        </div>

        <div class="logreg-box">
            <div class="form-box login">
                <form action="" method="post">
                    <h2>Sign In</h2>

                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-envelope'></i></span>
                        <input type="username" required name='users'>
                        <label>Username</label>
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-lock'></i></span>
                        <input type="password" required name="passw">
                        <label>Password</label>
                    </div>

                    <div class="remember-forgot">
                        <a href="forgotPass.php">Forgot Password?</a>
                    </div>

                    <button type="submit" name="logi" class="btn">Sign In</button>

                    <div class="login-register">
                        <p>Don't have an account? <a href="#" class="register-link">Sign up</a></p>
                    </div>


                </form>


            </div>

            <div class="form-box register">
                <form action="" method="post">
                    <h2>Sign Up</h2>

                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-envelope'></i></span>
                        <input type="text" required name="email">
                        <label>Email</label>
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-user-plus'></i></span>
                        <input type="username" required name="usern">
                        <label>Username</label>
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-lock'></i></span>
                        <input type="password" required name="pass">
                        <label>Password</label>
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-lock'></i></span>
                        <input type="password" required name="repass">
                        <label>Retype Password</label>
                    </div>


                    <!-- <div class="remember-forgot">
                    <label ><input type="checkbox">I agree to terms & conditions </label>
                    
                </div> -->

                    <!-- <button type="submit" class="btn" name="signup">Sign Up</button> -->
                    <input type="submit" class="btn" name="signup" value='SIGN UP'>

                    <div class="login-register">
                        <p>Already have an account? <a href="#" class="login-link">Sign In</a></p>
                    </div>

                </form>
            </div>
        </div>

    </div>

    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <p><?php echo $message ?></p>
        </div>
    </div>

    <script src="script/script.js"></script>

    <script>
        var closeButton = document.getElementsByClassName("close")[0];

        // When the user clicks the button, show the popup

        // When the user clicks on the close button, hide the popup
        closeButton.onclick = function () {
            popup.style.display = "none";
            window.history.back();
        }
    </script>
</body>

</html>
