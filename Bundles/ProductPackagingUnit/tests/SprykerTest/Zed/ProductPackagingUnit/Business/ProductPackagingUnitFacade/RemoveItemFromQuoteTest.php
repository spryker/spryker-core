<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use Spryker\DecimalObject\Decimal;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group RemoveItemFromQuoteTest
 * Add your own group annotations below this line
 */
class RemoveItemFromQuoteTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @return void
     */
    public function testAddAndRemoveItemsToAndFromQuote(): void
    {
        $itemSku = 'sku_1';
        $quoteTransfer = $this->tester->createQuoteTransfer();
        $itemTransfer = $this->tester->createProductPackagingUnitItemTransfer($itemSku, 1, new Decimal(1.3));

        // Act
        $quoteTransfer = $this->tester->getFacade()->addItemToQuote($itemTransfer, $quoteTransfer);

        // Assert
        $this->assertCount(1, $quoteTransfer->getItems());

        // Act
        $quoteTransfer = $this->tester->getFacade()->addItemToQuote($itemTransfer, $quoteTransfer);

        // Assert
        $this->assertCount(1, $quoteTransfer->getItems());
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertSame(2, $itemTransfer->getQuantity());
            $this->assertSame('2', $itemTransfer->getAmount()->toString());
        }

        // Act
        $this->tester->getFacade()->removeItemFromQuote(
            $this->tester->createProductPackagingUnitItemTransfer($itemSku, 1, new Decimal(1.4)),
            $quoteTransfer,
        );

        // Assert
        $this->assertCount(1, $quoteTransfer->getItems());
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertSame(1, $itemTransfer->getQuantity());
            $this->assertSame('1', $itemTransfer->getAmount()->toString());
        }

        // Act
        $this->tester->getFacade()->removeItemFromQuote(
            $this->tester->createProductPackagingUnitItemTransfer($itemSku, 1, new Decimal(1.7)),
            $quoteTransfer,
        );

        // Assert
        $this->assertCount(0, $quoteTransfer->getItems());
    }
}
