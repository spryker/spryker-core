<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductAbstractPublishStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductAbstractStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductConcretePublishStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductConcreteStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductStoreAbstractStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductStoreConcreteStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceTypeProductAbstractStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceTypeProductConcreteStorageListener;

/**
 * @method \Spryker\Zed\PriceProductStorage\Communication\PriceProductStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductStorage\Business\PriceProductStorageFacadeInterface getFacade()
 */
class PriceProductStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addPriceProductAbstractPublishStorageListener($eventCollection);
        $this->addPriceProductAbstractUnpublishStorageListener($eventCollection);
        $this->addPriceProductConcretePublishStorageListener($eventCollection);
        $this->addPriceProductConcreteUnpublishStorageListener($eventCollection);
        $this->addPriceProductAbstractCreateStorageListener($eventCollection);
        $this->addPriceProductConcreteCreateStorageListener($eventCollection);
        $this->addPriceProductAbstractUpdateStorageListener($eventCollection);
        $this->addPriceProductConcreteUpdateStorageListener($eventCollection);
        $this->addPriceProductAbstractDeleteStorageListener($eventCollection);
        $this->addPriceProductConcreteDeleteStorageListener($eventCollection);
        $this->addPriceTypeProductAbstractUpdateStorageListener($eventCollection);
        $this->addPriceTypeProductConcreteUpdateStorageListener($eventCollection);
        $this->addPriceTypeProductAbstractDeleteStorageListener($eventCollection);
        $this->addPriceTypeProductConcreteDeleteStorageListener($eventCollection);
        $this->addPriceProductStoreConcreteCreateStorageListener($eventCollection);
        $this->addPriceProductStoreConcreteUpdateStorageListener($eventCollection);
        $this->addPriceProductStoreConcreteDeleteStorageListener($eventCollection);
        $this->addPriceProductStoreAbstractCreateStorageListener($eventCollection);
        $this->addPriceProductStoreAbstractUpdateeStorageListener($eventCollection);
        $this->addPriceProductStoreAbstractDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductAbstractPublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::PRICE_ABSTRACT_PUBLISH, new PriceProductAbstractPublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductAbstractUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::PRICE_ABSTRACT_UNPUBLISH, new PriceProductAbstractPublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductConcretePublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::PRICE_CONCRETE_PUBLISH, new PriceProductConcretePublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductConcreteUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::PRICE_CONCRETE_UNPUBLISH, new PriceProductConcretePublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductAbstractCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE, new PriceProductAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductAbstractUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_UPDATE, new PriceProductAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductAbstractDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_DELETE, new PriceProductAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductConcreteCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE, new PriceProductConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductConcreteUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_UPDATE, new PriceProductConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductConcreteDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_DELETE, new PriceProductConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceTypeProductAbstractUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_TYPE_UPDATE, new PriceTypeProductAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceTypeProductAbstractDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_TYPE_DELETE, new PriceTypeProductAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceTypeProductConcreteUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_TYPE_UPDATE, new PriceTypeProductConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceTypeProductConcreteDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_TYPE_DELETE, new PriceTypeProductConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductStoreConcreteCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE, new PriceProductStoreConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductStoreConcreteUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_UPDATE, new PriceProductStoreConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductStoreConcreteDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_DELETE, new PriceProductStoreConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductStoreAbstractCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE, new PriceProductStoreAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductStoreAbstractUpdateeStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_UPDATE, new PriceProductStoreAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductStoreAbstractDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_DELETE, new PriceProductStoreAbstractStorageListener());
    }
}
