<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Log\Config;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use SprykerEngine\Shared\Config;

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
     * @return HandlerInterface[]
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
