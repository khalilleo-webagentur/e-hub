<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NoJsController extends AbstractController
{
    #[Route('/no-js/e6b5k1u7a9s8x7u4', name: 'app_noscript')]
    public function index(): Response
    {
        return $this->render('static/noscript.html.twig');
    }
}
