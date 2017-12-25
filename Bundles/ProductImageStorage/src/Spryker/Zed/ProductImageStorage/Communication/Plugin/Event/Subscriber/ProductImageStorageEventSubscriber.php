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
        $eventCollection
            ->addListenerQueued(ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_PUBLISH, new ProductImageAbstractPublishStorageListener())
            ->addListenerQueued(ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_UNPUBLISH, new ProductImageAbstractPublishStorageListener())
            ->addListenerQueued(ProductImageEvents::PRODUCT_IMAGE_PRODUCT_CONCRETE_PUBLISH, new ProductImageConcretePublishStorageListener())
            ->addListenerQueued(ProductImageEvents::PRODUCT_IMAGE_PRODUCT_CONCRETE_UNPUBLISH, new ProductImageConcretePublishStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE, new ProductAbstractImageStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE, new ProductConcreteImageStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_DELETE, new ProductAbstractImageStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_DELETE, new ProductConcreteImageStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE, new ProductAbstractImageSetStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE, new ProductConcreteImageSetStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_UPDATE, new ProductAbstractImageSetStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_UPDATE, new ProductConcreteImageSetStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_DELETE, new ProductAbstractImageSetStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_DELETE, new ProductConcreteImageSetStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE, new ProductAbstractImageSetProductImageStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE, new ProductConcreteImageSetProductImageStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_DELETE, new ProductAbstractImageSetProductImageStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_DELETE, new ProductConcreteImageSetProductImageStorageListener());

        return $eventCollection;
    }

}
