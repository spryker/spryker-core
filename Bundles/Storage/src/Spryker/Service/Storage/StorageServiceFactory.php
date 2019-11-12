<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Storage;

use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;
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
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    public function getStorageClient(): StorageClientInterface
    {
        return $this->getProvidedDependency(StorageDependencyProvider::CLIENT_STORAGE);
    }
}
