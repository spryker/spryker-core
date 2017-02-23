<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

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
