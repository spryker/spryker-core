<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountPromotion\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DiscountPromotion
 * @group Business
 * @group Facade
 * @group FilterDiscountPromotionItemsTest
 * Add your own group annotations below this line
 */
class FilterDiscountPromotionItemsTest extends Unit
{
    /**
     * @var int
     */
    protected const FAKE_ID_DISCOUNT_PROMOTIONAL = 12345;

    /**
     * @var \SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFilterDiscountPromotionItemsRemovesPromotionProducts(): void
    {
        // Arrange
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem((new ItemTransfer())->setIdDiscountPromotion(static::FAKE_ID_DISCOUNT_PROMOTIONAL))
            ->addItem((new ItemTransfer())->setIdDiscountPromotion(null));

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->filterDiscountPromotionItems($cartChangeTransfer);

        // Assert
        $this->assertCount(1, $cartChangeTransfer->getItems());
        $this->assertNull($cartChangeTransfer->getItems()->offsetGet(0)->getIdDiscountPromotion());
    }

    /**
     * @return void
     */
    public function testFilterDiscountPromotionItemsUpdatesRemovesAllItemsFromCartChange(): void
    {
        // Arrange
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem((new ItemTransfer())->setIdDiscountPromotion(static::FAKE_ID_DISCOUNT_PROMOTIONAL))
            ->addItem((new ItemTransfer())->setIdDiscountPromotion(static::FAKE_ID_DISCOUNT_PROMOTIONAL));

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->filterDiscountPromotionItems($cartChangeTransfer);

        // Assert
        $this->assertEmpty($cartChangeTransfer->getItems());
    }
}
