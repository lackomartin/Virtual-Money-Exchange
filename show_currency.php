<?php 
    require('connection.php');
    session_start();

    if(!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
    }

    $user_type = $_SESSION['user_type'];
    $userID = $_SESSION['id'];

    $id = $_GET['id'];

    $currency = mysqli_query($db, "SELECT * FROM valuta WHERE valuta_id = '$id'");

    while($row = mysqli_fetch_array($currency)) {
        $audio = $row['zvuk'];
        $anthem = "";
        if($audio != '') {
            $anthem = "<audio controls id='audio'>
                          <source src='".$audio."'>
                       </audio>";
        }

        if($user_type === '2' && $userID === $row['moderator_id'] ) {
            echo "
                <img src='".$row['slika']."'>
                <h1>".$row['naziv']."</h1>
                <div class='rate-info'>
                    <p>Rate:</p>
                    <input type='number' class='rate-input' step='0.01' name='rate' value='".$row['tecaj']."'>
                    <input type='hidden' name='id' value='".$id."'>
                    <button name='update-rate' class='update-rate-btn'>Update rate</button>
                </div>
                ".$anthem."
            ";
        }
        else if($user_type === '1') {
            echo "
                <img src='".$row['slika']."'>
                <h1>".$row['naziv']."</h1>
                <div class='rate-info'>
                    <p>Rate:</p>
                    <input type='number' class='rate-input' step='0.01' name='rate' value='".$row['tecaj']."'>
                    <input type='hidden' name='id' value='".$id."'>
                    <button name='update-rate' class='update-rate-btn'>Update rate</button>
                </div>
                ".$anthem."
            ";
        }
        else {
            echo "
                <img src='".$row['slika']."'>
                <h1>".$row['naziv']."</h1>
                <div class='rate-info'>
                    <p>Rate:</p>
                    <p>".$row['tecaj']."</p>
                </div>
                ".$anthem."
            ";
        }
    }

?>