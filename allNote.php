<?php
        $limito = 7; 
        $page = isset($_GET['page']) ? $_GET['page'] : 1; 
        $starto = ($page - 1) * $limito;
        $userId = $_SESSION['userId'];
        // $limito = 2; 
        // $starto = 0;
     
        try {
            $conn = connectDB();

            if ($conn) {
                $sql = "SELECT * FROM `notes` WHERE archive = 0 AND id = :uId LIMIT :starto, :limito";
                $stmt = $conn->prepare($sql);                
                $stmt->bindParam(':starto', $starto, PDO::PARAM_INT);
                $stmt->bindParam(':limito', $limito, PDO::PARAM_INT);
                $stmt->bindParam(':uId', $userId);
                $stmt->execute();
                
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // $isStarred = $result['star'];
                if (count($result) > 0) {
                foreach ($result as $row) {

                    $isStarred = ''. $row["star"] .'';

                    echo "<div class = 'noteCont' onclick=\"populateNote(" . $row["n_id"] . ", '" . $row["n_title"] . "', '" . $row["n_description"] . "', '" . $row["n_date"] ."')\">
                    
                    <div class='stary'>
                    <form id='starForm' action='' method = 'post'>
                    <input type='checkbox' class='star' id='starred' name='starred' value='1'". ($isStarred ? 'checked' : '') ." onchange='this.form.submit()'><br>
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
                             
                              
                               <button id = 'del' name = 'del' onclick=\"idTodele(" . $row["n_id"] . ", '" . $row["n_title"] . "', '" . $row["n_description"] . "', '" . $row["n_date"] . "',". $row['star'] .",". $row['archive'].")\"><i class='bi bi-archive'></i></button>
                               
                               
                            </div>
                        </div>";

                }
            }else{
                echo '<h1 class = "noNote">No Notes Found</h1>';
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
