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
use Spryker\Zed\Availability\Business\AvailabilityFacadeInterface;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\Stock\Business\StockFacadeInterface;

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
        $discountPromotionTransfer = $this->tester->getDiscountPromotionTransfer('001', 1);
        $discountTransfer = (new DiscountTransfer())
            ->setIdDiscount($discountPromotionTransfer->getFkDiscount());

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer);

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
    public function testCollectWhenPromotionItemIsAlreadyInCartShouldCollectIt(): void
    {
        // Arrange
        $promotionItemSku = '001';
        $promotionItemQuantity = 1;
        $grossPrice = 100;
        $price = 80;
        $quantity = 1;

        $discountPromotionTransfer = $this->tester->getDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer = $this->getDiscountPromotionFacade()
            ->createPromotionDiscount($discountPromotionTransfer);

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setIdDiscount($discountPromotionTransfer->getFkDiscount());

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
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
    public function testCollectWhenItemIsNotAvailableShouldSkipPromotion(): void
    {
        // Arrange
        $promotionItemSku = 'promotion-001';
        $promotionItemQuantity = 1;

        $discountPromotionTransfer = $this->tester->getDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setIdDiscount($discountPromotionTransfer->getFkDiscount());

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer);

        $this->getDiscountPromotionFacade()->createPromotionDiscount($discountPromotionTransfer);

        $productTransfer = $this->tester->haveProduct([], ['sku' => $promotionItemSku]);
        $this->tester->haveAvailabilityConcrete(
            $productTransfer->getSku(),
            $storeTransfer,
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
    public function testCollectAdjustsQuantityBasedOnAvailability(): void
    {
        // Arrange
        $promotionItemSku = '001';
        $promotionItemQuantity = 5;
        $grossPrice = 100;
        $price = 80;
        $quantity = 1;

        $discountPromotionTransfer = $this->tester->getDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);
        $discountPromotionTransfer = $this->getDiscountPromotionFacade()
            ->createPromotionDiscount($discountPromotionTransfer);

        $discountTransfer = (new DiscountTransfer())
            ->setIdDiscount($discountPromotionTransfer->getFkDiscount());

        $itemTransfer = (new ItemTransfer())
            ->setAbstractSku($promotionItemSku)
            ->setQuantity($quantity)
            ->setIdDiscountPromotion($discountPromotionTransfer->getIdDiscountPromotion())
            ->setUnitGrossPrice($grossPrice)
            ->setUnitPrice($price);

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
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
    public function testSavePromotionDiscountShouldHavePersistedPromotionDiscount(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->getDiscountPromotionTransfer('001', 1);
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
    public function testUpdateDiscountPromotionShouldUpdateExistingPromotion(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->getDiscountPromotionTransfer('001', 1);
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
    public function testDeletePromotionDiscountShouldDeleteAnyExistingPromotions(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->getDiscountPromotionTransfer('001', 1);
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
    public function testDeletePromotionDiscountShouldNotFailIfThereWasNoExistingPromotion(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->getDiscountPromotionTransfer('001', 1);

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
    public function testFindDiscountPromotionByIdDiscountPromotionShouldReturnPersistedPromotion(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->getDiscountPromotionTransfer('001', 1);
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
    public function testExpandDiscountConfigurationWithPromotionShouldPopulateConfigurationObjectWithPromotion(): void
    {
        // Arrange
        $discountGeneralTransfer = $this->tester->haveDiscount();
        $discountPromotionTransfer = $this->tester->mapDiscountPromotionAttributesToTransfer([
            DiscountPromotionTransfer::ABSTRACT_SKU => '001',
            DiscountPromotionTransfer::QUANTITY => 1,
            DiscountPromotionTransfer::FK_DISCOUNT => $discountGeneralTransfer->getIdDiscount(),
        ]);

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
    public function testIsDiscountWithPromotionShouldReturnTrueIfDiscountHavePromo(): void
    {
        // Arrange
        $discountPromotionTransfer = $this->tester->getDiscountPromotionTransfer('001', 1);

        // Act
        $this->getDiscountPromotionFacade()->createPromotionDiscount($discountPromotionTransfer);

        // Assert
        $this->assertTrue(
            $this->getDiscountPromotionFacade()->isDiscountWithPromotion($discountPromotionTransfer->getFkDiscount())
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
            $this->getDiscountPromotionFacade()->isDiscountWithPromotion($discountGeneralTransfer->getIdDiscount())
        );
    }

    /**
     * @return void
     */
    public function testDiscountPromotionCollectWhenNonNumericProductSkuUsed(): void
    {
        // Arrange
        $localeTransfer = $this->getLocaleFacade()->getCurrentLocale();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

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

        $this->addStockForProduct($productConcreteTransfer);

        $this->getAvailabilityFacade()->updateAvailability($productConcreteTransfer->getSku());

        $abstractSku = $this->getProductFacade()->getAbstractSkuFromProductConcrete($productConcreteTransfer->getSku());

        $discountPromotionTransfer = $this->tester->getDiscountPromotionTransfer($abstractSku, 1);

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setIdDiscount($discountPromotionTransfer->getFkDiscount());

        $this->getDiscountPromotionFacade()->createPromotionDiscount($discountPromotionTransfer);

        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer);

        // Act
        $collectedDiscounts = $this->getDiscountPromotionFacade()->collect($discountTransfer, $quoteTransfer);

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
        $discountPromotionTransfer = $this->tester->getDiscountPromotionTransfer('001', 1);

        $discountPromotionTransferSaved = $this->getDiscountPromotionFacade()
            ->createPromotionDiscount($discountPromotionTransfer);

        // Act
        $discountPromotionTransferRead = $this->getDiscountPromotionFacade()
            ->findDiscountPromotionByUuid($discountPromotionTransferSaved->getUuid());

        // Assert
        $this->assertNotNull($discountPromotionTransferRead);
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
    protected function getAvailabilityFacade(): AvailabilityFacadeInterface
    {
        return $this->tester->getLocator()->availability()->facade();
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected function getProductFacade(): ProductFacadeInterface
    {
        return $this->tester->getLocator()->product()->facade();
    }

    /**
     * @return \Spryker\Zed\Stock\Business\StockFacadeInterface
     */
    protected function getStockFacade(): StockFacadeInterface
    {
        return $this->tester->getLocator()->stock()->facade();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->tester->getLocator()->locale()->facade();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function addStockForProduct(ProductConcreteTransfer $productConcreteTransfer): void
    {
        $availableStockTypes = $this->getStockFacade()->getAvailableStockTypes();
        foreach ($availableStockTypes as $stockType) {
            $stockProductTransfer = (new StockProductTransfer())
                ->setSku($productConcreteTransfer->getSku())
                ->setQuantity(5)
                ->setStockType($stockType);

            $this->getStockFacade()->createStockProduct($stockProductTransfer);
        }
    }
}
