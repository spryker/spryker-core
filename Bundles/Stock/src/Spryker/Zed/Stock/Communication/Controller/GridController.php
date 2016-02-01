<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Stock\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Stock\Communication\StockCommunicationFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method StockCommunicationFactory getFactory()
 */
class GridController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function stockAction(Request $request)
    {
        $grid = $this->getFactory()->getStockGrid($request);

        return $this->jsonResponse($grid->renderData());
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function stockProductAction(Request $request)
    {
        $grid = $this->getFactory()->getStockProductGrid($request);

        return $this->jsonResponse($grid->renderData());
    }

}
