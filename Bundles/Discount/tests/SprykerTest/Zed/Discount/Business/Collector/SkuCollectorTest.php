<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Spryker\Zed\Discount\Business\Collector\SkuCollector;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface;
use SprykerTest\Zed\Discount\Business\BaseRuleTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Collector
 * @group SkuCollectorTest
 * Add your own group annotations below this line
 */
class SkuCollectorTest extends BaseRuleTester
{
    /**
     * @return void
     */
    public function testCollectWhenSkuMatchesShouldCollectMatchingItems()
    {
        $comparatorMock = $this->createComparatorMock();
        $comparatorMock->expects($this->at(0))
            ->method('compare')
            ->willReturn(true);

        $comparatorMock->expects($this->at(1))
            ->method('compare')
            ->willReturn(false);

        $itemSkuCollector = $this->createItemSkuCollector($comparatorMock);

        $clauseTransfer = $this->createClauseTransfer(100);
        $items[] = $this->createItemTransfer(100, 0, 'sku123');
        $items[] = $this->createItemTransfer(120, 0, 'sku321');
        $quoteTransfer = $this->createQuoteTransfer($items);

        $discountableItems = $itemSkuCollector->collect($quoteTransfer, $clauseTransfer);

        $this->assertCount(1, $discountableItems);
        $this->assertInstanceOf(DiscountableItemTransfer::class, $discountableItems[0]);
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface|null $comparatorMock
     *
     * @return \Spryker\Zed\Discount\Business\Collector\SkuCollector
     */
    protected function createItemSkuCollector(?ComparatorOperatorsInterface $comparatorMock = null)
    {
        if (!$comparatorMock) {
            $comparatorMock = $this->createComparatorMock();
        }

        return new SkuCollector($comparatorMock);
    }
}
