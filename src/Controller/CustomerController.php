<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Form\CustomerUpdateType;
use App\Form\LoginType;
use App\Form\RegistrationType;
use App\Repository\CustomerRepository;
use App\Service\Account\AccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    /**
     * @Route("/admin/customers", name="customer_all")
     */
    public function all(AccountService $account, CustomerRepository $repository)
    {
        if(is_null($account->getUserAdmin())){return $this->redirectToRoute('admin_login');}

        return $this->render('customer/all.html.twig', ['customers' => $repository->findAll()]);
    }

    /**
     * @Route("/admin/detail/customer/{id}", name="customer_details")
     */
    public function details(AccountService $account, Customer $customer)
    {
        if(is_null($account->getUserAdmin())){return $this->redirectToRoute('admin_login');}

        return $this->render('customer/details.html.twig', ['customer' => $customer]);
    }
    
    /**
     * @Route("/account/dashboard", name="customer_index")
     */
    public function index(AccountService $account, CustomerRepository $repository)
    {
        if(is_null($account->getUser())){return $this->redirectToRoute('customer_login');}

        $customer = $repository->find($account->getUser());

        return $this->render('customer/index.html.twig', ['customer' => $customer]);
    }

    /**
     * @Route("/account/edit", name="customer_edit")
     */
    public function edit(Request $request, AccountService $account, CustomerRepository $repository)
    {
        if(is_null($account->getUser())){ return $this->redirectToRoute('customer_login'); }

        $customer = $repository->find($account->getUser());
        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(CustomerUpdateType::class, $customer);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->flush();

            $this->addFlash('success', 'Vos informations personnelles ont bien été mises à jours.');
            return $this->redirectToRoute('customer_index');
        }

        return $this->render('customer/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/register", name="customer_register")
     */
    public function register(Request $request, AccountService $account)
    {
        if(!is_null($account->getUser())){ return $this->redirectToRoute('customer_index'); }

        $customer = new Customer();
        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(RegistrationType::class, $customer);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = password_hash($customer->getPassword(), PASSWORD_BCRYPT);
            $customer->setPassword($hash);

            $manager->persist($customer);
            $manager->flush();

            $account->setUser($customer->getId());

            $this->addFlash(
                "info",
                '<span class="mr-1">Votre compte client a été créé avec succès.</span>
                <span class="text-wrap">Merci pour votre confiance !</span>'
            );

            return $this->redirectToRoute('customer_index');
        }

        return $this->render('customer/register.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/login", name="customer_login")
     */
    public function login(AccountService $account, CustomerRepository $repository, Request $request)
    {
        if(!is_null($account->getUser())){ return $this->redirectToRoute('customer_index'); }

        $error = null;
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $password = $user->getPassword();
            $customer = $repository->findOneBy(['email' => $user->getEmail()]);

            if(is_null($customer)){ $error = true; }

            if(!is_null($customer) && !password_verify($password, $customer->getPassword())){
                $error = true;
            }elseif(!is_null($customer) && password_verify($password, $customer->getPassword())){
                $account->setUser($customer->getId());
                return $this->redirectToRoute('customer_index');
            }
        }

        return $this->render('customer/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }

    /**
     * @Route("/account/logout", name="customer_logout")
     */
    public function logout(AccountService $account)
    {
        $account->removeUser();

        return $this->redirectToRoute('home');
    }
}
