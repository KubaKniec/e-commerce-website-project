<?php
include ("config.php");
session_start();
if(empty($_SESSION['user_id'])){
    $_SESSION['user_id'] = 0;
}

$userId =  $_SESSION['user_id'];
$zapytanie2 = "SELECT * FROM user WHERE id='$userId'";

$selectNazwa = mysqli_query($connection, $zapytanie2);
$nazwa = mysqli_fetch_assoc($selectNazwa);
$email = $_SESSION['email'];

$query = "SELECT * FROM zamowienia WHERE email='$email'";
$select = mysqli_query($connection,$query);


?>

<!DOCTYPE html>
<html>
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
<body class="body">

<div class="produkt">
    <a href="edytujDane.php">
        <button>Edytuj profil</button>
    </a>
    <h3>Moje zamowienia</h3>_____________________________________<br><br>
    <?php
    while($zamowienie = mysqli_fetch_assoc($select)){
        echo "
        
        <li>Cena: $zamowienie[cena]zl</li>
        <li>Data zamowienia: $zamowienie[data]</li>
        <h5>Szczegoly dostawy</h5>
        <li>Miasto: $zamowienie[miasto]</li>
        <li>Adres: $zamowienie[adres]</li>
        <li>Miasto: $zamowienie[miasto]</li>
        <li>Kod pocztowy: $zamowienie[kod]</li>
        <li>Imie: $zamowienie[imie]</li>
        <li>Nazwisko: $zamowienie[nazwisko]</li>
        <li>Numer telefonu: $zamowienie[numer]</li>
        <li>Metoda platnosci: $zamowienie[metodaPlatnosci]</li>
        <li>Metoda dostawy: $zamowienie[metodaDostawy]</li>
        <li>E-mail: $zamowienie[email]</li>_____________________________________";
    }
    ?>

</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
<footer></footer>
</html>
