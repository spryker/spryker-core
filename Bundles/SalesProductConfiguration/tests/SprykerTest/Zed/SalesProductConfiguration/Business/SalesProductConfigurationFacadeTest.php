<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConfiguration\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfiguration;
use Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfigurationQuery;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesProductConfiguration
 * @group Business
 * @group Facade
 * @group SalesProductConfigurationFacadeTest
 * Add your own group annotations below this line
 */
class SalesProductConfigurationFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesProductConfiguration\SalesProductConfigurationBusinessTester
     */
    protected $tester;

    protected const PRODUCT_CONFIGURATION_TEST_KEY = 'product_configuration_test_key';

    /**
     * @return void
     */
    public function testSaveSalesOrderItemConfigurationsFromQuoteCheckSuccessSave(): void
    {
        //Arrange
        $productConfigurationInstance = (new ProductConfigurationInstanceTransfer())->setConfiguratorKey(
            static::PRODUCT_CONFIGURATION_TEST_KEY
        );

        $orderId = $this->tester->createOrder();
        $salesOrderItem = $this->tester->createSalesOrderItemForOrder($orderId);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::ID_SALES_ORDER_ITEM => $salesOrderItem->getIdSalesOrderItem(),
            ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => $productConfigurationInstance,
        ]))->build();

        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        //Act
        $this->tester->getFacade()->saveSalesOrderItemConfigurationsFromQuote($quoteTransfer);

        $productConfigurationEntity = SpySalesOrderItemConfigurationQuery::create()
            ->filterByConfiguratorKey(static::PRODUCT_CONFIGURATION_TEST_KEY)
            ->findOne();

        //Assert
        $this->assertSame($itemTransfer->getIdSalesOrderItem(), $productConfigurationEntity->getFkSalesOrderItem());
    }

    /**
     * @return void
     */
    public function testSaveSalesOrderItemConfigurationsFromQuoteWillSkipProductConfigurationSaveWhenNoProductConfiguration(): void
    {
        //Arrange
        $orderId = $this->tester->createOrder();
        $salesOrderItem = $this->tester->createSalesOrderItemForOrder($orderId);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::ID_SALES_ORDER_ITEM => $salesOrderItem->getIdSalesOrderItem(),
        ]))->build();

        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        //Act
        $result = $this->tester->getFacade()->saveSalesOrderItemConfigurationsFromQuote($quoteTransfer);

        //Assert
        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testSaveSalesOrderItemConfigurationsFromQuoteFailSalesOrderIdRequired(): void
    {
        //Arrange
        $productConfigurationInstance = (new ProductConfigurationInstanceTransfer())->setConfiguratorKey(
            static::PRODUCT_CONFIGURATION_TEST_KEY
        );

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => $productConfigurationInstance,
        ]))->build();
        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        //Assert
        $this->expectException(RequiredTransferPropertyException::class);

        //Act
        $this->tester->getFacade()->saveSalesOrderItemConfigurationsFromQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testSaveSalesOrderItemConfigurationsFromQuoteFailProductConfigurationKeyRequired(): void
    {
        //Arrange
        $productConfigurationInstance = new ProductConfigurationInstanceTransfer();

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => $productConfigurationInstance,
        ]))->build();
        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        //Assert
        $this->expectException(RequiredTransferPropertyException::class);

        //Act
        $this->tester->getFacade()->saveSalesOrderItemConfigurationsFromQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithProductConfigurationCheckExpanderSuccess(): void
    {
        //Arrange
        $orderId = $this->tester->createOrder();
        $salesOrderItem = $this->tester->createSalesOrderItemForOrder($orderId);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::ID_SALES_ORDER_ITEM => $salesOrderItem->getIdSalesOrderItem(),
        ]))->build();

        (new SpySalesOrderItemConfiguration())
            ->setConfiguratorKey(static::PRODUCT_CONFIGURATION_TEST_KEY)
            ->setFkSalesOrderItem($salesOrderItem->getIdSalesOrderItem())
            ->save();

        //Act
        $itemTransferExpanded = $this->tester->getFacade()->expandOrderItemsWithProductConfiguration([$itemTransfer]);
        $salesProductConfigurationKey = array_shift($itemTransferExpanded)
            ->getSalesOrderItemConfiguration()
            ->getConfiguratorKey();

        //Assert
        $this->assertSame(static::PRODUCT_CONFIGURATION_TEST_KEY, $salesProductConfigurationKey);
    }
}
