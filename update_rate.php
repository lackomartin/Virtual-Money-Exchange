<?php 
    require('connection.php');
    session_start();

    if(!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
    }

    $user_type = $_SESSION['user_type'];

    if(isset($_POST['update-rate'])) {
        $date = date("Y-m-d");
        $rate = $_POST['rate'];
        $id = $_POST['id'];

        if(empty($rate)) {
            echo "
            <script>
                alert('You must enter a rate!');
                window.location = 'dashboard.php';
            </script>
            ";
        }
        else {
            $currentDate = mysqli_query($db, "SELECT datum_azuriranja FROM valuta WHERE valuta_id = '$id'");
            $row = mysqli_fetch_array($currentDate);

            if($user_type === '2' && $date === $row['datum_azuriranja']) {
                echo "
                    <script>
                        alert('You can no longer update the rate!');
                        window.location = 'dashboard.php';
                    </script>
                ";
            }
            else if($user_type === '2' && $date !== $row['datum_azuriranja']){
                $updateRate = mysqli_query($db, "UPDATE `valuta` SET `tecaj` = '$rate', `datum_azuriranja` = '$date' WHERE valuta_id = '$id'");
                echo "
                    <script>
                        alert('Complete!');
                        window.location = 'dashboard.php';
                    </script>
                ";
            }
            else if($user_type === '1'){
                $updateRate = mysqli_query($db, "UPDATE `valuta` SET `tecaj` = '$rate', `datum_azuriranja` = '$date' WHERE valuta_id = '$id'");
                    echo "
                        <script>
                            alert('Complete!');
                            window.location = 'dashboard.php';
                        </script>
                    ";
            }
        }
    }

?>