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
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

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
    protected const DE_STORE_NAME = 'DE';

    /**
     * @var \SprykerTest\Zed\DiscountPromotion\DiscountPromotionBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCollectWhenPromotionItemIsNotInCartShouldAddItToQuote()
    {
        // Arrange
        $promotionItemSku = '001';
        $promotionItemQuantity = 1;

        $discountGeneralTransfer = $this->tester->haveDiscount();
        $discountTransfer = (new DiscountTransfer())
            ->setIdDiscount($discountGeneralTransfer->getIdDiscount());

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DE_STORE_NAME]);
        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer);

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

        $this->getDiscountPromotionFacade()->createPromotionDiscount($discountPromotionTransfer);

        // Act
        $collectedDiscounts = $this->getDiscountPromotionFacade()->collect($discountTransfer, $quoteTransfer);

        // Assert
        $this->assertCount(1, $quoteTransfer->getPromotionItems());
        $this->assertCount(0, $collectedDiscounts);
    }

    /**
     * @return void
     */
    public function testCollectWhenPromotionItemIsAlreadyInCartShouldCollectIt()
    {
        // Arrange
        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $grossPrice = 100;
        $price = 80;
        $quantity = 1;

        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());
        $this->getDiscountPromotionFacade()->createPromotionDiscount($discountPromotionTransfer);

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setIdDiscount($discountGeneralTransfer->getIdDiscount());

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DE_STORE_NAME]);
        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer);
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setAbstractSku($promotionItemSku);
        $itemTransfer->setQuantity($quantity);
        $itemTransfer->setIdDiscountPromotion($discountPromotionTransfer->getIdDiscountPromotion());
        $itemTransfer->setUnitGrossPrice($grossPrice);
        $itemTransfer->setUnitPrice($price);
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $collectedDiscounts = $this->getDiscountPromotionFacade()->collect($discountTransfer, $quoteTransfer);

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
    public function testCollectWhenItemIsNotAvailableShouldSkipPromotion()
    {
        // Arrange
        $promotionItemSku = 'promotion-001';
        $promotionItemQuantity = 1;
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);

        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setIdDiscount($discountGeneralTransfer->getIdDiscount());

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DE_STORE_NAME]);
        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer);

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

        $this->getDiscountPromotionFacade()->createPromotionDiscount($discountPromotionTransfer);

        $productTransfer = $this->tester->haveProduct([], ['sku' => $promotionItemSku]);
        $this->tester->haveAvailabilityAbstract(
            (new ProductConcreteTransfer())->setSku($productTransfer->getSku()),
            new Decimal(0)
        );

        // Act
        $collectedDiscounts = $this->getDiscountPromotionFacade()->collect($discountTransfer, $quoteTransfer);

        // Assert
        $this->assertCount(0, $quoteTransfer->getPromotionItems());
        $this->assertCount(0, $collectedDiscounts);
    }

    /**
     * @return void
     */
    public function testCollectAdjustsQuantityBasedOnAvailability()
    {
        // Arrange
        $promotionItemSku = '001';
        $promotionItemQuantity = 5;
        $grossPrice = 100;
        $price = 80;
        $quantity = 1;

        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());
        $this->getDiscountPromotionFacade()->createPromotionDiscount($discountPromotionTransfer);

        $discountTransfer = (new DiscountTransfer())
            ->setIdDiscount($discountGeneralTransfer->getIdDiscount());

        $itemTransfer = (new ItemTransfer())
            ->setAbstractSku($promotionItemSku)
            ->setQuantity($quantity)
            ->setIdDiscountPromotion($discountPromotionTransfer->getIdDiscountPromotion())
            ->setUnitGrossPrice($grossPrice)
            ->setUnitPrice($price);

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DE_STORE_NAME]);
        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer);
        $quoteTransfer->addItem($itemTransfer);

        // Act
        $collectedDiscounts = $this->getDiscountPromotionFacade()->collect($discountTransfer, $quoteTransfer);

        // Assert
        $promotionItemTransfer = $quoteTransfer->getItems()[0];

        $this->assertCount(0, $quoteTransfer->getPromotionItems());
        $this->assertSame($quantity, $collectedDiscounts[0]->getQuantity());
        $this->assertSame($promotionItemQuantity, $promotionItemTransfer->getMaxQuantity());
    }

    /**
     * @return void
     */
    public function testSavePromotionDiscountShouldHavePersistedPromotionDiscount()
    {
        // Arrange
        $promotionItemSku = '001';
        $promotionItemQuantity = 1;

        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

        $discountPromotionTransferSaved = $this->getDiscountPromotionFacade()->createPromotionDiscount($discountPromotionTransfer);

        $this->assertNotEmpty($discountPromotionTransferSaved);

        // Act
        $discountPromotionTransfer = $this->getDiscountPromotionFacade()
            ->findDiscountPromotionByIdDiscountPromotion($discountPromotionTransferSaved->getIdDiscountPromotion());

        // Assert
        $this->assertNotNull($discountPromotionTransfer);
        $this->assertSame($discountPromotionTransferSaved->getIdDiscountPromotion(), $discountPromotionTransfer->getIdDiscountPromotion());
    }

    /**
     * @return void
     */
    public function testUpdateDiscountPromotionShouldUpdateExistingPromotion()
    {
        // Arrange
        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

        $discountPromotionTransferSaved = $this->getDiscountPromotionFacade()->createPromotionDiscount($discountPromotionTransfer);

        $updateSku = '321';
        $discountPromotionTransferSaved->setAbstractSku($updateSku);

        // Act
        $this->getDiscountPromotionFacade()->updatePromotionDiscount($discountPromotionTransferSaved);

        // Assert
        $discountPromotionTransferUpdated = $this->getDiscountPromotionFacade()->findDiscountPromotionByIdDiscountPromotion(
            $discountPromotionTransferSaved->getIdDiscountPromotion()
        );
        $this->assertSame($discountPromotionTransferUpdated->getAbstractSku(), $updateSku);
    }

    /**
     * @return void
     */
    public function testDeletePromotionDiscountShouldDeleteAnyExistingPromotions()
    {
        // Arrange
        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

        $discountPromotionTransferSaved = $this->getDiscountPromotionFacade()->createPromotionDiscount($discountPromotionTransfer);

        // Act
        $this->getDiscountPromotionFacade()->removePromotionByIdDiscount($discountPromotionTransferSaved->getFkDiscount());

        $discountPromotionTransferUpdated = $this->getDiscountPromotionFacade()->findDiscountPromotionByIdDiscount(
            $discountPromotionTransferSaved->getFkDiscount()
        );

        // Assert
        $this->assertNull($discountPromotionTransferUpdated);
    }

    /**
     * @return void
     */
    public function testDeletePromotionDiscountShouldNotFailIfThereWasNoExistingPromotion()
    {
        // Arrange
        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

        // Act
        $this->getDiscountPromotionFacade()->removePromotionByIdDiscount($discountPromotionTransfer->getFkDiscount());

        // Assert
        $discountPromotionTransferUpdated = $this->getDiscountPromotionFacade()
            ->findDiscountPromotionByIdDiscount(
                $discountPromotionTransfer->getFkDiscount()
            );
        $this->assertEmpty($discountPromotionTransferUpdated);
    }

    /**
     * @return void
     */
    public function testFindDiscountPromotionByIdDiscountPromotionShouldReturnPersistedPromotion()
    {
        // Arrange
        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

        $discountPromotionTransferSaved = $this->getDiscountPromotionFacade()->createPromotionDiscount($discountPromotionTransfer);

        // Act
        $discountPromotionTransferRead = $this->getDiscountPromotionFacade()->findDiscountPromotionByIdDiscountPromotion(
            $discountPromotionTransferSaved->getIdDiscountPromotion()
        );

        // Assert
        $this->assertNotNull($discountPromotionTransferRead);
    }

    /**
     * @return void
     */
    public function testExpandDiscountConfigurationWithPromotionShouldPopulateConfigurationObjectWithPromotion()
    {
        // Arrange
        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

        $this->getDiscountPromotionFacade()->createPromotionDiscount($discountPromotionTransfer);

        $discountConfigurationTransfer = new DiscountConfiguratorTransfer();
        $discountConfigurationTransfer->setDiscountGeneral($discountGeneralTransfer);
        $discountConfigurationTransfer->setDiscountCalculator(new DiscountCalculatorTransfer());

        // Act
        $discountConfigurationTransfer = $this->getDiscountPromotionFacade()
            ->expandDiscountConfigurationWithPromotion(
                $discountConfigurationTransfer
            );

        // Assert
        $this->assertNotEmpty($discountConfigurationTransfer->getDiscountCalculator()->getDiscountPromotion());
    }

    /**
     * @return void
     */
    public function testIsDiscountWithPromotionShouldReturnTrueIfDiscountHavePromo()
    {
        // Arrange
        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

        // Act
        $this->getDiscountPromotionFacade()->createPromotionDiscount($discountPromotionTransfer);

        // Assert
        $this->assertTrue(
            $this->getDiscountPromotionFacade()->isDiscountWithPromotion($discountGeneralTransfer->getIdDiscount())
        );
    }

    /**
     * @return void
     */
    public function testIsDiscountWithPromotionShouldReturnFalseIfDiscountDoesNotHavePromo()
    {
        // Arrange
        $discountGeneralTransfer = $this->tester->haveDiscount();

        // Assert
        $this->assertFalse(
            $this->getDiscountPromotionFacade()->isDiscountWithPromotion($discountGeneralTransfer->getIdDiscount())
        );
    }

    /**
     * @return void
     */
    public function testDiscountPromotionCollectWhenNonNumericProductSkuUsed()
    {
        // Arrange
        $localeTransfer = $this->getLocaleFacade()->getCurrentLocale();

        $abstractSku = 'DE-SKU';
        $productConcreteTransfer = $this->tester->haveProduct(
            [],
            [
                ProductAbstractTransfer::SKU => $abstractSku,
                ProductAbstractTransfer::LOCALIZED_ATTRIBUTES => [
                    [
                        LocalizedAttributesTransfer::LOCALE => $localeTransfer,
                        LocalizedAttributesTransfer::NAME => 'Test product',
                    ],
                ],
            ]
        );

        $this->tester->haveProductInStock([
            StockProductTransfer::SKU => $productConcreteTransfer->getSku(),
            StockProductTransfer::QUANTITY => 5,
        ]);

        $this->getAvailabilityFacade()->updateAvailability($productConcreteTransfer->getSku());

        $discountGeneralTransfer = $this->tester->haveDiscount();

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setIdDiscount($discountGeneralTransfer->getIdDiscount());

        $promotionItemQuantity = 1;
        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($abstractSku, $promotionItemQuantity);
        $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());

        $this->getDiscountPromotionFacade()->createPromotionDiscount($discountPromotionTransfer);

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DE_STORE_NAME]);
        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer);

        // Act
        $collectedDiscounts = $this->getDiscountPromotionFacade()->collect($discountTransfer, $quoteTransfer);

        // Assert
        $this->assertCount(1, $quoteTransfer->getPromotionItems());
        $this->assertCount(0, $collectedDiscounts);
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
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected function getLocaleFacade()
    {
        return $this->tester->getLocator()->locale()->facade();
    }

    /**
     * @param string $promotionSku
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    protected function createDiscountPromotionTransfer($promotionSku, $quantity)
    {
        return (new DiscountPromotionTransfer())
            ->setAbstractSku($promotionSku)
            ->setQuantity($quantity);
    }
}
