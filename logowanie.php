<?php
include 'config.php';
session_start();

if(isset($_POST['submit'])){

    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $pass = mysqli_real_escape_string($connection, md5($_POST['password']));

    $select = mysqli_query($connection, "SELECT * FROM user WHERE email = '$email' AND haslo = '$pass'") or die('query failed');

    if(mysqli_num_rows($select) > 0){
        $row = mysqli_fetch_assoc($select);
        $_SESSION['id'] = $row['id'];
        header('location:main_site.php');
    }else{
        $message[] = 'Niepoprawny email lub haslo';
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

<div class="logowanie">

    <form action="" method="post">
        <h3>Logowanie</h3>
        <input type="email" name="email" required placeholder="wpisz email" class="box">
        <input type="password" name="password" required placeholder="wpisz haslo" class="box">
        <input type="submit" name="submit" class="btn" value="zaloguj">
        <p>Nie masz jeszcze konta ? <a href="rejestracja.php">zarejestruj sie</a></p>
    </form>

</div>


</body>
<footer></footer>
</html>