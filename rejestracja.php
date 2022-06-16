<?php
session_start();

include 'config.php';

if (isset($_POST['submit'])) {

    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $pass = mysqli_real_escape_string($connection, md5($_POST['pass']));
    $confirmPass = mysqli_real_escape_string($connection, md5($_POST['confirmPass']));

    $select = mysqli_query($connection, "SELECT * FROM user WHERE email = '$email' AND haslo = '$pass'") or die('query failed');

    if (mysqli_num_rows($select) > 0) {
        $message[] = 'user already exist!';
    } else {
        mysqli_query($connection, "INSERT INTO user(nazwa, email, haslo) VALUES('$name', '$email', '$pass')") or die('query failed');
        $message[] = 'registered successfully!';
        header('location:logowanie.php');
    }

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
                <a href="koszyk.php">
                    <button>Koszyk</button></a>
                <a href="logowanie.php">
                    <button>Zaloguj</button></a>
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

    <form action="" method="post">
        <h3>Rejestracja</h3>
        <input type="text" name="name" required placeholder="wpisz nazwe" class="box">
        <input type="email" name="email" required placeholder="wpisz email" class="box">
        <input type="password" name="password" required placeholder="wpisz haslo" class="box">
        <input type="password" name="confirmPass" required placeholder="potwierdz haslo" class="box">
        <input type="submit" name="submit" class="btn" value="zarejestruj">
        <p>Masz juz konto ? <a href="logowanie.php">Zaloguj</a></p>
    </form>

</div>

</body>
</html>



</body>
<footer></footer>
</html>

