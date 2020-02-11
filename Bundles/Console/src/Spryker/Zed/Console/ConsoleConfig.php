<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Console\ConsoleConfig getSharedConfig()
 */
class ConsoleConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function shouldCatchExceptions(): bool
    {
        return $this->getSharedConfig()->shouldCatchExceptions();
    }
}
