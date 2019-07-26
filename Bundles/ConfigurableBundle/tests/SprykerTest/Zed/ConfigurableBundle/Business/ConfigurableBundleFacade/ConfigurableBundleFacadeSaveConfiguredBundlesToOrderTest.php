<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundle\Business\ConfigurableBundleFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ConfigurableBundleTemplateBuilder;
use Generated\Shared\DataBuilder\ConfiguredBundleBuilder;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleItemQuery;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainer;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacade;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberBridge;
use Spryker\Zed\Sales\SalesConfig;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ConfigurableBundle
 * @group Business
 * @group ConfigurableBundleFacade
 * @group Facade
 * @group ConfigurableBundleFacadeSaveConfiguredBundlesToOrderTest
 * Add your own group annotations below this line
 */
class ConfigurableBundleFacadeSaveConfiguredBundlesToOrderTest extends Unit
{
    protected const FAKE_CONFIGURABLE_BUNDLE_UUID_1 = 'FAKE_CONFIGURABLE_BUNDLE_UUID_1';
    protected const FAKE_CONFIGURABLE_BUNDLE_UUID_2 = 'FAKE_CONFIGURABLE_BUNDLE_UUID_2';

    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1';
    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2';
    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_3 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_3';
    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_4 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_4';
    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_5 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_5';

    /**
     * @see \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @var \SprykerTest\Zed\ConfigurableBundle\ConfigurableBundleBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $countryFacadeMock = $this->getMockBuilder(SalesToCountryInterface::class)->setMethods(['getIdCountryByIso2Code', 'getAvailableCountries'])->getMock();
        $countryFacadeMock->method('getIdCountryByIso2Code')
            ->will($this->returnValue(1));

        $omsOrderProcessEntity = $this->getProcessEntity();

        $omsFacadeMock = $this->getMockBuilder(SalesToOmsInterface::class)
            ->setMethods([
                'selectProcess',
                'getInitialStateEntity',
                'getProcessEntity',
                'getManualEvents',
                'getItemsWithFlag',
                'getManualEventsByIdSalesOrder',
                'getDistinctManualEventsByIdSalesOrder',
                'getOrderItemMatrix',
                'isOrderFlaggedExcludeFromCustomer',
            ])
            ->getMock();
        $omsFacadeMock->method('selectProcess')
            ->will($this->returnValue('CheckoutTest01'));

        $omcConfig = new OmsConfig();

        $initialStateEntity = SpyOmsOrderItemStateQuery::create()
            ->filterByName($omcConfig->getInitialStatus())
            ->findOneOrCreate();
        $initialStateEntity->save();

        $omsFacadeMock->method('getInitialStateEntity')
            ->will($this->returnValue($initialStateEntity));

        $omsFacadeMock->method('getProcessEntity')
            ->will($this->returnValue($omsOrderProcessEntity));

        $sequenceNumberFacade = new SequenceNumberFacade();

        $container = new Container();
        $container[SalesDependencyProvider::FACADE_COUNTRY] = new SalesToCountryBridge($countryFacadeMock);
        $container[SalesDependencyProvider::FACADE_OMS] = new SalesToOmsBridge($omsFacadeMock);
        $container[SalesDependencyProvider::FACADE_SEQUENCE_NUMBER] = new SalesToSequenceNumberBridge($sequenceNumberFacade);
        $container[SalesDependencyProvider::QUERY_CONTAINER_LOCALE] = new LocaleQueryContainer();
        $container[SalesDependencyProvider::STORE] = Store::getInstance();
        $container[SalesDependencyProvider::ORDER_EXPANDER_PRE_SAVE_PLUGINS] = [];
        $container[SalesDependencyProvider::PLUGINS_ORDER_POST_SAVE] = [];
        $container[SalesDependencyProvider::ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS] = function (Container $container) {
            return [];
        };

        $this->salesFacade = new SalesFacade();
        $businessFactory = new SalesBusinessFactory();
        $salesConfigMock = $this->getMockBuilder(SalesConfig::class)->setMethods(['determineProcessForOrderItem'])->getMock();
        $salesConfigMock->method('determineProcessForOrderItem')->willReturn('');
        $businessFactory->setConfig($salesConfigMock);
        $businessFactory->setContainer($container);
        $this->salesFacade->setFactory($businessFactory);
    }

    /**
     * @return void
     */
    public function testSaveConfiguredBundlesToOrderCopyConfiguredBundlesFromQuoteToNewOrder(): void
    {
        // Arrange
        $quoteTransfer = $this->createValidBaseQuoteTransfer();
        $this->salesFacade->saveSalesOrder($quoteTransfer, new SaveOrderTransfer());

        // Act
        $this->tester
            ->getFacade()
            ->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);

