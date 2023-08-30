<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Auteur;
use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
use App\Repository\EmpruntRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    
    #[Route('/', name: 'app_home')]
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'livres' => $livreRepository->findAll(),
        ]);
    }

    #[Route("/fiche-auteur-{id}", name: "app_home_auteur", requirements: ["id" => "\d+"])]
    public function auteur(Auteur $auteur)
    {
        return $this->render("home/fiche_auteur.html.twig", ["auteur" => $auteur]);
    }

    #[Route("/fiche-livre-{id}", name: "app_home_livre", requirements: ["id" => "\d+"])]
    public function livre(Livre $livre, EmpruntRepository $er, LivreRepository $lr)
    {
        $livresDisponibles = $lr->livresDisponibles();
        // $disponible = in_array($livre, $livresDisponibles);
        // $livre->setDisponible( $disponible);
        return $this->render("home/fiche_livre.html.twig", [
            "livre" => $livre,
            "nb_emprunts" => $er->nbEmprunts($livre->getId()),
            // "disponible"  => $disponible
        ]);
    }
    
    #[Route("/genres", name: "app_home_genres")]
    public function genres(GenreRepository $gr)
    {
        return $this->render("home/genres.html.twig", [ "genres" => $gr->findAll() ]);
    }

    #[Route("/fiche-genre-{id}", name: "app_home_genre", requirements: ["id" => "\d+"])]
    public function genre(Genre $genre)
    {
        return $this->render("home/fiche_genre.html.twig", ["genre" => $genre]);
    }

    #[Route("/livres-indisponibles", name: "app_home_livres_indisponibles")]
    public function indisponibles(LivreRepository $lr)
    {
       return $this->render("home/indisponibles.html.twig", [ "livres" => $lr->livresIndisponibles() ]);
    }

    #[Route("/livres-disponibles", name: "app_home_livres_disponibles")]
    public function disponibles(LivreRepository $lr)
    {
       return $this->render("home/disponibles.html.twig", [ "livres" => $lr->livresDisponibles() ]);
    }
}
