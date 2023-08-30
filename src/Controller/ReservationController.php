<?php

namespace App\Controller;

use App\Entity\Livre;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface as Session;

#[IsGranted("ROLE_LECTEUR")]
class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(Session $session): Response
    {
        $panier = $session->get("panier", []);
        return $this->render('reservation/index.html.twig', [
            "panier" => $panier,
        ]);
    }

    /* EXERCICE : 
        • modifier la réservation d'un livre pour qu'une nouvelle réservation s'ajoute aux réservations en cours
                    au lieu de l'écraser.
        • Il faut qu'un même livre ne puisse pas être réservé 2 fois (si on tente de réserver un livre déjà dans
                  la liste on affiche un message d'avertissement) */

    #[Route('/reservation/livre-{id}', name: 'app_reservation_livre')]
    public function livre(Livre $livre, Session $session): Response
    {
        $panier = $session->get("panier", []);
        $dejaReserve = false;
        foreach ($panier as $indice => $item) {
            if($item["livre"]->getId() == $livre->getId()) {
                $dejaReserve = true;
                break;
            }
        }
        
        if( $dejaReserve ) {
            $this->addFlash("warning", "Le livre " . $livre->getTitreAuteur() . " est déjà dans la liste de vos réservations");
        } else {
            $panier[] = [ "livre" => $livre, "date" => date("d/m/Y") ];
            $session->set("panier", $panier);  // $_SESSION["panier"] = $panier;
            $this->addFlash("info", "Le livre " . $livre->getTitreAuteur() . " a été ajouté dans la liste de vos réservations");
        }
        return $this->redirectToRoute("app_reservation");
    }


    
}
