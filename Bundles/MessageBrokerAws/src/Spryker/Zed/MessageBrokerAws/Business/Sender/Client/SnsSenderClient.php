<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Sender\Client;

use AsyncAws\Core\Configuration;
use AsyncAws\Sns\SnsClient;
use AsyncAws\Sns\SnsClient as AsyncAwsSnsClient;
use AsyncAws\Sns\ValueObject\MessageAttributeValue;
use Spryker\Zed\MessageBrokerAws\Business\Config\ConfigFormatterInterface;
use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Stamp\SenderClientStamp;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Throwable;

class SnsSenderClient implements SenderClientInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_ATTRIBUTE_NAME = 'X-Symfony-Messenger';

    /**
     * @var string
     */
    protected const TOPIC_FIFO_CRITERIA = '.fifo';

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
     * @throws \Symfony\Component\Messenger\Exception\TransportException
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function send(Envelope $envelope): Envelope
    {
        $snsConfiguration = $this->getConfiguration();
        $topic = $snsConfiguration['topic'] ?? '';
        unset($snsConfiguration['topic']);

        $snsClient = $this->createSenderClient($snsConfiguration);

        $encodedMessage = $this->serializer->encode($envelope);
        $headers = $encodedMessage['headers'] ?? [];
        $arguments = [
            'Message' => $encodedMessage['body'],
            'TopicArn' => $topic,
            'MessageGroupId' => 'default',
        ];

        $arguments = $this->extendArgumentsWithMessageDeduplicationId($arguments, $topic);

        $specialHeaders = [];
        foreach ($headers as $name => $value) {
            if ($name[0] === '.' || $name === static::MESSAGE_ATTRIBUTE_NAME || strlen($name) > 256 || substr($name, -1) === '.' || substr($name, 0, strlen('AWS.')) === 'AWS.' || substr($name, 0, strlen('Amazon.')) === 'Amazon.' || preg_match('/([^a-zA-Z0-9_\.-]+|\.\.)/', $name)) {
                // @codeCoverageIgnoreStart
                $specialHeaders[$name] = $value;

                continue;
                // @codeCoverageIgnoreEnd
            }

            if ($this->isValueJson($value)) {
                $arguments['MessageAttributes'][$name] = new MessageAttributeValue([
                    'DataType' => 'String.Array',
                    'StringValue' => $value,
                ]);

                continue;
            }

            $arguments['MessageAttributes'][$name] = new MessageAttributeValue([
                'DataType' => 'String',
                'StringValue' => $value,
            ]);
        }

        if ($specialHeaders) {
            // @codeCoverageIgnoreStart
            $arguments['MessageAttributes'][static::MESSAGE_ATTRIBUTE_NAME] = new MessageAttributeValue([
                'DataType' => 'String',
                'StringValue' => (string)json_encode($specialHeaders),
            ]);
            // @codeCoverageIgnoreEnd
        }

        try {
            $result = $snsClient->publish($arguments);
            $messageId = $result->getMessageId();
        } catch (Throwable $e) {
            throw new TransportException($e->getMessage(), 0, $e);
        }

        if ($messageId === null) {
            throw new TransportException('Could not add a message to the SNS topic');
        }

        return $envelope->with(new SenderClientStamp(static::class));
    }

    /**
     * @param array<string, mixed> $configuration
     *
     * @return \AsyncAws\Sns\SnsClient
     */
    protected function createSenderClient(array $configuration): SnsClient
    {
        return new AsyncAwsSnsClient($configuration);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConfiguration(): array
    {
        $snsSenderConfig = $this->config->getSnsSenderConfig();

        if (is_string($snsSenderConfig)) {
            $snsSenderConfig = $this->configFormatter->format($snsSenderConfig);
        }

        $snsSenderConfig[Configuration::OPTION_DEBUG] = $this->config->isDebugEnabled();

        return $snsSenderConfig;
    }

    /**
     * @param array<mixed> $arguments
     * @param string $topic
     *
     * @return array<mixed>
     */
    protected function extendArgumentsWithMessageDeduplicationId(array $arguments, string $topic): array
    {
        if (mb_strpos($topic, static::TOPIC_FIFO_CRITERIA) !== false) {
            $arguments['MessageDeduplicationId'] = str_replace([' ', '.'], '', microtime());
        }

        return $arguments;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    protected function isValueJson(string $value): bool
    {
        return is_string($value) && is_array(json_decode($value, true)) ? true : false;
    }
}
