<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountPromotion\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConditionTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConstants;
use Spryker\Zed\Discount\DiscountDependencyProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DiscountPromotion
 * @group Business
 * @group Facade
 * @group DiscountPromotionFacadeTest
 * Add your own group annotations below this line
 */
class DiscountPromotionFacadeTest extends Unit
{

    /**
     * @var \SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCollectWhenPromotionItemIsNotInCartShouldAddItToQuote()
    {
        $discountPromotionFacade = $this->getDiscountPromotionFacade();
        $discountFacade = $this->getDiscountFacade();

        $promotionItemSku = 'promo';
        $promotionItemQuantity = 1;
        $discountConfigurationTransfer = $this->createDiscountConfiguratorTransfer($promotionItemSku, $promotionItemQuantity);
        $idDiscount = $discountFacade->saveDiscount($discountConfigurationTransfer);

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setIdDiscount($idDiscount);

        $quoteTransfer = new QuoteTransfer();

        $collectedDiscounts = $discountPromotionFacade->collect($discountTransfer, $quoteTransfer);

        $this->assertCount(1, $quoteTransfer->getPromotionItems());
        $this->assertCount(0, $collectedDiscounts);
    }

    /**
     * @param string $promotionSku
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    protected function createDiscountConfiguratorTransfer($promotionSku, $quantity)
    {
        $discountConfiguratorTransfer = new DiscountConfiguratorTransfer();

        $discountGeneralTransfer = new DiscountGeneralTransfer();
        $discountGeneralTransfer->setDisplayName('Promotion discount test data');
        $discountGeneralTransfer->setDiscountType(DiscountConstants::TYPE_CART_RULE);
        $discountGeneralTransfer->setIsActive(true);
        $discountGeneralTransfer->setIsExclusive(true);
        $discountGeneralTransfer->setDescription('Description');
        $discountGeneralTransfer->setValidFrom(new DateTime());
        $discountGeneralTransfer->setValidTo(new DateTime());
        $discountConfiguratorTransfer->setDiscountGeneral($discountGeneralTransfer);

        $discountCalculatorTransfer = new DiscountCalculatorTransfer();
        $discountCalculatorTransfer->setAmount(100);
        $discountCalculatorTransfer->setCalculatorPlugin(DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE);
        $discountCalculatorTransfer->setCollectorType(DiscountPromotionConstants::DISCOUNT_COLLECTOR_STRATEGY);

        $discountPromotionTransfer = new DiscountPromotionTransfer();
        $discountPromotionTransfer->setAbstractSku($promotionSku);
        $discountPromotionTransfer->setQuantity($quantity);
        $discountCalculatorTransfer->setDiscountPromotion($discountPromotionTransfer);

        $discountConfiguratorTransfer->setDiscountCalculator($discountCalculatorTransfer);

        $discountConditionTransfer = new DiscountConditionTransfer();
        $discountConditionTransfer->setDecisionRuleQueryString('sku = "*"');
        $discountConfiguratorTransfer->setDiscountCondition($discountConditionTransfer);

        return $discountConfiguratorTransfer;
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getDiscountPromotionFacade()
    {
        return $this->tester->getFacade();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DiscountFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getDiscountFacade()
    {
        return $this->tester->getLocator()->discount()->facade();
    }

}
