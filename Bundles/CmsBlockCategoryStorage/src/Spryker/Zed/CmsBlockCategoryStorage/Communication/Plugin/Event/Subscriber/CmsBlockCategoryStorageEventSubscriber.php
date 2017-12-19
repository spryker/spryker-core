<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\CmsBlockCategoryConnector\Dependency\CmsBlockCategoryConnectorEvents;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryConnectorStorageListener;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryPositionStorageListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Communication\CmsBlockCategoryStorageCommunicationFactory getFactory()
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

        $eventCollection
            ->addListenerQueued(CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_CONNECTOR_CREATE, new CmsBlockCategoryConnectorStorageListener())
            ->addListenerQueued(CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_CONNECTOR_UPDATE, new CmsBlockCategoryConnectorStorageListener())
            ->addListenerQueued(CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_CONNECTOR_DELETE, new CmsBlockCategoryConnectorStorageListener())
            ->addListenerQueued(CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_POSITION_CREATE, new CmsBlockCategoryPositionStorageListener())
            ->addListenerQueued(CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_POSITION_UPDATE, new CmsBlockCategoryPositionStorageListener())
            ->addListenerQueued(CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_POSITION_DELETE, new CmsBlockCategoryPositionStorageListener());

        return $eventCollection;
    }
}
