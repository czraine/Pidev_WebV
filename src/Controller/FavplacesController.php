<?php

namespace App\Controller;

use App\Entity\Favplaces;
use App\Entity\Placetovisit;
use App\Entity\User;
use App\Form\FavplacesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Repository\FavplacesRepository;
use Symfony\Component\HttpFoundation\JsonResponse;



#[Route('admin/favplaces')]
class FavplacesController extends AbstractController
{
    #[Route('/', name: 'app_favplaces_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $favplaces = $entityManager
            ->getRepository(Favplaces::class)
            ->findAll();

        return $this->render('favplaces/index.html.twig', [
            'favplaces' => $favplaces,
        ]);
    }
    // /**
    //  * @Route("/favorites/toggle/{placeId}", name="favorite_toggle", methods={"POST"})
    //  */
    // public function toggle(int $placeId, EntityManagerInterface $entityManager): JsonResponse
    // {
    //     $user = $this->getUser();
    //     $place = $entityManager->getRepository(Placetovisit::class)->find($placeId);

    //     // check if user has already favorited the place
    //     $favorite = $entityManager->getRepository(Favplaces::class)->findOneBy(['idPlace' => $place, 'idUser' => $user]);

    //     if ($favorite) {
    //         // remove favorite
    //         $entityManager->remove($favorite);
    //         $entityManager->flush();
    //         $isFavorite = false;
    //         return new JsonResponse(['status' => 'removed']);
    //     } else {
    //         // add favorite
    //         $favorite = new Favplaces();
    //         $favorite->setIdPlace($place);
    //         $favorite->setIdUser($user);

    //         $entityManager->persist($favorite);
    //         $entityManager->flush();
    //         $isFavorite = true;

    //         return new JsonResponse(['status' => 'added']);
            
    //     }
    //     return new Response(json_encode(['is_favorite' => $isFavorite]));
    // }

    #[Route('/new', name: 'app_favplaces_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); // Get the logged-in user
        $favplace = new Favplaces();
        $favplace->setIdUser($user);
        // $favplace->setIdUser($user->getIdUser());
        dump($favplace);

        $form = $this->createForm(FavplacesType::class, $favplace);
        $form->handleRequest($request);
        dump($favplace);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($favplace);
            $entityManager->flush();

            return $this->redirectToRoute('app_favplaces_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('favplaces/new.html.twig', [
            'favplace' => $favplace,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_favplaces_show', methods: ['GET'])]
    public function show(Favplaces $favplace): Response
    {
        return $this->render('favplaces/show.html.twig', [
            'favplace' => $favplace,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_favplaces_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Favplaces $favplace, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FavplacesType::class, $favplace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_favplaces_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('favplaces/edit.html.twig', [
            'favplace' => $favplace,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_favplaces_delete', methods: ['POST'])]
    public function delete(Request $request, Favplaces $favplace, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$favplace->getId(), $request->request->get('_token'))) {
            $entityManager->remove($favplace);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_favplaces_index', [], Response::HTTP_SEE_OTHER);
    }
}
