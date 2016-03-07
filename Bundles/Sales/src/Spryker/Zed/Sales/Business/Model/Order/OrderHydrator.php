<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemStateTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSalesAggregatorInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

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

        $orderTransfer = $this->applyOrderTransferHydrators($orderEntity);
        $orderTransfer = $this->salesAggregatorFacade->getOrderTotalByOrderTransfer($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function applyOrderTransferHydrators(SpySalesOrder $orderEntity)
    {
        $orderTransfer = $this->hydrateBaseOrderTransfer($orderEntity);

        $this->hydrateOrderItemsToOrderTransfer($orderEntity, $orderTransfer);
        $this->hydrateBillingAddressToOrderTransfer($orderEntity, $orderTransfer);
        $this->hydrateShippingAddressToOrderTransfer($orderEntity, $orderTransfer);
        $this->hydrateShipmentMethodToOrderTransfer($orderEntity, $orderTransfer);
        $this->hydrateExpensesToOrderTransfer($orderEntity, $orderTransfer);
        $orderTransfer->setTotalOrderCount(
            $this->getTotalCustomerOrderCount($orderTransfer->getFkCustomer())
        );

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function hydrateOrderItemsToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
    {
        foreach ($orderEntity->getItems() as $orderItemEntity) {
            $itemTransfer = $this->hydrateOrderItemTransfer($orderItemEntity);
            $orderTransfer->addItem($itemTransfer);
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateBaseOrderTransfer(SpySalesOrder $orderEntity)
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($orderEntity->toArray(), true);

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function hydrateOrderItemTransfer(SpySalesOrderItem $orderItemEntity)
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
    protected function hydrateBillingAddressToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
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
    protected function hydrateShippingAddressToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
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
    protected function hydrateShipmentMethodToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
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
    protected function hydrateExpensesToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
    {
        foreach ($orderEntity->getExpenses() as $expenseEntity) {
            $expenseTransfer = new ExpenseTransfer();
            $expenseTransfer->fromArray($expenseEntity->toArray(), true);
            $expenseTransfer->setUnitGrossPrice($expenseEntity->getGrossPrice());
            $orderTransfer->addExpense($expenseTransfer);
        }
    }

    /**
     * @param int $idCustomer
     *
     * @return int
     */
    protected function getTotalCustomerOrderCount($idCustomer)
    {
        $totalOrderCount = $this->queryContainer
            ->querySalesOrder()
            ->findByFkCustomer($idCustomer)->count();

        return $totalOrderCount;
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
