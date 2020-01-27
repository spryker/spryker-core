<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountPromotionsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DiscountPromotionsRestApi
 * @group Business
 * @group Facade
 * @group DiscountPromotionsRestApiFacadeTest
 * Add your own group annotations below this line
 */
class DiscountPromotionsRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMapCartItemRequestTransferToPersistentCartChangeTransferShouldSetUpIdDiscountPromotionInPersistentCartChangeTransfer(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->getDiscountPromotionTransfer('001', 1);
        $savedDiscountPromotionTransfer = $this->getDiscountPromotionFacade()
            ->createPromotionDiscount($discountPromotionTransfer);

        $cartItemRequestTransfer = $this->createCartItemRequestTransfer($savedDiscountPromotionTransfer);
        $persistentCartChangeTransfer = $this->createPersistentCartChangeTransfer();

        // Act
        $changedPersistentCartChangeTransfer = $this->tester->getFacade()
            ->mapCartItemRequestTransferToPersistentCartChangeTransfer($cartItemRequestTransfer, $persistentCartChangeTransfer);

        // Assert
        $this->assertNotNull($changedPersistentCartChangeTransfer->getItems()[0]->getIdDiscountPromotion());
        $this->assertSame($changedPersistentCartChangeTransfer->getItems()[0]->getIdDiscountPromotion(), $savedDiscountPromotionTransfer->getIdDiscountPromotion());
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface
     */
    protected function getDiscountPromotionFacade(): DiscountPromotionFacadeInterface
    {
        return $this->tester->getLocator()
            ->discountPromotion()
            ->facade();
    }

    /**
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    protected function createPersistentCartChangeTransfer(): PersistentCartChangeTransfer
    {
        return (new PersistentCartChangeTransfer())
            ->addItem(new ItemTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    protected function createCartItemRequestTransfer(DiscountPromotionTransfer $discountPromotionTransfer): CartItemRequestTransfer
    {
        return (new CartItemRequestTransfer())
            ->setDiscountPromotionUuid($discountPromotionTransfer->getUuid());
    }
}
