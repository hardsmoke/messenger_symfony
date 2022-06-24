<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessengerController extends AbstractController
{
    /**
     * @Route("/messenger", name="app_messenger")
     */
    public function index(Request $request, MessageRepository $messageRepository): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            $message->setDatetime(new \DateTime());
            $user = $this->getUser();
            $message->setSender($user);
            $em->persist($message);
            $em->flush();
        }

        return $this->render('messenger/index.html.twig', [
            'message_form' => $form->createView(),
            'messages' => $messageRepository->findAll(),
        ]);
    }
}
