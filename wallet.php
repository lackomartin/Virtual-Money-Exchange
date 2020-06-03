<?php 
    require('connection.php');
    session_start();

    if(!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
    }

    $userID = $_SESSION['id'];
    $user_type = $_SESSION['user_type'];

    $error = "";
    if(isset($_POST['submit-amount'])) {
        $currencyID = $_POST['currency'];
        $amount = $_POST['amount'];

        if(!isset($amount) || empty($amount)) {
            $error .= "You must enter an amount!";
        }
        
        if(empty($error)) {
            $allCurrency = mysqli_query($db, "SELECT * FROM sredstva WHERE valuta_id = '$currencyID' AND korisnik_id = '$userID'");

            if(mysqli_num_rows($allCurrency) > 0) {
                while($row = mysqli_fetch_array($allCurrency)) {
                    $newAmount = intval($row['iznos']) + intval($amount);
                }
                $updateCurr = mysqli_query($db, "UPDATE `sredstva` SET `iznos` = '$newAmount' WHERE
                korisnik_id = '$userID' AND valuta_id = '$currencyID'");
            }
            else {
                $insertCurr = mysqli_query($db, "INSERT INTO `sredstva` (`korisnik_id`, `valuta_id`, `iznos`) VALUES ('$userID', '$currencyID',
                '$amount')");
            }

            echo "
            <script>
                alert('Complete!');
                window.location = 'wallet.php';
            </script>
            ";   
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Virtual Money Exchange</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/wallet.css">
        <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">
    </head>

    <body>
        <header>
            <div class="logo">Virtual Money Exchange</div>
            <div class="user-nav">
                <a href="profile.php"><p><?php echo $_SESSION['name'] ." ". $_SESSION['lastname']?></p></a>
                <a href="about_author.html"><p class="author">About the author</p></a>
            </div>

            <p class="responsive-menu">Menu</p>
            <div class="responsive-menu-container">
                <a href="#" class="close-menu">Close</a>
                <ul>
                    <a href="dashboard.php"><li>Dashboard</li></a>
                    <a href="wallet.php"><li class="selected">Wallet</li></a>
                    <a href="exchange.php"><li>Exchange</li></a>
                    <a href="my-requests.php"><li>My requests</li></a>
                    <?php 
                        if($user_type === '1') {
                            echo "
                                <a href='all-requests.php'><li>All requests</li></a>
                                <a href='currency.php'><li>Curency</li></a>
                                <a href='users.php'><li>Users</li></a>
                            ";
                        }
                    ?>
                    <a href="about_author.html"><li>About the author</li></a>
                    <a href="logout.php"><li>Logout</li></a>
                </ul>
            </div>
        </header>
        <section class="body">
            <section class="navigation-container">
                <nav>
                    <ul>
                        <a href="dashboard.php"><li>Dashboard</li></a>
                        <a href="wallet.php"><li class="selected">Wallet</li></a>
                        <a href="exchange.php"><li>Exchange</li></a>
                        <a href="my-requests.php"><li>My requests</li></a>
                        <?php 
                            if($user_type === '1') {
                                echo "
                                    <a href='all-requests.php'><li>All requests</li></a>
                                    <a href='currency.php'><li>Curency</li></a>
                                    <a href='users.php'><li>Users</li></a>
                                ";
                            }
                        ?>
                    </ul>
                </nav>
                <div class="logout"><a href="logout.php"><p>Logout</p></a></div>
            </section>
    
            <section class="main-section">
                <div class="wallet-container">
                    <h1>My wallet</h1>
                    <div class="my-wallet">
                        <?php 
                            $amount = mysqli_query($db, "SELECT v.naziv, s.sredstva_id, s.iznos, s.korisnik_id FROM sredstva s, valuta v 
                            WHERE s.valuta_id = v.valuta_id AND s.korisnik_id = '$userID'");
                            while($row = mysqli_fetch_array($amount)) {
                                $amount_decimals = number_format((float)$row['iznos'], 2, '.', '');
                                echo "
                                    <div class='my-amount'>
                                        <p hidden>".$row['sredstva_id']."</p>
                                        <h2>Your Amount/Currency</h2>
                                        <p>Amount<span>".$amount_decimals."</span></p>
                                        <p>Currency<span id='name-wallet'>".$row['naziv']."</span></p>
                                        <p><button class='update-wallet'>Update</button></p>
                                    </div>
                                ";
                            }
                        ?>
                    </div>
                </div>

                <div class="add-amount">
                    <h1>Add amount to your wallet</h1>
                    <form name="add-amount" class="add-amount-form" method="POST" action="">
                        <input type="number" step="0.01" name="amount" placeholder="Type amount...">
                        <select name="currency">
                            <?php 
                                $sql = mysqli_query($db, "SELECT * FROM valuta");
                                while($row = mysqli_fetch_array($sql)) {
                                    echo "
                                        <option value='".$row['valuta_id']."'>".$row['naziv']."</option>
                                    ";
                                }
                            ?>
                        </select>
                        <p class="message"><?php echo $error ?></p>
                        <button name="submit-amount" class="submit-amount">Add amount</button>
                    </form>
                </div>
            </section>
                
        </section>

        <!-- update wallet -->
        <div class="background">
            <div class="update-wallet-box">
                <a href="#" class="close"></a>
                <form name="update-amount" class="update-amount-form" method="POST" action="update_wlt.php">
                    
                </form>
            </div>
        </div>

        <!-- Javascript -->
        <script src="assets/scripts/index.js"></script>
        <script src="assets/scripts/menu.js"></script>
    </body>
</html>