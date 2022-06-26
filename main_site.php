<?php
session_start();

include ("config.php");

if(empty($_SESSION['user_id'])){
    $_SESSION['user_id'] = 0;
}

$userId =  $_SESSION['user_id'];
$zapytanie2 = "SELECT* FROM user WHERE id='$userId'";

$selectNazwa = mysqli_query($connection, $zapytanie2);
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
}

//dodawanie przedmiotu (ADMIN)
if(isset($_POST['dodajPrzedmiot'])){
    $tytul = $_POST['tytul'];
    $zdjecie = $_POST['zdjecie'];
    $opis = $_POST['krotki_opis'];
    $opisDuzy = $_POST['dlugi_opis'];
    $cenaBez = $_POST['cena_bez_dostawy'];
    $cenaZ = $_POST['cena_z_dostawa'];
    $ilosc = $_POST['ilosc'];

    $query = "INSERT INTO produkty(tytul, zdjecie, cena_bez_dostawy, cena_z_dostawa, opis, ilosc, opisDuzy) 
    VALUES ('$tytul','$zdjecie','$cenaBez','$cenaZ','$opis','$ilosc','$opisDuzy')";
    mysqli_query($connection, $query);
    header("Location: main_site.php");

}

//usuwanie przedmiotu(ADMIN)
if (isset($_POST['usun'])){
    $nazwa = $_POST['nazwaPrzedmiotu'];
    $query = "DELETE FROM produkty WHERE tytul='$nazwa'";
    mysqli_query($connection,$query);
    header("Location: main_site.php");

}

//dodawanie do koszyka
if(isset($_POST['dodaj'])){
    if ($_POST['ileProduktow']<=$ilosc) {
        $ileProduktow = $_POST['ileProduktow'];
        $_SESSION['ileProduktow'] = $ileProduktow;
        $_SESSION['ilosc'] = $ilosc;
        $_SESSION['idProduktu'] = $id;
        $query = "UPDATE produkty SET ilosc=ilosc -'$ileProduktow' WHERE id='$id'";
        $connection->query($query);

        if(isset($_SESSION['koszyk']))
        {
            $item_array_id = array_column($_SESSION['koszyk'],'item_id');
            if(!in_array($id,$item_array_id))
            {
                $count = count($_SESSION['koszyk']);
                $item_array = array(
                    'item_id' => $_POST['id'],
                    'item_tytul' => $tytul,
                    'item_cenaBez' => $cenaBez,
                    'item_cenaZ' => $cenaZ,
                    'item_ilosc' => $ilosc,
                    'item_ileProduktow' => $_POST['ileProduktow']
                );
                $_SESSION['koszyk'][$count] = $item_array;


            }
            else
            {
                echo '<script> alert("Przedmiot byl juz dodany")</script>';
                echo '<script>window.location="koszyk.php"</script>';
            }
        }
        else
        {
            $item_array = array(
                'item_id' => $_POST['id'],
                'item_tytul' => $tytul,
                'item_cenaBez' => $cenaBez,
                'item_cenaZ' => $cenaZ,
                'item_ilosc' => $ilosc,
                'item_ileProduktow' => $_POST['ileProduktow']

            );
            $_SESSION['koszyk'][0]=$item_array;
        }
        header('Location: koszyk.php');
    }
    else{
        echo '<script> alert("Nie mamy tyle produktow w magazynie")</script>';
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

    <a href='main_site.php'>
    <br>
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
<!--formularz filtrowania/sortowania-->
<div class="produkt">
    <h4>Filtrowanie</h4>
    <form method="post">
        <select name="cena">
            <option value="rosnaco">Cena rosnaco</option>
            <option value="malejaco">Cena malejaco</option>
        </select>
        Cena od:
        <input type="text" name="cenaOd" pattern="\d+" placeholder="od">
        Cena do:
        <input type="text" name="cenaDo" pattern="\d+" placeholder="od">
        <input type="submit" name="filtruj" value="filtruj">
    </form>
</div>
<?php

//filtrowanie/sortowanie

if(empty($_POST['filtruj'])){
    $zapytanie = "SELECT* FROM produkty";
    $select = mysqli_query($connection,$zapytanie);
}
else{
    if($_POST['cena']=="rosnaco") {
        if($_POST['cenaOd'] == null || $_POST['cenaDo'] == null) {
            $zapytanie = "SELECT * FROM produkty order by cena_bez_dostawy asc";
            $select = mysqli_query($connection, $zapytanie);
        }
        else{
            $cenaOd = $_POST['cenaOd'];
            $cenaDo = $_POST['cenaDo'];
            $zapytanie = "SELECT * FROM produkty where cena_bez_dostawy between '$cenaOd' and '$cenaDo' order by cena_bez_dostawy asc";
            $select = mysqli_query($connection, $zapytanie);
        }
    }
    elseif($_POST['cena']=="malejaco") {
        if($_POST['cenaOd'] == null || $_POST['cenaDo'] == null) {
            $zapytanie = "SELECT * FROM produkty order by cena_bez_dostawy desc";
            $select = mysqli_query($connection, $zapytanie);
        }
        else{
            $cenaOd = $_POST['cenaOd'];
            $cenaDo = $_POST['cenaDo'];
            $zapytanie = "SELECT * FROM produkty where cena_bez_dostawy between '$cenaOd' and '$cenaDo' order by cena_bez_dostawy desc";
            $select = mysqli_query($connection, $zapytanie);
        }
    }

}
//wyswietlanie produktow
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
        <input type='text' class='addToCartMainSite' name='ileProduktow' placeholder='Wpisz ilosc' value='1'>  
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
<?php
//dodawnie produktow formularz
if ($_SESSION['czyAdmin']==1){
    echo "<div class='produkt'>
            <h4>Dodaj przedmiot</h4>
            <form method='post'>
                <input type='text' name='tytul' placeholder='tytul' required>
                <input type='text' name='zdjecie' placeholder='zdjecie(nazwa pliku)' required>
                <input type='text' name='cena_bez_dostawy' placeholder='cena bez dostawy' required>
                <input type='text' name='cena_z_dostawa' placeholder='cena z dostawa' required>
                <input type='text' name='krotki_opis' placeholder='krotki opis' required>
                <input type='text' name='dlugi_opis' placeholder='dlugi opis' required>
                <input type='text' name='ilosc' placeholder='ilosc w magazynnie' required>
                <input class='btn btn-success btn-sm' type='submit' name='dodajPrzedmiot' value='Dodaj przedmiot'>
            </form>
          </div>";

    //usuwanie produktu formularz
    echo "<div class='produkt'>
           <h4>Usuwanie przedmiotu</h4>
           <form method='post'>
                <input type='text' name='nazwaPrzedmiotu' placeholder='Wpisz nazwa przedmiotu' required>
                <input class='btn btn-danger btn-sm' type='submit' name='usun' value='Usun przedmiot'>
           </form>";

}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
<footer></footer>
</html>