<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Availability\Dependency\AvailabilityEvents;
use Spryker\Zed\AvailabilityStorage\Communication\Plugin\Event\Listener\AvailabilityProductStorageListener;
use Spryker\Zed\AvailabilityStorage\Communication\Plugin\Event\Listener\AvailabilityStorageListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\ProductEvents;

/**
 * @method \Spryker\Zed\AvailabilityStorage\Communication\AvailabilityStorageCommunicationFactory getFactory()
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
        $eventCollection
            ->addListenerQueued(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_ABSTRACT_CREATE, new AvailabilityStorageListener())
            ->addListenerQueued(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_ABSTRACT_UPDATE, new AvailabilityStorageListener())
            ->addListenerQueued(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_ABSTRACT_DELETE, new AvailabilityStorageListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_UPDATE, new AvailabilityProductStorageListener());

        return $eventCollection;
    }

}
