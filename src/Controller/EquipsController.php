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

    #[Route('/equip/nou', name:'nou_equip')]
    public function nou(Request $request, ManagerRegistry $doctrine) {

        $success = true;
        $error = null;

        $equip = new Equip();
        $formulari = $this->createFormBuilder($equip)
        ->add('nom', TextType::class)
        ->add('cicle', TextType::class)
        ->add('curs', TextType::class)
        ->add('imatge', FileType::class,array('required' => false))
        ->add('nota', NumberType::class)
        ->add('save', SubmitType::class,array('label' => 'Enviar'))
        ->getForm();
        $formulari->handleRequest($request);

        if($formulari->isSubmitted() && $formulari->isValid()) {
            $fitxer = $formulari->get('imatge')->getData();
            if($fitxer) {
                $nomFitxer = $fitxer->getClientOriginalName();
                $directori = $this->getParameter('kernel.project_dir')."/public/img/";
                try {
                    $fitxer->move($directori,$nomFitxer);
                } catch (FileException $e) {
                    $error=$e->getMessage();
                }

                $equip->setImatge($nomFitxer);

            } else {
                $equip->setImatge('equipPerDefecte.png');
            }

            $equip->setNom($formulari->get('nom')->getData());
            $equip->setCicle($formulari->get('cicle')->getData());
            $equip->setCurs($formulari->get('curs')->getData());
            $equip->setNota($formulari->get('nota')->getData());
            $equip->setImatge($formulari->get('imatge')->getData());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($equip);
            $entityManager->flush(); 
            
            return $this->redirectToRoute('inici');
        }

        return $this->render('nou_equip.html.twig', array(
            'formulari' => $formulari->createView()
        ));
    }

        
}
    


?>