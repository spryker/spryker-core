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
use Generated\Shared\Transfer\DiscountPromotionConditionsTransfer;
use Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\PromotionItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepository;

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
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const TEST_ITEM_SKU = 'test_sku';

    /**
     * @var int
     */
    protected const TEST_NOT_EXISTING_DISCOUNT_PROMOTION_ID = 0;

    /**
     * @var string
     */
    protected const CART_OPERATION_ADD = 'add';

    /**
     * @var string
     */
    protected const INVALID_CART_OPERATION_ADD = 'invalid operation';

    /**
     * @var string
     */
    protected const TEST_ABSTRACT_SKU = 'sku-123';

    /**
     * @var string
     */
    protected const TEST_ABSTRACT_SKU_2 = 'sku-130';

    /**
     * @var int
     */
    protected const TEST_FAKE_ID_DISCOUNT = -1;

    /**
     * @var string
     */
    protected const TEST_FAKE_UUID = 'fake-uuid';

    /**
     * @var \SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepository
     */
    protected $discountPromotionRepository;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->discountPromotionRepository = new DiscountPromotionRepository();
    }

    /**
     * @return void
     */
    public function testCollectWhenPromotionItemIsNotInCartShouldAddItToQuote(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
            DiscountPromotionTransfer::ABSTRACT_SKU => static::TEST_ABSTRACT_SKU,
            DiscountPromotionTransfer::ABSTRACT_SKUS => [static::TEST_ABSTRACT_SKU],
        ]);
        $discountTransfer = (new DiscountBuilder([
            DiscountTransfer::ID_DISCOUNT => $discountPromotionTransfer->getFkDiscount(),
        ]))->build();

        $quoteTransfer = $this->tester->prepareQuoteWithStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveProductWithStock(
            [ProductAbstractTransfer::SKU => static::TEST_ABSTRACT_SKU],
            [],
            $discountPromotionTransfer->getQuantity(),
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
            DiscountPromotionTransfer::ABSTRACT_SKU => static::TEST_ABSTRACT_SKU,
            DiscountPromotionTransfer::ABSTRACT_SKUS => [static::TEST_ABSTRACT_SKU],
        ]);
        $discountTransfer = (new DiscountBuilder([
            DiscountTransfer::ID_DISCOUNT => $discountPromotionTransfer->getFkDiscount(),
        ]))->build();

        $quoteTransfer = $this->tester->prepareQuoteWithStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveProductWithStock(
            [ProductAbstractTransfer::SKU => static::TEST_ABSTRACT_SKU],
            [],
            $discountPromotionTransfer->getQuantity(),
        );

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::ABSTRACT_SKU => static::TEST_ABSTRACT_SKU,
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
            DiscountPromotionTransfer::ABSTRACT_SKU => static::TEST_ABSTRACT_SKU,
            DiscountPromotionTransfer::ABSTRACT_SKUS => [static::TEST_ABSTRACT_SKU],
        ]);
        $discountTransfer = (new DiscountBuilder([
            DiscountTransfer::ID_DISCOUNT => $discountPromotionTransfer->getFkDiscount(),
        ]))->build();

        $quoteTransfer = $this->tester->prepareQuoteWithStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveProductWithStock(
            [ProductAbstractTransfer::SKU => static::TEST_ABSTRACT_SKU],
            [],
            0,
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
            DiscountPromotionTransfer::ABSTRACT_SKU => static::TEST_ABSTRACT_SKU,
            DiscountPromotionTransfer::ABSTRACT_SKUS => [static::TEST_ABSTRACT_SKU],
        ]);
        $discountTransfer = (new DiscountBuilder([
            DiscountTransfer::ID_DISCOUNT => $discountPromotionTransfer->getFkDiscount(),
        ]))->build();

        $quoteTransfer = $this->tester->prepareQuoteWithStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveProductWithStock(
            [ProductAbstractTransfer::SKU => static::TEST_ABSTRACT_SKU],
            [],
            $promotionItemQuantity,
        );

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::ABSTRACT_SKU => static::TEST_ABSTRACT_SKU,
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
            DiscountPromotionTransfer::ABSTRACT_SKU => static::TEST_ABSTRACT_SKU,
            DiscountPromotionTransfer::ABSTRACT_SKUS => [static::TEST_ABSTRACT_SKU],
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
    public function testUpdateDiscountPromotionShouldReturnTransferEvenIfDiscountDoesNotExists(): void
    {
        // Arrange
        $discountPromotionTransferSaved = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
            DiscountPromotionTransfer::ABSTRACT_SKU => static::TEST_ABSTRACT_SKU,
            DiscountPromotionTransfer::ABSTRACT_SKUS => [static::TEST_ABSTRACT_SKU],
        ]);

        $discountPromotionTransferSaved->setIdDiscountPromotion(99999);

        // Act
        $discountPromotionTransfer = $this->tester->getFacade()->updatePromotionDiscount($discountPromotionTransferSaved);

        // Assert
        $this->assertNull($discountPromotionTransfer->getIdDiscountPromotion());
    }

    /**
     * @return void
     */
    public function testUpdateDiscountPromotionShouldUpdateExistingPromotion(): void
    {
        if ($this->discountPromotionRepository->isAbstractSkusFieldExists()) {
            $this->markTestSkipped('This test is not suitable for discount promotion with multiple abstract products functionality.');
        }

        // Arrange
        $discountPromotionTransferSaved = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $expectedQuantity = 3;
        $discountPromotionTransferSaved->setQuantity($expectedQuantity)
            ->setAbstractSku(static::TEST_ABSTRACT_SKU);

        // Act
        $this->tester->getFacade()->updatePromotionDiscount($discountPromotionTransferSaved);

        // Assert
        $discountPromotionTransferUpdated = $this->tester->getFacade()->findDiscountPromotionByIdDiscountPromotion(
            $discountPromotionTransferSaved->getIdDiscountPromotion(),
        );

        $this->assertSame($discountPromotionTransferUpdated->getQuantity(), $expectedQuantity);
        $this->assertSame($discountPromotionTransferUpdated->getAbstractSku(), static::TEST_ABSTRACT_SKU);
        $this->assertEmpty($discountPromotionTransferUpdated->getAbstractSkus());
    }

    /**
     * @return void
     */
    public function testUpdateDiscountPromotionShouldUpdateExistingPromotionWithMultipleAbstractSkus(): void
    {
        if (!$this->discountPromotionRepository->isAbstractSkusFieldExists()) {
            $this->markTestSkipped('This test is not suitable for discount promotion without multiple abstract products functionality.');
        }

        // Arrange
        $discountPromotionTransferSaved = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $expectedAbstractSkus = [static::TEST_ABSTRACT_SKU, static::TEST_ABSTRACT_SKU_2];
        $expectedQuantity = 3;
        $discountPromotionTransferSaved
            ->setQuantity($expectedQuantity)
            ->setAbstractSkus($expectedAbstractSkus);

        // Act
        $this->tester->getFacade()->updatePromotionDiscount($discountPromotionTransferSaved);

        // Assert
        $discountPromotionTransferUpdated = $this->tester->getFacade()->findDiscountPromotionByIdDiscountPromotion(
            $discountPromotionTransferSaved->getIdDiscountPromotion(),
        );

        $this->assertSame($discountPromotionTransferUpdated->getQuantity(), $expectedQuantity);
        $this->assertSame($discountPromotionTransferUpdated->getAbstractSkus(), $expectedAbstractSkus);
        $this->assertEmpty($discountPromotionTransferUpdated->getAbstractSku());
    }

    /**
     * @return void
     */
    public function testCreateDiscountPromotionShouldCreatePromotion(): void
    {
        if ($this->discountPromotionRepository->isAbstractSkusFieldExists()) {
            $this->markTestSkipped('This test is not suitable for discount promotion with multiple abstract products functionality.');
        }

        // Arrange
        $discountPromotionTransfer = (new DiscountPromotionTransfer())
            ->setAbstractSku(static::TEST_ABSTRACT_SKU)
            ->setFkDiscount($this->tester->haveDiscount()->getIdDiscount())
            ->setQuantity(3);

        // Act
        $discountPromotionTransferCreated = $this->tester->getFacade()->createPromotionDiscount($discountPromotionTransfer);

        // Assert
        $this->assertSame($discountPromotionTransferCreated->getQuantity(), $discountPromotionTransfer->getQuantity());
        $this->assertSame($discountPromotionTransferCreated->getAbstractSku(), $discountPromotionTransfer->getAbstractSku());
        $this->assertEmpty($discountPromotionTransferCreated->getAbstractSkus());
    }

    /**
     * @return void
     */
    public function testCreateDiscountPromotionShouldCreatePromotionWithMultipleAbstractSkus(): void
    {
        if (!$this->discountPromotionRepository->isAbstractSkusFieldExists()) {
            $this->markTestSkipped('This test is not suitable for discount promotion without multiple abstract products functionality.');
        }

        $expectedAbstractSkus = [static::TEST_ABSTRACT_SKU, static::TEST_ABSTRACT_SKU_2];

        // Arrange
        $discountPromotionTransfer = (new DiscountPromotionBuilder([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
            DiscountPromotionTransfer::ABSTRACT_SKUS => $expectedAbstractSkus,
        ]))->build();

        // Act
        $discountPromotionTransferCreated = $this->tester->getFacade()->createPromotionDiscount($discountPromotionTransfer);

        // Assert
        $this->assertSame((int)$discountPromotionTransferCreated->getQuantity(), (int)$discountPromotionTransfer->getQuantity());
        $this->assertSame($discountPromotionTransferCreated->getAbstractSkus(), $discountPromotionTransfer->getAbstractSkus());
        $this->assertEmpty($discountPromotionTransferCreated->getAbstractSku());
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
    public function testValidateCartDiscountPromotionsWithPromotionItemInQuote(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        $itemTransfer = (new ItemTransfer())
            ->setSku(static::TEST_ITEM_SKU)
            ->setIdDiscountPromotion($discountPromotionTransfer->getIdDiscountPromotion());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setOperation(static::CART_OPERATION_ADD)
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer())->addItem($itemTransfer));

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
            $discountPromotionTransferSaved->getFkDiscount(),
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
                $discountPromotionTransfer->getFkDiscount(),
            );
        $this->assertEmpty($discountPromotionTransferUpdated);
    }

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
    public function testFindDiscountPromotionByIdDiscountPromotionShouldReturnPersistedPromotion(): void
    {
        // Arrange
        $discountPromotionTransferSaved = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);

        // Act
        $discountPromotionTransferRead = $this->tester->getFacade()->findDiscountPromotionByIdDiscountPromotion(
            $discountPromotionTransferSaved->getIdDiscountPromotion(),
        );

        // Assert
        $this->assertNotNull($discountPromotionTransferRead);
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
            $this->tester->getFacade()->isDiscountWithPromotion($discountPromotionTransfer->getFkDiscount()),
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
            $this->tester->getFacade()->isDiscountWithPromotion($discountGeneralTransfer->getIdDiscount()),
        );
    }

    /**
     * @return void
     */
    public function testDiscountPromotionCollectWhenNonNumericProductSkuUsed(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::ABSTRACT_SKU => static::TEST_ABSTRACT_SKU,
            DiscountPromotionTransfer::ABSTRACT_SKUS => [static::TEST_ABSTRACT_SKU],
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
        ]);
        $discountTransfer = (new DiscountBuilder([
            DiscountTransfer::ID_DISCOUNT => $discountPromotionTransfer->getFkDiscount(),
        ]))->build();

        $quoteTransfer = $this->tester->prepareQuoteWithStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveProductWithStock(
            [ProductAbstractTransfer::SKU => static::TEST_ABSTRACT_SKU],
            [],
            $discountPromotionTransfer->getQuantity(),
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

    /**
     * @return void
     */
    public function testDiscountPromotionCollectShouldReturnPromotionItemsWithProperQuantity(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::ABSTRACT_SKU => static::TEST_ABSTRACT_SKU,
            DiscountPromotionTransfer::ABSTRACT_SKUS => [static::TEST_ABSTRACT_SKU],
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
            DiscountPromotionTransfer::QUANTITY => 1,
        ]);
        $discountTransfer = (new DiscountBuilder([
            DiscountTransfer::ID_DISCOUNT => $discountPromotionTransfer->getFkDiscount(),
        ]))->build();

        $quoteTransfer = $this->tester->prepareQuoteWithStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveProductWithStock(
            [ProductAbstractTransfer::SKU => static::TEST_ABSTRACT_SKU],
            [],
            $discountPromotionTransfer->getQuantity(),
        );

        // Act
        $this->tester->getFacade()->collect($discountTransfer, $quoteTransfer);
        $promotionItemTransfers = $quoteTransfer->getPromotionItems();

        // Assert
        $this->assertCount(1, $promotionItemTransfers);
        $this->assertSame(1, $promotionItemTransfers->offsetGet(0)->getMaxQuantity());
    }

    /**
     * @return void
     */
    public function testDiscountPromotionCollectShouldReturnPromotionItemsWithProperQuantityWhenItemIsAddedToTheQuote(): void
    {
        if (!$this->discountPromotionRepository->isAbstractSkusFieldExists()) {
            $this->markTestSkipped('This test is not suitable for discount promotion without multiple abstract products functionality.');
        }

        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::ABSTRACT_SKU => static::TEST_ABSTRACT_SKU,
            DiscountPromotionTransfer::ABSTRACT_SKUS => [static::TEST_ABSTRACT_SKU, static::TEST_ABSTRACT_SKU_2],
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
            DiscountPromotionTransfer::QUANTITY => 3,
        ]);

        $discountTransfer = (new DiscountBuilder([
            DiscountTransfer::ID_DISCOUNT => $discountPromotionTransfer->getFkDiscount(),
        ]))->build();

        $quoteTransfer = $this->tester->prepareQuoteWithStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $quoteTransfer->addItem((new ItemBuilder([
            ItemTransfer::ABSTRACT_SKU => static::TEST_ABSTRACT_SKU,
            ItemTransfer::ID_DISCOUNT_PROMOTION => $discountPromotionTransfer->getIdDiscountPromotion(),
            ItemTransfer::QUANTITY => 2,
        ]))->build());

        $this->tester->haveProductWithStock(
            [ProductAbstractTransfer::SKU => static::TEST_ABSTRACT_SKU],
            [],
            $discountPromotionTransfer->getQuantity(),
        );

        $this->tester->haveProductWithStock(
            [ProductAbstractTransfer::SKU => static::TEST_ABSTRACT_SKU_2],
            [],
            $discountPromotionTransfer->getQuantity(),
        );

        // Act
        $discountableItemTransfers = $this->tester->getFacade()->collect($discountTransfer, $quoteTransfer);
        $promotionItemTransfers = $quoteTransfer->getPromotionItems();

        // Assert
        $this->assertCount(1, $promotionItemTransfers);
        $this->assertSame(1, $promotionItemTransfers->offsetGet(0)->getMaxQuantity());

        $this->assertCount(1, $discountableItemTransfers);
        $this->assertSame(2, $discountableItemTransfers[0]->getQuantity());
    }

    /**
     * @return void
     */
    public function testDiscountPromotionCollectShouldReturnPromotionItemsWithProperQuantityWhenItemsAreNotAddedToTheQuote(): void
    {
        if (!$this->discountPromotionRepository->isAbstractSkusFieldExists()) {
            $this->markTestSkipped('This test is not suitable for discount promotion without multiple abstract products functionality.');
        }

        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::ABSTRACT_SKU => static::TEST_ABSTRACT_SKU,
            DiscountPromotionTransfer::ABSTRACT_SKUS => [static::TEST_ABSTRACT_SKU, static::TEST_ABSTRACT_SKU_2],
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
            DiscountPromotionTransfer::QUANTITY => 3,
        ]);

        $discountTransfer = (new DiscountBuilder([
            DiscountTransfer::ID_DISCOUNT => $discountPromotionTransfer->getFkDiscount(),
        ]))->build();

        $quoteTransfer = $this->tester->prepareQuoteWithStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        $this->tester->haveProductWithStock(
            [ProductAbstractTransfer::SKU => static::TEST_ABSTRACT_SKU],
            [],
            $discountPromotionTransfer->getQuantity(),
        );

        $this->tester->haveProductWithStock(
            [ProductAbstractTransfer::SKU => static::TEST_ABSTRACT_SKU_2],
            [],
            $discountPromotionTransfer->getQuantity(),
        );

        // Act
        $this->tester->getFacade()->collect($discountTransfer, $quoteTransfer);
        $promotionItemTransfers = $quoteTransfer->getPromotionItems();

        // Assert
        $this->assertCount(2, $promotionItemTransfers);
        $this->assertSame(3, $promotionItemTransfers->offsetGet(0)->getMaxQuantity());
        $this->assertSame(3, $promotionItemTransfers->offsetGet(1)->getMaxQuantity());
    }

    /**
     * @return void
     */
    public function testDiscountPromotionCollectShouldFilterOutPromotionItemFromCollectionWhenItemInQuoteRaiseMaxQuantity(): void
    {
        if (!$this->discountPromotionRepository->isAbstractSkusFieldExists()) {
            $this->markTestSkipped('This test is not suitable for discount promotion without multiple abstract products functionality.');
        }

        // Arrange
        $discountPromotionTransfer = $this->tester->haveDiscountPromotion([
            DiscountPromotionTransfer::ABSTRACT_SKU => static::TEST_ABSTRACT_SKU,
            DiscountPromotionTransfer::ABSTRACT_SKUS => [static::TEST_ABSTRACT_SKU, static::TEST_ABSTRACT_SKU_2],
            DiscountPromotionTransfer::FK_DISCOUNT => $this->tester->haveDiscount()->getIdDiscount(),
            DiscountPromotionTransfer::QUANTITY => 3,
        ]);

        $discountTransfer = (new DiscountBuilder([
            DiscountTransfer::ID_DISCOUNT => $discountPromotionTransfer->getFkDiscount(),
        ]))->build();

        $quoteTransfer = $this->tester->prepareQuoteWithStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $quoteTransfer->addItem((new ItemBuilder([
            ItemTransfer::ABSTRACT_SKU => static::TEST_ABSTRACT_SKU,
            ItemTransfer::ID_DISCOUNT_PROMOTION => $discountPromotionTransfer->getIdDiscountPromotion(),
            ItemTransfer::QUANTITY => $discountPromotionTransfer->getQuantity(),
        ]))->build());

        $this->tester->haveProductWithStock(
            [ProductAbstractTransfer::SKU => static::TEST_ABSTRACT_SKU],
            [],
            $discountPromotionTransfer->getQuantity(),
        );

        $this->tester->haveProductWithStock(
            [ProductAbstractTransfer::SKU => static::TEST_ABSTRACT_SKU_2],
            [],
            $discountPromotionTransfer->getQuantity(),
        );

        // Act
        $discountableItemTransfers = $this->tester->getFacade()->collect($discountTransfer, $quoteTransfer);

        // Assert
        $this->assertCount(0, $quoteTransfer->getPromotionItems());
        $this->assertCount(1, $discountableItemTransfers);
        $this->assertSame(3, $discountableItemTransfers[0]->getQuantity());
    }
}
