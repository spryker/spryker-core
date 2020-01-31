<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Storage;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Storage\HealthCheck\HealthCheckInterface;
use Spryker\Yves\Storage\HealthCheck\KeyValueStoreHealthCheck;

/**
 * @method \Spryker\Yves\Storage\StorageConfig getConfig()
 */
class StorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Storage\HealthCheck\HealthCheckInterface
     */
    public function createKeyValueStoreHealthChecker(): HealthCheckInterface
    {
        return new KeyValueStoreHealthCheck(
            $this->getStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(StorageDependencyProvider::CLIENT_STORAGE);
    }
}
