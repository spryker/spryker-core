<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence;

use Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\SpyPublishAndSynchronizeHealthCheckQuery;
use Orm\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\SpyPublishAndSynchronizeHealthCheckStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageDependencyProvider;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageConfig getConfig()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\PublishAndSynchronizeHealthCheckStorageRepositoryInterface getRepository()
 */
class PublishAndSynchronizeHealthCheckStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\SpyPublishAndSynchronizeHealthCheckStorageQuery
     */
    public function createPublishAndSynchronizeHealthCheckStoragePropelQuery(): SpyPublishAndSynchronizeHealthCheckStorageQuery
    {
        return SpyPublishAndSynchronizeHealthCheckStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\SpyPublishAndSynchronizeHealthCheckQuery
     */
    public function getPublishAndSynchronizeHealthCheckPropelQuery(): SpyPublishAndSynchronizeHealthCheckQuery
    {
        return $this->getProvidedDependency(PublishAndSynchronizeHealthCheckStorageDependencyProvider::PROPEL_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_QUERY);
    }
}
