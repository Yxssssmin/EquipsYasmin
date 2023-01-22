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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class MembresController extends AbstractController {

    private $equips;
    public function __construct(ServeiDadesEquips $dades) {

        $this->equips = $dades->get();
    }


    #[Route('/membre/inserir', name:'inserir_membre')]
    public function inserirMembre(ManagerRegistry $doctrine) {

        $repositori = $doctrine->getRepository(Equip::class);
        $equip = $repositori->find(1);

        $entityManager = $doctrine->getManager();
        $success = true;
        $error = null;

        $membre = new Membre();
        $membre->setNom("Sarah");
        $membre->setCognoms("Connor");
        $membre->setEmail("sarahconnor@skynet.com");
        $membre->setImatgePerfil("sarahconnor.jpg");
        $membre->setDataNaixement(new \DateTime("1963-11-29"));
        $membre->setNota(9.7);
        $membre->setEquip($equip);

        $entityManager->persist($membre);
        try {
            $entityManager->flush();
        } catch (Exception $e) {
            $error = $e->getMessage();
            $success = false;
        }

        return $this->render('inserir_membre.html.twig', array(
            'success' => $success,
            'error' => $error,
            'membre' => $membre
        ));
    }

    #[Route('/membre/nou', name:'nou_membre')]
    public function nouMembre(Request $request, ManagerRegistry $doctrine) {

        $membre = new Membre();
        $formulari = $this->createFormBuilder($membre)
        ->add('nom', TextType::class)
        ->add('cognoms', TextType::class)
        ->add('email', TextType::class, array('label' => 'Correu electrònic'))
        ->add('dataNaixement', DateType::class)
        ->add('imatgePerfil', FileType::class)
        ->add('equip', EntityType::class, 
            array('class' => Equip::class,'choice_label' => 'nom',
        ))
        ->add('nota', NumberType::class)
        ->add('save', SubmitType::class,array('label' => 'Enviar'))
        ->getForm();
        $formulari->handleRequest($request);

        if($formulari->isSubmitted() && $formulari->isValid()) {
            $fitxer = $formulari->get('imatgePerfil')->getData();
            if($fitxer) {
                $nomFitxer = $fitxer->getClientOriginalName();
                $directori = $this->getParameter('kernel.project_dir')."/public/img/membres";
                try {
                    $fitxer->move($directori,$nomFitxer);
                } catch (FileException $e) {
                    $error=$e->getMessage();
                }

                $membre->setImatgePerfil($nomFitxer);

            } else {
                $membre->setImatgePerfil('membrePerDefecte.png');
            }

            $membre->setNom($formulari->get('nom')->getData());
            $membre->setCognoms($formulari->get('cognoms')->getData());
            $membre->setEmail($formulari->get('email')->getData());
            $membre->setDataNaixement($formulari->get('dataNaixement')->getData());
            $membre->setImatgePerfil($formulari->get('imatgePerfil')->getData());
            $membre->setEquip($formulari->get('equip')->getData());
            $membre->setNota($formulari->get('nota')->getData());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($membre);
            $entityManager->flush(); 
            
            return $this->redirectToRoute('inici');
        
        } 
            
        return $this->render('nou_membre.html.twig', array(
            'formulari' => $formulari->createView()
        ));   


    }
}