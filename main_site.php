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

if (isset($_POST['dodaj'])) {
    $tytul = $_POST['tytul'];
    $zdjecie = $_POST['zdjecie'];
    $opis = $_POST['opis'];
    $opisDuzy = $_POST['opisDuzy'];
    $cenaBez = $_POST['cenaBez'];
    $cenaZ = $_POST['cenaZ'];
    $ilosc = $_POST['ilosc'];
    $id = $_POST['id'];

    echo "dodano do koszyka produkt: " . $tytul;
}

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

    <a href='main_site.php'>
    <br>
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

    <?php

    while($koszulka = mysqli_fetch_assoc($select)) {
        echo"
     <div class='produkt' >

     <img class='zdjecieKoszulki'  src='$koszulka[zdjecie]' width='150px' height='150px'>
     <form method='post' action='szczegoly_produktu.php'>
     
     <input type='submit' class='przejdzDoSzczegolow' name='szczegoly' value='Przejdz do szczegolow'>
     <input type='hidden' value='$koszulka[tytul]' name='tytul'> 
     <input type='hidden' value='$koszulka[zdjecie]' name='zdjecie'> 
     <input type='hidden' value='$koszulka[opis]' name='opis'> 
     <input type='hidden' value='$koszulka[opisDuzy]' name='opisDuzy'>
     <input type='hidden' value='$koszulka[cena_bez_dostawy]' name='cenaBez'> 
     <input type='hidden' value='$koszulka[cena_z_dostawa]' name='cenaZ'> 
     <input type='hidden' value='$koszulka[ilosc]' name='ilosc'> 
     <input type='hidden' value='$koszulka[id]' name='id'>
     </form>
     
        <li >   $koszulka[tytul] </li >
        <li >   Opis: $koszulka[opis]</li >
        <li >   Cena : $koszulka[cena_bez_dostawy]zl</li >
        <li >   Pozostalo: $koszulka[ilosc] sztuk</li >
        <form method='post' action='main_site.php'>
        <input type='submit' class='addToCart' name='dodaj' value='Dodaj do koszyka'>  
        <input type='hidden' value='$koszulka[tytul]' name='tytul'> 
        <input type='hidden' value='$koszulka[zdjecie]' name='zdjecie'> 
        <input type='hidden' value='$koszulka[opis]' name='opis'> 
        <input type='hidden' value='$koszulka[opisDuzy]' name='opisDuzy'> 
        <input type='hidden' value='$koszulka[cena_bez_dostawy]' name='cenaBez'> 
        <input type='hidden' value='$koszulka[cena_z_dostawa]' name='cenaZ'> 
        <input type='hidden' value='$koszulka[ilosc]' name='ilosc'> 
        <input type='hidden' value='$koszulka[id]' name='id'>
        
        </form>
    </div >
    ";
    }
    ?>

</head>
<body>

</body>
<footer></footer>
</html>