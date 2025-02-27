<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales;

use Codeception\Actor;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\OrderListRequestBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Oms\Persistence\Base\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistoryQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotals;
use Spryker\Shared\Price\PriceMode;
use Spryker\Zed\Oms\OmsConfig;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\Sales\PHPMD)
 */
class SalesBusinessTester extends Actor
{
    use _generated\SalesBusinessTesterActions;

    /**
     * @param string $stateMachineProcessName
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderByStateMachineProcessName(
        string $stateMachineProcessName,
        ?CustomerTransfer $customerTransfer = null
    ): OrderTransfer {
        $quoteTransfer = $this->buildFakeQuote(
            $customerTransfer ?? $this->haveCustomer(),
            $this->haveStore([StoreTransfer::NAME => 'DE']),
        );

        $saveOrderTransfer = $this->haveOrderFromQuote($quoteTransfer, $stateMachineProcessName);

        return (new OrderTransfer())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setStore($quoteTransfer->getStore()->getName())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setItems($saveOrderTransfer->getOrderItems());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function buildFakeQuote(CustomerTransfer $customerTransfer, StoreTransfer $storeTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder())
            ->withItem()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $quoteTransfer
            ->setCustomer($customerTransfer)
            ->setStore($storeTransfer);

        return $quoteTransfer;
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\OrderListRequestTransfer
     */
    public function createOrderListRequestTransfer(array $seed): OrderListRequestTransfer
    {
        /** @var \Generated\Shared\Transfer\OrderListRequestTransfer $orderListRequestTransfer */
        $orderListRequestTransfer = (new OrderListRequestBuilder($seed))
            ->build();

        return $orderListRequestTransfer;
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function buildQuote(array $seed = []): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder($seed))
            ->withItem()
            ->withStore()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->build();

