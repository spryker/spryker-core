<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\TaxProductStorage\Communication\Plugin\Event\Listener\TaxProductStoragePublishListener;
use Spryker\Zed\TaxProductStorage\Communication\Plugin\Event\Listener\TaxProductStorageUnpublishListener;

/**
 * @method \Spryker\Zed\TaxProductStorage\TaxProductStorageConfig getConfig()
 * @method \Spryker\Zed\TaxProductStorage\Business\TaxProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\TaxProductStorage\Communication\TaxProductStorageCommunicationFactory getFactory()
 */
class TaxProductStorageSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductAbstractPublishListener($eventCollection);
        $this->addProductAbstractUnpublishListener($eventCollection);
        $this->addProductAbstractCreateListener($eventCollection);
        $this->addProductAbstractUpdateListener($eventCollection);
        $this->addProductAbstractDeleteListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractPublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, new TaxProductStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractUnpublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::PRODUCT_ABSTRACT_UNPUBLISH, new TaxProductStorageUnpublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractCreateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_CREATE, new TaxProductStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractUpdateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE, new TaxProductStoragePublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractDeleteListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_DELETE, new TaxProductStorageUnpublishListener());
    }
}
