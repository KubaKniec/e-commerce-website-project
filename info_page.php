<?php
session_start();
$zapytanie = "SELECT* FROM produkty";
$polaczenie = mysqli_connect("127.0.0.1", "root","","sklep_internetowy");

if(empty($_SESSION['user_id'])){
    $_SESSION['user_id'] = 0;
}
$userId =  $_SESSION['user_id'];
$zapytanie2 = "SELECT* FROM user WHERE id='$userId'";

$select = mysqli_query($polaczenie,$zapytanie);
$selectNazwa = mysqli_query($polaczenie, $zapytanie2);
$nazwa = mysqli_fetch_assoc($selectNazwa);
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
<body class="body">
<div class="infoPage">
    <h3>O nas</h3>
    <a class="infoPageNapisy">!!! Witaj w naszym sklepie !!! <br/></a>
    <a class="infoPageNapisy">Znajdziesz tu tylko najwyzszej jakosci produkty. <br/></a>
    <a class="infoPageNapisy">Termin dostawy to od 2 dni roboczych do roku. <br/></a>
    <a class="infoPageNapisy">To zalezy od tego czy bedzie nam sie chcialo wysylac twoja paczke. <br/></a>
    <a class="infoPageNapisy">Pozdrawiamy serdecznie, zespol Koszulki Premium <br/></a>
    <a class="infoPageNapisy">Kontakt: <br/></a>
    <a class="infoPageNapisy">📞 +48 100 555 100<br/></a>
    <a class="infoPageNapisy">📧 najlepsze.koszulki@tshirt.com<br/></a>
    <a class="infoPageNapisy"><br/><br/><br/></a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
<footer></footer>
</html>