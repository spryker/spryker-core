<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductReview\Dependency\ProductReviewEvents;
use Spryker\Zed\ProductReviewSearch\Communication\Plugin\Event\Listener\ProductReviewSearchListener;

/**
 * @method \Spryker\Zed\ProductReviewSearch\Communication\ProductReviewSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductReviewSearch\Business\ProductReviewSearchFacadeInterface getFacade()
 */
class ProductReviewSearchEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addProductReviewPublishSearchListener($eventCollection);
        $this->addProductReviewUnpublishSearchListener($eventCollection);
        $this->addProductReviewCreateSearchListener($eventCollection);
        $this->addProductReviewUpdateSearchListener($eventCollection);
        $this->addProductReviewDeleteSearchListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductReviewPublishSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductReviewEvents::PRODUCT_REVIEW_PUBLISH, new ProductReviewSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductReviewUnpublishSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductReviewEvents::PRODUCT_REVIEW_UNPUBLISH, new ProductReviewSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductReviewCreateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductReviewEvents::ENTITY_SPY_PRODUCT_REVIEW_CREATE, new ProductReviewSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductReviewUpdateSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductReviewEvents::ENTITY_SPY_PRODUCT_REVIEW_UPDATE, new ProductReviewSearchListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductReviewDeleteSearchListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductReviewEvents::ENTITY_SPY_PRODUCT_REVIEW_DELETE, new ProductReviewSearchListener());
    }
}
