<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Facade;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Facade
 * @group MergeProductAbstractImageSetsIntoProductConcreteTest
 * Add your own group annotations below this line
 */
class MergeProductAbstractImageSetsIntoProductConcreteTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testMergeProductAbstractImageSetsIntoProductConcreteTakenFromProductAbstractIfEmpty(): void
    {
        // Arrange
        $productAbstractImageSet = (new ProductImageSetTransfer())->setIdProduct(2)->toArray();

        $productConcrete = new ProductConcreteTransfer();
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['imageSets' => [$productAbstractImageSet]]);

        // Act
        $productConcreteResult = $this->productImageFacade->mergeProductAbstractImageSetsIntoProductConcrete(
            $productConcrete,
            $productAbstract,
        );

        // Assert
        $this->assertEquals($productAbstractImageSet, $productConcreteResult->getImageSets()[0]->toArray());
    }

    /**
     * @return void
     */
    public function testMergeProductAbstractImageSetsIntoProductConcreteNotTakenFromProductAbstractIfExist(): void
    {
        // Arrange
        $productConcreteImageSet = (new ProductImageSetTransfer())->setIdProduct(1)->toArray();
        $productAbstractImageSet = (new ProductImageSetTransfer())->setIdProduct(2)->toArray();

        $productConcrete = (new ProductConcreteTransfer())->fromArray(['imageSets' => [$productConcreteImageSet]]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['imageSets' => [$productAbstractImageSet]]);

        // Act
        $productConcreteResult = $this->productImageFacade->mergeProductAbstractImageSetsIntoProductConcrete(
            $productConcrete,
            $productAbstract,
        );

        // Assert
        $this->assertEquals($productConcreteImageSet, $productConcreteResult->getImageSets()[0]->toArray());
    }
}
