<?php
session_start();

$polaczenie = mysqli_connect("127.0.0.1", "root","","sklep_internetowy");

if(empty($_SESSION['user_id'])){
    $_SESSION['user_id'] = 0;
}
$userId =  $_SESSION['user_id'];
$zapytanie2 = "SELECT* FROM user WHERE id='$userId'";

$selectNazwa = mysqli_query($polaczenie, $zapytanie2);
$nazwa = mysqli_fetch_assoc($selectNazwa);
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

    <a href='main_site.php'><br>
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

    <div class="koszyk">
        <table class="tabela">
            <tr>
                <th width="40%">Nazwa</th>
                <th width="10%">Ilosc</th>
                <th width="15%">Cena bez dostawy</th>
                <th width="10%">Razem</th>
                <th width="15%">Usun</th>
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
            <?php
            }
            ?>
        </table>

    </div>





</head>
<body>


</body>
<footer></footer>
</html>
