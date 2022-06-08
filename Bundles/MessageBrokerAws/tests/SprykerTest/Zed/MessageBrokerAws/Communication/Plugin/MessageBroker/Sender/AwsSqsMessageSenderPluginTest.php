<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBrokerAws\Communication\Plugin\MessageBroker\Sender;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\SqsSenderClient;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Stamp\SenderClientStamp;
use Spryker\Zed\MessageBrokerAws\Communication\Plugin\MessageBroker\Sender\AwsSqsMessageSenderPlugin;
use SprykerTest\Zed\MessageBrokerAws\MessageBrokerAwsCommunicationTester;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Stamp\SentStamp;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBrokerAws
 * @group Communication
 * @group Plugin
 * @group MessageBroker
 * @group Sender
 * @group AwsSqsMessageSenderPluginTest
 * Add your own group annotations below this line
 */
class AwsSqsMessageSenderPluginTest extends Unit
{
    /**
     * @var string
     */
    public const CHANNEL_NAME = 'channel';

    /**
     * @var \SprykerTest\Zed\MessageBrokerAws\MessageBrokerAwsCommunicationTester
     */
    protected MessageBrokerAwsCommunicationTester $tester;

    /**
     * @return void
     */
    public function testSendUsesSqsSenderWhenSqsSenderIsConfiguredForChannel(): void
    {
        // Arrange
        $messageBrokerTestMessageTransfer = $this->tester->createMessageWithRequiredMessageAttributes();
        $envelope = Envelope::wrap($messageBrokerTestMessageTransfer);

        $this->tester->setMessageToChannelMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);
        $this->tester->setChannelToSenderTransportMap(static::CHANNEL_NAME, 'sqs');
        $this->tester->setSqsSenderConfiguration();

        $sqsSenderClient = $this->tester->mockSuccessfulSqsClientSendResponse();

        // Act
        $awsSqsMessageSenderPlugin = new AwsSqsMessageSenderPlugin();
        $awsSqsMessageSenderPlugin->setFacade($this->tester->getFacade());
        $envelope = $awsSqsMessageSenderPlugin->send($envelope);

        // Assert
        /** @var \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Stamp\SenderClientStamp $senderClientStamp */
        $senderClientStamp = $envelope->last(SenderClientStamp::class);

        $this->assertSame(
            get_class($sqsSenderClient),
            $senderClientStamp->getSenderClientName(),
            sprintf(
                'Expected not to have the message sent with the "%s" client but it was sent with "%s".',
                SqsSenderClient::class,
                $senderClientStamp->getSenderClientName(),
            ),
        );
    }

    /**
     * The message will have a SentStamp only when handled properly.
     *
     * @return void
     */
    public function testSendReturnsUnHandledEnvelopeWhenSentStampDoesNotExist(): void
    {
        // Arrange
        $messageBrokerTestMessageTransfer = $this->tester->createMessageWithRequiredMessageAttributes();

        $envelope = Envelope::wrap($messageBrokerTestMessageTransfer);

        // Act
        $awsSqsMessageSenderPlugin = new AwsSqsMessageSenderPlugin();
        $awsSqsMessageSenderPlugin->setFacade($this->tester->getFacade());
        $envelope = $awsSqsMessageSenderPlugin->send($envelope);

        // Assert
        /** @var \Symfony\Component\Messenger\Stamp\SentStamp $sentStamp */
        $sentStamp = $envelope->last(SentStamp::class);

        $this->assertNull($sentStamp, sprintf('Expected not to have a "%s" but it is given.', SentStamp::class));
    }

    /**
     * Exception will be thrown because the SqsClient configuration is empty. Thus publish throws an exception.
     *
     * @return void
     */
    public function testSendWithSqsSenderThrowsExceptionWhenPublishThrowsAnException(): void
    {
        // Arrange
        $messageBrokerTestMessageTransfer = $this->tester->createMessageWithRequiredMessageAttributes();

        $envelope = Envelope::wrap($messageBrokerTestMessageTransfer);

        $this->tester->setMessageToChannelMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);
        $this->tester->setChannelToSenderTransportMap(static::CHANNEL_NAME, 'sqs');
        $this->tester->setInvalidSqsSenderConfiguration();

        // Expect
        $this->expectException(TransportException::class);

        // Act
        $awsSqsMessageSenderPlugin = new AwsSqsMessageSenderPlugin();
        $awsSqsMessageSenderPlugin->setFacade($this->tester->getFacade());
        $awsSqsMessageSenderPlugin->send($envelope);
    }

    /**
     * @return void
     */
    public function testGetClientNameReturnNameOfTheSupportedClient(): void
    {
        // Arrange
        $awsSqsMessageSenderPlugin = new AwsSqsMessageSenderPlugin();

        // Act
        $clientName = $awsSqsMessageSenderPlugin->getTransportName();

        // Assert
        $this->assertSame('sqs', $clientName, sprintf('Expected to get "sqs" as client name but got "%s"', $clientName));
    }
}
