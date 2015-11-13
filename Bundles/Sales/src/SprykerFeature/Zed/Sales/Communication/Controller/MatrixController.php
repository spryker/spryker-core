<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Sales\Communication\SalesDependencyContainer;

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

        die(dump($matrix));
        return [
            'matrix' => $matrix,
        ];
    }

}
