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
        $eventCollection
            ->addListenerQueued(CmsBlockProductConnectorEvents::CMS_BLOCK_PRODUCT_CONNECTOR_PUBLISH, new CmsBlockProductConnectorPublishStorageListener())
            ->addListenerQueued(CmsBlockProductConnectorEvents::CMS_BLOCK_PRODUCT_CONNECTOR_UNPUBLISH, new CmsBlockProductConnectorPublishStorageListener())
            ->addListenerQueued(CmsBlockProductConnectorEvents::ENTITY_SPY_CMS_BLOCK_PRODUCT_CONNECTOR_CREATE, new CmsBlockProductConnectorStorageListener())
            ->addListenerQueued(CmsBlockProductConnectorEvents::ENTITY_SPY_CMS_BLOCK_PRODUCT_CONNECTOR_UPDATE, new CmsBlockProductConnectorStorageListener())
            ->addListenerQueued(CmsBlockProductConnectorEvents::ENTITY_SPY_CMS_BLOCK_PRODUCT_CONNECTOR_DELETE, new CmsBlockProductConnectorStorageListener());

        return $eventCollection;
    }
}
