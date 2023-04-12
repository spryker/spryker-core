<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Filter;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Discount\Business\Filter\CollectedDiscountItemFilter;
use SprykerTest\Zed\Discount\DiscountBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Filter
 * @group CollectedDiscountItemFilterTest
 * Add your own group annotations below this line
 */
class CollectedDiscountItemFilterTest extends Unit
{
    /**
     * @var int
     */
    protected const DISCOUNT_ID = 0;

    /**
     * @var int
     */
    protected const ITEM_ID = 0;

    /**
     * @var int
     */
    protected const ITEM_QUANTITY = 5;

    /**
     * @var int
     */
    protected const ITEM_UNIT_PRICE = 1000;

    /**
     * @var int
     */
    protected const DISCOUNT_TOTAL_AMOUNT = 2500;

    /**
     * @var \SprykerTest\Zed\Discount\DiscountBusinessTester
     */
    protected DiscountBusinessTester $tester;

    /**
     * @return void
     */
    public function testFilterAppliesDiscountWhenOriginalItemCalculatedDiscountAmountExceedItemUnitPrice(): void
    {
        // Arrange
        $discountTransfer = (new DiscountTransfer())
            ->setAmount(static::DISCOUNT_TOTAL_AMOUNT)
            ->setIdDiscount(static::DISCOUNT_ID);

        $itemTransfer = (new ItemTransfer())->setId(static::ITEM_ID);

        $discountableItemTransfer = (new DiscountableItemTransfer())
            ->setOriginalItem($itemTransfer)
            ->setUnitPrice(static::ITEM_UNIT_PRICE)
            ->setQuantity(static::ITEM_QUANTITY);

        $calculatedDiscountTransfer = (new CalculatedDiscountTransfer())
            ->setIdDiscount($discountTransfer->getIdDiscount())
            ->setUnitAmount($discountTransfer->getAmount() / $discountableItemTransfer->getQuantity());

        for ($i = 0; $i < static::ITEM_QUANTITY; $i++) {
            $discountableItemTransfer->addOriginalItemCalculatedDiscounts($calculatedDiscountTransfer);
        }

        $collectedDiscountItemFilter = new CollectedDiscountItemFilter();
        $collectedDiscountTransfers = [
            (new CollectedDiscountTransfer())
                ->setDiscount($discountTransfer)
                ->addDiscountableItems($discountableItemTransfer),
        ];

        // Act
        $collectedDiscountTransfers = $collectedDiscountItemFilter->filter($collectedDiscountTransfers);

        // Assert
        $this->assertCount(1, $collectedDiscountTransfers);
        $this->assertCount(1, $collectedDiscountTransfers[0]->getDiscountableItems());
    }
}
