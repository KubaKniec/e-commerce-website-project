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

$query = "SELECT * FROM user WHERE email='$email'";
$select = mysqli_query($connection,$query);

//zmiana danych konta
if (isset($_POST['zmien'])){
    $newNazwa = mysqli_real_escape_string($connection, $_POST['nazwa']);
    $newEmail = mysqli_real_escape_string($connection, $_POST['email']);
    $newHaslo = mysqli_real_escape_string($connection, md5($_POST['haslo']));
    $updateQuery = "UPDATE user SET nazwa='$newNazwa',email='$newEmail',haslo='$newHaslo' WHERE email='$email'";
    mysqli_query($connection,$updateQuery);
    $_SESSION['email'] = $newEmail;
    $email = $_SESSION['email'];
    echo "<script> alert('Zmieniono dane')</script>";
    header("Location: mojeKonto.php");
}

//usuwanie konta
if (isset($_POST['usun'])){
    $deleteQuery = "DELETE FROM user WHERE email='$email'";
    mysqli_query($connection,$deleteQuery);
    echo "<script> alert('Konto zostalo usuniete')</script>";
    session_destroy();
    header("Location: main_site.php");
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
<body class="body">

<div class="produkt">
<!--    edytowanie danych konta formularz-->
    <h3>Wprowadz nowe dane</h3>
    <form method="post">
        <input type="text" name="nazwa" required placeholder="wpisz nową nazwe" class="box">
        <input type="email" name="email" required placeholder="wpisz nowy email" class="box">
        <input type="password" name="haslo" required placeholder="wpisz nowe haslo" class="box">
        <input class="btn btn-default btn-sm" type="submit" name="zmien" value="Zmien dane">
    </form>

<!--    usuwanie konta formularz-->
    <form method="post">
        <br><br>_____________________________________<br>
        <h3>Usun konto</h3>
        <h5>Uwaga! Konto zostanie bezpowrotnie usuniete</h5>
        <input class="btn btn-danger btn-sm" type="submit" name="usun" value="Usun konto">
    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
<footer></footer>
</html>
