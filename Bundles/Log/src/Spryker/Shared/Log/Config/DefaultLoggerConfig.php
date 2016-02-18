<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Log\Config;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Spryker\Shared\Config;
use Spryker\Shared\Log\LogConstants;

class DefaultLoggerConfig implements LoggerConfigInterface
{

    /**
     * @return string
     */
    public function getChannelName()
    {
        return 'default';
    }

    /**
     * @return \Monolog\Handler\HandlerInterface[]
     */
    public function getHandlers()
    {
        return [
            new StreamHandler(
                Config::get(LogConstants::LOG_FILE_PATH),
                Config::get(LogConstants::LOG_LEVEL, Logger::ERROR)
            ),
        ];
    }

    /**
     * @return \callable[]
     */
    public function getProcessors()
    {
        return [];
    }

}
