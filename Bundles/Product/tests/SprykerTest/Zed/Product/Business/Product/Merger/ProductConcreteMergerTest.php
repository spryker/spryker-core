<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Product\Merger;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Product\Merger\ProductConcreteMerger;

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
     * @var \Spryker\Zed\Product\Business\Product\Merger\ProductConcreteMerger
     */
    protected $productConcreteMerger;

    /**
     * @return void
     */
    public function _setUp()
    {
        parent::_setUp();

        $this->productConcreteMerger = new ProductConcreteMerger([]);
    }

    /**
     * @return void
     */
    public function testProductConcreteAttributesExtendedWithProductAbstract(): void
    {
        // Arrange
        $productConcrete = (new ProductConcreteTransfer())->fromArray(['attributes' => ['red', 'green']]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['attributes' => ['green', 'blue']]);

        // Act
        $productConcreteResult = $this->productConcreteMerger->mergeProductConcreteWithProductAbstract(
            $productConcrete,
            $productAbstract,
        );

        // Assert
        $this->assertEquals(['green', 'blue', 'red'], $productConcreteResult->getAttributes());
    }

    /**
     * @return void
     */
    public function testProductConcreteLocalizedAttributesTakenFromProductAbstractIfEmpty(): void
    {
        // Arrange
        $productAbstractLocalizedAttribute = (new LocalizedAttributesTransfer())
            ->setLocale((new LocaleTransfer())->setIdLocale(1))
            ->setName('abstractLocalizedAttributeName')
            ->toArray();

        $productConcrete = new ProductConcreteTransfer();
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['localizedAttributes' => [$productAbstractLocalizedAttribute]]);

        // Act
        $productConcreteResult = $this->productConcreteMerger->mergeProductConcreteWithProductAbstract(
            $productConcrete,
            $productAbstract,
        );

        //Assert
        $this->assertEquals($productAbstractLocalizedAttribute, $productConcreteResult->getLocalizedAttributes()[0]->toArray());
    }

    /**
     * @return void
     */
    public function testProductConcreteLocalizedAttributesNotTakenFromProductAbstractIfExist(): void
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

        $productConcrete = (new ProductConcreteTransfer())->fromArray(['localizedAttributes' => [$productConcreteLocalizedAttribute]]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['localizedAttributes' => [$productAbstractLocalizedAttribute]]);

        // Act
        $productConcreteResult = $this->productConcreteMerger->mergeProductConcreteWithProductAbstract(
            $productConcrete,
            $productAbstract,
        );

        //Assert
        $this->assertEquals($productConcreteLocalizedAttribute, $productConcreteResult->getLocalizedAttributes()[0]->toArray());
    }

    /**
     * @return void
     */
    public function testProductConcreteLocalizedAttributesMissingValuesTakenFromProductAbstract(): void
    {
        // Arrange
        $productConcreteLocalizedAttribute = (new LocalizedAttributesTransfer())
            ->setLocale((new LocaleTransfer())->setIdLocale(1))
            ->toArray();

        $productAbstractLocalizedAttribute = (new LocalizedAttributesTransfer())
            ->setLocale((new LocaleTransfer())->setIdLocale(1))
            ->setName('abstractLocalizedAttributeName')
            ->setDescription('abstractLocalizedAttributeDescription')
            ->setIsSearchable(true)
            ->setMetaDescription('abstractLocalizedAttributeMetaDescription')
            ->setMetaKeywords('abstractLocalizedAttributeMetaKeywords')
            ->setMetaTitle('abstractLocalizedAttributeMetaTitle')
            ->toArray();

        $productConcrete = (new ProductConcreteTransfer())->fromArray(['localizedAttributes' => [$productConcreteLocalizedAttribute]]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['localizedAttributes' => [$productAbstractLocalizedAttribute]]);

        // Act
        $productConcreteResult = $this->productConcreteMerger->mergeProductConcreteWithProductAbstract(
            $productConcrete,
            $productAbstract,
        );

        //Assert
        $this->assertEquals($productAbstractLocalizedAttribute, $productConcreteResult->getLocalizedAttributes()[0]->toArray());
    }

    /**
     * @return void
     */
    public function testProductConcreteLocalizedAttributesExistedValuesNotTakenFromProductAbstractIfExist(): void
    {
        // Arrange
        $productConcreteLocalizedAttribute = (new LocalizedAttributesTransfer())
            ->setLocale((new LocaleTransfer())->setIdLocale(1))
            ->setName('concreteLocalizedAttributeName')
            ->setDescription('concreteLocalizedAttributeDescription')
            ->setIsSearchable(true)
            ->setMetaDescription('concreteLocalizedAttributeMetaDescription')
            ->setMetaKeywords('concreteLocalizedAttributeMetaKeywords')
            ->setMetaTitle('concreteLocalizedAttributeMetaTitle')
            ->toArray();

        $productAbstractLocalizedAttribute = (new LocalizedAttributesTransfer())
            ->setLocale((new LocaleTransfer())->setIdLocale(1))
            ->setName('abstractLocalizedAttributeName')
            ->setDescription('abstractLocalizedAttributeDescription')
            ->setIsSearchable(true)
            ->setMetaDescription('abstractLocalizedAttributeMetaDescription')
            ->setMetaKeywords('abstractLocalizedAttributeMetaKeywords')
            ->setMetaTitle('abstractLocalizedAttributeMetaTitle')
            ->toArray();

        $productConcrete = (new ProductConcreteTransfer())->fromArray(['localizedAttributes' => [$productConcreteLocalizedAttribute]]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['localizedAttributes' => [$productAbstractLocalizedAttribute]]);

        // Act
        $productConcreteResult = $this->productConcreteMerger->mergeProductConcreteWithProductAbstract(
            $productConcrete,
            $productAbstract,
        );

        //Assert
        $this->assertEquals($productConcreteLocalizedAttribute, $productConcreteResult->getLocalizedAttributes()[0]->toArray());
    }

    /**
     * @return void
     */
    public function testProductConcreteLocalizedAttributesMissingLocalesTakenFromProductAbstract(): void
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
                'localizedAttributes' => [
                    $productConcreteLocalizedAttribute1,
                    $productConcreteLocalizedAttribute2,
                ],
            ],
        );
        $productAbstract = (new ProductAbstractTransfer())->fromArray(
            [
                'localizedAttributes' => [
                    $productAbstractLocalizedAttribute1,
                    $productAbstractLocalizedAttribute2,
                ],
            ],
        );

        // Act
        $productConcreteResult = $this->productConcreteMerger->mergeProductConcreteWithProductAbstract(
            $productConcrete,
            $productAbstract,
        );

        //Assert
        $this->assertEquals($productConcreteLocalizedAttribute1, $productConcreteResult->getLocalizedAttributes()[0]->toArray());
        $this->assertEquals($productConcreteLocalizedAttribute2, $productConcreteResult->getLocalizedAttributes()[1]->toArray());
        $this->assertEquals($productAbstractLocalizedAttribute2, $productConcreteResult->getLocalizedAttributes()[2]->toArray());
    }

    /**
     * @return void
     */
    public function testProductConcreteLocalizedAttributesNestedAttributesExtendedWithProductAbstract(): void
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

        $productConcrete = (new ProductConcreteTransfer())->fromArray(['localizedAttributes' => [$productConcreteLocalizedAttribute]]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['localizedAttributes' => [$productAbstractLocalizedAttribute]]);

        // Act
        $productConcreteResult = $this->productConcreteMerger->mergeProductConcreteWithProductAbstract(
            $productConcrete,
            $productAbstract,
        );

        // Assert
        $this->assertEquals(['green', 'blue', 'red'], $productConcreteResult->getLocalizedAttributes()[0]->getAttributes());
    }

    /**
     * @return void
     */
    public function testProductConcreteRatingContainsNull(): void
    {
        // Arrange
        $productConcrete = (new ProductConcreteTransfer());
        $productAbstract = (new ProductAbstractTransfer());

        // Act
        $productConcreteResult = $this->productConcreteMerger->mergeProductConcreteWithProductAbstract(
            $productConcrete,
            $productAbstract,
        );

        // Assert
        $this->assertNull($productConcreteResult->getRating());
    }

    /**
     * @return void
     */
    public function testProductConcreteSearchMetadataNotTakenFromProductAbstractIfNotEmpty(): void
    {
        // Arrange
        $productConcrete = (new ProductConcreteTransfer())->fromArray(['searchMetadata' => ['color' => ['red', 'green']]]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['searchMetadata' => ['color' => ['white', 'black']]]);

        // Act
        $productConcreteResult = $this->productConcreteMerger->mergeProductConcreteWithProductAbstract(
            $productConcrete,
            $productAbstract,
        );

        // Assert
        $this->assertEquals(['color' => ['red', 'green']], $productConcreteResult->getSearchMetadata());
    }

    /**
     * @return void
     */
    public function testProductConcreteSearchMetadataTakenFromProductAbstractIfEmpty(): void
    {
        // Arrange
        $productConcrete = (new ProductConcreteTransfer())->fromArray(['searchMetadata' => []]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['searchMetadata' => ['color' => ['white', 'black']]]);

        // Act
        $productConcreteResult = $this->productConcreteMerger->mergeProductConcreteWithProductAbstract(
            $productConcrete,
            $productAbstract,
        );

        // Assert
        $this->assertEquals(['color' => ['white', 'black']], $productConcreteResult->getSearchMetadata());
    }
}
