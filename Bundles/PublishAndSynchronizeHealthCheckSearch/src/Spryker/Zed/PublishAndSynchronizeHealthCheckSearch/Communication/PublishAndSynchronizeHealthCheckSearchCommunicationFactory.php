<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Dependency\Facade\PublishAndSynchronizeHealthCheckSearchToPublishAndSynchronizeHealthCheckFacadeInterface;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchDependencyProvider;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchConfig getConfig()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Business\PublishAndSynchronizeHealthCheckSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\PublishAndSynchronizeHealthCheckSearchRepositoryInterface getRepository()
 */
class PublishAndSynchronizeHealthCheckSearchCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Dependency\Facade\PublishAndSynchronizeHealthCheckSearchToPublishAndSynchronizeHealthCheckFacadeInterface
     */
    public function getPublishAndSynchronizeHealthCheckFacade(): PublishAndSynchronizeHealthCheckSearchToPublishAndSynchronizeHealthCheckFacadeInterface
    {
        return $this->getProvidedDependency(PublishAndSynchronizeHealthCheckSearchDependencyProvider::FACADE_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK);
    }
}
