<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBrokerAws\Communication\Plugin\MessageBroker\Sender;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Spryker\Shared\MessageBroker\MessageBrokerConstants;
use Spryker\Shared\MessageBrokerAws\MessageBrokerAwsConstants;
use Spryker\Zed\MessageBroker\Business\Receiver\Stamp\ChannelNameStamp;
use Spryker\Zed\MessageBrokerAws\Business\Exception\MessageValidationFailedException;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Stamp\SenderClientStamp;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsDependencyProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Envelope;

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
 * @group HttpChannelMessageSenderPluginTest
 * Add your own group annotations below this line
 */
class HttpChannelMessageSenderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const CHANNEL_NAME = 'product-events';

    /**
     * @var string
     */
    protected const SENDER_BASE_URL = 'https://producer.atrs-testing.demo-spryker.com/';

    /**
     * @var string
     */
    protected const TENANT_IDENTIFIER = 'dev-DE';

    /**
     * @var \SprykerTest\Zed\MessageBrokerAws\MessageBrokerAwsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setConfig(MessageBrokerAwsConstants::HTTP_CHANNEL_SENDER_BASE_URL, static::SENDER_BASE_URL);
        $this->tester->setConfig(MessageBrokerConstants::TENANT_IDENTIFIER, static::TENANT_IDENTIFIER);
    }

    /**
     * @return void
     */
    public function testSendEnvelopeSuccessfullyExecutesWhenRequestIsCorrect(): void
    {
        // Arrange
        $this->tester->setMessageToChannelMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);
        $this->tester->setChannelToSenderTransportMap(static::CHANNEL_NAME, MessageBrokerAwsConfig::HTTP_CHANNEL_TRANSPORT);

        $messageAttributesTransfer = $this->tester->createMessageAttributesTransfer([
            MessageAttributesTransfer::NAME => 'MessageBrokerTestMessage',
            MessageAttributesTransfer::ACTOR_ID => 'actorId',
            MessageAttributesTransfer::TENANT_IDENTIFIER => static::TENANT_IDENTIFIER,
            MessageAttributesTransfer::METADATA => null,
            MessageAttributesTransfer::PUBLISHER => null,
            MessageAttributesTransfer::EMITTER => null,
        ]);
        $messageAttributesTransfer->setMetadata(null);
        $envelope = Envelope::wrap($this->tester->createMessageWithRequiredMessageAttributes());
        $envelope->getMessage()->setMessageAttributes($messageAttributesTransfer);
        $envelope = $envelope->with(new ChannelNameStamp(static::CHANNEL_NAME));

        $httpClientMock = $this->createMock(Client::class);
        $httpClientMock->expects($this->once())
            ->method('request')
            ->with(
                Request::METHOD_POST,
                static::SENDER_BASE_URL . static::CHANNEL_NAME,
                [
                    RequestOptions::HEADERS => [
                        'Store-Reference' => $messageAttributesTransfer->getStoreReference(),
                        'Transfer-Name' => $messageAttributesTransfer->getTransferName(),
                        'Name' => $messageAttributesTransfer->getName(),
                        'Metadata' => $messageAttributesTransfer->getMetadata(),
                        'Actor-Id' => $messageAttributesTransfer->getActorId(),
                        'Tenant-Identifier' => $messageAttributesTransfer->getTenantIdentifier(),
                        'Publisher' => $messageAttributesTransfer->getPublisher(),
                        'Emitter' => $messageAttributesTransfer->getPublisher(),
                    ],
                    RequestOptions::BODY => json_encode(['key' => 'value']),
                ],
            );
        $this->tester->setDependency(
            MessageBrokerAwsDependencyProvider::CLIENT_HTTP,
            $httpClientMock,
        );

        // Act
        $expectedEnvelope = $this->tester->getFacade()->sendMessageToHttpChannel($envelope);

        // Assert
        $this->assertInstanceOf(Envelope::class, $expectedEnvelope);
        $this->assertInstanceOf(
            SenderClientStamp::class,
            $expectedEnvelope->last(SenderClientStamp::class),
        );
        $this->assertSame(
            $messageAttributesTransfer->getActorId(),
            $expectedEnvelope->getMessage()->getMessageAttributes()->getActorID(),
        );
    }

    /**
     * @return void
     */
    public function testSendEnvelopeThrowsExceptionWhenEnvelopeIsInvalid(): void
    {
        // Arrange
        $this->tester->setMessageToChannelMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);
        $this->tester->setChannelToSenderTransportMap(static::CHANNEL_NAME, MessageBrokerAwsConfig::HTTP_CHANNEL_TRANSPORT);

        $messageAttributesTransfer = $this->tester->createMessageAttributesTransfer([
            MessageAttributesTransfer::NAME => null,
            MessageAttributesTransfer::ACTOR_ID => null,
        ]);
        $envelope = Envelope::wrap(
            $this->tester->createMessageBrokerTestMessageTransfer([
                MessageBrokerTestMessageTransfer::MESSAGE_ATTRIBUTES => $messageAttributesTransfer,
            ]),
        );
        $envelope = Envelope::wrap($this->tester->createMessageWithRequiredMessageAttributes());
        $envelope = $envelope->with(new ChannelNameStamp(static::CHANNEL_NAME));

        // Assert
        $this->expectException(MessageValidationFailedException::class);

        // Act
        $this->tester->getFacade()->sendMessageToHttpChannel($envelope);
    }
}
