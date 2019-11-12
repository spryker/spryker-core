<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\CmsSlot\Dependency\CmsSlotEvents;
use Spryker\Zed\CmsSlotStorage\Communication\Plugin\Event\Listener\CmsSlotStoragePublishListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsSlotStorage\CmsSlotStorageConfig getConfig()
 * @method \Spryker\Zed\CmsSlotStorage\Business\CmsSlotStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsSlotStorage\Communication\CmsSlotStorageCommunicationFactory getFactory()
 */
class CmsSlotStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $this->addCmsSlotPublishStorageListener($eventCollection);
        $this->addCmsSlotCreateStorageListener($eventCollection);
        $this->addCmsSlotUpdateStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsSlotPublishStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(CmsSlotEvents::CMS_SLOT_PUBLISH, new CmsSlotStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsSlotCreateStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(CmsSlotEvents::ENTITY_SPY_CMS_SLOT_CREATE, new CmsSlotStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsSlotUpdateStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(CmsSlotEvents::ENTITY_SPY_CMS_SLOT_UPDATE, new CmsSlotStoragePublishListener());
    }
}
