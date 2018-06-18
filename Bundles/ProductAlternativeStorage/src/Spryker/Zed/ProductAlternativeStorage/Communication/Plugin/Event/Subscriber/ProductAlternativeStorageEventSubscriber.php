<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductAlternative\Dependency\ProductAlternativeEvents;
use Spryker\Zed\ProductAlternativeStorage\Communication\Plugin\Event\Listener\ProductAlternativeStorageListener;
use Spryker\Zed\ProductAlternativeStorage\Communication\Plugin\Event\Listener\ProductReplacementStorageListener;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\Communication\ProductAlternativeStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeStorageFacadeInterface getFacade()
 */
class ProductAlternativeStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductAlternativePublishListener($eventCollection);
        $this->addProductAlternativeCreateListener($eventCollection);
        $this->addProductAlternativeUpdateListener($eventCollection);
        $this->addProductAlternativeDeleteListener($eventCollection);
        $this->addProductReplacementPublishListener($eventCollection);
        $this->addProductReplacementCreateListener($eventCollection);
        $this->addProductReplacementUpdateListener($eventCollection);
        $this->addProductReplacementDeleteListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAlternativePublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection
            ->addListenerQueued(ProductAlternativeEvents::PRODUCT_ALTERNATIVE_PUBLISH, new ProductAlternativeStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAlternativeCreateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection
            ->addListenerQueued(ProductAlternativeEvents::ENTITY_SPY_PRODUCT_ALTERNATIVE_CREATE, new ProductAlternativeStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAlternativeUpdateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection
            ->addListenerQueued(ProductAlternativeEvents::ENTITY_SPY_PRODUCT_ALTERNATIVE_UPDATE, new ProductAlternativeStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAlternativeDeleteListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection
            ->addListenerQueued(ProductAlternativeEvents::ENTITY_SPY_PRODUCT_ALTERNATIVE_DELETE, new ProductAlternativeStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductReplacementPublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection
            ->addListenerQueued(ProductAlternativeEvents::PRODUCT_REPLACEMENT_PUBLISH, new ProductReplacementStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductReplacementCreateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection
            ->addListenerQueued(ProductAlternativeEvents::ENTITY_SPY_PRODUCT_REPLACEMENT_CREATE, new ProductReplacementStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductReplacementUpdateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection
            ->addListenerQueued(ProductAlternativeEvents::ENTITY_SPY_PRODUCT_REPLACEMENT_UPDATE, new ProductReplacementStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductReplacementDeleteListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection
            ->addListenerQueued(ProductAlternativeEvents::ENTITY_SPY_PRODUCT_REPLACEMENT_DELETE, new ProductReplacementStorageListener());
    }
}
