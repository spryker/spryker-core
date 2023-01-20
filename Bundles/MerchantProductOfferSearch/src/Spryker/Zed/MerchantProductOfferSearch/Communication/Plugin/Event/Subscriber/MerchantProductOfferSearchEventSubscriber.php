<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantProductOffer\Dependency\MerchantProductOfferEvents;
use Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\Event\Listener\MerchantProductOfferSearchEventListener;

/**
 * @method \Spryker\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferSearch\Business\MerchantProductOfferSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOfferSearch\Communication\MerchantProductOfferSearchCommunicationFactory getFactory()
 */
class MerchantProductOfferSearchEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @uses \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::PRODUCT_OFFER_PUBLISH
     *
     * @var string
     */
    protected const PRODUCT_OFFER_PUBLISH = 'ProductOffer.product_offer.publish';

    /**
     * @uses \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_CREATE
     *
     * @var string
     */
    protected const ENTITY_SPY_PRODUCT_OFFER_CREATE = 'Entity.spy_product_offer.create';

    /**
     * @uses \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_UPDATE
     *
     * @var string
     */
    protected const ENTITY_SPY_PRODUCT_OFFER_UPDATE = 'Entity.spy_product_offer.update';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $eventCollection->addListenerQueued(static::PRODUCT_OFFER_PUBLISH, new MerchantProductOfferSearchEventListener(), 0, null, $this->getConfig()->getMerchantProductOfferEventQueueName());
        $eventCollection->addListenerQueued(static::ENTITY_SPY_PRODUCT_OFFER_CREATE, new MerchantProductOfferSearchEventListener(), 0, null, $this->getConfig()->getMerchantProductOfferEventQueueName());
        $eventCollection->addListenerQueued(static::ENTITY_SPY_PRODUCT_OFFER_UPDATE, new MerchantProductOfferSearchEventListener(), 0, null, $this->getConfig()->getMerchantProductOfferEventQueueName());

        return $eventCollection;
    }

    /**
     * @deprecated Will be removed next major release.
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function getMerchantProductOfferSearchEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $eventCollection->addListenerQueued(MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_PUBLISH, new MerchantProductOfferSearchEventListener(), 0, null, $this->getConfig()->getMerchantProductOfferEventQueueName());
        $eventCollection->addListenerQueued(MerchantProductOfferEvents::ENTITY_SPY_PRODUCT_OFFER_CREATE, new MerchantProductOfferSearchEventListener(), 0, null, $this->getConfig()->getMerchantProductOfferEventQueueName());
        $eventCollection->addListenerQueued(MerchantProductOfferEvents::ENTITY_SPY_PRODUCT_OFFER_UPDATE, new MerchantProductOfferSearchEventListener(), 0, null, $this->getConfig()->getMerchantProductOfferEventQueueName());

        return $eventCollection;
    }
}
