<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ErrorHandler;

use Codeception\Test\Unit;
use Exception;
use Psr\Log\LoggerInterface;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Shared\NewRelicApi\NewRelicApiInterface;

/**
 * Auto-generated group annotations
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
    public function testLogShouldAddCriticalLogAndNoticeErrorToNewRelic()
    {
        $loggerMock = $this->getLoggerMock();
        $loggerMock->expects($this->once())->method('critical');

        $newRelicApiMock = $this->getNewRelicApiMock();
        $newRelicApiMock->expects($this->once())->method('noticeError');

        $errorLoggerMock = $this->getErrorLoggerMock($loggerMock, $newRelicApiMock);
        $exception = new Exception('TestException');

        $errorLoggerMock->log($exception);
    }

    /**
     * @return void
     */
    public function testWhenLoggerThrowsExceptionLogShouldNoticeErrorToNewRelic()
    {
        $exception = new Exception('TestException');
        $loggerMock = $this->getLoggerMock();
        $loggerMock->expects($this->once())->method('critical')->willThrowException($exception);
        $loggerMock->expects($this->never())->method('error');

        $newRelicApiMock = $this->getNewRelicApiMock();
        $newRelicApiMock->expects($this->exactly(2))->method('noticeError');

        $errorLoggerMock = $this->getErrorLoggerMock($loggerMock, $newRelicApiMock);

        $errorLoggerMock->log($exception);
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Spryker\Shared\NewRelicApi\NewRelicApiInterface $newRelicApi
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\ErrorHandler\ErrorLogger
     */
    protected function getErrorLoggerMock(LoggerInterface $logger, NewRelicApiInterface $newRelicApi)
    {
        $errorLoggerMock = $this->getMockBuilder(ErrorLogger::class)
            ->setMethods(['getLogger', 'createNewRelicApi'])
            ->getMock();

        $errorLoggerMock->method('getLogger')->willReturn($logger);
        $errorLoggerMock->method('createNewRelicApi')->willReturn($newRelicApi);

        return $errorLoggerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Psr\Log\LoggerInterface
     */
    protected function getLoggerMock()
    {
        $loggerMock = $this->getMockBuilder(LoggerInterface::class)
            ->getMock();

        return $loggerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\NewRelicApi\NewRelicApiInterface
     */
    protected function getNewRelicApiMock()
    {
        $newRelicApiMock = $this->getMockBuilder(NewRelicApiInterface::class)
            ->getMock();

        return $newRelicApiMock;
    }

}
