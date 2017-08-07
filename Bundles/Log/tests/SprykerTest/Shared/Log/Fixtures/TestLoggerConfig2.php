<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerTest\Shared\Log\Fixtures;

use Monolog\Handler\HandlerInterface;
use Spryker\Shared\Log\Config\LoggerConfigInterface;

class TestLoggerConfig2 implements LoggerConfigInterface
{

    /**
     * @return string
     */
    public function getChannelName()
    {
        return 'test2';
    }

    /**
     * @return HandlerInterface[]
     */
    public function getHandlers()
    {
        return [];
    }

    /**
     * @return callable[]
     */
    public function getProcessors()
    {
        return [];
    }

}
