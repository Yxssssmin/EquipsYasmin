<?php
namespace App\Service;

class ServeiDadesEquips {

    private $equips = array(
        array("codi" => 1, "nom" => "Os Tres Porquinhos", "curs" => "22/23", "nota" => 10, "cicle" => "DAW", "imatge" => "imagen1.png"),
        array("codi" => 2, "nom" => "Equip Roig", "curs" => "22/23", "nota" => 8, "cicle" => "DAM", "imatge" => "imagen2.png"),
        array("codi" => 3, "nom" => "Equip Verd", "curs" => "22/23", "nota" => 7, "cicle" => "ASIX", "imatge" => "imagen3.png"),
    );

    public function get() {
        return $this->equips;
    }
} 


?>