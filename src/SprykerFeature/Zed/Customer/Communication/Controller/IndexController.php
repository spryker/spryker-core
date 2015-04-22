<?php

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use SprykerFeature\Zed\Customer\Communication\CustomerDependencyContainer;

/**
 * @method CustomerDependencyContainer getDependencyContainer
 */
class IndexController extends AbstractController
{
    public function indexAction()
    {

    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function gridAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->createCustomerGrid($request);

        return $this->jsonResponse($grid->toArray());
    }
}
