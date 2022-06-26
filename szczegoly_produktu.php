<?php
session_start();
include ("config.php");
$zdjecie = $_POST['zdjecie'];
$tytul = $_POST['tytul'];
$opis = $_POST['opis'];
$opisDuzy = $_POST['opisDuzy'];
$cenaBez = $_POST['cenaBez'];
$cenaZ = $_POST['cenaZ'];
$ilosc = $_POST['ilosc'];
$id = $_POST['id'];



//dodawawanie do koszyka
if(isset($_POST['dodaj'])){
    if ($_POST['ileProduktow']<=$ilosc) {
        $ileProduktow = $_POST['ileProduktow'];
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
//uzytkownik
if(empty($_SESSION['user_id'])){
    $_SESSION['user_id'] = 0;
}
$userId =  $_SESSION['user_id'];
$zapytanie2 = "SELECT* FROM user WHERE id='$userId'";

$selectNazwa = mysqli_query($connection, $zapytanie2);
$nazwa = mysqli_fetch_assoc($selectNazwa);

//usuwanie z koszyka
if(isset($_GET["action"]))
{
    if($_GET["action"] == "usun")
    {
        foreach($_SESSION["koszyk"] as $keys => $values)
        {
            if($values["item_id"] == $_GET["id"])
            {
                unset($_SESSION["koszyk"][$keys]);
                echo '<script>alert("Usunieto przedmiot")</script>';
                echo '<script>window.location="koszyk.php"</script>';
            }
        }
    }
}
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
<body>
<?php
//szczegoly produktu
echo"
    <div class='produkt'>
        <img class='zdjecieKoszulki'  src='$zdjecie' width='350px' height='350px'>
        
        <br/><li > $tytul </li >
        <li >   Cena bez dostawy: $cenaBez zl</li >
        <li >   Cena z dostawą: $cenaZ zl</li >
        <li >   Pozostalo: $ilosc sztuk</li >
        <li >   Opis: $opisDuzy</li >
        
        <form method='post' action=''>
        <input type='submit' class='addToCart' name='dodaj' value='Dodaj do koszyka'>
        <input type='text' class='' name='ileProduktow' placeholder='Wpisz ilosc' value='1'>  
        <input type='hidden' value='$tytul' name='tytul'> 
        <input type='hidden' value='$zdjecie' name='zdjecie'> 
        <input type='hidden' value='$opis' name='opis'> 
        <input type='hidden' value='$cenaBez' name='cenaBez'> 
        <input type='hidden' value='$cenaZ' name='cenaZ'> 
        <input type='hidden' value='$ilosc' name='ilosc'>
        <input type='hidden' value='$opisDuzy' name='opisDuzy'>
        <input type='hidden' value='$id' name='id'>
        </form>
        </div>
        "
?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
<footer></footer>
</html>
