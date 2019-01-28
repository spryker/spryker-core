<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\ProductList\Dependency\ProductListEvents;
use Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Listener\ProductConcretePageSearchPublishListener as ProductListCategoryProductConcretePageSearchPublishListener;
use Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Listener\ProductListCategorySearchListener as CategoryProductAbstractPageSearchPublishListener;
use Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Listener\ProductListProductCategorySearchListener as ProductListCategoryProductAbstractPageSearchPublishListener;
use Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Listener\ProductListProductConcretePageSearchPublishListener;
use Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Listener\ProductListProductConcretePublishSearchListener;
use Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Listener\ProductListProductConcreteSearchListener as ProductListProductConcreteProductAbstractPageSearchPublishListener;
use Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Listener\ProductListSearchListener as ProductListProductAbstractPageSearchPublishListener;
use Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Listener\ProductSearchListener as ProductConcreteProductAbstractPageSearchPublishListener;

/**
 * @method \Spryker\Zed\ProductListSearch\Business\ProductListSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductListSearch\Communication\ProductListSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductListSearch\ProductListSearchConfig getConfig()
 */
class ProductListSearchEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductListProductConcreteCreateSearchListener($eventCollection);
        $this->addProductListProductConcreteUpdateSearchListener($eventCollection);
        $this->addProductListProductConcreteDeleteSearchListener($eventCollection);

        $this->addProductListProductCategoryCreateSearchListener($eventCollection);
        $this->addProductListProductCategoryUpdateSearchListener($eventCollection);
        $this->addProductListProductCategoryDeleteSearchListener($eventCollection);

        $this->addProductListCategoryCreateSearchListener($eventCollection);
        $this->addProductListCategoryUpdateSearchListener($eventCollection);
        $this->addProductListCategoryDeleteSearchListener($eventCollection);

        $this->addProductCreateSearchListener($eventCollection);
        $this->addProductUpdateSearchListener($eventCollection);
        $this->addProductDeleteSearchListener($eventCollection);

        $this->addProductListUpdateSearchListener($eventCollection);

        $this->addProductListUpdateProductConcretePageSearchPublishListener($eventCollection);

        $this->addProductListCategoryCreateProductConcretePageSearchPublishListener($eventCollection);
        $this->addProductListCategoryUpdateProductConcretePageSearchPublishListener($eventCollection);
        $this->addProductListCategoryDeleteProductConcretePageSearchPublishListener($eventCollection);

        $this->addProductListProductConcreteCreateProductConcretePageSearchPublishListener($eventCollection);
        $this->addProductListProductConcreteUpdateProductConcretePageSearchPublishListener($eventCollection);
        $this->addProductListProductConcreteDeleteProductConcretePageSearchPublishListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductCreateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_CREATE, new ProductConcreteProductAbstractPageSearchPublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductUpdateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_UPDATE, new ProductConcreteProductAbstractPageSearchPublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductDeleteSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_DELETE, new ProductConcreteProductAbstractPageSearchPublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListCategoryCreateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_CREATE, new CategoryProductAbstractPageSearchPublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListCategoryUpdateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_UPDATE, new CategoryProductAbstractPageSearchPublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListCategoryDeleteSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_DELETE, new CategoryProductAbstractPageSearchPublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductCategoryCreateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_CATEGORY_CREATE, new ProductListCategoryProductAbstractPageSearchPublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductCategoryUpdateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_CATEGORY_UPDATE, new ProductListCategoryProductAbstractPageSearchPublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductCategoryDeleteSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_CATEGORY_DELETE, new ProductListCategoryProductAbstractPageSearchPublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductConcreteCreateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_CREATE, new ProductListProductConcreteProductAbstractPageSearchPublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductConcreteUpdateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_UPDATE, new ProductListProductConcreteProductAbstractPageSearchPublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductConcreteDeleteSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_DELETE, new ProductListProductConcreteProductAbstractPageSearchPublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListUpdateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_UPDATE, new ProductListProductAbstractPageSearchPublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListUpdateProductConcretePageSearchPublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_UPDATE, new ProductListProductConcretePageSearchPublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductConcreteCreateProductConcretePageSearchPublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_CREATE, new ProductListProductConcretePublishSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductConcreteUpdateProductConcretePageSearchPublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_UPDATE, new ProductListProductConcretePublishSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductConcreteDeleteProductConcretePageSearchPublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_DELETE, new ProductListProductConcretePublishSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListCategoryCreateProductConcretePageSearchPublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_CATEGORY_CREATE, new ProductListCategoryProductConcretePageSearchPublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListCategoryUpdateProductConcretePageSearchPublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_CATEGORY_UPDATE, new ProductListCategoryProductConcretePageSearchPublishListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListCategoryDeleteProductConcretePageSearchPublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_CATEGORY_DELETE, new ProductListCategoryProductConcretePageSearchPublishListener());
    }
}
