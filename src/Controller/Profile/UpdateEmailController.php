<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Entity\TempUser;
use App\Mails\Account\ChangeEmailUserMail;
use App\Service\TempUserService;
use App\Service\TokenGeneratorService;
use App\Service\UserService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UpdateEmailController extends AbstractController
{
    use FormValidationTrait;

    private const APP_PROFILE = 'app_profile';
    private const APP_AUTH = 'app_auth';

    public function __construct(
        private readonly UserService $userService,
        private readonly TempUserService $tempUserService,
        private readonly TokenGeneratorService $tokenGeneratorService
    ) {
    }

    #[Route('/profile/personal-data/u9k0d6bkngh6dba8/change-email', name: 'app_profile_personal_data_email_store', methods: 'POST')]
    public function storeEmail(Request $request, ChangeEmailUserMail $changeEmailUserMail): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        $newEmail = $this->validateEmail($request->request->get('iEmail'));

        if (!$newEmail) {
            $this->addFlash('warning', 'Email field is required.');
            return $this->redirectToRoute(self::APP_PROFILE);
        }

        if ($this->userService->getByEmail($newEmail)) {
            $this->addFlash('warning', 'Email is not valid.');
            return $this->redirectToRoute(self::APP_PROFILE);
        }

        $tempUser = new TempUser();

        $token = $this->tokenGeneratorService->randomTokenForVerification();

        $this->tempUserService->save(
            $tempUser
                ->setUser($user)
                ->setEmail($newEmail)
                ->setToken($token)
        );

        $changeEmailUserMail->send($user->getName(), $user->getUserIdentifier(), $token);

        $this->addFlash('notice', sprintf('An email has been sent to your Email [%s]. Verifiy your email now.', $user->getUserIdentifier()));

        return $this->redirectToRoute(self::APP_PROFILE);
    }

    #[Route('/p/u/verify/{token}', name: 'app_profile_personal_data_email_verify')]
    public function verifiyEmail(?string $token): RedirectResponse
    {
        $token = $this->validate($token);

        if (!$token) {
            $this->addFlash('notice', 'Token is expired.');
            return $this->redirectToRoute(self::APP_AUTH);
        }

        if ($tempUser = $this->tempUserService->getByToken($token)) {
            $user = $tempUser->getUser();
            $newEmail = $tempUser->getEmail();
            $this->userService->save(
                $user
                    ->setEmail($newEmail)
                    ->setPassword($this->userService->encodePassword($newEmail))
                );
            $this->tempUserService->delete($tempUser);
            $this->addFlash('success', sprintf('Your email has been updated. Login with your new Email [%s]', $newEmail));
            return $this->redirectToRoute(self::APP_AUTH);
        }

        $this->addFlash('notice', 'Email could not be updated.');

        return $this->redirectToRoute(self::APP_AUTH);
    }
}
