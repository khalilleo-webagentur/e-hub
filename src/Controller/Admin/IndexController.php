<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('u7x6g2q8r6/dashboard/o3o4v1v3a1g8h2q2')]
class IndexController extends AbstractController
{
    #[Route('/home', name: 'app_admin_index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('admin/index.html.twig');
    }
}
