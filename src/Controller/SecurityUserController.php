<?php
/**
 * Created by PhpStorm.
 * User: DarkoKlisuric
 * Date: 23.7.2019.
 * Time: 13:03
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityUserController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/login" , name="security_login")
     * @param AuthenticationUtils $utils
     */
    public function login(AuthenticationUtils $utils)
    {
        return new Response($this->twig->render(
                'security/login.html.twig' ,
            [
                'last_username' =>$utils->getLastUsername(),
                'error' =>$utils->getLastAuthenticationError()
            ]
        ));
    }

    /**
     * @Route("/logout" , name="security_logout")
     */
    public function logout()
    {

    }
}