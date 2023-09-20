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
 * @group FindDiscountPromotionByUuidTest
 * Add your own group annotations below this line
 */
class FindDiscountPromotionByUuidTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_FAKE_UUID = 'fake-uuid';

    /**
     * @var \SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester
     */
    protected DiscountPromotionBusinessTester $tester;

    /**
     * @return void
     */
    public function testFindDiscountPromotionByUuidShouldReturnPersistedPromotion(): void
    {
        // Arrange
        $discountPromotionTransferSaved = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        // Act
        $discountPromotionTransferRead = $this->tester->getFacade()
            ->findDiscountPromotionByUuid($discountPromotionTransferSaved->getUuid());

        // Assert
        $this->assertNotNull($discountPromotionTransferRead);
    }

    /**
     * @return void
     */
    public function testFindDiscountPromotionByUuidShouldReturnNullWhenDiscountPromotionWithGivenUuidDoesNotExist(): void
    {
        // Arrange
        $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        // Act
        $discountPromotionTransfer = $this->tester->getFacade()
            ->findDiscountPromotionByUuid(static::TEST_FAKE_UUID);

        // Assert
        $this->assertNull($discountPromotionTransfer);
    }
}
