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
        $eventCollection
            ->addListenerQueued(ProductSetEvents::PRODUCT_SET_PUBLISH, new ProductSetPageSearchListener())
            ->addListenerQueued(ProductSetEvents::PRODUCT_SET_UNPUBLISH, new ProductSetPageSearchListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_CREATE, new ProductSetPageSearchListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_UPDATE, new ProductSetPageSearchListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_DELETE, new ProductSetPageSearchListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_DATA_CREATE, new ProductSetDataPageSearchListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_DATA_UPDATE, new ProductSetDataPageSearchListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_SET_DATA_DELETE, new ProductSetDataPageSearchListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_ABSTRACT_SET_CREATE, new ProductAbstractProductSetPageSearchListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_ABSTRACT_SET_UPDATE, new ProductAbstractProductSetPageSearchListener())
            ->addListenerQueued(ProductSetEvents::ENTITY_SPY_PRODUCT_ABSTRACT_SET_DELETE, new ProductAbstractProductSetPageSearchListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_UPDATE, new ProductSetPageUrlSearchListener())
            ->addListenerQueued(UrlEvents::ENTITY_SPY_URL_DELETE, new ProductSetPageUrlSearchListener());

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
        $eventCollection
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE, new ProductSetPageProductImageSearchListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_DELETE, new ProductSetPageProductImageSearchListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE, new ProductSetPageProductImageSetSearchListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_UPDATE, new ProductSetPageProductImageSetSearchListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_DELETE, new ProductSetPageProductImageSetSearchListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE, new ProductSetPageProductImageSetImageSearchListener())
            ->addListenerQueued(ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_DELETE, new ProductSetPageProductImageSetImageSearchListener());
    }

}
