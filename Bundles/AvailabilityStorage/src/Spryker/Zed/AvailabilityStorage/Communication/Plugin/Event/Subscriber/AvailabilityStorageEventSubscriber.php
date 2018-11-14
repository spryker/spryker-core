<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Availability\Dependency\AvailabilityEvents;
use Spryker\Zed\AvailabilityStorage\Communication\Plugin\Event\Listener\AvailabilityProductStorageListener;
use Spryker\Zed\AvailabilityStorage\Communication\Plugin\Event\Listener\AvailabilityStockStorageListener;
use Spryker\Zed\AvailabilityStorage\Communication\Plugin\Event\Listener\AvailabilityStorageListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\ProductEvents;

/**
 * @method \Spryker\Zed\AvailabilityStorage\Communication\AvailabilityStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\AvailabilityStorage\Business\AvailabilityStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\AvailabilityStorage\AvailabilityStorageConfig getConfig()
 * @method \Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageQueryContainerInterface getQueryContainer()
 */
class AvailabilityStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection)
    {
        $this->addAvailabilityAbstractPublishListener($eventCollection);
        $this->addAvailabilityAbstractUnPublishListener($eventCollection);
        $this->addAvailabilityAbstractCreateListener($eventCollection);
        $this->addAvailabilityAbstractUpdateListener($eventCollection);
        $this->addAvailabilityAbstractDeleteListener($eventCollection);
        $this->addProductUpdateListener($eventCollection);
        $this->addAvailabilityStockUpdateListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addAvailabilityAbstractPublishListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(AvailabilityEvents::AVAILABILITY_ABSTRACT_PUBLISH, new AvailabilityStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addAvailabilityAbstractUnPublishListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(AvailabilityEvents::AVAILABILITY_ABSTRACT_UNPUBLISH, new AvailabilityStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addAvailabilityAbstractCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_ABSTRACT_CREATE, new AvailabilityStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addAvailabilityAbstractUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_ABSTRACT_UPDATE, new AvailabilityStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addAvailabilityAbstractDeleteListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_ABSTRACT_DELETE, new AvailabilityStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_UPDATE, new AvailabilityProductStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addAvailabilityStockUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_UPDATE, new AvailabilityStockStorageListener());
    }
}
