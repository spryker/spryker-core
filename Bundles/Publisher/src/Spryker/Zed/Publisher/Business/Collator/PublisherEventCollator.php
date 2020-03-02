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
            $publishQueueName = $publisherPlugin->getPublishQueueName() ?? EventConstants::EVENT_QUEUE;

            if (!isset($eventCollection[$publishQueueName])) {
                $eventCollection[$publishQueueName] = [];
            }

            foreach ($publisherPlugin->getSubscribedEvents() as $subscribedEvent) {
                if (!isset($eventCollection[$publishQueueName][$subscribedEvent])) {
                    $eventCollection[$publishQueueName][$subscribedEvent] = [];
                }
                $eventCollection[$publishQueueName][$subscribedEvent][] = get_class($publisherPlugin);
            }
        }

        return $eventCollection;
    }
}
