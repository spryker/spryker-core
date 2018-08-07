<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListStorage\Communication\Plugin\Event\Listener\ShoppingListCompanyBusinessUnitStorageListener;
use Spryker\Zed\ShoppingListStorage\Communication\Plugin\Event\Listener\ShoppingListCompanyUserStorageListener;
use Spryker\Zed\ShoppingListStorage\Communication\Plugin\Event\Listener\ShoppingListStorageListener;
use Spryker\Zed\ShoppingListStorage\Dependency\ShoppingListEvents;

/**
 * @method \Spryker\Zed\ShoppingListStorage\Business\ShoppingListStorageFacade getFacade()
 * @method \Spryker\Zed\ShoppingListStorage\Communication\ShoppingListStorageCommunicationFactory getFactory()
 */
class ShoppingListStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addShoppingListCreateListener($eventCollection);
        $this->addShoppingListDeleteListener($eventCollection);
        $this->addShoppingListCompanyUserCreateListener($eventCollection);
        $this->addShoppingListCompanyUserDeleteListener($eventCollection);
        $this->addShoppingListCompanyBusinerrUnitCreateListener($eventCollection);
        $this->addShoppingListCompanyBusinerrUnitUpdateListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addShoppingListCreateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ShoppingListEvents::ENTITY_SPY_SHOPPING_LIST_CREATE,
            new ShoppingListStorageListener()
        );
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addShoppingListDeleteListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ShoppingListEvents::ENTITY_SPY_SHOPPING_LIST_DELETE,
            new ShoppingListStorageListener()
        );
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addShoppingListCompanyUserCreateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ShoppingListEvents::ENTITY_SPY_SHOPPING_LIST_COMPANY_USER_CREATE,
            new ShoppingListCompanyUserStorageListener()
        );
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addShoppingListCompanyUserDeleteListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ShoppingListEvents::ENTITY_SPY_SHOPPING_LIST_COMPANY_USER_DELETE,
            new ShoppingListCompanyUserStorageListener()
        );
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addShoppingListCompanyBusinerrUnitCreateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ShoppingListEvents::ENTITY_SPY_SHOPPING_LIST_COMPANY_BUSINESS_UNIT_CREATE,
            new ShoppingListCompanyBusinessUnitStorageListener()
        );
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addShoppingListCompanyBusinerrUnitUpdateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ShoppingListEvents::ENTITY_SPY_SHOPPING_LIST_COMPANY_BUSINESS_UNIT_UPDATE,
            new ShoppingListCompanyBusinessUnitStorageListener()
        );
    }
}
