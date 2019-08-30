<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event;

use Spryker\Client\Kernel\ClassResolver\Config\BundleConfigNotFoundException;
use Spryker\Client\Kernel\ClassResolver\Config\BundleConfigResolver;
use Spryker\Shared\Event\EventConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class EventConfig extends AbstractBundleConfig
{
    public const DEFAULT_EVENT_MESSAGE_CHUNK_SIZE = 1000;
    public const DEFAULT_MAX_RETRY = 1;

    /**
     * @var bool
     */
    protected static $eventRetryActive;

    /**
     * @return string|null
     */
    public function findEventLogPath()
    {
        if ($this->getConfig()->hasKey(EventConstants::LOG_FILE_PATH)) {
            return $this->get(EventConstants::LOG_FILE_PATH);
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isLoggerActivated()
    {
        if (!$this->getConfig()->hasKey(EventConstants::LOGGER_ACTIVE)) {
            return false;
        }

        return $this->getConfig()->get(EventConstants::LOGGER_ACTIVE, false);
    }

    /**
     * @return bool
     */
    public function isEventRetryEnabled()
    {
        if ($this->isEventRetryQueueExists()) {
            return true;
        }

        return $this->getConfig()->get(EventConstants::EVENT_RETRY_ACTIVE, false);
    }

    /**
     * @return int
     */
    public function getEventQueueMessageChunkSize()
    {
        return $this->get(EventConstants::EVENT_CHUNK, 500);
    }

    /**
     * @return int
     */
    public function getMaxRetryAmount()
    {
        return $this->getConfig()->get(EventConstants::MAX_RETRY_ON_FAIL, static::DEFAULT_MAX_RETRY);
    }

    /**
     * @deprecated This is added only for BC reason and will
     * be removed in the next major.
     *
     * @return bool
     */
    protected function isEventRetryQueueExists(): bool
    {
        if (static::$eventRetryActive === null) {
            static::$eventRetryActive = false;
            $bundleConfigResolver = new BundleConfigResolver();
            try {
                /** @var \Spryker\Client\RabbitMq\RabbitMqConfig $config */
                $config = $bundleConfigResolver->resolve('\Spryker\Client\RabbitMq\RabbitMqFactory');
                static::$eventRetryActive = $this->isEventRetryConfigExists($config);
            } catch (BundleConfigNotFoundException $exception) {
                static::$eventRetryActive = false;
            }
        }

        return static::$eventRetryActive;
    }

    /**
     * @deprecated This is added only for BC reason and will
     * be removed in the next major.
     *
     * @param \Spryker\Client\RabbitMq\RabbitMqConfig $config
     *
     * @return bool
     */
    protected function isEventRetryConfigExists($config): bool
    {
        $connections = $config->getQueueConnections();
        $defaultConnection = current($connections);
        foreach ($defaultConnection->getQueueOptionCollection() as $option) {
            if ($option->getQueueName() !== 'event') {
                continue;
            }
            $bindingQueueOptionCollection = $option->getBindingQueueCollection();
            foreach ($bindingQueueOptionCollection as $queueOption) {
                if ($queueOption->getQueueName() === 'event.retry') {
                    return true;
                }
            }

            return false;
        }

        return false;
    }
}
