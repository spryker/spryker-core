<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\Refund\Communication\RefundCommunicationFactory getFactory()
 * @method \Spryker\Zed\Refund\Business\RefundFacade getFacade()
 * @method \Spryker\Zed\Refund\Persistence\RefundQueryContainer getQueryContainer()
 */
class DetailsController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idRefund = $request->get('id-refund');

        $refundEntity = $this->getQueryContainer()
            ->queryRefundByIdRefund($idRefund)
            ->findOne();

        if ($refundEntity === null) {
            throw new NotFoundHttpException('Record not found');
        }

        $orderItems = $this->getFactory()->getSalesQueryContainer()
            ->querySalesOrderItem()
            ->filterByFkRefund($idRefund)
            ->find();

        $expenses = $this->getFactory()->getSalesQueryContainer()
            ->querySalesExpense()
            ->filterByFkRefund($idRefund)
            ->find();

        return [
            'idRefund' => $idRefund,
            'refund' => $refundEntity,
            'orderItems' => $orderItems,
            'expenses' => $expenses,
        ];
    }

}
