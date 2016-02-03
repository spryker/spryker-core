<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Log\Config;

use Monolog\Handler\StreamHandler;
use Spryker\Shared\Config;

class DefaultLoggerConfig implements LoggerConfigInterface
{

    const DEFAULT_LOG_FILE_PATH = 'DEFAULT_LOG_FILE_PATH';
    const DEFAULT_LOG_LEVEL = 'DEFAULT_LOG_LEVEL';

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
            new StreamHandler(Config::get(self::DEFAULT_LOG_FILE_PATH), Config::get(self::DEFAULT_LOG_LEVEL)),
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
