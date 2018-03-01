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
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductAbstractStoreStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductAbstractUrlStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteLocalizedAttributesStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteProductAbstractLocalizedAttributesStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteProductAbstractRelationStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteProductAbstractStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteProductAbstractUrlStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteStorageListener;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * @method \Spryker\Zed\ProductStorage\Communication\ProductStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface getFacade()
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
        $this->addProductAbstractPublishStorageListener($eventCollection);
        $this->addProductAbstractUnpublishStorageListener($eventCollection);
        $this->addProductAbstractCreateStorageListener($eventCollection);
        $this->addProductAbstractUpdateStorageListener($eventCollection);
        $this->addProductAbstractDeleteStorageListener($eventCollection);
        $this->addProductConcreteProductAbstractStorageListener($eventCollection);
        $this->addProductConcretePublishStorageListener($eventCollection);
        $this->addProductConcreteUnpublishStorageListener($eventCollection);
        $this->addProductConcreteCreateStorageListener($eventCollection);
        $this->addProductConcreteUpdateStorageListener($eventCollection);
        $this->addProductConcreteDeleteStorageListener($eventCollection);
        $this->addProductConcreteProductAbstractRelationCreateStorageListener($eventCollection);
        $this->addProductConcreteProductAbstractRelationUpdateStorageListener($eventCollection);
        $this->addProductConcreteProductAbstractDeleteCreateStorageListener($eventCollection);
        $this->addProductAbstractLocalizedAttributesUpdateStorageListener($eventCollection);
        $this->addProductAbstractLocalizedAttributesDeleteStorageListener($eventCollection);
        $this->addProductConcreteProductAbstractLocalizedAttributesUpdateStorageListener($eventCollection);
        $this->addProductConcreteLocalizedAttributesUpdateStorageListener($eventCollection);
        $this->addProductConcreteLocalizedAttributesDeleteStorageListener($eventCollection);
        $this->addProductAbstractUrlCreateStorageListener($eventCollection);
        $this->addProductAbstractUrlUpdateStorageListener($eventCollection);
        $this->addProductAbstractUrlDeleteStorageListener($eventCollection);
        $this->addProductConcreteProductAbstractUrlCreateStorageListener($eventCollection);
        $this->addProductConcreteProductAbstractUrlUpdateStorageListener($eventCollection);
        $this->addProductConcreteProductAbstractUrlDeleteStorageListener($eventCollection);
        $this->addProductAbstractStoreCreateStorageListener($eventCollection);
        $this->addProductAbstractStoreUpdateStorageListener($eventCollection);
        $this->addProductAbstractStoreDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractPublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, new ProductAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::PRODUCT_ABSTRACT_UNPUBLISH, new ProductAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_CREATE, new ProductAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE, new ProductAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_DELETE, new ProductAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteProductAbstractStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE, new ProductConcreteProductAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcretePublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::PRODUCT_CONCRETE_PUBLISH, new ProductConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::PRODUCT_CONCRETE_UNPUBLISH, new ProductConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_CREATE, new ProductConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_UPDATE, new ProductConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_DELETE, new ProductConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteProductAbstractRelationCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_CREATE, new ProductConcreteProductAbstractRelationStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteProductAbstractRelationUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_UPDATE, new ProductConcreteProductAbstractRelationStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteProductAbstractDeleteCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_DELETE, new ProductConcreteProductAbstractRelationStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractLocalizedAttributesUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_UPDATE, new ProductAbstractLocalizedAttributesStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractLocalizedAttributesDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_DELETE, new ProductAbstractLocalizedAttributesStorageListener());
        ;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteProductAbstractLocalizedAttributesUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_UPDATE, new ProductConcreteProductAbstractLocalizedAttributesStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteLocalizedAttributesUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_UPDATE, new ProductConcreteLocalizedAttributesStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteLocalizedAttributesDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_DELETE, new ProductConcreteLocalizedAttributesStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractUrlCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(UrlEvents::ENTITY_SPY_URL_CREATE, new ProductAbstractUrlStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractUrlUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(UrlEvents::ENTITY_SPY_URL_UPDATE, new ProductAbstractUrlStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractUrlDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(UrlEvents::ENTITY_SPY_URL_DELETE, new ProductAbstractUrlStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteProductAbstractUrlCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(UrlEvents::ENTITY_SPY_URL_CREATE, new ProductConcreteProductAbstractUrlStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteProductAbstractUrlUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(UrlEvents::ENTITY_SPY_URL_UPDATE, new ProductConcreteProductAbstractUrlStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteProductAbstractUrlDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(UrlEvents::ENTITY_SPY_URL_DELETE, new ProductConcreteProductAbstractUrlStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractStoreCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_STORE_CREATE, new ProductAbstractStoreStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractStoreUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_STORE_UPDATE, new ProductAbstractStoreStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractStoreDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_STORE_DELETE, new ProductAbstractStoreStorageListener());
    }
}
