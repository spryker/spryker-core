<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;
use Spryker\Zed\ProductSet\Dependency\ProductSetEvents;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductAbstractProductSetPageSearchListener;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductSetDataPageSearchListener;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductSetPageProductImageSearchListener;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductSetPageProductImageSetImageSearchListener;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductSetPageProductImageSetSearchListener;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductSetPageSearchListener;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductSetPageUrlSearchListener;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * @method \Spryker\Zed\ProductSetPageSearch\Communication\ProductSetPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSetPageSearch\Business\ProductSetPageSearchFacadeInterface getFacade()
 */
class ProductSetPageSearchEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductSetPagePublishSearchListener($eventCollection);
        $this->addProductSetPageUnpublishSearchListener($eventCollection);
        $this->addProductSetPageCreateSearchListener($eventCollection);
        $this->addProductSetPageUpdateSearchListener($eventCollection);
        $this->addProductSetPageDeleteSearchListener($eventCollection);
        $this->addProductSetDataPageCreateSearchListener($eventCollection);
        $this->addProductSetDataPageUpdateSearchListener($eventCollection);
        $this->addProductSetDataPageDeleteSearchListener($eventCollection);
        $this->addProductAbstractProductSetPageCreateSearchListener($eventCollection);
        $this->addProductAbstractProductSetPageUpdateSearchListener($eventCollection);
        $this->addProductAbstractProductSetPageDeleteSearchListener($eventCollection);
        $this->addProductSetPageUrlUpdateSearchListener($eventCollection);
        $this->addProductSetPageUrlDeleteSearchListener($eventCollection);

        $this->addProductImageEvents($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductImageEvents(EventCollectionInterface $eventCollection)
    {
        $this->addProductSetPageProductImageUpdateSearchListener($eventCollection);
        $this->addProductSetPageProductImageDeleteSearchListener($eventCollection);
        $this->addProductSetPageProductImageSetCreateSearchListener($eventCollection);
        $this->addProductSetPageProductImageSetUpdateSearchListener($eventCollection);
        $this->addProductSetPageProductImageSetDeleteSearchListener($eventCollection);
        $this->addProductSetPageProductImageSetImageUpdateSearchListener($eventCollection);
        $this->addProductSetPageProductImageSetImageDeleteSearchListener($eventCollection);
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSetPagePublishSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductSetEvents::PRODUCT_SET_PUBLISH, new ProductSetPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSetPageUnpublishSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductSetEvents::PRODUCT_SET_UNPUBLISH, new ProductSetPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSetPageCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_CREATE, new ProductSetPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSetPageUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_UPDATE, new ProductSetPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSetPageDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_DELETE, new ProductSetPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSetDataPageCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_DATA_CREATE, new ProductSetDataPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSetDataPageUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_DATA_UPDATE, new ProductSetDataPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSetDataPageDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_DATA_DELETE, new ProductSetDataPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractProductSetPageCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_ABSTRACT_SET_CREATE, new ProductAbstractProductSetPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractProductSetPageUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_ABSTRACT_SET_UPDATE, new ProductAbstractProductSetPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractProductSetPageDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_ABSTRACT_SET_DELETE, new ProductAbstractProductSetPageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSetPageUrlUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(UrlEvents::ENTITY_SPY_URL_UPDATE, new ProductSetPageUrlSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSetPageUrlDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(UrlEvents::ENTITY_SPY_URL_DELETE, new ProductSetPageUrlSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSetPageProductImageUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE, new ProductSetPageProductImageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSetPageProductImageDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_DELETE, new ProductSetPageProductImageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSetPageProductImageSetCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE, new ProductSetPageProductImageSetSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSetPageProductImageSetUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_UPDATE, new ProductSetPageProductImageSetSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSetPageProductImageSetDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_DELETE, new ProductSetPageProductImageSetSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSetPageProductImageSetImageUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE, new ProductSetPageProductImageSetImageSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductSetPageProductImageSetImageDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_DELETE, new ProductSetPageProductImageSetImageSearchListener());
    }
}
