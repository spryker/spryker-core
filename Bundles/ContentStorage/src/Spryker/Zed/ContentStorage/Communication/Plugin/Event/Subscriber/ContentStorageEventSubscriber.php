<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Content\Dependency\ContentEvents;
use Spryker\Zed\ContentStorage\Communication\Plugin\Event\Listener\ContentStorageListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentStorage\Business\ContentStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ContentStorage\Communication\ContentStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentStorage\ContentStorageConfig getConfig()
 */
class ContentStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addContentCreateListener($eventCollection);
        $this->addContentUpdateListener($eventCollection);
        $this->addContentPublishListener($eventCollection);
        $this->addContentUnpublishListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addContentUpdateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ContentEvents::ENTITY_SPY_CONTENT_UPDATE, new ContentStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addContentCreateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ContentEvents::ENTITY_SPY_CONTENT_CREATE, new ContentStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addContentPublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ContentEvents::CONTENT_PUBLISH, new ContentStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addContentUnpublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ContentEvents::ENTITY_SPY_CONTENT_UNPUBLISH, new ContentStorageListener());
    }
}
