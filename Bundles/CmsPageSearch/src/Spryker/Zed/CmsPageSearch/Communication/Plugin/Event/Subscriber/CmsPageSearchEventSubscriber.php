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
 * @method \Spryker\Zed\CmsPageSearch\Business\CmsPageSearchFacadeInterface getFacade()
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
        $this->addCmsPagePublishSearchListener($eventCollection);
        $this->addCmsPageUnpublishSearchListener($eventCollection);
        $this->addCmsPageCreateSearchListener($eventCollection);
        $this->addCmsPageUpdateSearchListener($eventCollection);
        $this->addCmsPageDeleteSearchListener($eventCollection);
        $this->addCmsPageVersionCreateSearchListener($eventCollection);
        $this->addCmsPageUrlCreateSearchListener($eventCollection);
        $this->addCmsPageUrlUpdateSearchListener($eventCollection);
        $this->addCmsPageUrlDeleteSearchListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPagePublishSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsEvents::CMS_VERSION_PUBLISH, new CmsPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageUnpublishSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsEvents::CMS_VERSION_UNPUBLISH, new CmsPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_PAGE_CREATE, new CmsPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_PAGE_UPDATE, new CmsPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_PAGE_DELETE, new CmsPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageVersionCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_VERSION_CREATE, new CmsPageVersionSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageUrlCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(UrlEvents::ENTITY_SPY_URL_CREATE, new CmsPageUrlSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageUrlUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(UrlEvents::ENTITY_SPY_URL_UPDATE, new CmsPageUrlSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageUrlDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(UrlEvents::ENTITY_SPY_URL_DELETE, new CmsPageUrlSearchListener());
    }
}
