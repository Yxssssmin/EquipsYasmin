<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EquipsController {

    private $equips = array(
    array("codi" => "1", "nom" => "Equip Roig", "cicle" => "DAW","curs" => "22/23" , "membres" => "Elena, Vicent, Joan, Maria"),
    array("codi" => "2", "nom" => "Equip Taronja", "cicle" => "DAW","curs" => "22/23" , "membres" => "Marina, Marcos, Pablo, Yasmin"),
    array("codi" => "3", "nom" => "Equip Verd", "cicle" => "DAW","curs" => "22/23" , "membres" => "Fran, Pepe, Jose, Jaume"),
    array("codi" => "4", "nom" => "Equip Blau", "cicle" => "DAW","curs" => "22/23" , "membres" => "Sergi, Miguel, Juan, Marta"),
    );

    #[Route('/equip/{codi<\d+>?1}',name:'dades_equip')]
    public function dades($codi) {
        $resultat = array_filter($this->equips, 
        function($equip) use ($codi) {
            return $equip["codi"] == $codi;
        });

        if(count($resultat) > 0) {
            $resposta = "";
            $resultat = array_shift($resultat); #torna el primer element
            $resposta .= "<ul><li>" . $resultat["nom"] . "</li>" . "<li>" . $resultat["cicle"] . "</li>" . 
            "<li>" . $resultat["curs"] . "</li>" . "<li>" . $resultat["membres"] . "</li></ul>";

            return new Response("<html><body>$resposta</body></html>");
        } else return new Response("Contacte no trobat");
    }

    #[Route('/equip/{text}', name:'buscar_equip')]
    public function buscar($text) {
        $resultat = array_filter($this->equips,
        function($equip) use ($text) {
            return strpos($equip["nom"], $text) !== FALSE;
        });

        $resposta ="";
        if(count($resultat) > 0) {
            foreach ($resultat as $equip)
            $resposta .= "<ul><li>" . $equip["nom"] . "</li>" . 
            "<li>" . $equip["cicle"] . "</li>" . 
            "<li>" . $equip["curs"] . "</li>" . 
            "<li>" . $equip["membres"] . "</li></ul>";
            return new Response("<html><body>" . $resposta . "</body></hmtl>");
        } else return new Response("No s'ha trobat l'equip");
    }
    
}

?>