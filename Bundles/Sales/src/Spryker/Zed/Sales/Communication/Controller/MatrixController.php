<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\Communication\SalesCommunicationFactory;

/**
 * @method SalesCommunicationFactory getCommunicationFactory()
 */
class MatrixController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $matrix = $this->getCommunicationFactory()->getOmsFacade()->getOrderItemMatrix();

        return [
            'matrix' => $matrix,
        ];
    }

}
