<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductResourceAliasStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductResourceAliasStorage\Communication\Plugin\Event\Listener\ProductAbstractMappingResourceStorageListener;
use Spryker\Zed\ProductResourceAliasStorage\Communication\Plugin\Event\Listener\ProductConcreteMappingResourceStorageListener;

/**
 * @method \Spryker\Zed\ProductResourceAliasStorage\Business\ProductResourceAliasStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductResourceAliasStorage\Communication\ProductResourceAliasStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductResourceAliasStorage\ProductResourceAliasStorageConfig getConfig()
 */
class ProductResourceAliasStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductAbstractMappingResourceStorageListener($eventCollection);
        $this->addProductConcreteMappingResourceStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractMappingResourceStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, new ProductAbstractMappingResourceStorageListener());
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_CREATE, new ProductAbstractMappingResourceStorageListener());
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE, new ProductAbstractMappingResourceStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteMappingResourceStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::PRODUCT_CONCRETE_PUBLISH, new ProductConcreteMappingResourceStorageListener());
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_CREATE, new ProductConcreteMappingResourceStorageListener());
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_UPDATE, new ProductConcreteMappingResourceStorageListener());
    }
}
