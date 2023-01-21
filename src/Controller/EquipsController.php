<?php
namespace App\Controller;

use App\Entity\Equip;
use App\Entity\Membre;
use App\Service\ServeiDadesEquips;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class EquipsController extends AbstractController {

    private $equips;
    public function __construct(ServeiDadesEquips $dades) {
        $this->equips = $dades->get();
    }

    /*#[Route('/equip/{text}', name:'buscar_equip')]
    public function buscar($text) {
        $resultat = array_filter($this->equips,
        function($equip) use ($text) {
            return strpos($equip["nom"], $text) !== FALSE;
        });

        return $this->render('llista_equips.html.twig', array('equips' => $resultat));
    }*/

    #[Route('/equip/inserir', name:'inserir_equip')]
    public function inserir(ManagerRegistry $doctrine) {

        $entityManager = $doctrine->getManager();
        $equip = new Equip();
        $equip->setNom("Equip Groc");
        $equip->setCicle("DAW");
        $equip->setCurs("22/23");
        $equip->setNota(8.25);
        $equip->setImatge("equipPerDefecte.png");
        
        $entityManager->persist($equip);
        $success = true;

        try {
            $entityManager->flush();
            return $this->render('inserir_equip.html.twig', [
                'equip' => $equip,
                'success' => true
            ]);
        } catch (\Exception $e) {
            return $this->render('inserir_equip.html.twig' , [
                'error' => $e->getMessage(),
                'success' => false
            ]);
        }
        

    }

    #[Route('/equip/inserirmultiple', name:'inserirmultiple_equip')]
    public function inserirmultiple(ManagerRegistry $doctrine) {
        $entityManager = $doctrine->getManager();
        $success = true;
        $error = null;
        $equipsCreats = array();

        foreach($this->equips as $equipDades) {
            $equip = new Equip();
            $equip->setNom($equipDades['nom']);
            $equip->setCicle($equipDades['cicle']);
            $equip->setCurs($equipDades['curs']);


            $entityManager-> persist($equip);
            try {
                $entityManager->flush();
                $equipsCreats[] = $equip;
            } catch(\Exception $e) {
                $error=$e->getMessage();
                $success=false;
                break;
            }
        }

        return $this->render('inserir_equip_multiple.html.twig', array(
            'success' => $success,
            'error' => $error,
            'equips' => $equipsCreats
        ));

    }

    #[Route('/equip/{codi}', name:'dades_equip', requirements:['codi' => '\d+'])]
    public function fitxa($codi, ManagerRegistry $doctrine) {
        $repository = $doctrine->getRepository(Equip::class);
        $equip = $repository->find($codi);

        if($equip) {
//            $llistaMembres = "";
//
//            foreach ($resultat["membres"] as $membre) {
//                $llistaMembres .= $membre . " ";
//            }

            return $this->render('dades_equip.html.twig', array (
                'equip' => $equip
            ));

        } else {
            return $this->render('dades_equip.html.twig', array(
                'equip' => NULL
            ));
        }
    }

    #[Route('/equip/nota/{nota}', name:'filtrar_notes', requirements:['nota' => '^1[0]$|[0-9](\.[0-9]{1,2})?$'])]
    public function filtrarNotes($nota, ManagerRegistry $doctrine) {

        $qb = $doctrine->getRepository(Equip::class)->createQueryBuilder('e');
        $qb->andWhere('e.nota >= :nota')
            ->setParameter('nota', $nota);

        $equips = $qb->getQuery()->getResult();

        $updatedEquips = [];
        foreach ($equips as $equip) {
            $updatedEquipName = $equip->getNom() . ' (Nota: ' . $equip->getNota() . ')';
            $updatedEquips[] = [
                'id' => $equip->getId(),
                'nom' => $updatedEquipName,
            ];
        }

        return $this->render('inici.html.twig', [
            'equips' => $updatedEquips
        ]);
    }
        
}
    


?>