<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Sender\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Spryker\Zed\MessageBrokerAws\Business\Config\ConfigFormatterInterface;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Formatter\HttpHeaderFormatterInterface;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Stamp\SenderClientStamp;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Throwable;

class HttpSenderClient implements SenderClientInterface
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
     * @var \Spryker\Zed\MessageBrokerAws\Business\Config\ConfigFormatterInterface
     */
    protected ConfigFormatterInterface $configFormatter;

    /**
     * @var \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Formatter\HttpHeaderFormatterInterface
     */
    protected HttpHeaderFormatterInterface $httpHeaderFormatter;

    /**
     * @var array<string, mixed>|null
     */
    protected ?array $clientConfiguration = null;

    /**
     * @param \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig $config
     * @param \Symfony\Component\Messenger\Transport\Serialization\SerializerInterface $serializer
     * @param \Spryker\Zed\MessageBrokerAws\Business\Config\ConfigFormatterInterface $configFormatter
     * @param \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Formatter\HttpHeaderFormatterInterface $httpHeaderFormatter
     */
    public function __construct(
        MessageBrokerAwsConfig $config,
        SerializerInterface $serializer,
        ConfigFormatterInterface $configFormatter,
        HttpHeaderFormatterInterface $httpHeaderFormatter
    ) {
        $this->config = $config;
        $this->serializer = $serializer;
        $this->configFormatter = $configFormatter;
        $this->httpHeaderFormatter = $httpHeaderFormatter;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @throws \Symfony\Component\Messenger\Exception\TransportException
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function send(Envelope $envelope): Envelope
    {
        $configuration = $this->getConfiguration();

        if (!$configuration) {
            return $envelope;
        }

        $client = $this->createSenderClient();

        $encodedMessage = $this->serializer->encode($envelope);

        $headers = $this->httpHeaderFormatter->formatHeaders($encodedMessage['headers'] ?? []);

        $request = new Request(
            'POST',
            $configuration['endpoint'],
            ['Content-Type' => 'application/json'] + $headers,
            (string)json_encode($encodedMessage['bodyRaw']),
        );

        try {
            $client->send($request);
        } catch (Throwable $e) {
            throw new TransportException($e->getMessage(), 0, $e);
        }

        return $envelope->with(new SenderClientStamp(static::class));
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function createSenderClient(): Client
    {
        $configuration = $this->getConfiguration();

        return new Client($configuration);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConfiguration(): array
    {
        if (!$this->clientConfiguration) {
            $httpSenderConfig = $this->config->getHttpSenderConfig();

            if (is_string($httpSenderConfig)) {
                $httpSenderConfig = $this->configFormatter->format($httpSenderConfig);
            }

            $this->clientConfiguration = $httpSenderConfig;
        }

        return $this->clientConfiguration;
    }
}
