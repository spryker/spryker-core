<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cache\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Cache\Business\Model\CacheDelete;
use SprykerFeature\Zed\Cache\CacheConfig;

/**
 * @method CacheConfig getConfig()
 */
class CacheDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return CacheDelete
     */
    public function createCacheDelete()
    {
        $config = $this->getConfig();

        return new CacheDelete($config);
    }

}
