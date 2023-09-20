<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountPromotion\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DiscountPromotion
 * @group Business
 * @group Facade
 * @group IsDiscountWithPromotionTest
 * Add your own group annotations below this line
 */
class IsDiscountWithPromotionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester
     */
    protected DiscountPromotionBusinessTester $tester;

    /**
     * @return void
     */
    public function testIsDiscountWithPromotionShouldReturnTrueIfDiscountHavePromo(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        // Act
        $result = $this->tester->getFacade()->isDiscountWithPromotion($discountPromotionTransfer->getFkDiscount());

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testIsDiscountWithPromotionShouldReturnFalseIfDiscountDoesNotHavePromo(): void
    {
        // Arrange
        $discountGeneralTransfer = $this->tester->haveDiscount();

        // Act
        $result = $this->tester->getFacade()->isDiscountWithPromotion($discountGeneralTransfer->getIdDiscount());

        // Assert
        $this->assertFalse($result);
    }
}
