<?php

namespace App\Controller;

use App\Entity\ProductCategory;
use App\Form\CategoryType;
use App\Repository\ProductCategoryRepository;
use App\Service\Account\AccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/categories", name="category_all")
     */
    public function all(AccountService $account, ProductCategoryRepository $repository)
    {
        if(is_null($account->getUserAdmin())){return $this->redirectToRoute('admin_login');}

        $categories = $repository->findAll();

        return $this->render('category/index.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/admin/add/category", name="category_add")
     */
    public function add(AccountService $account, Request $request)
    {
        if(is_null($account->getUserAdmin())){return $this->redirectToRoute('admin_login');}

        $category = new ProductCategory();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();
            $url = $this->generateUrl('category_all');
            $this->addFlash(
                'info',
                'Nouvelle catégorie ajouté avec succès. Retour vers les <a href="'.$url.'">catégories</a>.'
            );
            return $this->redirectToRoute('category_add');
        }

        return $this->render('category/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/edit/category/{id}", name="category_edit")
     */
    public function edit(ProductCategory $category, AccountService $account, Request $request)
    {
        if(is_null($account->getUserAdmin())){return $this->redirectToRoute('admin_login');}

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash(
                'success',
                'Modification effectuée avec succès !'
            );
            return $this->redirectToRoute('category_all');
        }

        return $this->render('category/edit.html.twig', ['form' => $form->createView()]);
    }
}
