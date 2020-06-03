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
        <link rel="stylesheet" href="assets/css/dashboard.css">
        <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
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
                    <a href="dashboard.php"><li class="selected">Dashboard</li></a>
                    <a href="wallet.php"><li>Wallet</li></a>
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
                        <a href="dashboard.php"><li class="selected">Dashboard</li></a>
                        <a href="wallet.php"><li>Wallet</li></a>
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
                    <div class="currency-content">
                        <?php
                            $currency = mysqli_query($db, "SELECT * FROM valuta");
                            while($row = mysqli_fetch_array($currency)) {
                                echo "
                                    <div class='item'>
                                        <p hidden>".$row['valuta_id']."</p>
                                        <img src='".$row['slika']."' class='currency-img'>
                                    </div>
                                ";
                            }
                        ?>
                    </div>

                <div id="doughnut-chart-container">
                    <?php 
                        $recieved = mysqli_query($db, "SELECT prihvacen FROM zahtjev WHERE prihvacen = 1 AND korisnik_id = '$userID'");
                        $rejected = mysqli_query($db, "SELECT prihvacen FROM zahtjev WHERE prihvacen = 0 AND korisnik_id = '$userID'");
                        $onHold = mysqli_query($db, "SELECT prihvacen FROM zahtjev WHERE prihvacen = 2 AND korisnik_id = '$userID'");

                        $recieved = mysqli_num_rows($recieved);
                        $rejected = mysqli_num_rows($rejected);
                        $onHold = mysqli_num_rows($onHold);

                        echo "
                            <p id='recieved' hidden>".$recieved."</p>
                            <p id='rejected' hidden>".$rejected."</p>
                            <p id='onHold' hidden>".$onHold."</p>
                        ";
                    ?>
                    <canvas id="doughnut-chart" width="600" height="350"></canvas>
                </div>
            </section>
        </section>

        <!-- Currency info -->
        <div class="background">
            <div class="currency-info-container">
                <a href="#" class="close"></a>
                <form name="update-rate-form" class="update-rate-form" method="POST" action="update_rate.php">
                </form>
            </div>
        </div>

        <!-- Chart.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
        <script src="assets/scripts/Chart.js"></script>

        <!-- Javascript -->
        <script src="assets/scripts/index.js"></script>
        <script src="assets/scripts/menu.js"></script>
    </body>
</html>