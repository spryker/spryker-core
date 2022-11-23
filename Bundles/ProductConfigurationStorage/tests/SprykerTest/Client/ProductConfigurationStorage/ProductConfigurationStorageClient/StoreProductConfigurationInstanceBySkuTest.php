<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClient;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceCriteriaBuilder;
use Generated\Shared\Transfer\ProductConfigurationInstanceConditionsTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationStorage
 * @group ProductConfigurationStorageClient
 * @group StoreProductConfigurationInstanceBySkuTest
 * Add your own group annotations below this line
 */
class StoreProductConfigurationInstanceBySkuTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testStoreProductConfigurationInstanceBySkuStoresInstanceInSession(): void
    {
        // Arrange
        $productConcreteTransfer = (new ProductConcreteBuilder())->build();
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder([
            ProductConfigurationInstanceTransfer::PRICES => new ArrayObject(),
        ]))->build();

        $productConfigurationInstanceCriteriaTransfer = (new ProductConfigurationInstanceCriteriaBuilder())
            ->withProductConfigurationInstanceConditions([
                    ProductConfigurationInstanceConditionsTransfer::SKUS => [$productConcreteTransfer->getSkuOrFail()],
                ])->build();

        // Act
        $this->tester
            ->getClient()
            ->storeProductConfigurationInstanceBySku(
                $productConcreteTransfer->getSkuOrFail(),
                $productConfigurationInstanceTransfer,
            );

        // Assert
        $productConfigurationInstanceCollectionTransfer = $this->tester
            ->getClient()
            ->getProductConfigurationInstanceCollection($productConfigurationInstanceCriteriaTransfer);

        $this->assertEquals(
            $productConfigurationInstanceCollectionTransfer->getProductConfigurationInstances()
                ->getIterator()
                ->current(),
            $productConfigurationInstanceTransfer,
            'Expects that store product configuration in session.',
        );
    }

    /**
     * @return void
     */
    public function testStoreProductConfigurationInstanceBySkuStoresEmptyInstanceInSession(): void
    {
        // Arrange
        $productConcreteTransfer = (new ProductConcreteBuilder())->build();
        $productConfigurationInstanceCriteriaTransfer = (new ProductConfigurationInstanceCriteriaBuilder())
            ->withProductConfigurationInstanceConditions([
                ProductConfigurationInstanceConditionsTransfer::SKUS => [$productConcreteTransfer->getSkuOrFail()],
            ])->build();

        // Act
        $this->tester
            ->getClient()
            ->storeProductConfigurationInstanceBySku(
                $productConcreteTransfer->getSkuOrFail(),
                new ProductConfigurationInstanceTransfer(),
            );

        // Assert
        $productConfigurationInstanceCollectionTransfer = $this->tester
            ->getClient()
            ->getProductConfigurationInstanceCollection($productConfigurationInstanceCriteriaTransfer);

        $this->assertNotNull(
            $productConfigurationInstanceCollectionTransfer->getProductConfigurationInstances()
                ->getIterator()
                ->current(),
            'Expects that store empty product configuration in session.',
        );
    }
}
