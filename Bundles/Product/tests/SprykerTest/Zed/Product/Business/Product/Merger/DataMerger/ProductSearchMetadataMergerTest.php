<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Product\Merger\DataMerger;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductSearchMetadataMerger;

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
 * @group ProductSearchMetadataMergerTest
 * Add your own group annotations below this line
 */
class ProductSearchMetadataMergerTest extends Unit
{
    /**
     * @return void
     */
    public function testProductConcreteSearchMetadataNotTakenFromProductAbstractIfNotEmpty(): void
    {
        // Arrange
        $productConcrete = (new ProductConcreteTransfer())->fromArray(['fkProductAbstract' => 1, 'searchMetadata' => ['color' => ['red', 'green']]]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['idProductAbstract' => 1, 'searchMetadata' => ['color' => ['white', 'black']]]);

        $productSearchMetadataMerger = new ProductSearchMetadataMerger();

        // Act
        $productConcreteCollection = $productSearchMetadataMerger->merge(
            [$productConcrete],
            [1 => $productAbstract],
        );

        // Assert
        $this->assertEquals(['color' => ['red', 'green']], $productConcreteCollection[0]->getSearchMetadata());
    }

    /**
     * @return void
     */
    public function testProductConcreteSearchMetadataTakenFromProductAbstractIfEmpty(): void
    {
        // Arrange
        $productConcrete = (new ProductConcreteTransfer())->fromArray(['fkProductAbstract' => 1, 'searchMetadata' => []]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['idProductAbstract' => 1, 'searchMetadata' => ['color' => ['white', 'black']]]);

        $productSearchMetadataMerger = new ProductSearchMetadataMerger();

        // Act
        $productConcreteCollection = $productSearchMetadataMerger->merge(
            [$productConcrete],
            [1 => $productAbstract],
        );

        // Assert
        $this->assertEquals(['color' => ['white', 'black']], $productConcreteCollection[0]->getSearchMetadata());
    }
}
