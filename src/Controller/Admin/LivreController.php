<?php

namespace App\Controller\Admin;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/admin/livre')]
class LivreController extends AbstractController
{
    #[Route('/', name: 'app_admin_livre_index', methods: ['GET'])]
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('admin/livre/index.html.twig', [
            'livres' => $livreRepository->findAll(["titre" => "ASC"]),
        ]);
    }

    #[Route('/new', name: 'app_admin_livre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if( $fichier = $form->get("couverture")->getData() ) {  // si un fichier a été téléversé...

                // on récupère le nom du fichier
                $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);

                // pour remplacer les caractères interdits dans les URL, on utilise la classe AsciiSlugger 
                $slugger = new AsciiSlugger();
                $nouveauNomFichier = $slugger->slug( $nomFichier );

                // on ajouter un string unique pour éviter d'avoir des doublons
                $nouveauNomFichier .= "_" . uniqid();

                // on ajoute l'extension du fichier téléversé
                $nouveauNomFichier .= "." . $fichier->guessExtension();

                // on copie le fichier dans un dossier accessible aux navigateurs clients
                $fichier->move( $this->getParameter("dossier_images"), $nouveauNomFichier);

                // on modifie la propriété 'couverture' de l'objet Livre
                $livre->setCouverture( $nouveauNomFichier );
            }

            $entityManager->persist($livre);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/livre/new.html.twig', [
            'entite' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_livre_show', methods: ['GET'], requirements:['id' => '\d+'])]
    public function show(Livre $livre): Response
    {
        return $this->render('admin/livre/show.html.twig', [
            'livre' => $livre,
            'entite' => $livre,
        ]);
    }

    #[Route('/{titre}', name: 'app_admin_livre_fiche', methods: ['GET'])]
    public function fiche(Livre $livre): Response
    {
        return $this->render('admin/livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }



    #[Route('/{id}/edit', name: 'app_admin_livre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get("couverture")->getData();
            if( $fichier ) {  // si un fichier a été téléversé...

                if( $livre->getCouverture() ) {
                    $ancienFichier = $this->getParameter("dossier_images") . "/" .  $livre->getCouverture();
                    if( file_exists( $ancienFichier ) ) {
                        /**
                        Si vous essayez de supprimer un fichier qui n'existe pas, la fonction unlink renvoie une erreur
                         */
                        unlink($ancienFichier);
                    }
                }

                // on récupère le nom du fichier
                $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);

                // pour remplacer les caractères interdits dans les URL, on utilise la classe AsciiSlugger 
                $slugger = new AsciiSlugger();
                $nouveauNomFichier = $slugger->slug( $nomFichier );

                // on ajouter un string unique pour éviter d'avoir des doublons
                $nouveauNomFichier .= "_" . uniqid();

                // on ajoute l'extension du fichier téléversé
                $nouveauNomFichier .= "." . $fichier->guessExtension();

                // on copie le fichier dans un dossier accessible aux navigateurs clients
                $fichier->move( $this->getParameter("dossier_images"), $nouveauNomFichier);

                // on modifie la propriété 'couverture' de l'objet Livre
                $livre->setCouverture( $nouveauNomFichier );
            }

            $entityManager->flush();
            $this->addFlash("success", "Le livre n°" . $livre->getId() . " a bien été modifié");

            return $this->redirectToRoute('app_admin_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/livre/edit.html.twig', [
            'entite' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_livre_delete', methods: ['POST'])]
    public function delete(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_livre_index', [], Response::HTTP_SEE_OTHER);
    }
}
