<?php

namespace App\Controller\Admin;

use App\Entity\Abonne;
use App\Form\AbonneType;
use App\Repository\AbonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as Hasher;

#[Route('/admin/abonne')]
class AbonneController extends AbstractController
{
    #[Route('/', name: 'app_admin_abonne_index', methods: ['GET'])]
    public function index(AbonneRepository $abonneRepository): Response
    {
        return $this->render('admin/abonne/index.html.twig', [
            'abonnes' => $abonneRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_abonne_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Hasher $hasher): Response
    {
        $abonne = new Abonne();
        $form = $this->createForm(AbonneType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if( $form->isValid() ) {
                /* on récupère le mot de passe tapé dans le formulaire */
                $mdp = $form->get("password")->getData();

                /* on 'hashe' le mot de passe */
                $mdpHashe = $hasher->hashPassword( $abonne, $mdp );

                /* on affecte la propriété 'password' de l'objet Abonne avec le mdp hashé */
                $abonne->setPassword($mdpHashe);

                /* l'abonné est inséré en bdd */
                $entityManager->persist($abonne);
                $entityManager->flush();
                $this->addFlash("success", "Le nouvel abonné a été enregistré");
                return $this->redirectToRoute('app_admin_abonne_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash("danger", "Le formulaire n'est pas valide");
            }
        }

        return $this->render('admin/abonne/new.html.twig', [
            'entite' => $abonne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_abonne_show', methods: ['GET'])]
    public function show(Abonne $abonne): Response
    {
        return $this->render('admin/abonne/show.html.twig', [
            'abonne' => $abonne,
            'entite' => $abonne
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_abonne_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Abonne $abonne, EntityManagerInterface $entityManager, Hasher $hasher): Response
    {
        $form = $this->createForm(AbonneType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* on récupère le mot de passe tapé dans le formulaire */
            $mdp = $form->get("password")->getData();

            /* on vérifie que le mot de passe n'est pas vide */
            if( $mdp ) {

                /* on 'hashe' le mot de passe */
                $mdpHashe = $hasher->hashPassword( $abonne, $mdp );

                /* on affecte la propriété 'password' de l'objet Abonne avec le mdp hashé */
                $abonne->setPassword($mdpHashe);
            }

            /* l'abonné est inséré en bdd */
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_abonne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/abonne/edit.html.twig', [
            'entite' => $abonne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_abonne_delete', methods: ['POST'])]
    public function delete(Request $request, Abonne $abonne, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonne->getId(), $request->request->get('_token'))) {
            $entityManager->remove($abonne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_abonne_index', [], Response::HTTP_SEE_OTHER);
    }
}