        return $quoteTransfer;
    }

    /**
     * @deprecated Will be removed in the next major without replacement.
     *
     * @return bool
     */
    public function hasItemUuidField(): bool
    {
        return property_exists(SpySalesOrderItem::class, 'uuid');
    }

    /**
     * @param int $idSalesOrder
     * @param array<string, mixed> $salesOrderTotalsData
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderTotals
     */
    public function createSalesOrderTotals(int $idSalesOrder, array $salesOrderTotalsData = []): SpySalesOrderTotals
    {
        $salesOrderTotalsEntity = new SpySalesOrderTotals();
        $salesOrderTotalsEntity->setFkSalesOrder($idSalesOrder);
        $salesOrderTotalsEntity->fromArray($salesOrderTotalsData);
        $salesOrderTotalsEntity->save();

        return $salesOrderTotalsEntity;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\Base\SpyOmsOrderItemState
     */
    public function createInitialState(): SpyOmsOrderItemState
    {
        $initialState = SpyOmsOrderItemStateQuery::create()
            ->filterByName((new OmsConfig())->getInitialStatus())
            ->findOneOrCreate();

        $initialState->save();

        return $initialState;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getValidBaseQuoteTransfer(): QuoteTransfer
    {
        $country = new SpyCountry();
        $country->setIso2Code('ix');
        $country->save();

        $currencyTransfer = (new CurrencyTransfer())->setCode('EUR');
        $billingAddress = (new AddressBuilder())->build();
        $shippingAddress = (new AddressBuilder())->build();
        $customerTransfer = (new CustomerBuilder())->build();
        $itemTransfer = (new ItemBuilder())
            ->withShipment()
            ->build();

        $paymentTransfer = (new PaymentTransfer())
            ->setPaymentSelection('dummyPaymentInvoice');

        $shipmentTransfer = (new ShipmentTransfer())
            ->setMethod(new ShipmentMethodTransfer())
            ->setShippingAddress($shippingAddress);

        $totalsTransfer = (new TotalsTransfer())
            ->setGrandTotal(1337)
            ->setSubtotal(337)
            ->setTaxTotal((new TaxTotalTransfer())->setAmount(10));

        $storeTransfer = $this->haveStore([StoreTransfer::NAME => 'DE']);

        return (new QuoteTransfer())
            ->setCurrency($currencyTransfer)
            ->setPriceMode(PriceMode::PRICE_MODE_GROSS)
            ->setShippingAddress($shippingAddress)
            ->setBillingAddress($billingAddress)
            ->setTotals($totalsTransfer)
            ->setCustomer($customerTransfer)
            ->setShipment($shipmentTransfer)
            ->addItem($itemTransfer)
            ->setPayment($paymentTransfer)
            ->setStore($storeTransfer);
    }

    /**
     * @return void
     */
    public function ensureSalesOrderTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getSalesOrderQuery());
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderByIdSalesOrder(int $idSalesOrder): OrderTransfer
    {
        $salesOrderEntity = $this->getSalesOrderQuery()
            ->setModelAlias('order')
            ->innerJoinWith('order.BillingAddress billingAddress')
            ->leftJoinWith('order.ShippingAddress shippingAddress')
            ->filterByIdSalesOrder($idSalesOrder)
            ->findOne();

        $orderTransfer = (new OrderTransfer())->fromArray($salesOrderEntity->toArray(), true);
        $orderTransfer->setBillingAddress(
            (new AddressTransfer())->fromArray($salesOrderEntity->getBillingAddress()->toArray(), true),
        );
        if ($salesOrderEntity->getShippingAddress() !== null) {
            $orderTransfer->setShippingAddress(
                (new AddressTransfer())->fromArray($salesOrderEntity->getShippingAddress()->toArray(), true),
            );
        }

        return $orderTransfer;
    }

    /**
     * @param int $idSalesOrderAddress
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress|null
     */
    public function findSalesOrderAddressEntityById(int $idSalesOrderAddress): ?SpySalesOrderAddress
    {
        return $this->getSalesOrderAddressQuery()
            ->filterByIdSalesOrderAddress($idSalesOrderAddress)
            ->findOne();
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem|null
     */
    public function findSalesOrderItemEntityById(int $idSalesOrderItem): ?SpySalesOrderItem
    {
        return $this->getSalesOrderItemQuery()
            ->filterByIdSalesOrderItem($idSalesOrderItem)
            ->findOne();
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem|null
     */
    public function findSalesOrderEntityById(int $idSalesOrder): ?SpySalesOrder
    {
        return $this->getSalesOrderQuery()
            ->filterByIdSalesOrder($idSalesOrder)
            ->findOne();
    }

    /**
     * @return void
     */
    public function ensureSalesExpenseTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getSalesExpenseQuery());
    }

    /**
     * @return list<\Generated\Shared\Transfer\ExpenseTransfer>
     */
    public function getSalesExpenses(): array
    {
        $expenseTransfers = [];
        $salesExpenseEntities = $this->getSalesExpenseQuery()->orderByIdSalesExpense()->find();

        foreach ($salesExpenseEntities as $salesExpenseEntity) {
            $expenseTransfers[] = (new ExpenseTransfer())->fromArray($salesExpenseEntity->toArray(), true);
        }

        return $expenseTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ExpenseTransfer> $expenseTransfers
     *
     * @return list<int>
     */
    public function extractSalesExpenseIds(array $expenseTransfers): array
    {
        $salesExpenseIds = [];
        foreach ($expenseTransfers as $expenseTransfer) {
            $salesExpenseIds[] = $expenseTransfer->getIdSalesExpenseOrFail();
        }

        return $salesExpenseIds;
    }

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function getSalesOrderItems(array $salesOrderItemIds): array
    {
        $salesOrderItemEntities = $this->getSalesOrderItemQuery()
            ->filterByIdSalesOrderItem_In($salesOrderItemIds)
            ->find();

        $itemTransfers = [];

        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            $itemTransfers[] = (new ItemTransfer())->fromArray($salesOrderItemEntity->toArray(), true);
        }

        return $itemTransfers;
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return void
     */
    public function clearSalesOrderItemOmsHistory(int $idSalesOrderItem): void
    {
        $this->getOmsOrderItemStateHistoryQuery()->findByFkSalesOrderItem($idSalesOrderItem)->delete();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function getSalesOrderQuery(): SpySalesOrderQuery
    {
        return SpySalesOrderQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery
     */
    protected function getSalesOrderAddressQuery(): SpySalesOrderAddressQuery
    {
        return SpySalesOrderAddressQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected function getSalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return SpySalesOrderItemQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpenseQuery
     */
    protected function getSalesExpenseQuery(): SpySalesExpenseQuery
    {
        return SpySalesExpenseQuery::create();
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistoryQuery
     */
    protected function getOmsOrderItemStateHistoryQuery(): SpyOmsOrderItemStateHistoryQuery
    {
        return SpyOmsOrderItemStateHistoryQuery::create();
    }
}
