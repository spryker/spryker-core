<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Cms\Dependency\CmsEvents;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageStorageListener;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageUrlStorageListener;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageVersionStorageListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * @method \Spryker\Zed\CmsStorage\Communication\CmsStorageCommunicationFactory getFactory()
 */
class CmsStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
            ->addListenerQueued(CmsEvents::CMS_VERSION_PUBLISH, new CmsPageStorageListener())
            ->addListenerQueued(CmsEvents::CMS_VERSION_UNPUBLISH, new CmsPageStorageListener())
            ->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_PAGE_CREATE, new CmsPageStorageListener())
            ->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_PAGE_UPDATE, new CmsPageStorageListener())
            ->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_PAGE_DELETE, new CmsPageStorageListener())
            ->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_VERSION_CREATE, new CmsPageVersionStorageListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_CREATE, new CmsPageUrlStorageListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_UPDATE, new CmsPageUrlStorageListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_DELETE, new CmsPageUrlStorageListener());

        return $eventCollection;
    }
}
