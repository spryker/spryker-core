<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\ExpensesTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderItemsAndExpensesTransfer;
use Generated\Shared\Transfer\OrderItemsTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use SprykerFeature\Zed\Library\Copy;
use SprykerFeature\Zed\Oms\Business\OmsFacade;
use SprykerFeature\Zed\Sales\Business\Exception\InvalidSalesOrderException;
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

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @throws InvalidSalesOrderException
     *
     * @return OrderTransfer
     */
    public function getOrderDetails(OrderTransfer $orderTransfer)
    {
        $orderEntity = $this->queryContainer
            ->querySalesOrderDetails($orderTransfer->getIdSalesOrder(), $orderTransfer->getFkCustomer())
            ->findOne()
        ;

        if (null === $orderEntity) {
            throw new InvalidSalesOrderException();
        }

        $orderTransfer = $this->convertOrderDetailsEntityIntoTransfer($orderEntity);

        return $orderTransfer;
    }

    /**
     * @param $orderEntity
     *
     * @return OrderTransfer
     */
    protected function convertOrderDetailsEntityIntoTransfer(SpySalesOrder $orderEntity)
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($orderEntity->toArray(), true);

        $this->addOrderItemsToOrderTransfer($orderEntity, $orderTransfer);

        $this->addBillingAddressToOrderTransfer($orderEntity, $orderTransfer);

        $this->addShippingAddressToOrderTransfer($orderEntity, $orderTransfer);

        $this->addShipmentMethodToOrderTransfer($orderEntity, $orderTransfer);

        $this->addExpensesToOrderTransfer($orderEntity, $orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param SpySalesOrder $orderEntity
     * @param OrderTransfer $orderTransfer
     */
    protected function addOrderItemsToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
    {
        $orderItemsTransfer = new OrderItemsTransfer();
        foreach ($orderEntity->getItems() as $orderItemEntity) {
            $itemTransfer = new ItemTransfer();
            $itemTransfer->fromArray($orderItemEntity->toArray(), true);

            foreach ($orderItemEntity->getOptions() as $orderItemOption) {
                $productOptionsTransfer = new ProductOptionTransfer();
                $productOptionsTransfer->fromArray($orderItemOption->toArray(), true);
                $itemTransfer->addProductOption($productOptionsTransfer);
            }

            $orderItemsTransfer->addOrderItem($itemTransfer);
        }
        $orderTransfer->setItems($orderItemsTransfer);
    }

    /**
     * @param SpySalesOrder $orderEntity
     * @param OrderTransfer $orderTransfer
     */
    protected function addBillingAddressToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
    {
        $billingAddressTransfer = new AddressTransfer();
        $billingAddressTransfer->fromArray($orderEntity->getBillingAddress()->toArray(), true);
        $billingAddressTransfer->setIso2Code($orderEntity->getBillingAddress()->getCountry()->getIso2Code());
        $orderTransfer->setBillingAddress($billingAddressTransfer);
    }

    /**
     * @param SpySalesOrder $orderEntity
     * @param OrderTransfer $orderTransfer
     */
    protected function addShippingAddressToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
    {
        $shippingAddressTransfer = new AddressTransfer();
        $shippingAddressTransfer->fromArray($orderEntity->getShippingAddress()->toArray(), true);
        $shippingAddressTransfer->setIso2Code($orderEntity->getShippingAddress()->getCountry()->getIso2Code());
        $orderTransfer->setShippingAddress($shippingAddressTransfer);
    }

    /**
     * @param SpySalesOrder $orderEntity
     * @param OrderTransfer $orderTransfer
     */
    protected function addShipmentMethodToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
    {
        $shipmentMethodTransfer = new ShipmentMethodTransfer();
        $shipmentMethodTransfer->fromArray($orderEntity->getShipmentMethod()->toArray(), true);
        $orderTransfer->setShipmentMethod($shipmentMethodTransfer);
    }

    /**
     * @param SpySalesOrder $orderEntity
     * @param OrderTransfer $orderTransfer
     */
    protected function addExpensesToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
    {
        $expensesTransfer = new ExpensesTransfer();
        foreach ($orderEntity->getExpenses() as $expenseEntity) {
            $expenseTransfer = new ExpenseTransfer();
            $expenseTransfer->fromArray($expenseEntity->toArray(), true);
            $expensesTransfer->addCalculationExpense($expenseTransfer);
        }
        $orderTransfer->setExpenses($expensesTransfer);
    }

    /**
     * @param int $idRefund
     * @param OrderItemsAndExpensesTransfer $orderItemsAndExpensesTransfer
     */
    public function updateOrderItemsAndExpensesAfterRefund($idRefund, $orderItemsAndExpensesTransfer)
    {
        foreach ($orderItemsAndExpensesTransfer->getOrderItems() as $orderItem) {
            $orderItemEntity = $this->queryContainer
                ->querySalesOrderItem()
                ->filterByIdSalesOrderItem($orderItem->getIdSalesOrderItem())
                ->findOne();

            $orderItemEntity->setFkRefund($idRefund);
            $orderItemEntity->save();
        }
        foreach ($orderItemsAndExpensesTransfer->getExpenses()  as $expense) {
            $expenseEntity = $this->queryContainer
                ->querySalesExpense()
                ->filterByIdSalesExpense($expense->getIdExpense())
                ->findOne();

            $expenseEntity->setFkRefund($idRefund);
            $expenseEntity->save();
        }
    }

}
