<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\SearchProduitType;
use App\Manager\ProduitManager;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\Exception\NotFoundException;


#[Route('/home/produit')]
class ProduitTouristeController extends AbstractController
{
    #[Route('/', name: 'app_produit_index_touriste', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository,Request $request): Response
    {
        $produits = $produitRepository->findAll();
        $form = $this->createForm(SearchProduitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchquery = $form->getData()['nameProd'];
            $produits = $produitRepository->search($searchquery);
        }
        return $this->render('produit_touriste/index.html.twig', [
            'produits' => $produits,
            'form' => $form->createView()
        ]);
    }

    #[Route('/new', name: 'app_produit_new_touriste', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository, SluggerInterface $slugger): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageProd = $form->get('imageProd')->getData();
            if ($imageProd !== null) { // check if it's a file object
                $originalFilename = pathinfo($imageProd->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageProd->guessExtension();
                try {
                    $imageProd->move(
                        $this->getParameter('FimageProd_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $produit->setImageProd($newFilename);
            }

            $produitRepository->save($produit, true);
            //return $this->redirectToRoute('app_produit_index');
        }

        return $this->renderForm('produit_touriste/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{idProduit}', name: 'app_produit_show_touriste', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit_touriste/show.html.twig', [
            'produit' => $produit,
        ]);
    }
    #[Route('/search/create-search', name: 'search_ajax', methods: ['GET'])]
    public function search(Request $request,ProduitRepository $produitRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $produits = $em->getRepository('App\Entity\Produit')->findEntitiesByString($requestString);
        if (!$produits) {
            $result['produits']['error'] = "NOT FOUND";
        } else {
            $result['produits'] = $this->getRealEntities($produits);
        }

        return new Response(json_encode($result));
    }
    public function getRealEntities($produits)
    {

        foreach ($produits as $produit) {
            $realEntities[$produit->getIdProduit()] = $produit->getNameProd() ;
        }
        return $realEntities;
    }



    #[Route('/{idProduit}/edit/produit', name: 'app_produit_edit_touriste', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit_touriste/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/search/produit', name: 'app_reservationcircuit_search', methods: ['GET'])]
    public function searchProduct(Request $request, EntityManagerInterface $entityManager)
    {
        $search = $request->query->get('search');
        $produits = $entityManager
            ->getRepository(Produit::class)
            ->findAll();
        if ($search) {
            $productRepository = $entityManager->getRepository(Produit::class);

            $searchResults = $productRepository->createQueryBuilder('p')
                ->where('p.idProduit = :query OR p.nameProd LIKE :query OR p.prodDescription LIKE :query OR p.typeProd LIKE :query OR p.priceProd = :query OR p.quantite = :query OR p.imageProd LIKE :query OR p.status LIKE :query')
                ->setParameter('query', '%'.$search.'%')
                ->getQuery()
                ->getResult();
        }
        else{
            $searchResults = $entityManager
                ->getRepository(Produit::class)
                ->findAll();
        }

        return  $this->json($searchResults);

    }


}

