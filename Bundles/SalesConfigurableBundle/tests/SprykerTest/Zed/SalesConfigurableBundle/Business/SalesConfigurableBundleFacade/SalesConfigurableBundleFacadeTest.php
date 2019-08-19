<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesConfigurableBundle\Business\SalesConfigurableBundleFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ConfigurableBundleTemplateBuilder;
use Generated\Shared\DataBuilder\ConfiguredBundleBuilder;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleFilterTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group SalesConfigurableBundle
 * @group Business
 * @group SalesConfigurableBundleFacade
 * @group Facade
 * @group SalesConfigurableBundleFacadeTest
 * Add your own group annotations below this line
 */
class SalesConfigurableBundleFacadeTest extends Unit
{
    protected const FAKE_CONFIGURABLE_BUNDLE_UUID_1 = 'FAKE_CONFIGURABLE_BUNDLE_UUID_1';
    protected const FAKE_CONFIGURABLE_BUNDLE_UUID_2 = 'FAKE_CONFIGURABLE_BUNDLE_UUID_2';

    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1';
    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2';
    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_3 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_3';
    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_4 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_4';
    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_5 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_5';

    /**
     * @var \SprykerTest\Zed\SalesConfigurableBundle\SalesConfigurableBundleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testSalesOrderConfiguredBundleCollectionByFilterRetrievesSalesOrderConfiguredBundlesByTemplateUuid(): void
    {
        // Arrange
        $quoteTransfer = $this->getFakeQuoteWithConfiguredBundleItems();
        $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        $this->tester
            ->getFacade()
            ->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);

        // Act
        $configuredBundleFilterTransfer = (new SalesOrderConfiguredBundleFilterTransfer())
            ->setConfigurableBundleTemplateUuid(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1);

        // Assert
        $salesOrderConfiguredBundleCollection = $this->tester
            ->getFacade()
            ->getSalesOrderConfiguredBundleCollectionByFilter($configuredBundleFilterTransfer);

        /** @var \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer */
        $salesOrderConfiguredBundleTransfer = $salesOrderConfiguredBundleCollection->getSalesOrderConfiguredBundles()->offsetGet(0);

