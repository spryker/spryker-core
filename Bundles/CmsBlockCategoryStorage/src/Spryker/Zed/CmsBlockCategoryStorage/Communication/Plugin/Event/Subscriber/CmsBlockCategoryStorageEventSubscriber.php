<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\CmsBlockCategoryConnector\Dependency\CmsBlockCategoryConnectorEvents;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryConnectorEntityStoragePublishListener;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryConnectorEntityStorageUnpublishListener;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryConnectorStoragePublishListener;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryConnectorStorageUnpublishListener;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryPositionStorageListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Communication\CmsBlockCategoryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlockCategoryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageConfig getConfig()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainerInterface getQueryContainer()
 */
class CmsBlockCategoryStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addCmsBlockCategoryConnectorPublishStorageListener($eventCollection);
        $this->addCmsBlockCategoryConnectorUnpublishStorageListener($eventCollection);
        $this->addCmsBlockCategoryConnectorCreateStorageListener($eventCollection);
        $this->addCmsBlockCategoryConnectorUpdateStorageListener($eventCollection);
        $this->addCmsBlockCategoryConnectorDeleteStorageListener($eventCollection);
        $this->addCmsBlockCategoryPositionCreateStorageListener($eventCollection);
        $this->addCmsBlockCategoryPositionUpdateStorageListener($eventCollection);
        $this->addCmsBlockCategoryPositionDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockCategoryConnectorPublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockCategoryConnectorEvents::CMS_BLOCK_CATEGORY_CONNECTOR_PUBLISH, new CmsBlockCategoryConnectorStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockCategoryConnectorUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockCategoryConnectorEvents::CMS_BLOCK_CATEGORY_CONNECTOR_UNPUBLISH, new CmsBlockCategoryConnectorStorageUnpublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockCategoryConnectorCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_CONNECTOR_CREATE, new CmsBlockCategoryConnectorEntityStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockCategoryConnectorUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_CONNECTOR_UPDATE, new CmsBlockCategoryConnectorEntityStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockCategoryConnectorDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_CONNECTOR_DELETE, new CmsBlockCategoryConnectorEntityStorageUnpublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockCategoryPositionCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_POSITION_CREATE, new CmsBlockCategoryPositionStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockCategoryPositionUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_POSITION_UPDATE, new CmsBlockCategoryPositionStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockCategoryPositionDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_POSITION_DELETE, new CmsBlockCategoryPositionStorageListener());
    }
}
