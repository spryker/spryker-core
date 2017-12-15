<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Price\Dependency\PriceEvents;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageCategoryNodeSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageCategorySearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageImageSetProductImageSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageImageSetSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageLocalizedAttributesSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPagePriceSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPagePriceTypeSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductAbstractListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductCategorySearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductConcreteLocalizedAttributesSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductConcreteSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductImageSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageUrlSearchListener;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacade getFacade()
 */
class ProductPageSearchEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_CREATE, new ProductPageProductAbstractListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE, new ProductPageProductAbstractListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_DELETE, new ProductPageProductAbstractListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_CREATE, new ProductPageLocalizedAttributesSearchListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_UPDATE, new ProductPageLocalizedAttributesSearchListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_DELETE, new ProductPageLocalizedAttributesSearchListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_CREATE, new ProductPageProductConcreteSearchListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_UPDATE, new ProductPageProductConcreteSearchListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_DELETE, new ProductPageProductConcreteSearchListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_CREATE, new ProductPageProductConcreteLocalizedAttributesSearchListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_UPDATE, new ProductPageProductConcreteLocalizedAttributesSearchListener())
            ->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_DELETE, new ProductPageProductConcreteLocalizedAttributesSearchListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_UPDATE, new ProductPageUrlSearchListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_DELETE, new ProductPageUrlSearchListener());

        $this->addPriceEvents($eventCollection);
        $this->addProductImageEvents($eventCollection);
        $this->addProductCategoryEvents($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceEvents(EventCollectionInterface $eventCollection)
    {
        $eventCollection
            ->addListenerQueued(PriceEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE, new ProductPagePriceSearchListener())
            ->addListenerQueued(PriceEvents::ENTITY_SPY_PRICE_PRODUCT_UPDATE, new ProductPagePriceSearchListener())
            ->addListenerQueued(PriceEvents::ENTITY_SPY_PRICE_PRODUCT_DELETE, new ProductPagePriceSearchListener())
            ->addListenerQueued(PriceEvents::ENTITY_SPY_PRICE_TYPE_UPDATE, new ProductPagePriceTypeSearchListener())
            ->addListenerQueued(PriceEvents::ENTITY_SPY_PRICE_TYPE_DELETE, new ProductPagePriceTypeSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductImageEvents(EventCollectionInterface $eventCollection)
    {
        $eventCollection
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE, new ProductPageImageSetSearchListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_UPDATE, new ProductPageImageSetSearchListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_DELETE, new ProductPageImageSetSearchListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE, new ProductPageImageSetProductImageSearchListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_DELETE, new ProductPageImageSetProductImageSearchListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE, new ProductPageProductImageSearchListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_DELETE, new ProductPageProductImageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductCategoryEvents(EventCollectionInterface $eventCollection)
    {
        $eventCollection
            ->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_CREATE, new ProductPageProductCategorySearchListener())
            ->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_UPDATE, new ProductPageProductCategorySearchListener())
            ->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_DELETE, new ProductPageProductCategorySearchListener())
            ->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_CREATE, new ProductPageCategorySearchListener())
            ->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_UPDATE, new ProductPageCategorySearchListener())
            ->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_DELETE, new ProductPageCategorySearchListener())
            ->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_CREATE, new ProductPageCategoryNodeSearchListener())
            ->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_UPDATE, new ProductPageCategoryNodeSearchListener())
            ->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_DELETE, new ProductPageCategoryNodeSearchListener());
    }

}
