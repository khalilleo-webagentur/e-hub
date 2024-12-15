<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/profile/u8k8s5b5n0i6d9a0', name: 'app_profile')]
    public function index(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser()->isDeleted()) {
            $request->getSession()->invalidate(1);
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('profile/index.html.twig', [
            'email' => $this->getParameter('infoEmail'),
        ]);
    }
}
