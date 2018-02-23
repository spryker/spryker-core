<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductSetStorage\Mapper\ProductSetStorageMapper;
use Spryker\Client\ProductSetStorage\Storage\ProductSetStorageReader;

class ProductSetStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductSetStorage\Storage\ProductSetStorageReaderInterface
     */
    public function createProductSetStorage()
    {
        return new ProductSetStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->createProductSetStorageMapper()
        );
    }

    /**
     * @return \Spryker\Client\ProductSetStorage\Dependency\Client\ProductSetStorageToStorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(ProductSetStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductSetStorage\Dependency\Service\ProductSetStorageToSynchronizationServiceBridge
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductSetStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ProductSetStorage\Mapper\ProductSetStorageMapperInterface
     */
    public function createProductSetStorageMapper()
    {
        return new ProductSetStorageMapper();
    }
}
