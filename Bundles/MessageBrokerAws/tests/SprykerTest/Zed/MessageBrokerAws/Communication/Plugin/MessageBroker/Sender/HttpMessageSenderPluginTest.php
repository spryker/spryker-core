<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBrokerAws\Communication\Plugin\MessageBroker\Sender;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\HttpSenderClient;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Stamp\SenderClientStamp;
use Spryker\Zed\MessageBrokerAws\Communication\Plugin\MessageBroker\Sender\HttpMessageSenderPlugin;
use SprykerTest\Zed\MessageBrokerAws\MessageBrokerAwsCommunicationTester;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Symfony\Component\Process\Process;

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
 * @group HttpMessageSenderPluginTest
 * Add your own group annotations below this line
 */
class HttpMessageSenderPluginTest extends Unit
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
    public function testSendUsesHttpSenderWhenHttpSenderIsConfiguredForChannel(): void
    {
        $pathToServer = __DIR__ . '../../../../_support/Server/200_OK.php';
        $process = new Process(['php', '-S', '0.0.0.0:8000', $pathToServer], null, null, null, 3);
        $process->start();
        $process->waitUntil(function ($type, $buffer) {
            return strpos($buffer, 'Development Server (http://0.0.0.0:8000) started') !== false;
        });

        // Arrange
        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();
        $messageBrokerTestMessageTransfer->setKey('value');

        $messageAttributesTransfer = new MessageAttributesTransfer();
        $messageAttributesTransfer->setTransferName('MessageBrokerTestMessage');
        $messageAttributesTransfer->setCorrelationId(Uuid::uuid4()->toString());
        $messageAttributesTransfer->setTimestamp(microtime());

        $messageBrokerTestMessageTransfer->setMessageAttributes($messageAttributesTransfer);

        $this->tester->setMessageToChannelMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);
        $this->tester->setChannelToSenderTransportMap(static::CHANNEL_NAME, 'http');
        $this->tester->setHttpSenderConfiguration();

        // Act
        $httpMessageSenderPlugin = new HttpMessageSenderPlugin();
        $httpMessageSenderPlugin->setFacade($this->tester->getFacade());
        $envelope = $httpMessageSenderPlugin->send(Envelope::wrap($messageBrokerTestMessageTransfer));

        // Assert
        /** @var \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Stamp\SenderClientStamp $senderClientStamp */
        $senderClientStamp = $envelope->last(SenderClientStamp::class);

        $this->assertSame(
            HttpSenderClient::class,
            $senderClientStamp->getSenderClientName(),
            sprintf(
                'Expected not to have the message sent with the "%s" client but it was sent with "%s".',
                HttpSenderClient::class,
                $senderClientStamp->getSenderClientName(),
            ),
        );

        $process->stop(60, SIGINT);
    }

    /**
     * The message will have a SentStamp only when handled properly.
     *
     * @return void
     */
    public function testSendReturnsUnHandledEnvelopeWhenSentStampDoesNotExist(): void
    {
        // Arrange
        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();
        $messageBrokerTestMessageTransfer->setKey('value');

        $envelope = Envelope::wrap($messageBrokerTestMessageTransfer);

        // Act
        $httpMessageSenderPlugin = new HttpMessageSenderPlugin();
        $httpMessageSenderPlugin->setFacade($this->tester->getFacade());
        $envelope = $httpMessageSenderPlugin->send($envelope);

        // Assert
        /** @var \Symfony\Component\Messenger\Stamp\SentStamp $sentStamp */
        $sentStamp = $envelope->last(SentStamp::class);

        $this->assertNull($sentStamp, sprintf('Expected not to have a "%s" but it is given.', SentStamp::class));
    }

    /**
     * Exception will be thrown because the HttpClient configuration is empty.
     *
     * @return void
     */
    public function testSendWithHttpSenderThrowsExceptionWhenMessageCanNotBeTransported(): void
    {
        // Arrange
        $messageBrokerTestMessageTransfer = $this->tester->createMessageWithRequiredMessageAttributes();

        $envelope = Envelope::wrap($messageBrokerTestMessageTransfer);

        $this->tester->setMessageToChannelMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);
        $this->tester->setChannelToSenderTransportMap(static::CHANNEL_NAME, 'http');

        // Expect
        $this->expectException(TransportException::class);

        // Act
        $httpMessageSenderPlugin = new HttpMessageSenderPlugin();
        $httpMessageSenderPlugin->setFacade($this->tester->getFacade());
        $httpMessageSenderPlugin->send($envelope);
    }

    /**
     * @return void
     */
    public function testGetClientNameReturnNameOfTheSupportedClient(): void
    {
        // Arrange
        $httpMessageSenderPlugin = new HttpMessageSenderPlugin();

        // Act
        $clientName = $httpMessageSenderPlugin->getTransportName();

        // Assert
        $this->assertSame('http', $clientName, sprintf('Expected to get "http" as client name but got "%s"', $clientName));
    }
}
