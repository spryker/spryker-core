<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business\Logger;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Event\EventConstants;
use Spryker\Shared\Log\Config\LoggerConfigInterface;
use Spryker\Shared\Log\LoggerTrait;

class EventLogger implements EventLoggerInterface
{

    use LoggerTrait;

    /**
     * @var \Spryker\Shared\Log\Config\LoggerConfigInterface
     */
    protected $loggerConfig;

    /**
     * @var \Spryker\Shared\Config\Config
     */
    protected $applicationConfig;

    /**
     * @param \Spryker\Shared\Log\Config\LoggerConfigInterface $loggerConfig
     * @param \Spryker\Shared\Config\Config $applicationConfig
     */
    public function __construct(
        LoggerConfigInterface $loggerConfig,
        Config $applicationConfig
    ) {
        $this->loggerConfig = $loggerConfig;
        $this->applicationConfig = $applicationConfig;
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function log($message)
    {
        if (!$this->isLoggerActivated()) {
             return;
        }

        $this->getLogger($this->loggerConfig)
            ->info($message);
    }

    /**
     * @return bool
     */
    protected function isLoggerActivated()
    {
        if (!$this->applicationConfig->hasKey(EventConstants::LOGGER_ACTIVE)) {
            return false;
        }

        return $this->applicationConfig->get(EventConstants::LOGGER_ACTIVE, false);
    }

}
