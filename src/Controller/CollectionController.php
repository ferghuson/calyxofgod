<?php

namespace App\Controller;

use App\Entity\ProductCollection;
use App\Form\ProductCollectionType;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductCollectionRepository;
use App\Service\Account\AccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CollectionController extends AbstractController
{
    /**
     * @Route("/collection/{slug}", name="collection_detail")
     */
    public function detail(ProductCollection $collection)
    {
        return $this->render('collection/collection.html.twig', ['collection' => $collection]);
    }

    /**
     * @Route("/admin/collections", name="collection_all")
     */
    public function all(ProductCollectionRepository $collectionRepository)
    {
        $collections = $collectionRepository->findAll();

        return $this->render('collection/index.html.twig', [
            'collections' => $collections
        ]);
    }

    /**
     * @Route("/admin/add/collection", name="collection_add")
     */
    public function add(AccountService $account, Request $request, ProductCategoryRepository $categoryRepository)
    {
        if(is_null($account->getUserAdmin())){return $this->redirectToRoute('admin_login');}

        $categories = $categoryRepository->findAll();
        $collection = new ProductCollection();
        $form = $this->createForm(ProductCollectionType::class, $collection);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $id = $request->request->get('category');
            $category = $categoryRepository->find($id);
            $collection->setCategory($category);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($collection);
            $manager->flush();

            $url = $this->generateUrl('collection_all');
            $this->addFlash(
                'info',
                'Nouvelle catégorie ajouté avec succès. Retour vers les <a href="'.$url.'">collections</a>.'
            );
            return $this->redirectToRoute('collection_add');
        }

        return $this->render('collection/new.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/edit/collection/{id}", name="collection_edit")
     */
    public function edit(ProductCollection $collection, AccountService $account, Request $request, ProductCategoryRepository $categoryRepository)
    {
        if(is_null($account->getUserAdmin())){return $this->redirectToRoute('admin_login');}

        $categories = $categoryRepository->findAll();
        $form = $this->createForm(ProductCollectionType::class, $collection);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $id = $request->request->get('category');
            $category = $categoryRepository->find($id);
            $collection->setCategory($category);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($collection);
            $manager->flush();

            $this->addFlash('success', 'Modification effectuée avec succès !');
            return $this->redirectToRoute('collection_all');
        }

        return $this->render('collection/edit.html.twig', [
            'form' => $form->createView(),
            'collection' => $collection,
            'categories' => $categories
        ]);
    }
}
