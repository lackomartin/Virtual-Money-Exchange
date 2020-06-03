<?php
    require('connection.php');
    session_start();

    if(!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
    }

    $username = $_GET['user'];
    $_SESSION['username'] = $username;

    $user = mysqli_query($db, "SELECT * FROM korisnik WHERE korisnicko_ime = '$username'");
    while($row = mysqli_fetch_array($user)) {
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
            <input type='password' id='password' name='password' value='".$row['lozinka']."'>
            <label for='select-type'>Select user type:</label>
            <select id='select-type' name='select-user-type'>
        ";
    }

?>
    <?php
        $current_type = mysqli_query($db, "SELECT t.tip_korisnika_id, t.naziv FROM tip_korisnika t, korisnik k WHERE 
        t.tip_korisnika_id = k.tip_korisnika_id AND korisnicko_ime = '$username'");
        $row1 = mysqli_fetch_array($current_type);
        $current_type_id = $row1['tip_korisnika_id'];

        $user_type = mysqli_query($db, "SELECT * FROM tip_korisnika WHERE tip_korisnika_id != $current_type_id");

        echo "
            <option value='".$row1['tip_korisnika_id']."' >".$row1['naziv']."</option>
        ";

        while($row = mysqli_fetch_array($user_type)) {
            echo "
                <option value='".$row['tip_korisnika_id']."' >".$row['naziv']."</option>
            ";
        }
    ?>

<?php
    $user = mysqli_query($db, "SELECT * FROM korisnik WHERE korisnicko_ime = '$username'");
    while($row = mysqli_fetch_array($user)) {
        echo "
            </select>
            <label for='profile-image'>Profile image:</label>
            <input type='text' name='profile-image' id='profile-image' value='".$row['slika']."'>
            <button class='update-info-btn' name='update-info-btn' >Update info</button>
        ";
    }
?>
    
        
        
