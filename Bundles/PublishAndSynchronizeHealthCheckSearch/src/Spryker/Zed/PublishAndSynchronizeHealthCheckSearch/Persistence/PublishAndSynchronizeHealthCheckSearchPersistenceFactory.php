<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence;

use Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\SpyPublishAndSynchronizeHealthCheckQuery;
use Orm\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\SpyPublishAndSynchronizeHealthCheckSearchQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchDependencyProvider;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchConfig getConfig()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\PublishAndSynchronizeHealthCheckSearchRepositoryInterface getRepository()
 */
class PublishAndSynchronizeHealthCheckSearchPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\SpyPublishAndSynchronizeHealthCheckSearchQuery
     */
    public function createPublishAndSynchronizeHealthCheckSearchPropelQuery(): SpyPublishAndSynchronizeHealthCheckSearchQuery
    {
        return SpyPublishAndSynchronizeHealthCheckSearchQuery::create();
    }

    /**
     * @return \Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\SpyPublishAndSynchronizeHealthCheckQuery
     */
    public function getPublishAndSynchronizeHealthCheckPropelQuery(): SpyPublishAndSynchronizeHealthCheckQuery
    {
        return $this->getProvidedDependency(PublishAndSynchronizeHealthCheckSearchDependencyProvider::PROPEL_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_QUERY);
    }
}
