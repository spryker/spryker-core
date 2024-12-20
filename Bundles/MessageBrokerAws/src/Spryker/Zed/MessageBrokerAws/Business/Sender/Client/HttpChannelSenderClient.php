<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Sender\Client;

use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\MessageMetadataTransfer;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Spryker\Zed\MessageBrokerAws\Business\Exception\MessageValidationFailedException;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Formatter\HttpHeaderFormatterInterface;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Stamp\SenderClientStamp;
use Spryker\Zed\MessageBrokerAws\Dependency\Service\MessageBrokerAwsToUtilEncodingServiceInterface;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class HttpChannelSenderClient implements SenderClientInterface
{
    /**
     * @var \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig
     */
    protected MessageBrokerAwsConfig $config;

    /**
     * @var \Symfony\Component\Messenger\Transport\Serialization\SerializerInterface
     */
    protected SerializerInterface $serializer;

    /**
     * @var \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Formatter\HttpHeaderFormatterInterface
     */
    protected HttpHeaderFormatterInterface $httpHeaderFormatter;

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
     * @param \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Formatter\HttpHeaderFormatterInterface $httpHeaderFormatter
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @param \Spryker\Zed\MessageBrokerAws\Dependency\Service\MessageBrokerAwsToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        MessageBrokerAwsConfig $config,
        SerializerInterface $serializer,
        HttpHeaderFormatterInterface $httpHeaderFormatter,
        ClientInterface $httpClient,
        MessageBrokerAwsToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->config = $config;
        $this->serializer = $serializer;
        $this->httpHeaderFormatter = $httpHeaderFormatter;
        $this->httpClient = $httpClient;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @throws \Spryker\Zed\MessageBrokerAws\Business\Exception\MessageValidationFailedException
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function send(Envelope $envelope): Envelope
    {
        /**
         * @var \Spryker\Zed\MessageBroker\Business\Receiver\Stamp\ChannelNameStamp|null $channelNameStamp
         */
        $channelNameStamp = $envelope->last(MessageBrokerAwsConfig::HTTP_CHANNEL_STAMP_CLASS);

        if ($channelNameStamp === null) {
            throw new MessageValidationFailedException('The message channel name is missing');
        }

        $encodedMessage = $this->serializer->encode($envelope);

        /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer */
        $messageTransfer = $envelope->getMessage();
        /** @var \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer */
        $messageAttributesTransfer = $messageTransfer->getMessageAttributes();

        if (!$this->areMessageAttributesValid($messageAttributesTransfer)) {
            throw new MessageValidationFailedException(
                sprintf(
                    'Envelop: %s is invalid',
                    print_r($messageAttributesTransfer->toArray(true, true), true),
                ),
            );
        }

        $headers = $this->httpHeaderFormatter->formatHeaders($encodedMessage['headers'] ?? []);

        $response = $this->httpClient->request(
            SymfonyRequest::METHOD_POST,
            $this->config->getHttpChannelSenderBaseUrl() . $channelNameStamp->getChannelName(),
            [
                RequestOptions::HEADERS => $headers,
                RequestOptions::BODY => $encodedMessage['body'],
            ],
        );

        $messageAttributesTransfer = $this->updateMessageAttributesMetadata($messageAttributesTransfer, $response);
        $messageTransfer->setMessageAttributes($messageAttributesTransfer);

        return $envelope->with(new SenderClientStamp(static::class));
    }

    /**
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return bool
     */
    public function areMessageAttributesValid(MessageAttributesTransfer $messageAttributesTransfer): bool
    {
        return $messageAttributesTransfer->getActorId()
            && $messageAttributesTransfer->getTenantIdentifier()
            && $messageAttributesTransfer->getName();
    }

    /**
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    protected function updateMessageAttributesMetadata(
        MessageAttributesTransfer $messageAttributesTransfer,
        ResponseInterface $response
    ): MessageAttributesTransfer {
        $messageMetadataTransfer = $messageAttributesTransfer->getMetadata();

        if (!$messageMetadataTransfer) {
            $messageMetadataTransfer = new MessageMetadataTransfer();
        }

        $messageMetadataTransfer->setMessageId($this->findMessageId($response));

        return $messageAttributesTransfer->setMetadata($messageMetadataTransfer);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return string|null
     */
    protected function findMessageId(ResponseInterface $response): ?string
    {
        if (!($response->getStatusCode() >= Response::HTTP_OK && $response->getStatusCode() < Response::HTTP_MULTIPLE_CHOICES)) {
            return null;
        }

        $messageResponse = $this->utilEncodingService->decodeJson($response->getBody()->getContents(), true);

        if (!is_array($messageResponse)) {
            return null;
        }

        return $messageResponse['MessageId'] ?? null;
    }
}
