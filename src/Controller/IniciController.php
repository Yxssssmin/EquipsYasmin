<?php 
namespace App\Controller;

use App\Entity\Equip;
use App\Service\ServeiDadesEquips;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class IniciController extends AbstractController {
    
    private $logger;
    private $dades;

    public function __construct(LoggerInterface $logger, $dadesEquips, ManagerRegistry $doctrine) {
        $this->logger = $logger;
        $this->dades = $doctrine->getRepository(Equip::class);
    }

    #[Route('/' ,name:'inici')]
    public function inici() {
        $data_hora = new \DateTime();
        $this->logger->info("Accés el " . $data_hora->format("d/m/y H:i:s"));
        
        return $this->render('inici.html.twig', array(
            'equips' => $this->dades->findAll()
        ));
    }

}

?>