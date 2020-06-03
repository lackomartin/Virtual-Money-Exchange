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
        <link rel="stylesheet" href="assets/css/currency.css">
        <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                <a href="#" class="close-menu"></a>
                <ul>
                    <a href="dashboard.php"><li>Dashboard</li></a>
                    <a href="wallet.php"><li>Wallet</li></a>
                    <a href="exchange.php"><li>Exchange</li></a>
                    <a href="my-requests.php"><li>My requests</li></a>
                    <?php 
                        if($user_type === '1') {
                            echo "
                                <a href='all-requests.php'><li>All requests</li></a>
                                <a href='currency.php'><li class='selected'>Curency</li></a>
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
                                    <a href='all-requests.php'><li>All requests</li></a>
                                    <a href='currency.php'><li class='selected'>Curency</li></a>
                                    <a href='users.php'><li>Users</li></a>
                                ";
                            }
                        ?>
                    </ul>
                </nav>
                <div class="logout"><a href="logout.php"><p>Logout</p></a></div>
            </section>
    
            <section class="main-section">
                <div class="currency-container">
                    <div class="add-currency-container">
                        <h1>Add new currency</h1>
                        <form class="new-currency-form" method="POST" action="add_currency.php">
                            <label for="currency-name">Currency name:</label>
                            <input type="text" id="currency-name" name="currency-name" placeholder="Currency name...">
                            <label for="currency-rate">Currency rate:</label>
                            <input type="number" id="currency-rate" name="currency-rate" placeholder="Currency rate...">
                            <label for="select-moderator">Select moderator for currency:</label>
                            <select id="select-moderator" name="select-moderator">
                            <?php
                                $moderators = mysqli_query($db, "SELECT * FROM korisnik WHERE tip_korisnika_id = 2");

                                while($row = mysqli_fetch_array($moderators)) {
                                    echo "<option value='".$row['korisnik_id']."'>".$row['korisnicko_ime']."</option>";
                                }
                            ?>
                            </select>
                            <label for="currency-image">Currency image:</label>
                            <input type="text" id="currency-image" name="currency-image" placeholder="Image url...">
                            <label for="currency-audio">Audio (optional):</label>
                            <input type="text" id="currency-audio" name="currency-audio" placeholder="Audio url...">
                            <label for="active-from">Active from:</label>
                            <input type="text" id="active-from" name="active-from"  placeholder="Active from...">
                            <label for="active-until">Active until:</label>
                            <input type="text" id="active-until" name="active-until" placeholder="Active until...">
                            <?php
                                if(isset($_SESSION['error'])) {
                                    $error = $_SESSION['error'];
                                    echo "<p class='message'> $error </p>";
                                }
                            ?>
                            <button class="add-currency" name="add-currency">Add currency</button>
                        </form>
                    </div>

                    <div class="currency-box">
                        <h1>Current currency</h1>
                        <div class="curr-box">
                            <div class="currency">
                                <p>Currency Name</p>
                                <p>Rate</p>
                                <p>Moderator</p>
                                <p>Active from</p>
                                <p>Active until</p>
                            </div>
                            <?php 
                                $sql = mysqli_query($db, "SELECT v.naziv, v.tecaj, v.slika, v.zvuk, v.aktivno_od, v.aktivno_do,
                                k.korisnicko_ime FROM valuta v, korisnik k WHERE v.moderator_id = k.korisnik_id");
                                while($row = mysqli_fetch_array($sql)) {
                                    echo "
                                    <div class='currency'>
                                        <p id='name-currency'>".$row['naziv']."</p>
                                        <p>".$row['tecaj']."</p>
                                        <p>".$row['korisnicko_ime']."</p>
                                        <p>".$row['aktivno_od']."</p>
                                        <p>".$row['aktivno_do']."</p>
                                        <button class='update-currency-btn'>Update</button>
                                    </div>
                                    ";
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </section>
                
        </section>

        <!-- update currency -->
        <div class="background">
            <div class="update-currency-box">
                <a href="#" class="close"></a>
                <h1>Update currency</h1>
                <form name="new-currency-form" class="update-currency-form" method="POST" action="update_curr.php">
                </form>
            </div>
        </div>

        <!-- Javascript -->
        <script src="assets/scripts/index.js"></script>
        <script src="assets/scripts/menu.js"></script>
    </body>
</html>

<?php
    unset($_SESSION['error']);
?>

