<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Shared\Log\Fixtures;

use Monolog\Handler\HandlerInterface;
use SprykerFeature\Shared\Log\Config\LoggerConfigInterface;

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
     * @return \callable[]
     */
    public function getProcessors()
    {
        return [];
    }

}
