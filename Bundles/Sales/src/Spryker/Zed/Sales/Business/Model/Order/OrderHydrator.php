<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemStateTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSalesAggregatorInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class OrderHydrator implements OrderHydratorInterface
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
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToSalesAggregatorInterface
     */
    protected $salesAggregatorFacade;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToSalesAggregatorInterface $salesAggregatorFacade
     */
    public function __construct(
        SalesQueryContainerInterface $queryContainer,
        SalesToOmsInterface $omsFacade,
        SalesToSalesAggregatorInterface $salesAggregatorFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->omsFacade = $omsFacade;
        $this->salesAggregatorFacade = $salesAggregatorFacade;
    }

    /**
     * @throws \Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderTransferFromPersistenceByIdSalesOrder($idSalesOrder)
    {
        $orderEntity = $this->queryContainer
            ->querySalesOrderDetails($idSalesOrder)
            ->findOne();

        if ($orderEntity === null) {
            throw new InvalidSalesOrderException();
        }

        $this->queryContainer->queryOrderItemsStateHistoriesOrderedByNewestState($orderEntity->getItems());

        $orderTransfer = $this->convertOrderDetailsEntityIntoTransfer($orderEntity);
        $orderTransfer = $this->addTotalOrderCount($orderTransfer);
        $orderTransfer = $this->salesAggregatorFacade->getOrderTotalByOrderTransfer($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
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
        $itemTransfer->setUnitGrossPrice($orderItemEntity->getGrossPrice());
        $this->addStateHistory($orderItemEntity, $itemTransfer);

        foreach ($orderItemEntity->getOptions() as $orderItemOptionEntity) {
            $productOptionsTransfer = new ProductOptionTransfer();
            $productOptionsTransfer->setUnitGrossPrice($orderItemOptionEntity->getGrossPrice());
            $productOptionsTransfer->fromArray($orderItemOptionEntity->toArray(), true);
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
        $shipmentMethodEntity = $orderEntity->getShipmentMethod();
        $shipmentMethodTransfer->fromArray($shipmentMethodEntity->toArray(), true);
        $shipmentMethodTransfer->setCarrierName($shipmentMethodEntity->getShipmentCarrier()->getName());
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
        foreach ($orderEntity->getExpenses() as $expenseEntity) {
            $expenseTransfer = new ExpenseTransfer();
            $expenseTransfer->fromArray($expenseEntity->toArray(), true);
            $expenseTransfer->setUnitGrossPrice($expenseEntity->getGrossPrice());
            $orderTransfer->addExpense($expenseTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function addTotalOrderCount(OrderTransfer $orderTransfer)
    {
        $totalOrderCount = $this->queryContainer
            ->querySalesOrder()
            ->findByFkCustomer($orderTransfer->getFkCustomer())->count();

        $orderTransfer->setTotalOrderCount($totalOrderCount);

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addStateHistory(SpySalesOrderItem $orderItemEntity, ItemTransfer $itemTransfer)
    {
        foreach ($orderItemEntity->getStateHistories() as $stateHistoryEntity) {
            $itemStateTransfer = new ItemStateTransfer();
            $itemStateTransfer->fromArray($stateHistoryEntity->toArray(), true);
            $itemStateTransfer->setState($stateHistoryEntity->getState()->getName());
            $itemStateTransfer->setIdSalesOrder($orderItemEntity->getFkSalesOrder());
            $itemTransfer->addStateHistory($itemStateTransfer);
        }
    }

}
