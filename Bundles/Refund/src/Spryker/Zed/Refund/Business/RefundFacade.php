<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Refund\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Refund\Business\RefundBusinessFactory as SprykerRefundBusinessFactory;
use Orm\Zed\Refund\Persistence\SpyRefund;
use Spryker\Zed\Refund\RefundDependencyProvider;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesExpense;

/**
 * @method SprykerRefundBusinessFactory getFactory()
 */
class RefundFacade extends AbstractFacade
{

    /**
     * @param OrderTransfer $orderTransfer
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
     * @return RefundTransfer[]
     */
    public function getRefundsByIdSalesOrder($idSalesOrder)
    {
        $refundQueryContainer = $this->getFactory()->getProvidedDependency(RefundDependencyProvider::QUERY_CONTAINER_REFUND);

        $refunds = $refundQueryContainer->queryRefundsByIdSalesOrder($idSalesOrder)->find();

        $result = [];
        /** @var SpyRefund $refund */
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
     * @param RefundTransfer $refundTransfer
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
     * @param $idOrder
     *
     * @return SpySalesOrderItem[]
     */
    public function getRefundableItems($idOrder)
    {
        return $this->getFactory()->createRefundModel()->getRefundableItems($idOrder);
    }

    /**
     * @param $idOrder
     *
     * @return SpySalesExpense[]
     */
    public function getRefundableExpenses($idOrder)
    {
        return $this->getFactory()->createRefundModel()->getRefundableExpenses($idOrder);
    }

}
