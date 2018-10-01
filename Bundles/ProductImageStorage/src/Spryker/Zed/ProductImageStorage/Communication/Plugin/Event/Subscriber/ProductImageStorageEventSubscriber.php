<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageSetProductImageStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageSetStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductAbstractImageStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageSetProductImageStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageSetStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductConcreteImageStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductImageAbstractPublishStorageListener;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductImageConcretePublishStorageListener;

/**
 * @method \Spryker\Zed\ProductImageStorage\Communication\ProductImageStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductImageStorage\Business\ProductImageStorageFacadeInterface getFacade()
 */
class ProductImageStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductImageAbstractPublishStorageListener($eventCollection);
        $this->addProductImageAbstractUnpublishStorageListener($eventCollection);
        $this->addProductImageConcretePublishStorageListener($eventCollection);
        $this->addProductImageConcreteUnpublishStorageListener($eventCollection);
        $this->addProductAbstractImageUpdateStorageListener($eventCollection);
        $this->addProductConcreteImageUpdateStorageListener($eventCollection);
        $this->addProductAbstractImageDeleteStorageListener($eventCollection);
        $this->addProductConcreteImageDeleteStorageListener($eventCollection);
        $this->addProductAbstractImageSetCreateStorageListener($eventCollection);
        $this->addProductConcreteImageSetCreateStorageListener($eventCollection);
        $this->addProductAbstractImageSetUpdateStorageListener($eventCollection);
        $this->addProductConcreteImageSetUpdateStorageListener($eventCollection);
        $this->addProductAbstractImageSetDeleteStorageListener($eventCollection);
        $this->addProductConcreteImageSetDeleteStorageListener($eventCollection);
        $this->addProductAbstractImageSetProductImageUpdateStorageListener($eventCollection);
        $this->addProductConcreteImageSetProductImageUpdateStorageListener($eventCollection);
        $this->addProductAbstractImageSetProductImageDeleteStorageListener($eventCollection);
        $this->addProductConcreteImageSetProductImageDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductImageAbstractPublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_PUBLISH, new ProductImageAbstractPublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductImageAbstractUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_UNPUBLISH, new ProductImageAbstractPublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductImageConcretePublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::PRODUCT_IMAGE_PRODUCT_CONCRETE_PUBLISH, new ProductImageConcretePublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductImageConcreteUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::PRODUCT_IMAGE_PRODUCT_CONCRETE_UNPUBLISH, new ProductImageConcretePublishStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractImageUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE, new ProductAbstractImageStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractImageDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_DELETE, new ProductAbstractImageStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteImageUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE, new ProductConcreteImageStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteImageDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_DELETE, new ProductConcreteImageStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractImageSetCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE, new ProductAbstractImageSetStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractImageSetUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_UPDATE, new ProductAbstractImageSetStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractImageSetDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_DELETE, new ProductAbstractImageSetStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteImageSetCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE, new ProductConcreteImageSetStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteImageSetUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_UPDATE, new ProductConcreteImageSetStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteImageSetDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_DELETE, new ProductConcreteImageSetStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractImageSetProductImageUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE, new ProductAbstractImageSetProductImageStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractImageSetProductImageDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_DELETE, new ProductAbstractImageSetProductImageStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteImageSetProductImageUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE, new ProductConcreteImageSetProductImageStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteImageSetProductImageDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_DELETE, new ProductConcreteImageSetProductImageStorageListener());
    }
}
