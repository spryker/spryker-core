<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\LoggerConfig;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Log\LogConstants;

class LoggerConfigLoaderGlue implements LoggerConfigLoaderInterface
{
    /**
     * @return bool
     */
    public function accept()
    {
        return $this->isGlueApplication() && Config::hasKey(LogConstants::LOGGER_CONFIG_GLUE);
    }

    /**
     * @return \Spryker\Shared\Log\Config\LoggerConfigInterface
     */
    public function create()
    {
        $loggerClassName = Config::get(LogConstants::LOGGER_CONFIG_GLUE);

        return new $loggerClassName();
    }

    /**
     * @return bool
     */
    protected function isGlueApplication(): bool
    {
        return APPLICATION === 'GLUE';
    }
}
