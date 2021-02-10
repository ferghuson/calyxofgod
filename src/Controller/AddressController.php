<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use App\Repository\CustomerRepository;
use App\Service\Account\AccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    /**
     * @Route("/address", name="address_index")
     */
    public function index(AccountService $account, CustomerRepository $customerRepository, AddressRepository $repository)
    {
        if(is_null($account->getUser())){return $this->redirectToRoute('customer_login');}

        $customer = $customerRepository->find($account->getUser());
        $addresses = $repository->findBy(['customer' => $customer, 'removed' => false]);

        return $this->render('address/index.html.twig', ['addresses' => $addresses]);
    }

    /**
     * @Route("/address/new", name="address_add")
     */
    public function add(AccountService $account, Request $request, CustomerRepository $customerRepository)
    {
        if(is_null($account->getUser())){return $this->redirectToRoute('customer_login');}

        $customer = $customerRepository->find($account->getUser());
        $address = new Address();
        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $address->setCustomer($customer);
            $manager->persist($address);
            $manager->flush();

            return $this->redirectToRoute('address_index');
        }

        return $this->render('address/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/address/edit/{id}", name="address_edit")
     */
    public function edit(Address $address, AccountService $account, Request $request)
    {
        if(is_null($account->getUser())){return $this->redirectToRoute('customer_login');}

        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->flush();
            return $this->redirectToRoute('address_index');
        }

        return $this->render('address/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/address/remove/{id}", name="address_remove")
     */
    public function remove(Address $address, AccountService $account)
    {
        if(is_null($account->getUser())){return $this->redirectToRoute('customer_login');}

        $manager = $this->getDoctrine()->getManager();
        $address->setRemoved(true);
        $manager->flush();
        return $this->redirectToRoute('address_index');
    }
}
