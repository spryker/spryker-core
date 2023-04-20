<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductCategoryStorage\Filter\ProductCategoryStorageFilter;
use Spryker\Client\ProductCategoryStorage\Filter\ProductCategoryStorageFilterInterface;
use Spryker\Client\ProductCategoryStorage\Sorter\ProductCategoryStorageSorter;
use Spryker\Client\ProductCategoryStorage\Sorter\ProductCategoryStorageSorterInterface;
use Spryker\Client\ProductCategoryStorage\Storage\ProductAbstractCategoryStorageReader;

class ProductCategoryStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductCategoryStorage\Storage\ProductAbstractCategoryStorageReaderInterface
     */
    public function createProductCategoryStorageReader()
    {
        return new ProductAbstractCategoryStorageReader(
            $this->getStorage(),
            $this->getSynchronizationService(),
            $this->getProductAbstractCategoryStorageCollectionExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Client\ProductCategoryStorage\Filter\ProductCategoryStorageFilterInterface
     */
    public function createProductCategoryStorageFilter(): ProductCategoryStorageFilterInterface
    {
        return new ProductCategoryStorageFilter();
    }

    /**
     * @return \Spryker\Client\ProductCategoryStorage\Sorter\ProductCategoryStorageSorterInterface
     */
    public function createProductCategoryStorageSorter(): ProductCategoryStorageSorterInterface
    {
        return new ProductCategoryStorageSorter();
    }

    /**
     * @return \Spryker\Client\ProductCategoryStorage\Dependency\Client\ProductCategoryStorageToStorageClientInterface
     */
    public function getStorage()
    {
        return $this->getProvidedDependency(ProductCategoryStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductCategoryStorage\Dependency\Service\ProductCategoryStorageToSynchronizationServiceBridge
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductCategoryStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return list<\Spryker\Client\ProductCategoryStorageExtension\Dependency\Plugin\ProductAbstractCategoryStorageCollectionExpanderPluginInterface>
     */
    public function getProductAbstractCategoryStorageCollectionExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductCategoryStorageDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_CATEGORY_STORAGE_COLLECTION_EXPANDER);
    }
}
