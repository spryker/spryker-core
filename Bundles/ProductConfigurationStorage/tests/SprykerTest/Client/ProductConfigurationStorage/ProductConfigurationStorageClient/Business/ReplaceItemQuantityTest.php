<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClient\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemReplaceTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToCartClientBridge;
use Spryker\Client\ProductConfigurationStorage\Replacer\QuoteItemReplacer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationStorage
 * @group ProductConfigurationStorageClient
 * @group Business
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
     * @dataProvider provideItemQuantities
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
        $itemTransfer = (new ItemBuilder())->build()->setQuantity($itemQuantity);
        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceTransfer())
            ->setAvailableQuantity($availableQuantity);

        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setProductConfigurationInstance($productConfigurationInstanceTransfer)
            ->setSku($itemTransfer->getSku())
            ->setItemGroupKey($itemTransfer->getGroupKey());

        // Act
        $this->createQuoteItemReplacerMock($quoteTransfer)->replaceItemInQuote(
            $productConfiguratorResponseTransfer,
            new ProductConfiguratorResponseProcessorResponseTransfer()
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
        $itemTransfer = (new ItemBuilder())->build()->setQuantity(10);
        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setProductConfigurationInstance(null)
            ->setSku($itemTransfer->getSku())
            ->setItemGroupKey($itemTransfer->getGroupKey());

        // Act
        $this->createQuoteItemReplacerMock($quoteTransfer)->replaceItemInQuote(
            $productConfiguratorResponseTransfer,
            new ProductConfiguratorResponseProcessorResponseTransfer()
        );

        // Assert
        $this->assertSame(10, $this->newItemQuantity, 'New item quantity is wrong.');
    }

    /**
     * @return array
     */
    public function provideItemQuantities(): array
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
     * @return \Spryker\Client\ProductConfigurationStorage\Replacer\QuoteItemReplacer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteItemReplacerMock(QuoteTransfer $quoteTransfer): QuoteItemReplacer
    {
        return $this->getMockBuilder(QuoteItemReplacer::class)
            ->setConstructorArgs([$this->createCartClientMock($quoteTransfer)])
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToCartClientBridge|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createCartClientMock(QuoteTransfer $quoteTransfer): ProductConfigurationStorageToCartClientBridge
    {
        $cartClientMock = $this->getMockBuilder(ProductConfigurationStorageToCartClientBridge::class)
            ->onlyMethods([
                'getQuote',
                'findQuoteItem',
                'replaceItem',
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $cartClientMock
            ->method('getQuote')
            ->willReturn($quoteTransfer);

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
}
