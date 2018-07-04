<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemStateTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
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
     * @var array|\Spryker\Zed\Sales\Dependency\Plugin\HydrateOrderPluginInterface[]
     */
    protected $hydrateOrderPlugins;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     * @param \Spryker\Zed\Sales\Dependency\Plugin\HydrateOrderPluginInterface[] $hydrateOrderPlugins
     */
    public function __construct(
        SalesQueryContainerInterface $queryContainer,
        SalesToOmsInterface $omsFacade,
        array $hydrateOrderPlugins = []
    ) {
        $this->queryContainer = $queryContainer;
        $this->omsFacade = $omsFacade;
        $this->hydrateOrderPlugins = $hydrateOrderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getCustomerOrder(OrderTransfer $orderTransfer)
    {
        $orderEntity = $this->getOrderEntity($orderTransfer);

        $this->queryContainer->fillOrderItemsWithLatestStates($orderEntity->getItems());

        $orderTransfer = $this->createOrderTransfer($orderEntity);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function getOrderEntity(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireIdSalesOrder()
            ->requireFkCustomer();

        $orderEntity = $this->queryContainer
            ->querySalesOrderDetails($orderTransfer->getIdSalesOrder())
            ->filterByFkCustomer($orderTransfer->getFkCustomer())
            ->findOne();

        if ($orderEntity === null) {
            throw new InvalidSalesOrderException(sprintf(
                'Order could not be found for ID %s and customer reference %s',
                $orderTransfer->getIdSalesOrder(),
                $orderTransfer->getCustomerReference()
            ));
        }

        return $orderEntity;
    }

    /**
     * @param int $idSalesOrder
     *
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
            throw new InvalidSalesOrderException(
                sprintf(
                    'Order could not be found for ID %s',
                    $idSalesOrder
                )
            );
        }

        $this->queryContainer->fillOrderItemsWithLatestStates($orderEntity->getItems());

        $orderTransfer = $this->createOrderTransfer($orderEntity);

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

        $this->hydrateOrderTotals($orderEntity, $orderTransfer);
        $this->hydrateOrderItemsToOrderTransfer($orderEntity, $orderTransfer);
        $this->hydrateBillingAddressToOrderTransfer($orderEntity, $orderTransfer);
        $this->hydrateShippingAddressToOrderTransfer($orderEntity, $orderTransfer);
        $this->hydrateExpensesToOrderTransfer($orderEntity, $orderTransfer);
        $this->hydrateMissingCustomer($orderEntity, $orderTransfer);

        $orderTransfer->setTotalOrderCount(0);
        if ($orderTransfer->getCustomerReference()) {
            $customerReference = $orderTransfer->getCustomerReference();
            $totalCustomerOrderCount = $this->getTotalCustomerOrderCount($customerReference);
            $orderTransfer->setTotalOrderCount($totalCustomerOrderCount);
        }

        $uniqueProductQuantity = (int)$this->queryContainer
            ->queryCountUniqueProductsForOrder($orderEntity->getIdSalesOrder())
            ->count();

        $orderTransfer->setUniqueProductQuantity($uniqueProductQuantity);

        $orderTransfer = $this->executeHydrateOrderPlugins($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function executeHydrateOrderPlugins(OrderTransfer $orderTransfer)
    {
        foreach ($this->hydrateOrderPlugins as $hydrateOrderPlugin) {
            $orderTransfer = $hydrateOrderPlugin->hydrate($orderTransfer);
        }

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
        $orderTransfer->setCustomerReference($orderEntity->getCustomerReference());
        // Deprecated: Using FK to customer is obsolete, but needed to prevent BC break.
        $orderTransfer->setFkCustomer($orderEntity->getFkCustomer());

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
        $itemTransfer->setProcess($orderItemEntity->getProcess()->getName());

        $itemTransfer->setQuantity($orderItemEntity->getQuantity());
        $itemTransfer->setSumGrossPrice($orderItemEntity->getGrossPrice());
        $itemTransfer->setSumNetPrice($orderItemEntity->getNetPrice());
        $itemTransfer->setSumPrice($orderItemEntity->getPrice());
        $itemTransfer->setSumSubtotalAggregation($orderItemEntity->getSubtotalAggregation());
        $itemTransfer->setRefundableAmount($orderItemEntity->getRefundableAmount());
        $itemTransfer->setSumDiscountAmountAggregation($orderItemEntity->getDiscountAmountAggregation());
        $itemTransfer->setSumDiscountAmountFullAggregation($orderItemEntity->getDiscountAmountFullAggregation());
        $itemTransfer->setSumExpensePriceAggregation($orderItemEntity->getExpensePriceAggregation());
        $itemTransfer->setSumTaxAmount($orderItemEntity->getTaxAmount());
        $itemTransfer->setSumTaxAmountFullAggregation($orderItemEntity->getTaxAmountFullAggregation());
        $itemTransfer->setSumPriceToPayAggregation($orderItemEntity->getPriceToPayAggregation());

        // BC: Unit prices are populated for BC reasons only
        $this->hydrateOrderItemTransferUnitPrices($itemTransfer);

        $this->hydrateStateHistory($orderItemEntity, $itemTransfer);
        $this->hydrateCurrentSalesOrderItemState($orderItemEntity, $itemTransfer);

        return $itemTransfer;
    }

    /**
     * @deprecated Uses derived unit price which is accurate for quantity = 1 only
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function hydrateOrderItemTransferUnitPrices(ItemTransfer $itemTransfer)
    {
        $itemTransfer->setUnitGrossPrice((int)round($itemTransfer->getSumGrossPrice() / $itemTransfer->getQuantity()));
        $itemTransfer->setUnitNetPrice((int)round($itemTransfer->getSumNetPrice() / $itemTransfer->getQuantity()));
        $itemTransfer->setUnitPrice((int)round($itemTransfer->getSumPrice() / $itemTransfer->getQuantity()));
        $itemTransfer->setUnitSubtotalAggregation((int)round($itemTransfer->getSumSubtotalAggregation() / $itemTransfer->getQuantity()));
        $itemTransfer->setUnitDiscountAmountAggregation((int)round($itemTransfer->getSumDiscountAmountAggregation() / $itemTransfer->getQuantity()));
        $itemTransfer->setUnitDiscountAmountFullAggregation((int)round($itemTransfer->getSumDiscountAmountFullAggregation() / $itemTransfer->getQuantity()));
        $itemTransfer->setUnitExpensePriceAggregation((int)round($itemTransfer->getSumExpensePriceAggregation() / $itemTransfer->getQuantity()));
        $itemTransfer->setUnitTaxAmount((int)round($itemTransfer->getSumTaxAmount() / $itemTransfer->getQuantity()));
        $itemTransfer->setUnitTaxAmountFullAggregation((int)round($itemTransfer->getSumTaxAmountFullAggregation() / $itemTransfer->getQuantity()));
        $itemTransfer->setUnitPriceToPayAggregation((int)round($itemTransfer->getSumPriceToPayAggregation() / $itemTransfer->getQuantity()));
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function hydrateBillingAddressToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
    {
        $countryEntity = $orderEntity->getBillingAddress()->getCountry();

        $billingAddressTransfer = new AddressTransfer();
        $billingAddressTransfer->fromArray($orderEntity->getBillingAddress()->toArray(), true);
        $this->hydrateCountryEntityIntoAddressTransfer($billingAddressTransfer, $countryEntity);

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
        $countryEntity = $orderEntity->getShippingAddress()->getCountry();

        $shippingAddressTransfer = new AddressTransfer();
        $shippingAddressTransfer->fromArray($orderEntity->getShippingAddress()->toArray(), true);
        $this->hydrateCountryEntityIntoAddressTransfer($shippingAddressTransfer, $countryEntity);

        $orderTransfer->setShippingAddress($shippingAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Orm\Zed\Country\Persistence\SpyCountry $countryEntity
     *
     * @return void
     */
    protected function hydrateCountryEntityIntoAddressTransfer(
        AddressTransfer $addressTransfer,
        SpyCountry $countryEntity
    ) {
        $addressTransfer->setIso2Code($countryEntity->getIso2Code());
        $countryTransfer = (new CountryTransfer())->fromArray($countryEntity->toArray(), true);
        $addressTransfer->setCountry($countryTransfer);
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

            $expenseTransfer->setQuantity(1);
            $expenseTransfer->setSumGrossPrice($expenseEntity->getGrossPrice());
            $expenseTransfer->setSumNetPrice($expenseEntity->getNetPrice());
            $expenseTransfer->setSumPrice($expenseEntity->getPrice());
            $expenseTransfer->setSumPriceToPayAggregation($expenseEntity->getPriceToPayAggregation());
            $expenseTransfer->setSumTaxAmount($expenseEntity->getTaxAmount());

            // BC: Unit prices are populated for BC reasons only
            $this->hydrateExpenseTransferUnitPrices($expenseTransfer);

            $orderTransfer->addExpense($expenseTransfer);
        }
    }

    /**
     * @deprecated Uses derived unit price which is accurate for quantity = 1 only
     *
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return void
     */
    protected function hydrateExpenseTransferUnitPrices(ExpenseTransfer $expenseTransfer)
    {
        $expenseTransfer->setUnitGrossPrice((int)round($expenseTransfer->getSumGrossPrice() / $expenseTransfer->getQuantity()));
        $expenseTransfer->setUnitNetPrice((int)round($expenseTransfer->getSumNetPrice() / $expenseTransfer->getQuantity()));
        $expenseTransfer->setUnitPrice((int)round($expenseTransfer->getSumPrice() / $expenseTransfer->getQuantity()));
        $expenseTransfer->setUnitPriceToPayAggregation((int)round($expenseTransfer->getSumPriceToPayAggregation() / $expenseTransfer->getQuantity()));
        $expenseTransfer->setUnitTaxAmount((int)round($expenseTransfer->getSumTaxAmount() / $expenseTransfer->getQuantity()));
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function hydrateCurrentSalesOrderItemState(SpySalesOrderItem $orderItemEntity, ItemTransfer $itemTransfer)
    {
        $stateTransfer = new ItemStateTransfer();
        $stateTransfer->fromArray($orderItemEntity->getState()->toArray(), true);
        $stateTransfer->setIdSalesOrder($orderItemEntity->getIdSalesOrderItem());

        $lastStateHistory = $this->queryContainer
            ->queryOmsOrderItemStateHistoryByOrderItemIdAndOmsStateIdDesc(
                $orderItemEntity->getIdSalesOrderItem(),
                $orderItemEntity->getFkOmsOrderItemState()
            )->findOne();

        if ($lastStateHistory) {
            $stateTransfer->setCreatedAt($lastStateHistory->getCreatedAt());
        }

        $itemTransfer->setState($stateTransfer);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function hydrateStateHistory(SpySalesOrderItem $orderItemEntity, ItemTransfer $itemTransfer)
    {
        foreach ($orderItemEntity->getStateHistories() as $stateHistoryEntity) {
            $itemStateTransfer = new ItemStateTransfer();
            $itemStateTransfer->fromArray($stateHistoryEntity->toArray(), true);
            $itemStateTransfer->setName($stateHistoryEntity->getState()->getName());
            $itemStateTransfer->setIdSalesOrder($orderItemEntity->getFkSalesOrder());
            $itemTransfer->addStateHistory($itemStateTransfer);
        }
    }

    /**
     * @param int|null $customerReference
     *
     * @return int
     */
    protected function getTotalCustomerOrderCount($customerReference)
    {
        if ($customerReference === null) {
            return 0;
        }

        $totalOrderCount = $this->queryContainer
            ->querySalesOrder()
            ->filterByCustomerReference($customerReference)
            ->count();

        return $totalOrderCount;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity $orderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer(SpySalesOrder $orderEntity)
    {
        return $this->applyOrderTransferHydrators($orderEntity);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function hydrateOrderTotals(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
    {
        $salesOrderTotalsEntity = $orderEntity->getLastOrderTotals();

        if (!$salesOrderTotalsEntity) {
            return;
        }

        $totalsTransfer = new TotalsTransfer();

        $taxTotalTransfer = new TaxTotalTransfer();
        $taxTotalTransfer->setAmount($salesOrderTotalsEntity->getTaxTotal());
        $totalsTransfer->setTaxTotal($taxTotalTransfer);

        $totalsTransfer->setExpenseTotal($salesOrderTotalsEntity->getOrderExpenseTotal());
        $totalsTransfer->setRefundTotal($salesOrderTotalsEntity->getRefundTotal());
        $totalsTransfer->setGrandTotal($salesOrderTotalsEntity->getGrandTotal());
        $totalsTransfer->setSubtotal($salesOrderTotalsEntity->getSubtotal());
        $totalsTransfer->setDiscountTotal($salesOrderTotalsEntity->getDiscountTotal());
        $totalsTransfer->setCanceledTotal($salesOrderTotalsEntity->getCanceledTotal());

        $orderTransfer->setTotals($totalsTransfer);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function hydrateMissingCustomer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
    {
        if (!$orderEntity->getCustomer()) {
            $orderTransfer->setCustomerReference(null);
            // Deprecated: Using FK to customer is obsolete, but needed to prevent BC break.
            $orderTransfer->setFkCustomer(null);
        }
    }
}
