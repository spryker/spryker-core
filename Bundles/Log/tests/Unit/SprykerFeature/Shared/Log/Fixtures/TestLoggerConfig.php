<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Shared\Log\Fixtures;

use Monolog\Handler\HandlerInterface;
use SprykerFeature\Shared\Log\Config\LoggerConfigInterface;

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
     * @return array|\callable[]
     */
    public function getProcessors()
    {
        return [];
    }
}
