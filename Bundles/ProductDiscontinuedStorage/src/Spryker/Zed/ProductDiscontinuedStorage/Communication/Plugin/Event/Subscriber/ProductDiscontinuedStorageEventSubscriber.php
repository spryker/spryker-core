<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductDiscontinued\Dependency\ProductDiscontinuedEvents;
use Spryker\Zed\ProductDiscontinuedStorage\Communication\Plugin\Event\Listener\ProductDiscontinuedNoteStorageListener;
use Spryker\Zed\ProductDiscontinuedStorage\Communication\Plugin\Event\Listener\ProductDiscontinuedStorageListener;

/**
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Communication\ProductDiscontinuedStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedStorageFacadeInterface getFacade()
 */
class ProductDiscontinuedStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductDiscontinuedPublishListener($eventCollection);
        $this->addProductDiscontinuedUnpublishListener($eventCollection);
        $this->addProductDiscontinuedCreateListener($eventCollection);
        $this->addProductDiscontinuedUpdateListener($eventCollection);
        $this->addProductDiscontinuedDeleteListener($eventCollection);
        $this->addProductDiscontinuedNoteCreateListener($eventCollection);
        $this->addProductDiscontinuedNoteUpdateListener($eventCollection);
        $this->addProductDiscontinuedNoteDeleteListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductDiscontinuedPublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection
            ->addListenerQueued(ProductDiscontinuedEvents::PRODUCT_DISCONTINUED_PUBLISH, new ProductDiscontinuedStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductDiscontinuedUnpublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection
            ->addListenerQueued(ProductDiscontinuedEvents::PRODUCT_DISCONTINUED_UNPUBLISH, new ProductDiscontinuedStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductDiscontinuedCreateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection
            ->addListenerQueued(ProductDiscontinuedEvents::ENTITY_SPY_PRODUCT_DISCONTINUED_CREATE, new ProductDiscontinuedStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductDiscontinuedUpdateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection
            ->addListenerQueued(ProductDiscontinuedEvents::ENTITY_SPY_PRODUCT_DISCONTINUED_UPDATE, new ProductDiscontinuedStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductDiscontinuedDeleteListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection
            ->addListenerQueued(ProductDiscontinuedEvents::ENTITY_SPY_PRODUCT_DISCONTINUED_DELETE, new ProductDiscontinuedStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductDiscontinuedNoteCreateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection
            ->addListenerQueued(ProductDiscontinuedEvents::ENTITY_SPY_PRODUCT_DISCONTINUED_NOTE_CREATE, new ProductDiscontinuedNoteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductDiscontinuedNoteUpdateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection
            ->addListenerQueued(ProductDiscontinuedEvents::ENTITY_SPY_PRODUCT_DISCONTINUED_NOTE_UPDATE, new ProductDiscontinuedNoteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductDiscontinuedNoteDeleteListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection
            ->addListenerQueued(ProductDiscontinuedEvents::ENTITY_SPY_PRODUCT_DISCONTINUED_NOTE_DELETE, new ProductDiscontinuedNoteStorageListener());
    }
}
