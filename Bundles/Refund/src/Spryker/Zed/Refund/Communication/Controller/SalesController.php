<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Refund\Communication\RefundCommunicationFactory getFactory()
 * @method \Spryker\Zed\Refund\Persistence\RefundQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Refund\Business\RefundFacade getFacade()
 */
class SalesController extends AbstractController
{

    /**
     * @return array
     */
    public function listAction(Request $request)
    {
        $idSalesOrder = $request->request->get('id-sales-order');

        $table = $this->getFactory()->createRefundTable($this->getFacade());

        return $this->viewResponse(['refunds' => $table->render()]);
    }

}
