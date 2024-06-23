<?php

declare(strict_types=1);

namespace App\Controller\Admin\Newsletter;

use App\Controller\Admin\AbstractBaseController;
use App\Service\SubscriberService;
use App\Traits\FormValidationTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/subscribers')]
class SearchController extends AbstractBaseController
{
    use FormValidationTrait;

    private const TWO_FACTOR_AUTH_ROUTE = 'app_profile_security_2fa_index';
    private const SUBSCRIBERS_ROUTE = 'app_admin_newsletter_subscribers_index';

    public function __construct(
        private readonly SubscriberService $newsletterService
    ) {
    }

    #[Route('/search', name: 'app_admin_newsletter_subscribers_serach_show', methods: 'POST')]
    public function show(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('authenticate', $this->validate($request->request->get('_csrf_token')))) {
            $this->addFlash('warning', 'CSRF Token is not valid.');
            return $this->redirectToRoute(self::SUBSCRIBERS_ROUTE);
        }

        $this->denyAccessUnlessGrantedRoleAdmin();

        $keyword = $this->validate($request->request->get('keyword'));

        $route = $this->validate($request->request->get('route'));

        if ($route !== self::SUBSCRIBERS_ROUTE) {
            $route = self::SUBSCRIBERS_ROUTE;
        }

        if (empty($keyword) || strlen($keyword) > 100) {
            return $this->redirectToRoute($route);
        }

        $user = $this->getUser();

        $subscribers = $this->newsletterService->search($keyword);

        return $this->render('admin/newsletter/subscriber/search.html.twig', [
            'subscribers' => $subscribers,
            'profile' => [],
            'notifications' => [],
            'route' => $route,
            'keyword' => $keyword,
        ]);
    }
}