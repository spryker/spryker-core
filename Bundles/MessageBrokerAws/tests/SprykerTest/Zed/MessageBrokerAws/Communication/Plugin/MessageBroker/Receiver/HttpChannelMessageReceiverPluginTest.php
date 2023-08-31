<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBrokerAws\Communication\Plugin\MessageBroker\Receiver;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;
use Generated\Shared\Transfer\MessageMetadataTransfer;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\MessageBrokerAws\MessageBrokerAwsConstants;
use Spryker\Zed\MessageBroker\Business\Receiver\Stamp\ChannelNameStamp;
use Spryker\Zed\MessageBrokerAws\Business\Exception\MessageValidationFailedException;
use Spryker\Zed\MessageBrokerAws\Communication\Plugin\MessageBroker\Receiver\HttpChannelMessageReceiverPlugin;
use Spryker\Zed\MessageBrokerAws\Communication\Plugin\MessageBrokerAws\Expander\ConsumerIdHttpChannelMessageReceiverRequestExpanderPlugin;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsDependencyProvider;
use SprykerTest\Zed\MessageBrokerAws\MessageBrokerAwsBusinessTester;
use stdClass;
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
 * @group Receiver
 * @group HttpChannelMessageReceiverPluginTest
 * Add your own group annotations below this line
 */
class HttpChannelMessageReceiverPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const CHANNEL_NAME = 'payment-events';

    /**
     * @var string
     */
    protected const RECEIVER_BASE_URL = 'https://consumer.atrs-testing.demo-spryker.com/';

    /**
     * @var string
     */
    protected const CONSUMER_ID = 'dev-DE';

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

        $this->tester->setConfig(MessageBrokerAwsConstants::HTTP_CHANNEL_RECEIVER_BASE_URL, static::RECEIVER_BASE_URL);
        $this->tester->setConfig(MessageBrokerAwsConstants::CONSUMER_ID, static::CONSUMER_ID);
    }

    /**
     * @return void
     */
    public function testGetHttpChannelMessageEndsSuccessfullyExecutesWhenRequestIsCorrect(): void
    {
        // Arrange
        $this->tester->setDependency(
            MessageBrokerAwsDependencyProvider::PLUGINS_HTTP_CHANNEL_MESSAGE_RECEIVER_REQUEST_EXPANDER,
            [
                new ConsumerIdHttpChannelMessageReceiverRequestExpanderPlugin(),
            ],
        );

        $responseMock = $this->createMock(ResponseInterface::class);
        $streamMock = $this->createMock(StreamInterface::class);
        $streamMock->method('getContents')->willReturn(
            json_encode([[
                'MessageBody' => json_encode(['body']),
                'MessageAttributes' => [
                    'tenantIdentifier' => static::CONSUMER_ID,
                    'name' => 'MessageBrokerTestMessage',
                    'publisher' => 'publisher',
                ],
                'MessageId' => 'MessageId',
            ]]),
        );
        $responseMock->method('getBody')->willReturn($streamMock);

        $httpClientMock = $this->createMock(Client::class);
        $httpClientMock->expects($this->once())
            ->method('request')
            ->with(
                Request::METHOD_GET,
                static::RECEIVER_BASE_URL . static::CHANNEL_NAME,
                [
                    RequestOptions::QUERY => ['limit' => 100],
                    RequestOptions::HEADERS => [
                        'Consumer-Id' => static::CONSUMER_ID,
                        'Content-Type' => 'application/json',
                    ],
                    RequestOptions::BODY => null,
                ],
            )->willReturn($responseMock);

        $this->tester->setDependency(
            MessageBrokerAwsDependencyProvider::CLIENT_HTTP,
            $httpClientMock,
        );

        // Act
        $httpChannelMessageReceiverPlugin = new HttpChannelMessageReceiverPlugin();
        $httpChannelMessageReceiverPlugin->setFacade($this->tester->getFacade());
        $envelopes = $httpChannelMessageReceiverPlugin->getFromQueues([static::CHANNEL_NAME]);

        // Assert
        foreach ($envelopes as $envelope) {
            $this->assertInstanceOf(Envelope::class, $envelope);
            $messageAttributesTransfer = $envelope->getMessage()->getMessageAttributes();
            $this->assertSame($messageAttributesTransfer->getMetadata()->getMessageId(), 'MessageId');
            $this->assertSame($messageAttributesTransfer->getStoreReference(), static::CONSUMER_ID);
            $this->assertSame($messageAttributesTransfer->getTenantIdentifier(), static::CONSUMER_ID);
        }
    }

    /**
     * @return void
     */
    public function testRemoveHttpChannelMessageSuccessfullyExecutesWhenRequestIsCorrect(): void
    {
        // Arrange
        $this->tester->setConfig(MessageBrokerAwsConstants::CHANNEL_TO_RECEIVER_TRANSPORT_MAP, [
            static::CHANNEL_NAME => MessageBrokerAwsConfig::HTTP_CHANNEL_TRANSPORT,
        ]);

        $this->tester->setDependency(
            MessageBrokerAwsDependencyProvider::PLUGINS_HTTP_CHANNEL_MESSAGE_RECEIVER_REQUEST_EXPANDER,
            [
                new ConsumerIdHttpChannelMessageReceiverRequestExpanderPlugin(),
            ],
        );

        $messageAttributesTransfer = $this->tester->createMessageAttributesTransfer([
            MessageAttributesTransfer::NAME => 'MessageBrokerTestMessage',
            MessageAttributesTransfer::EMITTER => MessageBrokerAwsBusinessTester::PUBLISHER,
        ]);
        $envelope = Envelope::wrap(
            $this->tester->createMessageBrokerTestMessageTransfer([
                MessageBrokerTestMessageTransfer::MESSAGE_ATTRIBUTES => $messageAttributesTransfer,
            ]),
        );
        $envelope = $envelope->with(new ChannelNameStamp(static::CHANNEL_NAME));

        // Assert
        $httpClientMock = $this->createMock(Client::class);
        $httpClientMock->expects($this->exactly(2))
            ->method('request')
            ->with(
                Request::METHOD_DELETE,
                static::RECEIVER_BASE_URL . static::CHANNEL_NAME,
                [
                    RequestOptions::QUERY => [],
                    RequestOptions::HEADERS => [
                        'Consumer-Id' => static::CONSUMER_ID,
                        'Content-Type' => 'application/json',
                    ],
                    RequestOptions::BODY => json_encode([
                        'messageIds' => [$messageAttributesTransfer->getMetadata()->getMessageId()],
                    ]),
                ],
            );

        $this->tester->setDependency(
            MessageBrokerAwsDependencyProvider::CLIENT_HTTP,
            $httpClientMock,
        );

        // Act
        $httpChannelMessageReceiverPlugin = new HttpChannelMessageReceiverPlugin();
        $httpChannelMessageReceiverPlugin->setFacade($this->tester->getFacade());
        $httpChannelMessageReceiverPlugin->ack($envelope);
        $httpChannelMessageReceiverPlugin->reject($envelope);
    }

    /**
     * @dataProvider brokenEnvelopeTestDataProvider
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function testRemoveHttpChannelMessageThrowsExceptionWhenEnvelopeIsInvalid(Envelope $envelope): void
    {
        // Arrange
        $httpChannelMessageReceiverPlugin = new HttpChannelMessageReceiverPlugin();

        // Assert
        $this->expectException(MessageValidationFailedException::class);

        // Act
        $httpChannelMessageReceiverPlugin->ack($envelope);
    }

    /**
     * @return array
     */
    public function brokenEnvelopeTestDataProvider(): array
    {
        $wrongTypeEnvelope = Envelope::wrap(new stdClass());
        $emptyMessageAttributesEnvelope = Envelope::wrap($this->createMock(AbstractTransfer::class));

        return [
            [$wrongTypeEnvelope],
            [$emptyMessageAttributesEnvelope],
        ];
    }

    /**
     * @return void
     */
    public function testRemoveHttpChannelMessageThrowsExceptionWhenEnvelopeHasEmptyMessageId(): void
    {
        // Arrange
        $messageAttributesTransfer = $this->tester->createMessageAttributesTransfer([
            MessageMetadataTransfer::MESSAGE_ID => null,
        ]);
        $envelope = Envelope::wrap(
            $this->tester->createMessageBrokerTestMessageTransfer([
                MessageBrokerTestMessageTransfer::MESSAGE_ATTRIBUTES => $messageAttributesTransfer,
            ]),
        );
        $httpChannelMessageReceiverPlugin = new HttpChannelMessageReceiverPlugin();

        // Assert
        $this->expectException(MessageValidationFailedException::class);

        // Act
        $httpChannelMessageReceiverPlugin->ack($envelope);
    }
}
