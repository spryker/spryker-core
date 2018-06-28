<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSearchConfigStorage;

use Spryker\Client\Kernel\AbstractFactory;

class ProductSearchConfigStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductSearchConfigStorage\Dependency\Client\ProductSearchConfigStorageToStorageClientInterface
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(ProductSearchConfigStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductSearchConfigStorage\Dependency\Service\ProductSearchConfigStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductSearchConfigStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
