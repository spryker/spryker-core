<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 */
class MatrixController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $matrix = $this->getFactory()->getOmsFacade()->getOrderItemMatrix();

        return [
            'matrix' => $matrix,
        ];
    }

}
