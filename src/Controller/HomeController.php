<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Annonce;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use App\Repository\EventRepository;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use App\Repository\AnnonceRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AnnonceRepository $annonceRepository, EventRepository $eventRepository): Response
    {
        // $username= $this->getUser()->getUsername();
        // $token = (new Builder())
        //     ->withClaim('mercure', ['subscribe' => [sprintf('/%s', $username)]])
        //     ->getToken(
        //         new Sha256(),
        //         new Key($this->getParameter('mercure_secret_key'))
        //     )
        // ;
        $annonces = new Annonce();
        $annonces = $annonceRepository->LastAnnonce();
        dump($annonces);
        $events = new Event();        
        $events = $eventRepository->threeLastEvent();
        $response = $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'annonces' => $annonces,
            'events' => $events
        ]);

        // $response->headers->setCookie(
        //     new Cookie(
        //         'mercureAuthorization', 
        //         $token, 
        //         (new \DateTime())
        //         ->add(new \DateInterval('PT2H')),
        //         '/.well-known/mercure',
        //         null,
        //         false,
        //         true,
        //         false,
        //         'strict'
        //     )
        //     );
         return $response;
    }
}
