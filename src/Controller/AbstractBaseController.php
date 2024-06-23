<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * AbstractBaseController for User
 */
abstract class AbstractBaseController extends AbstractController
{
    protected const LOGIN_ROUTE = 'app_login';
    protected const HOME_ROUTE = 'app_home';

    protected function granteUserRoleUserOrRedirectToLogin(): ?RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if (!$this->getUser()) {
            return $this->redirectToRoute(self::LOGIN_ROUTE);
        }

        return null;
    }
}