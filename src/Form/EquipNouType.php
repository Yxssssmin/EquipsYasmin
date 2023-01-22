<?php
namespace App\Form;

use App\Entity\Equip;
use App\Entity\Membre;
use App\Service\ServeiDadesEquips;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EquipNouType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
        ->add('id', HiddenType::class)
        ->add('nom', TextType::class)
        ->add('cicle', TextType::class)
        ->add('curs', TextType::class)
        ->add('imatge', FileType::class, ['mapped' => false])
        ->add('nota', NumberType::class)
        ->add('save', SubmitType::class,array('label' => 'Enviar'));
    }
}


?>