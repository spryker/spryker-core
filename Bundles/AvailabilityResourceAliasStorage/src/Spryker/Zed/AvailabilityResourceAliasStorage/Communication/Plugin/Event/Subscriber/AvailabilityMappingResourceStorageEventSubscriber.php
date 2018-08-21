<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityResourceAliasStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Availability\Dependency\AvailabilityEvents;
use Spryker\Zed\AvailabilityResourceAliasStorage\Communication\Plugin\Event\Listener\AvailabilityMappingResourceStorageListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AvailabilityResourceAliasStorage\Business\AvailabilityResourceAliasStorageFacade getFacade()
 * @method \Spryker\Zed\AvailabilityResourceAliasStorage\Communication\AvailabilityResourceAliasStorageCommunicationFactory getFactory()
 */
class AvailabilityMappingResourceStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addAvailabilityMappingResourceStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addAvailabilityMappingResourceStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(AvailabilityEvents::AVAILABILITY_ABSTRACT_PUBLISH, new AvailabilityMappingResourceStorageListener());
        $eventCollection->addListenerQueued(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_ABSTRACT_CREATE, new AvailabilityMappingResourceStorageListener());
        $eventCollection->addListenerQueued(AvailabilityEvents::ENTITY_SPY_AVAILABILITY_ABSTRACT_UPDATE, new AvailabilityMappingResourceStorageListener());
    }
}
