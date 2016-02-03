<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\ExpensesTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Library\Copy;
use Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

class OrderDetailsManager
{

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Plugin\PaymentLogReceiverInterface[]
     */
    protected $logReceiverPluginStack;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     * @param array $logReceiverPluginStack
     */
    public function __construct(SalesQueryContainerInterface $queryContainer, SalesToOmsInterface $omsFacade, array $logReceiverPluginStack)
    {
        $this->queryContainer = $queryContainer;
        $this->omsFacade = $omsFacade;
        $this->logReceiverPluginStack = $logReceiverPluginStack;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function updateOrderCustomer(OrderTransfer $orderTransfer, $idOrder)
    {
        $orderEntity = $this->queryContainer
            ->querySalesOrderById($idOrder)
            ->findOne();

        Copy::transferToEntity($orderTransfer, $orderEntity);

        $orderEntity->save();

        return $orderEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param int $idAddress
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
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
     * @param int $idOrder
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderDetails(OrderTransfer $orderTransfer)
    {
        $orderEntity = $this->queryContainer
            ->querySalesOrderDetails($orderTransfer->getIdSalesOrder(), $orderTransfer->getFkCustomer())
            ->findOne();

        if ($orderEntity === null) {
            throw new InvalidSalesOrderException();
        }

        $orderTransfer = $this->convertOrderDetailsEntityIntoTransfer($orderEntity);

        return $orderTransfer;
    }

    /**
     * @param $orderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
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
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addOrderItemsToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
    {
        foreach ($orderEntity->getItems() as $orderItemEntity) {
            $itemTransfer = $this->createOrderItemTransfer($orderItemEntity);
            $orderTransfer->addItem($itemTransfer);
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createOrderItemTransfer(SpySalesOrderItem $orderItemEntity)
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->fromArray($orderItemEntity->toArray(), true);

        foreach ($orderItemEntity->getOptions() as $orderItemOption) {
            $productOptionsTransfer = new ProductOptionTransfer();
            $productOptionsTransfer->fromArray($orderItemOption->toArray(), true);
            $itemTransfer->addProductOption($productOptionsTransfer);
        }

        return $itemTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addBillingAddressToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
    {
        $billingAddressTransfer = new AddressTransfer();
        $billingAddressTransfer->fromArray($orderEntity->getBillingAddress()->toArray(), true);
        $billingAddressTransfer->setIso2Code($orderEntity->getBillingAddress()->getCountry()->getIso2Code());
        $orderTransfer->setBillingAddress($billingAddressTransfer);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addShippingAddressToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
    {
        $shippingAddressTransfer = new AddressTransfer();
        $shippingAddressTransfer->fromArray($orderEntity->getShippingAddress()->toArray(), true);
        $shippingAddressTransfer->setIso2Code($orderEntity->getShippingAddress()->getCountry()->getIso2Code());
        $orderTransfer->setShippingAddress($shippingAddressTransfer);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addShipmentMethodToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
    {
        $shipmentMethodTransfer = new ShipmentMethodTransfer();
        $shipmentMethodTransfer->fromArray($orderEntity->getShipmentMethod()->toArray(), true);
        $orderTransfer->setShipmentMethod($shipmentMethodTransfer);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
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
     * @param \Generated\Shared\Transfer\OrderItemsAndExpensesTransfer $orderItemsAndExpensesTransfer
     *
     * @return void
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
        foreach ($orderItemsAndExpensesTransfer->getExpenses() as $expense) {
            $expenseEntity = $this->queryContainer
                ->querySalesExpense()
                ->filterByIdSalesExpense($expense->getIdExpense())
                ->findOne();

            $expenseEntity->setFkRefund($idRefund);
            $expenseEntity->save();
        }
    }

}
