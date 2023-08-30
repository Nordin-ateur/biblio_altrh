<?php

namespace App\Form;

use App\Entity\Auteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AuteurFormType extends AbstractType
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
                        "min"           =>  4,
                        "minMessage"    =>  "Le prénom ne doit pas contenir mois de 4 caractères"
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
            ->add("sauvegarder", SubmitType::class)
            ->add("effacer", ResetType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Auteur::class,
        ]);
    }
}
