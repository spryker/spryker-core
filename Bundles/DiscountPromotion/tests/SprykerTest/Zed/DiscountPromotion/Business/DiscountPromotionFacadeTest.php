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
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConfig;
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

        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountConfigurationTransfer = $this->createDiscountConfiguratorTransfer();
        $idDiscount = $discountFacade->saveDiscount($discountConfigurationTransfer);

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setIdDiscount($idDiscount);

        $quoteTransfer = new QuoteTransfer();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($idDiscount);

        $discountPromotionFacade->createPromotionDiscount($discountPromotionTransfer);

        $collectedDiscounts = $discountPromotionFacade->collect($discountTransfer, $quoteTransfer);

        $this->assertCount(1, $quoteTransfer->getPromotionItems());
        $this->assertCount(0, $collectedDiscounts);
    }

    /**
     * @return void
     */
    public function testCollectWhenPromotionItemIsAlreadyInCartShouldCollectIt()
    {
        $discountPromotionFacade = $this->getDiscountPromotionFacade();
        $discountFacade = $this->getDiscountFacade();

        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountConfigurationTransfer = $this->createDiscountConfiguratorTransfer();
        $idDiscount = $discountFacade->saveDiscount($discountConfigurationTransfer);

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($idDiscount);
        $discountPromotionFacade->createPromotionDiscount($discountPromotionTransfer);

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setIdDiscount($idDiscount);

        $quoteTransfer = new QuoteTransfer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setAbstractSku($promotionItemSku);
        $itemTransfer->setQuantity(1);
        $itemTransfer->setIdDiscountPromotion($discountPromotionTransfer->getIdDiscountPromotion());
        $quoteTransfer->addItem($itemTransfer);

        $collectedDiscounts = $discountPromotionFacade->collect($discountTransfer, $quoteTransfer);

        $this->assertCount(0, $quoteTransfer->getPromotionItems());
        $this->assertCount(1, $collectedDiscounts);
    }

    /**
     * @return void
     */
    public function testCollectWhenItemIsNotAvailableShouldSkipPromotion()
    {
        $discountPromotionFacade = $this->getDiscountPromotionFacade();
        $discountFacade = $this->getDiscountFacade();

        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountConfigurationTransfer = $this->createDiscountConfiguratorTransfer();
        $idDiscount = $discountFacade->saveDiscount($discountConfigurationTransfer);

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setIdDiscount($idDiscount);

        $quoteTransfer = new QuoteTransfer();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($idDiscount);

        $discountPromotionFacade->createPromotionDiscount($discountPromotionTransfer);

         $this->getAvailabilityFacade()->saveProductAvailability('001_25904006', 0);

        $collectedDiscounts = $discountPromotionFacade->collect($discountTransfer, $quoteTransfer);

        $this->assertCount(0, $quoteTransfer->getPromotionItems());
        $this->assertCount(0, $collectedDiscounts);
    }

    /**
     * @return void
     */
    public function testSavePromotionDiscountShouldHavePersistedPromotionDiscount()
    {
        $discountPromotionFacade = $this->getDiscountPromotionFacade();
        $discountFacade = $this->getDiscountFacade();

        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountConfigurationTransfer = $this->createDiscountConfiguratorTransfer();
        $idDiscount = $discountFacade->saveDiscount($discountConfigurationTransfer);

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($idDiscount);

        $discountPromotionTransferSaved = $discountPromotionFacade->createPromotionDiscount($discountPromotionTransfer);

        $this->assertNotEmpty($discountPromotionTransferSaved);

        $discountPromotionTransfer = $discountPromotionFacade->findDiscountPromotionByIdDiscountPromotion($discountPromotionTransferSaved->getIdDiscountPromotion());

        $this->assertNotNull($discountPromotionTransfer);

        $this->assertSame($discountPromotionTransferSaved->getIdDiscountPromotion(), $discountPromotionTransfer->getIdDiscountPromotion());
    }

    /**
     * @return void
     */
    public function testUpdateDiscountPromotionShouldUpdateExistingPromotion()
    {
        $discountPromotionFacade = $this->getDiscountPromotionFacade();
        $discountFacade = $this->getDiscountFacade();

        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountConfigurationTransfer = $this->createDiscountConfiguratorTransfer();
        $idDiscount = $discountFacade->saveDiscount($discountConfigurationTransfer);

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($idDiscount);

        $discountPromotionTransferSaved = $discountPromotionFacade->createPromotionDiscount($discountPromotionTransfer);

        $updateSku = '321';
        $discountPromotionTransferSaved->setAbstractSku($updateSku);

        $discountPromotionFacade->updatePromotionDiscount($discountPromotionTransferSaved);

        $discountPromotionTransferUpdated = $discountPromotionFacade->findDiscountPromotionByIdDiscountPromotion(
            $discountPromotionTransferSaved->getIdDiscountPromotion()
        );

        $this->assertSame($discountPromotionTransferUpdated->getAbstractSku(), $updateSku);
    }

    /**
     * @return void
     */
    public function testFindDiscountPromotionByIdDiscountPromotionShouldReturnPersistedPromotion()
    {
        $discountPromotionFacade = $this->getDiscountPromotionFacade();
        $discountFacade = $this->getDiscountFacade();

        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountConfigurationTransfer = $this->createDiscountConfiguratorTransfer();
        $idDiscount = $discountFacade->saveDiscount($discountConfigurationTransfer);

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($idDiscount);

        $discountPromotionTransferSaved = $discountPromotionFacade->createPromotionDiscount($discountPromotionTransfer);

        $discountPromotionTransferRead = $discountPromotionFacade->findDiscountPromotionByIdDiscountPromotion(
            $discountPromotionTransferSaved->getIdDiscountPromotion()
        );

        $this->assertNotNull($discountPromotionTransferRead);
    }

    /**
     * @return void
     */
    public function testExpandDiscountConfigurationWithPromotionShouldPopulateConfigurationObjectWithPromotion()
    {
        $discountPromotionFacade = $this->getDiscountPromotionFacade();
        $discountFacade = $this->getDiscountFacade();

        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountConfigurationTransfer = $this->createDiscountConfiguratorTransfer();
        $idDiscount = $discountFacade->saveDiscount($discountConfigurationTransfer);

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($idDiscount);

        $discountPromotionFacade->createPromotionDiscount($discountPromotionTransfer);

        $discountConfigurationTransfer = $discountPromotionFacade->expandDiscountConfigurationWithPromotion(
            $discountConfigurationTransfer
        );

        $this->assertNotEmpty($discountConfigurationTransfer->getDiscountCalculator()->getDiscountPromotion());
    }

    /**
     * @return void
     */
    public function testIsDiscountWithPromotionShouldReturnTrueIfDiscountHavePromo()
    {
        $discountPromotionFacade = $this->getDiscountPromotionFacade();
        $discountFacade = $this->getDiscountFacade();

        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountConfigurationTransfer = $this->createDiscountConfiguratorTransfer();
        $idDiscount = $discountFacade->saveDiscount($discountConfigurationTransfer);

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($idDiscount);

        $discountPromotionFacade->createPromotionDiscount($discountPromotionTransfer);

        $this->assertTrue($discountPromotionFacade->isDiscountWithPromotion($idDiscount));
    }

    /**
     * @return void
     */
    public function testIsDiscountWithPromotionShouldReturnFalseIfDiscountDoesNotHavePromo()
    {
        $discountPromotionFacade = $this->getDiscountPromotionFacade();
        $discountFacade = $this->getDiscountFacade();

        $discountConfigurationTransfer = $this->createDiscountConfiguratorTransfer();
        $idDiscount = $discountFacade->saveDiscount($discountConfigurationTransfer);

        $this->assertFalse($discountPromotionFacade->isDiscountWithPromotion($idDiscount));
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    protected function createDiscountConfiguratorTransfer()
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
        $discountCalculatorTransfer->setCollectorStrategyType(DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY);

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

    /**
     * @return \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface
     */
    protected function getAvailabilityFacade()
    {
        return $this->tester->getLocator()->availability()->facade();
    }

    /**
     * @param string $promotionSku
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    protected function createDiscountPromotionTransfer($promotionSku, $quantity)
    {
        $discountPromotionTransfer = new DiscountPromotionTransfer();
        $discountPromotionTransfer->setAbstractSku($promotionSku);
        $discountPromotionTransfer->setQuantity($quantity);
        return $discountPromotionTransfer;
    }

}
