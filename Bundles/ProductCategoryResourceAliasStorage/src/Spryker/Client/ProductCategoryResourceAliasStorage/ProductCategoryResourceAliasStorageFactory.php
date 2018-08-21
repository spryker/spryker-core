<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryResourceAliasStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductCategoryResourceAliasStorage\Storage\ProductAbstractCategoryStorageReader;

class ProductCategoryResourceAliasStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductCategoryResourceAliasStorage\Storage\ProductAbstractCategoryStorageReaderInterface
     */
    public function createProductAbstractCategoryStorageReader()
    {
        return new ProductAbstractCategoryStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductCategoryResourceAliasStorage\Dependency\Client\ProductCategoryResourceAliasStorageToStorageClientInterface
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(ProductCategoryResourceAliasStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductCategoryResourceAliasStorage\Dependency\Service\ProductCategoryResourceAliasStorageToSynchronizationServiceBridge
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductCategoryResourceAliasStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
