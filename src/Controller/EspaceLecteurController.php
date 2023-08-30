<?php

namespace App\Controller;

use App\Entity\Livre, App\Entity\Emprunt;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/espace-lecteur", name: "app_espace_lecteur")]
#[IsGranted("ROLE_LECTEUR")]
class EspaceLecteurController extends AbstractController
{
    #[Route('/', name: '')]
    public function index(): Response
    {
        return $this->render('espace_lecteur/index.html.twig');
    }

    #[Route("/emprunter-livre-{id}", name: "_emprunter")]
    public function emprunter(Livre $livre, EntityManagerInterface $em): Response
    {
        /**
        Pour récupérer les informations de l'utilisateur connecté : 
            • Twig          : app.user
            • Controller    : $this->getUser()
         */
        /* EXERCICE : Lorsque le lecteur clique sur le bouton pour emprunter un livre, insérer un nouvel enregistrement 
                dans la table Emprunt 


            Le livre emprunté est le livre sur lequel le lecteur a cliqué.
            L'abonné qui emprunte est celui qui est connecté.
            La date d'emprunt est le jour actuel. 

            Un message de confirmation de l'emprunt doit s'afficher    
        */
        if( $livre->getDisponible() ){
            $emprunt = new Emprunt;
            $emprunt->setLivre($livre);
            $emprunt->setAbonne($this->getUser());
            $emprunt->setDateEmprunt(new \DateTime("now"));

            $em->persist($emprunt);
            $em->flush();
            
            $this->addFlash("success", "Confirmation de l'emprut du livre <em>" . $livre->getTitreAuteur() . "</em>.");
            return $this->redirectToRoute("app_espace_lecteur");
        }
        $this->addFlash("warning", "Vous ne pouvez pas emprunter ce livre");
        return $this->redirectToRoute("app_home");
    }
}
