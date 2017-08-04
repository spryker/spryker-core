<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerTest\Shared\Log\Fixtures;

use Monolog\Handler\HandlerInterface;
use Spryker\Shared\Log\Config\LoggerConfigInterface;

class TestLoggerConfig implements LoggerConfigInterface
{

    /**
     * @return string
     */
    public function getChannelName()
    {
        return 'test';
    }

    /**
     * @return HandlerInterface[]
     */
    public function getHandlers()
    {
        return [];
    }

    /**
     * @return \callable[]
     */
    public function getProcessors()
    {
        return [];
    }

}
