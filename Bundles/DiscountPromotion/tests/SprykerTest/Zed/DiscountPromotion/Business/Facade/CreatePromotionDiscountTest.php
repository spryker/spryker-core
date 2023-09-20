<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountPromotion\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\DiscountPromotionBuilder;
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
 * @group CreatePromotionDiscountTest
 * Add your own group annotations below this line
 */
class CreatePromotionDiscountTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester
     */
    protected DiscountPromotionBusinessTester $tester;

    /**
     * @return void
     */
    public function testCreatePromotionDiscountShouldHavePersistedPromotionDiscount(): void
    {
        // Act
        $discountPromotionTransferSaved = $this->tester->createDiscountPromotionWithProductStock();

        // Assert
        $this->assertNotEmpty($discountPromotionTransferSaved);

        $discountPromotionTransfer = $this->tester->getFacade()->findDiscountPromotionByIdDiscountPromotion(
            $discountPromotionTransferSaved->getIdDiscountPromotion(),
        );
        $this->assertNotNull($discountPromotionTransfer);
        $this->assertSame($discountPromotionTransferSaved->getIdDiscountPromotion(), $discountPromotionTransfer->getIdDiscountPromotion());
    }

    /**
     * @return void
     */
    public function testCreateDiscountPromotionShouldCreatePromotion(): void
    {
        if ($this->tester->isAbstractSkusFieldExists()) {
            $this->markTestSkipped('This test is not suitable for discount promotion with multiple abstract products functionality.');
        }

        // Arrange
        $discountPromotionTransfer = (new DiscountPromotionTransfer())
            ->setAbstractSku(DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU)
            ->setFkDiscount($this->tester->haveDiscount()->getIdDiscount())
            ->setQuantity(3);

        // Act
        $discountPromotionTransferCreated = $this->tester->getFacade()->createPromotionDiscount($discountPromotionTransfer);

        // Assert
        $this->assertSame($discountPromotionTransferCreated->getQuantity(), $discountPromotionTransfer->getQuantity());
        $this->assertSame($discountPromotionTransferCreated->getAbstractSku(), $discountPromotionTransfer->getAbstractSku());
        $this->assertEmpty($discountPromotionTransferCreated->getAbstractSkus());
    }

    /**
     * @return void
     */
    public function testCreateDiscountPromotionShouldCreatePromotionWithMultipleAbstractSkus(): void
    {
        if (!$this->tester->isAbstractSkusFieldExists()) {
            $this->markTestSkipped('This test is not suitable for discount promotion without multiple abstract products functionality.');
        }

        $expectedAbstractSkus = [DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU, DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU_2];

        // Arrange
        $discountPromotionTransfer = (new DiscountPromotionBuilder([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
            DiscountPromotionTransfer::ABSTRACT_SKUS => $expectedAbstractSkus,
        ]))->build();

        // Act
        $discountPromotionTransferCreated = $this->tester->getFacade()->createPromotionDiscount($discountPromotionTransfer);

        // Assert
        $this->assertSame((int)$discountPromotionTransferCreated->getQuantity(), (int)$discountPromotionTransfer->getQuantity());
        $this->assertSame($discountPromotionTransferCreated->getAbstractSkus(), $discountPromotionTransfer->getAbstractSkus());
        $this->assertEmpty($discountPromotionTransferCreated->getAbstractSku());
    }
}
