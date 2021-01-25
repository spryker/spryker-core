<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Communication\Plugin\Event;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Publisher\Business\PublisherFacadeInterface getFacade()
 * @method \Spryker\Zed\Publisher\PublisherConfig getConfig()
 * @method \Spryker\Zed\Publisher\Communication\PublisherCommunicationFactory getFactory()
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
        $publisherEventCollection = $this->getFacade()->getPublisherEventCollection();

        return $this->extractEventListenerCollection($eventCollection, $publisherEventCollection);
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     * @param array $publisherEventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function extractEventListenerCollection(EventCollectionInterface $eventCollection, array $publisherEventCollection): EventCollectionInterface
    {
        foreach ($publisherEventCollection as $queueName => $eventsCollection) {
            foreach ($eventsCollection as $eventName => $listeners) {
                $this->addListenerToEventCollection($eventCollection, $listeners, $eventName, $queueName);
            }
        }

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     * @param string[] $listeners
     * @param string $eventName
     * @param string $queueName
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function addListenerToEventCollection(
        EventCollectionInterface $eventCollection,
        array $listeners,
        string $eventName,
        string $queueName
    ): EventCollectionInterface {
        foreach ($listeners as $listener) {
            $eventCollection->addListenerQueued($eventName, new $listener(), 0, null, $queueName);
        }

        return $eventCollection;
    }
}
