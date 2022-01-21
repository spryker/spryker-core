<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\LoggerConfig;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Log\LogConstants;

class LoggerConfigLoaderZed implements LoggerConfigLoaderInterface
{
    /**
     * @return bool
     */
    public function accept()
    {
        return $this->isZedApplication() && Config::hasKey(LogConstants::LOGGER_CONFIG_ZED);
    }

    /**
     * @return \Spryker\Shared\Log\Config\LoggerConfigInterface
     */
    public function create()
    {
        /** @phpstan-var class-string<\Spryker\Shared\Log\Config\LoggerConfigInterface> $loggerClassName */
        $loggerClassName = Config::get(LogConstants::LOGGER_CONFIG_ZED);

        return new $loggerClassName();
    }

    /**
     * @return bool
     */
    protected function isZedApplication(): bool
    {
        return APPLICATION === 'ZED';
    }
}
