<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\CustomerAccess\Dependency\CustomerAccessEvents;
use Spryker\Zed\CustomerAccessStorage\Communication\Plugin\Event\Listener\CustomerAccessStorageListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CustomerAccessStorage\Business\CustomerAccessStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerAccessStorage\Communication\CustomerAccessStorageCommunicationFactory getFactory()
 */
class CustomerAccessStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $this->addUnauthenticatedCustomerAccessCreateListener($eventCollection);
        $this->addUnauthenticatedCustomerAccessUpdateListener($eventCollection);
        $this->addUnauthenticatedCustomerAccessDeleteListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addUnauthenticatedCustomerAccessUpdateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(CustomerAccessEvents::ENTITY_SPY_UNAUTHENTICATED_CUSTOMER_ACCESS_UPDATE, new CustomerAccessStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addUnauthenticatedCustomerAccessCreateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(CustomerAccessEvents::ENTITY_SPY_UNAUTHENTICATED_CUSTOMER_ACCESS_CREATE, new CustomerAccessStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addUnauthenticatedCustomerAccessDeleteListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(CustomerAccessEvents::ENTITY_SPY_UNAUTHENTICATED_CUSTOMER_ACCESS_DELETE, new CustomerAccessStorageListener());
    }
}
