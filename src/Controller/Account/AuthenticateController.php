<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Mails\Account\HandelTwoFactorAuthMail;
use App\Service\TokenGeneratorService;
use App\Service\UserService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticateController extends AbstractController
{
    use FormValidationTrait;

    private const HOME_ROUTE = 'app_home';
    private const LOGIN_ROUTE = 'app_login';
    private const AUTHENTICATE_ROUTE = 'app_auth';
    private const PROFILE_ROUTE = 'app_profile';

    public function __construct(
        private readonly UserService $userService,
        private readonly TokenGeneratorService $tokenGeneratorService
    ) {
    }

    #[Route('/auth/e1j4c3o7l9i5x9k6', name: 'app_auth')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute(self::PROFILE_ROUTE);
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('account/auth.html.twig', [
            'email' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/auth/send/t8n4q0o0t5l2w5e5', name: 'app_auth_send')]
    public function send(Request $request, HandelTwoFactorAuthMail $handelTwoFactorAuthMail): RedirectResponse|Response
    {
        $email = $this->validateEmail($request->request->get('_username'));

        if (!$email) {
            $this->addFlash('warning', 'Email field is required.');
            return $this->redirectToRoute(self::AUTHENTICATE_ROUTE);
        }

        $user = $this->userService->getByEmail($email);

        if (!$user) {
            $this->addFlash('notice', 'If your email exists then a new OTP just being sent to your email.');
            return $this->redirectToRoute(self::HOME_ROUTE);
        }

        if (!$user->isVerified()) {
            $this->addFlash('warning', 'Your account is not verified yet. Please verify your email.');
            return $this->redirectToRoute(self::HOME_ROUTE);
        }

        if ($user->isDeleted()) {
            $this->addFlash('notice', 'Recently you have requested to delete your account. Contact us to get your account back.');
            return $this->redirectToRoute(self::HOME_ROUTE);
        }

        $otp = $this->tokenGeneratorService->randomToken();

        $this->userService->save($user->setToken($otp));

        $handelTwoFactorAuthMail->send($user->getName(), $email, $otp);

        $this->addFlash('notice', 'A new OTP is being sent to your email.');

        return $this->render('account/login.html.twig', [
            'email' => $email
        ]);
    }
}
