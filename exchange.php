<?php
    require('connection.php');
    session_start();

    if(!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
    }

    $userID = $_SESSION['id'];
    $user_type = $_SESSION['user_type'];

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Virtual Money Exchange</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/exchange.css">
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
                    <a href="wallet.php"><li>Wallet</li></a>
                    <a href="exchange.php"><li class="selected">Exchange</li></a>
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
                        <a href="wallet.php"><li>Wallet</li></a>
                        <a href="exchange.php"><li class="selected">Exchange</li></a>
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
                <div class="select-currency-container">
                    <div class="exchange-currency-box">
                        <h2>Exchange</h2>
                        <div class="select-box">
                            <div class="select-arrow">
                                <p><i class="arrow down"></i></p>
                            </div>
                            <form method="POST" action="my-requests.php">
                            <select name="exchange" id="exchange" onchange="getSelectedRate(); getSelectedCurrency(); userRequest();">
                                <?php
                                    $user_currency = mysqli_query($db, "SELECT v.valuta_id, v.naziv, v.tecaj, s.iznos FROM 
                                    sredstva s, valuta v WHERE s.valuta_id = v.valuta_id AND s.korisnik_id = '$userID'");
                                    while($row = mysqli_fetch_array($user_currency)) {
                                        echo "
                                        <option value='".$row['valuta_id']."' id='".$row['tecaj']."'>".$row['naziv']."</option>
                                        ";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="input-amount-box">
                            <input type="number" step="0.01" name="amount" id="user-amount" placeholder="Type amount...">
                        </div>
                    </div>
                    <div class="recieve-currency-box">
                        <h2>Recieve</h2>
                        <div class="select-box">
                            <div class="select-arrow">
                                <p><i class="arrow down"></i></p>
                            </div>
                            <select name="recieve" id="recieve" onchange="getSelectedRate(); getSelectedCurrency(); userRequest();">
                                <?php
                                    require('connection.php');

                                    $currency = mysqli_query($db, "SELECT * FROM valuta");
                                    while($row = mysqli_fetch_array($currency)) {
                                        echo "
                                        <option value='".$row['valuta_id']."' id='".$row['tecaj']."'>".$row['naziv']."</option>
                                        ";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="input-amount-box">
                            <p id="selected-rate"></p>
                            <p>Rate</p>
                        </div>
                    </div>
                </div>

                <div class="select-currency-container" >
                    <div class="exchange-currency-box" style="border-right: none;">
                        <div class="exchange-result-box">
                            <h2>You are exchanging</h2>
                            <p class="amount" id="exchange-amount"></p>
                            <p class="amount-curr" id="exchange-currency"></p>
                        </div>
                    </div>

                    <div class="exchange-circle">&#8674;</div>
                    <input type="hidden" name="user" value="<?php echo $userID ?>">
                    <button class="submit-exchange" name="submit-exchange">Exchange</button>
                    </form>

                    <div class="recieve-currency-box">
                        <div class="exchange-result-box" style="text-align: left;">
                            <h2 style="text-align: left;">You are recieve</h2>
                            <p class="amount" id="recieve-amount"></p>
                            <p class="amount-curr" id="recieve-currency"></p>
                        </div>
                    </div>
                </div>
            </section>
        </section>

        <!-- Javascript -->
        <script src="assets/scripts/exchange.js"></script>
        <script src="assets/scripts/menu.js"></script>
    </body>
</html>