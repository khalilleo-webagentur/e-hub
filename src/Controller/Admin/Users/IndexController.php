<?php

declare(strict_types=1);

namespace App\Controller\Admin\Users;

use App\Controller\Admin\AbstractBaseController;
use App\Service\UserService;
use App\Traits\FormValidationTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('u7x6g2q8r6/dashboard/c2o5i0p7v6s5p4w5')]
class IndexController extends AbstractBaseController
{
    use FormValidationTrait;

    private const ADMIN_USERS_ROUTE = 'app_admin_users_index';

    public function __construct(
        private readonly UserService $userService
    ) {
    }

    #[Route('/users/home', name: 'app_admin_users_index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $users = $this->isSuperAdmin()
            ? $this->userService->getAll()
            : [$this->getUser()];

        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/edit/{id}', name: 'app_admin_user_edit')]
    public function edit(?string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->isSuperAdmin()
            ? $this->userService->getById($this->validateNumber($id))
            : $this->getUser();

        if (!$user) {
            $this->addFlash('warning', 'User could not be found.');
            return $this->redirectToRoute(self::ADMIN_USERS_ROUTE);
        }

        return $this->render('admin/users/edit.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/store/{id}', name: 'app_admin_user_store', methods: 'POST')]
    public function store(?string $id, Request $request): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $name = $this->validate($request->request->get('name'));

        $email = $this->validateEmail($request->request->get('email'));

        if (!$name || !$email) {
            $this->addFlash('warning', 'Name and Email are required.');
            return $this->redirectToRoute(self::ADMIN_USERS_ROUTE);
        }

        $token = $this->validate($request->request->get('token'));

        $user = $this->isSuperAdmin()
            ? $this->userService->getById($this->validateNumber($id))
            : $this->getUser();

        if (!$user) {
            $this->addFlash('warning', 'User could not be found.');
            return $this->redirectToRoute(self::ADMIN_USERS_ROUTE);
        }

        $isVerified = $this->validateCheckbox($request->request->get('isVerified'));

        if (!$isVerified) {
            $token = null;
        }

        $isDeleted = $this->validateCheckbox($request->request->get('isDeleted'));

        if ($isDeleted) {
            $isVerified = false;
            $token = null;
        }

        $this->userService->save(
            $user
                ->setName($name)
                ->setEmail($email)
                ->setPassword($this->userService->encodePassword($email))
                ->setToken($token)
                ->setIsVerified($isVerified)
                ->setDeleted($isDeleted)
        );

        $this->addFlash('notice', 'Changes has been saved.');

        return $this->redirectToRoute(self::ADMIN_USERS_ROUTE);
    }
}
