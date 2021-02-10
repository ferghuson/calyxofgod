<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use App\Repository\AdminRepository;
use App\Repository\CommandRepository;
use App\Repository\CustomerRepository;
use App\Repository\ProductRepository;
use App\Service\Account\AccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin_index")
     */
    public function index(AccountService $account, CommandRepository $commandRepository, ProductRepository $productRepository, CustomerRepository $customerRepository)
    {
        if(is_null($account->getUserAdmin())){ return $this->redirectToRoute('admin_login'); }

        return $this->render('admin/index.html.twig', [
            'sales' => $commandRepository->count(['state'=>'Livré']),
            'commands' => $commandRepository->undelivered(),
            'products' => $productRepository->count(['removed'=>false]),
            'customers' => $customerRepository->count([])
        ]);
    }

    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function login(Request $request, AdminRepository $repository, AccountService $account)
    {
        $error = false;
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $password = $admin->getPassword();
            $admin = $repository->findOneBy(['username' => $admin->getUsername()]);

            $error = (is_null($admin)) ? true : false;

            if(!is_null($admin) && !password_verify($password, $admin->getPassword()))
            {
                $error = true;
            }
            elseif(!is_null($admin) && password_verify($password, $admin->getPassword()))
            {
                $account->setUserAdmin($admin->getId());

                return $this->redirectToRoute('admin_index');
            }
        }

        return $this->render('admin/login.html.twig', [
            'error' => $error,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/logout", name="admin_logout")
     */
    public function logout(AccountService $account)
    {
        $account->removeUserAdmin();

        return $this->redirectToRoute('admin_login');
    }
}
