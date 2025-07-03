<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Communication\Plugin\Availability;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OriginalSalesOrderItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SellableItemRequestTransfer;
use Generated\Shared\Transfer\SellableItemResponseTransfer;
use Generated\Shared\Transfer\SellableItemsRequestTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;
use Spryker\Service\SalesOrderAmendment\SalesOrderAmendmentService;
use Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentBusinessFactory;
use Spryker\Zed\SalesOrderAmendment\Communication\Plugin\Availability\OrderAmendmentQuantityBatchAvailabilityStrategyPlugin;
use Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentDependencyProvider;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group Availability
 * @group OrderAmendmentQuantityBatchAvailabilityStrategyPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentQuantityBatchAvailabilityStrategyPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SKU = 'test-sku';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentCommunicationTester
     */
    protected SalesOrderAmendmentCommunicationTester $tester;

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();

        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::SERVICE_SALES_ORDER_AMENDMENT,
            new SalesOrderAmendmentService(),
        );
    }

    /**
     * @return void
     */
    public function testFindItemsAvailabilityForStoreShouldBeSellableWhenOriginalItemQuantityPlusStockIsEnough(): void
    {
        // Arrange
        $originalQuantity = 5;
        $stock = 3;
        $requestedQuantity = 8;

        $sellableItemsRequestTransfer = $this->createSellableItemsRequestTransfer(
            static::TEST_SKU,
            $originalQuantity,
            $requestedQuantity,
        );
        $sellableItemsResponseTransfer = $this->createSellableItemsResponseTransfer(static::TEST_SKU, $stock);

        // Act
        $sellableItemsResponseTransfer = $this->createOrderAmendmentQuantityBatchAvailabilityStrategyPlugin()
            ->findItemsAvailabilityForStore($sellableItemsRequestTransfer, $sellableItemsResponseTransfer);

        // Assert
        $sellableResponses = $this->getSellableResponsesIndexedBySku($sellableItemsResponseTransfer);
        $this->assertTrue($sellableResponses[static::TEST_SKU]->getIsSellable());
        $this->assertSame($originalQuantity + $stock, $sellableResponses[static::TEST_SKU]->getAvailableQuantity()->toInt());
    }

    /**
     * @return void
     */
    public function testFindItemsAvailabilityForStoreWhenOriginalItemQuantityPlusStockIsNotEnoughShouldBeNotSellable(): void
    {
        // Arrange
        $originalQuantity = 5;
        $stock = 3;
        $requestedQuantity = 9;

        $sellableItemsRequestTransfer = $this->createSellableItemsRequestTransfer(
            static::TEST_SKU,
            $originalQuantity,
            $requestedQuantity,
        );
        $sellableItemsResponseTransfer = $this->createSellableItemsResponseTransfer(static::TEST_SKU, $stock);

        // Act
        $sellableItemsResponseTransfer = $this->createOrderAmendmentQuantityBatchAvailabilityStrategyPlugin()
            ->findItemsAvailabilityForStore($sellableItemsRequestTransfer, $sellableItemsResponseTransfer);

        // Assert
        $sellableResponses = $this->getSellableResponsesIndexedBySku($sellableItemsResponseTransfer);
        $this->assertFalse($sellableResponses[static::TEST_SKU]->getIsSellable());
        $this->assertSame($originalQuantity + $stock, $sellableResponses[static::TEST_SKU]->getAvailableQuantity()->toInt());
    }

    /**
     * @return void
     */
    public function testFindItemsAvailabilityForStoreWhenItemNotInOriginalOrderShouldNotBeProcessed(): void
    {
        // Arrange
        $stock = 10;
        $requestedQuantity = 1;

        $sellableItemsRequestTransfer = (new SellableItemsRequestTransfer())
            ->setQuote(new QuoteTransfer())
            ->addSellableItemRequest((new SellableItemRequestTransfer())->setSku(static::TEST_SKU)->setQuantity($requestedQuantity));
        $sellableItemsResponseTransfer = $this->createSellableItemsResponseTransfer(static::TEST_SKU, $stock);

        // Act
        $sellableItemsResponseTransfer = $this->createOrderAmendmentQuantityBatchAvailabilityStrategyPlugin()
            ->findItemsAvailabilityForStore($sellableItemsRequestTransfer, $sellableItemsResponseTransfer);

        // Assert
        $sellableResponses = $this->getSellableResponsesIndexedBySku($sellableItemsResponseTransfer);
        $this->assertFalse($sellableResponses[static::TEST_SKU]->getIsSellable());
        $this->assertSame($stock, $sellableResponses[static::TEST_SKU]->getAvailableQuantity()->toInt());
    }

    /**
     * @return void
     */
    public function testFindItemsAvailabilityForStoreWhenAvailabilityIsNegativeShouldUseOriginalQuantityAndBeSellable(): void
    {
        // Arrange
        $originalQuantity = 5;
        $stock = -1;
        $requestedQuantity = 5;

        $sellableItemsRequestTransfer = $this->createSellableItemsRequestTransfer(
            static::TEST_SKU,
            $originalQuantity,
            $requestedQuantity,
        );
        $sellableItemsResponseTransfer = $this->createSellableItemsResponseTransfer(static::TEST_SKU, $stock);

        // Act
        $sellableItemsResponseTransfer = $this->createOrderAmendmentQuantityBatchAvailabilityStrategyPlugin()
            ->findItemsAvailabilityForStore($sellableItemsRequestTransfer, $sellableItemsResponseTransfer);

        // Assert
        $sellableResponses = $this->getSellableResponsesIndexedBySku($sellableItemsResponseTransfer);
        $this->assertTrue($sellableResponses[static::TEST_SKU]->getIsSellable());
        $this->assertSame($originalQuantity, $sellableResponses[static::TEST_SKU]->getAvailableQuantity()->toInt());
    }

    /**
     * @return void
     */
    public function testFindItemsAvailabilityForStoreWithMultipleRequestsShouldBeNotSellableWhenOneRequestIsHigherThanAvailability(): void
    {
        // Arrange
        $originalQuantity = 5;
        $stock = 5;
        $requestedQuantities = [6, 11];

        $sellableItemsRequestTransfer = $this->createSellableItemsRequestTransfer(
            static::TEST_SKU,
            $originalQuantity,
            ...$requestedQuantities,
        );
        $sellableItemsResponseTransfer = $this->createSellableItemsResponseTransfer(static::TEST_SKU, $stock);

        // Act
        $sellableItemsResponseTransfer = $this->createOrderAmendmentQuantityBatchAvailabilityStrategyPlugin()
            ->findItemsAvailabilityForStore($sellableItemsRequestTransfer, $sellableItemsResponseTransfer);

        // Assert
        $sellableResponses = $this->getSellableResponsesIndexedBySku($sellableItemsResponseTransfer);
        $this->assertFalse($sellableResponses[static::TEST_SKU]->getIsSellable());
        $this->assertSame($originalQuantity + $stock, $sellableResponses[static::TEST_SKU]->getAvailableQuantity()->toInt());
    }

    /**
     * @param string $sku
     * @param int $originalQuantity
     * @param int ...$requestedQuantities
     *
     * @return \Generated\Shared\Transfer\SellableItemsRequestTransfer
     */
    protected function createSellableItemsRequestTransfer(
        string $sku,
        int $originalQuantity,
        int ...$requestedQuantities
    ): SellableItemsRequestTransfer {
        $sellableItemsRequestTransfer = new SellableItemsRequestTransfer();
        $originalSalesOrderItems = new ArrayObject([
            (new OriginalSalesOrderItemTransfer())->setSku($sku)->setQuantity($originalQuantity)->setGroupKey($this->buildGroupKeyForItem($this->createItem($sku))),
        ]);
        $quoteTransfer = (new QuoteTransfer())->setOriginalSalesOrderItems($originalSalesOrderItems);
        $sellableItemsRequestTransfer->setQuote($quoteTransfer);
        foreach ($requestedQuantities as $requestedQuantity) {
            $sellableItemsRequestTransfer->addSellableItemRequest((new SellableItemRequestTransfer())->setSku($sku)->setQuantity($requestedQuantity));
        }

        return $sellableItemsRequestTransfer;
    }

    /**
     * @param string $sku
     * @param int $availableQuantity
     *
     * @return \Generated\Shared\Transfer\SellableItemsResponseTransfer
     */
    protected function createSellableItemsResponseTransfer(string $sku, int $availableQuantity): SellableItemsResponseTransfer
    {
        $sellableItemsResponseTransfer = new SellableItemsResponseTransfer();
        $sellableItemsResponseTransfer->addSellableItemResponse((new SellableItemResponseTransfer())->setSku($sku)->setAvailableQuantity($availableQuantity)->setIsSellable(false));

        return $sellableItemsResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function buildGroupKeyForItem(ItemTransfer $itemTransfer): string
    {
        return (new SalesOrderAmendmentService())->buildOriginalSalesOrderItemGroupKey($itemTransfer);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItem(string $sku): ItemTransfer
    {
        return (new ItemTransfer())->setSku($sku);
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemsResponseTransfer $sellableItemsResponseTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\SellableItemResponseTransfer>
     */
    protected function getSellableResponsesIndexedBySku(SellableItemsResponseTransfer $sellableItemsResponseTransfer): array
    {
        $sellableResponses = [];
        foreach ($sellableItemsResponseTransfer->getSellableItemResponses() as $response) {
            $sellableResponses[$response->getSku()] = $response;
        }

        return $sellableResponses;
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendment\Communication\Plugin\Availability\OrderAmendmentQuantityBatchAvailabilityStrategyPlugin
     */
    protected function createOrderAmendmentQuantityBatchAvailabilityStrategyPlugin(): OrderAmendmentQuantityBatchAvailabilityStrategyPlugin
    {
        return (new OrderAmendmentQuantityBatchAvailabilityStrategyPlugin())
            ->setBusinessFactory(new SalesOrderAmendmentBusinessFactory());
    }
}
