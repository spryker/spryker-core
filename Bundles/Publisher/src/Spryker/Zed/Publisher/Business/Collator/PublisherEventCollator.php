<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Business\Collator;

use Spryker\Shared\Event\EventConstants;
use Spryker\Zed\Publisher\PublisherConfig;

class PublisherEventCollator implements PublisherEventCollatorInterface
{
    /**
     * @var array
     */
    protected $publisherPlugins;

    /**
     * @var \Spryker\Zed\Publisher\PublisherConfig
     */
    protected $publisherConfig;

    /**
     * @var string[]
     */
    protected static $eventCollectionBuffer;

    /**
     * @param array $publisherPlugins
     * @param \Spryker\Zed\Publisher\PublisherConfig $publisherConfig
     */
    public function __construct(array $publisherPlugins, PublisherConfig $publisherConfig)
    {
        $this->publisherPlugins = $publisherPlugins;
        $this->publisherConfig = $publisherConfig;
    }

    /**
     * @return array
     */
    public function getPublisherEventCollection(): array
    {
        if (static::$eventCollectionBuffer === null) {
            static::$eventCollectionBuffer = $this->registerEventCollection();
        }

        return static::$eventCollectionBuffer;
    }

    /**
     * @return array
     */
    protected function registerEventCollection(): array
    {
        $eventCollection = [];

        foreach ($this->publisherPlugins as $queueName => $publisherData) {
            if (is_object($publisherData)) {
                $eventCollection = $this->registerSubscribedEventsByPublisher($eventCollection, $publisherData->getSubscribedEvents(), get_class($publisherData));

                continue;
            }

            if (is_array($publisherData)) {
                $eventCollection = $this->registerSubscribedEventsByQueueName($eventCollection, $publisherData, $queueName);
            }
        }

        return $eventCollection;
    }

    /**
     * @param array $eventCollection
     * @param \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface[] $publisherPlugins
     * @param string $publishQueueName
     *
     * @return array
     */
    public function registerSubscribedEventsByQueueName(array $eventCollection, array $publisherPlugins, string $publishQueueName): array
    {
        foreach ($publisherPlugins as $publisherPlugin) {
            $eventCollection = $this->registerSubscribedEventsByPublisher($eventCollection, $publisherPlugin->getSubscribedEvents(), get_class($publisherPlugin), $publishQueueName);
        }

        return $eventCollection;
    }

    /**
     * @param array $eventCollection
     * @param string[] $subscribedEvents
     * @param string $publisherClassName
     * @param string|null $publishQueueName
     *
     * @return array
     */
    protected function registerSubscribedEventsByPublisher(
        array $eventCollection,
        array $subscribedEvents,
        string $publisherClassName,
        ?string $publishQueueName = null
    ): array {
        $defaultPublishQueueName = $this->publisherConfig->getPublishQueueName() ?? EventConstants::EVENT_QUEUE;
        $publishQueueName = $publishQueueName ?? $defaultPublishQueueName;

        foreach ($subscribedEvents as $subscribedEvent) {
            $eventCollection[$publishQueueName][$subscribedEvent][] = $publisherClassName;
        }

        return $eventCollection;
    }
}
