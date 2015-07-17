<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Category\Communication\CategoryDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CategoryDependencyContainer getDependencyContainer()
 */
class GridController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function categoryAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->createCategoryGrid($request);

        return $this->jsonResponse($grid->renderData());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function categoryNodeAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->createCategoryNodeGrid($request);

        return $this->jsonResponse($grid->renderData());
    }

}
