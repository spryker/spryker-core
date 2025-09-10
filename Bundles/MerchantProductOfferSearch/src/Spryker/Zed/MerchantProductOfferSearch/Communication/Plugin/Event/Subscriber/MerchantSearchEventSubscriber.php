<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\Event\Listener\MerchantSearchEventListener;

/**
 * @deprecated Use {@link \Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\Publisher\Product\MerchantSearchPublisherPlugin} instead.
 *
 * This plugin subscribes to merchant-related events to trigger the search index updates.
 * It listens to events such as `MerchantEvents::MERCHANT_PUBLISH` and `MerchantEvents::ENTITY_SPY_MERCHANT_UPDATE`.
 * Registers in the DependencyProvider only if the Spryker\Zed\MerchantProductSearch\Communication\Plugin\Publisher\Merchant\MerchantProductSearchWritePublisherPlugin is not enabled.
 *
 * @method \Spryker\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferSearch\Business\MerchantProductOfferSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOfferSearch\Communication\MerchantProductOfferSearchCommunicationFactory getFactory()
 */
class MerchantSearchEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $eventCollection->addListenerQueued(MerchantEvents::MERCHANT_PUBLISH, new MerchantSearchEventListener(), 0, null, $this->getConfig()->getMerchantEventQueueName());
        $eventCollection->addListenerQueued(MerchantEvents::ENTITY_SPY_MERCHANT_UPDATE, new MerchantSearchEventListener(), 0, null, $this->getConfig()->getMerchantEventQueueName());

        return $eventCollection;
    }
}
