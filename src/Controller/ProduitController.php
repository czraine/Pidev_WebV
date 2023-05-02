<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\SearchProduitType;
use App\Repository\ProduitRepository;
use App\Repository\ShoppingCartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Twilio\Rest\Client;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

use symfony\component\Serializer\Normalizer\NormalizableInterface;
use symfony\Component\Serializer\Annotation\Groups;

#[Route('/produit')]
class ProduitController extends AbstractController
{

    #[Route("/ALLProducts", name: "list")]
    public function getProduits(ProduitRepository $produitRepository, SerializerInterface $serializer)
    {
        $produits = $produitRepository->findAll();        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets
        //* students en  tableau associatif simple.
        // $studentsNormalises = $normalizer->normalize($students, 'json', ['groups' => "students"]);

        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        // $json = json_encode($studentsNormalises);

        $json = $serializer->serialize($produits, 'json', ['groups' => "Produits"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }
    #[Route("/AllProduct/{idProduit}", name:"produit")]
    public function DemandsId($idProduit, NormalizerInterface $normalizer, ProduitRepository $produitRepository)
    {
        $produits = $produitRepository->find($idProduit);
        $produitsNormalises = $normalizer->normalize($produits, 'json', ['groups' => "Produits"]);
        return new Response(json_encode($produitsNormalises));
    }
    #[Route("/addDemandsJSON/new", name: "addDemandsJSON")]
    public function addSDemandJSON(Request $req,   NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $produit = new produit();
        $produit->setNameProd($req->get('nameProd'));
        $produit->setProdDescription($req->get('prodDescription'));
        $produit->setTypeProd($req->get('typeProd'));
        $produit->setPriceProd($req->get('priceProd'));
        $produit->setQuantite($req->get('quantite'));
        $produit->setImageProd($req->get('imageProd'));

        $produit->setStatus($req->get('status'));

        $em->persist($produit);
        $em->flush();
        //https://127.0.0.1:8000/produit/addDemandsJSON/new?nameProd=wael~&prodDescription=wael&typeProd=11111&quantite=202312&priceProd=202312&imageProd=ILLUSTRATION2-63fe33d4e13a4.jpg&status=avaible

        $jsonContent = $Normalizer->normalize($produit, 'json', ['groups' => "Produits"]);
        return new Response(json_encode($jsonContent));
    }
    #[Route("/updateProduitJSON/{idProduit}", name: "updateDemandJSON")]
    public function updateDemandJSON(Request $req, $idProduit, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $produit  = $em->getRepository(Produit::class)->find($idProduit);

        $produit->setNameProd($req->get('nameProd'));
        $produit->setProdDescription($req->get('prodDescription'));
        $produit->setTypeProd($req->get('typeProd'));
        $produit->setPriceProd($req->get('priceProd'));
        $produit->setQuantite($req->get('quantite'));
        $produit->setImageProd($req->get('imageProd'));

        $produit->setStatus($req->get('status'));

        $em->flush();

        $jsonContent = $Normalizer->normalize($produit, 'json', ['groups' => "Produits"]);
        return new Response("Demand updated successfully " . json_encode($jsonContent));
    }

    #[Route("/deleteProductJSON/{idProduit}", name: "deleteProduct")]
    public function deleteDemandJSON(Request $req, $idProduit, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $produits= $em->getRepository(Produit::class)->find($idProduit);
        $em->remove($produits);
        $em->flush();
        $jsonContent = $Normalizer->normalize($produits, 'json', ['groups' => "Produits"]);
        return new Response("Product deleted successfully " . json_encode($jsonContent));
    }
    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    
            $imageProd = $form->get('imageProd')->getData();
            if ($imageProd !== null)  { // check if it's a file object
                $originalFilename = pathinfo($imageProd->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageProd->guessExtension();
                try {
                    $imageProd->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $produit->setImageProd($newFilename);
            }
            $entityManager->persist($produit);
            $entityManager->flush();
                /* $sid = 'AC41b4907ff65d42e395894f9f3a003852';
            $token = 'c3f36ef46864faaa4806eeb9a9260f0a';
            $from = '+16814000701';
            $twilio = new Client($sid, $token);
            $message = $twilio->messages
                ->create(
                    "+21651223720", // to
                    array(
                        "from" => "+16814000701",
                        "body" => "Hello from Twilio",
                    )
                );
            dump($message->sid);
            */
            return $this->redirectToRoute('app_produit_index');
        }
    
        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{idProduit}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }


    #[Route('/{idProduit}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{idProduit}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getIdProduit(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
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

    /**
     * @Route ("/")
     * @param Request $request
     * @return Response
     */
    public function index(ProduitRepository $produitRepository,Request $request): Response
    {
        $produits = $produitRepository->findAll();
        $form = $this->createForm(SearchProduitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchquery = $form->getData()['nameProd'];
            $produits = $produitRepository->search($searchquery);
        }
        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route ("/view/view")
     * @param Request $request
     * @return Response
     */
    public function searchView(ProduitRepository $produitRepository,Request $request): Response
    {
        $produits = $produitRepository->findAll();
        $form = $this->createForm(SearchProduitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchquery = $form->getData()['nameProd'];
            $produits = $produitRepository->search($searchquery);
        }
        return $this->render('produit/Search_results.html.twig', [
            'produits' => $produits,
            'form' => $form->createView()
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

    #[Route('produit/pdf', name: 'app_pdf', methods: ['GET'])]
    public function pdf(ProduitRepository $produitRepository): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFront', 'Arial'); //lhne 3mlne type te3 font


        $dompdf = new Dompdf($pdfOptions); // nouveau pdf ajoute


        $html = $this->renderView('produit/pdf.html.twig', ['produits' => $produitRepository->findAll(),]); // lezem etdakhelha hedhii bch yemchii
        $dompdf->loadHtml($html); //lhne lhtml l bch yokhrej fl fichier html.twig bch ywalli pdf
        $dompdf->setPaper('A4', 'portrait'); // lhne bch ndakhell type te3 papier
        $dompdf->render(); //lhne bch n7otthom
        $dompdf->stream('produit.pdf', ['Attachement' => true]); //lhne bch yetsab automatique w dakhalna esm fichier.pdf

        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(), // lhne bch etdakhell les informations bch yekhdem twigg kima les routes lokhrin
        ]);
    }
}
