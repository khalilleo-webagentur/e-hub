<?php

declare(strict_types=1);

namespace App\Controller\Admin\Newsletter;

use App\Controller\Admin\AbstractBaseController;
use App\Service\Export\ExportNewslettersService;
use App\Service\Export\ExportSubscribersDataService;
use App\Service\UserService;
use App\Traits\FormValidationTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/u7x6g2q8r6/newsletter/export')]
class ExportController extends AbstractBaseController
{
    use FormValidationTrait;

    private const ADMIN_SUBSCRIBERS_ROUTE_INDEX = 'app_admin_newsletter_subscribers_index';
    private const ADMIN_NEWSLETTERS_ROUTE_INDEX = 'app_admin_newsletter_index';

    public function __construct(
        private readonly UserService $userService
    ) {
    }

    #[Route('/subscribers-data', name: 'app_admin_newsletter_subscribers_export', methods: 'POST')]
    public function export(Request $request, ExportSubscribersDataService $exportSubscribersDataService): RedirectResponse|Response
    {
        if (!$this->isCsrfTokenValid('authenticate', $this->validate($request->request->get('_csrf_token')))) {
            $this->addFlash('warning', 'CSRF Token is not valid.');
            return $this->redirectToRoute(self::ADMIN_SUBSCRIBERS_ROUTE_INDEX);
        }

        $this->denyAccessUnlessGrantedRoleUser();

        $currentPassword = $this->validate($request->request->get('currentPassword'));

        if (!$this->userService->isPasswordValid($this->getUser(), $currentPassword)) {
            $this->addFlash('warning', 'Please type your current password');
            return $this->redirectToRoute(self::ADMIN_SUBSCRIBERS_ROUTE_INDEX);
        }

        $user = $this->getUser();

        if ($this->validateCheckbox($request->request->get('justEmails'))) {
            return new Response($exportSubscribersDataService->emailsAsJson($user));
        }

        return new Response($exportSubscribersDataService->asJson($user));
    }


    #[Route('/newsletters-data', name: 'app_admin_newsletter_export', methods: 'POST')]
    public function exportNewsletters(Request $request, ExportNewslettersService $exportNewslettersService): RedirectResponse|Response
    {
        $token = $this->validate($request->request->get('_csrf_token'));

        if (!$this->isCsrfTokenValid('authenticate', $token)) {
            $this->addFlash('warning', 'CSRF Token is not valid.');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTERS_ROUTE_INDEX);
        }

        $this->denyAccessUnlessGrantedRoleUser();
        $currentPassword = $this->validate($request->request->get('currentPassword'));

        if (!$this->userService->isPasswordValid($this->getUser(), $currentPassword)) {
            $this->addFlash('warning', 'Please type your current password');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTERS_ROUTE_INDEX);
        }

        $user = $this->getUser();

        return new Response($exportNewslettersService->asJson($user));
    }
}
