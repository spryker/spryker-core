<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Price\Dependency\PriceEvents;
use Spryker\Zed\PriceStorage\Communication\Plugin\Event\Listener\PriceProductAbstractPublishStorageListener;
use Spryker\Zed\PriceStorage\Communication\Plugin\Event\Listener\PriceProductAbstractStorageListener;
use Spryker\Zed\PriceStorage\Communication\Plugin\Event\Listener\PriceProductConcretePublishStorageListener;
use Spryker\Zed\PriceStorage\Communication\Plugin\Event\Listener\PriceProductConcreteStorageListener;
use Spryker\Zed\PriceStorage\Communication\Plugin\Event\Listener\PriceTypeProductAbstractStorageListener;
use Spryker\Zed\PriceStorage\Communication\Plugin\Event\Listener\PriceTypeProductConcreteStorageListener;

/**
 * @method \Spryker\Zed\PriceStorage\Communication\PriceStorageCommunicationFactory getFactory()
 */
class PriceStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
            ->addListenerQueued(PriceEvents::PRICE_ABSTRACT_PUBLISH, new PriceProductAbstractPublishStorageListener())
            ->addListenerQueued(PriceEvents::PRICE_ABSTRACT_UNPUBLISH, new PriceProductAbstractPublishStorageListener())
            ->addListenerQueued(PriceEvents::PRICE_CONCRETE_PUBLISH, new PriceProductConcretePublishStorageListener())
            ->addListenerQueued(PriceEvents::PRICE_CONCRETE_UNPUBLISH, new PriceProductConcretePublishStorageListener())
            ->addListenerQueued(PriceEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE, new PriceProductAbstractStorageListener())
            ->addListenerQueued(PriceEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE, new PriceProductConcreteStorageListener())
            ->addListenerQueued(PriceEvents::ENTITY_SPY_PRICE_PRODUCT_UPDATE, new PriceProductAbstractStorageListener())
            ->addListenerQueued(PriceEvents::ENTITY_SPY_PRICE_PRODUCT_UPDATE, new PriceProductConcreteStorageListener())
            ->addListenerQueued(PriceEvents::ENTITY_SPY_PRICE_PRODUCT_DELETE, new PriceProductAbstractStorageListener())
            ->addListenerQueued(PriceEvents::ENTITY_SPY_PRICE_PRODUCT_DELETE, new PriceProductConcreteStorageListener())
            ->addListenerQueued(PriceEvents::ENTITY_SPY_PRICE_TYPE_UPDATE, new PriceTypeProductAbstractStorageListener())
            ->addListenerQueued(PriceEvents::ENTITY_SPY_PRICE_TYPE_UPDATE, new PriceTypeProductConcreteStorageListener())
            ->addListenerQueued(PriceEvents::ENTITY_SPY_PRICE_TYPE_DELETE, new PriceTypeProductAbstractStorageListener())
            ->addListenerQueued(PriceEvents::ENTITY_SPY_PRICE_TYPE_DELETE, new PriceTypeProductConcreteStorageListener());

        return $eventCollection;
    }

}
