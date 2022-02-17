<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log;

use Psr\Log\LoggerInterface;
use Spryker\Shared\Log\Config\LoggerConfigInterface;

trait LoggerTrait
{
    /**
     * @param \Spryker\Shared\Log\Config\LoggerConfigInterface|null $loggerConfig
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected function getLogger(?LoggerConfigInterface $loggerConfig = null): LoggerInterface
    {
        return LoggerFactory::getInstance($loggerConfig);
    }
}
