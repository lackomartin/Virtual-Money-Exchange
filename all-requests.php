<?php
    require('connection.php');
    session_start();

    $user_type = $_SESSION['user_type'];

    if(!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
    }
    else if($user_type !== '1') {
        header('Location: dashboard.php');
        exit;
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
                    <a href="my-requests.php"><li>My requests</li></a>
                    <?php 
                        if($user_type === '1') {
                            echo "
                                <a href='all-requests.php'><li class='selected'>All requests</li></a>
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
                        <a href="my-requests.php"><li>My requests</li></a>
                        <?php 
                            if($user_type === '1') {
                                echo "
                                    <a href='all-requests.php'><li class='selected'>All requests</li></a>
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
                <div class="filter-section">
                    <div class="filter-container">
                        <h1>Filter requests</h1>
                        <form class="filter-form" method="POST" action="">
                            <label for="filter-select">Filter requests by moderator</label>
                            <select id="filter-select" name="filter-select">
                                <?php 
                                $moderators = mysqli_query($db, "SELECT korisnicko_ime, korisnik_id FROM korisnik WHERE tip_korisnika_id = 2");
                                while($row = mysqli_fetch_array($moderators)) {
                                    echo "<option value='".$row['korisnik_id']."'>".$row['korisnicko_ime']."</option>";
                                }
                                ?>
                            </select>
    
                            <label for="date-time">Filter requests by date and time</label>
                            <input type="text" id="date-time" name="date_from" placeholder="Date from...">
                            <input type="text" id="date-time" name="date_to" placeholder="Date to...">
                            <input type="text" id="date-time" name="time_from" placeholder="Time from...">
                            <input type="text" id="date-time" name="time_to" placeholder="Time to...">
                            <button name="submit-filter" class="submit-filter">Update</button>
                        </form>
                    </div>

                    <div class="my-requests-container total-container">
                        <h1 id="request-h1">All requests</h1>
                        <div class="request first-of-type">
                            <p>Selling currency</p>
                            <p>Total amount</p>
                        </div>
                        <div id="request-box">
                            <?php 
                                if(isset($_POST['submit-filter'])) {
                                    $moderator = $_POST['filter-select'];
                                    $date_from = $_POST['date_from'];
                                    $date_to = $_POST['date_to'];
                                    $time_from = $_POST['time_from'];
                                    $time_to = $_POST['time_to'];

                                    if(empty($date_from) || empty($date_from) || empty($date_from) || empty($date_from)) {
                                        $all_requests = mysqli_query($db, "SELECT v.naziv, SUM(z.iznos) as ukupno_prodani_iznos FROM valuta v,
                                        zahtjev z WHERE v.valuta_id = z.prodajem_valuta_id AND z.prihvacen = 1 AND moderator_id = $moderator 
                                        GROUP BY v.valuta_id ORDER BY ukupno_prodani_iznos DESC");
        
                                        while($row = mysqli_fetch_array($all_requests)) {
                                            echo "
                                                <div class='request'>
                                                    <p>".$row['naziv']."</p>
                                                    <p>".$row['ukupno_prodani_iznos']."</p>
                                                </div>
                                            ";
                                        }    
                                    }
                                    else {
                                        $date_from = date("Y-m-d", strtotime($date_from));
                                        $date_to = date("Y-m-d", strtotime($date_to));
                                        $time_from = date("H:i:s", strtotime($time_from));
                                        $time_to = date("H:i:s", strtotime($time_to));
    
                                        $date_time_1 = $date_from . ' ' . $time_from;
                                        $date_time_2 = $date_to . ' ' . $time_to;
                                        
                                                                                                               
                                        $all_requests = mysqli_query($db, "SELECT v.naziv, SUM(z.iznos) as ukupno_prodani_iznos FROM valuta v,
                                        zahtjev z WHERE v.valuta_id = z.prodajem_valuta_id AND z.prihvacen = 1 AND moderator_id = $moderator 
                                        AND datum_vrijeme_kreiranja BETWEEN '$date_time_1' AND '$date_time_2'
                                        GROUP BY v.valuta_id ORDER BY ukupno_prodani_iznos DESC");
        
                                        while($row = mysqli_fetch_array($all_requests)) {
                                            echo "
                                                <div class='request'>
                                                    <p>".$row['naziv']."</p>
                                                    <p>".$row['ukupno_prodani_iznos']."</p>
                                                </div>
                                            ";
                                        }                                                               
                                    }      
                                }
                                else {
                                    $all_requests = mysqli_query($db, "SELECT v.naziv, SUM(z.iznos) as ukupno_prodani_iznos FROM valuta v,
                                    zahtjev z WHERE v.valuta_id = z.prodajem_valuta_id AND z.prihvacen = 1
                                    GROUP BY v.valuta_id ORDER BY ukupno_prodani_iznos DESC");

                                    while($row = mysqli_fetch_array($all_requests)) {
                                        echo "
                                            <div class='request'>
                                                <p>".$row['naziv']."</p>
                                                <p>".$row['ukupno_prodani_iznos']."</p>
                                            </div>
                                        ";
                                    }
                                }
                            ?>
                        </div>
                    </div>
            </section>
        </section>

        <!-- Javascript -->
        <script src="assets/scripts/index.js"></script>
        <script src="assets/scripts/menu.js"></script>
    </body>
</html>