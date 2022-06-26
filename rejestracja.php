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


if (isset($_POST['submit'])) {
    $uppercase = preg_match('@[A-Z]@', $_POST['password']);
    $lowercase = preg_match('@[a-z]@', $_POST['password']);
    $number    = preg_match('@[0-9]@', $_POST['password']);
    $specialChars = preg_match('@[^\w]@', $_POST['password']);

    if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($_POST['password']) < 8) {
    echo '<div class="alert alert-warning" style="text-align: center">
                <strong>Haslo musi miec conajmniej 8 znakow, conajmniej 1 duza litere, 1 znak specjalny oraz 1 znak normalny</strong> 
          </div>';

        }
    else{
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {  //validacja email
            $name = mysqli_real_escape_string($connection, $_POST['name']);
            $email = mysqli_real_escape_string($connection, $_POST['email']);
            $pass = mysqli_real_escape_string($connection, md5($_POST['password']));
            $confirmPass = mysqli_real_escape_string($connection, md5($_POST['confirmPass']));

            if ($pass === $confirmPass) {
                $select = mysqli_query($connection, "SELECT * FROM user WHERE email = '$email' AND haslo = '$pass'") or die('query failed');

                if (mysqli_num_rows($select) > 0) {
                    echo '<div class="alert alert-warning" style="text-align: center">
                        <strong>Taki uzytkownik juz istnieje</strong> 
                      </div>';
                } else {
                    mysqli_query($connection, "INSERT INTO user(nazwa, email, haslo) VALUES('$name', '$email', '$pass')") or die('query failed');
                    header('location:logowanie.php');
                }
            } else {
                echo '<div class="alert alert-warning" style="text-align: center">
                        <strong>Pola HASLO oraz POTWIERDZ HASLO musza byc takie same. Wprowadz dane ponownie</strong> 
                      </div>';
            }

        } else {
            echo '<div class="alert alert-warning" style="text-align: center">
                      <strong>Email ma niepoprawny format. Wprowadz dane ponownie</strong> 
                  </div>';
        }
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


<?php
if(isset($message)){
    foreach($message as $message){
        echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
    }
}
?>
<div class="rejestracja">
    <!--formularz rejestracji z walidacja email-->
    <form action="" method="post">
        <h3>Rejestracja</h3>
        <input type="text" name="name" required placeholder="wpisz nazwe" class="box">
        <input type="text" name="email" required placeholder="wpisz email" class="box">
        <input type="password" name="password" required placeholder="wpisz haslo" class="box">
        <input type="password" name="confirmPass" required placeholder="potwierdz haslo" class="box">
        <input type="submit" name="submit" class="btn" value="zarejestruj">
        <p>Masz juz konto ?  <a href="logowanie.php">Zaloguj</a></p>
    </form>

</div>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
<footer></footer>
</html>

