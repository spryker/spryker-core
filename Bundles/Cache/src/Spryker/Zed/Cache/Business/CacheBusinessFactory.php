<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cache\Business;

use Spryker\Zed\Cache\Business\Model\CacheDelete;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Cache\CacheConfig getConfig()
 */
class CacheBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Cache\Business\Model\CacheDelete
     */
    public function createCacheDelete()
    {
        $config = $this->getConfig();

        return new CacheDelete($config);
    }

}
