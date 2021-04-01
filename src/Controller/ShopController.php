<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


    /**
     * @Route("/shop")
     */
class ShopController extends AbstractController
{
    /**
     * @Route("/", name="shop")
     */
    public function index(): Response
    {
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
        ]);
    }
    /**
     * @Route("/show", name="shop_show")
     */
    public function show():Response
    {
        return $this->render('shop/show.html.twig', [
            'controller_name' => 'ShopController',
        ]);
    }
}
