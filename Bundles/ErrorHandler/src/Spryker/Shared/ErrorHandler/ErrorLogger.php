<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler;

use Spryker\Shared\Log\LoggerTrait;
use Spryker\Shared\NewRelicApi\NewRelicApiTrait;
use Spryker\Yves\Monitoring\MonitoringFactory;
use Spryker\Zed\Monitoring\Communication\MonitoringCommunicationFactory;
use Throwable;

class ErrorLogger implements ErrorLoggerInterface
{
    use LoggerTrait;
    use NewRelicApiTrait;

    /**
     * @var self
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
            $this->createMonitoring()->setError($message, $exception);
            $this->getLogger()->critical($message, ['exception' => $exception]);
        } catch (Throwable $internalException) {
            $this->createMonitoring()->setError($internalException->getMessage(), $exception);
        }
    }

    /**
     * @return \Spryker\Shared\MonitoringExtension\MonitoringInterface
     */
    protected function createMonitoring()
    {
        if (APPLICATION === 'ZED') {
            $zedMonitoringFactory = new MonitoringCommunicationFactory();

            return $zedMonitoringFactory->createMonitoring();
        }

        $yvesMonitoringFactory = new MonitoringFactory();

        return $yvesMonitoringFactory->createMonitoring();
    }

    /**
     * @param \Exception|\Throwable $exception
>>>>>>> core-4452 added Monitoring to Shared ErrorHandler
     *
     * @return string
     */
    protected function buildMessage($exception)
    {
        return sprintf(
            '%s - %s in "%s::%d"',
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
    }
}
