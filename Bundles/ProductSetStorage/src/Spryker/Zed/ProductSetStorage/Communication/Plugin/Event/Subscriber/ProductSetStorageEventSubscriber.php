<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;
use Spryker\Zed\ProductSet\Dependency\ProductSetEvents;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener\ProductAbstractProductSetStorageListener;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener\ProductSetDataStorageListener;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener\ProductSetProductImageSetImageStorageListener;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener\ProductSetProductImageSetStorageListener;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener\ProductSetProductImageStorageListener;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener\ProductSetStorageListener;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener\ProductSetUrlStorageListener;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * @method \Spryker\Zed\ProductSetStorage\Communication\ProductSetStorageCommunicationFactory getFactory()
 */
class ProductSetStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    const QUEUE_POOL_NAME_SHARED = 'sharedPool';

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
            ->addListenerQueued(ProductSetEvents::PRODUCT_SET_PUBLISH, new ProductSetStorageListener())
            ->addListenerQueued(ProductSetEvents::PRODUCT_SET_UNPUBLISH, new ProductSetStorageListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_CREATE, new ProductSetStorageListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_UPDATE, new ProductSetStorageListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_DELETE, new ProductSetStorageListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_DATA_CREATE, new ProductSetDataStorageListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_DATA_UPDATE, new ProductSetDataStorageListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_DATA_DELETE, new ProductSetDataStorageListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_ABSTRACT_SET_CREATE, new ProductAbstractProductSetStorageListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_ABSTRACT_SET_UPDATE, new ProductAbstractProductSetStorageListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_ABSTRACT_SET_DELETE, new ProductAbstractProductSetStorageListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_UPDATE, new ProductSetUrlStorageListener(), static::QUEUE_POOL_NAME_SHARED)
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_DELETE, new ProductSetUrlStorageListener(), static::QUEUE_POOL_NAME_SHARED)
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE, new ProductSetProductImageStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_DELETE, new ProductSetProductImageStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE, new ProductSetProductImageSetStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_UPDATE, new ProductSetProductImageSetStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_DELETE, new ProductSetProductImageSetStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE, new ProductSetProductImageSetImageStorageListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_DELETE, new ProductSetProductImageSetImageStorageListener());

        return $eventCollection;
    }
}
