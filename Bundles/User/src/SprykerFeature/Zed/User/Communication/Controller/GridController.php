<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\User\Communication\UserDependencyContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method UserDependencyContainer getDependencyContainer()
 */
class GridController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->getUserGrid($request);

        return $this->jsonResponse($grid->renderData());
    }

}
