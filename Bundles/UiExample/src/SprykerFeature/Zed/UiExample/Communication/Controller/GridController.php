<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\UiExample\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\UiExample\Communication\UiExampleDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method UiExampleDependencyContainer getDependencyContainer()
 */
class GridController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function uiExampleAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->getUiExampleGrid($request);

        return $this->jsonResponse($grid->renderData());
    }

}
