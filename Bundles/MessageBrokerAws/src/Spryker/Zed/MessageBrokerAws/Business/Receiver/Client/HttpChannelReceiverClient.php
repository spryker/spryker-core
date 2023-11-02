<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Receiver\Client;

use Generated\Shared\Transfer\HttpRequestTransfer;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\MessageBrokerAws\Business\Exception\MessageValidationFailedException;
use Spryker\Zed\MessageBrokerAws\Dependency\Service\MessageBrokerAwsToUtilEncodingServiceInterface;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class HttpChannelReceiverClient implements ReceiverClientInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig
     */
    protected MessageBrokerAwsConfig $config;

    /**
     * @var \Symfony\Component\Messenger\Transport\Serialization\SerializerInterface
     */
    protected SerializerInterface $serializer;

    /**
     * @var list<\Spryker\Zed\MessageBrokerAwsExtension\Dependency\Plugin\HttpChannelMessageReceiverRequestExpanderPluginInterface>
     */
    protected array $httpChannelMessageReceiverRequestExpanderPlugins;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected ClientInterface $httpClient;

    /**
     * @var \Spryker\Zed\MessageBrokerAws\Dependency\Service\MessageBrokerAwsToUtilEncodingServiceInterface
     */
    protected MessageBrokerAwsToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig $config
     * @param \Symfony\Component\Messenger\Transport\Serialization\SerializerInterface $serializer
     * @param list<\Spryker\Zed\MessageBrokerAwsExtension\Dependency\Plugin\HttpChannelMessageReceiverRequestExpanderPluginInterface> $httpChannelMessageReceiverRequestExpanderPlugins
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @param \Spryker\Zed\MessageBrokerAws\Dependency\Service\MessageBrokerAwsToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        MessageBrokerAwsConfig $config,
        SerializerInterface $serializer,
        array $httpChannelMessageReceiverRequestExpanderPlugins,
        ClientInterface $httpClient,
        MessageBrokerAwsToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->config = $config;
        $this->serializer = $serializer;
        $this->httpChannelMessageReceiverRequestExpanderPlugins = $httpChannelMessageReceiverRequestExpanderPlugins;
        $this->httpClient = $httpClient;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $channelName
     *
     * @return list<\Symfony\Component\Messenger\Envelope>
     */
    public function get(string $channelName): iterable
    {
        $httpRequestTransfer = new HttpRequestTransfer();
        $httpRequestTransfer->setRequestUri($this->getReceiverEndpoint($channelName));
        $httpRequestTransfer = $this->expandHttpRequestTransfer($httpRequestTransfer);

        $response = $this->sendReceiverRequest(
            Request::METHOD_GET,
            $httpRequestTransfer,
            ['limit' => $this->config->getMessageConsumeLimit()],
        );
        $messages = (array)$this->utilEncodingService->decodeJson($response->getBody()->getContents(), true);

        return $this->transformMessagesToEnvelopes($messages);
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @throws \Spryker\Zed\MessageBrokerAws\Business\Exception\MessageValidationFailedException
     *
     * @return void
     */
    public function ack(Envelope $envelope): void
    {
        $messageId = $this->getEnvelopeMessageId($envelope);
        /**
         * @var \Spryker\Zed\MessageBroker\Business\Receiver\Stamp\ChannelNameStamp|null $channelNameStamp
         */
        $channelNameStamp = $envelope->last(MessageBrokerAwsConfig::HTTP_CHANNEL_STAMP_CLASS);
        if ($channelNameStamp === null) {
            throw new MessageValidationFailedException(
                sprintf('Envelope with message ID "%s", does not have `ChannelNameStamp`.', $messageId),
            );
        }

        $httpRequestTransfer = new HttpRequestTransfer();
        $httpRequestTransfer->setRequestUri($this->getReceiverEndpoint($channelNameStamp->getChannelName()));
        $httpRequestTransfer->setBody($this->utilEncodingService->encodeJson(['messageIds' => [$messageId]]));
        $httpRequestTransfer = $this->expandHttpRequestTransfer($httpRequestTransfer);

        $this->sendReceiverRequest(Request::METHOD_DELETE, $httpRequestTransfer, []);
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function reject(Envelope $envelope): void
    {
        $this->ack($envelope);
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @throws \Spryker\Zed\MessageBrokerAws\Business\Exception\MessageValidationFailedException
     *
     * @return string
     */
    protected function getEnvelopeMessageId(Envelope $envelope): string
    {
        /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer */
        $messageTransfer = $envelope->getMessage();

        if (!($messageTransfer instanceof AbstractTransfer)) {
            throw new MessageValidationFailedException(sprintf('Could not decode message, expected type of "%s" but got "%s".', AbstractTransfer::class, gettype($messageTransfer)));
        }

        if (!method_exists($messageTransfer, 'getMessageAttributes')) {
            throw new MessageValidationFailedException(sprintf('Could not decode message, expected to have a method `getMessageAttributes()` but it was not found in "%s".', get_class($messageTransfer)));
        }

        /** @var \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer */
        $messageAttributesTransfer = $messageTransfer->getMessageAttributes();
        $messageId = $messageAttributesTransfer->getMetadataOrFail()->getMessageId();

        if (!$messageId) {
            throw new MessageValidationFailedException(sprintf('The `messageId` property is missing in "%s".', get_class($messageAttributesTransfer)));
        }

        return $messageId;
    }

    /**
     * @param string $channelName
     *
     * @return string
     */
    protected function getReceiverEndpoint(string $channelName): string
    {
        return $this->config->getHttpChannelReceiverBaseUrl() . $channelName;
    }

    /**
     * @param string $httpMethod
     * @param \Generated\Shared\Transfer\HttpRequestTransfer $httpRequestTransfer
     * @param array<string, mixed> $queryParams
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function sendReceiverRequest(
        string $httpMethod,
        HttpRequestTransfer $httpRequestTransfer,
        array $queryParams
    ): ResponseInterface {
        return $this->httpClient->request(
            $httpMethod,
            $httpRequestTransfer->getRequestUriOrFail(),
            [
                RequestOptions::QUERY => $queryParams,
                RequestOptions::HEADERS => $httpRequestTransfer->getHeaders() + ['Content-Type' => 'application/json'],
                RequestOptions::BODY => $httpRequestTransfer->getBody(),
            ],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\HttpRequestTransfer $httpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HttpRequestTransfer
     */
    protected function expandHttpRequestTransfer(HttpRequestTransfer $httpRequestTransfer): HttpRequestTransfer
    {
        foreach ($this->httpChannelMessageReceiverRequestExpanderPlugins as $expanderPlugin) {
            $httpRequestTransfer = $expanderPlugin->expand($httpRequestTransfer);
        }

        return $httpRequestTransfer;
    }

    /**
     * @param list<mixed> $messages
     *
     * @return list<\Symfony\Component\Messenger\Envelope>
     */
    protected function transformMessagesToEnvelopes(array $messages): array
    {
        $envelopes = [];
        foreach ($messages as $message) {
            try {
                $envelope = $this->serializer->decode([
                    'body' => $message['MessageBody'] ?? '',
                    'headers' => $message['MessageAttributes'] ?? '',
                    'messageId' => $message['MessageId'] ?? '',
                ]);
            } catch (MessageDecodingFailedException $e) {
                $this->getLogger()->error($e->getMessage());

                continue;
            }

            $envelopes[] = $envelope;
        }

        return $envelopes;
    }
}
