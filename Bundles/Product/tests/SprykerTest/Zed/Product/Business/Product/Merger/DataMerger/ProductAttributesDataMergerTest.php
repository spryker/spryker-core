<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Product\Merger\DataMerger;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductAttributesDataMerger;

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
 * @group ProductAttributesDataMergerTest
 * Add your own group annotations below this line
 */
class ProductAttributesDataMergerTest extends Unit
{
    /**
     * @return void
     */
    public function testProductConcreteAttributesExtendedWithProductAbstract(): void
    {
        // Arrange
        $productConcrete = (new ProductConcreteTransfer())->fromArray(['fkProductAbstract' => 1, 'attributes' => ['color' => 'black', 'pack' => 'box', 'system' => ['Windows', 'Linux']]]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['idProductAbstract' => 1, 'attributes' => ['color' => 'red', 'material' => 'wood']]);

        $productAttributesDataMerger = new ProductAttributesDataMerger();

        // Act
        $productConcreteCollection = $productAttributesDataMerger->merge(
            [$productConcrete],
            [1 => $productAbstract],
        );

        // Assert
        $this->assertEquals(['color' => 'black', 'pack' => 'box', 'material' => 'wood', 'system' => ['Windows', 'Linux']], $productConcreteCollection[0]->getAttributes());
    }
}
