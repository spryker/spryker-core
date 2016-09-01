<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Error;

use Psr\Log\LoggerInterface;
use Spryker\Shared\Error\ErrorHandler;
use Spryker\Shared\Error\ErrorLogger;
use Spryker\Shared\Error\ErrorLoggerInterface;
use Spryker\Shared\Error\ErrorRenderer\ErrorRendererInterface;
use Spryker\Shared\NewRelic\NewRelicApiInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Error
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

        $errorHandlerMock->expects($this->never())->method('doDatabaseRollback');
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

        $errorHandlerMock->expects($this->never())->method('doDatabaseRollback');
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

        $errorHandlerMock->expects($this->once())->method('doDatabaseRollback');
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

        $errorHandlerMock->expects($this->once())->method('doDatabaseRollback');
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
     * @param \Spryker\Shared\Error\ErrorLoggerInterface $errorLogger
     * @param \Spryker\Shared\Error\ErrorRenderer\ErrorRendererInterface $errorRenderer
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Error\ErrorHandler
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
            'doDatabaseRollback',
        ];

        $methods = array_merge($mockMethods, $methods);

        $errorHandlerMock = $this->getMockBuilder(ErrorHandler::class)
            ->setMethods($methods)
            ->setConstructorArgs([$errorLogger, $errorRenderer])
            ->getMock();

        return $errorHandlerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Error\ErrorLogger
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Error\ErrorRenderer\ErrorRendererInterface
     */
    protected function getErrorRendererMock()
    {
        $errorRendererMock = $this->getMockBuilder(ErrorRendererInterface::class)
            ->getMock();

        return $errorRendererMock;
    }

}
