<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Service\Export\PersonalDataExport;
use App\Service\UserService;
use App\Traits\FormValidationTrait;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile/personal-data/u7k0s9bkngf6dba7')]
class PersonalDataController extends AbstractController
{
    use FormValidationTrait;

    private const APP_PROFILE = 'app_profile';

    public function __construct(
        private readonly UserService $userService
    ) {
    }

    #[Route('/change-username', name: 'app_profile_personal_data_username_store', methods: 'POST')]
    public function storeUsername(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        $newUsername = $this->validateUsernameAndReplaceSpace($request->request->get('iUsername'));

        if (!$newUsername) {
            $this->addFlash('warning', 'Username is required.');
            return $this->redirectToRoute(self::APP_PROFILE);
        }

        $user = $this->getUser();

        $this->userService->save($user->setName($newUsername));

        $this->addFlash('success', 'Username ha been changed.');

        return $this->redirectToRoute(self::APP_PROFILE);
    }

    #[Route('/download-personal-data', name: 'app_profile_personal_data_download', methods: ['GET', 'POST'])]
    public function downloadPersonalData(PersonalDataExport $PersonalDataExport): RedirectResponse|Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        try {
            $data = $PersonalDataExport->asJson($user);
        } catch (Exception $e) {
            $data = '';
        }

        if ($data === '') {
            $this->addFlash('notice', 'Personal data could not be exported.');
            return $this->redirectToRoute(self::APP_PROFILE);
        }

        return new Response($data);
    }

    #[Route('/delete', name: 'app_profile_personal_data_delete', methods: 'POST')]
    public function delete(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        $isConfirmed = $this->validateCheckbox($request->request->get('confirm'));

        if (!$isConfirmed) {
            $this->addFlash('warning', 'You have to confirm the deletion of your data.');
            return $this->redirectToRoute(self::APP_PROFILE);
        }

        $user = $this->getUser();

        $this->userService->save($user->setDeleted(true));

        $this->addFlash('success', 'Your account will be deleted with in 4 Months.');

        return $this->redirectToRoute(self::APP_PROFILE);
    }
}
