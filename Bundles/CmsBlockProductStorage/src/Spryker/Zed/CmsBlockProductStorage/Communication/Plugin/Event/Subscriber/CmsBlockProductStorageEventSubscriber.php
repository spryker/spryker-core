<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\CmsBlockProductConnector\Dependency\CmsBlockProductConnectorEvents;
use Spryker\Zed\CmsBlockProductStorage\Communication\Plugin\Event\Listener\CmsBlockProductConnectorPublishStorageListener;
use Spryker\Zed\CmsBlockProductStorage\Communication\Plugin\Event\Listener\CmsBlockProductConnectorStorageListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockProductStorage\Communication\CmsBlockProductStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockProductStorage\Business\CmsBlockProductStorageFacadeInterface getFacade()
 */
class CmsBlockProductStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addCmsBlockProductConnectorPublishStorageListener($eventCollection);
        $this->addCmsBlockProductConnectorUnpublishStorageListener($eventCollection);
        $this->addCmsBlockProductConnectorCreateStorageListener($eventCollection);
        $this->addCmsBlockProductConnectorUpdateStorageListener($eventCollection);
        $this->addCmsBlockProductConnectorDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockProductConnectorPublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockProductConnectorEvents::CMS_BLOCK_PRODUCT_CONNECTOR_PUBLISH, new CmsBlockProductConnectorPublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockProductConnectorUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockProductConnectorEvents::CMS_BLOCK_PRODUCT_CONNECTOR_UNPUBLISH, new CmsBlockProductConnectorPublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockProductConnectorCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockProductConnectorEvents::ENTITY_SPY_CMS_BLOCK_PRODUCT_CONNECTOR_CREATE, new CmsBlockProductConnectorStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockProductConnectorUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockProductConnectorEvents::ENTITY_SPY_CMS_BLOCK_PRODUCT_CONNECTOR_UPDATE, new CmsBlockProductConnectorStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsBlockProductConnectorDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CmsBlockProductConnectorEvents::ENTITY_SPY_CMS_BLOCK_PRODUCT_CONNECTOR_DELETE, new CmsBlockProductConnectorStorageListener());
    }
}
