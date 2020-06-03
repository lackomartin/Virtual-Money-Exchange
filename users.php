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

    if(isset($_POST['add-user'])) {
        $error = "";
        $username = $_POST['username'];
        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $userType = $_POST['select-user-type'];
        $image = $_POST['profile-image'];
        $password = $_POST['password'];

        if(!isset($username) || empty($username)) {
            $error .= "You must enter username!";
        }
        else if(!isset($name) || empty($name)) {
            $error .= "You must enter name!";
        }
        else if(!isset($lastname) || empty($lastname)) {
            $error .= "You must enter lastname!";
        }
        else if(!isset($password) || empty($password)) {
            $error .= "You must enter password!";
        }

        if(empty($error)) {
            $password = md5($password);

            $user = mysqli_query($db, "INSERT INTO `korisnik`(`tip_korisnika_id`, `korisnicko_ime`, `lozinka`, `ime`, `prezime`, `email`,
            `slika`) VALUES ('$userType', '$username', '$password', '$name', '$lastname', '$email', '$image')");

            echo "
            <script>
                alert('Complete!');
                window.location = 'users.php';
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
                                <a href='users.php'><li class='selected'>Users</li></a>
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
                                    <a href='users.php'><li class='selected'>Users</li></a>
                                ";
                            }
                        ?>
                    </ul>
                </nav>
                <div class="logout"><a href="logout.php"><p>Logout</p></a></div>
            </section>
    
            <section class="main-section">
                <div class="users-container">
                    <div class="add-user-box">
                        <h1>Add new user</h1>
                        <form name="new-currency-form" class="user-form" style="margin-left: 20px; width: 60%;" method="POST" action="">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" placeholder="username...">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" placeholder="Name...">
                            <label for="lastname">Lastname:</label>
                            <input type="text" id="lastname" name="lastname" placeholder="Lastname...">
                            <label for="email">Email:</label>
                            <input type="text" id="email" name="email" placeholder="Email...">
                            <label for="email">Password:</label>
                            <input type="password" id="password" name="password" placeholder="Password...">
                            <label for="select-type">Select user type:</label>
                            <select id="select-type" name="select-user-type">
                                <?php
                                    $user_type = mysqli_query($db, "SELECT * FROM tip_korisnika");
                                    while($row = mysqli_fetch_array($user_type)) {
                                        echo "
                                            <option value='".$row['tip_korisnika_id']."'>".$row['naziv']."</option>
                                        ";
                                    }
                                ?>
                            </select>
                            <label for="profile-image">Profile image:</label>
                            <input type="text" id="profile-image" name="profile-image" placeholder="Image src...">
                            <?php
                                if(isset($error)) {
                                    echo "<p class='message'> $error </p>";
                                }
                            ?>
                            <button class="update-info-btn" name="add-user">Add user</button>
                        </form>
                    </div>

                    <div class="users-info-box">
                        <h1>Users</h1>
                        <div class="users">
                            <div class="user">
                                <p>Username</p>
                                <p>Name</p>
                                <p>Lastname</p>
                                <p>Email</p>
                            </div>
                            <?php 
                                $all_users = mysqli_query($db, "SELECT * FROM korisnik"); 
                                while($row = mysqli_fetch_array($all_users)) {
                                    echo "
                                        <div class='user'>
                                            <p>".$row['korisnicko_ime']."</p>
                                            <p>".$row['ime']."</p>
                                            <p>".$row['prezime']."</p>
                                            <p>".$row['email']."</p>
                                            <button class='show-info-btn'>Show info</button>
                                        </div>
                                    ";
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </section>
                
        </section>

        <!-- update users -->
        <div class="background">
            <div class="update-user-box">
                <a href="#" class="close"></a>
                <h1>Update user profile</h1>
                <form name="user-form" class="update-user-form" method="POST" action="update_usr.php">
                </form>
            </div>
        </div>

        <!-- Javascript -->
        <script src="assets/scripts/index.js"></script>
        <script src="assets/scripts/menu.js"></script>
    </body>
</html>