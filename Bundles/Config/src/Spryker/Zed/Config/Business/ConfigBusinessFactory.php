<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Config\Business;

use Spryker\Zed\Config\Business\ConfigProfiler\ConfigProfiler;
use Spryker\Zed\Config\Business\ConfigProfiler\ConfigProfilerInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Config\ConfigConfig getConfig()
 * @method \Spryker\Zed\Config\Persistence\ConfigEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Config\Persistence\ConfigRepositoryInterface getRepository()
 */
class ConfigBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Config\Business\ConfigProfiler\ConfigProfilerInterface
     */
    public function createConfigProfiler(): ConfigProfilerInterface
    {
        return new ConfigProfiler();
    }
}
