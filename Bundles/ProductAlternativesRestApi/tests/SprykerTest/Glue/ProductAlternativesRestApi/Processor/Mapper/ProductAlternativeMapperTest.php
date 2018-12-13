<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductAlternativesRestApi\Processor\Mapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestAlternativeProductsAttributesTransfer;
use Spryker\Glue\ProductAlternativesRestApi\Processor\Mapper\ProductAlternativeMapper;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group ProductAlternativesRestApi
 * @group Processor
 * @group Mapper
 * @group ProductAlternativeMapperTest
 * Add your own group annotations below this line
 */
class ProductAlternativeMapperTest extends Unit
{
    protected const CONCRETE_PRODUCT_SKU = '134_26145012';
    protected const ABSTRACT_PRODUCT_SKU = '134';

    /**
     * @var \SprykerTest\Glue\ProductAlternativesRestApi\ProductAlternativesMapperTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMapProductAbstractStorageDataToRestAlternativeProductsAttributesTransferWillPopulateTransferOnlyWithAbstractProductsSku()
    {
        // Arrange
        $mapper = new ProductAlternativeMapper();
        $concreteProductStorageData = $this->tester->hasProductConcreteStorageData();
        $restAlternativeProductsAttributesTransfer = new RestAlternativeProductsAttributesTransfer();

        // Act
        $restAlternativeProductsAttributesTransfer = $mapper->mapProductConcreteStorageDataToRestAlternativeProductsAttributesTransfer(
            $concreteProductStorageData,
            $restAlternativeProductsAttributesTransfer
        );

        // Assert
        $this->assertEquals($restAlternativeProductsAttributesTransfer->getConcreteProductIds(), [static::CONCRETE_PRODUCT_SKU]);
        $this->assertEmpty($restAlternativeProductsAttributesTransfer->getAbstractProductIds());
    }

    /**
     * @return void
     */
    public function testMapProductConcreteStorageDataToRestAlternativeProductsAttributesTransfer()
    {
        // Arrange
        $mapper = new ProductAlternativeMapper();
        $abstractProductStorageData = $this->tester->hasProductAbstractStorageData();
        $restAlternativeProductsAttributesTransfer = new RestAlternativeProductsAttributesTransfer();

        // Act
        $restAlternativeProductsAttributesTransfer = $mapper->mapProductAbstractStorageDataToRestAlternativeProductsAttributesTransfer(
            $abstractProductStorageData,
            $restAlternativeProductsAttributesTransfer
        );

        // Assert
        $this->assertEquals($restAlternativeProductsAttributesTransfer->getAbstractProductIds(), [static::ABSTRACT_PRODUCT_SKU]);
        $this->assertEmpty($restAlternativeProductsAttributesTransfer->getConcreteProductIds());
    }
}