        $this->assertCount(2, $salesOrderConfiguredBundleTransfer->getSalesOrderConfiguredBundleItems());
        $this->assertSame(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1, $salesOrderConfiguredBundleTransfer->getConfigurableBundleTemplateUuid());
    }

    /**
     * @return void
     */
    public function testSalesOrderConfiguredBundleCollectionByFilterRetrievesSalesOrderConfiguredBundlesByTemplateSlotUuid(): void
    {
        // Arrange
        $quoteTransfer = $this->getFakeQuoteWithConfiguredBundleItems();
        $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        $this->tester
            ->getFacade()
            ->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);

        // Act
        $configuredBundleFilterTransfer = (new SalesOrderConfiguredBundleFilterTransfer())
            ->setConfigurableBundleTemplateSlotUuid(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1);

        // Assert
        $salesOrderConfiguredBundleCollection = $this->tester
            ->getFacade()
            ->getSalesOrderConfiguredBundleCollectionByFilter($configuredBundleFilterTransfer);

        /** @var \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer */
        $salesOrderConfiguredBundleTransfer = $salesOrderConfiguredBundleCollection->getSalesOrderConfiguredBundles()->offsetGet(0);

        $this->assertCount(1, $salesOrderConfiguredBundleTransfer->getSalesOrderConfiguredBundleItems());
        $this->assertSame(
            static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1,
            $salesOrderConfiguredBundleTransfer->getSalesOrderConfiguredBundleItems()->offsetGet(0)->getConfigurableBundleTemplateSlotUuid()
        );
    }

    /**
     * @return void
     */
    public function testSalesOrderConfiguredBundleCollectionByFilterRetrievesSalesOrderConfiguredBundlesBySalesOrderItemIds(): void
    {
        // Arrange
        $quoteTransfer = $this->getFakeQuoteWithConfiguredBundleItems();
        $orderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        $salesOrderItemIds = [];

        foreach ($orderTransfer->getOrderItems() as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }

        $this->tester
            ->getFacade()
            ->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);

        // Act
        $configuredBundleFilterTransfer = (new SalesOrderConfiguredBundleFilterTransfer())
            ->setSalesOrderItemIds($salesOrderItemIds);

        // Assert
        $salesOrderConfiguredBundleCollection = $this->tester
            ->getFacade()
            ->getSalesOrderConfiguredBundleCollectionByFilter($configuredBundleFilterTransfer);

        $this->assertCount(2, $salesOrderConfiguredBundleCollection->getSalesOrderConfiguredBundles());
    }

    /**
     * @return void
     */
    public function testSaveSalesOrderConfiguredBundlesFromQuoteCopiesConfiguredBundlesFromQuoteToNewOrder(): void
    {
        // Arrange
        $quoteTransfer = $this->getFakeQuoteWithConfiguredBundleItems();
        $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        // Act
        $this->tester
            ->getFacade()
            ->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);

        // Assert
        $configuredBundleFilterTransfer = (new SalesOrderConfiguredBundleFilterTransfer())
            ->setConfigurableBundleTemplateUuid(static::FAKE_CONFIGURABLE_BUNDLE_UUID_2);

        $salesOrderConfiguredBundleCollection = $this->tester
            ->getFacade()
            ->getSalesOrderConfiguredBundleCollectionByFilter($configuredBundleFilterTransfer);

        $this->assertCount(3, $salesOrderConfiguredBundleCollection->getSalesOrderConfiguredBundles()->offsetGet(0)->getSalesOrderConfiguredBundleItems());
    }

    /**
     * @return void
     */
    public function testSaveSalesOrderConfiguredBundlesFromQuoteThrowsExceptionWhenIdSalesOrderItemNotProvided(): void
    {
        // Arrange
        $quoteTransfer = $this->getFakeQuoteWithConfiguredBundleItems();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testSaveSalesOrderConfiguredBundlesFromQuoteThrowsExceptionWhenGroupKeyNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1),
            ])
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testSaveSalesOrderConfiguredBundlesFromQuoteThrowsExceptionWhenQuantityNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1, uniqid('', true))
                    ->setQuantity(null),
            ])
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testSaveSalesOrderConfiguredBundlesFromQuoteThrowsExceptionWhenTemplateNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1, uniqid('', true))
                    ->setTemplate(null),
            ])
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testSaveSalesOrderConfiguredBundlesFromQuoteThrowsExceptionWhenTemplateUuidNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(null, uniqid('', true)),
            ])
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testSaveSalesOrderConfiguredBundlesFromQuoteThrowsExceptionWhenTemplateNameNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1),
                ItemTransfer::CONFIGURED_BUNDLE => (new ConfiguredBundleBuilder())->build()
                    ->setGroupKey(uniqid('', true))
                    ->setTemplate((new ConfigurableBundleTemplateTransfer())->setUuid(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1)->setName(null)),
            ])
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testSaveSalesOrderConfiguredBundlesFromQuoteThrowsExceptionWhenSlotNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => new ConfiguredBundleItemTransfer(),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1, uniqid('', true)),
            ])
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testSaveSalesOrderConfiguredBundlesFromQuoteThrowsExceptionWhenSlotUuidNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1, uniqid('', true)),
            ])
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testExpandOrderWithConfiguredBundlesToOrderExpandsItemInOrderByConfiguredBundle(): void
    {
        // Arrange
        $quoteTransfer = $this->getFakeQuoteWithConfiguredBundleItems();
        $saveOrderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        $this->tester->getFacade()->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);

        $orderTransfer = (new OrderTransfer())
            ->setItems($saveOrderTransfer->getOrderItems());

        // Act
        $orderTransfer = $this->tester
            ->getFacade()
            ->expandOrderWithConfiguredBundles($orderTransfer);

        // Assert
        $this->assertCount(2, $orderTransfer->getSalesOrderConfiguredBundles());
        $this->assertInstanceOf(
            SalesOrderConfiguredBundleItemTransfer::class,
            $orderTransfer->getItems()->offsetGet(0)->getSalesOrderConfiguredBundleItem()
        );
    }

    /**
     * @return void
     */
    public function testExpandOrderWithConfiguredBundlesWithoutConfiguredBundle(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
            ])
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $saveOrderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        $this->tester->getFacade()->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);

        $orderTransfer = (new OrderTransfer())
            ->setItems($saveOrderTransfer->getOrderItems());

        // Act
        $orderTransfer = $this->tester
            ->getFacade()
            ->expandOrderWithConfiguredBundles($orderTransfer);

        // Assert
        $this->assertCount(0, $orderTransfer->getSalesOrderConfiguredBundles());
        $this->assertNull($orderTransfer->getItems()->offsetGet(0)->getSalesOrderConfiguredBundleItem());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getFakeQuoteWithConfiguredBundleItems(): QuoteTransfer
    {
        $firstGroupKey = uniqid('', true);
        $secondGroupKey = uniqid('', true);

        return (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1, $firstGroupKey),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1, $firstGroupKey),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_3),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_2, $secondGroupKey),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_4),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_2, $secondGroupKey),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE_ITEM => $this->createConfiguredBundleItem(static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_5),
                ItemTransfer::CONFIGURED_BUNDLE => $this->createConfiguredBundle(static::FAKE_CONFIGURABLE_BUNDLE_UUID_2, $secondGroupKey),
            ])
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();
    }

    /**
     * @param string|null $templateUuid
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    protected function createConfiguredBundle(?string $templateUuid = null, ?string $groupKey = null): ConfiguredBundleTransfer
    {
        return (new ConfiguredBundleBuilder())->build()
            ->setTemplate((new ConfigurableBundleTemplateBuilder())->build()->setUuid($templateUuid))
            ->setGroupKey($groupKey);
    }

    /**
     * @param string|null $slotUuid
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleItemTransfer
     */
    protected function createConfiguredBundleItem(?string $slotUuid = null): ConfiguredBundleItemTransfer
    {
        return (new ConfiguredBundleItemTransfer())
            ->setSlot((new ConfigurableBundleTemplateSlotTransfer())->setUuid($slotUuid));
    }
}
