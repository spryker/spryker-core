<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Url\Dependency\UrlEvents;
use Spryker\Zed\UrlStorage\Communication\Plugin\Event\Listener\RedirectStorageListener;
use Spryker\Zed\UrlStorage\Communication\Plugin\Event\Listener\UrlStorageListener;

/**
 * @method \Spryker\Zed\UrlStorage\Communication\UrlStorageCommunicationFactory getFactory()
 */
class UrlStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_CREATE, new UrlStorageListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_UPDATE, new UrlStorageListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_DELETE, new UrlStorageListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_REDIRECT_CREATE, new RedirectStorageListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_REDIRECT_UPDATE, new RedirectStorageListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_REDIRECT_DELETE, new RedirectStorageListener());

        return $eventCollection;
    }

}
