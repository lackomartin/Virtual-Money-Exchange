<?php
    require('connection.php');
    session_start();

    if(!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
    }

    $userID = $_SESSION['id'];

    $amountID = intval($_GET['amount']);
    $wallet = mysqli_query($db, "SELECT v.naziv, s.sredstva_id, s.korisnik_id, s.iznos, s.valuta_id FROM valuta v, sredstva s 
    WHERE s.valuta_id = v.valuta_id AND s.sredstva_id = '$amountID'");

    while($row = mysqli_fetch_array($wallet)) {
        $_SESSION['amount_id'] = $row['sredstva_id'];
        $amount_decimals = number_format((float)$row['iznos'], 2, '.', '');
        
        echo "
            <label for='current-amount'>Current amount:</label>
            <input type='number' step='0.01' name='current-amount' id='current-amount' value='".$amount_decimals."'>
            <label for='current-amount'>Current currency:</label>
            <select id='currency' name='currency'>
                <option value='".$row['valuta_id']."'>".$row['naziv']."</option>
        ";
    }

    $currency = mysqli_query($db, "SELECT * FROM valuta WHERE naziv != '$amountID'");

    while($row = mysqli_fetch_array($currency)) {
        echo "
                <option value='".$row['valuta_id']."'>".$row['naziv']."</option>
        ";
    }

    echo "
        </select>
        <button name='submit-amount' class='submit-amount' style='width: 150px; height: 40px;'>Update amount</button>
    ";

?>
