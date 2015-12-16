<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cache\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Cache\Business\Model\CacheDelete;
use Spryker\Zed\Cache\CacheConfig;

/**
 * @method CacheConfig getConfig()
 */
class CacheBusinessFactory extends AbstractBusinessFactory
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
