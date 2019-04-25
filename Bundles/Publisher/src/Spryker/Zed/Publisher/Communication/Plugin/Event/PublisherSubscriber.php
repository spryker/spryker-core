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
 * @method \Spryker\Zed\Publisher\Business\PublisherFacadeInterface getFacade()()
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
        $plugins = $this->getFacade()->mergePublisherPlugins();
        $this->extractEventListenerCollection($eventCollection, $plugins);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     * @param array $publisherPlugins
     *
     * @return void
     */
    protected function extractEventListenerCollection(EventCollectionInterface $eventCollection, array $publisherPlugins): void
    {
        foreach ($publisherPlugins as $eventName => $listeners) {
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
