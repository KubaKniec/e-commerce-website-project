<?php
session_start();

include ("config.php");

if(empty($_SESSION['user_id'])){
    $_SESSION['user_id'] = 0;
}

$userId =  $_SESSION['user_id'];
$zapytanie2 = "SELECT* FROM user WHERE id='$userId'";


//usuwanie z koszyka
$selectNazwa = mysqli_query($connection, $zapytanie2);
$nazwa = mysqli_fetch_assoc($selectNazwa);
if(isset($_GET["action"]))
{
    if($_GET["action"] == "usun")
    {
        foreach($_SESSION["koszyk"] as $keys => $values)
        {
            if($values["item_id"] == $_GET["id"])
            {
                $ileProduktow = $_SESSION['ileProduktow'] ;
                $ilosc = $_SESSION['ilosc'];
                $id = $_SESSION['idProduktu'];
                $query = "UPDATE produkty SET ilosc=ilosc +'$ileProduktow' WHERE id='$id'";
                $connection->query($query);

                unset($_SESSION["koszyk"][$keys]);
                echo'<div class="alert alert-info" style="text-align: center">
                        <strong>Usunieto przedmiot</strong> 
                    </div>';

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

        echo '<ahref="logout.php"><br>
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
<div class="koszyk">
    <table class="tabela">
        <tr>
            <th width="40%">Nazwa</th>
            <th width="10%">Ilosc</th>
            <th width="15%">Cena bez dostawy</th>
            <th width="10%">Razem</th>
            <th width="15%">-</th>
        </tr>
        <?php
        if(!empty($_SESSION['koszyk']))
        {
            $total = 0;
            foreach ($_SESSION['koszyk'] as $keys => $values) {
                ?>
                <tr>
                    <td><?php echo $values['item_tytul']?></td>
                    <td><?php echo $values['item_ileProduktow']?></td>
                    <td><?php echo "zl " . $values['item_cenaBez']?></td>
                    <td><?php echo "zl " . number_format($values['item_ileProduktow'] * $values['item_cenaBez'], 2);?></td>
                    <td><a href="koszyk.php?action=usun&id=<?php echo $values['item_id']; ?>"><span class="">usun</span></a></td>
                </tr>
                <?php
                $total = $total + ($values['item_ileProduktow'] * $values['item_cenaBez']);
            }
            ?>
            <tr>

                <td colspan="3" align="right">Razem: </td>
                <td align="right">zl <?php echo number_format($total,2);?></td>
                <td></td>
            </tr>
            <tr>

                <td colspan="3" align="right">Razem z dostawa: </td>
                <td align="right">zl <?php echo number_format($total+5,2);?></td>
            </tr>
            <?php

        }
        ?>
    </table>

</div>
<br>

<div class="formDost">
    <h3>Dane do dostawy</h3>
    <?php

    //kupowanie
    if(isset($_POST['dodaj']) && !empty($_SESSION['koszyk']) && !$_SESSION['user_id'] == 0) {
        $imie = $_POST['imie'];
        $nazwisko = $_POST['nazwisko'];
        $numer = $_POST['numer'];
        $miasto = $_POST['miasto'];
        $adres = $_POST['adres'];
        $kod = $_POST['kod'];
        $metodaPlatnosci = $_POST['metodaPlatnosci'];
        $metodaDostawy = $_POST['metodaDostawy'];
        $email = $_SESSION['email'];
        $data = date('d/n/y H:i:s');
        $cenaZDos = $total+5;

        //validacja email, nr tel, kod pocztowy oraz finalizacja zakupu
        if((filter_var($email, FILTER_VALIDATE_EMAIL)) && preg_match('/^\+48\d{9}$/',$numer) && preg_match("(\b\d{2}-\d{3}\b)",  $kod)){ //validacja email, telefonu i kodu pocztowego
            if($_POST['metodaDostawy'] == "Odbiór w punkcie") {
                $instert = "INSERT INTO `zamowienia`( `cena`, `adres`, `miasto`, `metodaPlatnosci`, `metodaDostawy`, `numer`, `email`, `imie`, `nazwisko`, `kod`, `data`) VALUES 
                    ('$total','$adres','$miasto','$metodaPlatnosci','$metodaDostawy','$numer','$email','$imie','$nazwisko','$kod','$data') ";
                mysqli_query($connection, $instert);
            }
            else{
                $instert = "INSERT INTO `zamowienia`( `cena`, `adres`, `miasto`, `metodaPlatnosci`, `metodaDostawy`, `numer`, `email`, `imie`, `nazwisko`, `kod`, `data`) VALUES 
                    ('$cenaZDos','$adres','$miasto','$metodaPlatnosci','$metodaDostawy','$numer','$email','$imie','$nazwisko','$kod','$data') ";
                mysqli_query($connection, $instert);
            }
            unset($_SESSION['koszyk']);
            header('Location: mojeKonto.php');

        }
        else{
            echo'<div class="alert alert-warning" style="text-align: center">
                    <strong>Email, numer telefonu lub kod pocztowy maja niepoprawny format. Wprowadz dane ponownie</strong> 
            </div>';

        }
    }
    if (empty($_SESSION['koszyk'])){
        echo"Koszyk jest pusty";
    }
    if ($_SESSION['user_id'] == 0){
        echo'<div class="alert alert-warning style="text-align: center">
                    <strong>Aby dokonac zakupu musisz byc zalogowany</strong> 
            </div>';

    }


    ?>
    <!--        formluarz szeczoglow zamowienia-->
    <form method="post">
        <input type="text" name="imie" placeholder="imie" required>
        <input type="text" name="nazwisko" placeholder="nazwisko" required>
        <input type="text" name="numer" placeholder="numer telefonu" required>
        <input type="text" name="miasto" placeholder="miasto" required>
        <input type="text" name="adres" placeholder="adres" required>
        <input type="text" name="kod" placeholder="kod pocztowy" required>
        <select name="metodaPlatnosci">
            <option value="Za pobraniem">Za pobraniem</option>
            <option value="BLIK">BLIK</option>
            <option value="Przelew">Przelew</option>
        </select>
        <select name="metodaDostawy">
            <option value="Paczkomat" >Paczkomat</option>
            <option value="Kurier">Kurier</option>
            <option value="Odbiór w punkcie">Odbior w sklepie</option>
        </select>
        <input class="btn btn-default btn-sm" type="submit" name="dodaj" value="Kup teraz">
    </form>
    <h3></h3>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
<footer></footer>
</html>
