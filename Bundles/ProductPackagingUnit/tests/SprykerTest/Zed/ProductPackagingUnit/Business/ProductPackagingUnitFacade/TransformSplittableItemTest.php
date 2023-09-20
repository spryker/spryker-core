<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group TransformSplittableItemTest
 * Add your own group annotations below this line
 */
class TransformSplittableItemTest extends Unit
{
    /**
     * @var string
     */
    protected const ABSTRACT_PRODUCT_SKU = 'ABSTRACT_PRODUCT_SKU';

    /**
     * @var string
     */
    protected const CONCRETE_PRODUCT_SKU = 'CONCRETE_PRODUCT_SKU';

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @return void
     */
    public function testTransformSplittableItem(): void
    {
        // Arrange
        $this->setData(true);
        $itemTransfer = (new ItemTransfer())
            ->setSku(static::CONCRETE_PRODUCT_SKU)
            ->setQuantity(2)
            ->setAmount(3)
            ->setAmountSalesUnit(new ProductMeasurementSalesUnitTransfer());

        // Act
        $itemCollectionTransfer = $this->tester->getFacade()->transformSplittableItem($itemTransfer);

        // Assert
        foreach ($itemCollectionTransfer->getItems() as $itemTransfer) {
            $this->assertSame(1, $itemTransfer->getQuantity());
            $this->assertSame('1.5', $itemTransfer->getAmount()->trim()->toString());
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

        $product
            ->setFkProductAbstract($productAbstract->getIdProductAbstract())
            ->setIsQuantitySplittable($isQuantitySplittable)
            ->save();
    }
}
