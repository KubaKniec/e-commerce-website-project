<?php
$polaczenie = mysqli_connect("127.0.0.1", "root","","sklep_internetowy");
//$zapytanie = "SELECT tytul,opis,cena_bez_dostawy,cena_z_dostawa,ilosc,zdjecie FROM produkty";
$zapytanie = "SELECT* FROM produkty";

$select = mysqli_query($polaczenie,$zapytanie);


if (isset($_POST['dodaj'])) {
    $tytul = $_POST['tytul'];
    $zdjecie = $_POST['zdjecie'];
    $opis = $_POST['opis'];
    $cenaBez = $_POST['cenaBez'];
    $cenaZ = $_POST['cenaZ'];
    $ilosc = $_POST['ilosc'];
    echo "dodano do koszyka produkt" . $tytul;
}

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css.css">

    <title>
        👕 KOSZULKI PREMIUM 👕
    </title>
    <a href='main_site.php'>
    <img class="logo" src="logo.png" ></a>
    <div class="navigationbar">
        <nav>
            <ul>
                <a href="main_site.php">
                    <button>Strona glowna</button></a>
                <a href="info_page.php">
                    <button>O nas</button></a>
                <a href="">
                    <button>Jestem Adminem</button></a>
            </ul>
        </nav>
    </div>
    <?php
    while($koszulka = mysqli_fetch_assoc($select)) {
        echo"
     <div class='produkt' >
     <a href='produktDetails.php'>
     <img class='zdjecieKoszulki' src='$koszulka[zdjecie]' width='150px' height='150px'>
     </a>
        <li >   $koszulka[tytul] </li >
        <li >   Opis: $koszulka[opis]</li >
        <li >   Cena bez dostawy: $koszulka[cena_bez_dostawy]zl</li >
        <li >   Cena z dostawą: $koszulka[cena_z_dostawa]zl</li >
        <li >   Pozostalo: $koszulka[ilosc] sztuk</li >
        <form method='post' action='main_site.php'>
        <input type='submit' class='addToCart' name='dodaj' value='Dodaj do koszyka'>  
        <input type='hidden' value='$koszulka[tytul]' name='tytul'> 
        <input type='hidden' value='$koszulka[zdjecie]' name='zdjecie'> 
        <input type='hidden' value='$koszulka[opis]' name='opis'> 
        <input type='hidden' value='$koszulka[cena_bez_dostawy]' name='cenaBez'> 
        <input type='hidden' value='$koszulka[cena_z_dostawa]' name='cenaZ'> 
        <input type='hidden' value='$koszulka[ilosc]' name='ilosc'> 
        </form>
    </div >
    ";
    }

    ?>

</head>
<body class="body">



</body>
<footer></footer>
</html>