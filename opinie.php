<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
include("config.php");
include("opinia.php");

if(empty($_SESSION['user_id'])){
    $_SESSION['user_id'] = 0;
}

$id = $_SESSION['id_produktu'];
$userId =  $_SESSION['user_id'];



$zapytanie2 = "SELECT * FROM user WHERE id='$userId'";

$selectNazwa = mysqli_query($connection, $zapytanie2);
$nazwa = mysqli_fetch_assoc($selectNazwa);

if ($_SESSION['czyAdmin']==1) {
    $nazwaUzytkownika = $_POST['nazwa'];
    $zapytanie1 = "SELECT * FROM opinia join user on user.id=opinia.id_uzytkownika WHERE nazwa='$nazwaUzytkownika'";
    $selectid = mysqli_query($connection, $zapytanie1);
    $id_uzytkownika_od_opinii = mysqli_fetch_assoc($selectid);
    $id_uzytkownika_od_opinii = $id_uzytkownika_od_opinii['id'];
}


//dodawanie opinii

if (isset($_POST['dodajOpinie'])) {
    if ($_SESSION['user_id'] != 0){
        $select = mysqli_query($connection, "SELECT * FROM opinia WHERE id_uzytkownika = '$userId' and id_produktu='$id'");
        if (mysqli_num_rows($select) > 0) {
            echo '<div class="alert alert-warning" style="text-align: center">
                        <strong>Dodales juz opinie o tym produkcie</strong> 
                      </div>';
        } //walidacja oceny
        elseif ($_POST['ocena'] <= 5 && $_POST['ocena'] > 0) {
            $ocena = $_POST['ocena'];
            $tresc = $_POST['tresc'];
            $opinia = new opinia($_SESSION['user_id'], $id, $ocena, $tresc);
            $opinia->dodajOpinie();
        }
        else{
            echo '<div class="alert alert-warning" style="text-align: center">
                 <strong>Opinia wykracza poza zakres. Wpisz ocene od 1 do 5</strong> 
              </div>';
        }
    }
    else{
        echo '<div class="alert alert-warning" style="text-align: center">
                 <strong>Aby dodac opinie musisz byc zalogowanym</strong> 
              </div>';
    }

}

?>
<!DOCTYPE html>
<html lang="pl">
<head>

    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>
        👕 KOSZULKI PREMIUM 👕
    </title>
    <?php
    if (!empty($_SESSION['user_id'])){
        echo
        '<div class="loginInfo">
        <ul>';
        echo 'Zalogowano jako: ';
        echo $nazwa['nazwa'];
        if($_SESSION['czyAdmin']==1){
            echo '<br>Konto ma status ADMINA';
        }

        echo '<a  href="logout.php"><br>
                   <button class="btn btn-default">Wyloguj</button></a> 
                   <a href="mojeKonto.php">
                   <button class="btn btn-default">Moje konto</button></a>
        </ul>
    </div>';
    }
    ?>

    <a href='main_site.php'><br>
        <img class="logo" src="logo.png" ></a>
    <div class="navigationbar">
        <nav>
            <ul>
                <a href="main_site.php">
                    <button class="btn btn-default btn-sm">Strona glowna</button></a>
                <a href="info_page.php">
                    <button class="btn btn-default btn-sm">O nas</button></a>
                <a href="koszyk.php">
                    <button class="btn btn-default btn-sm">Koszyk</button></a>
                <a href="logowanie.php">
                    <button class="btn btn-default btn-sm">Zaloguj</button></a>
            </ul>
        </nav>
    </div>

</head>
<body>


<!--dodawanie opinii formularz-->
<div class="produkt">
    <form method="post">
        <input class="opiniaOcena" type="text" name="ocena" pattern="\d+" placeholder="ocena od 1 do 5" required><br>
        <input class="opinia" type="text" name="tresc" placeholder="wpisz opinie" required><br>
        <input type="submit" name="dodajOpinie" value="Dodaj opinie">
    </form>
</div>
<br>

<?php //wyswietlanie opinii
if (isset($_POST['przejdzDoOpinii'])){
    $_SESSION['img'] = $_POST['zdjecie'];
}
$zapytanie = "SELECT * FROM opinia join user on user.id=id_uzytkownika WHERE id_produktu='$id'";
$select = mysqli_query($connection,$zapytanie);
echo "<div class='produkt'>
        <h3 style='text-align: center'>Opinie o produkcie</h3>
        <img class='zdjecieKoszulki'  src='$_SESSION[img]' width='350px' height='350px'>
      </div>";

while($opinia = mysqli_fetch_assoc($select)){
echo "<div class='produkt'>
    <li>Dodno przez: $opinia[nazwa]</li>
    <li>Ocena: $opinia[ocena] / 5</li>
    <li>Tresc: $opinia[tresc]</li>
</div>";
}


//usuwanie opinii formularz
if($_SESSION['czyAdmin']==1){
    echo "<br><div class='produkt'>
           <h4>Usuwanie opinii</h4>
           <form method='post'>
                <input style='width: 450px' type='text' name='nazwa' placeholder='Wpisz nazwa uzytkownika, ktorego opinia ma zostac usunieta' required>
                <input class='btn btn-danger btn-sm' type='submit' name='usun' value='Usun opinie'>
           </form>";

    if (isset($_POST['usun'])){

        $query = "DELETE FROM opinia WHERE id_uzytkownika='$id_uzytkownika_od_opinii' and id_produktu='$id'";
        mysqli_query($connection,$query);
        header("Location: main_site.php");
    }
}

?>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
<footer></footer>
</html>
