<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Refund\RefundDependencyProvider;

/**
 * @method \Spryker\Zed\Refund\Business\RefundBusinessFactory getFactory()
 */
class RefundFacade extends AbstractFacade implements RefundFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    public function calculateRefundableAmount(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()
            ->createRefundManager()
            ->calculateRefundableAmount($orderTransfer);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\RefundTransfer[]
     */
    public function getRefundsByIdSalesOrder($idSalesOrder)
    {
        $refundQueryContainer = $this->getFactory()->getProvidedDependency(RefundDependencyProvider::QUERY_CONTAINER_REFUND);

        $refunds = $refundQueryContainer->queryRefundsByIdSalesOrder($idSalesOrder)->find();

        $result = [];
        /** @var \Orm\Zed\Refund\Persistence\SpyRefund $refund */
        foreach ($refunds as $refund) {
            $result[] = (new RefundTransfer())->fromArray($refund->toArray(), true);
        }

        return $result;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()
            ->getSalesFacade()
            ->getOrderByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function saveRefund(RefundTransfer $refundTransfer)
    {
        return $this->getFactory()
            ->createRefundModel()
            ->saveRefund($refundTransfer);
    }

    /**
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem[]
     */
    public function getRefundableItems($idOrder)
    {
        return $this->getFactory()->createRefundModel()->getRefundableItems($idOrder);
    }

    /**
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense[]
     */
    public function getRefundableExpenses($idOrder)
    {
        return $this->getFactory()->createRefundModel()->getRefundableExpenses($idOrder);
    }

}
