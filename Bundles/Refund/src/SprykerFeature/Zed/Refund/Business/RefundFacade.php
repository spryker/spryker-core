<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Refund\Business\RefundDependencyContainer as SprykerRefundDependencyContainer;
use SprykerFeature\Zed\Refund\Persistence\Propel\SpyRefund;
use SprykerFeature\Zed\Refund\RefundDependencyProvider;

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
     * @return RefundTransfer[]
     */
    public function getRefundsByIdSalesOrder($idSalesOrder) {

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
     * @param RefundTransfer $refundTransfer
     *
     * @return RefundTransfer
     */
    public function saveRefund(RefundTransfer $refundTransfer)
    {
        return $this->getDependencyContainer()
            ->createRefundModel()
            ->saveRefund($refundTransfer)
        ;
    }

}
