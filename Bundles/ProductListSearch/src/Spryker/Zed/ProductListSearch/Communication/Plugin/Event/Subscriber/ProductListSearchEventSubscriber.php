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
use Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Listener\ProductListCategoryPublishSearchListener;
use Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Listener\ProductListCategorySearchListener;
use Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Listener\ProductListProductCategoryPublishSearchListener;
use Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Listener\ProductListProductCategorySearchListener;
use Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Listener\ProductListProductConcretePublishSearchListener;
use Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Listener\ProductListProductConcreteSearchListener;
use Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Listener\ProductPublishSearchListener;
use Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Listener\ProductSearchListener;

/**
 * @method \Spryker\Zed\ProductListSearch\Business\ProductListSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductListSearch\Communication\ProductListSearchCommunicationFactory getFactory()
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
        $this->addProductListProductConcretePublishSearchListener($eventCollection);
        $this->addProductListProductConcreteCreateSearchListener($eventCollection);
        $this->addProductListProductConcreteUpdateSearchListener($eventCollection);
        $this->addProductListProductConcreteDeleteSearchListener($eventCollection);

        $this->addProductListProductCategoryPublishSearchListener($eventCollection);
        $this->addProductListProductCategoryCreateSearchListener($eventCollection);
        $this->addProductListProductCategoryUpdateSearchListener($eventCollection);
        $this->addProductListProductCategoryDeleteSearchListener($eventCollection);

        $this->addProductListCategoryPublishSearchListener($eventCollection);
        $this->addProductListCategoryCreateSearchListener($eventCollection);
        $this->addProductListCategoryUpdateSearchListener($eventCollection);
        $this->addProductListCategoryDeleteSearchListener($eventCollection);

        $this->addProductPublishSearchListener($eventCollection);
        $this->addProductCreateSearchListener($eventCollection);
        $this->addProductUpdateSearchListener($eventCollection);
        $this->addProductDeleteSearchListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPublishSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::PRODUCT_CONCRETE_PUBLISH, new ProductPublishSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductCreateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_CREATE, new ProductSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductUpdateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_UPDATE, new ProductSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductDeleteSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_DELETE, new ProductSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListCategoryPublishSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductCategoryEvents::PRODUCT_CATEGORY_PUBLISH, new ProductListCategoryPublishSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListCategoryCreateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_CREATE, new ProductListCategorySearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListCategoryUpdateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_UPDATE, new ProductListCategorySearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListCategoryDeleteSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_DELETE, new ProductListCategorySearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductCategoryPublishSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::PRODUCT_LIST_CATEGORY_PUBLISH, new ProductListProductCategoryPublishSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductCategoryCreateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_CATEGORY_CREATE, new ProductListProductCategorySearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductCategoryUpdateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_CATEGORY_UPDATE, new ProductListProductCategorySearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductCategoryDeleteSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_CATEGORY_DELETE, new ProductListProductCategorySearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductConcretePublishSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::PRODUCT_LIST_PRODUCT_CONCRETE_PUBLISH, new ProductListProductConcretePublishSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductConcreteCreateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_CREATE, new ProductListProductConcreteSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductConcreteUpdateSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_UPDATE, new ProductListProductConcreteSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductListProductConcreteDeleteSearchListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(ProductListEvents::ENTITY_SPY_PRODUCT_LIST_PRODUCT_CONCRETE_DELETE, new ProductListProductConcreteSearchListener());
    }
}
