<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Spryker\Shared\Library\Currency\CurrencyManagerInterface;
use Spryker\Zed\Discount\Business\Collector\ItemPriceCollector;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface;
use Unit\Spryker\Zed\Discount\Business\BaseRuleTester;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Discount
 * @group Business
 * @group Collector
 * @group ItemPriceCollectorTest
 */
class ItemPriceCollectorTest extends BaseRuleTester
{

    /**
     * @return void
     */
    public function testCollectWhenMatchesPriceShouldReturnListOfDiscountableItems()
    {
        $comparatorMock = $this->createComparatorMock();
        $comparatorMock->expects($this->at(0))
            ->method('compare')
            ->willReturn(true);

        $comparatorMock->expects($this->at(1))
            ->method('compare')
            ->willReturn(false);

        $itemPriceCollector = $this->createItemPriceCollector($comparatorMock);

        $clauseTransfer = $this->createClauseTransfer(100);
        $items[] = $this->createItemTransfer(100);
        $items[] = $this->createItemTransfer(120);
        $quoteTransfer = $this->createQuoteTransfer($items);

        $discountableItems = $itemPriceCollector->collect($quoteTransfer, $clauseTransfer);

        $this->assertCount(1, $discountableItems);
        $this->assertInstanceOf(DiscountableItemTransfer::class, $discountableItems[0]);
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface $comparatorMock
     * @param \Spryker\Shared\Library\Currency\CurrencyManagerInterface $currencyManagerMock
     *
     * @return \Spryker\Zed\Discount\Business\Collector\ItemPriceCollector
     */
    protected function createItemPriceCollector(
        ComparatorOperatorsInterface $comparatorMock = null,
        CurrencyManagerInterface $currencyManagerMock = null
    ) {

        if (!$comparatorMock) {
            $comparatorMock = $this->createComparatorMock();
        }

        if (!$currencyManagerMock) {
            $currencyManagerMock = $this->createCurrencyCoverterMock();
        }

        return new ItemPriceCollector($comparatorMock, $currencyManagerMock);
    }

}
