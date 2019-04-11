<?php
/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publishing\Communication\Plugin\Event;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Publishing\Communication\PublishingCommunicationFactory;
use Spryker\Zed\Publishing\Dependency\PublisherEventRegistry;

/**
 *  @method PublishingCommunicationFactory getFactory()
 */
class PublishingSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $publishingRegistryCollection = $this->getFactory()->getPublisherRegistryCollection();

        $publishingListeners = $this->getRegisteredPublishingCollection($publishingRegistryCollection);

        foreach ($publishingListeners as $publishingEventCollection) {
            $this->extractEventListenerCollection($eventCollection, $publishingEventCollection);
        }

        return $eventCollection;
    }

    /**
     * @param $publishingRegistryCollection
     *
     * @return array
     */
    protected function getRegisteredPublishingCollection($publishingRegistryCollection): array
    {
        $publishingListeners = [];
        foreach ($publishingRegistryCollection as $publishingRegistry) {
            $publishingListeners[] = $publishingRegistry->getPublisherEventRegistry(new PublisherEventRegistry());
        }

        return $publishingListeners;
    }

    /**
     * @param EventCollectionInterface $eventCollection
     * @param $publishingEventCollection
     *
     * @return void
     */
    protected function extractEventListenerCollection(EventCollectionInterface $eventCollection, $publishingEventCollection): void
    {
        foreach ($publishingEventCollection as $eventName => $listeners) {
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
