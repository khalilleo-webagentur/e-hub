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
        $street = $this->configService->getParameter('address_street');
        $plz = $this->configService->getParameter('adress_plz');
        $city = $this->configService->getParameter('adress_city');
        $country = $this->configService->getParameter('adress_country');
        $phonenumber = $this->configService->getParameter('phonenumber');
        $email = $this->configService->getParameter('legal_emal');

        return $this->render('static/privacy-policy.html.twig',  [
            'street' => $street,
            'plz' => $plz,
            'city' => $city,
            'country' => $country,
            'phonenumber' => $phonenumber,
            'email' => $email,
        ]);
    }
}
