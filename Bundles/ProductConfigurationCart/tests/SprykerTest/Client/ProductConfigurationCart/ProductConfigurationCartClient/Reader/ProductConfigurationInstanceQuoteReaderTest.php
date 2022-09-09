<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationCart\ProductConfigurationCartClient\Reader;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToCartClientBridge;
use Spryker\Client\ProductConfigurationCart\Reader\ProductConfigurationInstanceQuoteReader;
use SprykerTest\Client\ProductConfigurationCart\ProductConfigurationCartClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationCart
 * @group ProductConfigurationCartClient
 * @group Reader
 * @group ProductConfigurationInstanceQuoteReaderTest
 * Add your own group annotations below this line
 */
class ProductConfigurationInstanceQuoteReaderTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductConfigurationCart\ProductConfigurationCartClientTester
     */
    protected ProductConfigurationCartClientTester $tester;

    /**
     * @return void
     */
    public function testFindProductConfigurationInstanceInQuoteEnsureQuantityPropertyToBeUpdated(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder([
            ItemTransfer::QUANTITY => 10,
            ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => (new ProductConfigurationInstanceTransfer())->setIsComplete(true),
        ]))->build();

        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        // Act
        $productConfigurationInstanceTransfer = $this->createQuoteItemReplacerMock($quoteTransfer)
            ->findProductConfigurationInstanceInQuote($itemTransfer->getSku(), $itemTransfer->getSku(), $quoteTransfer);

        // Assert
        $this->assertSame(10, $productConfigurationInstanceTransfer->getQuantity());
    }

    /**
     * @return void
     */
    public function testFindProductConfigurationInstanceInQuoteEnsureQuantityPropertyIsNull(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder([
            ItemTransfer::QUANTITY => null,
            ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => (new ProductConfigurationInstanceTransfer())->setIsComplete(true),
        ]))->build();

        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        // Act
        $productConfigurationInstanceTransfer = $this->createQuoteItemReplacerMock($quoteTransfer)
            ->findProductConfigurationInstanceInQuote($itemTransfer->getSku(), $itemTransfer->getSku(), $quoteTransfer);

        // Assert
        $this->assertNull($productConfigurationInstanceTransfer->getQuantity());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Client\ProductConfigurationCart\Reader\ProductConfigurationInstanceQuoteReader|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteItemReplacerMock(QuoteTransfer $quoteTransfer): ProductConfigurationInstanceQuoteReader
    {
        return $this->getMockBuilder(ProductConfigurationInstanceQuoteReader::class)
            ->setConstructorArgs([
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
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $cartClientMock
            ->method('findQuoteItem')
            ->willReturn($quoteTransfer->getItems()->getIterator()->current());

        return $cartClientMock;
    }
}
