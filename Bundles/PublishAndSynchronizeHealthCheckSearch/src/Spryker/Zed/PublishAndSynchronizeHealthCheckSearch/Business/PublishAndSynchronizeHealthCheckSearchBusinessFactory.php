<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Business\HealthCheck\HealthCheck;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Business\HealthCheck\HealthCheckInterface;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Business\Writer\PublishAndSynchronizeHealthCheckSearchWriter;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Business\Writer\PublishAndSynchronizeHealthCheckSearchWriterInterface;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Dependency\Client\PublishAndSynchronizeHealthCheckSearchToSearchClientInterface;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Dependency\Facade\PublishAndSynchronizeHealthCheckSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchDependencyProvider;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchConfig getConfig()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\PublishAndSynchronizeHealthCheckSearchRepositoryInterface getRepository()
 */
class PublishAndSynchronizeHealthCheckSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Business\Writer\PublishAndSynchronizeHealthCheckSearchWriterInterface
     */
    public function createPublishAndSynchronizeHealthCheckSearchWriter(): PublishAndSynchronizeHealthCheckSearchWriterInterface
    {
        return new PublishAndSynchronizeHealthCheckSearchWriter(
            $this->getEventBehaviorFacade(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Dependency\Facade\PublishAndSynchronizeHealthCheckSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): PublishAndSynchronizeHealthCheckSearchToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(PublishAndSynchronizeHealthCheckSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Business\HealthCheck\HealthCheckInterface
     */
    public function createHealthCheck(): HealthCheckInterface
    {
        return new HealthCheck(
            $this->getSearchClient(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Dependency\Client\PublishAndSynchronizeHealthCheckSearchToSearchClientInterface
     */
    public function getSearchClient(): PublishAndSynchronizeHealthCheckSearchToSearchClientInterface
    {
        return $this->getProvidedDependency(PublishAndSynchronizeHealthCheckSearchDependencyProvider::CLIENT_SEARCH);
    }
}
