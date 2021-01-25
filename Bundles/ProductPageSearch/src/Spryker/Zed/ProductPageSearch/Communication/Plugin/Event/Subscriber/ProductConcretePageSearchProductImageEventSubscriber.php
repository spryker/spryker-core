<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductConcretePageSearchProductImageListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductConcretePageSearchProductImageProductConcreteListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductConcretePageSearchProductImageSetListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductConcretePageSearchProductImageSetToProductImageListener;

/**
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 */
class ProductConcretePageSearchProductImageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductConcretePageSearchProductImageProductConcretePublishListener($eventCollection);
        $this->addProductConcretePageSearchProductImageUpdateListener($eventCollection);
        $this->addProductConcretePageSearchProductImageSetToProductImageCreateListener($eventCollection);
        $this->addProductConcretePageSearchProductImageSetToProductImageUpdateListener($eventCollection);
        $this->addProductConcretePageSearchProductImageSetToProductImageDeleteListener($eventCollection);
        $this->addProductConcretePageSearchProductImageSetDeleteListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcretePageSearchProductImageProductConcretePublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductImageEvents::PRODUCT_IMAGE_PRODUCT_CONCRETE_PUBLISH, new ProductConcretePageSearchProductImageProductConcreteListener(), 0, null, $this->getConfig()->getProductConcretePageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcretePageSearchProductImageUpdateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE, new ProductConcretePageSearchProductImageListener(), 0, null, $this->getConfig()->getProductConcretePageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcretePageSearchProductImageSetToProductImageCreateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_CREATE, new ProductConcretePageSearchProductImageSetToProductImageListener(), 0, null, $this->getConfig()->getProductConcretePageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcretePageSearchProductImageSetToProductImageUpdateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE, new ProductConcretePageSearchProductImageSetToProductImageListener(), 0, null, $this->getConfig()->getProductConcretePageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcretePageSearchProductImageSetToProductImageDeleteListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_DELETE, new ProductConcretePageSearchProductImageSetToProductImageListener(), 0, null, $this->getConfig()->getProductConcretePageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcretePageSearchProductImageSetDeleteListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_DELETE, new ProductConcretePageSearchProductImageSetListener(), 0, null, $this->getConfig()->getProductConcretePageEventQueueName());
    }
}
