<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business;

use Generated\Shared\Sales\ItemSplitResponseInterface;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\OrderItemsTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\PayonePaymentDetailTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Sales\SalesDependencyProvider;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 */
class SalesFacade extends AbstractFacade
{

    /**
     * @deprecated
     *
     * @param CommentTransfer $commentTransfer
     *
     * @return CommentTransfer
     */
    public function saveComment(CommentTransfer $commentTransfer)
    {
        $commentsManager = $this->getDependencyContainer()->createCommentsManager();
        $commentsManager->saveComment($commentTransfer);

        return $commentsManager->convertToTransfer();
    }

    /**
     * @param int $idOrder
     *
     * @return array
     */
    public function getArrayWithManualEvents($idOrder)
    {
        $orderManager = $this->getDependencyContainer()->createOrderDetailsManager();

        return $orderManager->getArrayWithManualEvents($idOrder);
    }

    /**
     * @param int $idOrder
     *
     * @return array
     */
    public function getAggregateState($idOrder)
    {
        $orderManager = $this->getDependencyContainer()->createOrderDetailsManager();

        return $orderManager->getAggregateState($idOrder);
    }

    /**
     * @deprecated
     *
     * @param int $idOrderItem
     *
     * @return array
     */
    public function getOrderItemManualEvents($idOrderItem)
    {
        return $this->getDependencyContainer()
            ->getProvidedDependency(SalesDependencyProvider::FACADE_OMS)
            ->getManualEvents($idOrderItem)
        ;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return OrderTransfer
     */
    public function getOrderByIdSalesOrder($idSalesOrder)
    {
        return $this->getDependencyContainer()
            ->createOrderManager()
            ->getOrderByIdSalesOrder($idSalesOrder)
        ;
    }

    /**
     * @param int $idOrderItem
     *
     * @deprecated
     *
     * @return OrderItemsTransfer
     */
    public function getOrderItemById($idOrderItem)
    {
        return $this->getDependencyContainer()
            ->getProvidedDependency(SalesDependencyProvider::FACADE_OMS)
            ->getOrderItemById($idOrderItem)
        ;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return OrderTransfer
     */
    public function saveOrder(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()
            ->createOrderManager()
            ->saveOrder($orderTransfer);
    }

    /**
     * @param int $idSalesOrderItem
     * @param int $quantity
     *
     * @return ItemSplitResponseInterface
     */
    public function splitSalesOrderItem($idSalesOrderItem, $quantity)
    {
        return $this->getDependencyContainer()->createOrderItemSplitter()->split($idSalesOrderItem, $quantity);
    }

    /**
     * @param OrderTransfer $orderTransfer
     * @param int $idOrder
     *
     * @return SpySalesOrder
     */
    public function updateOrderCustomer(OrderTransfer $orderTransfer, $idOrder)
    {
        return $this->getDependencyContainer()
            ->createOrderDetailsManager()
            ->updateOrderCustomer($orderTransfer, $idOrder)
        ;
    }

    /**
     * @param AddressTransfer $addressesTransfer
     * @param int $idAddress
     *
     * @return mixed
     */
    public function updateOrderAddress(AddressTransfer $addressesTransfer, $idAddress)
    {
        return $this->getDependencyContainer()
            ->createOrderDetailsManager()
            ->updateOrderAddress($addressesTransfer, $idAddress)
        ;
    }

    /**
     * @param string $idOrder
     *
     * @return array
     */
    public function getPaymentLogs($idOrder)
    {
        return $this->getDependencyContainer()
            ->createOrderDetailsManager()
            ->getPaymentLogs($idOrder)
            ;
    }

    /**
     * @param OrderListTransfer $orderListTransfer
     *
     * @return OrderListTransfer
     */
    public function getOrders(OrderListTransfer $orderListTransfer)
    {
        return $this->getDependencyContainer()
            ->createOrderManager()
            ->getOrders($orderListTransfer)
            ;
    }

    /**
     * @param PayonePaymentDetailTransfer $paymentDetailTransfer
     * @param int $idPayment
     *
     * @return mixed
     */
    public function updatePaymentDetail(PayonePaymentDetailTransfer $paymentDetailTransfer, $idPayment)
    {
        return $this->getDependencyContainer()
            ->createOrderManager()
            ->updatePaymentDetail($paymentDetailTransfer, $idPayment)
            ;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return OrderTransfer
     */
    public function getOrderDetails(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()
            ->createOrderDetailsManager()
            ->getOrderDetails($orderTransfer)
        ;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return RefundTransfer[]
     */
    public function getRefunds($idSalesOrder)
    {
        return $this->getDependencyContainer()
            ->getProvidedDependency(SalesDependencyProvider::FACADE_REFUND)
            ->getRefundsByIdSalesOrder($idSalesOrder)
        ;
    }

}
