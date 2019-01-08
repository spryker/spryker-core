<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler;

use Spryker\Service\Monitoring\MonitoringService;
use Spryker\Shared\Log\LoggerTrait;
use Throwable;

class ErrorLogger implements ErrorLoggerInterface
{
    use LoggerTrait;

    /**
     * @var self|null
     */
    protected static $instance;

    /**
     * @return \Spryker\Shared\ErrorHandler\ErrorLogger
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    /**
     * @param \Throwable $exception
     *
     * @return void
     */
    public function log($exception)
    {
        try {
            $message = $this->buildMessage($exception);
            $this->createMonitoringService()->setError($message, $exception);
            $this->getLogger()->critical($message, ['exception' => $exception]);
            unset($message);
        } catch (Throwable $internalException) {
            $this->createMonitoringService()->setError($internalException->getMessage(), $exception);
        }
    }

    /**
     * @param \Throwable $exception
     *
     * @return string
     */
    protected function buildMessage(Throwable $exception)
    {
        return sprintf(
            '%s - %s in "%s::%d"',
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
    }

    /**
     * @return \Spryker\Service\Monitoring\MonitoringServiceInterface
     */
    protected function createMonitoringService()
    {
        return new MonitoringService();
    }
}
