<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EquipsController extends AbstractController {

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

        if(count($resultat) > 0)
        return $this->render('dades_equip.html.twig',array('equip' => array_shift($resultat)));
        else 
        return $this->render('dades_equip.html.twig',array('equip' => NULL));
        
    }

    #[Route('/equip/{text}', name:'buscar_equip')]
    public function buscar($text) {
        $resultat = array_filter($this->equips,
        function($equip) use ($text) {
            return strpos($equip["nom"], $text) !== FALSE;
        });

        return $this->render('llista_equips.html.twig', array('equips' => $resultat));
    }
    
}

?>