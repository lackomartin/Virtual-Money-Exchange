<?php
    require('connection.php');
    session_start();

    if(!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
    }

    $userID = $_SESSION['id'];
    $user_type = $_SESSION['user_type'];

    if(isset($_POST['submit-exchange'])) {

        $user = $_POST['user'];
        $exchange_currency = $_POST['exchange'];
        $recieve_currency = $_POST['recieve'];
        $exchange_amount = $_POST['amount'];
        $date = date("Y-m-d H:i:s");

        $allAmounts = mysqli_query($db, "SELECT * FROM sredstva WHERE korisnik_id = $userID AND valuta_id = $exchange_currency");
        $row = mysqli_fetch_array($allAmounts);
            if($row['iznos'] < $exchange_amount) {
                echo "
                <script>
                    alert('You do not have enough funds!');
                    window.location = 'exchange.php';
                </script>
                ";
            }
            else {
                $send_request = mysqli_query($db, "INSERT INTO `zahtjev`(`korisnik_id`, `iznos`, `prodajem_valuta_id`, `kupujem_valuta_id`, 
                `datum_vrijeme_kreiranja`, `prihvacen`) VALUES ('$user', '$exchange_amount', '$exchange_currency', '$recieve_currency', 
                '$date', 2)");

                echo "
                <script>
                    alert('The request has been sent!');
                    window.location = 'my-requests.php';
                </script>
                ";
            }
    }    

    if(isset($_POST['accept'])) {
        $requestID = $_POST['request_id'];
        $user = $_POST['user'];
        $exchange_currency = $_POST['exchange'];
        $recieve_currency = $_POST['recieve'];
        $rate1 = $_POST['rate1'];
        $rate2 = $_POST['rate2'];
        $amount = $_POST['amount'];

        $accept = mysqli_query($db, "UPDATE `zahtjev` SET `prihvacen` = 1 WHERE zahtjev_id = $requestID");

        $exchange_curr = mysqli_query($db, "SELECT * FROM sredstva WHERE valuta_id = '$exchange_currency' AND korisnik_id = '$user'");
        while($row = mysqli_fetch_array($exchange_curr)) {
            $newAmount = intval($row['iznos']) - intval($amount);

            $updateCurr = mysqli_query($db, "UPDATE `sredstva` SET `iznos` = '$newAmount' WHERE
            korisnik_id = '$user' AND valuta_id = '$exchange_currency'");
        }

        $recieve_curr = mysqli_query($db, "SELECT * FROM sredstva WHERE valuta_id = '$recieve_currency' AND korisnik_id = '$user'");
        $recieve = ($amount * $rate1)/$rate2;
        
        if(mysqli_num_rows($recieve_curr) > 0) {
            while($row = mysqli_fetch_array($recieve_curr)) {
                $newAmount = intval($row['iznos']) + intval($recieve);
            }
            $updateCurr = mysqli_query($db, "UPDATE `sredstva` SET `iznos` = '$newAmount' WHERE
            korisnik_id = '$user' AND valuta_id = '$recieve_currency'");
        }
        else {
            $insertCurr = mysqli_query($db, "INSERT INTO `sredstva` (`korisnik_id`, `valuta_id`, `iznos`) VALUES ('$user', 
            '$recieve_currency', '$recieve')");
        }

        
        echo "
        <script>
            alert('The request was accepted!');
        </script>
        ";
    }

    if(isset($_POST['reject'])) {
        $requestID = $_POST['request_id'];
        $accept = mysqli_query($db, "UPDATE `zahtjev` SET `prihvacen` = 0 WHERE zahtjev_id = $requestID");

        echo "
        <script>
            alert('The request was denied!');
        </script>
        ";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Virtual Money Exchange</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/requests.css">
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
                    <a href="exchange.php"><li>Exchange</li></a>
                    <a href="my-requests.php"><li class="selected">My requests</li></a>
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
                        <a href="exchange.php"><li>Exchange</li></a>
                        <a href="my-requests.php"><li class="selected">My requests</li></a>
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
                <div class="requests">
                    <div class="my-requests-container">
                        <h1 id="request-h1">My requests</h1>
                        <div class="request first-of-type">
                            <p>Selling</p>
                            <p>Purchase</p>
                            <p>Amount</p>
                            <p>Status</p>
                        </div>
                        <div id="request-box">
                        <?php
                             $my_requests_recieved = mysqli_query($db, "SELECT
                             (SELECT naziv FROM valuta WHERE valuta_id = prodajem_valuta_id) as exchange,
                             (SELECT naziv FROM valuta WHERE valuta_id = kupujem_valuta_id) as recieve,
                             iznos, prihvacen FROM zahtjev WHERE korisnik_id = '$userID' AND prihvacen = 1 ORDER BY 
                             zahtjev_id DESC");
 
                             while($row = mysqli_fetch_array($my_requests_recieved)) {
                                 echo "
                                     <div class='request' id='rejected'>
                                         <p>".$row['exchange']."</p>
                                         <p>".$row['recieve']."</p>
                                         <p>".$row['iznos']."</p>
                                         <p class='recieved'>Recieved</p>
                                     </div>
                                 ";                               
                             }

                            $my_requests_rejected = mysqli_query($db, "SELECT
                            (SELECT naziv FROM valuta WHERE valuta_id = prodajem_valuta_id) as exchange,
                            (SELECT naziv FROM valuta WHERE valuta_id = kupujem_valuta_id) as recieve,
                            iznos, prihvacen FROM zahtjev WHERE korisnik_id = '$userID' AND prihvacen = 0 ORDER BY 
                            zahtjev_id DESC");

                            while($row = mysqli_fetch_array($my_requests_rejected)) {
                                echo "
                                    <div class='request' id='rejected'>
                                        <p>".$row['exchange']."</p>
                                        <p>".$row['recieve']."</p>
                                        <p>".$row['iznos']."</p>
                                        <p class='rejected'>Rejected</p>
                                    </div>
                                ";                               
                            }
                        ?>
                        </div>
                    </div>

                    <div class="onhold-container">
                        <h1 id="request-h1">On hold</h1>
                        <div class="request first-of-type">
                            <p>Selling</p>
                            <p>Purchase</p>
                            <p>Amount</p>
                            <p>Status</p>
                        </div>
                        <div id="request-box">
                        <?php 
                            $on_hold = mysqli_query($db, "SELECT
                            (SELECT naziv FROM valuta WHERE valuta_id = prodajem_valuta_id) as exchange,
                            (SELECT naziv FROM valuta WHERE valuta_id = kupujem_valuta_id) as recieve,
                            iznos FROM zahtjev WHERE korisnik_id = '$userID' AND prihvacen = 2 ORDER BY zahtjev_id DESC");

                            while($row = mysqli_fetch_array($on_hold)) {
                                echo "
                                    <div class='request'>
                                        <p>".$row['exchange']."</p>
                                        <p>".$row['recieve']."</p>
                                        <p>".$row['iznos']."</p>
                                        <p>On hold</p>
                                    </div>
                                ";
                            }
                        ?>
                        </div>
                    </div>
                </div>
                
                <?php
                if($user_type === '2') {
                    echo "
                        <div class='users-requests-container'>
                        <h1 id='request-h1'>Users requests</h1>
                        <div class='request first-of-type'>
                                <p>Selling</p>
                                <p>Purchase</p>
                                <p>Amount</p>
                                <p>Accept/Reject</p>
                        </div>
                        <div id='request-box'>
                    ";

                    $user_requests = mysqli_query($db, "SELECT 
                            (SELECT naziv FROM valuta WHERE valuta_id = prodajem_valuta_id) as exchange,
                            (SELECT naziv FROM valuta WHERE valuta_id = kupujem_valuta_id) as recieve,
                            (SELECT tecaj FROM valuta WHERE valuta_id = prodajem_valuta_id) as rate1,
                            (SELECT tecaj FROM valuta WHERE valuta_id = kupujem_valuta_id) as rate2,
                            z.zahtjev_id, z.korisnik_id, z.iznos, z.prodajem_valuta_id, z.kupujem_valuta_id, v.aktivno_od, v.aktivno_do 
                            FROM zahtjev z, valuta v WHERE prodajem_valuta_id = valuta_id AND moderator_id = '$userID' AND 
                            prihvacen = 2 ORDER BY zahtjev_id DESC"); 

                            while($row = mysqli_fetch_array($user_requests)) {
                                $time = date("H:i:s");

                                if($time >= $row['aktivno_od'] && $time <= $row['aktivno_do']) {
                                    echo "
                                        <form method='POST' action=''>
                                        <div class='request'>
                                            <input type='hidden' name='request_id' value='".$row['zahtjev_id']."'>
                                            <input type='hidden' name='user' value='".$row['korisnik_id']."'>
                                            <input type='hidden' name='exchange' value='".$row['prodajem_valuta_id']."'>
                                            <input type='hidden' name='recieve' value='".$row['kupujem_valuta_id']."'>
                                            <input type='hidden' name='rate1' value='".$row['rate1']."'>
                                            <input type='hidden' name='rate2' value='".$row['rate2']."'>
                                            <input type='hidden' name='amount' value='".$row['iznos']."'>
                                            <p>".$row['exchange']."</p>
                                            <p>".$row['recieve']."</p>
                                            <p>".$row['iznos']."</p>
                                            <button class='accept' name='accept'>Accept</button>
                                            <button class='reject' name='reject'>Reject</button>
                                        </div>
                                        </form>
                                    ";
                                }
                                else {
                                    echo "
                                        <div class='request'>
                                            <p>".$row['exchange']."</p>
                                            <p>".$row['recieve']."</p>
                                            <p>".$row['iznos']."</p>
                                            <p>Not active</p>
                                        </div>
                                    ";
                                }
                            }
                    echo "
                        </div>
                        </div>
                    ";    
                }

                if($user_type == 1) {
                    echo "
                        <div class='users-requests-container'>
                        <h1 id='request-h1'>Users requests</h1>
                        <div class='request first-of-type'>
                                <p>Selling</p>
                                <p>Purchase</p>
                                <p>Amount</p>
                                <p>Accept/Reject</p>
                        </div>
                        <div id='request-box'>
                    ";

                    $user_requests = mysqli_query($db, "SELECT 
                            (SELECT naziv FROM valuta WHERE valuta_id = prodajem_valuta_id) as exchange,
                            (SELECT naziv FROM valuta WHERE valuta_id = kupujem_valuta_id) as recieve,
                            (SELECT tecaj FROM valuta WHERE valuta_id = prodajem_valuta_id) as rate1,
                            (SELECT tecaj FROM valuta WHERE valuta_id = kupujem_valuta_id) as rate2,
                            z.zahtjev_id, z.korisnik_id, z.iznos, z.prodajem_valuta_id, z.kupujem_valuta_id 
                            FROM zahtjev z, valuta v WHERE prodajem_valuta_id = valuta_id AND 
                            prihvacen = 2 ORDER BY zahtjev_id DESC"); 

                    while($row = mysqli_fetch_array($user_requests)) {
                        echo "
                            <form method='POST' action=''>
                                <div class='request'>
                                    <input type='hidden' name='request_id' value='".$row['zahtjev_id']."'>
                                    <input type='hidden' name='user' value='".$row['korisnik_id']."'>
                                    <input type='hidden' name='exchange' value='".$row['prodajem_valuta_id']."'>
                                    <input type='hidden' name='recieve' value='".$row['kupujem_valuta_id']."'>
                                    <input type='hidden' name='rate1' value='".$row['rate1']."'>
                                    <input type='hidden' name='rate2' value='".$row['rate2']."'>
                                    <input type='hidden' name='amount' value='".$row['iznos']."'>
                                    <p>".$row['exchange']."</p>
                                    <p>".$row['recieve']."</p>
                                    <p>".$row['iznos']."</p>
                                    <button class='accept' name='accept'>Accept</button>
                                    <button class='reject' name='reject'>Reject</button>
                                </div>
                            </form>
                        ";
                    }
                }
                
                ?>
            </section>
        </section>

        <!-- Javascript -->
        <script src="assets/scripts/menu.js"></script>

    </body>
</html>