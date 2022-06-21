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
<html>
<head>
    <link rel="stylesheet" href="css.css">

    <title>
        👕 KOSZULKI PREMIUM 👕
    </title>
    <?php
    if (!empty($_SESSION['user_id'])){
        echo
        '<div class="loginInfo">
        <ul>';
        echo 'Zalogowano jako:';
        echo $nazwa['nazwa'];

        echo '<a href="logout.php"><br>wyloguj</a> 
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
                    <button>Strona glowna</button></a>
                <a href="info_page.php">
                    <button>O nas</button></a>
                <a href="koszyk.php">
                    <button>Koszyk</button></a>
                <a href="logowanie.php">
                    <button>Zaloguj</button></a>
            </ul>
        </nav>
    </div>
    <div class="infoPage">
        <a class="infoPageNapisy"><br/><br/><br/></a>
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

</head>
<body class="body">


</body>
<footer></footer>
</html>