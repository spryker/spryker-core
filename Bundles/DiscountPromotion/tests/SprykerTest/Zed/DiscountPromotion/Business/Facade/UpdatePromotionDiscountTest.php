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
 * @group UpdatePromotionDiscountTest
 * Add your own group annotations below this line
 */
class UpdatePromotionDiscountTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester
     */
    protected DiscountPromotionBusinessTester $tester;

    /**
     * @return void
     */
    public function testUpdateDiscountPromotionShouldReturnTransferEvenIfDiscountDoesNotExists(): void
    {
        // Arrange
        $discountPromotionTransferSaved = $this->tester->createDiscountPromotionWithProductStock();
        $discountPromotionTransferSaved->setIdDiscountPromotion(99999);

        // Act
        $discountPromotionTransfer = $this->tester->getFacade()->updatePromotionDiscount($discountPromotionTransferSaved);

        // Assert
        $this->assertNull($discountPromotionTransfer->getIdDiscountPromotion());
    }

    /**
     * @return void
     */
    public function testUpdateDiscountPromotionShouldUpdateExistingPromotion(): void
    {
        if ($this->tester->isAbstractSkusFieldExists()) {
            $this->markTestSkipped('This test is not suitable for discount promotion with multiple abstract products functionality.');
        }

        // Arrange
        $discountPromotionTransferSaved = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $expectedQuantity = 3;
        $discountPromotionTransferSaved->setQuantity($expectedQuantity)
            ->setAbstractSku(DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU);

        // Act
        $this->tester->getFacade()->updatePromotionDiscount($discountPromotionTransferSaved);

        // Assert
        $discountPromotionTransferUpdated = $this->tester->getFacade()->findDiscountPromotionByIdDiscountPromotion(
            $discountPromotionTransferSaved->getIdDiscountPromotion(),
        );

        $this->assertSame($discountPromotionTransferUpdated->getQuantity(), $expectedQuantity);
        $this->assertSame($discountPromotionTransferUpdated->getAbstractSku(), DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU);
        $this->assertEmpty($discountPromotionTransferUpdated->getAbstractSkus());
    }

    /**
     * @return void
     */
    public function testUpdateDiscountPromotionShouldUpdateExistingPromotionWithMultipleAbstractSkus(): void
    {
        if (!$this->tester->isAbstractSkusFieldExists()) {
            $this->markTestSkipped('This test is not suitable for discount promotion without multiple abstract products functionality.');
        }

        // Arrange
        $discountPromotionTransferSaved = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $expectedAbstractSkus = [DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU, DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU_2];
        $expectedQuantity = 3;
        $discountPromotionTransferSaved
            ->setQuantity($expectedQuantity)
            ->setAbstractSkus($expectedAbstractSkus);

        // Act
        $this->tester->getFacade()->updatePromotionDiscount($discountPromotionTransferSaved);

        // Assert
        $discountPromotionTransferUpdated = $this->tester->getFacade()->findDiscountPromotionByIdDiscountPromotion(
            $discountPromotionTransferSaved->getIdDiscountPromotion(),
        );

        $this->assertSame($discountPromotionTransferUpdated->getQuantity(), $expectedQuantity);
        $this->assertSame($discountPromotionTransferUpdated->getAbstractSkus(), $expectedAbstractSkus);
        $this->assertEmpty($discountPromotionTransferUpdated->getAbstractSku());
    }
}
