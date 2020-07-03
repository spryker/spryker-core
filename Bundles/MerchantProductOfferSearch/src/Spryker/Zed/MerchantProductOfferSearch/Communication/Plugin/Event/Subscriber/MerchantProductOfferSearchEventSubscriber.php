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
 */
class MerchantProductOfferSearchEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
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
        $eventCollection->addListenerQueued(MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_PUBLISH, new MerchantProductOfferSearchEventListener(), 0, null, $this->getConfig()->getMerchantProductOfferEventQueueName());
        $eventCollection->addListenerQueued(MerchantProductOfferEvents::ENTITY_SPY_PRODUCT_OFFER_CREATE, new MerchantProductOfferSearchEventListener(), 0, null, $this->getConfig()->getMerchantProductOfferEventQueueName());
        $eventCollection->addListenerQueued(MerchantProductOfferEvents::ENTITY_SPY_PRODUCT_OFFER_UPDATE, new MerchantProductOfferSearchEventListener(), 0, null, $this->getConfig()->getMerchantProductOfferEventQueueName());

        return $eventCollection;
    }
}
