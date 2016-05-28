<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cache\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Cache\Business\CacheBusinessFactory getFactory()
 * @method \Spryker\Zed\Cache\CacheConfig getConfig()
 */
class CacheFacade extends AbstractFacade implements CacheFacadeInterface
{

    /**
     * @api
     *
     * @return array
     */
    public function deleteAllFiles()
    {
        return $this->getFactory()->createCacheDelete()->deleteAllFiles();
    }

    /**
     * @api
     *
     * @return array
     */
    public function deleteAllAutoloaderFiles()
    {
        return $this->getFactory()->createAutoloaderCacheDelete()->deleteAllFiles();
    }
}
