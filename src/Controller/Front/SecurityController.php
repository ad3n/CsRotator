<?php

declare(strict_types=1);

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login()
    {
        $authUtils = $this->container->get('security.authentication_utils');

        return $this->render('security/login.html.twig', [
            'username' => $authUtils->getLastUsername(),
            'error' => $authUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheck()
    {
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }
}
