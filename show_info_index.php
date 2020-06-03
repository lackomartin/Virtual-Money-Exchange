<?php 
    require('connection.php');
    session_start();

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

?>