<?php
    require('connection.php');
    session_start();

    if(!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
    }

    $userID = $_SESSION['id'];
    $user_type = $_SESSION['user_type'];

    if(isset($_POST['update-profile'])) {
        $username = $_POST['username'];
        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password = md5($password);
        
        
        if($_POST['image'] != "") {
            $image = $image = $_POST['image'];

            $user = mysqli_query($db, "UPDATE `korisnik` SET `korisnicko_ime` = '$username', `ime` = '$name', `prezime` = '$lastname',
            `email` = '$email', `lozinka` = '$password', `slika` = '$image' WHERE korisnik_id = '$userID'");
        }
        else {
            $user = mysqli_query($db, "UPDATE `korisnik` SET `korisnicko_ime` = '$username', `ime` = '$name', `prezime` = '$lastname',
            `email` = '$email', `lozinka` = '$password' WHERE korisnik_id = '$userID'");
        }

        echo "
            <script>
                alert('Complete!');
                window.location = 'profile.php';
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
        <link rel="stylesheet" href="assets/css/users.css">
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
                <div class="users-container">
                    <div class="users-info-box" style="margin-right: 20px; height: 30vh;">
                        <h1><?php echo $_SESSION['name'] ." ". $_SESSION['lastname']?></h1>
                        <?php 
                            $user_img = mysqli_query($db, "SELECT slika FROM `korisnik` WHERE korisnik_id = $userID");
                            $row = mysqli_fetch_array($user_img);

                            if($row['slika'] != "") {
                                echo "<img src='".$row['slika']."' class='user-img'>";
                            }
                            else {
                                echo "<img src='assets/img/user.png' class='user-img'>";
                            }
                            
                        ?>
                    </div>

                    <div class="add-user-box">
                        <h1>Personal information</h1>
                        <form name="new-currency-form" class="user-form" style="margin-left: 20px; width: 60%;" method="POST" action="">
                            <?php
                                $user_info = mysqli_query($db, "SELECT * FROM `korisnik` WHERE korisnik_id = $userID");
                                while($row = mysqli_fetch_array($user_info)) {
                                    echo "
                                        <label for='username'>Username:</label>
                                        <input type='text' id='username' name='username' value='".$row['korisnicko_ime']."'>
                                        <label for='name'>Name:</label>
                                        <input type='text' id='name' name='name' value='".$row['ime']."'>
                                        <label for='lastname'>Lastname:</label>
                                        <input type='text' id='lastname' name='lastname' value='".$row['prezime']."'>
                                        <label for='email'>Email:</label>
                                        <input type='text' id='email' name='email' value='".$row['email']."'>
                                        <label for='password'>Password:</label>
                                        <input type='password' id='password' name='password' value='".$row['lozinka']."' 
                                        placeholder='Type new password'>
                                        <label for='image'>Image:</label>
                                        <input type='text' id='profile-image' name='image' value='".$row['slika']."'>
                                    ";
                                }
                            ?>
                            <button class="update-info-btn" name="update-profile">Update profile</button>
                        </form>
                    </div>
                </div>
            </section>
                
        </section>

        <!-- update currency -->
        <div class="background">
            <div class="update-user-box">
                <a href="#" class="close"></a>
                <h1>Update user profile</h1>
                <form name="new-currency-form" class="user-form">
                    <label for="username">Username:</label>
                    <input type="text" id="username" value="username">
                    <label for="name">Name:</label>
                    <input type="text" id="name" value="Name">
                    <label for="lastname">Lastname:</label>
                    <input type="text" id="lastname" value="Lastname">
                    <label for="email">Email:</label>
                    <input type="text" id="email" value="Email">
                    <label for="select-type">Select user type:</label>
                    <select id="select-type" name="select-moderator">
                        <option>Type 1</option>
                        <option>Type 2</option>
                        <option>Type 3</option>
                    </select>
                    <label for="profile-image">Profile image:</label>
                    <input type="file" id="profile-image">
                    <button class="update-info-btn">Update info</button>
                </form>
            </div>
        </div>

        <!-- Javascript -->
        <script src="assets/scripts/index.js"></script>
        <script src="assets/scripts/menu.js"></script>
    </body>
</html>