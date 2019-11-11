<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Storage;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Storage\Dependency\Client\StorageToStorageClientInterface;
use Spryker\Service\Storage\HealthIndicator\HealthIndicator;
use Spryker\Service\Storage\HealthIndicator\HealthIndicatorInterface;

class StorageServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Storage\HealthIndicator\HealthIndicatorInterface
     */
    public function createStorageHealthIndicator(): HealthIndicatorInterface
    {
        return new HealthIndicator(
            $this->getStorageClient()
        );
    }

    /**
     * @return \Spryker\Service\Storage\Dependency\Client\StorageToStorageClientInterface
     */
    public function getStorageClient(): StorageToStorageClientInterface
    {
        return $this->getProvidedDependency(StorageDependencyProvider::CLIENT_STORAGE);
    }
}
