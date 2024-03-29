<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Spryker\Zed\Discount\Business\Collector\ItemQuantityCollector;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface;
use SprykerTest\Zed\Discount\Business\BaseRuleTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Collector
 * @group ItemQuantityCollectorTest
 * Add your own group annotations below this line
 */
class ItemQuantityCollectorTest extends BaseRuleTester
{
    /**
     * @return void
     */
    public function testItemQuantityCollectorShouldReturnAllItemsMatchingQuantity(): void
    {
        $comparatorMock = $this->createComparatorMock();
        $comparatorMock->expects($this->exactly(3))
            ->method('compare')
            ->willReturnOnConsecutiveCalls(true, false, false);

        $itemPriceCollector = $this->createItemQuantityCollector($comparatorMock);

        $clauseTransfer = $this->createClauseTransfer(100);
        $items = [];
        $items[] = $this->createItemTransfer(100, 5);
        $items[] = $this->createItemTransfer(120, 1);
        $quoteTransfer = $this->createQuoteTransfer($items);

        $discountableItems = $itemPriceCollector->collect($quoteTransfer, $clauseTransfer);

        $this->assertCount(1, $discountableItems);
        $this->assertInstanceOf(DiscountableItemTransfer::class, $discountableItems[0]);
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface|null $comparatorMock
     *
     * @return \Spryker\Zed\Discount\Business\Collector\ItemQuantityCollector
     */
    protected function createItemQuantityCollector(?ComparatorOperatorsInterface $comparatorMock = null): ItemQuantityCollector
    {
        if (!$comparatorMock) {
            $comparatorMock = $this->createComparatorMock();
        }

        return new ItemQuantityCollector($comparatorMock);
    }
}
