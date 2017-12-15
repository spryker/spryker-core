<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Cms\Dependency\CmsEvents;
use Spryker\Zed\CmsPageSearch\Communication\Plugin\Event\Listener\CmsPageSearchListener;
use Spryker\Zed\CmsPageSearch\Communication\Plugin\Event\Listener\CmsPageUrlSearchListener;
use Spryker\Zed\CmsPageSearch\Communication\Plugin\Event\Listener\CmsPageVersionSearchListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * @method \Spryker\Zed\CmsPageSearch\Communication\CmsPageSearchCommunicationFactory getFactory()
 */
class CmsPageSearchEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
            ->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_PAGE_CREATE, new CmsPageSearchListener())
            ->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_PAGE_UPDATE, new CmsPageSearchListener())
            ->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_PAGE_DELETE, new CmsPageSearchListener())
            ->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_VERSION_CREATE, new CmsPageVersionSearchListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_CREATE, new CmsPageUrlSearchListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_UPDATE, new CmsPageUrlSearchListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_DELETE, new CmsPageUrlSearchListener());

        return $eventCollection;
    }

}
