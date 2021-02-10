<?php

namespace App\Controller;

use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function navBar(ProductCategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findBy([], ['name'=>'ASC']);

        return $this->render('partials/header.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $repository)
    {
        $selection = $repository->findBy(['selected'=>true,'removed'=>false]);
        $latest = $repository->findBy(['removed'=>false], ['addedAt'=>'DESC'], 10);

        return $this->render('home/index.html.twig', ["selection"=>$selection, "latest"=>$latest]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('home/about.html.twig');
    }
}
