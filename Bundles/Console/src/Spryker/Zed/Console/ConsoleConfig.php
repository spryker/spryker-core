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
     * @var string
     */
    protected const SAPI_NAME_CONSOLE = 'cli';

    /**
     * @api
     *
     * @return bool
     */
    public function shouldCatchExceptions(): bool
    {
        return $this->getSharedConfig()->shouldCatchExceptions();
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->getSharedConfig()->isDebugModeEnabled();
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isPhpSapiEqualCli(): bool
    {
        return PHP_SAPI === static::SAPI_NAME_CONSOLE;
    }
}
