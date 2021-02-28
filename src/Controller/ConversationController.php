<?php

namespace App\Controller;

use Exception;
use App\Entity\Partcipant;
use App\Entity\Conversation;
use App\Repository\UserRepository;
use Symfony\Component\WebLink\Link;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/conversations", name="conversations.")
 */

class ConversationController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ConversationRepository 
     */
    private $conversationRepository;


    public function __construct(UserRepository $userRepository, 
                                EntityManagerInterface $entityManager,
                                ConversationRepository $conversationRepository)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->conversationRepository = $conversationRepository;
    }

    /**
     * @Route("/", name="newConversations", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */

    public function index(Request $request)
    {
        $otherUser = $request->get('otherUser', 0);
        $otherUser = $this->userRepository->find($otherUser);
        
        if (is_null($otherUser)){
            throw new \Exception("The user was not found");
        }

        //cannot create a conversation with myself
        if ($otherUser->getId() === $this->getUser()->getId()){
            throw new \Exception("That's deep but you can't create a conversation with yourself");
        }

        //check if conversation already exists
            $conversation = $this->conversationRepository->findConversationByParticipants(
            $otherUser->getId(),
            $this->getUser()->getId()
        );

        if(count($conversation)) {
            throw new \Exception("The conversation already exists");
        }

        $conversation = new Conversation();

        $participant = new Partcipant();
        $participant->setUser($this->getUser());
        $participant->setConversation($conversation);

        
        $otherParticipant = new Partcipant();
        $otherParticipant->setUser($otherUser);
        $otherParticipant->setConversation($conversation);

        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->entityManager->persist($conversation);
            $this->entityManager->persist($participant);
            $this->entityManager->persist($otherParticipant);
            
            $this->entityManager->flush();
            $this->entityManager->commit();
        }catch(\Exception $e){
            $this->entityManager->rollback();
            throw $e;
        }


        return $this->json([
            'id' => $conversation->getId()
        ], Response::HTTP_CREATED, [], []);
    }

    /**
     * @Route("/", name="getConversations", methods={"GET"})
     * @param Request $request
     * @return JsonResponse 
     */
    public function getConvs(Request $request){

        $conversations = $this->conversationRepository->findConversationsByUser($this->getUser()->getId());
        
        $hubURL = $this->getParameter('mercure.default_hub');

        $this->addLink($request, new Link('mercure', $hubURL));
        
        dd($conversations);
        return $this->json($conversations);
    }

}