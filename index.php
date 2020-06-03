<!DOCTYPE html>
<html>
    <head>
        <title>Virtual Money Exchange</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/dashboard.css">
        <link rel="stylesheet" href="assets/css/index.css">
        <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">
    </head>

    <body>
        <header>
            <a href="index.php"><div class="logo">Virtual Money Exchange</div></a>
            <nav>
                <ul>
                    <li><a href="#about">About</a></li>
                    <li><a href="#currency">Currency</a></li>
                    <li><a href="#functionalities">Functionalities</a></li>
                    <li><a href="about_author.html">About author</a></li>
                </ul>
            </nav>

            <a href="login.php"><p class="login">Login</p></a>
        </header>

        <section id="section-header">
            <img src="assets/img/header.png" class="header-img">
            <div class="heading">
                <h1>Welcome to the virtual money exchange</h1>
                <h2>Buy and sell currencies in just a few clicks.</h2>
                <a href="login.php"><button class="get-started">Get started</button></a>
            </div>
        </section>

        <section id="about">
            <h3>About us</h3>
            <div class="about-us">
                <h1>Virtual Money Exchange</h1>
                We provide services of fast and efficient purchase or sale of currencies. 
                Our system allows the exchange of the world's most popular currencies.
            </div>
        </section>

        <section id="currency">
            <h3>Currencies</h3>
            <p>All active currencies in our app.</p>

            <div class="currency-content-index">
                <?php
                    require('connection.php');

                    $currency = mysqli_query($db, "SELECT * FROM valuta");
                    if(mysqli_num_rows($currency) > 0) {
                        while($row = mysqli_fetch_array($currency)) {
                            echo "
                                <div class='item'>
                                    <p hidden>".$row['valuta_id']."</p>
                                    <img src='".$row['slika']."' class='currency-img'>
                                </div>
                            ";
                        }
                    }
                    else {
                        echo "There is no active currencies in our app.";
                    }
                    
                ?>
            </div>
        </section>

        <section id="functionalities">
            <h3>Functionalities</h3>
            <h1>The trusted platform for your exchange</h1>
            <div class="functionalities-container">
                <div class="functionalities">
                    <p><span>&#10004;</span> View all currency information</p>
                    <p><span>&#10004;</span> Input of funds in different values</p>
                    <p><span>&#10004;</span> Update your amounts</p>
                    <p><span>&#10004;</span> Tracking of all requests</p>
                    <p><span>&#10004;</span> Buy and sell currencies in just a few clicks</p>
                </div>
                <div class="screen-img-container">
                    <img src="assets/img/screen.png" class="screen-img">
                </div>
            </div>
        </section>

        <footer>
            <p>Copyright &copy; Martin Lacko</p>
        </footer>

        <!-- Currency info -->
        <div class="background">
            <div class="currency-info-container">
                <a href="#currency" class="close"></a>
                <div class="currency-info-index"></div>
            </div>
        </div>

        <!-- Javascript -->
        <script src="assets/scripts/index.js"></script>
    </body>
</html>