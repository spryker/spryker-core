<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Navigation\Dependency\NavigationEvents;
use Spryker\Zed\NavigationStorage\Communication\Plugin\Event\Listener\NavigationNodeLocalizedAttributeStorageListener;
use Spryker\Zed\NavigationStorage\Communication\Plugin\Event\Listener\NavigationNodeStorageListener;
use Spryker\Zed\NavigationStorage\Communication\Plugin\Event\Listener\NavigationStorageListener;
use Spryker\Zed\NavigationStorage\Communication\Plugin\Event\Listener\NavigationUrlRelationStorageListener;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * @method \Spryker\Zed\NavigationStorage\Communication\NavigationStorageCommunicationFactory getFactory()
 */
class NavigationStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    const QUEUE_POOL_NAME_SHARED = 'sharedPool';

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
            ->addListenerQueued(NavigationEvents::NAVIGATION_KEY_PUBLISH, new NavigationStorageListener())
            ->addListenerQueued(NavigationEvents::NAVIGATION_KEY_UNPUBLISH, new NavigationStorageListener())
            ->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_CREATE, new NavigationStorageListener())
            ->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_UPDATE, new NavigationStorageListener())
            ->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_DELETE, new NavigationStorageListener())
            ->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_NODE_CREATE, new NavigationNodeStorageListener())
            ->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_NODE_UPDATE, new NavigationNodeStorageListener())
            ->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_NODE_DELETE, new NavigationNodeStorageListener())
            ->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_NODE_LOCALIZED_ATTRIBUTE_CREATE, new NavigationNodeLocalizedAttributeStorageListener())
            ->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_NODE_LOCALIZED_ATTRIBUTE_UPDATE, new NavigationNodeLocalizedAttributeStorageListener())
            ->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_NODE_LOCALIZED_ATTRIBUTE_DELETE, new NavigationNodeLocalizedAttributeStorageListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_UPDATE, new NavigationUrlRelationStorageListener(), static::QUEUE_POOL_NAME_SHARED);

        return $eventCollection;
    }
}
