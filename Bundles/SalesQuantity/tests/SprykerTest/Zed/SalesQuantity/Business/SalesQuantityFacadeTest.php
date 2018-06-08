<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesQuantity\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\SalesQuantity\Business\SalesQuantityFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group SalesQuantity
 * @group Business
 * @group Facade
 * @group SalesQuantityFacadeTest
 * Add your own group annotations below this line
 */
class SalesQuantityFacadeTest extends Unit
{
    protected const ABSTRACT_PRODUCT_SKU = 'ABSTRACT_PRODUCT_SKU';
    protected const CONCRETE_PRODUCT_SKU = 'CONCRETE_PRODUCT_SKU';

    /**
     * @var \Spryker\Zed\SalesQuantity\Business\SalesQuantityFacadeInterface
     */
    private $salesQuantityFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->salesQuantityFacade = new SalesQuantityFacade();
    }

    /**
     * @return void
     */
    public function testTransformItemShouldNotSplitItemsPerQuantity(): void
    {
        $quantity = 5;

        $itemTransfer = (new ItemTransfer())->setQuantity($quantity);
        $itemCollectionTransfer = $this->salesQuantityFacade->transformItem($itemTransfer);

        $this->assertSame($itemCollectionTransfer->getItems()->count(), 1);

        foreach ($itemCollectionTransfer->getItems() as $itemTransfer) {
            $this->assertSame($itemTransfer->getQuantity(), $quantity);
        }
    }

    /**
     * @return void
     */
    public function testExpandItemsShouldPreSetBdValuesToItemTransfer(): void
    {
        $this->setData(true);
        $item = (new ItemTransfer())->setSku(static::CONCRETE_PRODUCT_SKU);
        $cartChangeTransfer = (new CartChangeTransfer())->setItems(new ArrayObject($item));

        $resultCartChangeTransfer = $this->salesQuantityFacade->expandItems($cartChangeTransfer);

        foreach ($resultCartChangeTransfer->getItems() as $itemTransfer) {
            $this->assertSame($itemTransfer->getIsQuantitySplittable(), true);
        }

        $this->setData(false);

        $resultCartChangeTransfer = $this->salesQuantityFacade->expandItems($cartChangeTransfer);

        foreach ($resultCartChangeTransfer->getItems() as $itemTransfer) {
            $this->assertSame($itemTransfer->getIsQuantitySplittable(), false);
        }
    }

    /**
     * @return void
     */
    public function testTransformDiscountableItemShouldBeUsedNonSplitTransformation(): void
    {
        $quantity = 5;
        $discountableItemTransfer = (new DiscountableItemTransfer())->setUnitPrice(100)
            ->setQuantity($quantity);
        $discountTransfer = (new DiscountTransfer())->setIdDiscount(1);
        $totalDiscountAmount = 10;
        $totalAmount = 100;

        $this->salesQuantityFacade->transformDiscountableItem($discountableItemTransfer, $discountTransfer, $totalDiscountAmount, $totalAmount, $quantity);

        $this->assertSame($discountableItemTransfer->getOriginalItemCalculatedDiscounts()->count(), 1);

        foreach ($discountableItemTransfer->getOriginalItemCalculatedDiscounts() as $resultedDiscountableItemTransfer) {
            $this->assertSame($resultedDiscountableItemTransfer->getUnitAmount(), 50);
            $this->assertSame($resultedDiscountableItemTransfer->getQuantity(), 1);
        }
    }

    /**
     * @param bool $isQuantitySplittable
     *
     * @return void
     */
    protected function setData(bool $isQuantitySplittable): void
    {
        $productAbstract = SpyProductAbstractQuery::create()
            ->filterBySku(static::ABSTRACT_PRODUCT_SKU)
            ->findOne();

        if ($productAbstract === null) {
            $productAbstract = new SpyProductAbstract();

            $productAbstract
                ->setAttributes('{}')
                ->setSku(static::ABSTRACT_PRODUCT_SKU);
        }

        $productAbstract->save();

        $product = SpyProductQuery::create()
            ->filterBySku(static::CONCRETE_PRODUCT_SKU)
            ->findOne();

        if ($product === null) {
            $product = new SpyProduct();
            $product->setAttributes('{}')
                ->setSku(static::CONCRETE_PRODUCT_SKU);
        }

        $product->setFkProductAbstract($productAbstract->getIdProductAbstract())
            ->setIsQuantitySplittable($isQuantitySplittable)
            ->save();
    }
}
