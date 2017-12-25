<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductAbstractLocalizedAttributesStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductAbstractStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductAbstractUrlStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteLocalizedAttributesStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteProductAbstractLocalizedAttributesStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteProductAbstractStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteProductAbstractUrlStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteStorageListener;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * @method \Spryker\Zed\ProductStorage\Communication\ProductStorageCommunicationFactory getFactory()
 */
class ProductStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
            ->addListenerQueued(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, new ProductAbstractStorageListener())
            ->addListenerQueued(ProductEvents::PRODUCT_ABSTRACT_UNPUBLISH, new ProductAbstractStorageListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_CREATE, new ProductAbstractStorageListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE, new ProductAbstractStorageListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE, new ProductConcreteProductAbstractStorageListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_DELETE, new ProductAbstractStorageListener())
            ->addListenerQueued(ProductEvents::PRODUCT_CONCRETE_PUBLISH, new ProductConcreteStorageListener())
            ->addListenerQueued(ProductEvents::PRODUCT_CONCRETE_UNPUBLISH, new ProductConcreteStorageListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_CREATE, new ProductConcreteStorageListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_UPDATE, new ProductConcreteStorageListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_DELETE, new ProductConcreteStorageListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_UPDATE, new ProductAbstractLocalizedAttributesStorageListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_UPDATE, new ProductConcreteProductAbstractLocalizedAttributesStorageListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_DELETE, new ProductAbstractLocalizedAttributesStorageListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_UPDATE, new ProductConcreteLocalizedAttributesStorageListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_DELETE, new ProductConcreteLocalizedAttributesStorageListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_UPDATE, new ProductAbstractUrlStorageListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_DELETE, new ProductAbstractUrlStorageListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_UPDATE, new ProductConcreteProductAbstractUrlStorageListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_DELETE, new ProductConcreteProductAbstractUrlStorageListener());

        return $eventCollection;
    }

}
