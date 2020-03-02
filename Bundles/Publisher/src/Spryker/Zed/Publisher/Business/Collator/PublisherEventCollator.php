<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Business\Collator;

use Spryker\Shared\Event\EventConstants;

class PublisherEventCollator implements PublisherEventCollatorInterface
{
    /**
     * @var \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface[]
     */
    protected $publisherPlugins;

    /**
     * @var string[]
     */
    protected static $eventCollectionBuffer;

    /**
     * @param \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface[] $publisherPlugins
     */
    public function __construct(array $publisherPlugins)
    {
        $this->publisherPlugins = $publisherPlugins;
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

        foreach ($this->publisherPlugins as $publisherPlugin) {
            $eventCollection = $this->registerSubscribedEventsByPublisher(
                $eventCollection,
                $publisherPlugin->getSubscribedEvents(),
                get_class($publisherPlugin)
            );
        }

        return $eventCollection;
    }

    /**
     * @param array $eventCollection
     * @param string[] $subscribedEvents
     * @param string $publisherClassName
     *
     * @return array
     */
    protected function registerSubscribedEventsByPublisher(array $eventCollection, array $subscribedEvents, string $publisherClassName): array
    {
        $publishQueueName = EventConstants::EVENT_QUEUE;

        if (!isset($eventCollection[$publishQueueName])) {
            $eventCollection[$publishQueueName] = [];
        }

        foreach ($subscribedEvents as $subscribedEvent) {
            if (!isset($eventCollection[$publishQueueName][$subscribedEvent])) {
                $eventCollection[$publishQueueName][$subscribedEvent] = [];
            }
            $eventCollection[$publishQueueName][$subscribedEvent][] = $publisherClassName;
        }

        return $eventCollection;
    }
}
