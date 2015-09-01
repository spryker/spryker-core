<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use SprykerFeature\Zed\Library\Copy;
use SprykerFeature\Zed\Oms\Business\OmsFacade;
use SprykerFeature\Zed\Sales\Dependency\Plugin\PaymentLogReceiverInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

class OrderDetailsManager
{

    /**
     * @var SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var OmsFacade
     */
    protected $omsFacade;

    /**
     * @var PaymentLogReceiverInterface[]
     */
    protected $logReceiverPluginStack;

    /**
     * @param SalesQueryContainerInterface $queryContainer
     * @param OmsFacade $omsFacade
     * @param array $logReceiverPluginStack
     */
    public function __construct(SalesQueryContainerInterface $queryContainer, OmsFacade $omsFacade, array $logReceiverPluginStack)
    {
        $this->queryContainer = $queryContainer;
        $this->omsFacade = $omsFacade;
        $this->logReceiverPluginStack = $logReceiverPluginStack;
    }

    /**
     * @param OrderTransfer $orderTransfer
     * @param int $idOrder
     *
     * @return SpySalesOrder
     */
    public function updateOrderCustomer(OrderTransfer $orderTransfer, $idOrder)
    {
        $orderEntity = $this->queryContainer
            ->querySalesOrderById($idOrder)
            ->findOne()
        ;

        Copy::transferToEntity($orderTransfer, $orderEntity);

        $orderEntity->save();

        return $orderEntity;
    }

    /**
     * @param AddressTransfer $addressTransfer
     * @param int $idAddress
     *
     * @return SpySalesOrderAddress
     */
    public function updateOrderAddress(AddressTransfer $addressTransfer, $idAddress)
    {
        $addressEntity = $this->queryContainer
            ->querySalesOrderAddressById($idAddress)
            ->findOne();

        Copy::transferToEntity($addressTransfer, $addressEntity);

        $addressEntity->save();

        return $addressEntity;
    }

    /**
     * @param $idOrder
     *
     * @return array
     */
    public function getArrayWithManualEvents($idOrder)
    {
        $orderItems = $this->queryContainer->querySalesOrderItemsByIdSalesOrder($idOrder)->find();

        $events = [];
        foreach ($orderItems as $orderItem) {
            $events[$orderItem->getIdSalesOrderItem()] = $this->omsFacade->getManualEvents($orderItem->getIdSalesOrderItem());
        }

        return $events;
    }

    /**
     * @param int $idOrder
     *
     * @return array
     */
    public function getAggregateState($idOrder)
    {
        $orderItems = $this->queryContainer->querySalesOrderItemsByIdSalesOrder($idOrder)->find();

        $status = [];
        foreach ($orderItems as $orderItem) {
            $status[$orderItem->getIdSalesOrderItem()] = $orderItem->getState()->getName();
        }

        return $status;
    }

    /**
     * @param string $idOrder
     *
     * @return array
     */
    public function getPaymentLogs($idOrder)
    {
        $orders = $this->queryContainer->querySalesOrderById($idOrder)
            ->find();

        $logs = [];
        foreach ($this->logReceiverPluginStack as $logReceiver) {
            $logs = $logReceiver->getPaymentLogs($orders);
        }

        return $logs;
    }

}
