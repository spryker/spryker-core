<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\AvailabilityGui\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use InvalidArgumentException;
use Orm\Zed\AvailabilityGui\Persistence\SpyAvailabilityGui;
use Orm\Zed\AvailabilityGui\Persistence\SpyAvailabilityGuiAbstract;
use Orm\Zed\AvailabilityGui\Persistence\SpyAvailabilityGuiQuery;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockProduct;
use Spryker\Zed\AvailabilityGui\Business\AvailabilityGuiFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group AvailabilityGui
 * @group Business
 * @group AvailabilityGuiFacadeTest
 */
class AvailabilityGuiFacadeTest extends Test
{

    const ABSTRACT_SKU = '123_AvailabilityGui_test';
    const CONCRETE_SKU = '123_AvailabilityGui_test-concrete';

    /**
     * @return void
     */
    public function testIsProductSellableWhenNeverOutOfStockShouldReturnSuccess()
    {
        $AvailabilityGuiFacade = $this->createAvailabilityGuiFacade();

        $this->createProductWithStock(self::ABSTRACT_SKU, self::CONCRETE_SKU, ['is_never_out_of_stock' => true]);

        $isProductSellable = $AvailabilityGuiFacade->isProductSellable(self::CONCRETE_SKU, 1);

        $this->assertTrue($isProductSellable);
    }

    /**
     * @return void
     */
    public function testIsProductSellableWhenStockIsEmptyShouldReturnFailure()
    {
        $AvailabilityGuiFacade = $this->createAvailabilityGuiFacade();

        $this->createProductWithStock(self::ABSTRACT_SKU, self::CONCRETE_SKU,  ['quantity' => 0]);

        $isProductSellable = $AvailabilityGuiFacade->isProductSellable(self::CONCRETE_SKU, 1);

        $this->assertFalse($isProductSellable);
    }

    /**
     * @return void
     */
    public function testIsProductSellableWhenStockFulfilledShouldReturnSuccess()
    {
        $AvailabilityGuiFacade = $this->createAvailabilityGuiFacade();

        $this->createProductWithStock(self::ABSTRACT_SKU, self::CONCRETE_SKU, ['quantity' => 5]);

        $isProductSellable = $AvailabilityGuiFacade->isProductSellable(self::CONCRETE_SKU, 1);

        $this->assertTrue($isProductSellable);
    }

    /**
     * @return void
     */
    public function testCalculateStockForProductShouldReturnPersistedStock()
    {
        $AvailabilityGuiFacade = $this->createAvailabilityGuiFacade();

        $quantity = 5;

        $this->createProductWithStock(self::ABSTRACT_SKU, self::CONCRETE_SKU, ['quantity' => $quantity]);

        $calculatedQuantity = $AvailabilityGuiFacade->calculateStockForProduct(self::CONCRETE_SKU);

        $this->assertSame($quantity, $calculatedQuantity);
    }

    /**
     * @return void
     */
    public function testCalculateStockWhenProductDoesNotExistsShouldThrowException()
    {
        $this->expectException(InvalidArgumentException::class);
        $AvailabilityGuiFacade = $this->createAvailabilityGuiFacade();
        $AvailabilityGuiFacade->calculateStockForProduct(self::CONCRETE_SKU);
    }

