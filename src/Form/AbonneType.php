<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /**
            $options["data"] permet de récupérer l'objet qui est utilisé comme données
            du formulaire.
         */
        $abonne = $options["data"];
        $builder
            ->add('pseudo')
            ->add('password', null, [
                "mapped"    =>  false,
                // "required"  =>  $abonne->getId() ? false : true,
                "required"  =>  ! $abonne->getId(),
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                "choices"   =>  [
                    "Abonné"        =>  "ROLE_USER",
                    "Lecteur"       =>  "ROLE_LECTEUR",
                    "Bibliothécaire"=>  "ROLE_BIBLIO",
                    "Administrateur"=>  "ROLE_ADMIN",
                    "Développeur"   =>  "ROLE_DEV"
                ],
                "multiple"  => true,
                "expanded"  => true,
                "label"     => "Niveau d'accès"
            ])
            ->add('prenom')
            ->add('nom')
            ->add('naissance', DateType::class, [
                "widget"    =>  "single_text",
                "required"  =>  false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}
