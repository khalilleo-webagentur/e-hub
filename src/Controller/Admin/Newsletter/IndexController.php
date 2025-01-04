<?php

declare(strict_types=1);

namespace App\Controller\Admin\Newsletter;

use App\Controller\Admin\AbstractBaseController;
use App\Service\NewsletterService;
use App\Service\NewsletterSubscriberService;
use App\Traits\FormValidationTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/newsletter')]
class IndexController extends AbstractBaseController
{
    use FormValidationTrait;

    private const TWO_FACTOR_AUTH_ROUTE = 'app_profile_security_2fa_index';
    private const PAGE_NOT_FOUND_ROUTE_INDEX = 'app_home_status_code_404';
    private const ADMIN_NEWSLETTER_ROUTE_INDEX = 'app_admin_newsletter_index';

    public function __construct(
        private readonly NewsletterService           $newsletterService,
        private readonly NewsletterSubscriberService $newsletterSubscriberService
    ) {
    }

    #[Route('/home', name: 'app_admin_newsletter_index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGrantedRoleAdmin();

        $user = $this->getUser();

        $newsletters = $this->newsletterService->getAll();

        return $this->render('admin/newsletter/index.html.twig', [
            'profile' => [],
            'notifications' => [],
            'newsletters' => $newsletters,
            'total' => count($newsletters),
        ]);
    }

    #[Route('/add', name: 'app_admin_newsletter_new', methods: 'POST')]
    public function new(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('authenticate', $this->validate($request->request->get('_csrf_token')))) {
            $this->addFlash('warning', 'CSRF Token is not valid.');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_ROUTE_INDEX);
        }

        $this->denyAccessUnlessGrantedRoleAdmin();

        $title = $this->validate($request->request->get('title'));
        $content = $this->validateTextarea($request->request->get('content'), true);
        $canBePublished = $this->validateCheckbox($request->request->get('canBePublished'));

        if ($canBePublished) {
            $this->newsletterService->deactiveAllOthersNewsletter();
        }

        if ($title && $content) {

            $this->newsletterService->createOrupdate(null, $title, $content, $canBePublished);

            $this->addFlash('success', 'Newsletter has been added.');

            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_ROUTE_INDEX);
        }

        $this->addFlash('warning', 'Hmm. Unable to add Newsletter.');

        return $this->redirectToRoute(self::ADMIN_NEWSLETTER_ROUTE_INDEX);
    }

    #[Route('/view/{id}', name: 'app_admin_newsletter_show')]
    public function show(string $id): Response
    {
        $this->denyAccessUnlessGrantedRoleAdmin();

        $user = $this->getUser();


        $newsletter = $this->newsletterService->getById(
            $this->validateNumber($id)
        );

        if (!$newsletter) {
            $this->addFlash('warning', 'Newsletter could not be found');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_ROUTE_INDEX);
        }

        return $this->render('admin/newsletter/edit.html.twig', [
            'newsletter' => $newsletter,
            'profile' => [],
            'notifications' => [],
        ]);
    }

    #[Route('/store', name: 'app_admin_newsletter_store', methods: 'POST')]
    public function store(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('authenticate', $this->validate($request->request->get('_csrf_token')))) {
            $this->addFlash('warning', 'CSRF Token is not valid.');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_ROUTE_INDEX);
        }

        $this->denyAccessUnlessGrantedRoleAdmin();

        $newsletter = $this->newsletterService->getById(
            $this->validateNumber($request->request->get('id'))
        );

        if (!$newsletter) {
            $this->addFlash('warning', 'Newsletter could not be found');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_ROUTE_INDEX);
        }

        if ($this->validateCheckbox($request->request->get('delete'))) {
            $this->newsletterSubscriberService->deleteByNewsletter($newsletter);
            $this->newsletterService->delete($newsletter);
            $this->addFlash('success', 'Newsletter has been deleted permanentelly');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_ROUTE_INDEX);
        }

        $title = $this->validate($request->request->get('title'));
        $content = $this->validateTextarea($request->request->get('content'), true);

        if (!$title || !$content) {
            $this->addFlash('warning', 'Fields with star (*) are required');
            return $this->redirectToRoute(self::ADMIN_NEWSLETTER_ROUTE_INDEX);
        }

        $canBePublished = $this->validateCheckbox($request->request->get('canBePublished'));

        if ($canBePublished) {
            $this->newsletterService->deactiveAllOthersNewsletter();
        }

        $newsletter = $this->newsletterService->createOrupdate(
            $newsletter,
            $title,
            $content,
            $canBePublished,
            $this->validateCheckbox($request->request->get('isSent')),
            $this->validate($request->request->get('token'))
        );

        $this->addFlash('success', 'Changes has been saved');

        return $this->redirectToRoute(self::ADMIN_NEWSLETTER_ROUTE_INDEX);
    }
}
