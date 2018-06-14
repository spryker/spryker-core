<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryFilterStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductCategoryFilterStorage\Storage\ProductCategoryFilterStorageReader;

class ProductCategoryFilterStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductCategoryFilterStorage\Storage\ProductCategoryFilterStorageReader
     */
    public function createProductCategoryFilterStorageReader()
    {
        return new ProductCategoryFilterStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductCategoryFilterStorage\Dependency\Client\ProductCategoryFilterStorageToStorageInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(ProductCategoryFilterStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductCategoryFilterStorage\Dependency\Service\ProductCategoryFilterStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductCategoryFilterStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
