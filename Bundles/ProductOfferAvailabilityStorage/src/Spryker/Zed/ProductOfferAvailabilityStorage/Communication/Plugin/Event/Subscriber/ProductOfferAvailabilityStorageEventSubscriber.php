<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferAvailability\Dependency\ProductOfferAvailabilityEvents;
use Spryker\Zed\ProductOfferAvailabilityStorage\Communication\Plugin\Event\Listener\OmsProductReservationStoragePublishListener;
use Spryker\Zed\ProductOfferAvailabilityStorage\Communication\Plugin\Event\Listener\ProductOfferStockStoragePublishListener;
use Spryker\Zed\ProductOfferAvailabilityStorage\Communication\Plugin\Event\Listener\ProductOfferStoragePublishListener;

/**
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Business\ProductOfferAvailabilityStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Communication\ProductOfferAvailabilityStorageCommunicationFactory getFactory()
 */
class ProductOfferAvailabilityStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     * - Ads product offer availability storage related listeners.
     *
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $this->addOmsProductReservationCreateListener($eventCollection)
            ->addOmsProductReservationUpdateListener($eventCollection)
            ->addProductOfferPublishListener($eventCollection)
            ->addProductOfferStockCreateListener($eventCollection)
            ->addProductOfferStockUpdateListener($eventCollection)
            ->addProductOfferCreateListener($eventCollection)
            ->addProductOfferUpdateListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addOmsProductReservationCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOfferAvailabilityEvents::ENTITY_SPY_OMS_PRODUCT_RESERVATION_CREATE, new OmsProductReservationStoragePublishListener());

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addOmsProductReservationUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOfferAvailabilityEvents::ENTITY_SPY_OMS_PRODUCT_RESERVATION_UPDATE, new OmsProductReservationStoragePublishListener());

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addProductOfferStockCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOfferAvailabilityEvents::ENTITY_SPY_PRODUCT_OFFER_STOCK_CREATE, new ProductOfferStockStoragePublishListener());

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addProductOfferStockUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOfferAvailabilityEvents::ENTITY_SPY_PRODUCT_OFFER_STOCK_UPDATE, new ProductOfferStockStoragePublishListener());

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addProductOfferCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOfferAvailabilityEvents::ENTITY_SPY_PRODUCT_OFFER_CREATE, new ProductOfferStoragePublishListener());

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addProductOfferPublishListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOfferAvailabilityEvents::ENTITY_SPY_PRODUCT_OFFER_PUBLISH, new ProductOfferStoragePublishListener());

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addProductOfferUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductOfferAvailabilityEvents::ENTITY_SPY_PRODUCT_OFFER_UPDATE, new ProductOfferStoragePublishListener());

        return $this;
    }
}
