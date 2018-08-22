<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryResourceAliasStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductCategoryResourceAliasStorage\Dependency\Client\ProductCategoryResourceAliasStorageToStorageClientInterface;
use Spryker\Client\ProductCategoryResourceAliasStorage\Dependency\Service\ProductCategoryResourceAliasStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductCategoryResourceAliasStorage\Storage\ProductAbstractCategoryStorageReader;
use Spryker\Client\ProductCategoryResourceAliasStorage\Storage\ProductAbstractCategoryStorageReaderInterface;

class ProductCategoryResourceAliasStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductCategoryResourceAliasStorage\Storage\ProductAbstractCategoryStorageReaderInterface
     */
    public function createProductAbstractCategoryStorageReader(): ProductAbstractCategoryStorageReaderInterface
    {
        return new ProductAbstractCategoryStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductCategoryResourceAliasStorage\Dependency\Client\ProductCategoryResourceAliasStorageToStorageClientInterface
     */
    public function getStorageClient(): ProductCategoryResourceAliasStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductCategoryResourceAliasStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductCategoryResourceAliasStorage\Dependency\Service\ProductCategoryResourceAliasStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ProductCategoryResourceAliasStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductCategoryResourceAliasStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