        $salesOrderConfiguredBundleItemCount = (new SpySalesOrderConfiguredBundleItemQuery())
            ->filterByConfigurableBundleTemplateSlotUuid_In([
                static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1,
                static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2,
                static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_3,
                static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_4,
                static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_5,
            ])
            ->count();

        // Assert
        $this->assertEquals(5, $salesOrderConfiguredBundleItemCount);
    }

    /**
     * @param string $templateUuid
     * @param string $slotUuid
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    protected function createFakeConfiguredBundle(string $templateUuid, string $slotUuid): ConfiguredBundleTransfer
    {
        return (new ConfiguredBundleBuilder())->build()
            ->setTemplate((new ConfigurableBundleTemplateBuilder())->build()->setUuid($templateUuid))
            ->setSlot((new ConfigurableBundleTemplateSlotTransfer())->setUuid($slotUuid));
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createValidBaseQuoteTransfer(): QuoteTransfer
    {
        $country = (new SpyCountry())
            ->setIso2Code('ix');

        $country->save();

        $currencyTransfer = (new CurrencyTransfer())
            ->setCode('EUR');

        $billingAddress = (new AddressTransfer())
            ->setIso2Code('ix')
            ->setAddress1('address-1-1-test')
            ->setFirstName('Max')
            ->setLastName('Mustermann')
            ->setZipCode('1337')
            ->setCity('SpryHome');

        $shippingAddress = (new AddressTransfer())
            ->setIso2Code('ix')
            ->setAddress1('address-1-2-test')
            ->setFirstName('Max')
            ->setLastName('Mustermann')
            ->setZipCode('1337')
            ->setCity('SpryHome');

        $totals = (new TotalsTransfer())
            ->setGrandTotal(1337)
            ->setSubtotal(337)
            ->setTaxTotal((new TaxTotalTransfer())->setAmount(10));

        $customerTransfer = (new CustomerTransfer())
            ->setEmail('max@mustermann.de')
            ->setFirstName('Max')
            ->setLastName('Mustermann');

        $paymentTransfer = (new PaymentTransfer())
            ->setPaymentSelection('dummyPaymentInvoice');

        $itemTransfer = (new ItemTransfer())
            ->setUnitPrice(1)
            ->setUnitGrossPrice(1)
            ->setSumGrossPrice(1)
            ->setQuantity(1)
            ->setName('test-name')
            ->setSku('sku-test');

        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE => $this->createFakeConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1, static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE => $this->createFakeConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1, static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE => $this->createFakeConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_2, static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_3),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE => $this->createFakeConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_2, static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_4),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE => $this->createFakeConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_2, static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_5),
            ])
            ->build();

        $quoteTransfer
            ->setCurrency($currencyTransfer)
            ->setPriceMode(static::PRICE_MODE_GROSS)
            ->addItem($itemTransfer)
            ->setShippingAddress($shippingAddress)
            ->setBillingAddress($billingAddress)
            ->setTotals($totals)
            ->setCustomer($customerTransfer)
            ->setShipment((new ShipmentTransfer())->setMethod(new ShipmentMethodTransfer()))
            ->addPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    protected function getProcessEntity()
    {
        $omsOrderProcessEntity = (new SpyOmsOrderProcessQuery())->filterByName('CheckoutTest01')->findOneOrCreate();
        $omsOrderProcessEntity->save();

        return $omsOrderProcessEntity;
    }
}
