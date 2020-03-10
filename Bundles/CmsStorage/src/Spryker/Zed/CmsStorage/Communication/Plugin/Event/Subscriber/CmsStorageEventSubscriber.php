<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Cms\Dependency\CmsEvents;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageStoragePublishListener;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageStorageUnpublishListener;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageStoreStorageListener;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageUrlStoragePublishListener;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageUrlStorageUnpublishListener;
use Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageVersionStorageListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * @method \Spryker\Zed\CmsStorage\Communication\CmsStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsStorage\Business\CmsStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsStorage\CmsStorageConfig getConfig()
 * @method \Spryker\Zed\CmsStorage\Persistence\CmsStorageQueryContainerInterface getQueryContainer()
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
        $this->addCmsPagePublishStorageListener($eventCollection);
        $this->addCmsPageUnpublishStorageListener($eventCollection);
        $this->addCmsPageCreateStorageListener($eventCollection);
        $this->addCmsPageUpdateStorageListener($eventCollection);
        $this->addCmsPageDeleteStorageListener($eventCollection);
        $this->addCmsPageVersionCreateStorageListener($eventCollection);
        $this->addCmsPageUrlCreateStorageListener($eventCollection);
        $this->addCmsPageUrlUpdateStorageListener($eventCollection);
        $this->addCmsPageUrlDeleteStorageListener($eventCollection);
        $this->addCmsPageStoreCreateStorageListener($eventCollection);
        $this->addCmsPageStoreUpdateStorageListener($eventCollection);
        $this->addCmsPageStoreDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPagePublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsEvents::CMS_VERSION_PUBLISH, new CmsPageStoragePublishListener(), 0, null, $this->getConfig()->getCmsPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsEvents::CMS_VERSION_UNPUBLISH, new CmsPageStorageUnpublishListener(), 0, null, $this->getConfig()->getCmsPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_PAGE_CREATE, new CmsPageStoragePublishListener(), 0, null, $this->getConfig()->getCmsPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_PAGE_UPDATE, new CmsPageStoragePublishListener(), 0, null, $this->getConfig()->getCmsPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_PAGE_DELETE, new CmsPageStorageUnpublishListener(), 0, null, $this->getConfig()->getCmsPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageVersionCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_VERSION_CREATE, new CmsPageVersionStorageListener(), 0, null, $this->getConfig()->getCmsPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageUrlCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(UrlEvents::ENTITY_SPY_URL_CREATE, new CmsPageUrlStoragePublishListener(), 0, null, $this->getConfig()->getCmsPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageUrlUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(UrlEvents::ENTITY_SPY_URL_UPDATE, new CmsPageUrlStoragePublishListener(), 0, null, $this->getConfig()->getCmsPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageUrlDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(UrlEvents::ENTITY_SPY_URL_DELETE, new CmsPageUrlStorageUnpublishListener(), 0, null, $this->getConfig()->getCmsPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageStoreCreateStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_STORE_CREATE, new CmsPageStoreStorageListener(), 0, null, $this->getConfig()->getCmsPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageStoreUpdateStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_STORE_UPDATE, new CmsPageStoreStorageListener(), 0, null, $this->getConfig()->getCmsPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsPageStoreDeleteStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(CmsEvents::ENTITY_SPY_CMS_STORE_DELETE, new CmsPageStoreStorageListener(), 0, null, $this->getConfig()->getCmsPageEventQueueName());
    }
}
