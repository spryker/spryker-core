<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountPromotion\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\DiscountBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DiscountPromotion
 * @group Business
 * @group Facade
 * @group CollectTest
 * Add your own group annotations below this line
 */
class CollectTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester
     */
    protected DiscountPromotionBusinessTester $tester;

    /**
     * @var \Generated\Shared\Transfer\DiscountTransfer
     */
    protected DiscountTransfer $discountTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected QuoteTransfer $quoteTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->quoteTransfer = $this->tester->prepareQuoteWithStore([StoreTransfer::NAME => DiscountPromotionBusinessTester::STORE_NAME_DE]);
        $this->discountTransfer = (new DiscountBuilder([]))->build();
    }

    /**
     * @return void
     */
    public function testCollectWhenPromotionItemIsNotInCartShouldAddItToQuote(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->createDiscountPromotionWithProductStock();
        $this->discountTransfer->setIdDiscount($discountPromotionTransfer->getFkDiscount());

        // Act
        $collectedDiscounts = $this->tester->getFacade()->collect($this->discountTransfer, $this->quoteTransfer);

        // Assert
        $this->assertCount(1, $this->quoteTransfer->getPromotionItems());
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

        $discountPromotionTransfer = $this->tester->createDiscountPromotionWithProductStock();
        $this->discountTransfer->setIdDiscount($discountPromotionTransfer->getFkDiscount());

        $this->quoteTransfer->addItem((new ItemBuilder([
            ItemTransfer::ABSTRACT_SKU => DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU,
            ItemTransfer::ID_DISCOUNT_PROMOTION => $discountPromotionTransfer->getIdDiscountPromotion(),
            ItemTransfer::QUANTITY => $quantity,
            ItemTransfer::UNIT_GROSS_PRICE => $grossPrice,
            ItemTransfer::UNIT_PRICE => $price,
        ]))->build());

        // Act
        $collectedDiscounts = $this->tester->getFacade()->collect($this->discountTransfer, $this->quoteTransfer);

        // Assert
        $this->assertCount(0, $this->quoteTransfer->getPromotionItems());
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
        $discountPromotionTransfer = $this->tester->createDiscountPromotionWithProductStock([], 0);
        $this->discountTransfer->setIdDiscount($discountPromotionTransfer->getFkDiscount());

        // Act
        $collectedDiscounts = $this->tester->getFacade()->collect($this->discountTransfer, $this->quoteTransfer);

        // Assert
        $this->assertCount(0, $this->quoteTransfer->getPromotionItems());
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

        $discountPromotionTransfer = $this->tester->createDiscountPromotionWithProductStock([
            DiscountPromotionTransfer::QUANTITY => $promotionItemQuantity,
        ]);
        $this->discountTransfer->setIdDiscount($discountPromotionTransfer->getFkDiscount());

        $this->quoteTransfer->addItem((new ItemBuilder([
            ItemTransfer::ABSTRACT_SKU => DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU,
            ItemTransfer::ID_DISCOUNT_PROMOTION => $discountPromotionTransfer->getIdDiscountPromotion(),
            ItemTransfer::QUANTITY => $quantity,
        ]))->build());

        // Act
        $collectedDiscounts = $this->tester->getFacade()->collect($this->discountTransfer, $this->quoteTransfer);

        // Assert
        $promotionItemTransfer = $this->quoteTransfer->getItems()[0];

        $this->assertCount(0, $this->quoteTransfer->getPromotionItems());
        $this->assertSame($quantity, $collectedDiscounts[0]->getQuantity());
        $this->assertSame($promotionItemQuantity, $promotionItemTransfer->getMaxQuantity());
    }

    /**
     * @return void
     */
    public function testDiscountPromotionCollectWhenNonNumericProductSkuUsed(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->createDiscountPromotionWithProductStock();
        $this->discountTransfer->setIdDiscount($discountPromotionTransfer->getFkDiscount());

        // Act
        $collectedDiscounts = $this->tester->getFacade()->collect($this->discountTransfer, $this->quoteTransfer);

        // Assert
        $this->assertCount(1, $this->quoteTransfer->getPromotionItems());
        $this->assertCount(0, $collectedDiscounts);
    }

    /**
     * @return void
     */
    public function testDiscountPromotionCollectShouldReturnPromotionItemsWithProperQuantity(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->createDiscountPromotionWithProductStock([
            DiscountPromotionTransfer::QUANTITY => 1,
        ]);
        $this->discountTransfer->setIdDiscount($discountPromotionTransfer->getFkDiscount());

        // Act
        $this->tester->getFacade()->collect($this->discountTransfer, $this->quoteTransfer);

        // Assert
        $promotionItemTransfers = $this->quoteTransfer->getPromotionItems();
        $this->assertCount(1, $promotionItemTransfers);
        $this->assertSame(1, $promotionItemTransfers->offsetGet(0)->getMaxQuantity());
    }

    /**
     * @return void
     */
    public function testDiscountPromotionCollectShouldReturnPromotionItemsWithProperQuantityWhenItemIsAddedToTheQuote(): void
    {
        if (!$this->tester->isAbstractSkusFieldExists()) {
            $this->markTestSkipped('This test is not suitable for discount promotion without multiple abstract products functionality.');
        }

        // Arrange
        $discountPromotionTransfer = $this->tester->createDiscountPromotionWithProductStock([
            DiscountPromotionTransfer::ABSTRACT_SKUS => [DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU, DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU_2],
            DiscountPromotionTransfer::QUANTITY => 3,
        ]);
        $this->discountTransfer->setIdDiscount($discountPromotionTransfer->getFkDiscount());

        $this->quoteTransfer->addItem((new ItemBuilder([
            ItemTransfer::ABSTRACT_SKU => $discountPromotionTransfer->getAbstractSkus()[0],
            ItemTransfer::ID_DISCOUNT_PROMOTION => $discountPromotionTransfer->getIdDiscountPromotion(),
            ItemTransfer::QUANTITY => 2,
        ]))->build());

        // Act
        $discountableItemTransfers = $this->tester->getFacade()->collect($this->discountTransfer, $this->quoteTransfer);

        // Assert
        $promotionItemTransfers = $this->quoteTransfer->getPromotionItems();
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
        if (!$this->tester->isAbstractSkusFieldExists()) {
            $this->markTestSkipped('This test is not suitable for discount promotion without multiple abstract products functionality.');
        }

        // Arrange
        $discountPromotionTransfer = $this->tester->createDiscountPromotionWithProductStock([
            DiscountPromotionTransfer::ABSTRACT_SKUS => [DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU, DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU_2],
            DiscountPromotionTransfer::QUANTITY => 3,
        ]);
        $this->discountTransfer->setIdDiscount($discountPromotionTransfer->getFkDiscount());

        // Act
        $this->tester->getFacade()->collect($this->discountTransfer, $this->quoteTransfer);

        // Assert
        $promotionItemTransfers = $this->quoteTransfer->getPromotionItems();
        $this->assertCount(2, $promotionItemTransfers);
        $this->assertSame(3, $promotionItemTransfers->offsetGet(0)->getMaxQuantity());
        $this->assertSame(3, $promotionItemTransfers->offsetGet(1)->getMaxQuantity());
    }

    /**
     * @return void
     */
    public function testDiscountPromotionCollectShouldFilterOutPromotionItemFromCollectionWhenItemInQuoteRaiseMaxQuantity(): void
    {
        if (!$this->tester->isAbstractSkusFieldExists()) {
            $this->markTestSkipped('This test is not suitable for discount promotion without multiple abstract products functionality.');
        }

        // Arrange
        $discountPromotionTransfer = $this->tester->createDiscountPromotionWithProductStock([
            DiscountPromotionTransfer::ABSTRACT_SKUS => [DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU, DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU_2],
            DiscountPromotionTransfer::QUANTITY => 3,
        ]);
        $this->discountTransfer->setIdDiscount($discountPromotionTransfer->getFkDiscount());

        $this->quoteTransfer->addItem((new ItemBuilder([
            ItemTransfer::ABSTRACT_SKU => DiscountPromotionBusinessTester::TEST_ABSTRACT_SKU,
            ItemTransfer::ID_DISCOUNT_PROMOTION => $discountPromotionTransfer->getIdDiscountPromotion(),
            ItemTransfer::QUANTITY => $discountPromotionTransfer->getQuantity(),
        ]))->build());

        // Act
        $discountableItemTransfers = $this->tester->getFacade()->collect($this->discountTransfer, $this->quoteTransfer);

        // Assert
        $this->assertCount(0, $this->quoteTransfer->getPromotionItems());
        $this->assertCount(1, $discountableItemTransfers);
        $this->assertSame(3, $discountableItemTransfers[0]->getQuantity());
    }
}
