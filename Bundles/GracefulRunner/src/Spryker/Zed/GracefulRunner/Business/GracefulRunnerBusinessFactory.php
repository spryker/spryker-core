<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GracefulRunner\Business;

use Spryker\Zed\GracefulRunner\Business\GracefulRunner\GracefulRunner;
use Spryker\Zed\GracefulRunner\Business\GracefulRunner\GracefulRunnerInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\GracefulRunner\GracefulRunnerConfig getConfig()
 * @method \Spryker\Zed\GracefulRunner\Persistence\GracefulRunnerEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\GracefulRunner\Persistence\GracefulRunnerRepositoryInterface getRepository()
 */
class GracefulRunnerBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\GracefulRunner\Business\GracefulRunner\GracefulRunnerInterface
     */
    public function createGracefulRunner(): GracefulRunnerInterface
    {
        return new GracefulRunner();
    }
}
