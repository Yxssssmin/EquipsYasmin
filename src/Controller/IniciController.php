<?php 
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;

class IniciController extends AbstractController {
    
    private $logger;
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    #[Route('/' ,name:'inici')]
    public function inici() {
        $data_hora = new \DateTime();
        $this->logger->info("Accés el " . $data_hora->format("d/m/y H:i:s"));
        return $this->render('inici.html.twig');
    }

}

?>