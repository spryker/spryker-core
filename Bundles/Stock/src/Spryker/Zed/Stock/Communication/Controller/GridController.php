<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Stock\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Stock\Communication\StockDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method StockDependencyContainer getCommunicationFactory()
 */
class GridController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function stockAction(Request $request)
    {
        $grid = $this->getCommunicationFactory()->getStockGrid($request);

        return $this->jsonResponse($grid->renderData());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function stockProductAction(Request $request)
    {
        $grid = $this->getCommunicationFactory()->getStockProductGrid($request);

        return $this->jsonResponse($grid->renderData());
    }

}
