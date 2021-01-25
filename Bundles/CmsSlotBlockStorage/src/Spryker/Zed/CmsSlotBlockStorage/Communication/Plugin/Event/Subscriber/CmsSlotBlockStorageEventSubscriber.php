<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\CmsSlotBlock\Dependency\CmsSlotBlockEvents;
use Spryker\Zed\CmsSlotBlockStorage\Communication\Plugin\Event\Listener\CmsSlotBlockStoragePublishListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsSlotBlockStorage\Communication\CmsSlotBlockStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsSlotBlockStorage\Business\CmsSlotBlockStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsSlotBlockStorage\CmsSlotBlockStorageConfig getConfig()
 */
class CmsSlotBlockStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addCmsSlotBlockPublishStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCmsSlotBlockPublishStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(CmsSlotBlockEvents::CMS_SLOT_BLOCK_PUBLISH, new CmsSlotBlockStoragePublishListener(), 0, null, $this->getConfig()->getEventQueueName());
    }
}
