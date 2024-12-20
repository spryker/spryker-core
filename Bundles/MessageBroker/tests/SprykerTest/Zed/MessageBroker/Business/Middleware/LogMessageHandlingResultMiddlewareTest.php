<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Business\Middleware;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Spryker\Zed\MessageBroker\Business\Exception\MessageBrokerException;
use Spryker\Zed\MessageBroker\Business\Logger\MessageLogger;
use Spryker\Zed\MessageBroker\Business\Middleware\LogMessageHandlingResultMiddleware;
use SprykerTest\Zed\MessageBroker\MessageBrokerBusinessTester;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBroker
 * @group Business
 * @group Middleware
 * @group LogMessageHandlingResultMiddlewareTest
 * Add your own group annotations below this line
 */
class LogMessageHandlingResultMiddlewareTest extends Unit
{
    /**
     * @var string
     */
    protected const TRANSFER_NAME = 'MessageBrokerTestMessage';

    /**
     * @var \SprykerTest\Zed\MessageBroker\MessageBrokerBusinessTester
     */
    protected MessageBrokerBusinessTester $tester;

    /**
     * @return void
     */
    public function testLogMessageHandlingResultMiddlewareWritesSuccessLogInCaseOfSuccessfulMessageSending(): void
    {
        // Arrange
        $messageAttributesTransfer = $this->tester->getMessageAttributesTransfer([
            MessageAttributesTransfer::TRANSFER_NAME => static::TRANSFER_NAME,
            MessageAttributesTransfer::EVENT => static::TRANSFER_NAME,
            MessageAttributesTransfer::NAME => static::TRANSFER_NAME,
        ]);
        $messageBrokerTestMessageTransfer = (new MessageBrokerTestMessageTransfer())
            ->setMessageAttributes($messageAttributesTransfer);

        $loggerMock = $this->createMock(LoggerInterface::class);
        $logMessageHandlingResultMiddleware = (new LogMessageHandlingResultMiddleware($this->getMessageLoggerMock($loggerMock)));

        $middlewareMock = $this->getMiddlewareMock();
        $stackMock = $this->getStackMock($middlewareMock);

        $envelope = Envelope::wrap($messageBrokerTestMessageTransfer);
        $middlewareMock->expects($this->once())->method('handle')->willReturn($envelope);

        $context = $this->getMessageLogDataFromMessageAttributesTransfer(
            $messageAttributesTransfer,
            static::TRANSFER_NAME,
        );
        $expectedLog = [
                'code' => $this->tester->getModuleConfig()->getMessageBrokerCallSuccessCode(),
                'codeName' => $this->tester->getModuleConfig()->getMessageBrokerCallSuccessCodeName(),
                'duration' => '0s',
                'activityType' => 'Message publishing',
            ] + $context;

        // Assert
        $loggerMock->expects($this->once())
            ->method('log')
            ->with(Logger::INFO, '', $expectedLog);

        // Act
        $logMessageHandlingResultMiddleware->handle($envelope, $stackMock);
    }

    /**
     * @return void
     */
    public function testLogMessageHandlingResultMiddlewareWritesSuccessLogInCaseOfSuccessfulMessageReceiving(): void
    {
        // Arrange
        $messageAttributesTransfer = $this->tester->getMessageAttributesTransfer([
            MessageAttributesTransfer::TRANSFER_NAME => static::TRANSFER_NAME,
            MessageAttributesTransfer::EVENT => static::TRANSFER_NAME,
            MessageAttributesTransfer::NAME => static::TRANSFER_NAME,
        ]);
        $messageBrokerTestMessageTransfer = (new MessageBrokerTestMessageTransfer())
            ->setMessageAttributes($messageAttributesTransfer);

        $loggerMock = $this->createMock(LoggerInterface::class);
        $logMessageHandlingResultMiddleware = (new LogMessageHandlingResultMiddleware($this->getMessageLoggerMock($loggerMock)));

        $middlewareMock = $this->getMiddlewareMock();
        $stackMock = $this->getStackMock($middlewareMock);

        $envelope = Envelope::wrap($messageBrokerTestMessageTransfer, [new ReceivedStamp('test')]);
        $middlewareMock->expects($this->once())->method('handle')->willReturn($envelope);

        $context = $this->getMessageLogDataFromMessageAttributesTransfer(
            $messageAttributesTransfer,
            static::TRANSFER_NAME,
        );
        $expectedLog = [
                'code' => $this->tester->getModuleConfig()->getMessageBrokerConsumeSuccessCode(),
                'codeName' => $this->tester->getModuleConfig()->getMessageBrokerConsumeSuccessCodeName(),
                'duration' => '0s',
                'activityType' => 'Message consuming',
            ] + $context;

        // Assert
        $loggerMock->expects($this->once())
            ->method('log')
            ->with(Logger::INFO, '', $expectedLog);

        // Act
        $logMessageHandlingResultMiddleware->handle($envelope, $stackMock);
    }

