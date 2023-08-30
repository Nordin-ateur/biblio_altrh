<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RechercheController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche')]
    public function index(Request $rq, LivreRepository $lr): Response
    {
        $mot = $rq->query->get("recherche");
        $livres = $lr->findByRecherche($mot);
        return $this->render('recherche/index.html.twig', [
            'mot'       => $mot,
            'livres'    => $livres
        ]);
    }
}
