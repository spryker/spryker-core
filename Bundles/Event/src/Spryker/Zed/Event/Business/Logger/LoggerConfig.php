<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business\Logger;

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Spryker\Shared\Log\Config\LoggerConfigInterface;
use Spryker\Zed\Event\EventConfig;

class LoggerConfig implements LoggerConfigInterface
{

    /**
     * @var \Spryker\Zed\Event\EventConfig
     */
    protected $eventConfig;

    /**
     * @param \Spryker\Zed\Event\EventConfig $eventConfig
     */
    public function __construct(EventConfig $eventConfig)
    {
        $this->eventConfig = $eventConfig;
    }

    /**
     * @return string
     */
    public function getChannelName()
    {
        return 'application_events';
    }

    /**
     * @return \Monolog\Handler\HandlerInterface[]
     */
    public function getHandlers()
    {
        return [
            $this->createStreamHandler(),
        ];
    }

    /**
     * @return callable[]
     */
    public function getProcessors()
    {
        return [
            new PsrLogMessageProcessor(),
        ];
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function createStreamHandler()
    {
        $eventLogPath = $this->eventConfig->findEventLogPath();

        if ($eventLogPath === null) {
            return new NullHandler();
        }

        return new StreamHandler(
            $eventLogPath,
            Logger::INFO
        );
    }

}
