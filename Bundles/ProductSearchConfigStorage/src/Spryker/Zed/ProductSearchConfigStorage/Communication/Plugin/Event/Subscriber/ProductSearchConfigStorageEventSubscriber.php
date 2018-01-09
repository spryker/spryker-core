<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearchConfigStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductSearch\Dependency\ProductSearchEvents;
use Spryker\Zed\ProductSearchConfigStorage\Communication\Plugin\Event\Listener\ProductSearchConfigStorageListener;

/**
 * @method \Spryker\Zed\ProductSearchConfigStorage\Communication\ProductSearchConfigStorageCommunicationFactory getFactory()
 */
class ProductSearchConfigStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
            ->addListenerQueued(ProductSearchEvents::PRODUCT_SEARCH_CONFIG_PUBLISH, new ProductSearchConfigStorageListener())
            ->addListenerQueued(ProductSearchEvents::PRODUCT_SEARCH_CONFIG_UNPUBLISH, new ProductSearchConfigStorageListener())
            ->addListenerQueued(ProductSearchEvents::ENTITY_SPY_PRODUCT_SEARCH_ATTRIBUTE_CREATE, new ProductSearchConfigStorageListener())
            ->addListenerQueued(ProductSearchEvents::ENTITY_SPY_PRODUCT_SEARCH_ATTRIBUTE_UPDATE, new ProductSearchConfigStorageListener())
            ->addListenerQueued(ProductSearchEvents::ENTITY_SPY_PRODUCT_SEARCH_ATTRIBUTE_DELETE, new ProductSearchConfigStorageListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ATTRIBUTE_KEY_CREATE, new ProductSearchConfigStorageListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ATTRIBUTE_KEY_UPDATE, new ProductSearchConfigStorageListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ATTRIBUTE_KEY_DELETE, new ProductSearchConfigStorageListener());

        return $eventCollection;
    }
}
