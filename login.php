<?php
    require('connection.php');
    session_start();

    $login_message = "";

    if(isset($_POST['login'])) {
        $error = "";
        $username = strip_tags($_POST['username']);
        $password = strip_tags($_POST['password']);

        $username = stripslashes($username);
        $password = stripslashes($password);

        $username = mysqli_real_escape_string($db, $username);
        $password = mysqli_real_escape_string($db, $password);

        if(!isset($username) || empty($username)) {
            $error .= "You must enter username!";
        }
        else if(!isset($password) || empty($password)) {
            $error .= "You must enter password!";
        }

        if(empty($error)) {
            $sql = mysqli_query($db, "SELECT * FROM korisnik WHERE `korisnicko_ime` = '$username'");
        
            if(mysqli_num_rows($sql) > 0) {
                $row = mysqli_fetch_array($sql);
                $hash = $row['lozinka'];

                if(md5($password) == $hash) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $row['korisnik_id'];
                    $_SESSION['user_type'] = $row['tip_korisnika_id'];
                    $_SESSION['name'] = $row['ime'];
                    $_SESSION['lastname'] = $row['prezime'];

                    header('Location: dashboard.php');
                }
                else {
                    $login_message = "Incorrect password!";
                }
            }
            else {
                $login_message = "Incorrect username!";
            }
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
        <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">
    </head>

    <body>
        <a href="index.php"><p class="back-btn">Back</p></a>
        <section class="form-section">
            <h1>Sign in</h1>
            <form class="login-form" name="login-form" method="POST" action="">
                <input type="text" name="username" placeholder="Userame...">
                <input type="password" name="password" placeholder="Password...">
                <p class="message"><?php echo $login_message?></p>
                <?php
                    if(isset($error)) {
                        echo "<p class='message'> $error </p>";
                    }
                ?>
                <button name="login">Submit</button>
            </form>
        </section>
    </body>
</html>