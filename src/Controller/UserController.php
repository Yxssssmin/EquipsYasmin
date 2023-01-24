<?php
namespace App\Controller;

use App\Entity\Usuari;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;


class UserController extends AbstractController {

    #[Route('/usuari', name: 'usuari')]
    public function usuari() {

        $usuari = new Usuari();

        return $this->render('usuario.html.twig', array (
            'usuari' => $usuari
        ));
    }

    #[Route('/usuari/nou', name: 'nou_usuari')]
    public function nou(Request $request, ManagerRegistry $doctrine) {
        $usuari = new Usuari();
        $formulari = $this->createFormBuilder($usuari)
        ->add('username', TextType::class)
        ->add('password', PasswordType::class)
        ->add('roles', HiddenType::class,  [
            'mapped' => false,
            'empty_data' => "ROLE_USER"
        ])
        ->add('save', SubmitType::class, array('label' => 'Enviar'))
        ->getForm();
        $formulari->handleRequest($request);
        if($formulari->isSubmitted() && $formulari->isValid()) {
            $usuari = $formulari->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($usuari);
            $entityManager->flush();

            return $this->redirectToRoute('usuari');
        }

        return $this->render('nou_usuari.html.twig', array(
            'formulari' => $formulari->createView()
        ));
    }

    #[Route('/usuari/editar/{id}', name: 'editar_usuari', requirements:['id' => '\d+'])]
    public function editar(Request $request, $id, ManagerRegistry $doctrine) {
        $repositori = $doctrine->getRepository(Usuari::class);
        $usuari = $repositori->find($id);
        $formulari = $this->createFormBuilder($usuari)
        ->add('username', TextType::class)
        ->add('password', PasswordType::class, [
            'required'   => false,
            'mapped' => false
        ])
        ->add('roles', HiddenType::class,  [
            'mapped' => false,
            'empty_data' => "ROLE_USER"
        ])
        ->add('save', SubmitType::class, array('label' => 'Enviar'))
        ->getForm();
        $formulari->handleRequest($request);
        if($formulari->isSubmitted() && $formulari->isValid()) {
            $usuari = $formulari->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($usuari);
            $entityManager->flush();

            return $this->redirectToRoute('usuari');
        }

        return $this->render('nou_usuari.html.twig', array(
            'formulari' => $formulari->createView()
        ));
 
    }
    
    #[Route('/usuari/eliminar/{id}', name: 'eliminar_usuari')]
    public function eliminar($id, ManagerRegistry $doctrine) {

        $entityManager = $doctrine->getManager();
        $repositori = $doctrine->getRepository(Usuari::class);
        
        $usuari = $repositori->find($id);
        $entityManager->remove($usuari);
        $entityManager->flush();


        return $this->redirectToRoute('usuari');
    }

}

?>