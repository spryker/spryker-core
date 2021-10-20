<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ErrorHandler;

use Codeception\Test\Unit;
use Exception;
use Psr\Log\LoggerInterface;
use Spryker\Service\UtilSanitize\UtilSanitizeService;
use Spryker\Shared\ErrorHandler\ErrorHandler;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Shared\ErrorHandler\ErrorLoggerInterface;
use Spryker\Shared\ErrorHandler\ErrorRenderer\ErrorRendererInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group ErrorHandler
 * @group ErrorHandlerTest
 * Add your own group annotations below this line
 */
class ErrorHandlerTest extends Unit
{
    /**
     * @return void
     */
    public function testIfHandleExceptionThrowsExceptionErrorLoggerShouldLogBeforeExceptionAndLogExceptionAndSendExitCode(): void
    {
        $errorLoggerMock = $this->getErrorLoggerMock();
        $errorLoggerMock->expects($this->exactly(2))->method('log');

        $exception = new Exception('Test exception');

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
    public function testIfHandleExceptionThrowsExceptionErrorLoggerShouldLogBeforeExceptionAndLogExceptionAndShouldNotSendExitCode(): void
    {
        $errorLoggerMock = $this->getErrorLoggerMock();
        $errorLoggerMock->expects($this->exactly(2))->method('log');

        $exception = new Exception('Test exception');

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
    public function testHandleExceptionShouldLogRenderErrorAndSendExitCode(): void
    {
        $errorLoggerMock = $this->getErrorLoggerMock();
        $errorLoggerMock->expects($this->once())->method('log');

        $errorRendererMock = $this->getErrorRendererMock();
        $errorRendererMock->expects($this->once())->method('render');

        $errorHandlerMock = $this->getErrorHandlerMock($errorLoggerMock, $errorRendererMock);

        $errorHandlerMock->expects($this->once())->method('cleanOutputBuffer');
        $errorHandlerMock->expects($this->once())->method('sendExitCode');

        $errorHandlerMock->handleException(new Exception());
    }

    /**
     * @return void
     */
    public function testHandleExceptionShouldLogRenderErrorAndNotSendExitCode(): void
    {
        $errorLoggerMock = $this->getErrorLoggerMock();
        $errorLoggerMock->expects($this->once())->method('log');

        $errorRendererMock = $this->getErrorRendererMock();
        $errorRendererMock->expects($this->once())->method('render');

        $errorHandlerMock = $this->getErrorHandlerMock($errorLoggerMock, $errorRendererMock);

        $errorHandlerMock->expects($this->once())->method('cleanOutputBuffer');
        $errorHandlerMock->expects($this->never())->method('sendExitCode');

        $errorHandlerMock->handleException(new Exception(), false);
    }

    /**
     * @return void
     */
    public function testHandleFatalShouldCallHandleExceptionWhenLastErrorExists(): void
    {
        $errorLoggerMock = $this->getErrorLoggerMock();
        $errorRendererMock = $this->getErrorRendererMock();
        $errorHandlerMock = $this->getErrorHandlerMock($errorLoggerMock, $errorRendererMock, ['getLastError', 'handleException']);
        $errorHandlerMock->expects($this->once())->method('handleException');
        $errorHandlerMock->expects($this->once())->method('getLastError')->willReturn(
            ['message' => 'message', 'file' => 'file', 'type' => 1, 'line' => 123],
        );

        $errorHandlerMock->handleFatal();
    }

    /**
     * @return void
     */
    public function testHandleFatalShouldNotCallHandleExceptionWhenNoLastErrorExists(): void
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\ErrorHandler\ErrorHandler
     */
    protected function getErrorHandlerMock(
        ErrorLoggerInterface $errorLogger,
        ErrorRendererInterface $errorRenderer,
        array $methods = []
    ): ErrorHandler {
        $mockMethods = [
            'cleanOutputBuffer',
            'sendExitCode',
            'send500Header',
        ];

        $methods = array_merge($mockMethods, $methods);

        $errorHandlerMock = $this->getMockBuilder(ErrorHandler::class)
            ->setMethods($methods)
            ->setConstructorArgs([$errorLogger, $errorRenderer, new UtilSanitizeService()])
            ->getMock();

        return $errorHandlerMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\ErrorHandler\ErrorLogger
     */
    protected function getErrorLoggerMock(): ErrorLogger
    {
        $loggerMock = $this->getLoggerMock();

        $errorLoggerMock = $this->getMockBuilder(ErrorLogger::class)
            ->setMethods(['getLogger', 'log'])
            ->getMock();

        $errorLoggerMock->method('getLogger')->willReturn($loggerMock);

        return $errorLoggerMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Psr\Log\LoggerInterface
     */
    protected function getLoggerMock(): LoggerInterface
    {
        $loggerMock = $this->getMockBuilder(LoggerInterface::class)
            ->getMock();

        return $loggerMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\ErrorHandler\ErrorRenderer\ErrorRendererInterface
     */
    protected function getErrorRendererMock(): ErrorRendererInterface
    {
        $errorRendererMock = $this->getMockBuilder(ErrorRendererInterface::class)
            ->getMock();

        return $errorRendererMock;
    }
}
