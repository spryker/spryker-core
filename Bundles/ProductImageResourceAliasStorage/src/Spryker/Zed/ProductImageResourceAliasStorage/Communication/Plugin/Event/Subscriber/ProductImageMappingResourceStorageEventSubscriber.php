<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageResourceAliasStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;
use Spryker\Zed\ProductImageResourceAliasStorage\Communication\Plugin\Event\Listener\ProductAbstractImageMappingResourceStorageListener;
use Spryker\Zed\ProductImageResourceAliasStorage\Communication\Plugin\Event\Listener\ProductConcreteImageMappingResourceStorageListener;

/**
 * @method \Spryker\Zed\ProductImageResourceAliasStorage\Business\ProductImageResourceAliasStorageFacade getFacade()
 * @method \Spryker\Zed\ProductImageResourceAliasStorage\Communication\ProductImageResourceAliasStorageCommunicationFactory getFactory()
 */
class ProductImageMappingResourceStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductAbstractImageMappingResourceStorageListener($eventCollection);
        $this->addProductConcreteImageMappingResourceStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractImageMappingResourceStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_PUBLISH, new ProductAbstractImageMappingResourceStorageListener());
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE, new ProductAbstractImageMappingResourceStorageListener());
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_UPDATE, new ProductAbstractImageMappingResourceStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteImageMappingResourceStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductImageEvents::PRODUCT_IMAGE_PRODUCT_CONCRETE_PUBLISH, new ProductConcreteImageMappingResourceStorageListener());
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE, new ProductConcreteImageMappingResourceStorageListener());
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_UPDATE, new ProductConcreteImageMappingResourceStorageListener());
    }
}
