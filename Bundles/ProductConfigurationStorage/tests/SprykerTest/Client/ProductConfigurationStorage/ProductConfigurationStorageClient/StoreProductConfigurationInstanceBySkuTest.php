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

        // Act
        $this->tester
            ->getClient()
            ->storeProductConfigurationInstanceBySku(
                $productConcreteTransfer->getSku(),
                $productConfigurationInstanceTransfer,
            );

        // Assert
        $storedProductConfigurationInstanceTransfer = $this->tester
            ->getClient()
            ->findProductConfigurationInstanceBySku($productConcreteTransfer->getSku());

        $this->assertEquals(
            $productConfigurationInstanceTransfer,
            $storedProductConfigurationInstanceTransfer,
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
        $productConfigurationInstanceTransfer = new ProductConfigurationInstanceTransfer();

        // Act
        $this->tester
            ->getClient()
            ->storeProductConfigurationInstanceBySku(
                $productConcreteTransfer->getSku(),
                $productConfigurationInstanceTransfer,
            );

        // Assert
        $storedProductConfigurationInstanceTransfer = $this->tester
            ->getClient()
            ->findProductConfigurationInstanceBySku($productConcreteTransfer->getSku());

        $this->assertNotNull(
            $storedProductConfigurationInstanceTransfer,
            'Expects that store empty product configuration in session.',
        );
    }
}
