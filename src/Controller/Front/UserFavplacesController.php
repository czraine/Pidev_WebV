<?php
namespace App\Controller\Front;
use App\Controller\UserController;
use App\Entity\Placetovisit;
use App\Entity\Favplaces;
use App\Form\FavplacesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FavplacesRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\UtilisateurRepository;

#[Route('/favplaces')]
class UserFavplacesController extends AbstractController
{
    #[Route('/', name: 'front_favplaces_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,
    SessionInterface $session,
    UserController $utilisateurController,
    UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateurController = new UserController();
        $userconnected = $utilisateurController->getDataUserConnected($session);
        $idUser=$utilisateurRepository->findByIDobject($userconnected->getIdUser());
        $favplaces = $entityManager
            ->getRepository(Favplaces::class)
            // ->findAll();
            ->findBy(['idUser' => $idUser]);

        return $this->render('Front/favplaces/index.html.twig', [
            'favplaces' => $favplaces,
        ]);
    }

    #[Route('/new', name: 'front_favplaces_new', methods: ['GET', 'POST'])]
    public function new(Request $request, 
    EntityManagerInterface $entityManager,
    SessionInterface $session,
    UserController $utilisateurController,
    UtilisateurRepository $utilisateurRepository): Response
    {

        $utilisateurController = new UserController();
        $userconnected = $utilisateurController->getDataUserConnected($session);
        $idUser=$utilisateurRepository->findByIDobject($userconnected->getIdUser());
var_dump($idUser);
        $favplace = new Favplaces();
        $favplace->setIdUser($idUser);
        $form = $this->createForm(FavplacesType::class, $favplace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($favplace);
            $entityManager->flush();

            return $this->redirectToRoute('front_favplaces_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Front/favplaces/new.html.twig', [
            'favplace' => $favplace,
            'form' => $form,
        ]);
    }
       /**
     * @Route("/favorites/toggle/{placeId}", name="favorite_toggle", methods={"POST"})
     */
    public function toggle(int $placeId, 
    EntityManagerInterface $entityManager, 
    SessionInterface $session,
    UserController $utilisateurController,
    UtilisateurRepository $utilisateurRepository): JsonResponse
    {
        $utilisateurController = new UserController();
        $userconnected = $utilisateurController->getDataUserConnected($session);
        $user=$utilisateurRepository->findByIDobject($userconnected->getIdUser());
        // var_dump($user);
        // $user = $this->getUser();
        $place = $entityManager->getRepository(Placetovisit::class)->find($placeId);

        // check if user has already favorited the place
        $favorite = $entityManager->getRepository(Favplaces::class)->findOneBy(['idPlace' => $place, 'idUser' => $user]);

        if ($favorite) {
            // remove favorite
            $entityManager->remove($favorite);
            $entityManager->flush();
            $isFavorite = false;
            return new JsonResponse(['status' => 'removed']);
        } else {
            // add favorite
            $favorite = new Favplaces();
            $favorite->setIdPlace($place);
            $favorite->setIdUser($user);

            $entityManager->persist($favorite);
            $entityManager->flush();
            $isFavorite = true;

            return new JsonResponse(['status' => 'added']);
            
        }
        return new Response(json_encode(['is_favorite' => $isFavorite]));
    }

    #[Route('/{id}/edit', name: 'front_favplaces_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Favplaces $favplace, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FavplacesType::class, $favplace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('front_favplaces_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Front/favplaces/edit.html.twig', [
            'favplace' => $favplace,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'front_favplaces_delete', methods: ['POST'])]
    public function delete(Request $request, Favplaces $favplace, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$favplace->getId(), $request->request->get('_token'))) {
            $entityManager->remove($favplace);
            $entityManager->flush();
        }

        return $this->redirectToRoute('front_favplaces_index', [], Response::HTTP_SEE_OTHER);
    }
}
