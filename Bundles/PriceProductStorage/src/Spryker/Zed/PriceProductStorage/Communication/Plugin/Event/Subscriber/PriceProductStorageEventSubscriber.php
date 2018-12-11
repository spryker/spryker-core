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
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductAbstractEntityStoragePublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductAbstractStoragePublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductAbstractStorageUnpublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductConcreteEntityStoragePublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductConcreteEntityStorageUnpublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductConcreteStoragePublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductConcreteStorageUnpublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductDefaultAbstractStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductDefaultConcreteStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductStoreAbstractStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceProductStoreConcreteStorageListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceTypeProductAbstractStoragePublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceTypeProductAbstractStorageUnpublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceTypeProductConcreteStoragePublishListener;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener\PriceTypeProductConcreteStorageUnpublishListener;

/**
 * @method \Spryker\Zed\PriceProductStorage\Communication\PriceProductStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductStorage\Business\PriceProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductStorage\PriceProductStorageConfig getConfig()
 * @method \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface getQueryContainer()
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
        $this->addPriceProductStoreAbstractUpdateStorageListener($eventCollection);
        $this->addPriceProductStoreAbstractDeleteStorageListener($eventCollection);
        $this->addPriceProductDefaultAbstractCreateStorageListener($eventCollection);
        $this->addPriceProductDefaultAbstractUpdateStorageListener($eventCollection);
        $this->addPriceProductDefaultAbstractDeleteStorageListener($eventCollection);
        $this->addPriceProductDefaultConcreteCreateStorageListener($eventCollection);
        $this->addPriceProductDefaultConcreteUpdateStorageListener($eventCollection);
        $this->addPriceProductDefaultConcreteDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductAbstractPublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::PRICE_ABSTRACT_PUBLISH, new PriceProductAbstractStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductAbstractUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::PRICE_ABSTRACT_UNPUBLISH, new PriceProductAbstractStorageUnpublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductConcretePublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::PRICE_CONCRETE_PUBLISH, new PriceProductConcreteStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductConcreteUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::PRICE_CONCRETE_UNPUBLISH, new PriceProductConcreteStorageUnpublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductAbstractCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE, new PriceProductAbstractEntityStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductAbstractUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_UPDATE, new PriceProductAbstractEntityStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductAbstractDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_DELETE, new PriceProductAbstractEntityStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductConcreteCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE, new PriceProductConcreteEntityStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductConcreteUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_UPDATE, new PriceProductConcreteEntityStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductConcreteDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_DELETE, new PriceProductConcreteEntityStorageUnpublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceTypeProductAbstractUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_TYPE_UPDATE, new PriceTypeProductAbstractStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceTypeProductAbstractDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_TYPE_DELETE, new PriceTypeProductAbstractStorageUnpublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceTypeProductConcreteUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_TYPE_UPDATE, new PriceTypeProductConcreteStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceTypeProductConcreteDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_TYPE_DELETE, new PriceTypeProductConcreteStorageUnpublishListener());
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
    protected function addPriceProductStoreAbstractUpdateStorageListener(EventCollectionInterface $eventCollection): void
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

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductDefaultAbstractCreateStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_DEFAULT_CREATE, new PriceProductDefaultAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductDefaultAbstractUpdateStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_DEFAULT_UPDATE, new PriceProductDefaultAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductDefaultAbstractDeleteStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_DEFAULT_DELETE, new PriceProductDefaultAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductDefaultConcreteCreateStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_DEFAULT_CREATE, new PriceProductDefaultConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductDefaultConcreteUpdateStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_DEFAULT_UPDATE, new PriceProductDefaultConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductDefaultConcreteDeleteStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_DEFAULT_DELETE, new PriceProductDefaultConcreteStorageListener());
    }
}
