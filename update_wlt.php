<?php 
    require('connection.php');
    session_start();

    if(!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
    }

    $amountID = $_SESSION['amount_id'];
    $amount = $_POST['current-amount'];
    $currency = $_POST['currency'];

    if(isset($_POST['submit-amount'])) {
        if(empty($amount)) {
            echo "
            <script>
                alert('You must enter an amount!');
                window.location = 'wallet.php';
            </script>
            ";
        }
        else {
            $updateAmount = mysqli_query($db, "UPDATE `sredstva` SET `iznos` = '$amount', `valuta_id` = '$currency' WHERE sredstva_id = $amountID");

            echo "
            <script>
                alert('Complete!');
                window.location = 'wallet.php';
            </script>
            ";
        }
    }

?>