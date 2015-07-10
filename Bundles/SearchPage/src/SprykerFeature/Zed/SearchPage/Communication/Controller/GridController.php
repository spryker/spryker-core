<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Communication\Controller;

use SprykerFeature\Zed\SearchPage\Communication\SearchPageDependencyContainer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SearchPageDependencyContainer getDependencyContainer()
 */
class GridController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function pageElementAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->createPageElementGrid($request);

        return $this->jsonResponse($grid->renderData());
    }

}
