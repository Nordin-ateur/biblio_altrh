<?php

namespace App\Twig;

use Twig\TwigTest;
use Twig\TwigFilter;
use App\Entity\Abonne;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Extension extends AbstractExtension {
    private $parametres;

    public function __construct(ParameterBagInterface $parametres) {
        $this->parametres = $parametres;
    }

    public function extrait(?string $texte, int $longueur) {

        return strlen($texte) > $longueur ? substr($texte, 0, $longueur) . "[...]" : $texte;

    }

    public function estNumerique($variable): bool {
        return is_numeric($variable);
    }

    public function roles(Abonne $abonne): string {
        $text = "";
        foreach ($abonne->getRoles() as $role ) {
            $text .= $text ? ", " : "";
            switch ($role) {
                case 'ROLE_ADMIN':
                    $text .= "Directeur";
                    break;

                case 'ROLE_BIBLIO':
                    $text .= "Bibliothécaire";
                    break;
                
                case 'ROLE_LECTEUR':
                    $text .= "Lecteur";
                    break;
                
                case 'ROLE_USER':
                    $text .= "Abonné";
                    break;
                
                // case 'ROLE_DEV':
                //     $text .= "Développeur";
                //     break;
                
                default:
                    $text .= "Autre";
                    break;
            }
        }
        return $text;

    }

    public function baliseImg($imageName, $classes = "", $alt = "")
    {
        $balise = "<img src='" . $this->parametres->get("chemin_images") .  "$imageName' class='$classes' alt='$alt'>";
        return $balise;
    }

    ////////////////////////////////////////////
    /* Pour ajouter une fonction à Twig, on utilise la méthode getFunctions */
    /* Pour ajouter une filtre à Twig, on utilise la méthode getFilters */
    /* Pour ajouter une test à Twig, on utilise la méthode getTests */
    public function getFunctions()
    {
        return [
            new TwigFunction("extrait", [$this, "extrait"])
        ];
    }

    /* */
    public function getFilters()
    {
        return [
            new TwigFilter("extrait", [$this, "extrait"]),
            new TwigFilter("autorisations", [$this, "roles"]),
            new TwigFilter("img", [$this, "baliseImg"])
        ];
    }

    public function getTests() {
        return [
            new TwigTest("numeric", [$this, "estNumerique"])
        ];
    }
}