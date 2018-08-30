<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\ProductList\Dependency\ProductListEvents;
use Spryker\Zed\ProductListStorage\Communication\Plugin\Event\Listener\ProductAbstractStorageListener;
use Spryker\Zed\ProductListStorage\Communication\Plugin\Event\Listener\ProductCategoryAbstractStorageListener;
use Spryker\Zed\ProductListStorage\Communication\Plugin\Event\Listener\ProductCategoryConcreteStorageListener;
use Spryker\Zed\ProductListStorage\Communication\Plugin\Event\Listener\ProductConcreteStorageListener;
use Spryker\Zed\ProductListStorage\Communication\Plugin\Event\Listener\ProductListProductAbstractStorageListener;
use Spryker\Zed\ProductListStorage\Communication\Plugin\Event\Listener\ProductListProductCategoryAbstractStorageListener;
use Spryker\Zed\ProductListStorage\Communication\Plugin\Event\Listener\ProductListProductCategoryConcreteStorageListener;
use Spryker\Zed\ProductListStorage\Communication\Plugin\Event\Listener\ProductListProductConcreteStorageListener;
use Spryker\Zed\ProductListStorage\Communication\Plugin\Event\Listener\ProductListStorageListener;

/**
 * @method \Spryker\Zed\ProductListStorage\Communication\ProductListStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductListStorage\Business\ProductListStorageFacadeInterface getFacade()
 */
class ProductListStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductAbstractStorageListener($eventCollection);
        $this->addProductListProductCategoryAbstractStorageListener($eventCollection);
        $this->addProductCategoryAbstractStorageListener($eventCollection);
        $this->addProductListProductAbstractStorageListener($eventCollection);

        $this->addProductListProductConcreteStorageListener($eventCollection);
        $this->addProductListProductCategoryConcreteStorageListener($eventCollection);
        $this->addProductCategoryConcreteStorageListener($eventCollection);
        $this->addProductConcreteStorageListener($eventCollection);

        $this->addProductListListenerUpdate($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductConcreteStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::PRODUCT_CONCRETE_PUBLISH, new ProductConcreteStorageListener());
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_CREATE, new ProductConcreteStorageListener());
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_UPDATE, new ProductConcreteStorageListener());
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_DELETE, new ProductConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductCategoryConcreteStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductCategoryEvents::PRODUCT_CATEGORY_PUBLISH, new ProductCategoryConcreteStorageListener());
        $eventCollection->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_CREATE, new ProductCategoryConcreteStorageListener());
        $eventCollection->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_UPDATE, new ProductCategoryConcreteStorageListener());
        $eventCollection->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_DELETE, new ProductCategoryConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductCategoryConcreteStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::PRODUCT_LIST_CATEGORY_PUBLISH, new ProductListProductCategoryConcreteStorageListener());
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_CATEGORY_CREATE, new ProductListProductCategoryConcreteStorageListener());
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_CATEGORY_UPDATE, new ProductListProductCategoryConcreteStorageListener());
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_CATEGORY_DELETE, new ProductListProductCategoryConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductConcreteStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::PRODUCT_LIST_PRODUCT_CONCRETE_PUBLISH, new ProductListProductConcreteStorageListener());
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_CREATE, new ProductListProductConcreteStorageListener());
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_UPDATE, new ProductListProductConcreteStorageListener());
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_DELETE, new ProductListProductConcreteStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductAbstractStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::PRODUCT_LIST_PRODUCT_CONCRETE_PUBLISH, new ProductListProductAbstractStorageListener());
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_CREATE, new ProductListProductAbstractStorageListener());
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_UPDATE, new ProductListProductAbstractStorageListener());
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_DELETE, new ProductListProductAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::PRODUCT_CONCRETE_PUBLISH, new ProductAbstractStorageListener());
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_CREATE, new ProductAbstractStorageListener());
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_UPDATE, new ProductAbstractStorageListener());
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_DELETE, new ProductAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductCategoryAbstractStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::PRODUCT_LIST_CATEGORY_PUBLISH, new ProductListProductCategoryAbstractStorageListener());
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_CATEGORY_CREATE, new ProductListProductCategoryAbstractStorageListener());
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_CATEGORY_UPDATE, new ProductListProductCategoryAbstractStorageListener());
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_CATEGORY_DELETE, new ProductListProductCategoryAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductCategoryAbstractStorageListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductCategoryEvents::PRODUCT_CATEGORY_PUBLISH, new ProductCategoryAbstractStorageListener());
        $eventCollection->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_CREATE, new ProductCategoryAbstractStorageListener());
        $eventCollection->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_UPDATE, new ProductCategoryAbstractStorageListener());
        $eventCollection->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_DELETE, new ProductCategoryAbstractStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListListenerUpdate(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_UPDATE, new ProductListStorageListener());
    }
}
