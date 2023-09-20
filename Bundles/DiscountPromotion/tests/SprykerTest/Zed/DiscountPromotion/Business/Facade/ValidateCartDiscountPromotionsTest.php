<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountPromotion\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PromotionItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DiscountPromotion
 * @group Business
 * @group Facade
 * @group ValidateCartDiscountPromotionsTest
 * Add your own group annotations below this line
 */
class ValidateCartDiscountPromotionsTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_ITEM_SKU = 'test_sku';

    /**
     * @var int
     */
    protected const TEST_NOT_EXISTING_DISCOUNT_PROMOTION_ID = 0;

    /**
     * @var string
     */
    protected const CART_OPERATION_ADD = 'add';

    /**
     * @var string
     */
    protected const INVALID_CART_OPERATION_ADD = 'invalid operation';

    /**
     * @var \SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester
     */
    protected DiscountPromotionBusinessTester $tester;

    /**
     * @return void
     */
    public function testValidateCartDiscountPromotionsWithExistingPromotion(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $itemTransfer = (new ItemTransfer())
            ->setSku(static::TEST_ITEM_SKU)
            ->setIdDiscountPromotion($discountPromotionTransfer->getIdDiscountPromotion());
        $promotionItemTransfer = (new PromotionItemTransfer())
            ->setIdDiscountPromotion($discountPromotionTransfer->getIdDiscountPromotion());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setOperation(static::CART_OPERATION_ADD)
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer())->addPromotionItem($promotionItemTransfer));

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->validateCartDiscountPromotions($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateCartDiscountPromotionsWithNotExistingPromotion(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())
            ->setSku(static::TEST_ITEM_SKU)
            ->setIdDiscountPromotion(static::TEST_NOT_EXISTING_DISCOUNT_PROMOTION_ID);

        $cartChangeTransfer = (new CartChangeTransfer())
            ->setOperation(static::CART_OPERATION_ADD)
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer()));

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->validateCartDiscountPromotions($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateCartDiscountPromotionsWithInvalidOperation(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())
            ->setSku(static::TEST_ITEM_SKU)
            ->setIdDiscountPromotion(static::TEST_NOT_EXISTING_DISCOUNT_PROMOTION_ID);

        $cartChangeTransfer = (new CartChangeTransfer())
            ->setOperation(static::INVALID_CART_OPERATION_ADD)
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer()));

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->validateCartDiscountPromotions($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateCartDiscountPromotionsWithPromotionItemInQuote(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $itemTransfer = (new ItemTransfer())
            ->setSku(static::TEST_ITEM_SKU)
            ->setIdDiscountPromotion($discountPromotionTransfer->getIdDiscountPromotion());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setOperation(static::CART_OPERATION_ADD)
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer())->addItem($itemTransfer));

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->validateCartDiscountPromotions($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }
}
