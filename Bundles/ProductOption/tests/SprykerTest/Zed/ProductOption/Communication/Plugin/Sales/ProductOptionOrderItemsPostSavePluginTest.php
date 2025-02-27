<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\ProductOption\Communication\Plugin\Sales\ProductOptionOrderItemsPostSavePlugin;
use SprykerTest\Zed\ProductOption\ProductOptionCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group ProductOptionOrderItemsPostSavePluginTest
 * Add your own group annotations below this line
 */
class ProductOptionOrderItemsPostSavePluginTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\ProductOption\ProductOptionCommunicationTester
     */
    protected ProductOptionCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureSalesOrderItemOptionDatabaseTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testShouldNotCreateAnyProductOptions(): void
    {
        // Arrange
        $quoteTransfer = $this->createOrder();

        // Act
        (new ProductOptionOrderItemsPostSavePlugin())->execute(new SaveOrderTransfer(), $quoteTransfer);

        // Assert
        $this->assertSame(0, $this->tester->getSalesOrderItemOptionQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldCreateProductOption(): void
    {
        // Arrange
        $quoteTransfer = $this->createOrder(true);

        // Act
        (new ProductOptionOrderItemsPostSavePlugin())->execute(new SaveOrderTransfer(), $quoteTransfer);

        // Assert
        $this->assertSalesOrderItemProductOptionEntity($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowPropelExceptionWhenIdSalesOrderItemIsNotSet(): void
    {
        // Arrange
        $quoteTransfer = $this->createOrder(true);
        $quoteTransfer->getItems()->offsetGet(0)->setIdSalesOrderItem(null);

        // Assert
        $this->expectException(PropelException::class);

        // Act
        (new ProductOptionOrderItemsPostSavePlugin())->execute(new SaveOrderTransfer(), $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertSalesOrderItemProductOptionEntity(QuoteTransfer $quoteTransfer): void
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getItems()->offsetGet(0);
        $idSalesOrderItem = $itemTransfer->getIdSalesOrderItemOrFail();
        /** @var \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer */
        $productOptionTransfer = $itemTransfer->getProductOptions()->offsetGet(0);

        $salesOrderItemOptionEntity = $this->tester->findSalesOrderItemOption($idSalesOrderItem);

        $this->assertSame($idSalesOrderItem, $salesOrderItemOptionEntity->getFkSalesOrderItem());
        $this->assertSame($productOptionTransfer->getSku(), $salesOrderItemOptionEntity->getSku());
        $this->assertSame($productOptionTransfer->getValue(), $salesOrderItemOptionEntity->getValue());
    }

    /**
     * @param bool|null $withProductOptions
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createOrder(?bool $withProductOptions = false): QuoteTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem()
            ->withAnotherItem()
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $quoteTransfer->setStore($storeTransfer);

        if ($withProductOptions) {
            $quoteTransfer->getItems()->offsetGet(0)
                ->addProductOption($this->tester->createProductOption($storeTransfer));
        }

        $saveOrderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);
        $quoteTransfer->setItems($saveOrderTransfer->getOrderItems());

        return $quoteTransfer;
    }
}
