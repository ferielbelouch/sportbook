<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
 

    /**
     * @Route("/profile")
     */
class ProfileController extends AbstractController
{
    /**
     * @Route("/club", name="profile_club")
     */
    public function index()
    {
        // return $this->render('profile/index.html.twig', [
        //     'controller_name' => 'ProfileController',
        // ]);
        return $this->render('profile/index.html.twig');
    }


    /**
     * @Route("/player", name="profile_player")
     */
    public function index2(): Response
    {
        // return $this->render('profile/index_two.html.twig', [
        //     'controller_name' => 'ProfileController',
        // ]);
        return $this->render('profile/index_two.html.twig');
    }

        /**
     * @Route("/coach", name="profile_coach")
     */
    public function index3(): Response
    {
        return $this->render('profile/index_three.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
}
