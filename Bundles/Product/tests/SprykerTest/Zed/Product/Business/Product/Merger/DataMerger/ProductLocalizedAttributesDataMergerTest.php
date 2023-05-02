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
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductLocalizedAttributesDataMerger;

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
 * @group ProductLocalizedAttributesDataMergerTest
 * Add your own group annotations below this line
 */
class ProductLocalizedAttributesDataMergerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Product\ProductBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProductAbstractLocalizedAttributesAreTakenFromProductAbstractWhenTheyAreEmptyInProductConcrete(): void
    {
        // Arrange
        $productAbstractLocalizedAttribute = (new LocalizedAttributesTransfer())
            ->setLocale((new LocaleTransfer())->setIdLocale(1))
            ->setName('abstractLocalizedAttributeName')
            ->toArray();

        $productConcrete = (new ProductConcreteTransfer())->fromArray(['fkProductAbstract' => 1]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['idProductAbstract' => 1, 'localizedAttributes' => [$productAbstractLocalizedAttribute]]);

        $productAbstractLocalizedAttributesDataMerger = new ProductLocalizedAttributesDataMerger();

        // Act
        $productConcreteCollection = $productAbstractLocalizedAttributesDataMerger->merge(
            [$productConcrete],
            [1 => $productAbstract],
        );

        //Assert
        $this->assertEquals($productAbstractLocalizedAttribute, $productConcreteCollection[0]->getLocalizedAttributes()[0]->toArray());
    }

    /**
     * @return void
     */
    public function testProductConcreteLocalizedAttributesAreNotTakenFromProductAbstractWhenTheyExistInProductConcrete(): void
    {
        // Arrange
        $productConcreteLocalizedAttribute = (new LocalizedAttributesTransfer())
            ->setLocale((new LocaleTransfer())->setIdLocale(1))
            ->setName('concreteLocalizedAttributeName')
            ->toArray();

        $productAbstractLocalizedAttribute = (new LocalizedAttributesTransfer())
            ->setLocale((new LocaleTransfer())->setIdLocale(1))
            ->setName('abstractLocalizedAttributeName')
            ->toArray();

        $productConcrete = (new ProductConcreteTransfer())->fromArray(['fkProductAbstract' => 1, 'localizedAttributes' => [$productConcreteLocalizedAttribute]]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['idProductAbstract' => 1, 'localizedAttributes' => [$productAbstractLocalizedAttribute]]);

        $productLocalizedAttributesDataMerger = new ProductLocalizedAttributesDataMerger();

        // Act
        $productConcreteCollection = $productLocalizedAttributesDataMerger->merge(
            [$productConcrete],
            [1 => $productAbstract],
        );

        //Assert
        $this->assertEquals($productConcreteLocalizedAttribute, $productConcreteCollection[0]->getLocalizedAttributes()[0]->toArray());
    }

    /**
     * @return void
     */
    public function testProductConcreteLocalizedAttributesValuesAreTakenFromProductAbstractWhenTheyAreMissingInProductConcreteLocalizedAttributes(): void
    {
        // Arrange
        $productConcreteLocalizedAttribute = (new LocalizedAttributesTransfer())
            ->setLocale((new LocaleTransfer())->setIdLocale(1))
            ->toArray();

        $productAbstractLocalizedAttribute = $this->tester->createProductLocalizedAttribute(1, 'abstract');

        $productConcrete = (new ProductConcreteTransfer())->fromArray(['fkProductAbstract' => 1, 'localizedAttributes' => [$productConcreteLocalizedAttribute]]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['idProductAbstract' => 1, 'localizedAttributes' => [$productAbstractLocalizedAttribute]]);

        $productLocalizedAttributesDataMerger = new ProductLocalizedAttributesDataMerger();

        // Act
        $productConcreteCollection = $productLocalizedAttributesDataMerger->merge(
            [$productConcrete],
            [1 => $productAbstract],
        );

        //Assert
        $this->assertEquals($productAbstractLocalizedAttribute, $productConcreteCollection[0]->getLocalizedAttributes()[0]->toArray());
    }

    /**
     * @return void
     */
    public function testProductConcreteLocalizedAttributesValuesAreNotOverridedFromProductAbstractWhenTheyExistInProductConcrete(): void
    {
        // Arrange
        $productConcreteLocalizedAttribute = $this->tester->createProductLocalizedAttribute(1, 'concrete');
        $productAbstractLocalizedAttribute = $this->tester->createProductLocalizedAttribute(1, 'abstract');

        $productConcrete = (new ProductConcreteTransfer())->fromArray(['fkProductAbstract' => 1, 'localizedAttributes' => [$productConcreteLocalizedAttribute]]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['idProductAbstract' => 1, 'localizedAttributes' => [$productAbstractLocalizedAttribute]]);

        $productLocalizedAttributesDataMerger = new ProductLocalizedAttributesDataMerger();

        // Act
        $productConcreteCollection = $productLocalizedAttributesDataMerger->merge(
            [$productConcrete],
            [1 => $productAbstract],
        );

        //Assert
        $this->assertEquals($productConcreteLocalizedAttribute, $productConcreteCollection[0]->getLocalizedAttributes()[0]->toArray());
    }

    /**
     * @return void
     */
    public function testProductConcreteLocalizedAttributesLocalesAreTakenFromProductAbstractWhenTheyAreMissingInProductConcrete(): void
    {
        // Arrange
        $productConcreteLocalizedAttribute1 = (new LocalizedAttributesTransfer())
            ->setLocale((new LocaleTransfer())->setIdLocale(1))
            ->setName('concreteLocalizedAttributeNameForLocale1')
            ->toArray();

        $productConcreteLocalizedAttribute2 = (new LocalizedAttributesTransfer())
            ->setLocale((new LocaleTransfer())->setIdLocale(2))
            ->setName('concreteLocalizedAttributeNameForLocale2')
            ->toArray();

        $productAbstractLocalizedAttribute1 = (new LocalizedAttributesTransfer())
            ->setLocale((new LocaleTransfer())->setIdLocale(2))
            ->setName('abstractLocalizedAttributeNameForLocale2')
            ->toArray();

        $productAbstractLocalizedAttribute2 = (new LocalizedAttributesTransfer())
            ->setLocale((new LocaleTransfer())->setIdLocale(3))
            ->setName('abstractLocalizedAttributeNameForLocale3')
            ->toArray();

        $productConcrete = (new ProductConcreteTransfer())->fromArray(
            [
                'fkProductAbstract' => 1,
                'localizedAttributes' => [
                    $productConcreteLocalizedAttribute1,
                    $productConcreteLocalizedAttribute2,
                ],
            ],
        );
        $productAbstract = (new ProductAbstractTransfer())->fromArray(
            [
                'idProductAbstract' => 1,
                'localizedAttributes' => [
                    $productAbstractLocalizedAttribute1,
                    $productAbstractLocalizedAttribute2,
                ],
            ],
        );

        $productLocalizedAttributesDataMerger = new ProductLocalizedAttributesDataMerger();

        // Act
        $productConcreteCollection = $productLocalizedAttributesDataMerger->merge(
            [$productConcrete],
            [1 => $productAbstract],
        );

        //Assert
        $this->assertEquals($productConcreteLocalizedAttribute1, $productConcreteCollection[0]->getLocalizedAttributes()[0]->toArray());
        $this->assertEquals($productConcreteLocalizedAttribute2, $productConcreteCollection[0]->getLocalizedAttributes()[1]->toArray());
        $this->assertEquals($productAbstractLocalizedAttribute2, $productConcreteCollection[0]->getLocalizedAttributes()[2]->toArray());
    }

    /**
     * @return void
     */
    public function testProductConcreteLocalizedAttributesNestedAttributesExtendedWithProductAbstractLocalizedAttributes(): void
    {
        // Arrange
        $productConcreteLocalizedAttribute = (new LocalizedAttributesTransfer())
            ->setLocale((new LocaleTransfer())->setIdLocale(1))
            ->setAttributes(['red', 'green'])
            ->toArray();

        $productAbstractLocalizedAttribute = (new LocalizedAttributesTransfer())
            ->setLocale((new LocaleTransfer())->setIdLocale(1))
            ->setAttributes(['green', 'blue'])
            ->toArray();

        $productConcrete = (new ProductConcreteTransfer())->fromArray(['fkProductAbstract' => 1, 'localizedAttributes' => [$productConcreteLocalizedAttribute]]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['idProductAbstract' => 1, 'localizedAttributes' => [$productAbstractLocalizedAttribute]]);

        $productLocalizedAttributesDataMerger = new ProductLocalizedAttributesDataMerger();

        // Act
        $productConcreteCollection = $productLocalizedAttributesDataMerger->merge(
            [$productConcrete],
            [1 => $productAbstract],
        );

        // Assert
        $this->assertEquals(['green', 'blue', 'red'], $productConcreteCollection[0]->getLocalizedAttributes()[0]->getAttributes());
    }
}
