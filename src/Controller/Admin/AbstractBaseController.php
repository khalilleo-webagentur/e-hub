<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Helper\Model\UserHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * AbstractBaseController for Admin Dashboard
 */
abstract class AbstractBaseController extends AbstractController
{
    protected const LOGIN_ROUTE = 'app_login';
    protected const HOME_ROUTE = 'app_home';

    protected function denyAccessUnlessGrantedRoleEditor(): ?RedirectResponse
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute(self::LOGIN_ROUTE);
        }

        $this->denyAccessUnlessGranted(UserHelper::ROLE_EDITOR);

        return null;
    }

    protected function denyAccessUnlessGrantedRoleAdmin(): ?RedirectResponse
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute(self::LOGIN_ROUTE);
        }

        $this->denyAccessUnlessGranted(UserHelper::ROLE_ADMIN);

        return null;
    }
}