<?php

namespace App\Form;

use App\Entity\Auteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AuteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                "label"         =>  "Prénom",
                "required"      =>  false,
                "constraints"   =>  [
                    new Length([
                        "max"           =>  20,
                        "maxMessage"    =>  "Le prénom ne doit pas contenir plus de 20 caractères",
                        "min"           =>  2,
                        "minMessage"    =>  "Le prénom ne doit pas contenir mois de 2 caractères"
                    ])
                ]
            ])
            ->add('nom', null, [
                "constraints"   =>  [
                    new Length([
                        "max"           =>  30,
                        "maxMessage"    =>  "Le prénom ne doit pas contenir plus de 30 caractères",
                    ]),
                    new NotBlank([ "message" => "Le nom ne peut pas être vide !" ])
                ], 
                "required"  =>  false,
                "label"     =>  "Nom*"
            ])
            ->add('bio')
            ->add('naissance', DateType::class, [
                "widget"    =>  "single_text",
                "required"      =>  false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Auteur::class,
        ]);
    }
}
