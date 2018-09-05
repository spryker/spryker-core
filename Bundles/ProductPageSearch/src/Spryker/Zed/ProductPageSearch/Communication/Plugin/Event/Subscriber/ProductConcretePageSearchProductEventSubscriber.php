<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductConcretePageSearchProductListener;

/**
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacade getFacade()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class ProductConcretePageSearchProductEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductConcretePageProductConcreteCreateSearchListener($eventCollection);
        $this->addProductConcretePageProductConcreteUpdateSearchListener($eventCollection);
        $this->addProductConcretePageProductConcreteDeleteSearchListener($eventCollection);

        $this->addProductConcretePageProductConcretePublishSearchListener($eventCollection);
        $this->addProductConcretePageProductConcreteUnpublishSearchListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcretePageProductConcreteCreateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_CREATE, new ProductConcretePageSearchProductListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcretePageProductConcreteUpdateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_UPDATE, new ProductConcretePageSearchProductListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcretePageProductConcreteDeleteSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_DELETE, new ProductConcretePageSearchProductListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcretePageProductConcretePublishSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::PRODUCT_CONCRETE_PUBLISH, new ProductConcretePageSearchProductListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcretePageProductConcreteUnpublishSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::PRODUCT_CONCRETE_UNPUBLISH, new ProductConcretePageSearchProductListener());
    }
}
