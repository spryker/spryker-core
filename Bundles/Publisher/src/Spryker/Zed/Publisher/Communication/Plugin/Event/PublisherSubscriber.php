<?php
/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Communication\Plugin\Event;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Publisher\Communication\PublisherCommunicationFactory;
use Spryker\Zed\Publisher\Dependency\PublisherEventRegistry;

/**
 *  @method PublisherCommunicationFactory getFactory()
 */
class PublisherSubscriber extends AbstractPlugin implements EventSubscriberInterface
{

    /**
     * @param EventCollectionInterface $eventCollection
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection)
    {
        $publisherRegistryCollection = $this->getFactory()->getPublisherRegistryCollection();

        $publisherListeners = $this->getRegisteredPublisherCollection($publisherRegistryCollection);

        foreach ($publisherListeners as $publisherEventCollection) {
            $this->extractEventListenerCollection($eventCollection, $publisherEventCollection);
        }

        return $eventCollection;
    }

    /**
     * @param $publisherRegistryCollection
     *
     * @return array
     */
    protected function getRegisteredPublisherCollection($publisherRegistryCollection): array
    {
        $publisherListeners = [];
        foreach ($publisherRegistryCollection as $publisherRegistry) {
            $publisherListeners[] = $publisherRegistry->getPublisherEventRegistry(new PublisherEventRegistry());
        }

        return $publisherListeners;
    }

    /**
     * @param EventCollectionInterface $eventCollection
     * @param $publisherEventCollection
     *
     * @return void
     */
    protected function extractEventListenerCollection(EventCollectionInterface $eventCollection, $publisherEventCollection): void
    {
        foreach ($publisherEventCollection as $eventName => $listeners) {
            $this->addListenerToEventCollection($eventCollection, $listeners, $eventName);
        }
    }

    /**
     * @param EventCollectionInterface $eventCollection
     * @param $listeners
     * @param $eventName
     *
     * @return void
     */
    protected function addListenerToEventCollection(EventCollectionInterface $eventCollection, $listeners, $eventName): void
    {
        foreach ($listeners as $listener) {
            $eventCollection->addListenerQueued($eventName, $listener);
        }
    }
}
