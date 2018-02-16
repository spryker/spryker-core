<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\CategoryAttributeStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\CategoryNodeStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\CategoryStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\CategoryUrlStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\ProductCategoryPublishStorageListener;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener\ProductCategoryStorageListener;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * @method \Spryker\Zed\ProductCategoryStorage\Communication\ProductCategoryStorageCommunicationFactory getFactory()
 */
class ProductCategoryStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
            ->addListenerQueued(ProductCategoryEvents::PRODUCT_CATEGORY_PUBLISH, new ProductCategoryPublishStorageListener())
            ->addListenerQueued(ProductCategoryEvents::PRODUCT_CATEGORY_UNPUBLISH, new ProductCategoryPublishStorageListener())
            ->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_CREATE, new ProductCategoryStorageListener())
            ->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_UPDATE, new ProductCategoryStorageListener())
            ->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_DELETE, new ProductCategoryStorageListener())
            ->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_CREATE, new CategoryStorageListener())
            ->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_UPDATE, new CategoryStorageListener())
            ->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_DELETE, new CategoryStorageListener())
            ->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_CREATE, new CategoryAttributeStorageListener())
            ->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_UPDATE, new CategoryAttributeStorageListener())
            ->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_DELETE, new CategoryAttributeStorageListener())
            ->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_CREATE, new CategoryNodeStorageListener())
            ->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_UPDATE, new CategoryNodeStorageListener())
            ->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_DELETE, new CategoryNodeStorageListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_CREATE, new CategoryUrlStorageListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_UPDATE, new CategoryUrlStorageListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_DELETE, new CategoryUrlStorageListener());

        return $eventCollection;
    }
}
