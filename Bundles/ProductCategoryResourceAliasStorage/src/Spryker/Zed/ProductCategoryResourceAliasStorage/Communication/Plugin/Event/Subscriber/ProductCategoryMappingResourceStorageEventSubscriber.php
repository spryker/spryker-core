<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryResourceAliasStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\ProductCategoryResourceAliasStorage\Communication\Plugin\Event\Listener\ProductAbstractCategoryMappingResourceStorageListener;

/**
 * @method \Spryker\Zed\ProductCategoryResourceAliasStorage\Business\ProductCategoryResourceAliasStorageFacade getFacade()
 * @method \Spryker\Zed\ProductCategoryResourceAliasStorage\Communication\ProductCategoryResourceAliasStorageCommunicationFactory getFactory()
 */
class ProductCategoryMappingResourceStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductAbstractCategoryMappingResourceStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractCategoryMappingResourceStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductCategoryEvents::PRODUCT_CATEGORY_PUBLISH, new ProductAbstractCategoryMappingResourceStorageListener());
        $eventCollection->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_CREATE, new ProductAbstractCategoryMappingResourceStorageListener());
        $eventCollection->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_UPDATE, new ProductAbstractCategoryMappingResourceStorageListener());
    }
}
