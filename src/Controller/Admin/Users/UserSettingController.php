<?php

declare(strict_types=1);

namespace App\Controller\Admin\Users;

use App\Service\UserSettingService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/dashboard/user-setting/c4o6ihpsvn6s5w4wl')]
class UserSettingController extends AbstractController
{
    use FormValidationTrait;

    public function __construct(
        private readonly UserSettingService $userSettingService
    ) {
    }


    #[Route('/update-pagination-limit}', name: 'app_admin_user_setting_store_limit', methods: 'POST')]
    public function store(Request $request): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $uri = $this->validate($request->request->get('uri'));

        $limit = $this->validateNumber($request->request->get('limit'));

        if ($limit <= 0) {
            $this->addFlash('warning', 'Change cannot be saved.');
            return $this->redirectToRoute($uri);
        }

        if ($userSetting = $this->userSettingService->getByUser($this->getUser())) {
            $this->userSettingService->save(
                $userSetting->setPaginationLimit($limit)
            );
        }

        return $this->redirectToRoute($uri);
    }
}
