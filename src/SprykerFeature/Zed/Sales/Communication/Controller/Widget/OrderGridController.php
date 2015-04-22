<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller\Widget;

use SprykerFeature\Zed\Library\Controller\Action\AbstractGridController;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Sales\Communication\Grid\Order\DataSource;
use SprykerFeature\Zed\Sales\Communication\Grid\Order as OrderGrid;

class OrderGridController extends AbstractGridController
{

    /**
     * @param Request $request
     * @return mixed|OrderGrid
     */
    protected function initializeGrid(Request $request)
    {
        return new OrderGrid(new DataSource());
    }


}
