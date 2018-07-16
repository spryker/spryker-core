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
 * @method \Spryker\Zed\NavigationStorage\Business\NavigationStorageFacadeInterface getFacade()
 */
class NavigationStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addNavigationPublishStorageListener($eventCollection);
        $this->addNavigationUnpublishStorageListener($eventCollection);
        $this->addNavigationCreateStorageListener($eventCollection);
        $this->addNavigationUpdateStorageListener($eventCollection);
        $this->addNavigationDeleteStorageListener($eventCollection);
        $this->addNavigationNodeCreateStorageListener($eventCollection);
        $this->addNavigationNodeUpdateStorageListener($eventCollection);
        $this->addNavigationNodeDeleteStorageListener($eventCollection);
        $this->addNavigationNodeLocalizedAttributeCreateStorageListener($eventCollection);
        $this->addNavigationNodeLocalizedAttributeUpdateStorageListener($eventCollection);
        $this->addNavigationNodeLocalizedAttributeDeleteStorageListener($eventCollection);
        $this->addNavigationUrlRelationUpdateStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addNavigationPublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(NavigationEvents::NAVIGATION_KEY_PUBLISH, new NavigationStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addNavigationUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(NavigationEvents::NAVIGATION_KEY_UNPUBLISH, new NavigationStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addNavigationCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_CREATE, new NavigationStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addNavigationUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_UPDATE, new NavigationStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addNavigationDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_DELETE, new NavigationStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addNavigationNodeCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_NODE_CREATE, new NavigationNodeStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addNavigationNodeUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_NODE_UPDATE, new NavigationNodeStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addNavigationNodeDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_NODE_DELETE, new NavigationNodeStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addNavigationNodeLocalizedAttributeCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_NODE_LOCALIZED_ATTRIBUTE_CREATE, new NavigationNodeLocalizedAttributeStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addNavigationNodeLocalizedAttributeUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_NODE_LOCALIZED_ATTRIBUTE_UPDATE, new NavigationNodeLocalizedAttributeStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addNavigationNodeLocalizedAttributeDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(NavigationEvents::ENTITY_SPY_NAVIGATION_NODE_LOCALIZED_ATTRIBUTE_DELETE, new NavigationNodeLocalizedAttributeStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addNavigationUrlRelationUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(UrlEvents::ENTITY_SPY_URL_UPDATE, new NavigationUrlRelationStorageListener());
    }
}
