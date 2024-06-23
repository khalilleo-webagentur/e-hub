<?php

namespace App\Security;

use App\Service\TempUserService;
use App\Service\TwoFactorAuthService;
use App\Service\UserService;
use App\Traits\FormValidationTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;
    use FormValidationTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UserService $userService,
        private readonly TwoFactorAuthService $twoFactorAuthService,
        private readonly TempUserService $tempUserService
    ) {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $this->validate($request->request->get('_username', ''));

        $otp = $this->validate($request->request->get('otp', ''));

        $csrfToken = $this->validate($request->request->get('_csrf_token', ''));

        if (!$email || !$otp || !$csrfToken) {
            throw new CustomUserMessageAuthenticationException('All fields are required.');
        }

        $user = $this->userService->getByEmail($email);

        if (!$user || $otp !== $user->getToken()) {
            $email = 'no@token.yet';
        }

        $request->getSession()->set('_username', $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($email),
            [
                new CsrfTokenBadge('authenticate', $csrfToken),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user = $token->getUser();

        $this->userService->save($user->setToken(null));

        if ($tempUser = $this->tempUserService->getByEmail($user->getUserIdentifier())) {
            $this->tempUserService->delete($tempUser);
        }

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_profile'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
