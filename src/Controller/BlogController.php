<?php

namespace App\Controller;

use App\Service\Greeting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @var Greeting
     */
    private $greeting;

    public function __construct(Greeting $greeting)
    {
        $this->greeting = $greeting;
    }

    /**
     * @Route("/" , name="blog_index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        return $this->render('base.html.twig' , ['message' => $this->greeting->greet(
            $request->get('name')
        )]);
    }
}

