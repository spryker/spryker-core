<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\ErrorHandler;

use Psr\Log\LoggerInterface;
use Spryker\Shared\ErrorHandler\ErrorHandler;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Shared\ErrorHandler\ErrorLoggerInterface;
use Spryker\Shared\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Spryker\Shared\NewRelic\NewRelicApiInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group ErrorHandler
 * @group ErrorHandlerTest
 */
class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testIfHandleExceptionThrowsExceptionErrorLoggerShouldLogBeforeExceptionAndLogExceptionAndSendExitCode()
    {
        $errorLoggerMock = $this->getErrorLoggerMock();
        $errorLoggerMock->expects($this->exactly(2))->method('log');

        $exception = new \Exception('Test exception');

        $errorRendererMock = $this->getErrorRendererMock();

        $errorHandlerMock = $this->getErrorHandlerMock($errorLoggerMock, $errorRendererMock);
        $errorHandlerMock->expects($this->once())->method('send500Header')->willThrowException($exception);

        $errorHandlerMock->expects($this->never())->method('cleanOutputBuffer');
        $errorHandlerMock->expects($this->once())->method('sendExitCode');

        $errorHandlerMock->handleException($exception);
    }

    /**
     * @return void
     */
    public function testIfHandleExceptionThrowsExceptionErrorLoggerShouldLogBeforeExceptionAndLogExceptionAndShouldNotSendExitCode()
    {
        $errorLoggerMock = $this->getErrorLoggerMock();
        $errorLoggerMock->expects($this->exactly(2))->method('log');

        $exception = new \Exception('Test exception');

        $errorRendererMock = $this->getErrorRendererMock();

        $errorHandlerMock = $this->getErrorHandlerMock($errorLoggerMock, $errorRendererMock);
        $errorHandlerMock->expects($this->once())->method('send500Header')->willThrowException($exception);

        $errorHandlerMock->expects($this->never())->method('cleanOutputBuffer');
        $errorHandlerMock->expects($this->never())->method('sendExitCode');

        $errorHandlerMock->handleException($exception, false);
    }

    /**
     * @return void
     */
    public function testHandleExceptionShouldLogRenderErrorAndSendExitCode()
    {
        $errorLoggerMock = $this->getErrorLoggerMock();
        $errorLoggerMock->expects($this->once())->method('log');

        $errorRendererMock = $this->getErrorRendererMock();
        $errorRendererMock->expects($this->once())->method('render');

        $errorHandlerMock = $this->getErrorHandlerMock($errorLoggerMock, $errorRendererMock);

        $errorHandlerMock->expects($this->once())->method('cleanOutputBuffer');
        $errorHandlerMock->expects($this->once())->method('sendExitCode');

        $errorHandlerMock->handleException(new \Exception);
    }

    /**
     * @return void
     */
    public function testHandleExceptionShouldLogRenderErrorAndNotSendExitCode()
    {
        $errorLoggerMock = $this->getErrorLoggerMock();
        $errorLoggerMock->expects($this->once())->method('log');

        $errorRendererMock = $this->getErrorRendererMock();
        $errorRendererMock->expects($this->once())->method('render');

        $errorHandlerMock = $this->getErrorHandlerMock($errorLoggerMock, $errorRendererMock);

        $errorHandlerMock->expects($this->once())->method('cleanOutputBuffer');
        $errorHandlerMock->expects($this->never())->method('sendExitCode');

        $errorHandlerMock->handleException(new \Exception, false);
    }

    /**
     * @return void
     */
    public function testHandleFatalShouldCallHandleExceptionWhenLastErrorExists()
    {
        $errorLoggerMock = $this->getErrorLoggerMock();
        $errorRendererMock = $this->getErrorRendererMock();
        $errorHandlerMock = $this->getErrorHandlerMock($errorLoggerMock, $errorRendererMock, ['getLastError', 'handleException']);
        $errorHandlerMock->expects($this->once())->method('handleException');
        $errorHandlerMock->expects($this->once())->method('getLastError')->willReturn(
            ['message' => 'message', 'file' => 'file', 'type' => 1, 'line' => 123]
        );

        $errorHandlerMock->handleFatal();
    }

    /**
     * @return void
     */
    public function testHandleFatalShouldNotCallHandleExceptionWhenNoLastErrorExists()
    {
        $errorLoggerMock = $this->getErrorLoggerMock();
        $errorRendererMock = $this->getErrorRendererMock();
        $errorHandlerMock = $this->getErrorHandlerMock($errorLoggerMock, $errorRendererMock, ['getLastError', 'handleException']);
        $errorHandlerMock->expects($this->never())->method('handleException');
        $errorHandlerMock->expects($this->once())->method('getLastError')->willReturn(null);

        $errorHandlerMock->handleFatal();
    }

    /**
     * @param \Spryker\Shared\ErrorHandler\ErrorLoggerInterface $errorLogger
     * @param \Spryker\Shared\ErrorHandler\ErrorRenderer\ErrorRendererInterface $errorRenderer
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\ErrorHandler\ErrorHandler
     */
    protected function getErrorHandlerMock(
        ErrorLoggerInterface $errorLogger,
        ErrorRendererInterface $errorRenderer,
        array $methods = []
    ) {
        $mockMethods = [
            'cleanOutputBuffer',
            'sendExitCode',
            'send500Header',
        ];

        $methods = array_merge($mockMethods, $methods);

        $errorHandlerMock = $this->getMockBuilder(ErrorHandler::class)
            ->setMethods($methods)
            ->setConstructorArgs([$errorLogger, $errorRenderer])
            ->getMock();

        return $errorHandlerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\ErrorHandler\ErrorLogger
     */
    protected function getErrorLoggerMock()
    {
        $loggerMock = $this->getLoggerMock();
        $newRelicApiMock = $this->getNewRelicApiMock();

        $errorLoggerMock = $this->getMockBuilder(ErrorLogger::class)
            ->setMethods(['getLogger', 'createNewRelicApi', 'log'])
            ->getMock();

        $errorLoggerMock->method('getLogger')->willReturn($loggerMock);
        $errorLoggerMock->method('createNewRelicApi')->willReturn($newRelicApiMock);

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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\NewRelic\NewRelicApiInterface
     */
    protected function getNewRelicApiMock()
    {
        $newRelicApiMock = $this->getMockBuilder(NewRelicApiInterface::class)
            ->getMock();

        return $newRelicApiMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\ErrorHandler\ErrorRenderer\ErrorRendererInterface
     */
    protected function getErrorRendererMock()
    {
        $errorRendererMock = $this->getMockBuilder(ErrorRendererInterface::class)
            ->getMock();

        return $errorRendererMock;
    }

}
