<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Log;

use Psr\Log\LoggerInterface;
use Spryker\Shared\Log\Config\LoggerConfigInterface;

trait LoggerTrait
{

    /**
     * @param \Spryker\Shared\Log\Config\LoggerConfigInterface $loggerConfig
     *
     * @return LoggerInterface|null
     */
    protected function getLogger(LoggerConfigInterface $loggerConfig = null)
    {
        return LoggerFactory::getInstance($loggerConfig);
    }

}
