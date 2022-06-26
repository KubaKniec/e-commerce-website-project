<?php
include ("config.php");
session_start();
if(empty($_SESSION['user_id'])){
    $_SESSION['user_id'] = 0;
}
$userId =  $_SESSION['user_id'];
$zapytanie2 = "SELECT* FROM user WHERE id='$userId'";

$selectNazwa = mysqli_query($connection, $zapytanie2);
$nazwa = mysqli_fetch_assoc($selectNazwa);

if(isset($_POST['submit'])){
    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $pass = mysqli_real_escape_string($connection, md5($_POST['password']));

        $select = mysqli_query($connection, "SELECT * FROM user WHERE email = '$email' AND haslo = '$pass'") or die('query failed');

        if(mysqli_num_rows($select) > 0){
            $_SESSION['email'] = $_POST['email'];
            $row = mysqli_fetch_assoc($select);
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['czyAdmin'] = $row['czyAdmin'];

            header('location:main_site.php');
        }
        else{
            echo '<div class="alert alert-warning" style="text-align: center">
                 <strong>zly email lub haslo!</strong> 
              </div>';
        }
    }
    else{
        echo '<div class="alert alert-warning" style="text-align: center">
                 <strong>Email ma niepoprawny format. Wpisz dane ponownie</strong> 
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
<body class="body">



<div class="logowanie">
    <?php
    if(isset($message)){
        foreach($message as $message){
            echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
        }
    }
    ?>
    <form action="" method="post">
        <h3>Logowanie</h3>
        <input type="text" name="email" required placeholder="wpisz email" class="box">
        <input type="password" name="password" required placeholder="wpisz haslo" class="box">
        <input type="submit" name="submit" class="btn" value="zaloguj">
        <p>Nie masz jeszcze konta ? <a href="rejestracja.php">zarejestruj sie</a></p>
    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
<footer></footer>
</html>