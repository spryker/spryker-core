<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Facade\PublishAndSynchronizeHealthCheckStorageToPublishAndSynchronizeHealthCheckFacadeInterface;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageDependencyProvider;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Business\PublishAndSynchronizeHealthCheckStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageConfig getConfig()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\PublishAndSynchronizeHealthCheckStorageRepositoryInterface getRepository()
 */
class PublishAndSynchronizeHealthCheckStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Facade\PublishAndSynchronizeHealthCheckStorageToPublishAndSynchronizeHealthCheckFacadeInterface
     */
    public function getPublishAndSynchronizeHealthCheckFacade(): PublishAndSynchronizeHealthCheckStorageToPublishAndSynchronizeHealthCheckFacadeInterface
    {
        return $this->getProvidedDependency(PublishAndSynchronizeHealthCheckStorageDependencyProvider::FACADE_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK);
    }
}
