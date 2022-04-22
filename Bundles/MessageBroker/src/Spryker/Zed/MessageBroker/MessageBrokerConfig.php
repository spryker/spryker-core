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
