<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\PartcipantRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/messages", name="messages.")
 */
class MessageController extends AbstractController
{
    const ATTRIBUTES_TO_SERIALIZE = ['id', 'content', 'createdAt', 'mine'];

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var MessageRepository
     */
    private $messageRepository;
     /**
     * @var PartcipantRepository
     */
    private $partcipantRepository;
     /**
     * @var PublisherInterface
     */
    private $publisher;

    public function __construct(EntityManagerInterface $entityManager,
                                MessageRepository $messageRepository,
                                UserRepository $userRepository,
                                PartcipantRepository $partcipantRepository,
                                PublisherInterface $publisher)
    {
        $this->entityManager = $entityManager;
        $this->messageRepository = $messageRepository;
        $this->userRepository = $userRepository;
        $this->partcipantRepository = $partcipantRepository;
        $this->publisher = $publisher;

    }

    /**
     * @Route("/{id}", name="getMessages", methods={"GET"})
     * @param Request $request
     * @param Conversation $conversation
     * @return Response
     */
    public function index(Request $request, Conversation $conversation)
    {

        // Si le user connecté n'est pas un participant de la conversation, l'accès ne sera pas autorisé
        $this->denyAccessUnlessGranted('view', $conversation);

        $messages = $this->messageRepository->findMesssageByConversationId(
            $conversation->getId()
        );

        // dd($messages);
        
        /**
         * @var $message Message
         */
        array_map(function ($message) 
        {
            $message->setMine(
            $message->getUser()->getId() === $this->getUser()->getId()
            ? true: false);
        }, $messages);

        // return $this->render('message/index.html.twig', [
        //     'controller_name' => 'MessageController'
        // ]);

        return $this->json($messages, Response::HTTP_OK, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }

    /**
     * @Route("/{id}", name="newMessage", methods={"POST"})
     * @param Request $request
     * @param Conversation $conversation
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws \Exception
     */
    public function newMessage(Request $request, Conversation $conversation, SerializerInterface $serializer)
    {
        $user = $this->userRepository->findOneBy(['id' => 12]);

        $recipient = $this->partcipantRepository->findParticipantByConversationIdAndUserId(
            $conversation->getId(),
            $user->getId()
        );

        
        $content = $request->get('content', null);
        $message = new Message();
        $message->setContent($content);
        // $message->setUser($this->userRepository->findOneBy(['id' => 12]));
        $message->setUser($user);
        // $message->setMine(true);

        $conversation->addMessage($message);
        $conversation->setLastMessage($message);

        $this->entityManager->getConnection()->beginTransaction();
        try{
            $this->entityManager->persist($message);
            $this->entityManager->persist($conversation);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch(\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }


        $message->setMine(false);
        $messageSerialized = $serializer->Serialize($message, 'json', [
            'attributes' => ['id', 'content', 'createdAt', 'mine', 'conversation' => ['id']]
        ]);

        $update = new Update(
            [
                sprintf("/conversations/%s", $conversation->getId()),
                sprintf("/conversations/%s", $recipient->getUser()->getUsername()),
            ],
            $messageSerialized,
            sprintf("/%s", $recipient->getUser()->getUsername() ) 
        );

        $this->publisher->__invoke($update);
        $message->setMine(true);



        return $this->json($message, Response::HTTP_CREATED, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);    
    }
}