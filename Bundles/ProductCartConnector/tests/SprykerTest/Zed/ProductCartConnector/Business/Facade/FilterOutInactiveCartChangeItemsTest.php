<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCartConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartChangeBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ProductCartConnector\ProductCartConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCartConnector
 * @group Business
 * @group Facade
 * @group FilterOutInactiveCartChangeItemsTest
 * Add your own group annotations below this line
 */
class FilterOutInactiveCartChangeItemsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductCartConnector\ProductCartConnectorBusinessTester
     */
    protected ProductCartConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function testRemovesInactiveItems(): void
    {
        // Arrange
        $storeTransfer = $this->tester->getAllowedStore();
        $activeProductConcreteTransfer = $this->tester->haveFullProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $inactiveProductConcreteTransfer = $this->tester->haveFullProduct([
            ProductConcreteTransfer::IS_ACTIVE => false,
        ]);
        $cartChangeTransfer = (new CartChangeBuilder())
            ->withQuote([QuoteTransfer::STORE => $storeTransfer->toArray()])
            ->withItem([ItemTransfer::SKU => $inactiveProductConcreteTransfer->getSku()])
            ->withAnotherItem([ItemTransfer::SKU => $activeProductConcreteTransfer->getSku()])
            ->build();

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->filterOutInactiveCartChangeItems($cartChangeTransfer);

        // Assert
        $this->assertCount(1, $cartChangeTransfer->getItems());
        $itemTransfer = $cartChangeTransfer->getItems()->getIterator()->current();
        $this->assertSame($activeProductConcreteTransfer->getSku(), $itemTransfer->getSku());
    }

    /**
     * @return void
     */
    public function testThrowsNullValueExceptionWhenQuoteIsNotSet(): void
    {
        // Arrange
        $cartChangeTransfer = (new CartChangeBuilder([
            CartChangeTransfer::QUOTE => null,
        ]))->withItem()->build();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage(sprintf('Property "quote" of transfer `%s` is null.', CartChangeTransfer::class));

        // Act
        $this->tester->getFacade()->filterOutInactiveCartChangeItems($cartChangeTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsNullValueExceptionWhenStoreIsNotSet(): void
    {
        // Arrange
        $cartChangeTransfer = (new CartChangeBuilder())
            ->withQuote([QuoteTransfer::STORE => null])
            ->withItem()
            ->build();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage(sprintf('Property "store" of transfer `%s` is null.', QuoteTransfer::class));

        // Act
        $this->tester->getFacade()->filterOutInactiveCartChangeItems($cartChangeTransfer);
    }
}
