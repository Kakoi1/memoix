<?php
session_start();
include_once('connection/dbConnect.php');

$emails = $_SESSION['email'];

if ($emails != false) {
    try {

        $conn = connectDB();

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM `accounts` WHERE `email` = :emil";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':emil', $emails);
        $stmt->execute();

        $dataUser = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['userId'] = $dataUser['id'];

        $userId = $dataUser['id'];

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "<script>alert('no data found');
        document.location.href = 'logout.php';
    </script>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<link rel="stylesheet" href="css/dashboard.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<body>
    <div class="container">

        <div class="inner">
            <div class="navbar">
                <h1 class="appnam">Note App</h1>
                <div class="imag">
                    <div class="pic"><i class="bi bi-person-circle"></i></div>
                    <h3 id='nams'><?php echo $dataUser['name']; ?></h3>
                </div>
                <div class="navButon">

                    <div onclick="showTab(1)">
                        <p><i class="bi bi-display-fill"></i> Dashboard</p>
                    </div>
                    <div id="fav" onclick="showTab(2)">
                        <p><i class="bi bi-star-fill"></i> Favorites</p>
                    </div>
                    <div id="archives" onclick="">
                        <p><a href="archiveNotes.php"><i class="bi bi-archive"></i> Archived Notes </a></p>
                    </div>
                    <div id="archives" onclick="">
                        <p><a href="userEdit.php"><i class="bi bi-pencil-square"></i> Edit User</a></p>
                    </div>
                    <div id="archives" onclick="">
                        <p><a href="logout.php"><i class="bi bi-box-arrow-left"></i> Log Out</a></p>
                    </div>

                </div>
            </div>

            <div class="content"> <!-- MAIN CONTAINER FOR CONTENTS -->

                <!-- CONTENT 1 ACTIVE -->
                <div class="tab-content tab1-content active">
                    <div class="boardPag">
                        <div class="board">
                            <div class="noteConts" id="addNote"><img src="..//images/icons8-add-note-96.png" alt=""
                                    width="200px" height="200px"></div>
                            <?php
                            $limito = 7;
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;

                            include_once('allNote.php');


                            ?>
                        </div>

                        <div class="overlay1" id="overNote">
                            <div class="noteForm">

                                <form action="" method="post">
                                    <label for="title">Note Title:</label>
                                    <br>
                                    <input type="text" name="title" id="title" required>
                                    <br>
                                    <label for="descrip">Note Description</label>
                                    <br>
                                    <div id="charCount">0 / 255</div>
                                    <textarea name="descrip" id="myTextarea" cols="100" rows="4" required></textarea>
                                    <br><br>
                                    <div class="button-container">

                                        <input id="add" name="add" class="add" type="submit" value="add">
                                        <input id="cancel" name="cancel" class="cancel" type="button"
                                            onclick="closeForm()" value="cancel">

                                    </div>

                                </form>

                            </div>
                        </div>


                        <?php

                        $conn = connectDB();
                        $sqli = 'SELECT * FROM `notes` WHERE archive = 0 AND id = :uId';
                        $stmt = $conn->prepare($sqli);
                        $stmt->bindParam(':uId', $userId);
                        $stmt->execute();
                        $num_rows = 0;


                        while ($stmt->fetch(PDO::FETCH_ASSOC)) {
                            $num_rows++;
                        }

                        $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                        $totalPage = ceil($num_rows / $limito);

                        echo "<div class = 'paginat'>";
                        if ($page > 1) {
                            echo "<a href='?page=1'>First</a> ";
                            echo "<a href='?page=" . ($page - 1) . "'>Previous</a> ";
                        }

                        for ($i = 1; $i <= $totalPage; $i++) {

                            echo "<a class = 'withNum' href='?page=$i' id='page_$i'>$i</a> ";
                        }

                        if ($page < $totalPage) {
                            echo "<a href='?page=" . ($page + 1) . "'>Next</a> ";
                            echo "<a href='?page=$totalPage'>Last</a> ";
                        }
                        echo "</div>";

                        ?>
                    </div>



                </div><!-- END CONTENT 1 -->


                <div class="tab-content tab2-content">
                    <!-- COntent 2 $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->

                    <div class='boardPag'>
                        <div class='board'>

                            <?php

                            include_once("favourites.php");



                            ?>
                        </div>

                    </div>



                </div>
            </div>
            <div class="overlayNote" id='overlayNote'>
                <div class='confirmBox' id='confirmBox'>
                    <div class='confirmCont'>
                        <h3 id="nameDel"></h3>
                        <div class='delBut'>
                            <form action='' method='post'>
                                <button id='del' name='del'>Remove</button>
                                <input type='hidden' id='noteId' name='noteId'>
                                <input type='hidden' id='titil' name='titil'>
                                <input type='hidden' id='descri' name='descri'>
                                <input type='hidden' id='dats' name='dats'>
                                <input type='hidden' id='str' name='str'>
                                <input type='hidden' id='arch' name='arch'>
                            </form>

                            <button id="cance" name="cance" onclick="cancelDel()">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="overlay" id="viewNote">
                <div class="viewForm">

                    <h3 id="nid"></h3>
                    <h2 id="ntitle"></h2>
                    <textarea name="ndesc" id="ndesc" cols="30" rows="10" readonly></textarea>
                    <h4 id="ndate"></h4>
                    <br>
                    <button id="sira" onclick="closeNote()">close</button>

                </div>
            </div>

        </div>

        <script src="..//script/jsCode.js"></script>

        <script>
            function updateStar() {
                document.getElementById('starForm').submit();
            }
        </script>

        <script>

            var currentPage = <?php echo $page; ?>;


            var selectedPageLink = document.getElementById('page_' + currentPage);

            selectedPageLink.style.color = 'white';
            selectedPageLink.style.backgroundColor = 'rgb(86, 7, 7)';

            selectedPageLink.style.fontWeight = 'bold';

        </script>

</body>

</html>
<!-- INSERT HERE -->
<?php

include_once('insertNote.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['add'])) {
        $title = $_POST['title'];
        $descrip = $_POST['descrip'];


        try {
            $conn = connectDB();

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            inserting($title, $descrip, $userId);

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

    }
}
?>
<!-- DELETE HEARE -->
<?php
// if($_SERVER["REQUEST_METHOD"] == "POST"){

//     include_once ('insertNote.php');

//     if(isset($_POST['del'])){

//         $delId = $_POST['noteId'];

//         try {

//             $conn = connectDB();

//             $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//             deleting($delId);  


//         } catch  (PDOException $e) {
//             echo "Error: " . $e->getMessage();                 
//         }



//     }

// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include_once('insertNote.php');

    if (isset($_POST['del'])) {

        $n_id = $_POST['noteId'];
        $star = $_POST['str'];
        $n_title = $_POST['titil'];
        $n_description = $_POST['descri'];
        $date = $_POST['dats'];
        $arc = 1;

        try {

            $conn = connectDB();

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            updating($n_title, $n_description, $n_id, $star, $arc, $date);

            echo '<script>window.location.href=window.location.href;</script>';

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }



    }

}

?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include_once('insertNote.php');
    // if (isset($_POST['yawa'])) { 


    $isStarred = isset($_POST['starred']) ? 1 : 0;
    $noteId = $_POST['notId'];
    $title = $_POST['notTit'];
    $descrip = $_POST['notDesc'];
    $notDate = $_POST['notDate'];
    $notArc = $_POST['notArc'];
    try {
        $conn = connectDB();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        if ($isStarred) {
            // $sql = "UPDATE `notes` SET `star`='1' WHERE `n_id` = :id";
            $star = 1;
        } else {
            // $sql = "UPDATE `notes` SET `star`='0' WHERE `n_id` = :id";
            $star = 0;
        }

        updating($title, $descrip, $noteId, $star, $notArc, $notDate);

        echo '<script>window.location.href=window.location.href;</script>';

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
// }
?>