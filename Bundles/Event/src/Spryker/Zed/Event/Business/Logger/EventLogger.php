<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business\Logger;

use Spryker\Shared\Log\Config\LoggerConfigInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Event\EventConfig;

class EventLogger implements EventLoggerInterface
{

    use LoggerTrait;

    /**
     * @var \Spryker\Shared\Log\Config\LoggerConfigInterface
     */
    protected $loggerConfig;

    /**
     * @var \Spryker\Zed\Event\EventConfig
     */
    protected $eventConfig;

    /**
     * @param \Spryker\Shared\Log\Config\LoggerConfigInterface $loggerConfig
     * @param \Spryker\Zed\Event\EventConfig $eventConfig
     */
    public function __construct(
        LoggerConfigInterface $loggerConfig,
        EventConfig $eventConfig
    ) {
        $this->loggerConfig = $loggerConfig;
        $this->eventConfig = $eventConfig;
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function log($message)
    {
        if (!$this->eventConfig->isLoggerActivated()) {
             return;
        }

        $this->getLogger($this->loggerConfig)
            ->info($message);
    }

}
