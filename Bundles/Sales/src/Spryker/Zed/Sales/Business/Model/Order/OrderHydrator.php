<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemStateTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Spryker\Zed\Sales\SalesConfig;

/**
 * @deprecated Use {@link \Spryker\Zed\Sales\Business\Order\OrderHydrator} instead.
 */
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
     * @var \Spryker\Zed\Sales\SalesConfig
     */
    protected $salesConfig;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface[]
     */
    protected $hydrateOrderPlugins;

    /**
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface[]
     */
    protected $orderItemExpanderPlugins;

    /**
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\CustomerOrderAccessCheckPluginInterface[]
     */
    protected $customerOrderAccessCheckPlugins;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     * @param \Spryker\Zed\Sales\SalesConfig $salesConfig
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerInterface $customerFacade
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface[] $hydrateOrderPlugins
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface[] $orderItemExpanderPlugins
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\CustomerOrderAccessCheckPluginInterface[] $customerOrderAccessCheckPlugins
     */
    public function __construct(
        SalesQueryContainerInterface $queryContainer,
        SalesToOmsInterface $omsFacade,
        SalesConfig $salesConfig,
        SalesToCustomerInterface $customerFacade,
        array $hydrateOrderPlugins = [],
        array $orderItemExpanderPlugins = [],
        array $customerOrderAccessCheckPlugins = []
    ) {
        $this->queryContainer = $queryContainer;
        $this->omsFacade = $omsFacade;
        $this->salesConfig = $salesConfig;
        $this->customerFacade = $customerFacade;
        $this->hydrateOrderPlugins = $hydrateOrderPlugins;
        $this->orderItemExpanderPlugins = $orderItemExpanderPlugins;
        $this->customerOrderAccessCheckPlugins = $customerOrderAccessCheckPlugins;
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

        $customerTransfer = $this->getCustomerByFkCustomer($orderTransfer);

        $orderEntity = $this->queryContainer
            ->querySalesOrderDetails($orderTransfer->getIdSalesOrder())
            ->findOne();

        if (!$this->isOrderApplicableForRetrieval($orderTransfer, $customerTransfer, $orderEntity)) {
            throw new InvalidSalesOrderException(sprintf(
                'Order could not be found for ID %s and customer reference %s',
                $orderTransfer->getIdSalesOrder(),
                $orderTransfer->getCustomerReference()
            ));
        }

        return $orderEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder|null $orderEntity
     *
     * @return bool
     */
    protected function isOrderApplicableForRetrieval(
        OrderTransfer $orderTransfer,
        CustomerTransfer $customerTransfer,
        ?SpySalesOrder $orderEntity
    ): bool {
        if (!$orderEntity) {
            return false;
        }

        if ($customerTransfer->getCustomerReference() === $orderEntity->getCustomerReference()) {
            return true;
        }

        if (!$orderTransfer->getCustomer()) {
            return false;
        }

        return $this->isCustomerOrderAccessGranted($orderEntity, $orderTransfer->getCustomer());
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
            ->querySalesOrderDetailsWithoutShippingAddress($idSalesOrder)
            ->findOne();

        if ($orderEntity === null) {
            throw new InvalidSalesOrderException(
                sprintf(
                    'Order could not be found for ID %s',
                    $idSalesOrder
                )
            );
        }

        $criteria = new Criteria();
        $criteria->addDescendingOrderByColumn(SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM);

        return $this->hydrateOrderTransferFromPersistenceBySalesOrder($orderEntity);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderTransferFromPersistenceBySalesOrder(SpySalesOrder $orderEntity): OrderTransfer
    {
        $this->queryContainer->fillOrderItemsWithLatestStates($orderEntity->getItems());

        return $this->createOrderTransfer($orderEntity);
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
        $itemTransfers = [];

        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM);

        foreach ($orderEntity->getItems($criteria) as $orderItemEntity) {
            $itemTransfers[] = $this->hydrateOrderItemTransfer($orderItemEntity);
        }

        $itemTransfers = $this->executeOrderItemExpanderPlugins($itemTransfers);
        $orderTransfer->setItems(new ArrayObject($itemTransfers));
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

        $itemTransfer->setIsOrdered(true);
        $itemTransfer->setIsReturnable(true);

        $this->deriveOrderItemUnitPrices($itemTransfer);

        $this->hydrateStateHistory($orderItemEntity, $itemTransfer);
        $this->hydrateCurrentSalesOrderItemState($orderItemEntity, $itemTransfer);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function executeOrderItemExpanderPlugins(array $itemTransfers): array
    {
        foreach ($this->orderItemExpanderPlugins as $orderItemExpanderPlugin) {
            $itemTransfers = $orderItemExpanderPlugin->expand($itemTransfers);
        }

        return $itemTransfers;
    }

    /**
     * Unit prices are populated for presentation purposes only. For further calculations use sum prices or properly populated unit prices.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function deriveOrderItemUnitPrices(ItemTransfer $itemTransfer)
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
        $orderShippingAddressEntity = $orderEntity->getShippingAddress();
        if ($orderShippingAddressEntity === null) {
            return;
        }

        $countryEntity = $orderShippingAddressEntity->getCountry();
        $shippingAddressTransfer = new AddressTransfer();
        $shippingAddressTransfer->fromArray($orderShippingAddressEntity->toArray(), true);
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
        foreach ($orderEntity->getExpenses(new Criteria()) as $expenseEntity) {
            $expenseTransfer = new ExpenseTransfer();
            $expenseTransfer->fromArray($expenseEntity->toArray(), true);

            $expenseTransfer->setQuantity(1);
            $expenseTransfer->setSumGrossPrice($expenseEntity->getGrossPrice());
            $expenseTransfer->setSumNetPrice($expenseEntity->getNetPrice());
            $expenseTransfer->setSumPrice($expenseEntity->getPrice());
            $expenseTransfer->setSumPriceToPayAggregation($expenseEntity->getPriceToPayAggregation());
            $expenseTransfer->setSumTaxAmount($expenseEntity->getTaxAmount());

            $expenseTransfer->setIsOrdered(true);

            $this->deriveExpenseUnitPrices($expenseTransfer);

            $orderTransfer->addExpense($expenseTransfer);
        }
    }

    /**
     * Unit prices are populated for presentation purposes only. For further calculations use sum prices or properly populated unit prices.
     *
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return void
     */
    protected function deriveExpenseUnitPrices(ExpenseTransfer $expenseTransfer)
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
     * @deprecated Use {@link \Spryker\Zed\Oms\Communication\Plugin\Sales\StateHistoryOrderItemExpanderPlugin} instead.
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function hydrateStateHistory(SpySalesOrderItem $orderItemEntity, ItemTransfer $itemTransfer)
    {
        // For BC reasons
        if (!$this->salesConfig->isHydrateOrderHistoryToItems()) {
            return;
        }

        foreach ($orderItemEntity->getStateHistories() as $stateHistoryEntity) {
            $itemStateTransfer = new ItemStateTransfer();
            $itemStateTransfer->fromArray($stateHistoryEntity->toArray(), true);
            $itemStateTransfer->setName($stateHistoryEntity->getState()->getName());
            $itemStateTransfer->setIdSalesOrder($orderItemEntity->getFkSalesOrder());
            $itemTransfer->addStateHistory($itemStateTransfer);
        }
    }

    /**
     * @param string|null $customerReference
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

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerByFkCustomer(OrderTransfer $orderTransfer): CustomerTransfer
    {
        $customerTransfer = $this->customerFacade->findCustomerById(
            (new CustomerTransfer())->setIdCustomer($orderTransfer->getFkCustomer())
        );

        if (!$customerTransfer) {
            throw new PropelException('Customer not found');
        }

        return $customerTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isCustomerOrderAccessGranted(SpySalesOrder $orderEntity, CustomerTransfer $customerTransfer): bool
    {
        $orderTransfer = (new OrderTransfer())->fromArray($orderEntity->toArray(), true);

        foreach ($this->customerOrderAccessCheckPlugins as $customerOrderAccessCheckPlugin) {
            if ($customerOrderAccessCheckPlugin->check($orderTransfer, $customerTransfer)) {
                return true;
            }
        }

        return false;
    }
}
