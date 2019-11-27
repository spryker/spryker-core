<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductOffer\Dependency\PriceProductOfferEvents;
use Spryker\Zed\PriceProductOfferStorage\Communication\Plugin\Event\Listener\PriceProductOfferStoragePublishListener;
use Spryker\Zed\PriceProductOfferStorage\Communication\Plugin\Event\Listener\PriceProductOfferStorageUnpublishListener;
use Spryker\Zed\PriceProductOfferStorage\Communication\Plugin\Event\Listener\ProductPublishListener;
use Spryker\Zed\PriceProductOfferStorage\Communication\Plugin\Event\Listener\ProductUnpublishListener;
use Spryker\Zed\Product\Dependency\ProductEvents;

/**
 * @method \Spryker\Zed\PriceProductOfferStorage\PriceProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\PriceProductOfferStorage\Business\PriceProductOfferStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductOfferStorage\Communication\PriceProductOfferStorageCommunicationFactory getFactory()
 */
class PriceProductOfferStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $eventCollection->addListenerQueued(PriceProductOfferEvents::ENTITY_SPY_PRICE_PRODUCT_OFFER_PUBLISH, new PriceProductOfferStoragePublishListener());
        $eventCollection->addListenerQueued(PriceProductOfferEvents::ENTITY_SPY_PRICE_PRODUCT_OFFER_CREATE, new PriceProductOfferStoragePublishListener());
        $eventCollection->addListenerQueued(PriceProductOfferEvents::ENTITY_SPY_PRICE_PRODUCT_OFFER_UPDATE, new PriceProductOfferStoragePublishListener());

        $eventCollection->addListenerQueued(PriceProductOfferEvents::ENTITY_SPY_PRICE_PRODUCT_OFFER_UNPUBLISH, new PriceProductOfferStorageUnpublishListener());
        $eventCollection->addListenerQueued(PriceProductOfferEvents::ENTITY_SPY_PRICE_PRODUCT_OFFER_DELETE, new PriceProductOfferStorageUnpublishListener());

        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_UPDATE, new ProductPublishListener());
        $eventCollection->addListenerQueued(ProductEvents::PRODUCT_CONCRETE_UNPUBLISH, new ProductUnpublishListener());

        return $eventCollection;
    }
}
