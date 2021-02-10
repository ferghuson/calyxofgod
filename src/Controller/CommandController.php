<?php

namespace App\Controller;

use App\Entity\Command;
use App\Form\CommandUpdateType;
use App\Repository\CommandRepository;
use App\Repository\CustomerRepository;
use App\Service\Account\AccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommandController extends AbstractController
{
    /**
     * @Route("/admin/commands", name="command_index")
     */
    public function index(AccountService $account, CommandRepository $commandRepository)
    {
        if(is_null($account->getUserAdmin())){return $this->redirectToRoute('admin_login');}

        $commands = $commandRepository->undelivered();

        return $this->render('command/index.html.twig', ['commands' => $commands]);
    }

    /**
     * @Route("/admin/sales", name="command_sales")
     */
    public function sales(AccountService $account, CommandRepository $commandRepository)
    {
        if(is_null($account->getUserAdmin())){return $this->redirectToRoute('admin_login');}

        $sales = $commandRepository->findBy(['state'=>'Livré']);

        return $this->render('command/sales.html.twig', ['commands' => $sales]);
    }

    /**
     * @Route("/admin/detail/command/{id}", name="command_details")
     */
    public function details(AccountService $account, Command $command, Request $request)
    {
        if(is_null($account->getUserAdmin())){return $this->redirectToRoute('admin_login');}

        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(CommandUpdateType::class, $command);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->flush();
            return $this->redirectToRoute('command_index');
        }

        return $this->render('command/details.html.twig', [
            'form' => $form->createView(),
            'command' => $command
        ]);
    }
    
    /**
     * @Route("/orders", name="customer_orders")
     */
    public function customerOrders(AccountService $account, CustomerRepository $customerRepository, CommandRepository $repository)
    {
        if(is_null($account->getUser())){return $this->redirectToRoute('customer_login');}

        $customer = $customerRepository->find($account->getUser());
        $commands = $repository->findBy(['customer'=>$customer]);

        return $this->render('command/customer-commands.html.twig', ['commands' => $commands]);
    }

    /**
     * @Route("/details/order/{id}", name="customer_order")
     */
    public function customerOrder(AccountService $account, Command $command)
    {
        if(is_null($account->getUser())){return $this->redirectToRoute('customer_login');}

        return $this->render('command/customer-command.html.twig', ['command' => $command]);
    }
}
