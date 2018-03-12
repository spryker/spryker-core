<?php

namespace Spryker\Zed\CustomerAccessStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\CustomerAccess\Dependency\CustomerAccessEvents;
use Spryker\Zed\CustomerAccessStorage\Communication\Plugin\Event\Listener\CustomerAccessStorageListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class CustomerAccessStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addUnauthenticatedCustomerAccessCreateListener($eventCollection);
        $this->addUnauthenticatedCustomerAccessUpdateListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addUnauthenticatedCustomerAccessUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CustomerAccessEvents::ENTITY_SPY_UNAUTHENTICATED_CUSTOMER_ACCESS_UPDATE, new CustomerAccessStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addUnauthenticatedCustomerAccessCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CustomerAccessEvents::UNAUTHENTICATED_CUSTOMER_ACCESS_ABSTRACT_PUBLISH, new CustomerAccessStorageListener());
    }
}
