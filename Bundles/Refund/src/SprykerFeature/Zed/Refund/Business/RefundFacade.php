<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundCommentTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Refund\Business\RefundDependencyContainer as SprykerRefundDependencyContainer;
use SprykerFeature\Zed\Refund\RefundDependencyProvider;
use SprykerFeature\Zed\Sales\Business\SalesFacade;

/**
 * @method SprykerRefundDependencyContainer getDependencyContainer()
 */
class RefundFacade extends AbstractFacade
{

    /**
     * @param $orderItems
     * @param $orderEntity
     *
     * @return int
     */
    public function calculateAmount($orderItems, $orderEntity)
    {
        $this->getDependencyContainer()->getRefundCalculator()->calculateAmount($orderItems, $orderEntity);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return OrderTransfer
     */
    public function getOrderByIdSalesOrder($idSalesOrder)
    {
        return $this->getDependencyContainer()
            ->getProvidedDependency(RefundDependencyProvider::FACADE_SALES)
            ->getOrderByIdSalesOrder($idSalesOrder)
        ;
    }

    /**
     * @param RefundCommentTransfer $refundCommentTransfer
     *
     * @return RefundCommentTransfer
     */
    public function saveRefundComment(RefundCommentTransfer $refundCommentTransfer)
    {
        return $this->getDependencyContainer()
            ->createRefundCommentModel()
            ->saveRefundComment($refundCommentTransfer)
        ;
    }

}
