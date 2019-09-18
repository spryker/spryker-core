<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ErrorHandler;

use Codeception\Test\Unit;
use Exception;
use Psr\Log\LoggerInterface;
use Spryker\Service\Monitoring\MonitoringServiceInterface;
use Spryker\Shared\ErrorHandler\ErrorLogger;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group ErrorHandler
 * @group ErrorLoggerTest
 * Add your own group annotations below this line
 */
class ErrorLoggerTest extends Unit
{
    /**
     * @return void
     */
    public function testLogShouldAddCriticalLogAndNoticeErrorToMonitoring()
    {
        $loggerMock = $this->getLoggerMock();
        $loggerMock->expects($this->once())->method('critical');

        $monitoringServiceMock = $this->getMonitoringServiceMock();
        $monitoringServiceMock->expects($this->once())->method('setError');

        $errorLoggerMock = $this->getErrorLoggerMock($loggerMock, $monitoringServiceMock);
        $exception = new Exception('TestException');

        $errorLoggerMock->log($exception);
    }

    /**
     * @return void
     */
    public function testWhenLoggerThrowsExceptionLogShouldNoticeErrorToMonitoring()
    {
        $exception = new Exception('TestException');
        $loggerMock = $this->getLoggerMock();
        $loggerMock->expects($this->once())->method('critical')->willThrowException($exception);
        $loggerMock->expects($this->never())->method('error');

        $monitoringServiceMock = $this->getMonitoringServiceMock();
        $monitoringServiceMock->expects($this->exactly(2))->method('setError');

        $errorLoggerMock = $this->getErrorLoggerMock($loggerMock, $monitoringServiceMock);

        $errorLoggerMock->log($exception);
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Spryker\Service\Monitoring\MonitoringServiceInterface $monitoringService
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\ErrorHandler\ErrorLogger
     */
    protected function getErrorLoggerMock(LoggerInterface $logger, MonitoringServiceInterface $monitoringService)
    {
        $errorLoggerMock = $this->getMockBuilder(ErrorLogger::class)
            ->setMethods(['getLogger', 'createMonitoringService'])
            ->getMock();

        $errorLoggerMock->method('getLogger')->willReturn($logger);
        $errorLoggerMock->method('createMonitoringService')->willReturn($monitoringService);

        return $errorLoggerMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Psr\Log\LoggerInterface
     */
    protected function getLoggerMock()
    {
        $loggerMock = $this->getMockBuilder(LoggerInterface::class)
            ->getMock();

        return $loggerMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\Monitoring\MonitoringServiceInterface
     */
    protected function getMonitoringServiceMock()
    {
        $monitoringServiceMock = $this->getMockBuilder(MonitoringServiceInterface::class)
            ->getMock();

        return $monitoringServiceMock;
    }
}
