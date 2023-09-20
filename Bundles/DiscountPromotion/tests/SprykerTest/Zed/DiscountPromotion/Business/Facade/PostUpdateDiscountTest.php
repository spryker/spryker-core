<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountPromotion\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DiscountPromotion
 * @group Business
 * @group Facade
 * @group PostUpdateDiscountTest
 * Add your own group annotations below this line
 */
class PostUpdateDiscountTest extends Unit
{
    /**
     * @var int
     */
    protected const TEST_FAKE_ID_DISCOUNT_PROMOTION = -1;

    /**
     * @var \SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester
     */
    protected DiscountPromotionBusinessTester $tester;

    /**
     * @return void
     */
    public function testPostUpdateDiscountShouldCreatePromotionDiscountWhenIdDiscountPromotionIsNotSet(): void
    {
        // Arrange
        $discountPromotionTransfer = (new DiscountPromotionTransfer())->fromArray([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
            DiscountPromotionTransfer::ABSTRACT_SKU => DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU,
            DiscountPromotionTransfer::ABSTRACT_SKUS => [DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU],
            DiscountPromotionTransfer::QUANTITY => 1,
        ]);

        $discountConfiguratorTransfer = (new DiscountConfiguratorTransfer())
            ->setDiscountCalculator((new DiscountCalculatorTransfer())->setDiscountPromotion($discountPromotionTransfer))
            ->setDiscountGeneral((new DiscountGeneralTransfer())->setIdDiscount($discountPromotionTransfer->getFkDiscount()));

        // Act
        $discountConfiguratorTransfer = $this->tester->getFacade()->postUpdateDiscount($discountConfiguratorTransfer);

        // Assert
        $this->assertNotNull($discountConfiguratorTransfer->getDiscountCalculator()->getDiscountPromotion()->getIdDiscountPromotion());
    }

    /**
     * @return void
     */
    public function testPostUpdateDiscountShouldSetAnEmptyPromotionDiscountWhenPromotionDiscountIsNotProvided(): void
    {
        // Arrange
        $discountConfiguratorTransfer = (new DiscountConfiguratorTransfer())
            ->setDiscountCalculator((new DiscountCalculatorTransfer()));

        // Act
        $discountConfiguratorTransfer = $this->tester->getFacade()->postUpdateDiscount($discountConfiguratorTransfer);

        // Assert
        $this->assertNotNull($discountConfiguratorTransfer->getDiscountCalculator()->getDiscountPromotion());
    }

    /**
     * @return void
     */
    public function testPostUpdateDiscountShouldUpdateDiscountWhenValidIdDiscountPromotionIsProvided(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->createDiscountPromotionWithProductStock([
            DiscountPromotionTransfer::QUANTITY => 1,
        ]);

        $discountPromotionTransfer->setQuantity(2);

        $discountConfiguratorTransfer = (new DiscountConfiguratorTransfer())
            ->setDiscountCalculator((new DiscountCalculatorTransfer())->setDiscountPromotion($discountPromotionTransfer))
            ->setDiscountGeneral((new DiscountGeneralTransfer())->setIdDiscount($discountPromotionTransfer->getFkDiscount()));

        // Act
        $discountConfiguratorTransfer = $this->tester->getFacade()->postUpdateDiscount($discountConfiguratorTransfer);

        // Assert
        $this->assertSame(2, $discountConfiguratorTransfer->getDiscountCalculator()->getDiscountPromotion()->getQuantity());
        $discountPromotionTransferUpdated = $this->tester->getFacade()->findDiscountPromotionByIdDiscountPromotion(
            $discountPromotionTransfer->getIdDiscountPromotion(),
        );
        $this->assertSame(2, $discountPromotionTransferUpdated->getQuantity());
    }

    /**
     * @return void
     */
    public function testPostUpdateDiscountShouldReturnAnEmptyDiscountPromotionWhenIdDiscountPromotionIsNotValid(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->createDiscountPromotionWithProductStock([
            DiscountPromotionTransfer::QUANTITY => 1,
        ]);

        $discountPromotionTransfer->setIdDiscountPromotion(static::TEST_FAKE_ID_DISCOUNT_PROMOTION);

        $discountConfiguratorTransfer = (new DiscountConfiguratorTransfer())
            ->setDiscountCalculator((new DiscountCalculatorTransfer())->setDiscountPromotion($discountPromotionTransfer))
            ->setDiscountGeneral((new DiscountGeneralTransfer())->setIdDiscount($discountPromotionTransfer->getFkDiscount()));

        // Act
        $discountConfiguratorTransfer = $this->tester->getFacade()->postUpdateDiscount($discountConfiguratorTransfer);

        // Assert
        $this->assertNull($discountConfiguratorTransfer->getDiscountCalculator()->getDiscountPromotion()->getIdDiscountPromotion());
    }

    /**
     * @return void
     */
    public function testPostUpdateDiscountShouldSetFkDiscountFromDiscountGeneralToDiscountPromotion(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->createDiscountPromotionWithProductStock([
            DiscountPromotionTransfer::QUANTITY => 1,
        ]);

        $discountGeneralTransfer = (new DiscountGeneralTransfer())->setIdDiscount(
            $this->tester->haveDiscount()->getIdDiscount(),
        );

        $discountConfiguratorTransfer = (new DiscountConfiguratorTransfer())
            ->setDiscountCalculator((new DiscountCalculatorTransfer())->setDiscountPromotion($discountPromotionTransfer))
            ->setDiscountGeneral($discountGeneralTransfer);

        // Act
        $discountConfiguratorTransfer = $this->tester->getFacade()->postUpdateDiscount($discountConfiguratorTransfer);

        // Assert
        $this->assertSame(
            $discountGeneralTransfer->getIdDiscount(),
            $discountConfiguratorTransfer->getDiscountCalculator()->getDiscountPromotion()->getFkDiscount(),
        );
    }

    /**
     * @return void
     */
    public function testPostUpdateDiscountShouldThrowAnExceptionWhenDiscountGeneralIsNotSet(): void
    {
        // Arrange
        $discountConfiguratorTransfer = (new DiscountConfiguratorTransfer())
            ->setDiscountCalculator((new DiscountCalculatorTransfer())->setDiscountPromotion((new DiscountPromotionTransfer())));

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->postUpdateDiscount($discountConfiguratorTransfer);
    }

    /**
     * @return void
     */
    public function testPostUpdateDiscountShouldThrowAnExceptionWhenIdDiscountIsNotSet(): void
    {
        // Arrange
        $discountConfiguratorTransfer = (new DiscountConfiguratorTransfer())
            ->setDiscountCalculator((new DiscountCalculatorTransfer())->setDiscountPromotion((new DiscountPromotionTransfer())))
            ->setDiscountGeneral((new DiscountGeneralTransfer()));

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->postUpdateDiscount($discountConfiguratorTransfer);
    }

    /**
     * @return void
     */
    public function testPostUpdateDiscountShouldThrowAnExceptionWhenDiscountCalculatorIsNotSet(): void
    {
        // Arrange
        $discountGeneralTransfer = (new DiscountGeneralTransfer())->setIdDiscount(
            $this->tester->haveDiscount()->getIdDiscount(),
        );

        $discountConfiguratorTransfer = (new DiscountConfiguratorTransfer())
            ->setDiscountGeneral($discountGeneralTransfer);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->postUpdateDiscount($discountConfiguratorTransfer);
    }
}
