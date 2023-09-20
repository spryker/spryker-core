<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountPromotion\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
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
 * @group ExpandDiscountConfigurationWithPromotionTest
 * Add your own group annotations below this line
 */
class ExpandDiscountConfigurationWithPromotionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester
     */
    protected DiscountPromotionBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandDiscountConfigurationWithPromotionShouldPopulateConfigurationObjectWithPromotion(): void
    {
        // Arrange
        $discountGeneralTransfer = $this->tester->haveDiscount();
        $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $discountGeneralTransfer->getIdDiscount(),
        ]);

        $discountConfigurationTransfer = new DiscountConfiguratorTransfer();
        $discountConfigurationTransfer->setDiscountGeneral($discountGeneralTransfer);
        $discountConfigurationTransfer->setDiscountCalculator(new DiscountCalculatorTransfer());

        // Act
        $discountConfigurationTransfer = $this->tester->getFacade()
            ->expandDiscountConfigurationWithPromotion(
                $discountConfigurationTransfer,
            );

        // Assert
        $this->assertNotEmpty($discountConfigurationTransfer->getDiscountCalculator()->getDiscountPromotion());
    }
}
