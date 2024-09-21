<?php
include_once('connection/dbConnect.php');
$userId = $_SESSION['userId'];
try {
    $conn = connectDB();

    if ($conn) {
        $sql = "SELECT * FROM `notes` WHERE archive = 0 AND star = 1 AND id = :uId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':uId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // $isStarred = $result['star'];
        if (count($result) > 0) {
            foreach ($result as $row) {

                $isStarred = '' . $row["star"] . '';

                echo "<div class = 'noteCont' onclick=\"populateNote(" . $row["n_id"] . ", '" . $row["n_title"] . "', '" . $row["n_description"] . "', '" . $row["n_date"] . "')\">

                     <div class='stary'>
                     <form id='starForm' action='' method = 'post'>
                     <input type='checkbox' class='star' id='starred' name='starred' value='1'" . ($isStarred ? 'checked' : '') . " onchange='this.form.submit()'><br>
                     <input type='hidden' name='notId' value = '{$row['n_id']}'>
                     <input type='hidden' name='notTit' value = '{$row['n_title']}'>
                     <input type='hidden' name='notDesc' value = '{$row['n_description']}'>
                     <input type='hidden' name='notDate' value = '{$row['n_date']}'>
                     <input type='hidden' name='notArc' value = '{$row['archive']}'>
                     </form>
                     </div>

                     <input type='hidden' name='notId' value = ' {$row['n_id']}'>
                            <h1>{$row['n_title']}</h1>
                            <h3>{$row['n_date']}</h3>
                            <div class = 'actions'>     
                
                                <button id = 'views' onclick='openNote()'><i class='bi bi-eye'></i></button>
                
                                <form action='viewing.php' method ='post'>
                               <button id = 'edit' onclick=''><i class='bi bi-pencil'></i></button>
                               <input type='hidden' name='notId' value = ' {$row['n_id']}'>
                               
                               </form>
                             
                              
                               <button id = 'del' name = 'del' onclick=\"idTodele(" . $row["n_id"] . ", '" . $row["n_title"] . "', '" . $row["n_description"] . "', '" . $row["n_date"] . "'," . $row['star'] . "," . $row['archive'] . ")\"><i class='bi bi-archive'></i></button>
                               
                               
                            </div>
                        </div>";

            }
        } else {
            echo '<h1 class = "nofav">No Favorites</h1>';
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