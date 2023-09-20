<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountPromotion\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountPromotionConditionsTransfer;
use Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer;
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
 * @group GetDiscountPromotionsTest
 * Add your own group annotations below this line
 */
class GetDiscountPromotionsTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_FAKE_UUID = 'fake-uuid';

    /**
     * @var int
     */
    protected const TEST_FAKE_ID_DISCOUNT = -1;

    /**
     * @var \SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester
     */
    protected DiscountPromotionBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetDiscountPromotionCollectionShouldReturnPersistedPromotionByIdDiscountPromotion(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $discountPromotionConditionsTransfer = (new DiscountPromotionConditionsTransfer())
            ->addIdDiscountPromotion($discountPromotionTransfer->getIdDiscountPromotion());
        $discountPromotionCriteriaTransfer = (new DiscountPromotionCriteriaTransfer())
            ->setDiscountPromotionConditions($discountPromotionConditionsTransfer);

        // Act
        $discountPromotions = $this->tester->getFacade()
            ->getDiscountPromotionCollection($discountPromotionCriteriaTransfer)
            ->getDiscountPromotions();

        // Assert
        $this->assertCount(1, $discountPromotions);
        $this->assertSame(
            $discountPromotions->getIterator()->current()->getFkDiscount(),
            $discountPromotionTransfer->getFkDiscount(),
        );
    }

    /**
     * @return void
     */
    public function testGetDiscountPromotionCollectionShouldReturnPersistedPromotionByUuid(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $discountPromotionConditionsTransfer = (new DiscountPromotionConditionsTransfer())
            ->addUuid($discountPromotionTransfer->getUuid());
        $discountPromotionCriteriaTransfer = (new DiscountPromotionCriteriaTransfer())
            ->setDiscountPromotionConditions($discountPromotionConditionsTransfer);

        // Act
        $discountPromotions = $this->tester->getFacade()
            ->getDiscountPromotionCollection($discountPromotionCriteriaTransfer)
            ->getDiscountPromotions();

        // Assert
        $this->assertCount(1, $discountPromotions);
        $this->assertSame(
            $discountPromotionTransfer->getIdDiscountPromotion(),
            $discountPromotions->getIterator()->current()->getIdDiscountPromotion(),
        );
    }

    /**
     * @return void
     */
    public function testGetDiscountPromotionCollectionShouldReturnNothingByUnknownUuid(): void
    {
        // Arrange
        $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $discountPromotionConditionsTransfer = (new DiscountPromotionConditionsTransfer())
            ->addUuid(static::TEST_FAKE_UUID);
        $discountPromotionCriteriaTransfer = (new DiscountPromotionCriteriaTransfer())
            ->setDiscountPromotionConditions($discountPromotionConditionsTransfer);

        // Act
        $discountPromotions = $this->tester->getFacade()
            ->getDiscountPromotionCollection($discountPromotionCriteriaTransfer)
            ->getDiscountPromotions();

        // Assert
        $this->assertCount(0, $discountPromotions);
    }

    /**
     * @return void
     */
    public function testGetDiscountPromotionCollectionShouldReturnNothingByUnknownIdDiscount(): void
    {
        // Arrange
        $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $discountPromotionConditionsTransfer = (new DiscountPromotionConditionsTransfer())
            ->addIdDiscount(static::TEST_FAKE_ID_DISCOUNT);
        $discountPromotionCriteriaTransfer = (new DiscountPromotionCriteriaTransfer())
            ->setDiscountPromotionConditions($discountPromotionConditionsTransfer);

        // Act
        $discountPromotions = $this->tester->getFacade()
            ->getDiscountPromotionCollection($discountPromotionCriteriaTransfer)
            ->getDiscountPromotions();

        // Assert
        $this->assertCount(0, $discountPromotions);
    }

    /**
     * @return void
     */
    public function testGetDiscountPromotionCollectionShouldReturnNothingByUnknownIdPromotionDiscount(): void
    {
        // Arrange
        $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $discountPromotionConditionsTransfer = (new DiscountPromotionConditionsTransfer())
            ->addIdDiscountPromotion(static::TEST_FAKE_ID_DISCOUNT);
        $discountPromotionCriteriaTransfer = (new DiscountPromotionCriteriaTransfer())
            ->setDiscountPromotionConditions($discountPromotionConditionsTransfer);

        // Act
        $discountPromotions = $this->tester->getFacade()
            ->getDiscountPromotionCollection($discountPromotionCriteriaTransfer)
            ->getDiscountPromotions();

        // Assert
        $this->assertCount(0, $discountPromotions);
    }

    /**
     * @return void
     */
    public function testGetDiscountPromotionCollectionShouldReturnPersistedPromotionByIdDiscount(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $discountPromotionConditionsTransfer = (new DiscountPromotionConditionsTransfer())
            ->addIdDiscount($discountPromotionTransfer->getFkDiscount());
        $discountPromotionCriteriaTransfer = (new DiscountPromotionCriteriaTransfer())
            ->setDiscountPromotionConditions($discountPromotionConditionsTransfer);

        // Act
        $discountPromotions = $this->tester->getFacade()
            ->getDiscountPromotionCollection($discountPromotionCriteriaTransfer)
            ->getDiscountPromotions();

        // Assert
        $this->assertCount(1, $discountPromotions);
        $this->assertSame(
            $discountPromotions->getIterator()->current()->getFkDiscount(),
            $discountPromotionTransfer->getFkDiscount(),
        );
    }

    /**
     * @return void
     */
    public function testFindDiscountPromotionCollectionShouldReturnPersistedPromotion(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $discountPromotionConditionsTransfer = (new DiscountPromotionConditionsTransfer())
            ->addIdDiscountPromotion($discountPromotionTransfer->getIdDiscountPromotion());
        $discountPromotionCriteriaTransfer = (new DiscountPromotionCriteriaTransfer())
            ->setDiscountPromotionConditions($discountPromotionConditionsTransfer);

        // Act
        $discountPromotions = $this->tester->getFacade()
            ->getDiscountPromotionCollection($discountPromotionCriteriaTransfer)
            ->getDiscountPromotions();

        // Assert
        $this->assertCount(1, $discountPromotions);
        $this->assertSame(
            $discountPromotions->getIterator()->current()->getFkDiscount(),
            $discountPromotionTransfer->getFkDiscount(),
        );
    }
}
