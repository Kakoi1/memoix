<?



?>
<?php
session_start();
include_once('connection/dbConnect.php');
$message = "";
// session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['sent'])) {


        $codes = $_POST['code'];
        $forgot = isset($_SESSION['forgot']) ? $_SESSION['forgot'] : "";
        $usr = isset($_SESSION['use']) ? $_SESSION['use'] : "";
        $mil = isset($_SESSION['imil']) ? $_SESSION['imil'] : "";
        $pas = isset($_SESSION['pass']) ? $_SESSION['pass'] : "";
        $action = isset($_SESSION['useredit']) ? $_SESSION['useredit'] : "";
        $userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : "";


        try {
            $conn = connectDB();

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM `accounts` WHERE  email = :mail AND `reset_token` = :code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':mail', $userEmail);
            $stmt->bindParam(':code', $codes);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                $sqli = "UPDATE `accounts` SET `reset_token`= 0,`status`='verified' WHERE `reset_token` = :cods ";

                $stmt = $conn->prepare($sqli);
                $stmt->bindParam(':cods', $codes);

                $stmt->execute();

                $userData = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($forgot == "forgotPass") {

                    echo "<script>alert('Code Verified');
            document.location.href = 'passwordForm.php';
            </script>";
                    $_SESSION['forgot'] = "";

                } elseif ($action == "userEdit") {

                    $sqli = "UPDATE `accounts` SET `name`= :user,`password`= :pass,`reset_token`= 0 WHERE `email` = :mail";
                    $stm = $conn->prepare($sqli);
                    $stm->bindParam(':mail', $mil);
                    $stm->bindParam(':user', $usr);
                    $stm->bindParam(':pass', $pas);

                    $stm->execute();
                    if ($stm->rowCount() > 0) {
                        echo "<script>alert('Edit Success');
                    document.location.href = 'logout.php';
                    </script>";
                    }

                } else {

                    echo "<script>alert('Verification Complete');
        document.location.href = 'logout.php';
        </script>";
                }
            } else {
                echo "<script>";
                echo "document.addEventListener('DOMContentLoaded', function() {";
                echo "    document.getElementById('popup').style.display = 'block';";
                echo "});";
                echo "</script>";
                $message = "Wrong Verification Code.";
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
                        <h2>Verification</h2>

                        <div class="input-box">
                            <span class="icon"><i class='bx bx-message-rounded-dots'></i></span>
                            <input type="text" required name='code'>
                            <label>Verification Code</label>
                        </div>


                        <div class="remember-forgot">
                            <a href="logout.php">Log in Form</a>
                        </div>

                        <button type="submit" class="btn" name="sent">Submit</button>

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
