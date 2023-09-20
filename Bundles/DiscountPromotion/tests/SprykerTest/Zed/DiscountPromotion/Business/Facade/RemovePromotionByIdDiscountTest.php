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
 * @group RemovePromotionByIdDiscountTest
 * Add your own group annotations below this line
 */
class RemovePromotionByIdDiscountTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester
     */
    protected DiscountPromotionBusinessTester $tester;

    /**
     * @return void
     */
    public function testDeletePromotionDiscountShouldDeleteAnyExistingPromotions(): void
    {
        // Arrange
        $discountPromotionTransferSaved = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        // Act
        $this->tester->getFacade()->removePromotionByIdDiscount($discountPromotionTransferSaved->getFkDiscount());

        // Assert
        $discountPromotionTransferUpdated = $this->tester->getFacade()
            ->findDiscountPromotionByIdDiscount($discountPromotionTransferSaved->getFkDiscount());
        $this->assertNull($discountPromotionTransferUpdated);
    }

    /**
     * @return void
     */
    public function testDeletePromotionDiscountShouldNotFailIfThereWasNoExistingPromotion(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        // Act
        $this->tester->getFacade()->removePromotionByIdDiscount($discountPromotionTransfer->getFkDiscount());

        // Assert
        $discountPromotionTransferUpdated = $this->tester->getFacade()
            ->findDiscountPromotionByIdDiscount($discountPromotionTransfer->getFkDiscount());
        $this->assertEmpty($discountPromotionTransferUpdated);
    }
}
