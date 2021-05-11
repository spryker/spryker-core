<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductCategoryStorage;

use Codeception\Test\Unit;
use Spryker\Client\ProductCategoryStorage\ProductCategoryStorageClient;
use Spryker\Client\ProductCategoryStorage\ProductCategoryStorageClientInterface;
use Spryker\Client\Storage\StorageDependencyProvider;
use Spryker\Client\StorageRedis\Plugin\StorageRedisPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductCategoryStorage
 * @group ProductCategoryStorageClientTest
 * Add your own group annotations below this line
 */
class ProductCategoryStorageClientTest extends Unit
{
    protected const INVALID_ID_PRODUCT_ABSTRACT = 1234567890;

    /**
     * @var \SprykerTest\Client\ProductCategoryStorage\ProductCategoryStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(StorageDependencyProvider::PLUGIN_STORAGE, new StorageRedisPlugin());
    }

    /**
     * @return void
     */
    public function testFindInvalidProductAbstractCategoryReturnsNull(): void
    {
        // Act
        $returnValue = $this->createProductCategoryStorageClient()
            ->findProductAbstractCategory(static::INVALID_ID_PRODUCT_ABSTRACT, 'de_DE', 'DE');

        // Assert
        $this->assertNull($returnValue);
    }

    /**
     * @return void
     */
    public function testFindBulkProductAbstractCategoryReturnsEmptyArray(): void
    {
        // Act
        $productAbstractCategoryStorageTransfers = $this->createProductCategoryStorageClient()
            ->findBulkProductAbstractCategory([static::INVALID_ID_PRODUCT_ABSTRACT], 'de_DE', 'DE');

        // Assert
        $this->assertCount(0, $productAbstractCategoryStorageTransfers);
    }

    /**
     * @return \Spryker\Client\ProductCategoryStorage\ProductCategoryStorageClientInterface
     */
    protected function createProductCategoryStorageClient(): ProductCategoryStorageClientInterface
    {
        return new ProductCategoryStorageClient();
    }
}
