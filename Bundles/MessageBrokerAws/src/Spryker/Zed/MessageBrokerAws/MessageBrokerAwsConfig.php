<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws;

use Generated\Shared\Transfer\MessageDataFilterConfigurationTransfer;
use Generated\Shared\Transfer\MessageDataFilterItemConfigurationTransfer;
use Spryker\Shared\MessageBrokerAws\MessageBrokerAwsConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class MessageBrokerAwsConfig extends AbstractBundleConfig
{
    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const SQS_TRANSPORT = 'sqs';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const SNS_TRANSPORT = 'sns';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const HTTP_TRANSPORT = 'http';

    /**
     * @var string
     */
    public const HTTP_CHANNEL_TRANSPORT = 'http-channel';

    /**
     * @uses \Spryker\Zed\MessageBroker\Business\Receiver\Stamp\ChannelNameStamp::class
     *
     * @var string
     */
    public const HTTP_CHANNEL_STAMP_CLASS = 'Spryker\Zed\MessageBroker\Business\Receiver\Stamp\ChannelNameStamp';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    protected const SQS_AWS_API_VERSION = '2012-11-05';

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return array<string, mixed>|string
     */
    public function getSnsSenderConfig()
    {
        if (getenv('SPRYKER_MESSAGE_BROKER_SNS_SENDER_CONFIG') !== false) {
            return getenv('SPRYKER_MESSAGE_BROKER_SNS_SENDER_CONFIG');
        }

        // @codeCoverageIgnoreStart
        if ($this->getConfig()->hasKey(MessageBrokerAwsConstants::SNS_SENDER_CONFIG)) {
            return $this->get(MessageBrokerAwsConstants::SNS_SENDER_CONFIG);
        }

        return [];
        // @codeCoverageIgnoreEnd
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return array<string, mixed>|string
     */
    public function getSqsSenderConfig()
    {
        if (getenv('SPRYKER_MESSAGE_BROKER_SQS_SENDER_CONFIG') !== false) {
            return getenv('SPRYKER_MESSAGE_BROKER_SQS_SENDER_CONFIG');
        }

        // @codeCoverageIgnoreStart
        if ($this->getConfig()->hasKey(MessageBrokerAwsConstants::SQS_SENDER_CONFIG)) {
            return $this->get(MessageBrokerAwsConstants::SQS_SENDER_CONFIG);
        }

        return [];
        // @codeCoverageIgnoreEnd
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return array<string, mixed>|string
     */
    public function getHttpSenderConfig()
    {
        if (getenv('SPRYKER_MESSAGE_BROKER_HTTP_SENDER_CONFIG') !== false) {
            return getenv('SPRYKER_MESSAGE_BROKER_HTTP_SENDER_CONFIG');
        }

        // @codeCoverageIgnoreStart
        if ($this->getConfig()->hasKey(MessageBrokerAwsConstants::HTTP_SENDER_CONFIG)) {
            return $this->get(MessageBrokerAwsConstants::HTTP_SENDER_CONFIG);
        }

        return [];
        // @codeCoverageIgnoreEnd
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return array<string, mixed>|string
     */
    public function getSqsReceiverConfig()
    {
        if (getenv('SPRYKER_MESSAGE_BROKER_SQS_RECEIVER_CONFIG') !== false) {
            return getenv('SPRYKER_MESSAGE_BROKER_SQS_RECEIVER_CONFIG');
        }

        // @codeCoverageIgnoreStart
        if ($this->getConfig()->hasKey(MessageBrokerAwsConstants::SQS_RECEIVER_CONFIG)) {
            return $this->get(MessageBrokerAwsConstants::SQS_RECEIVER_CONFIG);
        }

        return [];
        // @codeCoverageIgnoreEnd
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    public function isDebugEnabled(): bool
    {
        if (getenv('SPRYKER_DEBUG_ENABLED') !== false) {
            return (bool)getenv('SPRYKER_DEBUG_ENABLED');
        }

        // @codeCoverageIgnoreStart
        if ($this->getConfig()->hasKey(MessageBrokerAwsConstants::DEBUG_ENABLED)) {
            return $this->get(MessageBrokerAwsConstants::DEBUG_ENABLED);
        }

        return false;
        // @codeCoverageIgnoreEnd
    }

    /**
     * @api
     *
     * @return array<string, string>|string
     */
    public function getChannelToSenderTransportMap()
    {
        if (getenv('SPRYKER_CHANNEL_TO_SENDER_TRANSPORT_MAP') !== false) {
            return getenv('SPRYKER_CHANNEL_TO_SENDER_TRANSPORT_MAP');
        }

        // @codeCoverageIgnoreStart
        if ($this->getConfig()->hasKey(MessageBrokerAwsConstants::CHANNEL_TO_SENDER_TRANSPORT_MAP)) {
            return $this->get(MessageBrokerAwsConstants::CHANNEL_TO_SENDER_TRANSPORT_MAP);
        }

        return [];
        // @codeCoverageIgnoreEnd
    }

    /**
     * @api
     *
     * @return array<string, string>|string
     */
    public function getChannelToReceiverTransportMap()
    {
        if (getenv('SPRYKER_CHANNEL_TO_RECEIVER_TRANSPORT_MAP') !== false) {
            return getenv('SPRYKER_CHANNEL_TO_RECEIVER_TRANSPORT_MAP');
        }

        // @codeCoverageIgnoreStart
        if ($this->getConfig()->hasKey(MessageBrokerAwsConstants::CHANNEL_TO_RECEIVER_TRANSPORT_MAP)) {
            return $this->get(MessageBrokerAwsConstants::CHANNEL_TO_RECEIVER_TRANSPORT_MAP);
        }

        return [];
        // @codeCoverageIgnoreEnd
    }

    /**
     * @api
     *
     * @return array<string, string>|string
     */
    public function getMessageToChannelMap()
    {
        if (getenv('SPRYKER_MESSAGE_TO_CHANNEL_MAP') !== false) {
            return getenv('SPRYKER_MESSAGE_TO_CHANNEL_MAP');
        }

        // @codeCoverageIgnoreStart
        if ($this->getConfig()->hasKey(MessageBrokerAwsConstants::MESSAGE_TO_CHANNEL_MAP)) {
            return $this->get(MessageBrokerAwsConstants::MESSAGE_TO_CHANNEL_MAP);
        }

        return [];
        // @codeCoverageIgnoreStart
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return array<int, string>
     */
    public function getSqsQueuesNames(): array
    {
        return $this->get(MessageBrokerAwsConstants::SQS_AWS_CREATOR_QUEUE_NAMES, []);
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getSqsToSnsSubscriptions(): array
    {
        return $this->get(MessageBrokerAwsConstants::SQS_AWS_TO_SNS_SUBSCRIPTIONS, []);
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return array<string>
     */
    public function getSnsTopicNames(): array
    {
        return $this->get(MessageBrokerAwsConstants::SNS_AWS_CREATOR_TOPIC_NAMES, []);
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    public function getSqsAwsAccessKey(): string
    {
        return $this->get(MessageBrokerAwsConstants::SQS_AWS_SECRET_ACCESS, '');
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    public function getSqsAwsAccessSecret(): string
    {
        return $this->get(MessageBrokerAwsConstants::SQS_AWS_ACCESS_KEY, '');
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    public function getSqsAwsEndpoint(): string
    {
        return $this->get(MessageBrokerAwsConstants::SQS_AWS_ENDPOINT, '');
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    public function getSqsAwsRegion(): string
    {
        return $this->get(MessageBrokerAwsConstants::SQS_AWS_REGION, '');
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    public function getSqsAwsVersion(): string
    {
        return static::SQS_AWS_API_VERSION;
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return array<string>
     */
    public function getStandardHttpHeaders(): array
    {
        return [
            'Authorization',
        ];
    }

    /**
     * Specification:
     * - Get default configuration for message data filters
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MessageDataFilterConfigurationTransfer
     */
    public function getDefaultMessageDataFilterConfiguration(): MessageDataFilterConfigurationTransfer
    {
        $config = new MessageDataFilterConfigurationTransfer();
        $config->setStripIdFieldsConfiguration(
            (new MessageDataFilterItemConfigurationTransfer())
                ->setPatterns([
                    '/^id[A-Z]/',
                    '/^fk[A-Z]/',
                ]),
        );
        $config->setStripNullFieldsConfiguration(
            (new MessageDataFilterItemConfigurationTransfer()),
        );

        return $config;
    }

    /**
     * Specification:
     * - Specifies the base URL for the HTTP channel sender.
     *
     * @api
     *
     * @return string
     */
    public function getHttpChannelSenderBaseUrl(): string
    {
        return $this->get(MessageBrokerAwsConstants::HTTP_CHANNEL_SENDER_BASE_URL, '');
    }

    /**
     * Specification:
     * - Specifies the base URL for the HTTP channel receiver.
     *
     * @api
     *
     * @return string
     */
    public function getHttpChannelReceiverBaseUrl(): string
    {
        return $this->get(MessageBrokerAwsConstants::HTTP_CHANNEL_RECEIVER_BASE_URL, '');
    }

    /**
     * Specification:
     * - Sets a limit for message consumption.
     *
     * @api
     *
     * @return int
     */
    public function getMessageConsumeLimit(): int
    {
        return 100;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getConsumerId(): string
    {
        return $this->get(MessageBrokerAwsConstants::CONSUMER_ID, '');
    }
}
