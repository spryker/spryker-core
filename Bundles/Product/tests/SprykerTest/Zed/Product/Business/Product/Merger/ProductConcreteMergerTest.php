<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Product\Merger;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductDataMergerInterface;
use Spryker\Zed\Product\Business\Product\Merger\ProductConcreteMerger;
use Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteMergerPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Product
 * @group Merger
 * @group ProductConcreteMergerTest
 * Add your own group annotations below this line
 */
class ProductConcreteMergerTest extends Unit
{
    /**
     * @return void
     */
    public function testProductConcreteTransfersMergedWithProductAbstractTransfers(): void
    {
        // Arrange
        $productConcrete = (new ProductConcreteTransfer())->fromArray(['fkProductAbstract' => 1]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['idProductAbstract' => 1]);

        // Assert
        $productDataMergerPlugin = $this->createMock(ProductDataMergerInterface::class);
        $productDataMergerPlugin->expects($this->once())
            ->method('merge')
            ->with(
                [$productConcrete],
                [1 => $productAbstract],
            )
            ->willReturn([$productConcrete]);

        $productConcreteMergerPlugin = $this->createMock(ProductConcreteMergerPluginInterface::class);
        $productConcreteMergerPlugin
            ->expects($this->once())
            ->method('merge')
            ->with($productConcrete, $productAbstract)
            ->willReturn($productConcrete);

        // Arrange
        $productConcreteMerger = new ProductConcreteMerger([$productDataMergerPlugin], [$productConcreteMergerPlugin]);

        // Act
        $productConcreteCollection = $productConcreteMerger->mergeProductConcreteTransfersWithProductAbstractTransfers(
            [$productConcrete],
            [1 => $productAbstract],
        );

        // Assert
        $this->assertIsArray($productConcreteCollection);
        $this->assertNotEmpty($productConcreteCollection);
        $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcreteCollection[0]);
        $this->assertEquals($productConcreteCollection[0]->getIdProductConcrete(), $productConcrete->getIdProductConcrete());
    }

    /**
     * @return void
     */
    public function testProductConcreteRatingContainsNull(): void
    {
        // Arrange
        $productConcrete = (new ProductConcreteTransfer())->fromArray(['fkProductAbstract' => 1]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['idProductAbstract' => 1]);

        $productConcreteMerger = new ProductConcreteMerger([], []);

        // Act
        $productConcreteCollection = $productConcreteMerger->mergeProductConcreteTransfersWithProductAbstractTransfers(
            [$productConcrete],
            [1 => $productAbstract],
        );

        // Assert
        $this->assertNull($productConcreteCollection[0]->getRating());
    }
}
