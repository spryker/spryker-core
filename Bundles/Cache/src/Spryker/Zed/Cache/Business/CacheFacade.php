<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cache\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Cache\Business\CacheBusinessFactory getFactory()
 * @method \Spryker\Zed\Cache\CacheConfig getConfig()
 */
class CacheFacade extends AbstractFacade
{

    /**
     * @return array
     */
    public function deleteAllFiles()
    {
        return $this->getFactory()->createCacheDelete()->deleteAllFiles();
    }

}