    /**
     * @return void
     */
    public function testCheckAvailabilityGuiPrecoditionShouldNotWriteErrorsWhenAvailabilityGuiIsSatisfied()
    {
        $AvailabilityGuiFacade = $this->createAvailabilityGuiFacade();

        $this->createProductWithStock(self::ABSTRACT_SKU, self::CONCRETE_SKU, ['quantity' => 5]);

        $quoteTransfer = $this->createQuoteTransfer();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $AvailabilityGuiFacade->checkoutAvailabilityGuiPreCondition($quoteTransfer, $checkoutResponseTransfer);

        $this->assertEmpty($checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCheckAvailabilityGuiPrecoditionShouldWriteErrorWhenAvailabilityGuiIsNotSatisfied()
    {
        $AvailabilityGuiFacade = $this->createAvailabilityGuiFacade();

        $this->createProductWithStock(self::ABSTRACT_SKU, self::CONCRETE_SKU, ['quantity' => 0]);

        $quoteTransfer = $this->createQuoteTransfer();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        $AvailabilityGuiFacade->checkoutAvailabilityGuiPreCondition($quoteTransfer, $checkoutResponseTransfer);

        $this->assertNotEmpty($checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testUpdateAvailabilityGuiShouldStoreNewQuantity()
    {
        $AvailabilityGuiFacade = $this->createAvailabilityGuiFacade();

        $stockProductEntity = $this->createProductWithStock(self::ABSTRACT_SKU, self::CONCRETE_SKU, ['quantity' => 5]);

        $stockProductEntity->setQuantity(50);
        $stockProductEntity->save();

        $AvailabilityGuiFacade->updateAvailabilityGui(self::CONCRETE_SKU);

        $AvailabilityGuiEntity = SpyAvailabilityGuiQuery::create()->findOneBySku(self::CONCRETE_SKU);

        $this->assertSame(50, $AvailabilityGuiEntity->getQuantity());
    }

    /**
     * @return void
     */
    public function testUpdateAvailabilityGuiWhenItsEmptyShouldStoreNewQuantity()
    {
        $AvailabilityGuiFacade = $this->createAvailabilityGuiFacade();

        $this->createProductWithStock(self::ABSTRACT_SKU, self::CONCRETE_SKU, ['quantity' => 50]);

        $this->createProductAvailabilityGui();

        $AvailabilityGuiFacade->updateAvailabilityGui(self::CONCRETE_SKU);

        $AvailabilityGuiEntity = SpyAvailabilityGuiQuery::create()->findOneBySku(self::CONCRETE_SKU);

        $this->assertSame(50, $AvailabilityGuiEntity->getQuantity());
    }

    /**
     * @return void
     */
    public function testUpdateAvailabilityGuiWhenSetToEmptyShouldStoreEmptyQuantity()
    {
        $AvailabilityGuiFacade = $this->createAvailabilityGuiFacade();

        $this->createProductWithStock(self::ABSTRACT_SKU, self::CONCRETE_SKU, ['quantity' => 0]);

        $AvailabilityGuiEntity = $this->createProductAvailabilityGui(5);

        $this->assertSame(5, $AvailabilityGuiEntity->getQuantity());

        $AvailabilityGuiFacade->updateAvailabilityGui(self::CONCRETE_SKU);

        $AvailabilityGuiEntity = SpyAvailabilityGuiQuery::create()->findOneBySku(self::CONCRETE_SKU);

        $this->assertSame(0, $AvailabilityGuiEntity->getQuantity());
    }

    /**
     * @return \Spryker\Zed\AvailabilityGui\Business\AvailabilityGuiFacade
     */
    protected function createAvailabilityGuiFacade()
    {
        return new AvailabilityGuiFacade();
    }

    /**
     * @param string $abstractSku
     * @param string $concreteSku
     * @param array $stockData
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProduct
     */
    protected function createProductWithStock($abstractSku, $concreteSku, array $stockData)
    {
        $productAbstractEntity = new SpyProductAbstract();
        $productAbstractEntity->setSku($abstractSku);
        $productAbstractEntity->setAttributes('');
        $productAbstractEntity->save();

        $productEntity = new SpyProduct();
        $productEntity->setSku($concreteSku);
        $productEntity->setAttributes('');
        $productEntity->setIsActive(true);
        $productEntity->setFkProductAbstract($productAbstractEntity->getIdProductAbstract());
        $productEntity->save();

        $stockEntity = new SpyStock();
        $stockEntity->setName('test-case-warehause');
        $stockEntity->save();

        $stockProductEntity = new SpyStockProduct();
        $stockProductEntity->fromArray($stockData);
        $stockProductEntity->setFkProduct($productEntity->getIdProduct());
        $stockProductEntity->setFkStock($stockEntity->getIdStock());
        $stockProductEntity->save();

        return $stockProductEntity;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku(self::CONCRETE_SKU);
        $itemTransfer->setQuantity(1);
        $quoteTransfer->addItem($itemTransfer);

        return $quoteTransfer;
    }

    /**
     * @param int $quantity
     *
     * @return \Orm\Zed\AvailabilityGui\Persistence\SpyAvailabilityGui
     */
    protected function createProductAvailabilityGui($quantity = 0)
    {
        $AvailabilityGuiAbstractEntity = new SpyAvailabilityGuiAbstract();
        $AvailabilityGuiAbstractEntity->setAbstractSku(self::ABSTRACT_SKU);
        $AvailabilityGuiAbstractEntity->setQuantity($quantity);
        $AvailabilityGuiAbstractEntity->save();

        $AvailabilityGuiEntity = new SpyAvailabilityGui();
        $AvailabilityGuiEntity->setFkAvailabilityGuiAbstract($AvailabilityGuiAbstractEntity->getIdAvailabilityGuiAbstract());
        $AvailabilityGuiEntity->setQuantity($quantity);
        $AvailabilityGuiEntity->setSku(self::CONCRETE_SKU);
        $AvailabilityGuiEntity->save();

        return $AvailabilityGuiEntity;
    }

}
