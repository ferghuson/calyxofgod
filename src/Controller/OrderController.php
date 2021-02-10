<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Command;
use App\Entity\Customer;
use App\Entity\Details;
use App\Entity\Payment;
use App\Entity\User;
use App\Form\AddressType;
use App\Form\LoginType;
use App\Form\PaymentType;
use App\Form\RegistrationType;
use App\Repository\AddressRepository;
use App\Repository\CustomerRepository;
use App\Service\Account\AccountService;
use App\Service\Cart\CartService;
use App\Service\Order\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/order/address", name="order_address")
     */
    public function address(AccountService $account, CartService $cart, OrderService $order, CustomerRepository $customerRepository, AddressRepository $repository, Request $request)
    {
        if(is_null($account->getUser())){ return $this->redirectToRoute('order_login'); }
        if($cart->getTotalQuantity() == 0){return $this->redirectToRoute('cart_index');}

        $customer = $customerRepository->find($account->getUser());
        $manager = $this->getDoctrine()->getManager();
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $address->setCustomer($customer);
            $manager->persist($address);
            $manager->flush();

            $order->setAddress($address->getId());
            return $this->redirectToRoute('order_payment');
        }
        elseif($request->request->has('old_address'))
        {
            $id = $request->request->get('old_address');
            $order->setAddress($id);
            return $this->redirectToRoute('order_payment');
        }

        return $this->render('order/address.html.twig', [
            'addresses' => $repository->findBy(['customer'=>$customer, 'removed'=>false]),
            'form' => $form->createView(),
            'items' => $cart->getTotalQuantity(),
            'total' => $cart->getTotalPrice()
        ]);
    }

    /**
     * @Route("/order/payment", name="order_payment")
     */
    public function payment(AccountService $account, CartService $cart, OrderService $order, Request $request)
    {
        if(is_null($account->getUser())){ return $this->redirectToRoute('order_login'); }
        if($cart->getTotalQuantity() == 0){ return $this->redirectToRoute('cart_index'); }
        if(is_null($order->getAddress())) {return $this->redirectToRoute('order_address');  }

        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $order->setPayment($payment->getMethod());
            return $this->redirectToRoute('order_complete');
        }

        return $this->render('order/payment.html.twig', [
            'form' => $form->createView(),
            'items' => $cart->getTotalQuantity(),
            'total' => $cart->getTotalPrice()
        ]);
    }

    /**
     * @Route("/order/complete", name="order_complete")
     */
    public function complete(AccountService $account, CartService $cart, OrderService $order, CustomerRepository $customerRepository)
    {
        if(is_null($account->getUser())){ return $this->redirectToRoute('order_login'); }
        if($cart->getTotalQuantity() == 0){return $this->redirectToRoute('cart_index');}

        $command        = new Command();
        $customer       = $customerRepository->find($account->getUser());
        $manager        = $this->getDoctrine()->getManager();

        $command->setCustomer($customer)->setAddress($order->getAddress())->setPayment($order->getPayment())->setTotal($cart->getTotalPrice());
        $manager->persist($command);
        $manager->flush();

        foreach ($cart->getFullCart() as $item) {
            $commandDetails = new Details();
            $commandDetails->setCommand($command)->setProduct($item['product'])->setQuantity($item['quantity'])
                ->setPrice($item['product']->getPrice())
            ;
            $manager->persist($commandDetails);
            $manager->flush();
        }

        $cart->clearCart();

        return $this->render('order/complete.html.twig');
    }

    /**
     * @Route("/order/login", name="order_login")
     */
    public function login(AccountService $account, CustomerRepository $repository, Request $request)
    {
        if(!is_null($account->getUser())){ return $this->redirectToRoute('order_address'); }

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
                return $this->redirectToRoute('order_address');
            }
        }

        return $this->render('order/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }

    /**
     * @Route("/order/register", name="order_register")
     */
    public function register(Request $request, AccountService $account)
    {
        if(!is_null($account->getUser())){ return $this->redirectToRoute('order_address'); }

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

            return $this->redirectToRoute('order_address');
        }

        return $this->render('order/register.html.twig', ['form' => $form->createView()]);
    }
}
