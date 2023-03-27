<?php

namespace App\Controller;

use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CommentaireRepository $repository): Response
    {
        return $this->render('home/index.html.twig', [
            'commentaires' => $repository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    {
    #[Route('/commentaire/new', name: 'app_commentaire_new')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $manager, ReviewRepository $repository)
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Configurer la date de création et le créateur du commentaire (l'utilisateur connecté)
            $review->setCreatedAt(new \DateTimeImmutable());
            $review->setUser($this->getUser());

            // Méthode 1 pour ajouter en BDD
            $manager->persist($review);
            $manager->flush();

            // Méthode 2
            // $repository->save($review, true);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('review/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/review/delete/{id}', name: 'app_review_delete')]
    #[Security("is_granted('ROLE_USER') and user === review.getUser()")]
    public function delete(Review $review, EntityManagerInterface $manager)
    {
        $manager->remove($review);
        $manager->flush();

        return $this->redirectToRoute('app_home');
    }
}


?>