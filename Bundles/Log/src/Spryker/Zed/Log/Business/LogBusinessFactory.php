<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Log\Business\Model\LogFileDirectoryRemover;

/**
 * @method \Spryker\Zed\Log\LogConfig getConfig()
 */
class LogBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Log\Business\Model\LogFileDirectoryRemoverInterface
     */
    public function createLogFileDirectoryRemover()
    {
        return new LogFileDirectoryRemover(
            $this->getConfig()->getLogFileDirectories()
        );
    }
}
