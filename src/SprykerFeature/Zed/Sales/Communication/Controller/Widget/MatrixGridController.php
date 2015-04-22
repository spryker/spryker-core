<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller\Widget;

use SprykerFeature\Zed\Library\Controller\Action\AbstractGridController;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Sales\Communication\Grid\Matrix\DataSource;
use SprykerFeature\Zed\Sales\Communication\Grid\Matrix as MatrixGrid;

class MatrixGridController extends AbstractGridController
{

    /**
     * @param Request $request
     * @return mixed|MatrixGrid
     */
    protected function initializeGrid(Request $request)
    {
        return new MatrixGrid(new DataSource());
    }


}
