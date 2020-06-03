<?php 
    require('connection.php');
    session_start();

    if(!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
    }
    
        $name = $_GET['name'];
        $update = mysqli_query($db, "SELECT v.valuta_id, v.naziv, v.tecaj, v.slika, v.zvuk, v.aktivno_od, v.aktivno_do,
        k.korisnicko_ime, k.korisnik_id FROM valuta v, korisnik k WHERE v.moderator_id = k.korisnik_id AND naziv = '$name'");

        while($row = mysqli_fetch_array($update)) {
            $currentModerator = $row['korisnik_id'];
            $_SESSION['currency_id'] = $row['valuta_id'];

            echo "
                <label for='currency-name'>Currency name:</label>
                <input type='text' id='currency-name' value='".$row['naziv']."' name='currency-name'>
                <label for='currency-rate'>Currency rate:</label>
                <input type='number' step='0.01' id='currency-rate' value='".$row['tecaj']."' name='rate'>
                <label for='select-moderator'>Select moderator for currency:</label>
                <select id='select-moderator' name='select-moderator'>
                    <option value='".$row['korisnik_id']."'>".$row['korisnicko_ime']."</option>"; 
        }
?>
                    <?php 
                        $moderators = mysqli_query($db, "SELECT korisnicko_ime AS kor_ime, korisnik_id AS kor_id FROM korisnik 
                        WHERE tip_korisnika_id = 2 AND korisnik_id != $currentModerator");

                        while($row = mysqli_fetch_array($moderators)) {
                        echo "<option value='".$row['kor_id']."'>".$row['kor_ime']."</option>";
                        } 
                    ?>

<?php 
        $update = mysqli_query($db, "SELECT v.valuta_id, v.naziv, v.tecaj, v.slika, v.zvuk, v.aktivno_od, v.aktivno_do,
        k.korisnicko_ime, k.korisnik_id FROM valuta v, korisnik k WHERE v.moderator_id = k.korisnik_id AND naziv = '$name'");

        while($row = mysqli_fetch_array($update)) {
            echo"
                </select>
                <label for='currency-image'>Currency image:</label>
                <input type='text' id='currency-image' value='".$row['slika']."' name='image'>
                <label for='currency-audio'>Audio (optional):</label>
                <input type='text' id='currency-audio' value='".$row['zvuk']."' name='audio'>
                <label for='active-from'>Active from:</label>
                <input type='text' id='active-from' value='".$row['aktivno_od']."' name='active-from'>
                <label for='active-until'>Active until:</label>
                <input type='text' id='active-until' value='".$row['aktivno_do']."' name='active-until'>
                <button class='add-currency' name='update-currency'>Update currency</button>
            ";
        }
?>

