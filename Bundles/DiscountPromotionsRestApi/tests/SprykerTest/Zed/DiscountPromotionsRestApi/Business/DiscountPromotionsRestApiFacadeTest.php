<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountPromotionsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartItemRequestBuilder;
use Generated\Shared\DataBuilder\PersistentCartChangeBuilder;
use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;

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
        $savedDiscountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $cartItemRequestTransfer = (new CartItemRequestBuilder([
            CartItemRequestTransfer::DISCOUNT_PROMOTION_UUID => $savedDiscountPromotionTransfer->getUuid(),
        ]))->build();
        $persistentCartChangeTransfer = (new PersistentCartChangeBuilder())
            ->withItem()
            ->build();

        // Act
        $changedPersistentCartChangeTransfer = $this->tester->getFacade()
            ->mapCartItemRequestTransferToPersistentCartChangeTransfer($cartItemRequestTransfer, $persistentCartChangeTransfer);

        // Assert
        $this->assertNotNull(
            $changedPersistentCartChangeTransfer->getItems()[0]->getIdDiscountPromotion(),
            'Discount promotion id should be set for the first item in PersistentCartChangeTransfer.'
        );
        $this->assertSame(
            $changedPersistentCartChangeTransfer->getItems()[0]->getIdDiscountPromotion(),
            $savedDiscountPromotionTransfer->getIdDiscountPromotion(),
            'Discount promotion id should be the same as the persisted in the database.'
        );
    }
}
