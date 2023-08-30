<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// use stdClass;
// #[IsGranted("ROLE_LECTEUR")]
class TestController extends AbstractController
{
    /**
     * une annotation est utilisée pour la documentation automatique.
     Quand on définit le chemin d'une route, il faut toujours commencer par /
     */
    #[Route('/test', name: 'app_test')]
    # [IsGranted("ROLE_ADMIN")]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/test/nouvelle-route', name: 'app_test_nouvelle_route')]
    public function nouvelleRoute(): Response
    {
        return $this->render('test/index.html.twig', [
            "controller_name" => "ALTRH"
        ]);
    }

    /* EXERCICES
        1. Ajouter une nouvelle route pour le chemin '/test/bonjour' dont le titre (h1) sera "Bonjour tout le monde"
            (l'affichage se fera à partir du fichier test/bonjour.html.twig)
       2. Ajouter un footer qui doit apparâitre sur toutes la pages et qui affichera "ceci est le footer"
       3. Dans la nouvelle route, le contenu du footer doit être "le footer du fichier bonjour.html.twig"
    */

    #[Route("/test/bonjour", name: "app_test_bonjour")]
    public function bonjour()
    {
        return $this->render("test/bonjour.html.twig");
    }

    #[Route("/admin/test/addition", name: "app_test_addition")]
    public function addition()
    {
        return $this->render("test/addition.html.twig", [
            "nb1"   => 140,
            "nb2"   => 15
        ]);
    }

    /* Route paramétrée 
        Dans le chemin d'une route, la partie entre {} est dynamique, la
        valeur peut changer changer à chaque fois.
        Pour récupérer la valeur passée dans l'url, il faut définir
        un argument qui a le même nom dans la méthode du contrôleur.
        REGEX : la chaîne de caractères doit correspondre au modèle défini dans la regex (expression réguilère)
         [0-9] : le caractère doit être compris entre le 0 et le 9
         \d    : le caractère doit être un chiffre (d pour digit, chiffre en anglais)
           +   : le caractère précédent le + doit être présent au moins 1 fois
           *   : le caractère précédent le * peut être présent 0 fois ou autant que l'on veut
           ?   : le caractère précédent le ? ne peut être présent qu'1 fois
    */
    #[Route("/test/calcul/{nombre1}/{nombre2}", name: "app_test_calcul", requirements: ["nombre1" => "[0-9]+", "nombre2" => "\d+"])]
    public function calcul($nombre1, $nombre2)
    {
        // dump($nombre1, $nombre2); die;
        // dd($nombre1, $nombre2);
        return $this->render("test/addition.html.twig", [
            "nb1" => $nombre1,
            "nb2" => $nombre2,
        ]);
    }


    #[Route("/test/boucles", name: "app_test_boucles")]
    public function boucles()
    {
        $tableau = [ 75, "test", false, "fin", "bonjour" ];
        return $this->render("test/boucles.html.twig", [
            "tab" => $tableau,
            "longueur" => count($tableau)
        ]);
    }

    /* EXO  https://sharemycode.fr/exo
    Ajoutez une nouvelle route (chemin: /test/boucle/...)
    les ... seront remplacés par un entier qui peut varier.
    Créez une nouvelle vue (nouveau template) dans le dossier 'test' qui s'appellera 'loop.html.twig'
    Cette route devra afficher tous les nombres de 1 jusqu'au nombre récupéré dans le chemin.

    //////
    Pour la réponse, créez un sharemycode avec votre prénom (si possible) et mettez le lien en message direct
    quand vous avez fini

    */
    #[Route("/test/boucle/{entier}", name: "app_test_boucle")]
    public function boucle($entier)
    {
        // if($entier > 1000 ) {
        //     $entier = 1000;
        // }
        return $this->render("test/loop.html.twig", [ "nombre" => $entier ]);
    }

    #[Route("/test/tableau", name: "app_test_tableau")]
    public function tableau()
    {
        $personne = [
            "nom"       =>  "Cérien",
            "prenom"    =>  "Jean",
            "age"       =>  32
        ];
        return $this->render("test/associatif.html.twig", [ "personne" => $personne ]);
    }

    #[Route("/test/objet", name: "app_test_objet")]
    public function objet()
    {
        $personne = new \stdClass;
        $personne->nom = "Onyme";
        $personne->prenom = "Anne";
        $personne->age = 54;
        return $this->render("test/associatif.html.twig", [ "personne" => $personne ]);
    }

    #[Route("/test/formulaire-get", name: "app_test_get")]
    public function get(Request $rq)
    {
        $nombre1 = $rq->query->get("nombre1"); 
        $nombre2 = $rq->query->get("nombre2"); 
        if( $nombre1 && $nombre2 ) {
            return $this->render("test/operations.html.twig", [
                "nombre1" => $nombre1,
                "nombre2" => $nombre2
            ]);
        }
        return $this->render("test/get.html.twig");
    }

    #[Route("/test/formulaire-post", name: "app_test_post")]
    public function post(Request $rq)
    {
        if( $rq->isMethod("POST") ) {
            $nombre1 = $rq->request->get("nombre1"); 
            $nombre2 = $rq->request->get("nombre2");
            return $this->render("test/operations.html.twig", [
                "nombre1" => $nombre1,
                "nombre2" => $nombre2
            ]);
        }
        return $this->render("test/post.html.twig");
    }

    /**
     * Route pour afficher le formlaire
     */
    #[Route("/test/formulaire", name: "app_test_formulaire")]
    public function formulaire()
    {
        return $this->render("test/formulaire.html.twig");
    }

    /**
     * Route pour gérer les données du formulaire en méthode POST
     */
    #[Route('/test/gestion/formulaire', name: 'app_test_gestion_formulaire', methods: ["POST"])]
    public function gestionFormulaire(Request $rq)
    {
        $nombre1 = $rq->request->get("nombre1"); 
        $nombre2 = $rq->request->get("nombre2");
        return $this->render("test/operations.html.twig", [
            "nombre1" => $nombre1,
            "nombre2" => $nombre2
        ]);
    }
}


/**
Toutes les méthodes d'un contrôleur Symfony 
DOIVENT retourner un objet de la classe Response
 */