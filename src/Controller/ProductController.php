<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Form\ProductUpdateType;
use App\Repository\ProductCollectionRepository;
use App\Repository\ProductRepository;
use App\Service\Account\AccountService;
use App\Service\Upload\UploadService;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{slug}", name="product_detail")
     */
    public function detail(Product $product)
    {
        return $this->render('product/product.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/search", name="product_search")
     */
    public function search(Request $request, ProductRepository $repository)
    {
        $keyword = trim(strtolower(strip_tags($request->query->get('q'))));
        $products = $repository->search($keyword);

        return $this->render('product/search.html.twig', [
            'keyword' => $keyword,
            'products' => $products
        ]);
    }

    /**
     * @Route("/admin/products", name="product_all")
     */
    public function all(AccountService $account, ProductRepository $repository, Request $request)
    {
        if(is_null($account->getUserAdmin())){return $this->redirectToRoute('admin_login');}

        $products = $repository->findBy(['removed'=>false], ['name'=>'ASC']);

        $keyword = (!empty($request->request->get('keyword'))) ? strtolower(trim($request->request->get('keyword'))) : null;

        if(!is_null($keyword))
        {
            $products = $repository->search($keyword);
        }

        return $this->render('product/index.html.twig', ['products' => $products, 'keyword'=>$keyword]);
    }

    /**
     * @Route("/admin/add/product", name="product_add")
     */
    public function add(AccountService $account, ProductCollectionRepository $collectionRepository, Request $request, UploadService $uploader)
    {
        if(is_null($account->getUserAdmin())){return $this->redirectToRoute('admin_login');}

        $collections = $collectionRepository->findAll();
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $id = $request->request->get('collection');
            $collection = $collectionRepository->find($id);
            $product->setSection($collection);

            $image = $form->get('image')->getData();
            if($image){
                $slugify = new Slugify();
                $targetDirectory = $this->getParameter('product_directory');
                $safeFileName = $slugify->slugify(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
                $imageName = $uploader->upload($image, $safeFileName, $targetDirectory);
                $imagePath = '/img/product/'.$imageName;
                $product->setImage($imagePath);
            }

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();

            $url = $this->generateUrl('product_all');
            $this->addFlash(
                'info',
                'Nouveau produit ajouté avec succès. Retour vers les <a href="'.$url.'">produits</a>.'
            );
            return $this->redirectToRoute('product_add');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
            'collections' => $collections
        ]);
    }

    /**
     * @Route("/admin/edit/product/{id}", name="product_edit")
     */
    public function edit(Product $product, AccountService $account, Request $request, ProductCollectionRepository $collectionRepository)
    {
        if(is_null($account->getUserAdmin())){return $this->redirectToRoute('admin_login');}

        $collections = $collectionRepository->findAll();
        $form = $this->createForm(ProductUpdateType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $id = $request->request->get('collection');
            $collection = $collectionRepository->find($id);
            $product->setSection($collection);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Modification effectuée avec succès !');
            return $this->redirectToRoute('product_all');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
            'collections' => $collections
        ]);
    }

    /**
     * @Route("/admin/remove/product/{id}", name="product_remove")
     */
    public function remove(Product $product)
    {
        $product->setRemoved(true);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', 'Suppression effectuée avec succès !');
        return $this->redirectToRoute('product_all');
    }
}
