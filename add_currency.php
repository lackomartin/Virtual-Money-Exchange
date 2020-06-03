<?php
    require('connection.php');
    session_start();

    $name = $_POST['currency-name'];
    $rate = $_POST['currency-rate'];
    $moderator = $_POST['select-moderator'];
    $image = $_POST['currency-image'];
    $audio = $_POST['currency-audio'];
    $activeFrom = $_POST['active-from'];
    $activeUntil = $_POST['active-until'];
    $date = date("Y-m-d");

    if(isset($_POST['add-currency'])) {
        $error = "";
        
        if(!isset($name) || empty($name)) {
            $error .= "You must enter name!";
        }
        else if(!isset($rate) || empty($rate)) {
            $error .= "You must enter rate!";
        }
        else if(!isset($image) || empty($image)) {
            $error .= "You must enter image link!";
        }
        else if(!isset($activeFrom) || empty($activeFrom)) {
            $error .= "You must enter a value!";
        }
        else if(!isset($activeUntil) || empty($activeUntil)) {
            $error .= "You must enter a value!";
        }

        $_SESSION['error'] = $error;
        echo "
            <script>
                window.location = 'currency.php';
            </script>
        ";

        if(empty($error)) {
            $activeFrom = date('H:i:s', strtotime($activeFrom));
            $activeUntil = date('H:i:s', strtotime($activeUntil));

            $addCurrency = mysqli_query($db, "INSERT INTO `valuta`(`moderator_id`, `naziv`, `tecaj`, `slika`, `zvuk`, `aktivno_od`, `aktivno_do`,
            `datum_azuriranja`) VALUES ('$moderator', '$name', $rate, '$image', '$audio', '$activeFrom', '$activeUntil', '$date')");

            echo "
                <script>
                    alert('Complete!');
                    window.location = 'currency.php';
                </script>
            ";
        }
    }
?>