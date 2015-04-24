<?php

namespace SprykerFeature\Zed\Acl\Communication\Controller;

use SprykerFeature\Zed\Acl\Communication\AclDependencyContainer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method AclDependencyContainer getDependencyContainer()
 */
class UsersController extends AbstractController
{
    const USER_LIST_URL = '/acl/users';

    /**
     *
     */
    public function indexAction()
    {

    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->createUserGrid($request);

        return $this->jsonResponse($grid->renderData());
    }
}
