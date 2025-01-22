<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker;

use Spryker\Shared\MessageBroker\MessageBrokerConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class MessageBrokerConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const MESSAGE_BROKER_CALL_SUCCESS_CODE = 11000;

    /**
     * @var string
     */
    protected const MESSAGE_BROKER_CALL_SUCCESS_CODE_NAME = 'Message Broker call sendMessage Success';

    /**
     * @var int
     */
    protected const MESSAGE_BROKER_CALL_ERROR_CODE = 11001;

    /**
     * @var string
     */
    protected const MESSAGE_BROKER_CALL_ERROR_CODE_NAME = 'Message Broker call sendMessage Error';

    /**
     * @var int
     */
    protected const MESSAGE_BROKER_CONSUME_SUCCESS_CODE = 11002;

    /**
     * @var string
     */
    protected const MESSAGE_BROKER_CONSUME_SUCCESS_CODE_NAME = 'Message Broker consume message Success';

    /**
     * @var int
     */
    protected const MESSAGE_BROKER_CONSUME_ERROR_CODE = 11003;

    /**
     * @var string
     */
    protected const MESSAGE_BROKER_CONSUME_ERROR_CODE_NAME = 'Message Broker consume message Error';

    /**
     * Specification:
     * - This configuration defines success code for message broker call.
     *
     * @api
     *
     * @return int
     */
    public function getMessageBrokerCallSuccessCode(): int
    {
        return static::MESSAGE_BROKER_CALL_SUCCESS_CODE;
    }

    /**
     * Specification:
     * - This configuration defines success code name for message broker call.
     *
     * @api
     *
     * @return string
     */
    public function getMessageBrokerCallSuccessCodeName(): string
    {
        return static::MESSAGE_BROKER_CALL_SUCCESS_CODE_NAME;
    }

    /**
     * Specification:
     * - This configuration defines error code for message broker call.
     *
     * @api
     *
     * @return int
     */
    public function getMessageBrokerCallErrorCode(): int
    {
        return static::MESSAGE_BROKER_CALL_ERROR_CODE;
    }

    /**
     * Specification:
     * - This configuration defines error code name for message broker call.
     *
     * @api
     *
     * @return string
     */
    public function getMessageBrokerCallErrorCodeName(): string
    {
        return static::MESSAGE_BROKER_CALL_ERROR_CODE_NAME;
    }

    /**
     * Specification:
     * - This configuration defines success code for message broker consume.
     *
     * @api
     *
     * @return int
     */
    public function getMessageBrokerConsumeSuccessCode(): int
    {
        return static::MESSAGE_BROKER_CONSUME_SUCCESS_CODE;
    }

    /**
     * Specification:
     * - This configuration defines success code name for message broker consume.
     *
     * @api
     *
     * @return string
     */
    public function getMessageBrokerConsumeSuccessCodeName(): string
    {
        return static::MESSAGE_BROKER_CONSUME_SUCCESS_CODE_NAME;
    }

    /**
     * Specification:
     * - This configuration defines error code for message broker consume.
     *
     * @api
     *
     * @return int
     */
    public function getMessageBrokerConsumeErrorCode(): int
    {
        return static::MESSAGE_BROKER_CONSUME_ERROR_CODE;
    }

    /**
     * Specification:
     * - This configuration defines error code name for message broker consume.
     *
     * @api
     *
     * @return string
     */
    public function getMessageBrokerConsumeErrorCodeName(): string
    {
        return static::MESSAGE_BROKER_CONSUME_ERROR_CODE_NAME;
    }

    /**
     * Specification:
     * - This configuration can to be done via environment variable.
     *
     * @api
     *
     * @return array<string, string>|string
     */
    public function getMessageToChannelMap()
    {
        if (getenv('SPRYKER_MESSAGE_TO_CHANNEL_MAP') !== false) {
            return getenv('SPRYKER_MESSAGE_TO_CHANNEL_MAP');
        }

        if ($this->getConfig()->hasKey(MessageBrokerConstants::MESSAGE_TO_CHANNEL_MAP)) {
            // @codeCoverageIgnoreStart
            return $this->get(MessageBrokerConstants::MESSAGE_TO_CHANNEL_MAP);
            // @codeCoverageIgnoreEnd
        }

        return [];
    }

    /**
     * Specification:
     * - This configuration can be done via environment variables.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\MessageBroker\MessageBrokerConfig::getChannelToSenderTransportMap()} instead.
     *
     * @return array<string, string>|string
     */
    public function getChannelToTransportMap()
    {
        if (getenv('SPRYKER_CHANNEL_TO_TRANSPORT_MAP') !== false) {
            return getenv('SPRYKER_CHANNEL_TO_TRANSPORT_MAP');
        }

        if ($this->getConfig()->hasKey(MessageBrokerConstants::CHANNEL_TO_TRANSPORT_MAP)) {
            // @codeCoverageIgnoreStart
            return $this->get(MessageBrokerConstants::CHANNEL_TO_TRANSPORT_MAP);
            // @codeCoverageIgnoreEnd
        }

        return [];
    }

    /**
     * Specification:
     * - This configuration enables loggin for worker.
     *
     * @api
     *
     * @return bool
     */
    public function isLoggingEnabled(): bool
    {
        return $this->get(MessageBrokerConstants::LOGGING_ENABLED, false);
    }

    /**
     * Specification:
     * - This configuration identifies whether default Spryker logger must be used instead of file logger.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    public function isDefaultApplicationLoggerUsed(): bool
    {
        return false;
    }

    /**
     * Specification:
     * - This configuration defines whether message body must be logged by message logger.
     *
     * @api
     *
     * @return bool
     */
    public function isMessageBodyIncludedInLogs(): bool
    {
        return false;
    }

    /**
     * Specification:
     * - This configuration defines log file path.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    public function getLogFilePath(): string
    {
        return sprintf('%s/data/logs/message_broker_%s.log', APPLICATION_ROOT_DIR, APPLICATION_STORE);
    }

    /**
     * Specification:
     * - Defines attributes which should not be logged.
     *
     * @api
     *
     * @return array<string>
     */
    public function getProtectedMessageAttributes(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Gets default channels for worker.
     *
     * @api
     *
     * @return array<string>
     */
    public function getDefaultWorkerChannels(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Returns system worker channels used to retrieve service messages.
     *
     * @api
     *
     * @return list<string>
     */
    public function getSystemWorkerChannels(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Global on/off toggle for sending and receiving messages through the MessageBroker.
     *
     * @api
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->get(MessageBrokerConstants::IS_ENABLED, true);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTenantIdentifier(): string
    {
        return $this->get(MessageBrokerConstants::TENANT_IDENTIFIER, '');
    }

    /**
     * Specification:
     * - Gets list of sender channels with mapping to their transports.
     *
     * @api
     *
     * @return array<string, mixed>
     */
    public function getChannelToSenderTransportMap(): array
    {
        return $this->get(MessageBrokerConstants::CHANNEL_TO_SENDER_TRANSPORT_MAP, []);
    }

    /**
     * Specification:
     * - Gets list of receiver channels with mapping to their transports.
     *
     * @api
     *
     * @return array<string, mixed>
     */
    public function getChannelToReceiverTransportMap(): array
    {
        return $this->get(MessageBrokerConstants::CHANNEL_TO_RECEIVER_TRANSPORT_MAP, []);
    }
}
