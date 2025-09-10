<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Subscriber;

use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\PriceProductDefaultProductPagePublishListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageAvailabilityStockSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageCategoryNodeSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageCategorySearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageImageSetProductImageSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageImageSetSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageLocalizedAttributesSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPagePriceSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPagePriceTypeSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductAbstractListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductAbstractPublishListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductAbstractStoreSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductAbstractUnpublishListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductCategorySearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductConcreteLocalizedAttributesSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductConcreteSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductImageSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageUrlSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductSearchListener;
use Spryker\Zed\ProductSearch\Dependency\ProductSearchEvents;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 */
class ProductPageSearchEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @uses \Spryker\Zed\Availability\Dependency\AvailabilityEvents::ENTITY_SPY_AVAILABILITY_UPDATE
     *
     * @var string
     */
    protected const ENTITY_SPY_AVAILABILITY_UPDATE = 'Entity.spy_availability.update';

    /**
     * @uses \Spryker\Zed\Category\Dependency\CategoryEvents::ENTITY_CATEGORY_PUBLISH
     *
     * @var string
     */
    protected const ENTITY_CATEGORY_PUBLISH = 'Entity.spy_category.publish';

    /**
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection)
    {
        $this->addProductPageProductPublishAbstractListener($eventCollection);
        $this->addProductPageProductPublishAbstractSearchListener($eventCollection);
        $this->addProductPageProductUnpublishAbstractListener($eventCollection);
        $this->addProductPageProductAbstractCreateListener($eventCollection);
        $this->addProductPageProductAbstractUpdateListener($eventCollection);
        $this->addProductPageProductAbstractDeleteListener($eventCollection);
        $this->addProductPageLocalizedAttributesCreateSearchListener($eventCollection);
        $this->addProductPageLocalizedAttributesUpdateSearchListener($eventCollection);
        $this->addProductPageLocalizedAttributesDeleteSearchListener($eventCollection);
        $this->addProductPageProductConcreteCreateSearchListener($eventCollection);
        $this->addProductPageProductConcreteUpdateSearchListener($eventCollection);
        $this->addProductPageProductConcreteDeleteSearchListener($eventCollection);
        $this->addProductPageProductConcreteLocalizedAttributesCreateSearchListener($eventCollection);
        $this->addProductPageProductConcreteLocalizedAttributesUpdateSearchListener($eventCollection);
        $this->addProductPageProductConcreteLocalizedAttributesDeleteSearchListener($eventCollection);
        $this->addProductPageProductAbstractFilterPublishListener($eventCollection);
        $this->addProductPageUrlUpdateSearchListener($eventCollection);
        $this->addProductPageUrlDeleteSearchListener($eventCollection);
        $this->addProductPageProductAbstractStoreCreateSearchListener($eventCollection);
        $this->addProductPageProductAbstractStoreUpdateSearchListener($eventCollection);
        $this->addProductPageProductAbstractStoreDeleteSearchListener($eventCollection);

        $this->addPriceProductEvents($eventCollection);
        $this->addProductImageEvents($eventCollection);
        $this->addProductCategoryEvents($eventCollection);
        $this->addProductSearchEvents($eventCollection);

        $this->addProductPageAvailabilityStockUpdateSearchListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPriceProductEvents(EventCollectionInterface $eventCollection)
    {
        $this->addProductPagePriceCreateSearchListener($eventCollection);
        $this->addProductPagePriceUpdateSearchListener($eventCollection);
        $this->addProductPagePriceDeleteSearchListener($eventCollection);
        $this->addProductPagePriceTypeUpdateSearchListener($eventCollection);
        $this->addProductPagePriceTypeDeleteSearchListener($eventCollection);
        $this->addProductPagePriceProductDefaultCreateSearchListener($eventCollection);
        $this->addProductPagePriceProductDefaultUpdateSearchListener($eventCollection);
        $this->addProductPagePriceProductDefaultDeleteSearchListener($eventCollection);
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductImageEvents(EventCollectionInterface $eventCollection)
    {
        $this->addProductPageImageSetCreateSearchListener($eventCollection);
        $this->addProductPageImageSetUpdateSearchListener($eventCollection);
        $this->addProductPageImageSetDeleteSearchListener($eventCollection);
        $this->addProductPageImageSetProductImageCreateSearchListener($eventCollection);
        $this->addProductPageImageSetProductImageUpdateSearchListener($eventCollection);
        $this->addProductPageImageSetProductImageDeleteSearchListener($eventCollection);
        $this->addProductPageProductImageUpdateSearchListener($eventCollection);
        $this->addProductPageProductImageDeleteSearchListener($eventCollection);
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductCategoryEvents(EventCollectionInterface $eventCollection)
    {
        $this->addProductPageProductCategoryCreateSearchListener($eventCollection);
        $this->addProductPageProductCategoryUpdateSearchListener($eventCollection);
        $this->addProductPageProductCategoryDeleteSearchListener($eventCollection);
        $this->addProductPageCategoryCreateSearchListener($eventCollection);
        $this->addProductPageCategoryUpdateSearchListener($eventCollection);
        $this->addProductPageCategoryDeleteSearchListener($eventCollection);
        $this->addProductPageCategoryPublishSearchListener($eventCollection);
        $this->addProductPageCategoryNodeCreateSearchListener($eventCollection);
        $this->addProductPageCategoryNodeUpdateSearchListener($eventCollection);
        $this->addProductPageCategoryNodeDeleteSearchListener($eventCollection);
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductCategoryCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_CREATE, new ProductPageProductCategorySearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductCategoryUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_UPDATE, new ProductPageProductCategorySearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductCategoryDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_DELETE, new ProductPageProductCategorySearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageCategoryCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_CREATE, new ProductPageCategorySearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageCategoryPublishSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(static::ENTITY_CATEGORY_PUBLISH, new ProductPageCategorySearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageCategoryUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_UPDATE, new ProductPageCategorySearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageCategoryDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_DELETE, new ProductPageCategorySearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageCategoryNodeCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_CREATE, new ProductPageCategoryNodeSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageCategoryNodeUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_UPDATE, new ProductPageCategoryNodeSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageCategoryNodeDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(CategoryEvents::ENTITY_SPY_CATEGORY_NODE_DELETE, new ProductPageCategoryNodeSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageImageSetCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE, new ProductPageImageSetSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageImageSetUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_UPDATE, new ProductPageImageSetSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageImageSetDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_DELETE, new ProductPageImageSetSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageImageSetProductImageCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductPageSearchConfig::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_CREATE, new ProductPageImageSetProductImageSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageImageSetProductImageUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE, new ProductPageImageSetProductImageSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageImageSetProductImageDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_DELETE, new ProductPageImageSetProductImageSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductImageUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE, new ProductPageProductImageSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductImageDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_DELETE, new ProductPageProductImageSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPagePriceCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE, new ProductPagePriceSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPagePriceUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_UPDATE, new ProductPagePriceSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPagePriceDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_DELETE, new ProductPagePriceSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPagePriceTypeUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_TYPE_UPDATE, new ProductPagePriceTypeSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPagePriceTypeDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_TYPE_DELETE, new ProductPagePriceTypeSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPagePriceProductDefaultCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_DEFAULT_CREATE, new PriceProductDefaultProductPagePublishListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPagePriceProductDefaultUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_DEFAULT_UPDATE, new PriceProductDefaultProductPagePublishListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPagePriceProductDefaultDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_DEFAULT_DELETE, new PriceProductDefaultProductPagePublishListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductPublishAbstractListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, new ProductPageProductAbstractPublishListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductPublishAbstractSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::PRODUCT_ABSTRACT_SEARCH_PUBLISH, new ProductPageProductAbstractPublishListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductUnpublishAbstractListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::PRODUCT_ABSTRACT_UNPUBLISH, new ProductPageProductAbstractUnpublishListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductAbstractCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_CREATE, new ProductPageProductAbstractPublishListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductAbstractUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE, new ProductPageProductAbstractPublishListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductAbstractDeleteListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_DELETE, new ProductPageProductAbstractUnpublishListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageLocalizedAttributesCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_CREATE, new ProductPageLocalizedAttributesSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageLocalizedAttributesUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_UPDATE, new ProductPageLocalizedAttributesSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageLocalizedAttributesDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_DELETE, new ProductPageLocalizedAttributesSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductConcreteCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_CREATE, new ProductPageProductConcreteSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductConcreteUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_UPDATE, new ProductPageProductConcreteSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductConcreteDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_DELETE, new ProductPageProductConcreteSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductConcreteLocalizedAttributesCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_CREATE, new ProductPageProductConcreteLocalizedAttributesSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductConcreteLocalizedAttributesUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_UPDATE, new ProductPageProductConcreteLocalizedAttributesSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductConcreteLocalizedAttributesDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_DELETE, new ProductPageProductConcreteLocalizedAttributesSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductAbstractFilterPublishListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductSearchEvents::SYNCHRONIZATION_FILTER_PUBLISH, new ProductPageProductAbstractListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageUrlUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(UrlEvents::ENTITY_SPY_URL_UPDATE, new ProductPageUrlSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageUrlDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(UrlEvents::ENTITY_SPY_URL_DELETE, new ProductPageUrlSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductAbstractStoreCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_STORE_CREATE, new ProductPageProductAbstractStoreSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductAbstractStoreUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_STORE_UPDATE, new ProductPageProductAbstractStoreSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageProductAbstractStoreDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_STORE_DELETE, new ProductPageProductAbstractStoreSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSearchEvents(EventCollectionInterface $eventCollection): void
    {
        $eventCollection
            ->addListenerQueued(ProductSearchEvents::ENTITY_SPY_PRODUCT_SEARCH_CREATE, new ProductSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName())
            ->addListenerQueued(ProductSearchEvents::ENTITY_SPY_PRODUCT_SEARCH_UPDATE, new ProductSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName())
            ->addListenerQueued(ProductSearchEvents::ENTITY_SPY_PRODUCT_SEARCH_DELETE, new ProductSearchListener(), 0, null, $this->getConfig()->getProductPageEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductPageAvailabilityStockUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(static::ENTITY_SPY_AVAILABILITY_UPDATE, new ProductPageAvailabilityStockSearchListener());
    }
}
