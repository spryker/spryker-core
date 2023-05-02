<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Product\Merger\DataMerger;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductAbstractLocalizedAttributesDataMerger;

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
 * @group ProductAbstractLocalizedAttributesDataMergerTest
 * Add your own group annotations below this line
 */
class ProductAbstractLocalizedAttributesDataMergerTest extends Unit
{
    /**
     * @return void
     */
    public function testProductConcreteAbstractLocalizedAttributesTakenFromProductAbstractLocalizedAttributes(): void
    {
        // Arrange
        $productAbstractLocalizedAttribute = (new LocalizedAttributesTransfer())
            ->setLocale((new LocaleTransfer())->setIdLocale(1))
            ->setName('abstractLocalizedAttributeName')
            ->toArray();

        $productConcrete = (new ProductConcreteTransfer())->fromArray(['fkProductAbstract' => 1, 'abstractLocalizedAttributes' => []]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['idProductAbstract' => 1, 'localizedAttributes' => [$productAbstractLocalizedAttribute]]);

        $productAbstractLocalizedAttributesDataMerger = new ProductAbstractLocalizedAttributesDataMerger();

        // Act
        $productConcreteCollection = $productAbstractLocalizedAttributesDataMerger->merge(
            [$productConcrete],
            [1 => $productAbstract],
        );

        // Assert
        $this->assertEquals($productAbstractLocalizedAttribute, $productConcreteCollection[0]->getAbstractLocalizedAttributes()[0]->toArray());
    }
}
