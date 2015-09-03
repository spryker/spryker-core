<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cache\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Cache\CacheConfig;

/**
 * @method CacheDependencyContainer getDependencyContainer()
 * @method CacheConfig getConfig()
 */
class CacheFacade extends AbstractFacade
{

    /**
     * @return array
     */
    public function deleteAllFiles()
    {
        return $this->getDependencyContainer()->createCacheDelete()->deleteAllFiles();
    }

}