    /**
     * @return void
     */
    public function testLogMessageHandlingResultMiddlewareWritesErrorLogInCaseOfNotSuccessfulMessageSending(): void
    {
        // Arrange
        $messageAttributesTransfer = $this->tester->getMessageAttributesTransfer([
            MessageAttributesTransfer::TRANSFER_NAME => static::TRANSFER_NAME,
            MessageAttributesTransfer::EVENT => static::TRANSFER_NAME,
            MessageAttributesTransfer::NAME => static::TRANSFER_NAME,
        ]);
        $messageBrokerTestMessageTransfer = (new MessageBrokerTestMessageTransfer())
            ->setMessageAttributes($messageAttributesTransfer);

        $loggerMock = $this->createMock(LoggerInterface::class);
        $logMessageHandlingResultMiddleware = (new LogMessageHandlingResultMiddleware($this->getMessageLoggerMock($loggerMock)));

        $middlewareMock = $this->getMiddlewareMock();
        $stackMock = $this->getStackMock($middlewareMock);

        $exceptionMessage = 'TestException';
        $middlewareMock->expects($this->once())
            ->method('handle')
            ->willThrowException(new MessageBrokerException($exceptionMessage));

        $context = $this->getMessageLogDataFromMessageAttributesTransfer(
            $messageAttributesTransfer,
            static::TRANSFER_NAME,
        );
        $expectedLog = [
                'code' => $this->tester->getModuleConfig()->getMessageBrokerCallErrorCode(),
                'codeName' => $this->tester->getModuleConfig()->getMessageBrokerCallErrorCodeName(),
                'duration' => '0s',
                'activityType' => 'Message publishing',
            ] + $context;

        // Assert
        $loggerMock->expects($this->once())
            ->method('log')
            ->with(Logger::ERROR, $exceptionMessage, $expectedLog);
        $this->expectException(MessageBrokerException::class);

        // Act
        $logMessageHandlingResultMiddleware->handle(Envelope::wrap($messageBrokerTestMessageTransfer), $stackMock);
    }

    /**
     * @return void
     */
    public function testLogMessageHandlingResultMiddlewareWritesErrorLogInCaseOfNotSuccessfulMessageReceiving(): void
    {
        // Arrange
        $messageAttributesTransfer = $this->tester->getMessageAttributesTransfer([
            MessageAttributesTransfer::TRANSFER_NAME => static::TRANSFER_NAME,
            MessageAttributesTransfer::EVENT => static::TRANSFER_NAME,
            MessageAttributesTransfer::NAME => static::TRANSFER_NAME,
        ]);
        $messageBrokerTestMessageTransfer = (new MessageBrokerTestMessageTransfer())
            ->setMessageAttributes($messageAttributesTransfer);

        $loggerMock = $this->createMock(LoggerInterface::class);
        $logMessageHandlingResultMiddleware = (new LogMessageHandlingResultMiddleware($this->getMessageLoggerMock($loggerMock)));

        $middlewareMock = $this->getMiddlewareMock();
        $stackMock = $this->getStackMock($middlewareMock);

        $exceptionMessage = 'TestException';
        $middlewareMock->expects($this->once())
            ->method('handle')
            ->willThrowException(new MessageBrokerException($exceptionMessage));

        $context = $this->getMessageLogDataFromMessageAttributesTransfer(
            $messageAttributesTransfer,
            static::TRANSFER_NAME,
        );
        $expectedLog = [
                'code' => $this->tester->getModuleConfig()->getMessageBrokerConsumeErrorCode(),
                'codeName' => $this->tester->getModuleConfig()->getMessageBrokerConsumeErrorCodeName(),
                'duration' => '0s',
                'activityType' => 'Message consuming',
            ] + $context;

        // Assert
        $loggerMock->expects($this->once())
            ->method('log')
            ->with(Logger::ERROR, $exceptionMessage, $expectedLog);
        $this->expectException(MessageBrokerException::class);

        // Act
        $logMessageHandlingResultMiddleware->handle(
            Envelope::wrap($messageBrokerTestMessageTransfer, [new ReceivedStamp('test')]),
            $stackMock,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     * @param string $transferName
     *
     * @return array
     */
    protected function getMessageLogDataFromMessageAttributesTransfer(
        MessageAttributesTransfer $messageAttributesTransfer,
        string $transferName
    ): array {
        $messageAttributesLogData = $messageAttributesTransfer->toArrayRecursiveCamelCased();
        $messageAttributesLogData['transferName'] = $transferName;
        $messageAttributesLogData['event'] = $transferName;
        $messageAttributesLogData['name'] = $transferName;

        if (isset($messageAttributesLogData[MessageAttributesTransfer::AUTHORIZATION])) {
            unset($messageAttributesLogData[MessageAttributesTransfer::AUTHORIZATION]);
        }

        return $messageAttributesLogData;
    }

    /**
     * @param \Psr\Log\LoggerInterface $loggerMock
     *
     * @return \Spryker\Zed\MessageBroker\Business\Logger\MessageLogger|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMessageLoggerMock(LoggerInterface $loggerMock): MessageLogger
    {
        $messageLoggerMock = $this->getMockBuilder(MessageLogger::class)
            ->setConstructorArgs([
                $this->tester->getFactory()->getConfig(),
            ])
            ->onlyMethods([
                'getLogger',
                'getDurationInSeconds',
            ])
            ->getMock();

        $messageLoggerMock->expects($this->once())->method('getLogger')->willReturn($loggerMock);
        $messageLoggerMock->method('getDurationInSeconds')->willReturn(0.0);

        return $messageLoggerMock;
    }

    /**
     * @return \Symfony\Component\Messenger\Middleware\MiddlewareInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMiddlewareMock(): MiddlewareInterface
    {
        return $this->createMock(MiddlewareInterface::class);
    }

    /**
     * @param \Symfony\Component\Messenger\Middleware\MiddlewareInterface $middlewareMock
     *
     * @return \Symfony\Component\Messenger\Middleware\StackInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getStackMock(MiddlewareInterface $middlewareMock): StackInterface
    {
        $stackMock = $this->createMock(StackInterface::class);
        $stackMock->expects($this->once())->method('next')->willReturn($middlewareMock);

        return $stackMock;
    }
}
