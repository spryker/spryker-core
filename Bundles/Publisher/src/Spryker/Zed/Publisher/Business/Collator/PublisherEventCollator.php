<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Business\Collator;

use Spryker\Zed\Publisher\Business\Registry\PublisherEventRegistryInterface;

class PublisherEventCollator implements PublisherEventCollatorInterface
{
    /**
     * @var \Spryker\Zed\Publisher\Business\Registry\PublisherEventRegistryInterface
     */
    protected $publishedEventRegistry;

    /**
     * @var \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface[]
     */
    protected $publisherPlugins = [];

    /**
     * @var string[]
     */
    protected static $eventCollectionBuffer;

    /**
     * @param \Spryker\Zed\Publisher\Business\Registry\PublisherEventRegistryInterface $publishedEventRegistry
     * @param \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface[] $publisherPlugins
     */
    public function __construct(PublisherEventRegistryInterface $publishedEventRegistry, array $publisherPlugins)
    {
        $this->publishedEventRegistry = $publishedEventRegistry;
        $this->publisherPlugins = $publisherPlugins;
    }

    /**
     * @return string[]
     */
    public function getPublisherEventCollection(): array
    {
        if (static::$eventCollectionBuffer === null) {
            static::$eventCollectionBuffer = $this->registerEventCollection();
        }

        return static::$eventCollectionBuffer;
    }

    /**
     * @return string[]
     */
    protected function registerEventCollection(): array
    {
        foreach ($this->publisherPlugins as $publisherPlugin) {
            foreach ($publisherPlugin->getSubscribedEvents() as $subscribedEvent) {
                $this->publishedEventRegistry = $this->publishedEventRegistry->register($subscribedEvent, get_class($publisherPlugin));
            }
        }

        return $this->getEventCollection();
    }

    /**
     * @return string[]
     */
    protected function getEventCollection(): array
    {
        $eventCollection = [];

        foreach ($this->publishedEventRegistry as $eventName => $listeners) {
            $eventCollection[$eventName] = $listeners;
        }

        return $eventCollection;
    }
}
