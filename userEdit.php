<?php
session_start();
include_once('connection/dbConnect.php');
include_once('insertNote.php');
$message = "";
?>

<?php
$conn = connectDB();
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['sav'])) {

        $token = rand(999999, 111111);
        $imil = $_POST['imil'];
        $pass = $_POST['passw'];
        $repass = $_POST['repass'];
        $use = $_POST['username'];
        $salt = "codeflix";
        $hashedPassword = sha1($pass . $salt);

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
            $_SESSION['use'] = $use;
            $_SESSION['imil'] = $imil;
            $_SESSION['pass'] = $hashedPassword;
            $_SESSION['useredit'] = "userEdit";

            // $spl = "UPDATE `accounts` SET `reset_token`= :toks WHERE email = :imil";
            // $stmt = $conn->prepare($spl);
            // $stmt->bindParam(':toks', $token);
            // $stmt->bindParam(':imil', $imil);
            // $stmt->execute();
            $actions = "userEdit";


            requestCode($token, $imil, $actions);
            senEmail($token, $imil);
            //     if ($stmt->rowCount() > 0) {
            //         senEmail($token, $imil);
            //         echo "<script>alert('Edit Success');
            //         document.location.href = 'mailer.php';
            //         </script>";

            // }
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="background"></div>
    <div class="container">
        <div class="content">
            <div class="logregbox">
                <div class="form-box login">

                    <?php


                    try {
                        $conn = connectDB();
                        $emails = $_SESSION['email'];

                        if ($emails != false) {

                            $sql = "SELECT `id`, `name`, `email`, `password` FROM `accounts` WHERE `email` = :imil";
                            $stmt = $conn->prepare($sql);
                            $stmt->bindParam(':imil', $emails);
                            $stmt->execute();

                            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($userData) {
                                // User data found, render the edit form
                    
                                ?>
                                <form action="" method="post">
                                    <h2>Verification</h2>
                                    <input type="hidden" value="<?php echo $userData['email'] ?>" name="imil" required>
                                    <input type="hidden" value="<?php echo $userData['id'] ?>" name="id" required>

                                    <div class="input-box">
                                        <span class="icon"><i class='bx bxs-user-plus'></i></span>
                                        <input type="text" value="<?php echo $userData['name'] ?>" name="username" required>
                                        <label>Username</label>
                                    </div>

                                    <div class="input-box">
                                        <span class="icon"><i class='bx bxs-lock'></i></span>
                                        <input type="password" required name="passw">
                                        <label>Password</label>
                                    </div>

                                    <div class="input-box">
                                        <span class="icon"><i class='bx bxs-lock'></i></span>
                                        <input type="password" required name="repass">
                                        <label>Retype Password</label>
                                    </div>

                                    <br>
                                    <div class="button-container">
                                        <button type="submit" class="update-button" name="sav">Save</button>
                                        <button class="cancel-button" onclick="window.history.back();">Cancel</button>
                                    </div>
                                </form>


                                <?php
                            } else {
                                echo "<p>User not found.</p>";
                            }
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();

                    }
                    ?>
                    <!-- </div> -->
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
