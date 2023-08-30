<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\Abonne;
use App\Entity\Emprunt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateEmprunt', DateType::class, [
                "widget"    =>  "single_text",
                "constraints"   =>  [
                    new LessThanOrEqual([
                        "value" => "today",
                        "message" => "La date d'emprunt doit être antérieure ou égale à aujourd'hui"
                    ])
                ]
            ])
            ->add('dateRetour', DateType::class, [
                "widget"    =>  "single_text",
                "required"  =>  false
            ])
            ->add('abonne', EntityType::class, [
                "class"         =>  Abonne::class,
                "choice_label"  =>  "pseudo",
                "placeholder"   =>  ""
            ])
            ->add('livre', EntityType::class, [
                "class"         =>  Livre::class,
                "choice_label"  =>  function(Livre $l){
                    // return $l->getTitre() . " - " . $l->getAuteur()->getIdentite();
                    return $l->getTitreAuteur();
                },
                "placeholder"   =>  ""
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
