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
     * @inheritDoc
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(
            ShoppingListEvents::ENTITY_SPY_SHOPPING_LIST_CREATE,
            new ShoppingListStorageListener()
        );
        $eventCollection->addListenerQueued(
            ShoppingListEvents::ENTITY_SPY_SHOPPING_LIST_DELETE,
            new ShoppingListStorageListener()
        );
        $eventCollection->addListenerQueued(
            ShoppingListEvents::ENTITY_SPY_SHOPPING_LIST_COMPANY_USER_CREATE,
            new ShoppingListCompanyUserStorageListener()
        );
        $eventCollection->addListenerQueued(
            ShoppingListEvents::ENTITY_SPY_SHOPPING_LIST_COMPANY_USER_DELETE,
            new ShoppingListCompanyUserStorageListener()
        );
        $eventCollection->addListenerQueued(
            ShoppingListEvents::ENTITY_SPY_SHOPPING_LIST_COMPANY_BUSINESS_UNIT_CREATE,
            new ShoppingListCompanyBusinessUnitStorageListener()
        );
        $eventCollection->addListenerQueued(
            ShoppingListEvents::ENTITY_SPY_SHOPPING_LIST_COMPANY_BUSINESS_UNIT_UPDATE,
            new ShoppingListCompanyBusinessUnitStorageListener()
        );

        return $eventCollection;
    }
}
