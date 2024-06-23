<?php

declare(strict_types=1);

namespace App\Controller\Admin\Users;

use App\Service\UserService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/dashboard/c2o5i0p7v6s5p4w5')]
class IndexController extends AbstractController
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
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $users = $this->userService->getAll();

        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/edit/{id}', name: 'app_admin_user_edit')]
    public function edit(?string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $user = $this->userService->getById($this->validateNumber($id));

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
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $name = $this->validate($request->request->get('name'));

        $email = $this->validateEmail($request->request->get('email'));

        if (!$name || !$email) {
            $this->addFlash('warning', 'Name and Email are required.');
            return $this->redirectToRoute(self::ADMIN_USERS_ROUTE);
        }

        $token = $this->validate($request->request->get('token'));

        $user = $this->userService->getById($this->validateNumber($id));

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
