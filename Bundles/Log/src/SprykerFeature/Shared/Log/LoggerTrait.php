<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Log;

use Psr\Log\LoggerInterface;
use SprykerFeature\Shared\Log\Config\LoggerConfigInterface;

trait LoggerTrait
{

    /**
     * @param LoggerConfigInterface $loggerConfig
     *
     * @return LoggerInterface|null
     */
    protected function getLogger(LoggerConfigInterface $loggerConfig = null)
    {
        return LoggerMultiton::getInstance($loggerConfig);
    }

}
