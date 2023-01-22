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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MembreNouType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
        ->add('id', HiddenType::class)
        ->add('nom', TextType::class)
        ->add('cognoms', TextType::class)
        ->add('email', TextType::class, array('label' => 'Correu electrònic'))
        ->add('dataNaixement', DateType::class)
        ->add('imatgePerfil', FileType::class, ['mapped' => false])
        ->add('equip', EntityType::class, 
            array('class' => Equip::class,'choice_label' => 'nom',
        ))
        ->add('nota', NumberType::class)
        ->add('save', SubmitType::class,array('label' => 'Enviar'));
    }
}

?>