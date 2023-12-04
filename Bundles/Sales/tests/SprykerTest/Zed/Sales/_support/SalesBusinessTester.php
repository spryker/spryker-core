<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales;

use Codeception\Actor;
use Generated\Shared\DataBuilder\OrderListRequestBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
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
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
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

        $quoteTransfer = new QuoteTransfer();
        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode('EUR');
        $quoteTransfer->setCurrency($currencyTransfer);

        $quoteTransfer->setPriceMode(PriceMode::PRICE_MODE_GROSS);
        $billingAddress = new AddressTransfer();

        $billingAddress->setIso2Code('ix')
            ->setAddress1('address-1-1-test')
            ->setFirstName('Max')
            ->setLastName('Mustermann')
            ->setZipCode('1337')
            ->setCity('SpryHome');

        $shippingAddress = new AddressTransfer();
        $shippingAddress->setIso2Code('ix')
            ->setAddress1('address-1-2-test')
            ->setFirstName('Max')
            ->setLastName('Mustermann')
            ->setZipCode('1337')
            ->setCity('SpryHome');

        $totals = new TotalsTransfer();
        $totals->setGrandTotal(1337)
            ->setSubtotal(337);

        $totals->setTaxTotal((new TaxTotalTransfer())->setAmount(10));

        $quoteTransfer->setShippingAddress($shippingAddress)
            ->setBillingAddress($billingAddress)
            ->setTotals($totals);

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail('max@mustermann.de');
        $customerTransfer->setFirstName('Max');
        $customerTransfer->setLastName('Mustermann');

        $quoteTransfer->setCustomer($customerTransfer);

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentTransfer->setMethod(new ShipmentMethodTransfer());
        $shipmentTransfer->setShippingAddress($shippingAddress);
        $quoteTransfer->setShipment($shipmentTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer
            ->setUnitPrice(1)
            ->setUnitGrossPrice(1)
            ->setSumGrossPrice(1)
            ->setQuantity(1)
            ->setName('test-name')
            ->setSku('sku-test')
            ->setShipment($shipmentTransfer);
        $quoteTransfer->addItem($itemTransfer);

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentSelection('dummyPaymentInvoice');

        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }
}
