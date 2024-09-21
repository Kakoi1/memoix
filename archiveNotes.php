<?php
session_start();
include_once('connection/dbConnect.php');

$userId = $_SESSION['userId'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>
    <div class="container">

        <div class="inner1">

            <div class="boardPag">
                <div class='returN'>
                    <a href="dashboard.php">
                        <h1>
                            << /h1>
                    </a>
                </div>
                <div class="board1">
                    <?php
                    $limito = 7;
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $starto = ($page - 1) * $limito;
                    // $limito = 2; 
                    // $starto = 0;
                    
                    try {
                        $conn = connectDB();

                        if ($conn) {
                            $sql = "SELECT * FROM `notes` WHERE archive = 1 and id = :uid LIMIT :starto, :limito";
                            $stmt = $conn->prepare($sql);
                            $stmt->bindParam(':starto', $starto, PDO::PARAM_INT);
                            $stmt->bindParam(':limito', $limito, PDO::PARAM_INT);
                            $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
                            $stmt->execute();

                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // $isStarred = $result['star'];
                    
                            if (count($result) > 0) {
                                foreach ($result as $row) {

                                    $isStarred = '' . $row["star"] . '';

                                    echo "<div class = 'noteCont' onclick=\"populateNote(" . $row["n_id"] . ", '" . $row["n_title"] . "', '" . $row["n_description"] . "', '" . $row["n_date"] . "')\">
                      
               

                      <input type='hidden' name='notId' value = ' {$row['n_id']}'>
                              <h1>{$row['n_title']}</h1>
                              <h3>{$row['n_date']}</h3>

                              <div class = 'arcBut'>     
  
                                  <button id = 'views' onclick='openNote()'><i class='bi bi-eye'></i></button>
  
                                  <form action='' method ='post'>
                                 <button id = 'restore' name = 'rest' onclick=''><i class='bi bi-recycle'></i></button>
                                 <input type='hidden' name='notId' value = ' {$row['n_id']}'>
                                 <input type='hidden' name='notTit' value = '{$row['n_title']}'>
                                 <input type='hidden' name='notDesc' value = '{$row['n_description']}'>
                                 <input type='hidden' name='notDate' value = '{$row['n_date']}'>
                                 
                                 </form>
                               
                                
                                 <button id = 'del' name = 'del' onclick=\"removeData(" . $row["n_id"] . ", '" . $row["n_title"] . "')\"><i class='bi bi-trash-fill'></i></button>
                                 
                                 
                              </div>
                          </div>";

                                }
                            } else {
                                echo '<h1 class = "nofav">No Archive Notes</h1>';
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



                <?php

                $conn = connectDB();
                $sqli = 'SELECT * FROM `notes` WHERE archive = 1';
                $stmt = $conn->prepare($sqli);
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

            <div class="overlay" id="viewNote">
                <div class="viewForm">

                    <h3 id="nid"></h3>
                    <h2 id="ntitle"></h2>
                    <textarea name="ndesc" id="ndesc" cols="30" rows="10"></textarea>
                    <h4 id="ndate"></h4>
                    <button id="sira" onclick="closeNote()">close</button>

                </div>
            </div>


            <div class="overlayNote" id='overlayNote'>
                <div class='confirmBox' id='confirmBox'>
                    <div class='confirmCont'>
                        <h3 id="dataNm"></h3>
                        <div class='delBut'>
                            <form action='' method='post'>
                                <button id='del' name='del'>delete</button>
                                <input type='hidden' id='dataId' name='dataId'>
                            </form>

                            <button id="cance" name="cance" onclick="removeCancel()">cancel</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <script src="..//script/jsCode.js"></script>

    <script>


        var currentPage = <?php echo $page; ?>;


        var selectedPageLink = document.getElementById('page_' + currentPage);
        selectedPageLink.style.color = 'white';
        selectedPageLink.style.backgroundColor = 'rgb(86, 7, 7)';

        selectedPageLink.style.fontWeight = 'bold';


    </script>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include_once('insertNote.php');

    if (isset($_POST['rest'])) {

        $n_id = $_POST['notId'];
        $star = 0;
        $n_title = $_POST['notTit'];
        $n_description = $_POST['notDesc'];
        $date = $_POST['notDate'];
        $arc = 0;

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

    if (isset($_POST['del'])) {

        $delId = $_POST['dataId'];

        try {

            $conn = connectDB();

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            deleting($delId);

            echo '<script>window.location.href=window.location.href;</script>';

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }



    }

}
?>
