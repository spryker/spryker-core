<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductsBackendApi;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConcreteConditionsBuilder;
use Generated\Shared\DataBuilder\ProductConcreteCriteriaBuilder;
use Generated\Shared\Transfer\ProductConcreteConditionsTransfer;
use Generated\Shared\Transfer\ProductConcreteCriteriaTransfer;
use Spryker\Glue\ProductsBackendApi\ProductsBackendApiConfig;
use Spryker\Glue\ProductsBackendApi\ProductsBackendApiResourceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductsBackendApi
 * @group GetProductConcreteResourceCollectionTest
 * Add your own group annotations below this line
 */
class GetProductConcreteResourceCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PRODUCT_CONCRETE_SKU = '1337';

    /**
     * @var string
     */
    protected const TEST_LOCALE_NAME = 'bn_IN';

    /**
     * @var \SprykerTest\Glue\ProductsBackendApi\ProductsBackendApiTester
     */
    protected ProductsBackendApiTester $tester;

    /**
     * @var \Spryker\Glue\ProductsBackendApi\ProductsBackendApiResourceInterface
     */
    protected ProductsBackendApiResourceInterface $productsBackendApiResource;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->productsBackendApiResource = $this->tester
            ->getLocator()
            ->productsBackendApi()
            ->resource();
    }

    /**
     * @return void
     */
    public function testGetProductConcreteResourceCollectionShouldReturnEmptyCollectionWhileNoCriteriaIsMatched(): void
    {
        // Arrange
        $productConcreteConditionsTransfer = (new ProductConcreteConditionsBuilder([
            ProductConcreteConditionsTransfer::SKUS => [
                static::TEST_PRODUCT_CONCRETE_SKU,
            ],
        ]))->build();

        $productConcreteCriteriaTransfer = (new ProductConcreteCriteriaBuilder([
            ProductConcreteCriteriaTransfer::PRODUCT_CONCRETE_CONDITIONS => $productConcreteConditionsTransfer,
        ]))->build();

        // Act
        $productConcreteResourceCollectionTransfer = $this->productsBackendApiResource
            ->getProductConcreteResourceCollection($productConcreteCriteriaTransfer);

        // Assert
        $this->assertCount(0, $productConcreteResourceCollectionTransfer->getProductConcreteResources());
    }

    /**
     * @return void
     */
    public function testGetProductConcreteResourceCollectionShouldReturnCollectionWithOneResourceAndTransferWhileConditionsMatched(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $localizedAttribuesTransfer = $productConcreteTransfer->getLocalizedAttributes()->getIterator()->current();

        $productConcreteConditionsTransfer = (new ProductConcreteConditionsBuilder([
            ProductConcreteConditionsTransfer::SKUS => [
                $productConcreteTransfer->getSku(),
            ],
            ProductConcreteConditionsTransfer::LOCALE_NAMES => [
                $localizedAttribuesTransfer->getLocale()->getLocaleName(),
            ],
        ]))->build();

        $productConcreteCriteriaTransfer = (new ProductConcreteCriteriaBuilder([
            ProductConcreteCriteriaTransfer::PRODUCT_CONCRETE_CONDITIONS => $productConcreteConditionsTransfer,
        ]))->build();

        // Act
        $productConcreteResourceCollectionTransfer = $this->productsBackendApiResource
            ->getProductConcreteResourceCollection($productConcreteCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productConcreteResourceCollectionTransfer->getProductConcreteResources());

        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $productConcreteResourceCollectionTransfer->getProductConcreteResources()->getIterator()->current();
        $this->assertSame($productConcreteTransfer->getSku(), $glueResourceTransfer->getId());
        $this->assertSame(ProductsBackendApiConfig::RESOURCE_CONCRETE_PRODUCTS, $glueResourceTransfer->getType());

        /** @var \Generated\Shared\Transfer\ApiProductsProductConcreteAttributesTransfer $apiProductsConcreteAttributesTransfer */
        $apiProductsConcreteAttributesTransfer = $glueResourceTransfer->getAttributes();
        $this->assertSame($productConcreteTransfer->getSku(), $apiProductsConcreteAttributesTransfer->getSku());
        $this->assertCount(1, $apiProductsConcreteAttributesTransfer->getLocalizedAttributes());
    }

    /**
     * @return void
     */
    public function testGetProductConcreteResourceCollectionShouldReturnCollectionWithNoProductConcreteResourceWhileLocaleNamesConditionNotMatched(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();

        $productConcreteConditionsTransfer = (new ProductConcreteConditionsBuilder([
            ProductConcreteConditionsTransfer::SKUS => [
                $productConcreteTransfer->getSku(),
            ],
            ProductConcreteConditionsTransfer::LOCALE_NAMES => [
                static::TEST_LOCALE_NAME,
            ],
        ]))->build();

        $productConcreteCriteriaTransfer = (new ProductConcreteCriteriaBuilder([
            ProductConcreteCriteriaTransfer::PRODUCT_CONCRETE_CONDITIONS => $productConcreteConditionsTransfer,
        ]))->build();

        // Act
        $productConcreteResourceCollectionTransfer = $this->productsBackendApiResource
            ->getProductConcreteResourceCollection($productConcreteCriteriaTransfer);

        // Assert
        $this->assertCount(0, $productConcreteResourceCollectionTransfer->getProductConcreteResources());
    }
}
