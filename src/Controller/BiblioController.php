<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Repository\EmpruntRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/biblio', name: 'app_biblio')]
class BiblioController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function index(): Response
    {
        return $this->render('biblio/index.html.twig', [
            'controller_name' => 'BiblioController',
        ]);
    }

    #[Route("/retour", name: "_retour")]
    public function retour(EmpruntRepository $er)
    {
        $emprunts = $er->findByDateRetourNull();
        return $this->render('biblio/retour.html.twig', [
            'emprunts' => $emprunts
        ]);
    }

    #[Route("/retour-emprunt-{id}", name: "_retour_emprunt")]
    public function retourEmprunt(Emprunt $emprunt, EntityManagerInterface $em){
        $emprunt->setDateRetour(new \DateTime());
        $em->flush();
        $this->addFlash("info", "L'emprunt n°" . $emprunt->getId() . " a une date retour");
        return $this->redirectToRoute("app_biblio_retour");
    }


}
