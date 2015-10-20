<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Business;

use Generated\Shared\Refund\RefundInterface;
use Generated\Shared\Refund\OrderInterface;
use Generated\Shared\Transfer\RefundTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Refund\Business\RefundDependencyContainer as SprykerRefundDependencyContainer;
use SprykerFeature\Zed\Refund\Persistence\Propel\SpyRefund;
use SprykerFeature\Zed\Refund\RefundDependencyProvider;
use SprykerFeature\Zed\Sales\Persistence\Propel\Base\SpySalesOrderItem;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesExpense;

/**
 * @method SprykerRefundDependencyContainer getDependencyContainer()
 */
class RefundFacade extends AbstractFacade
{

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return int
     */
    public function calculateRefundableAmount(OrderInterface $orderTransfer)
    {
        return $this->getDependencyContainer()
            ->createRefundManager()
            ->calculateRefundableAmount($orderTransfer);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return RefundInterface[]
     */
    public function getRefundsByIdSalesOrder($idSalesOrder)
    {
        $refundQueryContainer = $this->getDependencyContainer()->getProvidedDependency(RefundDependencyProvider::QUERY_CONTAINER_REFUND);

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
     * @return OrderInterface
     */
    public function getOrderByIdSalesOrder($idSalesOrder)
    {
        return $this->getDependencyContainer()
            ->createSalesFacade()
            ->getOrderByIdSalesOrder($idSalesOrder)
        ;
    }

    /**
     * @param RefundInterface $refundTransfer
     *
     * @return RefundInterface
     */
    public function saveRefund(RefundInterface $refundTransfer)
    {
        return $this->getDependencyContainer()
            ->createRefundModel()
            ->saveRefund($refundTransfer)
        ;
    }

    /**
     * @param $idOrder
     *
     * @return SpySalesOrderItem[]
     */
    public function getRefundableItems($idOrder)
    {
        return $this->getDependencyContainer()->createRefundModel()->getRefundableItems($idOrder);
    }

    /**
     * @param $idOrder
     *
     * @return SpySalesExpense[]
     */
    public function getRefundableExpenses($idOrder)
    {
        return $this->getDependencyContainer()->createRefundModel()->getRefundableExpenses($idOrder);
    }

}
