<?php

declare(strict_types=1);

namespace App\Controller\Admin\Newsletter;

use App\Controller\Admin\AbstractBaseController;
use App\Service\NewsletterService;
use App\Service\SubscriberService;
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
        private readonly UserService $userService,
        private readonly NewsletterService $newsletterService,
        private readonly SubscriberService $subscriberService
    ) {
    }

    #[Route('/change-subscribed-status', name: 'app_admin_newsletter_subscriber_job_subscriber_store', methods: 'POST')]
    public function switchIsPublished(Request $request): RedirectResponse
    {
        if (!$this->isCsrfTokenValid('authenticate', $this->validate($request->request->get('_csrf_token')))) {
            $this->addFlash('warning', 'CSRF Token is not valid.');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCRIBERS_ROUTE_INDEX);
        }

        $this->denyAccessUnlessGrantedRoleAdmin();

        $id = $this->validateNumber($request->request->get('id'));

        if ($subscriber = $this->subscriberService->getById($id)) {
            $isSubscribed = !$this->validateCheckbox($request->request->get('isSubscribed'));
            $this->subscriberService->save($subscriber->setIsSubscribed($isSubscribed));
            $this->addFlash(
                'notice',
                sprintf('Subscriber (%s) is being %s', ucfirst($subscriber->getName()), $isSubscribed ? 'subscribed.' : 'un-subscribed.')
            );

            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCRIBERS_ROUTE_INDEX);
        }

        $this->addFlash('warning', 'Subscriber could not be found!');

        return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCRIBERS_ROUTE_INDEX);
    }

    #[Route('/reset-receiver', name: 'app_admin_newsletter_job_subscribers_reset_receiver', methods: 'POST')]
    public function resetReceiver(Request $request): RedirectResponse
    {
        if (!$this->isCsrfTokenValid('authenticate', $this->validate($request->request->get('_csrf_token')))) {
            $this->addFlash('warning', 'CSRF Token is not valid.');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCRIBERS_ROUTE_INDEX);
        }

        $this->denyAccessUnlessGrantedRoleAdmin();

        if ($subscribers = $this->subscriberService->getSubescribedAndReceivedNewsletter()) {

            $count = 0;

            foreach ($subscribers as $subscriber) {
                $this->subscriberService->updateHasReceived($subscriber, false);
                $count++;
            }

            $this->addFlash(
                'success',
                sprintf('Subscribers will get newsletters again [%s]', $count)
            );

            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCRIBERS_ROUTE_INDEX);
        }

        $this->addFlash('notice', 'No Data has been found');

        return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCRIBERS_ROUTE_INDEX);
    }

    #[Route('/delete-all-older-than-six-months', name: 'app_admin_newsletter_job_subscribers_delete_by_date', methods: 'POST')]
    public function delete(Request $request): RedirectResponse
    {
        if (!$this->isCsrfTokenValid('authenticate', $this->validate($request->request->get('_csrf_token')))) {
            $this->addFlash('warning', 'CSRF Token is not valid.');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCRIBERS_ROUTE_INDEX);
        }

        $this->denyAccessUnlessGrantedRoleAdmin();

        $this->addFlash('notice', 'No Data has been found');

        return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCRIBERS_ROUTE_INDEX);
    }

    #[Route('/delete-all-subscribers-permanently', name: 'app_admin_newsletter_job_subscribers_delete_all', methods: 'POST')]
    public function deleteSubscribers(Request $request): RedirectResponse
    {
        if (!$this->isCsrfTokenValid('authenticate', $this->validate($request->request->get('_csrf_token')))) {
            $this->addFlash('warning', 'CSRF Token is not valid.');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCRIBERS_ROUTE_INDEX);
        }

        $this->denyAccessUnlessGrantedRoleAdmin();

        $currentPassword = $this->validate($request->request->get('currentPassword'));

        if (!$this->userService->isPasswordValid($this->getUser(), $currentPassword)) {
            $this->addFlash('warning', 'Please type your current password');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCRIBERS_ROUTE_INDEX);
        }

        $this->validateCheckbox($request->request->get('justInActive'))
            ? $subscribers = $this->subscriberService->getUnActiveSubscribers()
            : $subscribers = $this->subscriberService->getAll();

        $count = 0;

        if (count($subscribers) > 0) {
            foreach ($subscribers as $subscriber) {
                $this->subscriberService->delete($subscriber);
                $count++;
            }
        }

        $this->addFlash(
            'success',
            sprintf('Subscribers has been deleted permanently [%s]', $count)
        );

        return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCRIBERS_ROUTE_INDEX);
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
                $this->newsletterService->delete($newsletter);
                $count++;
            }
        }

        $this->addFlash(
            'success',
            sprintf('Newsletters has been deleted permanently [%s]', $count)
        );

        return $this->redirectToRoute(self::ADMIN_NEWSLETTER_ROUTE_INDEX);
    }
}
