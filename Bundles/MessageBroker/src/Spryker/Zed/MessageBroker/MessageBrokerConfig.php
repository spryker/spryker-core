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
     * This configuration defines success code for message broker call.
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
     * This configuration defines success code name for message broker call.
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
     * This configuration defines error code for message broker call.
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
     * This configuration defines error code name for message broker call.
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
     * This configuration can to be done via environment variable.
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
     * This configuration can be done via environment variables.
     *
     * @api
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
     * This configuration enables loggin for worker.
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
     * This configuration defines log file path.
     *
     * @api
     *
     * @return string
     */
    public function getLogFilePath(): string
    {
        return sprintf('%s/data/logs/message_broker_%s.log', APPLICATION_ROOT_DIR, APPLICATION_STORE);
    }

    /**
     * Defines attributes which should not be logged.
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
     * Gets default channels for worker.
     *
     * @api
     *
     * @return array<string>
     */
    public function getDefaultWorkerChannels(): array
    {
        return [];
    }
}
