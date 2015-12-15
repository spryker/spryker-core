<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\Communication\SalesDependencyContainer;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 */
class MatrixController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $matrix = $this->getDependencyContainer()->getOmsFacade()->getOrderItemMatrix();

        return [
            'matrix' => $matrix,
        ];
    }

}
