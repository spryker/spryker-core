<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelationStorage;

use Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToProductStorageClientInterface;
use Spryker\Client\ProductRelationStorage\Relation\RelatedProductReader;
use Spryker\Client\ProductRelationStorage\Relation\RelatedProductReaderInterface;
use Spryker\Client\ProductRelationStorage\Relation\UpSellingProductReader;
use Spryker\Client\ProductRelationStorage\Relation\UpSellingProductReaderInterface;
use Spryker\Client\ProductRelationStorage\Storage\ProductAbstractRelationStorageReader;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface;

class ProductRelationStorageFactory extends AbstractFactory
{
    /**
     * @return RelatedProductReaderInterface
     */
    public function createRelatedProductReader()
    {
        return new RelatedProductReader(
            $this->createProductRelationStorageReader(),
            $this->getProductStorageClient(),
            $this->getRelatedProductExpanderPlugins()
        );
    }

    /**
     * @return UpSellingProductReaderInterface
     */
    public function createUpSellingProductReader()
    {
        return new UpSellingProductReader(
            $this->createProductRelationStorageReader(),
            $this->getProductStorageClient(),
            $this->getRelatedProductExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Client\ProductRelationStorage\Storage\ProductAbstractRelationStorageReaderInterface
     */
    public function createProductRelationStorageReader()
    {
        return new ProductAbstractRelationStorageReader($this->getStorage(), $this->getSynchronizationService());
    }

    /**
     * @return \Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToStorageClientInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(ProductRelationStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductRelationStorage\Dependency\Service\ProductRelationStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductRelationStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return ProductRelationStorageToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductRelationStorageToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductRelationStorageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return ProductViewExpanderPluginInterface[]
     */
    protected function getRelatedProductExpanderPlugins()
    {
        return $this->getProvidedDependency(ProductRelationStorageDependencyProvider::PLUGIN_RELATED_PRODUCT_EXPANDERS);
    }
}
