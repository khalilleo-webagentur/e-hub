<?php

declare(strict_types=1);

namespace App\Controller\Admin\Subscriber;

use App\Controller\Admin\AbstractBaseController;
use App\Service\Import\SubscriberImporter;
use App\Service\UserService;
use App\Traits\FormValidationTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/u7x6g2q8r6/subscribers/import')]
class ImportController extends AbstractBaseController
{
    use FormValidationTrait;

    private const ADMIN_SUBSCRIBERS_ROUTE_INDEX = 'app_admin_newsletter_subscribers_index';

    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    #[Route('/import-subscribers-data', name: 'app_admin_newsletter_subscribers_import', methods: 'POST')]
    public function import(
        Request                 $request,
        SubscriberImporter $subscriberImportService
    ): RedirectResponse
    {

        if (!$this->isCsrfTokenValid('authenticate', $this->validate($request->request->get('_csrf_token')))) {
            $this->addFlash('warning', 'CSRF Token is not valid.');
            return $this->redirectToRoute(self::ADMIN_SUBSCRIBERS_ROUTE_INDEX);
        }

        $this->denyAccessUnlessGrantedRoleUser();

        /** @var UploadedFile $file */
        $file = $request->files->get('type');

        if (!$file) {
            $this->addFlash('warning', 'Json file could not be read.');
            return $this->redirectToRoute(self::ADMIN_SUBSCRIBERS_ROUTE_INDEX);
        }

        $user = $this->getUser();

        if ($file->getClientOriginalExtension() === 'csv') {

            $countImportedSubscribers = $subscriberImportService
                ->setSubscribed($this->validateCheckbox($request->request->get('subscribed')))
                ->setSeparator($this->escape($request->request->get('seperator'), false))
                ->fromCsv($file, $user);

            $this->addFlash(
                'success',
                sprintf('Subscribers [%s] has been imported.', $countImportedSubscribers)
            );

            return $this->redirectToRoute(self::ADMIN_SUBSCRIBERS_ROUTE_INDEX);
        }

        if ($file->getClientOriginalExtension() === 'json') {

            $countImportedSubscribers = $subscriberImportService
                ->setSubscribed($this->validateCheckbox($request->request->get('subscribed')))
                ->fromJson($file, $user);

            $this->addFlash(
                'success',
                sprintf('Subscribers [%s] has been imported.', $countImportedSubscribers)
            );

            return $this->redirectToRoute(self::ADMIN_SUBSCRIBERS_ROUTE_INDEX);
        }

        $this->addFlash('warning', 'Subscribers could not be imported.');

        return $this->redirectToRoute(self::ADMIN_SUBSCRIBERS_ROUTE_INDEX);
    }
}
