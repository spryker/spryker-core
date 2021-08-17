<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationCart\ProductConfigurationCartClient\Replacer;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\Transfer\ItemReplaceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToCartClientBridge;
use Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToQuoteClientBridge;
use Spryker\Client\ProductConfigurationCart\Replacer\QuoteItemReplacer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationCart
 * @group ProductConfigurationCartClient
 * @group Replacer
 * @group ReplaceItemQuantityTest
 * Add your own group annotations below this line
 */
class ReplaceItemQuantityTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClientTester
     */
    protected $tester;

    /**
     * @var int|null
     */
    protected $newItemQuantity;

    /**
     * @dataProvider replaceItemQuantityDataProvider
     *
     * @param int $itemQuantity
     * @param int|null $availableQuantity
     * @param int $newItemQuantity
     *
     * @return void
     */
    public function testReplaceItemQuantity(int $itemQuantity, ?int $availableQuantity, int $newItemQuantity): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder([
            ItemTransfer::QUANTITY => $itemQuantity,
        ]))->build();

        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder([
            ProductConfigurationInstanceTransfer::AVAILABLE_QUANTITY => $availableQuantity,
        ]))->build();

        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setProductConfigurationInstance($productConfigurationInstanceTransfer)
            ->setSku($itemTransfer->getSku())
            ->setItemGroupKey($itemTransfer->getGroupKey());

        // Act
        $this->createQuoteItemReplacerMock($quoteTransfer)->replaceItemInQuote(
            (new ProductConfiguratorResponseProcessorResponseTransfer())->setProductConfiguratorResponse($productConfiguratorResponseTransfer)
        );

        // Assert
        $this->assertSame($newItemQuantity, $this->newItemQuantity, 'New item quantity is wrong.');
    }

    /**
     * @return void
     */
    public function testReplaceItemQuantityWithoutProductConfigurationInstance(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder([
            ItemTransfer::QUANTITY => 10,
        ]))->build();

        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setProductConfigurationInstance(null)
            ->setSku($itemTransfer->getSku())
            ->setItemGroupKey($itemTransfer->getGroupKey());

        // Act
        $this->createQuoteItemReplacerMock($quoteTransfer)->replaceItemInQuote(
            (new ProductConfiguratorResponseProcessorResponseTransfer())->setProductConfiguratorResponse($productConfiguratorResponseTransfer)
        );

        // Assert
        $this->assertSame(10, $this->newItemQuantity, 'New item quantity is wrong.');
    }

    /**
     * @return array
     */
    public function replaceItemQuantityDataProvider(): array
    {
        return [
            [10, 5, 5],
            [10, 0, 10],
            [10, null, 10],
            [10, 10, 10],
            [10, 15, 10],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Client\ProductConfigurationCart\Replacer\QuoteItemReplacer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteItemReplacerMock(QuoteTransfer $quoteTransfer): QuoteItemReplacer
    {
        return $this->getMockBuilder(QuoteItemReplacer::class)
            ->setConstructorArgs([
                $this->createQuoteClientMock($quoteTransfer),
                $this->createCartClientMock($quoteTransfer),
            ])
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToCartClientBridge|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createCartClientMock(QuoteTransfer $quoteTransfer): ProductConfigurationCartToCartClientBridge
    {
        $cartClientMock = $this->getMockBuilder(ProductConfigurationCartToCartClientBridge::class)
            ->onlyMethods([
                'findQuoteItem',
                'replaceItem',
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $cartClientMock
            ->method('findQuoteItem')
            ->willReturn($quoteTransfer->getItems()->getIterator()->current());

        $cartClientMock
            ->method('replaceItem')
            ->willReturnCallback(function (ItemReplaceTransfer $itemReplaceTransfer) {
                $this->newItemQuantity = $itemReplaceTransfer->getNewItem()->getQuantity();

                return (new QuoteResponseTransfer())->setIsSuccessful(true);
            });

        return $cartClientMock;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToQuoteClientBridge|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteClientMock(QuoteTransfer $quoteTransfer): ProductConfigurationCartToQuoteClientBridge
    {
        $quoteClientMock = $this->getMockBuilder(ProductConfigurationCartToQuoteClientBridge::class)
            ->onlyMethods(['getQuote'])
            ->disableOriginalConstructor()
            ->getMock();

        $quoteClientMock
            ->method('getQuote')
            ->willReturn($quoteTransfer);

        return $quoteClientMock;
    }
}
