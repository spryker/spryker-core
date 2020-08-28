<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountPromotion\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\DiscountBuilder;
use Generated\Shared\DataBuilder\DiscountPromotionBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\PromotionItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * Auto-generated group annotations
 *
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
    protected const STORE_NAME_DE = 'DE';
    protected const TEST_ITEM_SKU = 'test_sku';
    protected const TEST_NOT_EXISTING_DISCOUNT_PROMOTION_ID = 0;
    protected const CART_OPERATION_ADD = 'add';
    protected const INVALID_CART_OPERATION_ADD = 'invalid operation';

    /**
     * @var \SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCollectWhenPromotionItemIsNotInCartShouldAddItToQuote(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);
        $discountTransfer = (new DiscountBuilder([
            DiscountTransfer::ID_DISCOUNT => $discountPromotionTransfer->getFkDiscount(),
        ]))->build();

        $quoteTransfer = $this->tester->prepareQuoteWithStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveProductWithStock(
            [ProductAbstractTransfer::SKU => $discountPromotionTransfer->getAbstractSku()],
            [],
            $discountPromotionTransfer->getQuantity()
        );

        // Act
        $collectedDiscounts = $this->tester->getFacade()
            ->collect($discountTransfer, $quoteTransfer);

        // Assert
        $this->assertCount(1, $quoteTransfer->getPromotionItems());
        $this->assertCount(0, $collectedDiscounts);
    }

    /**
     * @return void
     */
    public function testCollectWhenPromotionItemIsAlreadyInCartShouldCollectIt(): void
    {
        // Arrange
        $grossPrice = 100;
        $price = 80;
        $quantity = 1;

        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);
        $discountTransfer = (new DiscountBuilder([
            DiscountTransfer::ID_DISCOUNT => $discountPromotionTransfer->getFkDiscount(),
        ]))->build();

        $quoteTransfer = $this->tester->prepareQuoteWithStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveProductWithStock(
            [ProductAbstractTransfer::SKU => $discountPromotionTransfer->getAbstractSku()],
            [],
            $discountPromotionTransfer->getQuantity()
        );

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::ABSTRACT_SKU => $discountPromotionTransfer->getAbstractSku(),
            ItemTransfer::ID_DISCOUNT_PROMOTION => $discountPromotionTransfer->getIdDiscountPromotion(),
            ItemTransfer::QUANTITY => $quantity,
            ItemTransfer::UNIT_GROSS_PRICE => $grossPrice,
            ItemTransfer::UNIT_PRICE => $price,
        ]))->build();
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $collectedDiscounts = $this->tester->getFacade()
            ->collect($discountTransfer, $quoteTransfer);

        // Assert
        $this->assertCount(0, $quoteTransfer->getPromotionItems());
        $this->assertCount(1, $collectedDiscounts);
        $this->assertSame($grossPrice, $collectedDiscounts[0]->getUnitGrossPrice());
        $this->assertSame($price, $collectedDiscounts[0]->getUnitPrice());
        $this->assertSame($quantity, $collectedDiscounts[0]->getQuantity());
    }

    /**
     * @return void
     */
    public function testCollectWhenItemIsNotAvailableShouldSkipPromotion(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);
        $discountTransfer = (new DiscountBuilder([
            DiscountTransfer::ID_DISCOUNT => $discountPromotionTransfer->getFkDiscount(),
        ]))->build();

        $quoteTransfer = $this->tester->prepareQuoteWithStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveProductWithStock(
            [ProductAbstractTransfer::SKU => $discountPromotionTransfer->getAbstractSku()],
            [],
            0
        );

        // Act
        $collectedDiscounts = $this->tester->getFacade()
            ->collect($discountTransfer, $quoteTransfer);

        // Assert
        $this->assertCount(0, $quoteTransfer->getPromotionItems());
        $this->assertCount(0, $collectedDiscounts);
    }

    /**
     * @return void
     */
    public function testCollectAdjustsQuantityBasedOnAvailability(): void
    {
        // Arrange
        $promotionItemQuantity = 5;
        $quantity = 1;

        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::QUANTITY => $promotionItemQuantity,
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);
        $discountTransfer = (new DiscountBuilder([
            DiscountTransfer::ID_DISCOUNT => $discountPromotionTransfer->getFkDiscount(),
        ]))->build();

        $quoteTransfer = $this->tester->prepareQuoteWithStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveProductWithStock(
            [ProductAbstractTransfer::SKU => $discountPromotionTransfer->getAbstractSku()],
            [],
            $promotionItemQuantity
        );

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::ABSTRACT_SKU => $discountPromotionTransfer->getAbstractSku(),
            ItemTransfer::ID_DISCOUNT_PROMOTION => $discountPromotionTransfer->getIdDiscountPromotion(),
            ItemTransfer::QUANTITY => $quantity,
        ]))->build();
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $collectedDiscounts = $this->tester->getFacade()->collect($discountTransfer, $quoteTransfer);

        // Assert
        $promotionItemTransfer = $quoteTransfer->getItems()[0];

        $this->assertCount(0, $quoteTransfer->getPromotionItems());
        $this->assertSame($quantity, $collectedDiscounts[0]->getQuantity());
        $this->assertSame($promotionItemQuantity, $promotionItemTransfer->getMaxQuantity());
    }

    /**
     * @return void
     */
    public function testSavePromotionDiscountShouldHavePersistedPromotionDiscount(): void
    {
        // Arrange
        $discountPromotionTransferSaved = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $this->assertNotEmpty($discountPromotionTransferSaved);

        // Act
        $discountPromotionTransfer = $this->tester->getFacade()
            ->findDiscountPromotionByIdDiscountPromotion($discountPromotionTransferSaved->getIdDiscountPromotion());

        // Assert
        $this->assertNotNull($discountPromotionTransfer);
        $this->assertSame($discountPromotionTransferSaved->getIdDiscountPromotion(), $discountPromotionTransfer->getIdDiscountPromotion());
    }

    /**
     * @return void
     */
    public function testUpdateDiscountPromotionShouldUpdateExistingPromotion(): void
    {
        // Arrange
        $discountPromotionTransferSaved = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $updateSku = '321';
        $discountPromotionTransferSaved->setAbstractSku($updateSku);

        // Act
        $this->tester->getFacade()->updatePromotionDiscount($discountPromotionTransferSaved);

        // Assert
        $discountPromotionTransferUpdated = $this->tester->getFacade()->findDiscountPromotionByIdDiscountPromotion(
            $discountPromotionTransferSaved->getIdDiscountPromotion()
        );
        $this->assertSame($discountPromotionTransferUpdated->getAbstractSku(), $updateSku);
    }

    /**
     * @return void
     */
    public function testValidateCartDiscountPromotionsWithExistingPromotion(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $itemTransfer = (new ItemTransfer())
            ->setSku(static::TEST_ITEM_SKU)
            ->setIdDiscountPromotion($discountPromotionTransfer->getIdDiscountPromotion());
        $promotionItemTransfer = (new PromotionItemTransfer())
            ->setIdDiscountPromotion($discountPromotionTransfer->getIdDiscountPromotion());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setOperation(static::CART_OPERATION_ADD)
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer())->addPromotionItem($promotionItemTransfer));

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->validateCartDiscountPromotions($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateCartDiscountPromotionsWithNotExistingPromotion(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())
            ->setSku(static::TEST_ITEM_SKU)
            ->setIdDiscountPromotion(static::TEST_NOT_EXISTING_DISCOUNT_PROMOTION_ID);

        $cartChangeTransfer = (new CartChangeTransfer())
            ->setOperation(static::CART_OPERATION_ADD)
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer()));

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->validateCartDiscountPromotions($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateCartDiscountPromotionsWithInvalidOperation(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())
            ->setSku(static::TEST_ITEM_SKU)
            ->setIdDiscountPromotion(static::TEST_NOT_EXISTING_DISCOUNT_PROMOTION_ID);

        $cartChangeTransfer = (new CartChangeTransfer())
            ->setOperation(static::INVALID_CART_OPERATION_ADD)
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer()));

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->validateCartDiscountPromotions($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testDeletePromotionDiscountShouldDeleteAnyExistingPromotions(): void
    {
        // Arrange
        $discountPromotionTransferSaved = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        // Act
        $this->tester->getFacade()->removePromotionByIdDiscount($discountPromotionTransferSaved->getFkDiscount());

        $discountPromotionTransferUpdated = $this->tester->getFacade()->findDiscountPromotionByIdDiscount(
            $discountPromotionTransferSaved->getFkDiscount()
        );

        // Assert
        $this->assertNull($discountPromotionTransferUpdated);
    }

    /**
     * @return void
     */
    public function testDeletePromotionDiscountShouldNotFailIfThereWasNoExistingPromotion(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        // Act
        $this->tester->getFacade()->removePromotionByIdDiscount($discountPromotionTransfer->getFkDiscount());

        // Assert
        $discountPromotionTransferUpdated = $this->tester->getFacade()
            ->findDiscountPromotionByIdDiscount(
                $discountPromotionTransfer->getFkDiscount()
            );
        $this->assertEmpty($discountPromotionTransferUpdated);
    }

    /**
     * @return void
     */
    public function testFindDiscountPromotionByIdDiscountPromotionShouldReturnPersistedPromotion(): void
    {
        // Arrange
        $discountPromotionTransferSaved = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        // Act
        $discountPromotionTransferRead = $this->tester->getFacade()->findDiscountPromotionByIdDiscountPromotion(
            $discountPromotionTransferSaved->getIdDiscountPromotion()
        );

        // Assert
        $this->assertNotNull($discountPromotionTransferRead);
    }

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
                $discountConfigurationTransfer
            );

        // Assert
        $this->assertNotEmpty($discountConfigurationTransfer->getDiscountCalculator()->getDiscountPromotion());
    }

    /**
     * @return void
     */
    public function testIsDiscountWithPromotionShouldReturnTrueIfDiscountHavePromo(): void
    {
        // Arrange
        $discountPromotionTransfer = (new DiscountPromotionBuilder([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]))->build();

        // Act
        $this->tester->getFacade()->createPromotionDiscount($discountPromotionTransfer);

        // Assert
        $this->assertTrue(
            $this->tester->getFacade()->isDiscountWithPromotion($discountPromotionTransfer->getFkDiscount())
        );
    }

    /**
     * @return void
     */
    public function testIsDiscountWithPromotionShouldReturnFalseIfDiscountDoesNotHavePromo(): void
    {
        // Arrange
        $discountGeneralTransfer = $this->tester->haveDiscount();

        // Assert
        $this->assertFalse(
            $this->tester->getFacade()->isDiscountWithPromotion($discountGeneralTransfer->getIdDiscount())
        );
    }

    /**
     * @return void
     */
    public function testDiscountPromotionCollectWhenNonNumericProductSkuUsed(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::ABSTRACT_SKU => 'DE-SKU',
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);
        $discountTransfer = (new DiscountBuilder([
            DiscountTransfer::ID_DISCOUNT => $discountPromotionTransfer->getFkDiscount(),
        ]))->build();

        $quoteTransfer = $this->tester->prepareQuoteWithStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveProductWithStock(
            [ProductAbstractTransfer::SKU => $discountPromotionTransfer->getAbstractSku()],
            [],
            $discountPromotionTransfer->getQuantity()
        );

        // Act
        $collectedDiscounts = $this->tester->getFacade()->collect($discountTransfer, $quoteTransfer);

        // Assert
        $this->assertCount(1, $quoteTransfer->getPromotionItems());
        $this->assertCount(0, $collectedDiscounts);
    }

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
}
