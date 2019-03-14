<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacade;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\Sales\SalesConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group ShippingAddressSaveTest
 * Add your own group annotations below this line
 */
class ShippingAddressSaveTest extends Test
{
    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider saveOrderAddressShouldPersistAddressEntityDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function testSaveOrderAddressShouldPersistAddressEntity(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
//        dd($quoteTransfer->getShippingAddress());

        // Arrange
        $salesOrderQuery = SpySalesOrderQuery::create()->orderByIdSalesOrder(Criteria::DESC);
        $shippingAddressTransfer = $quoteTransfer->getShippingAddress();
        $salesOrderAddressQuery = SpySalesOrderAddressQuery::create()->filterByAddress1($shippingAddressTransfer->getAddress1());

        // Act
        $this->getSalesFacadeWithMockedConfig($quoteTransfer)->saveSalesOrder($quoteTransfer, $saveOrderTransfer);

        // Assert
        $this->assertTrue($salesOrderAddressQuery->count() === 1, 'Shipping address should have been saved');
        $this->assertNull($salesOrderQuery->findOne()->getShippingAddress(), 'Shipping address should not have been assigned on sales order level.');
    }

    /**
     * @dataProvider saveOrderAddressShouldntPersistAddressEntityDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function testSaveOrderAddressShouldntPersistAddressEntity(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        // assign
        $salesOrderAddressQuery = SpySalesOrderAddressQuery::create();
        $countBefore = $salesOrderAddressQuery->count();

        // act
        $this->tester->getFacade()->saveSalesOrder($quoteTransfer, $saveOrderTransfer);

        // assert
        $expectedOrderAddressCount = $countBefore + 1;
        $this->assertEquals($expectedOrderAddressCount, $salesOrderAddressQuery->count(), 'Address count mismatch! Only billing address should have been saved.');
    }

    /**
     * @return array
     */
    public function saveOrderAddressShouldPersistAddressEntityDataProvider()
    {
        return [
            'with quote level shipping address' => $this->getDataWithQuoteLevelShippingAddress(),
        ];
    }

    /**
     * @return array
     */
    public function saveOrderAddressShouldntPersistAddressEntityDataProvider()
    {
        return [
            'without quote level shipping address' => $this->getDataWithoutQuoteLevelShippingAddress(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShippingAddress()
    {
        $itemTransfer1 = (new ItemBuilder([
           'unitPrice' => 1001,
        ]));

        $itemTransfer2 = (new ItemBuilder([
            'unitPrice' => 2002,
        ]));

        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress()
            ->withBillingAddress()
            ->withItem($itemTransfer1)
            ->withItem($itemTransfer2)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, new SaveOrderTransfer()];
    }

    /**
     * @return array
     */
    protected function getDataWithoutQuoteLevelShippingAddress()
    {
        $itemTransfer1 = (new ItemBuilder([
            'unitPrice' => 1001,
        ]));

        $itemTransfer2 = (new ItemBuilder([
            'unitPrice' => 2002,
        ]));

        $quoteTransfer = (new QuoteBuilder())
            ->withBillingAddress()
            ->withItem($itemTransfer1)
            ->withItem($itemTransfer2)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, new SaveOrderTransfer()];
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    private function getValidBaseQuoteTransfer(): QuoteTransfer
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

        $quoteTransfer
            ->setBillingAddress($billingAddress)
            ->setTotals($totals);

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail('max@mustermann.de');
        $customerTransfer->setFirstName('Max');
        $customerTransfer->setLastName('Mustermann');

        $quoteTransfer->setCustomer($customerTransfer);

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentTransfer
            ->setMethod(new ShipmentMethodTransfer())
            ->setShippingAddress($shippingAddress);

        $itemTransfer = new ItemTransfer();
        $itemTransfer
            ->setUnitPrice(1)
            ->setUnitGrossPrice(1)
            ->setSumGrossPrice(1)
            ->setQuantity(1)
            ->setShipment($shipmentTransfer)
            ->setName('test-name')
            ->setSku('sku-test');
        $quoteTransfer->addItem($itemTransfer);

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentSelection('dummyPaymentInvoice');

        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function getSalesFacadeWithMockedConfig(
        QuoteTransfer $quoteTransfer
    ): SalesFacadeInterface {
        $salesFacade = $this->createSalesFacade();
        $salesBusinessFactory = $this->createBusinessFactory();

        $salesConfigMock = $this->createSalesConfigMock();
        $res = $salesConfigMock->isTestOrder($quoteTransfer);
        $salesConfigMock->method('determineProcessForOrderItem')->willReturn([
            'function' => 'determineProcessForOrderItem',
        ]);
        $salesConfigMock->method('getOrderReferenceDefaults')->willReturn([
            'function' => 'getOrderReferenceDefaults',
        ]);

        $salesBusinessFactory->setConfig($salesConfigMock);
        $salesFacade->setFactory($salesBusinessFactory);

        return $salesFacade;
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function createSalesFacade()
    {
        return new SalesFacade();
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesBusinessFactory
     */
    protected function createBusinessFactory()
    {
        return new SalesBusinessFactory();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Sales\SalesConfig
     */
    protected function createSalesConfigMock()
    {
        return $this->getMockBuilder(SalesConfig::class)->getMock();
    }

    /**
     * This method determines state machine process from the given quote transfer and order item.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function determineProcessForOrderItem(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer)
    {
        $paymentMethodStatemachineMapping = $this->getPaymentMethodStatemachineMapping();

        if (!array_key_exists($quoteTransfer->getPayment()->getPaymentSelection(), $paymentMethodStatemachineMapping)) {
            return parent::determineProcessForOrderItem($quoteTransfer, $itemTransfer);
        }

        return $paymentMethodStatemachineMapping[$quoteTransfer->getPayment()->getPaymentSelection()];
    }

    /**
     * Defines the prefix for the sequence number which is the public id of an order.
     *
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    public function getOrderReferenceDefaults()
    {
        $sequenceNumberSettingsTransfer = new SequenceNumberSettingsTransfer();

        $sequenceNumberSettingsTransfer->setName(SalesConstants::NAME_ORDER_REFERENCE);

        $sequenceNumberPrefixParts = [];
        $sequenceNumberPrefixParts[] = Store::getInstance()->getStoreName();
        $sequenceNumberPrefixParts[] = $this->get(SalesConstants::ENVIRONMENT_PREFIX);
        $prefix = implode($this->getUniqueIdentifierSeparator(), $sequenceNumberPrefixParts) . $this->getUniqueIdentifierSeparator();
        $sequenceNumberSettingsTransfer->setPrefix($prefix);

        return $sequenceNumberSettingsTransfer;
    }
}
