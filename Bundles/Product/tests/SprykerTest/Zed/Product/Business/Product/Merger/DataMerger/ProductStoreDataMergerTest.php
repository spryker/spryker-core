<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Product\Merger\DataMerger;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductSearchMetadataMerger;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductStoreDataMerger;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Product
 * @group Merger
 * @group DataMerger
 * @group ProductStoreDataMergerTest
 * Add your own group annotations below this line
 */
class ProductStoreDataMergerTest extends Unit
{
    /**
     * @return void
     */
    public function testProductConcreteStoresTakenFromProductAbstractWhenEmpty(): void
    {
        // Arrange
        $productConcreteStore = (new StoreTransfer())->setIdStore(100);

        $productConcrete = (new ProductConcreteTransfer())->fromArray(['fkProductAbstract' => 1]);

        $productAbstract = (new ProductAbstractTransfer())->fromArray(['idProductAbstract' => 1]);
        $productAbstract->setStoreRelation((new StoreRelationTransfer())->addStores($productConcreteStore));

        $productStoreDataMerger = new ProductStoreDataMerger();

        // Act
        $productConcreteCollection = $productStoreDataMerger->merge(
            [$productConcrete],
            [1 => $productAbstract],
        );

        // Assert
        $this->assertNotEmpty($productConcreteCollection[0]->getStores());
        $this->assertEquals($productConcreteStore->getIdStore(), $productConcreteCollection[0]->getStores()->offsetGet(0)->getIdStore());
    }

    /**
     * @return void
     */
    public function testProductConcreteStoresNotTakenFromProductAbstractWhenProductConcreteHaveStores(): void
    {
        // Arrange
        $productConcreteStore = (new StoreTransfer())->setIdStore(100);
        $productAbstractStore = (new StoreTransfer())->setIdStore(200);

        $productConcrete = (new ProductConcreteTransfer())->fromArray(['fkProductAbstract' => 1]);
        $productConcrete->addStores($productConcreteStore);

        $productAbstract = (new ProductAbstractTransfer())->fromArray(['idProductAbstract' => 1]);
        $productAbstract->setStoreRelation((new StoreRelationTransfer())->addStores($productAbstractStore));

        $productSearchMetadataMerger = new ProductSearchMetadataMerger();

        // Act
        $productConcreteCollection = $productSearchMetadataMerger->merge(
            [$productConcrete],
            [1 => $productAbstract],
        );

        // Assert
        $this->assertNotEmpty($productConcreteCollection[0]->getStores());
        $this->assertEquals($productConcreteStore->getIdStore(), $productConcreteCollection[0]->getStores()->offsetGet(0)->getIdStore());
    }

    /**
     * @return void
     */
    public function testProductConcreteStoresNotTakenFromProductAbstractWhenProductAbstractDoesNotHaveStoreRelation(): void
    {
        // Arrange
        $productConcreteStore = (new StoreTransfer())->setIdStore(100);

        $productConcrete = (new ProductConcreteTransfer())->fromArray(['fkProductAbstract' => 1]);
        $productConcrete->addStores($productConcreteStore);

        $productAbstract = (new ProductAbstractTransfer())->fromArray(['idProductAbstract' => 1]);

        $productSearchMetadataMerger = new ProductSearchMetadataMerger();

        // Act
        $productConcreteCollection = $productSearchMetadataMerger->merge(
            [$productConcrete],
            [1 => $productAbstract],
        );

        // Assert
        $this->assertNotEmpty($productConcreteCollection[0]->getStores());
        $this->assertEquals($productConcreteStore->getIdStore(), $productConcreteCollection[0]->getStores()->offsetGet(0)->getIdStore());
    }
}
