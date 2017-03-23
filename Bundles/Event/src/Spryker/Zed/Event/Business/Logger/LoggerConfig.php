<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Event\EventConstants;
use Spryker\Shared\Log\Config\LoggerConfigInterface;

class LoggerConfig implements LoggerConfigInterface
{

    /**
     * @var \Spryker\Shared\Config\Config
     */
    protected $applicationConfig;

    /**
     * @param \Spryker\Shared\Config\Config $applicationConfig
     */
    public function __construct(Config $applicationConfig)
    {
        $this->applicationConfig = $applicationConfig;
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
     * @return \callable[]
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
        return new StreamHandler(
            $this->applicationConfig->get(EventConstants::LOG_FILE_PATH),
            Logger::INFO
        );
    }

}
