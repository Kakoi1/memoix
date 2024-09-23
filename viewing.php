<?php
include_once('connection/dbConnect.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <div class="container">
        <div class="innerView">
            <div class='viewCont'>
                <?php


                try {
                    $conn = connectDB();

                    if ($conn && isset($_POST['notId'])) {
                        $notId = $_POST['notId'];
                        $sql = "SELECT `n_id`, `n_title`, `n_description`, `n_date`, star, archive FROM `notes` WHERE  `n_id` = :nid";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':nid', $notId);
                        $stmt->execute();

                        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($userData) {
                            // User data found, render the edit form
                            ?>
                            <form action="" method="post">

                                <input type="hidden" id="current_time" name="current_time" value="<?php echo date('Y-m-d'); ?>"
                                    readonly><br><br>

                                <input type="hidden" name="nid" value="<?php echo $userData['n_id']; ?>">

                                <input type="hidden" name="star" value="<?php echo $userData['star']; ?>">

                                <input type="hidden" name="arc" value="<?php echo $userData['archive']; ?>">

                                <label for="ntitle">Title:</label>
                                <br>
                                <input type="text" name="ntitle" id="ntitle" value="<?php echo $userData['n_title']; ?>" required>
                                <br>

                                <label for="desc">Note Description:</label>
                                <br>
                                <div id="charCount"><?php echo strlen($userData['n_description']); ?> / 255</div>
                                <textarea name="desc" id="myTextarea" cols="30" required rows="10"
                                    value=""><?php echo htmlspecialchars($userData['n_description']); ?></textarea>
                                <br>

                                <h4 id="dates">Date Posted: <?php echo $userData['n_date']; ?></h4>

                                <br>
                                <div class="button-container">
                                    <button type="submit" class="update-button" name="save">Save</button>
                                    <button class="cancel-button" type="button"
                                        onclick="window.location.href = 'dashboard.php'">Cancel</button>
                                </div>
                            </form>

                        </div>
                        <?php
                        } else {
                            echo "<p>User not found.</p>";
                        }
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                } finally {
                    if ($conn) {
                        $conn = null;
                    }
                }
                ?>
        </div>
    </div>

    <script>
        const textarea = document.getElementById('myTextarea');
        const charCount = document.getElementById('charCount');
        const maxChars = 255;

        textarea.addEventListener('input', function () {
            const text = textarea.value;
            const remainingChars = maxChars - text.length;

            charCount.textContent = text.length + ' / ' + maxChars;

            if (remainingChars <= 0) {
                // Disable further input if the character limit is reached
                textarea.value = text.slice(0, maxChars);
            }
        });
    </script>

</body>

</html>
<?php
include_once('insertNote.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['save'])) {

        $n_id = $_POST['nid'];
        $star = $_POST['star'];
        $arc = $_POST['arc'];
        $n_title = $_POST['ntitle'];
        $n_description = $_POST['desc'];
        $date = $_POST['current_time'];

        try {
            $conn = connectDB();

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            updating($n_title, $n_description, $n_id, $star, $arc, $date);
           echo '<script type="text/javascript">
        window.location.href = "./dashboard.php";
      </script>';

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

    }

}

if (isset($_POST['back'])) {
    header("Location: dashboard.php");

}

?>
