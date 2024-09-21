<?php
session_start();
include_once('connection/dbConnect.php');

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['emilSent'])) {

        $pass = $_POST['pass'];
        $repass = $_POST['repass'];
        $salt = "codeflix";
        $hashedPassword = sha1($pass . $salt);
        $passEmail = $_SESSION['email'];

        if ($pass !== $repass) {

            echo "<script>";
            echo "document.addEventListener('DOMContentLoaded', function() {";
            echo "    document.getElementById('popup').style.display = 'block';";
            echo "});";
            echo "</script>";
            $message = "Password Doesn't Match";

        } else if (strlen($pass) < 8 || strlen($repass) < 8) {

            echo "<script>";
            echo "document.addEventListener('DOMContentLoaded', function() {";
            echo "    document.getElementById('popup').style.display = 'block';";
            echo "});";
            echo "</script>";
            $message = "Password must have at least 8 characters.";

        } else {
            try {

                $conn = connectDB();
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sqli = "UPDATE `accounts` SET `password`= :pass WHERE `email` = :imil";
                $stmt = $conn->prepare($sqli);
                $stmt->bindParam(':pass', $hashedPassword);
                $stmt->bindParam(':imil', $passEmail);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    echo "<script>alert('Password Reseted');
                        document.location.href = 'logout.php';
                    </script>";
                }


            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }


        }

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Code Verification</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="background"></div>
    <div class="container">
        <div class="content">
            <div class="logregbox">
                <div class="form-box login">
                    <form action="" method="post">
                        <h2>Forgot Password</h2>

                        <div class="input-box">
                            <span class="icon"><i class='bx bxs-envelope'></i></span>
                            <input type="password" required name='pass'>
                            <label>New Password</label>
                        </div>


                        <div class="input-box">
                            <span class="icon"><i class='bx bxs-envelope'></i></span>
                            <input type="password" required name='repass'>
                            <label>Retype Password</label>
                        </div>


                        <div class="remember-forgot">
                            <a href="logout.php">Log in Form</a>
                        </div>

                        <button type="submit" class="btn" name="emilSent">Submit</button>

                        <!-- <div class="login-register">
                    <p>Don't have an account? <a href="#" class="register-link">Sign up</a></p>
                </div> -->


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
    </div>



    <script>
        var closeButton = document.getElementsByClassName("close")[0];

        closeButton.onclick = function () {
            popup.style.display = "none";
            window.history.back();
        }
    </script>

</body>

</html>
