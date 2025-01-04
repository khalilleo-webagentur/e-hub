<?php

declare(strict_types=1);

namespace App\Controller\Admin\Newsletter;

use App\Controller\Admin\AbstractBaseController;
use App\Service\NewsletterService;
use App\Service\NewsletterSubscriberService;
use App\Service\UserService;
use App\Traits\FormValidationTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/newsletter/job')]
class JobController extends AbstractBaseController
{
    use FormValidationTrait;

    private const ADMIN_NEWSLETTER_ROUTE_INDEX = 'app_admin_newsletter_index';
    private const ADMIN_NEWSLETTER_SUBSCRIBERS_ROUTE_INDEX = 'app_admin_newsletter_subscribers_index';

    public function __construct(
        private readonly UserService                 $userService,
        private readonly NewsletterService           $newsletterService,
        private readonly NewsletterSubscriberService $newsletterSubscriberService
    ) {
    }

    #[Route('/delete-all-newsletters-permanently', name: 'app_admin_newsletter_job_delete_all_newsletters', methods: 'POST')]
    public function deleteNewsletters(Request $request): RedirectResponse
    {
        if (!$this->isCsrfTokenValid('authenticate', $this->validate($request->request->get('_csrf_token')))) {
            $this->addFlash('warning', 'CSRF Token is not valid.');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_ROUTE_INDEX);
        }

        $this->denyAccessUnlessGrantedRoleAdmin();

        $currentPassword = $this->validate($request->request->get('currentPassword'));

        if (!$this->userService->isPasswordValid($this->getUser(), $currentPassword)) {
            $this->addFlash('warning', 'Please type your current password');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_ROUTE_INDEX);
        }

        $count = 0;

        if ($newsletters = $this->newsletterService->getAll()) {

            foreach ($newsletters as $newsletter) {
                $this->newsletterSubscriberService->deleteByNewsletter($newsletter);
                $this->newsletterService->delete($newsletter);
                $count++;
            }
        }

        $this->addFlash('success', sprintf('Newsletters has been deleted permanently [%s]', $count));

        return $this->redirectToRoute(self::ADMIN_NEWSLETTER_ROUTE_INDEX);
    }
}
