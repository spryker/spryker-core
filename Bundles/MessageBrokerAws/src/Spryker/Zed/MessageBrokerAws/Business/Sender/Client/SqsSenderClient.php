<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Sender\Client;

use AsyncAws\Sqs\SqsClient;
use Spryker\Zed\MessageBrokerAws\Business\Config\ConfigFormatterInterface;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Stamp\SenderClientStamp;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;
use Symfony\Component\Messenger\Bridge\AmazonSqs\Transport\AmazonSqsSender;
use Symfony\Component\Messenger\Bridge\AmazonSqs\Transport\Connection;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\HttpChannelSenderClient} instead.
 */
class SqsSenderClient implements SenderClientInterface
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
     * @var array<string, mixed>|null
     */
    protected ?array $sqsConfiguration = null;

    /**
     * @param \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig $config
     * @param \Symfony\Component\Messenger\Transport\Serialization\SerializerInterface $serializer
     * @param \Spryker\Zed\MessageBrokerAws\Business\Config\ConfigFormatterInterface $configFormatter
     */
    public function __construct(MessageBrokerAwsConfig $config, SerializerInterface $serializer, ConfigFormatterInterface $configFormatter)
    {
        $this->config = $config;
        $this->serializer = $serializer;
        $this->configFormatter = $configFormatter;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function send(Envelope $envelope): Envelope
    {
        return $this->createSenderClient()->send($envelope)->with(new SenderClientStamp(static::class));
    }

    /**
     * @return \Symfony\Component\Messenger\Bridge\AmazonSqs\Transport\AmazonSqsSender
     */
    protected function createSenderClient(): AmazonSqsSender
    {
        $configuration = $this->getConfiguration();
        $connection = new Connection($configuration, $this->createSqsClient());

        return new AmazonSqsSender($connection, $this->serializer);
    }

    /**
     * @return \AsyncAws\Sqs\SqsClient
     */
    protected function createSqsClient(): SqsClient
    {
        $configuration = $this->getConfiguration();
        $options = [
            'endpoint' => null,
            'accessKeyId' => null,
            'accessKeySecret' => null,
            'region' => null,
        ];

        return new SqsClient(array_intersect_key($configuration, $options));
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConfiguration(): array
    {
        if (!$this->sqsConfiguration) {
            $sqsReceiverConfig = $this->config->getSqsSenderConfig();

            if (is_string($sqsReceiverConfig)) {
                $sqsReceiverConfig = $this->configFormatter->format($sqsReceiverConfig);
            }

            $sqsReceiverConfig['debug'] = $this->config->isDebugEnabled();

            $this->sqsConfiguration = $sqsReceiverConfig;
        }

        return $this->sqsConfiguration;
    }
}
