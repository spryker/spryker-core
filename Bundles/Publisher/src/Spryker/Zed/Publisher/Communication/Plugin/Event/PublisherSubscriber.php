<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Communication\Plugin\Event;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Publisher\Communication\Collection\PublisherRegistryCollectionInterface;
use Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface;

/**
 * @method \Spryker\Zed\Publisher\Communication\PublisherCommunicationFactory getFactory()
 * @method \Spryker\Zed\Publisher\PublisherConfig getConfig()
 */
class PublisherSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $publisherRegistryCollection = $this->getFactory()->getPublisherRegistryCollection();
        $publisherEventRegistries = $this->getRegisteredPublisherEventRegistries($publisherRegistryCollection);

        foreach ($publisherEventRegistries as $publisherEventRegistry) {
            $this->extractEventListenerCollection($eventCollection, $publisherEventRegistry);
        }

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Publisher\Communication\Collection\PublisherRegistryCollectionInterface $publisherRegistryCollection
     *
     * @return \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface[]
     */
    protected function getRegisteredPublisherEventRegistries(PublisherRegistryCollectionInterface $publisherRegistryCollection): array
    {
        $publisherPlugins = [];
        foreach ($publisherRegistryCollection as $publisherRegistryPlugin) {
            $publisherPlugins[] = $publisherRegistryPlugin->getPublisherEventRegistry($this->getFactory()->createPublisherEventRegistry());
        }

        return $publisherPlugins;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     * @param \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface $publisherEventRegistry
     *
     * @return void
     */
    protected function extractEventListenerCollection(EventCollectionInterface $eventCollection, PublisherEventRegistryInterface $publisherEventRegistry): void
    {
        foreach ($publisherEventRegistry as $eventName => $listeners) {
            $this->addListenerToEventCollection($eventCollection, $listeners, $eventName);
        }
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventBaseHandlerInterface[] $listeners
     * @param string $eventName
     *
     * @return void
     */
    protected function addListenerToEventCollection(EventCollectionInterface $eventCollection, array $listeners, string $eventName): void
    {
        foreach ($listeners as $listener) {
            $eventCollection->addListenerQueued($eventName, $listener);
        }
    }
}
