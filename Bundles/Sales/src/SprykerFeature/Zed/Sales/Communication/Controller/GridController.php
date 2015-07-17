<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Sales\Communication\SalesDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 */
class GridController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function indexAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->getSalesGrid($request);

        return $this->jsonResponse($grid->renderData());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function commentsAction(Request $request)
    {
        $grid = $this->getDependencyContainer()
            ->getCommentsGridByOrderId($request)
        ;

        $gridData = $grid->renderData();

        return $this->jsonResponse($gridData);
    }

}
