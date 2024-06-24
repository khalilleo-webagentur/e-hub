<?php

declare(strict_types=1);

namespace App\Controller\Admin\Subscriber;

use App\Controller\Admin\AbstractBaseController;
use App\Service\SubscriberService;
use App\Service\Core\PaginationService;
use App\Traits\FormValidationTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/subscriber')]
class IndexController extends AbstractBaseController
{
    use FormValidationTrait;

    private const TWO_FACTOR_AUTH_ROUTE = 'app_profile_security_2fa_index';
    private const PAGE_NOT_FOUND_ROUTE_INDEX = 'app_home_status_code_404';
    private const ADMIN_NEWSLETTER_SUBSCIRBERS_ROUTE = 'app_admin_newsletter_subscribers_index';
    private const SEARCH_ROUTE = 'app_admin_newsletter_subscribers_serach_show';

    public function __construct(
        private readonly SubscriberService $subscriberService,
    ) {
    }

    #[Route('/home/{page?}', name: 'app_admin_newsletter_subscribers_index')]
    public function index(?string $page, PaginationService $paginationService): Response
    {
        $this->denyAccessUnlessGrantedRoleAdmin();

        $user = $this->getUser();

        $page = $this->validateNumber($page);

        if ($page < 0) {
            return $this->redirectToRoute(self::PAGE_NOT_FOUND_ROUTE_INDEX);
        }

        $limit = 8;

        $offset = $paginationService->getOffset($page, $limit);

        $subscribers = $this->subscriberService->getAllWithOffsetAndLimit($offset, $limit);

        $allSubscribers = $this->subscriberService->getAll();

        $pagination = $paginationService->paginate($allSubscribers, $page, $limit);

        return $this->render('admin/subscriber/index.html.twig', [
            'profile' => [],
            'notifications' => [],
            'subscribers' => $subscribers,
            'pagination' => $pagination,
            'paginationLimit' => $limit,
            'total' => count($allSubscribers),
            'searchRoute' => self::SEARCH_ROUTE,
        ]);
    }

    #[Route('/view/{id}', name: 'app_admin_newsletter_subscriber_show')]
    public function show(?string $id): Response
    {
        $this->denyAccessUnlessGrantedRoleAdmin();

        $user = $this->getUser();

        $subscriberId = $this->validateNumber($id);

        if ($subscriberId <= 0) {
            $this->addFlash('warning', 'Undefined ID');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCIRBERS_ROUTE);
        }

        $subscriber = $this->subscriberService->getById($subscriberId);

        if (!$subscriber) {
            $this->addFlash('warning', 'Subscriber could not be found');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCIRBERS_ROUTE);
        }

        $isSubscriberInBlackList = true;

        return $this->render('admin/subscriber/edit.html.twig', [
            'subscriber' => $subscriber,
            'profile' => [],
            'notifications' => [],
            'isSubscriberInBlackList' => $isSubscriberInBlackList,
        ]);
    }

    #[Route('/store', name: 'app_admin_newsletter_subscriber_store')]
    public function store(Request $request): Response
    {
        $this->denyAccessUnlessGrantedRoleAdmin();

        $subscriberId = $this->validateNumber($request->request->get('newsId'));

        if ($subscriberId <= 0) {
            $this->addFlash('warning', 'Undefined ID');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCIRBERS_ROUTE);
        }

        $subscriber = $this->subscriberService->getById($subscriberId);

        if (!$subscriber) {
            $this->addFlash('warning', 'Subscriber could not be found');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCIRBERS_ROUTE);
        }

        if ($this->validateCheckbox($request->request->get('delete'))) {
            $this->subscriberService->delete($subscriber);
            $this->addFlash('success', 'Subscriber has been deleted permanentelly');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCIRBERS_ROUTE);
        }

        $name = $this->validate($request->request->get('name'));
        $email = $this->validate($request->request->get('email'));

        if (empty($name) || empty($email)) {
            $this->addFlash('warning', 'Fields with star (*) are required');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCIRBERS_ROUTE);
        }

        $token = $this->validate($request->request->get('token'));
        $isSubscribed = $this->validateCheckbox($request->request->get('subscribed'));
        $received = $this->validateCheckbox($request->request->get('received'));

        $updatedAt = $this->validate($request->request->get('updated'));
        $createdAt = $this->validate($request->request->get('created'));

        $this->subscriberService->update($subscriber, $name, $email, $token, $isSubscribed, $received, $updatedAt, $createdAt);

        $this->addFlash('success', 'The rest of the changes has been saved');

        return $this->redirectToRoute(self::ADMIN_NEWSLETTER_SUBSCIRBERS_ROUTE);
    }
}
