<?php
session_start();
include_once('insertNote.php');
$message = "";
include_once('connection/dbConnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['emilSent'])) {

        $userEmail = $_POST['userEmil'];
        $tokens = rand(999999, 111111);
        $conn = connectDB();

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM `accounts` WHERE `email` = :emil";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':emil', $userEmail);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $_SESSION['email'] = $userEmail;
            $_SESSION['forgot'] = "forgotPass";
            $actions = "forgotPass";

            requestCode($tokens, $userEmail, $actions);
            senEmail($tokens, $userEmail);

        } else {
            echo "<script>";
            echo "document.addEventListener('DOMContentLoaded', function() {";
            echo "    document.getElementById('popup').style.display = 'block';";
            echo "});";
            echo "</script>";
            $message = "Email Doesnt Exist";
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
                            <input type="text" required name='userEmil'>
                            <label>Enter Email</label>
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

    </div>
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <p><?php echo $message ?></p>
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