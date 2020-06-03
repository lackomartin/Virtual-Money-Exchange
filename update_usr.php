<?php
    require('connection.php');
    session_start();

    if(!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
    }

    $user = $_SESSION['username'];
    $username = $_POST['username'];
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $user_type = $_POST['select-user-type'];
    $image = $_POST['profile-image'];

    $password = $_POST['password'];
    $password = md5($password);

    if(isset($_POST['update-info-btn'])) {
        if(empty($username) || empty($name) || empty($lastname) || empty($email) || empty($password)) {
            echo "
            <script>
                alert('You must enter all values!');
                window.location = 'users.php';
            </script>
            ";
        }
        else {
            $update_user = mysqli_query($db, "UPDATE `korisnik` SET `korisnicko_ime` = '$username', `ime` = '$name', `prezime` = '$lastname',
            `tip_korisnika_id` = '$user_type', `email` = '$email', `lozinka` = '$password', `slika` = '$image' WHERE korisnicko_ime = '$user'");

            echo "
                <script>
                    alert('Complete!');
                    window.location = 'users.php';
                </script>
            ";
        }
    }
?>