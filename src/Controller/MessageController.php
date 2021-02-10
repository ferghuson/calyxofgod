<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request)
    {
        $message = new Message();
        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(ContactType::class, $message);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($message);
            $manager->flush();

            $this->addFlash('success', 'Votre message a bien été reçu... nous vous répondrons sous peu par mail ou par appel. Merci !');
            return $this->redirectToRoute('contact');
        }

        return $this->render('message/contact.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/messages", name="message_all")
     */
    public function all()
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }
}
