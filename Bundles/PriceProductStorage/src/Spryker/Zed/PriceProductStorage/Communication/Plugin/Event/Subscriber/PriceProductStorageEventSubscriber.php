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
        $eventCollection
            ->addListenerQueued(PriceProductEvents::PRICE_ABSTRACT_PUBLISH, new PriceProductAbstractPublishStorageListener())
            ->addListenerQueued(PriceProductEvents::PRICE_ABSTRACT_UNPUBLISH, new PriceProductAbstractPublishStorageListener())
            ->addListenerQueued(PriceProductEvents::PRICE_CONCRETE_PUBLISH, new PriceProductConcretePublishStorageListener())
            ->addListenerQueued(PriceProductEvents::PRICE_CONCRETE_UNPUBLISH, new PriceProductConcretePublishStorageListener())
            ->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE, new PriceProductAbstractStorageListener())
            ->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE, new PriceProductConcreteStorageListener())
            ->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_UPDATE, new PriceProductAbstractStorageListener())
            ->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_UPDATE, new PriceProductConcreteStorageListener())
            ->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_DELETE, new PriceProductAbstractStorageListener())
            ->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_DELETE, new PriceProductConcreteStorageListener())
            ->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_TYPE_UPDATE, new PriceTypeProductAbstractStorageListener())
            ->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_TYPE_UPDATE, new PriceTypeProductConcreteStorageListener())
            ->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_TYPE_DELETE, new PriceTypeProductAbstractStorageListener())
            ->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_TYPE_DELETE, new PriceTypeProductConcreteStorageListener())
            ->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE, new PriceProductStoreConcreteStorageListener())
            ->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_UPDATE, new PriceProductStoreConcreteStorageListener())
            ->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_DELETE, new PriceProductStoreConcreteStorageListener())
            ->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE, new PriceProductStoreAbstractStorageListener())
            ->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_UPDATE, new PriceProductStoreAbstractStorageListener())
            ->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_DELETE, new PriceProductStoreAbstractStorageListener());

        return $eventCollection;
    }
}
