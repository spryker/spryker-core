<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Log;

use Spryker\Shared\Log\Config\LoggerConfigInterface;

trait LoggerTrait
{

    /**
     * @param \Spryker\Shared\Log\Config\LoggerConfigInterface $loggerConfig
     *
     * @return \Psr\Log\LoggerInterface|null
     */
    protected function getLogger(LoggerConfigInterface $loggerConfig = null)
    {
        return LoggerFactory::getInstance($loggerConfig);
    }

}
