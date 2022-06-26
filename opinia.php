<?php

class opinia
{
    private $id_uzytkownika;
    private $id_produktu;
    private $tresc;
    private $ocena;

    /**
     * @param $id_uzytkownika
     * @param $id_produktu
     * @param $tresc
     * @param $ocena
     */
    public function __construct($id_uzytkownika, $id_produktu, $ocena, $tresc)
    {
        $this->id_uzytkownika = $id_uzytkownika;
        $this->id_produktu = $id_produktu;
        $this->ocena = $ocena;
        $this->tresc = $tresc;
    }

    function dodajOpinie()
    {
        include "config.php";
        $query = "INSERT INTO opinia(id_uzytkownika, id_produktu, ocena, tresc) 
                VALUES ('$this->id_uzytkownika', '$this->id_produktu',' $this->ocena',' $this->tresc')";
        mysqli_query($connection, $query);
    }


}