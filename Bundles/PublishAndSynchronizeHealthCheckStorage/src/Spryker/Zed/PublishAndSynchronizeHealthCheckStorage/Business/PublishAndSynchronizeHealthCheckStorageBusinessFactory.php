<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Business\HealthCheck\HealthCheck;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Business\HealthCheck\HealthCheckInterface;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Business\Writer\PublishAndSynchronizeHealthCheckStorageWriter;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Business\Writer\PublishAndSynchronizeHealthCheckStorageWriterInterface;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Client\PublishAndSynchronizeHealthCheckStorageToStorageClientInterface;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Facade\PublishAndSynchronizeHealthCheckStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageDependencyProvider;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageConfig getConfig()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\PublishAndSynchronizeHealthCheckStorageRepositoryInterface getRepository()
 */
class PublishAndSynchronizeHealthCheckStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Business\Writer\PublishAndSynchronizeHealthCheckStorageWriterInterface
     */
    public function createPublishAndSynchronizeHealthCheckStorageWriter(): PublishAndSynchronizeHealthCheckStorageWriterInterface
    {
        return new PublishAndSynchronizeHealthCheckStorageWriter(
            $this->getEventBehaviorFacade(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Facade\PublishAndSynchronizeHealthCheckStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): PublishAndSynchronizeHealthCheckStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(PublishAndSynchronizeHealthCheckStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Business\HealthCheck\HealthCheckInterface
     */
    public function createHealthCheck(): HealthCheckInterface
    {
        return new HealthCheck(
            $this->getStorageClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Client\PublishAndSynchronizeHealthCheckStorageToStorageClientInterface
     */
    public function getStorageClient(): PublishAndSynchronizeHealthCheckStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(PublishAndSynchronizeHealthCheckStorageDependencyProvider::CLIENT_STORAGE);
    }
}
