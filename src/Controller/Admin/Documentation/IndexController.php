<?php

declare(strict_types=1);

namespace App\Controller\Admin\Documentation;

use App\Controller\Admin\AbstractBaseController;
use App\Traits\FormValidationTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/documentation')]
class IndexController extends AbstractBaseController
{
    use FormValidationTrait;

    private const ADMIN_DOCUMENTATION_ROUTE_INDEX = 'app_admin_documentation_index';

    #[Route('/home', name: 'app_admin_documentation_index')]
    public function index(): RedirectResponse|Response
    {
        $this->denyAccessUnlessGrantedRoleAdmin();

        return $this->render('admin/documentation/index.html.twig');
    }
}
