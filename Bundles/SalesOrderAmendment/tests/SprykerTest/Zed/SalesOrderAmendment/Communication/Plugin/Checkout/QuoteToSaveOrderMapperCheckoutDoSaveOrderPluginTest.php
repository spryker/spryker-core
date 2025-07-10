<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Communication\Plugin\Checkout;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesOrderAmendment\Communication\Plugin\Checkout\QuoteToSaveOrderMapperCheckoutDoSaveOrderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group Checkout
 * @group QuoteToSaveOrderMapperCheckoutDoSaveOrderPluginTest
 * Add your own group annotations below this line
 */
class QuoteToSaveOrderMapperCheckoutDoSaveOrderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const ORDER_REFERENCE = 'test-order-reference';

    /**
     * @return void
     */
    public function testSaveOrderShouldMapOrderAndItems(): void
    {
        // Arrange
        $orderTransfer = (new OrderBuilder())->withTotals()->build();
        $orderTransfer->setOrderReference(static::ORDER_REFERENCE);
        $quoteTransfer = (new QuoteBuilder())
            ->withItem((new ItemBuilder())->build()->toArray())
            ->build();
        $quoteTransfer->setOriginalOrder($orderTransfer);
        $saveOrderTransfer = new SaveOrderTransfer();

        // Act
        (new QuoteToSaveOrderMapperCheckoutDoSaveOrderPlugin())->saveOrder($quoteTransfer, $saveOrderTransfer);

        // Assert
        $this->assertCount(1, $saveOrderTransfer->getOrderItems());
        $this->assertSame(
            $quoteTransfer->getItems()->getIterator()->current()->getSku(),
            $saveOrderTransfer->getOrderItems()->getIterator()->current()->getSku(),
        );
        $this->assertSame(static::ORDER_REFERENCE, $saveOrderTransfer->getOrderReference());
    }

    /**
     * @return void
     */
    public function testSaveOrderShouldThrowExceptionWhenOriginalOrderIsNotSet(): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "originalOrder" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.');

        // Act
        (new QuoteToSaveOrderMapperCheckoutDoSaveOrderPlugin())->saveOrder(new QuoteTransfer(), new SaveOrderTransfer());
    }
}
