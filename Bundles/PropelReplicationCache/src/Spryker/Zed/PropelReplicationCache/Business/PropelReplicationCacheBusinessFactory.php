<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelReplicationCache\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PropelReplicationCache\Business\Model\PropelReplicationCache;
use Spryker\Zed\PropelReplicationCache\Dependency\Client\PropelReplicationCacheToStorageRedisClientInterface;
use Spryker\Zed\PropelReplicationCache\PropelReplicationCacheDependencyProvider;

/**
 * @method \Spryker\Zed\PropelReplicationCache\PropelReplicationCacheConfig getConfig()
 */
class PropelReplicationCacheBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @var \Spryker\Zed\PropelReplicationCache\Business\Model\PropelReplicationCache|null Performance optimization
     */
    protected $propelReplicationCacheModel;

    /**
     * @return \Spryker\Zed\PropelReplicationCache\Business\Model\PropelReplicationCache
     */
    public function createReplicationCacheModel(): PropelReplicationCache
    {
        if ($this->propelReplicationCacheModel === null) {
            $this->propelReplicationCacheModel = new PropelReplicationCache(
                $this->getPropelReplicationCacheClient(),
                $this->getConfig()->isReplicationEnabled(),
                $this->getConfig()->getCacheTTL(),
            );
        }

        return $this->propelReplicationCacheModel;
    }

    /**
     * @return \Spryker\Zed\PropelReplicationCache\Dependency\Client\PropelReplicationCacheToStorageRedisClientInterface
     */
    public function getPropelReplicationCacheClient(): PropelReplicationCacheToStorageRedisClientInterface
    {
        return $this->getProvidedDependency(PropelReplicationCacheDependencyProvider::CLIENT_STORAGE_REDIS);
    }
}
