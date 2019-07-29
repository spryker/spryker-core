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
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
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
 * @group ConfigurableBundle
 * @group Business
 * @group ConfigurableBundleFacade
 * @group Facade
 * @group ConfigurableBundleFacadeTest
 * Add your own group annotations below this line
 */
class ConfigurableBundleFacadeTest extends Unit
{
    protected const FAKE_CONFIGURABLE_BUNDLE_UUID_1 = 'FAKE_CONFIGURABLE_BUNDLE_UUID_1';
    protected const FAKE_CONFIGURABLE_BUNDLE_UUID_2 = 'FAKE_CONFIGURABLE_BUNDLE_UUID_2';

    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1';
    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2';
    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_3 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_3';
    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_4 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_4';
    protected const FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_5 = 'FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_5';

    /**
     * @var \SprykerTest\Zed\ConfigurableBundle\ConfigurableBundleBusinessTester
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

        $this->assertCount(3, $salesOrderConfiguredBundleCollection->getSalesOrderConfiguredBundles()->offsetGet(0)->getItems());
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
                ItemTransfer::CONFIGURED_BUNDLE => (new ConfiguredBundleBuilder())->build()->setGroupKey(null),
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
                ItemTransfer::CONFIGURED_BUNDLE => (new ConfiguredBundleBuilder())->build()
                    ->setGroupKey(uniqid('', true))
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
                ItemTransfer::CONFIGURED_BUNDLE => (new ConfiguredBundleBuilder())->build()
                    ->setGroupKey(uniqid('', true))
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
                ItemTransfer::CONFIGURED_BUNDLE => (new ConfiguredBundleBuilder())->build()
                    ->setGroupKey(uniqid('', true))
                    ->setTemplate((new ConfigurableBundleTemplateTransfer())->setUuid(null)),
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
                ItemTransfer::CONFIGURED_BUNDLE => (new ConfiguredBundleBuilder())->build()
                    ->setGroupKey(uniqid('', true))
                    ->setTemplate((new ConfigurableBundleTemplateBuilder())->build()->setUuid(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1)),
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
                ItemTransfer::CONFIGURED_BUNDLE => (new ConfiguredBundleBuilder())->build()
                    ->setGroupKey(uniqid('', true))
                    ->setTemplate((new ConfigurableBundleTemplateBuilder())->build()->setUuid(static::FAKE_CONFIGURABLE_BUNDLE_UUID_1))
                    ->setSlot((new ConfigurableBundleTemplateSlotTransfer())->setUuid(null)),
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
    public function testHydrateConfiguredBundlesToOrderExpandsItemInOrderByConfiguredBundle(): void
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
            ->hydrateConfiguredBundlesToOrder($orderTransfer);

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
    public function testHydrateConfiguredBundlesWithoutConfiguredBundle(): void
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
            ->hydrateConfiguredBundlesToOrder($orderTransfer);

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
                ItemTransfer::CONFIGURED_BUNDLE => $this->createFakeConfiguredBundle(
                    static::FAKE_CONFIGURABLE_BUNDLE_UUID_1,
                    static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_1,
                    $firstGroupKey
                ),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE => $this->createFakeConfiguredBundle(
                    static::FAKE_CONFIGURABLE_BUNDLE_UUID_1,
                    static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_2,
                    $firstGroupKey
                ),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE => $this->createFakeConfiguredBundle(
                    static::FAKE_CONFIGURABLE_BUNDLE_UUID_2,
                    static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_3,
                    $secondGroupKey
                ),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE => $this->createFakeConfiguredBundle(
                    static::FAKE_CONFIGURABLE_BUNDLE_UUID_2,
                    static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_4,
                    $secondGroupKey
                ),
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::CONFIGURED_BUNDLE => $this->createFakeConfiguredBundle(
                    static::FAKE_CONFIGURABLE_BUNDLE_UUID_2,
                    static::FAKE_CONFIGURABLE_BUNDLE_SLOT_UUID_5,
                    $secondGroupKey
                ),
            ])
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();
    }

    /**
     * @param string $templateUuid
     * @param string $slotUuid
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    protected function createFakeConfiguredBundle(string $templateUuid, string $slotUuid, ?string $groupKey = null): ConfiguredBundleTransfer
    {
        return (new ConfiguredBundleBuilder())->build()
            ->setTemplate((new ConfigurableBundleTemplateBuilder())->build()->setUuid($templateUuid))
            ->setSlot((new ConfigurableBundleTemplateSlotTransfer())->setUuid($slotUuid))
            ->setGroupKey($groupKey);
    }
}
