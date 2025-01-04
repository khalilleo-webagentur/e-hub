<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ConfigService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LegalController extends AbstractController
{
    public function __construct(
        private readonly ConfigService $configService
    ) {
    }

    #[Route('/imprint/u6p2c2c5i7m4g5q0', name: 'app_legal_imprint')]
    public function imprint(): Response
    {
        return $this->render('static/imprint.html.twig');
    }

    #[Route('/privacy-policy/y7l0q6k9e1o1z3r5', name: 'app_legal_privacy_policy')]
    public function privacyPolicy(): Response
    {
        $street = $this->configService->getParameter('addressStreet');
        $plz = $this->configService->getParameter('addressPlz');
        $city = $this->configService->getParameter('addressCity');
        $country = $this->configService->getParameter('addressCountry');
        $phonenumber = $this->configService->getParameter('phoneNumber');
        $email = $this->configService->getParameter('legalEmail');

        return $this->render('static/privacy-policy.html.twig', [
            'street' => $street,
            'plz' => $plz,
            'city' => $city,
            'country' => $country,
            'phoneNumber' => $phonenumber,
            'email' => $email,
        ]);
    }
}
