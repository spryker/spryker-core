<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerTest\Zed\DiscountPromotion\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

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

        $promotionItemSku = '001';
        $promotionItemQuantity = 1;

        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setIdDiscount($discountGeneralTransfer->getIdDiscount());

        $quoteTransfer = new QuoteTransfer();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

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

        $promotionItemSku = '001';
        $promotionItemQuantity = 1;

        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());
        $discountPromotionFacade->createPromotionDiscount($discountPromotionTransfer);

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setIdDiscount($discountGeneralTransfer->getIdDiscount());

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

        $promotionItemSku = '001';
        $promotionItemQuantity = 1;

        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setIdDiscount($discountGeneralTransfer->getIdDiscount());

        $quoteTransfer = new QuoteTransfer();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

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

        $promotionItemSku = '001';
        $promotionItemQuantity = 1;

        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

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

        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

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

        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

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

        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

        $discountPromotionFacade->createPromotionDiscount($discountPromotionTransfer);

        $discountConfigurationTransfer = new DiscountConfiguratorTransfer();
        $discountConfigurationTransfer->setDiscountGeneral($discountGeneralTransfer);
        $discountConfigurationTransfer->setDiscountCalculator(new DiscountCalculatorTransfer());

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

        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

        $discountPromotionFacade->createPromotionDiscount($discountPromotionTransfer);

        $this->assertTrue(
            $discountPromotionFacade->isDiscountWithPromotion($discountGeneralTransfer->getIdDiscount())
        );
    }

    /**
     * @return void
     */
    public function testIsDiscountWithPromotionShouldReturnFalseIfDiscountDoesNotHavePromo()
    {
        $discountPromotionFacade = $this->getDiscountPromotionFacade();

        $discountGeneralTransfer = $this->tester->haveDiscount();

        $this->assertFalse($discountPromotionFacade->isDiscountWithPromotion($discountGeneralTransfer->getIdDiscount()));
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getDiscountPromotionFacade()
    {
        return $this->tester->getFacade();
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
