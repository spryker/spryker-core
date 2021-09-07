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
    /**
     * @var int
     */
    public const DEFAULT_EVENT_MESSAGE_CHUNK_SIZE = 500;
    /**
     * @var int
     */
    protected const ENQUEUE_EVENT_MESSAGE_CHUNK_SIZE = 500;

    /**
     * @var int
     */
    public const DEFAULT_MAX_RETRY = 1;
    /**
     * @var int
     */
    public const NO_RETRY = 0;

    /**
     * @api
     *
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
     * @api
     *
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
     * @api
     *
     * @return int
     */
    public function getEventQueueMessageChunkSize()
    {
        return $this->get(EventConstants::EVENT_CHUNK, static::DEFAULT_EVENT_MESSAGE_CHUNK_SIZE);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getMaxRetryAmount()
    {
        $maxRetry = $this->getConfig()->get(EventConstants::MAX_RETRY_ON_FAIL, false);

        if ($maxRetry !== false) {
            return $maxRetry;
        }

        if (!$this->isEventRetryQueueExists()) {
            return static::NO_RETRY;
        }

        return static::DEFAULT_MAX_RETRY;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getEnqueueEventMessageChunkSize(): int
    {
        return $this->get(EventConstants::ENQUEUE_EVENT_CHUNK, static::ENQUEUE_EVENT_MESSAGE_CHUNK_SIZE);
    }

    /**
     * Keeping instance pooling disallowed helps to avoid caching of used entities inside Propel which keeps
     * memory consumption minimal.
     * Allowing instance pooling can help performance. Make sure that the added memory consumption is
     * not an issue then, though.
     *
     * @api
     *
     * @return bool
     */
    public function isInstancePoolingAllowed(): bool
    {
        return $this->get(EventConstants::IS_INSTANCE_POOLING_ALLOWED, false);
    }

    /**
     * @deprecated This is added only for BC reason and will
     * be removed in the next major.
     *
     * @return bool
     */
    protected function isEventRetryQueueExists(): bool
    {
        $bundleConfigResolver = new BundleConfigResolver();
        try {
            /** @var \Spryker\Client\RabbitMq\RabbitMqConfig $config */
            $config = $bundleConfigResolver->resolve('\Spryker\Client\RabbitMq\RabbitMqFactory');

            return $this->hasEventRetryQueueConfig($config);
        } catch (BundleConfigNotFoundException $exception) {
            return false;
        }
    }

    /**
     * @deprecated This is added only for BC reason and will
     * be removed in the next major.
     *
     * @param \Spryker\Client\RabbitMq\RabbitMqConfig $config
     *
     * @return bool
     */
    protected function hasEventRetryQueueConfig($config): bool
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
