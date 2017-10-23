<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Refund\Communication\RefundCommunicationFactory getFactory()
 * @method \Spryker\Zed\Refund\Persistence\RefundQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Refund\Business\RefundFacade getFacade()
 */
class SalesController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $refundQuery = $this->getQueryContainer()->queryRefunds();
        $orderTransfer = $this->getOrderTransfer($request);
        $refunds = $refundQuery->filterByFkSalesOrder($orderTransfer->getIdSalesOrder())->find();

        return $this->viewResponse([
            'refunds' => $refunds,
            'order' => $orderTransfer,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(Request $request)
    {
        return $request->request->get('orderTransfer');
    }
}
