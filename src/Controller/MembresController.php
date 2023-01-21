<?php

namespace App\Controller;

use App\Entity\Equip;
use App\Entity\Membre;
use App\Service\ServeiDadesEquips;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
}