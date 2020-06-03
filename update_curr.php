<?php 
    require('connection.php');
    session_start();

    if(!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
    }

    $currencyID = $_SESSION['currency_id'];
    $currencyName = $_POST['currency-name'];
    $rate = $_POST['rate'];
    $selectModerator = $_POST['select-moderator'];
    $image = $_POST['image'];
    $currencyAudio = $_POST['audio'];
    $activeFrom = $_POST['active-from'];
    $activeUntil = $_POST['active-until'];
    $date = date("Y-m-d");

    if(isset($_POST['update-currency'])) {
        if(empty($currencyName) || empty($rate) || empty($image) || empty($activeFrom) || empty($activeUntil)) {
            echo "
            <script>
                alert('You must enter all values!');
                window.location = 'currency.php';
            </script>
            ";
        }
        else {
            $activeFrom = date('H:i:s', strtotime($activeFrom));
            $activeUntil = date('H:i:s', strtotime($activeUntil));

            $updateCurrency = mysqli_query($db, "UPDATE `valuta` SET `moderator_id` = '$selectModerator', `naziv` = '$currencyName',
            `tecaj` = '$rate', `slika` = '$image', `zvuk` = '$currencyAudio', `aktivno_od` = '$activeFrom', `aktivno_do` = '$activeUntil', 
            `datum_azuriranja` = '$date' WHERE valuta_id = $currencyID");

            echo "
            <script>
                alert('Complete!');
                window.location = 'currency.php';
            </script>
            ";
        }
    }
?>