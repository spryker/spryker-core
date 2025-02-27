<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConfiguration\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\SalesProductConfiguration\Communication\Plugin\Sales\SalesProductConfigurationSalesOrderItemCollectionPostUpdatePlugin;
use SprykerTest\Zed\SalesProductConfiguration\SalesProductConfigurationCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesProductConfiguration
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group SalesProductConfigurationSalesOrderItemCollectionPostUpdatePluginTest
 * Add your own group annotations below this line
 */
class SalesProductConfigurationSalesOrderItemCollectionPostUpdatePluginTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\SalesProductConfiguration\SalesProductConfigurationCommunicationTester
     */
    protected SalesProductConfigurationCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureSalesOrderItemConfigurationDatabaseTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testShouldNotUpdateAnyProductConfigurations(): void
    {
        // Arrange
        $quoteTransfer = $this->createOrder();
        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Act
        (new SalesProductConfigurationSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);

        // Assert
        $this->assertSame(0, $this->tester->getSpySalesOrderItemConfigurationQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldCreateProductConfiguration(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder())->build();
        $quoteTransfer = $this->createOrder($productConfigurationInstanceTransfer);

        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Act
        (new SalesProductConfigurationSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);

        // Assert
        $this->assertSalesOrderItemConfigurationEntity($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testShouldUpdateProductConfiguration(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder())->build();
        $quoteTransfer = $this->createOrder($productConfigurationInstanceTransfer);

        $this->tester->getFacade()->saveSalesOrderItemConfigurationsFromQuote($quoteTransfer);
        $productConfigurationInstanceTransfer->setConfiguratorKey('new-key');

        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Act
        (new SalesProductConfigurationSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);

        // Assert
        $this->assertSalesOrderItemConfigurationEntity($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowRequiredTransferPropertyExceptionWhenIdSalesOrderItemIsNotSet(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder())->build();
        $quoteTransfer = $this->createOrder($productConfigurationInstanceTransfer);

        $quoteTransfer->getItems()->offsetGet(0)->setIdSalesOrderItem(null);

        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(sprintf('Missing required property "idSalesOrderItem" for transfer %s.', ItemTransfer::class));

        // Act
        (new SalesProductConfigurationSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowRequiredTransferPropertyExceptionWhenConfigurationKeyIsNotSet(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder())->build();
        $quoteTransfer = $this->createOrder($productConfigurationInstanceTransfer);

        $quoteTransfer->getItems()->offsetGet(0)->getProductConfigurationInstance()->setConfiguratorKey(null);

        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(sprintf('Missing required property "configuratorKey" for transfer %s.', ProductConfigurationInstanceTransfer::class));

        // Act
        (new SalesProductConfigurationSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertSalesOrderItemConfigurationEntity(QuoteTransfer $quoteTransfer): void
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getItems()->offsetGet(0);
        $idSalesOrderItem = $itemTransfer->getIdSalesOrderItemOrFail();
        $productConfigurationInstanceTransfer = $itemTransfer->getProductConfigurationInstance();

        $salesOrderItemConfigurationEntity = $this->tester->findSalesOrderItemConfiguration($idSalesOrderItem);

        $this->assertSame($idSalesOrderItem, $salesOrderItemConfigurationEntity->getFkSalesOrderItem());
        $this->assertSame($productConfigurationInstanceTransfer->getConfiguratorKey(), $salesOrderItemConfigurationEntity->getConfiguratorKey());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createOrder(?ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer = null): QuoteTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => $productConfigurationInstanceTransfer,
            ])
            ->withAnotherItem()
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $quoteTransfer->setStore($storeTransfer);

        $saveOrderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);
        $quoteTransfer->setItems($saveOrderTransfer->getOrderItems());

        return $quoteTransfer;
    }
}
