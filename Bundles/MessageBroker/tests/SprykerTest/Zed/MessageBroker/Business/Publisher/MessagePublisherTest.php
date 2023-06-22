<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Business\Publisher;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;
use Generated\Shared\Transfer\OutgoingMessageTransfer;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Spryker\Zed\MessageBroker\Business\Exception\MessageBrokerException;
use Spryker\Zed\MessageBroker\Business\Logger\MessagePublishLogger;
use Spryker\Zed\MessageBroker\Business\Publisher\MessagePublisher;
use Spryker\Zed\MessageBroker\Business\Publisher\MessagePublisherInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBroker
 * @group Business
 * @group Publisher
 * @group MessagePublisherTest
 * Add your own group annotations below this line
 */
class MessagePublisherTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MessageBroker\MessageBrokerBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPublishMessageThrowsExceptionWhenPassedTransferDoesNotHaveMessageAttributes(): void
    {
        // Arrange
        $this->tester->enableMessageBroker();

        $messageBrokerWorkerConfigTransfer = $this->tester->buildMessageBrokerWorkerConfigTransfer();
        $messagePublisher = $this->tester->getFactory()->createMessagePublisher();

        // Expect
        $this->expectException(MessageBrokerException::class);

        // Act
        $messagePublisher->sendMessage($messageBrokerWorkerConfigTransfer);
    }

    /**
     * @return void
     */
    public function testPublishMessageWritesSuccessLogInCaseOfSuccessfullMessageSending(): void
    {
        // Arrange
        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();
        $messageAttributesTransfer = $this->tester->getMessageAttributesTransfer();
        $messageBrokerTestMessageTransfer->setMessageAttributes($messageAttributesTransfer);

        $loggerMock = $this->createMock(LoggerInterface::class);
        $messageBusMock = $this->createMock(MessageBus::class);

        $envelope = Envelope::wrap($messageBrokerTestMessageTransfer, []);
        $messageBusMock->method('dispatch')->willReturn($envelope);
        $messagePublisher = $this->getMessagePublisherMock($loggerMock, $messageBusMock);

        $context = $this->getMessageLogDataFromMessageAttributesTransfer(
            $messageAttributesTransfer,
            'MessageBrokerTestMessage',
        );
        $expectedLog = [
                'code' => 11000,
                'codeName' => 'Message Broker call sendMessage Success',
                'duration' => 0,
                'activityType' => 'Message publishing',
            ] + $context;

        // Assert
        $loggerMock->expects($this->once())
            ->method('log')
            ->with(Logger::INFO, '', $expectedLog);

        // Act
        $messagePublisher->sendMessage($messageBrokerTestMessageTransfer);
    }

    /**
     * @return void
     */
    public function testPublishMessageWritesErrorLogInCaseOfNotSuccessfullMessageSending(): void
    {
        // Arrange
        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();
        $messageAttributesTransfer = $this->tester->getMessageAttributesTransfer();
        $messageBrokerTestMessageTransfer->setMessageAttributes($messageAttributesTransfer);

        $loggerMock = $this->createMock(LoggerInterface::class);
        $messageBusMock = $this->createMock(MessageBus::class);
        $messageBusMock->method('dispatch')->willThrowException(new MessageBrokerException('TestException'));
        $messagePublisher = $this->getMessagePublisherMock($loggerMock, $messageBusMock);

        $context = $this->getMessageLogDataFromMessageAttributesTransfer(
            $messageAttributesTransfer,
            'MessageBrokerTestMessage',
        );
        $expectedLog = [
                'code' => 11001,
                'codeName' => 'Message Broker call sendMessage Error',
                'duration' => 0,
                'activityType' => 'Message publishing',
            ] + $context;

        // Assert
        $loggerMock->expects($this->once())
            ->method('log')
            ->with(Logger::ERROR, 'TestException', $expectedLog);
        $this->expectException(MessageBrokerException::class);

        // Act
        $messagePublisher->sendMessage($messageBrokerTestMessageTransfer);
    }

    /**
     * @return void
     */
    public function testPublishMessageWritesErrorLogInCaseOfMessageAttributesMethodDoesntExist(): void
    {
        // Arrange
        $outgoingMessageTransfer = new OutgoingMessageTransfer();

        $loggerMock = $this->createMock(LoggerInterface::class);
        $messageBusMock = $this->createMock(MessageBus::class);
        $messagePublisher = $this->getMessagePublisherMock($loggerMock, $messageBusMock);

        $expectedLog = [
            'code' => 11001,
            'codeName' => 'Message Broker call sendMessage Error',
            'duration' => 0,
            'activityType' => 'Message publishing',
        ];

        // Assert
        $errorText = sprintf(
            'The passed "%s" transfer object must have an attribute "messageAttributes" but it was not found. Please add "<property name=\"messageAttributes\" type=\"MessageAttributes\"/>" to your transfer definition.',
            get_class($outgoingMessageTransfer),
        );
        $loggerMock->expects($this->once())
            ->method('log')
            ->with(Logger::ERROR, $errorText, $expectedLog);
        $this->expectException(MessageBrokerException::class);

        // Act
        $messagePublisher->sendMessage($outgoingMessageTransfer);
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

        return $messageAttributesLogData;
    }

    /**
     * @param \Psr\Log\LoggerInterface $loggerMock
     * @param \Symfony\Component\Messenger\MessageBusInterface $messageBusMock
     *
     * @return \Spryker\Zed\MessageBroker\Business\Publisher\MessagePublisherInterface
     */
    protected function getMessagePublisherMock(
        LoggerInterface $loggerMock,
        MessageBusInterface $messageBusMock
    ): MessagePublisherInterface {
        $messagePublishLoggerMock = $this->getMockBuilder(MessagePublishLogger::class)
            ->setConstructorArgs([
                $this->tester->getFactory()->getConfig(),
            ])
            ->setMethods([
                'getLogger',
                'getDuration',
            ])
            ->getMock();

        $messagePublishLoggerMock->method('getLogger')->willReturn($loggerMock);
        $messagePublishLoggerMock->method('getDuration')->willReturn(0);

        $messagePublisher = $this->createTestProxy(MessagePublisher::class, [
            $this->tester->getFactory()->createMessageDecorator(),
            $messageBusMock,
            $messagePublishLoggerMock,
            $this->tester->getModuleConfig(),
        ]);

        return $messagePublisher;
    }
}
